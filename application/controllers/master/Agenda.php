<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends KZ_Controller {

    private $module = 'master/agenda';
    private $module_do = 'master/agenda_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_agenda','m_lokasi']);
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Agenda', 'List Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('master/agenda/v_index', $this->data);
    }
    function add() {
        $this->data['lokasi'] = $this->m_lokasi->all();
        $this->data['edit'] = $this->m_agenda->getEmpty();
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Agenda', 'Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/agenda/v_form', $this->data);
    }
    function edit($id = NULL) {
        if (empty(decode($id))) {
            redirect($this->module);
        }
        $this->data['lokasi'] = $this->m_lokasi->all();
        $this->data['edit'] = $this->m_agenda->get(decode($id));
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do . '/edit/' . $id;
        $this->data['title'] = array('Agenda', 'Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/agenda/v_form', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $get = $this->m_agenda->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        // PRESENSI
        $getpre = $this->m_agenda->getPresensi(['agenda_id' => $id]);
        if(!empty($getpre)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak dapat dihapus. Terhubung data presensi'));
            redirect($this->module);
        }
        $result = $this->m_agenda->delete($id);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if (is_null($routing_module['type'])) {
            redirect('');
        }
        if ($routing_module['type'] == 'table') {
            //TABLE
            if ($routing_module['source'] == 'index') {
                $this->_tableIndex();
            }
        }
    }
    //function
    function _tableIndex() {
        $jenis = $this->input->post('jenis');
        $presensi = $this->input->post('presensi');
        $bulan = $this->input->post('bulan');
        
        $where = [];
        if ($jenis != '') {
            $where['jenis_agenda'] = $jenis;
        }
        if ($presensi != '') {
            $where['is_open'] = $presensi;
        }
        if ($bulan != '') {
            $where['DATE_FORMAT(waktu_agenda,"%m-%Y")'] = $bulan;
        }
        
        $datatables = $this->m_agenda->getDatatables($where, ['module' => $this->module]);
        jsonResponse($datatables);
    }
}