<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_do extends KZ_Controller {
    
    private $module = 'master/pegawai';
    private $module_do = 'master/pegawai_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_pegawai'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        // AKUN
        $user['id_group'] = 4;
        $user['id_user'] = random_string('unique');
        $user['fullname'] = strtoupper($this->input->post('nama'));
        $user['username'] = $this->input->post('nik');
        $user['password'] = password_hash(preg_replace('/\s/', '', $user['username']), PASSWORD_DEFAULT);
        $user['status_user'] = '1';
        $user['log_user'] = 'Registrasi Akun';
        $user['ip_user'] = ip_agent();
        $user['buat_user'] = date('Y-m-d H:i:s');
        // DATA
        $data['user_id'] = $user['id_user'];
        $data['id_pegawai'] = $user['id_user'];
        $data['nik'] = $user['username'];
        $data['nama'] = $user['fullname'];
        
        $check = $this->m_pegawai->get(['nik' => $data['nik']]);
        if (!empty($check)) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'NIK sudah digunakan oleh '.$check['nama']));
            redirect($this->module.'/add');
        }
        $result = $this->m_pegawai->createAkun($data, $user);
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
        $data['nama'] = strtoupper($this->input->post('nama'));
        $data['nik'] = $this->input->post('nik');
        
        $result = $this->m_pegawai->update(decode($id), $data);
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
            'label' => 'Nama Lengkap',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'nik',
            'label' => 'NIK',
            'rules' => 'required|trim|xss_clean|is_natural|exact_length[16]'
        ),array(
            'field' => 'status',
            'label' => 'Status',
            //'rules' => 'required|trim|xss_clean'
        )
    );    
}
