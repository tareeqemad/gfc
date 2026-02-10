<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 18/10/14
 * Time: 08:08 ص
 */
class Convert_cash_bank extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('convert_cash_bank_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('financial/accounts_permission_model');

        $this->load->model('root/rmodel');
    }

    function index($page = 1)
    {
        $data['title'] = ' ترحيل مبالغ محصلة للبنك ';
        $data['content'] = 'convert_cash_bank_index';

        $data['page'] = $page;
        $this->load->view('template/template', $data);

    }

    function look_ups(&$data)
    {


        $data['cash_type'] = $this->constant_details_model->get_list(4);
        $data['money_received'] = $this->constant_details_model->get_list(10);
        $income_type = $this->constant_details_model->get_list(4);
        array_pop($income_type);
        $data['income_type'] = $income_type;

        $data['bank_prefix'] = $this->get_system_info('BANK_PREFIX', '1');
        $data['treasure_prefix'] = $this->get_system_info('TREASURE_PREFIX', '1');
        $data['back_ch_prefix'] = $this->get_system_info('BANK_CK_PREFIX', '1');

        $this->rmodel->package = 'financial_pkg';
        $data['BkFin'] = $this->rmodel->getList('BANKS_FIN_CENTER_ST_TB_LIST', "  ", 0, 1000);
    }


    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
			{ // mkilani 202210
                $create_time= time();
                if($create_time < $this->session->userdata('cash_bank_create_time') + 40){
                    $this->print_error("انتظر 25 ثانية ثم حاول مرة اخرى"); die();
                }
                $this->session->set_userdata('cash_bank_create_time', $create_time);
            }

            $result = $this->convert_cash_bank_model->create($this->_postedData());

            if (intval($result) <= 0)
                $this->print_error('فشل في حفظ البيانات');

            if ($this->user->branch == 1) {
                //  $this->convert_cash_bank_model->adopt($result,2);
            }


        } else {


            add_css('combotree.css');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_js('jquery.hotkeys.js');
            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');

            $data['title'] = 'ترحيل مبالغ محصلة إلي البنك ';
            $data['content'] = 'convert_cash_bank_show';
            $data['help'] = $this->help;

            $this->look_ups($data);

            $this->load->view('template/template', $data);
        }
    }


    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = $this->convert_cash_bank_model->edit($this->_postedData_Edit());

            if (intval($result) <= 0)
                $this->print_error('فشل في حفظ البيانات'.$result);

            if ($this->user->branch == 1) {
                $this->convert_cash_bank_model->adopt($result, 2);
            }


        }
    }

    /**
     * Update adopt ..
     */
    function adopt($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $convert_cash_bank_id = $this->input->post('convert_cash_bank_id');

            echo $this->convert_cash_bank_model->adopt($convert_cash_bank_id, 2);
        } else {

            $data['title'] = ' ترحيل مبالغ محصلة للبنك ';
            $data['content'] = 'convert_cash_bank_index';

            $data['page'] = $page;
            $this->load->view('template/template', $data);
        }
    }


    function get($id, $action = 'index')
    {

        $data['title'] = 'ترحيل مبالغ محصلة إلي البنك ';

        $data['content'] = 'convert_cash_bank_show';

        $data['help'] = $this->help;
        add_css('combotree.css');

        $result = $this->convert_cash_bank_model->get($id);
        $this->date_format($result, 'CONVERT_CASH_BANK_DATE');

        $data['cash_bank_data'] = $result;
        $data['action'] = $action;

        $data['can_edit'] = count($result) > 0 && $result['0']['CONVERT_CASH_BANK_CASE'] == 1 && $result[0]['TREASURY_USER_ID'] == $this->user->id;

        $this->look_ups($data);

        $this->load->view('template/template', $data);
    }

    /**
     * @param $id
     *  return Bank account id by check id  ..
     */
    function public_get_bank_account_id($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $result = $this->convert_cash_bank_model->get_bank_account_id($id);

        $result = json_encode($result);
        $result = str_replace('subs', 'children', $result);
        $result = str_replace('TREASURY_ACCOUNT_ID_NAME', 'text', $result);
        $result = str_replace('TREASURY_ACCOUNT_ID', 'id', $result);

        echo $result;
    }


    function get_page($page = 1)
    {

        $this->load->library('pagination');

        $data['adopt'] = $this->action == 'adopt' ? true : false;

        $sql = $data['adopt'] ? '' : " AND TREASURY_USER_ID = {$this->user->id} ";
        $sql = $sql . " AND BRANCHES = {$this->user->branch} ";

        $count_rs = $this->convert_cash_bank_model->get_count($sql, null, null);

        $config['base_url'] = base_url('treasury/convert_cash_bank/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $result = $this->convert_cash_bank_model->get_list($sql, $offset, $row);
        $this->date_format($result, 'CONVERT_CASH_BANK_DATE');

        $data["cashes_bank"] = $result;
        $data['adopt'] = $this->action;
        $this->load->view('convert_cash_bank_page', $data);

    }


    function _postedData()
    {


        $convert_cash_bank_type = $this->input->post('convert_cash_bank_type');

        $treasury_account_id = $this->input->post('treasury_account_id');
        $convert_cash_bank_one = $this->input->post('convert_cash_bank_one');

        $convert_cash_bank_account_id = $this->input->post('convert_cash_bank_account_id');
        $convert_cash_bank_total = $this->input->post('convert_cash_bank_total');
        $convert_cash_bank_hints = $this->input->post('convert_cash_bank_hints');


        $this->check_accounts($convert_cash_bank_type, $treasury_account_id, 2);
        $this->check_accounts($convert_cash_bank_type, $convert_cash_bank_account_id, null);


        $result = array(
            array('name' => 'CONVERT_CASH_BANK_TYPE', 'value' => $convert_cash_bank_type, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_ONE', 'value' => $convert_cash_bank_one, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_DATE', 'value' => $this->p_convert_cash_bank_date, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_HINTS', 'value' => $convert_cash_bank_hints, 'type' => '', 'length' => -1),
            array('name' => 'BANK_ACCOUNT_ID', 'value' => $convert_cash_bank_account_id, 'type' => '', 'length' => -1),
            array('name' => 'TREASURY_ACCOUNT_ID', 'value' => $treasury_account_id, 'type' => '', 'length' => -1),
            array('name' => 'TREASURY_USER_ID', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'TREASURY_USER_TRANS', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'TREASURY_USER_TRANS_DATE', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_TOTAL', 'value' => $convert_cash_bank_total, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_CASE', 'value' => 1, 'type' => '', 'length' => -1),
            array('name' => 'BK_FIN_ID', 'value' => $this->p_bk_fin_id, 'type' => '', 'length' => -1)
        );

        return $result;
    }

    function _postedData_Edit()
    {

        $result = array(
            array('name' => 'CONVERT_CASH_BANK_ID', 'value' => $this->p_convert_cash_bank_id, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_TYPE', 'value' => $this->p_convert_cash_bank_type, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_ONE', 'value' => $this->p_convert_cash_bank_one, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_DATE', 'value' => $this->p_convert_cash_bank_date, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_HINTS', 'value' => $this->p_convert_cash_bank_hints, 'type' => '', 'length' => -1),
            array('name' => 'BANK_ACCOUNT_ID', 'value' => $this->p_convert_cash_bank_account_id, 'type' => '', 'length' => -1),
            array('name' => 'TREASURY_ACCOUNT_ID', 'value' => $this->p_treasury_account_id, 'type' => '', 'length' => -1),

            array('name' => 'CONVERT_CASH_BANK_TOTAL', 'value' => $this->p_convert_cash_bank_total, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_CASH_BANK_CASE', 'value' => 1, 'type' => '', 'length' => -1),
            array('name' => 'BK_FIN_ID', 'value' => $this->p_bk_fin_id, 'type' => '', 'length' => -1)

        );

        return $result;
    }

    function check_accounts($type, $account_id, $case)
    {

        $result = $this->accounts_permission_model->get_user_accounts('1', $type, null, null, $case, null);

        foreach ($result as $ac) {
            if ($ac['ACOUNT_ID'] == $account_id) {

                return;
            }
        }

        $this->print_error('البيانات الحسابات غير مطابقة!!');
    }


    function delete()
    {

        echo $this->convert_cash_bank_model->adopt($this->p_id, 0);

    }


}