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
     * PIN Length
     * @var int 
     */
    private $pin_length = 10;
    
    /**
     * Salt Length
     * @var int 
     */
    private $salt_length = 10;
    
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
        if(!empty($search))
            $this->db->like('name',$search);
        $this->db->where('status >=',0);
        return $this->db->get($this->table_name,$limit,$offset);
    }
    
    /**
     * Count all item on table
     * @param string $search
     * @return int
     */
    function count_all($search='')
    {
        if(!empty($search))
            $this->db->like('name',$search);
        $this->db->where('status >=',0);
        $query=$this->db->get($this->table_name);
        return $query->num_rows();
    }
    
    /**
     * Get item by id
     * @param int $user_id
     * @return array
     */
    function get_by_id($user_id)
    {
        $this->db->where($this->primary_key,$user_id);
        return $this->db->get($this->table_name);
    }
    
    /**
     * Insert new item to table
     * @param array $person
     * @return int
     */
    function insert($person)
    {
        $person['salt']=$this->_get_salt_random();
        $person['date_create']=$this->_get_date_now();
        $person['date_update']=$this->_get_date_empty();
        $person['date_delete']=$this->_get_date_empty();
        $this->db->insert($this->table_name, $person);
        return $this->db->insert_id();
    }
    
    /**
     * Update item on table
     * @param id $user_id
     * @param array $person
     */
    function update($user_id,$person)
    {
        $person['salt']=$this->get_by_id($user_id)->row_array()['salt'];
        if(!empty($person['password']))
            $person['password']=$this->_format_password ($person);
        $person['date_update']=$this->_get_date_now();
        $this->db->where($this->primary_key,$user_id);
        $this->db->update($this->table_name,$person);
    }
    
    /**
     * Add item to trash
     * @param int $user_id
     */
    function delete($user_id)
    {
        $person['status']=-1;
        $person['date_delete']=$this->_get_date_now();
        $this->db->where($this->primary_key,$user_id);
        $this->db->update($this->table_name,$person);
    }
    
/**
     * Get Random PIN
     * @return string
     */
    function _get_pin_random()
    {
        return substr(md5(date('HisYmd')),2,$this->pin_length);
    }
    
    /**
     * Get Random Salt
     * @return string
     */
    function _get_salt_random()
    {
        return substr(md5(date('HYimsd')),5,$this->salt_length);
    }
    
    /**
     * Get date now from system
     * @param string $format
     * @return string
     */
    function _get_date_now($format='Y-m-d H:i:s')
    {
        return date($format);
    }
    
    /**
     * Get empty date
     * @param string $format
     * @return string
     */
    function _get_date_empty($format='Y-m-d H:i:s')
    {
        return date($format,  strtotime(0));
    }
    
    /**
     * 
     * @param array $person
     * @return string
     */
    function _format_password($person){
        return md5($person['password'].$person['salt']);
    }
    
}