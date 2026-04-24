<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan_do extends KZ_Controller {

    private $module = 'presensi/kegiatan';
    private $module_do = 'presensi/kegiatan_do'; 
    private $url_route = array('id', 'source', 'type');
    private $path = 'upload/kegiatan/';
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_kegiatan']);
        $this->load->library(['location']);
        
        $this->_pegawaiId();
    }
    function ajax()
    {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'action') {
            //AKSI
            if ($routing_module['source'] == 'location') {
                $this->_checkLocation();
            }else if ($routing_module['source'] == 'presensi') {
                $this->_savePresensi();
            }else if ($routing_module['source'] == 'validate') {
                $this->_validPresensi();
            }
        }
    }
    function _validPresensi()
    {
        if($this->sessionlevel != '1'){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak memilik akses'));
        }
        $id = array_filter(explode(",", $this->input->post('id')));
        if(empty($id) || !is_array($id)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada data yang dipilih'));
        }
        $success = array();
        $error = array();
        foreach ($id as $newId) {
            if(!empty(decode($newId))){
                
                $update = $this->m_kegiatan->update(decode($newId), ['validasi' => '1']);
                if($update){
                    $success[] = $newId;
                }else{
                    $error[] = $newId;
                }
            }
        }
        if(count($success) < 1){
            jsonResponse(array('status' => FALSE, 'msg' => count($error).' Data gagal diubah'));
        }
        jsonResponse(array('status' => TRUE, 'msg' => count($success).' Data berhasil diubah | '
            .count($error).' Data gagal diubah'));
    }
    function _checkLocation()
    {
        $this->load->model(['m_agenda','m_lokasi']);
        
        $id = decode($this->input->post('id'));
        $userLat = $this->input->post('latitude');
        $userLng = $this->input->post('longitude');
        
        if(empty($userLat) || empty($userLng)){
            jsonResponse(['status' => false, 'msg' => 'GPS tidak ditemukan. Silahkan coba ulang.']);
        }
        $agenda = $this->m_agenda->get($id);
        if(empty($agenda)){
            jsonResponse(['status' => false, 'msg' => 'Agenda kegiatan tidak ditemukan.']);
        }
        $lokasi = $this->m_lokasi->all(['id_lokasi' => $agenda['lokasi_id']]);
        if($lokasi['rows'] < 1){
            jsonResponse(['status' => false, 'msg' => 'Lokasi agenda kegiatan tidak ditemukan.']);
        }
        $result = $this->location->detectRadius($userLat, $userLng, $lokasi['data']);
        if(!empty($result)) {
            jsonResponse(['data' => $result['nama_lokasi'].' ['.round($result['distance']).' meter]', 'status' => true, 
                'msg' => 'Lokasi ditemukan. <br>Jarak : <b>'.round($result['distance']).' meter</b> dari <b>'.$result['nama_lokasi'].'</b>']);
        } else {
            jsonResponse(['status' => false, 'msg' => 'GPS tidak sesuai. Lokasi berada di luar jangkauan area.']);
        }
    }
    function _savePresensi()
    {
        if(!$this->fungsi->Validation($this->rules, 'ajax')){
            jsonResponse(['status' => false, 'msg' => validation_errors()]);
        }
        $this->load->model(['m_pegawai','m_agenda']);
        
        $id = decode($this->input->post('agenda'));
        $lokasi = $this->input->post('lokasi');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $foto = $this->input->post('foto');
        
        // CEK AKUN
        $pegawai = $this->m_pegawai->get($this->pid);
        if(empty($pegawai)){
            jsonResponse(['status' => false, 'msg' => 'Pegawai tidak ditemukan']);
        }
        // CEK AGENDA
        $agenda = $this->m_agenda->get($id);
        if(empty($agenda)){
            jsonResponse(['status' => false, 'msg' => 'Agenda Kegiatan tidak ditemukan']);
        }
        $masuk = $agenda['waktu_agenda'];
        $limit = (int) $agenda['limit_agenda'];
        
        $now = new DateTime();
        $jamMasuk  = new DateTime($masuk);
        
        $jamLimit = clone $jamMasuk;
        $jamLimit->modify("+{$limit} minutes");
        
        $jamAwal = clone $jamMasuk;
        $jamAwal->modify("-1 hour");
        
        $jamAkhir = clone $jamMasuk;
        $jamAkhir->modify("+6 hour");
        // CEK AWAL
        if ($now < $jamAwal) {
            jsonResponse(['status' => false, 'msg' => 'Presensi belum diizinkan. Agenda mulai pada : '.format_date($masuk,0)]);
        }
        // CEK AKHIR
        if ($now > $jamAkhir) {
            jsonResponse(['status' => false, 'msg' => 'Presensi tidak diizinkan. Agenda telah selesai']);
        }
        // CEK HADIR
        $cekHadir = $this->m_kegiatan->get(['pegawai_id' => $this->pid, 'agenda_id' => $id]);
        if(!empty($cekHadir)){
            jsonResponse(['status' => false, 'msg' => 'Sudah presensi sebelumnya']);
        }
        // STATUS LIMIT
        $is_status ='TEPAT WAKTU';
        if ($now > $jamLimit) {
            $diff = $jamLimit->diff($now);
            $menitTelat = ($diff->h * 60) + $diff->i;

            if ($menitTelat > 0) {
                $is_status = 'TERLAMBAT';
            }
        }
        //SAVE
        $data['pegawai_id'] = $this->pid;
        $data['agenda_id'] = $id;
        $data['status'] = $is_status;
        $data['validasi'] = '0';
        $data['waktu'] = date('Y-m-d H:i:s');
        $data['lokasi'] = $lokasi;
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        //UPLOAD
        $filename = url_title(date('Y m d H i s').' '.random_string('alnum', 4),'dash',TRUE); 
        $upload = $this->fungsi->imgUpBase64($foto, $filename, $this->path);
        if(empty($upload['data'])){
            jsonResponse(['status' => false, 'msg' => $upload['msg']]);
        }
        $data['foto'] = $upload['data'];
        
        $query = $this->m_kegiatan->insert($data);
        if($query){
            jsonResponse(['status' => true, 'msg' => 'Presensi masuk berhasil disimpan. Status : '.$is_status]);
        }else{
            jsonResponse(['status' => false, 'msg' => 'Presensi gagal disimpan']);
        }
    }
    private $rules = [
        [
            'field' => 'agenda',
            'label' => 'Agenda Kegiatan',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'lokasi',
            'label' => 'Lokasi',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'latitude',
            'label' => 'Latitude',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'longitude',
            'label' => 'Longitude',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'foto',
            'label' => 'Foto',
            'rules' => 'required'
        ]
    ];
}
