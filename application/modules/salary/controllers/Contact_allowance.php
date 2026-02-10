<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 20/02/23
 * Time: 08:20 ص
 */
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Contact_allowance extends MY_Controller
{

    var $PAGE_URL = 'salary/Contact_allowance/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'SALARY_EMP_PKG';

    }

    function index($page = 1)
    {

        $data['title'] = 'كشف بدل الاتصال';
        $data['content'] = 'contact_allowance_index';
        $data['page'] = $page;
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
        $where_sql .= isset($this->p_status_type) && $this->p_status_type != null ? " AND  M.STATUS = '{$this->p_status_type}'  " : "";
        $where_sql .= " AND  M.ADOPT >=  1 ";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CONTACT_ALLOWANCE_TB  M ' . $where_sql);
        $sum_rs = $this->get_sum_coloum('DESERVED_AMOUNT','TOTAL_VALUE_MA',' CONTACT_ALLOWANCE_TB  M ',$where_sql);
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

        $this->load->view('contact_allowance_page', $data);
    }

    function excel_report(){
        $where_sql = 'WHERE 1 = 1';

        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND M.BRANCH_ID = '{$this->q_branch_no}' " : '';
        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.THE_MONTH= '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.EMP_NO = '{$this->q_emp_no}'  " : "";
        $where_sql .= isset($this->q_status_type) && $this->q_status_type != null ? " AND  M.STATUS = '{$this->q_status_type}'  " : "";
        $where_sql .= " AND  M.ADOPT >=  1 ";

        $count_rs = $this->get_table_count(' CONTACT_ALLOWANCE_TB  M ' . $where_sql);
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

    function trans_data()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $emp_branch = $this->input->post('emp_branch');
            $month = $this->input->post('month');
            $ChkCount = $this->rmodel->getTwoColum('SALARY_EMP_PKG', 'CONTACT_ALLOWANCE_GET', $emp_branch, $month);
            if (count($ChkCount) > 0) {
                echo 'سجل موجود';
                return -1;
            } else {
                $data_arr = array(
                    array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1),
                    array('name' => 'EMP_BRANCH', 'value' => $emp_branch, 'type' => '', 'length' => -1),
                );
                $res = $this->rmodel->update('CONTACT_ALLOWANCE_TRANS', $data_arr);
                if (intval($res) >= 1) {
                    echo 1;
                } else {
                    $this->print_error('Error_' . $res);
                }
            }

        }
    }

    function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $emp_no = $this->input->post('emp_no');
            $category_amount = $this->input->post('category_amount');
            $category = $this->input->post('category');
            $deserved_amount = $this->input->post('deserved_amount');
            $the_month = $this->input->post('the_month');
            $billing_value = $this->input->post('billing_value');
            $status = $this->input->post('status');

            $data_arr = array(
                array('name' => 'EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
                array('name' => 'CATEGORY', 'value' => $category, 'type' => '', 'length' => -1),
                array('name' => 'CATEGORY_AMOUNT', 'value' => $category_amount, 'type' => '', 'length' => -1),
                array('name' => 'DESERVED_AMOUNT', 'value' => $deserved_amount, 'type' => '', 'length' => -1),
                array('name' => 'THE_MONTH', 'value' => $the_month, 'type' => '', 'length' => -1),
                array('name' => 'BILLING_VALUE', 'value' => $billing_value, 'type' => '', 'length' => -1),
                array('name' => 'STATUS', 'value' => $status, 'type' => '', 'length' => -1)
            );
            $res = $this->rmodel->insert('CONTACT_ALLOWANCE_INSERT', $data_arr);
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }

    function update_data()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $p_ser = $this->input->post('pp_ser');
            $emp_no_m = $this->input->post('emp_no_m');
            $category_m = $this->input->post('category_m');
            $category_amount_m = $this->input->post('category_amount_m');
            $deserved_amount_m = $this->input->post('deserved_amount_m');
            $month_m = $this->input->post('month_m');
            $billing_value_m = $this->input->post('billing_value_m');

            $data_arr = array(
                array('name' => 'SER', 'value' => $p_ser, 'type' => '', 'length' => -1),
                array('name' => 'EMP_NO', 'value' => $emp_no_m, 'type' => '', 'length' => -1),
                array('name' => 'CATEGORY', 'value' => $category_m, 'type' => '', 'length' => -1),
                array('name' => 'CATEGORY_AMOUNT', 'value' => $category_amount_m, 'type' => '', 'length' => -1),
                array('name' => 'DESERVED_AMOUNT', 'value' => $deserved_amount_m, 'type' => '', 'length' => -1),
                array('name' => 'THE_MONTH', 'value' => $month_m, 'type' => '', 'length' => -1),
                array('name' => 'BILLING_VALUE', 'value' => $billing_value_m, 'type' => '', 'length' => -1)
            );
            $res = $this->rmodel->update('CONTACT_ALLOWANCE_UPDATE', $data_arr);
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }

    function delete_data()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->rmodel->delete('CONTACT_ALLOWANCE_DELETE', $this->p_id);
        }
    }

    function public_get_emp_data_1()
    {
        $no = $this->input->post('no');
        $the_month = $this->input->post('the_month');
        if (intval($no) > 0) {
            $data = $this->rmodel->getTwoColum('SALARY_EMP_PKG','EMPLOYEE_DETAIL_1_GET', $no ,$the_month);
            echo json_encode($data);
        }
    }
    function public_get_emp_data_2()
    {
        $no = $this->input->post('no');
        $the_month = $this->input->post('the_month');
        if (intval($no) > 0) {
            $data = $this->rmodel->getTwoColum('SALARY_EMP_PKG','EMPLOYEE_DETAIL_2_GET', $no ,$the_month);
            echo json_encode($data);
        }
    }

    function public_get_emp_data_exception()
    {
        $no = $this->input->post('no');
        $the_month = $this->input->post('the_month');
        if (intval($no) > 0) {
            $data = $this->rmodel->getTwoColum('SALARY_EMP_PKG','EMPLOYEE_DETAIL_EXCEPTION_GET', $no ,$the_month);
            echo json_encode($data);
        }
    }

    function public_get_reason_detail()
    {
        $id = $this->input->post('pp_ser');

        if (intval($id) > 0) {
            $rertMain = $this->rmodel->get('CONTACT_ALLOWANCE_ROW_GET', $id);
            $emp_no = $rertMain[0]['EMP_NO'];
            $emp_name = $rertMain[0]['EMP_NAME'];
            $the_month = $rertMain[0]['THE_MONTH'];
            if($rertMain[0]['STATUS'] == 1){
                $category_amount = $rertMain[0]['CATEGORY_AMOUNT'];
            }else{
                $category_amount = $rertMain[0]['BALANCE'];
            }
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
                                    <input type="text" readonly name="deserved_amount_m" id="txt_deserved_amount_m" class="form-control" value="' . $deserved_amount . '">
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
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('salary/constants_sal_model');
        $this->load->model('settings/constant_details_model');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['status_type'] = $this->constant_details_model->get_list(502);
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