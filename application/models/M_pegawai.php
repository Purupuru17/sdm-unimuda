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
    function updateAkun($id, $data, $user)
    {
        $this->db->trans_start();
        
        $this->db->insert('yk_user', $user);
        parent::update($id, $data);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function getDatatables($where = [], $param = []) 
    {
        $options = [
            'alias'      => 'p',
            'select'     => 'p.*, u.nama_unit, j.nama_jabatan, uu.nama_unit AS unit_jabatan',
            'join'       => [ 
                ['m_unit u','u.id_unit = p.unit_id','left'],
                ['m_jabatan j','j.id_jabatan = p.jabatan_id','left'],
                ['m_unit uu','uu.id_unit = j.unit_id','left'],
            ],
            'columns'    => [null,'nama','jenis_pegawai','nama_jabatan','tgl_pegawai','akademik',null],
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
            $btn_aksi .= empty($items['user_id']) ? '<a href="#" itemid="'. encode($items['id_pegawai']) .'" itemprop="'. ctk($items['nama']) .'" id="akun-btn" 
                    class="tooltip-success btn btn-white btn-success btn-mini btn-round" data-rel="tooltip" title="Buat Akun">
                    <span class="green"><i class="ace-icon fa fa-user-plus"></i></span>
                </a>' : '';
            
            $tanggal_masuk = new DateTime($items['tgl_pegawai']);
            $hari_ini = new DateTime();
            $selisih = $tanggal_masuk->diff($hari_ini);
            $masa_kerja = empty($items['tgl_pegawai']) ? '' : $selisih->y . ' Tahun ' . $selisih->m . ' Bulan ';
                       
            $row = [];
            $row[] = ctk($no);
            $row[] = '<strong>'.ctk($items['nama']).'</strong><br><span class="blue">'.ctk($items['nik']).'</span>';
            $row[] = ctk($items['jenis_pegawai']).'<br><span>'.ctk($items['nama_unit']).'</span>';
            $row[] = $items['nama_jabatan'].'<br><small>'.$items['unit_jabatan'].'</small>';
            $row[] = $items['nuptk'].'<br>'.$items['nidn'];
            $row[] = $items['akademik'].' - '.$items['pangkat'].'<br>'.$masa_kerja;
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
            'nama_gelar' => null,
            'nik' => null,
            'nidn' => null,
            'nuptk' => null,
            'jenis_kelamin' => null,
            'tempat_lahir' => null,
            'tgl_lahir' => null,
            'agama' => null,
            'telepon' => null,
            'email' => null,
            'pendidikan' => null,
            'status_nikah' => null,
            'jumlah_anak' => null,
            'tgl_pegawai' => null,
            'status_pegawai' => null,
            'jenis_pegawai' => null,
            'pangkat' => null,
            'akademik' => null,

            'unit_id' => null,
            'jabatan_id' => null,
            
            'alamat' => null,
            'update_at' => null
        ];
    }
}
