<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 08:09 ص
 */
class Financial_chains extends MY_Controller
{
    var $curr_id;
    var $title;
    var $url = "financial/financial_chains/get";

    function __construct()
    {
        parent::__construct();
        $this->load->model('financial_chains_model');

        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('accounts_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/CustomerAccountInterface_model');
        $this->load->model('root/rmodel');

        if ($this->input->get('type'))
            if ($this->q_type) {
                switch ($this->q_type) {
                    case 4:
                        $this->title = ' القيد المالي العام ';
                        break;
                    case 12:
                        $this->title = 'قيد مقاصة ';
                        break;

                }

            }

    }

    function index($page = 1)
    {

        $page = isset($this->q_page) && $this->q_page != '' ? $this->q_page : 1;

        $data['title'] = ' إعداد ' . $this->title;
        $data['content'] = 'financial_chains_index';

        $data['page'] = $page;

        $data['case'] = 1;
        $data['type'] = $this->q_type;
        $data['action'] = 'index';

        $data['sources'] = $this->constant_details_model->get_list(13);

        add_css('datepicker3.css');

        add_js('jquery.hotkeys.js');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');


        $this->load->view('template/template', $data);
    }

    function archive($page = 1)
    {

        $data['title'] = 'ارشيف القيود المالية';

        $data['content'] = 'financial_chains_archive';

        $data['page'] = $page;

        $data['sources'] = $this->constant_details_model->get_list(13);
        $data['branches'] = $this->gcc_branches_model->get_all();

        add_js('jquery.hotkeys.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template', $data);

    }

    function look_ups(&$data, $date = null)
    {
        add_css('combotree.css');

        add_js('jquery.hotkeys.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['title'] = $this->title;
        $data['content'] = 'financial_chains_show';
        $data['help'] = $this->help;

        $data['currency'] = $this->currency_model->get_all_date($date);

        $this->rmodel->package = 'financial_pkg';
        $data['BkFin'] = $this->rmodel->getList('BANKS_FIN_CENTER_ST_TB_LIST', "  ", 0, 1000);

    }

    function get($id, $action = 'index')
    {

        $result = $this->financial_chains_model->get($id);

        $this->date_format($result, 'FINANCIAL_CHAINS_DATE');

        $data['chains_data'] = $result;

        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER'] && $result[0]['FINANCIAL_CHAINS_CASE'] == 1 && $action == 'index') ? true : false : false;

        $data['action'] = $action;

        $data['type'] = count($result) > 0 ? $result[0]['FIANANCIAL_CHAINS_SOURCE'] : 0;

        $this->look_ups($data);

        $this->load->view('template/template', $data);
    }

    function public_copy($id, $action = 'index')
    {

        $result = array();

        $this->date_format($result, 'FINANCIAL_CHAINS_DATE');

        $data['chains_data'] = $result;

        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER'] && $result[0]['FINANCIAL_CHAINS_CASE'] == 1 && $action == 'index') ? true : false : false;

        $data['action'] = $action;

        $data['type'] = count($result) > 0 ? $result[0]['FIANANCIAL_CHAINS_SOURCE'] : 4;

        $data['fin_copy_id'] = $id;

        $this->look_ups($data);

        $this->load->view('template/template', $data);
    }

    function create()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_validate_data();

            $debit_sum = 0;
            $credit_sum = 0;
            $this->curr_id = $this->input->post('curr_id');
            $account_type = $this->input->post('account_type');
            $account_id = $this->input->post('account_id');
            $debit = $this->input->post('debit');
            $credit = $this->input->post('credit');
            $hints = $this->input->post('dhints');

            $FINANCIAL_CHAINS_DETAIL_TB_array = array();

            for ($i = 0; $i < count($account_id); $i++) {

                if ($account_type[$i] == 1)
                    $this->accounts_validation($account_id[$i], $this->curr_id);

                $debit_sum += $debit[$i];
                $credit_sum += $credit[$i];

                if (($debit[$i] <= 0 && $credit[$i] <= 0)) {

                    //$this->print_error('القيد غير متزن');
                }

            }

            if ('' . $debit_sum != '' . $credit_sum) {

                //$this->print_error('القيد غير متزن');
            }

            $account_id = array_filter($account_id);
            $account_type = array_filter($account_type);

            if (count($account_type) != count($account_id) || count($account_id) != count($debit)) {
                $this->print_error('يجب إدخال جميع البيانات');
				
            }


            for ($i = 0; $i < count($account_id); $i++) {


                $p_root_account_id =  isset($this->p_root_account_id) && isset($this->p_root_account_id[$i]) ? $this->p_root_account_id[$i] : 0;
                $p_root_account_type = isset($this->p_root_account_type) && isset($this->p_root_account_type[$i]) ? $this->p_root_account_type[$i] : 0;
                $customer_account_type =isset($this->p_customer_account_type) && isset($this->p_customer_account_type[$i]) ? $this->p_customer_account_type[$i] : 0;
				
				   if($customer_account_type == 0 && $account_type[$i] == 2){

                        $this->print_error('يجب تحديد نوع المستفيد للحساب : '.$account_id[$i]);
                    }

					
                $bk_fin_id =  isset($_POST['bk_fin_id'.$i]) ? $_POST['bk_fin_id'.$i] : 0;

                $hints[$i] = $hints[$i] == null || $hints[$i] == '' ? $this->p_hints : $hints[$i];

                $fin_details_string = "0჻{$account_type[$i]}჻{$account_id[$i]}჻{$debit[$i]}჻{$credit[$i]}჻{$hints[$i]}჻{$p_root_account_id}჻{$p_root_account_type}჻{$customer_account_type}჻{$bk_fin_id}";


                array_push($FINANCIAL_CHAINS_DETAIL_TB_array, $fin_details_string);

            }


            $id = $this->financial_chains_model->create($this->_postedData($this->q_type, $FINANCIAL_CHAINS_DETAIL_TB_array));

            if (intval($id) <= 0) {
                $this->print_error('فشل في حفظ القيد ؟!'.$id);
            }

            echo 1;

        } else {


            $this->look_ups($data);
            $data['action'] = 'index';
            $data['type'] = $this->q_type;;
            $this->load->view('template/template', $data);
        }
    }

    function edit()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($this->p_account_id)) {

                /*$this->print_error('فشل في حفظ البيانات , لا يوجد أي تغيرات');

                return;*/
            } 
			 
			

            $this->curr_id = $this->input->post('curr_id');
            $account_type = $this->input->post('account_type');
            $account_id = $this->input->post('account_id');
            $debit = $this->input->post('debit');
            $credit = $this->input->post('credit');
            $hints = $this->input->post('dhints');

            $financial_chains_seq = $this->input->post('financial_chains_seq');

            $FINANCIAL_CHAINS_DETAIL_TB_array = array();
            if (isset($this->p_account_id)) {

                $this->_validate_data();
 
                for ($i = 0; $i < count($account_id); $i++) {
                    if ($account_type[$i] == 1)
                        $this->accounts_validation($account_id[$i], $this->curr_id);

                    if (($debit[$i] <= 0 && $credit[$i] <= 0)) {

                        //$this->print_error('القيد غير متزن');
                    }
                }

                $debit_sum = floatval(array_sum($credit) . '');
                $credit_sum = floatval(array_sum($debit) . '');


                if ($debit_sum != $credit_sum) {

                    //$this->print_error('القيد غير متزن');
                }
				
				

                $account_id = array_filter($account_id);
                $account_type = array_filter($account_type);

                /*if(count($account_type) != count($account_id) || count($account_id) !=count($debit)){
                    $this->print_error('يجب إدخال جميع البيانات');
                }*/

                if (count($account_type) != count($account_id) || count($account_id) != count($hints)) {
                    $this->print_error('يجب إدخال جميع البيانات'.  '  , '.count($account_id).  '  , '.count($account_type).  '  , '.count($hints));
                }

                $debit = array_values($debit);
                $credit = array_values($credit);


                for ($i = 0; $i < count($financial_chains_seq); $i++) {
                    $seq = $financial_chains_seq[$i] == null ? 0 : $financial_chains_seq[$i];
                     $p_root_account_id =  isset($this->p_root_account_id) && isset($this->p_root_account_id[$i]) ? $this->p_root_account_id[$i] : 0;
					 $p_root_account_type = isset($this->p_root_account_type) && isset($this->p_root_account_type[$i]) ? $this->p_root_account_type[$i] : 0;
					 $customer_account_type =isset($this->p_customer_account_type) && isset($this->p_customer_account_type[$i]) ? $this->p_customer_account_type[$i] : 0;
					 
					if($customer_account_type == 0 && $account_type[$i] == 2){

                        $this->print_error('يجب تحديد نوع المستفيد للحساب : '.$account_id[$i]);
                    }

				
                    $bk_fin_id =  isset($_POST['bk_fin_id'.$i]) ? $_POST['bk_fin_id'.$i] : 0;

                    $hints[$i] = $hints[$i] == null || $hints[$i] == '' ? $this->p_hints : $hints[$i];

                    $fin_details_string = "{$seq}჻{$account_type[$i]}჻{$account_id[$i]}჻{$debit[$i]}჻{$credit[$i]}჻{$hints[$i]}჻{$p_root_account_id}჻{$p_root_account_type}჻{$customer_account_type}჻{$bk_fin_id}";

                    array_push($FINANCIAL_CHAINS_DETAIL_TB_array, $fin_details_string);
                }
            }
            $result = $this->financial_chains_model->edit($this->_postedData_update($FINANCIAL_CHAINS_DETAIL_TB_array));


            if (intval($result) > 0) {
              
                echo 1;

            } else
                $this->print_error('فشل في حفظ القيد ؟!'.$result);

			 
        }
    }

    /**
     * Update adopt ..
     */
    function adopt($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->input->post('financial_chains_id');
            $this->_notify('review', " قيد مالي : {$this->p_hints}");
            echo $this->financial_chains_model->adopt($id, 2);


        } else {

            $data['title'] = 'إعتماد ' . $this->title;
            $data['content'] = 'financial_chains_index';

            $data['page'] = $page;
            $data['case'] = 1;
            $data['action'] = 'adopt';
            $data['type'] = $this->q_type;

            $this->load->view('template/template', $data);
        }
    }

    /**
     * Update review ..
     */
    function review($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->input->post('financial_chains_id');
            echo $this->financial_chains_model->adopt($id, 3);

        } else {

            $data['title'] = 'تدقيق ' . $this->title;
            $data['content'] = 'financial_chains_index';

            $data['page'] = $page;
            $data['case'] = 2;
            $data['action'] = 'review';
            $data['type'] = $this->q_type;
            $this->load->view('template/template', $data);
        }
    }

    function public_return()
    {

        $id = $this->input->post('financial_chains_id');
        $this->_notify('index', " قيد مالي : {$this->p_hints}");
        echo $this->financial_chains_model->adopt($id, 1);
    }

    function public_get_details($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['chains_details'] = $this->financial_chains_model->get_details_all($id);
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(3);

        $this->look_ups($data);

        $this->load->view('financial_chains_page', $data);
    }

    function public_get_details_copy($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['chains_details'] = $this->financial_chains_model->get_details_all($id);

        $this->load->view('financial_chains_page_copy', $data);
    }

    function get_page($page = 1, $case = 1, $type = 4, $action = 'index')
    {

        $case = isset($this->p_case) ? $this->p_case : $case;
        $case_prev = $case;
        $case = $case + 1;

        $sql = " AND (BRANCHES = {$this->user->branch} )";
        $sql .= isset($this->p_id) && $this->p_id != null ? " AND FINANCIAL_CHAINS_ID ={$this->p_id} " : '';
        $sql = $sql . (isset($this->p_date_from) && $this->p_date_from != null ? " AND FINANCIAL_CHAINS_DATE >= '{$this->p_date_from}' " : '');
        $sql = $sql . (isset($this->p_date_to) && $this->p_date_to != null ? " AND FINANCIAL_CHAINS_DATE <= '{$this->p_date_to}' " : '');
        $sql = $sql . (isset($this->p_account_id) && $this->p_account_id != null ? " AND FINANCIAL_CHAINS_ID IN (SELECT FINANCIAL_CHAINS_ID FROM FINANCIAL_CHAINS_DETAIL_TB WHERE ACCOUNT_ID='{$this->p_account_id}' OR ROOT_ACCOUNT_ID ='{$this->p_account_id}' ) " : '');
        $sql = $sql . (isset($this->p_source) && $this->p_source != null ? " AND FIANANCIAL_CHAINS_SOURCE = {$this->p_source} " : '');
        $sql = $sql . (isset($this->p_entry_user) && $this->p_entry_user != null ? " AND ENTRY_USER IN (SELECT ID FROM USERS_PROG_TB WHERE USER_NAME LIKE '%{$this->p_entry_user}%') " : '');

        $sql = $action == 'review' ? $sql . "and FINANCIAL_CHAINS_CASE = {$case_prev}  " : $sql . "and FINANCIAL_CHAINS_CASE BETWEEN {$case_prev} AND {$case} ";

        $orderBy = isset($this->p_sort_data) && $this->p_sort_data != null ? " {$this->p_sort_data} " : ' FINANCIAL_CHAINS_ID DESC ';


        $this->load->library('pagination');
        $count_rs = $this->financial_chains_model->get_count(" AND FIANANCIAL_CHAINS_SOURCE = {$type} AND (BRANCHES = {$this->user->branch} ) and FINANCIAL_CHAINS_CASE BETWEEN {$case_prev} AND {$case}  {$sql}");
        $config['base_url'] = base_url('financial/financial_chains/get_page');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;

        /*$config['page_query_string']=TRUE;
        $config['query_string_segment']='page';*/

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));

        $result = $this->financial_chains_model->get_list(" where    FIANANCIAL_CHAINS_SOURCE = {$type} AND (BRANCHES = {$this->user->branch} ) {$sql} ", $offset, $row, $orderBy);

        $this->date_format($result, 'FINANCIAL_CHAINS_DATE');

        $data["chains"] = $result;

        $data['action'] = isset($this->p_action) ? $this->p_action : $action;

        $data['sort'] = isset($this->p_sort_data) && $this->p_sort_data != null ? $this->p_sort_data : 'FINANCIAL_CHAINS_ID DESC';


        $data['type'] = $type;
        $data['offset'] = $offset + 1;
        $this->load->view('financial_chains_rows_page', $data);

    }

    function get_page_archive($page = 1)
    {

        $sql = isset($this->p_id) && $this->p_id != null ? " AND FINANCIAL_CHAINS_ID ={$this->p_id} " : '';
        $sql = $sql . (isset($this->p_date_from) && $this->p_date_from != null ? " AND FINANCIAL_CHAINS_DATE >= '{$this->p_date_from}' " : '');
        $sql = $sql . (isset($this->p_date_to) && $this->p_date_to != null ? " AND FINANCIAL_CHAINS_DATE <= '{$this->p_date_to}' " : '');
        $sql = $sql . (isset($this->p_account_id) && $this->p_account_id != null ? " AND FINANCIAL_CHAINS_ID IN (SELECT FINANCIAL_CHAINS_ID FROM FINANCIAL_CHAINS_DETAIL_TB WHERE ACCOUNT_ID='{$this->p_account_id}' OR ROOT_ACCOUNT_ID ='{$this->p_account_id}' ) " : '');
        $sql = $sql . (isset($this->p_source) && $this->p_source != null ? " AND FIANANCIAL_CHAINS_SOURCE = {$this->p_source} " : '');
        $sql = $sql . (isset($this->p_check_id) && $this->p_check_id != null ? " AND CHECK_ID = {$this->p_check_id} " : '');
        $sql = $sql . (isset($this->p_entry_user) && $this->p_entry_user != null ? " AND ENTRY_USER IN (SELECT ID FROM USERS_PROG_TB WHERE USER_NAME LIKE '%{$this->p_entry_user}%') " : '');
        $sql .= isset($this->p_price) && $this->p_price != null ? " AND   FINANCIAL_PKG.FINANCIAL_CHAIN_DET_TB_SUM_D(FINANCIAL_CHAINS_ID) {$this->p_price_op} {$this->p_price} " : "";
        $sql = $sql . (isset($this->p_hints) && $this->p_hints != null ? " AND HINTS like '%{$this->p_hints}%' " : '');

        $sql = $sql . (isset($this->p_branch) && $this->p_branch != null ? " AND BRANCHES = {$this->p_branch} " : '');

        $orderBy = isset($this->p_sort_data) && $this->p_sort_data != null ? " {$this->p_sort_data} " : ' FINANCIAL_CHAINS_ID DESC ';

        $this->load->library('pagination');
        $count_rs = $this->financial_chains_model->get_count("  AND (BRANCHES = {$this->user->branch}  or {$this->user->branch} = 1 )  {$sql}");
        $config['base_url'] = base_url('financial/financial_chains/get_page_archive');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;

        //$config['page_query_string']=TRUE;
        // $config['query_string_segment']='page';

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));

        $result = $this->financial_chains_model->get_list(" where    (BRANCHES = {$this->user->branch} or {$this->user->branch} = 1 ) {$sql}  ", $offset, $row, $orderBy);

        $this->date_format($result, 'FINANCIAL_CHAINS_DATE');

        $data["chains"] = $result;

        $data['action'] = 'index';
        $data['offset'] = $offset + 1;

        $data['sort'] = isset($this->p_sort_data) && $this->p_sort_data != null ? $this->p_sort_data : 'FINANCIAL_CHAINS_ID DESC';

        $this->load->view('financial_chains_rows_page', $data);

    }

    function _postedData($type, $details = null, $create = null)
    {


        $financial_chains_id = $this->input->post('financial_chains_id');

        $payment_id = $this->input->post('payment_id');

        $curr_value = $this->input->post('curr_value');
        $check_id = $this->input->post('check_id');
        $hints = $this->input->post('hints');

        $result = array(
            array('name' => 'FINANCIAL_CHAINS_ID', 'value' => $financial_chains_id, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_CHAINS_DATE', 'value' => $this->p_financial_chains_date, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'FIANANCIAL_CHAINS_SOURCE', 'value' => 4, 'type' => '', 'length' => -1),
            array('name' => 'PAYMENT_ID', 'value' => $payment_id, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_CHAINS_CASE', 'value' => 1, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->curr_id, 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $curr_value, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'AUDIT_DATE', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'AUDIT_USER', 'value' => null, 'type' => '', 'length' => -1),

            array('name' => 'TRANS_DATE', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'TRANS_USER', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_ID', 'value' => $check_id, 'type' => '', 'length' => -1),
            array('name' => 'QEED_INFO', 'value' => $details, 'type' => SQLT_CHR, 'length' => -1),
        );

        if ($create == null) {
            array_shift($result);
        }

        return $result;
    }

    function _postedData_update($QEED_INFO = null)
    {


        $financial_chains_id = $this->input->post('financial_chains_id');

        $payment_id = $this->input->post('payment_id');

        $curr_value = $this->input->post('curr_value');
        $check_id = $this->input->post('check_id');
        $hints = $this->input->post('hints');

        $result = array(
            array('name' => 'FINANCIAL_CHAINS_ID', 'value' => $financial_chains_id, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_CHAINS_DATE', 'value' => $this->p_financial_chains_date, 'type' => '', 'length' => -1),

            array('name' => 'FIANANCIAL_CHAINS_SOURCE', 'value' => 4, 'type' => '', 'length' => -1),
            array('name' => 'PAYMENT_ID', 'value' => $payment_id, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_CHAINS_CASE', 'value' => 1, 'type' => '', 'length' => -1),

            array('name' => 'CURR_VALUE', 'value' => $curr_value, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),

            array('name' => 'CHECK_ID', 'value' => $check_id, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->p_curr_id, 'type' => '', 'length' => -1),
            array('name' => 'QEED_INFO', 'value' => $QEED_INFO, 'type' => SQLT_CHR, 'length' => -1),
        );

	  /*print_r($_POST);
	   print_r($result);*/

        return $result;
    }

    function _postDetailsData($id, $type, $account, $debit, $credit, $hints, $create = null, $Did = null, $root_account_id, $root_account_type)
    {

        $result = array(
            array('name' => 'FINANCIAL_CHAINS_SEQ', 'value' => $Did, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_CHAINS_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $type, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_ID', 'value' => $account, 'type' => '', 'length' => -1),
            array('name' => 'DEBIT', 'value' => $debit, 'type' => '', 'length' => -1),
            array('name' => 'CREDIT', 'value' => $credit, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'ROOT_ACCOUNT_ID', 'value' => $root_account_id, 'type' => '', 'length' => -1),
            array('name' => 'ROOT_ACCOUNT_TYPE', 'value' => $root_account_type, 'type' => '', 'length' => -1),
        );

        if ($create == null) {
            array_shift($result);
        }


        return $result;

    }

    function _postDetailsData_Update($id, $type, $account, $debit, $credit, $hints, $Did = null, $root_account_id, $root_account_type)
    {

        $result = array(
            array('name' => 'FINANCIAL_CHAINS_SEQ', 'value' => $Did, 'type' => '', 'length' => -1),

            array('name' => 'ACCOUNT_TYPE', 'value' => $type, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_ID', 'value' => $account, 'type' => '', 'length' => -1),
            array('name' => 'DEBIT', 'value' => $debit, 'type' => '', 'length' => -1),
            array('name' => 'CREDIT', 'value' => $credit, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'ROOT_ACCOUNT_ID', 'value' => $root_account_id, 'type' => '', 'length' => -1),
            array('name' => 'ROOT_ACCOUNT_TYPE', 'value' => $root_account_type, 'type' => '', 'length' => -1),

        );

        return $result;

    }

    function accounts_validation($account, $curr_id)
    {

        $result = $this->accounts_model->get($account);
        $result = count($result) > 0 ? $result[0] : null;

        // if($result['CURR_ID'] != $curr_id)
        //     $this->print_error('عملة القيد تختلف عن عملة الحسابات ؟!!');

    }

    function public_get_balance($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        echo $this->financial_chains_model->get_balance($id);
    }

    function delete($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        echo $this->financial_chains_model->delete($id);
    }

    function _validate_data()
    {

        $exp_date = str_replace('/', '-', $this->p_financial_chains_date);
        if ((strtotime($this->today) < strtotime($exp_date)) || (strtotime($this->prev_year) > strtotime($exp_date)))
            $this->print_error('تاريخ القيد غير صحيح ؟!');
		 

        for ($i = 0; $i < count($this->p_account_id); $i++) {

            if ($this->p_account_type[$i] == 1)
                if (!$this->accounts_model->isAccountExists($this->p_account_id[$i]))
                    $this->print_error('رقم الحساب غير صحيح' . ' (' . $this->p_account_id[$i] . ')');
        }

    }

    function copy_chain()
    {

        $rs = $this->financial_chains_model->copy_chain($this->p_id);

        if (intval($rs) <= 0)
            $this->print_error('فشل في نسخ القيد');

        echo $rs;

    }

    function _notify($action, $message, $id = null)
    {
        if ($id == null)
            $this->_notifyMessage("{$action}?type=4", "{$this->url}/{$this->p_financial_chains_id}/{$action}?type=4", $message);
        else
            $this->_notifyMessage("{$action}?type=4", "{$this->url}/{$id}/{$action}?type=4", $message);


    }

    //CURRENCY_DIFFERENCE
    function Currency_difference()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $rs = $this->financial_chains_model->currency_difference($this->p_id, $this->p_idto, $this->p_date);

            if (intval($rs) <= 0)
                $this->print_error('فشل في حفظ البيانات'.$rs);
            echo $rs;

        } else {
            $this->look_ups($data);
            $data['title'] = 'قيد فرق العملة';

            $data['help'] = $this->help;

            $data['content'] = 'Currency_difference_show';


            $this->load->view('template/template', $data);
        }
    }
}