<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 13/05/2020
 * Time: 12:43 م
 */
class Salary_benefits extends MY_Controller
{

    var $page_url = 'payroll_data/salary_benefits/get_page';
    var $financial_query_url = 'payroll_data/salary_benefits/financial_query';


    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

    }

    function index($page = 1)
    {
        $data['title'] = 'الاستحقاقات المالية  ';
        $data['content'] = 'salary_benefits_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        if (HaveAccess($this->financial_query_url)){
            $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO = '{$this->p_emp_no}' " : "";
        }else{
            $where_sql .= " and M.EMP_NO= {$this->user->emp_no} ";

        }
        /*echo $where_sql;*/

        $where_sql .= isset($this->p_branch_id) && $this->p_branch_id != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->p_branch_id}' ) " : '';
        $where_sql .= isset($this->p_con_no) && $this->p_con_no != null ? " AND  M.CON_NO = '{$this->p_con_no}' " : "";
        $where_sql .= isset($this->p_from_month) && $this->p_from_month != null ? " AND  M.FROM_MONTH >= '{$this->p_from_month}' " : "";
        $where_sql .= isset($this->p_to_month) && $this->p_to_month != null ? " AND  M.TO_MONTH <= '{$this->p_to_month}' " : "";
        $where_sql .= isset($this->p_value) && $this->p_value != null ? " AND  M.VALUE $this->p_op {$this->p_value}  " : "";
        $where_sql .= isset($this->p_is_taxed) && $this->p_is_taxed != null ? " AND  M.IS_TAXED = '{$this->p_is_taxed}' " : "";


        $where_sql .= ($this->p_badl_typ == 1) ? " AND M.CON_NO IN (SELECT no FROM data.constant 
            where data.constant.is_add =1 AND data.constant.IS_CONSTANT = 1)  " : '';

        $where_sql .= ($this->p_badl_typ == 2) ? " AND M.CON_NO IN (
             SELECT no  FROM data.constant 
             where data.constant.is_add =1 AND data.constant.IS_CONSTANT = 2 )  " : '';

        $where_sql .= ($this->p_badl_typ == 3) ? " AND M.CON_NO IN ( 
             SELECT no  FROM data.constant
             where data.constant.is_add = 0  )  " : '';
        $config['base_url'] = base_url($this->page_url);
        $count_rs = $this->get_table_count(" DATA.ADD_AND_DED M  WHERE 1 = 1  {$where_sql}");
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = 200; //200;
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
        $data["page_rows"] = $this->rmodel->getList('ADD_AND_DED_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('salary_benefits_page', $data);

    }

    function excel_report(){
        $where_sql = '';
        if ($this->user->emp_no == 1361) {
            $where_sql .= " and M.EMP_NO= {$this->user->emp_no} ";
        }
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.EMP_NO = '{$this->q_emp_no}' " : "";
        $where_sql .= isset($this->q_branch_id) && $this->q_branch_id != null ? " AND  EMP_PKG.GET_EMP_BRANCH(M.EMP_NO) = '{$this->q_branch_id}' " : "";
        $where_sql .= isset($this->q_con_no) && $this->q_con_no != null ? " AND  M.CON_NO = '{$this->q_con_no}' " : "";
        $where_sql .= isset($this->q_from_month) && $this->q_from_month != null ? " AND  M.FROM_MONTH >= '{$this->q_from_month}' " : "";
        $where_sql .= isset($this->q_to_month) && $this->q_to_month != null ? " AND  M.TO_MONTH <= '{$this->q_to_month}' " : "";
        $where_sql .= isset($this->q_value) && $this->q_value != null ? " AND  M.VALUE $this->q_op {$this->q_value}  " : "";
        $where_sql .= isset($this->q_is_taxed) && $this->q_is_taxed != null ? " AND  M.IS_TAXED = '{$this->q_is_taxed}' " : "";


        $where_sql .= ($this->q_badl_typ == 1) ? " AND M.CON_NO IN (SELECT no FROM data.constant 
            where data.constant.is_add =1 AND data.constant.IS_CONSTANT = 1)  " : '';

        $where_sql .= ($this->q_badl_typ == 2) ? " AND M.CON_NO IN (
             SELECT no  FROM data.constant 
             where data.constant.is_add =1 AND data.constant.IS_CONSTANT = 2 )  " : '';

        $where_sql .= ($this->q_badl_typ == 3) ? " AND M.CON_NO IN ( 
             SELECT no  FROM data.constant
             where data.constant.is_add = 0  )  " : '';
        $count_rs = $this->get_table_count(" DATA.ADD_AND_DED M  WHERE 1 = 1  {$where_sql}");
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $this->Rmodel->package = 'GFC_TECH_PKG';
        $excelData =$this->rmodel->getList('ADD_AND_DED_LIST', $where_sql, 0, $count_rs);
        //PHP 7 gs2
        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("النظام الاداري");
        // Create a first sheet
        $spreadsheet->setActiveSheetIndex(0);
        // set Header
        /*a b c d e f g h i j k l m n o p q r s t u v w x y z*/


        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('A1', 'م');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('B1', 'المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('C1', 'الرقم الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('D1', 'الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'نوع البدل');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'البند');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'القيمة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'من شهر ');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'الى شهر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'خاضع للضريبة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('K1', 'الملاحظة');


        $from = "A1"; // or any value
        $to = "T1"; // or any value
        $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

        // Set auto size and
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(50);

        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['EMP_NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['BADL_NAME'])
                ->setCellValue('F' . $rows, $val['CON_NAME'])
                ->setCellValue('G' . $rows, $val['REAL_VAL'])
                ->setCellValue('H' . $rows, $val['FROM_MONTH'])
                ->setCellValue('I' . $rows, $val['TO_MONTH'])
                ->setCellValue('J' . $rows, $val['IS_TAXED_NAME'])
                ->setCellValue('K' . $rows, $val['NOTES'])
            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "تقرير الاستحقاقات المالية للموظفين" . date('m/d/Y h:i:s a', time()) . ".xlsx";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $object_writer->save('php://output');
    }

    function public_get_badl_type()
    {
        $badl_typ = $this->input->post('badl_typ');
        if (intval($badl_typ) > 0) {
            $res = $this->rmodel->get('BADL_TYPE_GET', $badl_typ);
            echo json_encode($res);
        }
    }

    function _look_ups(&$data)
    {
        $data['data_cons'] = $this->rmodel->getData('CONSTANT_DATA_GET_ALL');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['badl_typ_cons'] = $this->constant_details_model->get_list(343);
        $data['is_taxed_cons'] = $this->constant_details_model->get_list(344);

    }
}
