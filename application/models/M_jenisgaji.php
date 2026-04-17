<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_jenisgaji extends KZ_Model {

    protected $id = 'id_jenis';
    protected $table = 'gj_jenis';
    protected $uuid = true;
    
    function getEmpty()
    {
        return [
            $this->id => null,
            'nama' => null,
            'tipe' => null,
            'rutin' => null,
            'persen' => null,
            'nominal' => null,
        ];
    }
}
