<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group_do extends KZ_Controller {
    
    private $module = 'sistem/group';
    private $module_do = 'sistem/group_do';
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_group'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['nama_group'] = $this->input->post('nama');
        $data['keterangan_group'] = $this->input->post('keterangan');
        $data['level'] = $this->input->post('status');

        $result = $this->m_group->insert($data);
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
        $data['nama_group'] = $this->input->post('nama');
        $data['keterangan_group'] = $this->input->post('keterangan');
        $data['level'] = $this->input->post('status');

        $result = $this->m_group->update(decode($id), $data);
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
            'label' => 'Nama Group',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'keterangan',
            'label' => 'Keterangan',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Super Admin',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
