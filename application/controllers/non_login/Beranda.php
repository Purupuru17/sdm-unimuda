<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends KZ_Controller {
    
    private $module = 'non_login/beranda';
    private $url_route = array('id', 'source', 'type'); 
    
    function index() {
        empty($this->sessionid) ? redirect('login') : null;
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Beranda','');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Beranda', 'url'=>'#')
        );
        $this->load_view('non_login/v_dashboard', $this->data);
    }
    function err_404() {
        $this->data['breadcrumb'] = array( 
            array('title'=>'Halaman Tidak Ditemukan', 'url'=>'#')
        );
        $this->load_view('errors/html/error_404', $this->data);
    }
    function err_module() {
        $this->data['breadcrumb'] = array( 
            array('title'=>'Gagal Akses Module', 'url'=>'#')
        );
        $this->load_view('errors/html/error_module', $this->data);
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            if ($routing_module['source'] == '') {
                
            }
        }
    }
}
