<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_presensi extends KZ_Model {

    protected $id = 'id_presensi';
    protected $table = 'm_presensi';
    protected $uuid = true;
    
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'alias'      => 'p',
            'select'     => 'p.*, pg.nama, pg.nik',
            'join'       => [ ['m_pegawai pg','pg.id_pegawai = p.pegawai_id','left'] ],
            'columns'    => [null,'nama','tgl_presensi','waktu_masuk','status_presensi','waktu_pulang',null],
            'searchable' => ['nik','nama'],
            'order'      => ['created_at' => 'desc']
        ];
        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            $btn_aksi = '<a href="'. site_url($param['module'].'/detail/'. encode($items['id_presensi'])) .'" 
                    class="tooltip-info btn btn-white btn-info btn-sm btn-round" data-rel="tooltip" title="Lihat Data">
                    <span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span>
                </a>';
            $btn_aksi .= ($param['level'] != '1') ? '' : '<a href="'. site_url($param['module'].'/edit/'. encode($items['id_presensi'])) .'" 
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a><a href="#" itemid="'. encode($items['id_presensi']) .'" itemprop="'. ctk($items['nama']) .'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $btn_masuk = '<a href="https://www.google.com/maps/search/?api=1&query='.$items['latitude_masuk'].','.$items['longitude_masuk'].'" 
                    target="_blank" class=""> <small>'.$items['lokasi_masuk'].'</small>
                </a>';
            $btn_masuk .= empty($items['foto_masuk']) ? '' : ' <a href="#" itemid="'. load_file($items['foto_masuk']) .'" id="imgMasuk-btn" 
                    class="btn btn-white btn-info btn-mini btn-round"> <i class="ace-icon fa fa-image"></i>
                </a>';
            $btn_pulang = '<a href="https://www.google.com/maps/search/?api=1&query='.$items['latitude_pulang'].','.$items['longitude_pulang'].'" 
                    target="_blank" class=""> <small>'.$items['lokasi_pulang'].'</small>
                </a>';
            $btn_pulang .= empty($items['foto_pulang']) ? '' : ' <a href="#" itemid="'. load_file($items['foto_pulang']) .'" id="imgPulang-btn" 
                    class="btn btn-white btn-info btn-mini btn-round"> <i class="ace-icon fa fa-image"></i>
                </a>';
            $jam_kerja = $this->_jamKerja($items['waktu_masuk'], $items['waktu_pulang']);
            
            $row = [];
            $row[] = ctk($no);
            $row[] = '<strong>'.ctk($items['nama']).'</strong><br><span class="grey">'.ctk($items['nik']).'</span>';
            $row[] = format_date($items['tgl_presensi'])
                .'<br> <small>Jam Kerja</small> : <strong class="blue">'.$jam_kerja.'</strong>';
            $row[] = '<strong class="green">'.$items['waktu_masuk'].'</strong><br>'.$btn_masuk;
            $row[] = st_mhs($items['status_presensi']).'<br><small>'.$items['catat_presensi'].'</small>';
            $row[] = '<strong class="orange">'.$items['waktu_pulang'].'</strong><br>'.$btn_pulang;
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
    function _jamKerja($masuk, $pulang) {
        if (empty($masuk) || empty($pulang)) {
            return 0;
        }
        $today = date('Y-m-d');
        $jamMasuk  = new DateTime("$today $masuk");
        $jamPulang = new DateTime("$today $pulang");
        
        if ($jamPulang < $jamMasuk) {
            return 0;
        }
        // hitung selisih
        $diff = $jamMasuk->diff($jamPulang);
        $totalMenit = ($diff->h * 60) + $diff->i;
        $jamKerja = floor($totalMenit / 60);

        return $jamKerja;
    }
}
