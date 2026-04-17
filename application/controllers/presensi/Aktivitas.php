<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aktivitas extends KZ_Controller {

    private $module = 'presensi/aktivitas';
    private $module_do = 'presensi/aktivitas_do'; 
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_presensi']);
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
        if(empty($this->pid) && $this->sessionlevel != '1'){
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
            } else if($routing_module['source'] == 'rekap') {
                $this->_tableRekap();
            }
        }else if($routing_module['type'] == 'list') {
            //TABLE
            if($routing_module['source'] == 'pegawai') {
                $this->_listPegawai();
            }
        }
    }
    function _tableIndex() 
    {
        $where = [];
        $pegawai = decode($this->input->post('pegawai'));
        $jenis = $this->input->post('jenis');
        $status = $this->input->post('status');
        $awal = $this->input->post('awal');
        $akhir = $this->input->post('akhir');
        
        if ($pegawai != '') {
            $where['pegawai_id'] = $pegawai;
        }
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['pegawai_id'] = $this->pid;
        }
        if ($jenis != '') {
            $where['jenis_pegawai'] = $jenis;
        }
        if ($status != '') {
            $where['status_presensi'] = $status;
        }
        if ($awal != '') {
            $where['DATE(tgl_presensi) >='] = $awal;
        }
        if ($akhir != '') {
            $where['DATE(tgl_presensi) <='] = $akhir;
        }
        
        $datatables = $this->m_presensi->getDatatables($where, ['module' => $this->module, 'level' => $this->sessionlevel]);
        jsonResponse($datatables);
    }
    function _tableRekap()
    {
        $this->load->model(['m_pegawai']);
        
        $pegawai = decode($this->input->post('pegawai'));
        $jenis = $this->input->post('jenis');
        $awal = $this->input->post('awal');
        $akhir = $this->input->post('akhir');
        
        if(empty($awal) || empty($akhir)){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $where['tgl_presensi >='] = $awal;
        $where['tgl_presensi <='] = $akhir;
        if ($jenis != '') {
            $where['jenis_pegawai'] = $jenis;
        }
        if ($pegawai != '') {
            $where['pegawai_id'] = $pegawai;
        }
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['pegawai_id'] = $this->pid;
        }
        $options = [
            'alias'      => 'p',
            'select'     => 'p.id_pegawai, p.nama, p.nik, pr.tgl_presensi, pr.status_presensi',
            'join'       => [ 
                ['m_presensi pr','pr.pegawai_id = p.id_pegawai','left'],
            ],
            'order'      => 'nama ASC'
        ];
        $result = $this->m_pegawai->all($where, $options); 
        if($result['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        // ================= RANGE TANGGAL =================
        $start = new DateTime($awal);
        $end   = new DateTime($akhir);
        $periode = [];
        while ($start <= $end) {
            $periode[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }
        $pivot = [];
        // ================= PIVOT =================
        foreach ($result['data'] as $row) {            
            // init pegawai
            if (!isset($pivot[$row['id_pegawai']])) {
                $arr_item = [
                    'nama' => '<strong>'.ctk($row['nama']).
                    '</strong><br><span class="grey">'.ctk($row['nik']).'</span>'
                ];
                // init semua tanggal null
                foreach ($periode as $tgl) {
                    $arr_item[$tgl] = null;
                }
                $pivot[$row['id_pegawai']] = $arr_item;
            }
            if (!empty($row['tgl_presensi'])) {
                $tgl = date('Y-m-d', strtotime($row['tgl_presensi']));
                $pivot[$row['id_pegawai']][$tgl] = $row['status_presensi'];
            }
        }
        // ================= JADI TABLE =================
        $data = ['table' => []];
        $no = 1;
        foreach ($pivot as $item) {

            $rows = [];
            $rows[] = $no;
            $rows[] = $item['nama'];
            
            $jml_tepat = 0;
            $jml_lambat = 0;
            $jml_kosong = 0;

            foreach ($periode as $tgl) {

                $val = $item[$tgl] ?? null;

                if ($val === 'TEPAT WAKTU') {
                    $html = '<i class="fa fa-calendar-check-o green bigger-150"></i>';
                    $jml_tepat++;
                } else if ($val === 'TERLAMBAT') {
                    $html = '<i class="fa fa-calendar-check-o orange bigger-120"></i>';
                    $jml_lambat++;
                } else {
                    $html = '<i class="fa fa-calendar-times-o grey"></i>';
                    $jml_kosong++;
                }

                $rows[] = $html;
            }
            $total = count($periode);
            $persen = $total ? round((($jml_tepat+$jml_lambat) / $total) * 100) : 0;
            
            $rows[] = '(<b>'.$persen.'%</b>)<br><small>'.$jml_tepat.' TEPAT - '.$jml_lambat.' TERLAMBAT</small>';

            $data['table'][] = $rows;
            $no++;
        }
        $data['periode'] = $periode;
        
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _listPegawai()
    {
        $this->load->model(array('m_pegawai'));
        
        $where = null;
        $key = $this->input->post('key');
        $id = $this->input->get('id');
        
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['id_pegawai'] = $this->pid;
        }
        if(!empty($id)){
            $where['id_pegawai'] = decode($id);
            $result = $this->m_pegawai->all($where);
        }else{
            $result = $this->m_pegawai->all($where, ['order' => 'nama ASC', 'like' => ['nama'], 'key' => $key]);
        }
        $data = array();
        foreach ($result['data'] as $val) {
            $text = $val['nama'];
            $data[] = array("id" => encode($val['id_pegawai']), "text" => $text);
        }
        jsonResponse($data);
    }
}
