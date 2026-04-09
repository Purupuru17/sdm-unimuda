<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Libur_do extends KZ_Controller {
    
    private $module = 'master/libur';
    private $module_do = 'master/libur_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_libur'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['tgl_libur'] = $this->input->post('tanggal');
        $data['catat_libur'] = strtoupper($this->input->post('catatan'));
        
        $check = $this->m_libur->get(['tgl_libur' => $data['tgl_libur']]);
        if (!empty($check)) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tanggal '.$data['tgl_libur'].'sudah ada sebelumnya'));
            redirect($this->module.'/add');
        }
        $result = $this->m_libur->insert($data);
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
        $data['tgl_libur'] = $this->input->post('tanggal');
        $data['catat_libur'] = strtoupper($this->input->post('catatan'));
        
        $result = $this->m_libur->update(decode($id), $data);
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
            'field' => 'tanggal',
            'label' => 'Tanggal',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'catatan',
            'label' => 'Catatan',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        )
    );    
}
