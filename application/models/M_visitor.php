<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_visitor extends KZ_Model {

    protected $id = 'site_log_id';
    protected $table = 'yk_site_log';
    protected $uuid = true;
    
    function __construct()
    {
        parent::__construct();
    }
    function getDatatables($where = []) 
    {
        $options = [
            'columns'    => [null, 'access_date','ip_address','no_of_visits','requested_url','page_name','user_agent'],
            'searchable' => ['ip_address','user_agent'],
            'order'      => ['access_date' => 'desc']
        ];
        
        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            
            $row = [];
            $row[] = ctk($no);
            $row[] = format_date($items['access_date'],2);
            $row[] = ctk($items['ip_address']);
            $row[] = ctk($items['no_of_visits']);
            $row[] = '<small class="break-word">'.ctk($items['requested_url']).'</small>';
            $row[] = '<small class="break-word">'.ctk($items['referer_page']).'</small>';
            $row[] = '<small>'.ctk($items['user_agent']).'</small>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $result['draw'],
            "recordsTotal" => $result['recordsTotal'],
            "recordsFiltered" => $result['recordsFiltered'],
            "data" => $data,
        );
        return $output;
    }
    function getCountIp($where = null) {
        $options['select'] = 'COUNT(ip_address) as visits';
        return parent::get($where, $options);
    }
    function getChart($where = null, $today = true) {
        $format = ($today) ? '%h %p' : '%d-%m-%Y';
        $options['select'] = 'COUNT(ip_address) as visits,
            DATE_FORMAT(access_date,"'.$format.'") AS day, SUM(no_of_visits) AS akses';
        $options['group'] = ($today) ? 'HOUR(access_date)' : 'DATE(access_date)';
        
        return parent::all($where, $options);
    }
}