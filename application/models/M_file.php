<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_file extends KZ_Model {

    protected $id = 'id_file';
    protected $table = 'wb_file';

    function __construct()
    {
        parent::__construct();
    }
    function getEmpty()
    {
        return [
            $this->id => null,
            'nama_file' => null,
            'type_file' => null,
            'size_file' => null,
            'url_file' => null,
            
            'update_file' => null,
            'log_file' => null
        ];
    }
}
