<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan_do extends KZ_Controller {
    
    private $module = 'master/jabatan';
    private $module_do = 'master/jabatan_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_jabatan'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['nama_jabatan'] = $this->input->post('nama');
        $data['unit_id'] = decode($this->input->post('unit'));
        
        $atasan = decode($this->input->post('atasan'));
        $data['atasan'] = empty($atasan) ? null : $atasan;
        
        $result = $this->m_jabatan->insert($data);
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
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['nama_jabatan'] = $this->input->post('nama');
        $data['unit_id'] = decode($this->input->post('unit'));
        
        $atasan = decode($this->input->post('atasan'));
        $data['atasan'] = empty($atasan) ? null : $atasan; 
        
        $result = $this->m_jabatan->update(decode($id), $data);
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
            'label' => 'Nama Jabatan',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'unit',
            'label' => 'Unit Kerja',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'atasan',
            'label' => 'Penanggung Jawab (Atasan)',
            'rules' => 'trim|xss_clean'
        )
    );    
}
