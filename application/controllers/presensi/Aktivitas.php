<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
        if(!$this->fungsi->Validation($this->rules_export, 'ajax')){
            jsonResponse(['status' => false, 'msg' => validation_errors()]);
        }
        $pivot = $this->_pivotPresensi();
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
                $html = '<i class="fa fa-calendar-times-o grey"></i>';
                
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
    function _pivotPresensi($type = null) 
    {
        $this->load->model(['m_pegawai']);
        
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
        $options = [
            'alias'      => 'p',
            'select'     => 'p.id_pegawai, p.nama, p.nik, p.jenis_pegawai,
                pr.tgl_presensi, pr.status_presensi, pr.waktu_masuk, pr.waktu_pulang, pr.foto_masuk, pr.foto_pulang',
            'join'       => [ 
                ['m_presensi pr','pr.pegawai_id = p.id_pegawai','left'],
            ],
            'order'      => 'nama ASC'
        ];
        $result = $this->m_pegawai->all($where, $options); 
        if($result['rows'] < 1){
            if(empty($type)){
                jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
            } else {
                $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
                redirect($this->module);
            }
        }
        // ================= RANGE TANGGAL =================
        $start = new DateTime($awal);
        $end   = new DateTime($akhir);
        $periode = [];
        while ($start <= $end) {
            $periode[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }
        // ================= PIVOT =================
        $pivot = [];
        foreach ($result['data'] as $row) {            
            // init pegawai
            if (!isset($pivot[$row['id_pegawai']])) {
                $arr_item = [
                    'nama' => $row['nama'],
                    'nik' => $row['nik'],
                    'jenis' => $row['jenis_pegawai']
                ];
                // init semua tanggal null
                foreach ($periode as $tgl) {
                    $arr_item[$tgl] = null;
                }
                $pivot[$row['id_pegawai']] = $arr_item;
            }
            if (!empty($row['tgl_presensi'])) {
                $tgl = date('Y-m-d', strtotime($row['tgl_presensi']));
                $pivot[$row['id_pegawai']][$tgl] = [
                    'status' => $row['status_presensi'],
                    'masuk'  => $row['waktu_masuk'],
                    'pulang' => $row['waktu_pulang'],
                    'fotomasuk' => $row['foto_masuk'],
                    'fotopulang' => $row['foto_pulang'],
                ];
            }
        }
        return [
            'data' => $pivot, 'periode' => $periode, 'count' => count($periode),
            'awal' => $awal, 'akhir' => $akhir
        ];
    }
    function export()
    {
        $pivot = $this->_pivotPresensi('export');
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('REKAP');
        $title = 'Presensi '.format_date($pivot['awal'],1).' sd '.format_date($pivot['akhir'],1);
             
        $rowHeader1 = 1;
        $rowHeader2 = 2;
        $row = 3;
        $col = 1;
        // ================= HEADER =================
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'No');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        $col++;

        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'Nama');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        $col++;

        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'Jenis');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        $col++;
        
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'Tanggal');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col + $pivot['count'] - 1, $rowHeader1);
        // Baris ke-2 isi tanggal
        foreach ($pivot['periode'] as $tgl) {
            $sheet->setCellValueByColumnAndRow($col++, $rowHeader2, date('d-m', strtotime($tgl)));
        }
        // Kolom terakhir (merge 2 baris)
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'Kehadiran');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        // ================= STYLE HEADER =================
        $lastCol = $col;
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'2')->getFont()->setBold(true);
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'2')
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $no = 1;
        foreach ($pivot['data'] as $val) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col++, $row, $no);
            $sheet->setCellValueByColumnAndRow($col++, $row, $val['nama']);
            $sheet->setCellValueByColumnAndRow($col++, $row, $val['jenis']);

            $jml_tepat = 0;
            $jml_tepat_plng = 0;
            $jml_lambat = 0;
            $jml_lambat_plng = 0;
            
            foreach ($pivot['periode'] as $tgl) {

                $status = $val[$tgl]['status'] ?? null;
                $masuk = $val[$tgl]['masuk'] ?? '';
                $pulang = $val[$tgl]['pulang'] ?? '';
                $cellVal = '';
                $rgb = '808080';
                
                if ($status === 'TEPAT WAKTU') {
                    if(!empty($pulang)){
                        $cellVal = 'TP';
                        $rgb = '69aa46';
                        $jml_tepat_plng++;
                    } else {
                        $cellVal = 'TM';
                        $rgb = 'ff892a';
                        $jml_tepat++;
                    }
                } elseif ($status === 'TERLAMBAT') {
                    if(!empty($pulang)){
                        $cellVal = 'LP';
                        $rgb = '478fca';
                        $jml_lambat_plng++;
                    } else {
                        $cellVal = 'LM';
                        $rgb = 'f50000';
                        $jml_lambat++;
                    }
                }
                $cellVal = $cellVal . "\n" . format_date($masuk,4) . ' - ' . format_date($pulang,4);
                $sheet->setCellValueByColumnAndRow($col, $row, $cellVal);
                
                $style = $sheet->getStyleByColumnAndRow($col, $row);
                $style->getAlignment()->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $style->getFont()->getColor()->setRGB($rgb);
                $sheet->getRowDimension($row)->setRowHeight(32);
                
                $col++;
            }
            $sheet->setCellValueByColumnAndRow($col,$row,
                'TP:'.$jml_tepat_plng.' LP:'.$jml_lambat_plng.' TM:'.$jml_tepat.' LM:'.$jml_lambat
            );
            $no++;
            $row++;
        }
        // ================= AUTO WIDTH =================
        for ($i = 1; $i <= $lastCol; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
        // ================= TABLE =================
        $tableStyle = [
            'borders' => [
                'allBorders' => [ 'borderStyle' => Border::BORDER_THIN, ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER, 'wrapText'   => false,
            ],
        ];
        $lastColumn = $sheet->getHighestColumn();
        $lastRow    = $sheet->getHighestRow();
        $range = 'A1:' . $lastColumn . $lastRow;
        $sheet->getStyle($range)->applyFromArray($tableStyle);
        
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = url_title($title,'dash', TRUE).'.xlsx';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
    private $rules_export = array(
        array(
            'field' => 'awal',
            'label' => 'Tanggal Awal',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'akhir',
            'label' => 'Tanggal Akhir',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        )
    );
}