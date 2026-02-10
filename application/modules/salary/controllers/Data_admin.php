<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 13/10/20
 */

// EMP_NO, MONTH, EMP_TYPE, BRAN, BRANCH, DEPARTMENT, STATUS, DEGREE, PERIODICAL_ALLOWNCES, BANK, ACCOUNT, W_NO, NET_SALARY, W_NO_ADMIN, Q_NO

class data_admin extends MY_Controller{

    var $MODEL_NAME = 'data_admin_model';
    var $PAGE_URL = 'salary/data_admin/get_page';
    var $PAGE_ACT;

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->page_act = $this->input->post('page_act');
        $this->emp_no= $this->input->post('emp_no');
        $this->month_1= $this->input->post('month_1');
        $this->month_2= $this->input->post('month_2');
        $this->emp_type= $this->input->post('emp_type');
        $this->branch_id= $this->input->post('branch_id');
        $this->net_salary= $this->input->post('net_salary');
        $this->sal_con= $this->input->post('sal_con');

        /*
        my - شاشة اذونات الموظف
        hr_branch - شاشة الشؤون الادارية حسب المقر
        hr_admin - شاشة للشؤون الادارية تظهر كل الموظفين
        */


        if($this->uri->segment(3) == 'get'){
            $this->PAGE_ACT = $this->uri->segment(6);
        }else{
            $this->PAGE_ACT = $this->uri->segment(5);
        }

        if (isset($this->PAGE_ACT) and $this->PAGE_ACT != '' and ($this->uri->segment(3) == 'index' or $this->uri->segment(3) == 'get')) {

            if (!HaveAccess(base_url("salary/data_admin/index/1/" . $this->PAGE_ACT))) {
                die('Error: No Permission ' . $this->PAGE_ACT);
            }

            if ($this->PAGE_ACT == 'my') {

            } elseif ($this->PAGE_ACT == 'hr_branch') {

            } elseif ($this->PAGE_ACT == 'hr_admin') {

            } else {
                die('PAGE_ACT');
            }
        } elseif ($this->uri->segment(3) == 'index') {
            die('index');
        }

    }

    function index($page = 1, $page_act = -1, $emp_no= -1, $month_1= -1, $month_2= -1, $emp_type= -1, $branch_id= -1, $net_salary= -1, $sal_con= -1)
    {
        if (isset($this->PAGE_ACT)) {
            if ($this->PAGE_ACT == 'my') {
                $data['title'] = 'رواتب الموظف';
            } elseif ($this->PAGE_ACT == 'hr_branch') {
                $data['title'] = 'رواتب موظفي المقر';
            } elseif ($this->PAGE_ACT == 'hr_admin') {
                $data['title'] = 'رواتب كل موظفي الشركة';
            } else {
                $data['title'] = ' خطأ؟!!';
            }
        }

        $data['content'] = 'data_admin_index';

        $data['page'] = $page;
        $data['page_act'] = $page_act;
        $data['emp_no']= $emp_no;
        $data['month_1']= $month_1;
        $data['month_2']= $month_2;
        $data['emp_type']= $emp_type;
        $data['branch_id']= $branch_id;
        $data['net_salary']= $net_salary;
        $data['sal_con']= $sal_con;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template', $data);
    }


    function get_page($page = 1, $page_act = -1, $emp_no= -1, $month_1= -1, $month_2= -1, $emp_type= -1, $branch_id= -1, $net_salary= -1, $sal_con= -1)
    {
        $this->load->library('pagination');

        $page_act = $this->check_vars($page_act, 'page_act');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $month_1= $this->check_vars($month_1,'month_1');
        $month_2= $this->check_vars($month_2,'month_2');
        $emp_type= $this->check_vars($emp_type,'emp_type');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $net_salary= $this->check_vars($net_salary,'net_salary');
        $sal_con= $this->check_vars($sal_con,'sal_con');

        $where_sql = " where 1=1 ";

        $this->PAGE_ACT = $page_act;
        $data['page_act'] = $page_act;
        $data['sal_con'] = $sal_con;

        if (isset($this->PAGE_ACT)) {
            if ($this->PAGE_ACT == 'my') {
                $where_sql .= " and emp_no= {$this->user->emp_no} and month <= SALARY_EMP_PKG.GET_LAST_SALARY_MONTH ";
            } elseif ($this->PAGE_ACT == 'hr_branch') {
                $where_sql .= " and emp_pkg.get_emp_branch(emp_no)= {$this->user->branch} and month <= SALARY_EMP_PKG.GET_LAST_SALARY_MONTH ";
            } elseif ($this->PAGE_ACT == 'hr_admin') {
                $where_sql .= " and 1= 1 ";
            } else {
                $where_sql .= " and 1= 2 ";
            }
        } else {
            $where_sql .= " and 1= 2 ";
        }

        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= ($month_1!= null)? " and month >= '{$month_1}' " : '';
        $where_sql.= ($month_2!= null)? " and month <= '{$month_2}' " : '';
        $where_sql.= ($emp_type!= null)? " and emp_type= '{$emp_type}' " : '';
        $where_sql.= ($branch_id!= null)? " and emp_pkg.get_emp_branch(emp_no)= '{$branch_id}' " : '';
        $where_sql.= ($net_salary!= null)? " and net_salary between {$net_salary}-50 and {$net_salary}+50 " : '';
        $where_sql.= ($sal_con!= null)? " and EXISTS ( select 1   from data.salary s   where s.emp_no = m.emp_no   and s.month = m.month   and s.con_no = '{$sal_con}' )  " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.ADMIN M ' . $where_sql);
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

        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $sal_con, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('data_admin_page', $data);
    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        if ($c_var == 'adopt')
            $var = isset($this->{$c_var}) ? $this->{$c_var} : $var;
        else
            $var = ($this->{$c_var}) ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function get($emp_no, $month, $page_act = -1)
    {
        $result = $this->{$this->MODEL_NAME}->get($emp_no, $month);
        if (!(count($result) == 1))
            die('get');

        // اذا حاول الموظف الدخول لبيانات موظف اخر
        if($page_act=='my' and $emp_no!= $this->user->emp_no ){
            die('Error emp_no');
        }

        // اذا حاول مسؤول المقر الدخول لبيانات موظف في مقر اخر
        if($page_act=='hr_branch' and $result[0]['BRANCH_ID']!= $this->user->branch ){
            die('Error branch_id');
        }

        // منع عرض بيانات الراتب الا بعد اعتماده باستثناء صلاحية كل المقرات
        if($page_act!='hr_admin' and $result[0]['MONTH'] > $result[0]['LAST_SALARY_MONTH'] ){
            die('Error last_salary_month');
        }

        $additions= $this->{$this->MODEL_NAME}->get_salary($emp_no, $month, 1);
        $discounts= $this->{$this->MODEL_NAME}->get_salary($emp_no, $month, 0);

        $data['master_tb_data'] = $result;
        $data['additions_data'] = $additions;
        $data['discounts_data'] = $discounts;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        if ($page_act == '' or $page_act == -1) die('get:page_act');
        $data['page_act'] = $page_act;
        $data['content'] = 'data_admin_show';
        $data['title'] = 'بيانات الراتب ';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        //$this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('constants_sal_model');

        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['status_cons'] = $this->constants_sal_model->get_list(1);
        $data['q_no_cons'] = $this->constants_sal_model->get_list(8);
        $data['degree_cons'] = $this->constants_sal_model->get_list(11);
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);
        $data['bank_cons'] = $this->constants_sal_model->get_list(17);
        $data['department_cons'] = $this->constants_sal_model->get_list(7);
        $data['branch_cons'] = $this->constants_sal_model->get_list(4);
        $data['bran_cons'] = $this->constants_sal_model->get_list(5);

        $data['sal_con_cons'] = $this->constants_sal_model->get_list(25);

        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, $this->PAGE_ACT);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}