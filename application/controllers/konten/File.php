<?php defined('BASEPATH') OR exit('No direct script access allowed');

class File extends KZ_Controller {
    
    private $module = 'konten/file';
    private $module_do = 'konten/file_do';    
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_file'));
    }
    function index() {
        $this->data['file'] = $this->m_file->all();
        
        $this->data['title'] = array('File','Daftar File');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->data['module'] = $this->module;
        $this->load_view('konten/file/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_file->getEmpty();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('File','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/file/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_file->get(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('File','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/file/v_form', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $data = $this->m_file->get(decode($id));
        $old_file = $data['url_file'];
        
        $result = $this->m_file->delete(decode($id));
        if ($result) {
            (is_file($old_file)) ? unlink($old_file) : '';
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
}
