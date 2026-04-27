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
    function index($page = 1, $branch_no = -1, $emp_no = -1, $is_active = -1, $has_acc = -1)
    {
        $data['title']     = 'إدارة حسابات الصرف';
        $data['content']   = 'payment_accounts_index';
        $data['page']      = $page;
        $data['branch_no'] = $branch_no;
        $data['emp_no']    = $emp_no;
        $data['is_active'] = $is_active;
        $data['has_acc']   = $has_acc;
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    // ==================== PAGINATED LIST ====================
    function get_page($page = 1, $branch_no = -1, $emp_no = -1, $is_active = -1, $has_acc = -1)
    {
        $this->load->library('pagination');

        $branch_no = $this->check_vars($branch_no, 'branch_no');
        $emp_no    = $this->check_vars($emp_no,    'emp_no');
        $is_active = $this->check_vars($is_active, 'is_active');
        $has_acc   = $this->check_vars($has_acc,   'has_acc');

        if ($this->user->branch != 1) {
            $branch_no = $this->user->branch;
        }

        $filters = [
            'emp_no'    => ($emp_no    !== null && $emp_no    !== '') ? intval($emp_no)    : null,
            'branch_no' => ($branch_no !== null && $branch_no !== '') ? intval($branch_no) : null,
            'is_active' => ($is_active !== null && $is_active !== '') ? intval($is_active) : null,
            'has_acc'   => ($has_acc   !== null && $has_acc   !== '') ? intval($has_acc)   : null,
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

        if ($this->user->branch != 1) {
            $branch_no = $this->user->branch;
        }

        $filters = [
            'emp_no'    => ($emp_no    !== null && $emp_no    !== '') ? intval($emp_no)    : null,
            'branch_no' => ($branch_no !== null && $branch_no !== '') ? intval($branch_no) : null,
            'is_active' => ($is_active !== null && $is_active !== '') ? intval($is_active) : null,
            'has_acc'   => ($has_acc   !== null && $has_acc   !== '') ? intval($has_acc)   : null,
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

            // الحالة: متوفى > حساب مغلق > فعّال/متقاعد
            if ($has_dead) {
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

            if ($has_dead) {
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

        $data['title']         = 'حسابات الصرف — ' . ($emp_data['EMP_NAME'] ?? $emp_no);
        $data['emp_no']        = $emp_no;
        $data['emp_data']      = $emp_data;
        $data['accounts']      = $this->{$this->MODEL_NAME}->accounts_list($emp_no);
        $data['beneficiaries'] = $beneficiaries;
        $data['providers']     = $this->{$this->MODEL_NAME}->providers_list();
        $data['content']       = 'payment_accounts_emp';

        $this->load->view('template/template1', $data);
    }

    // ==================== ACCOUNT ENDPOINTS (AJAX) ====================
    function account_save()
    {
        $beneficiary_id = $this->input->post('beneficiary_id') ?: null;

        // Validation: لا يُسمح بربط حساب بمستفيد بدون مرفقات (مستندات إثبات)
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
        $acc_id = $this->input->post('acc_id');
        $reason = $this->input->post('reason') ?: 9;
        $notes  = $this->input->post('notes') ?: '';
        $res = $this->{$this->MODEL_NAME}->account_deactivate($acc_id, $reason, $notes);
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
            'provider_id' => $this->input->post('provider_id'),
            'name'        => $this->input->post('name'),
            'print_no'    => $this->input->post('print_no'),
            'address'     => $this->input->post('address'),
            'phone1'      => $this->input->post('phone1'),
            'phone2'      => $this->input->post('phone2'),
            'fax'         => $this->input->post('fax'),
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
