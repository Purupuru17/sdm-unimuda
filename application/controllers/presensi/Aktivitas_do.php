<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aktivitas_do extends KZ_Controller {

    private $module = 'presensi/aktivitas';
    private $module_do = 'presensi/aktivitas_do'; 
    private $url_route = array('id', 'source', 'type');
    private $path = 'upload/presensi/';
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_presensi']);
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
            }
        }
    }
    function _checkLocation()
    {
        $this->load->model(['m_lokasi']);
        
        $userLat = $this->input->post('latitude');
        $userLng = $this->input->post('longitude');
        
        if(empty($userLat) || empty($userLng)){
            jsonResponse(['status' => false, 'msg' => 'GPS tidak ditemukan. Silahkan coba ulang.']);
        }
        $lokasi = $this->m_lokasi->all(['jenis_lokasi' => '1', 'status_lokasi' => '1']);
        
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
        $this->load->model(['m_pegawai','m_kerja','m_libur']);
        
        $status = $this->input->post('status');
        $lokasi = $this->input->post('lokasi');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $foto = $this->input->post('foto');
        
        // CEK AKUN
        $pegawai = $this->m_pegawai->get($this->pid);
        if(empty($pegawai)){
            jsonResponse(['status' => false, 'msg' => 'Pegawai tidak ditemukan']);
        }
        // CEK LIBUR
        $libur = $this->m_libur->get(['tgl_libur' => date('Y-m-d')]);
        if(!empty($libur)){
            jsonResponse(['status' => false, 'msg' => 'Hari Libur, '.$libur['catat_libur']]);
        }
        // CEK KERJA
        $kerja = $this->m_kerja->get(['hari_kerja' => date('N')]);
        if(empty($kerja)){
            jsonResponse(['status' => false, 'msg' => 'Diluar Hari Kerja']);
        }
        $masuk = $kerja['masuk_kerja'];
        $pulang = $kerja['pulang_kerja'];
        $limit = (int) $kerja['limit_kerja'];
        
        $now = new DateTime();
        $today = date('Y-m-d');

        $jamMasuk  = new DateTime("$today $masuk");
        $jamPulang = new DateTime("$today $pulang");
        $jamLimit = clone $jamMasuk;
        $jamLimit->modify("+{$limit} minutes");
        
        switch ($status) {
            case '1':
                // CEK AWAL
                if ($now < $jamMasuk) {
                    jsonResponse(['status' => false, 'msg' => 'Belum waktunya presensi masuk. Jam Masuk : '.format_date($masuk,4)]);
                }
                // CEK OVER
                if ($now > $jamPulang) {
                    jsonResponse(['status' => false, 'msg' => 'Presensi masuk sudah berakhir, telah melewati Waktu Kerja']);
                }
                // CEK TGL
                $cekTgl = $this->m_presensi->get(['pegawai_id' => $this->pid, 'tgl_presensi' => $today]);
                if(!empty($cekTgl)){
                    jsonResponse(['status' => false, 'msg' => 'Sudah presensi masuk sebelumnya']);
                }
                // STATUS LIMIT
                $is_status ='TEPAT WAKTU';
                $is_note = '';
                if ($now > $jamLimit) {
                    $diff = $jamLimit->diff($now);
                    $menitTelat = ($diff->h * 60) + $diff->i;
                    
                    if ($menitTelat > 0) {
                        $is_status = 'TERLAMBAT';
                        $is_note = $menitTelat.' Menit';
                    }
                }
                //SAVE
                $data['pegawai_id'] = $this->pid;
                $data['status_presensi'] = $is_status;
                $data['tgl_presensi'] = $today;
                $data['waktu_masuk'] = date('H:i:s');
                $data['lokasi_masuk'] = $lokasi;
                $data['latitude_masuk'] = $latitude;
                $data['longitude_masuk'] = $longitude;
                $data['catat_presensi'] = $is_note;
                $data['created_at'] = date('Y-m-d H:i:s');
                //UPLOAD
                $filename = url_title(date('Y m d H i s').' '.random_string('alnum', 4),'dash',TRUE); 
                $upload = $this->fungsi->imgUpBase64($foto, $filename, $this->path);
                if(empty($upload['data'])){
                    jsonResponse(['status' => false, 'msg' => $upload['msg']]);
                }
                $data['foto_masuk'] = $upload['data'];
                
                $query = $this->m_presensi->insert($data);
                if($query){
                    jsonResponse(['status' => true, 'msg' => 'Presensi masuk berhasil disimpan. Status : '.$is_status.' '.$is_note]);
                }else{
                    jsonResponse(['status' => false, 'msg' => 'Presensi gagal disimpan']);
                }
                break;
            
            case '2':
                //CEK PULANG
                if ($now < $jamPulang) {
                    jsonResponse(['status' => false, 
                        'msg' => 'Belum waktunya presensi pulang. Jam Pulang : '.format_date($pulang,4)]);
                }
                //CEK MASUK
                $cekMasuk = $this->m_presensi->get(['pegawai_id' => $this->pid, 'tgl_presensi' => $today, 
                    'waktu_masuk <>' => NULL, 'status_presensi !=' => ''
                ]);
                if(empty($cekMasuk)){
                    jsonResponse(['status' => false, 'msg' => 'Belum ada presensi masuk untuk hari ini']);
                }
                if(!empty($cekMasuk['waktu_pulang'])){
                    jsonResponse(['status' => false, 'msg' => 'Sudah presensi pulang sebelumnya']);
                }
                //SAVE
                $data['waktu_pulang'] = date('H:i:s');
                $data['lokasi_pulang'] = $lokasi;
                $data['latitude_pulang'] = $latitude;
                $data['longitude_pulang'] = $longitude;
                $data['update_at'] = $this->sessionname.' presensi pulang '.date('d-m-Y H:i:s');
                //UPLOAD
                $filename = url_title(date('Y m d H i s').' '.random_string('alnum', 4),'dash',TRUE);           
                $upload = $this->fungsi->imgUpBase64($foto, $filename, $this->path);
                if(empty($upload['data'])){
                    jsonResponse(['status' => false, 'msg' => $upload['msg']]);
                }
                $data['foto_pulang'] = $upload['data'];
                        
                $query = $this->m_presensi->update($cekMasuk['id_presensi'], $data);
                if($query){
                    jsonResponse(['status' => true, 'msg' => 'Terimakasih. Presensi pulang berhasil disimpan']);
                }else{
                    jsonResponse(['status' => false, 'msg' => 'Presensi gagal disimpan']);
                }
                break;

            default:
                jsonResponse(['status' => false, 'msg' => 'Not Found']);
                break;
        }
    }
    private $rules = [
        [
            'field' => 'status',
            'label' => 'Status',
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
