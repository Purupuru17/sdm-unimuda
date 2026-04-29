<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_kegiatan extends KZ_Model {

    protected $id = 'id';
    protected $table = 'aik_presensi';
    protected $uuid = true;
    
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'alias'      => 'p',
            'select'     => 'p.*, pg.nama, pg.nik, pg.jenis_pegawai, a.jenis_agenda, a.judul_agenda, a.waktu_agenda, a.limit_agenda',
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
                </a>' : '';
            $btn_aksi .= ($param['level'] == '1') ? '<a href="#" itemid="'. encode($items['id']) .'" itemprop="'. ctk($items['nama']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>' : '';
            $btn_loc = '<br><a href="https://www.google.com/maps/search/?api=1&query='.$items['latitude'].','.$items['longitude'].'" 
                    target="_blank" class="smaller-90">'.$items['lokasi'].'</a>';
            $btn_loc .= empty($items['foto']) ? '' : ' <a href="#" itemid="'. load_file($items['foto']) .'" id="img-btn" 
                    class="btn btn-white btn-info btn-mini btn-round"> <i class="ace-icon fa fa-image"></i>
                </a>';
            $is_admin = ($param['level'] == '1') ? st_aktif($items['validasi'],'yn') : '';
            
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
            $row[] = $is_admin.' &nbsp'.st_label($items['status']);
            $row[] = $btn_pop.'<br><small>'. format_date($items['waktu_agenda'],2).' ('.$items['limit_agenda'].' Menit) </small>';
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
    function pivotData($where, $awal, $akhir) 
    {
        $this->load->model(['m_pegawai','m_agenda']);
        $options = [
            'alias'      => 'p',
            'select'     => 'p.id_pegawai, p.nama, p.nik, p.jenis_pegawai, pr.agenda_id, pr.waktu, pr.status, pr.foto',
            'join'       => [ 
                ['aik_presensi pr','pr.pegawai_id = p.id_pegawai','left'],
            ],
            'order'      => 'nama ASC'
        ];
        $result = $this->m_pegawai->all($where, $options);
        if($result['rows'] < 1){
            return ['data' => ''];
        }
        // ================= TANGGAL AGENDA =================
        $agenda = $this->m_agenda->all(['DATE(waktu_agenda) >=' => $awal, 'DATE(waktu_agenda) <=' => $akhir], 
            ['order' => 'waktu_agenda ASC']);
        if($agenda['rows'] < 1){
            return ['data' => ''];
        }
        // ================= PIVOT =================
        $pivot = [];
        foreach ($result['data'] as $row) {            
            // init pegawai
            if (!isset($pivot[$row['id_pegawai']])) {
                $arr_item = [
                    'nama' => $row['nama'],
                    'nik' => $row['nik'],
                    'jenis' => $row['jenis_pegawai']
                ];
                // init semua tanggal null
                foreach ($agenda['data'] as $keg) {
                    $arr_item[$keg['id_agenda']] = null;
                }
                $pivot[$row['id_pegawai']] = $arr_item;
            }
            if (!empty($row['agenda_id'])) {
                $pivot[$row['id_pegawai']][$row['agenda_id']] = [
                    'status' => $row['status'],
                    'waktu'  => $row['waktu'],
                    'foto' => $row['foto'],
                ];
            }
        }
        return [
            'data' => $pivot, 'agenda' => $agenda['data'], 'count' => $agenda['rows']
        ];
    }
}
