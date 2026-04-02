<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('rupiah')) {

    function rupiah($angka, $format = NULL) {
        $number = number_format($angka, 0, ',', '.');
        
        return is_null($format) ? "Rp " . $number : $number ;
    }
}
if (!function_exists('angka')) {

    function angka($string, $format = NULL) {
        if(is_null($format)){
            return preg_replace("/[^0-9]/", "", $string);//str_replace('.', '', $string);
        }
    }
}
if (!function_exists('ctk')) {

    function ctk($string, $format = NULL) {

        $hasil = strip_tags(html_entity_decode($string));
        if(!is_null($format)){
            $hasil = htmlentities($string, ENT_QUOTES); 
        }
        return $hasil;
    }
}
if (!function_exists('filter')) {

    function filter($string) {
        return preg_replace("/[^A-Za-z0-9.!?\s]/","",$string);
    }
}
if (!function_exists('limit_text')) {
    
    function limit_text($text, $limit) {
        if (mb_strlen($text) > $limit) {
            return ctk(mb_substr($text, 0, $limit)). '...';
        }
        return ctk($text);
    }
}
if (!function_exists('is_keyword')) {
    
    function is_keyword($kalimat, $keyword = []) {
        foreach ($keyword as $kata) {
            if (stripos($kalimat, $kata) !== false) {
                return true;
            }
        }
        return false;
    }
}
if (!function_exists('format_date')) {

    function format_date($tanggal, $format = NULL) {
        if (empty($tanggal) || $tanggal === '0000-00-00') {
            return NULL;
        }
        $get = date_create($tanggal);
        $day = format_day(date_format($get, 'N'));
        $date = date_format($get, 'd');
        $month = format_month(date_format($get, 'n'));
        $year = date_format($get, 'Y');
        
        $output = '';
        if (is_null($format)) {
            $output = $day . ', ' . $date . ' ' . $month . ' ' . $year;
        } else if ($format == 0) {
            $year = date_format($get, 'Y | H:i a');
            $output = $day . ', ' . $date . ' ' . $month . ' ' . $year;
        } else if ($format == 1) {
            $output = $date . ' ' . $month . ' ' . $year;
        } else if ($format == 2) {
            $year = date_format($get, 'Y | H:i a');
            $output = $date . ' ' . $month . ' ' . $year;
        } else {
            $output = date_format($get, 'H:i a');
        }
        return $output;
    }

}
if (!function_exists('format_month')) {

    function format_month($month) {
        switch ($month) {
            case 1 : $bulan = "Januari";
                break;
            case 2 : $bulan = "Februari";
                break;
            case 3 : $bulan = "Maret";
                break;
            case 4 : $bulan = "April";
                break;
            case 5 : $bulan = "Mei";
                break;
            case 6 : $bulan = "Juni";
                break;
            case 7 : $bulan = "Juli";
                break;
            case 8 : $bulan = "Agustus";
                break;
            case 9 : $bulan = "September";
                break;
            case 10 : $bulan = "Oktober";
                break;
            case 11 : $bulan = "November";
                break;
            case 12 : $bulan = "Desember";
                break;
            default : $bulan = "";
                break;
        }
        return $bulan;
    }

}
if (!function_exists('format_day')) {

    function format_day($day) {
        switch ($day) {
            case 1 : $hari = "Senin";
                break;
            case 2 : $hari = "Selasa";
                break;
            case 3 : $hari = "Rabu";
                break;
            case 4 : $hari = "Kamis";
                break;
            case 5 : $hari = "Jumat";
                break;
            case 6 : $hari = "Sabtu";
                break;
            case 7 : $hari = "Minggu";
                break;
            default : $hari = "";
                break;
        }
        return $hari;
    }

}
if (!function_exists('selisih_wkt')) {

    function selisih_wkt($tgl) {
        $awal = date_create($tgl);
        $akhir = date_create();
        $diff = date_diff($awal, $akhir);

        if ($diff->y != 0) {
            $val = $diff->y . ' tahun yang lalu';
        } else if ($diff->m != 0) {
            $val = $diff->m . ' bulan yang lalu';
        } else if ($diff->d != 0) {
            $val = $diff->d . ' hari yang lalu | ' . date_format($awal, 'H:i a');
        } else if ($diff->h != 0) {
            $val = $diff->h . ' jam yang lalu';
        } else if ($diff->i != 0) {
            $val = $diff->i . ' menit yang lalu';
        } else if ($diff->s != 0) {
            $val = ' Beberapa detik yang lalu';
        } else {
            $val = format_date($tgl, 0);
        }
        return $val;
    }
}
if (!function_exists('is_online')) {

    function is_online($tgl) {        
        $awal  = strtotime($tgl);
        $akhir = strtotime(date('Y-m-d H:i:s'));
        $diff  = $akhir - $awal;
        
        $online = '<span class="label label-success arrowed-in-right">
                    <i class="ace-icon fa fa-circle smaller-80 align-middle"></i>
                    online
                </span>';
        $offline = '<span class="label label-grey arrowed-in-right">
                    '. selisih_wkt($tgl) .'
                </span>';
        return ($diff < 12) ? $online : $offline; 
        
    }
}
if (!function_exists('is_beetwen')) {

    function is_beetwen($from, $till, $input) {
        $f = DateTime::createFromFormat('!H:i', $from);
        $t = DateTime::createFromFormat('!H:i', $till);
        $i = DateTime::createFromFormat('!H:i', $input);
        if ($f > $t) {
            $t->modify('+1 day');
        }
        return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
    }
}
if (!function_exists('element')) {

    function element($item, $array = [], $default = null) {
        //adding trim - updated by budy
        if (!is_array($array)) {
            return null;
        }

        if (array_key_exists($item, $array)) {
            $return = $array[$item];
            //if not array, trim it
            if (!is_array($return)) {
                $return = trim($return);
            }
            return $return;
        } else {
            return (!is_array($default)) ? trim($default) : $default;
        }
    }
}
if (!function_exists('array_find')) {

    function array_find($item, $array = array()) {
        if (!is_array($array)) {
            return null;
        }
        $id = (count($array) == 14) ? 'huruf' : 'id';
        $txt = (count($array) == 14) ? 'angka' : 'txt';
        $key = array_search($item, array_column($array, $id));
        
        return (is_int($key)) ? $array[$key][$txt] : null;
    }
}
if (!function_exists('dash')) {

    function dash($string) {
        $clean = str_replace(["-" , "-"], ' ',$string);
        return ucwords($clean);
    }
}
if (!function_exists('random_string')) {

    function random_string($type = 'alnum', $len = 8) {
        switch ($type) {
            case 'basic':
                return mt_rand();
            case 'alnum':
            case 'capnum':
            case 'numeric':
            case 'nozero':
            case 'alpha':
                switch ($type) {
                    case 'alpha':
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum':
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'capnum':
                        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $pool = '0123456789';
                        break;
                    case 'nozero':
                        $custom = (string) uniqid(mt_rand());
                        $custom = ltrim($custom, '0');
                        $pool = '123456789' . $custom;
                        break;
                }
                return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
            case 'unique': 
                return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    // 32 bits for "time_low"
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    // 16 bits for "time_mid"
                    mt_rand(0, 0xffff),
                    // 16 bits for "time_hi_and_version",
                    // four most significant bits holds version number 4
                    mt_rand(0, 0x0fff) | 0x4000,
                    // 16 bits, 8 bits for "clk_seq_hi_res",
                    // 8 bits for "clk_seq_low",
                    // two most significant bits holds zero and one for variant DCE1.1
                    mt_rand(0, 0x3fff) | 0x8000,
                    // 48 bits for "node"
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                );
            case 'md5':
                return md5(uniqid(mt_rand()));
            case 'encrypt': // todo: remove in 3.1+
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
        }
    }
}