<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_unit extends KZ_Model {

    protected $id = 'id_unit';
    protected $table = 'm_unit';
    protected $uuid = true;
    
    function getEmpty()
    {
        return [
            $this->id => null,
            'kode_unit' => null,
            'nama_unit' => null
        ];
    }
}
