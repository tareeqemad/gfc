<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 03/03/2020
 * Time: 11:22 ص
 */
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Bouns_risk extends MY_Controller
{


    var $PAGE_URL = 'payroll_data/bouns_risk/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

    }

    function index($page = 1)
    {

        $data['title'] = 'علاوة المخاطرة -  مرحلة الاعداد';
        $data['content'] = 'bouns_risk_index';
        $data['page'] = $page;
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " and M.EMP_BRANCH in ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
            WHERE    DECODE(GCC_BRAN,8,2,GCC_BRAN) = '{$this->p_branch_no}' ) " : '';
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.MONTH= '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.NO = '{$this->p_emp_no}'  " : "";
        $where_sql .= " AND  M.AGREE_FI >=  0 AND M.AGREE_MA >= 0 ";
        $where_sql .= " AND  M.VALUE_MA !=  0 ";
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.BOUNS_RISK_MA M , DATA.EMPLOYEES D where M.NO = D.NO ' . $where_sql);
        $sum_rs = $this->get_sum_coloum('VALUE_MA','TOTAL_VALUE_MA','DATA.BOUNS_RISK_MA M , DATA.EMPLOYEES D where M.NO = D.NO',$where_sql);

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
        $data["page_rows"] = $this->rmodel->getList('BOUNS_RISK_MA_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['count_row'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $data['total_value_ma'] = is_array($sum_rs) && count($sum_rs) > 0 ? $sum_rs[0]['TOTAL_VALUE_MA'] : 0;

        $this->load->view('bouns_risk_page', $data);
    }


    function excel_report(){
        $where_sql = '';
        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " and M.EMP_BRANCH in ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
            WHERE    DECODE(GCC_BRAN,8,2,GCC_BRAN) = '{$this->q_branch_no}' ) " : '';
        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.MONTH= '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.NO = '{$this->q_emp_no}'  " : "";
        $where_sql .= " AND  M.AGREE_FI >=  0 AND M.AGREE_MA >= 0 ";
        $where_sql .= " AND  M.VALUE_MA !=  0 ";
        $count_rs = $this->get_table_count(' DATA.BOUNS_RISK_MA M , DATA.EMPLOYEES D where M.NO = D.NO ' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('BOUNS_RISK_MA_LIST', $where_sql, 0, $count_rs);
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
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'طبيعة العمل');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'المسمى الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'الراتب الأساسي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'المبلغ');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'تاريخ القرار');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('K1', 'عن شهر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('L1', 'المخاطرة المقترحة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('M1', 'المخاطرة المعتمدة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('N1', 'فرق المخاطرة');


        $from = "A1"; // or any value
        $to = "N1"; // or any value
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

        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['EMP_TYPE_NAME'])
                ->setCellValue('F' . $rows, $val['W_NO_NAME'])
                ->setCellValue('G' . $rows, $val['JOB_TYPE_NAME'])
                ->setCellValue('H' . $rows, $val['B_SALARY'])
                ->setCellValue('I' . $rows, $val['JOB_TYPE_RATIO'])
                ->setCellValue('J' . $rows, $val['DES_DATE'])
                ->setCellValue('K' . $rows, $val['MONTH'])
                ->setCellValue('L' . $rows, $val['VALUE'])
                ->setCellValue('M' . $rows, $val['VALUE_MA'])
                ->setCellValue('N' . $rows, $val['VALUE_MA'] -  $val['VALUE'])

            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "كشف علاوة المخاطرة" . date('d/m/y', time()) . ".xlsx";
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
            $ChkCount = $this->rmodel->getTwoColum('TRANSACTION_PKG', 'BOUNS_RISK_MA_GET', $emp_branch, $month);
            if (count($ChkCount) > 0) {
                echo 'سجل موجود';
                return -1;
            } else {
                $data_arr = array(
                    array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1),
                    array('name' => 'EMP_BRANCH', 'value' => $emp_branch, 'type' => '', 'length' => -1),
                );
                $res = $this->rmodel->update('BOUNS_RISK_MA_TRANS', $data_arr);
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

            $no = $this->input->post('no');
            $month = $this->input->post('month');
            $emp_branch = $this->input->post('emp_branch');
            $job_type = $this->input->post('job_type');
            $data_arr = array(
                array('name' => 'NO', 'value' => $no, 'type' => '', 'length' => -1),
                array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1),
                array('name' => 'JOB_TYPE', 'value' => $job_type, 'type' => '', 'length' => -1)
            );
            $res = $this->rmodel->insert('BOUNS_RISK_MA_INSERT', $data_arr);
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
            $no = $this->input->post('no');
            $job_type = $this->input->post('job_type');
            $month = $this->input->post('month');
            $data_arr = array(
                array('name' => 'P_SER', 'value' => $p_ser, 'type' => '', 'length' => -1),
                array('name' => 'NO', 'value' => $no, 'type' => '', 'length' => -1),
                array('name' => 'JOB_TYPE', 'value' => $job_type, 'type' => '', 'length' => -1),
                array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('BOUNS_RISK_MA_UPDATE', $data_arr);
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

            echo $this->rmodel->delete('BOUNS_RISK_MA_DELETE', $this->p_id);
        }
    }


    function public_get_emp_data()
    {
        $no = $this->input->post('no');
        if (intval($no) > 0) {
            $data = $this->rmodel->get('EMPLOYEE_DETAIL_GET', $no);
            echo json_encode($data);
        }
    }


   function public_get_reason_detail()
    {
        $id = $this->input->post('pp_ser');
        if (intval($id) > 0) {
            $rertMain = $this->rmodel->get('BOUNS_RISK_MA_ROW_GET', $id);
            $no = $rertMain[0]['NO'];
            $emp_name = $rertMain[0]['EMP_NAME'];
            $month = $rertMain[0]['MONTH'];
            $basic_sal = $rertMain[0]['B_SALARY'];
            $value_ma = $rertMain[0]['VALUE_MA'];

            $rertMainAdopt = $this->rmodel->get('BOUNS_RISK_MA_ADOPT_GET', $id);
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
                                    <label>الشهر</label>
                                    <input type="text" readonly name="month_m" id="txt_month_m" class="form-control" value="' . $month . '">
                                </div>
                                 <div class="form-group  col-md-2">
                                    <label>الراتب الاساسي </label>
                                    <input type="text" readonly name="basic_sal_m" id="txt_basic_sal_m" class="form-control" value="' . $basic_sal . '">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label">المخاطرة المعتمدة </label>
                                    <input type="text" readonly name="value_ma_m" id="txt_value_ma_m" class="form-control" value="' . $value_ma . '">
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
            $html .= '</tbody></table></div></div></div></div>';
            echo $html;
        }
    }

    function _lookup(&$data)
    {

        $data['job_types'] = $this->rmodel->getAll('TRANSACTION_PKG', 'BOUNS_RISK_JOB_GET_ALL');
        $this->load->model('salary/constants_sal_model');
        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('settings/constant_details_model');
        $data['adopt_cons'] = $this->constant_details_model->get_list(318);
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