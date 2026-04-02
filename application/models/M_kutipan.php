<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_kutipan extends CI_Model {
    var $id = 'id_kutipan';
    var $table = 'wb_kutipan';
    
    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        $row = $this->db->insert($this->table, $data);
        return $row;
    }
    //UPDATE
    function update($id, $data) {
        $this->db->where($this->id, $id);
        $row = $this->db->update($this->table, $data);
        return $row;
    }
    //DELETE
    function delete($id) {
        $this->db->where($this->id, $id);
        $row = $this->db->delete($this->table);
        return $row;
    }
    
    //GET
    function getAll($where = NULL, $order = 'asc', $limit = NULL) {
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($limit)){
            $this->db->limit($limit);
        }
        $this->db->order_by('log_kutipan', $order);
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getId($id) {
        $this->db->from($this->table)->where($this->id, $id);
        return $this->db->get()->row_array();
    }
    function getEmpty() {
        $data['id_kutipan'] = NULL;
        $data['oleh'] = NULL;
        $data['quote'] = NULL;
        $data['update_kutipan'] = NULL;
        $data['log_kutipan'] = NULL;
        
        return $data;
   }
}
