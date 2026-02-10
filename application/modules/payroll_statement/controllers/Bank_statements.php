<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 19/09/22
 * Time: 11:30 ص
 */
class Bank_statements extends MY_Controller {

    var $MODEL_NAME= 'Bank_statements_model';
    var $PAGE_URL= 'payroll_statement/Bank_statements/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->rmodel->package = 'PAYROLL_STATEMENT_PKG';

        $this->from_month = $this->input->post('from_month');
        $this->to_month = $this->input->post('to_month');
        $this->bank_names = $this->input->post('bank_names');
        $this->branch_id = $this->input->post('branch_id');
        $this->emp_no = $this->input->post('emp_no');
        $this->emp_id = $this->input->post('emp_id');
        $this->master_bank = $this->input->post('master_bank');


    }

    function index()
    {
        $data['content']='bank_statements_index';
        $data['title']='كشوفات البنوك';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');



        $where_sql= "";
        $where_sql .= ($this->bank_names != null) ? " and A.BANK IN (" . $this->array_to_char($this->bank_names) . ") " : '';

        $where_sql.= ($this->emp_no!= null)? " and A.EMP_NO = '{$this->emp_no}' " : '';
        $where_sql.= ($this->emp_id!= null)? " and EMPLOYEES.ID = '{$this->emp_id}' " : '';
        $where_sql.= ($this->master_bank!= null)? " and DATA.BANKS.MASTER_BANK = '{$this->master_bank}' " : '';

        $where_sql .= isset($this->from_month) && $this->from_month != null ? " AND  A.MONTH >= '{$this->from_month}' " : "";
        $where_sql .= isset($this->to_month) && $this->to_month != null ? " AND  A.MONTH <= '{$this->to_month}' " : "";

        $where_sql.= ($this->branch_id!= null)? " and EMP_PKG.GET_EMP_BRANCH_FROM_DATA(A.EMP_NO)= '{$this->branch_id}' " : '';
//         echo $where_sql;
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(" DATA.ADMIN A, DATA.EMPLOYEES, DATA.BANKS,DATA.SUM_SALARY_NO_BOUNS S ,DATA.SUM_IS_ADD_1_BOUNS S_B
        WHERE (A.EMP_TYPE<>5
        AND A.NET_SALARY<>0)
        AND  ((A.EMP_NO=DATA.EMPLOYEES.NO)
        AND (A.BANK=DATA.BANKS.NO))
        AND S.MONTH(+) = A.MONTH
        AND S.EMP_NO(+) =A.EMP_NO
        AND S_B.MONTH(+) = A.MONTH
        AND S_B.EMP_NO(+) = A.EMP_NO  {$where_sql}");

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
        $this->load->view('bank_statements_page',$data);

    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('root/Rmodel');
        $this->load->model('hr_attendance_model');

        $data['bank_names'] = $this->Rmodel->getAll('PAYROLL_STATEMENT_PKG', 'BANK_NAMES_LIST');
        $data['master_banks'] = $this->Rmodel->getAll('PAYROLL_STATEMENT_PKG', 'MASTER_BANKS_LIST');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function array_to_char($parm)
    {
        if (!is_array($parm))
            return null;
        else {
            $parm = implode(",", $parm);
            return $parm;
        }
    }


}