<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends KZ_Controller {
    
    private $module = 'sistem/akun';
    private $module_do = 'sistem/akun_do';
    private $path = 'upload/akun/';
    
    public function __construct() {
        parent::__construct();
        $this->load->model(array('m_user'));
    }
    public function index(){
        $this->data['user'] = $this->m_user->get($this->sessionid);
        
        $this->data['action'] = $this->module.'/edit';
        $this->data['title'] = array('Profil',  $this->sessionname);
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/user/v_akun', $this->data);
    }
    public function edit() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module);
        }            
        $data['fullname'] = $this->input->post('nama');
        $data['username'] = strtolower($this->input->post('username'));
        $data['email'] = $this->input->post('email');
        $data['update_user'] = date('Y-m-d H:i:s');
        $data['log_user'] = $this->sessionname . ' ubah profil User';
        $data['ip_user'] = ip_agent();
        
        if(!empty($_FILES['foto']['name'])){
            $img = url_title($data['fullname'].' '.random_string('alnum', 2), 'dash', TRUE);
            $upload = $this->fungsi->ImgUpload('foto', $img, $this->path, 200);
            if(is_null($upload)){
                redirect($this->module);
            }
            $data['foto_user'] = $upload;
            $old_img = $this->input->post('exfoto');
            (is_file($old_img)) ? unlink($old_img) : '';
        }

        $result = $this->m_user->update($this->sessionid, $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah. Silahkan login ulang untuk melihat perubahan.'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module);
        }
    }
    var $rules = array(
       array(
            'field' => 'nama',
            'label' => 'Nama Lengkap',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|xss_clean|valid_email'
        )
    );
}
