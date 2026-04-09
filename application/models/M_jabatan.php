<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_jabatan extends KZ_Model {

    protected $id = 'id_jabatan';
    protected $table = 'm_jabatan';
    protected $uuid = true;
    
    function getEmpty()
    {
        return [
            $this->id => null,
            'unit_id' => null,
            'atasan_jabatan' => null,
            'nama_jabatan' => null
        ];
    }
}
