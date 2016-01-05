<?php

/**
    @project : SISKA
    @date    : Jan 6, 2016, 6:15:20 AM
    @author  : Yusuf N. Mambrasar, S.Kom
    @email   : yusuf_mambrasar@yahoo.com
    @company : CV. Uchupx Solution
*/

class User_model extends CI_Model
{
    
    /**
     * Table primary key
     * @var string $primary_key
     */
    private $primary_key = 'user_id';
    
    /**
     * Table name
     * @var string $table_name
     */
    private $table_name = 'user';
    
    /**
     * Constructor
     * @return  void
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
     * @return array
     */
    function get_paged_list(
            $limit=10, 
            $offset=0, 
            $order_column='', 
            $order_type='asc')
    {
        if ( empty($order_column) || empty($order_type) )
            $this->db->order_by($this->primary_key,'asc');         
        else         
            $this->db->order_by($order_column, $order_type);
        return $this->db->get($this->table_name,$limit,$offset);
    }
    
    /**
     * Count all item on table
     * @return int
     */
    function count_all()
    {
        return $this->db->count_all($this->table_name);
    }
    
    /**
     * Get item by id
     * @param int $id
     * @return array
     */
    function get_by_id($id)
    {
        $this->db->where($this->primary_key,$id);
        return $this->db->get($this->table_name);
    }
    
    /**
     * Insert new item to table
     * @param array $person
     * @return int
     */
    function insert($person)
    {
        $this->db->insert($this->table_name, $person);
        return $this->db->insert_id();
    }
    
    /**
     * Update item on table
     * @param id $id
     * @param array $person
     */
    function update($id,$person)
    {
        $this->db->where($this->primary_key,$id);
        $this->db->update($this->table_name,$person);
    }
    
    /**
     * Delete item from table
     * @param int $id
     */
    function delete($id)
    {
        $this->db->where($this->primary_key,$id);
        $this->db->delete($this->table_name);
    }
    
}