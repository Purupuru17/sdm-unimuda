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
if (!function_exists('st_label')) {

    function st_label($value, $type = NULL) {
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
            case 'tahun':
                $awal = intval(date('Y'));
                for($i = 2017; $i <= $awal + 1; $i++ ){
                    $val[] = $i;
                }
                break;
            case 'status':
                $val = ['PENDING', 'PROSES', 'VALID' ,'SELESAI', 'AKTIF', 'TIDAK AKTIF'];
                break;
            case 'agama':
                $val = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'];
                break;
            case 'jenis_pegawai':
                $val = ['DOSEN','TENDIK','PARTTIME'];
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