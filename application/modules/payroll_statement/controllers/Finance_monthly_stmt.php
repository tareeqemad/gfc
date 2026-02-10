<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 02/09/2022
 * Time: 21:47
 */
class Finance_monthly_stmt extends MY_Controller
{

    var $PAGE_URL = 'payroll_statement/Finance_monthly_stmt/get_page';

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'PAYROLL_STATEMENT_PKG';

    }

    function index()
    {

        $data['title'] = 'كشف اجماليات الرواتب حسب المالية';
        $data['content'] = 'Finance_monthly_stmt_index';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get_page(){
        $month = $this->input->post('month');
        $branch = '';
        if (intval($month) > 0) {
            $data['curr_month'] = $month;
            $prev_month_arr =  $this->rmodel->get('SALARY_GET_PREV_MONTH', $month);
            $data['prev_month'] = $prev_month_arr[0]['PREV_MONTH'];
            /*********اجمالي الموظفين المصروف لهم راتب في الشهر الحالي والسابق **********/
            $data['Total_Emp_arr'] = $this->rmodel->getTwoColum('PAYROLL_STATEMENT_PKG', 'TOTAL_EMP_NET_SALARY', $month, $branch);
            /*----------اجماليات بنود الاستحقاق المصروف من الراتب ---------------*/
            $data['Add_Clauses_arr'] = $this->rmodel->get('SALARY_ADD_CLAUSES_GET', $month);
            /*----------------اجماليات بنود الاستقطاع المستقطع من الرواتب -----------------*/
            $data['Ded_Clauses_arr'] = $this->rmodel->get('SALARY_DEDUCTION_CLAUSES_GET', $month);
            $this->load->view('Finance_monthly_stmt_page', $data);
        }
    }

    function public_get_prev_month_val(){
        $month = $this->input->post('month');
        $rertMain = $this->getIDCalc('PAYROLL_STATEMENT_PKG' , 'GET_PREV_MONTH_VAL' , $month);
        $next_month= array_slice($rertMain, 1, 1, true);
        echo reset($next_month);
    }

    function getIDCalc($package , $procedure , $id)
    {
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 5000)
        );
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        return $result;
    }


    function _look_ups(&$data)
    {

        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
    }
}