<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dues extends MY_Controller
{
    var $PKG_NAME   = "SALARY_DUES_PKG";
    var $MODEL_NAME = "Dues_model";
    var $PAGE_URL   = "payroll_data/dues/get_page";

    function __construct()
    {
        parent::__construct();

        $this->load->model($this->MODEL_NAME);

        $this->load->model('root/rmodel');
        $this->rmodel->package = $this->PKG_NAME;

        $this->load->model('settings/gcc_branches_model');
        $this->load->model('payroll_data/salary_dues_types_model');

        // قيم POST الخام (لو احتجتها بفلاتر)
        $this->serial    = $this->input->post('serial');
        $this->emp_no    = $this->input->post('emp_no');
        $this->the_month = $this->input->post('the_month');
        $this->pay_type  = $this->input->post('pay_type');
        $this->pay       = $this->input->post('pay');
        $this->note      = $this->input->post('note');

        // فلتر
        $this->branch_no = $this->input->post('branch_no');
    }

    function index($page = 1, $branch_no = -1, $the_month = -1, $emp_no = -1, $pay_type = -1)
    {
        $data['title']   = 'تسديد مستحقات الموظفين';
        $data['content'] = 'dues_index';
        $data['page']    = $page;

        $data['branch_no'] = $branch_no;
        $data['the_month'] = $the_month;
        $data['emp_no']    = $emp_no;
        $data['pay_type']  = $pay_type;

        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1, $branch_no = -1, $the_month = -1, $emp_no = -1, $pay_type = -1)
    {
        $this->load->library('pagination');

        $branch_no = $this->check_vars($branch_no, 'branch_no');
        $emp_no    = $this->check_vars($emp_no, 'emp_no');
        $the_month = $this->check_vars($the_month, 'the_month');
        $pay_type  = $this->check_vars($pay_type, 'pay_type');

        $where_sql = ' where 1=1 ';

        // فلترة الفرع (حسب user branch)
        if ($this->user->branch == 1) {
            $where_sql .= ($branch_no != null) ? " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$branch_no}' " : '';
        } else {
            $where_sql .= " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$this->user->branch}' ";
        }

        $where_sql .= ($emp_no != null)    ? " and M.EMP_NO= '{$emp_no}' "       : '';
        $where_sql .= ($the_month != null) ? " and M.THE_MONTH= '{$the_month}' " : '';
        $where_sql .= ($pay_type != null)  ? " and M.PAY_TYPE= '{$pay_type}' "   : '';

        $config['base_url'] = base_url($this->PAGE_URL);

        // count
        $count_rs = $this->get_table_count(" SALARY_DUES_TB M " . $where_sql);

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page']   = $this->page_size;
        $config['num_links']  = 20;
        $config['cur_page']   = $page;

        $config['full_tag_open']  = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close']= $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close']= $config['num_tag_close'] = '</li>';
        $config['cur_tag_open']   = '<li class="active"><span><b>';
        $config['cur_tag_close']  = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row    = (($page * $config['per_page']));

        $data["page_rows"] = $this->rmodel->getList('SALARY_DUES_TB_LIST', $where_sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page']   = $page;

        $this->load->view('dues_page', $data);
    }

    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(false);

            $res = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if (is_numeric($res) && intval($res) > 0) {
                echo intval($res);
            } else {
                $this->print_error($res);
            }
        } else {
            $data['title']    = 'اضافة دفعة مستحق';
            $data['isCreate'] = true;
            $data['action']   = 'index';
            $data['content']  = 'dues_show';

            $this->_lookup($data);
            $this->load->view('template/template1', $data);
        }
    }

    function get($id)
    {
        $result = $this->rmodel->get('SALARY_DUES_TB_GET', $id);
        if (!(count($result) == 1)) die('get');

        $data['master_tb_data'] = $result;
        $data['isCreate']       = false;
        $data['can_edit']       = 1;
        $data['action']         = 'edit';
        $data['content']        = 'dues_show';
        $data['title']          = 'تفاصيل الدفعة';

        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(true);

            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());

            if (is_numeric($res) && intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error($res);
            }
        }
    }

    public function delete()
    {
        // دعم serial سواء جاء كـ p_serial (framework) أو serial POST
        $serial = $this->p_serial ? $this->p_serial : $this->input->post('serial');
        $result = $this->rmodel->delete('SALARY_DUES_TB_DELETE', $serial);
        echo $result;
    }

    /**************** helpers ****************/

    function check_vars($var, $c_var)
    {
        $var = ($this->{$c_var}) ? $this->{$c_var} : $var;
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function _post_validation($isEdit = false)
    {
        if ($isEdit && $this->p_serial == '') {
            $this->print_error('SERIAL مطلوب للتعديل');
        }

        if ($this->p_emp_no == '') {
            $this->print_error('يجب ادخال رقم الموظف');
        }

        // الشهر صار اختياري: لو موجود لازم يكون YYYYMM
        if ($this->p_the_month != '' && (strlen($this->p_the_month) != 6 || !ctype_digit($this->p_the_month))) {
            $this->print_error('يجب ادخال الشهر بصيغة YYYYMM مثل 202501 أو تركه فارغ');
        }

        if ($this->p_pay_type == '') {
            $this->print_error('يجب اختيار نوع الدفع');
        }

        if ($this->p_pay == '' || floatval($this->p_pay) <= 0) {
            $this->print_error('يجب ادخال مبلغ صحيح');
        }
    }

    function _postedData($typ = null)
    {
        // لو الشهر فاضي: الشهر الحالي YYYYMM
        $the_month_val = ($this->p_the_month != '' ? $this->p_the_month : date('Ym'));

        $result = array(
            array('name' => 'SERIAL',    'value' => $this->p_serial,   'type' => '', 'length' => -1),
            array('name' => 'EMP_NO',    'value' => $this->p_emp_no,   'type' => '', 'length' => -1),
            array('name' => 'THE_MONTH', 'value' => $the_month_val,    'type' => '', 'length' => -1),
            array('name' => 'PAY_TYPE',  'value' => $this->p_pay_type, 'type' => '', 'length' => -1),
            array('name' => 'PAY',       'value' => $this->p_pay,      'type' => '', 'length' => -1),
            array('name' => 'NOTE',      'value' => $this->p_note,     'type' => '', 'length' => -1),
        );

        if ($typ == 'create') {
            // insert ما فيه SERIAL
            unset($result[0]);
        }

        return $result;
    }

    /**
     * AJAX: يرجع ملخص مستحقات/مسدد/متبقي للموظف
     * يقبل to_month أو the_month
     * لو الشهر فاضي -> NULL (البروسيجر عندك default NULL)
     */
    public function public_get_summary()
    {
        $emp_no = trim((string)$this->input->post('emp_no'));

        $to_month = $this->input->post('to_month');
        if ($to_month === null) {
            $to_month = $this->input->post('the_month');
        }
        $to_month = trim((string)$to_month);
        if ($to_month === '') {
            $to_month = null;
        }

        if ($emp_no === '' || !ctype_digit($emp_no) || intval($emp_no) <= 0) {
            echo json_encode(['ok' => false, 'msg' => 'Invalid employee']);
            return;
        }


        if ($to_month !== null && (!ctype_digit($to_month) || strlen($to_month) != 6)) {
            echo json_encode(['ok' => false, 'msg' => 'Invalid month']);
            return;
        }

        $summary = $this->{$this->MODEL_NAME}->get_summary((int)$emp_no, ($to_month === null ? null : (int)$to_month));
        echo json_encode(['ok' => true, 'data' => $summary]);
    }


    public function import_excel_smart()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['ok' => false, 'msg' => 'Invalid request']);
            return;
        }

        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] != 0) {
            echo json_encode(['ok' => false, 'msg' => 'يجب اختيار ملف Excel']);
            return;
        }

        // upload temp
        $config['upload_path']   = FCPATH . 'uploads/tmp/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size']      = 10240;
        $config['encrypt_name']  = true;

        if (!is_dir($config['upload_path'])) {
            @mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('excel_file')) {
            echo json_encode(['ok' => false, 'msg' => $this->upload->display_errors('', '')]);
            return;
        }

        $uploadData = $this->upload->data();
        $fullPath   = $uploadData['full_path'];

        // read file
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fullPath);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            @unlink($fullPath);
            echo json_encode(['ok' => false, 'msg' => 'فشل قراءة ملف Excel: ' . $e->getMessage()]);
            return;
        }
        @unlink($fullPath);

        if (!is_array($rows) || count($rows) < 2) {
            echo json_encode(['ok' => false, 'msg' => 'الملف فارغ أو لا يحتوي بيانات كافية']);
            return;
        }

        // detect header
        $header = $rows[1];
        $hA = strtoupper(trim((string)($header['A'] ?? '')));
        $hC = strtoupper(trim((string)($header['C'] ?? '')));
        $hD = strtoupper(trim((string)($header['D'] ?? '')));
        $hasHeader = ($hA == 'EMP_NO' && $hC == 'PAY_TYPE' && $hD == 'PAY');

        // فحص الحد الأقصى للسجلات (2000 سجل)
        $maxRows = 2000;
        $totalRows = count($rows) - ($hasHeader ? 1 : 0);
        if ($totalRows > $maxRows) {
            echo json_encode(['ok' => false, 'msg' => "الملف يحتوي على {$totalRows} سجل. الحد الأقصى المسموح هو {$maxRows} سجل"]);
            return;
        }

        $startRow = $hasHeader ? 2 : 1;
        $defaultMonth = (int)date('Ym');

        // جلب قائمة أنواع الدفع من شجرة البنود (فقط الأوراق Leaf)
        $tree_flat = $this->salary_dues_types_model->getTreeList(1);
        $validPayTypes = [];
        if (is_array($tree_flat)) {
            foreach ($tree_flat as $pt) {
                // IS_LEAF = 1 يعني بند فرعي قابل للاختيار
                if (isset($pt['IS_LEAF']) && $pt['IS_LEAF'] == 1) {
                    $validPayTypes[(int)$pt['TYPE_ID']] = $pt['TYPE_NAME'];
                }
            }
        }

        // جلب قائمة الموظفين المتاحين للتحقق
        $this->load->model('hr_attendance/hr_attendance_model');
        $emp_no_cons = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $validEmpNos = [];
        foreach ($emp_no_cons as $emp) {
            $validEmpNos[(int)$emp['EMP_NO']] = $emp['EMP_NAME'];
        }

        // 1) parse + normalize into items
        $items = [];     // raw parsed rows
        $parseErrors = [];
        $invalidEmps = [];  // الموظفون غير الموجودين
        $invalidPayTypes = []; // أنواع الدفع غير الصحيحة

        for ($i = $startRow; $i <= count($rows); $i++) {
            $r = $rows[$i];
            if (!is_array($r)) continue;

            $empNo    = trim((string)($r['A'] ?? ''));
            $theMonth = trim((string)($r['B'] ?? ''));
            $payType  = trim((string)($r['C'] ?? ''));
            $pay      = trim((string)($r['D'] ?? ''));
            $note     = trim((string)($r['E'] ?? ''));

            // skip empty
            if ($empNo === '' && $payType === '' && $pay === '') continue;

            // validate basics
            if ($empNo === '') {
                $parseErrors[] = "Row {$i}: رقم الموظف فارغ";
                continue;
            }

            if (!ctype_digit($empNo) || (int)$empNo <= 0) {
                $parseErrors[] = "Row {$i}: رقم الموظف '{$empNo}' غير صحيح (يجب أن يكون رقماً صحيحاً)";
                continue;
            }

            // التحقق من طول رقم الموظف (من رقمين إلى 4 أرقام)
            $empNoLength = strlen($empNo);
            if ($empNoLength < 2 || $empNoLength > 4) {
                $parseErrors[] = "Row {$i}: رقم الموظف '{$empNo}' غير صحيح (يجب أن يكون من رقمين إلى 4 أرقام)";
                continue;
            }

            $empNoInt = (int)$empNo;
            if (!isset($validEmpNos[$empNoInt])) {
                if (!isset($invalidEmps[$empNoInt])) {
                    $invalidEmps[$empNoInt] = [];
                }
                $invalidEmps[$empNoInt][] = $i;
                continue;
            }

            if ($theMonth === '') {
                $theMonth = $defaultMonth;
            } else {
                $theMonth = preg_replace('/[^0-9]/', '', $theMonth);
                if (strlen($theMonth) != 6) {
                    $parseErrors[] = "Row {$i}: الشهر '{$r['B']}' غير صحيح (يجب أن يكون YYYYMM مثل 202501)";
                    continue;
                }
                $theMonth = (int)$theMonth;
                
                // التحقق من أن الشهر ليس في المستقبل البعيد (أكثر من سنة)
                $currentYearMonth = (int)date('Ym');
                $maxFutureMonth = (int)date('Ym', strtotime('+12 months'));
                if ($theMonth > $maxFutureMonth) {
                    $parseErrors[] = "Row {$i}: الشهر '{$theMonth}' في المستقبل البعيد (أكثر من سنة)";
                    continue;
                }
            }

            if ($payType === '') {
                $parseErrors[] = "Row {$i}: نوع الدفع فارغ";
                continue;
            }

            if (!ctype_digit($payType) || (int)$payType <= 0) {
                $parseErrors[] = "Row {$i}: نوع الدفع '{$payType}' غير صحيح (يجب أن يكون رقماً)";
                continue;
            }

            $payTypeInt = (int)$payType;
            if (!isset($validPayTypes[$payTypeInt])) {
                if (!isset($invalidPayTypes[$payTypeInt])) {
                    $invalidPayTypes[$payTypeInt] = [];
                }
                $invalidPayTypes[$payTypeInt][] = $i;
                continue;
            }

            if ($pay === '') {
                $parseErrors[] = "Row {$i}: المبلغ فارغ";
                continue;
            }

            $payNum = (float)$pay;
            if ($payNum <= 0) {
                $parseErrors[] = "Row {$i}: المبلغ '{$pay}' غير صحيح (يجب أن يكون أكبر من صفر)";
                continue;
            }

            $items[] = [
                'row' => $i,
                'EMP_NO' => $empNoInt,
                'THE_MONTH' => $theMonth,
                'PAY_TYPE' => $payTypeInt,
                'PAY' => $payNum,
                'NOTE' => $note,
            ];
        }

        // إضافة أخطاء الموظفين غير الموجودين
        foreach ($invalidEmps as $empNo => $rows) {
            $parseErrors[] = "الموظف رقم {$empNo} غير موجود في النظام (الصفوف: " . implode(', ', $rows) . ")";
        }

        // إضافة أخطاء أنواع الدفع غير الصحيحة
        foreach ($invalidPayTypes as $payType => $rows) {
            $parseErrors[] = "نوع الدفع رقم '{$payType}' غير موجود في قائمة أنواع الدفع المعتمدة. راجع sheet 'أنواع الدفع' في قالب Excel (الصفوف: " . implode(', ', $rows) . ")";
        }

        if (count($items) == 0) {
            echo json_encode(['ok' => false, 'msg' => 'لا يوجد بيانات صالحة للاستيراد', 'errors' => $parseErrors]);
            return;
        }

        /**
         * 2) SMART GROUPING
         * - نجمع المكرر: نفس EMP_NO + THE_MONTH + PAY_TYPE => نجمع PAY
         * - وبنفس الوقت نحسب إجمالي كل موظف (سلة واحدة)
         */
        $grouped = [];         // key: emp|month|type
        $groupedDetails = [];  // key: emp|month|type => array of original rows that were grouped
        $empTotals = [];       // key: emp => sum pay in file
        $empRowsMap = [];      // emp => rows list for reporting

        foreach ($items as $it) {
            $emp = $it['EMP_NO'];
            $k = $it['EMP_NO'] . '|' . $it['THE_MONTH'] . '|' . $it['PAY_TYPE'];

            if (!isset($grouped[$k])) {
                $grouped[$k] = $it;
                $groupedDetails[$k] = [
                    'original_rows' => [$it['row']],
                    'original_count' => 1,
                    'total_pay' => $it['PAY']
                ];
            } else {
                $grouped[$k]['PAY'] += $it['PAY'];
                $groupedDetails[$k]['original_rows'][] = $it['row'];
                $groupedDetails[$k]['original_count']++;
                $groupedDetails[$k]['total_pay'] = $grouped[$k]['PAY'];
                // ملاحظات: اترك أول NOTE أو ادمج بشكل بسيط
                if ($it['NOTE'] != '' && $grouped[$k]['NOTE'] == '') $grouped[$k]['NOTE'] = $it['NOTE'];
            }

            if (!isset($empTotals[$emp])) $empTotals[$emp] = 0;
            $empTotals[$emp] += $it['PAY'];

            if (!isset($empRowsMap[$emp])) $empRowsMap[$emp] = [];
            $empRowsMap[$emp][] = $it['row'];
        }

        $empNos = array_keys($empTotals);

        $balances = []; // emp => balance
        foreach ($empNos as $emp) {
            // لازم تعمل دالة بالموديل ترجع balance رقم
            // مثال: $this->Dues_model->get_emp_balance($emp)
            $bal = $this->{$this->MODEL_NAME}->get_emp_balance($emp);
            $balances[$emp] = (float)$bal;
        }

        /**
         * 4) Decision: الموظف اللي مجموع الملف تبعه > balance => كله FAIL
         * اللي <= => OK
         */
        $failedEmps = [];
        $okEmps = [];

        foreach ($empTotals as $emp => $sumPay) {
            $bal = isset($balances[$emp]) ? $balances[$emp] : 0;
            if ($sumPay > $bal) {
                $failedEmps[$emp] = [
                    'EMP_NO' => $emp,
                    'FILE_TOTAL' => $sumPay,
                    'BALANCE' => $bal,
                    'ROWS' => isset($empRowsMap[$emp]) ? $empRowsMap[$emp] : [],
                    'REASON' => 'المجموع يتجاوز المتبقي'
                ];
            } else {
                $okEmps[] = $emp;
            }
        }

        /**
         * 5) INSERT فقط للموظفين OK
         * من grouped data (عشان ما ندخل duplicates)
         */
        $inserted = 0;
        $insertedRecords = [];  // السجلات التي تم إدخالها
        $groupedRecords = [];   // السجلات التي تم تجميعها
        $insertErrors = [];

        foreach ($grouped as $k => $row) {
            $emp = $row['EMP_NO'];
            if (!in_array($emp, $okEmps)) continue;

            $payload = $this->_importPayloadToCreate($row);

            $res = $this->{$this->MODEL_NAME}->create($payload);

            if (is_numeric($res) && (int)$res > 0) {
                $inserted++;
                $insertedRecords[] = [
                    'EMP_NO' => $emp,
                    'THE_MONTH' => $row['THE_MONTH'],
                    'PAY_TYPE' => $row['PAY_TYPE'],
                    'PAY' => $row['PAY'],
                    'SERIAL' => (int)$res
                ];

                // إذا تم تجميع أكثر من سجل واحد
                if (isset($groupedDetails[$k]) && $groupedDetails[$k]['original_count'] > 1) {
                    $groupedRecords[] = [
                        'EMP_NO' => $emp,
                        'THE_MONTH' => $row['THE_MONTH'],
                        'PAY_TYPE' => $row['PAY_TYPE'],
                        'ORIGINAL_ROWS' => $groupedDetails[$k]['original_rows'],
                        'ORIGINAL_COUNT' => $groupedDetails[$k]['original_count'],
                        'TOTAL_PAY' => $groupedDetails[$k]['total_pay'],
                        'SERIAL' => (int)$res
                    ];
                }
            } else {
                // هذا خطأ أثناء الإدخال (غير خطأ balance)
                $insertErrors[] = [
                    'EMP_NO' => $emp,
                    'KEY' => $k,
                    'MSG' => is_string($res) ? mb_substr($res, 0, 200) : 'Unknown error'
                ];
            }
        }

        echo json_encode([
            'ok' => true,
            'inserted' => $inserted,
            'total_rows_in_file' => count($items),
            'grouped_count' => count($groupedRecords),
            'inserted_records' => $insertedRecords,
            'grouped_records' => $groupedRecords,
            'failed_employees' => array_values($failedEmps),
            'parse_errors' => $parseErrors,
            'insert_errors' => $insertErrors
        ]);
    }


    /**
     * تحويل بيانات الاستيراد إلى الصيغة التي يتوقعها الموديل (_extract_data)
     */
    private function _importPayloadToCreate($row)
    {
        return array(
            array('name' => 'EMP_NO',    'value' => $row['EMP_NO'],    'type' => '', 'length' => -1),
            array('name' => 'THE_MONTH', 'value' => $row['THE_MONTH'], 'type' => '', 'length' => -1),
            array('name' => 'PAY_TYPE',  'value' => $row['PAY_TYPE'],  'type' => '', 'length' => -1),
            array('name' => 'PAY',       'value' => $row['PAY'],       'type' => '', 'length' => -1),
            array('name' => 'NOTE',      'value' => $row['NOTE'] ?? '', 'type' => '', 'length' => -1),
        );
    }

    /**
     * تنزيل قالب Excel لاستيراد المستحقات
     */
    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        
        // Sheet 1: البيانات
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('استيراد المستحقات');

        $headers = ['EMP_NO', 'THE_MONTH', 'PAY_TYPE', 'PAY', 'NOTE'];
        $examples = [12345, date('Ym'), 1, 500.00, 'دفعة شهرية'];

        $sheet->setCellValue('A1', $headers[0]);
        $sheet->setCellValue('B1', $headers[1]);
        $sheet->setCellValue('C1', $headers[2]);
        $sheet->setCellValue('D1', $headers[3]);
        $sheet->setCellValue('E1', $headers[4]);

        $sheet->setCellValue('A2', $examples[0]);
        $sheet->setCellValue('B2', $examples[1]);
        $sheet->setCellValue('C2', $examples[2]);
        $sheet->setCellValue('D2', $examples[3]);
        $sheet->setCellValue('E2', $examples[4]);

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        // Sheet 2: أنواع الدفع
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('أنواع الدفع');
        $sheet2->setCellValue('A1', 'الكود');
        $sheet2->setCellValue('B1', 'نوع الدفع');
        $sheet2->setCellValue('C1', 'النوع');
        $sheet2->getStyle('A1:C1')->getFont()->setBold(true);
        
        $tree_flat = $this->salary_dues_types_model->getTreeList(1);
        
        $row = 2;
        if (is_array($tree_flat)) {
            foreach ($tree_flat as $pt) {
                if (isset($pt['IS_LEAF']) && $pt['IS_LEAF'] == 1) {
                    $sheet2->setCellValue('A' . $row, $pt['TYPE_ID']);
                    $sheet2->setCellValue('B' . $row, $pt['TYPE_NAME']);
                    $sheet2->setCellValue('C' . $row, ($pt['LINE_TYPE'] == 1 ? 'إضافة' : 'خصم'));
                    $row++;
                }
            }
        }
        
        $sheet2->getColumnDimension('A')->setAutoSize(true);
        $sheet2->getColumnDimension('B')->setAutoSize(true);
        $sheet2->getColumnDimension('C')->setAutoSize(true);
        
        // إرجاع للـ sheet الأول
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'قالب_استيراد_المستحقات_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    function _lookup(&$data)
    {
        add_css('combotree.css');

        $data['branches'] = $this->gcc_branches_model->get_all();

        // URL شجرة أنواع الدفع (combotree JSON)
        $data['pay_type_tree_url'] = base_url('payroll_data/salary_dues_types/public_get_tree_json');

        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
    }
}
