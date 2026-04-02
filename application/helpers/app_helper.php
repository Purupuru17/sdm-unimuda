<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//MENU
if (!function_exists('menu_active')) {

    function menu_active($module) {
        $CI = & get_instance();

        $menu = $CI->uri->segment(1);
        $str = substr($module, 0, strpos($module, '/'));
        $status = "";
        if (strtolower($str) == $menu) {
            $status = "open active";
        }
        return $status;
    }
}
if (!function_exists('sub_active')) {

    function sub_active($module) {
        $CI = & get_instance();

        $menu = $CI->uri->segment(1);
        $sub = $CI->uri->segment(2);
        $sub .= empty(element('q', $_GET)) ? '':'?q='. strtolower(element('q', $_GET));
        $text = strtolower(str_replace('/', '', $module));
        
        $status = "";
        if ($text == $menu . $sub || $text . '_do' == $menu . $sub) {
            $status = "active";
        }
        return $status;
    }
}
if (!function_exists('sidebar')) {
    function sidebar($data, $parrent, $module) {
        $str = '';
        if (isset($data[$parrent])) {
            // 
            foreach ($data[$parrent] as $value) {
                $child = sidebar($data, $value['id_menu'], $module);
                if ($child) {
                    $str .= '<li class="' . menu_active($value['module_menu']) . '">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon ' . $value['icon_menu'] . '"></i>
                            <span class="menu-text">' . $value['nama_menu'] . '</span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">' . $child . '</ul>
                    </li>';
                }else{
                    $str .= '<li class="' . sub_active($value['module_menu']) . '">
                        <a href="' . site_url($module . $value['module_menu']) . '">
                            <i class="orange ' . $value['icon_menu'] . '"></i>&nbsp;&nbsp;
                            <i class="menu-icon fa fa-caret-right"></i>'. $value['nama_menu'] .'</a>
                        <b class="arrow"></b>
                    </li>';
                }
            }
        }
        return $str;
    }
}
if (!function_exists('breadcrumb')) {
    
    function breadcrumb($breadcrumb, $meta = null) {
        if (isset($breadcrumb) && is_array($breadcrumb)) {

            $buffString = "";
            foreach ($breadcrumb as $values) {

                $title = strtoupper($values['title']);
                $url = $values['url'];

                $breadcrumContent = "";

                if ($url != "") {
                    $breadcrumContent = '<a href="' . $url . '">' . $title . '</a>';
                } else {
                    $breadcrumContent = $title;
                }
                $buffString .= '<li>' . $breadcrumContent . '</li>';
            }
            krsort($breadcrumb);
            $lastKey = key(array_slice($breadcrumb, -1, 1, true));
            $metaTitle = "";
            foreach ($breadcrumb as $index => $items) {
                $metaTitle .= strtoupper($items['title']);
                //if ($index !== array_key_last($breadcrumb)) {
                if ($index !== $lastKey) {
                        $metaTitle .= ' > ';
                }
            }
            return !empty($meta) ? $metaTitle : $buffString;
        }
    }
}
//HOME
if (!function_exists('navbar')) {
    
    function navbar($data, $parrent, $module) {
        $str = '';
        if (isset($data[$parrent])) {
            
            foreach ($data[$parrent] as $value) {
                $child = navbar($data, $value['id_nav'], $module);
                if ($child) {
                    $str .= '<li>
                                <a href="'. site_url($module . $value['url_nav']) .'">
                                <span>'. $value['judul_nav'] .'</span></a>
                                <ul>'.$child.'</ul>
                            </li>';
                }else{
                    if($value['link_nav'] == '1'){
                        $str .= '<li>
                                <a target="_blank" href="'. $value['url_nav'] .'">'. $value['judul_nav'] .'</a>
                            </li>';
                    }else{
                        $str .= '<li>
                                <a href="'. site_url($module . $value['url_nav']) .'">'. $value['judul_nav'] .'</a>
                            </li>';
                    }
                    
                }
            }
        }
        return $str;
    }
}
if (!function_exists('breadhome')) {
    
    function breadhome($breadcrumb) {
        if (isset($breadcrumb) && is_array($breadcrumb)) {

            $buffString = "";
            foreach ($breadcrumb as $values) {

                $title = $values['title'];
                $url = $values['url'];
                $active = '';

                $breadcrumContent = "";

                if ($url != "#") {
                    $breadcrumContent = '<a href="' . $url . '">' . $title . '<i class="fa fa-angle-right"></i></a>';
                }else {
                    $breadcrumContent = '<a href="#">' . $title . '</a>';
                    $active = 'active';
                }
                $buffString .= '<li class="'.$active.'">' . $breadcrumContent . '</li>';
            }
            return $buffString;
        }
    }
}
//APP
if (!function_exists('notif')) {

    function notif($type, $title, $message) {
        $alert = '<div class="alert alert-' . $type . '">' .
                '<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>' .
                '<strong>' . $title . ' ! </strong></br>' . $message . '<br />' .
            '</div>';

        return $alert;
    }
}
if (!function_exists('load_css')) {

    function load_css(array $array) {
        foreach ($array as $uri) {
            echo '<link rel="stylesheet" type="text/css" href="' . base_url($uri) . '" />';
        }
    }
}
if (!function_exists('load_js')) {

    function load_js(array $array, $async = FALSE) {
        foreach ($array as $uri) {
            if(!$async){
                echo '<script type="text/javascript"  src="' . base_url($uri) . '"></script>';
            }else{
                echo '<script async type="text/javascript"  src="' . base_url($uri) . '"></script>';
            }
        }
    }
}
if (!function_exists('encode')) {

    function encode($param, $url_safe = TRUE) {
        if(is_null($param) || $param == '' ){
            return '';
        }
        $secret_key = config_item('encryption_key');
        $secret_iv = config_item('encrypt_iv');
        $encrypt_method = config_item('encrypt_method');
        // hash
        $key = hash('sha256', $secret_key);
        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        //do the encryption given text/string/number
        $result = openssl_encrypt($param, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);
        
        if ($url_safe) {
            $output = strtr($output, array('+' => '.', '=' => '-', '/' => '~'));
        }
        return $output;
    }
}
if (!function_exists('decode')) {

    function decode($param, $url_safe = TRUE) {
        $secret_key = config_item('encryption_key');
        $secret_iv = config_item('encrypt_iv');
        $encrypt_method = config_item('encrypt_method');
        
        if ($url_safe){
            $param = strtr($param, array('.' => '+', '-' => '=', '~' => '/'));
        }
        // hash
        $key = hash('sha256', $secret_key);
        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        //do the decryption given text/string/number
        $output = openssl_decrypt(base64_decode($param), $encrypt_method, $key, 0, $iv);

        return $output == false ? null : $output;
    }
}
if (!function_exists('load_file')) {

    function load_file($src, $type = NULL) {
        $default_img = empty($type) ? 'private/no-img.jpg' : 'private/no-avatar.png';
        if (empty($src)) {
            return base_url($default_img);
        }
        if(!is_keyword($src, ['private','upload'])){
            $CI = &get_instance();
            $CI->load->library(array('s3'));
            return $CI->s3->url($src);
        }
        $full_path = FCPATH . $src;
        if (!file_exists($full_path)) {
            return base_url($default_img);
        }
        if ($type == 'base64') {
            $ext = pathinfo($full_path, PATHINFO_EXTENSION);
            $data = file_get_contents($full_path);
            $base64 = 'data:image/' . $ext . ';base64,' . base64_encode($data);
            return $base64;
        }
        return base_url($src);
    }

}
if (!function_exists('st_file')) {

    function st_file($src, $file = NULL) {
        if(empty($src)){
            return '<i class="bigger-120 fa fa-times red"></i>';
        }
        $download = '&nbsp; | &nbsp;<a class="bigger-120" href="'. htmlspecialchars(load_file($src)) .'" target="_blank"><i class="fa fa-download"></i></a>';
        if(!is_keyword($src, ['private','upload'])){
            $status = '<i class="bigger-120 fa fa-check-square-o green"></i>';
            $status .= is_null($file) ? '' : $download;
            return $status;
        }
        if (file_exists(FCPATH . $src)) {
            $status = '<i class="bigger-120 fa fa-check green"></i>';
            $status .= is_null($file) ? '' : $download;
            return $status;
        }
        return '<i class="bigger-120 fa fa-times red"></i>';
    }
}
if (!function_exists('delete_file')) {

    function delete_file($src) {
        if(empty($src)){
            return false;
        }
        if(!is_keyword($src, ['private','upload'])){
            try {
                $CI = &get_instance();
                $CI->load->library(array('s3'));
                
                $delete = $CI->s3->remove($src);
                if ($delete['@metadata']['statusCode'] === 204) {
                    return true;
                }
                return false;
            } catch (Exception $ex) {
                return false;
            }
        }
        if (file_exists(FCPATH . $src)) {
            try {
                unlink($src);
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
    }
}
if (!function_exists('ip_agent')) {

    function ip_agent() {
        $CI = &get_instance();
        $CI->load->library('user_agent');
        
        $ip = $CI->input->ip_address();
        $platform = $CI->agent->platform() ?: 'Unknown Platform';
        $browser = $CI->agent->browser();
        $version = $CI->agent->version();
        
        if ($CI->agent->is_robot()) {
            $device = 'Robot: ' . $CI->agent->robot();
        } elseif ($CI->agent->is_mobile()) {
            $device = 'Mobile: ' . $CI->agent->mobile();
        } elseif ($CI->agent->is_browser()) {
            $device = 'Desktop';
        } else {
            $device = 'Unknown: ' . $CI->agent->agent_string();
        }
        return "{$ip} | {$device} | {$platform} | {$browser} {$version}";
    }
}
if (!function_exists('log_queries')) {
    
    function log_queries() {
        $CI = &get_instance();
        $CI->db->save_queries = TRUE;
        $queries = $CI->db->queries;
        $times = $CI->db->query_times;
        
        if (empty($queries)) return;
        
        $router = $CI->router;
        $controller = $router->fetch_class();
        $method = $router->fetch_method();
        $uri = uri_string();
        
        $log_file = './log/query-log-' . date('d-m-Y') . '.txt';
        $log = '';
        $threshold = 2; // detik, misalnya 0.1s (100ms)

        foreach ($queries as $key => $query) {
            $time = $times[$key];

            if ($time >= $threshold) {
                $log .= "========== HEAVY QUERY (AJAX) ==========\n";
                $log .= 'Datetime    : ' . date('Y-m-d H:i:s') . "\n";
                $log .= "URI         : {$uri}\n";
                $log .= "Controller  : {$controller}::{$method}()\n";
                $log .= "Exec Time   : " . number_format($time, 4) . "s\n";
                $log .= "Query       : {$query}\n";
                $log .= "===============================\n\n";
            }
        }
        if (!empty($log)) {
            file_put_contents($log_file, $log, FILE_APPEND);
        }
    }
}
if (!function_exists('jsonResponse')) {

    function jsonResponse($output, $code = 200) {
        $CI = &get_instance();
        $ajax_request = $CI->input->is_ajax_request();
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        
        $is_allowed = true;
        if (!empty($origin)) {
            $host = parse_url($origin, PHP_URL_HOST);
            $is_allowed = in_array($host, APP_ALLOWED);
        }
        if (ENVIRONMENT === 'production') {
            if (!$is_allowed || !$ajax_request) {
                $CI->output->set_status_header(403)->set_output('Forbidden Access : '.$origin)->_display();
                exit();
            }
            $CI->output->set_header("Access-Control-Allow-Origin: $origin")
                ->set_header("Access-Control-Allow-Credentials: true");
        }
        log_queries();
        //output
        $CI->output
            ->set_status_header($code)
            ->set_content_type('application/json', 'utf-8')
            ->set_header('Cache-Control: no-store, no-cache, must-revalidate')
            ->set_output(json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit();
    }
}