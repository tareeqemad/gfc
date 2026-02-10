<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 09/03/23
 * Time: 10:40ص
 */
class Works_teams_rent extends MY_Controller {

    var $MODEL_NAME= 'Works_teams_model';
    var $PAGE_URL= 'ratio_emp_lost/Works_teams_rent/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('Root/New_rmodel');

        $this->team = $this->input->post('team');
        $this->branch_no = $this->input->post('branch_no');
        $this->the_month = $this->input->post('the_month');
        $this->activity_no = $this->input->post('activity_no');

    }

    function index()
    {
        $data['content']='works_teams_rent_index';
        $data['title']='أجرة الأعمال للفرق';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->team!= null)? "and M.TEAM = '{$this->team}' " : '';
        $where_sql.= ($this->branch_no!= null)? "and M.BRANCH_NO = '{$this->branch_no}' " : '';
        $where_sql.= ($this->the_month!= null)? "and M.THE_MONTH = '{$this->the_month}' " : '';
        $where_sql.= ($this->activity_no!= null)? "and M.ACTIVITY_NO = '{$this->activity_no}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' WORKS_TEAMS_TB  M '.$where_sql);
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
        $this->load->view('works_teams_rent_page',$data);

    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');

        $data['activity_no'] = $this->constant_details_model->get_list(492);
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['get_teams'] = $this->{$this->MODEL_NAME}->get_teams();

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }


}