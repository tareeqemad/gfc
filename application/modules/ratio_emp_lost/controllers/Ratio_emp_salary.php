<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 18/07/23
 * Time: 10:10
 */
class Ratio_emp_salary extends MY_Controller {

    var $MODEL_NAME= 'Ratio_emp_salary_model';
    var $PAGE_URL= 'ratio_emp_lost/Ratio_emp_salary/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->team = $this->input->post('team');
        $this->the_month = $this->input->post('the_month');
        $this->branch_no = $this->input->post('branch_no');
        $this->emp_no = $this->input->post('emp_no');
        $this->emp_type = $this->input->post('emp_type');

    }

    function index()
    {
        $data['content']='ratio_emp_salary_index';
        $data['title']='رواتب موظفي الفاقد';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function index_total()
    {
        $data['content']='ratio_emp_salary_total_index';
        $data['title']='اجماليات موظفي الفاقد';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->team!= null)? " and M.TEAM= '{$this->team}' " : '';
        $where_sql.= ($this->the_month!= null)? " and M.THE_MONTH= '{$this->the_month}' " : '';
        $where_sql.= ($this->branch_no!= null)? " and M.BRANCH_NO= '{$this->branch_no}' " : '';
        $where_sql.= ($this->emp_no!= null)? " and M.EMP_NO= '{$this->emp_no}' " : '';
        $where_sql.= ($this->emp_type!= null)? " and M.EMP_TYPE= '{$this->emp_type}' " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' WORKS_TEAMS_SALARY_TB  M '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('ratio_emp_salary_page',$data);

    }
    function get_page2($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->team!= null)? " and M.TEAM= '{$this->team}' " : '';
        $where_sql.= ($this->the_month!= null)? " and M.THE_MONTH= '{$this->the_month}' " : '';
        $where_sql.= ($this->branch_no!= null)? " and M.BRANCH_NO= '{$this->branch_no}' " : '';
        $where_sql.= ($this->emp_no!= null)? " and M.EMP_NO= '{$this->emp_no}' " : '';
        $where_sql.= ($this->emp_type!= null)? " and M.EMP_TYPE= '{$this->emp_type}' " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' WORKS_TEAMS_SALARY_TB  M '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list2($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('ratio_emp_salary_total_page',$data);

    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->branch_no, $this->the_month ,$case);

        if(intval($res) <= 0){
            if ($case==5){
                $this->print_error('لم تتم العملية '.'<br>'. 'يجب اعتماد الشؤون الادارية اولا  ');
            }else{
                $this->print_error('لم تتم العملية '.'<br>'. 'يجب اعتماد سحب البيانات اولا');
            }

        }
        return 1;
    }

    function adopt_4(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
            echo $this->adopt(4);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_5(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo $this->adopt(5);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['emp_type'] = $this->constant_details_model->get_list(493);
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['get_teams'] = $this->{$this->MODEL_NAME}->get_teams();
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}