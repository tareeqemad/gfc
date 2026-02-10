<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Overtime extends MY_Controller {

    var $MODEL_NAME= 'Overtime_model';
    var $PAGE_URL= 'payroll_data/overtime/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';


    }

    function index($page= 1){

        $data['title'] = 'كشف الساعات الاضافية';
        $data['content'] = 'overtime_index';
        $data['page']=$page;
        $agree_ma = -1;
        $MODULE_NAME= 'payroll_data';
        $TB_NAME= 'overtime';

        $hr_time =  base_url("$MODULE_NAME/$TB_NAME/hr_time");  //اعتماد الشون الادرية  /****Agree Ma 1/
        $ChiefFinancial_time =  base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial_time"); //10
        $HeadOffice_time =  base_url("$MODULE_NAME/$TB_NAME/HeadOffice_time"); //30
        $InternalObserver_time =  base_url("$MODULE_NAME/$TB_NAME/InternalObserver_time"); //31
        $GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector_time"); //33
        $FinancialToPay =  base_url("$MODULE_NAME/$TB_NAME/FinancialToPay"); //agree ma >= 1 and is_active = 1
        if(1){
            if (HaveAccess($hr_time)){
                $agree_ma = 0;
            }
            //اعتماد المدير المالي
            if (HaveAccess($ChiefFinancial_time)){
                $agree_ma = 1;
            }

            //اعتماد مدير المقر
            elseif (HaveAccess($HeadOffice_time)){
                $agree_ma = 10 ;
            }

            //عتماد الرقابة الداخلية
            elseif (HaveAccess($InternalObserver_time)){
                $agree_ma = 30 ;
            }

            //اعتماد المدير العام
            elseif (HaveAccess($GeneralDirector)){
                $agree_ma = 31 ;
            }
            //اعتماد المالية للصرف
            elseif (HaveAccess($FinancialToPay)){
                $agree_ma = 31 ;
            }
        }
        //قيمة الاعتماد بناء على الصلاحية
        $data['agree_ma']= $agree_ma;
        $this->_lookup($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql = '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND M.EMP_BRANCH IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
              WHERE   DECODE(GCC_BRAN,8,2,GCC_BRAN) = '{$this->p_branch_no}' ) " : '';    // تم دمج مقر الصيانة مع مقر غزة 202210
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.MONTH= '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  D.NO = '{$this->p_emp_no}'  " : "";
        $where_sql .= isset($this->p_head_department) && $this->p_head_department != null ? " AND  D.HEAD_DEPARTMENT = '{$this->p_head_department}'  " : "";
        $where_sql .= isset($this->p_w_no) && $this->p_w_no != null ? " AND  D.W_NO = '{$this->p_w_no}'  " : "";
        $where_sql .= isset($this->p_emp_type) && $this->p_emp_type != null ? " AND  D.EMP_TYPE = '{$this->p_emp_type}'  " : "";
        $where_sql .= isset($this->p_agree_ma) && $this->p_agree_ma != null ? " AND  M.AGREE_MA = '{$this->p_agree_ma}'  " : "";
        $where_sql .= isset($this->p_is_active) && $this->p_is_active != null ? " AND  M.IS_ACTIVE = '{$this->p_is_active}'  " : "";
        $where_sql .= isset($this->p_actual_hours) && $this->p_actual_hours != null ? " AND  M.ACTUAL_HOURS $this->p_op  '{$this->p_actual_hours}'  " : ""; //عدد ساعات المقر
        $where_sql .= isset($this->p_calculated_hours) && $this->p_calculated_hours != null ? " AND  M.CALCULATED_HOURS $this->p_op  '{$this->p_calculated_hours}'  " : ""; //عدد ساعات المعتمدة في الراتب
        /******************/
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.OVERTIME  M , DATA.EMPLOYEES D WHERE  M.EMP_NO = D.NO'.$where_sql);
        $total_val_branch_rs = $this->get_sum_coloum('TRANSACTION_PKG.CAL_BASIC_PER_HOUR(M.EMP_NO,M.ACTUAL_HOURS,M.ACTUAL_DAY)','TOTAL_VAL_BRANCH','DATA.OVERTIME  M , DATA.EMPLOYEES D WHERE  M.EMP_NO = D.NO',$where_sql);
        $total_actual_hours_branch_rs = $this->get_sum_coloum('M.ACTUAL_HOURS','TOTAL_ACTUAL_HOURS_BRANCH','DATA.OVERTIME  M , DATA.EMPLOYEES D WHERE  M.EMP_NO = D.NO',$where_sql);//عدد ساعات المقر
        $total_val_adopt_rs = $this->get_sum_coloum('TRANSACTION_PKG.CAL_BASIC_PER_HOUR(M.EMP_NO,M.CALCULATED_HOURS,M.DAY)','TOTAL_VAL_ADOPT','DATA.OVERTIME  M , DATA.EMPLOYEES D WHERE  M.EMP_NO = D.NO',$where_sql);
        $total_calculated_hours_adopt_rs = $this->get_sum_coloum('M.CALCULATED_HOURS','TOTAL_CALCULATED_HOURS_BRANCH','DATA.OVERTIME  M , DATA.EMPLOYEES D WHERE  M.EMP_NO = D.NO',$where_sql);//عدد ساعات المعتمددة في الراتب
        $total_distinct_emp_rs = $this->get_distinct_coloum('M.EMP_NO','DIS_EMP_NO','DATA.OVERTIME  M , DATA.EMPLOYEES D WHERE  M.EMP_NO = D.NO'," AND  M.MONTH= '{$this->p_month}'  ");//عدد الموظفين المتكررين في السجل
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );
        $data["page_rows"] = $this->rmodel->getList('OVERTIME_LIST', $where_sql, $offset, $row);
        $data['offset']=$offset+1;
        $data['page']=$page;
        $data['count_row'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $data['total_val_branch'] = is_array($total_val_branch_rs) && count($total_val_branch_rs) > 0 ? $total_val_branch_rs[0]['TOTAL_VAL_BRANCH'] : 0; //اجمالي المبلغ من المقر
        $data['total_actual_hours_branch'] = is_array($total_actual_hours_branch_rs) && count($total_actual_hours_branch_rs) > 0 ? $total_actual_hours_branch_rs[0]['TOTAL_ACTUAL_HOURS_BRANCH'] : 0; //عدد ساعات المقر
        $data['total_val_adopt'] = is_array($total_val_adopt_rs) && count($total_val_adopt_rs) > 0 ? $total_val_adopt_rs[0]['TOTAL_VAL_ADOPT'] : 0; //اجمالي المبلغ الفعلي ا
        $data['total_calculated_hours_adopt'] = is_array($total_calculated_hours_adopt_rs) && count($total_calculated_hours_adopt_rs) > 0 ? $total_calculated_hours_adopt_rs[0]['TOTAL_CALCULATED_HOURS_BRANCH'] : 0; //عدد ساعات المعتمددة في الراتب
        $data['distinct_emp_val'] = is_array($total_distinct_emp_rs) && count($total_distinct_emp_rs) > 0 ? /*$total_distinct_emp_rs[0]['DIS_EMP_NO']*/ count($total_distinct_emp_rs) : 0;//عدد الموظفين المتكررين في السجل
        $data['where_sql_'] = $this->p_month;
        $data['param_branch'] = $this->p_branch_no;
        $this->load->view('overtime_page',$data);

    }


    function excel_report(){
        $where_sql = '';
        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND M.EMP_BRANCH IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
              WHERE    DECODE(GCC_BRAN,8,2,GCC_BRAN) = '{$this->q_branch_no}' ) " : '';
        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.MONTH= '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  D.NO = '{$this->q_emp_no}'  " : "";
        $where_sql .= isset($this->q_head_department) && $this->q_head_department != null ? " AND  D.HEAD_DEPARTMENT = '{$this->q_head_department}'  " : "";
        $where_sql .= isset($this->q_w_no) && $this->q_w_no != null ? " AND  D.W_NO = '{$this->q_w_no}'  " : "";
        $where_sql .= isset($this->q_emp_type) && $this->q_emp_type != null ? " AND  D.EMP_TYPE = '{$this->q_emp_type}'  " : "";
        $where_sql .= isset($this->q_agree_ma) && $this->q_agree_ma != null ? " AND  M.AGREE_MA = '{$this->q_agree_ma}'  " : "";
        $where_sql .= isset($this->q_is_active) && $this->q_is_active != null ? " AND  M.IS_ACTIVE = '{$this->q_is_active}'  " : "";
        $where_sql .= isset($this->q_actual_hours) && $this->q_actual_hours != null ? " AND  M.ACTUAL_HOURS $this->q_op  '{$this->q_actual_hours}'  " : "";
        $where_sql .= isset($this->q_calculated_hours) && $this->q_calculated_hours != null ? " AND  M.ACTUAL_HOURS $this->q_op  '{$this->q_calculated_hours}'  " : "";


        $count_rs =  $this->get_table_count(' DATA.OVERTIME  M , DATA.EMPLOYEES D WHERE  M.EMP_NO = D.NO'.$where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('OVERTIME_LIST', $where_sql, 0, $count_rs);
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
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'نوع التعين');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'عن شهر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'الادارة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'طبيعة العمل ');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'المسمى الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'الراتب الأساسي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('K1', 'المبلغ المسموح');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('L1', 'ساعات المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('M1', 'الأيام المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('N1', 'المبلغ من المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('O1', 'عدد الساعات');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('P1', 'عدد الأيام');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('Q1', 'المبلغ الفعلي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('R1', 'ملاحظات');

        $from = "A1"; // or any value
        $to = "R1"; // or any value

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
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(20);

        $count = 1;
        $rows = 2;

        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['EMP_NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['EMP_TYPE_NAME'])
                ->setCellValue('F' . $rows, $val['MONTH'])
                ->setCellValue('G' . $rows, $val['HEAD_DEPARTMENT_NAME'])
                ->setCellValue('H' . $rows, $val['W_NO_NAME'])
                ->setCellValue('I' . $rows, $val['W_NO_ADMIN_NAME'])
                ->setCellValue('J' . $rows, $val['B_SALARY'])
                ->setCellValue('K' . $rows, $val['BASIC_SAL_Q'])
                ->setCellValue('L' . $rows, $val['ACTUAL_HOURS'])
                ->setCellValue('M' . $rows, $val['ACTUAL_DAY'])
                ->setCellValue('N' . $rows, $val['VAL_BRANCH'])
                ->setCellValue('O' . $rows, $val['CALCULATED_HOURS'])
                ->setCellValue('P' . $rows, $val['DAY'])
                ->setCellValue('Q' . $rows, $val['VAL_ADOPT_BRANCH'])
                ->setCellValue('R' . $rows, $val['NOTES'])
            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "تقرير الوقت الاضافي" . date('d/m/y', time()) . ".xlsx";
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

    function public_adopt()
    {
        $adopt_no = $this->input->post('pp_ser');
        $agree_ma = $this->input->post('agree_ma');
        $note = $this->input->post('note');
        $x = 0;
        foreach ($adopt_no as $adopt_add) {
            $ret = $this->rmodel->update('OVERTIME_ADOPT_TB_ADOPT', $this->_postedData_adopt($adopt_add, $agree_ma, $note));
            if ($ret >= 1) $x++;
        }
        if ($x != count($adopt_no)) {
            echo 'Error';
        } else {
            echo 1;
        }
    }

    function public_unadopt()
    {
        $adopt_no = $this->input->post('pp_ser');
        $agree_ma = $this->input->post('agree_ma');
        $note = $this->input->post('note');
        $x = 0;
        foreach ($adopt_no as $adopt_add) {
            $ret = $this->rmodel->update('OVERTIME_ADOPT_TB_UNADOPT', $this->_postedData_unadopt($adopt_add, $agree_ma, $note));
            if ($ret >= 1) $x++;
        }
        if ($x != count($adopt_no)) {
            echo $ret;
        } else {
            echo 1;
        }
    }

    function _postedData_adopt($adopt_no, $agree_ma, $note)
    {
        $result = array(
            array('name' => 'P_SER', 'value' => $adopt_no, 'type' => '', 'length' => -1),
            array('name' => 'AGREE_MA', 'value' => $agree_ma, 'type' => '', 'length' => -1),
            array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1),
        );
        return $result;
    }

    function _postedData_unadopt($adopt_no, $agree_ma, $note)
    {
        $result = array(
            array('name' => 'P_SER', 'value' => $adopt_no, 'type' => '', 'length' => -1),
            array('name' => 'AGREE_MA', 'value' => $agree_ma, 'type' => '', 'length' => -1),
            array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1),
        );
        return $result;
    }

    public function public_get_adopt_detail()
    {
        $id = $this->input->post('pp_ser');
        if (intval($id) > 0) {
            $data['rertMain'] = $this->rmodel->get('OVERTIME_ROW_GET', $id);
            $data['rertMainAdopt'] = $this->rmodel->get('OVERTIME_ADOPT_TB_GET', $id);
            $this->load->view('overtime_/adopt_detail',$data);
        }
    }

    public function public_get_edit_detail()
    {
        $id = $this->input->post('pp_ser');
        if (intval($id) > 0) {
            $data['id'] = $id;
            $data['rertMain'] = $this->rmodel->get('OVERTIME_ROW_GET', $id);
            $this->load->view('overtime_/edit_detail',$data);
        }else{
            echo 'يوجد خطأ';
        }
    }
    function update_calculated_hours(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $pp_ser = $this->input->post('pp_ser');
            $calculated_hours = $this->input->post('calculated_hours');
            $data_arr = array(
                array('name' => 'P_SER_IN', 'value' => $pp_ser, 'type' => '', 'length' => -1),
                array('name' => 'CALCULATED_HOURS_IN', 'value' => $calculated_hours, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('OVERTIME_UPDATE', $data_arr);
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }
    function budget_overtime_detail()
    {
        $branch_no = $this->input->post('branch_no');
        $section_no = $this->input->post('section_no');
        $result = $this->{$this->MODEL_NAME}->get_budget($branch_no,$section_no);
        $html = '<div class="table-responsive">
                  <table class="table table-bordered" id="budget_tb">
                    <thead CLASS="table-primary">
                       <tr>
                         <th>المخصص السنوي للمقر</th>
                         <th>المخصص الشهري</th>
                        </tr>
                   </thead>
                <tbody>';
        foreach($result as $rows){
            $html .= '<tr>
			    <td class="total_update" id="total_update">'.$rows['TOTAL_UPDATE'].'</td>
				<td class="month_year">'.number_format($rows['MONTH_YEAR'],2).'</td>
			</tr>';
        }
        $html .= '</tbody></table></div>';
        echo $html;

    }

    function public_get_budget_interval(){
        $section_no = $this->input->post('section_no');
        $branch_no = $this->input->post('branch_no');
        $month = $this->input->post('month');
        $to_month= $this->input->post('to_month');
        $emp_type = $this->input->post('emp_type');
        $result = $this->{$this->MODEL_NAME}->get_budget_interval($section_no,$branch_no,$month,$to_month,$emp_type);
        $valud_ma_adopt = 0;
        $html = '
          <div class="table-responsive">
              <table class="table table-bordered" id="budget_interval_tb">
                 <thead class="table-primary">
                  <tr>
                       <th>الشهر</th>
                       <th>الساعات المحتسبة</th>
                       <th>المبلغ للصرف</th>
                   </tr>
                </thead>
         <tbody>';
        foreach($result as $rows){
            $valud_ma_adopt+= $rows['VAL_ADOPT_BRANCH'];
            $html .= '<tr>
			    <td class="month">'.$rows['MONTH'].'</td>
				<td class="total_calculated_hours">'.$rows['TOTAL_CALCULATED_HOURS'].'</td>
				<td class="val_adopt_branch" id="val_adopt_branch">'.number_format($rows['VAL_ADOPT_BRANCH'],2).'</td>
			</tr>';
        }
        $html .= '</tbody><tfoot><tr><td></td><td></td><td style="background-color: #c7f5d5;" id="budget_interval_val">'.number_format($valud_ma_adopt,2).'</td></tr></tr></tfoot></table></div>';
        echo $html;

    }

    function public_get_budget_salary(){
        $branch_no = $this->input->post('branch_no');
        $month = $this->input->post('month');
        $to_month= $this->input->post('to_month');
        $emp_type = $this->input->post('emp_type');
        $account_id = $this->input->post('account_id');
        //$value_salary = 0;
        $real_salary = 0; // المعتمد من المالية
        $result = $this->{$this->MODEL_NAME}->get_salary_interval($branch_no,$month,$to_month,$emp_type,$account_id);
        $html = '<div class="table-responsive">
                 <table class="table table-bordered" id="budget_salary_tb">
                  <thead class="table-primary">
                    <tr>
                      <th>الشهر</th>
                      <th>المبلغ </th>
                   </tr>
                  </thead>
            <tbody>';
        foreach($result as $rows){
            $real_salary+= $rows['REAL_VALUE'];
            $html .= '<tr>
			    <td class="month_salary">'.$rows['MONTH'].'</td>
				<td class="real_value">'.number_format($rows['REAL_VALUE'],2).'</td>
			</tr>';
        }
        $html .= '</tbody><tfoot><tr><td></td><td style="background-color: #c7f5d5;" id="c7f5d5">'.$real_salary.'</td></tr></tfoot></table></div>';
        echo $html;
    }

    function _lookup(&$data)
    {
        //this for constant using

        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('settings/constant_details_model');
        $data['adopt_cons'] = $this->constant_details_model->get_list(318);
        $this->load->model('salary/constants_sal_model');
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);
        //$data['bran_cons'] = $this->constants_sal_model->get_list(5);
        $data['head_department_cons'] = $this->constants_sal_model->get_list(7);
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');
    }

    function get_sum_coloum($coloum_name,$alias_name,$tb_name,$where_sql)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':COLOUM_NAME', 'value' => $coloum_name, 'type' => '', 'length' => -1),
            array('name' => ':ALIAS_NAME', 'value' => $alias_name, 'type' => '', 'length' => -1),
            array('name' => ':TB_NAME', 'value' => $tb_name, 'type' => '', 'length' => -1),
            array('name' => ':WHERE_SQL', 'value' => $where_sql, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures('TRANSACTION_PKG', 'GET_SUM_COLOUM', $params);

        return $result['CUR_RES'];
    }



    function get_distinct_coloum($coloum_name,$alias_name,$tb_name,$where_sql)
    {
        $params = array(
            array('name' => ':COLOUM_NAME', 'value' => $coloum_name, 'type' => '', 'length' => -1),
            array('name' => ':ALIAS_NAME', 'value' => $alias_name, 'type' => '', 'length' => -1),
            array('name' => ':TB_NAME', 'value' => $tb_name, 'type' => '', 'length' => -1),
            array('name' => ':WHERE_SQL', 'value' => $where_sql, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures('TRANSACTION_PKG', 'GET_DISTINCT_COLOUM', $params);

        return $result['CUR_RES'];
    }
    function get_recurring_records($coloum_name,$tb_name,$where_sql)
    {
        $params = array(
            array('name' => ':COLOUM_NAME', 'value' => $coloum_name, 'type' => '', 'length' => -1),
            array('name' => ':TB_NAME', 'value' => $tb_name, 'type' => '', 'length' => -1),
            array('name' => ':WHERE_SQL', 'value' => $where_sql, 'type' => '', 'length' => -1),
            array('name' =>  ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures('TRANSACTION_PKG', 'RECURRING_RECORDS_GET', $params);

        return $result['CUR_RES'];
    }

    function public_view_recurring_records(){
        $coloum_name = 'M.EMP_NO';
        $tb_name = 'DATA.OVERTIME  M ';
        $month = $this->input->post('month');
        $where_sql  = " AND  M.MONTH= '{$month}'  ";
        $total_count_emp_rs = $this->get_recurring_records($coloum_name,$tb_name,$where_sql);//عدد الموظفين المتكررين في السجل
        $data['total_count_arr'] = $total_count_emp_rs;
        $this->load->view('overtime_/overtime_recurring_records',$data);
    }

    function indexInlineno($id , $categories){
        add_js('ajax_upload_file.js');
        $count_rs =  $this->get_table_count(" GFC.GFC_ATTACHMENT_TB T WHERE UPPER(T.CATEGORY) = '{$categories}' AND T.IDENTITY ='{$id}'  ");
        $data['cnt_row'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $data['id'] = $id;
        $data['categories'] = $categories;
        $data['can_upload_priv'] = 1;
        $this->load->view('attachment_num_inline',$data);
    }


    function delete_hours()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->rmodel->delete('OVERTIME_DELETE', $this->p_id);
        }
    }

}
