<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_notif extends KZ_Model {

    protected $id = 'id_notif';
    protected $table = 'yk_notif';
    protected $uuid = true;
    
    private $alias = 'n';
    private $select = 'n.*, u.username, u.fullname';
    private $join = [
        ['yk_user u','n.send_id = u.id_user','left']
    ];
            
    function __construct()
    {
        parent::__construct();
    }
    function getAll($where = NULL, $limit = '')
    {
        $options['alias'] = $this->alias;
        $options['select'] = $this->select;
        $options['join'] = $this->join;
        $options['order'] = ['buat_notif','desc'];
        $options['limit'] = $limit;
        
        return parent::all($where, $options);
    }
    function insertAll($value, $level)
    {
        $this->db->trans_start();
        $user_id = NULL;
        
        switch($level) {
            case 1 :
                $user_id = $value['send_id'];
                break;
            case 2 :
                $this->db->select('user_id')->from('tmp_shop')->where('shop_id', $value['send_id']);
                $user_id = $this->db->get()->row_array()['user_id'];
                break;
            case 3 :
                $this->db->select('user_id')->from('tmp_cst')->where('cst_id', $value['send_id']);
                $user_id = $this->db->get()->row_array()['user_id'];
                break;
            default:
                $user_id = 1;
        }
        if(!is_null($user_id)){
            $data['from_id'] = $value['from_id'];
            $data['send_id'] = $user_id;
            $data['status_notif'] = '0';
            $data['subject_notif'] = $value['subject'];
            $data['msg_notif'] = $value['msg'];
            $data['link_notif'] = $value['link'];
            $data['buat_notif'] = date('Y-m-d H:i:s');
            $this->insert($data);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function insertBatch($post, $from_id)
    {
        $this->db->trans_start();
        
        $rs_user = NULL;
        switch($post['user']) {
            case 'shop' :
                $this->db->select('user_id')->from('tmp_shop');
                $rs_user = $this->db->get()->result_array();
                break;
            case 'cst' :
                $this->db->select('user_id')->from('tmp_cst');
                $rs_user = $this->db->get()->result_array();
                break;
            default:
                $row['from_id'] = $from_id;
                $row['send_id'] = decode($post['user']);
                $row['subject_notif'] = $post['subject'];
                $row['msg_notif'] = $post['pesan'];
                $row['link_notif'] = $post['link'];
                $row['status_notif'] = '0';
                $row['buat_notif'] = date('Y-m-d H:i:s');
                
                $this->insert($row);
                $this->db->trans_complete();
                return $this->db->trans_status();
        }
        $data = array();
        foreach ($rs_user as $item) {
            $row = array();
            $row['from_id'] = $from_id;
            $row['send_id'] = $item['user_id'];
            $row['subject_notif'] = $post['subject'];
            $row['msg_notif'] = $post['pesan'];
            $row['link_notif'] = $post['link'];
            $row['status_notif'] = '0';
            $row['buat_notif'] = date('Y-m-d H:i:s');

            $data[] = $row;
        }
        $this->db->insert_batch($this->table, $data);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function deleteBatch($arr_id = [])
    {
        $this->db->trans_start();
        $this->db->where_in($this->id, $arr_id)->delete($this->table);
        $this->db->trans_complete();
        
        return ($this->db->trans_status()) ? $this->db->affected_rows() : 0;
    }
    function getEmpty()
    {
        return [
            $this->id => null,
            'subject_notif' => null,
            'msg_notif' => null,
            'link_notif' => null
        ];
   }
    function getDatatables($where = []) 
    {
        $options = [
            'alias'      => $this->alias,
            'select'     => $this->select,
            'join'       => $this->join,
            'columns'    => [null,'fullname','subject_notif','msg_notif','buat_notif',null],
            'searchable' => ['fullname','subject_notif'],
            'order'      => ['buat_notif' => 'desc']
        ];

        $result = parent::datatable($where, $options);
        $data = [];
        $no = $this->input->post('start');
        foreach ($result['data'] as $items) {
            $no++;
            $btn_aksi = ($this->sessionlevel != '1') ? '' : '<a href="'. site_url($items['link_notif']) .'" itemid="'. encode($items['id_notif']).'"
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" id="link-btn" data-rel="tooltip" title="Link">
                    <span class="orange"><i class="ace-icon fa fa-external-link bigger-120"></i></span>
                </a>'; 
            $btn_aksi .= '<a href="#" itemid="'.encode($items['id_notif']).'" itemprop="'.$items['fullname'].' - '.$items['subject_notif'].'" id="delete-btn" 
                class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            $box = '<label class="pos-rel">
                <input value="'.encode($items['id_notif']).'" type="checkbox" class="ace" id="checkboxData" name="dataCheckbox[]" />
                <span class="lbl"></span></label>';
            $status = ($items['status_notif'] == '1') ? '<i class="bigger-120 fa fa-check green"></i>' : '';
            $message = !in_array($items['subject_notif'], load_array('bank')) ? $items['msg_notif']
                : '<span id="log-msg" itemid="'.$items['subject_notif'].'" itemname="'. base64_encode($items['msg_notif']).'">'.limit_text($items['msg_notif'],100).'</span>';
            
            $row = [];
            $row[] = $status.' '.$no.' '.$box;
            $row[] = '<strong>'.$items['fullname'].'</strong>';
            $row[] = '<strong>'.$items['subject_notif'].'</strong>';
            $row[] = $message;
            $row[] = '<small>'.format_date($items['buat_notif'],0).'</small>';
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $result['draw'],
            "recordsTotal" => $result['recordsTotal'],
            "recordsFiltered" => $result['recordsFiltered'],
            "data" => $data,
        );
        return $output;
    }
}
