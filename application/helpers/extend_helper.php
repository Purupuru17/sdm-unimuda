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
        $warning = array('PROSES');
        $info = array('VALID');
        $success = array('AKTIF','SELESAI','TEPAT WAKTU');
        $danger = array('TIDAK AKTIF','TERLAMBAT');
        
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
            case 'st_opsi':
                $val = array(
                    array('id' => '1', 'txt' => 'AKTIF'),array('id' => '0', 'txt' => 'TIDAK AKTIF')
                );
                break;
            case 'st_valid':
                $val = array(
                    'PENGAJUAN','VALID','TUNDA'
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
            case 'agama':
                $val = array(
                    'Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'
                );
                break;
            case 'jenis_pegawai':
                $val = ['DOSEN','PEGAWAI','PARTTIME'];
                break;
            case 'status_pegawai':
                $val = ['PNS','SWASTA','YAYASAN','KONTRAK'];
                break;
            case 'pangkat':
                $val = ['II/A','II/B','II/C','II/D',
                    'III/A','III/B','III/C','III/D', 'IV/A','IV/B','IV/C','IV/D', 'IV/E'
                    ];
                break;
            case 'fungsional':
                $val = ['TENAGA PENGAJAR','ASISTEN AHLI','LEKTOR','LEKTOR KEPALA','GURU BESAR'];
                break;
        }
        return $val;
    }
}