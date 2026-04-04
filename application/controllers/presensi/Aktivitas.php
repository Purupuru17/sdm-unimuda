<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aktivitas extends KZ_Controller {

    private $module = 'presensi/aktivitas';
    private $module_do = 'presensi/aktivitas_do'; 
    private $url_route = array('id', 'source', 'type');
    private $path = 'upload/presensi/';
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_presensi'));
        $this->_pegawaiId();
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Aktivitas','List Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('presensi/aktivitas/v_index', $this->data);
    }
    function add() {
        if(empty($this->pid)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Pegawai tidak ditemukan'));
            redirect($this->module);
        }
        //CEK LIBUR
        $this->load->model(['m_libur']);
        $libur = $this->m_libur->get(['tgl_libur' => date('Y-m-d')]);
        if(!empty($libur)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Hari Libur, '.$libur['catat_libur']));
            redirect($this->module);
        }
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Aktivitas','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('presensi/aktivitas/v_add', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_presensi->get(decode($id));
        jsonResponse($this->data);
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Aktivitas','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('presensi/aktivitas/v_edit', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['detail'] = $this->m_presensi->get(decode($id));
        jsonResponse($this->data);
        $this->data['title'] = array('Aktivitas','Detail Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('presensi/aktivitas/v_detail', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $get = $this->m_presensi->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $result = $this->m_presensi->delete($id);
        if ($result) {
            // DELETE FOTO
            (is_file($get['foto_masuk'])) ? unlink($get['foto_masuk']) : false;
            (is_file($get['foto_pulang'])) ? unlink($get['foto_pulang']) : false;
            
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //TABLE
            if($routing_module['source'] == 'index') {
                $this->_tableIndex();
            }
        }else if ($routing_module['type'] == 'action') {
            //AKSI
            if ($routing_module['source'] == 'location') {
                $this->_checkLocation();
            }else if ($routing_module['source'] == 'presensi') {
                $this->_savePresensi();
            }
        }
    }
    function _tableIndex() 
    {
        $where = [];
        $pegawai = decode($this->input->post('pegawai'));
        $tanggal = $this->input->post('tanggal');
        $status = $this->input->post('status');
        
        if ($pegawai != '') {
            $where['pegawai_id'] = $pegawai;
        }
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['pegawai_id'] = $this->pid;
        }
        if ($tanggal != '') {
            $where['DATE(tgl_presensi)'] = $tanggal;
        }
        if ($status != '') {
            $where['status_presensi'] = $status;
        }
        $datatables = $this->m_presensi->getDatatables($where, ['module' => $this->module, 'level' => $this->sessionlevel]);
        jsonResponse($datatables);
    }
    function _checkLocation()
    {
        $this->load->model(['m_lokasi']);
        
        $userLat = $this->input->post('latitude');
        $userLng = $this->input->post('longitude');
        
        if(empty($userLat) || empty($userLng)){
            jsonResponse(['status' => false, 'msg' => 'GPS belum akurat. Silakan coba ulang.']);
        }
        $lokasi = $this->m_lokasi->all(['status_lokasi' => '1']);
        
        $result = $this->_detectGedung($userLat, $userLng, $lokasi['data']);
        if(!empty($result)) {
            jsonResponse(['data' => $result['nama_lokasi'].' ['.round($result['distance']).' meter]', 'status' => true, 
                'msg' => 'Lokasi ditemukan. <br>Jarak : <b>'.round($result['distance']).' meter</b> dari <b>'.$result['nama_lokasi'].'</b>']);
        } else {
            jsonResponse(['status' => false, 'msg' => 'GPS belum akurat. Lokasi berada di luar jangkauan area']);
        }
    }
    function _savePresensi()
    {
        if(!$this->fungsi->Validation($this->rules, 'ajax')){
            jsonResponse(['status' => false, 'msg' => validation_errors()]);
        }
        if(empty($this->pid)){
            jsonResponse(['status' => false, 'msg' => 'Pegawai tidak ditemukan']);
        }
        $this->load->model(['m_kerja', 'm_libur']);
        
        $status = $this->input->post('status');
        $lokasi = $this->input->post('lokasi');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $foto = $this->input->post('foto');
        
        //CEK LIBUR
        $libur = $this->m_libur->get(['tgl_libur' => date('Y-m-d')]);
        if(!empty($libur)){
            jsonResponse(['status' => false, 'msg' => 'Hari Libur, '.$libur['catat_libur']]);
        }
        //CEK KERJA
        $kerja = $this->m_kerja->get(['hari_kerja' => date('N')]);
        if(empty($kerja)){
            jsonResponse(['status' => false, 'msg' => 'Diluar Hari Kerja']);
        }
        $masuk = $kerja['masuk_kerja'];
        $pulang = $kerja['pulang_kerja'];
        $limit = $kerja['limit_kerja'];
        
        $now = new DateTime();
        $today = date('Y-m-d');

        $jamMasuk  = new DateTime("$today $masuk");
        $jamPulang = new DateTime("$today $pulang");
        $jamLimit = clone $jamMasuk;
        $jamLimit->modify("+{$limit} minutes");
        
        switch ($status) {
            case '1':
                //CEK AWAL
                if ($now < $jamMasuk) {
                    jsonResponse(['status' => false, 'msg' => 'Belum waktunya presensi masuk. Jam Masuk : '.format_date($masuk,4)]);
                }
                //CEK OVER
                if ($now > $jamPulang) {
                    jsonResponse(['status' => false, 'msg' => 'Presensi masuk sudah berakhir, telah melewati Waktu Kerja']);
                }
                //CEK TGL
                $cekTgl = $this->m_presensi->get(['pegawai_id' => $this->pid, 'tgl_presensi' => $today]);
                if(!empty($cekTgl)){
                    jsonResponse(['status' => false, 'msg' => 'Sudah presensi masuk sebelumnya']);
                }
                //STATUS LIMIT
                $is_status ='TEPAT WAKTU';
                $is_note = '';
                if ($now > $jamLimit) {
                    $diff = $jamLimit->diff($now);
                    $menitTelat = ($diff->h * 60) + $diff->i;
                    
                    $is_status = 'TERLAMBAT';
                    $is_note = $menitTelat.' Menit';
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
                $data['foto_masuk'] = $this->_uploadBase64($foto, $filename, $this->path);
                
                $query = $this->m_presensi->insert($data);
                if($query){
                    jsonResponse(['status' => true, 'msg' => 'Presensi masuk berhasil disimpan. Status : '.$is_note]);
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
                $data['foto_pulang'] = $this->_uploadBase64($foto, $filename, $this->path);
                
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
        jsonResponse(['status' => true, 'msg' => json_encode($this->pid)]);
    }
    function _detectGedung($userLat, $userLng, $lokasi)
    {
        $candidates = [];

        foreach ($lokasi as $l) {
            $distance = $this->_haversineDistance(
                $userLat,
                $userLng,
                $l['latitude'],
                $l['longitude']
            );
            // cek apakah masuk radius gedung tsb
            if ($distance <= $l['radius']) {
                $l['distance'] = $distance;
                $candidates[] = $l;
            }
        }
        // kalau tidak ada yg masuk radius
        if (empty($candidates)) {
            return null;
        }
        // kalau lebih dari 1 (overlap), ambil yg paling dekat
        usort($candidates, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
        return $candidates[0];
    }
    function _haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // meter
    }
    public function _uploadBase64($base64, $name, $path)
    {
        if (!$base64) {
            jsonResponse(['status' => false, 'msg' => 'Foto masih kosong']);
        }
        // validasi format
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            jsonResponse(['status' => false, 'msg' => 'Format tidak valid']);
        }
        $imageType = strtolower($type[1]); // jpeg/png/webp
        if (!in_array($imageType, ['jpg','jpeg','png','webp'])) {
            jsonResponse(['status' => false, 'msg' => 'Tipe tidak diizinkan']);
        }
        // ambil data base64 nya
        $base64sub = substr($base64, strpos($base64, ',') + 1);
        $base64str = str_replace(' ', '+', $base64sub);
        $imageData = base64_decode($base64str);
        if ($imageData === false) {
            jsonResponse(['status' => false, 'msg' => 'Foto tidak ditemukan']);
        }
        // generate nama file
        $fileName = $name . '.' . $imageType;
        $new_path = FCPATH . $path . $fileName;
        // simpan file
        if (!file_put_contents($new_path, $imageData)) {
            jsonResponse(['status' => false, 'msg' => 'Foto gagal disimpan']);
        }
        return $path.$fileName;
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
            'field' => 'foto',
            'label' => 'Foto',
            'rules' => 'required'
        ],[
            'field' => 'latitude',
            'label' => 'Latitude',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'longitude',
            'label' => 'Longitude',
            'rules' => 'required|trim|xss_clean'
        ]
    ];
}
