<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 13/04/20
 * Time: 09:22 ص
 */
class No_reson_name extends MY_Controller
{

    var $PAGE_URL = 'payroll_data/no_reson_name/get_page';


    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

    }

    function index($page = 1)
    {

        $data['title'] = 'الغير ملتزمين بالانصراف  | الشؤون الادارية';
        $data['content'] = 'no_reson_name_index';
        $data['page'] = $page;
        $data['no_signed_reson_con'] = $this->rmodel->getAll('TRANSACTION_PKG', 'NO_SIGNED_RESON_ALL'); //array of constant
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);

    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND M.EMP_BRANCH IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
            WHERE    GCC_BRAN = '{$this->p_branch_no}' ) " : '';
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.THE_MONTH = '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.NO = '{$this->p_emp_no}'  " : "";
        $where_sql .= isset($this->p_reson_name) && $this->p_reson_name != null  && $this->p_reson_name != 'IS_NULL' ? " AND  M.RESON = '{$this->p_reson_name}'  " : "";
        $where_sql .= isset($this->p_reson_name) && $this->p_reson_name == 'IS_NULL' ? " AND  M.RESON   IS NULL  " : "";
        $where_sql .= isset($this->p_is_active) && $this->p_is_active != null ? " AND  M.IS_ACTIVE = '{$this->p_is_active}'  " : "";
        $where_sql .= isset($this->p_from_the_date) && $this->p_from_the_date != null ? " AND  TO_DATE(M.TIME_I) >= '{$this->p_from_the_date}'  " : "";
        $where_sql .= isset($this->p_to_the_date) && $this->p_to_the_date != null ? " AND    TO_DATE(M.TIME_I) <= '{$this->p_to_the_date}'  " : "";
        $where_sql .= isset($this->p_is_shift_emp) && $this->p_is_shift_emp != null ? " AND  TRANSACTION_PKG.CHK_IN_SHIFT_EMPLOYEE_TB(M.NO) = '{$this->p_is_shift_emp}'  " : "";
        //echo $where_sql;
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.NO_RESON_NAME  M WHERE 1 =1 ' . $where_sql);
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
        $data["page_rows"] = $this->rmodel->getList('NO_RESON_NAME_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('no_reson_name_page', $data);

    }

    function update_is_active()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $p_ser = $this->input->post('p_ser');
            $emp_no = $this->input->post('emp_no');
            $the_month = $this->input->post('the_month');
            $reson_in = $this->input->post('reson_in');
            $data_arr = array(
                array('name' => 'P_SER_IN', 'value' => $p_ser, 'type' => '', 'length' => -1),
                array('name' => 'NO_IN', 'value' => $emp_no, 'type' => '', 'length' => -1),
                array('name' => 'THE_MONTH_IN', 'value' => $the_month, 'type' => '', 'length' => -1),
                array('name' => 'RESON_IN', 'value' => $reson_in, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('NO_RESON_NAME_UPDATE', $data_arr);
            print_r($res);
        }

    }

    //ترحيل الموظفين الغير ملتزمين من تاريخ لتاريخ ومقر
    function public_no_reson_name_trans(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $from_the_date = $this->input->post('from_the_date');
            $to_the_date = $this->input->post('to_the_date');
            $branch_no = $this->input->post('branch_no');
                $data_arr = array(
                    array('name' => 'FROM_DATE_IN', 'value' => $from_the_date, 'type' => '', 'length' => -1),
                    array('name' => 'TO_DATE_IN', 'value' => $to_the_date, 'type' => '', 'length' => -1),
                    array('name' => 'EMP_BRANCH_IN', 'value' => $branch_no, 'type' => '', 'length' => -1),
                );
                $res = $this->rmodel->insert('NO_RESON_NAME_TRANS', $data_arr);
                echo $res;
        }
    }

    function _look_ups(&$data)
    {

        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');

        $this->load->model('settings/constant_details_model');
        $data['is_active_cons'] = $this->constant_details_model->get_list(442);
    }

}