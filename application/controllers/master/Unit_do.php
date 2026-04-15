<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_do extends KZ_Controller {
    
    private $module = 'master/unit';
    private $module_do = 'master/unit_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_unit'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['nama_unit'] = $this->input->post('nama');
        $data['kode_unit'] = $this->input->post('kode');
        
        $result = $this->m_unit->insert($data);
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
        $data['nama_unit'] = $this->input->post('nama');
        $data['kode_unit'] = $this->input->post('kode');
        
        $result = $this->m_unit->update(decode($id), $data);
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
            'label' => 'Nama Unit',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'kode',
            'label' => 'Kode Unit',
            'rules' => 'trim|xss_clean|is_natural|min_length[4]|max_length[5]'
        )
    );    
}
