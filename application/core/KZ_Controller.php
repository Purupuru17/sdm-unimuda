<?php defined('BASEPATH') OR exit('No direct script access allowed');
class KZ_Controller extends CI_Controller {
    
    public $loggedin = false;
    public $sessionid = null;
    public $sessionusr = null;
    public $sessionname = null;
    public $sessiongroup = null;
    public $sessionlevel = null;
    public $sessionfoto = null;
    
    public $pid = null;
            
    function __construct() {
        parent::__construct();
        
        $this->load->helper(array('security','app','format','extend'));
        $this->load->library(array('session','fungsi'));
        
        $this->_refresh();
        $this->_session();
        $this->_authentication(); 
    }
    //session
    function _session() {
        $this->load->model(array('m_aplikasi','m_authentication'));
        
        $this->loggedin = $this->session->userdata('logged');
        $this->sessionid = $this->session->userdata('id');
        $this->sessionusr = $this->session->userdata('usr');
        $this->sessionname = $this->session->userdata('name');
        $this->sessiongroup = $this->session->userdata('groupid');
        $this->sessionlevel = $this->session->userdata('level');
        $this->sessionfoto = $this->session->userdata('foto');
        
        if(empty($this->session->userdata('app_session'))){
            $app = $this->m_aplikasi->get(1);   
            $this->session->set_userdata(array('app_session' => $app));
        }
        if(empty($this->session->userdata('group_role')) && !empty($this->sessiongroup)){
            $this->load->model(array('m_group'));
            $group_role = $this->m_group->getRole(['r.user_id' => $this->sessionid]);
            $this->session->set_userdata(['group_role' => $group_role['data']]);
        }
    }
    //auth
    function _authentication() {
        $modules = [
            'non_login' => ['error_404', 'error_module', 'non_login', 'login', 
                'home', 'pages', 'galeri', 'artikel', 'tag', 'sitemap.xml'],
            'login' => ['beranda', 'logout'],
            'session' => [
                'non_login/login/ajax/type/action/source/auth',
                'non_login/login/ajax/type/action/source/autoload',
            ]
        ];
        // --- Ambil segment URL ---
        $module = $this->uri->segment(1) ?: 'home';
        $class  = $this->uri->segment(2) ?: 'home';
        $method = $this->uri->segment(3) ?: 'index';
        $segment = $this->uri->segment_array();
        
        // --- Hapus akhiran "_do" pada class ---
        if (substr($class, -3) === '_do') {
            $class = substr($class, 0, -3);
        }
        // --- Module yang tidak perlu login ---
        if (in_array($module, $modules['non_login'])) {
            return;
        }
        // --- Module yang butuh login ---
        if (in_array($module, $modules['login'])) {
            if (!isset($this->sessionid)) {
                redirect('login');
            }
            return;
        }
        // --- Validasi XSS pada URL ---
        $url_param = sprintf('%s %s %s %s', $module, $class, $method, $_SERVER['QUERY_STRING']);
        if ($this->security->xss_clean($url_param, TRUE) === FALSE) {
            redirect('error_404');
        }
        // --- Blok akses di jam tertentu (misalnya jam 23:30–03:00) ---
        if ($this->sessionlevel != '1' && is_beetwen('23:30', '03:00', date('H:i'))) {
            redirect('error_404');
        }
        // --- Khusus AJAX route ---
        $segment_str = implode('/', $segment);
        if (strpos($segment_str, '/ajax/') !== false) {
            // Cari posisi 'type' dan 'source' di segmen
            $typeIndex = array_search('type', $segment);
            $sourceIndex = array_search('source', $segment);

            $type = $typeIndex ? ($segment[$typeIndex + 1] ?? null) : null;
            $source = $sourceIndex ? ($segment[$sourceIndex + 1] ?? null) : null;

            if (empty($type) || empty($source)) {
                $this->output->set_status_header(403)->set_output(null)->_display();
                exit();
            }
            if (!in_array($module, $modules['non_login'])) {
                if(empty($this->sessionid)){
                    $this->output->set_status_header(403)->set_output(null)->_display();
                    exit();
                }
            }
            // Tutup session writing agar AJAX tidak nge-lock session file
            if (!in_array($segment_str, $modules['session'])) {
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_write_close();
                }
            }
            return;
        }
        // --- Cek izin module ---
        $allowed = $this->m_authentication->cekModule($module, $class, $method, $this->sessiongroup);
        if ($allowed) {
            return;
        }
        // --- Jika sudah login tapi tidak punya izin module ---
        if ($this->sessionid) {
            redirect('error_module');
        }
        // --- Jika belum login, redirect ke halaman login ---
        redirect('login');
    }
    //loadview
    function load_view($template, $data = '') {
        $this->load->model(array('m_menu'));
        
        $sidebar = $this->m_menu->getNavMenu($this->sessiongroup);
        $arrside = array();
        if (!is_null($sidebar)) {
            foreach ($sidebar['data'] as $side) {
                $arrside[$side['parent_menu']][] = $side;
            }
            $data['sidebar'] = $arrside;
        }
        $data['app_session'] = $this->session->userdata('app_session');
        $data['app_theme'] = json_decode($data['app_session']['tema'], true);
        
        $this->data['content'] = $this->load->view($template, $data, TRUE);
        $this->load->view('sistem/v_body', $this->data);
    }
    function load_home($template, $data = '') {
        $data['app_session'] = $this->session->userdata('app_session');
        $data['app_theme'] = json_decode($data['app_session']['tema'], true);
        
        $this->data['content'] = $this->load->view('home/'.APP_THEME.'/'.$template, $data, TRUE);
        $this->load->view('home/'.APP_THEME.'/h_body', $this->data);
    }
    //cache
    function _refresh(){
        // any valid date in the past
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // always modified right now
        $this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        // HTTP/1.1
        $this->output->set_header("Cache-Control: public, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        // HTTP/1.0
        $this->output->set_header("Pragma: no-cache");
    }
    function _pegawaiId() {
        if(empty($this->session->userdata('pid'))){
            $rs = $this->db->get_where('m_pegawai', ['user_id' => $this->sessionid])
                ->row_array();
            if(!is_null($rs)){
                $this->session->set_userdata(array('pid' => $rs['id_pegawai']));
            }
        }
        $this->pid = $this->session->userdata('pid');
    }
}