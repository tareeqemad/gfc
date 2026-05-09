<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Payment_accounts extends MY_Controller
{
    var $PKG_NAME   = "GFC_PAK.PAYMENT_ACCOUNTS_PKG";
    var $MODEL_NAME = "Payment_accounts_model";
    var $PAGE_URL   = "payment_accounts/payment_accounts/get_page";

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/gcc_branches_model');

        // URLs
        $this->emp_url             = base_url('payment_accounts/payment_accounts/emp');
        $this->account_save_url    = base_url('payment_accounts/payment_accounts/account_save');
        $this->account_del_url     = base_url('payment_accounts/payment_accounts/account_delete');
        $this->benef_save_url      = base_url('payment_accounts/payment_accounts/benef_save');
        $this->benef_del_url       = base_url('payment_accounts/payment_accounts/benef_delete');
        $this->provider_save_url   = base_url('payment_accounts/payment_accounts/provider_save');
        $this->branch_save_url     = base_url('payment_accounts/payment_accounts/branch_save');
    }

    // ==================== INDEX ====================
    function index($page = 1, $branch_no = -1, $emp_no = -1, $is_active = -1, $has_acc = -1, $has_benef = -1, $the_month = -1)
    {
        $data['title']     = 'إدارة حسابات الصرف';
        $data['content']   = 'payment_accounts_index';
        $data['page']      = $page;
        $data['branch_no'] = $branch_no;
        $data['emp_no']    = $emp_no;
        $data['is_active'] = $is_active;
        $data['has_acc']   = $has_acc;
        $data['has_benef'] = $has_benef;
        $data['the_month'] = $the_month;
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    // ==================== PAGINATED LIST ====================
    function get_page($page = 1, $branch_no = -1, $emp_no = -1, $is_active = -1, $has_acc = -1, $has_benef = -1, $the_month = -1)
    {
        $this->load->library('pagination');

        $branch_no = $this->check_vars($branch_no, 'branch_no');
        $emp_no    = $this->check_vars($emp_no,    'emp_no');
        $is_active = $this->check_vars($is_active, 'is_active');
        $has_acc   = $this->check_vars($has_acc,   'has_acc');
        $has_benef = $this->check_vars($has_benef, 'has_benef');
        $the_month = $this->check_vars($the_month, 'the_month');

        if ($this->user->branch != 1) {
            $branch_no = $this->user->branch;
        }

        $filters = [
            'emp_no'    => ($emp_no    !== null && $emp_no    !== '') ? intval($emp_no)    : null,
            'branch_no' => ($branch_no !== null && $branch_no !== '') ? intval($branch_no) : null,
            'is_active' => ($is_active !== null && $is_active !== '') ? intval($is_active) : null,
            'has_acc'   => ($has_acc   !== null && $has_acc   !== '') ? intval($has_acc)   : null,
            'has_benef' => ($has_benef !== null && $has_benef !== '') ? intval($has_benef) : null,
            'the_month' => ($the_month !== null && $the_month !== '') ? intval($the_month) : null,
        ];

        $total_rows = $this->{$this->MODEL_NAME}->employees_count($filters);
        $totals     = $this->{$this->MODEL_NAME}->employees_totals($filters);

        $config['base_url']         = base_url($this->PAGE_URL);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows']       = $total_rows;
        $config['per_page']         = $this->page_size;
        $config['num_links']        = 20;
        $config['cur_page']         = $page;
        $config['full_tag_open']    = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close']   = '</ul></div>';
        $config['first_tag_open']   = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close']  = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open']     = '<li class="active"><span><b>';
        $config['cur_tag_close']    = '</b></span></li>';

        $this->pagination->initialize($config);

        $offset = ($page - 1) * $config['per_page'];
        $rows   = $this->{$this->MODEL_NAME}->employees_list($filters, $offset, $config['per_page']);

        $data['page_rows']  = is_array($rows) ? $rows : [];
        $data['total_rows'] = $total_rows;
        $data['page']       = $page;
        $data['offset']     = $offset;
        $data['totals']     = $totals;
        $data['the_month']  = $filters['the_month']; // للعرض في الـ header (لو موجود)

        $this->load->view('payment_accounts_page', $data);
    }

    function check_vars($var, $c_var)
    {
        $p_var = $this->{'p_'.$c_var} ?? null;
        $var = ($p_var !== null && $p_var !== '') ? $p_var : $var;
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function _lookup(&$data)
    {
        $data['branches'] = $this->gcc_branches_model->get_all();

        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
    }

    // ==================== EXPORT EXCEL ====================
    function export_excel()
    {
        $branch_no = $this->check_vars(-1, 'branch_no');
        $emp_no    = $this->check_vars(-1, 'emp_no');
        $is_active = $this->check_vars(-1, 'is_active');
        $has_acc   = $this->check_vars(-1, 'has_acc');
        $has_benef = $this->check_vars(-1, 'has_benef');
        $the_month = $this->check_vars(-1, 'the_month');

        if ($this->user->branch != 1) {
            $branch_no = $this->user->branch;
        }

        $filters = [
            'emp_no'    => ($emp_no    !== null && $emp_no    !== '') ? intval($emp_no)    : null,
            'branch_no' => ($branch_no !== null && $branch_no !== '') ? intval($branch_no) : null,
            'is_active' => ($is_active !== null && $is_active !== '') ? intval($is_active) : null,
            'has_acc'   => ($has_acc   !== null && $has_acc   !== '') ? intval($has_acc)   : null,
            'has_benef' => ($has_benef !== null && $has_benef !== '') ? intval($has_benef) : null,
            'the_month' => ($the_month !== null && $the_month !== '') ? intval($the_month) : null,
        ];

        $rows = $this->{$this->MODEL_NAME}->employees_list($filters, 0, 999999);

        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات للتصدير"); history.back();</script>'; return;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('حسابات الصرف');
        $sheet->setRightToLeft(true);

        $headers = ['م', 'رقم الموظف', 'اسم الموظف', 'رقم الهوية', 'المقر', 'الحالة', 'النوع', 'البنك / المحفظة', 'رقم الحساب', 'IBAN', 'صاحب الحساب', 'حسابات إضافية', 'مستفيدون'];
        $lastCol = chr(64 + count($headers)); // M
        $col = 'A';
        foreach ($headers as $h) { $sheet->setCellValue($col . '1', $h); $col++; }

        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:{$lastCol}1")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $rowNum = 2; $count = 1;
        foreach ($rows as $row) {
            $emp_no_v   = $row['EMP_NO'] ?? '';
            $emp_name   = $row['EMP_NAME'] ?? '';
            $id_no_v    = $row['ID_NO'] ?? '';
            $branch     = $row['BRANCH_NAME'] ?? '';
            $is_act     = (int)($row['IS_ACTIVE'] ?? 0);
            $has_dead   = (int)($row['HAS_DECEASED'] ?? 0);
            $has_frozen = (int)($row['HAS_FROZEN'] ?? 0);
            $active_cnt = (int)($row['ACTIVE_COUNT'] ?? 0);
            $benef_cnt  = (int)($row['BENEF_COUNT'] ?? 0);
            $def_prov   = $row['DEF_PROVIDER_NAME'] ?? '';
            $def_type   = (int)($row['DEF_PROVIDER_TYPE'] ?? 0);
            $def_acc    = $row['DEF_ACCOUNT_NO'] ?? '';
            $def_iban   = $row['DEF_IBAN'] ?? '';
            $def_owner  = $row['DEF_OWNER_NAME'] ?? '';
            $more_cnt   = max(0, $active_cnt - 1);

            if ($active_cnt == 0) {
                $type_label = '';
                $prov_label = '— لا يوجد حساب نشط —';
            } elseif (!$def_prov) {
                $type_label = '';
                $prov_label = '— بيانات غير مكتملة —';
            } else {
                $type_label = ($def_type == 1) ? 'بنك' : 'محفظة';
                $prov_label = $def_prov;
            }

            // الحالة: لو شهر مُحدّد → الحالة التاريخية فقط (IS_ACTIVE من EMPLOYEES_MONTH عبر الـ procedure)
            //          لا → الحالة الحالية: متوفى > حساب مغلق > فعّال/متقاعد
            $is_historical_xl = !empty($filters['the_month']);
            if ($is_historical_xl) {
                $status_label = $is_act == 1 ? 'فعّال' : 'متقاعد';
            } elseif ($has_dead) {
                $status_label = 'متوفى';
            } elseif ($has_frozen) {
                $status_label = 'حساب مغلق';
            } else {
                $status_label = $is_act == 1 ? 'فعّال' : 'متقاعد';
            }

            $sheet->setCellValue('A' . $rowNum, $count);
            $sheet->setCellValue('B' . $rowNum, $emp_no_v);
            $sheet->setCellValue('C' . $rowNum, $emp_name);
            $sheet->setCellValueExplicit('D' . $rowNum, (string)$id_no_v, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowNum, $branch);
            $sheet->setCellValue('F' . $rowNum, $status_label);
            $sheet->setCellValue('G' . $rowNum, $type_label);
            $sheet->setCellValue('H' . $rowNum, $prov_label);
            $sheet->setCellValueExplicit('I' . $rowNum, (string)$def_acc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('J' . $rowNum, (string)($def_type == 1 ? $def_iban : ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('K' . $rowNum, ($def_owner && $def_owner != $emp_name) ? $def_owner : '');
            $sheet->setCellValue('L' . $rowNum, $more_cnt > 0 ? $more_cnt : '');
            $sheet->setCellValue('M' . $rowNum, $benef_cnt > 0 ? $benef_cnt : '');

            if ($is_historical_xl) {
                if ($is_act != 1) {
                    $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFont()->getColor()->setRGB('64748b');
                }
            } elseif ($has_dead) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFont()->getColor()->setRGB('991b1b');
            } elseif ($has_frozen) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFont()->getColor()->setRGB('92400e');
            } elseif ($is_act != 1) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFont()->getColor()->setRGB('64748b');
            }
            $count++; $rowNum++;
        }

        // رقم الهوية / رقم الحساب / IBAN بمحاذاة LTR
        $sheet->getStyle('D2:D' . ($rowNum - 1))->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('I2:J' . ($rowNum - 1))->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        foreach (range('A', $lastCol) as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }
        $sheet->getStyle("A1:{$lastCol}" . ($rowNum - 1))->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = 'حسابات_الصرف_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== EMP DETAILS ====================
    function emp($emp_no = null)
    {
        if (!$emp_no) { redirect($this->PAGE_URL); }

        $emp_data = $this->{$this->MODEL_NAME}->get_employee($emp_no);
        if (!$emp_data) { show_404(); return; }

        $beneficiaries = $this->{$this->MODEL_NAME}->benef_list($emp_no);

        // إضافة عدد المرفقات لكل مستفيد (CATEGORY = 'payment_benef')
        if (is_array($beneficiaries)) {
            $this->load->model('attachments/attachment_model');
            foreach ($beneficiaries as &$b) {
                $atts = $this->attachment_model->get_list($b['BENEFICIARY_ID'] ?? 0, 'payment_benef', 0);
                $b['ATTACH_COUNT'] = is_array($atts) ? count($atts) : 0;
            }
            unset($b);
        }

        // البحث عن دفعات محتسبة (غير منفّذة) للموظف
        $this->load->model('payment_req/payment_req_model');
        $pending_batches = $this->payment_req_model->emp_pending_batches($emp_no);

        $data['title']         = 'حسابات الصرف — ' . ($emp_data['EMP_NAME'] ?? $emp_no);
        $data['emp_no']        = $emp_no;
        $data['emp_data']      = $emp_data;
        $data['accounts']      = $this->{$this->MODEL_NAME}->accounts_list($emp_no);
        $data['beneficiaries'] = $beneficiaries;
        $data['providers']     = $this->{$this->MODEL_NAME}->providers_list();
        $data['pending_batches'] = is_array($pending_batches) ? $pending_batches : [];
        $data['content']       = 'payment_accounts_emp';

        $this->load->view('template/template1', $data);
    }

    // ==================== ACCOUNT ENDPOINTS (AJAX) ====================
    function account_save()
    {
        $beneficiary_id = $this->input->post('beneficiary_id') ?: null;

        // ⏸️ TEMPORARILY DISABLED — التحقق من المرفقات للمستفيد
        // (سيُعاد تشغيله لاحقاً — راجع طارق قبل الإلغاء)
        // Validation: لا يُسمح بربط حساب بمستفيد بدون مرفقات (مستندات إثبات)
        /*
        if ($beneficiary_id) {
            $this->load->model('attachments/attachment_model');
            $atts = $this->attachment_model->get_list($beneficiary_id, 'payment_benef', 0);
            $cnt  = is_array($atts) ? count($atts) : 0;
            if ($cnt === 0) {
                header('Content-Type: application/json');
                echo json_encode([
                    'ok'  => false,
                    'msg' => 'لا يمكن ربط حساب بهذا المستفيد قبل إرفاق المستندات اللازمة (على الأقل مستند واحد).'
                ]);
                return;
            }
        }
        */

        $data = [
            'emp_no'         => $this->input->post('emp_no'),
            'beneficiary_id' => $beneficiary_id,
            'provider_id'    => $this->input->post('provider_id'),
            'branch_id'      => $this->input->post('branch_id') ?: null,
            'account_no'     => $this->input->post('account_no'),
            'iban'           => $this->input->post('iban'),
            'wallet_number'  => $this->input->post('wallet_number'),
            'owner_id_no'    => $this->input->post('owner_id_no'),
            'owner_name'     => $this->input->post('owner_name'),
            'owner_phone'    => $this->input->post('owner_phone'),
            'is_default'     => $this->input->post('is_default') ?: 0,
            'split_type'     => $this->input->post('split_type') ?: 3,
            'split_value'    => $this->input->post('split_value'),
            'split_order'    => $this->input->post('split_order') ?: 1,
            'notes'          => $this->input->post('notes'),
        ];

        $acc_id = $this->input->post('acc_id');
        if ($acc_id) {
            $data['acc_id'] = $acc_id;
            $res = $this->{$this->MODEL_NAME}->account_update($data);
        } else {
            $res = $this->{$this->MODEL_NAME}->account_insert($data);
        }

        header('Content-Type: application/json');
        $ok = is_numeric($res) || $res == '1';
        echo json_encode(['ok' => $ok, 'msg' => $ok ? 'تم الحفظ' : $res, 'id' => $ok ? $res : null]);
    }

    function account_delete()
    {
        $acc_id = $this->input->post('acc_id');
        $res = $this->{$this->MODEL_NAME}->account_delete($acc_id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم الحذف' : $res]);
    }

    function account_deactivate()
    {
        $acc_id      = $this->input->post('acc_id');
        $reason      = $this->input->post('reason') ?: 9;
        $notes       = $this->input->post('notes') ?: '';
        // 🆕 شهر بدء الإيقاف YYYYMM (اختياري — لو فاضي، الـ procedure يستخدم الشهر الحالي)
        $inact_month = $this->input->post('inact_month');
        $inact_month = ($inact_month !== null && $inact_month !== '') ? intval($inact_month) : null;

        $res = $this->{$this->MODEL_NAME}->account_deactivate($acc_id, $reason, $notes, $inact_month);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم الإيقاف' : $res]);
    }

    function account_reactivate()
    {
        $acc_id = $this->input->post('acc_id');
        $notes  = $this->input->post('notes') ?: '';
        $res = $this->{$this->MODEL_NAME}->account_reactivate($acc_id, $notes);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم إعادة التفعيل' : $res]);
    }

    function account_set_default()
    {
        $acc_id = $this->input->post('acc_id');
        $res = $this->{$this->MODEL_NAME}->account_set_default($acc_id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم التعيين كافتراضي' : $res]);
    }

    // ==================== VALIDATION DASHBOARD ====================
    /**
     * لوحة تحقّق: تستعرض الموظفين الذين عندهم مشاكل في إعداد التوزيع.
     * يجمع كل الحسابات النشطة + يحسب المشاكل في PHP (لتجنّب SP جديدة).
     */
    function validation()
    {
        $branch_no = $this->user->branch != 1 ? $this->user->branch : null;
        $issues = $this->_collect_split_issues($branch_no);

        $data['title']    = 'تحقّق إعداد التوزيع';
        $data['content']  = 'payment_accounts_validation';
        $data['issues']   = $issues;
        $this->load->view('template/template1', $data);
    }

    function _collect_split_issues($branch_no = null)
    {
        // نأخذ كل الموظفين الذين عندهم حسابات نشطة (limit عريض)
        $filters = ['branch_no' => $branch_no, 'has_acc' => 1, 'is_active' => null];
        $rows = $this->{$this->MODEL_NAME}->employees_list($filters, 0, 9999);

        $issues = [];
        if (!is_array($rows)) return $issues;

        foreach ($rows as $r) {
            $emp_no = (int)($r['EMP_NO'] ?? 0);
            if (!$emp_no) continue;
            $accounts = $this->{$this->MODEL_NAME}->accounts_list($emp_no, 1); // active only
            if (!is_array($accounts) || count($accounts) === 0) continue;

            $sum_pct        = 0;
            $has_remainder  = false;
            $has_default    = false;
            $missing_iban   = 0;
            $bank_count     = 0;
            foreach ($accounts as $a) {
                $st = (int)($a['SPLIT_TYPE']    ?? 3);
                $sv = (float)($a['SPLIT_VALUE'] ?? 0);
                $pt = (int)($a['PROVIDER_TYPE'] ?? 1);
                if ($st == 1) $sum_pct += $sv;
                if ($st == 3) $has_remainder = true;
                if ((int)($a['IS_DEFAULT'] ?? 0) === 1) $has_default = true;
                if ($pt == 1) {
                    $bank_count++;
                    if (empty(trim($a['IBAN'] ?? ''))) $missing_iban++;
                }
            }
            $cnt = count($accounts);
            $errs = [];

            if ($cnt > 1 && !$has_remainder) {
                $errs[] = ['code' => 'NO_REMAINDER', 'label' => 'لا يوجد حساب "كامل الباقي"'];
            }
            if ($sum_pct > 100.01) {
                $errs[] = ['code' => 'PCT_OVER',    'label' => 'مجموع النسب يتجاوز 100% (' . number_format($sum_pct, 1) . '%)'];
            }
            if ($cnt > 1 && !$has_default) {
                $errs[] = ['code' => 'NO_DEFAULT',  'label' => 'لا يوجد حساب افتراضي'];
            }
            if ($missing_iban > 0) {
                $errs[] = ['code' => 'NO_IBAN',     'label' => "$missing_iban حساب بنكي بدون IBAN"];
            }
            // تحذير ناعم: نسب < 100 ولا يوجد "باقي"
            if ($cnt > 1 && $has_remainder === false && $sum_pct < 100 && $sum_pct > 0) {
                // already covered by NO_REMAINDER
            }

            if (count($errs) > 0) {
                $issues[] = [
                    'EMP_NO'    => $emp_no,
                    'EMP_NAME'  => $r['EMP_NAME']   ?? '',
                    'ID_NO'     => $r['ID_NO']      ?? '',
                    'BRANCH'    => $r['BRANCH_NAME'] ?? '',
                    'ACC_CNT'   => $cnt,
                    'SUM_PCT'   => $sum_pct,
                    'ERRORS'    => $errs,
                ];
            }
        }
        return $issues;
    }

    /**
     * إصلاح تلقائي للتوزيع لموظف محدّد:
     * - يضمن وجود حساب واحد على الأقل بـ SPLIT_TYPE=3 (كامل الباقي)
     * - يضمن وجود حساب افتراضي واحد (IS_DEFAULT=1)
     * - يعيد ترتيب SPLIT_ORDER (1, 2, 3, ...)
     */
    function auto_fix_splits()
    {
        $emp_no   = $this->input->post('emp_no');
        $accounts = $this->{$this->MODEL_NAME}->accounts_list($emp_no, 1); // active only

        if (!is_array($accounts) || count($accounts) === 0) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'لا يوجد حسابات نشطة لإصلاحها']);
            return;
        }

        $has_default   = false;
        $has_remainder = false;
        foreach ($accounts as $a) {
            if ((int)($a['IS_DEFAULT'] ?? 0) === 1) $has_default   = true;
            if ((int)($a['SPLIT_TYPE'] ?? 0) === 3) $has_remainder = true;
        }

        $fixed_count = 0;
        $changes_log = [];
        $i = 0;
        foreach ($accounts as $a) {
            $i++;
            $changes = [];

            $newDefault = (int)($a['IS_DEFAULT'] ?? 0);
            $newSplit   = (int)($a['SPLIT_TYPE'] ?? 3);
            $newOrder   = $i;

            // 1) إذا لا يوجد افتراضي — اجعل الأول افتراضياً
            if (!$has_default && $i === 1) {
                $newDefault = 1;
                $has_default = true;
                $changes[] = 'افتراضي';
            }

            // 2) إذا لا يوجد "كامل الباقي" — اجعل الأول/الافتراضي يأخذها
            if (!$has_remainder && (($newDefault === 1) || ($i === 1 && !$has_default))) {
                $newSplit = 3;
                $has_remainder = true;
                $changes[] = 'كامل الباقي';
            }

            // 3) إعادة ترتيب SPLIT_ORDER دائماً
            if ((int)($a['SPLIT_ORDER'] ?? 0) !== $newOrder) {
                $changes[] = 'ترتيب=' . $newOrder;
            }

            if (empty($changes)) continue;

            $upd = [
                'acc_id'        => $a['ACC_ID'],
                'branch_id'     => $a['BRANCH_ID']     ?? null,
                'account_no'    => $a['ACCOUNT_NO']    ?? null,
                'iban'          => $a['IBAN']          ?? null,
                'wallet_number' => $a['WALLET_NUMBER'] ?? null,
                'owner_id_no'   => $a['OWNER_ID_NO']   ?? null,
                'owner_name'    => $a['OWNER_NAME']    ?? null,
                'owner_phone'   => $a['OWNER_PHONE']   ?? null,
                'is_default'    => $newDefault,
                'split_type'    => $newSplit,
                'split_value'   => $a['SPLIT_VALUE']   ?? null,
                'split_order'   => $newOrder,
                'notes'         => $a['NOTES']         ?? null,
            ];
            $this->{$this->MODEL_NAME}->account_update($upd);
            $fixed_count++;
            $changes_log[] = ($a['PROVIDER_NAME'] ?? ('#'.$a['ACC_ID'])) . ': ' . implode('، ', $changes);
        }

        header('Content-Type: application/json');
        if ($fixed_count === 0) {
            echo json_encode(['ok' => true, 'msg' => 'التوزيع سليم — لا حاجة لإصلاح', 'fixed' => 0]);
        } else {
            echo json_encode([
                'ok'      => true,
                'msg'     => "تم إصلاح $fixed_count حساب",
                'fixed'   => $fixed_count,
                'details' => $changes_log,
            ]);
        }
    }

    function accounts_list_json()
    {
        $emp_no = $this->input->get_post('emp_no');
        $rows = $this->{$this->MODEL_NAME}->accounts_list($emp_no);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    // ==================== BENEFICIARY ENDPOINTS ====================
    function benef_save()
    {
        $data = [
            'emp_no'    => $this->input->post('emp_no'),
            'rel_type'  => $this->input->post('rel_type'),
            'id_no'     => $this->input->post('id_no'),
            'name'      => $this->input->post('name'),
            'phone'     => $this->input->post('phone'),
            'pct_share' => $this->input->post('pct_share'),
            'from_date' => $this->input->post('from_date'),
            'to_date'   => $this->input->post('to_date'),
            'notes'     => $this->input->post('notes'),
        ];

        $benef_id = $this->input->post('benef_id');
        if ($benef_id) {
            $data['benef_id'] = $benef_id;
            $data['status']   = $this->input->post('status') ?: 1;
            $res = $this->{$this->MODEL_NAME}->benef_update($data);
        } else {
            $res = $this->{$this->MODEL_NAME}->benef_insert($data);
        }

        header('Content-Type: application/json');
        $ok = is_numeric($res) || $res == '1';
        echo json_encode(['ok' => $ok, 'msg' => $ok ? 'تم الحفظ' : $res, 'id' => $ok ? $res : null]);
    }

    function benef_delete()
    {
        $id = $this->input->post('benef_id');
        $res = $this->{$this->MODEL_NAME}->benef_delete($id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم الحذف' : $res]);
    }

    function benef_list_json()
    {
        $emp_no = $this->input->get_post('emp_no');
        $rows = $this->{$this->MODEL_NAME}->benef_list($emp_no);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    // ==================== PROVIDER ENDPOINTS ====================
    function providers($page = 1, $type = -1, $is_active = -1, $search = -1)
    {
        $data['title']     = 'مزودو الصرف (بنوك + محافظ)';
        $data['content']   = 'payment_accounts_providers';
        $data['page']      = $page;
        $data['type']      = $type;
        $data['is_active'] = $is_active;
        $data['search']    = $search;
        $this->load->view('template/template1', $data);
    }

    function providers_export_excel()
    {
        $type      = $this->check_vars(-1, 'type');
        $is_active = $this->check_vars(-1, 'is_active');
        $q         = $this->check_vars(-1, 'q');

        $filters = [
            'type'      => ($type      !== null && $type      !== '') ? intval($type)      : null,
            'is_active' => ($is_active !== null && $is_active !== '') ? intval($is_active) : null,
            'search'    => ($q         !== null && $q         !== '') ? $q                 : null,
        ];

        $rows = $this->{$this->MODEL_NAME}->providers_list_paginated($filters, 0, 999999);

        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات للتصدير"); history.back();</script>'; return;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('مزودو الصرف');
        $sheet->setRightToLeft(true);

        $headers = ['م', 'المزود', 'الرمز', 'النوع', 'رقم الحساب', 'IBAN', 'الموظفون', 'الفروع', 'الحالة'];
        $lastCol = chr(64 + count($headers)); // I
        $col = 'A';
        foreach ($headers as $h) { $sheet->setCellValue($col . '1', $h); $col++; }

        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:{$lastCol}1")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $rowNum = 2; $count = 1;
        foreach ($rows as $row) {
            $name      = $row['PROVIDER_NAME']     ?? '';
            $code      = $row['PROVIDER_CODE']     ?? '';
            $type_v    = (int)($row['PROVIDER_TYPE']  ?? 1);
            $acc_no    = $row['COMPANY_ACCOUNT_NO'] ?? '';
            $iban      = $row['COMPANY_IBAN']       ?? '';
            $is_active = (int)($row['IS_ACTIVE']    ?? 1);
            $br_cnt    = (int)($row['BRANCH_COUNT']  ?? 0);
            $acc_cnt   = (int)($row['ACCOUNT_COUNT'] ?? 0);

            $sheet->setCellValue('A' . $rowNum, $count);
            $sheet->setCellValue('B' . $rowNum, $name);
            $sheet->setCellValue('C' . $rowNum, $code);
            $sheet->setCellValue('D' . $rowNum, $type_v == 1 ? 'بنك' : 'محفظة');
            $sheet->setCellValueExplicit('E' . $rowNum, (string)$acc_no, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $rowNum, (string)($type_v == 1 ? $iban : ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $rowNum, $acc_cnt);
            $sheet->setCellValue('H' . $rowNum, $type_v == 1 ? $br_cnt : '');
            $sheet->setCellValue('I' . $rowNum, $is_active == 1 ? 'نشط' : 'موقوف');

            if (!$is_active) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFont()->getColor()->setRGB('64748b');
            }
            $count++; $rowNum++;
        }

        // محاذاة LTR لرقم الحساب و IBAN
        $sheet->getStyle('E2:F' . ($rowNum - 1))->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        foreach (range('A', $lastCol) as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }
        $sheet->getStyle("A1:{$lastCol}" . ($rowNum - 1))->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = 'مزودو_الصرف_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    function providers_get_page($page = 1, $type = -1, $is_active = -1, $q = -1)
    {
        $this->load->library('pagination');

        $type      = $this->check_vars($type,      'type');
        $is_active = $this->check_vars($is_active, 'is_active');
        $q         = $this->check_vars($q,         'q');

        $filters = [
            'type'      => ($type      !== null && $type      !== '') ? intval($type)      : null,
            'is_active' => ($is_active !== null && $is_active !== '') ? intval($is_active) : null,
            'search'    => ($q         !== null && $q         !== '') ? $q                 : null,
        ];

        $total_rows = $this->{$this->MODEL_NAME}->providers_count($filters);
        $totals     = $this->{$this->MODEL_NAME}->providers_totals($filters);

        $config['base_url']         = base_url('payment_accounts/payment_accounts/providers_get_page');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows']       = $total_rows;
        $config['per_page']         = $this->page_size;
        $config['num_links']        = 20;
        $config['cur_page']         = $page;
        $config['full_tag_open']    = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close']   = '</ul></div>';
        $config['first_tag_open']   = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close']  = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open']     = '<li class="active"><span><b>';
        $config['cur_tag_close']    = '</b></span></li>';
        $this->pagination->initialize($config);

        $offset = ($page - 1) * $config['per_page'];
        $rows   = $this->{$this->MODEL_NAME}->providers_list_paginated($filters, $offset, $config['per_page']);

        $data['page_rows']  = is_array($rows) ? $rows : [];
        $data['total_rows'] = $total_rows;
        $data['page']       = $page;
        $data['offset']     = $offset;
        $data['totals']     = $totals;

        $this->load->view('payment_accounts_providers_page', $data);
    }

    function provider_save()
    {
        $data = [
            'type'           => $this->input->post('type'),
            'name'           => $this->input->post('name'),
            'code'           => $this->input->post('code'),
            'company_acc_no' => $this->input->post('company_acc_no'),
            'company_acc_id' => $this->input->post('company_acc_id'),
            'company_iban'   => $this->input->post('company_iban'),
            'export_format'  => $this->input->post('export_format'),
            'sort_order'     => $this->input->post('sort_order') ?: 999,
        ];

        $pid = $this->input->post('provider_id');
        if ($pid) {
            $data['provider_id'] = $pid;
            $data['is_active']   = $this->input->post('is_active') ?: 1;
            $res = $this->{$this->MODEL_NAME}->provider_update($data);
        } else {
            $res = $this->{$this->MODEL_NAME}->provider_insert($data);
        }

        header('Content-Type: application/json');
        $ok = is_numeric($res) || $res == '1';
        echo json_encode(['ok' => $ok, 'msg' => $ok ? 'تم الحفظ' : $res, 'id' => $ok ? $res : null]);
    }

    function providers_list_json()
    {
        $type = $this->input->get_post('type');
        $rows = $this->{$this->MODEL_NAME}->providers_list($type ?: null);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    // ==================== BULK IMPORT ====================
    function bulk_import()
    {
        $data['title']     = 'استيراد حسابات من Excel';
        $data['content']   = 'payment_accounts_bulk_import';
        $data['providers'] = $this->{$this->MODEL_NAME}->providers_list();
        $this->load->view('template/template1', $data);
    }

    function bulk_import_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Accounts');
        $sheet->setRightToLeft(true);

        $headers = ['رقم الموظف*', 'رمز المزود*', 'رقم الحساب', 'IBAN', 'رقم المحفظة', 'هوية صاحب الحساب', 'اسم صاحب الحساب', 'جوال صاحب الحساب', 'افتراضي (0/1)', 'نوع التوزيع*', 'قيمة التوزيع', 'ترتيب', 'ملاحظات'];
        $col = 'A';
        foreach ($headers as $h) { $sheet->setCellValue($col . '1', $h); $col++; }
        $lastCol = chr(64 + count($headers));

        // Header style
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->getColor()->setRGB('FFFFFF');

        // مثال
        $sheet->setCellValue('A2', '1090');
        $sheet->setCellValue('B2', 'BANK89');
        $sheet->setCellValueExplicit('C2', '1234567890', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('D2', 'PS31PALS045105274280991604000', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('F2', '801784513');
        $sheet->setCellValue('G2', 'حنين اسماعيل صافي');
        $sheet->setCellValue('I2', 1);
        $sheet->setCellValue('J2', 3); // كامل الباقي
        $sheet->setCellValue('L2', 1);

        // ملاحظات تعليمات
        $sheet->setCellValue('A4', 'ملاحظات:');
        $sheet->setCellValue('A5', '• نوع التوزيع: 1=نسبة، 2=مبلغ ثابت، 3=كامل الباقي');
        $sheet->setCellValue('A6', '• رمز المزود من شاشة المزودين (مثل BANK89, BANK82, JAWWAL, PALPAY)');
        $sheet->setCellValue('A7', '• الحقول بـ * إجبارية');
        $sheet->setCellValue('A8', '• لو رقم المحفظة موجود، رقم الحساب فارغ');

        foreach (range('A', $lastCol) as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }

        $filename = 'قالب_استيراد_حسابات.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    function bulk_import_preview()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'لم يتم رفع الملف']);
            return;
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['file']['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'فشل قراءة الملف: ' . $e->getMessage()]);
            return;
        }

        // Build provider lookup
        $providers = $this->{$this->MODEL_NAME}->providers_list();
        $prov_map  = [];
        if (is_array($providers)) {
            foreach ($providers as $p) {
                $code = trim($p['PROVIDER_CODE'] ?? '');
                if ($code) $prov_map[mb_strtoupper($code)] = $p;
            }
        }

        // helper: تطبيع الأرقام لمقارنة المحافظ/الحسابات (إزالة فراغات/شرَط/صفر بادئ)
        $norm = function ($s) {
            $t = mb_strtoupper(trim((string)$s));
            $t = preg_replace('/[\s\-]+/u', '', $t);
            return ltrim($t, '0');
        };

        $parsed = [];
        $row_n = 0;
        foreach ($rows as $rIdx => $r) {
            if ($rIdx == 1) continue; // header
            $row_n++;
            $emp_no   = trim((string)($r['A'] ?? ''));
            $prov_cd  = trim((string)($r['B'] ?? ''));
            $acc_no   = trim((string)($r['C'] ?? ''));
            $iban     = trim((string)($r['D'] ?? ''));
            $wallet   = trim((string)($r['E'] ?? ''));
            $own_id   = trim((string)($r['F'] ?? ''));
            $own_nm   = trim((string)($r['G'] ?? ''));
            $own_ph   = trim((string)($r['H'] ?? ''));
            $is_def   = (int)($r['I'] ?? 0);
            $sp_type  = (int)($r['J'] ?? 3);
            $sp_val   = $r['K'] ?? null;
            $sp_ord   = (int)($r['L'] ?? 1);
            $notes    = trim((string)($r['M'] ?? ''));

            if (empty($emp_no) && empty($prov_cd)) continue; // skip empty/notes rows

            $errors = [];
            if (!$emp_no || !ctype_digit($emp_no))            $errors[] = 'رقم الموظف غير صحيح';
            if (!$prov_cd)                                     $errors[] = 'رمز المزود مطلوب';
            $prov = $prov_map[mb_strtoupper($prov_cd)] ?? null;
            if (!$prov)                                        $errors[] = "المزود '$prov_cd' غير موجود";
            if (!in_array($sp_type, [1,2,3]))                  $errors[] = 'نوع التوزيع 1 أو 2 أو 3';
            if ($prov && (int)$prov['PROVIDER_TYPE'] === 1 && !$acc_no && !$iban) $errors[] = 'البنك يحتاج رقم حساب أو IBAN';
            if ($prov && (int)$prov['PROVIDER_TYPE'] === 2 && !$wallet)           $errors[] = 'المحفظة تحتاج رقم محفظة';

            $parsed[] = [
                'row'         => $row_n + 1,
                'emp_no'      => $emp_no,
                'provider'    => $prov ? $prov['PROVIDER_NAME'] : $prov_cd,
                'provider_id' => $prov['PROVIDER_ID'] ?? null,
                'provider_type' => $prov['PROVIDER_TYPE'] ?? null,
                'account_no'  => $acc_no,
                'iban'        => $iban,
                'wallet'      => $wallet,
                'owner_name'  => $own_nm,
                'owner_id_no' => $own_id,
                'owner_phone' => $own_ph,
                'is_default'  => $is_def,
                'split_type'  => $sp_type,
                'split_value' => $sp_val,
                'split_order' => $sp_ord,
                'notes'       => $notes,
                'errors'      => $errors,
                'valid'       => empty($errors),
                'duplicate'   => false,
                'dup_reason'  => '',
            ];
        }

        // 🆕 فحص التكرار: (1) داخل الملف نفسه (2) مقابل الموجود في DB
        $emp_set = [];
        foreach ($parsed as $p) {
            if (!empty($p['emp_no']) && ctype_digit($p['emp_no'])) {
                $emp_set[$p['emp_no']] = true;
            }
        }
        // قيود قائمة في DB لكل موظف (مرة واحدة لكل موظف)
        $db_keys = []; // key = emp_no|provider_id|kind|normalized_value
        foreach (array_keys($emp_set) as $emp_no) {
            $existing = $this->{$this->MODEL_NAME}->accounts_list($emp_no, 1);
            if (!is_array($existing)) continue;
            foreach ($existing as $a) {
                $pid = (int)($a['PROVIDER_ID'] ?? 0);
                if (!empty($a['WALLET_NUMBER'])) {
                    $db_keys["$emp_no|$pid|W|" . $norm($a['WALLET_NUMBER'])] = true;
                }
                if (!empty($a['ACCOUNT_NO'])) {
                    $db_keys["$emp_no|$pid|A|" . $norm($a['ACCOUNT_NO'])] = true;
                }
                if (!empty($a['IBAN'])) {
                    $db_keys["$emp_no|$pid|I|" . $norm($a['IBAN'])] = true;
                }
            }
        }

        // فحص كل صف
        $file_keys = [];
        foreach ($parsed as &$p) {
            if (!$p['valid']) continue;
            $emp_no = $p['emp_no']; $pid = (int)$p['provider_id'];
            $candidates = [];
            if (!empty($p['wallet']))     $candidates[] = ['W', $p['wallet'], 'محفظة'];
            if (!empty($p['account_no'])) $candidates[] = ['A', $p['account_no'], 'رقم حساب'];
            if (!empty($p['iban']))       $candidates[] = ['I', $p['iban'], 'IBAN'];
            foreach ($candidates as $c) {
                $key = "$emp_no|$pid|{$c[0]}|" . $norm($c[1]);
                if (isset($db_keys[$key])) {
                    $p['duplicate']  = true;
                    $p['dup_reason'] = $c[2] . ' موجود مسبقاً في قاعدة البيانات';
                    break;
                }
                if (isset($file_keys[$key])) {
                    $p['duplicate']  = true;
                    $p['dup_reason'] = $c[2] . ' مكرر في الملف نفسه';
                    break;
                }
                $file_keys[$key] = true;
            }
        }
        unset($p);

        $valid_cnt = 0; $err_cnt = 0; $dup_cnt = 0;
        foreach ($parsed as $p) {
            if (!$p['valid']) { $err_cnt++; continue; }
            if (!empty($p['duplicate'])) { $dup_cnt++; continue; }
            $valid_cnt++;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'ok'         => true,
            'rows'       => $parsed,
            'valid'      => $valid_cnt,
            'errors'     => $err_cnt,
            'duplicates' => $dup_cnt,
            'total'      => count($parsed),
        ], JSON_UNESCAPED_UNICODE);
    }

    function bulk_import_execute()
    {
        $rows_json = $this->input->post('rows');
        $rows = json_decode($rows_json, true);
        if (!is_array($rows)) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'بيانات غير صحيحة']);
            return;
        }

        $ok_cnt = 0; $err_cnt = 0; $skip_cnt = 0; $errors = [];
        foreach ($rows as $r) {
            if (empty($r['valid'])) { $err_cnt++; continue; }
            // 🆕 تخطّي المكرر (تم اكتشافه في المعاينة)
            if (!empty($r['duplicate'])) { $skip_cnt++; continue; }

            $data = [
                'emp_no'      => $r['emp_no'],
                'provider_id' => $r['provider_id'],
                'account_no'  => $r['account_no']  ?? '',
                'iban'        => $r['iban']        ?? '',
                'wallet_number' => $r['wallet']    ?? '',
                'owner_name'  => $r['owner_name']  ?? '',
                'owner_id_no' => $r['owner_id_no'] ?? '',
                'owner_phone' => $r['owner_phone'] ?? '',
                'is_default'  => $r['is_default']  ?? 0,
                'split_type'  => $r['split_type']  ?? 3,
                'split_value' => $r['split_value'] ?? null,
                'split_order' => $r['split_order'] ?? 1,
                'notes'       => $r['notes']       ?? '',
            ];
            $res = $this->{$this->MODEL_NAME}->account_insert($data);
            if (is_numeric($res)) {
                $ok_cnt++;
            } elseif (is_string($res) && strpos($res, 'حساب مكرر') !== false) {
                // الـ DB رفضت المكرر (race condition / فات على المعاينة)
                $skip_cnt++;
            } else {
                $err_cnt++;
                $errors[] = 'صف ' . ($r['row'] ?? '?') . ': ' . $res;
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'ok'         => true,
            'inserted'   => $ok_cnt,
            'skipped'    => $skip_cnt,
            'failed'     => $err_cnt,
            'errors'     => $errors,
        ], JSON_UNESCAPED_UNICODE);
    }

    // ==================== DASHBOARD ====================
    function dashboard()
    {
        // فحص سريع للصحة
        $health = $this->{$this->MODEL_NAME}->health_overview();

        // آخر 5 دفعات (من payment_req)
        $this->load->model('payment_req/payment_req_model');
        $recent = [];
        $all = $this->payment_req_model->batch_history_list();
        if (is_array($all)) {
            $recent = array_slice($all, 0, 5);
            array_walk_recursive($recent, function (&$v) {
                if (is_string($v)) { $v = mb_convert_encoding($v, 'UTF-8', 'UTF-8'); }
            });
        }

        $data['title']        = 'لوحة التحكم — حسابات الصرف';
        $data['content']      = 'payment_accounts_dashboard';
        $data['health']       = $health;
        $data['recent_batches'] = $recent;
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    // ==================== HEALTH CHECK ====================
    function health_check()
    {
        $data['title']     = 'فحص صحة بيانات الصرف';
        $data['content']   = 'payment_accounts_health';
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    function health_overview_json()
    {
        $res = $this->{$this->MODEL_NAME}->health_overview();
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res['msg'] == '1', 'data' => $res]);
    }

    function health_list_json()
    {
        // 🆕 يدعم POST (للـ JSON) و GET (للـ Excel export)
        $is_export = (int)$this->input->get('export') === 1;
        $get_post  = function ($k, $def = null) {
            return $this->input->post($k) ?? $this->input->get($k) ?? $def;
        };

        $category  = $get_post('category', 'EMP_NO_ACC');
        $branch_no = $get_post('branch_no');
        $offset    = (int)($get_post('offset', 0));
        $limit     = (int)($get_post('limit',  $is_export ? 50000 : 200));
        $filters   = ['branch_no' => ($branch_no !== null && $branch_no !== '' && $branch_no != -1) ? intval($branch_no) : null];

        $rows = $this->{$this->MODEL_NAME}->health_list($category, $filters, $offset, $limit);
        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        // 🆕 تصدير Excel
        if ($is_export) {
            $title = (string)$get_post('title', 'health_check');
            $this->_health_export_excel($category, $title, is_array($rows) ? $rows : []);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    /**
     * 🆕 تصدير قائمة health check إلى Excel
     */
    private function _health_export_excel($category, $title, $rows)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('فحص الصحة');
        $sheet->setRightToLeft(true);

        // headers حسب الفئة
        if ($category === 'PROV_INCOMPLETE') {
            $headers = ['م', 'المزود', 'الحالة', 'التفاصيل'];
        } else {
            $headers = ['م', 'رقم الموظف', 'اسم الموظف', 'المقر', 'الحالة', 'التفاصيل'];
        }
        $lastCol = chr(64 + count($headers));
        $col     = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $col++;
        }

        // تنسيق الـ header
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle("A1:{$lastCol}1")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // الصفوف
        $rowNum = 2;
        $count  = 1;
        foreach ($rows as $r) {
            $is_act = (int)($r['IS_ACTIVE'] ?? 0);
            if ($category === 'PROV_INCOMPLETE') {
                $sheet->setCellValue('A' . $rowNum, $count);
                $sheet->setCellValue('B' . $rowNum, $r['EMP_NAME'] ?? '');
                $sheet->setCellValue('C' . $rowNum, $is_act == 1 ? 'نشط' : 'موقوف');
                $sheet->setCellValue('D' . $rowNum, $r['DETAIL_INFO'] ?? '');
            } else {
                $sheet->setCellValue('A' . $rowNum, $count);
                $sheet->setCellValue('B' . $rowNum, $r['EMP_NO'] ?? '');
                $sheet->setCellValue('C' . $rowNum, $r['EMP_NAME'] ?? '');
                $sheet->setCellValue('D' . $rowNum, $r['BRANCH_NAME'] ?? '');
                $sheet->setCellValue('E' . $rowNum, $is_act == 1 ? 'فعّال' : 'متقاعد');
                $sheet->setCellValue('F' . $rowNum, $r['DETAIL_INFO'] ?? '');
            }
            $count++;
            $rowNum++;
        }

        foreach (range('A', $lastCol) as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }
        $sheet->getStyle("A1:{$lastCol}" . max($rowNum - 1, 1))->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // اسم الملف — نظافة من الأحرف الإشكالية
        $clean_title = preg_replace('/[^\p{L}\p{N}_\-]+/u', '_', $title);
        $filename    = ($clean_title ?: 'health_check') . '_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    function accounts_link_bulk_auto()
    {
        $res = $this->{$this->MODEL_NAME}->link_accounts_bulk_auto();
        $ok  = $res['msg'] == '1';
        header('Content-Type: application/json');
        echo json_encode([
            'ok'     => $ok,
            'linked' => (int)$res['linked'],
            'emps'   => (int)$res['emps'],
            'msg'    => $ok
                ? ('تم ربط ' . (int)$res['linked'] . ' حساب على ' . (int)$res['emps'] . ' موظف')
                : $res['msg']
        ]);
    }

    function account_link_auto()
    {
        $emp_no = (int)$this->input->post('emp_no');
        if (!$emp_no) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'رقم الموظف مطلوب']);
            return;
        }
        $res = $this->{$this->MODEL_NAME}->link_accounts_to_benef_auto($emp_no);
        $ok  = $res['msg'] == '1';
        $cnt = (int)$res['linked'];
        header('Content-Type: application/json');
        echo json_encode([
            'ok'     => $ok,
            'linked' => $cnt,
            'msg'    => $ok
                ? ($cnt > 0 ? "تم ربط $cnt حساب بمستفيدين" : "لا توجد حسابات قابلة للربط — الحسابات إما مرتبطة سابقاً أو لا تطابق أي مستفيد")
                : $res['msg']
        ]);
    }

    function provider_delete()
    {
        $pid = $this->input->post('provider_id');
        $res = $this->{$this->MODEL_NAME}->provider_delete($pid);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم الحذف' : $res]);
    }

    function provider_toggle_active()
    {
        $pid    = $this->input->post('provider_id');
        $active = (int)$this->input->post('is_active');
        $res    = $this->{$this->MODEL_NAME}->provider_toggle_active($pid, $active);
        header('Content-Type: application/json');
        echo json_encode([
            'ok'  => $res == '1',
            'msg' => $res == '1' ? ($active ? 'تم التفعيل' : 'تم الإيقاف') : $res
        ]);
    }

    function provider_accounts_json()
    {
        $pid = $this->input->get_post('provider_id');
        $only_active = (int)($this->input->get_post('only_active') ?? 0);
        $rows = $this->{$this->MODEL_NAME}->provider_accounts($pid, $only_active);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    // تصدير Excel لحسابات موظفين مزود معين
    function provider_accounts_export_excel($pid = null, $only_active = 0)
    {
        $pid = $pid ?: $this->input->get('provider_id');
        if (!$pid) { show_error('provider_id required'); return; }

        $rows = $this->{$this->MODEL_NAME}->provider_accounts($pid, (int)$only_active);
        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات للتصدير"); history.back();</script>'; return;
        }

        // اسم المزود لـ filename + title
        $provider = $this->{$this->MODEL_NAME}->provider_get($pid);
        $prov_name = (is_array($provider) && !empty($provider))
            ? ($provider[0]['PROVIDER_NAME'] ?? 'مزود')
            : 'مزود';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('حسابات ' . mb_substr($prov_name, 0, 25));
        $sheet->setRightToLeft(true);

        $headers = ['م', 'رقم الموظف', 'اسم الموظف', 'المقر', 'رقم الحساب', 'IBAN', 'الفرع', 'صاحب الحساب', 'افتراضي', 'الحالة'];
        $lastCol = chr(64 + count($headers)); // J
        $col = 'A';
        foreach ($headers as $h) { $sheet->setCellValue($col . '1', $h); $col++; }
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle("A1:{$lastCol}1")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $rowNum = 2; $count = 1;
        foreach ($rows as $r) {
            $acc = $r['ACCOUNT_NO'] ?? ($r['WALLET_NUMBER'] ?? '');
            $sheet->setCellValue('A' . $rowNum, $count++);
            $sheet->setCellValue('B' . $rowNum, $r['EMP_NO'] ?? '');
            $sheet->setCellValue('C' . $rowNum, $r['EMP_NAME'] ?? '');
            $sheet->setCellValue('D' . $rowNum, $r['BRANCH_NAME'] ?? '');
            $sheet->setCellValueExplicit('E' . $rowNum, (string)$acc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $rowNum, (string)($r['IBAN'] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $rowNum, $r['BANK_BRANCH_NAME'] ?? '');
            $sheet->setCellValue('H' . $rowNum, $r['OWNER_NAME'] ?? '');
            $sheet->setCellValue('I' . $rowNum, ((int)($r['IS_DEFAULT'] ?? 0) === 1) ? 'نعم' : '');
            $sheet->setCellValue('J' . $rowNum, ((int)($r['IS_ACTIVE'] ?? 0) === 1) ? 'نشط' : 'موقوف');
            $rowNum++;
        }

        // Auto width + borders
        foreach (range('A', $lastCol) as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }
        $sheet->getStyle("A1:{$lastCol}" . ($rowNum - 1))->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = 'accounts_' . preg_replace('/[^a-z0-9_\-]/i', '_', $prov_name) . '_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    function provider_branches_json()
    {
        $pid = $this->input->get_post('provider_id');
        $rows = $this->{$this->MODEL_NAME}->branches_list($pid);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    function providers_bulk_action()
    {
        $ids    = $this->input->post('ids');     // "100,101,102"
        $action = strtoupper(trim($this->input->post('action') ?? ''));
        $res    = $this->{$this->MODEL_NAME}->providers_bulk_action($ids, $action);

        $ok      = $res['msg'] == '1';
        $okCnt   = (int)$res['ok'];
        $failCnt = (int)$res['fail'];
        $msg     = $ok
            ? "تمت العملية: نجح $okCnt" . ($failCnt > 0 ? " — فشل $failCnt" : "")
            : $res['msg'];

        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok, 'msg' => $msg, 'success' => $okCnt, 'failed' => $failCnt]);
    }

    // ==================== BRANCH ENDPOINTS ====================
    function branch_save()
    {
        $data = [
            'provider_id'    => $this->input->post('provider_id'),
            'name'           => $this->input->post('name'),
            'legacy_bank_no' => $this->input->post('legacy_bank_no'),
            'print_no'       => $this->input->post('print_no'),
            'address'        => $this->input->post('address'),
            'phone1'         => $this->input->post('phone1'),
            'phone2'         => $this->input->post('phone2'),
            'fax'            => $this->input->post('fax'),
        ];

        $bid = $this->input->post('branch_id');
        if ($bid) {
            $data['branch_id'] = $bid;
            $data['is_active'] = $this->input->post('is_active') ?: 1;
            $res = $this->{$this->MODEL_NAME}->branch_update($data);
        } else {
            $res = $this->{$this->MODEL_NAME}->branch_insert($data);
        }

        header('Content-Type: application/json');
        $ok = is_numeric($res) || $res == '1';
        echo json_encode(['ok' => $ok, 'msg' => $ok ? 'تم الحفظ' : $res, 'id' => $ok ? $res : null]);
    }

    function branches_list_json()
    {
        $pid = $this->input->get_post('provider_id');
        $rows = $this->{$this->MODEL_NAME}->branches_list($pid ?: null);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    function branch_link_provider()
    {
        $bid = $this->input->post('branch_id');
        $pid = $this->input->post('provider_id');
        $res = $this->{$this->MODEL_NAME}->branch_link_provider($bid, $pid);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم الربط' : $res]);
    }

    // ==================== SPLIT ENDPOINTS ====================
    function split_list_json()
    {
        $detail_id = $this->input->get_post('detail_id');
        $rows = $this->{$this->MODEL_NAME}->split_list($detail_id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    function split_auto_fill()
    {
        $detail_id = $this->input->post('detail_id');
        $res = $this->{$this->MODEL_NAME}->split_auto_fill($detail_id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم التوزيع التلقائي' : $res]);
    }

    function split_update_manual()
    {
        $detail_id = $this->input->post('detail_id');
        $json      = $this->input->post('splits_json'); // [{"acc_id":101,"amount":700},...]
        $res = $this->{$this->MODEL_NAME}->split_update_manual($detail_id, $json);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم التحديث' : $res]);
    }

    function split_reset_auto()
    {
        $detail_id = $this->input->post('detail_id');
        $res = $this->{$this->MODEL_NAME}->split_reset_auto($detail_id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => $res == '1', 'msg' => $res == '1' ? 'تم الإرجاع للتلقائي' : $res]);
    }
}
