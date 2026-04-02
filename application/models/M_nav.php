<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_nav extends CI_Model {
    
    var $id = 'id_nav';
    var $table = 'wb_nav';

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
        $this->db->order_by('judul_nav', $order);
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getNav() {
        $this->db->distinct(true)
                ->from($this->table)
                ->where('status_nav =', '1');
        $this->db->order_by('order_nav','asc');
        
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
        $this->db->from($this->table)->where('url_nav', $slug)->where('status_nav', '1');
        return $this->db->get()->row_array();
    }
    function getParent($where = NULL) {
        $this->db->from($this->table);
        $this->db->where('parent_nav', '0');
        if(!is_null($where)){
            $this->db->where($where);
        }
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getEmpty() {
        $data['id_nav'] = NULL;
        $data['parent_nav'] = NULL;
        $data['judul_nav'] = NULL;
        $data['url_nav'] = NULL;
        $data['link_nav'] = NULL;
        $data['status_nav'] = NULL;
        $data['order_nav'] = NULL;
        $data['icon_nav'] = 'fa fa-list';
       
        $data['update_nav'] = NULL;
        $data['log_nav'] = NULL;
        return $data;
   }
}
