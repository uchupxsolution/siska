<?php

/**
    @project : SISKA
    @date    : Jan 6, 2016, 6:09:54 AM
    @author  : Yusuf N. Mambrasar, S.Kom
    @email   : yusuf_mambrasar@yahoo.com
    @company : CV. Uchupx Solution
*/

class User extends CI_Controller 
{
    /**
     * Limit paged list
     * @var int
     */
    private $limit = 10;
    
    /**
     * Construct
     */
    function __construct() {
        parent::__construct();
        $this->load->library(array('table','form_validation'));
        $this->load->helper(array('form','url'));
        $this->load->model('user_model','',TRUE);
    }
    
    /**
     * Index page
     * @param int $offset
     * @param string $order_column
     * @param string $order_type
     * @param string $search
     */
    function index(
            $offset=0,
            $order_column='user_id',
            $order_type='asc',
            $search='')
    {
        if(empty($offset)) $offset=0;
        if(empty($order_column)) $order_column = 'user_id';
        if(empty($order_type)) $order_type='asc';
        if(empty($search)) 
            $search='';
        else
            $search=$this->_search_decode($search);
        if(!empty($this->input->post('search'))) $search=$this->input->post('search');
        
        $data['link_create']=anchor('user/insert','Baru');
        $data['action']=  site_url('user/index');
        $data['search']=$search;
        $data['message']='';
        
        $users=$this->user_model->get_paged_list(
                $this->limit,
                $offset,
                $order_column,
                $order_type,
                $search)->result();
        $search_encode = $this->_search_encode($search);
        $this->load->library('pagination');
        $config['base_url']=site_url('user/index');
        $config['first_url']=site_url('user/index/0/'.$order_column.'/'.$order_type.'/'.$search_encode);
        $config['suffix']='/'.$order_column.'/'.$order_type.'/'.$search_encode;
        $config['total_rows']=$this->user_model->count_all($search);
        $config['per_page']=$this->limit;
        $config['uri_segment']=3;
        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();
        
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $order_type_new = ($order_type=='asc'?'desc':'asc');
        $this->table->set_heading(
                '#',
                anchor('user/index/'.$offset.'/name/'.$order_type_new.'/'.$search_encode,'Name'),
                anchor('user/index/'.$offset.'/pin/'.$order_type_new.'/'.$search_encode,'PIN'),
                anchor('user/index/'.$offset.'/date_create/'.$order_type_new.'/'.$search_encode,'Created'),
                anchor('user/index/'.$offset.'/date_update/'.$order_type_new.'/'.$search_encode,'Updated')
                );
        $i=0+$offset;
        foreach($users as $user)
        {
            $this->table->add_row(
                    ++$i,
                    anchor('user/update/'.$user->user_id,$user->name),
                    $user->pin,
                    $user->date_create,
                    $user->date_update
                    );
        }
        $data['table']=$this->table->generate();
        
        if ($this->uri->segment(3)=='delete_success')
            $data['message']='Data berhasil dihapus';
        else if ($this->uri->segment(3)=='add_success')
            $data['message']='Data berhasil ditambah';
        
        $this->load->view('user/list',$data);
        
    }
    
    /**
     * Insert new item to table
     */
    
    function insert()
    {
        $data['title']='Tambah User Baru';
        $data['action']=site_url('user/insert');
        $data['link_back']=anchor('user/index/','Kembali');
        $data['link_delete']='';
        $data['message']='';
        
        $this->_set_rules();
        
        if($this->form_validation->run()===FALSE)
        {
            $data['user']=array(
                'user_id' => '[auto]',
                'name' => '',
                'pin' => $this->user_model->_get_pin_random(),
                'status' => 0
            );
            $this->load->view('user/view',$data);
        }
        else 
        {
            $user = array(
                'name' => $this->input->post('name'),
                'pin' => $this->input->post('pin'),
                'password' => $this->input->post('password'),
                'status' => $this->input->post('status')
            );
            $user_id=$this->user_model->insert($user);
            $this->validation->id=$user_id;
            redirect('user/index/add_success');
        }
        
    }
        
    /**
     * Update item detail
     * @param int $user_id
     */
    function update($user_id)
    {
        $data['title']='Update Data User';
        $data['link_back']=anchor('user/index','Kembali');
        $data['link_delete']=anchor(
                'user/delete/'.$user_id,
                'Hapus',
                array('onclick'=>"return confirm('Apakah Anda yakin akan menghapus data ini?')"));
        $data['action']=site_url('user/update/'.$user_id);
        $data['message']='';
        $this->load->library('form_validation');
        
        $this->_set_rules(FALSE);
        
        if($this->form_validation->run()===FALSE)
        {
            $data['user']=$this->user_model->get_by_id($user_id)->row_array();
        }
        else
        {
            $user = array(
                'name' => $this->input->post('name'),
                'pin' => $this->input->post('pin'),
                'password' => $this->input->post('password'),
                'status' => $this->input->post('status')
            );
            $this->user_model->update($user_id,$user);
            $data['user']=$this->user_model->get_by_id($user_id)->row_array();
            $data['message']='Update user sukses';
        }
        $this->load->view('user/view',$data);
    }
    
    /**
     * Delete item
     * @param int $user_id
     */
    function delete($user_id)
    {
        $this->user_model->delete($user_id);
        redirect('user/index/delete_success','refresh');
    }
    
    /**
     * Set rules for form validation
     * @param boolean $insert
     */
    function _set_rules($insert=TRUE)
    {
        $this->form_validation->set_rules(
                'name','Name','trim|required');
        $this->form_validation->set_rules(
                'pin','PIN','trim|required|trim');
        if($insert)
        {
            $this->form_validation->set_rules(
                    'password','Password','trim|required');
            $this->form_validation->set_rules(
                    'password_confirm','Konfirmasi Password','trim|required|matches[password]');
        }
        else 
        {
            $this->form_validation->set_rules(
                    'password','Password','trim');
            $this->form_validation->set_rules(
                    'password_confirm','Konfirmasi Password','trim|matches[password]');
        }
        $this->form_validation->set_rules(
                'status','Status','required');
    }
    
    function _search_encode($string){
       return str_replace('=','-',base64_encode($string));
    }
    
    function _search_decode($string){
       return str_replace('-','=',base64_decode($string));
    }
    
}