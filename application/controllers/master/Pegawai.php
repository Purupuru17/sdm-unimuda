<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends KZ_Controller {
    
    private $module = 'master/pegawai';
    private $module_do = 'master/pegawai_do';    
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_pegawai'));
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
    function add() {
        $this->data['edit'] = $this->m_pegawai->getEmpty();
        
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
        $this->load->model(array('m_presensi'));
        
        $get = $this->m_pegawai->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $getpre = $this->m_presensi->get(['pegawai_id' => $id]);
        if(!empty($getpre)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak dapat dihapus'));
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
        }
    }
    function _tableIndex() 
    {
        $where = [];
        $datatables = $this->m_pegawai->getDatatables($where, ['module' => $this->module]);
        jsonResponse($datatables);
    }
}
