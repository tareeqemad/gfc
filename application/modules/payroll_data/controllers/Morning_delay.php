<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 13/04/20
 * Time: 09:22 ص
 */
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Morning_delay extends MY_Controller
{

    var $MODEL_NAME = 'morning_delay_model';
    var $PAGE_URL = 'payroll_data/morning_delay/get_page';


    function __construct()
    {
        parent::__construct();


        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

    }
    /****شاشة ترحيل التأخير الصباحي*/
    function public_index_trans($page = 1){

        $data['title'] = 'التأخير الصباحي  | ترحيل المتأخرين عن الدوام';
        $data['content'] = 'morning_delay_index_trans';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }
    /*********ترحيل التاخير الصباحي******/
    function trans_delay_emp()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $branch_no = $this->input->post('branch_no');
            $from_the_date = $this->input->post('from_the_date');
            $to_the_date = $this->input->post('to_the_date');
            //$ChkCount = $this->getThreeColum('TRANSACTION_PKG', 'CHECK_DELAY_SALARY_GET', $branch_no,$from_the_date,$to_the_date);
            $ChkCount = $this->rmodel->getTwoColum('TRANSACTION_PKG', 'CHECK_DELAY_SALARY_GET', $branch_no,$from_the_date);
            if (count($ChkCount) > 0) {
                echo 'سجلات الشهر المراد ترحيله معتمدة يرجى ادخال شهر غير معتمد';
                return -1;
            } else {
                $data_arr = array(
                    array('name' => 'BRANCH_NO_IN', 'value' => $branch_no, 'type' => '', 'length' => -1),
                    array('name' => 'F_THE_DAY_IN', 'value' => $from_the_date, 'type' => '', 'length' => -1),
                    array('name' => 'TO_THE_DAY_IN', 'value' => $to_the_date, 'type' => '', 'length' => -1),
                );
                $res = $this->rmodel->update('DELAY_EMP_TRANS', $data_arr);
                if (intval($res) >= 1) {
                    echo 1;
                } else {
                    $this->print_error('Error_' . $res);
                }

            }
        }
    }


    function getThreeColum($package, $procedure, $branch_no,$from_the_date,$to_the_date)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':BRANCH_NO_IN', 'value' => $branch_no, 'type' => '', 'length' => -1),
            array('name' => ':F_THE_DAY_IN', 'value' => $from_the_date, 'type' => '', 'length' => -1),
            array('name' => ':TO_THE_DAY_IN', 'value' => $to_the_date, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        return $result['CUR_RES'];
    }

    /*********متابعة كشف التأخير الصباحي***********/
    function index($page = 1)
    {

        $data['title'] = 'التأخير الصباحي  | الشؤون الادارية';
        $data['content'] = 'morning_delay_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);

    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->p_branch_no}' ) " : '';
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.THE_MONTH = '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO = '{$this->p_emp_no}'  " : "";
        $where_sql .= isset($this->p_from_the_date) && $this->p_from_the_date != null ? " AND  M.THE_DATE >= '{$this->p_from_the_date}'  " : "";
        $where_sql .= isset($this->p_to_the_date) && $this->p_to_the_date != null ? " AND  M.THE_DATE <= '{$this->p_to_the_date}'  " : "";
        $where_sql .= isset($this->p_is_active) && $this->p_is_active != null ? " AND  M.IS_ACTIVE = '{$this->p_is_active}'  " : "";
        $where_sql .= isset($this->p_the_post) && $this->p_the_post != null ? " AND  M.POST = '{$this->p_the_post}'  " : "";
        $where_sql .= isset($this->p_is_shift_emp) && $this->p_is_shift_emp != null ? " AND  TRANSACTION_PKG.CHK_IN_SHIFT_EMPLOYEE_TB(M.EMP_NO) = '{$this->p_is_shift_emp}'  " : "";

        /*echo $where_sql;*/
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.DELAY_EMP M WHERE 1 = 1 ' . $where_sql);
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
        $data["page_rows"] = $this->rmodel->getList('DELAY_EMP_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->model('settings/constant_details_model');
        $data['is_active_cons'] = $this->constant_details_model->get_list(335);
        $this->load->view('morning_delay_page', $data);

    }

    function excel_employee_morning_delay(){
        $where_sql = '';
        /*$where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND  EMP_PKG.GET_EMP_BRANCH(M.EMP_NO) = '{$this->q_branch_no}'  " : "";*/
        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->q_branch_no}' ) " : '';
        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.THE_MONTH = '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.EMP_NO = '{$this->q_emp_no}'  " : "";
        $where_sql .= isset($this->q_from_the_date) && $this->q_from_the_date != null ? " AND  M.THE_DATE >= '{$this->q_from_the_date}'  " : "";
        $where_sql .= isset($this->q_to_the_date) && $this->q_to_the_date != null ? " AND  M.THE_DATE <= '{$this->q_to_the_date}'  " : "";
        $where_sql .= isset($this->q_is_active) && $this->q_is_active != null ? " AND  M.IS_ACTIVE = '{$this->q_is_active}'  " : "";
        $where_sql .= isset($this->q_the_post) && $this->q_the_post != null ? " AND  M.POST = '{$this->q_the_post}'  " : "";
        $where_sql .= isset($this->q_is_shift_emp) && $this->q_is_shift_emp != null ? " AND  TRANSACTION_PKG.CHK_IN_SHIFT_EMPLOYEE_TB(M.EMP_NO) = '{$this->q_is_shift_emp}'  " : "";

        $count_rs = $this->get_table_count(' DATA.DELAY_EMP M WHERE 1 = 1 ' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('DELAY_EMP_LIST', $where_sql, 0, $count_rs);
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
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'الشهر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'اليوم');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'التاريخ');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'ساعة الحضور');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'اعتماد الخصم');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'قيمة الخصم بالساعة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('K1', 'الحالة');

        $from = "A1"; // or any value
        $to = "K1"; // or any value
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
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);


        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['EMP_NO'] )
                ->setCellValue('D' . $rows, $val['EMP_NAME'] )
                ->setCellValue('E' . $rows, $val['THE_MONTH'])
                ->setCellValue('F' . $rows, $val['DAY_AR'])
                ->setCellValue('G' . $rows, $val['THE_DATE'])
                ->setCellValue('H' . $rows, $val['PERIOD_TIME'])
                ->setCellValue('I' . $rows, $val['IS_ACTIVE_NAME'])
                ->setCellValue('J' . $rows, $val['VALVE'])
                ->setCellValue('k' . $rows, $val['POST_NAME'])
            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "كشف التأخير الصباحي" . date('d/m/y', time()) . ".xlsx";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        //ob_end_clean();
        set_time_limit(500);
        ini_set('memory_limit', '-1');
        $object_writer->save('php://output');
    }

    public function public_get_statstic_data()
    {
        $id = $this->input->post('pp_ser');
        if (intval($id) > 0) {
            $rertMain = $this->rmodel->get('DELAY_EMP_GET', $id);
            $no = $rertMain[0]['EMP_NO'];
            $emp_name = $rertMain[0]['EMP_NAME'];
            $the_month = $rertMain[0]['THE_MONTH'];
            $total_number_of_delays = $rertMain[0]['TOTAL_NUMBER_OF_DELAYS'];
            $total_call_up = $rertMain[0]['TOTAL_CALL_UP'];
            $total_legal_exemption = $rertMain[0]['TOTAL_LEGAL_EXEMPTION'];
            $total_excuse_count = $rertMain[0]['TOTAL_EXCUSE_COUNT'];
            $total_discount_hour = $rertMain[0]['TOTAL_DISCOUNT_HOUR'];
            $html = '<div class="container"><div class="card-border">
                        <div class="card-body">
                             <div class="row">
                                   <div class="form-group  col-md-2">
                                        <label> رقم الموظف </label>
                                        <input type="text" readonly name="emp_no_m" id="txt_emp_no_m" class="form-control" value="' . $no . '">
                                  </div>
                                 <div class="form-group  col-md-3">
                                    <label> اسم الموظف </label>
                                    <input type="text" readonly name="emp_name_m" id="txt_emp_name_m" class="form-control" value="' . $emp_name . '">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label>الشهر </label>
                                    <input type="text" readonly name="month_m" id="txt_month_m" class="form-control" value="' . $the_month . '">
                                </div>
                                <div class="form-group  col-md-3">
                                    <label class="text-primary">عدد مرات التأخير الكلية</label>
                                        <input type="text"  readonly name="total_number_of_delays" id="txt_total_number_of_delays" class="form-control" value="' . $total_number_of_delays . '">
                                </div>
                                <div class="form-group  col-md-3">
                                    <label class="text-warning">عدد مرات الاستدعاء</label>
                                        <input type="text"  readonly name="call_up" id="txt_call_up" class="form-control" value="' . $total_call_up . '">
                                </div>
                                <div class="form-group  col-md-3">
                                    <label class="text-success">عدد مرات الاعفاء القانوني</label>
                                    <input type="text"  readonly name="legal_exemption" id="txt_legal_exemption" class="form-control" value="' . $total_legal_exemption . '">
                                </div>
                                 <div class="form-group  col-md-3">
                                    <label  class="text-success">عدد مرات الاعفاء بعذر</label>
                                     <input type="text"  readonly name="excuse_count" id="txt_excuse" class="form-control" value="' . $total_excuse_count . '">
                                </div>
                                 <div class="form-group   col-md-3">
                                    <label class="text-danger">عدد ساعات الخصم</label>
                                    <input type="text"  readonly name="discount_hour_val" id="txt_discount_hour_val" class="form-control" value="' . $total_discount_hour . '">
                                </div>
                             </div>
                    </div>
                </div></div>';
            echo $html;
        }
    }

    function update_is_active()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $p_ser = $this->input->post('p_ser');
            $is_active = $this->input->post('is_active');
            $res = $this->{$this->MODEL_NAME}->update_is_active($p_ser, $is_active);
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }


    //عرض تجميع ساعات الخصم
    function get_page_calculated($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? "  AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->p_branch_no}' ) " : '';
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.THE_MONTH = '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO = '{$this->p_emp_no}'  " : "";
        //echo $where_sql;
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.DELAY_SALARY_TEST_VIEW M WHERE 1=1 ' . $where_sql);
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
        $data["page_rows"] = $this->rmodel->getList('DELAY_EMP_LIST_VM', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('morning_delay_calculated_page', $data);
    }

    function excel_collect_discount_hours(){
        $where_sql = '';
        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? "  AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->q_branch_no}' ) " : '';
        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.THE_MONTH = '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.EMP_NO = '{$this->q_emp_no}'  " : "";

        $count_rs = $this->get_table_count(' DATA.DELAY_SALARY_TEST_VIEW M WHERE 1=1 ' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('DELAY_EMP_LIST_VM', $where_sql, 0, $count_rs);
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
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'شهر التأخير');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'عدد ايام التاخير الكلية');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'عدد ايام الاعفاء حسب القانون');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'عدد ايام الاعفاء بعذر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'عدد ايام الغياب');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'عدد ايام الخصم المعتمدة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('K1', 'عدد الساعات المخصومة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('L1', 'الاعتماد');


        $from = "A1"; // or any value
        $to = "L1"; // or any value
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
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);

        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['EMP_NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['THE_MONTH'])
                ->setCellValue('F' . $rows, $val['COUNT_TOTAL_ALL'])
                ->setCellValue('G' . $rows, $val['COUNT_LAW'])
                ->setCellValue('H' . $rows, $val['COUNT_EXCUSE'])
                ->setCellValue('I' . $rows, $val['VAC'])
                ->setCellValue('J' . $rows, $val['DAY'])
                ->setCellValue('K' . $rows, $val['TOTAL'])
                ->setCellValue('L' . $rows, $val['IS_ACTIVE_NAME'])
            ;
            $rows++;
        }

        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "تجميع التأخير الصباحي" . date('d/m/y', time()) . ".xlsx";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        //ob_end_clean();
        set_time_limit(500);
        ini_set('memory_limit', '-1');
        $object_writer->save('php://output');
    }


    /**********احستاب الخصم ادارياً***********/
    function discount_calculation()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $month = $this->input->post('month');
            $branch_no = $this->input->post('branch_no');
            $ChkCount =$this->rmodel->getTwoColum('TRANSACTION_PKG', 'PREVENT_DIS_CAL_DELAY_SALARY', $month,$branch_no);
            if (count($ChkCount) > 0) {
                echo 'سجلات الشهر المراد ترحيله محتسبة ادارياً لا يمكن الاحتساب مرة اخرى';
                return -1;
            } else {
                $data_arr = array(
                    array('name' => 'THE_MONTH_IN', 'value' => $month, 'type' => '', 'length' => -1),
                    array('name' => 'BRANCH_ID_IN', 'value' => $branch_no, 'type' => '', 'length' => -1),
                );
                $res = $this->rmodel->update('TRANS_TO_DELAY_SALARY', $data_arr);
                if (intval($res) >= 1) {
                    echo 1;
                } else {
                    echo $res;
                }
            }
        }

     }


    public function public_get_adopt_detail()
    {
        $emp_no = $this->input->post('emp_no');
        $month = $this->input->post('month');
        $rertMainAdopt =$this->rmodel->getTwoColum('TRANSACTION_PKG', 'DELAY_EMP_ADOPT_TB_GET', $emp_no,$month);
        $html = '<div class="container"> <div class="card-border">
                        <div class="card-body">
                        
                            <div class="table-responsive">
                                <table class="table table-bordered" id="adopt_detail_tb">
                                <thead class="table-primary">
                                <tr>
                                      <th>الجهة المعتمدة</th>
                                      <th>اسم المعتمد</th>
                                      <th>تاريخ الاعتماد</th>
                                      <th>ملاحظة الاعتماد</th>
                                </tr>
                                </thead>
                                <tbody>';
        foreach ($rertMainAdopt as $rows) {
            $html .= '<tr>
                                <td>' . $rows['ADOPT_NAME'] . '</td>
                                <td>' . $rows['ADOPT_USER_NAME'] . '</td>
                                <td>' . $rows['ADOPT_DATE'] . '</td>
                                <td>' . $rows['NOTE'] . '</td>
                            </tr>';
        }
        $html .= '</tbody></table></div></div></div></div>';
        echo $html;
    }


    function _look_ups(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('settings/constant_details_model');
        $data['is_active_cons'] = $this->constant_details_model->get_list(335);
        $data['the_post_cons'] = $this->constant_details_model->get_list(336);
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
    }


}