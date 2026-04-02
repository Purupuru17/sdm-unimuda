<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_group extends KZ_Model {

    protected $id = 'id_group';
    protected $table = 'yk_group';
    protected $uuid = false;
    
    function __construct()
    {
        parent::__construct();
    }
    function getEmpty()
    {
        return [
            $this->id => null,
            'nama_group' => null,
            'level' => null,
            'keterangan_group' => null
        ];
    }
    function insertRole($data)
    {
        $this->db->insert('yk_group_role', $data);
        return $this->db->affected_rows() > 0;
    }
    function deleteRole($where = NULL)
    {
        if (!is_null($where)) {
            $this->db->where($where);
        }
        $this->db->delete('yk_group_role');
        return $this->db->affected_rows() > 0;
    }
    function getRole($where = NULL)
    {
        $options['alias'] = 'g';
        $options['select'] = 'r.*,u.fullname,u.username,g.nama_group,g.level';
        $options['join'] = [
            ['yk_group_role r', 'r.group_id = g.id_group', 'inner'],
            ['yk_user u', 'r.user_id = u.id_user', 'inner']
        ];
        $options['order'] = ['r.group_id','asc'];
        
        return parent::all($where, $options);
    }
}
