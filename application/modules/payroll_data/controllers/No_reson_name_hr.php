<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 24/07/2022
 * Time: 12:55
 */
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class No_reson_name_hr extends MY_Controller
{

    var $PAGE_URL = 'payroll_data/No_reson_name_hr/get_page';


    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

    }

    function index($page = 1)
    {

        $data['title'] = 'الغير ملتزمين بالانصراف  | احتساب الخصم ادارياً';
        $data['content'] = 'no_reson_name_hr_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);

    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';

        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->p_branch_no}' ) " : '';
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.THE_MONTH = '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.NO = '{$this->p_emp_no}'  " : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.COUNT_NO_RESON_NAME  M WHERE 1 =1 ' . $where_sql);
        //print_r( $count_rs);
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
        $data["page_rows"] = $this->rmodel->getList('COUNT_NO_RESON_NAME_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('no_reson_name_hr_page', $data);

    }

    function excel_report(){
        $where_sql = '';
        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->q_branch_no}' ) " : '';


        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.THE_MONTH = '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.NO = '{$this->q_emp_no}'  " : "";

        $count_rs =   $this->get_table_count(' DATA.COUNT_NO_RESON_NAME  M WHERE 1 =1 ' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('COUNT_NO_RESON_NAME_LIST', $where_sql, 0, $count_rs);
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
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'عدد ايام التوقيع');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'عدد مرات الاعفاء');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'عدد مرات الخصم');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'عدد ايام الخصم');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'شهر عدم الالتزام');



        $from = "A1"; // or any value
        $to = "I1"; // or any value
        $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

        // Set auto size and
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);

        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['COUNT_NO'])
                ->setCellValue('F' . $rows, $val['COUNT_PERMISSION'])
                ->setCellValue('G' . $rows, $val['COUNT_MINUS'])
                ->setCellValue('H' . $rows, $val['COUNT_DAY'])
                ->setCellValue('I' . $rows, $val['THE_MONTH'])
            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "كشف الغير ملتنزمين بالانصراف" . date('d/m/y', time()) . ".xlsx";
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
    //احتساب الخصم ادارياً
    function calc_deduction_hr(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $month = $this->input->post('month');
            $branch_no = $this->input->post('branch_no');

            $ChkCount = $this->rmodel->getTwoColum('TRANSACTION_PKG', 'NO_RESON_NAME_CHK_IS_NULL', $month, $branch_no); //فحص هل تم الانتهاء من جميع السجلات في الشغل الاداري
            $ChkHrAdoptCount = $this->rmodel->getTwoColum('TRANSACTION_PKG', 'COUNT_NO_RESON_NAME_GET', $month, $branch_no);

            if (count($ChkCount) > 0) {
                echo 'لم يتم الانتهاء من تعديل جميع السجلات في شاشة الشؤون الادارية ';
                return -1;
            }elseif (count($ChkHrAdoptCount) > 0){
                echo 'السجل معتمد ';
                return -1;
            } else {
                $data_arr = array(
                    array('name' => 'THE_MONTH_IN', 'value' => $month, 'type' => '', 'length' => -1),
                    array('name' => 'BRANCH_NO_IN', 'value' => $branch_no, 'type' => '', 'length' => -1),
                );
                $res = $this->rmodel->update('COUNT_NO_RESON_NAME_TRANS', $data_arr);
                if (intval($res) >= 1) {
                    echo 1;
                } else {
                    $this->print_error('Error_' . $res);
                }
            }

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