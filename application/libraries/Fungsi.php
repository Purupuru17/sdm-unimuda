<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class Fungsi {
    
    protected $ci;
    
    function __construct() {
        $this->ci = get_instance();
    }
    function Validation($rules, $delimiter = NULL) {
        $this->ci->load->library(array('form_validation'));
        
        $this->ci->form_validation->set_rules($rules);
        $this->ci->form_validation->set_message('required', 'Kolom %s harus diisi.');
        $this->ci->form_validation->set_message('min_length', 'Kolom %s harus minimal %s karakter.');
        $this->ci->form_validation->set_message('valid_email', 'Format %s tidak sesuai.');
        $this->ci->form_validation->set_message('numeric', 'Kolom %s harus berupa angka.');
        $this->ci->form_validation->set_message('is_natural', 'Kolom %s harus berupa angka.');
        $this->ci->form_validation->set_message('xss_clean', 'Programer yang baik tidak akan bertindak iseng dengan programer lainnya.');
        $this->ci->form_validation->set_error_delimiters('<div class="">', '</div>');
        if(!is_null($delimiter)){
            $this->ci->form_validation->set_error_delimiters('', '<br/>');
        }
        if ($this->ci->form_validation->run() == FALSE) {
            if(is_null($delimiter)){
                $this->ci->session->set_flashdata('notif', notif('danger', 'Peringatan', validation_errors()));
            }
            return FALSE;
        }else{
            return TRUE;
        }
    }
    //upload image
    function ImgUpload($post, $name, $path, $width = 0, $ratio = FALSE, $height = 0){
        $this->ci->load->library(array('upload','image_lib'));
        
        $file = $_FILES[$post]['tmp_name'];
        if(empty($file)){
            $this->ci->session->set_flashdata('notif', notif('danger', 'Peringatan', 'File tidak dapat ditemukan'));
            return NULL;
        }
        list($get_width, $get_height) = getimagesize($file);
        if($get_width < $width){
            $width = $get_width;
        }
        $cfg['file_name'] = $name.'-'.$get_width.'-'.$get_height;
        $cfg['upload_path'] = './' . $path;
        $cfg['allowed_types'] = $this->ci->config->item('app.allowed_img');
        $cfg['max_size'] = $this->ci->config->item('app.max_img');
        //Upload Image
        $this->ci->upload->initialize($cfg);
        if($this->ci->upload->do_upload($post)) {
            $upload = $this->ci->upload->data('file_name');
            //Compress Config
            $resize['image_library'] = 'gd2';
            $resize['source_image'] = './' . $path . $upload;
            $resize['create_thumb'] = FALSE;
            $resize['maintain_ratio'] = ($ratio) ? TRUE : FALSE;
            $resize['quality'] = '100%';
            $resize['width'] = ($width == 0) ? $this->ci->config->item('app.resize') : $width;
            $resize['height'] = ($height == 0) ? $width : $height;
            $resize['new_image']= './' . $path . $upload;
            //Compress Image
            $this->ci->image_lib->initialize($resize);
            if($this->ci->image_lib->resize()){
                return $path . $upload;
            }else{
                (is_file($path . $upload)) ? unlink($path . $upload) : '';    
                $this->ci->session->set_flashdata('notif', notif('danger', 
                        'Peringatan Foto Resize', strip_tags($this->ci->image_lib->display_errors())));
                return NULL;
            }
        }else{
            $this->ci->session->set_flashdata('notif', notif('danger', 
                    'Peringatan Foto', strip_tags($this->ci->upload->display_errors())));
            return NULL;
        }
    }
    function PdfGenerate($html, $filename='', $attach = 0 ,$paper = 'A4', $orientation = 'portrait', $stream = TRUE) {
        $tmp = sys_get_temp_dir();
        if (ob_get_level()) {
            ob_end_clean();
        }
        $options = new Options();
        $options->set('logOutputFile', '');
        $options->set('isRemoteEnabled', TRUE);
        $options->set('isFontSubsettingEnabled', TRUE);
        $options->set('defaultMediaType', 'all');
        
        $options->set('fontDir', $tmp);
        $options->set('fontCache', $tmp);
        $options->set('tempDir', $tmp);
        $options->set('chroot', $tmp);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            $dompdf->stream($filename.".pdf", array("Attachment" => $attach));
        } else {
            return $dompdf->output();
        }
    }
    function SetPaging($url = NULL, $rows = NULL, $limit = NULL) {
        $this->ci->load->library(array('pagination'));
        
        switch (APP_THEME) {
            case 'digiqole':
                $config['first_tag_open'] = '<li>';
                $config['first_link'] = '<i class="fa fa-angle-double-left" aria-hidden="true"></i>';
                $config['first_tag_close'] = '</li>';

                $config['last_tag_open'] = '<li>';
                $config['last_link'] = '<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
                $config['last_tag_close'] = '</li>';

                $config['prev_tag_open'] = '<li>';
                $config['prev_link'] = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
                $config['prev_tag_close'] = '</li>';
                $config['next_tag_open'] = '<li>';
                $config['next_link'] = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
                $config['next_tag_close'] = '</li>';

                $config['cur_tag_open'] = '<li><a href="#" class="active">';
                $config['cur_tag_close'] = '</a></li>';
                $config['num_tag_open'] = '<li>';
                $config['num_tag_close'] = '</li>';
                break;

            default:
                $config['first_tag_open'] = '<div class="prev page-numbers">';
                $config['first_link'] = '<<';
                $config['first_tag_close'] = '</div>';

                $config['last_tag_open'] = '<div class="next page-numbers">';
                $config['last_link'] = '>>';
                $config['last_tag_close'] = '</div>';

                $config['prev_tag_open'] = '<div class="prev page-numbers">';
                $config['prev_tag_close'] = '</div>';
                $config['next_tag_open'] = '<div class="next page-numbers">';
                $config['next_tag_close'] = '</div>';

                $config['cur_tag_open'] = '<div class="page-numbers current">';
                $config['cur_tag_close'] = '</div>';
                $config['num_tag_open'] = '<div class="page-numbers">';
                $config['num_tag_close'] = '</div>';
                break;
        }
        $config['attributes'] = array('class' => '');
        $config['base_url'] = $url;
        $config['total_rows'] = ($rows > 0) ? $rows : 0;
        $config['per_page'] = ($limit > 0) ? $limit : 0;
        $config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['query_string_segment'] = 'pg';
        
        $this->ci->pagination->initialize($config);
        return $this->ci->pagination->create_links();
    }
    function SendEmail($type, $from_email, $data, $to_email, $subject = '', $reply = '') {
        $from = array();
        $config = array();
        
        switch($from_email) {
            case 'admin' :
                $from = array('user' => 'admin@koputoko.com', 'pass' => '7c7FwNELKkjs');
                break;
            case 'cs' :
                $from = array('user' => 'cs@koputoko.com', 'pass' => '41Nkx86QEfyG');
                break;
            case 'no':
                $from = array('user' => 'no-reply@koputoko.com', 'pass' => 'GGH97lsGLmMhIl');
                break;
            default:
                $from = array('user' => 'galihbayu17@gmail.com', 'pass' => 'mrkcydtbahiggipj');
                break;
        }
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "smtp.gmail.com";
        $config['smtp_crypto'] = "ssl";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = $from['user'];
        $config['smtp_pass'] = $from['pass'];
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";
        $config['wordwrap'] = TRUE;
        
        $this->ci->load->library('email');
        $this->ci->email->initialize($config);
        $this->ci->email->from($from['user'], APP_NAME);
        if(!empty($reply)){
            $this->ci->email->reply_to($reply);
        }
        $this->ci->email->to($to_email);
        $this->ci->email->subject($subject);
        $this->ci->email->message($this->ci->load->view('email/' . $type . '-html', $data, TRUE));
        $this->ci->email->set_alt_message($this->ci->load->view('email/' . $type . '-txt', $data, TRUE));
        
        $send = ($this->ci->email->send()) ? TRUE : FALSE;
        return [
            'rs' => $send,
            'msg' => 'Email belum terkirim. Silahkan coba lagi'//$this->email->print_debugger()
        ];
    }
}