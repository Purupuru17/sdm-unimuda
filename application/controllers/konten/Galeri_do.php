<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Galeri_do extends KZ_Controller {
    
    private $module = 'konten/galeri';
    private $module_do = 'konten/galeri_do';  
    private $path = 'upload/galeri/';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_galeri'));
    }
    function add() {
        if($this->fungsi->Validation($this->rules) == FALSE){
            redirect($this->module);
        }
        $data['judul_galeri'] = $this->input->post('judul');
        $data['slug_galeri'] = url_title($this->input->post('judul'), 'dash', TRUE);
        $data['jenis_galeri'] = $this->input->post('jenis');
        $data['isi_galeri'] = $this->input->post('isi');
        $data['status_galeri'] = $this->input->post('status');
        $data['is_header'] = $this->input->post('header');

        $data['update_galeri'] = date('Y-m-d H:i:s');
        $data['log_galeri'] = $this->sessionname;
        
        if($data['jenis_galeri'] === '1'){
            $data['foto_galeri'] = $this->input->post('link');
        }
        
        $ratio = TRUE;
        $height = 0;
        if($data['is_header'] === '1'){
            $ratio = FALSE;
            $height = 400;
        }
        if(!empty($_FILES['foto']['name'])){
            $upload = $this->fungsi->ImgUpload('foto', $data['slug_galeri'], $this->path, 900, $ratio, $height);
            if(is_null($upload)){
                redirect($this->module.'/add');
            }
            $data['foto_galeri'] = $upload; 
        }
        
        $result = $this->m_galeri->insert($data);
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
        $data['judul_galeri'] = $this->input->post('judul');
        $data['slug_galeri'] = url_title($this->input->post('judul'), 'dash', TRUE);
        $data['jenis_galeri'] = $this->input->post('jenis');
        $data['isi_galeri'] = $this->input->post('isi');
        $data['status_galeri'] = $this->input->post('status');
        $data['is_header'] = $this->input->post('header');

        $data['update_galeri'] = date('Y-m-d H:i:s');
        $data['log_galeri'] = $this->sessionname;
        
        if($data['jenis_galeri'] === '1'){
            $data['foto_galeri'] = $this->input->post('link');
        }
        
        $ratio = TRUE;
        $height = 0;
        if($data['is_header'] === '1'){
            $ratio = FALSE;
            $height = 400;
        }
        if(!empty($_FILES['foto']['name'])){
            $upload = $this->fungsi->ImgUpload('foto', $data['slug_galeri'], $this->path, 900, $ratio, $height);
            if(is_null($upload)){
                redirect($this->module.'/edit/'.$id);
            }
            $data['foto_galeri'] = $upload;
            $old_img = $this->input->post('exfoto');
            (is_file($old_img)) ? unlink($old_img) : '';
        }
        
        $result = $this->m_galeri->update(decode($id), $data);
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
            'label' => 'Judul Galeri',
            'rules' => 'required|trim|xss_clean|min_length[10]'
        ),array(
            'field' => 'jenis',
            'label' => 'Jenis Galeri',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Galeri',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'isi',
            'label' => 'Isi Konten',
            'rules' => 'trim|min_length[10]'
        )
    );    
}
