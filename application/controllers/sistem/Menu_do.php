<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_do extends KZ_Controller {
    
    private $module = 'sistem/menu';
    private $module_do = 'sistem/menu_do'; 
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_menu'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['nama_menu'] = $this->input->post('nama');
        $data['module_menu'] = $this->input->post('module');
        $data['status_menu'] = $this->input->post('status');
        $data['order_menu'] = $this->input->post('order');
        $data['icon_menu'] = empty($this->input->post('icon')) ? 'fa fa-list' : $this->input->post('icon');

        $aksi = array(
            'index' => ($this->input->post('lihat')) ? 1 : 0,
            'add' => ($this->input->post('tambah')) ? 1 : 0,
            'edit' => ($this->input->post('ubah')) ? 1 : 0,
            'delete' => ($this->input->post('hapus')) ? 1 : 0,
            'detail' => ($this->input->post('detail')) ? 1 : 0,
            'cetak' => ($this->input->post('cetak')) ? 1 : 0,
            'export' => ($this->input->post('export')) ? 1 : 0
        );
        if($this->input->post('jenis') == '0'){
            $data['parent_menu'] = '0';
            $aksi['index'] = 1;
            $aksi['add'] = 0;
            $aksi['edit'] = 0;
            $aksi['delete'] = 0;
            $aksi['detail'] = 0;
            $aksi['export'] = 0;
            $aksi['cetak'] = 0;
        }else{
            $data['parent_menu'] = $this->input->post('menu');
        }
        $result = $this->m_menu->insertAksi($data, $aksi);
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
        $data['nama_menu'] = $this->input->post('nama');
        $data['module_menu'] = $this->input->post('module');
        $data['status_menu'] = $this->input->post('status');
        $data['order_menu'] = $this->input->post('order');
        $data['icon_menu'] = empty($this->input->post('icon')) ? 'fa fa-list' : $this->input->post('icon');

        $aksi = array(
            'index' => ($this->input->post('lihat')) ? 1 : 0,
            'add' => ($this->input->post('tambah')) ? 1 : 0,
            'edit' => ($this->input->post('ubah')) ? 1 : 0,
            'delete' => ($this->input->post('hapus')) ? 1 : 0,
            'detail' => ($this->input->post('detail')) ? 1 : 0,
            'export' => ($this->input->post('export')) ? 1 : 0,
            'cetak' => ($this->input->post('cetak')) ? 1 : 0
        );
        if($this->input->post('jenis') == '0'){
            $data['parent_menu'] = '0';
            $aksi['index'] = 1;
            $aksi['add'] = 0;
            $aksi['edit'] = 0;
            $aksi['delete'] = 0;
            $aksi['detail'] = 0;
            $aksi['export'] = 0;
            $aksi['cetak'] = 0;
        }else{
            $data['parent_menu'] = $this->input->post('menu');
        }

        $result = $this->m_menu->updateAksi(decode($id), $data, $aksi);
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
            'label' => 'Nama Menu',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'module',
            'label' => 'Module Menu',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'jenis',
            'label' => 'Jenis Menu',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Tampilkan',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
