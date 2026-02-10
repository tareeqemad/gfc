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
class Bouns_risk_adopt extends MY_Controller
{

    var $PAGE_URL = 'payroll_data/bouns_risk_adopt/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';
        //this for constant using

    }

    function index($page = 1)
    {
        $data['title'] = 'كشف علاوة المخاطرة | المعتمد ادارياً';
        $data['content'] = 'bouns_risk_adopt_index';
        $data['page'] = $page;
        $agree_ma = -1;
        $MODULE_NAME = 'payroll_data';
        $TB_NAME = 'bouns_risk_adopt';
        $ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial"); //10
        $HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice"); //30
        $InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver"); //31
        $GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector"); //33
        $FinancialToPay = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay"); //35
        if (1) {
            //اعتماد المدير المالي
            if (HaveAccess($ChiefFinancial)) {
                $agree_ma = 1;
            }elseif (HaveAccess($HeadOffice)) {  //اعتماد مدير المقر
                $agree_ma = 10;
            }
            //اعتماد الرقابة
            elseif (HaveAccess($InternalObserver)) { //اعتماد الرقابة الداخلية
                $agree_ma = 30;
            }


            elseif (HaveAccess($GeneralDirector)) { //اعتماد المدير العام
                $agree_ma = 31;
            }

            //اعتماد المالية للصرف
            elseif (HaveAccess($FinancialToPay)) { //اعتماد المالية
                $agree_ma = 31;
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

        $where_sql = '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " and M.EMP_BRANCH in ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
            WHERE    DECODE(GCC_BRAN,8,2,GCC_BRAN) = '{$this->p_branch_no}' ) " : '';

        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.MONTH= '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.NO = '{$this->p_emp_no}'  " : "";
        $where_sql .= isset($this->p_w_no) && $this->p_w_no != null ? " AND   D.W_NO = '{$this->p_w_no}'  " : "";
        $where_sql .= isset($this->p_emp_type) && $this->p_emp_type != null ? " AND   D.EMP_TYPE = '{$this->p_emp_type}'  " : "";
        $where_sql .= isset($this->p_agree_ma) && $this->p_agree_ma != null ? " AND  M.AGREE_MA = '{$this->p_agree_ma}'  " : "";
        $where_sql .= isset($this->p_agree_fi) && $this->p_agree_fi != null ? " AND  M.AGREE_FI = '{$this->p_agree_fi}'  " : "";
        $where_sql .= isset($this->p_value_ma) && $this->p_value_ma != null ? " AND  M.VALUE_MA $this->p_op {$this->p_value_ma}  " : "";
        $where_sql .= " AND  M.VALUE_MA  != 0 ";
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.BOUNS_RISK_MA M , DATA.EMPLOYEES D where M.NO = D.NO' . $where_sql);
        $sum_rs = $this->get_sum_coloum('VALUE_MA','TOTAL_VALUE_MA','DATA.BOUNS_RISK_MA M , DATA.EMPLOYEES D where M.NO = D.NO',$where_sql);
        $total_distinct_emp_rs = $this->get_distinct_coloum('M.NO','DIS_EMP_NO','DATA.BOUNS_RISK_MA  M , DATA.EMPLOYEES D WHERE  M.NO = D.NO',"AND  M.MONTH= '{$this->p_month}'");//عدد الموظفين المتكررين في السجل
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
        $data['distinct_emp_val'] = is_array($total_distinct_emp_rs) && count($total_distinct_emp_rs) > 0 ? count($total_distinct_emp_rs) : 0;//عدد الموظفين المتكررين في السجل
        $data['where_sql_'] = $this->p_month;
        $data['param_branch'] = $this->p_branch_no;
        $this->load->view('bouns_risk_adopt_page', $data);

    }
    function excel_report(){
        $where_sql = '';
        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " and M.EMP_BRANCH in ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
            WHERE    DECODE(GCC_BRAN,8,2,GCC_BRAN) = '{$this->q_branch_no}' ) " : '';
        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.MONTH= '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.NO = '{$this->q_emp_no}'  " : "";
        $where_sql .= isset($this->q_w_no) && $this->q_w_no != null ? " AND   D.W_NO = '{$this->q_w_no}'  " : "";
        $where_sql .= isset($this->q_emp_type) && $this->q_emp_type != null ? " AND   D.EMP_TYPE = '{$this->q_emp_type}'  " : "";
        $where_sql .= isset($this->q_agree_ma) && $this->q_agree_ma != null ? " AND  M.AGREE_MA = '{$this->q_agree_ma}'  " : "";
        $where_sql .= isset($this->q_agree_fi) && $this->q_agree_fi != null ? " AND  M.AGREE_FI = '{$this->q_agree_fi}'  " : "";
        $where_sql .= isset($this->q_value_ma) && $this->q_value_ma != null ? " AND  M.VALUE_MA $this->q_op {$this->q_value_ma}  " : "";
        $where_sql .= " AND  M.VALUE_MA  != 0 ";

        $count_rs = $this->get_table_count(' DATA.BOUNS_RISK_MA M , DATA.EMPLOYEES D where M.NO = D.NO' . $where_sql);
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
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'النسبة / المبلغ');
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
                ->setCellValue('G' . $rows, $val['W_NO_ADMIN_NAME'])
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
        $filename = "كشف علاوة المخاطرة المعتمد ادارياً" . date('d/m/y', time()) . ".xlsx";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        set_time_limit(500);
        ini_set('memory_limit', '-1');
        $object_writer->save('php://output');
    }

    function excel_monitoring()
    {
        $branch_no = isset($this->q_branch_no) ? intval($this->q_branch_no) : null;
        $month     = isset($this->q_month) ? intval($this->q_month) : null;

        $where_sql = '';
        if ($branch_no > 0) {
            $where_sql .= " AND M.EMP_BRANCH IN (
            SELECT BRANCH_NO FROM DATA.BRANCH 
            WHERE DECODE(GCC_BRAN, 8, 2, GCC_BRAN) = {$branch_no}
        ) ";
        }

        if ($month > 0) {
            $where_sql .= " AND M.MONTH = {$month} ";
        }

        $count_rs = $this->get_table_count('DATA.BOUNS_RISK_MA M, DATA.EMPLOYEES D WHERE M.NO = D.NO' . $where_sql);
        $count_rs = isset($count_rs[0]['NUM_ROWS']) ? $count_rs[0]['NUM_ROWS'] : 0;

        if ($count_rs == 0) {
            exit("لا توجد بيانات للتصدير.");
        }

        $excelData = $this->rmodel->getList('BOUNS_RISK_MA_REP', $where_sql, 0, $count_rs);

        $sum_rs = $this->get_sum_coloum(
            'VALUE_MA',
            'TOTAL_VALUE_MA',
            'DATA.BOUNS_RISK_MA M , DATA.EMPLOYEES D where M.NO = D.NO',
            $where_sql
        );
        $total_value_ma = is_array($sum_rs) && count($sum_rs) > 0 ? $sum_rs[0]['TOTAL_VALUE_MA'] : 0;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setRightToLeft(true);

        // 🟦 الهيدر
        $headers = [
            'A1' => 'المقر',
            'B1' => 'رقم الموظف',
            'C1' => 'اسم الموظف',
            'D1' => 'نوع التعيين',
            'E1' => 'المسمى المهني',
            'F1' => 'المسمى الوظيفي',
            'G1' => 'الراتب الأساسي',
            'H1' => 'النسبة / المبلغ',
            'I1' => 'عن شهر',
            'J1' => 'المخاطرة المقترحة',
            'K1' => 'المخاطرة المعتمدة',
            'L1' => 'فرق المخاطرة',
            'M1' => 'العلاوة الإشرافية'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // 🎨 تنسيق الهيدر
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9D9D9']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A1:M1')->applyFromArray($headerStyle);

        // 🟩 تعبئة البيانات
        $rowIndex = 2;
        foreach ($excelData as $row) {
            $sheet->setCellValue('A' . $rowIndex, $row['BRANCH_NAME']);
            $sheet->setCellValue('B' . $rowIndex, $row['NO']);
            $sheet->setCellValue('C' . $rowIndex, $row['EMP_NAME']);
            $sheet->setCellValue('D' . $rowIndex, $row['EMP_TYPE_NAME']);
            $sheet->setCellValue('E' . $rowIndex, $row['JOB_TYPE_NAME']);
            $sheet->setCellValue('F' . $rowIndex, $row['W_NO_ADMIN_NAME']);
            $sheet->setCellValue('G' . $rowIndex, number_format($row['BASIC_MONTH'], 2));
            $sheet->setCellValue('H' . $rowIndex, number_format($row['JOB_TYPE_RATIO'], 2));
            $sheet->setCellValue('I' . $rowIndex, $row['MONTH']);
            $sheet->setCellValue('J' . $rowIndex, $row['VALUE']);
            $sheet->setCellValue('K' . $rowIndex, $row['VALUE_MA']);
            $sheet->setCellValue('L' . $rowIndex, number_format($row['VALUE_MA'] - $row['VALUE'], 2));
            $sheet->setCellValue('M' . $rowIndex, number_format($row['SUPERVISOR_VAL'], 2));
            $rowIndex++;
        }

        // ✅ فوتر: الإجمالي
        $sheet->setCellValue('J' . $rowIndex, 'الإجمالي:');
        $sheet->setCellValue('K' . $rowIndex, number_format($total_value_ma, 2));

        $sheet->getStyle("J{$rowIndex}:K{$rowIndex}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF2CC']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ]);

        // 🧭 تنسيقات عامة
        $sheet->getStyle("A1:M{$rowIndex}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 📤 تصدير الملف
        $writer = new Xlsx($spreadsheet);
        $filename = 'تقرير_الرقابة_' . date('Y-m-d') . '.xlsx';

        ob_end_clean();
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");

        $writer->save('php://output');
        exit;
    }

    function public_adopt()
    {
        $adopt_no = $this->input->post('pp_ser');
        $agree_ma = $this->input->post('agree_ma');
        $note = $this->input->post('note');
        $x = 0;
        foreach ($adopt_no as $adopt_add) {
            $ret = $this->rmodel->update('BOUNS_RISK_MA_ADOPT', $this->_postedData_adopt($adopt_add, $agree_ma, $note));
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
            $ret = $this->rmodel->update('BOUNS_RISK_MA_UNADOPT', $this->_postedData_adopt($adopt_add, $agree_ma, $note));
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
            $rertMain = $this->rmodel->get('BOUNS_RISK_MA_ROW_GET', $id);
            $no = $rertMain[0]['NO'];
            $emp_name = $rertMain[0]['EMP_NAME'];
            $month = $rertMain[0]['MONTH'];
            $basic_sal = $rertMain[0]['B_SALARY'];
            $value_ma = $rertMain[0]['VALUE_MA'];

            $rertMainAdopt = $this->rmodel->get('BOUNS_RISK_MA_ADOPT_GET', $id);
            $html = '<div class="container">
                        <div class="card-border">
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
                                <td>' . $rows['ADOPT_DATE_TIME'] . '</td>
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

        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('salary/constants_sal_model');
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);
        $data['bran_cons'] = $this->constants_sal_model->get_list(5);
        $this->load->model('settings/constant_details_model');
        $data['adopt_cons'] = $this->constant_details_model->get_list(313);
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
            array('name' =>  ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
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

        $coloum_name = 'M.NO';
        $tb_name = 'DATA.BOUNS_RISK_MA M ';
        $month = $this->input->post('month');
        $where_sql  = " AND  M.MONTH= '{$month}'  ";
        $total_count_emp_rs = $this->get_recurring_records($coloum_name,$tb_name,$where_sql);//عدد الموظفين المتكررين في السجل
        $data['total_count_arr'] = $total_count_emp_rs;
        $this->load->view('bouns_risk_recurring_records',$data);

    }
}
