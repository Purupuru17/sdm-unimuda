<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_lokasi extends KZ_Model {

    protected $id = 'id_lokasi';
    protected $table = 'm_lokasi';
    protected $uuid = true;
    
    function getEmpty()
    {
        return [
            $this->id => null,
            'nama_lokasi' => null,
            'latitude' => null,
            'longitude' => null,
            'radius' => null,
            'status_lokasi' => null
        ];
    }
}
