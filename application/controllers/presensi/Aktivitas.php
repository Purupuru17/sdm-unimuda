<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aktivitas extends KZ_Controller {

    private $module = 'presensi/aktivitas';
    private $module_do = 'presensi/aktivitas_do'; 
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_presensi'));
        $this->_pegawaiId();
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Aktivitas','List Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('presensi/aktivitas/v_index', $this->data);
    }
    function add() {
        if(empty($this->pid)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Pegawai tidak ditemukan'));
            redirect($this->module);
        }
        //CEK LIBUR
        $this->load->model(['m_libur']);
        $libur = $this->m_libur->get(['tgl_libur' => date('Y-m-d')]);
        if(!empty($libur)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Hari Libur, '.$libur['catat_libur']));
            redirect($this->module);
        }
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Aktivitas','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('presensi/aktivitas/v_add', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        redirect($this->module);
        $this->data['edit'] = $this->m_presensi->get(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Aktivitas','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('presensi/aktivitas/v_edit', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        redirect($this->module);
        $this->data['detail'] = $this->m_presensi->get(decode($id));
        
        $this->data['title'] = array('Aktivitas','Detail Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('presensi/aktivitas/v_detail', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $get = $this->m_presensi->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $result = $this->m_presensi->delete($id);
        if ($result) {
            // DELETE FOTO
            (is_file($get['foto_masuk'])) ? unlink($get['foto_masuk']) : false;
            (is_file($get['foto_pulang'])) ? unlink($get['foto_pulang']) : false;
            
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //TABLE
            if($routing_module['source'] == 'index') {
                $this->_tableIndex();
            }
        }
    }
    function _tableIndex() 
    {
        $where = [];
        $pegawai = decode($this->input->post('pegawai'));
        $tanggal = $this->input->post('tanggal');
        $status = $this->input->post('status');
        
        if ($pegawai != '') {
            $where['pegawai_id'] = $pegawai;
        }
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['pegawai_id'] = $this->pid;
        }
        if ($tanggal != '') {
            $where['DATE(tgl_presensi)'] = $tanggal;
        }
        if ($status != '') {
            $where['status_presensi'] = $status;
        }
        $datatables = $this->m_presensi->getDatatables($where, ['module' => $this->module, 'level' => $this->sessionlevel]);
        jsonResponse($datatables);
    }
}
