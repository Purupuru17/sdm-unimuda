<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_kegiatan extends KZ_Model {

    protected $id = 'id';
    protected $table = 'aik_presensi';
    protected $uuid = true;
    
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'alias'      => 'p',
            'select'     => 'p.*, pg.nama, pg.nik, pg.jenis_pegawai, a.jenis_agenda, a.judul_agenda, a.waktu_agenda',
            'join'       => [ 
                    ['m_pegawai pg','pg.id_pegawai = p.pegawai_id','left'],
                    ['aik_agenda a','a.id_agenda = p.agenda_id','left'],
                ],
            'columns'    => [null,'nama','tgl_presensi','waktu_masuk','status_presensi','waktu_pulang',null],
            'searchable' => ['nik','nama'],
            'order'      => ['waktu' => 'desc']
        ];
        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            $btn_aksi = ($param['level'] != '1') ? '' : '<a href="#" itemid="'. encode($items['id']) .'" itemprop="'. ctk($items['nama']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $btn_loc = '<br><a href="https://www.google.com/maps/search/?api=1&query='.$items['latitude'].','.$items['longitude'].'" 
                    target="_blank" class="">'.$items['lokasi'].'</a>';
            
            $row = [];
            $row[] = ctk($no);
            $row[] = '<strong>'.ctk($items['nama']).'</strong><br><span class="grey">'
                    .ctk($items['nik']).'</span>';
            $row[] = '<strong>'.format_date($items['waktu'],2).'</strong><br>'.st_mhs($items['status']);
            $row[] = $items['jenis_agenda'].$btn_loc;
            $row[] = format_date($items['waktu_agenda'],0).'<br><small>'. limit_text($items['judul_agenda'], 50).'</small>';
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
