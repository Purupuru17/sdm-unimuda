<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Kegiatan_do extends KZ_Controller {

    private $module = 'presensi/kegiatan';
    private $module_do = 'presensi/kegiatan_do'; 
    private $url_route = array('id', 'source', 'type');
    private $path = 'upload/kegiatan/';
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_kegiatan']);
        $this->load->library(['location']);
        
        $this->_pegawaiId();
    }
    function ajax()
    {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'action') {
            //AKSI
            if ($routing_module['source'] == 'location') {
                $this->_checkLocation();
            }else if ($routing_module['source'] == 'presensi') {
                $this->_savePresensi();
            }else if ($routing_module['source'] == 'validate') {
                $this->_validPresensi();
            }
        }
    }
    function _validPresensi()
    {
        if($this->sessionlevel != '1'){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak memilik akses'));
        }
        $id = array_filter(explode(",", $this->input->post('id')));
        if(empty($id) || !is_array($id)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada data yang dipilih'));
        }
        $success = array();
        $error = array();
        foreach ($id as $newId) {
            if(!empty(decode($newId))){
                
                $update = $this->m_kegiatan->update(decode($newId), ['validasi' => '1']);
                if($update){
                    $success[] = $newId;
                }else{
                    $error[] = $newId;
                }
            }
        }
        if(count($success) < 1){
            jsonResponse(array('status' => FALSE, 'msg' => count($error).' Data gagal diubah'));
        }
        jsonResponse(array('status' => TRUE, 'msg' => count($success).' Data berhasil diubah | '
            .count($error).' Data gagal diubah'));
    }
    function _checkLocation()
    {
        $this->load->model(['m_agenda','m_lokasi']);
        
        $id = decode($this->input->post('id'));
        $userLat = $this->input->post('latitude');
        $userLng = $this->input->post('longitude');
        
        if(empty($userLat) || empty($userLng)){
            jsonResponse(['status' => false, 'msg' => 'GPS tidak ditemukan. Silahkan coba ulang.']);
        }
        $agenda = $this->m_agenda->get($id);
        if(empty($agenda)){
            jsonResponse(['status' => false, 'msg' => 'Agenda kegiatan tidak ditemukan.']);
        }
        $lokasi = $this->m_lokasi->all(['id_lokasi' => $agenda['lokasi_id']]);
        if($lokasi['rows'] < 1){
            jsonResponse(['status' => false, 'msg' => 'Lokasi agenda kegiatan tidak ditemukan.']);
        }
        $result = $this->location->detectRadius($userLat, $userLng, $lokasi['data']);
        if(!empty($result)) {
            jsonResponse(['data' => $result['nama_lokasi'].' ['.round($result['distance']).' meter]', 'status' => true, 
                'msg' => 'Lokasi ditemukan. <br>Jarak : <b>'.round($result['distance']).' meter</b> dari <b>'.$result['nama_lokasi'].'</b>']);
        } else {
            jsonResponse(['status' => false, 'msg' => 'GPS tidak sesuai. Lokasi berada di luar jangkauan area.']);
        }
    }
    function _savePresensi()
    {
        if(!$this->fungsi->Validation($this->rules, 'ajax')){
            jsonResponse(['status' => false, 'msg' => validation_errors()]);
        }
        $this->load->model(['m_pegawai','m_agenda']);
        
        $id = decode($this->input->post('agenda'));
        $lokasi = $this->input->post('lokasi');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $foto = $this->input->post('foto');
        
        // CEK AKUN
        $pegawai = $this->m_pegawai->get($this->pid);
        if(empty($pegawai)){
            jsonResponse(['status' => false, 'msg' => 'Pegawai tidak ditemukan']);
        }
        // CEK AGENDA
        $agenda = $this->m_agenda->get($id);
        if(empty($agenda)){
            jsonResponse(['status' => false, 'msg' => 'Agenda Kegiatan tidak ditemukan']);
        }
        $masuk = $agenda['waktu_agenda'];
        $limit = (int) $agenda['limit_agenda'];
        
        $now = new DateTime();
        $jamMasuk  = new DateTime($masuk);
        
        $jamLimit = clone $jamMasuk;
        $jamLimit->modify("+{$limit} minutes");
        
        $jamAwal = clone $jamMasuk;
        $jamAwal->modify("-1 hour");
        
        $jamAkhir = clone $jamMasuk;
        $jamAkhir->modify("+6 hour");
        // CEK AWAL
        if ($now < $jamAwal) {
            jsonResponse(['status' => false, 'msg' => 'Presensi belum diizinkan. Agenda mulai pada : '.format_date($masuk,0)]);
        }
        // CEK AKHIR
        if ($now > $jamAkhir) {
            jsonResponse(['status' => false, 'msg' => 'Presensi tidak diizinkan. Agenda telah selesai']);
        }
        // CEK HADIR
        $cekHadir = $this->m_kegiatan->get(['pegawai_id' => $this->pid, 'agenda_id' => $id]);
        if(!empty($cekHadir)){
            jsonResponse(['status' => false, 'msg' => 'Sudah presensi sebelumnya']);
        }
        // STATUS LIMIT
        $is_status ='TEPAT WAKTU';
        if ($now > $jamLimit) {
            $diff = $jamLimit->diff($now);
            $menitTelat = ($diff->h * 60) + $diff->i;

            if ($menitTelat > 0) {
                $is_status = 'TERLAMBAT';
            }
        }
        //SAVE
        $data['pegawai_id'] = $this->pid;
        $data['agenda_id'] = $id;
        $data['status'] = $is_status;
        $data['validasi'] = '0';
        $data['waktu'] = date('Y-m-d H:i:s');
        $data['lokasi'] = $lokasi;
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        //UPLOAD
        $filename = url_title(date('Y m d H i s').' '.random_string('alnum', 4),'dash',TRUE); 
        $upload = $this->fungsi->imgUpBase64($foto, $filename, $this->path);
        if(empty($upload['data'])){
            jsonResponse(['status' => false, 'msg' => $upload['msg']]);
        }
        $data['foto'] = $upload['data'];
        
        $query = $this->m_kegiatan->insert($data);
        if($query){
            jsonResponse(['status' => true, 'msg' => 'Presensi masuk berhasil disimpan. Status : '.$is_status]);
        }else{
            jsonResponse(['status' => false, 'msg' => 'Presensi gagal disimpan']);
        }
    }
    function export()
    {
        if(!$this->fungsi->Validation($this->rules_export)){
            redirect($this->module);
        }
        $jenis = $this->input->post('jenis');
        $awal = $this->input->post('awal');
        $akhir = $this->input->post('akhir');
        
        $where['DATE(waktu) >='] = $awal;
        $where['DATE(waktu) <='] = $akhir;
        if ($jenis != '') {
            $where['jenis_pegawai'] = $jenis;
        }
        if(!empty($this->pid) && ($this->sessionlevel != '1')){
            $where['pegawai_id'] = $this->pid;
        }
        $pivot = $this->m_kegiatan->pivotData($where, $awal, $akhir);
        if(empty($pivot['data'])){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        // ================= PIVOT EXCEL =================
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('REKAP');
        $title = 'Kegiatan '.format_date($awal,1).' sd '.format_date($akhir,1);
             
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
        
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'Agenda');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col + $pivot['count'] - 1, $rowHeader1);
        // Baris ke-2 isi agenda
        foreach ($pivot['agenda'] as $keg) {
            $sheet->setCellValueByColumnAndRow($col, $rowHeader2, 
                $keg['jenis_agenda']."\n".format_date($keg['waktu_agenda'],2));
            $col++;
        }
        // Kolom terakhir (merge 2 baris)
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'TEPAT');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        $col++;
        
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'TERLAMBAT');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        
        // ================= STYLE HEADER =================
        $lastCol = $col;
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'2')->getFont()->setBold(true);
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().'2')
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $no = 1;
        foreach ($pivot['data'] as $val) {
            $col = 1;
            $sheet->setCellValueByColumnAndRow($col++, $row, $no);
            $sheet->setCellValueByColumnAndRow($col++, $row, $val['nama']);
            $sheet->setCellValueByColumnAndRow($col++, $row, $val['jenis']);

            $jml_tepat = 0;
            $jml_lambat = 0;
            
            foreach ($pivot['agenda'] as $keg) {

                $status = $val[$keg['id_agenda']]['status'] ?? null;
                $waktu = $val[$keg['id_agenda']]['waktu'] ?? '';
                $rgb = '808080';
                
                if ($status === 'TEPAT WAKTU') {
                    $rgb = '69aa46';
                    $jml_tepat++;
                } elseif ($status === 'TERLAMBAT') {
                    $rgb = 'f50000';
                    $jml_lambat++;
                }
                $cellVal = format_date($waktu,3);
                $sheet->setCellValueByColumnAndRow($col, $row, $cellVal);
                
                $style = $sheet->getStyleByColumnAndRow($col, $row);
                $style->getAlignment()->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $style->getFont()->getColor()->setRGB($rgb);
                
                $col++;
            }
            $sheet->setCellValueByColumnAndRow($col,$row,$jml_tepat);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row,$jml_lambat);
            
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
    private $rules_export = [
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
    private $rules = [
        [
            'field' => 'agenda',
            'label' => 'Agenda Kegiatan',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'lokasi',
            'label' => 'Lokasi',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'latitude',
            'label' => 'Latitude',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'longitude',
            'label' => 'Longitude',
            'rules' => 'required|trim|xss_clean'
        ],[
            'field' => 'foto',
            'label' => 'Foto',
            'rules' => 'required'
        ]
    ];
}
