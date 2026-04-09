<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_agenda extends KZ_Model {

    protected $id = 'id_agenda';
    protected $table = 'aik_agenda';
    protected $uuid = true;
    
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'alias'      => 'a',
            'select'     => 'a.*, l.nama_lokasi',
            'join'       => [ ['m_lokasi l','l.id_lokasi = a.lokasi_id','left'] ],
            'columns'    => [null,'judul_agenda','petugas_agenda','waktu_agenda','nama_lokasi','status_agenda',null],
            'searchable' => ['judul_agenda'],
            'order'      => ['waktu_agenda' => 'desc']
        ];
        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            $btn_aksi = '<a href="'. site_url($param['module'].'/edit/'. encode($items['id_agenda'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id_agenda']) .'" itemprop="'. limit_text($items['judul_agenda'],50) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $ts_waktu = strtotime($items['waktu_agenda']);
            $limit = (int) $items['limit_agenda'];
            $limit_time = date("Y-m-d H:i:s", strtotime("+$limit minutes", $ts_waktu));
                       
            $row = [];
            $row[] = ctk($no);
            $row[] = limit_text($items['judul_agenda'], 150).'<br><i class="bolder">'.$items['jenis_agenda'].'</i>';
            $row[] = '<strong>'.format_date($items['waktu_agenda'],2).'</strong><br>'.
                ctk($items['limit_agenda']).' Menit<br><small class="red">'.format_date($limit_time,2).'</small>';
            $row[] = '<strong>'.ctk($items['nama_lokasi']).'</strong><br>'.st_aktif($items['is_open']);
            $row[] = ctk($items['petugas_agenda']);
            $row[] = st_aktif($items['status_agenda']);
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
            'lokasi_id' => null,
            'jenis_agenda' => null,
            'judul_agenda' => null,
            'petugas_agenda' => null,
            'waktu_agenda' => null,
            'limit_agenda' => null,
            'status_agenda' => null,
            'is_open' => null,
            
            'update_agenda' => null,
            'log_agenda' => null
        ];
    }
}
