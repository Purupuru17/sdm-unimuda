<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_artikel extends KZ_Model {
    
    protected $id = 'id_artikel';
    protected $table = 'wb_artikel';
    
    private $alias = 'a';
    private $join = [
        ['wb_jenis_artikel j', 'a.jenis_id = j.id_jenis', 'inner']
    ];
    
    function __construct()
    {
        parent::__construct();
    }
    function getJenis($where = [], $options = [], $all = true)
    {
        $options['alias'] = $this->alias;
        $options['join'] = $this->join;
        if(!$all){
            return parent::get($where, $options);
        }
        return parent::all($where, $options);
    }
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'alias'      => $this->alias,
            'select'     => '*',
            'join'       => $this->join,
            'columns'    => [null,'judul_artikel','judul_jenis','status_artikel','is_popular','is_breaking','update_artikel',null],
            'searchable' => ['judul_artikel','judul_jenis'],
            'order'      => ['update_artikel' => 'desc']
        ];

        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            
            $status = ($items['status_artikel'] == '0') ? '<span class="label label-danger label-white arrowed">Tidak Aktif</span>' : '<span class="label label-success label-white arrowed">Aktif</span>';
            $populer = ($items['is_popular'] == '0') ? '<span class="label label-default label-white arrowed">Tidak</span>' : '<span class="label label-info label-white arrowed">Ya</span>';
            $breaking = ($items['is_breaking'] == '0') ? '<span class="label label-default label-white arrowed">Tidak</span>' : '<span class="label label-warning label-white arrowed">Ya</span>';
            
            $btn_aksi = '<a href="'. site_url($param['module'].'/edit/'. encode($items['id_artikel'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id_artikel']) .'" itemprop="'. ctk($items['judul_artikel']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $row = [];
            $row[] = ctk($no);
            $row[] = '<a target="_blank" href="'. site_url('artikel/' . $items['slug_artikel']).'">'
                .ctk($items['judul_artikel']).'</a>';
            $row[] = '<label style="background:'.ctk($items['color_jenis']).'" class="label">'
                .ctk($items['judul_jenis']).'</label>';
            $row[] = $status;
            $row[] = $populer;
            $row[] = $breaking;
            $row[] = format_date($items['update_artikel'],2);
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
    function getEmpty()
    {
        return [
            $this->id => null,
            'judul_artikel' => null,
            'isi_artikel' => null,
            'jenis_id' => null,
            'status_artikel' => null,
            'is_popular' => null,
            'is_breaking' => null,
            
            'update_artikel' => null,
            'log_artikel' => null,
            'foto_artikel' => null
        ];
    }
}
