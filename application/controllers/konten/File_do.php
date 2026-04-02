<?php defined('BASEPATH') OR exit('No direct script access allowed');

class File_do extends KZ_Controller {
    
    private $module = 'konten/file';
    private $module_do = 'konten/file_do';  
    private $path = 'upload/file/';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_file'));
    }
    function add() {
        if($this->fungsi->Validation($this->rules) == FALSE){
            redirect($this->module);
        }
        $nama = $data['nama_file'] = $this->input->post('nama');
        $slug = url_title(substr($nama, 0, strpos($nama, ".")), 'dash', TRUE);
        $data['update_file'] = date('Y-m-d H:i:s');
        $data['log_file'] = $this->sessionname;
        
        $this->load->library(array('upload'));
        $cfg['file_name'] = $slug;
        $cfg['upload_path'] = './' . $this->path;
        $cfg['allowed_types'] = '*';
        $cfg['max_size'] = 10100;
        $this->upload->initialize($cfg);

        if(!empty($_FILES['foto']['name'])){
            if(!$this->upload->do_upload('foto')) {
                $this->session->set_flashdata('notif', notif('danger', 'Peringatan', strip_tags($this->upload->display_errors())));
                redirect($this->module.'/add');
            }
            $upload = $this->upload->data('file_name');
            $data['url_file'] = $this->path . $upload;
            $data['type_file'] = $this->upload->data('file_type');
            $data['size_file'] = $this->upload->data('file_size').' KB';
        }

        $result = $this->m_file->insert($data);
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
        
        $nama = $data['nama_file'] = $this->input->post('nama');
        $slug = url_title(substr($nama, 0, strpos($nama, ".")), 'dash', TRUE);
        $data['update_file'] = date('Y-m-d H:i:s');
        $data['log_file'] = $this->sessionname;
        
        $this->load->library(array('upload'));
        $cfg['file_name'] = $slug;
        $cfg['upload_path'] = './' . $this->path;
        $cfg['allowed_types'] = '*';
        $cfg['max_size'] = 10100;
        $this->upload->initialize($cfg);

        if(!empty($_FILES['foto']['name'])){
            if(!$this->upload->do_upload('foto')) {
                $this->session->set_flashdata('notif', notif('danger', 'Peringatan', strip_tags($this->upload->display_errors())));
                redirect($this->module.'/edit/'.$id);
            }
            $upload = $this->upload->data('file_name');
            $data['url_file'] = $this->path . $upload;
            $data['type_file'] = $this->upload->data('file_type');
            $data['size_file'] = $this->upload->data('file_size').' KB';
            
            $old_file = $this->input->post('exfoto');
            (is_file($old_file)) ? unlink($old_file) : ''; 
        }
        
        $result = $this->m_file->update(decode($id), $data);
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
            'field' => 'nama',
            'label' => 'Nama File',
            'rules' => 'required|trim|xss_clean|min_length[10]'
        )
    );    
}
