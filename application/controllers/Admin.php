<?php

/**
    @project : SISKA
    @date    : Jan 7, 2016, 6:46:03 PM
    @author  : Yusuf N. Mambrasar, S.Kom
    @email   : yusuf_mambrasar@yahoo.com
    @company : CV. Uchupx Solution
*/

class Admin extends CI_Controller
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
        $this->load->model('admin_model','',TRUE);
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
            $order_column='admin_id',
            $order_type='asc',
            $search='')
    {
        if(empty($offset)||$offset=='add_success') $offset=0;
        if(empty($order_column)) $order_column = 'admin_id';
        if(empty($order_type)) $order_type='asc';
        if(empty($search)) 
            $search='';
        else
            $search=$this->user_model->_search_decode($search);
        
        if(!empty($this->input->post('search'))) $search=$this->input->post('search');
        
        $data['link_create']=anchor('admin/insert','Baru');
        $data['form_action']=  site_url('admin/index');
        $data['search']=$search;
        $data['message']='';
        $search_encode = $this->user_model->_search_encode($search);
        
        $persons=$this->admin_model->get_paged_list(
                $this->limit,
                $offset,
                $order_column,
                $order_type,
                $search)->result();
        
        $this->load->library('pagination');
        $config['base_url']=site_url('admin/index');
        $config['first_url']=site_url('admin/index/0/'.$order_column.'/'.$order_type.'/'.$search_encode);
        $config['suffix']='/'.$order_column.'/'.$order_type.'/'.$search_encode;
        $config['total_rows']=$this->admin_model->count_all($search);
        $config['per_page']=$this->limit;
        $config['uri_segment']=3;
        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();
        
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $order_type_new = ($order_type=='asc'?'desc':'asc');
        $this->table->set_heading(
                '#',
                anchor('admin/index/'.$offset.'/name_first/'.$order_type_new.'/'.$search_encode,'First'),
                anchor('admin/index/'.$offset.'/name_last/'.$order_type_new.'/'.$search_encode,'Last'),
                'PIN',
                'Email',
                'Status'
                );
        $i=0+$offset;
        foreach($persons as $person)
        {
            $user = $this->user_model->get_by_id($person->user_id);
            if($person->status==1)
                $status = 'Aktif';
            elseif($person->status==2) 
                $status = 'Tidak Aktif';
            elseif($person->status==3) 
                $status = 'Pindah';
            elseif($person->status==4) 
                $status = 'Keluar';
            
            $this->table->add_row(
                    ++$i,
                    anchor('admin/update/'.$person->admin_id,$person->name_first),
                    anchor('admin/update/'.$person->admin_id,$person->name_last),
                    $user['pin'],
                    $user['email'],
                    $status
                    );
        }
        $data['table']=$this->table->generate();
        
        if ($this->uri->segment(3)=='delete_success')
            $data['message']='Data berhasil dihapus';
        else if ($this->uri->segment(3)=='add_success')
            $data['message']='Data berhasil ditambah';
        
        $this->load->view('admin/list',$data);
        
    }
    
    /**
     * Insert new item to table
     */
    
    function insert()
    {
        $data['form_action']=site_url('admin/insert');
        $data['link_back']=anchor('admin/index/','Kembali');
        $data['link_delete']='';
        $data['message']='';
        
        $this->_set_rules();
        
        if($this->form_validation->run()===FALSE)
        {
            $data['person']=array(
                'admin_id' => '[auto]',
                'user_id' => 0,
                'pin'=>$this->user_model->_get_pin_random(),
                'name_first' => '',
                'name_last' => '',
                'email' => '',
                'status' => 0
            );
            $this->load->view('admin/view',$data);
        }
        else 
        {
            $person = array(
                'pin' => $this->input->post('pin'),
                'name_first' => $this->input->post('name_first'),
                'name_last' => $this->input->post('name_last'),
                'password' => $this->input->post('password'),
                'email' => $this->input->post('email'),
                'status' => $this->input->post('status')
            );
            $person_id=$this->admin_model->insert($person);
            $this->validation->id=$person_id;
            redirect('admin/index/add_success');
        }
        
    }
        
    /**
     * Update item detail
     * @param int $admin_id
     */
    function update($admin_id)
    {
        $data['link_back']=anchor('admin/index','Kembali');
        $data['link_delete']=anchor(
                'admin/delete/'.$admin_id,
                'Hapus',
                array('onclick'=>"return confirm('Apakah Anda yakin akan menghapus data ini?')"));
        $data['form_action']=site_url('admin/update/'.$admin_id);
        $data['message']='';
        $this->load->library('form_validation');
        
        $this->_set_rules(FALSE);
        
        if($this->form_validation->run()===FALSE)
        {
            $data['person']=$this->admin_model->get_by_id($admin_id);
        }
        else
        {
            /** FIXME: Update Data admin_model without user_id **/
            $person = array(
                'admin_id' => $admin_id,
                'user_id' => $this->input->post('user_id'),
                'pin' => $this->input->post('pin'),
                'name_first' => $this->input->post('name_first'),
                'name_last' => $this->input->post('name_last'),
                'password' => $this->input->post('password'),
                'email' => $this->input->post('name_first'),
                'status' => $this->input->post('status')
            );
            $this->admin_model->update($admin_id,$person);
            $data['person']=$this->admin_model->get_by_id($admin_id);
            $data['message']='Update user sukses';
        }
        $this->load->view('admin/view',$data);
    }
    
    /**
     * Delete item
     * @param int $admin_id
     */
    function delete($admin_id)
    {
        $this->admin_model->delete($admin_id);
        redirect('admin/index/delete_success','refresh');
    }
    
    /**
     * Set rules for form validation
     * @param boolean $insert
     */
    function _set_rules($insert=TRUE)
    {
        $this->form_validation->set_rules(
                'pin','PIN','trim|required');
        $this->form_validation->set_rules(
                'name_first','Nama Depan','trim|required');
        $this->form_validation->set_rules(
                'name_last','Nama Belakang','trim');
        if($insert){
            $this->form_validation->set_rules(
                    'password','Password','trim|required');
            $this->form_validation->set_rules(
                    'password_confirm','Konfirmasi Password','trim|required|matches[password]');
        }else{
            $this->form_validation->set_rules(
                    'password','Password','trim');
            $this->form_validation->set_rules(
                    'password_confirm','Konfirmasi Password','trim|matches[password]');
        }
        $this->form_validation->set_rules(
                'email','Email','trim|required|valid_email');
        $this->form_validation->set_rules(
                'status','Status','required');
    }
    
}