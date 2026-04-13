<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan extends KZ_Controller {

    private $module = 'presensi/kegiatan';
    private $module_do = 'presensi/kegiatan_do'; 
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_kegiatan']);
        $this->_pegawaiId();
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Kegiatan','List Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('presensi/kegiatan/v_index', $this->data);
    }
    function add() {
        $this->load->model(['m_agenda']);
        
        if(empty($this->pid) && $this->sessionlevel != '1'){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Pegawai tidak ditemukan'));
            redirect($this->module);
        }
        $this->data['agenda'] = $this->m_agenda->all([
            'status_agenda' => '1', 'is_open' => '1', 'DATE(waktu_agenda)' => date('Y-m-d')
        ]);
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Kegiatan','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('presensi/kegiatan/v_add', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $get = $this->m_kegiatan->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $result = $this->m_kegiatan->delete($id);
        if ($result) {
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
            $where['status'] = $status;
        }
        if ($awal != '') {
            $where['DATE(waktu) >='] = $awal;
        }
        if ($akhir != '') {
            $where['DATE(waktu) <='] = $akhir;
        }
        
        $datatables = $this->m_kegiatan->getDatatables($where, ['module' => $this->module, 'level' => $this->sessionlevel]);
        jsonResponse($datatables);
    }
    function _listPegawai(){
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
