<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notif_do extends KZ_Controller {
    
    private $module = 'sistem/notif';
    private $module_do = 'sistem/notif_do';
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_notif'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $post = $this->input->post();
        $result = $this->m_notif->insertBatch($post, $this->sessionid);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module.'/add');
        }
    }
    private $rules = array(
        array(
            'field' => 'user',
            'label' => 'User',
            'rules' => 'required|trim|xss_clean'
        ),
        array(
            'field' => 'subject',
            'label' => 'Subject',
            'rules' => 'required|trim|xss_clean'
        ),
        array(
            'field' => 'pesan',
            'label' => 'Pesan',
            'rules' => 'required|trim|xss_clean'
        ),
        array(
            'field' => 'link',
            'label' => 'Link',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
