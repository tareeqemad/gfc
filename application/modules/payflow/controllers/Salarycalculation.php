<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
class Salarycalculation extends MY_Controller
{
    var $PAGE_URL = 'payflow/Salarycalculation/review_salary_calculation';
    var $MODEL_NAME = 'Salarycalculation_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'SALARYFORM';
    }

    /**
     * ✅ الشاشة الرئيسية لاحتساب الرواتب
     */
    function public_index($page = 1)
    {
        if (HaveAccess('payflow/Salarycalculation/public_index')) {
            $data['title'] = 'احتساب الرواتب';
            $data['content'] = 'salary_calculation_index';
            $data['page'] = $page;
            $data['emp_list'] = $this->rmodel->getAll('SALARYFORM', 'GET_ALL_ACTIVE_EMPLOYEES');
            $this->_look_ups($data);
            $this->load->view('template/template1', $data);
        }else{
            die('Access Denied');
        }
    }


    /**
     * ✅ احتساب الرواتب (كامل / جزئي / حسب النسبة)
     */
    function calculate_salaries($type = 'full')
    {
        $from_month = intval($this->input->post('from_month'));
        $emp_no = $this->input->post('emp_no');

        $partial_rate = floatval($this->input->post('partial_rate'));
        $partial_l_value = floatval($this->input->post('partial_l_value'));
        $partial_h_value = floatval($this->input->post('partial_h_value'));
        $partial_days = intval($this->input->post('partial_days'));

        $percentage_rate = floatval($this->input->post('percentage_rate'));
        $percentage_l_value = floatval($this->input->post('percentage_l_value'));
        $percentage_h_value = floatval($this->input->post('percentage_h_value'));

        if (!$emp_no) {
            echo json_encode(['status' => 'error', 'message' => '⚠️ يجب اختيار موظف واحد أو جميع الموظفين.']);
            return;
        }

        $emp_no_list = ($emp_no === 'ALL') ? $this->_get_employee_list("ALL") : $this->_get_employee_list($emp_no);

        if (!$emp_no_list) {
            echo json_encode(['status' => 'error', 'message' => '⚠️ لا يوجد موظفين للاحتساب.']);
            return;
        }

        switch ($type) {
            case 'ratio':
                $procedure = 'CAL_SALARY_RATE_PART';

                if ($emp_no === 'ALL' || count($emp_no_list) > 1) {
                    echo json_encode(['status' => 'error', 'message' => '⚠️ لا يمكن اختيار جميع الموظفين عند الاحتساب الجزئي.']);
                    return;
                }

                if ($partial_days < 1 || $partial_days > 31) {
                    echo json_encode(['status' => 'error', 'message' => '⚠️ عدد الأيام يجب أن يكون بين 1 و 31.']);
                    return;
                }

                $no_from = $no_to = $emp_no;
                break;

            case 'percentage':
                $procedure = 'CAL_SALARY_RATE';
                break;

            default:
                $procedure = 'CAL_SALARY_NEW';
        }

        // ✅ معالجة استدعاء CAL_SALARY_NEW بشكل صحيح
        if ($type === 'full') {
            $params = [
                ['name' => ':MY_MONTH', 'value' => $from_month, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':NO_FROM', 'value' => min($emp_no_list), 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':NO_TO', 'value' => max($emp_no_list), 'type' => SQLT_INT, 'length' => -1]
            ];
        }

        if ($type === 'ratio') {
            $params = [
                ['name' => ':THE_MONTH_IN', 'value' => $from_month, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':V_EMP_NO', 'value' => $emp_no, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':RATE_IN', 'value' => $partial_rate, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':DAY_CNT', 'value' => $partial_days, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':L_VALUE_IN', 'value' => $partial_l_value, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':H_VALUE_IN', 'value' => $partial_h_value, 'type' => SQLT_INT, 'length' => -1]
            ];
        } elseif ($type === 'percentage') {
            $params = [
                ['name' => ':THE_MONTH_IN', 'value' => $from_month, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':NO_FROM_IN', 'value' => min($emp_no_list), 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':NO_TO_IN', 'value' => max($emp_no_list), 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':RATE_IN', 'value' => $percentage_rate, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':L_VALUE_IN', 'value' => $percentage_l_value, 'type' => SQLT_INT, 'length' => -1],
                ['name' => ':H_VALUE_IN', 'value' => $percentage_h_value, 'type' => SQLT_INT, 'length' => -1]
            ];
        }

        $result = $this->{$this->MODEL_NAME}->executeProcedure('SALARYFORM', $procedure, $params);

        echo json_encode($result !== false
            ? ['status' => 'success', 'message' => '✅ تم بدء احتساب الرواتب!', 'total' => count($emp_no_list)]
            : ['status' => 'error', 'message' => "⚠️ فشل احتساب الرواتب للموظفين من رقم " . min($emp_no_list) . " إلى " . max($emp_no_list)]
        );
    }




    /**
     * ✅ متابعة تقدم الحساب
     */
    function get_salary_progress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $month = intval($this->input->post('month'));

            $processedEmployees = $this->{$this->MODEL_NAME}->executeFunction('SALARYFORM', 'COUNT_PROCESSED_EMPLOYEES', [
                ['name' => ':MONTH', 'value' => $month, 'type' => SQLT_INT, 'length' => -1]
            ]);

            echo json_encode([
                'status' => ($processedEmployees > 0 ? 'progress' : 'done'),
                'processed' => $processedEmployees ?: 0
            ]);
        }
    }


    /**
     * ✅ مراجعة الاحتساب
     */
    /**
     * ✅ مراجعة الاحتساب مع دعم اختيار "ALL" وجلب كل الموظفين عند الحاجة
     */
    function review_salary_calculation($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = 'WHERE 1 = 1';

        // ✅ التحقق من الفروع (إذا تم تحديدها)
        if (isset($this->p_branch_no) && !empty($this->p_branch_no)) {
            $where_sql .= " AND TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN (
        SELECT M.BRANCH_NO FROM DATA.BRANCH M WHERE GCC_BRAN = '{$this->p_branch_no}'
    ) ";
        }

        // ✅ التحقق من الشهر (إذا تم تحديده)
        if (isset($this->p_month) && !empty($this->p_month)) {
            $where_sql .= " AND M.MONTH = '{$this->p_month}' ";
        }

        // ✅ التحقق من الموظفين
        if (isset($this->p_emp_no) && !empty($this->p_emp_no)) {
            if (is_array($this->p_emp_no)) {
                if (!in_array("ALL", $this->p_emp_no)) {
                    $emp_list = implode("','", array_map('intval', $this->p_emp_no));
                    $where_sql .= " AND M.EMP_NO IN ('{$emp_list}') ";
                }
            } else {
                if ($this->p_emp_no !== "ALL") {
                    $where_sql .= " AND M.EMP_NO = '{$this->p_emp_no}' ";
                }
            }
        }
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.ADMIN_TEST M ' . $where_sql);
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
        // ✅ جلب البيانات
        $data["page_rows"] = $this->rmodel->getList('DATA_ADMIN_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('salary_calculated_page', $data);
    }




    function excel_salary_calculation() {
        $where_sql = 'WHERE 1 = 1';

        // ✅ التحقق من الفروع (إذا تم تحديدها)
        if (isset($this->q_branch_no) && !empty($this->q_branch_no)) {
            $where_sql .= " AND TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN (
        SELECT M.BRANCH_NO FROM DATA.BRANCH M WHERE GCC_BRAN = '{$this->q_branch_no}'
    ) ";
        }

        // ✅ التحقق من الشهر (إذا تم تحديده)
        if (isset($this->q_month) && !empty($this->q_month)) {
            $where_sql .= " AND M.MONTH = '{$this->q_month}' ";
        }

        // ✅ التحقق من الموظفين
        if (isset($this->q_emp_no) && !empty($this->q_emp_no)) {
            if (is_array($this->q_emp_no)) {
                if (!in_array("ALL", $this->q_emp_no)) {
                    $emp_list = implode("','", array_map('intval', $this->q_emp_no));
                    $where_sql .= " AND M.EMP_NO IN ('{$emp_list}') ";
                }
            } else {
                if ($this->q_emp_no !== "ALL") {
                    $where_sql .= " AND M.EMP_NO = '{$this->q_emp_no}' ";
                }
            }
        }

        $count_rs = $this->get_table_count(' DATA.ADMIN_TEST M ' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];

        $excelData = $this->rmodel->getList('DATA_ADMIN_LIST', $where_sql, 0, $count_rs);

        // ✅ إنشاء ملف Excel
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("النظام الإداري");
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setRightToLeft(true);

        // ✅ إضافة عنوان التقرير مع التاريخ والوقت
        date_default_timezone_set('Asia/Gaza');
        $currentDateTime = date('d-m-Y H:i:s');
        $reportTitle = "تقرير رواتب شهر " . ($this->q_month ?? "غير محدد") . " - $currentDateTime";
        $sheet->setCellValue('A1', $reportTitle);
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

        // ✅ تعيين العناوين
        $headers = ['#', 'رقم الموظف', 'اسم الموظف', 'الشهر', 'نوع التعيين', 'المقر', 'الإدارة', 'البنك', 'الحساب', 'المسمى', 'صافي الراتب'];
        $columns = range('A', 'K');

        foreach ($headers as $index => $header) {
            $sheet->setCellValue($columns[$index] . '2', $header);
        }

        // ✅ تنسيق الهيدر (تلوين الخلفية والخط)
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // لون النص (أبيض)
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '808080'], // لون الخلفية (رمادي)
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ];
        $sheet->getStyle("A2:K2")->applyFromArray($headerStyle);

        // ✅ ضبط عرض الأعمدة تلقائيًا
        $columnWidths = [10, 15, 20, 15, 20, 15, 20, 20, 20, 20, 20];
        foreach ($columns as $index => $col) {
            $sheet->getColumnDimension($col)->setWidth($columnWidths[$index]);
        }

        // ✅ إدراج البيانات
        $count = 1;
        $rows = 3;
        foreach ($excelData as $val) {
            $sheet->setCellValue("A$rows", $count++)
                ->setCellValue("B$rows", $val['EMP_NO'])
                ->setCellValue("C$rows", $val['EMP_NO_NAME'])
                ->setCellValue("D$rows", $val['MONTH'])
                ->setCellValue("E$rows", $val['EMP_TYPE_NAME'])
                ->setCellValue("F$rows", $val['BRANCH_NAME'])
                ->setCellValue("G$rows", $val['DEPARTMENT_NAME'])
                ->setCellValue("H$rows", $val['BANK_NAME'])
                ->setCellValue("I$rows", $val['ACCOUNT'])
                ->setCellValue("J$rows", $val['W_NO_NAME'])
                ->setCellValue("K$rows", number_format($val['NET_SALARY'], 2, '.', ''));
            $rows++;
        }

        // ✅ التأكد من عدم وجود أي مخرجات قبل الإرسال
        if (ob_get_length()) {
            ob_end_clean();
        }

        // ✅ إعدادات الملف والتحميل
        $object_writer = new Xlsx($spreadsheet);
        $filename = "تقرير رواتب شهر " . ($this->q_month ?? "غير محدد") . " - $currentDateTime.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        set_time_limit(500);
        ini_set('memory_limit', '-1');

        $object_writer->save('php://output');
        exit;
    }





    /**
     * ✅ جلب قائمة الموظفين الفعّالين
     */
    private function _get_employee_list($emp_no)
    {
        if ($emp_no === "ALL") {
            $active_employees = $this->rmodel->getAll('SALARYFORM', 'GET_ALL_ACTIVE_EMPLOYEES');
            $emp_no_list = array_map(function ($row) {
                return intval($row['EMP_NO']);
            }, $active_employees);

            if (empty($emp_no_list)) {
                echo json_encode(['status' => 'error', 'message' => '⚠️ لم يتم العثور على موظفين فعّالين.']);
                return false;
            }
            return $emp_no_list;
        }

        return is_array($emp_no) ? array_map('intval', $emp_no) : [intval($emp_no)];
    }


    function get($emp_no, $month)
    {
        $result = $this->rmodel->getTwoColum('SALARYFORM', 'DATA_ADMIN_GET', $emp_no,$month);
        if (!(count($result) == 1))
            die('get');

        $additions= $this->rmodel->getThreeColum('DATA_SALARY_GET', $emp_no, $month, 1);
        $discounts= $this->rmodel->getThreeColum('DATA_SALARY_GET', $emp_no, $month, 0);

        $data['master_tb_data'] = $result;
        $data['additions_data'] = $additions;
        $data['discounts_data'] = $discounts;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content'] = 'salary_calculation_show';
        $data['title'] = 'بيانات الراتب ';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    public function get_next_prev_employee()
    {
        $emp_no = $this->input->post('emp_no');
        $month = $this->input->post('month');
        $direction = $this->input->post('direction');

        if (!$emp_no || !$month || !$direction) {
            echo json_encode(['status' => 'error', 'message' => 'بيانات مفقودة']);
            exit;
        }

        // جلب جميع الموظفين النشطين
        $active_employees = $this->rmodel->getAll('SALARYFORM', 'GET_ALL_ACTIVE_EMPLOYEES');
        $emp_list = array_column($active_employees, 'EMP_NO');

        // البحث عن الموظف الحالي
        $current_index = array_search($emp_no, $emp_list);
        if ($current_index === false) {
            echo json_encode(['status' => 'error', 'message' => 'الموظف غير موجود']);
            exit;
        }

        // تحديد الموظف التالي أو السابق
        if ($direction === 'next' && isset($emp_list[$current_index + 1])) {
            $new_emp_no = $emp_list[$current_index + 1];
        } elseif ($direction === 'prev' && isset($emp_list[$current_index - 1])) {
            $new_emp_no = $emp_list[$current_index - 1];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'لا يوجد بيانات']);
            exit;
        }

        // جلب بيانات الموظف الجديد
        $result = $this->rmodel->getTwoColum('SALARYFORM', 'DATA_ADMIN_GET', $new_emp_no, $month);
        if (count($result) !== 1) {
            echo json_encode(['status' => 'error', 'message' => 'لم يتم العثور على بيانات الراتب']);
            exit;
        }

        $additions = $this->rmodel->getThreeColum('DATA_SALARY_GET', $new_emp_no, $month, 1);
        $discounts = $this->rmodel->getThreeColum('DATA_SALARY_GET', $new_emp_no, $month, 0);

        echo json_encode([
            'status' => 'success',
            'salary_data' => $result[0],
            'additions' => $additions,
            'discounts' => $discounts,
            'current_index' => $current_index + 1,
            'remaining_forms' => count($emp_list) - ($current_index + 1),
            'is_first' => ($current_index === 0),
            'is_last' => ($current_index === count($emp_list) - 1),
        ]);
        exit;
    }


    public function confirm_salaries()
    {
        $from_month = $this->input->post('from_month');
        $msg_out = '';

        if (!$from_month || !is_numeric($from_month)) {
            echo json_encode(['status' => 'error', 'message' => '⚠️ يجب إدخال شهر صالح.']);
            return;
        }

        $params = [
            ['name' => ':THE_MONTH_IN', 'value' => $from_month, 'type' => SQLT_INT, 'length' => -1],
            ['name' => ':MSG_OUT', 'value' => &$msg_out, 'type' => SQLT_CHR, 'length' => 10] // تعديل الحجم إلى 10
        ];

        $result = $this->{$this->MODEL_NAME}->executeProcedure('SALARYFORM', 'TRANS_SALARY_ALL', $params);

        if ($result !== false && $msg_out == '1') {
            echo json_encode(['status' => 'success', 'message' => '✅ تم ترحيل الرواتب بنجاح!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => "⚠️ فشل ترحيل الرواتب!"]);
        }
    }



    function _look_ups(&$data)
    {

        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('salary/constants_sal_model');


        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['status_cons'] = $this->constants_sal_model->get_list(1);
        $data['q_no_cons'] = $this->constants_sal_model->get_list(8);
        $data['degree_cons'] = $this->constants_sal_model->get_list(11);
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);
        $data['bank_cons'] = $this->constants_sal_model->get_list(17);
        $data['department_cons'] = $this->constants_sal_model->get_list(7);
        $data['branch_cons'] = $this->constants_sal_model->get_list(4);
        $data['bran_cons'] = $this->constants_sal_model->get_list(5);
        $data['sal_con_cons'] = $this->constants_sal_model->get_list(25);


        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');


    }
}
?>