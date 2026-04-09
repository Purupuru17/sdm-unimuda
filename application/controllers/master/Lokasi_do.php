<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi_do extends KZ_Controller {
    
    private $module = 'master/lokasi';
    private $module_do = 'master/lokasi_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_lokasi'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['nama_lokasi'] = strtoupper($this->input->post('nama'));
        $data['latitude'] = $this->input->post('latitude');
        $data['longitude'] = $this->input->post('longitude');
        $data['radius'] = (int) $this->input->post('radius');
        $data['status_lokasi'] = $this->input->post('status');
        $data['jenis_lokasi'] = $this->input->post('jenis');
        
        $result = $this->m_lokasi->insert($data);
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
        $data['nama_lokasi'] = strtoupper($this->input->post('nama'));
        $data['latitude'] = $this->input->post('latitude');
        $data['longitude'] = $this->input->post('longitude');
        $data['radius'] = (int) $this->input->post('radius');
        $data['status_lokasi'] = $this->input->post('status');
        $data['jenis_lokasi'] = $this->input->post('jenis');
        
        $result = $this->m_lokasi->update(decode($id), $data);
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
            'label' => 'Nama Lokasi',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'latitude',
            'label' => 'Latitude',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'longitude',
            'label' => 'Longitude',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'radius',
            'label' => 'Radius',
            'rules' => 'required|trim|xss_clean|is_natural|greater_than_equal_to[10]|less_than_equal_to[100]'
        ),array(
            'field' => 'jenis',
            'label' => 'Jenis',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required|trim|xss_clean'
        )
    );    
}
