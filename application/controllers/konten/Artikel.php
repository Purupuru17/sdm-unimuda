<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel extends KZ_Controller {
    
    private $module = 'konten/artikel';
    private $module_do = 'konten/artikel_do';   
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_artikel','m_jenis'));
    }
    function index() {
        $this->data['module'] = $this->module;
        
        $this->data['title'] = array('Artikel','Daftar Artikel');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('konten/artikel/v_index', $this->data);
    }
    function add() {
        $this->data['jenis'] = $this->m_jenis->getAll();
        $this->data['edit'] = $this->m_artikel->getEmpty();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Artikel','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/artikel/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['jenis'] = $this->m_jenis->getAll();
        $this->data['edit'] = $this->m_artikel->get(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Artikel','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/artikel/v_form', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $get = $this->m_artikel->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $result = $this->m_artikel->delete($id);
        if ($result) {
            (is_file($get['foto_artikel'])) ? unlink($get['foto_artikel']) : false;
            
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
            //LIST
            if($routing_module['source'] == 'index') {
                $this->_table_index();
            }
        }
    }
    //FUNCTION
    function _table_index() {
        $where = [];
        $datatables = $this->m_artikel->getDatatables($where, ['module' => $this->module]);
        jsonResponse($datatables);
    }
}
