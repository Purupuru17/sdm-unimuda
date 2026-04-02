<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends KZ_Controller {
    
    private $module = 'sistem/menu';
    private $module_do = 'sistem/menu_do'; 
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_menu'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Menu','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/menu/v_index', $this->data);
    }
    function add() {
        $this->data['menu'] = $this->m_menu->getEmpty();
        $this->data['aksi'] = $this->m_menu->getAksiEmpty();
        $this->data['parent'] = $this->m_menu->all(['parent_menu' => '0']);
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Menu','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/menu/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['menu'] = $this->m_menu->get(decode($id));
        $this->data['aksi'] = $this->m_menu->getMenuAksi(decode($id));
        $this->data['parent'] = $this->m_menu->all(['parent_menu' => '0']);
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Menu','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/menu/v_form', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $get = $this->m_menu->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $result = $this->m_menu->deleteSub($id);
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
                $this->_table_index();
            }
        }
    }
    function _table_index() {
        $where = [];
        $result = $this->m_menu->getRecursive($where); 
        if($result['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => []);
        $no = 1;
        foreach ($result['data'] as $items) {
            $btn_aksi = '<a href="'. site_url($this->module.'/edit/'. encode($items['id_menu'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id_menu']) .'" itemprop="'. ctk($items['nama_menu']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $row = [];  
            $row[] = ctk($no);
            $row[] = ctk($items['nama_menu']);
            $row[] = ctk($items['module_menu']);
            $row[] = ($items['parent_menu'] !== '0') ? ctk($items['full_path']) 
                : '<strong>'.ctk($items['full_path']).'</strong>';
            $row[] = st_aktif($items['status_menu']);
            $row[] = '<i class="'.ctk($items['icon_menu']).' blue bigger-120"></i>';
            $row[] = ctk($items['order_menu']);
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

            $data['table'][] = $row;
            $no++;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
}
