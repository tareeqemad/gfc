<?php



class Subscribers extends MY_Controller{

    var $MODEL_NAME= 'subscribers_model';
    var $MODULE_NAME= 'issues';
    var $TB_NAME= 'subscribers';
    var $PAGE_URL= 'issues/issues/public_get_page_sub';
	var $DETAILS_MODEL_NAME = 'actions_model';
    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
		$this->load->model($this->DETAILS_MODEL_NAME);
        $this->ser= $this->input->post('ser');
        $this->sub_no= $this->input->post('sub_no');
        $this->sub_name= $this->input->post('sub_name');
        $this->d_name= $this->input->post('d_name');
        $this->id= $this->input->post('id');
        $this->for_month= $this->input->post('for_month');
        $this->net_pay= $this->input->post('net_pay');
        $this->type_name= $this->input->post('type_pa');
        $this->address= $this->input->post('address');
        $this->issue_type= $this->input->post('issue_type');
        $this->branches= $this->input->post('branches');



        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
    }

    /********************************************************************************************************************************/

    /********************************************************************************************************************************/

    function _look_ups(&$data){
        add_css('combotree.css');
        add_css('datepicker3.css');
        add_js('jquery.hotkeys.js');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

		$data['types'] = $this->constant_details_model->get_list(258);
        $data['issue_type'] = $this->constant_details_model->get_list(252);
        $data['court_name'] = $this->constant_details_model->get_list(251);
        $data['paid'] = $this->constant_details_model->get_list(259);
        $data['status'] = $this->constant_details_model->get_list(253);
        $data['adopt'] = $this->constant_details_model->get_list(254);


        if ($this->user->branch==1)
        {

              $data['branches'] = $this->gcc_branches_model->get_all();
         }
        else
        {
            $data['branches'] =$this->gcc_branches_model->user_branch($this->user->id);

        }





    }

    /********************************************************************************************************************************/

    function check_vars($var, $c_var){
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        $var= $var == -1 ?null:$var;
        return $var;
    }

    /********************************************************************************************************************************/

    function index($page=1,$sub_no= -1,$sub_name= -1,$id= -1,$for_month= -1,$branches=-1){

        $data['page'] = $page;
        $data['title']='القضايا القانونية للمشتركين';
        $data['content']='subscribers_index';
        $data['sub_no']= $sub_no;
        $data['sub_name']= $sub_name;
        $data['id']= $id;
        $data['for_month']= $for_month;
        $data['branches']= $branches;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }

    /********************************************************************************************************************************/

    function public_get_page_sub($page = 1,$sub_no= -1,$sub_name= -1,$id= -1,$d_name='')
    {
        $this->load->library('pagination');
        $sub_no=$this->check_vars($sub_no,'sub_no');
        $sub_name=$this->check_vars($sub_name,'sub_name');
        $id=$this->check_vars($id,'id');
        $d_name=$this->check_vars($d_name,'d_name');
        //$d_name=$this->check_vars($d_name,'d_name');

        $where_sql = "where 1=1";
        $where_sql.= ($sub_name!= null)? " and sub_name like '".add_percent_sign($sub_name)."' " : '';
        $where_sql.= ($sub_no!= null)? " and sub_no= '{$sub_no}' " : '';
        $where_sql.= ($id!= null)? " and id= '{$id}' " : '';
        $where_sql.= ($d_name!= null)? " and d_name like '".add_percent_sign($d_name)."' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('LAW_SUBSCRIBER_INFO_TB', $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = (((($page) - 1) * $config['per_page']));
        $row = ((($page) * $config['per_page']));

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_sub($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('subscribers_page', $data);

    }

    /********************************************************************************************************************************/






}