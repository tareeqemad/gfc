<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 11:24 ص
 */
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class No_reson_name_adopt extends MY_Controller
{

    var $PAGE_URL = 'payroll_data/no_reson_name_adopt/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';
        //this for constant using

    }

    function index($page = 1)
    {

        $data['title'] = 'كشف الغير ملتزمين بالانصراف | المعتمد ادارياً';
        $data['content'] = 'no_reson_name_adopt_index';
        $data['page'] = $page;
        $post = -1;
        $MODULE_NAME = 'payroll_data';
        $TB_NAME = 'no_reson_name_adopt';
        $ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial");
        $HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice"); //اعتماد مدير المقر
        $InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver"); //اعتماد الرقابة
        $GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector"); //اعتماد المدير العام
        $FinancialToPay = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay"); //اعتماد المالية للصرف
        if (1) {
            //اعتماد المدير المالي
            if (HaveAccess($ChiefFinancial)) {
                $post = 10;
            }elseif (HaveAccess($HeadOffice)) {
                $post = 11;
            }elseif (HaveAccess($InternalObserver)) {
                $post = 13;
            }elseif (HaveAccess($GeneralDirector)) {
                $post = 14;
            }elseif (HaveAccess($FinancialToPay)) {
                $post = 14;
            }
        }
        //قيمة الاعتماد بناء على الصلاحية
        $data['post'] = $post;
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->p_branch_no}' ) " : '';
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.NO = '{$this->p_emp_no}'  " : "";
        $where_sql .= isset($this->p_the_month) && $this->p_the_month != null ? " AND  M.THE_MONTH = '{$this->p_the_month}'  " : "";
        $where_sql .= isset($this->p_month_sal) && $this->p_month_sal != null ? " AND  M.MONTH_SAL = '{$this->p_month_sal}'  " : ""; //شهر احتساب الراتب
        if ($this->p_post == 15 && $this->p_branch_no != 1 ){
            $where_sql .= isset($this->p_post) && $this->p_post != null ? " AND  M.POST = '14'  " : ""; //حالة الاعتماد
        }else{
            $where_sql .= isset($this->p_post) && $this->p_post != null ? " AND  M.POST = '{$this->p_post}'  " : ""; //حالة الاعتماد
        }
        $total_deduction_rs = $this->get_sum_coloum('TRANSACTION_PKG.TOTAL_DEDUCTION_NO_RESON_ADOPT(M.NO,M.THE_MONTH,M.COUNT_NO)','A_TOTAL_DEDUCTION','DATA.COUNT_NO_RESON M WHERE 1 = 1',$where_sql);
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.COUNT_NO_RESON  M WHERE 1 =1' . $where_sql);
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
        $data["page_rows"] = $this->rmodel->getList('COUNT_NO_RESON_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['v_count_row'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $data['v_total_deduction'] = is_array($total_deduction_rs) && count($total_deduction_rs) > 0 ? $total_deduction_rs[0]['A_TOTAL_DEDUCTION'] : 0;
        $data['param_branch'] = $this->p_branch_no;
        $this->load->view('no_reson_name_adopt_page', $data);

    }
    function excel_report(){

        $where_sql = '';
        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->q_branch_no}' ) " : '';
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.NO = '{$this->q_emp_no}'  " : "";
        $where_sql .= isset($this->q_the_month) && $this->q_the_month != null ? " AND  M.THE_MONTH = '{$this->q_the_month}'  " : "";
        $where_sql .= isset($this->q_month_sal) && $this->q_month_sal != null ? " AND  M.MONTH_SAL = '{$this->q_month_sal}'  " : ""; //شهر احتساب الراتب
        $where_sql .= isset($this->q_post) && $this->q_post != null ? " AND  M.POST = '{$this->q_post}'  " : ""; //حالة الاعتماد

        $count_rs = $this->get_table_count(' DATA.COUNT_NO_RESON  M WHERE 1 =1' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('COUNT_NO_RESON_LIST', $where_sql, 0, $count_rs);
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
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'شهر عدم الالتزام');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'عدد ايام التوقيع');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'عدد مرات الاعفاء');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'عدد مرات الخصم');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'عدد ايام الخصم');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'شهر احتساب الراتب');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('K1', 'قيمة الاستقطاع');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('L1', 'مبلغ الاستقطاع');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('M1', 'حالة الاعتماد');



        $from = "A1"; // or any value
        $to = "M1"; // or any value
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

        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['THE_MONTH'])
                ->setCellValue('F' . $rows, $val['COUNT_NO'])
                ->setCellValue('G' . $rows, $val['COUNT_PERMISSION'])
                ->setCellValue('H' . $rows, $val['COUNT_MINUS'])
                ->setCellValue('I' . $rows, $val['COUNT_DAY'])
                ->setCellValue('J' . $rows, $val['TOTAL_DEDUCTION'])
                ->setCellValue('K' . $rows, $val['ADOPT_STATUS_NAME'])
                ->setCellValue('L' . $rows, $val['VALUE'])
                ->setCellValue('M' . $rows, $val['VALUE_MA'])
            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "كشف الغير ملتزمين بالانصراف | المعتمد ادارياً" . date('d/m/y', time()) . ".xlsx";
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
        $post = $this->input->post('post');
        $month_sal = $this->input->post('month_sal');
        $note = $this->input->post('note');
        $x = 0;
        foreach ($adopt_no as $adopt_add) {
            $ret = $this->rmodel->update('P_COUNT_NO_RESON_ADOPT', $this->_postedData_adopt($adopt_add, $post,$month_sal, $note));
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
        $post = $this->input->post('post');
        $branch_no = $this->input->post('branch_no');
        $note = $this->input->post('note');
        $x = 0;
        foreach ($adopt_no as $adopt_add) {
            $ret = $this->rmodel->update('P_COUNT_NO_RESON_UN_ADOPT', $this->_postedData_un_adopt($adopt_add, $post,$branch_no, $note));
            if ($ret >= 1) $x++;
        }
        if ($x != count($adopt_no)) {
            echo 'Error';
        } else {
            echo 1;
        }
    }

    function _postedData_adopt($adopt_no, $post,$month_sal, $note)
    {
        $result = array(
            array('name' => 'P_SER_IN', 'value' => $adopt_no, 'type' => '', 'length' => -1),
            array('name' => 'POST_IN', 'value' => $post, 'type' => '', 'length' => -1),
            array('name' => 'MONTH_SAL_IN', 'value' => $month_sal, 'type' => '', 'length' => -1),
            array('name' => 'NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),
        );
        return $result;
    }

    function _postedData_un_adopt($adopt_no, $post,$month_sal, $note)
    {
        $result = array(
            array('name' => 'P_SER_IN', 'value' => $adopt_no, 'type' => '', 'length' => -1),
            array('name' => 'POST_IN', 'value' => $post, 'type' => '', 'length' => -1),
            array('name' => 'MONTH_SAL_IN', 'value' => $month_sal, 'type' => '', 'length' => -1),
            array('name' => 'NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),
        );
        return $result;
    }
    public function public_get_adopt_detail()
    {
        $id = $this->input->post('p_ser');
        if (intval($id) > 0) {
            $rertMain = $this->rmodel->get('COUNT_NO_RESON_ROW_GET', $id);
            $no = $rertMain[0]['NO']; //رقم الموظف
            $emp_name = $rertMain[0]['EMP_NAME']; //اسم الموظف
            $the_month = $rertMain[0]['THE_MONTH']; // شهر التاخير
            $total_deduction = $rertMain[0]['TOTAL_DEDUCTION']; //قيمة الخصم
            $month_sal = $rertMain[0]['MONTH_SAL']; //شهر احتساب الراتب
            $basic_sal = $rertMain[0]['BASIC_SAL'];  //الراتب الاساسي
            $adopt_status_name = $rertMain[0]['ADOPT_STATUS_NAME']; //حالة الاعتماد

            $rertMainAdopt = $this->rmodel->get('COUNT_NO_RESON_ADOPT_GET', $id);
            $html = '<div class="tr_border">
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
                                    <label>شهر التاخير </label>
                                    <input type="text" readonly name="month_m" id="txt_month_m" class="form-control" value="' . $the_month . '">
                                 </div>
                                 <div class="form-group  col-md-2">
                                    <label>قيمة الخصم </label>
                                    <input type="text" readonly name="total_deduction_m" id="txt_total_deduction_m" class="form-control" value="' . $total_deduction . '">
                                 </div>
                                  <div class="form-group  col-md-3">
                                    <label>شهر احتساب الراتب </label>
                                    <input type="text" readonly name="month_sal_m" id="txt_month_sal_m" class="form-control" value="' . $month_sal . '">
                                </div>
                                 <div class="form-group  col-md-2">
                                    <label>الراتب الاساسي</label>
                                    <input type="text" readonly name="basic_sal_ة" id="txt_basic_sal_m" class="form-control" value="' . $basic_sal . '">
                                 </div>
                                  <div class="form-group  col-md-3">
                                    <label>حالة الاعتماد</label>
                                    <input type="text" readonly name="adopt_status_name_m" id="txt_adopt_status_name_m" class="form-control" value="' . $adopt_status_name . '">
                                </div>
                             </div>
                            <div class="table-responsive tableRoundedCorner">
                                <table class="table mg-b-0 text-md-nowrap roundedTable table-bordered" id="adopt_detail_tb">
                                <thead class="table-light">
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
            $html .= '</tbody></table></div>';
            echo $html;
        }
    }


    function _lookup(&$data)
    {

        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('settings/constant_details_model');
        $data['adopt_cons'] = $this->constant_details_model->get_list(443);
        $this->load->model('hr_attendance/hr_attendance_model');
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
}
