<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Akses extends KZ_Controller {
    
    private $module = 'sistem/akses';
    private $module_do = 'sistem/akses_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_group'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Hak Akses','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/akses/v_index', $this->data);
    }
    function add($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['group'] = $this->m_group->get(decode($id));
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array($this->data['group']['nama_group'],'Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/akses/v_add', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->load->model(array('m_menu'));
        
        $this->data['group'] = $this->m_group->get(decode($id));
        $this->data['akses'] = $this->m_menu->getAuthenticationMenu(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array($this->data['group']['nama_group'],'Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/akses/v_edit', $this->data);
    }
    function delete() {
        $id = $this->input->post('id');
        $user = $this->input->post('user');
        
        if(empty(decode($id)) || empty(decode($user))){
            redirect($this->module);
        }
        $get = $this->m_group->get(decode($id));
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $result = $this->m_group->deleteRole(['group_id' => decode($id), 'user_id' => decode($user)]);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module.'/add/'.$id);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module.'/add/'.$id);
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
                $this->_table_index();
            } else if($routing_module['source'] == 'user') {
                $this->_table_user();
            }
        }else if($routing_module['type'] == 'list') {
            //LIST
            if($routing_module['source'] == 'user') {
                $this->_list_user();
            }
        }
    }
    //function
    function _table_index() {
        $where = [];
        $result = $this->m_group->all($where); 
        if($result['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => []);
        $no = 1;
        foreach ($result['data'] as $items) {
            $btn_aksi = '<a href="'. site_url($this->module.'/add/'. encode($items['id_group'])) .'" 
                    class="tooltip-success btn btn-white btn-success btn-sm btn-round" data-rel="tooltip" title="Tambah Hak Akses">
                    <span class="green"><i class="ace-icon fa fa-plus-circle bigger-120"></i></span>
                </a><a href="'. site_url($this->module.'/edit/'. encode($items['id_group'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Hak Akses">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a>';
            
            $row = [];  
            $row[] = ctk($no);
            $row[] = ctk($items['nama_group']);
            $row[] = ctk($items['keterangan_group']);
            $row[] = st_aktif($items['level'] == '1');
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

            $data['table'][] = $row;
            $no++;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _table_user() {
        $id = decode($this->input->post('id'));
        $where['r.group_id'] = $id;
        
        $result = $this->m_group->getRole($where);
        if($result['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => []);
        $no = 1;
        foreach ($result['data'] as $items) {
            $btn_aksi = '<a href="#" itemid="'. encode($items['group_id']) .'" itemprop="'. encode($items['user_id']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $row = [];  
            $row[] = ctk($no);
            $row[] = ctk($items['fullname']);
            $row[] = ctk($items['username']);
            $row[] = ctk($items['nama_group']);
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

            $data['table'][] = $row;
            $no++;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _list_user(){
        $this->load->model(array('m_user'));
        
        $where = null;
        $key = $this->input->post('key');
        
        $result = $this->m_user->all($where, ['like' => ['username','fullname'], 'key' => $key]);
        $data = [];
        foreach ($result['data'] as $val) {
            $text = ctk($val['fullname']).' ['.ctk($val['username']).']';
            $data[] = array("id" => encode($val['id_user']), "text" => $text);
        }
        jsonResponse($data);
    }
}
