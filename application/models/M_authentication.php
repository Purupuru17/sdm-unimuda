<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_authentication extends KZ_Model {

    function cekModule($module, $class, $fungsi, $groupid)
    {
        $this->db->select('id_menu_aksi')->from('yk_group_menu_aksi')
            ->where('id_group', $groupid)
            ->where('segmen', $module . '/' . $class . '/' . $fungsi);
        $get = $this->db->get();
        return $get->num_rows() > 0 ? true : false;
    }
    function getAuth($username)
    {
        $data = [];
        $this->db->from('yk_user u')
            ->join('yk_group g', 'g.id_group = u.id_group', 'inner')
            ->where('username', $username)->or_where('email', $username); 
        $query = $this->db->get();     
        $result = $query->result_array();
        if(sizeof($result) > 0) {
            $data = $result[0];
        }
        return $data;
    }
    function updateGroupMenuAksi($groupId, $auth)
    {
        $this->db->trans_start();
        $this->db->delete('yk_group_menu_aksi', array('id_group' => $groupId));         
        
        foreach ($auth as $aksi) {            
            if ($aksi['index'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 1);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');                    
                }
            }
            if ($aksi['add'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 2);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['edit'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 3);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['delete'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 4);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['detail'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 5);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['cetak'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 6);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['export'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 7);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function getMenuModule($menuId, $groupId, $aksiId)
    {
        $data = [];
        $this->db->select("ma.id_menu_aksi AS id_menu_aksi , $groupId AS id_group, CONCAT(m.module_menu, '/', a.fungsi) AS segmen", FALSE);
        $this->db->from('yk_menu_aksi ma')
            ->join('yk_menu m', 'ma.id_menu = m.id_menu', 'inner')
            ->join('yk_aksi a', 'ma.id_aksi = a.id_aksi', 'inner')
            ->where('ma.id_menu', $menuId)->where('ma.id_aksi', $aksiId);
        $query = $this->db->get();
        $result = $query->result_array();
        if(sizeof($result) > 0) {
            $data = $result[0];
        }
        return $data;       
    }    
}