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
            'columns'    => [null,'nama','waktu','validasi','waktu_agenda',null],
            'searchable' => ['nik','nama'],
            'order'      => ['waktu' => 'desc']
        ];
        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            
            $btn_aksi = ($items['validasi'] != '1' && $param['level'] == '1') ? '<a href="#" 
                itemid="'. encode($items['id']) .'" itemprop="'. ctk($items['nama']) .'" id="valid-btn" 
                    class="tooltip-success btn btn-white btn-success btn-sm btn-round" data-rel="tooltip" title="Validasi">
                    <span class="green"><i class="ace-icon fa fa-check-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id']) .'" itemprop="'. ctk($items['nama']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>' : '';
            $btn_loc = '<br><a href="https://www.google.com/maps/search/?api=1&query='.$items['latitude'].','.$items['longitude'].'" 
                    target="_blank" class="smaller-90">'.$items['lokasi'].'</a>';
            $btn_pop = '<a href="#" class="bolder grey" itemprop="'. ctk($items['judul_agenda'],1) .'" id="desc-btn">'
                .$items['jenis_agenda'].' <i class="fa fa-external-link"> </i></a>';
            
            $box = ($items['validasi'] != '1' && $param['level'] == '1') ? ' <label class="pos-rel">
                <input value="'. encode($items['id']).'" name="is_select[]" id="is_select"
                    type="checkbox" class="ace ace-checkbox-2" />
                <span class="lbl"></span></label>' : '';

            $row = [];
            $row[] = $no.' '.$box;
            $row[] = '<strong>'.ctk($items['nama']).'</strong><br><span class="grey">'
                    .ctk($items['nik']).'</span>';
            $row[] = '<strong>'.format_date($items['waktu'],2).'</strong>'.$btn_loc;
            $row[] = st_aktif($items['validasi'],'yn').' &nbsp'.st_mhs($items['status']);
            $row[] = $btn_pop.'<br><small>'. format_date($items['waktu_agenda'],2).'</small>';
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
