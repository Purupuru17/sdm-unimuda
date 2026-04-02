<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_jenis extends CI_Model {

    var $id = 'id_jenis';
    var $table = 'wb_jenis_artikel';

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
        $this->db->order_by('judul_jenis', $order);
        
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
    function getSlug($slug) {
        $this->db->from($this->table);
        $this->db->where('slug_jenis', $slug);
        
        return $this->db->get()->row_array();
    }
    function getGroup($where = NULL, $order = 'asc', $limit = 0) {
        $this->db->select('j.*, COUNT(a.id_artikel) AS total_artikel');
        $this->db->from('wb_artikel a');
        $this->db->join('wb_jenis_artikel j', 'a.jenis_id = j.id_jenis', 'inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($limit)){
            $this->db->limit($limit);
        }
        if(!is_null($order)){
            $this->db->order_by('j.judul_jenis', $order);
        }
        $this->db->group_by('j.id_jenis');
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getEmpty() {
        $data['id_jenis'] = NULL;
        $data['judul_jenis'] = NULL;
        $data['slug_jenis'] = NULL;
        $data['color_jenis'] = NULL;
        $data['icon_jenis'] = 'fa fa-newspaper-o';
        return $data;
    }
}
