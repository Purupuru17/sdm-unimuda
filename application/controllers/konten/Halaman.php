<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Halaman extends KZ_Controller {
    
    private $module = 'konten/halaman';
    private $module_do = 'konten/halaman_do';    
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_page'));
    }
    function index() {
        $this->data['pages'] = $this->m_page->getAll();
        
        $this->data['title'] = array('Halaman','Daftar Halaman');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->data['module'] = $this->module;
        $this->load_view('konten/page/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_page->getEmpty();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Halaman','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/page/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_page->getId(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Halaman','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/page/v_form', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $data = $this->m_page->getId(decode($id));
        $old_img = $data['foto_page'];
        
        $result = $this->m_page->delete(decode($id));
        if ($result) {
            (is_file($old_img)) ? unlink($old_img) : '';
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
}
