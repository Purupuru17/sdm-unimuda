<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends KZ_Model {

    protected $id = 'id_user';
    protected $table = 'yk_user';
    protected $uuid = true;
    
    private $alias = 'u';
    private $join = [
        ['yk_group g','u.id_group = g.id_group','inner']
    ];

    function __construct()
    {
        parent::__construct();
    }
    //GET
    function getEmpty()
    {
        return [
            $this->id => null,
            'id_group' => null,
            'fullname' => null,
            'username' => null,
            'password' => null,
            'status_user' => null,
            'foto_user' => null
        ];
    }
    function getGroup($where = NULL, $all = false)
    {
        $options = [ 'alias' => $this->alias, 'join' => $this->join ];
        if($all){
            return parent::all($where, $options);
        }
        return parent::get($where, $options);
    }
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'alias'      => $this->alias,
            'select'     => 'u.*, g.nama_group',
            'join'       => $this->join,
            'columns'    => [null,'fullname','username','nama_group','status_user','buat_user','last_login',null],
            'searchable' => ['fullname','username','nama_group'],
            'order'      => ['last_login' => 'desc']
        ];
        
        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            $btn_aksi = '<a href="'. site_url($param['module'].'/detail/'. encode($items['id_user'])) .'" 
                    class="tooltip-info btn btn-white btn-info btn-sm btn-round" data-rel="tooltip" title="Lihat Data">
                    <span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span>
                </a><a href="'. site_url($param['module'].'/edit/'. encode($items['id_user'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id_user']) .'" itemprop="'. ctk($items['fullname']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $row = [];
            $row[] = ctk($no);
            $row[] = ctk($items['fullname']);
            $row[] = ctk($items['username']);
            $row[] = ctk($items['nama_group']);
            $row[] = st_aktif($items['status_user']);
            $row[] = format_date($items['buat_user'], 2);
            $row[] = is_online($items['last_login']).'<br/><small>'.$items['ip_user'].'</small>';
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

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
}
