<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda_do extends KZ_Controller {
    
    private $module = 'master/agenda';
    private $module_do = 'master/agenda_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_agenda']);
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['lokasi_id'] = decode($this->input->post('lokasi'));
        $data['jenis_agenda'] = $this->input->post('jenis');
        $data['judul_agenda'] = eyd_text($this->input->post('judul'));
        $data['petugas_agenda'] = strtoupper($this->input->post('petugas'));
        $data['waktu_agenda'] = $this->input->post('waktu');
        $data['limit_agenda'] = (int) $this->input->post('limit');
        $data['status_agenda'] = $this->input->post('status');
        $data['is_open'] = $this->input->post('presensi');
        
        $data['update_agenda'] = date('Y-m-d H:i:s');
        $data['log_agenda'] = $this->sessionname.' menambahkan data';
        
        $result = $this->m_agenda->insert($data);
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
        $data['lokasi_id'] = decode($this->input->post('lokasi'));
        $data['jenis_agenda'] = $this->input->post('jenis');
        $data['judul_agenda'] = eyd_text($this->input->post('judul'));
        $data['petugas_agenda'] = strtoupper($this->input->post('petugas'));
        $data['waktu_agenda'] = $this->input->post('waktu');
        $data['limit_agenda'] = (int) $this->input->post('limit');
        $data['status_agenda'] = $this->input->post('status');
        $data['is_open'] = $this->input->post('presensi');
        
        $data['update_agenda'] = date('Y-m-d H:i:s');
        $data['log_agenda'] = $this->sessionname.' mengubah data';
        
        $result = $this->m_agenda->update(decode($id), $data);
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
            'field' => 'lokasi',
            'label' => 'Lokasi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'jenis',
            'label' => 'Jenis',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'judul',
            'label' => 'Agenda Kegiatan',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'petugas',
            'label' => 'Petugas',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'waktu',
            'label' => 'Waktu',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'limit',
            'label' => 'Keterlambatan',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'presensi',
            'label' => 'Presensi',
            'rules' => 'required|trim|xss_clean'
        )
    );
}