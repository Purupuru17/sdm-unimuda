<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_periode')) {

    function is_periode($periode) {
        if(empty($periode)){
            return null;
        }
        $tahun = substr($periode,0,4);
        $tipe = substr($periode,4,1);
        $semester = $tahun.'/'.($tahun + 1);
        switch ($tipe) {
            case '1':
                $semester .= ' Ganjil';
                break;
            case '2':
                $semester .= ' Genap';
                break;
            default:
                $semester .= ' Pendek';
                break;
        }
        return $semester;
    }
}
if (!function_exists('st_aktif')) {

    function st_aktif($value, $yes_no = null, $pay = null) {
        if(!is_null($yes_no)){
            return ($value == '1') ? '<span class="label label-success label-white">YA</span>' : '<span class="label label-danger label-white">TIDAK</span>';
        }
        $label = !is_null($pay) ? array('LUNAS','BELUM LUNAS') : array('AKTIF','TIDAK AKTIF');
        switch ($value) {
            case '1':
                $status = '<span class="label label-success arrowed-in-right arrowed">'.$label[0].'</span>';
                break;
            case '0':
                $status = '<span class="label label-danger arrowed-in-right arrowed">'.$label[1].'</span>';
                break;
            default:
                $status = '<span class="label label-default arrowed-in-right arrowed">PENDING</span>';
                break;
        }
        return $status;
    }
}
if (!function_exists('st_mhs')) {

    function st_mhs($value, $type = NULL) {
        $warning = array('LAINNYA','MUTASI','PROSES','SAKIT','PRAKTIKUM','CUTI');
        $info = array('LULUS','IZIN','OFFLINE','VALID','RPL-AJUAN');
        $success = array('AKTIF','HADIR','ONLINE','SELESAI','RPL-VALID');
        $danger = array('DIKELUARKAN','WAFAT','PUTUS SEKOLAH','MENGUNDURKAN DIRI',
            'HILANG','TIDAK AKTIF','TUNDA','PINDAH-PRODI');
        
        if (in_array($value, $info)) {
            $status = '<span class="label label-info arrowed-in-right arrowed">'.$value.'</span>';
        } else if (in_array($value, $warning)) {
            $status = '<span class="label label-warning arrowed-in-right arrowed">'.$value.'</span>';
        } else if (in_array($value, $success)) {
            $status = '<span class="label label-success arrowed-in-right arrowed">'.$value.'</span>';
        } else if (in_array($value, $danger)) {
            $status = '<span class="label label-danger arrowed-in-right arrowed">'.$value.'</span>';
        } else {
            $status = '<span class="label label-default arrowed-in-right arrowed">'.$value.'</span>';
        }
        return !empty($type) ? '<span class="label label-'.$type.' arrowed-in-right arrowed">'.$value.'</span>':$status;
    }

}
if (!function_exists('st_sinkron')) {

    function st_sinkron($value) {

        if ($value == '1') {
            $status = '<span><i class="ace-icon fa fa-check green bigger-110"></i></span>';
        } else if ($value == '2') {
            $status = '<span><i class="ace-icon fa fa-times red bigger-110"></i></span>';
        } else if (strlen($value) >= 10) {
            $status = '<span><i class="ace-icon fa fa-check green bigger-110"></i></span>';
        } else {
            $status = '<span><i class="ace-icon fa fa-question orange bigger-110"></i></span>';
        }
        return $status;
    }
}
if (!function_exists('st_soal')) {

    function st_soal($quiz, $is_done = false) {
        switch ($quiz['status_quiz']) {
            case '0': $val = 'btn-default';
                break;
            case '1': $val = 'btn-info';
                break;
            case '2': $val = 'btn-yellow';
                break;
            default : $val = '';
                break;
        }
        if($is_done){
            switch ($quiz['valid_quiz']) {
                case '0': $val = 'btn-danger';
                    break;
                case '1': $val = 'btn-success';
                    break;
                default : $val = '';
                    break;
            }
        }
        return $val;
    }
}
if (!function_exists('range_date')) {

    function range_date($check_date, $start_date, $end_date) {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $check_ts = strtotime($check_date);
        
        $diff = $end_ts - $check_ts;
        $jam   = floor($diff / (60 * 60));
        $menit = $diff - ( $jam * (60 * 60) );
        $detik = $diff % 60;
        
        if( ($check_ts >= $start_ts) && ($check_ts <= $end_ts) ){
            $st = TRUE;
            $rs = $jam .  ' Jam - ' . floor( $menit / 60 ) . ' Menit - ' . $detik . ' Detik' ;
            //$rs = $jam .  ':' . floor( $menit / 60 ) . ':' . $detik;
        }else if($check_ts < $start_ts){
            $st = FALSE;
            $rs = 'Sesi Ini Belum Dimulai';
        }else{
            $st = FALSE;
            $rs = 'Sesi Ini Telah Berakhir';
        }
        return [ 'rs' => $rs, 'st' => $st ];
    }
}
if (!function_exists('eyd_text')) {
    
    function eyd_text($text) {
        $is_remove = array('*','amp;');
        $is_clean = str_replace($is_remove, '', $text);
        $sentence = str_replace('&', 'dan', $is_clean);
        
        $words = preg_split('/(\b|\s|(?=[^\x20-\x7E])|\(\s+|\s+\))/', $sentence, -1, PREG_SPLIT_DELIM_CAPTURE);
        $lower = array('dan','di','oleh','pada','for','at','and','dalam','in','of','to','terhadap','ke');
        $upper = array('pkn','plsbt','pkhs','kpm','kkn','tik','sd','ipa','ips','aik','i','ii','iii','iv','v',
            'aud','paud','ccu','tefl','uu','uud','pkl','sup','sim','sia','tk','otk','ptm','ict','mipa','pih','pthi',
            'esp','bi','bs','pbsi','hw','si');
        
        foreach ($words as &$word) {
            $is_lower = strtolower($word);
            if (in_array($is_lower, $lower)) {
                $word = $is_lower;
            }else if (in_array($is_lower, $upper)) {
                $word = strtoupper($is_lower);
            }else {
                $word = ucfirst($is_lower); 
            }
        }
        return implode("", $words);
    }
}
if (!function_exists('load_array')) {

    function load_array($type) {
        $val = array();
        switch ($type) {
            case 'st_mhs':
                $val = array(
                    'AKTIF','LULUS','LAINNYA','SELESAI PENDIDIKAN NON GELAR','MUTASI',
                    'DIKELUARKAN','MENINGGAL DUNIA','PUTUS STUDI','MENGAJUKAN PENGUNDURAN DIRI','HILANG','TIDAK AKTIF'
                );
                break;
            case 'jenis_mhs':
                $val = array(
                    'PESERTA DIDIK BARU','PINDAHAN','ALIH JENJANG','PINDAHAN ALIH BENTUK','PENDIDIKAN NON GELAR (COURSE)',
                    'RPL PEROLEHAN SKS ','RPL TRANSFER SKS','FAST TRACK', 'MENGULANG', 'AKSELERASI'
                );
                break;
            case 'st_akm':
                $val = array(
                    array('id' => 'A', 'txt' => 'AKTIF'), array('id' => 'M', 'txt' => 'KAMPUS MERDEKA'),
                    array('id' => 'N', 'txt' => 'NON-AKTIF'), array('id' => 'U', 'txt' => 'MENUNGGU UJIAN'),
                    array('id' => 'C', 'txt' => 'CUTI'),array('id' => 'G', 'txt' => 'DOUBLE DEGREE'),
                );
                break;
            case 'st_keluar':
                $val = [
                    ['id' => 1, 'txt' => 'LULUS'], ['id' => 2, 'txt' => 'MUTASI'],
                    ['id' => 3, 'txt' => 'DIKELUARKAN'], ['id' => 4, 'txt' => 'MENGAJUKAN PENGUNDURAN DIRI'],
                    ['id' => 5, 'txt' => 'PUTUS STUDI'], ['id' => 6, 'txt' => 'MENINGGAL DUNIA'],
                    ['id' => '0', 'txt' => 'SELESAI PENDIDIKAN NON GELAR']
                ];
                break;
            case 'st_aktivitas':
                $val = [
                    ['id' => 1, 'txt' => 'LAPORAN AKHIR STUDI'], ['id' => 2, 'txt' => 'TUGAS AKHIR'],
                    ['id' => 3, 'txt' => 'TESIS'], ['id' => 4, 'txt' => 'DISERTASI'],
                    ['id' => 5, 'txt' => 'KULIAH KERJA NYATA'], ['id' => 6, 'txt' => 'KERJA PRAKTEK/PKL'],
                    ['id' => 7, 'txt' => 'BIMBINGAN AKADEMIS'], ['id' => 10, 'txt' => 'AKTIVITAS KEMAHASISWAAN'],
                    ['id' => 11, 'txt' => 'PROGRAM KREATIVITAS MAHASISWA'], ['id' => 12, 'txt' => 'KOMPETISI'],
                    ['id' => 13, 'txt' => 'MAGANG/PRAKTIK KERJA'], ['id' => 14, 'txt' => 'ASISTENSI MENGAJAR DI SATUAN PENDIDIKAN'],
                    ['id' => 15, 'txt' => 'PENELITIAN/RISET'], ['id' => 16, 'txt' => 'PROYEK KEMANUSIAAN'],
                    ['id' => 17, 'txt' => 'KEGIATAN WIRAUSAHA'], ['id' => 18, 'txt' => 'STUDI/PROYEK INDEPENDEN'],
                    ['id' => 19, 'txt' => 'MEMBANGUN DESA/KULIAH KERJA NYATA TEMATIK'], ['id' => 20, 'txt' => 'BELA NEGARA'],
                    ['id' => 21, 'txt' => 'PERTUKARAN PELAJAR'], ['id' => 22, 'txt' => 'SKRIPSI'],
                    ['id' => 23, 'txt' => 'KEGIATAN PENELITIAN REGULER'], ['id' => 24, 'txt' => 'PEMBELAJARAN MANDIRI']
                ];
                break;
            case 'st_opsi':
                $val = array(
                    array('id' => '1', 'txt' => 'AKTIF'),array('id' => '0', 'txt' => 'TIDAK AKTIF')
                );
                break;
            case 'tipe_bayar':
                $val = array(
                    array('id' => '0', 'txt' => 'TRANSFER'),array('id' => '1', 'txt' => 'BEASISWA'),
                    array('id' => '2', 'txt' => 'VIRTUAL ACCOUNT'), array('id' => '3', 'txt' => 'PMB (VIRTUAL ACCOUNT)')
                );
                break;
            case 'st_calon':
                $val = array(
                    'SEMINAR','UJIAN','YUDISIUM','WISUDA'
                );
                break;
            case 'st_valid':
                $val = array(
                    'PENGAJUAN','VALID','TUNDA'
                );
                break;
            case 'st_cuti':
                $val = array(
                    'CUTI','PINDAH-PRODI','RPL-AJUAN','RPL-VALID'
                );
                break;
            case 'tahun':
                $awal = intval(date('Y'));
                for($i = 2017; $i <= $awal + 1; $i++ ){
                    $val[] = $i;
                }
                break;
            case 'status':
                $val = array(
                    'PENDING', 'PROSES', 'VALID' ,'SELESAI', 'AKTIF', 'TIDAK AKTIF'
                );
                break;
            case 'nilai':
                $val = array(
                    array('huruf'=>'A','angka'=>4),array('huruf'=>'A-','angka'=>3.75),array('huruf'=>'AB','angka'=>3.5),
                    array('huruf'=>'B+','angka'=>3.25),array('huruf'=>'B','angka'=>3),array('huruf'=>'B-','angka'=>2.75),
                    array('huruf'=>'BC','angka'=>2.5),array('huruf'=>'C+','angka'=>2.25),array('huruf'=>'C','angka'=>2),
                    array('huruf'=>'C-','angka'=>1.75),array('huruf'=>'CD','angka'=>1.5),array('huruf'=>'D+','angka'=>1.25),
                    array('huruf'=>'D','angka'=>1),array('huruf'=>'E','angka'=>0)
                );
                break;
            case 'mbkm':
                $val = array(
                    'INTERNAL','KAMPUS MENGAJAR','PMM INBOUND', 'PMM OUTBOUND','MAGANG','STUDI INDEPENDEN','WIRAUSAHA','IISMA'
                );
                break;
            case 'tempat':
                $val = array(
                    'Sekolah Negeri','Sekolah Swasta','Sekolah Muhammadiyah',
                    'Kecamatan','Kelurahan','Kampung','Desa','Perusahaan','Dinas'
                );
                break;
            case 'agama':
                $val = array(
                    'Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'
                );
                break;
            case 'jenis_biaya':
                $val = [
                    ['id' => '1', 'txt' => 'MANDIRI'],['id' => '2', 'txt' => 'BEASISWA TIDAK PENUH'],
                    ['id' => '3', 'txt' => 'BEASISWA PENUH']
                ];
                break;
            case 'bank':
                $val = ['BNI','MUAMALAT','BTN','BRI'];
                break;
            case 'mode_jurnal':
                $val = ['ONLINE','OFFLINE','PRAKTIKUM'];
                break;
            case 'jenis_kelas':
                $val = ['WAJIB','PILIHAN','KHUSUS'];
                break;
            case 'lingkup_kelas':
                $val = [['id' => 1, 'txt' => 'Internal'],['id' => 2, 'txt' => 'External'],['id' => 3, 'txt' => 'Campuran']];
                break;
            case 'mode_kelas':
                $val = [['id' => 'O', 'txt' => 'Online'],['id' => 'F', 'txt' => 'Offline'],['id' => 'M', 'txt' => 'Campuran']];
                break;
            case 'evaluasi_kelas':
                $val = [['id' => 1, 'txt' => 'Evaluasi Akademik'],['id' => 2, 'txt' => 'Aktivitas Partisipatif'],
                    ['id' => 3, 'txt' => 'Hasil Proyek'], ['id' => 4, 'txt' => 'Kognitif/ Pengetahuan']];
                break;
        }
        return $val;
    }
}