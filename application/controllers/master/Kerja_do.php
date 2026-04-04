<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Kerja_do extends KZ_Controller {
    
    private $module = 'master/kerja';
    private $module_do = 'master/kerja_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_kerja'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['hari_kerja'] = (int) $this->input->post('hari');
        $data['masuk_kerja'] = $this->input->post('masuk');
        $data['pulang_kerja'] = $this->input->post('pulang');
        $data['limit_kerja'] = (int) $this->input->post('limit');
        
        $today = date('Y-m-d');
        $jamMasuk  = new DateTime("$today {$data['masuk_kerja']}");
        $jamPulang = new DateTime("$today {$data['pulang_kerja']}");
        if ($jamPulang <= $jamMasuk) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Waktu Pulang lebih kecil daripada Waktu Masuk'));
            redirect($this->module.'/add');
        }
        $result = $this->m_kerja->insert($data);
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
        $data['hari_kerja'] = (int) $this->input->post('hari');
        $data['masuk_kerja'] = $this->input->post('masuk');
        $data['pulang_kerja'] = $this->input->post('pulang');
        $data['limit_kerja'] = (int) $this->input->post('limit');
        
        $today = date('Y-m-d');
        $jamMasuk  = new DateTime("$today {$data['masuk_kerja']}");
        $jamPulang = new DateTime("$today {$data['pulang_kerja']}");
        if ($jamPulang <= $jamMasuk) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Waktu Pulang lebih kecil daripada Waktu Masuk'));
            redirect($this->module.'/edit/'.$id);
        }
        $result = $this->m_kerja->update(decode($id), $data);
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
            'field' => 'hari',
            'label' => 'Hari Kerja',
            'rules' => 'required|trim|xss_clean|is_natural|greater_than_equal_to[1]|less_than_equal_to[7]'
        ),array(
            'field' => 'masuk',
            'label' => 'Masuk',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'pulang',
            'label' => 'Pulang',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'limit',
            'label' => 'Toleransi',
            'rules' => 'required|trim|xss_clean|is_natural|greater_than_equal_to[10]|less_than_equal_to[300]'
        )
    );    
}
