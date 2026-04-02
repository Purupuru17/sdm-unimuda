<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Navigasi extends KZ_Controller {
    
    private $module = 'konten/navigasi';
    private $module_do = 'konten/navigasi_do';    
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_nav','m_page'));
    }
    function index() {
        $data = array();
        foreach ($this->m_nav->getAll()['data'] as $val) {
            $row = array();
            $row['a'] = $val;
            $row['b'] = $this->m_nav->getId($val['parent_nav']);
            
            $data[] = $row;
        }
        $this->data['nav'] = $data;
        
        $this->data['title'] = array('Navigasi','Daftar Menu');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->data['module'] = $this->module;
        $this->load_view('konten/nav/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_nav->getEmpty();
        $this->data['parent'] = $this->m_nav->getParent();
        $this->data['pages'] = $this->m_page->getURL();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Navigasi','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/nav/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_nav->getId(decode($id));
        $this->data['parent'] = $this->m_nav->getParent();
        $this->data['pages'] = $this->m_page->getURL();
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Navigasi','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/nav/v_form', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->m_nav->delete(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
}
