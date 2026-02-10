<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 26/02/23
 * Time: 13:00 م
 */
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Contact_allowance_adopt extends MY_Controller
{

    var $PAGE_URL = 'salary/Contact_allowance_adopt/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'SALARY_EMP_PKG';
    }

    function index($page = 1){
        $data['title'] = 'كشف بدل الاتصال | المعتمد';
        $data['content'] = 'contact_allowance_adopt_index';
        $data['page'] = $page;
        $agree_ma = -1;
        $MODULE_NAME = 'salary';
        $TB_NAME = 'Contact_allowance_adopt';
        $ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial"); //20
        $HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice"); //30
        $InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver"); //40
        $FinancialToPay = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay"); //50
        if (1) {
            if (HaveAccess($ChiefFinancial)) { //اعتماد المدير المالي والاداري
                $agree_ma = 10;
            }elseif (HaveAccess($HeadOffice)) {  //اعتماد مدير المقر
                $agree_ma = 20;
            } elseif (HaveAccess($InternalObserver)) { //اعتماد الرقابة الداخلية
                $agree_ma = 30;
            }elseif (HaveAccess($FinancialToPay)) {  //اعتماد المالية للصرف
                $agree_ma = 40 ;
            }
        }
        //قيمة الاعتماد بناء على الصلاحية
        $data['agree_ma'] = $agree_ma;
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = 'WHERE 1 = 1';

        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND M.BRANCH_ID = '{$this->p_branch_no}' " : '';
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.THE_MONTH= '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO = '{$this->p_emp_no}'  " : "";
        $where_sql .= isset($this->p_w_no) && $this->p_w_no != null ? " AND  M.JOB_TITLE = '{$this->p_w_no}'  " : "";
        $where_sql .= isset($this->p_agree_ma) && $this->p_agree_ma != null ? " AND  M.ADOPT = '{$this->p_agree_ma}'  " : "";
        $where_sql .= isset($this->p_value_ma) && $this->p_value_ma != null ? " AND  M.DESERVED_AMOUNT $this->p_op {$this->p_value_ma}  " : "";
        $where_sql .= isset($this->p_status_type) && $this->p_status_type != null ? " AND  M.STATUS = '{$this->p_status_type}'  " : "";
        $where_sql .= " AND  M.ADOPT >=  10 ";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CONTACT_ALLOWANCE_TB  M ' . $where_sql);
        $sum_rs = $this->get_sum_coloum('DESERVED_AMOUNT','TOTAL_VALUE_MA',' CONTACT_ALLOWANCE_TB  M ',$where_sql);
        $total_distinct_emp_rs = $this->get_distinct_coloum('M.EMP_NO','DIS_EMP_NO',' CONTACT_ALLOWANCE_TB  M ',"WHERE  M.THE_MONTH= '{$this->p_month}'");//عدد الموظفين المتكررين في السجل

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
        $data["page_rows"] = $this->rmodel->getList('CONTACT_ALLOWANCE_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['count_row'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $data['total_value_ma'] = is_array($sum_rs) && count($sum_rs) > 0 ? $sum_rs[0]['TOTAL_VALUE_MA'] : 0;

        $data['distinct_emp_val'] = is_array($total_distinct_emp_rs) && count($total_distinct_emp_rs) > 0 ? count($total_distinct_emp_rs) : 0;//عدد الموظفين المتكررين في السجل
        $data['where_sql_'] = $this->p_month;

        $this->load->view('contact_allowance_adopt_page', $data);
    }

    function excel_report(){
        $where_sql = 'WHERE 1 = 1';

        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND M.BRANCH_ID = '{$this->q_branch_no}' " : '';
        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.THE_MONTH= '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.EMP_NO = '{$this->q_emp_no}'  " : "";
        $where_sql .= isset($this->q_w_no) && $this->q_w_no != null ? " AND  M.JOB_TITLE = '{$this->q_w_no}'  " : "";
        $where_sql .= isset($this->q_agree_ma) && $this->q_agree_ma != null ? " AND  M.ADOPT = '{$this->q_agree_ma}'  " : "";
        $where_sql .= isset($this->q_value_ma) && $this->q_value_ma != null ? " AND  M.DESERVED_AMOUNT $this->q_op {$this->q_value_ma}  " : "";
        $where_sql .= isset($this->q_status_type) && $this->q_status_type != null ? " AND  M.STATUS = '{$this->q_status_type}'  " : "";
        $where_sql .= " AND  M.ADOPT >=  10 ";


        $count_rs = $this->get_table_count(' CONTACT_ALLOWANCE_TB  M  ' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('CONTACT_ALLOWANCE_LIST', $where_sql, 0, $count_rs);
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("النظام الاداري");
        $spreadsheet->setActiveSheetIndex(0);

        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('A1', 'م');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('B1', 'المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('C1', 'الرقم الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('D1', 'اسم الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'المسمى الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'نوع الرصيد');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'الشهر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'الفئة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'مبلغ الفئة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'المبلغ المستحق');


        $from = "A1";
        $to = "J1";
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

        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            if ($val['STATUS'] == 1){
                $category_amount =  $val['CATEGORY_AMOUNT'];
            }else {
                $category_amount = $val['BALANCE'];
            }

            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['EMP_NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['JOB_TITLE_L'])
                ->setCellValue('F' . $rows, $val['STATUS_NAME'])
                ->setCellValue('G' . $rows, $val['THE_MONTH'])
                ->setCellValue('H' . $rows, $val['CATEGORY'])
                ->setCellValue('I' . $rows, $category_amount)
                ->setCellValue('J' . $rows, $val['DESERVED_AMOUNT'])
            ;
            $rows++;
        }

        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "كشف بدل الاتصال" . date('d/m/y', time()) . ".xlsx";
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
            $ret = $this->rmodel->update('CONTACT_ALLOWANCE_ADOPT', $this->_postedData_adopt($adopt_add, $agree_ma, $note));
            if ($ret >= 1) $x++;
        }

        if ($x != count($adopt_no)) {
            echo $ret;
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
            $ret = $this->rmodel->update('CONTACT_ALLOWANCE_UNADOPT', $this->_postedData_adopt($adopt_add, $agree_ma, $note));
            if ($ret >= 1) $x++;
        }
        if ($x != count($adopt_no)) {
            echo 'Error';
        } else {
            echo 1;
        }
    }

    function _postedData_adopt($adopt_no, $agree_ma, $note)
    {
        $result = array(
            array('name' => 'SER', 'value' => $adopt_no, 'type' => '', 'length' => -1),
            array('name' => 'ADOPT', 'value' => $agree_ma, 'type' => '', 'length' => -1),
            array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1),
        );
        return $result;
    }

    public function public_get_adopt_detail()
    {
        $id = $this->input->post('pp_ser');

        if (intval($id) > 0) {
            $rertMain = $this->rmodel->get('CONTACT_ALLOWANCE_ROW_GET', $id);
            $emp_no = $rertMain[0]['EMP_NO'];
            $emp_name = $rertMain[0]['EMP_NAME'];
            $the_month = $rertMain[0]['THE_MONTH'];
            $category_amount = $rertMain[0]['CATEGORY_AMOUNT'];
            $deserved_amount = $rertMain[0]['DESERVED_AMOUNT'];

            $rertMainAdopt = $this->rmodel->get('CONTACT_ALLOWANCE_ADOPT_GET', $id);
            $html = '<div class="card">
                        <div class="card-body">
                             <div class="row">
                                   <div class="form-group  col-md-2">
                                        <label> رقم الموظف </label>
                                        <input type="text" readonly name="emp_no_m" id="txt_emp_no_m" class="form-control" value="' . $emp_no . '">
                                  </div>
                                 <div class="form-group  col-md-3">
                                    <label> اسم الموظف </label>
                                    <input type="text" readonly name="emp_name_m" id="txt_emp_name_m" class="form-control" value="' . $emp_name . '">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label>الشهر</label>
                                    <input type="text" readonly name="month_m" id="txt_month_m" class="form-control" value="' . $the_month . '">
                                </div>
                                 <div class="form-group  col-md-2">
                                    <label>مبلغ الفئة </label>
                                    <input type="text" readonly name="category_amount_m" id="txt_category_amount_m" class="form-control" value="' . $category_amount . '">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label">المبلغ المستحق</label>
                                    <input type="text" readonly name="deserved_amount _m" id="txt_deserved_amount_m" class="form-control" value="' . $deserved_amount . '">
                                </div>
                             </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="adopt_detail_tb">
                                <thead class="table-primary">
                                <tr>
                                      <th>الجهة المعتمدة</th>
                                      <th>اسم المعتمد</th>
                                      <th>تاريخ الاعتماد</th>
                                      <th>ملاحظة الاعتماد</th>
                                      <th>الحركة</th>
                                </tr>
                                </thead>
                                <tbody>';
            foreach ($rertMainAdopt as $rows) {
                $html .= '<tr>
                                <td>' . $rows['ADOPT_NAME'] . '</td>
                                <td>' . $rows['ADOPT_USER_NAME'] . '</td>
                                <td>' . $rows['ADOPT_DATE'] . '</td>
                                <td>' . $rows['NOTE'] . '</td>
                               <td>' . $rows['STATUS_NAME'] . '</td>
                            </tr>';
            }
            $html .= '</tbody></table></div></div></div>';
            echo $html;
        }
    }

    function _lookup(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('salary/constants_sal_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['adopt_cons'] = $this->constant_details_model->get_list(495);
        $data['status_type'] = $this->constant_details_model->get_list(502);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
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
        $tb_name = 'CONTACT_ALLOWANCE_TB M ';
        $month = $this->input->post('month');
        $where_sql  = " AND  M.THE_MONTH= '$month'  ";
        $total_count_emp_rs = $this->get_recurring_records($coloum_name,$tb_name,$where_sql);//عدد الموظفين المتكررين في السجل
        $data['total_count_arr'] = $total_count_emp_rs;
        $this->load->view('Contact_allowance_recurring_records',$data);

    }

}
