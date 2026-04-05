<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends KZ_Controller {
    
    private $module = 'home';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
    }
    function index() {
        empty($this->sessionid) ? redirect('login') : redirect('beranda');
        
        $this->data['module'] = $this->module;
        $this->load_home('h_home', $this->data);
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(3, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'list') {
            //TABLE
            if($routing_module['source'] == 'menu') {
                $this->_menu();
            } else if($routing_module['source'] == 'page') {
                $this->_page();
            } else if($routing_module['source'] == 'jenis') {
                $this->_jenis();
            } else if($routing_module['source'] == 'artikel') {
                $this->_artikel();
            } else if($routing_module['source'] == 'galeri') {
                $this->_galeri();
            } else if($routing_module['source'] == 'kutip') {
                $this->_kutipan();
            } else if($routing_module['source'] == 'visitor') {
                $this->_visitor();
            }
        }
    }
    function artikel($slug = NULL){
        if(empty($slug)){
            redirect('');
        }
        $this->load->model(array('m_artikel'));
        
        $where = [
            'a.slug_artikel' => $slug, 'a.status_artikel' => '1'
        ];
        $artikel = $this->m_artikel->getJenis($where, [], false);
        if(empty($artikel)){
            redirect('err_404');
        }
        $artikel['view_artikel'] = intval($artikel['view_artikel']) + 1; 
        $this->m_artikel->update($artikel['id_artikel'], array('view_artikel' => $artikel['view_artikel']));
        
        $this->data['detail'] = $artikel;
        $this->data['module'] = $this->module;
        $this->data['meta'] = array(
            'title' => ($artikel) ? $artikel['judul_artikel'] : NULL, 
            'description' => ($artikel) ? $artikel['isi_artikel'] : NULL,
            'thumbnail' => ($artikel) ? load_file($artikel['foto_artikel']) : NULL
        );
        $this->load_home('page/h_artikel', $this->data);
    }
    function pages($slug = NULL){
        if(is_null($slug)){
            redirect('');
        }
        $this->load->model(array('m_page'));
        
        $pages = $this->m_page->getSlug($slug);
        if(is_null($pages)){
            redirect('err_404');
        }
        $this->data['detail'] = $pages;
        $this->data['module'] = $this->module;
        $this->data['meta'] = array(
            'title' => ($pages) ? $pages['judul_page'] : NULL, 
            'description' => ($pages) ? $pages['isi_page'] : NULL,
            'thumbnail' => ($pages) ? load_file($pages['foto_page']) : NULL
        );
        $this->load_home('page/h_pages', $this->data);
    }
    function tag($slug = NULL) {
        if(is_null($slug)){
            redirect('');
        }
        $this->load->model(array('m_jenis','m_artikel'));
        $this->load->library(array('fungsi'));
        
        $param = $this->input->get(null, TRUE);
        $key = preg_replace('/[^a-zA-Z0-9\s]/u', '', trim(element('q', $param)));
        $search = ($key === '') ? null : $key;
        
        $page = max(1, (int) element('pg', $param, 1));
        $url = empty($search) ? current_url() . '?' : current_url() . '?q=' . $search;
        $limit = 10;
        $offset = ($page) ? ($page - 1) * $limit : 0;

        $title = 'Semua Kategori';
        $where['a.status_artikel'] = '1';
        if($slug != 'all'){
            $jenis = $this->m_jenis->getSlug($slug);
            if(is_null($jenis)){
                redirect('');
            }
            $where['j.slug_jenis'] = $slug;
            $title = $jenis['judul_jenis'];
        }
        $options = ['order' => 'update_artikel desc', 'limit' => $limit, 
            'offset' => $offset, 'like' => ['judul_artikel'], 'key' => $search];
        
        $data = $this->m_artikel->getJenis($where, $options);
        $count = $this->m_artikel->getJenis($where, ['like' => ['judul_artikel'], 'key' => $search])['rows'];
        
        $this->data['terbaru'] = $data;
        $this->data['module'] = $this->module;
        $this->data['title'] = $title ;
        $this->data['pagination'] = $this->fungsi->SetPaging($url, $count, $limit);

        $this->load_home('page/h_tag', $this->data);
    }
    function galeri($slug = NULL) {
        $this->load->model(array('m_galeri'));
        $this->load->library(array('fungsi'));
        
        if(!empty($slug)){
            
            $where = [
                'slug_galeri' => $slug, 'status_galeri' => '1'
            ];
            $detail = $this->m_galeri->get($where);
            if(empty($detail)){
                redirect('err_404');
            }
            $this->data['detail'] = $detail;
            $this->data['module'] = $this->module;
            $this->data['meta'] = array(
                'title' => ($detail) ? $detail['judul_galeri'] : NULL, 
                'description' => ($detail) ? $detail['isi_galeri'] : NULL,
                'thumbnail' => ($detail) ? load_file($detail['foto_galeri']) : NULL
            );
            $this->load_home('page/h_galeri_detail', $this->data);
            
        } else {
            
            $param = $this->input->get(null, TRUE);
            $page = max(1, (int) element('pg', $param, 1));
            $url = current_url() . '?';
            $limit = 15;
            $offset = ($page) ? ($page - 1) * $limit : 0;
            
            $where = ['status_galeri' => '1', 'jenis_galeri' => '0'];
            $options = ['order' => 'update_galeri desc', 'limit' => $limit, 'offset' => $offset];
            
            $data = $this->m_galeri->all($where, $options);
            $count = $this->m_galeri->all($where)['rows'];

            $this->data['galeri'] = $data;
            $this->data['module'] = $this->module;
            $this->data['pagination'] = $this->fungsi->SetPaging($url, $count, $limit);

            $this->load_home('page/h_galeri', $this->data);
        }
    }
    function sitemap() {
        $this->load->model(array('m_page','m_artikel'));
        
        $page = $this->m_page->getAll(null, 'desc');
        $artikel = $this->m_artikel->getJenis();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $xml .= '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . site_url() . '</loc>' . PHP_EOL;
        $xml .= '    <priority>1.00</priority>' . PHP_EOL;
        $xml .= '  </url>' . PHP_EOL;
        
        foreach ($page['data'] as $item) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . site_url('pages/' . $item['slug_page']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . date('Y-m-d', strtotime($item['update_page'])) . '</lastmod>' . PHP_EOL;
            $xml .= '    <priority>0.80</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }
        foreach ($artikel['data'] as $row) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . site_url('artikel/' . $row['slug_artikel']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . date('Y-m-d', strtotime($row['update_artikel'])) . '</lastmod>' . PHP_EOL;
            $xml .= '    <priority>0.80</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }
        $xml .= '</urlset>';
        
        $this->output->set_content_type('application/xml')->set_output($xml);
    }
    function err_404() {
        $this->data['breadcrumb'] = array( 
            array('title' => 'Halaman Tidak Ditemukan', 'url' => '#')
        );
        $this->load_view('errors/html/error_404', $this->data);
    }
    function err_module() {
        $this->data['breadcrumb'] = array( 
            array('title'=>'Gagal Akses Module', 'url'=>'#')
        );
        $this->load_view('errors/html/error_module', $this->data);
    }
    function _menu() {
        $this->load->model(array('m_nav'));
        
        $nav = $this->m_nav->getNav(array('status_nav' => '1'));
        $navbar = array();
        foreach ($nav['data'] as $row) {
            $row['url_nav'] = site_url($row['url_nav']);
            $navbar[] = $row;
        }
        jsonResponse(array('data' => $navbar, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _jenis() {
        $this->load->model(array('m_jenis'));
        
        $cat = $this->m_jenis->getGroup(NULL, 'RANDOM', 10);
        $category = array();
        foreach ($cat['data'] as $row) {
            $row['slug_jenis'] = site_url('tag/'.$row['slug_jenis']);
            $category[] = $row;
        }
        jsonResponse(array('data' => $category, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _page() {
        $this->load->model(array('m_page'));
        
        $slug = $this->input->post('slug');
        $order = $this->input->post('order');
        $limit = $this->input->post('limit');
        
        $where['status_page'] = '1';
        if ($slug != ''){
            $where['slug_page'] = $slug;
        }
        $result = $this->m_page->getAll($where, $order, $limit);
        $data = array();
        foreach ($result['data'] as $value) {
            $value['slug_page'] = site_url('pages/' . $value['slug_page']);
            $value['foto_page'] = load_file($value['foto_page']);
            $value['update_page'] = format_date($value['update_page'], 2);
            
            $data[] = $value;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _artikel() {
        $this->load->model(array('m_artikel'));
        
        $slug = $this->input->post('slug');
        $jenis = $this->input->post('jenis');
        $tipe = $this->input->post('tipe');
        $order = $this->input->post('order');
        $limit = (int) $this->input->post('limit');
        
        $short = 50;
        $where['a.status_artikel'] = '1';
        if ($slug != ''){
            $where['slug_artikel'] = $slug;
        }
        if ($jenis != ''){
            $where['j.slug_jenis'] = $jenis;
            $short = 200;
        }
        if ($tipe != ''){
            if($tipe == 'populer'){
                $where['a.is_popular'] = '1';
            }else if($tipe == 'header'){
                $where['a.is_breaking'] = '1';
            }
        }
        $options = ['limit' => $limit, 'offset' => 0, 'order' => ['a.update_artikel', $order]];
        
        $result = $this->m_artikel->getJenis($where, $options);
        $data = array();
        foreach ($result['data'] as $value) {
            $row = array();
            $row['jenis'] = $value['judul_jenis'];
            $row['tag'] = site_url('tag/' . $value['slug_jenis']);
            $row['color'] = $value['color_jenis'];
            
            $row['judul'] = $value['judul_artikel'];
            $row['slug'] = site_url('artikel/' . $value['slug_artikel']);
            $row['view'] = angka($value['view_artikel']);
            $row['isi'] = limit_text($value['isi_artikel'], $short);
            $row['foto'] = load_file($value['foto_artikel']);
            $row['update'] = format_date($value['update_artikel'], 2);
            $row['log'] = $value['log_artikel'];
            
            $data[] = $row;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _galeri() {
        $this->load->model(array('m_galeri'));
        
        $slug = $this->input->post('slug');
        $jenis = $this->input->post('jenis');
        $order = $this->input->post('order');
        $limit = (int) $this->input->post('limit');
        
        $where['status_galeri'] = '1';
        if ($slug != ''){
            $where['slug_galeri'] = $slug;
        }
        if ($jenis != ''){
            $where['jenis_galeri'] = $jenis;
        }
        $options = ['limit' => $limit, 'offset' => 0, 'order' => ['update_galeri', $order]];
        
        $result = $this->m_galeri->all($where, $options);
        $data = array();
        foreach ($result['data'] as $value) {
            $row = array();
            
            $row['judul'] = $value['judul_galeri'];
            $row['slug'] = site_url('galeri/' . $value['slug_galeri']);
            $row['isi'] = $value['isi_galeri'];
            $row['foto'] = ($jenis == '1') ? $value['foto_galeri'] : load_file($value['foto_galeri']);
            $row['update'] = format_date($value['update_galeri'], 2);
            $row['log'] = $value['log_galeri'];
            
            $data[] = $row;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _visitor() {
        $this->load->model(array('m_visitor'));
        
        $today = $this->m_visitor->getCountIp(['DATE(access_date) = CURDATE()' => null]);
        $yesterday = $this->m_visitor->getCountIp(['DATE(access_date) = CURDATE()-1' => null]);
        $last_week = $this->m_visitor->getCountIp(['YEARWEEK(access_date, 1) = YEARWEEK(CURDATE(), 1)' => null]);
        $last_month = $this->m_visitor->getCountIp([
            'YEAR(access_date) = YEAR(CURDATE())' => null,
            'MONTH(access_date) = MONTH(CURDATE())' => null
        ]);
        
        $output['vtoday'] = isset($today['visits']) ? $today['visits'] : 0;
        $output['vyesterday'] = isset($yesterday['visits']) ? $yesterday['visits'] : 0;
        $output['vweek'] = isset($last_week['visits']) ? $last_week['visits'] : 0;
        $output['vmonth'] = isset($last_month['visits']) ? $last_month['visits'] : 0;
        
        jsonResponse(array('data' => $output, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _kutipan() {
        $this->load->model(array('m_kutipan'));
        
        $limit = $this->input->post('limit');
        $order = $this->input->post('order');
        
        $where = null;
        $result = $this->m_kutipan->getAll($where, $order, $limit);
        $data = array();
        foreach ($result['data'] as $value) {
            $data[] = $value;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
}
