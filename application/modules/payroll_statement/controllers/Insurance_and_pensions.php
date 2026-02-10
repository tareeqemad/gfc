<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 21/09/22
 * Time: 08:00 ص
 */
class Insurance_and_pensions extends MY_Controller {

    var $MODEL_NAME= 'Insurance_and_pensions_model';
    var $PAGE_URL= 'payroll_statement/Insurance_and_pensions/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->rmodel->package = 'PAYROLL_STATEMENT_PKG';

        $this->emp_no = $this->input->post('emp_no');
        $this->branch_id = $this->input->post('branch_id');
        $this->from_month = $this->input->post('from_month');
        $this->to_month = $this->input->post('to_month');

    }

    function index()
    {
        $data['content']='insurance_and_pensions_index';
        $data['title']='كشوفات التأمين والمعاشات';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= "";
        $where_sql .= isset($this->from_month) && $this->from_month != null ? " AND  DATA.GET_INSURANCE.MONTH >= '{$this->from_month}' " : "";
        $where_sql .= isset($this->to_month) && $this->to_month != null ? " AND  DATA.GET_INSURANCE.MONTH <= '{$this->to_month}' " : "";
        $where_sql.= ($this->emp_no!= null)? " and DATA.GET_INSURANCE.EMP_NO = '{$this->emp_no}' " : '';
        $where_sql.= ($this->branch_id!= null)? " and EMP_PKG.GET_EMP_BRANCH_FROM_DATA(DATA.GET_INSURANCE.EMP_NO )= '{$this->branch_id}' " : '';
        //

        //echo $where_sql;
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count("DATA.ADMIN_EMP_REP_NEW, DATA.GET_INSURANCE, DATA.GET_BS,DATA.GET_ALLOW,DATA.GET_COM1,DATA.GET_COM2,DATA.GET_PROMOTION,DATA.GET_sp,DATA.GET_LIFE
        WHERE (data.ADMIN_EMP_REP_NEW.emp_type = 1) and  ((DATA.ADMIN_EMP_REP_NEW.EMP_NO=DATA.GET_INSURANCE.EMP_NO(+))
        AND (DATA.GET_INSURANCE.MONTH(+)=DATA.ADMIN_EMP_REP_NEW.MONTH)
        AND (DATA.ADMIN_EMP_REP_NEW.EMP_NO=DATA.GET_BS.EMP_NO(+))
        AND (DATA.ADMIN_EMP_REP_NEW.EMP_NO=DATA.GET_COM1.EMP_NO(+))
        AND (DATA.ADMIN_EMP_REP_NEW.EMP_NO=DATA.GET_COM2.EMP_NO(+))
        AND (DATA.ADMIN_EMP_REP_NEW.MONTH=DATA.GET_COM1.MONTH(+))
        AND (DATA.ADMIN_EMP_REP_NEW.MONTH=DATA.GET_COM2.MONTH(+))
        AND (DATA.ADMIN_EMP_REP_NEW.EMP_NO=DATA.GET_ALLOW.EMP_NO(+))
        AND (DATA.ADMIN_EMP_REP_NEW.MONTH=DATA.GET_PROMOTION.MONTH(+))
        AND (DATA.ADMIN_EMP_REP_NEW.EMP_NO=DATA.GET_PROMOTION.EMP_NO(+))
        AND (DATA.ADMIN_EMP_REP_NEW.EMP_NO=DATA.GET_LIFE.EMP_NO(+))
        AND (DATA.ADMIN_EMP_REP_NEW.MONTH=DATA.GET_sp.MONTH(+))
        AND (DATA.ADMIN_EMP_REP_NEW.EMP_NO=DATA.GET_sp.EMP_NO(+))
        AND (DATA.ADMIN_EMP_REP_NEW.MONTH=DATA.GET_BS.MONTH(+))
        AND (DATA.ADMIN_EMP_REP_NEW.MONTH=DATA.GET_LIFE.MONTH(+))
        AND (DATA.ADMIN_EMP_REP_NEW.MONTH=DATA.GET_ALLOW.MONTH(+))) {$where_sql}");

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
        $this->load->view('insurance_and_pensions_page',$data);

    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('root/Rmodel');
        $this->load->model('hr_attendance_model');
        $this->load->model('salary/constants_sal_model');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}