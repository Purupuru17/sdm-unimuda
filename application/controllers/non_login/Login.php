<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends KZ_Controller {
    
    private $module = 'non_login/login';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_user'));
    }
    function index() {
        !empty($this->sessionid) ? redirect('beranda') : null;
        $this->load->library(array('recaptcha'));
        
        $data['captcha'] = $this->recaptcha->getWidget();
        $data['script_captcha'] = $this->recaptcha->getScriptTag();
        
        $data['app_session'] = $this->session->userdata('app_session');
        $data['app_theme'] = json_decode($data['app_session']['tema'], true);
        $data['module'] = $this->module;

        $this->data['content'] = $this->load->view('non_login/v_login', $data, TRUE);
        $this->load->view('non_login/v_template', $this->data);
    }
    function changed($id = NULL, $level = NULL) {
        if(empty(decode($id)) || empty($level)){
            redirect('beranda');
        }
        $this->load->model(array('m_group'));
        
        $role = $this->m_group->getRole(['r.user_id' => $this->sessionid, 'r.group_id' => decode($id)]);
        if($role['rows'] < 1){
            redirect('home/err_module');
        }
        $this->session->set_userdata(array(
            'logged' => true, 'id' => $this->sessionid,
            'name' => $this->sessionname, 'usr' => $this->sessionusr,
            'groupid' => decode($id), 'level' => decode($level), 'foto' => $this->sessionfoto
        ));
        $update['last_login'] = date('Y-m-d H:i:s');
        $update['ip_user'] = ip_agent();
        $update['log_user'] = $this->sessionname . ' Login Sistem with Switch Account';

        $this->m_user->update($this->sessionid, $update);

        $this->session->set_flashdata('notif', notif('info', 'Selamat datang kembali', $this->sessionname));
        redirect('beranda');
    }
    function logout() {
        session_destroy();
        redirect();
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'action') {
            //ACTION
            if ($routing_module['source'] == 'auth') {
                $this->_authLogin();
            }else if ($routing_module['source'] == 'autoload') {
                $this->_autoModule();
            }
        }
    }
    //function
    function _authLogin() {
        if(!$this->fungsi->Validation($this->rules,'ajax')){
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $username = preg_replace('/[^a-zA-Z0-9\s]/u', '', $this->input->post('username'));
        $password = $this->input->post('password');
        
        if(!is_string($username) || !is_string($password)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Username tidak sesuai'));
        }
        $data = $this->m_authentication->getAuth($username);
        if (sizeof($data) < 1) {
            jsonResponse(array('status' => FALSE, 'msg' => 'Username belum terdaftar di sistem kami'));
        }   
        if($data['status_user'] == '0') {
            jsonResponse(array('status' => FALSE, 'msg' => 'Akun telah Non-Aktif. Hubungi Administrator'));
        }
        if(!password_verify($password, $data['password'])){
            jsonResponse(array('status' => FALSE, 'msg' => 'Password tidak sesuai'));
        }
        $this->session->set_userdata(array(
            'logged' => true, 'id' => $data['id_user'], 'name' => $data['fullname'],
            'usr' => $data['username'], 'groupid' => $data['id_group'],'level' => $data['level'], 'foto' => $data['foto_user']
        ));
        $update['last_login'] = date('Y-m-d H:i:s');
        $update['ip_user'] = ip_agent();
        $update['log_user'] = $data['fullname'] . ' Login Sistem';

        $this->m_user->update($data['id_user'], $update);

        $this->session->set_flashdata('notif', notif('info', 'Selamat datang kembali', $data['fullname']));
        jsonResponse(array('data' => site_url('beranda'), 'status' => TRUE, 
            'msg' => 'Selamat datang kembali, '.$data['fullname']));
    }
    function _autoModule() {
        $this->load->library(array('visitor'));
        
        $data = array();
        $data['visitor'] = $this->visitor->is_tracking();
        
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        if(!$this->loggedin){
            jsonResponse(array('data' => $data, 'item' => 0 ,'status' => false));
        }
        $this->load->model(array('m_notif'));
        //klik notifikasi
        $id = $this->input->post('id');
        if(!empty(decode($id))){
            $this->m_notif->update(decode($id), ['status_notif' => '1']);
            jsonResponse(array('data' => $data, 'item' => 0 ,'status' => false, 'msg' => 'Klik Notification'));
        }
        //update login
        $this->m_user->update($this->sessionid, array('last_login' => date('Y-m-d H:i:s')));
        //update notifikasi
        $result = $this->m_notif->getAll(['status_notif' => '0'], 10);
        if($result['rows'] < 1){
            jsonResponse(array('data' => $data, 'item' => 0 ,'status' => false, 'msg' => 'Empty Notification'));
        }
        $html = '';
        foreach ($result['data'] as $item) {
            $status = ($item['status_notif'] == '0') ? 'unread' : '';
            $html .= '<li id="'.encode($item['id_notif']).'" class="'.$status.'">
                <a href="'.site_url($item['link_notif']).'" class="clearfix"><span class="msg-body" style="margin-left:5px">
                    <span class="msg-title"><span class="blue bigger-110 bolder">'.$item['subject_notif'].'</span><br/>
                    <span class="grey">'.$item['msg_notif'].'</span></span><span class="msg-time">
                        <i class="smaller-90 ace-icon fa fa-clock-o"></i>
                    <span class="">'.selisih_wkt($item['buat_notif']).'</span></span></span>
                </a></li>';
            $data['table'][] = $item;
        }
        jsonResponse(array('data' => $data, 'html' => $html, 'item' => $result['rows'] ,'status' => true));
    }
    function _captchaGoogle($str){
        $this->load->library(array('recaptcha'));
        
        $result = $this->recaptcha->verifyResponse($str);
        if($result['success']){
            return TRUE;
        }else{
            $this->form_validation->set_message('_captchaGoogle', 'Centang atau selesaikan CAPTCHA terlebih dahulu');
            return FALSE;
        }
    }
    private $rules = array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[5]'
        ),array(
            'field' => 'g-recaptcha-response',
            'label' => 'Pengecekan Keamanan',
            'rules' => 'required|trim|xss_clean|callback__captchaGoogle' 
        )
    );
}
