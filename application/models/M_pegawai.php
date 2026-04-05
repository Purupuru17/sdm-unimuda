<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_pegawai extends KZ_Model {

    protected $id = 'id_pegawai';
    protected $table = 'm_pegawai';
    protected $uuid = true;
    
    function createAkun($data, $user)
    {
        $this->db->trans_start();
        
        $this->db->insert('yk_user', $user);
        $this->db->insert($this->table, $data);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'columns'    => [null,'nama','nama','nama','nama',null],
            'searchable' => ['nik','nama'],
            'order'      => ['nama' => 'ASC']
        ];
        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            $btn_aksi = '<a href="'. site_url($param['module'].'/detail/'. encode($items['id_pegawai'])) .'" 
                    class="tooltip-info btn btn-white btn-info btn-sm btn-round" data-rel="tooltip" title="Lihat Data">
                    <span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span>
                </a><a href="'. site_url($param['module'].'/edit/'. encode($items['id_pegawai'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id_pegawai']) .'" itemprop="'. ctk($items['nama']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
                       
            $row = [];
            $row[] = ctk($no);
            $row[] = '<strong>'.ctk($items['nama']).'</strong><br><span class="blue">'.ctk($items['nik']).'</span>';
            $row[] = '';
            $row[] = '';
            $row[] = '';
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
            'user_id' => null,
            'nama' => null,
            'nik' => null,
            'status' => null
        ];
    }
}
