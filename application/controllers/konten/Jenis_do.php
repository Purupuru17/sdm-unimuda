<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jenis_do extends KZ_Controller {
    
    private $module = 'konten/jenis';
    private $module_do = 'konten/jenis_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_jenis'));
    }
    function add() {
        if($this->fungsi->Validation($this->rules) == FALSE){
            redirect($this->module);
        }
        $data['judul_jenis'] = $this->input->post('judul');
        $data['slug_jenis'] = url_title($this->input->post('judul'), 'dash', TRUE);
        $data['color_jenis'] = $this->input->post('color');
        $data['icon_jenis'] = $this->input->post('icon');
        
        $result = $this->m_jenis->insert($data);
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
        $data['judul_jenis'] = $this->input->post('judul');
        $data['slug_jenis'] = url_title($this->input->post('judul'), 'dash', TRUE);
        $data['color_jenis'] = $this->input->post('color');
        $data['icon_jenis'] = $this->input->post('icon');
        
        $result = $this->m_jenis->update(decode($id), $data);
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
            'label' => 'Jenis Artikel',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'color',
            'label' => 'Warna Background',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'icon',
            'label' => 'Icon',
            'rules' => 'required|trim|xss_clean'
        )
    );    
}
