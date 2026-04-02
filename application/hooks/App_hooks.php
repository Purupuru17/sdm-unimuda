<?php defined('BASEPATH') OR exit('No direct script access allowed');

class App_hooks {

    private $ci;

    function __construct() {
        $this->ci = &get_instance();
    }

    public function redirect_ssl() {
        $class = $this->ci->router->fetch_class();
        $exclude = array(''); // Tambahkan controller yang tidak perlu HTTPS

        if (ENVIRONMENT === 'production' && php_sapi_name() !== 'cli') {
            // Deteksi apakah sudah HTTPS
            $is_https = (
                (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            );

            if (!in_array($class, $exclude)) {
                // Paksa redirect ke HTTPS
                $this->ci->config->set_item('base_url', str_replace('http://', 'https://', $this->ci->config->item('base_url')));
                if (!$is_https) {
                    redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 'location', 301);
                    exit;
                }
            } else {
                // Paksa redirect ke HTTP jika di-exclude
                $this->ci->config->set_item('base_url', str_replace('https://', 'http://', $this->ci->config->item('base_url')));
                if ($is_https) {
                    redirect('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 'location', 301);
                    exit;
                }
            }
        }
    }

    public function is_compress() {
        ini_set("pcre.recursion_limit", "16777");
        $buffer = $this->ci->output->get_output();
        // BUFFER 1
        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';
        $new_buffer = preg_replace($re, " ", $buffer);

        if (ENVIRONMENT === 'production') {
            $buffer = $new_buffer;
        }
        $this->ci->output->set_output($buffer);
        $this->ci->output->_display();
    }

    public function is_offline() {
        if (APP_STATUS == 0) {
            include (APPPATH . 'views/errors/html/error_offline.php');
            die();
        }
    }
    
    public function log_queries() {
        $this->ci->db->save_queries = TRUE;
        $queries = $this->ci->db->queries;
        $times = $this->ci->db->query_times;
        
        if (empty($queries)) return;
        
        $router = $this->ci->router;
        $controller = $router->fetch_class();
        $method = $router->fetch_method();
        $uri = uri_string();
        
        $log_file = './log/query-log-' . date('d-m-Y') . '.txt';
        $log = '';
        $threshold = 2; // detik, misalnya 0.1s (100ms)

        foreach ($queries as $key => $query) {
            $time = $times[$key];

            if ($time >= $threshold) {
                $log .= "========== HEAVY QUERY (HOOK) ==========\n";
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
    
    public function log_server() {
        $method = $_SERVER['REQUEST_METHOD'] ?? '';
        $uri    = $_SERVER['REQUEST_URI'] ?? '';
        $excluded_keywords = ['login', 'auth', 'register', 'konten', 'sistem', 'home', 'api', 'snap'];

        $is_uri_allowed = true;
        foreach ($excluded_keywords as $word) {
            if (stripos($uri, $word) !== false) {
                $is_uri_allowed = false;
                break;
            }
        }
        if ($is_uri_allowed && in_array($method, ['POST', 'PUT'])) {
            $post = [
                'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
                'server' => $_SERVER['REQUEST_URI'] ?? '',
                'raw'    => file_get_contents('php://input'),
                'file'   => $_FILES
            ];

            $log_file    = './log/server-log-' . date('d-m-Y') . '.txt';
            $log_message = "[" . date('Y-m-d H:i:s') . "] " . json_encode($post) . "\n\n";
            
            if (empty($_SESSION['id'])) {
                file_put_contents($log_file, $log_message, FILE_APPEND);
            }
        }
    }

}
