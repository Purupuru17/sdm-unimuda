<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_do extends KZ_Controller {
    
    private $module = 'sistem/user';
    private $module_do = 'sistem/user_do';
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_user'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['fullname'] = $this->input->post('nama');
        $data['username'] = $this->input->post('username');
        $data['password'] = password_hash($this->input->post('confirm'), PASSWORD_DEFAULT);
        $data['status_user'] = $this->input->post('status');
        $data['id_group'] = decode($this->input->post('group'));
        
        $data['buat_user'] = date('Y-m-d H:i:s');
        $data['ip_user'] = ip_agent();
        
        $result = $this->m_user->insert($data);
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
        if(!$this->fungsi->Validation($this->rules_edit)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['fullname'] = $this->input->post('nama');
        $data['username'] = $this->input->post('username');
        $data['status_user'] = $this->input->post('status');
        $data['id_group'] = decode($this->input->post('group'));
        
        $data['update_user'] = date('Y-m-d H:i:s');
        $data['log_user'] = $this->sessionname. ' ubah data User';
        $data['ip_user'] = ip_agent();
        
        if($this->input->post('password') === '1'){
            $data['password'] = password_hash(date('dmY'), PASSWORD_DEFAULT);
            $data['log_user'] = $this->sessionname. ' me-RESET password Akun';
        }
        $result = $this->m_user->update(decode($id), $data);
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
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'confirm',
            'label' => 'Konfirmasi Password',
            'rules' => 'trim|required|xss_clean|matches[password]|min_length[5]'
        ),array(
            'field' => 'group',
            'label' => 'Group User',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required|trim|xss_clean'
        )
    );
    private $rules_edit = array(
        array(
            'field' => 'nama',
            'label' => 'Nama Lengkap',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'group',
            'label' => 'Group User',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
