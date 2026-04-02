<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis extends KZ_Controller {
    
    private $module = 'konten/jenis';
    private $module_do = 'konten/jenis_do';    
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_jenis'));
    }
    function index() {
        $this->data['jenis'] = $this->m_jenis->getAll();
        
        $this->data['title'] = array('Jenis','Jenis Artikel');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->data['module'] = $this->module;
        $this->load_view('konten/jenis/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_jenis->getEmpty();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Jenis','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/jenis/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_jenis->getId(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Jenis','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/jenis/v_form', $this->data);
    }
}
