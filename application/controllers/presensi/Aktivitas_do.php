<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Aktivitas_do extends KZ_Controller {

    private $module = 'presensi/aktivitas';
    private $module_do = 'presensi/aktivitas_do'; 
    private $url_route = array('id', 'source', 'type');
    private $path = 'upload/presensi/';
    
    function __construct() {
        parent::__construct();
        $this->load->model(['m_presensi']);
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
            }
        }
    }
    function _checkLocation()
    {
        $this->load->model(['m_lokasi']);
        
        $userLat = $this->input->post('latitude');
        $userLng = $this->input->post('longitude');
        
        if(empty($userLat) || empty($userLng)){
            jsonResponse(['status' => false, 'msg' => 'GPS tidak ditemukan. Silahkan coba ulang.']);
        }
        $lokasi = $this->m_lokasi->all(['jenis_lokasi' => '1', 'status_lokasi' => '1']);
        
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
        $this->load->model(['m_pegawai','m_kerja','m_libur']);
        
        $status = $this->input->post('status');
        $lokasi = $this->input->post('lokasi');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $foto = $this->input->post('foto');
        
        // CEK AKUN
        $pegawai = $this->m_pegawai->get($this->pid);
        if(empty($pegawai)){
            jsonResponse(['status' => false, 'msg' => 'Pegawai tidak ditemukan']);
        }
        // CEK LIBUR
        $libur = $this->m_libur->get(['tgl_libur' => date('Y-m-d')]);
        if(!empty($libur)){
            jsonResponse(['status' => false, 'msg' => 'Hari Libur, '.$libur['catat_libur']]);
        }
        // CEK KERJA
        $kerja = $this->m_kerja->get(['hari_kerja' => date('N')]);
        if(empty($kerja)){
            jsonResponse(['status' => false, 'msg' => 'Diluar Hari Kerja']);
        }
        $masuk = $kerja['masuk_kerja'];
        $pulang = $kerja['pulang_kerja'];
        $limit = (int) $kerja['limit_kerja'];
        
        $now = new DateTime();
        $today = date('Y-m-d');

        $jamMasuk  = new DateTime("$today $masuk");
        $jamPulang = new DateTime("$today $pulang");
        $jamLimit = clone $jamMasuk;
        $jamLimit->modify("+{$limit} minutes");
        
        switch ($status) {
            case '1':
                // CEK AWAL
                if ($now < $jamMasuk) {
                    jsonResponse(['status' => false, 'msg' => 'Belum waktunya presensi masuk. Jam Masuk : '.format_date($masuk,4)]);
                }
                // CEK OVER
                if ($now > $jamPulang) {
                    jsonResponse(['status' => false, 'msg' => 'Presensi masuk sudah berakhir, telah melewati Waktu Kerja']);
                }
                // CEK TGL
                $cekTgl = $this->m_presensi->get(['pegawai_id' => $this->pid, 'tgl_presensi' => $today]);
                if(!empty($cekTgl)){
                    jsonResponse(['status' => false, 'msg' => 'Sudah presensi masuk sebelumnya']);
                }
                // STATUS LIMIT
                $is_status ='TEPAT WAKTU';
                $is_note = '';
                if ($now > $jamLimit) {
                    $diff = $jamLimit->diff($now);
                    $menitTelat = ($diff->h * 60) + $diff->i;
                    
                    if ($menitTelat > 0) {
                        $is_status = 'TERLAMBAT';
                        $is_note = $menitTelat.' Menit';
                    }
                }
                //SAVE
                $data['pegawai_id'] = $this->pid;
                $data['status_presensi'] = $is_status;
                $data['tgl_presensi'] = $today;
                $data['waktu_masuk'] = date('H:i:s');
                $data['lokasi_masuk'] = $lokasi;
                $data['latitude_masuk'] = $latitude;
                $data['longitude_masuk'] = $longitude;
                $data['catat_presensi'] = $is_note;
                $data['created_at'] = date('Y-m-d H:i:s');
                //UPLOAD
                $filename = url_title(date('Y m d H i s').' '.random_string('alnum', 4),'dash',TRUE); 
                $upload = $this->fungsi->imgUpBase64($foto, $filename, $this->path);
                if(empty($upload['data'])){
                    jsonResponse(['status' => false, 'msg' => $upload['msg']]);
                }
                $data['foto_masuk'] = $upload['data'];
                
                $query = $this->m_presensi->insert($data);
                if($query){
                    jsonResponse(['status' => true, 'msg' => 'Presensi masuk berhasil disimpan. Status : '.$is_status.' '.$is_note]);
                }else{
                    jsonResponse(['status' => false, 'msg' => 'Presensi gagal disimpan']);
                }
                break;
            
            case '2':
                //CEK PULANG
                if ($now < $jamPulang) {
                    jsonResponse(['status' => false, 
                        'msg' => 'Belum waktunya presensi pulang. Jam Pulang : '.format_date($pulang,4)]);
                }
                //CEK MASUK
                $cekMasuk = $this->m_presensi->get(['pegawai_id' => $this->pid, 'tgl_presensi' => $today, 
                    'waktu_masuk <>' => NULL, 'status_presensi !=' => ''
                ]);
                if(empty($cekMasuk)){
                    jsonResponse(['status' => false, 'msg' => 'Belum ada presensi masuk untuk hari ini']);
                }
                if(!empty($cekMasuk['waktu_pulang'])){
                    jsonResponse(['status' => false, 'msg' => 'Sudah presensi pulang sebelumnya']);
                }
                //SAVE
                $data['waktu_pulang'] = date('H:i:s');
                $data['lokasi_pulang'] = $lokasi;
                $data['latitude_pulang'] = $latitude;
                $data['longitude_pulang'] = $longitude;
                $data['update_at'] = $this->sessionname.' presensi pulang '.date('d-m-Y H:i:s');
                //UPLOAD
                $filename = url_title(date('Y m d H i s').' '.random_string('alnum', 4),'dash',TRUE);           
                $upload = $this->fungsi->imgUpBase64($foto, $filename, $this->path);
                if(empty($upload['data'])){
                    jsonResponse(['status' => false, 'msg' => $upload['msg']]);
                }
                $data['foto_pulang'] = $upload['data'];
                        
                $query = $this->m_presensi->update($cekMasuk['id_presensi'], $data);
                if($query){
                    jsonResponse(['status' => true, 'msg' => 'Terimakasih. Presensi pulang berhasil disimpan']);
                }else{
                    jsonResponse(['status' => false, 'msg' => 'Presensi gagal disimpan']);
                }
                break;

            default:
                jsonResponse(['status' => false, 'msg' => 'Not Found']);
                break;
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
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        // ================= PIVOT EXCEL =================
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('REKAP');
        $title = 'Presensi '.format_date($awal,1).' sd '.format_date($akhir,1);
             
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
            $sheet->setCellValueByColumnAndRow($col, $rowHeader2, date('d-m', strtotime($tgl)));
            $col++;
        }
        // Kolom terakhir (merge 2 baris)
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'TEPAT'."\n".'PULANG');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        $col++;
        
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'TERLAMBAT'."\n".'PULANG');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        $col++;
        
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'TEPAT'."\n".'MASUK');
        $sheet->mergeCellsByColumnAndRow($col, $rowHeader1, $col, $rowHeader2);
        $col++;
        
        $sheet->setCellValueByColumnAndRow($col, $rowHeader1, 'TERLAMBAT'."\n".'MASUK');
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
            $jml_tepat_plng = 0;
            $jml_lambat = 0;
            $jml_lambat_plng = 0;
            
            foreach ($pivot['periode'] as $tgl) {

                $status = $val[$tgl]['status'] ?? null;
                $masuk = $val[$tgl]['masuk'] ?? '';
                $pulang = $val[$tgl]['pulang'] ?? '';
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
                $cellVal = format_date($masuk,3) . ' - ' . format_date($pulang,3);
                $sheet->setCellValueByColumnAndRow($col, $row, $cellVal);
                
                $style = $sheet->getStyleByColumnAndRow($col, $row);
                $style->getAlignment()->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $style->getFont()->getColor()->setRGB($rgb);
                
                $col++;
            }
            $sheet->setCellValueByColumnAndRow($col,$row,$jml_tepat_plng);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row,$jml_lambat_plng);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row,$jml_tepat);
            $col++;
            $sheet->setCellValueByColumnAndRow($col,$row,$jml_lambat);
            $col++;
            
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
            'field' => 'status',
            'label' => 'Status',
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
