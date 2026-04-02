<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Ifsnop\Mysqldump as IMysqldump;

class Aplikasi extends KZ_Controller {

    private $module = 'sistem/aplikasi';
    private $path = 'private/';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_aplikasi'));
        $this->load->helper(array('directory','file','download'));
    }
    function index() {
        $this->data['is_admin'] = ($this->sessionlevel == '1') ? true : false;
        $this->data['log_path'] = config_item('log_path');
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module . '/edit/';
        $this->data['title'] = array('Aplikasi', 'Ubah Pengaturan');
        $this->data['breadcrumb'] = array(
            array('title' => $this->uri->segment(1), 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => '')
        );
        $this->load_view('sistem/aplikasi/v_edit', $this->data);
    }
    function add($get = null) {
        if(empty($get)){
            redirect($this->module);
        }
        $table = array_filter(explode('@', $get));
        if(!is_array($table)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Format tidak sesuai'));
            redirect($this->module);
        }
        $title = url_title(APP_NAME.' '.date('d m Y H i s'),'-',true);
        $file = "log/{$title}.sql";
        
        $dumpSettings = array('exclude-tables' => $table);
        try {
            $dump = new IMysqldump\Mysqldump($this->db->dsn, $this->db->username, $this->db->password, $dumpSettings);
            $dump->start($file);
            force_download($file, NULL);
        } catch (\Exception $e) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Backup Database gagal dilakukan : '.$e->getMessage()));
            redirect($this->module);
        }
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $path = config_item('log_path').decode($id);
        if (is_file($path)) {
            unlink($path);
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        }
    }
    function edit($id = NULL) {
        if(empty(decode($id))) {
            redirect($this->module);
        }
        if(!$this->fungsi->Validation($this->rules)) {
            redirect($this->module);
        }
        $param = $this->input->post(NULL, TRUE);
        
        $data['judul'] = element('judul', $param);
        $data['cipta'] = element('cipta', $param);
        $data['deskripsi'] = element('deskrip', $param);
        $data['log'] = $this->sessionname.' mengubah data '.date('d-m-Y H:i:s');

        $navbar = element('navbar', $param, 0);
        $sidebar = element('sidebar', $param, 0);
        $bread = element('bread', $param, 0);
        $compact = element('compact', $param, 0);
        $hover = element('hover', $param, 0);
        $horizontal = element('horizontal', $param, 0);
        $webcolor = element('webcolor', $param, '#000000');
        $webcolor_other = element('webcolor_other', $param, '#000000');
        
        if ($sidebar == 1) {
            $navbar = 1;
        }
        if ($bread == 1) {
            $navbar = 1;
            $sidebar = 1;
        }
        if ($compact == 1) {
            $hover = 1;
        }
        if ($horizontal == 1) {
            $compact = 1;
            $hover = 1;
        }
        $theme = array(
            'theme' => element('theme', $param),
            'login' => element('login', $param),
            'navbar' => $navbar,
            'sidebar' => $sidebar,
            'bread' => $bread,
            'container' => element('container', $param, 0),
            'hover' => $hover,
            'compact' => $compact,
            'horizontal' => $horizontal,
            'altitem' => element('altitem', $param, 0),
            'webcolor' => $webcolor,
            'webcolor_other' => $webcolor_other
        );
        $data['tema'] = json_encode($theme);
        
        $this->load->library(array('upload'));
        if (!empty($_FILES['foto']['name'])) {
            $img = url_title($data['judul'].' '.random_string('alnum', 4),'dash',TRUE);
            $upload = $this->fungsi->ImgUpload('foto', $img, $this->path, 1050, FALSE, 150);
            if(is_null($upload)){
                redirect($this->module);
            }
            $data['logo'] = $upload;
            $old_img = $this->input->post('exfoto');
            (is_file($old_img)) ? unlink($old_img) : '';
        }
        $result = $this->m_aplikasi->update(decode($id), $data);
        if ($result) {
            $this->session->set_userdata(['app_session' => $this->m_aplikasi->get(1)]);
            
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'table') {
            //TABLE
            if ($routing_module['source'] == 'visitor') {
                $this->_list_visitor();
            }else if ($routing_module['source'] == 'grafik') {
                $this->_chart_visitor();
            }
        }
    }
    //function
    function _list_visitor() {
        $this->load->model(array('m_visitor'));
        
        $awal = $this->input->post('awal');
        $akhir = $this->input->post('akhir');
        
        $where = array(
            'DATE(access_date) >=' => empty($awal) ? date('Y-m-d') : $awal,
            'DATE(access_date) <=' => empty($akhir) ? date('Y-m-d') : $akhir
        );
        $datatables = $this->m_visitor->getDatatables($where);
        jsonResponse($datatables);
    } 
    function _chart_visitor() {
        $this->load->model(array('m_visitor'));
        
        $awal = empty($this->input->post('awal')) ? date('Y-m-d') : $this->input->post('awal');
        $akhir = empty($this->input->post('akhir')) ? date('Y-m-d') : $this->input->post('akhir');
        
        if (strcmp($awal, $akhir) == 0){
            $result = $this->m_visitor->getChart(['DATE(access_date)' => $awal]);
        } else {
            $result = $this->m_visitor->getChart(
                ['DATE(access_date) >=' => $awal, 'DATE(access_date) <=' => $akhir],
                false
            );
        }
        if($result['rows'] < 1){
            jsonResponse(array('data' => [], 'total' => 0));
        }
        $data = [];
        $total = 0;
        foreach ($result['data'] as $item) {
            $row = [];
            $row['day'] = $item['day'];
            $row['visit'] = (int)$item['visits'];
            $row['akses'] = (int)$item['akses'];

            $data[] = $row;
            $total += $item['visits'];
        }
        jsonResponse(array('data' => $data, 'total' => $total));
    }
    var $rules = array(
        array(
            'field' => 'judul',
            'label' => 'Judul Aplikasi',
            'rules' => 'required|trim|xss_clean|min_length[5]|max_length[80]'
        ),array(
            'field' => 'cipta',
            'label' => 'Hak Cipta',
            'rules' => 'required|trim|xss_clean|min_length[5]|max_length[80]'
        ),array(
            'field' => 'deskrip',
            'label' => 'Deskripsi',
            'rules' => 'required|trim|xss_clean|min_length[5]|max_length[200]'
        ),array(
            'field' => 'theme',
            'label' => 'Tema Admin',
            'rules' => 'required|trim|xss_clean'
        ), array(
            'field' => 'login',
            'label' => 'Background Login',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'webcolor',
            'label' => 'Warna Utama',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'webcolor_other',
            'label' => 'Warna Kedua',
            'rules' => 'required|trim|xss_clean'
        )
    );

}
