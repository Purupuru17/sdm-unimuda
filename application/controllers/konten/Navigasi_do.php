<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Navigasi_do extends KZ_Controller {
    
    private $module = 'konten/navigasi';
    private $module_do = 'konten/navigasi_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_nav'));
    }
    function add() {
        if($this->fungsi->Validation($this->rules) == FALSE){
            redirect($this->module);
        }
        $data['judul_nav'] = $this->input->post('judul');
        $data['url_nav'] = $this->input->post('url');
        $data['link_nav'] = $this->input->post('link');
        $data['status_nav'] = $this->input->post('status');
        $data['order_nav'] = $this->input->post('order');
        $data['icon_nav'] = $this->input->post('icon');

        $data['update_nav'] = date('Y-m-d H:i:s');
        $data['log_nav'] = $this->sessionname;
        
        if($this->input->post('jenis') == '0'){
            $data['parent_nav'] = '0';
        }else{
            $data['parent_nav'] = $this->input->post('pilihan');
        }
        
        $result = $this->m_nav->insert($data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module.'/add');
        }
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if($this->fungsi->Validation($this->rules) == FALSE){
            redirect($this->module.'/edit/'.$id);
        }
        
        $data['judul_nav'] = $this->input->post('judul');
        $data['url_nav'] = $this->input->post('url');
        $data['link_nav'] = $this->input->post('link');
        $data['status_nav'] = $this->input->post('status');
        $data['order_nav'] = $this->input->post('order');
        $data['icon_nav'] = $this->input->post('icon');

        $data['update_nav'] = date('Y-m-d H:i:s');
        $data['log_nav'] = $this->sessionname;
        
        if($this->input->post('jenis') == '0'){
            $data['parent_nav'] = '0';
        }else{
            $data['parent_nav'] = $this->input->post('pilihan');
        }
        
        $result = $this->m_nav->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    private $rules = array(
        array(
            'field' => 'judul',
            'label' => 'Judul Navigasi',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'url',
            'label' => 'URL Navigasi',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'jenis',
            'label' => 'Jenis Navigasi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'order',
            'label' => 'Order Navigasi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Navigasi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'link',
            'label' => 'Tipe URL',
            'rules' => 'required|trim|xss_clean'
        )
    );    
}
