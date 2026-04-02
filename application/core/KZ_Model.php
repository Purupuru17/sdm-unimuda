<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class KZ_Model extends CI_Model
{
    protected $id = 'id';
    protected $table = 'tb';
    protected $uuid = false;
    
    protected $softDelete = false;
    protected $deletedAt = 'deleted_at';
    protected $defaultOptions = [
        'where'       => [],
        'alias'       => '',
        'join'        => [],
        'select'      => '*',
        'like'        => null,
        'key'         => null,
        'order'       => [],
        'group'       => null,
        'limit'       => null,
        'offset'      => 0,
        'withDeleted' => false,
        'columns'     => [],
        'searchable'  => []
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Insert data
     */
    public function insert($data)
    {
        if ($this->uuid) {
            $this->db->set($this->id, 'UUID()', FALSE);
        }
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0;
    }
    public function insert_batch($data)
    {
        $this->db->trans_start();
        $this->db->insert_batch($this->table, $data);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /**
     * Update data
     */
    public function update($id, $data)
    {
        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->id, $id);
        }
        return $this->db->update($this->table, $data);
    }
    public function update_batch($data)
    {
        $this->db->trans_start();
        $this->db->update_batch($this->table, $data, $this->id);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /**
     * Delete data
     */
    public function delete($id)
    {
        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->id, $id);
        }
        if ($this->softDelete) {
            $data = [$this->deletedAt => date('Y-m-d H:i:s')];
            $this->db->update($this->table, $data);
            
            return $this->db->affected_rows() > 0;
        } else {
            $this->db->delete($this->table);
            
            return $this->db->affected_rows() > 0;
        }
    }
    public function empty()
    {
        $this->db->empty_table($this->table);
        return $this->db->affected_rows();
    }
    /**
     * Restore data (khusus soft delete)
     */
    public function restore($id)
    {
        if (!$this->softDelete) { return false; }

        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->id, $id);
        }
        $data = [$this->deletedAt => NULL];
        $this->db->update($this->table, $data);
        
        return $this->db->affected_rows() > 0;
    }
    /**
     * Get all data (otomatis exclude soft delete)
     */
    public function all($where = NULL, $options = [])
    {
        // Merge default dengan parameter yang dikirim
        $opts = array_merge($this->defaultOptions, $options);
        // Table alias
        $table = !empty($opts['join']) ? "{$this->table} {$opts['alias']}" : $this->table;
        $this->db->select($opts['select'])->from($table);
        // Joins
        if (!empty($opts['join'])) {
            foreach ($opts['join'] as $join) {
                $alias = $join[0] ?? '';
                $on    = $join[1] ?? '';
                $type  = $join[2] ?? 'inner';
                if ($alias && $on) { $this->db->join($alias, $on, $type); }
            }
        }
        // Where
        if (!empty($where)) {
            $this->db->where($where);
        }
        // Like
        if (!empty($opts['like']) && !empty($opts['key'])) {
            $this->db->group_start();
            if (array_keys($opts['like']) === range(0, count($opts['like']) - 1)) {
                $keyword = $opts['key'] ?? '';
                $first = true;
                foreach ($opts['like'] as $column) {
                    if ($first) {
                        $this->db->like($column, $keyword, 'both');
                        $first = false;
                    } else {
                        $this->db->or_like($column, $keyword, 'both');
                    }
                }
            }
            $this->db->group_end();
        }
        // Soft delete
        if ($this->softDelete && !$opts['withDeleted']) {
            $this->db->where("{$table}.{$this->deletedAt} IS NULL", null, false);
        }
        // Group
        if (!empty($opts['group'])) {
            $this->db->group_by($opts['group']);
        }
        // Order
        if (!empty($opts['order'])) {
            if (is_array($opts['order'])) {
                $this->db->order_by($opts['order'][0], $opts['order'][1]);
            } else {
                $this->db->order_by($opts['order']);
            }
        }
        // Limit
        if (!empty($opts['limit'])) {
            $this->db->limit($opts['limit'], $opts['offset']);
        }
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    /**
     * Get by ID/single row
     */
    public function get($id, $options = [])
    {
        // Merge default dengan parameter yang dikirim
        $opts = array_merge($this->defaultOptions, $options);
        // Table alias
        $table = !empty($opts['join']) ? "{$this->table} {$opts['alias']}" : $this->table;
        $this->db->select($opts['select'])->from($table);
        // Joins
        if (!empty($opts['join'])) {
            foreach ($opts['join'] as $join) {
                $alias = $join[0] ?? '';
                $on    = $join[1] ?? '';
                $type  = $join[2] ?? 'inner';
                if ($alias && $on) { $this->db->join($alias, $on, $type); }
            }
        }
        // Where
        if (!empty($id) && is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->id, $id);
        }
        // Soft delete
        if ($this->softDelete && !$opts['withDeleted']) {
            $this->db->where("{$table}.{$this->deletedAt} IS NULL", null, false);
        }
        // Group
        if (!empty($opts['group'])) {
            $this->db->group_by($opts['group']);
        }
        return $this->db->get()->row_array();
    }
    /**
     * Datatable data
     */
    public function datatable($where = [], $options = [])
    {
        // Merge default dengan parameter yang dikirim
        $opts = array_merge($this->defaultOptions, $options);
        // Table alias
        $table = !empty($opts['join']) ? "{$this->table} {$opts['alias']}" : $this->table;
        $this->db->from($table);
        // === JOIN ===
        if (!empty($opts['join'])) {
            foreach ($opts['join'] as $join) {
                $alias = $join[0] ?? '';
                $on    = $join[1] ?? '';
                $type  = $join[2] ?? 'inner';
                if ($alias && $on) { $this->db->join($alias, $on, $type); }
            }
        }
        // === WHERE ===
        if (!empty($where)) {
            $this->db->where($where);
        }
        // === GROUP BY ===
        if (!empty($opts['group'])) {
            $this->db->group_by($opts['group']);
        }
        // === PENCARIAN ===
        $search_val = $this->input->post('search')['value'] ?? '';
        $search_trim = trim($search_val);
        $search = preg_replace('/[^a-zA-Z0-9\s\.\-]/', '', $search_trim);

        if (!empty($search) && strlen($search) >= 3 && !empty($opts['searchable'])) {

            $this->db->group_start();
            foreach ($opts['searchable'] as $i => $col) {
                if ($i === 0) {
                    $this->db->like($col, $search);
                } else {
                    $this->db->or_like($col, $search);
                }
            }
            $this->db->group_end();
        }
        // === SORTING ===
        $order = $this->input->post('order')[0] ?? null;
        if ($order) {
            $colIndex = $order['column'];
            $colName  = $opts['columns'][$colIndex] ?? $opts['columns'][0];
            $dir      = $order['dir'] ?? 'asc';
            $this->db->order_by($colName, $dir);
        } else if (isset($opts['order'])) {
            $defOrder = $opts['order'];
            $this->db->order_by(key($defOrder), $defOrder[key($defOrder)]);
        }
        // === PAGINATION ===
        $length = (int) $this->input->post('length');
        $start  = (int) $this->input->post('start');
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        // === SELECT kolom ===
        $data = $this->db->select($opts['select'])->get()->result_array();
        
        // === HITUNG TOTAL & FILTERED ===
        $recordsFiltered = $this->_countFiltered($where, $options);
        $recordsTotal    = $this->db->count_all($table);
        
        return [
            'draw'            => intval($this->input->post('draw')),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data
        ];
    }
    /**
     * Datatable count data
     */
    private function _countFiltered($where, $options = [])
    {
        // Merge default dengan parameter yang dikirim
        $opts = array_merge($this->defaultOptions, $options);
        
        $table = !empty($opts['join']) ? "{$this->table} {$opts['alias']}" : $this->table;
        $this->db->select($opts['select'])->from($table);
        // === JOIN ===
        if (!empty($opts['join'])) {
            foreach ($opts['join'] as $join) {
                $alias = $join[0] ?? '';
                $on    = $join[1] ?? '';
                $type  = $join[2] ?? 'inner';
                if ($alias && $on) { $this->db->join($alias, $on, $type); }
            }
        }
        // === WHERE ===
        if (!empty($where)) {
            $this->db->where($where);
        }
        // === PENCARIAN ===
        $search_val = $this->input->post('search')['value'] ?? '';
        $search_trim = trim($search_val);
        $search = preg_replace('/[^a-zA-Z0-9\s\.\-]/', '', $search_trim);

        if (!empty($search) && strlen($search) >= 3 && !empty($opts['searchable'])) {

            $this->db->group_start();
            foreach ($opts['searchable'] as $i => $col) {
                if ($i === 0) {
                    $this->db->like($col, $search);
                } else {
                    $this->db->or_like($col, $search);
                }
            }
            $this->db->group_end();
        }
        if (!empty($opts['group'])) {
            $this->db->group_by($opts['group']);
            $query = $this->db->get_compiled_select();
            
            return $this->db->query("SELECT COUNT(*) AS cnt FROM ($query) AS t")->row()->cnt;
        }
        return $this->db->count_all_results();
    }
}
