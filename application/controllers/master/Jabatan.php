<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends KZ_Controller {
    
    private $module = 'master/jabatan';
    private $module_do = 'master/jabatan_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_jabatan'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Jabatan','List Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('master/jabatan/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_jabatan->getEmpty();
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Jabatan','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/jabatan/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_jabatan->get(decode($id));
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Jabatan','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/jabatan/v_form', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $this->load->model(['m_pegawai']);
        
        $get = $this->m_jabatan->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        // PEGAWAI
        $getpre = $this->m_pegawai->get(['jabatan_id' => $id]);
        if(!empty($getpre)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak dapat dihapus. Terhubung data pegawai'));
            redirect($this->module);
        }
        $result = $this->m_jabatan->delete($id);
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
        } else if($routing_module['type'] == 'list') {
            //LIST
            if($routing_module['source'] == 'unit') {
                $this->_listUnit();
            } else if($routing_module['source'] == 'jabatan') {
                $this->_listJabatan();
            }
        }
    }
    function _tableIndex() {
        $options = [
            'alias'      => 'j',
            'select'     => 'j.*, u.nama_unit, jj.nama_jabatan AS nama_atasan, uu.nama_unit AS unit_atasan',
            'join'       => [ 
                ['m_unit u','u.id_unit = j.unit_id','inner'],
                ['m_jabatan jj','jj.id_jabatan = j.atasan','left'],
                ['m_unit uu','uu.id_unit = jj.unit_id','left'],
            ],
            'order'      => 'nama_jabatan ASC'
        ];
        $where = [];
        
        $result = $this->m_jabatan->all($where, $options); 
        if($result['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => []);
        $no = 1;
        foreach ($result['data'] as $items) {
            $btn_aksi = '<a href="'. site_url($this->module.'/edit/'. encode($items['id_jabatan'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id_jabatan']) .'" itemprop="'. ctk($items['nama_jabatan']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $row = [];  
            $row[] = ctk($no);
            $row[] = '<strong>'.ctk($items['nama_jabatan']).'</strong>';
            $row[] = $items['nama_unit'];
            $row[] = $items['nama_atasan'].'<br><small>'.$items['unit_atasan'].'</small>';
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

            $data['table'][] = $row;
            $no++;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _listUnit()
    {
        $this->load->model(['m_unit']);
        
        $key = $this->input->post('key');
        $id = decode($this->input->get('id'));
        
        if(!empty($id)){
            $result = $this->m_unit->all(['id_unit' => $id]);
        }else{
            $result = $this->m_unit->all(null, ['like' => ['nama_unit'], 'key' => $key, 'order' => 'nama_unit ASC']);
        }
        $data = array();
        foreach ($result['data'] as $val) {
            $text = $val['nama_unit'];
            $data[] = array("id" => encode($val['id_unit']), "text" => $text);
        }
        jsonResponse($data);
    }
    function _listJabatan()
    {
        $this->load->model(['m_jabatan']);
        
        $key = $this->input->post('key');
        $other_id = decode($this->input->post('id'));
        $id = decode($this->input->get('id'));
        
        $options = [
            'alias'     => 'j',
            'select'    => 'j.*, u.nama_unit',
            'join'      => [ ['m_unit u','u.id_unit = j.unit_id','left'] ],
            'like'      => ['nama_jabatan','nama_unit'],
            'key'       => $key,
            'order'     => 'nama_unit ASC'
        ];
        $where = [];
        if(!empty($other_id)){
            $where['id_jabatan <>'] = $other_id;
        }
        if(!empty($id)){
            $result = $this->m_jabatan->all(['id_jabatan' => $id], $options);
        }else{
            $result = $this->m_jabatan->all($where, $options);
        }
        $data = array();
        foreach ($result['data'] as $val) {
            $text = $val['nama_jabatan'].' ['.$val['nama_unit'].']';
            $data[] = array("id" => encode($val['id_jabatan']), "text" => $text);
        }
        jsonResponse($data);
    }
}
