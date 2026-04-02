<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Akses_do extends KZ_Controller {

    private $module = 'sistem/akses';
    private $module_do = 'sistem/akses_do';
    
    function __construct() {
        parent::__construct();
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module);
        }
        $this->load->model(array('m_group'));
        
        $id = $this->input->post('group');
        $data['group_id'] = decode($id);
        $data['user_id'] = decode($this->input->post('user'));
        
        $role = $this->m_group->getRole($data);
        if($role['rows'] > 0){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data telah tersimpan sebelumnya'));
            redirect($this->module.'/add/'.$id);
        }
        $result = $this->m_group->insertRole($data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module.'/add/'.$id);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module.'/add/'.$id);
        }
    }
    function edit($id = NULL) {
        if (empty(decode($id))) {
            redirect($this->module);
        }
        $this->load->model(array('m_authentication'));

        $total = $this->input->post('total');
        $param = $this->input->post();

        $auth = array();
        for ($i = 0; $i < $total; $i++) {
            array_push($auth, array("id_menu" => $param["id_menu" . $i],
                "index" => isset($param['lihat' . $i]) ? $param['lihat' . $i] : '',
                "add" => isset($param['tambah' . $i]) ? $param['tambah' . $i] : '',
                "edit" => isset($param['ubah' . $i]) ? $param['ubah' . $i] : '',
                "delete" => isset($param['hapus' . $i]) ? $param['hapus' . $i] : '',
                "detail" => isset($param['detail' . $i]) ? $param['detail' . $i] : '',
                "cetak" => isset($param['cetak' . $i]) ? $param['cetak' . $i] : '',
                "export" => isset($param['export' . $i]) ? $param['export' . $i] : ''));
        }
        $result = $this->m_authentication->updateGroupMenuAksi(decode($id), $auth);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module . '/edit/' . $id);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module . '/edit/' . $id);
        }
    }
    private $rules = array(
        array(
            'field' => 'group',
            'label' => 'Group Akses',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'user',
            'label' => 'User Aplikasi',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
