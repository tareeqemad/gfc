<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Payment_req extends MY_Controller
{
    var $PKG_NAME       = "GFC_PAK.DISBURSEMENT_PKG";
    var $MODEL_NAME     = "Payment_req_model";
    var $PAGE_URL       = "payment_req/payment_req/get_page";

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('payroll_data/salary_dues_types_model');

        $this->approve_url       = base_url('payment_req/payment_req/approve');
        $this->pay_url           = base_url('payment_req/payment_req/do_pay');
        $this->delete_url        = base_url('payment_req/payment_req/delete');
        $this->create_url        = base_url('payment_req/payment_req/create');
        $this->approve_batch_url = base_url('payment_req/payment_req/approve_batch');
        $this->pay_batch_url     = base_url('payment_req/payment_req/pay_batch');
        $this->import_url        = base_url('payment_req/payment_req/import_excel');
    }

    // ==================== INDEX ====================
    function index($page = 1, $branch_no = -1, $the_month = -1, $emp_no = -1, $status = -1)
    {
        $data['title']     = 'صرف الرواتب والمستحقات';
        $data['content']   = 'payment_req_index';
        $data['page']      = $page;
        $data['branch_no'] = $branch_no;
        $data['the_month'] = $the_month;
        $data['emp_no']    = $emp_no;
        $data['status']    = $status;
        $this->_lookup($data, 'list');
        $this->load->view('template/template1', $data);
    }

    // ==================== PAGINATED LIST ====================
    function get_page($page = 1, $branch_no = -1, $the_month = -1, $emp_no = -1, $status = -1)
    {
        $this->load->library('pagination');

        $branch_no = $this->check_vars($branch_no, 'branch_no');
        $emp_no    = $this->check_vars($emp_no, 'emp_no');
        $the_month = $this->check_vars($the_month, 'the_month');
        $req_type  = $this->check_vars(-1, 'req_type');
        $status    = $this->check_vars($status, 'status');

        $where_sql = '';
        if ($this->user->branch == 1) {
            $where_sql .= ($branch_no != null) ? " AND EMP_PKG.GET_EMP_BRANCH(D.EMP_NO)='{$branch_no}' " : '';
        } else {
            $where_sql .= " AND EMP_PKG.GET_EMP_BRANCH(D.EMP_NO)='{$this->user->branch}' ";
        }
        $where_sql .= ($emp_no != null)                    ? " AND D.EMP_NO='{$emp_no}' "         : '';
        $where_sql .= ($the_month != null)                 ? " AND M.THE_MONTH='{$the_month}' "   : '';
        $where_sql .= ($req_type != null)                  ? " AND M.REQ_TYPE='{$req_type}' "     : '';
        if ($status !== null && $status !== '') {
            if (strpos($status, ',') !== false) {
                $where_sql .= " AND M.STATUS IN ({$status}) ";
            } else {
                $where_sql .= " AND M.STATUS='{$status}' ";
            }
        }

        $view_mode = $this->input->post('view_mode') ?: $this->input->get('view_mode') ?: 'detail';

        $totals = $this->{$this->MODEL_NAME}->get_totals($where_sql);

        if ($view_mode === 'master') {
            // ماستر: where بدون D alias
            $master_where = str_replace('D.EMP_NO', '(SELECT MIN(DD.EMP_NO) FROM GFC.PAYMENT_REQ_DETAIL_TB DD WHERE DD.REQ_ID=M.REQ_ID)', $where_sql);
            $master_where = str_replace("EMP_PKG.GET_EMP_BRANCH(D.EMP_NO)", "EMP_PKG.GET_EMP_BRANCH((SELECT MIN(DD.EMP_NO) FROM GFC.PAYMENT_REQ_DETAIL_TB DD WHERE DD.REQ_ID=M.REQ_ID))", $master_where);

            // عدد الطلبات (مش الديتيل)
            $cnt_rs = $this->get_table_count("(SELECT DISTINCT M.REQ_ID FROM GFC.PAYMENT_REQ_TB M JOIN GFC.PAYMENT_REQ_DETAIL_TB D ON D.REQ_ID=M.REQ_ID WHERE 1=1 {$where_sql}) X WHERE 1=1");
            $total_rows = (is_array($cnt_rs) && count($cnt_rs) > 0) ? intval($cnt_rs[0]['NUM_ROWS']) : 0;
        } else {
            $total_rows = (int)($totals['count'] ?? 0);
        }

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
        $row    = $page * $config['per_page'];

        if ($view_mode === 'master') {
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_master($master_where, $offset, $row);
        } else {
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
        }

        $data['offset']     = $offset + 1;
        $data['page']       = $page;
        $data['totals']     = $totals;
        $data['total_rows'] = $total_rows;
        $data['view_mode']  = $view_mode;

        $this->load->view('payment_req_page', $data);
    }

    // ==================== CREATE (master only) ====================
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(false);
            // فحص التكرار يتم داخل Oracle procedure — PAYMENT_REQ_INSERT
            $res = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if (is_numeric($res) && intval($res) > 0) { echo intval($res); }
            else { $this->print_error($res); }
        } else {
            $data['title']    = 'صرف رواتب - إنشاء طلب';
            $data['isCreate'] = true;
            $data['action']   = 'index';
            $data['content']  = 'payment_req_show';
            $this->_lookup($data);
            $this->load->view('template/template1', $data);
        }
    }

    // ==================== GET ====================
    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        if (!(count($result) == 1)) die('get');

        $data['master_tb_data'] = $result;
        $data['detail_rows']    = $this->{$this->MODEL_NAME}->get_details($id);
        $data['log_rows']       = $this->{$this->MODEL_NAME}->get_log($id);
        $data['isCreate']       = false;
        $data['can_edit']       = (isset($result[0]['STATUS']) && $result[0]['STATUS'] == 0) ? 1 : 0;
        $data['action']         = 'edit';
        $data['content']        = 'payment_req_show';
        $data['title']          = 'صرف رواتب - تفاصيل الطلب';
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    // ==================== EDIT (master only) ====================
    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            // النتيجة: "1" = تم | "1|رسالة" = تم مع تنبيه | "2|رسالة" = تغيير نوع
            $parts = explode('|', $res);
            if ($parts[0] === '1' || $parts[0] === '2') {
                echo $res;
            } else {
                $this->print_error($res);
            }
        }
    }

    // ==================== DETAIL CRUD (AJAX) ====================
    function detail_add()
    {
        $req_id     = $this->p_req_id;
        $emp_no     = $this->p_emp_no;
        $req_amount = $this->p_req_amount ?: 0;
        $note       = $this->p_note ?: '';
        if (!$req_id) { echo json_encode(['ok' => false, 'msg' => 'REQ_ID required']); return; }
        if (!$emp_no) { echo json_encode(['ok' => false, 'msg' => 'EMP_NO required']); return; }
        // فحص تكرار: الموظف في طلب آخر من نفس النوع لنفس الشهر
        $dup_req = $this->{$this->MODEL_NAME}->emp_in_other_request($emp_no, $req_id);
        if ($dup_req) {
            echo json_encode(['ok' => false, 'msg' => 'الموظف موجود في طلب آخر ('.$dup_req.') من نفس النوع لنفس الشهر']);
            return;
        }
        $res = $this->{$this->MODEL_NAME}->detail_add($req_id, $emp_no, $req_amount, $note);
        if (is_numeric($res) && intval($res) > 0) {
            echo json_encode(['ok' => true, 'detail_id' => intval($res)]);
        } else {
            echo json_encode(['ok' => false, 'msg' => $res]);
        }
    }

    function detail_update()
    {
        $detail_id  = $this->p_detail_id;
        $req_amount = $this->p_req_amount ?: 0;
        $note       = $this->p_note ?: '';
        if (!$detail_id) { echo json_encode(['ok' => false, 'msg' => 'DETAIL_ID required']); return; }
        $res = $this->{$this->MODEL_NAME}->detail_update($detail_id, $req_amount, $note);
        echo json_encode(['ok' => ($res == '1'), 'msg' => $res]);
    }

    function detail_preview_single()
    {
        $req_id     = $this->p_req_id;
        $emp_no     = $this->p_emp_no;
        $req_amount = $this->p_req_amount ?: 0;
        if (!$req_id || !$emp_no) { echo json_encode(['ok' => false, 'msg' => 'بيانات ناقصة']); return; }
        $res = $this->{$this->MODEL_NAME}->detail_preview_single($req_id, $emp_no, $req_amount);
        $parts = explode('|', $res);
        if ($parts[0] === 'ERR') {
            echo json_encode(['ok' => false, 'msg' => $parts[1] ?? 'خطأ']);
        } else {
            echo json_encode([
                'ok'          => true,
                'calc_amount' => (float)($parts[0] ?? 0),
                'dues_avail'  => (float)($parts[1] ?? 0),
                'limit_flag'  => $parts[2] ?? '',
                'sal_323'     => (float)($parts[3] ?? 0),
                'emp_name'    => $parts[4] ?? '',
            ]);
        }
    }

    function detail_delete()
    {
        $detail_id = $this->p_detail_id;
        if (!$detail_id) { echo json_encode(['ok' => false, 'msg' => 'DETAIL_ID required']); return; }
        $res = $this->{$this->MODEL_NAME}->detail_delete($detail_id);
        echo json_encode(['ok' => ($res == '1'), 'msg' => $res]);
    }

    function detail_list()
    {
        $req_id = $this->p_req_id ?: $this->input->get('req_id');
        if (!$req_id) { echo json_encode(['ok' => false]); return; }

        $rows   = $this->{$this->MODEL_NAME}->get_details($req_id);
        $master = $this->{$this->MODEL_NAME}->get($req_id);
        $logs   = $this->{$this->MODEL_NAME}->get_log($req_id);

        $master_info = (is_array($master) && count($master) > 0) ? $master[0] : [];
        $total = 0; $cnt = 0;
        if (is_array($rows)) {
            foreach ($rows as $r) {
                if ((int)($r['DETAIL_STATUS'] ?? 0) != 9) {
                    $total += (float)($r['REQ_AMOUNT'] ?? 0);
                    $cnt++;
                }
            }
        }

        echo json_encode([
            'ok'     => true,
            'data'   => is_array($rows) ? $rows : [],
            'master' => [
                'STATUS'       => $master_info['STATUS'] ?? 0,
                'STATUS_NAME'  => $master_info['STATUS_NAME'] ?? '',
                'EMP_COUNT'    => $cnt,
                'TOTAL_AMOUNT' => $total,
            ],
            'logs'   => is_array($logs) ? $logs : [],
        ]);
    }

    // ==================== APPROVE ====================
    function approve()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/approve'))) { echo 'ليس لديك صلاحية'; return; }
        $req_id = $this->p_req_id;
        if (!$req_id) { echo 'معرف الطلب مطلوب'; return; }
        echo $this->{$this->MODEL_NAME}->approve($req_id);
    }

    // ==================== PAY ====================
    function do_pay()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/do_pay'))) { echo 'ليس لديك صلاحية'; return; }
        $req_id = $this->p_req_id;
        if (!$req_id) { echo 'معرف الطلب مطلوب'; return; }
        echo $this->{$this->MODEL_NAME}->pay($req_id);
    }

    // ==================== DELETE (Cancel) ====================
    function delete()
    {
        $req_id      = $this->p_req_id;
        $cancel_note = $this->p_cancel_note;
        if (!$req_id) { echo 'معرف الطلب مطلوب'; return; }
        echo $this->{$this->MODEL_NAME}->cancel($req_id, ($cancel_note ?: 'Canceled'));
    }

    // ==================== UNAPPROVE (إلغاء الاعتماد) ====================
    function unapprove()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/unapprove'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $req_id = $this->p_req_id;
        $note   = $this->p_note ?: '';
        if (!$req_id) { echo json_encode(['ok' => false, 'msg' => 'معرف الطلب مطلوب']); return; }
        $res = $this->{$this->MODEL_NAME}->unapprove($req_id, $note);
        header('Content-Type: application/json');
        echo json_encode(['ok' => ($res == '1'), 'msg' => ($res == '1') ? 'تم إلغاء الاعتماد' : $res]);
    }

    // ==================== DELETE REQUEST (حذف فعلي) ====================
    function delete_request()
    {
        $req_id = $this->p_req_id;
        if (!$req_id) { echo json_encode(['ok' => false, 'msg' => 'معرف الطلب مطلوب']); return; }

        $res = $this->{$this->MODEL_NAME}->delete_request($req_id);

        header('Content-Type: application/json');
        if (strpos($res, '1|') === 0) {
            $parts = explode('|', $res);
            echo json_encode(['ok' => true, 'msg' => 'تم حذف الطلب و ' . ($parts[1] ?? 0) . ' موظف']);
        } else {
            echo json_encode(['ok' => false, 'msg' => $res]);
        }
    }

    // ==================== DETAIL APPROVE (partial) ====================
    function detail_approve()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/approve'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $ids = $this->p_detail_ids;
        if (!$ids) { echo json_encode(['ok' => false, 'msg' => 'لم يتم تحديد موظفين']); return; }
        $res = $this->{$this->MODEL_NAME}->detail_approve($ids);
        if ($res == '1') {
            echo json_encode(['ok' => true, 'msg' => 'تم الاعتماد']);
        } elseif (strpos($res, '1|') === 0) {
            echo json_encode(['ok' => true, 'msg' => substr($res, 2), 'partial' => true]);
        } else {
            echo json_encode(['ok' => false, 'msg' => $res]);
        }
    }

    // ==================== DETAIL PAY (partial) ====================
    function detail_pay()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/do_pay'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $ids = $this->p_detail_ids;
        if (!$ids) { echo json_encode(['ok' => false, 'msg' => 'لم يتم تحديد موظفين']); return; }
        $res = $this->{$this->MODEL_NAME}->detail_pay($ids);
        if ($res == '1') {
            echo json_encode(['ok' => true, 'msg' => 'تم الصرف']);
        } elseif (strpos($res, '1|') === 0) {
            echo json_encode(['ok' => true, 'msg' => substr($res, 2), 'partial' => true]);
        } else {
            echo json_encode(['ok' => false, 'msg' => $res]);
        }
    }

    // ==================== REPORT — تقرير شهري ====================
    function report_monthly()
    {
        $data['title']   = 'تقرير صرف الرواتب والمستحقات';
        $data['content'] = 'payment_req_report';
        $this->_lookup($data, 'list');
        $this->load->view('template/template1', $data);
    }

    function report_monthly_data()
    {
        $month_from = $this->p_month_from ?: $this->p_the_month;
        $month_to   = $this->p_month_to ?: $month_from;
        $branch_no  = ($this->user->branch == 1) ? ($this->p_branch_no ?: null) : $this->user->branch;
        $req_type   = $this->p_req_type ?: null;
        $status     = $this->p_status;

        if (!$month_from) { echo json_encode(['ok' => false, 'msg' => 'الشهر مطلوب']); return; }

        $rows = $this->{$this->MODEL_NAME}->get_report($month_from, $month_to, $branch_no, $req_type, $status);

        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : [], 'count' => count($rows)]);
    }

    function report_monthly_export()
    {
        $month_from = $this->check_vars(-1, 'month_from') ?: $this->check_vars(-1, 'the_month');
        $month_to   = $this->check_vars(-1, 'month_to') ?: $month_from;
        $branch_no  = ($this->user->branch == 1) ? $this->check_vars(-1, 'branch_no') : $this->user->branch;
        $req_type   = $this->check_vars(-1, 'req_type');
        $status     = $this->check_vars(-1, 'status');

        $rows = $this->{$this->MODEL_NAME}->get_report($month_from, $month_to, $branch_no, $req_type, $status);

        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات للتصدير"); history.back();</script>'; return;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('تقرير الصرف');
        $sheet->setRightToLeft(true);

        $headers = ['م', 'رقم الطلب', 'الشهر', 'نوع الطلب', 'رقم الموظف', 'اسم الموظف', 'المقر', 'المبلغ', 'حالة الموظف', 'حالة الطلب', 'تاريخ الصرف'];
        $col = 'A';
        foreach ($headers as $h) { $sheet->setCellValue($col . '1', $h); $col++; }
        $lastCol = chr(64 + count($headers));

        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->getColor()->setRGB('FFFFFF');

        $rowNum = 2; $count = 1;
        foreach ($rows as $row) {
            $thm = $row['THE_MONTH'] ?? '';
            if (strlen($thm) == 6) { $thm = substr($thm, 4, 2) . '/' . substr($thm, 0, 4); }
            $sheet->setCellValue('A' . $rowNum, $count);
            $sheet->setCellValue('B' . $rowNum, $row['REQ_NO'] ?? '');
            $sheet->setCellValue('C' . $rowNum, $thm);
            $sheet->setCellValue('D' . $rowNum, $row['REQ_TYPE_NAME'] ?? '');
            $sheet->setCellValue('E' . $rowNum, $row['EMP_NO'] ?? '');
            $sheet->setCellValue('F' . $rowNum, $row['EMP_NAME'] ?? '');
            $sheet->setCellValue('G' . $rowNum, $row['BRANCH_NAME'] ?? '');
            $sheet->setCellValue('H' . $rowNum, (float)($row['REQ_AMOUNT'] ?? 0));
            $sheet->setCellValue('I' . $rowNum, $row['DETAIL_STATUS_NAME'] ?? '');
            $sheet->setCellValue('J' . $rowNum, $row['STATUS_NAME'] ?? '');
            $sheet->setCellValue('K' . $rowNum, $row['PAY_DATE'] ?? '');
            $count++; $rowNum++;
        }

        $sheet->getStyle('H2:H' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        foreach (range('A', $lastCol) as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }
        $sheet->getStyle("A1:{$lastCol}" . ($rowNum - 1))->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = 'تقرير_صرف_' . ($month_from ?? date('Ym')) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== STATEMENT — كشف حساب موظف ====================
    function emp_statement($emp_no = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $this->input->is_ajax_request()) {
            $emp_no    = $this->p_emp_no ?: $emp_no;
            $month_from = $this->p_month_from ?: null;
            $month_to   = $this->p_month_to   ?: null;
            if (!$emp_no) { echo json_encode(['ok' => false, 'msg' => 'رقم الموظف مطلوب']); return; }

            $rows    = $this->{$this->MODEL_NAME}->get_emp_statement($emp_no, $month_from, $month_to);
            $summary = $this->{$this->MODEL_NAME}->get_summary($emp_no, null);

            if (is_array($rows)) {
                array_walk_recursive($rows, function (&$val) {
                    if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
                });
            }

            header('Content-Type: application/json');
            echo json_encode([
                'ok'      => true,
                'data'    => is_array($rows) ? $rows : [],
                'summary' => is_array($summary) && count($summary) > 0 ? $summary[0] : [],
                'count'   => is_array($rows) ? count($rows) : 0
            ]);
            return;
        }

        $data['title']   = 'كشف حساب موظف';
        $data['content'] = 'payment_req_emp_statement';
        $data['emp_no']  = $emp_no;
        $this->_lookup($data, 'list');
        $this->load->view('template/template1', $data);
    }

    function emp_statement_export()
    {
        $emp_no     = $this->p_emp_no;
        $month_from = $this->p_month_from ?: null;
        $month_to   = $this->p_month_to   ?: null;
        if (!$emp_no) { echo '<script>alert("رقم الموظف مطلوب"); history.back();</script>'; return; }

        $rows = $this->{$this->MODEL_NAME}->get_emp_statement($emp_no, $month_from, $month_to);

        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات"); history.back();</script>'; return;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('كشف حساب');
        $sheet->setRightToLeft(true);

        $headers = ['م', 'رقم الطلب', 'الشهر', 'نوع الطلب', 'المبلغ', 'بند الصرف', 'حالة', 'تاريخ الصرف', 'ملاحظة'];
        $col = 'A';
        foreach ($headers as $h) { $sheet->setCellValue($col . '1', $h); $col++; }
        $lastCol = chr(64 + count($headers));

        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->getColor()->setRGB('FFFFFF');

        $rowNum = 2; $count = 1;
        foreach ($rows as $row) {
            $thm = $row['THE_MONTH'] ?? '';
            if (strlen($thm) == 6) { $thm = substr($thm, 4, 2) . '/' . substr($thm, 0, 4); }
            $sheet->setCellValue('A' . $rowNum, $count);
            $sheet->setCellValue('B' . $rowNum, $row['REQ_NO'] ?? '');
            $sheet->setCellValue('C' . $rowNum, $thm);
            $sheet->setCellValue('D' . $rowNum, $row['REQ_TYPE_NAME'] ?? '');
            $sheet->setCellValue('E' . $rowNum, (float)($row['REQ_AMOUNT'] ?? 0));
            $sheet->setCellValue('F' . $rowNum, $row['PAY_TYPE_NAME'] ?? '');
            $sheet->setCellValue('G' . $rowNum, $row['DETAIL_STATUS_NAME'] ?? '');
            $sheet->setCellValue('H' . $rowNum, $row['PAY_DATE'] ?? '');
            $sheet->setCellValue('I' . $rowNum, $row['NOTE'] ?? '');
            $count++; $rowNum++;
        }

        $sheet->getStyle('E2:E' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        foreach (range('A', $lastCol) as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }

        $filename = 'كشف_حساب_' . $emp_no . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== BANK CSV ====================
    function export_bank_csv()
    {
        $req_id = $this->p_req_id;
        if (!$req_id) { echo '<script>alert("معرف الطلب مطلوب"); history.back();</script>'; return; }

        $details = $this->{$this->MODEL_NAME}->get_details($req_id);

        if (!is_array($details) || count($details) === 0) {
            echo '<script>alert("لا توجد بيانات"); history.back();</script>'; return;
        }

        $filename = 'bank_transfer_' . $req_id . '_' . date('Ymd') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo "\xEF\xBB\xBF"; // BOM for Excel UTF-8
        $out = fopen('php://output', 'w');
        fputcsv($out, ['#', 'رقم الموظف', 'اسم الموظف', 'المقر', 'المبلغ', 'الحالة', 'ملاحظة']);

        $n = 0;
        foreach ($details as $d) {
            $st = (int)($d['DETAIL_STATUS'] ?? 0);
            if ($st == 9) continue;
            $n++;
            $stNames = [0 => 'مسودة', 1 => 'معتمد', 2 => 'منفّذ للصرف', 4 => 'محتسب'];
            fputcsv($out, [
                $n,
                $d['EMP_NO']      ?? '',
                $d['EMP_NAME']    ?? '',
                $d['BRANCH_NAME'] ?? '',
                number_format((float)($d['REQ_AMOUNT'] ?? 0), 2, '.', ''),
                $stNames[$st] ?? $st,
                $d['NOTE'] ?? ''
            ]);
        }
        fclose($out);
        exit;
    }

    // ==================== SUMMARY (AJAX) ====================
    function public_get_summary()
    {
        $emp_no    = $this->p_emp_no;
        $the_month = $this->p_the_month;
        if (!$emp_no) { echo json_encode(['ok' => false, 'msg' => 'رقم الموظف مطلوب']); return; }
        $the_month = ($the_month != '' && $the_month != null) ? $the_month : null;
        $result    = $this->{$this->MODEL_NAME}->get_summary($emp_no, $the_month);
        echo json_encode(['ok' => true, 'data' => $result]);
    }

    // ==================== APPROVE BATCH ====================
    function approve_batch()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/approve'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $the_month = $this->p_the_month;
        $req_ids   = $this->p_req_ids ?: null;
        if (!$the_month) { echo json_encode(['ok' => false, 'msg' => 'الشهر مطلوب']); return; }
        $result = $this->{$this->MODEL_NAME}->approve_batch($the_month, $req_ids);
        echo json_encode($result);
    }

    // ==================== PAY BATCH ====================
    function pay_batch()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/do_pay'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $the_month = $this->p_the_month;
        $req_ids   = $this->p_req_ids ?: null;
        if (!$the_month) { echo json_encode(['ok' => false, 'msg' => 'الشهر مطلوب']); return; }
        $result = $this->{$this->MODEL_NAME}->pay_batch($the_month, $req_ids);
        echo json_encode($result);
    }

    // ==================== تجهيز الصرف — الشاشة ====================
    function batch()
    {
        $data['title']   = 'احتساب الصرف';
        $data['content'] = 'payment_req_batch';
        $this->_lookup($data, 'minimal');
        $this->load->view('template/template1', $data);
    }

    // ==================== سجل الدفعات ====================
    function batch_history()
    {
        $data['title']   = 'سجل الدفعات';
        $data['content'] = 'payment_req_batch_history';
        $data['batch_rows'] = $this->{$this->MODEL_NAME}->batch_history_list();
        $this->load->model('settings/constant_details_model');
        $data['batch_status_cons'] = $this->constant_details_model->get_list(542);
        $this->_lookup($data, 'minimal');
        $this->load->view('template/template1', $data);
    }

    function batch_history_json()
    {
        $rows = $this->{$this->MODEL_NAME}->batch_history_list();
        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'rows' => is_array($rows) ? $rows : []]);
    }

    function batch_detail($id)
    {
        $batch_rows = $this->{$this->MODEL_NAME}->batch_history_list();
        $batch_info = null;
        if (is_array($batch_rows)) {
            foreach ($batch_rows as $b) {
                if ((int)($b['BATCH_ID'] ?? 0) == (int)$id) { $batch_info = $b; break; }
            }
        }
        if (!$batch_info) { redirect('payment_req/payment_req/batch_history'); return; }

        $detail_rows = $this->{$this->MODEL_NAME}->batch_history_get((int)$id);
        if (is_array($detail_rows)) {
            array_walk_recursive($detail_rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        // جلب بيانات الطلبات المشمولة (الشهور + الأنواع)
        $req_ids_str = $batch_info['REQ_IDS'] ?? '';
        $batch_reqs = [];
        if ($req_ids_str) {
            $where = " AND M.REQ_ID IN ($req_ids_str) ";
            $reqs = $this->{$this->MODEL_NAME}->get_list_master($where, 0, 100);
            if (is_array($reqs)) {
                array_walk_recursive($reqs, function (&$val) {
                    if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
                });
                $batch_reqs = $reqs;
            }
        }

        $this->load->model('settings/constant_details_model');
        $data['title']       = 'تفاصيل الدفعة — ' . ($batch_info['BATCH_NO'] ?? $id);
        $data['content']     = 'payment_req_batch_detail';
        $data['batch_info']  = $batch_info;
        $data['detail_rows'] = is_array($detail_rows) ? $detail_rows : [];
        $data['batch_reqs']  = $batch_reqs;
        $data['batch_status_cons'] = $this->constant_details_model->get_list(542);
        $this->_lookup($data, 'minimal');
        $this->load->view('template/template1', $data);
    }

    function batch_history_data($id)
    {
        $rows = $this->{$this->MODEL_NAME}->batch_history_get((int)$id);
        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    function batch_emp_accounts_json()
    {
        $batch_id = (int)$this->input->get_post('batch_id');
        $emp_no   = (int)$this->input->get_post('emp_no');
        $rows = $this->{$this->MODEL_NAME}->batch_emp_accounts($batch_id, $emp_no);
        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
    }

    function batch_reverse_pay_action()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/batch_reverse_pay_action'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $batch_id = (int)$this->input->post('batch_id');
        if (!$batch_id) { echo json_encode(['ok' => false, 'msg' => 'رقم الدفعة مطلوب']); return; }
        $res = $this->{$this->MODEL_NAME}->batch_reverse_pay($batch_id);
        $parts = explode('|', $res);
        header('Content-Type: application/json');
        if ($parts[0] === '1') {
            echo json_encode(['ok' => true, 'msg' => $parts[1] ?? 'تم عكس الصرف']);
        } else {
            echo json_encode(['ok' => false, 'msg' => $res]);
        }
    }

    function batch_cancel_action()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/batch_cancel_action'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $batch_id = (int)$this->input->post('batch_id');
        if (!$batch_id) { echo json_encode(['ok' => false, 'msg' => 'رقم الدفعة مطلوب']); return; }
        $res = $this->{$this->MODEL_NAME}->batch_cancel($batch_id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => ($res == '1'), 'msg' => ($res == '1') ? 'تم فك الاحتساب' : $res]);
    }

    // ==================== احتساب الصرف — بيانات المعاينة ====================
    function batch_data()
    {
        $req_ids = $this->p_req_ids;
        if (!$req_ids) { echo json_encode(['ok' => false, 'msg' => 'يجب تحديد طلبات']); return; }

        $result = $this->{$this->MODEL_NAME}->batch_preview($req_ids);

        // تحذيرات: طلبات فيها موظفين غير معتمدين
        $warnings = $this->{$this->MODEL_NAME}->batch_warnings($req_ids);

        if (is_array($result['rows'])) {
            array_walk_recursive($result['rows'], function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }
        $result['warnings'] = $warnings;
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    // ==================== تجهيز الصرف — اعتماد التجهيز ====================
    function batch_confirm_action()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/batch_confirm_action'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $req_ids = $this->input->post('req_ids') ?: $this->p_req_ids;
        $exclude_ids = $this->input->post('exclude_detail_ids') ?: '';
        // طريقة الصرف: 1=قديم (افتراضي) | 2=جديد (PAYMENT_ACCOUNTS + split)
        $disburse_method = (int)($this->input->post('disburse_method') ?: 1);
        if (!$req_ids) { echo json_encode(['ok' => false, 'msg' => 'يجب تحديد طلبات']); return; }

        $result = $this->{$this->MODEL_NAME}->batch_confirm($req_ids, $exclude_ids, $disburse_method);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    // ==================== تجهيز الصرف — تنفيذ الصرف ====================
    function batch_pay_action()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/batch_pay_action'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $batch_id = (int)$this->input->post('batch_id');
        if (!$batch_id) { echo json_encode(['ok' => false, 'msg' => 'رقم الدفعة مطلوب']); return; }

        $result = $this->{$this->MODEL_NAME}->batch_pay($batch_id);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    // ==================== تجهيز الصرف — تصدير CSV ====================
    function batch_export_csv()
    {
        $req_ids = $this->input->get('req_ids') ?: $this->p_req_ids;
        if (!$req_ids) { echo '<script>alert("يجب تحديد طلبات"); history.back();</script>'; return; }

        $result = $this->{$this->MODEL_NAME}->batch_preview($req_ids);

        $rows = $result['rows'] ?? [];
        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات"); history.back();</script>'; return;
        }

        $filename = 'كشف_احتساب_الصرف_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo "\xEF\xBB\xBF";
        $out = fopen('php://output', 'w');
        fputcsv($out, ['#', 'رقم الموظف', 'اسم الموظف', 'المقر', 'البنك الرئيسي', 'فرع البنك', 'IBAN', 'رقم الحساب', 'رقم الطلب', 'نوع الطلب', 'الشهر', 'المبلغ']);

        $n = 0;
        foreach ($rows as $d) {
            $n++;
            fputcsv($out, [
                $n,
                $d['EMP_NO']              ?? '',
                $d['EMP_NAME']            ?? '',
                $d['BRANCH_NAME']         ?? '',
                $d['MASTER_BANK_NAME']    ?? '',
                $d['BANK_NAME']           ?? '',
                $d['IBAN']                ?? '',
                $d['BANK_ACCOUNT']        ?? $d['ACCOUNT_BANK_EMAIL'] ?? '',
                $d['REQ_NO']              ?? '',
                $d['REQ_TYPE_NAME']       ?? '',
                $d['THE_MONTH']           ?? '',
                number_format((float)($d['REQ_AMOUNT'] ?? 0), 2, '.', ''),
            ]);
        }
        fclose($out);
        exit;
    }

    // ==================== تصدير ملف البنك — Excel ====================
    // قائمة البنوك بالدفعة (AJAX)
    function export_bank_list()
    {
        $batch_id = (int)($this->input->get('batch_id') ?: $this->input->post('batch_id') ?: $this->p_batch_id);
        if (!$batch_id) { header('Content-Type: application/json'); echo json_encode(['ok' => false, 'msg' => 'رقم الدفعة مطلوب']); return; }

        $rows = $this->{$this->MODEL_NAME}->batch_bank_export($batch_id, null);
        if (!is_array($rows) || count($rows) === 0) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'لا توجد بيانات — تأكد من تنفيذ procedure BATCH_BANK_EXPORT']); return;
        }

        $banks = [];
        foreach ($rows as $r) {
            $bk = $r['MASTER_BANK_NO'] ?? 0;
            if (!isset($banks[$bk])) $banks[$bk] = ['no' => $bk, 'name' => $r['MASTER_BANK_NAME'] ?? 'غير محدد', 'count' => 0, 'total' => 0];
            $banks[$bk]['count']++;
            $banks[$bk]['total'] += (float)($r['TOTAL_AMOUNT'] ?? 0);
        }

        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'banks' => array_values($banks)]);
    }

    // تصدير ملف بنك واحد (Excel)
    function export_bank_file()
    {
        $batch_id       = (int)($this->input->get('batch_id') ?: $this->p_batch_id);
        $master_bank_no = (int)($this->input->get('master_bank_no') ?: $this->p_master_bank_no);

        $rows = $this->{$this->MODEL_NAME}->batch_bank_export($batch_id, $master_bank_no ?: null);
        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات"); history.back();</script>'; return;
        }

        // النموذج 2: بنك فلسطين (89)، الإسلامي العربي (30)، القدس (82)
        $format2_banks = [89, 30, 82];
        $company_iban = [
            89 => 'PS31PALS045105274280991604000',
            30 => 'PS31PALS045105274280991604000',
            82 => 'PS50ALDN060200634000420010000',
        ];

        $batchNo = $rows[0]['BATCH_NO'] ?? $batch_id;

        // تجميع حسب البنك
        $banks = [];
        foreach ($rows as $r) {
            $bk = $r['MASTER_BANK_NO'] ?? 0;
            if (!isset($banks[$bk])) $banks[$bk] = ['name' => $r['MASTER_BANK_NAME'] ?? 'غير محدد', 'rows' => []];
            $banks[$bk]['rows'][] = $r;
        }

        // helper: بناء sheet لبنك واحد
        $buildSheet = function($sheet, $bkNo, $empRows) use ($format2_banks, $company_iban) {
            $sheet->setRightToLeft(true);
            if (in_array($bkNo, $format2_banks)) {
                $cIban = $company_iban[$bkNo] ?? '';
                $sheet->setCellValue('A1', 'اسم الشركة');
                $sheet->setCellValue('D1', 'شركة توزيع كهرباء محافظات  غزة');
                $sheet->setCellValue('A2', 'ايبان الشركة');
                $sheet->setCellValue('D2', $cIban);
                $sheet->setCellValue('A3', 'الملاحظات');
                // شهر الصرف: PAY_DATE لو منفّذ، الشهر الحالي لو محتسب
                $payDate = !empty($empRows[0]['PAY_DATE']) ? $empRows[0]['PAY_DATE'] : date('Ymd');
                $sheet->setCellValueExplicit('D3', date('Ym', strtotime($payDate)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValue('B4', 'تفاصيل الحوالة');
                $sheet->setCellValue('A5', 'الترتيب');
                $sheet->setCellValue('B5', 'اسم المستفيد');
                $sheet->setCellValue('C5', 'رقم بطاقة التعريف');
                $sheet->setCellValue('D5', 'ايبان المستفيد');
                $sheet->setCellValue('E5', 'المبلغ');
                $sheet->setCellValue('F5', 'العملة');
                $row = 6; $n = 0;
                foreach ($empRows as $r) {
                    $n++;
                    // المرسَل للبنك = صاحب الحساب الفعلي (موظف أو مستفيد)
                    // OWNER_NAME/OWNER_ID_NO يفولوا على EMP لو الطريقة قديمة (في الـ view)
                    $owner_name = $r['OWNER_NAME'] ?? $r['EMP_NAME'] ?? '';
                    $owner_id   = $r['OWNER_ID_NO'] ?? $r['EMP_ID'] ?? '';
                    $sheet->setCellValue('A' . $row, $n);
                    $sheet->setCellValue('B' . $row, $owner_name);
                    $sheet->setCellValueExplicit('C' . $row, (string)$owner_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $row, (string)($r['IBAN'] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('E' . $row, (float)($r['TOTAL_AMOUNT'] ?? 0));
                    $sheet->setCellValue('F' . $row, 'ILS');
                    $row++;
                }
                if ($row > 6) $sheet->getStyle('E6:E' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
            } else {
                $sheet->setCellValue('A1', 'ID_NO1');
                $sheet->setCellValue('B1', 'EMP_NAME');
                $sheet->setCellValue('C1', 'ACNT_NO');
                $sheet->setCellValue('D1', 'BRANCH_NO');
                $sheet->setCellValue('E1', 'NET_EARN');
                $sheet->setCellValue('F1', 'iban');
                $row = 2;
                foreach ($empRows as $r) {
                    $owner_name = $r['OWNER_NAME'] ?? $r['EMP_NAME'] ?? '';
                    $owner_id   = $r['OWNER_ID_NO'] ?? $r['EMP_ID'] ?? '';
                    $sheet->setCellValueExplicit('A' . $row, (string)$owner_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('B' . $row, $owner_name);
                    $sheet->setCellValueExplicit('C' . $row, (string)($r['BANK_ACCOUNT'] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('D' . $row, $r['BANK_NO'] ?? 0);
                    $sheet->setCellValue('E' . $row, (float)($r['TOTAL_AMOUNT'] ?? 0));
                    $sheet->setCellValueExplicit('F' . $row, (string)($r['IBAN'] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $row++;
                }
                if ($row > 2) $sheet->getStyle('E2:E' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
            }
            foreach (range('A', 'F') as $c) $sheet->getColumnDimension($c)->setAutoSize(true);
        };

        if ($master_bank_no && count($banks) === 1) {
            // بنك واحد → ملف واحد بـ sheet واحد
            $bkNo = array_key_first($banks);
            $bkData = $banks[$bkNo];
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle(mb_substr($bkData['name'], 0, 31));
            $buildSheet($sheet, $bkNo, $bkData['rows']);
            $filename = $bkData['name'] . '_' . $batchNo . '_' . date('Ymd') . '.xlsx';
        } else {
            // كل البنوك → ملف فيه sheet لكل بنك
            $spreadsheet = new Spreadsheet();
            $spreadsheet->removeSheetByIndex(0);
            foreach ($banks as $bkNo => $bkData) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle(mb_substr($bkData['name'], 0, 31));
                $buildSheet($sheet, $bkNo, $bkData['rows']);
            }
            $filename = 'تصدير_بنوك_' . $batchNo . '_' . date('Ymd') . '.xlsx';
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== TOTALS (AJAX) ====================
    function get_totals()
    {
        $the_month = $this->p_the_month;
        $emp_no    = $this->p_emp_no;
        $req_type  = $this->p_req_type;
        $status    = $this->p_status;
        $branch_no = $this->p_branch_no;

        $has_filter = ($the_month || $emp_no || ($status !== null && $status !== '') || $branch_no || $req_type);
        if (!$has_filter) {
            echo json_encode(['ok' => false, 'msg' => 'يجب تحديد فلتر']); return;
        }

        $where_sql = '';
        if ($this->user->branch == 1) {
            if ($branch_no) $where_sql .= " AND EMP_PKG.GET_EMP_BRANCH(D.EMP_NO)='{$branch_no}' ";
        } else {
            $where_sql .= " AND EMP_PKG.GET_EMP_BRANCH(D.EMP_NO)='{$this->user->branch}' ";
        }
        if ($emp_no)    $where_sql .= " AND D.EMP_NO='{$emp_no}' ";
        if ($the_month) $where_sql .= " AND M.THE_MONTH='{$the_month}' ";
        if ($req_type)  $where_sql .= " AND M.REQ_TYPE='{$req_type}' ";
        if ($status !== null && $status !== '') $where_sql .= " AND M.STATUS='{$status}' ";

        $result = $this->{$this->MODEL_NAME}->get_totals($where_sql);
        $branches_data = [];
        if ($this->user->branch == 1 && !$branch_no) {
            $br = $this->{$this->MODEL_NAME}->get_totals_branch($where_sql);
            if (isset($br['ok']) && $br['ok']) { $branches_data = $br['branches']; }
        }

        $result['branches'] = $branches_data;
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    // ==================== CHECK MONTH STATUS ====================
    function check_month_status()
    {
        $month = intval($this->input->post('month'));
        if (!$month) { echo json_encode(['status' => 'error']); return; }

        $r = $this->{$this->MODEL_NAME}->get_month_status($month);

        $emp_count  = intval($r['EMP_COUNT'] ?? 0);
        $total_net  = floatval($r['TOTAL_NET'] ?? 0);
        $total_323  = floatval($r['TOTAL_323'] ?? 0);
        $total_draft    = floatval($r['TOTAL_DRAFT'] ?? 0);
        $total_approved = floatval($r['TOTAL_APPROVED'] ?? 0);
        $total_paid     = floatval($r['TOTAL_PAID'] ?? 0);

        header('Content-Type: application/json');
        echo json_encode([
            'status'          => 'success',
            'calculated'      => ($emp_count > 0),
            'emp_count'       => $emp_count,
            'total_net'       => $total_net,
            'total_323'       => $total_323,
            'source'          => $r['SOURCE'] ?? 'none',
            'total_draft'     => $total_draft,
            'total_approved'  => $total_approved,
            'total_paid'      => $total_paid,
            'total_available' => max($total_323 - $total_draft - $total_approved - $total_paid, 0),
            'req_count'       => intval($r['REQ_COUNT'] ?? 0)
        ]);
    }

    // ==================== BULK PREVIEW ====================
    function bulk_preview()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/bulk_create'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $params = $this->_bulk_params();

        $result = $this->{$this->MODEL_NAME}->bulk_preview($params);

        $msg  = $result['msg']  ?? '';
        $rows = $result['rows'] ?? [];

        if ($msg !== '1' && $msg !== '') {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => $msg]); return;
        }

        if (is_array($rows) && count($rows) > 0) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        header('Content-Type: application/json');
        $json = json_encode([
            'ok'    => true, 'data'  => $rows,
            'count' => count($rows), 'params' => $params,
        ]);
        echo $json ?: json_encode(['ok' => false, 'msg' => json_last_error_msg()]);
    }

    // ==================== BULK CREATE ====================
    function bulk_create()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/bulk_create'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        $params         = $this->_bulk_params();
        $params['note'] = $this->p_note ?: 'bulk';
        // فحص التكرار يتم داخل Oracle procedure — PAYMENT_REQ_INSERT
        $result = $this->{$this->MODEL_NAME}->bulk_create($params);
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    // ==================== EXPORT EXCEL ====================
    function export_excel()
    {
        $branch_no = $this->check_vars(-1, 'branch_no');
        $emp_no    = $this->check_vars(-1, 'emp_no');
        $the_month = $this->check_vars(-1, 'the_month');
        $req_type  = $this->check_vars(-1, 'req_type');
        $status    = $this->check_vars(-1, 'status');

        $where_sql = '';
        if ($this->user->branch == 1) {
            $where_sql .= ($branch_no != null) ? " AND EMP_PKG.GET_EMP_BRANCH(D.EMP_NO)='{$branch_no}'" : '';
        } else {
            $where_sql .= " AND EMP_PKG.GET_EMP_BRANCH(D.EMP_NO)='{$this->user->branch}'";
        }
        $where_sql .= ($emp_no != null)                    ? " AND D.EMP_NO='{$emp_no}'" : '';
        $where_sql .= ($the_month != null)                 ? " AND M.THE_MONTH='{$the_month}'" : '';
        $where_sql .= ($req_type != null)                  ? " AND M.REQ_TYPE='{$req_type}'" : '';
        $where_sql .= ($status !== null && $status !== '')  ? " AND M.STATUS='{$status}'" : '';

        $rows = $this->{$this->MODEL_NAME}->get_list($where_sql, 0, 999999);

        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات للتصدير"); history.back();</script>'; return;
        }

        $status_map = [0 => 'مسودة', 1 => 'معتمد', 2 => 'منفّذ للصرف', 4 => 'محتسب', 9 => 'ملغى'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('طلبات الصرف');
        $sheet->setRightToLeft(true);

        $headers = ['م', 'رقم الطلب', 'رقم الموظف', 'اسم الموظف', 'المقر', 'الشهر', 'نوع الطلب', 'المبلغ', 'الحالة'];
        $lastCol = chr(64 + count($headers));
        $col = 'A';
        foreach ($headers as $h) { $sheet->setCellValue($col . '1', $h); $col++; }

        $sheet->getStyle("A1:{$lastCol}1")->getFont()->setBold(true);
        $sheet->getStyle("A1:{$lastCol}1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle("A1:{$lastCol}1")->getFont()->getColor()->setRGB('FFFFFF');

        $rowNum = 2; $count = 1;
        foreach ($rows as $row) {
            $st  = (int)($row['STATUS'] ?? 0);
            $thm = $row['THE_MONTH'] ?? '';
            if (strlen($thm) == 6) { $thm = substr($thm, 4, 2) . '/' . substr($thm, 0, 4); }

            $sheet->setCellValue('A' . $rowNum, $count);
            $sheet->setCellValue('B' . $rowNum, $row['REQ_NO'] ?? '');
            $sheet->setCellValue('C' . $rowNum, $row['EMP_NO'] ?? '');
            $sheet->setCellValue('D' . $rowNum, $row['EMP_NAME'] ?? '');
            $sheet->setCellValue('E' . $rowNum, $row['BRANCH_NAME'] ?? '');
            $sheet->setCellValue('F' . $rowNum, $thm);
            $sheet->setCellValue('G' . $rowNum, $row['REQ_TYPE_NAME'] ?? '');
            $sheet->setCellValue('H' . $rowNum, (float)($row['REQ_AMOUNT'] ?? 0));
            $sheet->setCellValue('I' . $rowNum, $row['STATUS_NAME'] ?? ($status_map[$st] ?? ''));

            if ($st == 9) { $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->getFont()->getColor()->setRGB('dc2626'); }
            $count++; $rowNum++;
        }

        $sheet->getStyle('H2:H' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        foreach (range('A', $lastCol) as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }
        $sheet->getStyle("A1:{$lastCol}" . ($rowNum - 1))->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = 'طلبات_صرف_' . ($the_month ?? date('Ym')) . '_' . date('His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== BULK EXPORT EXCEL ====================
    function export_bulk_preview()
    {
        $params = $this->_bulk_params();
        $result = $this->{$this->MODEL_NAME}->bulk_preview($params);

        $rows = $result['rows'] ?? [];
        if (!is_array($rows) || count($rows) === 0) {
            echo '<script>alert("لا توجد بيانات للتصدير"); history.back();</script>'; return;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('معاينة جماعي');
        $sheet->setRightToLeft(true);

        $headers = ['م', 'رقم الموظف', 'اسم الموظف', 'المقر', 'صافي الراتب', 'المبلغ المحتسب', 'مبلغ الصرف', 'موجود مسبقا'];
        $col = 'A';
        foreach ($headers as $h) { $sheet->setCellValue($col . '1', $h); $col++; }

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e293b');
        $sheet->getStyle('A1:H1')->getFont()->getColor()->setRGB('FFFFFF');

        $rowNum = 2; $count = 1;
        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $rowNum, $count);
            $sheet->setCellValue('B' . $rowNum, $row['EMP_NO'] ?? '');
            $sheet->setCellValue('C' . $rowNum, $row['EMP_NAME'] ?? '');
            $sheet->setCellValue('D' . $rowNum, $row['BRANCH_NAME'] ?? '');
            $sheet->setCellValue('E' . $rowNum, (float)($row['NET_SALARY'] ?? 0));
            $sheet->setCellValue('F' . $rowNum, (float)($row['CALC_AMOUNT'] ?? 0));
            $sheet->setCellValue('G' . $rowNum, (float)($row['CALC_AMOUNT'] ?? 0));
            $sheet->setCellValue('H' . $rowNum, ((int)($row['HAS_EXISTING'] ?? 0)) > 0 ? 'نعم' : 'لا');
            $count++; $rowNum++;
        }

        $sheet->getStyle('E2:G' . ($rowNum - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        foreach (range('A', 'H') as $c) { $sheet->getColumnDimension($c)->setAutoSize(true); }
        $sheet->getStyle('A1:H' . ($rowNum - 1))->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $filename = 'معاينة_جماعي_' . ($params['the_month'] ?? date('Ym')) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== IMPORT ====================
    private function _import_validate_params()
    {
        $the_month   = trim((string)$this->p_the_month);
        $req_type    = trim((string)$this->p_req_type);
        if ($the_month === '' || strlen($the_month) != 6 || !ctype_digit($the_month)) return 'يجب ادخال الشهر بصيغة YYYYMM';
        if (!$req_type)    return 'يجب اختيار نوع الطلب';
        return null;
    }

    private function _import_upload_file()
    {
        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] != 0) return [null, 'يجب اختيار ملف Excel'];
        $ext = strtolower(pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx','xls','csv'], true)) return [null, 'نوع الملف غير مسموح'];
        $config['upload_path'] = FCPATH . 'uploads/tmp/';
        $config['allowed_types'] = '*'; $config['max_size'] = 10240; $config['encrypt_name'] = true;
        if (!is_dir($config['upload_path'])) @mkdir($config['upload_path'], 0777, true);
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('excel_file')) return [null, $this->upload->display_errors('', '')];
        return [$this->upload->data('full_path'), null];
    }

    private function _import_parse_file($fullPath, $note, $req_type = null, $the_month = null)
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fullPath);
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        } catch (\Exception $e) { return [null, null, 'فشل قراءة الملف: ' . $e->getMessage()]; }
        if (count($rows) < 2) return [null, null, 'الملف فارغ'];

        $isFullSalary = ((int)$req_type === 1);
        $items = []; $errors = []; $rowNum = 0;

        foreach ($rows as $r) {
            $rowNum++;
            if ($rowNum == 1) continue;
            $empNo = isset($r['A']) ? trim((string)$r['A']) : '';
            if ($isFullSalary) {
                $rowNote = isset($r['B']) ? trim((string)$r['B']) : '';
                if ($empNo === '') continue;
            } else {
                $amount  = isset($r['B']) ? trim((string)$r['B']) : '';
                $rowNote = isset($r['C']) ? trim((string)$r['C']) : '';
                if ($empNo === '' && $amount === '') continue;
            }
            if ($empNo === '' || !ctype_digit($empNo)) { $errors[] = "صف {$rowNum}: رقم الموظف غير صحيح"; continue; }
            if ($isFullSalary) {
                $items[] = ['row' => $rowNum, 'EMP_NO' => (int)$empNo, 'REQ_AMOUNT' => 0, 'NOTE' => $rowNote ?: $note];
            } else {
                $amountNum = floatval(str_replace(',', '', $amount));
                if ($amountNum <= 0) { $errors[] = "صف {$rowNum}: المبلغ يجب أن يكون أكبر من صفر"; continue; }
                $items[] = ['row' => $rowNum, 'EMP_NO' => (int)$empNo, 'REQ_AMOUNT' => $amountNum, 'NOTE' => $rowNote ?: $note];
            }
        }
        return [$items, $errors, null];
    }

    function import_excel()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/import_excel'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['ok' => false, 'msg' => 'Invalid request']); return;
        }
        $err = $this->_import_validate_params();
        if ($err) { echo json_encode(['ok' => false, 'msg' => $err]); return; }

        $the_month   = trim((string)$this->p_the_month);
        $req_type    = trim((string)$this->p_req_type);
        $note        = $this->p_note ?: 'استيراد Excel';

        $file_token = $this->input->post('file_token');
        $fullPath   = null;
        if ($file_token) {
            $tmpPath = FCPATH . 'uploads/tmp/' . basename($file_token);
            if (!file_exists($tmpPath)) { echo json_encode(['ok' => false, 'msg' => 'انتهت صلاحية الملف']); return; }
            $fullPath = $tmpPath;
        } else {
            list($fullPath, $uploadErr) = $this->_import_upload_file();
            if ($uploadErr) { echo json_encode(['ok' => false, 'msg' => $uploadErr]); return; }
        }

        list($items, $parseErrors, $parseErr) = $this->_import_parse_file($fullPath, $note, $req_type, $the_month);
        @unlink($fullPath);
        if ($parseErr) { echo json_encode(['ok' => false, 'msg' => $parseErr]); return; }
        if (empty($items)) { echo json_encode(['ok' => false, 'msg' => 'لا يوجد بيانات صالحة', 'parse_errors' => $parseErrors]); return; }

        // 1. الطلب — موجود أو جديد
        $req_id = $this->p_req_id ? (int)$this->p_req_id : 0;
        if ($req_id <= 0) {
            // إنشاء ماستر جديد
            $masterData = array(
                array('name' => 'THE_MONTH',   'value' => $the_month,   'type' => '', 'length' => -1),
                array('name' => 'REQ_TYPE',    'value' => $req_type,    'type' => '', 'length' => -1),
                array('name' => 'CALC_METHOD', 'value' => null,         'type' => '', 'length' => -1),
                array('name' => 'PERCENT_VAL', 'value' => null,         'type' => '', 'length' => -1),
                array('name' => 'L_VALUE',     'value' => null,         'type' => '', 'length' => -1),
                array('name' => 'H_VALUE',     'value' => null,         'type' => '', 'length' => -1),
                array('name' => 'PAY_TYPE',    'value' => null,         'type' => '', 'length' => -1),
                array('name' => 'ENTRY_DATE',  'value' => null,         'type' => '', 'length' => -1),
                array('name' => 'NOTE',        'value' => $note,        'type' => '', 'length' => -1),
            );
            $masterRes = $this->{$this->MODEL_NAME}->create($masterData);
            if (!is_numeric($masterRes) || (int)$masterRes <= 0) {
                echo json_encode(['ok' => false, 'msg' => 'فشل إنشاء الطلب: ' . $masterRes]); return;
            }
            $req_id = (int)$masterRes;
        }

        // 2. Add details
        $inserted = 0; $insertErrors = [];
        foreach ($items as $it) {
            $res = $this->{$this->MODEL_NAME}->detail_add($req_id, $it['EMP_NO'], $it['REQ_AMOUNT'], $it['NOTE']);
            if (is_numeric($res) && (int)$res > 0) { $inserted++; }
            else { $insertErrors[] = ['row' => $it['row'], 'EMP_NO' => $it['EMP_NO'], 'msg' => mb_substr($res, 0, 200)]; }
        }

        echo json_encode([
            'ok' => true, 'req_id' => $req_id, 'inserted' => $inserted,
            'total_in_file' => count($items), 'parse_errors' => $parseErrors, 'insert_errors' => $insertErrors,
        ]);
    }

    function import_preview()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/import_excel'))) {
            echo json_encode(['ok' => false, 'msg' => 'ليس لديك صلاحية']); return;
        }
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['ok' => false, 'msg' => 'Invalid request']); return;
        }
        $err = $this->_import_validate_params();
        if ($err) { echo json_encode(['ok' => false, 'msg' => $err]); return; }

        list($fullPath, $uploadErr) = $this->_import_upload_file();
        if ($uploadErr) { echo json_encode(['ok' => false, 'msg' => $uploadErr]); return; }

        $the_month = trim((string)$this->p_the_month);
        $req_type  = trim((string)$this->p_req_type);
        $note      = $this->p_note ?: 'استيراد Excel';
        list($items, $parseErrors, $parseErr) = $this->_import_parse_file($fullPath, $note, $req_type, $the_month);

        if ($parseErr) { @unlink($fullPath); echo json_encode(['ok' => false, 'msg' => $parseErr]); return; }
        if (empty($items)) { @unlink($fullPath); echo json_encode(['ok' => false, 'msg' => 'لا يوجد بيانات صالحة', 'parse_errors' => $parseErrors]); return; }

        $file_token = basename($fullPath);
        echo json_encode([
            'ok' => true, 'file_token' => $file_token,
            'valid_count' => count($items), 'error_count' => count($parseErrors),
            'total_rows' => count($items) + count($parseErrors),
            'items' => $items, 'parse_errors' => $parseErrors,
        ]);
    }

    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('استيراد طلبات صرف');
        $sheet->setCellValue('A1', 'EMP_NO'); $sheet->setCellValue('B1', 'REQ_AMOUNT'); $sheet->setCellValue('C1', 'NOTE');
        $sheet->setCellValue('A2', 12345); $sheet->setCellValue('B2', 500.00); $sheet->setCellValue('C2', 'دفعة');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        foreach (range('A', 'C') as $c) $sheet->getColumnDimension($c)->setAutoSize(true);
        $filename = 'قالب_استيراد_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== HELPERS ====================
    private function _bulk_params()
    {
        return array(
            'the_month'   => $this->p_the_month,
            'req_type'    => $this->p_req_type,
            'calc_method' => $this->p_calc_method ?: null,
            'percent_val' => $this->p_percent_val ?: null,
            'req_amount'  => $this->p_req_amount  ?: null,
            'pay_type'    => $this->p_pay_type    ?: null,
            'sal_from'    => $this->p_sal_from    ?: null,
            'sal_to'      => $this->p_sal_to      ?: null,
            'branch_no'   => $this->p_branch_no   ?: null,
            'l_value'     => $this->p_l_value     ?: null,
            'h_value'     => $this->p_h_value     ?: null,
            'entry_date'  => $this->p_entry_date  ?: null,
        );
    }

    function check_vars($var, $c_var)
    {
        $p_var = $this->{'p_'.$c_var};
        $var = ($p_var) ? $p_var : $var;
        $var = $var == -1 ? null : $var;
        return $var;
    }

    // ==================== بنود الاستحقاقات (نوع 5) ====================
    function get_benefit_items_json()
    {
        $rows = $this->{$this->MODEL_NAME}->get_benefit_items();
        header('Content-Type: application/json');
        echo json_encode(is_array($rows) ? $rows : []);
    }

    function _post_validation($isEdit = false)
    {
        if ($isEdit && !$this->p_req_id) $this->print_error('REQ_ID مطلوب للتعديل');
        if (!$this->p_req_type)           $this->print_error('يجب اختيار نوع الطلب');
        if (!$this->p_the_month)          $this->print_error('الشهر مطلوب');

        $rt = intval($this->p_req_type);

        // نوع 1: راتب كامل
        if ($rt == 1) {
            if (!$this->p_pay_type) $this->print_error('بند المستحقات مطلوب');
        }
        // نوع 2: دفعة من الراتب — نسبة فقط
        if ($rt == 2) {
            if (!$this->p_pay_type)    $this->print_error('بند المستحقات مطلوب');
            if (!$this->p_percent_val) $this->print_error('النسبة مطلوبة');
            if ($this->p_l_value === null || $this->p_l_value === '') $this->print_error('الحد الأدنى مطلوب');
            if ($this->p_h_value === null || $this->p_h_value === '') $this->print_error('الحد الأعلى مطلوب');
        }
        // نوع 3: دفعة من المستحقات
        if ($rt == 3) {
            if (!$this->p_pay_type)    $this->print_error('بند المستحقات مطلوب');
            if (!$this->p_calc_method) $this->print_error('طريقة الاحتساب مطلوبة');
            if (intval($this->p_calc_method) == 1 && !$this->p_percent_val) $this->print_error('النسبة مطلوبة');
            if (intval($this->p_calc_method) == 2 && !$this->p_req_amount)  $this->print_error('المبلغ مطلوب');
        }
        // نوع 4: مستحقات حسب الشهر
        if ($rt == 4) {
            if (!$this->p_pay_type) $this->print_error('بند المستحقات مطلوب');
        }
        // نوع 5: استحقاقات وإضافات
        if ($rt == 5) {
            if (!$this->p_pay_type) $this->print_error('بند الاستحقاق مطلوب');
        }
    }

    function _postedData($typ = null)
    {
        $the_month_val = ($this->p_the_month != '' ? $this->p_the_month : date('Ym'));
        if ($typ == 'create') {
            // INSERT: P_THE_MONTH, P_REQ_TYPE, P_CALC_METHOD, P_PERCENT_VAL, P_PAY_TYPE, P_NOTE, P_MSG_OUT
            return array(
                array('name' => 'THE_MONTH',   'value' => $the_month_val,               'type' => '', 'length' => -1),
                array('name' => 'REQ_TYPE',    'value' => $this->p_req_type,            'type' => '', 'length' => -1),
                array('name' => 'CALC_METHOD', 'value' => $this->p_calc_method ?: null, 'type' => '', 'length' => -1),
                array('name' => 'PERCENT_VAL', 'value' => $this->p_percent_val ?: null, 'type' => '', 'length' => -1),
                array('name' => 'L_VALUE',     'value' => $this->p_l_value ?: null,     'type' => '', 'length' => -1),
                array('name' => 'H_VALUE',     'value' => $this->p_h_value ?: null,     'type' => '', 'length' => -1),
                array('name' => 'PAY_TYPE',    'value' => $this->p_pay_type ?: null,    'type' => '', 'length' => -1),
                array('name' => 'ENTRY_DATE',  'value' => $this->p_entry_date ?: null,  'type' => '', 'length' => -1),
                array('name' => 'NOTE',        'value' => $this->p_note,                'type' => '', 'length' => -1),
            );
        }
        // UPDATE: P_REQ_ID, P_THE_MONTH, P_REQ_TYPE, P_CALC_METHOD, P_PERCENT_VAL, P_PAY_TYPE, P_NOTE, P_MSG_OUT
        return array(
            array('name' => 'REQ_ID',      'value' => $this->p_req_id,              'type' => '', 'length' => -1),
            array('name' => 'THE_MONTH',   'value' => $the_month_val,               'type' => '', 'length' => -1),
            array('name' => 'REQ_TYPE',    'value' => $this->p_req_type,            'type' => '', 'length' => -1),
            array('name' => 'CALC_METHOD', 'value' => $this->p_calc_method ?: null, 'type' => '', 'length' => -1),
            array('name' => 'PERCENT_VAL', 'value' => $this->p_percent_val ?: null, 'type' => '', 'length' => -1),
            array('name' => 'L_VALUE',     'value' => $this->p_l_value ?: null,     'type' => '', 'length' => -1),
            array('name' => 'H_VALUE',     'value' => $this->p_h_value ?: null,     'type' => '', 'length' => -1),
            array('name' => 'PAY_TYPE',    'value' => $this->p_pay_type ?: null,    'type' => '', 'length' => -1),
            array('name' => 'NOTE',        'value' => $this->p_note,                'type' => '', 'length' => -1),
        );
    }

    function _lookup(&$data, $level = 'full')
    {
        add_css('combotree.css');
        $data['branches'] = $this->gcc_branches_model->get_all();

        if ($level === 'minimal') return;  // batch, history — بس فروع

        $data['pay_type_tree_url'] = base_url('payroll_data/salary_dues_types/public_get_tree_json');
        $this->load->model('settings/constant_details_model');
        $data['req_type_cons']    = $this->constant_details_model->get_list(538);
        $data['calc_method_cons'] = $this->constant_details_model->get_list(540);
        $data['status_cons']      = $this->constant_details_model->get_list(541);

        // تحميل قائمة الموظفين (للفلتر + الإضافة)
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
    }
}
