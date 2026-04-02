<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Halaman_do extends KZ_Controller {
    
    private $module = 'konten/halaman';
    private $module_do = 'konten/halaman_do';   
    private $path = 'upload/page/';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_page'));
    }
    function add() {
        if($this->fungsi->Validation($this->rules) == FALSE){
            redirect($this->module);
        }
        $data['judul_page'] = $this->input->post('judul');
        $data['slug_page'] = url_title($this->input->post('judul'), 'dash', TRUE);
        $data['status_page'] = $this->input->post('status');
        $data['isi_page'] = $this->input->post('isi');

        $data['update_page'] = date('Y-m-d H:i:s');
        $data['log_page'] = $this->sessionname;
        
        if(!empty($_FILES['foto']['name'])){
            $upload = $this->fungsi->ImgUpload('foto', $data['slug_page'], $this->path, 900, TRUE);
            if(is_null($upload)){
                redirect($this->module.'/add');
            }
            $data['foto_page'] = $upload; 
        }
        
        $result = $this->m_page->insert($data);
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
        
        $data['judul_page'] = $this->input->post('judul');
        $data['slug_page'] = url_title($this->input->post('judul'), 'dash', TRUE);
        $data['status_page'] = $this->input->post('status');
        $data['isi_page'] = $this->input->post('isi');

        $data['update_page'] = date('Y-m-d H:i:s');
        $data['log_page'] = $this->sessionname;
       
        if(!empty($_FILES['foto']['name'])){
            $upload = $this->fungsi->ImgUpload('foto', $data['slug_page'], $this->path, 900, TRUE);
            if(is_null($upload)){
                redirect($this->module.'/edit/'.$id);
            }
            $data['foto_page'] = $upload;
            $old_img = $this->input->post('exfoto');
            (is_file($old_img)) ? unlink($old_img) : '';
        }
        
        $result = $this->m_page->update(decode($id), $data);
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
            'label' => 'Judul Halaman',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'status',
            'label' => 'Status Halaman',
            'rules' => 'required|trim|xss_clean'
        )
    );    
}
