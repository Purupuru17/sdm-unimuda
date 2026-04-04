<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_kerja extends KZ_Model {

    protected $id = 'id_kerja';
    protected $table = 'm_kerja';
    protected $uuid = true;
 
    function getEmpty()
    {
        return [
            $this->id => null,
            'hari_kerja' => null,
            'masuk_kerja' => null,
            'pulang_kerja' => null,
            'limit_kerja' => null
        ];
    }
}
