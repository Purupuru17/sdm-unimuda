<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Kutipan_do extends KZ_Controller {
    
    private $module = 'konten/kutipan';
    private $module_do = 'konten/kutipan_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_kutipan'));
    }
    function add() {
        if($this->fungsi->Validation($this->rules) == FALSE){
            redirect($this->module);
        }
        $data['oleh'] = $this->input->post('oleh');
        $data['quote'] = $this->input->post('quote');
        $data['update_kutipan'] = date('Y-m-d H:i:s');
        $data['log_kutipan'] = $this->sessionname;
        
        $result = $this->m_kutipan->insert($data);
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
        $data['oleh'] = $this->input->post('oleh');
        $data['quote'] = $this->input->post('quote');
        $data['update_kutipan'] = date('Y-m-d H:i:s');
        $data['log_kutipan'] = $this->sessionname;
        
        $result = $this->m_kutipan->update(decode($id), $data);
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
            'field' => 'oleh',
            'label' => 'Oleh',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'quote',
            'label' => 'Isi Quote',
            'rules' => 'required|trim|xss_clean|min_length[10]'
        )
    );    
}
