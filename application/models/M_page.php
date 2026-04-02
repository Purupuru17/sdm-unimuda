<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_page extends CI_Model {
    
    var $id = 'id_page';
    var $table = 'wb_page';

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
        $this->db->order_by('judul_page', $order);
        
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
        $this->db->from($this->table)->where('slug_page', $slug)->where('status_page', '1');
        return $this->db->get()->row_array();
    }
    function getURL() {
        $this->db->select("CONCAT('pages', '/' , slug_page) AS slug");
        $this->db->from($this->table);
        $this->db->order_by('judul_page', 'asc');
        
        $get = $this->db->get();
        return $get->result_array();
    }
    function getEmpty() {
        $data['id_page'] = NULL;
        $data['judul_page'] = NULL;
        $data['isi_page'] = NULL;
        $data['slug_page'] = NULL;
        $data['status_page'] = NULL;
        $data['foto_page'] = NULL;
       
        $data['update_page'] = NULL;
        $data['log_page'] = NULL;
        return $data;
    }
}
