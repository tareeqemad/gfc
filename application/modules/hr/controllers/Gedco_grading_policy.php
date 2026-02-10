<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 26/09/16
 * Time: 11:57 ص
 */

class Gedco_grading_policy extends MY_Controller{

    var $MODEL_NAME= 'gedco_grading_policy_model';
    var $PAGE_URL= 'hr/gedco_grading_policy/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('hr/evaluation_order_model');
        $this->load->model('settings/gcc_branches_model');
        $this->evaluation_order_id= $this->input->post('evaluation_order_id');
        $this->admin_id= $this->input->post('admin_id');
    }

    function index(){
        $data['title']='سياسة توزيع الدرجات للإدارات';
        $data['order_id'] = $this->evaluation_order_model->get_all();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['content']='gedco_grading_policy_index';
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $evaluation_order_id= -1, $admin_id= -1){
        $this->load->library('pagination');

        $evaluation_order_id= $this->check_vars($evaluation_order_id,'evaluation_order_id');
        $admin_id= $this->check_vars($admin_id,'admin_id');

        $where_sql= " where 1=1 ";
        $where_sql.= ($evaluation_order_id!= null)? " and m.evaluation_order_id= '{$evaluation_order_id}' " : '';
        $where_sql.= ($admin_id!= null)? " and m.admin_id= '{$admin_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' GEDCO_GRADING_POLICY_TB m '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('gedco_grading_policy_page',$data);

    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
}