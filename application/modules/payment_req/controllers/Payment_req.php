<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_req extends MY_Controller
{
    var $PKG_NAME   = "PAYMENT_REQ_PKG";
    var $MODEL_NAME = "Payment_req_model";
    var $PAGE_URL   = "payment_req/payment_req/get_page";

    function __construct()
    {
        parent::__construct();

        $this->load->model($this->MODEL_NAME);

        $this->load->model('root/rmodel');
        $this->rmodel->package = $this->PKG_NAME;

        $this->load->model('settings/gcc_branches_model');
        $this->load->model('payroll_data/salary_dues_types_model');

        // POST values
        $this->req_id       = $this->input->post('req_id');
        $this->emp_no       = $this->input->post('emp_no');
        $this->the_month    = $this->input->post('the_month');
        $this->req_type     = $this->input->post('req_type');
        $this->wallet_type  = $this->input->post('wallet_type');
        $this->calc_method  = $this->input->post('calc_method');
        $this->percent_val  = $this->input->post('percent_val');
        $this->req_amount   = $this->input->post('req_amount');
        $this->pay_type     = $this->input->post('pay_type');
        $this->note         = $this->input->post('note');

        // فلتر
        $this->branch_no    = $this->input->post('branch_no');
        $this->status       = $this->input->post('status');
    }

    /* ==================== INDEX ==================== */
    function index($page = 1, $branch_no = -1, $the_month = -1, $emp_no = -1, $status = -1)
    {
        $data['title']   = 'طلبات الصرف';
        $data['content'] = 'payment_req_index';
        $data['page']    = $page;

        $data['branch_no'] = $branch_no;
        $data['the_month'] = $the_month;
        $data['emp_no']    = $emp_no;
        $data['status']    = $status;

        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    /* ==================== PAGINATED LIST ==================== */
    function get_page($page = 1, $branch_no = -1, $the_month = -1, $emp_no = -1, $status = -1)
    {
        $this->load->library('pagination');

        $branch_no = $this->check_vars($branch_no, 'branch_no');
        $emp_no    = $this->check_vars($emp_no, 'emp_no');
        $the_month = $this->check_vars($the_month, 'the_month');
        $status    = $this->check_vars($status, 'status');

        $where_sql = '';

        // فلترة الفرع
        if ($this->user->branch == 1) {
            $where_sql .= ($branch_no != null) ? " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$branch_no}' " : '';
        } else {
            $where_sql .= " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$this->user->branch}' ";
        }

        $where_sql .= ($emp_no != null)    ? " and M.EMP_NO= '{$emp_no}' "       : '';
        $where_sql .= ($the_month != null) ? " and M.THE_MONTH= '{$the_month}' " : '';
        $where_sql .= ($status != null)    ? " and M.STATUS= '{$status}' "        : '';

        // count
        $count_where = " PAYMENT_REQ_TB M where 1=1 " . $where_sql;
        $count_rs = $this->get_table_count($count_where);

        $config['base_url']         = base_url($this->PAGE_URL);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows']       = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page']         = $this->page_size;
        $config['num_links']        = 20;
        $config['cur_page']         = $page;

        $config['full_tag_open']   = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close']  = '</ul></div>';
        $config['first_tag_open']  = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close']= $config['num_tag_close'] = '</li>';
        $config['cur_tag_open']    = '<li class="active"><span><b>';
        $config['cur_tag_close']   = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row    = (($page * $config['per_page']));

        $data["page_rows"] = $this->rmodel->getList('PAYMENT_REQ_TB_LIST', $where_sql, $offset, $row);
        $data['offset']    = $offset + 1;
        $data['page']      = $page;

        $this->load->view('payment_req_page', $data);
    }

    /* ==================== CREATE ==================== */
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
            $data['title']    = 'إنشاء طلب صرف';
            $data['isCreate'] = true;
            $data['action']   = 'index';
            $data['content']  = 'payment_req_show';

            $this->_lookup($data);
            $this->load->view('template/template1', $data);
        }
    }

    /* ==================== GET ==================== */
    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        if (!(count($result) == 1)) die('get');

        $data['master_tb_data'] = $result;
        $data['isCreate']       = false;
        $data['can_edit']       = (isset($result[0]['STATUS']) && $result[0]['STATUS'] == 1) ? 1 : 0;
        $data['action']         = 'edit';
        $data['content']        = 'payment_req_show';
        $data['title']          = 'تفاصيل طلب الصرف';

        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }

    /* ==================== EDIT ==================== */
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

    /* ==================== APPROVE ==================== */
    function approve()
    {
        $req_id = $this->input->post('req_id');
        if (!$req_id) {
            echo 'معرف الطلب مطلوب';
            return;
        }

        $result = $this->{$this->MODEL_NAME}->approve($req_id);
        echo $result;
    }

    /* ==================== PAY ==================== */
    function do_pay()
    {
        $req_id = $this->input->post('req_id');
        if (!$req_id) {
            echo 'معرف الطلب مطلوب';
            return;
        }

        $result = $this->{$this->MODEL_NAME}->pay($req_id);
        echo $result;
    }

    /* ==================== DELETE (Cancel) ==================== */
    function delete()
    {
        $req_id      = $this->input->post('req_id');
        $cancel_note = $this->input->post('cancel_note');

        if (!$req_id) {
            echo 'معرف الطلب مطلوب';
            return;
        }

        $result = $this->{$this->MODEL_NAME}->cancel($req_id, ($cancel_note ?: 'Canceled'));
        echo $result;
    }

    /* ==================== SUMMARY (AJAX) ==================== */
    function public_get_summary()
    {
        $emp_no    = $this->input->post('emp_no');
        $the_month = $this->input->post('the_month');

        if (!$emp_no) {
            echo json_encode(['ok' => false, 'msg' => 'رقم الموظف مطلوب']);
            return;
        }

        $the_month = ($the_month != '' && $the_month != null) ? $the_month : null;

        $result = $this->{$this->MODEL_NAME}->get_summary($emp_no, $the_month);

        echo json_encode(['ok' => true, 'data' => $result]);
    }

    /* ==================== HELPERS ==================== */

    function check_vars($var, $c_var)
    {
        $var = ($this->{$c_var}) ? $this->{$c_var} : $var;
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function _post_validation($isEdit = false)
    {
        if ($isEdit && !$this->input->post('req_id')) {
            $this->print_error('REQ_ID مطلوب للتعديل');
        }

        if ($this->p_emp_no == '') {
            $this->print_error('يجب ادخال رقم الموظف');
        }

        $req_type = $this->input->post('req_type');
        if (!in_array($req_type, ['1', '2', '3'])) {
            $this->print_error('يجب اختيار نوع الطلب');
        }

        $wallet_type = $this->input->post('wallet_type');
        if (!in_array($wallet_type, ['1', '2'])) {
            $this->print_error('يجب اختيار نوع المحفظة');
        }
    }

    function _postedData($typ = null)
    {
        $the_month_val = ($this->the_month != '' ? $this->the_month : date('Ym'));

        $result = array(
            array('name' => 'REQ_ID',       'value' => $this->req_id,                      'type' => '', 'length' => -1),
            array('name' => 'EMP_NO',       'value' => $this->p_emp_no,                    'type' => '', 'length' => -1),
            array('name' => 'THE_MONTH',    'value' => $the_month_val,                     'type' => '', 'length' => -1),
            array('name' => 'REQ_TYPE',     'value' => $this->req_type,                    'type' => '', 'length' => -1),
            array('name' => 'WALLET_TYPE',  'value' => $this->wallet_type,                 'type' => '', 'length' => -1),
            array('name' => 'CALC_METHOD',  'value' => $this->calc_method ?: null,         'type' => '', 'length' => -1),
            array('name' => 'PERCENT_VAL',  'value' => $this->percent_val ?: null,         'type' => '', 'length' => -1),
            array('name' => 'REQ_AMOUNT',   'value' => $this->req_amount ?: null,          'type' => '', 'length' => -1),
            array('name' => 'PAY_TYPE',     'value' => $this->pay_type ?: null,            'type' => '', 'length' => -1),
            array('name' => 'NOTE',         'value' => $this->note,                        'type' => '', 'length' => -1),
        );

        if ($typ == 'create') {
            // insert ما فيه REQ_ID
            unset($result[0]);
            $result = array_values($result);
        }

        return $result;
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
