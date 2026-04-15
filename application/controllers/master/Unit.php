<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends KZ_Controller {
    
    private $module = 'master/unit';
    private $module_do = 'master/unit_do';    
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_unit'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Unit Kerja','List Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('master/unit/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_unit->getEmpty();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Unit Kerja','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/unit/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_unit->get(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Unit Kerja','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/unit/v_form', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $this->load->model(['m_pegawai']);
        
        $get = $this->m_unit->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        // PEGAWAI
        $getpre = $this->m_pegawai->get(['unit_id' => $id]);
        if(!empty($getpre)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak dapat dihapus. Terhubung data pegawai'));
            redirect($this->module);
        }
        $result = $this->m_unit->delete($id);
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
    function _tableIndex() {
        $where = [];
        $result = $this->m_unit->all($where, ['order' => 'nama_unit ASC']); 
        if($result['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => []);
        $no = 1;
        foreach ($result['data'] as $items) {
            $btn_aksi = '<a href="'. site_url($this->module.'/edit/'. encode($items['id_unit'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id_unit']) .'" itemprop="'. ctk($items['nama_unit']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $row = [];  
            $row[] = ctk($no);
            $row[] = '<strong>'.ctk($items['nama_unit']).'</strong>';
            $row[] = $items['kode_unit'];
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

            $data['table'][] = $row;
            $no++;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
}
