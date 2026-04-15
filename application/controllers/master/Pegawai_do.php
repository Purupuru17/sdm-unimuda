<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_do extends KZ_Controller {
    
    private $module = 'master/pegawai';
    private $module_do = 'master/pegawai_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(['m_pegawai']);
    }
    function add($id = NULL) {
        if (!empty(decode($id))){
            $this->_createAkun($id);
        }
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        // AKUN
        $user['id_group'] = 4;
        $user['id_user'] = random_string('unique');
        $user['fullname'] = strtoupper($this->input->post('nama'));
        $user['username'] = $this->input->post('nik');
        $user['password'] = password_hash(preg_replace('/\s/', '', '1234567'), PASSWORD_DEFAULT);
        $user['status_user'] = '1';
        $user['log_user'] = 'Registrasi Akun';
        $user['ip_user'] = ip_agent();
        $user['buat_user'] = date('Y-m-d H:i:s');
        // DATA
        $data['user_id'] = $user['id_user'];
        $data['id_pegawai'] = $user['id_user'];
        
        $data['nik'] = $user['username'];
        $data['nama'] = $user['fullname'];
        $data['nama_gelar'] = $this->input->post('gelar');
        
        $data['jenis_pegawai'] = $this->input->post('jenis');
        $data['tgl_pegawai'] = empty($this->input->post('tanggal')) ? null : $this->input->post('tanggal');
        $data['unit_id'] = decode($this->input->post('unit'));
        
        $data['nidn'] = $this->input->post('nidn');
        $data['nuptk'] = $this->input->post('nuptk');
        $data['status_pegawai'] = $this->input->post('status');
        $data['pangkat'] = $this->input->post('pangkat');
        $data['akademik'] = $this->input->post('akademik');
        $data['jabatan_id'] = decode($this->input->post('jabatan'));
        
        $data['update_at'] = date('d-m-Y H:i:s').' '.$this->sessionname.' menambahkan data';
        
        $check = $this->m_pegawai->get(['nik' => $data['nik']]);
        if (!empty($check)) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'NIK sudah digunakan oleh '.$check['nama']));
            redirect($this->module.'/add');
        }
        $result = $this->m_pegawai->createAkun($data, $user);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module.'/add');
        }
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['nik'] = $this->input->post('nik');
        $data['nama'] = strtoupper($this->input->post('nama'));
        $data['nama_gelar'] = $this->input->post('gelar');
        
        $data['jenis_pegawai'] = $this->input->post('jenis');
        $data['tgl_pegawai'] = empty($this->input->post('tanggal')) ? null : $this->input->post('tanggal');
        $data['unit_id'] = decode($this->input->post('unit'));
        
        $data['nidn'] = $this->input->post('nidn');
        $data['nuptk'] = $this->input->post('nuptk');
        $data['status_pegawai'] = $this->input->post('status');
        $data['pangkat'] = $this->input->post('pangkat');
        $data['akademik'] = $this->input->post('akademik');
        $data['jabatan_id'] = decode($this->input->post('jabatan'));
        
        $data['update_at'] = date('d-m-Y H:i:s').' '.$this->sessionname.' mengubah data';
        
        $result = $this->m_pegawai->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    function _createAkun($id) {
        $this->load->model(['m_user']);
        
        $check = $this->m_pegawai->get(decode($id));
        if (empty($check)) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $akun = $this->m_user->get(['username' => $check['nik']]);
        if (!empty($akun)) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Akun dengan Username : '.$check['nik'].' sudah ada'));
            redirect($this->module);
        }
        // AKUN
        $user['id_group'] = 4;
        $user['id_user'] = decode($id);
        $user['fullname'] = $check['nama'];
        $user['username'] = $check['nik'];
        $user['password'] = password_hash(preg_replace('/\s/', '', '1234567'), PASSWORD_DEFAULT);
        $user['status_user'] = '1';
        $user['log_user'] = 'Registrasi Akun';
        $user['ip_user'] = ip_agent();
        $user['buat_user'] = date('Y-m-d H:i:s');
        // DATA
        $data['user_id'] = $user['id_user'];
        $data['update_at'] = date('d-m-Y H:i:s').' '.$this->sessionname.' membuat akun';
        
        $result = $this->m_pegawai->updateAkun(decode($id), $data, $user);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Akun berhasil dibuat'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Akun gagal dibuat'));
            redirect($this->module);
        }
    }
    private $rules = array(
        array(
            'field' => 'nik',
            'label' => 'NIK',
            'rules' => 'required|trim|xss_clean|is_natural|exact_length[16]'
        ), array(
            'field' => 'nama',
            'label' => 'Nama Lengkap',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ), array(
            'field' => 'gelar',
            'label' => 'Nama & Gelar',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ), array(
            'field' => 'tanggal',
            'label' => 'Tanggal Masuk',
            'rules' => 'trim|xss_clean|min_length[5]'
        ), array(
            'field' => 'jenis',
            'label' => 'Jenis Pegawai',
            'rules' => 'required|trim|xss_clean'
        ), array(
            'field' => 'unit',
            'label' => 'Unit Kerja',
            'rules' => 'trim|xss_clean'
        ), array(
            'field' => 'nidn',
            'label' => 'NIDN',
            'rules' => 'trim|xss_clean|is_natural|min_length[5]'
        ), array(
            'field' => 'nuptk',
            'label' => 'NUPTK',
            'rules' => 'trim|xss_clean|is_natural|min_length[5]'
        ), array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'trim|xss_clean'
        ), array(
            'field' => 'pangkat',
            'label' => 'Pangkat',
            'rules' => 'trim|xss_clean'
        ), array(
            'field' => 'akademik',
            'label' => 'Akademik',
            'rules' => 'trim|xss_clean'
        ), array(
            'field' => 'jabatan',
            'label' => 'Jabatan',
            'rules' => 'trim|xss_clean'
        )
    );
    /*
    
    $data['jenis_kelamin'] = $this->input->post('kelamin');
    $data['tempat_lahir'] = strtoupper($this->input->post('tempat'));
    $data['tgl_lahir'] = $this->input->post('lahir');
    $data['agama'] = $this->input->post('agama');
    $data['telepon'] = $this->input->post('telepon');
    $data['email'] = $this->input->post('email');
    $data['pendidikan'] = $this->input->post('pendidikan');
    $data['status_nikah'] = $this->input->post('nikah');
    $data['jumlah_anak'] = $this->input->post('anak');
    $data['alamat'] = $this->input->post('alamat');
    
    private $rules_edit = array(
        array(
            'field' => 'kelamin',
            'label' => 'Jenis Kelamin',
            'rules' => 'required|trim|xss_clean'
        ), array(
            'field' => 'tempat',
            'label' => 'Tempat Lahir',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ), array(
            'field' => 'lahir',
            'label' => 'Tanggal Lahir',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ), array(
            'field' => 'agama',
            'label' => 'Agama',
            'rules' => 'required|trim|xss_clean'
        ), array(
            'field' => 'telepon',
            'label' => 'Telepon',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[10]'
        ), array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|xss_clean|min_length[5]|valid_email'
        ), array(
            'field' => 'pendidikan',
            'label' => 'Pendidikan',
            'rules' => 'trim|xss_clean|min_length[5]'
        ), array(
            'field' => 'nikah',
            'label' => 'Status Nikah',
            'rules' => 'trim|xss_clean'
        ), array(
            'field' => 'anak',
            'label' => 'Jumlah Anak',
            'rules' => 'trim|xss_clean|is_natural'
        ), array(
            'field' => 'alamat',
            'label' => 'Alamat',
            'rules' => 'trim|xss_clean|min_length[5]'
        )
    );
     * 
     */
}
