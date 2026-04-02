<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notif extends KZ_Controller {

    private $module = 'sistem/notif';
    private $module_do = 'sistem/notif_do';  
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_notif'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Notifikasi','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/notif/v_index', $this->data);
    }
    function add() {
        $this->data['notif'] = $this->m_notif->getEmpty();
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Notifikasi','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/notif/v_form', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $get = $this->m_notif->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $result = $this->m_notif->delete($id);
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
        }else if ($routing_module['type'] == 'action') {
            //AKSI
            if ($routing_module['source'] == 'delete') {
                $this->_delete_all();
            }
        }
    }
    function _table_index()
    {
        $where = [];
        if($this->sessionlevel != '1'){
            $where['n.send_id'] = $this->sessionid;
        }
        $datatables = $this->m_notif->getDatatables($where);
        jsonResponse($datatables);
    }
    function _delete_all()
    {
        $id = $this->input->post('id');
        if(empty($id)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada data yang dipilih' ));
        }
        $notif_arr = array_map(function($val) {
            return decode(trim($val));
        }, explode(",", $id));
        $notif_id = array_filter($notif_arr);
        
        $result = $this->m_notif->deleteBatch($notif_id);
        if($result > 0) {
            jsonResponse(array('status' => TRUE, 'msg' => $result.' Notifikasi berhasil dihapus'));
        }else{
            jsonResponse(array('status' => FALSE, 'msg' => 'Notifikasi gagal dihapus'));
        }
    }
}
