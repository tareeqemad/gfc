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

        // معاينة التوزيع: لكل موظف، حساباته والمبلغ المتوقّع لكل حساب
        // (يعرض inline في جدول الموظفين)
        $this->load->model('payment_accounts/payment_accounts_model');
        $preview_rows = $this->payment_accounts_model->accounts_preview_by_req((int)$id);
        if (is_array($preview_rows)) {
            array_walk_recursive($preview_rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }
        // نُجمّع الـ rows حسب EMP_NO + نحسب summary لكل موظف
        $accounts_map  = []; // emp_no => list of accounts
        $emp_summary   = []; // emp_no => {is_active, acc_cnt, inactive_acc_cnt, benef_total, benef_linked, alloc_total}
        foreach ((array)$preview_rows as $r) {
            $eno = (int)($r['EMP_NO'] ?? 0);
            if (!$eno) continue;
            if (!isset($accounts_map[$eno])) $accounts_map[$eno] = [];
            $accounts_map[$eno][] = $r;
            // summary مرة واحدة لكل موظف (الـ aggregate columns ثابتة عبر صفوفه)
            if (!isset($emp_summary[$eno])) {
                $emp_summary[$eno] = [
                    'is_active'         => (int)($r['EMP_IS_ACTIVE']     ?? 1),
                    'display_status'    => (int)($r['EMP_DISPLAY_STATUS'] ?? 1),  // 1=فعّال, 0=متقاعد, 2=متوفى, 4=حساب مغلق
                    'req_amount'        => (float)($r['REQ_AMOUNT']      ?? 0),
                    'acc_cnt'           => (int)($r['ACC_CNT']           ?? 0),
                    'inactive_acc_cnt'  => (int)($r['INACTIVE_ACC_CNT']  ?? 0),
                    'benef_total'       => (int)($r['BENEF_TOTAL']       ?? 0),
                    'benef_linked'      => (int)($r['BENEF_LINKED']      ?? 0),
                    'is_overage'        => (int)($r['IS_OVERAGE']        ?? 0),
                    // 🆕 تفاصيل التجاوز — للعرض في الواجهة لو IS_OVERAGE=1
                    'overage_cause'     => (int)($r['OVERAGE_CAUSE']     ?? 0),  // 1=ثابتة، 2=نسب، 3=معاً
                    'overage_fixed_sum' => (float)($r['OVERAGE_FIXED_SUM'] ?? 0),
                    'overage_pct_sum'   => (float)($r['OVERAGE_PCT_SUM']   ?? 0),
                    'overage_rest_cnt'  => (int)($r['OVERAGE_REST_CNT']  ?? 0),
                    'alloc_total'       => 0,
                    'desired_total'     => 0,
                    'inactive_reasons'  => [],
                ];
            }
            // المبلغ المُوزّع (للحسابات النشطة فقط) — هذا ما سيتم صرفه فعلاً
            if (!empty($r['ACC_ID']) && (int)($r['ACC_IS_ACTIVE'] ?? 1) === 1) {
                $emp_summary[$eno]['alloc_total']   += (float)($r['ALLOC_AMOUNT']  ?? 0);
                $emp_summary[$eno]['desired_total'] += (float)($r['DESIRED_AMT']   ?? 0);
            }
            // أسباب التجميد للحسابات الموقوفة
            if (!empty($r['ACC_ID']) && (int)($r['ACC_IS_ACTIVE'] ?? 1) === 0 && !empty($r['INACTIVE_REASON_NAME'])) {
                $reason = $r['INACTIVE_REASON_NAME'];
                if (!in_array($reason, $emp_summary[$eno]['inactive_reasons'])) {
                    $emp_summary[$eno]['inactive_reasons'][] = $reason;
                }
            }
        }
        $data['accounts_map'] = $accounts_map;
        $data['emp_summary']  = $emp_summary;

        // 🆕 ملفات الإكسل المستوردة لهذا الطلب (لو الطلب أُنشئ عبر استيراد)
        $data['import_files'] = [];
        $rs_id = (int)$id;   // ← الـ id متاح كـ parameter للـ method
        if ($rs_id > 0) {
            $this->load->model('attachments/attachment_model');
            $imp = $this->attachment_model->get_list($rs_id, 'payment_req_import', 0);
            $data['import_files'] = is_array($imp) ? $imp : [];
        }

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

    // =========================================================
    // DETAIL — تعيين override توزيع لسطر معين (قبل الاحتساب)
    // POST: detail_id, provider_type (NULL/1/2), acc_id (NULL أو رقم حساب)
    // =========================================================
    // 🆕 public_ prefix → لا تتطلب صلاحيات menu (متاحة لمن يفتح صفحة الطلب)
    function public_detail_set_override()
    {
        // ⚠️ ضمان response JSON دائماً (حتى عند أي خطأ) — يمنع SyntaxError في الـ frontend
        header('Content-Type: application/json; charset=utf-8');
        try {
            $detail_id     = (int)$this->input->post('detail_id');
            $provider_type = $this->input->post('provider_type');
            $acc_id        = $this->input->post('acc_id');

            // normalize: empty string → null
            if ($provider_type === '' || $provider_type === '0') $provider_type = null;
            if ($acc_id === '' || $acc_id === '0')               $acc_id        = null;

            if (!$detail_id) {
                echo json_encode(['ok' => false, 'msg' => 'DETAIL_ID مطلوب']);
                return;
            }

            $res = $this->{$this->MODEL_NAME}->detail_set_override($detail_id, $provider_type, $acc_id);
            echo json_encode(['ok' => ($res == '1'), 'msg' => $res]);
        } catch (\Exception $e) {
            log_message('error', 'detail_set_override exception: ' . $e->getMessage());
            echo json_encode(['ok' => false, 'msg' => 'خطأ في السيرفر: ' . $e->getMessage()]);
        }
    }

    // =========================================================
    // EMP ACCOUNTS — للـ override modal (بدون batch_id)
    // GET/POST: emp_no
    // =========================================================
    function public_emp_accounts_json()
    {
        $emp_no = (int)$this->input->get_post('emp_no');
        if (!$emp_no) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'EMP_NO مطلوب', 'data' => []]);
            return;
        }
        $rows = $this->{$this->MODEL_NAME}->emp_accounts_list($emp_no, 1);
        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'data' => is_array($rows) ? $rows : []]);
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

            // البيانات الأساسية (الموجودة)
            $rows     = $this->{$this->MODEL_NAME}->get_emp_statement($emp_no, $month_from, $month_to);
            $summary  = $this->{$this->MODEL_NAME}->get_summary($emp_no, null);

            // 🆕 بيانات إضافية للكشف الشامل
            $accounts = $this->{$this->MODEL_NAME}->emp_accounts_list($emp_no, 0);  // كل الحسابات
            $pending  = $this->{$this->MODEL_NAME}->emp_pending_batches($emp_no);    // الدفعات المحتسبة

            // معلومات الموظف من DATA.EMPLOYEES + الحالة المركّبة
            $emp_info = $this->{$this->MODEL_NAME}->get_emp_info($emp_no);

            if (is_array($rows))     array_walk_recursive($rows,     function (&$v) { if (is_string($v)) $v = mb_convert_encoding($v, 'UTF-8', 'UTF-8'); });
            if (is_array($accounts)) array_walk_recursive($accounts, function (&$v) { if (is_string($v)) $v = mb_convert_encoding($v, 'UTF-8', 'UTF-8'); });
            if (is_array($pending))  array_walk_recursive($pending,  function (&$v) { if (is_string($v)) $v = mb_convert_encoding($v, 'UTF-8', 'UTF-8'); });
            if (is_array($emp_info)) array_walk_recursive($emp_info, function (&$v) { if (is_string($v)) $v = mb_convert_encoding($v, 'UTF-8', 'UTF-8'); });

            header('Content-Type: application/json');
            echo json_encode([
                'ok'       => true,
                'data'     => is_array($rows)     ? $rows     : [],
                'summary'  => is_array($summary)  && count($summary)  > 0 ? $summary[0]  : [],
                'accounts' => is_array($accounts) ? $accounts : [],
                'pending'  => is_array($pending)  ? $pending  : [],
                'emp_info' => is_array($emp_info) && count($emp_info) > 0 ? $emp_info[0] : [],
                'count'    => is_array($rows) ? count($rows) : 0
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
        // ندعم GET (الرابط من الزر) و POST (التوافق مع NMVC القديم)
        $req_id = $this->input->get('req_id') ?: $this->input->post('req_id') ?: $this->p_req_id;
        $req_id = (int)$req_id;
        if (!$req_id) { echo '<script>alert("معرف الطلب مطلوب"); history.back();</script>'; return; }

        // البيانات الأساسية للموظفين
        $details = $this->{$this->MODEL_NAME}->get_details($req_id);
        if (!is_array($details) || count($details) === 0) {
            echo '<script>alert("لا توجد بيانات"); history.back();</script>'; return;
        }

        // 🏦 بيانات التوزيع: كل (موظف + حساب) → سطر CSV واحد بكل التفاصيل البنكية
        $this->load->model('payment_accounts/payment_accounts_model');
        $preview_rows = $this->payment_accounts_model->accounts_preview_by_req((int)$req_id);
        if (is_array($preview_rows)) {
            array_walk_recursive($preview_rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        // فهرس بالموظف لتسهيل الوصول
        $emp_index = [];
        foreach ($details as $d) {
            $eno = (int)($d['EMP_NO'] ?? 0);
            if ($eno) $emp_index[$eno] = $d;
        }

        $filename = 'bank_transfer_PR' . str_pad((int)$req_id, 5, '0', STR_PAD_LEFT) . '_' . date('Ymd') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo "\xEF\xBB\xBF"; // BOM for Excel UTF-8 (RTL display)
        $out = fopen('php://output', 'w');

        // Header — كل أعمدة الحركة البنكية
        fputcsv($out, [
            '#',
            'رقم الموظف',
            'اسم الموظف',
            'المقر',
            'نوع المستلم',
            'المستلم',
            'العلاقة',
            'البنك / المحفظة',
            'فرع البنك',
            'رقم الحساب',
            'IBAN',
            'رقم هوية صاحب الحساب',
            'هاتف صاحب الحساب',
            'المبلغ'
        ]);

        $n = 0; $total = 0;
        $emp_clean_cache = [];
        foreach ((array)$preview_rows as $r) {
            $eno = (int)($r['EMP_NO'] ?? 0);
            if (!$eno) continue;
            // تخطّى الحسابات الموقوفة وغير المخصص لها مبلغ
            if ((int)($r['ACC_IS_ACTIVE'] ?? 1) === 0) continue;
            $alloc = (float)($r['ALLOC_AMOUNT'] ?? 0);
            if ($alloc <= 0) continue;

            $d = $emp_index[$eno] ?? [];
            $emp_name = $d['EMP_NAME'] ?? '';
            $branch_name = $d['BRANCH_NAME'] ?? '';

            // تحديد نوع المستلم
            $type_label = 'الموظف';
            $recipient = $emp_name;
            $rel = '';
            $owner_clean = trim($r['OWNER_NAME'] ?? '');
            $emp_clean = isset($emp_clean_cache[$eno]) ? $emp_clean_cache[$eno] : ($emp_clean_cache[$eno] = trim($emp_name));

            if (!empty($r['BENEFICIARY_ID'])) {
                $type_label = 'وريث';
                $recipient = $r['BENEF_NAME'] ?? $owner_clean;
                $rel = $r['BENEF_REL_NAME'] ?? '';
            } elseif ($owner_clean && $owner_clean !== $emp_clean) {
                $type_label = 'صاحب حساب';
                $recipient = $owner_clean;
                $rel = 'حساب باسم آخر';
            }

            $n++;
            $total += $alloc;
            fputcsv($out, [
                $n,
                $eno,
                $emp_name,
                $branch_name,
                $type_label,
                $recipient,
                $rel,
                $r['PROVIDER_NAME']    ?? '',
                $r['PROV_BRANCH_NAME'] ?? '',
                $r['ACCOUNT_NO']       ?? '',  // ACCOUNT_NO أو WALLET_NUMBER (الـ procedure تستخدم NVL)
                $r['IBAN']             ?? '',
                $r['OWNER_ID_NO']      ?? '',
                $r['OWNER_PHONE']      ?? '',
                number_format($alloc, 2, '.', '')
            ]);
        }

        // صف الإجمالي
        fputcsv($out, ['', '', '', '', '', '', '', '', '', '', '', '', 'الإجمالي', number_format($total, 2, '.', '')]);

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

        // ملخص حسب البنك/المحفظة (snapshot-aware)
        $bank_summary = $this->{$this->MODEL_NAME}->batch_bank_summary((int)$id);
        if (is_array($bank_summary)) {
            array_walk_recursive($bank_summary, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        // 🆕 المستفيدون: قائمة flat لكل (موظف + حساب) — منظور المستلم الفعلي
        // (نفس البيانات التي تُصدّر للبنوك — snapshot-aware)
        $recipients_raw = $this->{$this->MODEL_NAME}->batch_bank_export((int)$id);
        if (is_array($recipients_raw)) {
            array_walk_recursive($recipients_raw, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        // 🆕 Trend: آخر 6 دفعات (الحالية + 5 سابقات) للمقارنة
        $batch_trend = $this->{$this->MODEL_NAME}->batch_trend((int)$id, 6);
        if (is_array($batch_trend)) {
            array_walk_recursive($batch_trend, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        // 🆕 حالة كل (موظف+شهر) في الدفعة — للفلتر الزمني (فعّال/غير فعّال حسب الشهر)
        $emp_month_rows = $this->{$this->MODEL_NAME}->batch_emp_month_statuses((int)$id);
        $emp_status_by_month = [];   // emp_no => { month => status }
        $batch_months_set    = [];   // [202509, 202605, ...]
        if (is_array($emp_month_rows)) {
            foreach ($emp_month_rows as $r) {
                $eno = (int)($r['EMP_NO'] ?? 0);
                $mon = (string)($r['THE_MONTH'] ?? '');
                $st  = (int)($r['EMP_STATUS'] ?? 1);
                if (!$eno || $mon === '') continue;
                if (!isset($emp_status_by_month[$eno])) $emp_status_by_month[$eno] = [];
                $emp_status_by_month[$eno][$mon] = $st;
                $batch_months_set[$mon] = 1;
            }
        }
        $batch_months = array_keys($batch_months_set);
        sort($batch_months);

        $this->load->model('settings/constant_details_model');
        $data['title']         = 'تفاصيل الدفعة — ' . ($batch_info['BATCH_NO'] ?? $id);
        $data['content']       = 'payment_req_batch_detail';
        $data['batch_info']    = $batch_info;
        $data['detail_rows']   = is_array($detail_rows) ? $detail_rows : [];
        $data['bank_summary']  = is_array($bank_summary) ? $bank_summary : [];
        $data['recipients']    = is_array($recipients_raw) ? $recipients_raw : [];
        $data['batch_reqs']    = $batch_reqs;
        $data['batch_trend']   = is_array($batch_trend) ? $batch_trend : [];
        $data['emp_status_by_month'] = $emp_status_by_month;
        $data['batch_months']  = $batch_months;
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

    /**
     * 🆕 تقرير طباعة شامل لدفعة معينة (محاسب — A4 RTL)
     * يحوي: رأس الدفعة + الشهور/الطلبات/الأنواع + تفصيل المقر بأنواع الصرف +
     *       ملخص حسب البنك/المحفظة + قسم التواقيع
     */
    function batch_print($id)
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

        $bank_summary = $this->{$this->MODEL_NAME}->batch_bank_summary((int)$id);
        if (is_array($bank_summary)) {
            array_walk_recursive($bank_summary, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        $this->load->model('settings/constant_details_model');
        $data['title']         = 'طباعة الدفعة — ' . ($batch_info['BATCH_NO'] ?? $id);
        $data['batch_info']    = $batch_info;
        $data['detail_rows']   = is_array($detail_rows) ? $detail_rows : [];
        $data['bank_summary']  = is_array($bank_summary) ? $bank_summary : [];
        $data['batch_reqs']    = $batch_reqs;
        $data['batch_status_cons'] = $this->constant_details_model->get_list(542);
        $data['user_name']     = $this->session->userdata('user_name') ?? '';

        // عرض مباشر بدون template (نظافة للطباعة)
        $this->load->view('payment_req_batch_print', $data);
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

    // ============================================================
    // 🆕 مقارنة الدفعات: Trend + Diff + Diff Summary + Excel Export
    // ============================================================
    function batch_trend_json()
    {
        $batch_id = (int)$this->input->get_post('batch_id');
        $limit    = (int)$this->input->get_post('limit') ?: 6;
        $rows = $this->{$this->MODEL_NAME}->batch_trend($batch_id, $limit);
        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'rows' => is_array($rows) ? $rows : []]);
    }

    function batch_diff_json()
    {
        $cur = (int)$this->input->get_post('cur_batch_id');
        $prv = (int)$this->input->get_post('prv_batch_id');
        if (!$cur || !$prv) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'يجب تحديد دفعتين للمقارنة']);
            return;
        }
        $summary = $this->{$this->MODEL_NAME}->batch_diff_summary($cur, $prv);
        $rows    = $this->{$this->MODEL_NAME}->batch_diff($cur, $prv);
        foreach ([&$summary, &$rows] as &$d) {
            if (is_array($d)) {
                array_walk_recursive($d, function (&$val) {
                    if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
                });
            }
        }
        header('Content-Type: application/json');
        echo json_encode([
            'ok'      => true,
            'summary' => is_array($summary) && !empty($summary) ? $summary[0] : [],
            'rows'    => is_array($rows) ? $rows : [],
        ]);
    }

    function batch_diff_export()
    {
        $cur = (int)$this->input->get('cur_batch_id');
        $prv = (int)$this->input->get('prv_batch_id');
        if (!$cur || !$prv) { show_error('يجب تحديد دفعتين'); return; }

        $rows = $this->{$this->MODEL_NAME}->batch_diff($cur, $prv);
        if (!is_array($rows)) $rows = [];

        // أسماء الدفعات للملف
        $list = $this->{$this->MODEL_NAME}->batch_history_list();
        $cur_no = $cur; $prv_no = $prv;
        if (is_array($list)) {
            foreach ($list as $b) {
                if ((int)($b['BATCH_ID'] ?? 0) === $cur) $cur_no = $b['BATCH_NO'] ?? $cur;
                if ((int)($b['BATCH_ID'] ?? 0) === $prv) $prv_no = $b['BATCH_NO'] ?? $prv;
            }
        }

        $sp = new Spreadsheet();
        $sp->getProperties()->setTitle("Diff $cur_no vs $prv_no");

        // ─── ورقة 1: ملخص ───
        $s = $sp->getActiveSheet();
        $s->setTitle('ملخص');
        $s->setRightToLeft(true);
        $cnt_new = 0; $cnt_left = 0; $cnt_changed = 0; $cnt_same = 0;
        $amt_new = 0; $amt_left = 0; $amt_inc = 0; $amt_dec = 0;
        foreach ($rows as $r) {
            $ct = $r['CHANGE_TYPE'] ?? '';
            $diff = (float)($r['DIFF'] ?? 0);
            if ($ct === 'NEW')     { $cnt_new++;     $amt_new  += (float)($r['CURRENT_AMOUNT'] ?? 0); }
            if ($ct === 'LEFT')    { $cnt_left++;    $amt_left += (float)($r['PREVIOUS_AMOUNT'] ?? 0); }
            if ($ct === 'CHANGED') { $cnt_changed++; if ($diff > 0) $amt_inc += $diff; else $amt_dec += abs($diff); }
            if ($ct === 'SAME')    { $cnt_same++; }
        }
        $s->fromArray([
            ['ملخص مقارنة الدفعات'],
            [],
            ['الدفعة الحالية',    $cur_no],
            ['الدفعة المقارَنة',  $prv_no],
            [],
            ['الفئة', 'العدد', 'المبلغ'],
            ['موظفون جدد',         $cnt_new,     $amt_new],
            ['موظفون خرجوا',       $cnt_left,    $amt_left],
            ['موظفون تغيّر مبلغهم', $cnt_changed, $amt_inc - $amt_dec],
            ['موظفون بدون تغيير',   $cnt_same,    0],
            [],
            ['زيادات',  '', $amt_inc],
            ['تخفيضات', '', $amt_dec],
            ['صافي التغيير', '', $amt_inc - $amt_dec],
        ], null, 'A1');
        foreach (range('A','C') as $col) $s->getColumnDimension($col)->setWidth(28);
        $s->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $s->getStyle('A6:C6')->getFont()->setBold(true);
        $s->getStyle('A6:C6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('1E40AF');
        $s->getStyle('A6:C6')->getFont()->getColor()->setRGB('FFFFFF');

        // ─── ورقة 2: جدد ───
        $sn = $sp->createSheet();
        $sn->setTitle('جدد');
        $this->_diff_sheet_fill($sn, $rows, 'NEW', ['#', 'رقم الموظف', 'الاسم', 'المقر', 'مبلغ الدفعة الحالية']);

        // ─── ورقة 3: خرجوا ───
        $sl = $sp->createSheet();
        $sl->setTitle('خرجوا');
        $this->_diff_sheet_fill($sl, $rows, 'LEFT', ['#', 'رقم الموظف', 'الاسم', 'المقر', 'مبلغ الدفعة السابقة']);

        // ─── ورقة 4: تعديلات ───
        $sc = $sp->createSheet();
        $sc->setTitle('تعديلات');
        $this->_diff_sheet_fill($sc, $rows, 'CHANGED', ['#', 'رقم الموظف', 'الاسم', 'المقر', 'الحالي', 'السابق', 'الفرق', 'النسبة %']);

        // إخراج
        $fname = "diff_{$cur_no}_vs_{$prv_no}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fname . '"');
        header('Cache-Control: max-age=0');
        $w = new Xlsx($sp);
        $w->save('php://output');
        exit;
    }

    private function _diff_sheet_fill($s, $rows, $filter_type, $headers)
    {
        $s->setRightToLeft(true);
        $s->fromArray($headers, null, 'A1');
        $s->getStyle('A1:' . chr(64 + count($headers)) . '1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $s->getStyle('A1:' . chr(64 + count($headers)) . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('1E40AF');
        $r = 2; $i = 0;
        foreach ($rows as $row) {
            if (($row['CHANGE_TYPE'] ?? '') !== $filter_type) continue;
            $i++;
            if ($filter_type === 'NEW') {
                $s->fromArray([$i, $row['EMP_NO'], $row['EMP_NAME'], $row['BRANCH_NAME'], (float)$row['CURRENT_AMOUNT']], null, "A$r");
            } elseif ($filter_type === 'LEFT') {
                $s->fromArray([$i, $row['EMP_NO'], $row['EMP_NAME'], $row['BRANCH_NAME'], (float)$row['PREVIOUS_AMOUNT']], null, "A$r");
            } else { // CHANGED
                $s->fromArray([$i, $row['EMP_NO'], $row['EMP_NAME'], $row['BRANCH_NAME'],
                    (float)$row['CURRENT_AMOUNT'], (float)$row['PREVIOUS_AMOUNT'],
                    (float)$row['DIFF'], $row['DIFF_PCT']], null, "A$r");
            }
            $r++;
        }
        for ($c = 0; $c < count($headers); $c++) {
            $s->getColumnDimension(chr(65 + $c))->setWidth($c < 1 ? 6 : ($c < 2 ? 12 : ($c < 4 ? 28 : 16)));
        }
    }

    function batch_preview_validation_json()
    {
        $req_ids     = trim((string)$this->input->post('req_ids'));
        $exclude_ids = trim((string)$this->input->post('exclude_detail_ids'));
        if (!$req_ids) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'لم تُحدّد طلبات']);
            return;
        }

        $rows = $this->{$this->MODEL_NAME}->batch_preview_validation($req_ids, $exclude_ids);
        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }

        // إجماليات
        $rows = is_array($rows) ? $rows : [];
        $ok = $warn = $err = 0; $total_amt = 0; $err_amt = 0;
        foreach ($rows as $r) {
            $total_amt += (float)($r['TOTAL_AMOUNT'] ?? 0);
            switch ($r['STATUS'] ?? '') {
                case 'OK':   $ok++;   break;
                case 'WARN': $warn++; break;
                case 'ERR':  $err++;  $err_amt += (float)($r['TOTAL_AMOUNT'] ?? 0); break;
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'ok'         => true,
            'data'       => $rows,
            'totals'     => [
                'employees'    => count($rows),
                'ok'           => $ok,
                'warn'         => $warn,
                'err'          => $err,
                'total_amount' => $total_amt,
                'err_amount'   => $err_amt,
                'safe_amount'  => $total_amt - $err_amt,
            ],
        ]);
    }

    /**
     * batch_compute_preview_json — يرجع التوزيع التفصيلي (per emp+account) لاحتساب الصرف
     * يستخدم في شاشة "تجهيز الصرف" بعد ضغط زر "احتساب" مع طريقة 2 (split)
     * بدون كتابة في DB — للعرض فقط
     */
    function batch_compute_preview_json()
    {
        $req_ids     = trim((string)$this->input->post('req_ids'));
        $exclude_ids = trim((string)$this->input->post('exclude_detail_ids'));
        if (!$req_ids) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'لم تُحدّد طلبات']);
            return;
        }

        $rows = $this->{$this->MODEL_NAME}->batch_compute_preview($req_ids, $exclude_ids);
        if (is_array($rows)) {
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
        }
        $rows = is_array($rows) ? $rows : [];

        // تجميع لكل موظف: الحالة + المجموع المخصّص + عدد الحسابات
        $emps = [];                         // emp_no => aggregated
        $ok = $warn = $err = 0;
        $total_amt = 0; $err_amt = 0;
        foreach ($rows as $r) {
            $eno = $r['EMP_NO'] ?? null;
            if (!$eno) continue;
            if (!isset($emps[$eno])) {
                $emps[$eno] = [
                    'emp_no'        => $eno,
                    'emp_name'      => $r['EMP_NAME'] ?? '',
                    'branch_name'   => $r['BRANCH_NAME'] ?? '',
                    'is_active'     => (int)($r['EMP_IS_ACTIVE'] ?? 0),
                    'total_amount'  => (float)($r['TOTAL_AMOUNT'] ?? 0),
                    'req_count'     => (int)($r['REQ_COUNT'] ?? 0),
                    'detail_ids'    => $r['DETAIL_IDS'] ?? '',
                    'acc_cnt'       => (int)($r['ACC_CNT'] ?? 0),
                    'status'        => $r['EMP_STATUS'] ?? 'OK',
                    'issue'         => $r['ISSUE'] ?? null,
                    'alloc_total'   => 0,
                    'accounts'      => [],
                ];
                $total_amt += (float)($r['TOTAL_AMOUNT'] ?? 0);
                switch ($r['EMP_STATUS'] ?? '') {
                    case 'OK':   $ok++;   break;
                    case 'WARN': $warn++; break;
                    case 'ERR':  $err++;  $err_amt += (float)($r['TOTAL_AMOUNT'] ?? 0); break;
                }
            }
            // إضافة سطر الحساب (لو فيه ACC_ID)
            if (!empty($r['ACC_ID'])) {
                $emps[$eno]['accounts'][] = [
                    'acc_id'        => (int)$r['ACC_ID'],
                    'split_type'    => (int)($r['SPLIT_TYPE'] ?? 0),
                    'split_value'   => (float)($r['SPLIT_VALUE'] ?? 0),
                    'is_default'    => (int)($r['IS_DEFAULT'] ?? 0),
                    'iban'          => $r['IBAN'] ?? '',
                    'account_no'    => $r['ACCOUNT_NO'] ?? '',
                    'wallet_number' => $r['WALLET_NUMBER'] ?? '',
                    'owner_name'    => $r['OWNER_NAME'] ?? '',
                    'owner_id_no'   => $r['OWNER_ID_NO'] ?? '',
                    'beneficiary_id'=> $r['BENEFICIARY_ID'] ?? null,
                    'benef_name'    => $r['BENEF_NAME'] ?? '',
                    'benef_rel'     => $r['BENEF_REL_NAME'] ?? '',
                    'provider_name' => $r['PROVIDER_NAME'] ?? '',
                    'provider_type' => (int)($r['PROVIDER_TYPE'] ?? 1),
                    'prov_branch'   => $r['PROV_BRANCH_NAME'] ?? '',
                    'alloc_amount'  => (float)($r['ALLOC_AMOUNT'] ?? 0),
                ];
                $emps[$eno]['alloc_total'] += (float)($r['ALLOC_AMOUNT'] ?? 0);
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'ok'      => true,
            'data'    => array_values($emps),
            'totals'  => [
                'employees'    => count($emps),
                'ok'           => $ok,
                'warn'         => $warn,
                'err'          => $err,
                'total_amount' => $total_amt,
                'err_amount'   => $err_amt,
                'safe_amount'  => $total_amt - $err_amt,
            ],
        ]);
    }

    function batch_refresh_split_action()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/batch_refresh_split_action'))) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'لا توجد صلاحية']);
            return;
        }
        $batch_id = (int)$this->input->post('batch_id');
        $res = $this->{$this->MODEL_NAME}->batch_refresh_split($batch_id);
        $msg = $res['msg'];
        $ok  = (substr((string)$msg, 0, 1) === '1');
        $missing = '';
        if ($ok && strpos($msg, '|') !== false) {
            $missing = substr($msg, 2);
        }
        header('Content-Type: application/json');
        echo json_encode([
            'ok'      => $ok,
            'changed' => (int)$res['changed'],
            'missing' => $missing,
            'msg'     => $ok
                ? ('تم إعادة احتساب التوزيع — ' . (int)$res['changed'] . ' حركة' . ($missing ? ' (موظفون بدون حسابات: ' . $missing . ')' : ''))
                : $msg
        ]);
    }

    // =========================================================
    // BATCH DETAIL REDIST — إعادة توزيع موظف معيّن في دفعة محتسبة
    // POST: batch_id, emp_no, provider_type (NULL/1/2), acc_id
    // =========================================================
    function batch_detail_redist_action()
    {
        if (!HaveAccess(base_url('payment_req/payment_req/batch_detail_redist_action'))) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'لا توجد صلاحية']);
            return;
        }
        $batch_id      = (int)$this->input->post('batch_id');
        $emp_no        = (int)$this->input->post('emp_no');
        $provider_type = $this->input->post('provider_type');
        $acc_id        = $this->input->post('acc_id');

        if ($provider_type === '' || $provider_type === '0') $provider_type = null;
        if ($acc_id === '' || $acc_id === '0')               $acc_id        = null;

        if (!$batch_id || !$emp_no) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'BATCH_ID و EMP_NO مطلوبين']);
            return;
        }

        $res = $this->{$this->MODEL_NAME}->batch_detail_redist($batch_id, $emp_no, $provider_type, $acc_id);
        $ok  = (trim((string)$res) === '1');
        header('Content-Type: application/json');
        echo json_encode(['ok' => $ok, 'msg' => $ok ? 'تم إعادة التوزيع' : $res]);
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

        // ═══════════════════════════════════════════════════════════════
        // تعريف صيغ التصدير بناءً على نماذج البنوك الفعلية
        // ═══════════════════════════════════════════════════════════════
        // Format A — Header + IBAN (الصيغة الأشيع، 7 بنوك)
        //   نماذج موجودة: فلسطين (89)، القدس (82)، الإسلامي العربي (30)
        //   افتراضي للباقي: الأردن (35)، القاهرة عمان (50)، العربي (70)، الوطني الإسلامي (4444)
        $format_a_banks = [89, 82, 30, 35, 50, 70, 4444];
        // Format B — ID + BRANCH (2 بنوك)
        //   الاستثمار (76) — BRANCH_NO=0 دائماً
        //   الإسلامي الفلسطيني (81) — BRANCH_NO من PRINT_NO تبع الفرع
        $format_b_banks = [76, 81];

        $batchNo = $rows[0]['BATCH_NO'] ?? $batch_id;

        // تجميع حسب البنك
        $banks = [];
        foreach ($rows as $r) {
            $bk = $r['MASTER_BANK_NO'] ?? 0;
            if (!isset($banks[$bk])) $banks[$bk] = ['name' => $r['MASTER_BANK_NAME'] ?? 'غير محدد', 'rows' => []];
            $banks[$bk]['rows'][] = $r;
        }

        // helper: بناء sheet لبنك/محفظة بصيغة مطابقة لقالب البنك بدقة
        $buildSheet = function($sheet, $bkNo, $empRows) use ($format_a_banks, $format_b_banks) {
            // ── إعدادات عامة لكل الصيغ ──
            $sheet->setRightToLeft(true);

            // تمييز نوع الصيغة من أول صف:
            $providerType = (int)($empRows[0]['PROVIDER_TYPE'] ?? 1);
            $isWallet     = ($providerType === 2);
            $isFormatA    = !$isWallet && in_array($bkNo, $format_a_banks);
            $isFormatB    = !$isWallet && in_array($bkNo, $format_b_banks);
            // افتراضي لو ما صنّفنا → Format A
            if (!$isWallet && !$isFormatA && !$isFormatB) $isFormatA = true;

            // ───────── Imports for styling ─────────
            $borderThin = new \PhpOffice\PhpSpreadsheet\Style\Border();
            $border = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $fontHeader = ['font' => ['name' => 'Arial', 'size' => 12]];
            $fontData   = ['font' => ['name' => 'Arial', 'size' => 11]];

            if ($isWallet) {
                // ═══════════════ Wallet (محفظة) ═══════════════
                // عرض الأعمدة (مماثل لـ Format B لكن بدون BRANCH/IBAN)
                $sheet->getColumnDimension('A')->setWidth(15.6);
                $sheet->getColumnDimension('B')->setWidth(30.6);
                $sheet->getColumnDimension('C')->setWidth(20.0);
                $sheet->getColumnDimension('D')->setWidth(15.6);
                $sheet->getRowDimension(1)->setRowHeight(15.5);

                $sheet->setCellValue('A1', 'ID_NO1');
                $sheet->setCellValue('B1', 'EMP_NAME');
                $sheet->setCellValue('C1', 'PHONE');
                $sheet->setCellValue('D1', 'NET_EARN');
                $sheet->getStyle('A1:D1')->applyFromArray($fontHeader);

                $row = 2;
                foreach ($empRows as $r) {
                    $owner_name = trim($r['OWNER_NAME']  ?? $r['EMP_NAME'] ?? '');
                    $owner_id   = trim($r['OWNER_ID_NO'] ?? $r['EMP_ID']  ?? '');
                    $phone      = $r['WALLET_NUMBER'] ?? $r['OWNER_PHONE'] ?? '';
                    $sheet->setCellValueExplicit('A' . $row, (string)$owner_id,  \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('B' . $row, $owner_name);
                    $sheet->setCellValueExplicit('C' . $row, (string)$phone,     \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('D' . $row, (float)($r['TOTAL_AMOUNT'] ?? 0));
                    $row++;
                }
                if ($row > 2) {
                    $sheet->getStyle('A2:D' . ($row - 1))->applyFromArray($fontData);
                    $sheet->getStyle('D2:D' . ($row - 1))->getNumberFormat()->setFormatCode('###0.00');
                }

            } elseif ($isFormatA) {
                // ═══════════════ Format A — Header + IBAN ═══════════════
                // (فلسطين، القدس، الإسلامي العربي، الأردن، القاهرة عمان، العربي، الوطني الإسلامي)

                // عرض الأعمدة (مطابق للقالب)
                $sheet->getColumnDimension('A')->setWidth(15.6);
                $sheet->getColumnDimension('B')->setWidth(30.6);
                $sheet->getColumnDimension('C')->setWidth(15.6);
                $sheet->getColumnDimension('D')->setWidth(50.6);
                $sheet->getColumnDimension('E')->setWidth(15.6);
                // F: عرض افتراضي

                // ارتفاع الصفوف الـ 5 الأولى
                for ($r = 1; $r <= 5; $r++) $sheet->getRowDimension($r)->setRowHeight(15.5);

                // الـ COMPANY_IBAN من المزود (موجود في الـ view)
                $cIban = $empRows[0]['COMPANY_IBAN'] ?? '';

                // R1: اسم الشركة
                $sheet->setCellValue('A1', 'اسم الشركة   ');
                $sheet->setCellValue('D1', ' شركة توزيع كهرباء محافظات  غزة   ');

                // R2: ايبان الشركة
                $sheet->setCellValue('A2', 'ايبان الشركة   ');
                $sheet->setCellValueExplicit('D2', (string)$cIban, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                // R3: الملاحظات (شهر الصرف YYYYMM)
                $sheet->setCellValue('A3', ' الملاحظات   ');
                $payDate = !empty($empRows[0]['PAY_DATE']) ? $empRows[0]['PAY_DATE'] : date('Ymd');
                $monthYM = date('Ym', strtotime($payDate));
                $sheet->setCellValueExplicit('D3', $monthYM, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

                // R4: تفاصيل الحوالة (في B فقط)
                $sheet->setCellValue('B4', ' تفاصيل الحواله   ');

                // R5: Headers
                $sheet->setCellValue('A5', 'الترتيب  ');
                $sheet->setCellValue('B5', 'اسم المستفيد');
                $sheet->setCellValue('C5', 'رقم بطاقه التعريف');
                $sheet->setCellValue('D5', 'ايبان المستفيد');
                $sheet->setCellValue('E5', 'المبلغ');
                $sheet->setCellValue('F5', 'العملة');

                // Font 12 على الـ header rows (1-5)
                $sheet->getStyle('A1:F5')->applyFromArray($fontHeader);

                // R6+ : البيانات (مع borders thin)
                $row = 6; $n = 0;
                foreach ($empRows as $r) {
                    $n++;
                    $owner_name = trim($r['OWNER_NAME'] ?? $r['EMP_NAME'] ?? '');
                    $owner_id   = trim($r['OWNER_ID_NO'] ?? $r['EMP_ID'] ?? '');
                    $sheet->setCellValue('A' . $row, $n);
                    $sheet->setCellValue('B' . $row, $owner_name);
                    $sheet->setCellValueExplicit('C' . $row, (string)$owner_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $row, (string)($r['IBAN'] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('E' . $row, (float)($r['TOTAL_AMOUNT'] ?? 0));
                    $sheet->setCellValue('F' . $row, 'ILS');
                    $row++;
                }
                if ($row > 6) {
                    $sheet->getStyle('A6:F' . ($row - 1))->applyFromArray($fontData);
                    $sheet->getStyle('A6:F' . ($row - 1))->applyFromArray($border); // BORDERS
                    $sheet->getStyle('E6:E' . ($row - 1))->getNumberFormat()->setFormatCode('###0.00');
                }

            } elseif ($isFormatB) {
                // ═══════════════ Format B — ID + BRANCH ═══════════════
                // (الاستثمار، الإسلامي الفلسطيني)

                // عرض الأعمدة (مطابق للقالب)
                $sheet->getColumnDimension('A')->setWidth(15.6);
                $sheet->getColumnDimension('B')->setWidth(30.6);
                $sheet->getColumnDimension('C')->setWidth(15.6);
                $sheet->getColumnDimension('D')->setWidth(17.6);
                $sheet->getColumnDimension('E')->setWidth(15.6);
                $sheet->getColumnDimension('F')->setWidth(30.6);
                $sheet->getRowDimension(1)->setRowHeight(15.5);

                // R1: Headers (Arial 12)
                $sheet->setCellValue('A1', 'ID_NO1');
                $sheet->setCellValue('B1', 'EMP_NAME');
                $sheet->setCellValue('C1', 'ACNT_NO');
                $sheet->setCellValue('D1', 'BRANCH_NO');
                $sheet->setCellValue('E1', 'NET_EARN');
                $sheet->setCellValue('F1', 'iban');
                $sheet->getStyle('A1:F1')->applyFromArray($fontHeader);

                // R2+ : البيانات (بدون borders)
                $row = 2;
                foreach ($empRows as $r) {
                    $owner_name = trim($r['OWNER_NAME'] ?? $r['EMP_NAME'] ?? '');
                    $owner_id   = trim($r['OWNER_ID_NO'] ?? $r['EMP_ID'] ?? '');
                    // BRANCH_NO: من PRINT_NO تبع الفرع (للإسلامي الفلسطيني)
                    //   لو NULL أو 0 → 0 (مثل الاستثمار)
                    $branchNo = (int)($r['BRANCH_PRINT_NO'] ?? 0);
                    $sheet->setCellValueExplicit('A' . $row, (string)$owner_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('B' . $row, $owner_name);
                    $sheet->setCellValueExplicit('C' . $row, (string)($r['BANK_ACCOUNT'] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValue('D' . $row, $branchNo);
                    $sheet->setCellValue('E' . $row, (float)($r['TOTAL_AMOUNT'] ?? 0));
                    $sheet->setCellValueExplicit('F' . $row, (string)($r['IBAN'] ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $row++;
                }
                if ($row > 2) {
                    $sheet->getStyle('A2:F' . ($row - 1))->applyFromArray($fontData);
                    $sheet->getStyle('E2:E' . ($row - 1))->getNumberFormat()->setFormatCode('###0.00');
                }
            }
        };

        if ($master_bank_no && count($banks) === 1) {
            // بنك واحد → ملف واحد بـ sheet واحد
            $bkNo = array_key_first($banks);
            $bkData = $banks[$bkNo];
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // 🆕 اسم الـ sheet: "ورقة1" (مطابق لقوالب البنوك)
            $sheet->setTitle('ورقة1');
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
        // 🆕 نقرأ الصفوف الخام أولاً، ثم نُجمّعها حسب EMP_NO (دمج التكرار)
        $rawLines = []; $errors = []; $rowNum = 0;

        foreach ($rows as $r) {
            $rowNum++;
            if ($rowNum == 1) continue;   // تخطي الـ header
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
                $rawLines[] = ['row' => $rowNum, 'EMP_NO' => (int)$empNo, 'AMOUNT' => 0, 'NOTE' => $rowNote];
            } else {
                $amountNum = floatval(str_replace(',', '', $amount));
                if ($amountNum <= 0) { $errors[] = "صف {$rowNum}: المبلغ يجب أن يكون أكبر من صفر"; continue; }
                $rawLines[] = ['row' => $rowNum, 'EMP_NO' => (int)$empNo, 'AMOUNT' => $amountNum, 'NOTE' => $rowNote];
            }
        }

        // 🆕 تجميع الصفوف لكل موظف — للـ DB سطر واحد بالإجمالي
        // الأصل (كل سطر منفصل) محفوظ:
        //   1. في الإكسل المرفوع كـ attachment على الطلب
        //   2. في PAYMENT_REQ_IMP_LINE_TB (relational — قابل للاستعلام)
        $byEmp = [];   // EMP_NO ⮕ ['lines' => [...full raw...], 'total' => float]
        foreach ($rawLines as $rl) {
            $eno = $rl['EMP_NO'];
            if (!isset($byEmp[$eno])) {
                $byEmp[$eno] = ['lines' => [], 'total' => 0.0];
            }
            $byEmp[$eno]['lines'][] = $rl;       // 🆕 نحفظ السطر الخام بكامله
            $byEmp[$eno]['total']  += (float)$rl['AMOUNT'];
        }

        // 🆕 بناء $items النهائية — صف واحد لكل موظف + breadcrumb للدمج
        $items = [];
        $merged_emps_count = 0;   // عدد الموظفين الذين تم دمجهم (>1 سطر)
        $merged_rows_count = 0;   // إجمالي الصفوف اللي تم دمجها
        foreach ($byEmp as $eno => $info) {
            $cnt        = count($info['lines']);
            $excelRows  = array_column($info['lines'], 'row');
            $rowNotes   = array_filter(array_column($info['lines'], 'NOTE'), function ($n) { return $n !== ''; });

            if ($cnt > 1) {
                $merged_emps_count++;
                $merged_rows_count += $cnt;
                $rowsList = implode('، ', array_slice($excelRows, 0, 7));
                if ($cnt > 7) $rowsList .= '...';
                $finalNote = '[دمج ' . $cnt . ' صفوف Excel: ' . $rowsList . ']';
                if (!empty($rowNotes)) {
                    $uniqueNotes = array_unique($rowNotes);
                    $finalNote .= ' ' . implode(' | ', array_slice($uniqueNotes, 0, 3));
                }
            } else {
                $finalNote = !empty($rowNotes) ? reset($rowNotes) : $note;
            }

            $items[] = [
                'row'        => $excelRows[0],
                'EMP_NO'     => $eno,
                'REQ_AMOUNT' => $isFullSalary ? 0 : round($info['total'], 2),
                'NOTE'       => $finalNote,
                'merged'     => $cnt > 1 ? 1 : 0,    // 🆕 flag للـ UI
                'merge_cnt'  => $cnt,                 // 🆕 عدد الصفوف الأصلية
                'merge_rows' => $excelRows,           // 🆕 أرقام الصفوف الأصلية
                'raw_lines'  => $info['lines'],       // 🆕 السطور الخام كاملة (للحفظ في sub-table)
            ];
        }

        return [
            $items,
            $errors,
            null,
            // 🆕 ملخص الدمج (الـ caller يستخدمه في preview JSON)
            [
                'merged_emps' => $merged_emps_count,
                'merged_rows' => $merged_rows_count,
                'total_raw'   => count($rawLines),
                'total_emps'  => count($items),
            ]
        ];
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

        // 🆕 نقرأ اسم الملف الأصلي قبل ما نستخدم الـ tmp path
        $originalName = $file_token
            ? ('imported_' . date('Ymd_His') . '.xlsx')   // لو جاي عبر file_token، الاسم الأصلي ضايع
            : (isset($_FILES['file']['name']) ? $_FILES['file']['name'] : 'imported.xlsx');

        $parseRes = $this->_import_parse_file($fullPath, $note, $req_type, $the_month);
        list($items, $parseErrors, $parseErr) = [$parseRes[0], $parseRes[1], $parseRes[2]];
        $mergeInfo = isset($parseRes[3]) ? $parseRes[3] : null;

        if ($parseErr) { @unlink($fullPath); echo json_encode(['ok' => false, 'msg' => $parseErr]); return; }
        if (empty($items)) { @unlink($fullPath); echo json_encode(['ok' => false, 'msg' => 'لا يوجد بيانات صالحة', 'parse_errors' => $parseErrors]); return; }

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

        // 🆕 2. حفظ ملف الإكسل الأصلي أولاً — نحتاج ATTACHMENT_ID لربط البنود به
        $attachSaved   = false;
        $attachment_id = 0;
        try {
            list($attachSaved, $attachment_id) = $this->_save_import_attachment($fullPath, $originalName, $req_id, $mergeInfo);
        } catch (\Exception $e) {
            log_message('error', 'Import attachment save failed: ' . $e->getMessage());
        }

        // 3. Add details + import lines
        $inserted = 0; $insertErrors = []; $linesInserted = 0;
        $entryUser = isset($this->user) ? (int)$this->user->emp_no : null;

        foreach ($items as $it) {
            $res = $this->{$this->MODEL_NAME}->detail_add($req_id, $it['EMP_NO'], $it['REQ_AMOUNT'], $it['NOTE']);
            if (is_numeric($res) && (int)$res > 0) {
                $inserted++;
                $detail_id = (int)$res;

                // 🆕 حفظ البنود الأصلية في الجدول الفرعي — للمراجعة لاحقاً
                // نحفظ السطور حتى لو سطر واحد (للتوحيد + لمعرفة "من أي ملف جاي هذا الموظف")
                $rawLines = $it['raw_lines'] ?? [];
                $line_no  = 0;
                foreach ($rawLines as $rl) {
                    $line_no++;
                    try {
                        $r = $this->{$this->MODEL_NAME}->import_line_add(
                            $detail_id,
                            $line_no,
                            (int)($rl['row'] ?? 0),
                            (float)($rl['AMOUNT'] ?? 0),
                            (string)($rl['NOTE'] ?? ''),
                            $attachment_id ?: null,
                            $entryUser
                        );
                        if (!empty($r['new_id'])) { $linesInserted++; }
                    } catch (\Exception $e) {
                        log_message('error', 'Import line insert failed for emp ' . $it['EMP_NO'] . ': ' . $e->getMessage());
                    }
                }
            } else {
                $insertErrors[] = ['row' => $it['row'], 'EMP_NO' => $it['EMP_NO'], 'msg' => mb_substr($res, 0, 200)];
            }
        }

        @unlink($fullPath);

        echo json_encode([
            'ok' => true, 'req_id' => $req_id, 'inserted' => $inserted,
            'total_in_file' => count($items), 'parse_errors' => $parseErrors, 'insert_errors' => $insertErrors,
            // 🆕 رد ملخص الدمج + الحفظ
            'merge_info'      => $mergeInfo,
            'attach_saved'    => $attachSaved,
            'attachment_id'   => $attachment_id,
            'lines_inserted'  => $linesInserted,
        ]);
    }

    /**
     * 🆕 حفظ ملف الإكسل المستورد كـ attachment على الطلب.
     * الفئة (CATEGORY) = 'payment_req_import' و IDENTITY = REQ_ID
     * يرجّع: [bool $saved, int $attachment_id]
     *
     * يستخدم attachment_model الموجود (الـ procedure GFC_ATTACHMENT_TB_INSERT).
     */
    private function _save_import_attachment($fullPath, $originalName, $req_id, $mergeInfo)
    {
        if (!file_exists($fullPath)) return [false, 0];

        $localDir = FCPATH . 'uploads/payment_req_imports/';
        if (!is_dir($localDir)) { @mkdir($localDir, 0755, true); }

        $ext       = pathinfo($originalName, PATHINFO_EXTENSION) ?: 'xlsx';
        $stored    = 'req_' . (int)$req_id . '_' . date('Ymd_His') . '_' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
        $destPath  = $localDir . $stored;
        if (!@copy($fullPath, $destPath)) {
            log_message('error', 'Import attach: copy failed to ' . $destPath);
            return [false, 0];
        }

        $note = 'ملف إكسل مستورد';
        if (is_array($mergeInfo) && (int)($mergeInfo['merged_emps'] ?? 0) > 0) {
            $note .= ' (دمج ' . (int)$mergeInfo['merged_emps'] . ' موظف من ' . (int)$mergeInfo['total_raw'] . ' صف)';
        }

        try {
            $this->load->model('attachments/attachment_model');
            $this->attachment_model->create([
                'IDENTITY'   => (int)$req_id,
                'CATEGORY'   => 'payment_req_import',
                'FILE_NAME'  => $originalName,
                'FILE_PATH'  => $stored,
                'NOTE'       => $note,
                'ENTRY_USER' => isset($this->user) ? (int)$this->user->emp_no : null,
            ]);
            // ATTACHMENT_ID اختياري — البنود تنحفظ بدونه برضو (NULL)
            return [true, 0];
        } catch (\Exception $e) {
            log_message('error', 'Import attach: model->create failed: ' . $e->getMessage());
            return [false, 0];
        }
    }

    /**
     * 🆕 حفظ السطور الخام من الإكسل في PAYMENT_REQ_IMP_LINE_TB
     * يُستدعى من الـ wizard بعد ما تنتهي إضافة الموظفين عبر detail_add.
     *
     * POST:
     *   - req_id (NUMBER)
     *   - lines  (JSON array of {row, emp_no, amount, note})
     *   - attachment_id (NUMBER, optional)
     */
    function public_save_import_lines_for_req()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['ok' => false, 'msg' => 'POST only']); return;
        }
        $req_id        = (int)$this->input->post('req_id');
        $lines_json    = (string)$this->input->post('lines');
        $attachment_id = (int)$this->input->post('attachment_id') ?: null;

        if ($req_id <= 0) {
            echo json_encode(['ok' => false, 'msg' => 'req_id مطلوب']); return;
        }

        $lines = json_decode($lines_json, true);
        if (!is_array($lines) || empty($lines)) {
            echo json_encode(['ok' => false, 'msg' => 'lines فارغ أو غير صالح']); return;
        }

        // ترتيب البنود لكل موظف (LINE_NO تتزايد لكل موظف)
        $line_no_per_emp = [];
        $entry_user      = isset($this->user) ? (int)$this->user->emp_no : null;
        $inserted        = 0;
        $skipped         = 0;
        $errors          = [];

        foreach ($lines as $ln) {
            $emp_no = (int)($ln['emp_no'] ?? 0);
            $amount = (float)($ln['amount'] ?? 0);
            $row    = (int)($ln['row']    ?? 0);
            $note   = (string)($ln['note'] ?? '');

            if (!$emp_no || $amount <= 0) { $skipped++; continue; }

            $line_no_per_emp[$emp_no] = ($line_no_per_emp[$emp_no] ?? 0) + 1;

            try {
                // 🆕 الـ procedure تحلّ DETAIL_ID داخلياً من (REQ_ID + EMP_NO)
                $r = $this->{$this->MODEL_NAME}->import_line_add_by_emp(
                    $req_id,
                    $emp_no,
                    $line_no_per_emp[$emp_no],
                    $row,
                    $amount,
                    $note,
                    $attachment_id,
                    $entry_user
                );
                if (!empty($r['new_id'])) {
                    $inserted++;
                } else {
                    $skipped++;
                    if (!empty($r['msg'])) $errors[] = 'EMP=' . $emp_no . ': ' . $r['msg'];
                }
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = 'EMP=' . $emp_no . ': ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            log_message('error', 'save_import_lines errors: ' . implode(' | ', array_slice($errors, 0, 5)));
        }

        echo json_encode([
            'ok'       => $inserted > 0,
            'inserted' => $inserted,
            'skipped'  => $skipped,
            'total'    => count($lines),
            'msg'      => 'تم حفظ ' . $inserted . ' بند' . ($skipped > 0 ? ' (تم تجاوز ' . $skipped . ')' : ''),
        ]);
    }

    /**
     * 🆕 رفع ملف Excel وربطه بطلب موجود (يُستدعى من wizBulkCreate بعد إنشاء الطلب)
     * POST: req_id + excel_file
     */
    function public_save_import_file_for_req()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['ok' => false, 'msg' => 'POST only']); return;
        }
        $req_id = (int)$this->input->post('req_id');
        if ($req_id <= 0) {
            echo json_encode(['ok' => false, 'msg' => 'req_id مطلوب']); return;
        }
        if (empty($_FILES['excel_file']['name'])) {
            echo json_encode(['ok' => false, 'msg' => 'لا يوجد ملف']); return;
        }

        // رفع الملف لـ uploads/tmp/ أولاً
        list($fullPath, $uploadErr) = $this->_import_upload_file();
        if ($uploadErr) {
            echo json_encode(['ok' => false, 'msg' => $uploadErr]); return;
        }

        $originalName = $_FILES['excel_file']['name'] ?: 'import.xlsx';

        // حفظ كـ attachment على الطلب
        $attachSaved = false; $attachment_id = 0;
        try {
            list($attachSaved, $attachment_id) = $this->_save_import_attachment($fullPath, $originalName, $req_id, null);
        } catch (\Exception $e) {
            log_message('error', 'Wizard import attach failed: ' . $e->getMessage());
        }
        @unlink($fullPath);

        echo json_encode([
            'ok' => $attachSaved,
            'attachment_id' => $attachment_id,
            'msg' => $attachSaved ? 'تم حفظ الملف' : 'فشل حفظ الملف'
        ]);
    }

    /**
     * 🆕 جلب البنود الأصلية لتفصيلة موظف (للـ modal في صفحة الطلب)
     * GET param: detail_id
     * يضيف FILE_NAME/FILE_PATH من attachment_model (لأن procedure ما يقدر يعمل JOIN عبر role)
     */
    function public_get_import_lines_json()
    {
        $detail_id = (int)$this->input->get_post('detail_id');
        if (!$detail_id) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => 'detail_id مطلوب']);
            return;
        }
        try {
            $rows = $this->{$this->MODEL_NAME}->import_lines_get($detail_id);
            if (!is_array($rows)) $rows = [];

            // 🆕 إثراء البنود ببيانات الملف من attachment_model
            $att_cache = [];   // ATTACHMENT_ID → ['FILE_NAME', 'FILE_PATH']
            if (!empty($rows)) {
                $this->load->model('attachments/attachment_model');
                $att_ids = array_unique(array_filter(array_map(function ($r) {
                    return (int)($r['ATTACHMENT_ID'] ?? 0);
                }, $rows)));
                foreach ($att_ids as $aid) {
                    if (!$aid) continue;
                    try {
                        $att = $this->attachment_model->get($aid);
                        if (is_array($att) && isset($att[0])) {
                            $att_cache[$aid] = [
                                'FILE_NAME' => $att[0]['FILE_NAME'] ?? '',
                                'FILE_PATH' => $att[0]['FILE_PATH'] ?? '',
                            ];
                        } elseif (is_array($att) && isset($att['FILE_NAME'])) {
                            $att_cache[$aid] = [
                                'FILE_NAME' => $att['FILE_NAME'] ?? '',
                                'FILE_PATH' => $att['FILE_PATH'] ?? '',
                            ];
                        }
                    } catch (\Exception $e) {
                        // تجاهل — خلّ FILE_NAME فارغ
                    }
                }
                foreach ($rows as &$r) {
                    $aid = (int)($r['ATTACHMENT_ID'] ?? 0);
                    $r['FILE_NAME'] = $att_cache[$aid]['FILE_NAME'] ?? '';
                    $r['FILE_PATH'] = $att_cache[$aid]['FILE_PATH'] ?? '';
                }
                unset($r);
            }

            // تنظيف encoding
            array_walk_recursive($rows, function (&$val) {
                if (is_string($val)) { $val = mb_convert_encoding($val, 'UTF-8', 'UTF-8'); }
            });
            header('Content-Type: application/json');
            echo json_encode(['ok' => true, 'data' => $rows, 'count' => count($rows)]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
        }
    }

    /**
     * 🆕 تنزيل ملف الإكسل المستورد لطلب معين (المسؤول/منشئ الطلب فقط)
     */
    function public_download_import_file($req_id = 0, $file_path = null)
    {
        $req_id = (int)$req_id;
        if (!$req_id || !$file_path) { show_error('Missing parameters', 400); return; }

        $file_path = basename($file_path);   // أمان: نحصر على basename
        $fullPath  = FCPATH . 'uploads/payment_req_imports/' . $file_path;
        if (!file_exists($fullPath)) { show_404(); return; }

        // نتأكد إن الملف فعلاً مرتبط بالطلب
        $this->load->model('attachments/attachment_model');
        $atts = $this->attachment_model->get_list($req_id, 'payment_req_import', 0);
        $found = false; $originalName = $file_path;
        if (is_array($atts)) {
            foreach ($atts as $a) {
                if (($a['FILE_PATH'] ?? '') === $file_path) {
                    $found = true;
                    $originalName = $a['FILE_NAME'] ?? $file_path;
                    break;
                }
            }
        }
        if (!$found) { show_error('Not authorized', 403); return; }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $originalName . '"');
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: max-age=0');
        readfile($fullPath);
        exit;
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
        $parseRes = $this->_import_parse_file($fullPath, $note, $req_type, $the_month);
        list($items, $parseErrors, $parseErr) = [$parseRes[0], $parseRes[1], $parseRes[2]];
        $mergeInfo = isset($parseRes[3]) ? $parseRes[3] : null;

        if ($parseErr) { @unlink($fullPath); echo json_encode(['ok' => false, 'msg' => $parseErr]); return; }
        if (empty($items)) { @unlink($fullPath); echo json_encode(['ok' => false, 'msg' => 'لا يوجد بيانات صالحة', 'parse_errors' => $parseErrors]); return; }

        $file_token = basename($fullPath);
        echo json_encode([
            'ok' => true, 'file_token' => $file_token,
            'valid_count' => count($items), 'error_count' => count($parseErrors),
            'total_rows' => count($items) + count($parseErrors),
            'items' => $items, 'parse_errors' => $parseErrors,
            // 🆕 ملخص الدمج (الـ UI يعرضه في Step 2)
            'merge_info' => $mergeInfo,
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
            'the_month'    => $this->p_the_month,
            'req_type'     => $this->p_req_type,
            'calc_method'  => $this->p_calc_method ?: null,
            'percent_val'  => $this->p_percent_val ?: null,
            'req_amount'   => $this->p_req_amount  ?: null,
            'pay_type'     => $this->p_pay_type    ?: null,
            'sal_from'     => $this->p_sal_from    ?: null,
            'sal_to'       => $this->p_sal_to      ?: null,
            'branch_no'    => $this->p_branch_no   ?: null,
            'l_value'      => $this->p_l_value     ?: null,
            'h_value'      => $this->p_h_value     ?: null,
            'entry_date'   => $this->p_entry_date  ?: null,
            // 🆕 شهر فلتر اختياري — يستخدم للنوع 3 (دفعة من المستحقات) لجلب الموظفين فقط
            'filter_month' => $this->p_filter_month ?: null,
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

        $rt = intval($this->p_req_type);

        // الشهر مطلوب لكل الأنواع ما عدا النوع 3 (دفعة من المستحقات — لا معنى مالي للشهر)
        if ($rt != 3 && !$this->p_the_month) $this->print_error('الشهر مطلوب');

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
            // المبلغ الموحد إجباري فقط في same mode (مبلغ ثابت)؛ في diff اليوزر بيدخل لكل موظف على حدة
            $am3 = $this->p_amount_mode ?: 'same';
            if (intval($this->p_calc_method) == 2 && $am3 === 'same' && !$this->p_req_amount) {
                $this->print_error('المبلغ مطلوب');
            }
        }
        // نوع 4: مستحقات حسب الشهر
        if ($rt == 4) {
            if (!$this->p_pay_type) $this->print_error('بند المستحقات مطلوب');
            // المبلغ الموحد اختياري في same (لو فاضي = احتساب تلقائي للمتبقي)
            // diff: اليوزر بيدخل لكل موظف على حدة
        }
        // نوع 5: استحقاقات وإضافات
        if ($rt == 5) {
            if (!$this->p_pay_type) $this->print_error('بند الاستحقاق مطلوب');
        }
    }

    function _postedData($typ = null)
    {
        $the_month_val = ($this->p_the_month != '' ? $this->p_the_month : date('Ym'));
        $filter_month  = ($this->p_filter_month ?: null);
        if ($typ == 'create') {
            // INSERT: P_THE_MONTH, P_REQ_TYPE, P_CALC_METHOD, P_PERCENT_VAL, P_PAY_TYPE, P_NOTE, P_MSG_OUT, P_FILTER_MONTH
            return array(
                array('name' => 'THE_MONTH',    'value' => $the_month_val,               'type' => '', 'length' => -1),
                array('name' => 'REQ_TYPE',     'value' => $this->p_req_type,            'type' => '', 'length' => -1),
                array('name' => 'CALC_METHOD',  'value' => $this->p_calc_method ?: null, 'type' => '', 'length' => -1),
                array('name' => 'PERCENT_VAL',  'value' => $this->p_percent_val ?: null, 'type' => '', 'length' => -1),
                array('name' => 'L_VALUE',      'value' => $this->p_l_value ?: null,     'type' => '', 'length' => -1),
                array('name' => 'H_VALUE',      'value' => $this->p_h_value ?: null,     'type' => '', 'length' => -1),
                array('name' => 'PAY_TYPE',     'value' => $this->p_pay_type ?: null,    'type' => '', 'length' => -1),
                array('name' => 'ENTRY_DATE',   'value' => $this->p_entry_date ?: null,  'type' => '', 'length' => -1),
                array('name' => 'NOTE',         'value' => $this->p_note,                'type' => '', 'length' => -1),
                array('name' => 'FILTER_MONTH', 'value' => $filter_month,                'type' => '', 'length' => -1),
            );
        }
        // UPDATE: P_REQ_ID, P_THE_MONTH, P_REQ_TYPE, P_CALC_METHOD, P_PERCENT_VAL, P_PAY_TYPE, P_NOTE, P_MSG_OUT, P_FILTER_MONTH
        return array(
            array('name' => 'REQ_ID',       'value' => $this->p_req_id,              'type' => '', 'length' => -1),
            array('name' => 'THE_MONTH',    'value' => $the_month_val,               'type' => '', 'length' => -1),
            array('name' => 'REQ_TYPE',     'value' => $this->p_req_type,            'type' => '', 'length' => -1),
            array('name' => 'CALC_METHOD',  'value' => $this->p_calc_method ?: null, 'type' => '', 'length' => -1),
            array('name' => 'PERCENT_VAL',  'value' => $this->p_percent_val ?: null, 'type' => '', 'length' => -1),
            array('name' => 'L_VALUE',      'value' => $this->p_l_value ?: null,     'type' => '', 'length' => -1),
            array('name' => 'H_VALUE',      'value' => $this->p_h_value ?: null,     'type' => '', 'length' => -1),
            array('name' => 'PAY_TYPE',     'value' => $this->p_pay_type ?: null,    'type' => '', 'length' => -1),
            array('name' => 'NOTE',         'value' => $this->p_note,                'type' => '', 'length' => -1),
            array('name' => 'FILTER_MONTH', 'value' => $filter_month,                'type' => '', 'length' => -1),
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
