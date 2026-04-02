<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel_do extends KZ_Controller {
    
    private $module = 'konten/artikel';
    private $module_do = 'konten/artikel_do';  
    private $path = 'upload/artikel/';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_artikel'));
    }
    function add() {
        if($this->fungsi->Validation($this->rules) == FALSE){
            redirect($this->module);
        }
        $data['judul_artikel'] = $this->input->post('judul');
        $data['slug_artikel'] = url_title($this->input->post('judul'), 'dash', TRUE);
        $data['jenis_id'] = $this->input->post('jenis');
        $data['isi_artikel'] = $this->input->post('isi');
        $data['status_artikel'] = $this->input->post('status');
        $data['is_popular'] = $this->input->post('popular');
        $data['is_breaking'] = $this->input->post('breaking');
        
        $data['update_artikel'] = $this->input->post('tgl');
        $data['log_artikel'] = $this->sessionname;
        
        if(!empty($_FILES['foto']['name'])){
            $upload = $this->fungsi->ImgUpload('foto', $data['slug_artikel'], $this->path, 700, TRUE);
            if(is_null($upload)){
                redirect($this->module.'/add');
            }
            $data['foto_artikel'] = $upload; 
        }
        
        $result = $this->m_artikel->insert($data);
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
        
        $data['judul_artikel'] = $this->input->post('judul');
        $data['slug_artikel'] = url_title($this->input->post('judul'), 'dash', TRUE);
        $data['jenis_id'] = $this->input->post('jenis');
        $data['isi_artikel'] = $this->input->post('isi');
        $data['status_artikel'] = $this->input->post('status');
        $data['is_popular'] = $this->input->post('popular');
        $data['is_breaking'] = $this->input->post('breaking');
        
        $data['update_artikel'] = $this->input->post('tgl');
        $data['log_artikel'] = $this->sessionname;
        
        if(!empty($_FILES['foto']['name'])){
            $upload = $this->fungsi->ImgUpload('foto', $data['slug_artikel'], $this->path, 700, TRUE);
            if(is_null($upload)){
                redirect($this->module.'/edit/'.$id);
            }
            $data['foto_artikel'] = $upload;
            $old_img = $this->input->post('exfoto');
            (is_file($old_img)) ? unlink($old_img) : '';
        }
        
        $result = $this->m_artikel->update(decode($id), $data);
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
            'label' => 'Judul Artikel',
            'rules' => 'required|trim|xss_clean|min_length[10]'
        ),array(
            'field' => 'jenis',
            'label' => 'Jenis Artikel',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tgl',
            'label' => 'Tanggal Artikel',
            'rules' => 'required|trim|xss_clean|min_length[15]'
        ),array(
            'field' => 'popular',
            'label' => 'Popular Artikel',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'breaking',
            'label' => 'Breaking News',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Artikel',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'isi',
            'label' => 'Isi Konten',
            'rules' => 'required|trim|min_length[10]'
        )
    );    
}
