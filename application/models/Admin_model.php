<?php

/**
    @project : SISKA
    @date    : Jan 7, 2016, 6:48:32 PM
    @author  : Yusuf N. Mambrasar, S.Kom
    @email   : yusuf_mambrasar@yahoo.com
    @company : CV. Uchupx Solution
*/

class Admin_model extends CI_Model
{
    
    /**
     * Table primary key
     * @var string $primary_key
     */
    private $primary_key = 'admin_id';
    
    /**
     * Table name
     * @var string $table_name
     */
    private $table_name = 'admin';
    
    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }
    
    /**
     * Get paged list from table
     * @param int $limit
     * @param int $offset
     * @param string $order_column
     * @param string $order_type
     * @param string $search
     * @return array
     */
    function get_paged_list(
            $limit=10, 
            $offset=0, 
            $order_column='', 
            $order_type='asc',
            $search='')
    {
        if (empty($order_column)||empty($order_type))
            $this->db->order_by($this->primary_key,'asc');         
        else         
            $this->db->order_by($order_column, $order_type);
        if(!empty($search)) {
            $this->db->where("status >= 0 AND (name_first LIKE '%$search%' OR name_last LIKE '%$search%')");
        }else{
            $this->db->where('status >= 0');
        }
        return $this->db->get($this->table_name,$limit,$offset);
    }
    
    /**
     * Count all item on table
     * @param string $search
     * @return int
     */
    function count_all($search='')
    {
        if(!empty($search)) {
            $this->db->where("status >= 0 AND (name_first LIKE '%$search%' OR name_last LIKE '%$search%')");
        }else{
            $this->db->where('status >= 0');
        }
        $query=$this->db->get($this->table_name);
        return $query->num_rows();
    }
    
    /**
     * Get item by id
     * @param int $admin_id
     * @return array
     */
    function get_by_id($admin_id)
    {
        $this->db->where($this->primary_key,$admin_id);
        $admin = $this->db->get($this->table_name)->row_array();
        $user = $this->user_model->get_by_id($admin['user_id']);
        $person['admin_id'] = $admin['admin_id'];
        $person['user_id'] = $admin['user_id'];
        $person['name_first'] = $admin['name_first'];
        $person['name_last'] = $admin['name_last'];
        $person['pin'] = $user['pin'];
        $person['email'] = $user['email'];
        $person['status'] = $admin['status'];
        return $person;
    }
    
    /**
     * Insert new item to table
     * @param array $person
     * @return int
     */
    function insert($person)
    {
        $admin['user_id']=$this->user_model->insert($person);
        $admin['name_first']=$person['name_first'];
        $admin['name_last']=$person['name_last'];
        $admin['date_create']=$this->user_model->_get_date_now();
        $admin['date_update']=$this->user_model->_get_date_empty();
        $admin['date_delete']=$this->user_model->_get_date_empty();
        $admin['status']=$person['status'];
        $this->db->insert($this->table_name, $admin);
        return $this->db->insert_id();
    }
    
    /**
     * Update item on table
     * @param id $admin_id
     * @param array $person
     */
    function update($admin_id, $person)
    {
        $this->user_model->update($person['user_id'],$person);
        $admin['name_first']=$person['name_first'];
        $admin['name_last']=$person['name_last'];
        $admin['date_update']=$this->user_model->_get_date_now();
        $admin['status']=$person['status'];
        $this->db->where($this->primary_key,$admin_id);
        $this->db->update($this->table_name,$admin);
    }
    
    /**
     * Add item to trash
     * @param int $person_id
     */
    function delete($admin_id)
    {
        $admin['date_delete']=$this->user_model->_get_date_now();
        $admin['status']=-1;
        $this->db->where($this->primary_key,$admin_id);
        $this->db->update($this->table_name,$admin);
    }
       
}