<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aktivitas extends KZ_Controller {

    private $module = 'presensi/aktivitas';
    private $module_do = 'presensi/aktivitas_do'; 
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_presensi']);
        $this->_pegawaiId();
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Aktivitas','List Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('presensi/aktivitas/v_index', $this->data);
    }
    function add() {
        if(empty($this->pid) && $this->sessionlevel != '1'){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Pegawai tidak ditemukan'));
            redirect($this->module);
        }
        //CEK LIBUR
        $this->load->model(['m_libur']);
        $libur = $this->m_libur->get(['tgl_libur' => date('Y-m-d')]);
        if(!empty($libur)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Hari Libur, '.$libur['catat_libur']));
            redirect($this->module);
        }
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Aktivitas','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title' => $this->uri->segment(1), 'url'=>'#'),
            array('title' => $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url'=>'')
        );
        $this->load_view('presensi/aktivitas/v_add', $this->data);
    }
    function delete() {
        $id = decode($this->input->post('id'));
        if(empty($id)){
            redirect($this->module);
        }
        $get = $this->m_presensi->get($id);
        if(empty($get)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $result = $this->m_presensi->delete($id);
        if ($result) {
            // DELETE FOTO
            (is_file($get['foto_masuk'])) ? unlink($get['foto_masuk']) : false;
            (is_file($get['foto_pulang'])) ? unlink($get['foto_pulang']) : false;
            
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
            } else if($routing_module['source'] == 'rekap') {
                $this->_tableRekap();
            }
        }else if($routing_module['type'] == 'list') {
            //TABLE
            if($routing_module['source'] == 'pegawai') {
                $this->_listPegawai();
            }
        }
    }
    function _tableIndex() 
    {
        $where = [];
        $pegawai = decode($this->input->post('pegawai'));
        $jenis = $this->input->post('jenis');
        $status = $this->input->post('status');
        $awal = $this->input->post('awal');
        $akhir = $this->input->post('akhir');
        
        if ($pegawai != '') {
            $where['pegawai_id'] = $pegawai;
        }
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['pegawai_id'] = $this->pid;
        }
        if ($jenis != '') {
            $where['jenis_pegawai'] = $jenis;
        }
        if ($status != '') {
            $where['status_presensi'] = $status;
        }
        if ($awal != '') {
            $where['DATE(tgl_presensi) >='] = $awal;
        }
        if ($akhir != '') {
            $where['DATE(tgl_presensi) <='] = $akhir;
        }
        
        $datatables = $this->m_presensi->getDatatables($where, ['module' => $this->module, 'level' => $this->sessionlevel]);
        jsonResponse($datatables);
    }
    function _tableRekap()
    {
        if(!$this->fungsi->Validation($this->rules, 'ajax')){
            jsonResponse(['status' => false, 'msg' => validation_errors()]);
        }
        $jenis = $this->input->post('jenis');
        $awal = $this->input->post('awal');
        $akhir = $this->input->post('akhir');
        
        $where['tgl_presensi >='] = $awal;
        $where['tgl_presensi <='] = $akhir;
        if ($jenis != '') {
            $where['jenis_pegawai'] = $jenis;
        }
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['pegawai_id'] = $this->pid;
        }
        $pivot = $this->m_presensi->pivotData($where, $awal, $akhir);
        if(empty($pivot['data'])){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        // ================= PIVOT TABLE =================
        $data = ['table' => []];
        $no = 1;
        foreach ($pivot['data'] as $item) {
            $rows = [];
            $rows[] = $no;
            $rows[] = '<strong>'.ctk($item['nama']).
                '</strong><br><small class="grey">'.ctk($item['jenis']).'</small>';
            
            $jml_tepat = 0;
            $jml_tepat_plng = 0;
            $jml_lambat = 0;
            $jml_lambat_plng = 0;
            
            foreach ($pivot['periode'] as $tgl) {

                $status = $item[$tgl]['status'] ?? null;
                $masuk = $item[$tgl]['masuk'] ?? '';
                $pulang = $item[$tgl]['pulang'] ?? '';
                $ftmasuk = $item[$tgl]['fotomasuk'] ?? null;
                $ftpulang = $item[$tgl]['fotopulang'] ?? null;
                $html = '-';
                
                if ($status === 'TEPAT WAKTU') {
                    if(!empty($pulang)){
                        $html = '<i class="fa fa-calendar-check-o bigger-150 green"></i>';
                        $jml_tepat_plng++;
                    } else {
                        $html = '<i class="fa fa-calendar-minus-o bigger-120 orange"></i>';
                        $jml_tepat++;
                    }
                } else if ($status === 'TERLAMBAT') {
                    if(!empty($pulang)){
                        $html = '<i class="fa fa-calendar-check-o bigger-150 blue"></i>';
                        $jml_lambat_plng++;
                    } else {
                        $html = '<i class="fa fa-calendar-minus-o bigger-120 red"></i>';
                        $jml_lambat++;
                    }
                }
                $rows[] = '<span id="view-btn" itemid="'.$status.'" itemwm="'.format_date($masuk,4).'"
                    itemwp="'.format_date($pulang,4).'" itemftm="'.load_file($ftmasuk).'" itemftp="'.load_file($ftpulang).'">'.$html.'</span>';
            }
            $jml_status = $jml_tepat_plng + $jml_lambat_plng;
            $persen = $pivot['count'] ? round(($jml_status / $pivot['count']) * 100) : 0;
            
            $rows[] = '(<b>'.$persen.'%</b>)<br><small>'.$jml_tepat_plng.' TEPAT - '.$jml_lambat_plng.' TERLAMBAT</small>';

            $data['table'][] = $rows;
            $no++;
        }
        $data['periode'] = $pivot['periode'];
        
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _listPegawai()
    {
        $this->load->model(['m_pegawai']);
        
        $where = null;
        $key = $this->input->post('key');
        $id = $this->input->get('id');
        
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['id_pegawai'] = $this->pid;
        }
        if(!empty($id)){
            $where['id_pegawai'] = decode($id);
            $result = $this->m_pegawai->all($where);
        }else{
            $result = $this->m_pegawai->all($where, ['order' => 'nama ASC', 'like' => ['nama'], 'key' => $key]);
        }
        $data = array();
        foreach ($result['data'] as $val) {
            $text = $val['nama'];
            $data[] = array("id" => encode($val['id_pegawai']), "text" => $text);
        }
        jsonResponse($data);
    }
    private $rules = [
        [
            'field' => 'awal',
            'label' => 'Tanggal Awal',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ],[
            'field' => 'akhir',
            'label' => 'Tanggal Akhir',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ]
    ];
}