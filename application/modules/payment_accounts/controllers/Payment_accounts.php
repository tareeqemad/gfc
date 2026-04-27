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

        $headers = ['م', 'رقم الموظف', 'اسم الموظف', 'المقر', 'التوظيف', 'النوع', 'البنك / المحفظة', 'رقم الحساب', 'IBAN', 'صاحب الحساب', 'حسابات إضافية', 'مستفيدون'];
        $lastCol = chr(64 + count($headers)); // L
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
            $branch     = $row['BRANCH_NAME'] ?? '';
            $is_act     = (int)($row['IS_ACTIVE'] ?? 0);
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

            $sheet->setCellValue('A' . $rowNum, $count);
            $sheet->setCellValue('B' . $rowNum, $emp_no_v);
            $sheet->setCellValue('C' . $rowNum, $emp_name);
            $sheet->setCellValue('D' . $rowNum, $branch);
            $sheet->setCellValue('E' . $rowNum, $is_act == 1 ? 'فعّال' : 'متقاعد');
            $sheet->setCellValue('F' . $rowNum, $type_label);
            $sheet->setCellValue('G' . $rowNum, $prov_label);
            $sheet->setCellValueExplicit('H' . $rowNum, (string)$def_acc, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('I' . $rowNum, (string)($def_type == 1 ? $def_iban : ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('J' . $rowNum, ($def_owner && $def_owner != $emp_name) ? $def_owner : '');
            $sheet->setCellValue('K' . $rowNum, $more_cnt > 0 ? $more_cnt : '');
            $sheet->setCellValue('L' . $rowNum, $benef_cnt > 0 ? $benef_cnt : '');

            if ($is_act != 1) {
                $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFont()->getColor()->setRGB('64748b');
            }
            $count++; $rowNum++;
        }

        // IBAN / Account No بمحاذاة LTR
        $sheet->getStyle('H2:I' . ($rowNum - 1))->getAlignment()
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

        $data['title']          = 'حسابات الصرف — ' . ($emp_data['EMP_NAME'] ?? $emp_no);
        $data['emp_no']         = $emp_no;
        $data['emp_data']       = $emp_data;
        $data['accounts']       = $this->{$this->MODEL_NAME}->accounts_list($emp_no);
        $data['beneficiaries']  = $this->{$this->MODEL_NAME}->benef_list($emp_no);
        $data['providers']      = $this->{$this->MODEL_NAME}->providers_list();
        $data['content']        = 'payment_accounts_emp';

        $this->load->view('template/template1', $data);
    }

    // ==================== ACCOUNT ENDPOINTS (AJAX) ====================
    function account_save()
    {
        $data = [
            'emp_no'         => $this->input->post('emp_no'),
            'beneficiary_id' => $this->input->post('beneficiary_id') ?: null,
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
    function branches()
    {
        $data['title']    = 'فروع البنوك';
        $data['content']  = 'payment_accounts_branches';
        $data['branches'] = $this->{$this->MODEL_NAME}->branches_list();
        $this->load->view('template/template1', $data);
    }

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
