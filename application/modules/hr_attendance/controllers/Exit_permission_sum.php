<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/03/18
 * Time: 09:59 ص
 */

// ser, emp_no, perm_year, sum_minutes, sum_days, branch_id

class exit_permission_sum extends MY_Controller{

    var $MODEL_NAME= 'exit_permission_sum_model';
    var $PAGE_URL= 'hr_attendance/exit_permission_sum/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->ser= $this->input->post('ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->perm_year= $this->input->post('perm_year');
        $this->sum_days= $this->input->post('sum_days');
        $this->branch_id= $this->input->post('branch_id');

        if( HaveAccess(base_url("hr_attendance/exit_permission_sum/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index($page= 1, $ser= -1, $emp_no= -1, $perm_year= -1, $sum_days= -1, $branch_id= -1 ){

        $data['title']=' تجميع اذونات الخروج';
        $data['content']='exit_permission_sum_index';

        $data['page']=$page;
        $data['ser']= $ser;
        $data['emp_no']= $emp_no;
        $data['perm_year']= $perm_year;
        $data['sum_days']= $sum_days;
        $data['branch_id']= $branch_id;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);

    }

    function get_page($page= 1, $ser= -1, $emp_no= -1, $perm_year= -1, $sum_days= -1, $branch_id= -1 ){
        $this->load->library('pagination');

        $ser= $this->check_vars($ser,'ser');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $perm_year= $this->check_vars($perm_year,'perm_year');
        $sum_days= $this->check_vars($sum_days,'sum_days');
        $branch_id= $this->check_vars($branch_id,'branch_id');

        $where_sql= " where 1=1 ";

        if(!$this->all_branches)
            $where_sql.= " and branch_id= {$this->user->branch} ";

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= ($perm_year!= null)? " and perm_year= '{$perm_year}' " : '';
        $where_sql.= ($sum_days!= null)? " and sum_days= '{$sum_days}' " : '';
        $where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' EXIT_PERMISSION_SUM_TB '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('exit_permission_sum_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        //$this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , 'hr_admin' );

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
