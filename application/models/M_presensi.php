<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_presensi extends KZ_Model {

    protected $id = 'id_presensi';
    protected $table = 'm_presensi';
    protected $uuid = true;
    
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'alias'      => 'p',
            'select'     => 'p.*, pg.nama, pg.nik, pg.jenis_pegawai',
            'join'       => [ ['m_pegawai pg','pg.id_pegawai = p.pegawai_id','left'] ],
            'columns'    => [null,'nama','tgl_presensi','waktu_masuk','waktu_pulang','status_presensi',null],
            'searchable' => ['nik','nama'],
            'order'      => ['created_at' => 'desc']
        ];
        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            $btn_aksi = ($param['level'] != '1') ? '' : '<a href="#" itemid="'. encode($items['id_presensi']) .'" itemprop="'. ctk($items['nama']) .'" id="delete-btn" 
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
            $row[] = '<strong>'.ctk($items['nama']).'</strong><br><span class="grey">'
                    .ctk($items['nik']).'</span>';
            $row[] = '<strong>'.format_date($items['tgl_presensi'])
                .'</strong><br> <small>Jam Kerja</small> : <strong class="blue">'.$jam_kerja.'</strong>';
            $row[] = '<strong class="green">'.$items['waktu_masuk'].'</strong><br>'.$btn_masuk;
            $row[] = '<strong class="orange">'.$items['waktu_pulang'].'</strong><br>'.$btn_pulang;
            $row[] = st_label($items['status_presensi']).' <br><small>'.$items['catat_presensi'].'</small>';
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
        $this->load->model(['m_pegawai']);
        $options = [
            'alias'      => 'p',
            'select'     => 'p.id_pegawai, p.nama, p.nik, p.jenis_pegawai,
                pr.tgl_presensi, pr.status_presensi, pr.waktu_masuk, pr.waktu_pulang, pr.foto_masuk, pr.foto_pulang',
            'join'       => [ 
                ['m_presensi pr','pr.pegawai_id = p.id_pegawai','left'],
            ],
            'order'      => 'nama ASC'
        ];
        $result = $this->m_pegawai->all($where, $options); 
        if($result['rows'] < 1){
            return ['data' => ''];
        }
        // ================= RANGE TANGGAL =================
        $start = new DateTime($awal);
        $end   = new DateTime($akhir);
        $periode = [];
        while ($start <= $end) {
            $periode[] = $start->format('Y-m-d');
            $start->modify('+1 day');
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
                foreach ($periode as $tgl) {
                    $arr_item[$tgl] = null;
                }
                $pivot[$row['id_pegawai']] = $arr_item;
            }
            if (!empty($row['tgl_presensi'])) {
                $tgl = date('Y-m-d', strtotime($row['tgl_presensi']));
                $pivot[$row['id_pegawai']][$tgl] = [
                    'status' => $row['status_presensi'],
                    'masuk'  => $row['waktu_masuk'],
                    'pulang' => $row['waktu_pulang'],
                    'fotomasuk' => $row['foto_masuk'],
                    'fotopulang' => $row['foto_pulang'],
                ];
            }
        }
        return [
            'data' => $pivot, 'periode' => $periode, 'count' => count($periode)
        ];
    }
    private function _jamKerja($masuk, $pulang)
    {
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
