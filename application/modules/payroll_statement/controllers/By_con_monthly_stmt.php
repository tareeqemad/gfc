<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 05/09/2022
 * Time: 10:55
 */
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class By_con_monthly_stmt extends MY_Controller
{

    var $PAGE_URL = 'payroll_data/By_con_monthly_stmt/get_page';

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'PAYROLL_STATEMENT_PKG';

    }

    function index()
    {

        $data['title'] = 'كشف الرواتب حسب البنود - المقرات';
        $data['content'] = 'By_con_monthly_stmt_index';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get_page()
    {
        $month = $this->input->post('month');
        $branch = $this->input->post('branch_no');

        if (intval($month) > 0) {
            $data['curr_month'] = $month;
            $prev_month_arr = $this->rmodel->get('SALARY_GET_PREV_MONTH', $month);
            $data['prev_month'] = $prev_month_arr[0]['PREV_MONTH'];
            /*********اجمالي الموظفين المصروف لهم راتب في الشهر الحالي والسابق **********/
            $data['Total_Emp_arr'] = $this->rmodel->getTwoColum('PAYROLL_STATEMENT_PKG', 'TOTAL_EMP_NET_SALARY', $month, $branch);
            /**************اجمالي بند الاستحقاق الثابت**************/
            $data['Sal_Fixed_arr'] =  $this->rmodel->getTwoColum('PAYROLL_STATEMENT_PKG', 'GET_TOTAL_SAL_FIXED_ADD', $month, $branch);
            /**************اجمالي بند الاستحقاق المتغير**************/
            $data['Sal_Change_arr'] = $this->rmodel->getTwoColum('PAYROLL_STATEMENT_PKG', 'GET_TOTAL_SAL_CHANGE_ADD', $month, $branch);
            /*----------------اجماليات بنود الاستقطاع المستقطع من الرواتب -----------------*/
            $data['Sal_Ded_arr'] = $this->rmodel->getTwoColum('PAYROLL_STATEMENT_PKG', 'GET_TOTAL_SAL_DEDUCTION', $month, $branch);
            $this->load->view('By_con_monthly_stmt_page', $data);
        }

    }

    function public_get_diff_emp()
    {
        $month = $this->input->post('month');
        $branch = $this->input->post('branch_no');
        $diff_emp_arr = $this->rmodel->getTwoColum('PAYROLL_STATEMENT_PKG','GET_MINUS_EMP_FROM_ADMIN', $month,$branch);

        $html = '<div class="container">
                            <hr>
                         <div class="flex-shrink-0" style="padding-right: 15px;">
                          <a href="javascript:void(0);" onclick="exportDiffEmpDataXlsx(' . $month . ','.$branch.')" class="btn btn-success" id="btn_export_emp_diff"><i class="fa fa-file-excel-o"></i> تصدير اكسل</a>
                          <a href="javascript:void(0);" onclick="exportDiffEmpDataPdf(' . $month . ','.$branch.')" class="btn btn-primary" ><i class="fa fa-file-pdf-o"></i> تقرير</a>
                         </div>
                         <br>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="get_diff_emp_tb">
                                <thead class="table-light">
                                <tr>
                                      <th>م</th>  
                                      <th>الرقم الوظيفي</th>
                                      <th>الموظف</th>
                                </tr>
                                </thead>
                                <tbody>';
        $count_row_emp = 1;
        foreach ($diff_emp_arr as $row) {
            $html .= '<tr>
                                <td>' . $count_row_emp++ . '</td>
                                <td>' . $row['EMP_NO'] . '</td>
                                <td>' . $row['EMP_NAME'] . '</td>
                            </tr>';
        }
        $html .= '</tbody></table>';
        $html .= '</div><hr><div class="row">';
        $html .= '</div>';
        $html .= '</div>';
        echo $html;
    }

    /***export diff emp to xlsx */
    function public_get_export_diff_emp(){

        $month = $this->input->get('month');
        $branch = $this->input->get('branch_no');

        $excelData = $this->rmodel->getTwoColum('PAYROLL_STATEMENT_PKG','GET_MINUS_EMP_FROM_ADMIN', $month,$branch);

        //PHP 7 gs2
        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("النظام الاداري الرواتب");
        // Create a first sheet
        $spreadsheet->setActiveSheetIndex(0);
        // set Header
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('A1', 'رقم الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('B1', 'اسم الموظف');
        $from = "A1"; // or any value
        $to = "B1"; // or any value
        $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );
        // Set auto size and
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);

        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $val['EMP_NO'])
                ->setCellValue('B' . $rows, $val['EMP_NAME'])

            ;
            $rows++;
        }

        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "الفرق في  الموظفين الشهري الحالي والسابق" . date('Ym') . ".xlsx";
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




    function public_get_diff_sal(){
        $month = $this->input->post('month');
        $con_no = $this->input->post('con_no');
        $branch_no = $this->input->post('branch_no');

        $prev_month_arr = $this->rmodel->get('SALARY_GET_PREV_MONTH', $month);
        $prev_month = $prev_month_arr[0]['PREV_MONTH'];

        $diff_sal_arr = $this->getThreeColum('PAYROLL_STATEMENT_PKG', 'GET_EMP_DIFF_VAL_MONTHS', $month, $con_no, $branch_no);
        $html = '<div class="container">
                          <hr>      
                         <div class="flex-shrink-0">
                           <a href="javascript:void(0);" onclick="exportDiffSalaryItemXlsx(' . $month . ',' . $con_no . ',' . $branch_no . ')" class="btn btn-success" id="btn_diff_xlsx"><i class="fa fa-file-excel-o"></i> تصدير اكسل</a>
                           <a href="javascript:void(0);" onclick="exportDiffSalaryItemPdf(' . $month . ','.$prev_month.',' . $con_no . ',' . $branch_no . ')" class="btn btn-primary" id="btn_diff_pdf"><i class="fa fa-file-pdf-o"></i> تقرير</a>
                         </div>
                         <br>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="get_diff_sal_tb">
                                <thead class="table-light">
                                <tr>
                                      <th>م</th>  
                                      <th>البند</th>
                                      <th>المقر</th>
                                      <th>رقم الموظف</th>
                                      <th>اسم الموظف</th>
                                       <th>المسمى الوظيفي</th>
                                      <th>قيمة شهر ' . $month . '</th>
                                      <th>قيمة شهر ' . $prev_month . '</th>
                                      <th>الزيادة</th>
                                      <th>النقص</th>
                                </tr>
                                </thead>
                                <tbody>';
        $count_row_diff = 1;
        $total_diff_p = 0;
        $total_diff_m = 0;
        foreach ($diff_sal_arr as $row) {
            $total_diff_p += $row['DIFF_POSTIVE'];
            $total_diff_m += $row['DIFF_DED_MINUS'];
            if ($row['DIFF_POSTIVE']) {$chk_class_p = 'text-success';}else{$chk_class_p = '';}
            if ($row['DIFF_MINUS']) {$chk_class_m = 'text-danger';}else{$chk_class_m = '';}
            $html .= '<tr>
                                <td>' . $count_row_diff++ . '</td>
                                <td>' . $row['SAL_CONSTANT_NAME'] . '</td>
                                <td>' . $row['BRANCH_NAME'] . '</td>
                                <td>' . $row['NO'] . '</td>
                                <td>' . $row['NAME'] . '</td>
                                <td>' . $row['W_NO_ADMIN_NAME'] . '</td>
                                <td>' . $row['CURR_VAL'] . '</td>
                                <td>' . $row['PREV_VAL'] . '</td>
                                <td class="'.$chk_class_p.'">' . $row['DIFF_POSTIVE'] . '</td>
                                <td class="'.$chk_class_m.'">' . $row['DIFF_MINUS'] . '</td>
                            </tr>';
        }
        $html .= '</tbody></table>';
        $html .= '</div><hr><div class="row">';
        $html .= '</div>';
        $html .= '</div>';
        echo $html;
    }

    /***export diff data to xlsx without over time */
    function public_get_exportDiffdata(){

        $month = $this->input->get('month');
        $con_no = $this->input->get('con_no');
        $branch_no = $this->input->get('branch_no');

        $prev_month_arr = $this->rmodel->get('SALARY_GET_PREV_MONTH', $month);
        $prev_month = $prev_month_arr[0]['PREV_MONTH'];

        $excelData = $this->getThreeColum('PAYROLL_STATEMENT_PKG', 'GET_EMP_DIFF_VAL_MONTHS', $month, $con_no, $branch_no);

        //PHP 7 gs2
        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("النظام الاداري الرواتب");
        // Create a first sheet
        $spreadsheet->setActiveSheetIndex(0);
        // set Header
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('A1', 'البند');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('B1', 'المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('C1', 'رقم الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('D1', 'اسم الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'المسمى الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', $month);
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', $prev_month);
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'الزيادة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'النقص');

        $from = "A1"; // or any value
        $to = "I1"; // or any value
        $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );
        // Set auto size and
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $val['SAL_CONSTANT_NAME'])
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['NO'])
                ->setCellValue('D' . $rows, $val['NAME'])
                ->setCellValue('E' . $rows, $val['W_NO_ADMIN_NAME'])
                ->setCellValue('F' . $rows, $val['CURR_VAL'])
                ->setCellValue('G' . $rows, $val['PREV_VAL'])
                ->setCellValue('H' . $rows, $val['DIFF_POSTIVE'])
                ->setCellValue('I' . $rows, $val['DIFF_MINUS'])
            ;
            $rows++;
        }

        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "الفرق في قيمة رواتب الموظفين الشهري الحالي والسابق" . date('Ym') . ".xlsx";
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


    function public_get_diff_sal_overtime(){
        $month = $this->input->post('month');
        $con_no = $this->input->post('con_no');
        $branch_no = $this->input->post('branch_no');

        $prev_month_arr = $this->rmodel->get('SALARY_GET_PREV_MONTH', $month);
        $prev_month = $prev_month_arr[0]['PREV_MONTH'];

        $diff_sal_arr = $this->getThreeColum('PAYROLL_STATEMENT_PKG', 'GET_EMP_DIFF_VAL_OVERTIME', $month, $con_no, $branch_no);
        $html = '<div class="container">
                         <hr>
                         <div class="flex-shrink-0" style="padding-right: 15px;">
                          <a href="javascript:void(0);" onclick="exportDiffOverTimeXlsx(' . $month . ',' . $con_no . ',' . $branch_no . ')" class="btn btn-success" id="btn_export_overtime_diff"><i class="fa fa-file-excel-o"></i> تصدير اكسل</a>
                          <a href="javascript:void(0);" onclick="exportDiffOverItemPdf(' . $month . ','.$prev_month.',' . $con_no . ',' . $branch_no . ')" class="btn btn-primary" id="btn_diff_pdf"><i class="fa fa-file-pdf-o"></i> تقرير</a>
                         </div>
                         <br>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="get_diff_sal_tb">
                                <thead class="table-light">
                                <tr>
                                      <th>م</th>  
                                      <th>البند</th>
                                      <th>المقر</th>
                                      <th>رقم الموظف</th>
                                      <th>اسم الموظف</th>
                                      <th>المسمى الوظيفي</th>
                                      <th>عدد الساعات</th> 
                                      <th>قيمة شهر ' . $month . '</th>
                                      <th>قيمة شهر ' . $prev_month . '</th>
                                       <th>الساعات السابقة</th>
                                      <th>الزيادة</th>
                                      <th>النقص</th>
                                </tr>
                                </thead>
                                <tbody>';
        $count_row_diff = 1;
        $total_diff_p = 0;
        $total_diff_m = 0;
        foreach ($diff_sal_arr as $row) {
            $total_diff_p += $row['DIFF_POSTIVE'];
            $total_diff_m += $row['DIFF_DED_MINUS'];
            if ($row['DIFF_POSTIVE']) {$chk_class_p = 'text-success';}else{$chk_class_p = '';}
            if ($row['DIFF_MINUS']) {$chk_class_m = 'text-danger';}else{$chk_class_m = '';}
            $html .= '<tr>
                                <td>' . $count_row_diff++ . '</td>
                                <td>' . $row['SAL_CONSTANT_NAME'] . '</td>
                                <td>' . $row['BRANCH_NAME'] . '</td>
                                <td>' . $row['NO'] . '</td>
                                <td>' . $row['NAME'] . '</td>
                                <td>' . $row['W_NO_ADMIN_NAME'] . '</td>
                                <td>' . $row['CALCULATED_HOURS_CURR'] . '</td>
                                <td>' . $row['CURR_VAL'] . '</td>
                                <td>' . $row['PREV_VAL'] . '</td>
                                <td>' . $row['CALCULATED_HOURS_PREV'] . '</td>
                                <td class="'.$chk_class_p.'">' . $row['DIFF_POSTIVE'] . '</td>
                                <td class="'.$chk_class_m.'">' . $row['DIFF_MINUS'] . '</td>
                            </tr>';
        }
        $html .= '</tbody></table>';
        $html .= '</div><hr><div class="row">';
        $html .= '</div>';
        $html .= '</div>';
        echo $html;
    }


    /***export diff data to xlsx wit over time */
    function public_get_exportDiffOvertime(){

        $month = $this->input->get('month');
        $con_no = $this->input->get('con_no');
        $branch_no = $this->input->get('branch_no');

        $prev_month_arr = $this->rmodel->get('SALARY_GET_PREV_MONTH', $month);
        $prev_month = $prev_month_arr[0]['PREV_MONTH'];

        $excelData = $this->getThreeColum('PAYROLL_STATEMENT_PKG', 'GET_EMP_DIFF_VAL_OVERTIME', $month, $con_no, $branch_no);
        /*a b c d e f g h i j k l m n o p q r s t u v w x y z*/
        //PHP 7 gs2
        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("النظام الاداري الرواتب");
        // Create a first sheet
        $spreadsheet->setActiveSheetIndex(0);
        // set Header
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('A1', 'البند');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('B1', 'المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('C1', 'رقم الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('D1', 'اسم الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'المسمى الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'عدد الساعات');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', $month);
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', $prev_month);
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'الساعات السابقة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'الزيادة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('K1', 'النقص');

        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );
        // Set auto size and
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $val['SAL_CONSTANT_NAME'])
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['NO'])
                ->setCellValue('D' . $rows, $val['NAME'])
                ->setCellValue('E' . $rows, $val['W_NO_ADMIN_NAME'])
                ->setCellValue('F' . $rows, $val['CALCULATED_HOURS_CURR'])
                ->setCellValue('G' . $rows, $val['CURR_VAL'])
                ->setCellValue('H' . $rows, $val['PREV_VAL'])
                ->setCellValue('I' . $rows, $val['CALCULATED_HOURS_PREV'])
                ->setCellValue('J' . $rows, $val['DIFF_POSTIVE'])
                ->setCellValue('K' . $rows, $val['DIFF_MINUS'])
            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "الفرق في قيمة رواتب الموظفين   بند الوقت الاضافي الشهري الحالي والسابق" . date('Ym') . ".xlsx";
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

    function getThreeColum($package, $procedure, $branch_no, $from_the_date, $to_the_date)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':BRANCH_NO_IN', 'value' => $branch_no, 'type' => '', 'length' => -1),
            array('name' => ':F_THE_DAY_IN', 'value' => $from_the_date, 'type' => '', 'length' => -1),
            array('name' => ':TO_THE_DAY_IN', 'value' => $to_the_date, 'type' => '', 'length' => -1),
            array('name' => ':REF_CURSOR_OUT', 'value' => 'CUR_RES', 'type' => 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        return $result['CUR_RES'];
    }
}


