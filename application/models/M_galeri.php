<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_galeri extends KZ_Model {
    
    protected $id = 'id_galeri';
    protected $table = 'wb_galeri';
    
    function __construct()
    {
        parent::__construct();
    }
    function getEmpty()
    {
        return [
            $this->id => null,
            'judul_galeri' => null,
            'jenis_galeri' => null,
            'isi_galeri' => null,
            'status_galeri' => null,
            'is_header' => null,
            'foto_galeri' => null,
            
            'update_galeri' => null,
            'log_galeri' => null
        ];
    }
}
