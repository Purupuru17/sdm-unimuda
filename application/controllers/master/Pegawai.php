<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends KZ_Controller {
    
    private $module = 'master/pegawai';
    private $module_do = 'master/pegawai_do';    
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_pegawai']);
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Pegawai','List Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('master/pegawai/v_index', $this->data);
    }
    function add($id = NULL) {
        if(!empty(decode($id))){
            $this->session->set_userdata(array('pid' => decode($id)));
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Terhubung akun : '. decode($id)));
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_pegawai->getEmpty();
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Pegawai','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/pegawai/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_pegawai->get(decode($id));
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Pegawai','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/pegawai/v_form', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $this->load->model(['m_presensi']);
        
        $get = $this->m_pegawai->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        // AKUN
        if(!empty($get['user_id'])){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak dapat dihapus. Terhubung data akun'));
            redirect($this->module);
        }
        // PRESENSI
        $getpre = $this->m_presensi->get(['pegawai_id' => $id]);
        if(!empty($getpre)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak dapat dihapus. Terhubung data presensi'));
            redirect($this->module);
        }
        $result = $this->m_pegawai->delete($id);
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
        } else if($routing_module['type'] == 'list') {
            //LIST
            if($routing_module['source'] == 'unit') {
                $this->_listUnit();
            } else if($routing_module['source'] == 'jabatan') {
                $this->_listJabatan();
            }
        }
    }
    function _tableIndex() 
    {
        $where = [];
        $datatables = $this->m_pegawai->getDatatables($where, ['module' => $this->module]);
        jsonResponse($datatables);
    }
    function _listUnit()
    {
        $this->load->model(['m_unit']);
        
        $key = $this->input->post('key');
        $id = decode($this->input->get('id'));
        
        if(!empty($id)){
            $result = $this->m_unit->all(['id_unit' => $id]);
        }else{
            $result = $this->m_unit->all(null, ['like' => ['nama_unit'], 'key' => $key]);
        }
        $data = array();
        foreach ($result['data'] as $val) {
            $text = $val['nama_unit'].' ['.$val['kode_unit'].']';
            $data[] = array("id" => encode($val['id_unit']), "text" => $text);
        }
        jsonResponse($data);
    }
    function _listJabatan()
    {
        $this->load->model(['m_jabatan']);
        
        $key = $this->input->post('key');
        $id = $this->input->get('id');
        $options = [
            'alias'     => 'j',
            'select'    => 'j.*, u.nama_unit',
            'join'      => [ ['m_unit u','u.id_unit = j.unit_id','left'] ],
            'like'      => ['nama_jabatan','nama_unit'],
            'key'       => $key    
        ];
        
        if(!empty($id)){
            $result = $this->m_jabatan->all(['id_jabatan' => decode($id)], $options);
        }else{
            $result = $this->m_jabatan->all(null, $options);
        }
        $data = array();
        foreach ($result['data'] as $val) {
            $text = $val['nama_jabatan'].' ['.$val['nama_unit'].']';
            $data[] = array("id" => encode($val['id_jabatan']), "text" => $text);
        }
        jsonResponse($data);
    }
}
