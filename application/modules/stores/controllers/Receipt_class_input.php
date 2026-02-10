<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 10:26 ص
 */
class receipt_class_input extends MY_Controller
{
    var $record_page = 0;
    var $type_page = 2;
    var $MODEL_NAME = 'receipt_class_input_model';
    var $PAGE_URL = 'stores/receipt_class_input/get_page';
    var $DETAIL_MODEL_NAME = 'receipt_class_input_detail_model';
    var $GROUP_MODEL_NAME = 'receipt_class_input_group_model';
    var $receipt_class_input_id, $receipt_class_input_date, $receipt_class_input_user, $order_id, $store_id, $customer_resource_id, $send_id, $send_case, $send_hints, $send_user_id, $send_user_date, $record_id, $record_case, $record_user_id, $record_user_date, $record_declaration, $record_trans_id, $record_trans_date, $entery_date, $return_case, $return_user, $return_date, $return_hint, $class_input_class_type, $send_date, $account_type, $old_class_input_class_type, $donation_file_id, $cust_id, $purchase_date;

    var $class_id, $class_unit, $amount, $approved_amount, $ser;

    var $group_person_id, $group_person_date, $g_ser, $emp_no, $status;

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAIL_MODEL_NAME);
        $this->load->model($this->GROUP_MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores/stores_model');
        $this->load->model('stores/store_members_model');
        $this->load->model('stores/store_committees_model');
        $this->load->model('payment/customers_model');
        $this->load->model('settings/CustomerAccountInterface_model');
        $this->load->model('settings/users_model');
        $this->record_page = intval($this->input->get_post('case'));
        $this->type_page = intval($this->input->get_post('type'));

        $this->receipt_class_input_id = $this->input->post('receipt_class_input_id');
        $this->receipt_class_input_date = $this->input->post('receipt_class_input_date');
        $this->receipt_class_input_user = $this->input->post('receipt_class_input_user');
        $this->order_id = $this->input->post('order_id');
        $this->store_id = $this->input->post('store_id');
        $this->customer_resource_id = $this->input->post('customer_resource_id');
        $this->send_id = $this->input->post('send_id');
        $this->send_case = $this->input->post('send_case');
        $this->send_hints = $this->input->post('send_hints');
        $this->send_user_id = $this->input->post('send_user_id');
        $this->send_user_date = $this->input->post('send_user_date');
        $this->record_id = $this->input->post('record_id');
        $this->record_case = $this->input->post('record_case');
        $this->record_user_id = $this->input->post('record_user_id');
        $this->record_user_date = $this->input->post('record_user_date');
        $this->record_declaration = $this->input->post('record_declaration');
        $this->record_trans_id = $this->input->post('record_trans_id');
        $this->record_trans_date = $this->input->post('record_trans_date');
        $this->entery_date = $this->input->post('entery_date');
        $this->return_case = $this->input->post('return_case');
        $this->return_user = $this->input->post('return_user');
        $this->return_date = $this->input->post('return_date');
        $this->return_hint = $this->input->post('return_hint');
        $this->class_input_class_type = $this->input->post('class_input_class_type');
        $this->send_date = $this->input->post('send_date');
        $this->old_class_input_class_type = $this->input->post('old_class_input_class_type');
        $this->donation_file_id = $this->input->post('donation_file_id');
        $this->cust_id = $this->input->post('cust_id');
        $this->purchase_date = $this->input->post('purchase_date');
        $this->customer_account_type = $this->input->post('customer_account_type');

        $this->type_matter = $this->input->post('type_matter');
        $this->order_id_ser = $this->input->post('order_id_ser');


        $this->class_id = $this->input->post('h_class_id');
        $this->class_unit = $this->input->post('unit_class_id');
        $this->amount = $this->input->post('amount');
        $this->approved_amount = $this->input->post('approved_amount');
        $this->ser = $this->input->post('h_ser');
        $this->donation_file_ser = $this->input->post('donation_file_ser');

        $this->group_person_id = $this->input->post('group_person_id');
        $this->group_person_date = $this->input->post('group_person_date');
        $this->g_ser = $this->input->post('h_group_ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->status = $this->input->post('status');
        $this->member_note = $this->input->post('member_note');

        //search parameters
        $this->is_recorded = $this->input->get_post('is_recorded');

        $this->is_convert = $this->input->post('is_convert');
        $this->is_return = $this->input->post('is_return');
        $this->has_refused_amounts = $this->input->post('has_refused_amounts');


    }

    function index($page = 1, $is_recorded = -1, $is_convert = -1, $is_return = -1, $has_refused_amounts = -1, $case = 1, $type = 1)
    {


        $data['help'] = $this->help;


        //  $this->record_page= intval($this->input->get_post('case'));
        //  $this->type_page= intval($this->input->get_post('type'));
        $data['type'] = $type;
        $data['case'] = $case;
        if ($this->record_page == 0) {
            $data['title'] = ' إشعار استلام مواد';
            $data['content'] = 'receipt_class_input_index';
        } else {
            if ($this->type_page == 4) {
                $data['title'] = ' أرشيف محاضر الفحص والاستلام';
                $data['content'] = 'receipt_class_input_record_arc_index';
            } else {
                $data['title'] = ' محاضر الفحص و الاستلام';
                $data['content'] = 'receipt_class_input_record_index';
            }
            //  $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
            //    $data['count_all']= count($data['get_all']);
            //   get_count
        }
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $data['page'] = $page;
        $data['is_recorded'] = $is_recorded;
        $data['is_convert'] = $is_convert;
        $data['is_return'] = $is_return;
        $data['has_refused_amounts'] = $has_refused_amounts;
        $data['action'] = 'edit';
        $data['case'] = 1;

        $this->_look_ups($data);
        $this->load->view('template/template', $data);


    }

    function _look_ups(&$data)
    {

        $data['stores'] = $this->stores_model->get_all();
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));


        //   $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        //  $data['unit_class_id'] = $this->constant_details_model->get_list(29);
        $data['class_input_class_type'] = $this->store_committees_model->get_all_by_type(1);
        //  $data['class_input_class_type'] = $this->store_members_model->get_list(29);
        $data['customer_type'] = $this->constant_details_model->get_list(18);
        $data['customer_ids'] = $this->customers_model->get_all_by_type(1);
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(3);
        //  print_r($data['ACCOUNT_TYPES']);
        $data['help'] = $this->help;
        ///tareq
        $data['type_matters_cons'] = $this->constant_details_model->get_list(430);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_js('jquery.hotkeys.js');

    }

    function public_index($text = null, $type = null, $id = '', $name = '', $page = 1)
    {
        $data['text'] = $this->input->get_post('text') ? $this->input->get_post('text') : $text;
        $data['id'] = $this->input->get_post('id') ? $this->input->get_post('id') : $id;
        $data['name'] = $this->input->get_post('name') ? $this->input->get_post('name') : $name;
        $data['page'] = $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        $data['content'] = 'receipt_orders_index';

        $data['txt'] = $text;
        $this->load->view('template/view', $data);
    }

    function get_page($page = 1, $is_recorded = -1, $is_convert = -1, $is_return = -1, $has_refused_amounts = -1, $case = 1, $type = 1)
    {

        $this->record_page = intval($this->input->get_post('case'));
        $this->type_page = intval($this->input->get_post('type'));

        $this->load->library('pagination');

        $is_recorded = $this->check_vars($is_recorded, 'is_recorded');
        $is_convert = $this->check_vars($is_convert, 'is_convert');
        $is_return = $this->check_vars($is_return, 'is_return');
        $type = $this->check_vars($type, 'type_page');
        $data['type'] = $type;
        $data['action'] = 'edit';
        $data['case'] = $this->check_vars($case, 'record_page');
        $has_refused_amounts = $this->check_vars($has_refused_amounts, 'has_refused_amounts');


        $sql = " where 1=1  ";

        $sql .= ($is_recorded != null) ? " and nvl(RECORD_CASE,3)= '{$is_recorded}' " : "";
        $sql .= ($is_convert != null) ? " and decode(is_convert,0,3,is_convert)= '{$is_convert}' " : "";
        $sql .= ($is_return != null) ? " and nvl(return_CASE,3)= '{$is_return}' " : "";

        if ($has_refused_amounts != null) {
            if ($has_refused_amounts == 1) $sql .= " and (select count(RECEIPT_CLASS_INPUT_ID) from receipt_class_input_detail_tb where RECEIPT_CLASS_INPUT_ID = r.RECEIPT_CLASS_INPUT_ID
            and APPROVED_AMOUNT<>AMOUNT)>0 "; else if ($has_refused_amounts == 2) $sql .= " and (select count(RECEIPT_CLASS_INPUT_ID) from receipt_class_input_detail_tb where RECEIPT_CLASS_INPUT_ID = r.RECEIPT_CLASS_INPUT_ID
            and APPROVED_AMOUNT<>AMOUNT)=0 ";
        }


        if ($this->record_page == 0) {
            $sql .= " and '{$this->user->id}' in (select user_id from store_usres_tb where store_id=R.store_id) ";

        } else {

            $sql .= " and (SEND_CASE=2 or RECORD_CASE in (1,2))  ";


            // $sql =' where (SEND_CASE=2 or RECORD_CASE in (1,2)) AND ('.$this->user->emp_no.' in (SELECT emp_no FROM receipt_class_input_group_tb where receipt_class_input_id=R.receipt_class_input_id))  ';
            if ($this->type_page == 1) $sql .= " and ( RECORD_CASE=1) AND (('{$this->user->emp_no}' in (SELECT emp_no FROM receipt_class_input_group_tb where receipt_class_input_id=R.receipt_class_input_id)) or (USER_PKG.GET_USER_EMP_NO=1485 and CLASS_INPUT_CLASS_TYPE=121) ) "; else if ($this->type_page == 2) $sql .= " and ( RECORD_CASE=3)  AND ('{$this->user->emp_no}' in (SELECT emp_no FROM receipt_class_input_group_tb where receipt_class_input_id=R.receipt_class_input_id)) "; else if ($this->type_page == 3) $sql .= " and  RECORD_CASE=3 and is_convert=0 "; else if ($this->type_page == 4) $sql .= " ";
        }

        $count_rs = $this->{$this->MODEL_NAME}->get_count(" receipt_class_input_tb R" . $sql);

        $config['base_url'] = base_url('payment/financial_payment/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;

        $config['per_page'] = $config['total_rows'];
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
        //echo $sql;
        $result = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);

        //  echo "fff".$page." ".$offset ." ". $row;
        $data['get_all'] = $result;
        $this->date_format($data['get_all'], 'RECEIPT_CLASS_INPUT_DATE');


        if ($this->record_page == 0)

            $this->load->view('receipt_class_input_page', $data); else
            /*  if ($this->type_page ==4){
                  $data['title']=' أرشيف محاضر الفحص والاستلام';
                  $data['content']='receipt_class_input_record_arc_index';
              } else{
                  $data['title']=' محاضر الفحص و الاستلام';
                  $data['content']='receipt_class_input_record_index';}*/

            $this->load->view('receipt_class_input_record_page', $data);
    }

    function check_vars($var, $c_var)
    {
        /* OLD CODE
         * $request_no= $this->request_no? $this->request_no:$request_no;
         * $request_no = $request_no == -1 ?null:$request_no;
         */

        // if post take it, else take the parameter
        $var = $this->{$c_var} ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function get_id($id, $action = 'index', $case = 1)
    {

        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->date_format($result, 'RECEIPT_CLASS_INPUT_DATE');//

        $data['can_edit'] = count($result) > 0 ? (($this->user->id == $result[0]['RECEIPT_CLASS_INPUT_USER']) && ($result[0]['SEND_CASE'] == 1)) ? true : false : false;

        $data['receipt_data'] = $result[0];
        $data['action'] = $action;
        $data['case'] = $case;
        //----------------------
        $all = $this->receipt_class_input_group_model->get_emails($id, 1);
        $emails = '';
        foreach ($all as $row) {
            $emails .= $row['EMAIL'] . ',';
        }
        $data['receipt_record_emails'] = substr($emails, 0, -1);

        //--------------------------
        $data['content'] = 'receipt_class_input_show';
        $data['title'] = ' إشعار استلام مواد';


        $this->_look_ups($data);
        //  if ($data['can_edit']==1)
        $data['stores'] = $this->stores_model->get_all_privs();

        $this->load->view('template/template', $data);
    }

    function get_record_id($id, $action = 'index', $case = 1, $type = 1)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        if ($result[0]['RECORD_CASE'] != 1 and $result[0]['RECORD_CASE'] != 2 and $result[0]['RECORD_CASE'] != 3) die('الإشعار غير محول للجنة');

        $this->date_format($result, 'RECEIPT_CLASS_INPUT_DATE');
        $this->date_format($result, 'RECORD_USER_DATE');
        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['RECEIPT_CLASS_INPUT_USER'] && $result[0]['SEND_CASE'] == 1 && $action == 'edit') ? true : false : false;

        $data['receipt_data'] = $result[0];
        $data['action'] = $action;
        $data['case'] = $case;

        $data['type'] = $type;


        $data['content'] = 'receipt_class_input_record_show';
        $data['title'] = 'محضر الفحص و الاستلام';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    /*  function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }*/
    function adopt()
    {
        $id = $this->input->post('id');
        $case = $this->input->post('case');
        $hints = $this->input->post('hints');
        $result = $this->{$this->MODEL_NAME}->adopt($id, $case, $hints);
        $this->return_json($result);
    }

    function record_return()
    {
        $id = $this->input->post('receipt_class_input_id');
        $hints = $this->input->post('record_declaration');
        $order_id = $this->input->post('order_id');
        $record_declaration_list = $this->input->post('record_declaration_list');
        $result = $this->{$this->MODEL_NAME}->record($id, -1, $hints, $record_declaration_list, $order_id);
        echo 1;
    }

    function record()
    {
        if (!$this->check_db_for_stores()) die('CLOSED..');

        $id = $this->input->post('receipt_class_input_id');
        $case = $this->input->post('record_case');
        $hints = $this->input->post('record_declaration');
        $order_id = $this->input->post('order_id');
        $this->group_person_id = $this->input->post('group_person_id');
        $this->group_person_date = $this->input->post('group_person_date');
        $this->g_ser = $this->input->post('h_group_ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->status = $this->input->post('status');
        $record_declaration_list = $this->input->post('record_declaration_list');
        if ($case == 1) $t = 2; else
            $t = 1;


        $result = $this->{$this->MODEL_NAME}->record($id, $t, $hints, $record_declaration_list, $order_id);

        /* if (intval($result) == 1) {


           for ($c = 0; $c < count($this->group_person_id); $c++) {
                $status = (isset($this->status[$c])) ? 1 : 2;

                if ($this->g_ser[$c] == 0)

                    $this->receipt_class_input_group_model->create($this->_postGroupsData($id, $id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));
                else
                    $this->receipt_class_input_group_model->edit($this->_postGroupsData($this->g_ser[$c], $id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));



            }

        }*/ //   if ($case ==1)
        //   $this->_notifyMessage('get_record_id', "stores/receipt_class_input/get_record_id/{$id}/edit/1/1/", "محضر فحص و استلام {$id}");

    }

    function record_cancel()
    {
        $id = $this->input->post('receipt_class_input_id');
        $hints = $this->input->post('record_declaration');
        $order_id = $this->input->post('order_id');
        $record_declaration_list = $this->input->post('record_declaration_list');
        $result = $this->{$this->MODEL_NAME}->record($id, 1, $hints, $record_declaration_list, $order_id);
        echo 1;
    }

    function record_record()
    {
        $id = $this->input->post('receipt_class_input_id');
        $case = $this->input->post('record_case');
        $hints = $this->input->post('record_declaration');
        $order_id = $this->input->post('order_id');
        $this->group_person_id = $this->input->post('group_person_id');
        $this->emp_no = $this->input->post('emp_no');
        $this->group_person_date = $this->input->post('group_person_date');
        $this->g_ser = $this->input->post('h_group_ser');

        $this->class_id = $this->input->post('h_class_id');
        $this->class_unit = $this->input->post('unit_class_id');
        $this->amount = $this->input->post('amount');
        $this->approved_amount = $this->input->post('approved_amount');
        $this->ser = $this->input->post('h_ser');
        $record_declaration_list = $this->input->post('record_declaration_list');
        if ($case == 1) $t = 2; else
            $t = 1;
        $result = $this->{$this->MODEL_NAME}->record($id, $t, $hints, $record_declaration_list, $order_id);


        /*    if (intval($result)==1){


                for($i=0; $i<count($this->class_id); $i++){
                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' ){ // create
                        $detail_seq= $this->receipt_class_input_detail_model->create($this->_postDetailsDataInsert($this->receipt_class_input_id,$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->approved_amount[$i]));

                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' and ( $this->amount[$i]>=$this->approved_amount[$i]) and $this->approved_amount[$i]!='' and $this->approved_amount[$i]>=0){ // edit
                        $detail_seq=   $this->receipt_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i],$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->approved_amount[$i]));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and ($this->amount[$i]=='' or $this->amount[$i]==0) ){ // delete
                        $detail_seq=  $this->receipt_class_input_detail_model->delete($this->ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }
                for($c = 0;$c<count($this->group_person_id);$c++){
                    if ($this->group_person_id[$c]!='' and $this->emp_no[$c] !='' ){
                        if($this->g_ser[$c] == 0)
                            $this->receipt_class_input_group_model->create($this->_postGroupsData($this->receipt_class_input_id,$this->receipt_class_input_id,$this->group_person_id[$c],$this->group_person_date[$c],$this->emp_no[$c],'create'));
                        else
                            $this->receipt_class_input_group_model->edit($this->_postGroupsData($this->g_ser[$c],$this->receipt_class_input_id,$this->group_person_id[$c],$this->group_person_date[$c],$this->emp_no[$c],'edit'));

                    }else{
                        $this->receipt_class_input_group_model->delete($this->g_ser[$c]);


                    }
                }



            }*/
        echo 1;

    }

/////////////////////////////////////////////////////////////////////////////
    function record_record_member()
    {

        $id = $this->input->post('receipt_class_input_id');
        $case = $this->input->post('record_case');
        $hints = $this->input->post('record_declaration');
        $order_id = $this->input->post('order_id');
        $this->group_person_id = $this->input->post('group_person_id');
        $this->emp_no = $this->input->post('emp_no');
        $this->group_person_date = $this->input->post('group_person_date');
        $this->g_ser = $this->input->post('h_group_ser');

        $this->class_id = $this->input->post('h_class_id');
        $this->class_unit = $this->input->post('unit_class_id');
        $this->amount = $this->input->post('amount');
        $this->approved_amount = $this->input->post('approved_amount');
        $this->ser = $this->input->post('h_ser');
        $record_declaration_list = $this->input->post('record_declaration_list');
        if ($case == 1) $t = 2; else
            $t = 1;
        $user_info = $this->users_model->get_user_info($this->user->id);
        $data['EMP_NO'] = $user_info[0]['EMP_NO'];
        for ($c = 0; $c < count($this->group_person_id); $c++) {
            if ($this->emp_no[$c] == $data['EMP_NO']) {
                $result = $this->{$this->MODEL_NAME}->member_record($id, $t, $hints, $record_declaration_list, $this->member_note[$c]);



            }
        }


        /*    if (intval($result)==1){


                for($i=0; $i<count($this->class_id); $i++){
                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' ){ // create
                        $detail_seq= $this->receipt_class_input_detail_model->create($this->_postDetailsDataInsert($this->receipt_class_input_id,$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->approved_amount[$i]));

                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' and ( $this->amount[$i]>=$this->approved_amount[$i]) and $this->approved_amount[$i]!='' and $this->approved_amount[$i]>=0){ // edit
                        $detail_seq=   $this->receipt_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i],$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->approved_amount[$i]));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and ($this->amount[$i]=='' or $this->amount[$i]==0) ){ // delete
                        $detail_seq=  $this->receipt_class_input_detail_model->delete($this->ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }
                for($c = 0;$c<count($this->group_person_id);$c++){
                    if ($this->group_person_id[$c]!='' and $this->emp_no[$c] !='' ){
                        if($this->g_ser[$c] == 0)
                            $this->receipt_class_input_group_model->create($this->_postGroupsData($this->receipt_class_input_id,$this->receipt_class_input_id,$this->group_person_id[$c],$this->group_person_date[$c],$this->emp_no[$c],'create'));
                        else
                            $this->receipt_class_input_group_model->edit($this->_postGroupsData($this->g_ser[$c],$this->receipt_class_input_id,$this->group_person_id[$c],$this->group_person_date[$c],$this->emp_no[$c],'edit'));

                    }else{
                        $this->receipt_class_input_group_model->delete($this->g_ser[$c]);


                    }
                }



            }*/
        //echo $result;
        if ($result == -1) {

            $this->print_error('يجب ادخال ملاحظات أعضاء محضر الفحص والاستلام !!');
        }
        if ($result == -2) {
            $this->print_error('يجب ادخال قائمة الملاحظات !!');


        }
        if ($result == -3) {
            $this->print_error('يجب اعتماد رئيس اللجنة أولا!!');

        } else
        {
            if (intval($result))
            {

                echo $result;
            }
            else
            {
                echo $result;
            }

        }




    }

/// ////////////////////////////////////////////////////////////////////////
    function returnp()
    {
        $id = $this->input->post('id');
        $case = $this->input->post('case');
        $hints = $this->input->post('hints');
        $result = $this->{$this->MODEL_NAME}->returnp($id, $case, $hints);
        $this->return_json($result);
    }

    function transform()
    {
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->transform($id);
        //  $this->return_json($result);
        echo intval($result);
    }

    function edit($page = 1)
    {


        if (!$this->check_db_for_stores()) die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_account_type = $this->input->post('customer_account_type');
            $purchase_date = $this->input->post('purchase_date');
            $account_type = $this->input->post('account_type');
            $cust_id = $this->input->post('cust_id');
            $type_matter = $this->input->post('type_matter');
            $order_id = $this->input->post('order_id');
            $real_order_id = $this->input->post('real_order_id');
            if (!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->amount)) <= 0) {
                $this->print_error('يجب ادخال صنف واحد على الاقل ');
            }/*else if( count(array_filter($this->class_id)) !=  count(array_count_values(array_filter($this->class_id))) ){
                $this->print_error('يوجد تكرار في الاصناف');
            }*/

            if (($account_type == 3) and (($cust_id == '') or ($purchase_date == ''))) {
                $this->print_error('يجب اختيار اسم المورد و إدخال تاريخ الشراء');
            }
            if (($account_type == 2) and ($customer_account_type == '')) {
                $this->print_error('يجب اختيار نوع حساب المستفيد');
            }
            if (($type_matter == 1) and (($order_id == '') or ($real_order_id == ''))) {
                $this->print_error('يجب ادخال امر التوريد');
            }
            // print_r($this->_postedData('edit'));
            //   die;
            $rs = $this->{$this->MODEL_NAME}->edit($this->_postedData('edit'));
            //  die('d');
            if (intval($rs) <= 0) {
                $this->print_error('فشل التعديل' . $rs);
            }

            /*  for($i = 0;$i<count($this->class_id);$i++){//or
                if ($this->class_id[$i] =='' or $this->class_unit[$i] ==''   or   $this->amount[$i] !='' or $this->amount[$i] <= 0  or (!preg_match ('/^[0-9]{1,12}$/',$this->amount[$i])) ){
                //    $this->print_error('يجب إدخال بيانات الأصناف');
                }else {

                 if ($this->amount[$i] !=''){

                    if($this->ser[$i] == 0)

                        $this->receipt_class_input_detail_model->create($this->_postDetailsDataInsert($this->receipt_class_input_id,$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->approved_amount[$i]));
                    else

                        $this->receipt_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i],$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->approved_amount[$i]));
                }else{
                        $this->print_error("h".$this->amount[$i]);
                      $x=  $this->receipt_class_input_detail_model->delete($this->ser[$i]);
                        $this->print_error("h".$x);
                    }
                }



            }
        */
            for ($i = 0; $i < count($this->class_id); $i++) {
                if ($this->ser[$i] == 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->class_unit[$i] != '') { // create
                    $detail_seq = $this->receipt_class_input_detail_model->create($this->_postDetailsDataInsert($this->receipt_class_input_id, $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->amount[$i], $this->donation_file_ser[$i]));

                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->ser[$i] != 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->class_unit[$i] != '' and $this->approved_amount[$i] != '' and $this->approved_amount[$i] >= 0) { // edit
                    if ($this->amount[$i] > $this->approved_amount[$i]) $detail_seq = $this->receipt_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i], $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->approved_amount[$i], $this->donation_file_ser[$i])); else
                        $detail_seq = $this->receipt_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i], $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->amount[$i], $this->donation_file_ser[$i]));
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->ser[$i] != 0 and ($this->amount[$i] == '' or $this->amount[$i] == 0)) { // delete
                    $detail_seq = $this->receipt_class_input_detail_model->delete($this->ser[$i]);
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                }
            }


            if ($this->old_class_input_class_type == $this->class_input_class_type) {
                /////////////
                $member_absants = 0;

                for ($abs = 0; $abs < count($this->group_person_id); $abs++) {
                    $abs_status = (isset($this->status[$abs])) ? 1 : 2;
                    if ($abs_status == 2) {
                        $member_absants++;

                    }

                }

                if ($member_absants > 1) {
                    $this->print_error('الحد الأقصى للتغيب عن توقيع المحضر عضو واحد فقط !!');
                } else
                {
                    for ($c = 0; $c < count($this->group_person_id); $c++) {
                        $status = (isset($this->status[$c])) ? 1 : 2;


                        if ($status == 2 and $this->member_note[$c] == '') {
                            $this->print_error('يجب ادخلال اسباب غياب العضو في بند ملاحظات العضو الغائب!!');

                        } else if ($member_absants > 1) {
                            $this->print_error('الحد الأقصى للتغيب عن توقيع المحضر عضو واحد فقط !!');
                            $member_absants++;
                        }
                        // echo $status;
                        // echo $status."ssss";
                        //    if ($this->group_person_id[$c]!='' ){

                        if ($this->g_ser[$c] == 0) {
                            $f = $this->receipt_class_input_group_model->create($this->_postGroupsData($this->receipt_class_input_id, $this->receipt_class_input_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));
                            if (intval($f) <= 0) {
                                $this->print_error($f);
                            }
                        } else {

                            $e = $this->receipt_class_input_group_model->edit($this->_postGroupsData($this->g_ser[$c], $this->receipt_class_input_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));

                            if (intval($e) <= 0) {
                                $this->print_error($e);
                            }
                        }
                        //    }else{
                        //     $this->receipt_class_input_group_model->delete($this->g_ser[$c]);


                        //   }

                    }
                    /////////////
                } }else {

                for ($c = 0; $c < count($this->group_person_id); $c++) $this->receipt_class_input_group_model->delete($this->g_ser[$c]);

                $res = $this->store_members_model->get_list($this->class_input_class_type);

                for ($c = 0; $c < count($res); $c++) {
                    $gid = $this->receipt_class_input_group_model->create($this->_postGroupsData($this->receipt_class_input_id, $this->receipt_class_input_id, $res[$c]['ID_NO'], $res[$c]['NAME'], $res[$c]['EMP_NO'], 1, '', 'create'));

                    if (intval($gid) <= 0) {
                        $this->print_error($gid);
                    }

                }

            }
            echo '1';


        } else {
            $data['title'] = ' إشعار استلام مواد';
            $data['content'] = 'receipt_class_input_index';

            $data['page'] = $page;
            $data['help'] = $this->help;
            $data['action'] = 'edit';
            $data['case'] = 1;
            $this->load->view('template/template', $data);
        }


    }

    function _postedData($typ = null)
    {

        $result = array(

            array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $this->input->post('receipt_class_input_id'), 'type' => '', 'length' => -1),
            array('name' => 'RECEIPT_CLASS_INPUT_DATE', 'value' => $this->input->post('receipt_class_input_date'), 'type' => '', 'length' => -1),
            array('name' => 'ORDER_ID', 'value' => $this->input->post('order_id'), 'type' => '', 'length' => -1),
            array('name' => 'STORE_ID', 'value' => $this->input->post('store_id'), 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_RESOURCE_ID', 'value' => $this->input->post('customer_resource_id'), 'type' => '', 'length' => -1),
            array('name' => 'SEND_ID', 'value' => $this->input->post('send_id'), 'type' => '', 'length' => -1),
            array('name' => 'SEND_HINTS', 'value' => $this->input->post('send_hints'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_CLASS_TYPE', 'value' => $this->input->post('class_input_class_type'), 'type' => '', 'length' => -1),
            array('name' => 'RECORD_DECLARATION', 'value' => $this->input->post('record_declaration'), 'type' => '', 'length' => -1),
            array('name' => 'SEND_DATE', 'value' => $this->input->post('send_date'), 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $this->input->post('account_type'), 'type' => '', 'length' => -1),
            array('name' => 'DONATION_FILE_ID', 'value' => $this->input->post('donation_file_id'), 'type' => '', 'length' => -1),
            array('name' => 'CUST_ID', 'value' => $this->cust_id, 'type' => '', 'length' => -1),
            array('name' => 'PURCHASE_DATE', 'value' => $this->purchase_date, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE', 'value' => $this->input->post('customer_account_type'), 'type' => '', 'length' => -1),
            array('name' => 'TYPE_MATTER', 'value' => $this->p_type_matter, 'type' => '', 'length' => -1),
            array('name' => 'ORDER_ID_SER', 'value' => $this->p_order_id_ser, 'type' => '', 'length' => -1),
            // array('name' => 'RECORD_DECLARATION_LIST', 'value' => $this->input->post('record_declaration_list'), 'type' => '', 'length' => -1)

        );
        if ($typ == 'create') {
            array_shift($result);
        }
        return $result;
    }

    function create()
    {
        if (!$this->check_db_for_stores()) die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_account_type = $this->input->post('customer_account_type');
            $purchase_date = $this->input->post('purchase_date');
            $account_type = $this->input->post('account_type');
            $cust_id = $this->input->post('cust_id');
            $class_input_class_type = $this->input->post('class_input_class_type');
            $class_id = $this->input->post('h_class_id');

            $class_unit = $this->input->post('unit_class_id');
            $amount = $this->input->post('amount');
            $type_matter = $this->input->post('type_matter');
            $order_id = $this->input->post('order_id');
            $real_order_id = $this->input->post('real_order_id');
            //   $approved_amount = $this->input->post('approved_amount');
            $donation_file_ser = $this->input->post('donation_file_ser');

            //   $refused_amount = $this->input->post('refused_amount');
            if (($account_type == 3) and (($cust_id == '') or ($purchase_date == ''))) {
                $this->print_error('يجب اختيار اسم المورد و إدخال تاريخ الشراء');
            }
            if (($account_type == 2) and ($customer_account_type == '')) {
                $this->print_error('يجب اختيار نوع حساب المستفيد');
            }

            if (!($class_id) or count(array_filter($class_id)) <= 0 or count(array_filter($amount)) <= 0) {
                $this->print_error('يجب ادخال صنف واحد على الاقل ');
            }/*else if( count(array_filter($class_id)) !=  count(array_count_values(array_filter($class_id))) ){
                $this->print_error('يوجد تكرار في الاصناف');
            }*/
            if (($type_matter == 1) and (($order_id == '') or ($real_order_id == ''))) {
                $this->print_error('يجب ادخال امر التوريد');
            }
            $id = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            //  $this->Is_success($result);
            if (intval($id) <= 0) {
                $this->print_error('فشل في الحفظ');
            }

            for ($i = 0; $i < count($class_id); $i++) {
                if ($class_id[$i] != '' and $class_unit[$i] != '' and $amount[$i] != '' and $amount[$i] > 0) {
                    $did = $this->receipt_class_input_detail_model->create($this->_postDetailsDataInsert($id, $class_id[$i], $class_unit[$i], $amount[$i], $amount[$i], $donation_file_ser[$i]));

                    if (intval($did) <= 0) $this->print_error_del($id, 'فشل في حفظ الأصناف' . $did);
                }
                //  else  $this->print_error_del($id,'يجب إدخال بيانات الأصناف');

            }

            $res = $this->store_members_model->get_list($class_input_class_type);

            for ($c = 0; $c < count($res); $c++) {
                $gid = $this->receipt_class_input_group_model->create($this->_postGroupsData($id, $id, $res[$c]['ID_NO'], $res[$c]['NAME'], $res[$c]['EMP_NO'], 1, '', 'create'));


            }

            /*    $group_person_id = $this->input->post('group_person_id');
                $group_person_date = $this->input->post('group_person_date');
                for($c = 0;$c<count($group_person_id);$c++){
                    if ($group_person_id[$c]!='' and $group_person_date[$c] !=''){
                    $gid= $this->receipt_class_input_group_model->create($this->_postGroupsData($id,$group_person_id[$c],$group_person_date[$c],'create'));
                    if(intval($gid) <= 0){
                        print_error_del($id,'');
                        $this->print_error('فشل في حفظ الأعضاء');
                    }
                    }

                }*/
            echo 1;

        } else {
            //  echo  modules::run($this->PAGE_URL);

            $data['title'] = ' إشعار استلام مواد';
            $data['help'] = $this->help;
            $data['content'] = 'receipt_class_input_show';
            $data['action'] = 'index';
            $data['can_edit'] = false;
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_css('combotree.css');

            add_css('jquery.dataTables.css');
            add_js('jquery.dataTables.js');

            //  add_css('jquery.dataTables.css');
            // add_js('jquery.dataTables.js');
            $this->_look_ups($data);
            $data['stores'] = $this->stores_model->get_all_privs();

            $this->load->view('template/template', $data);
        }
    }

    function _postDetailsDataInsert($id, $class_id, $class_unit, $amount, $approved_amount, $donation_file_ser)
    {

        $result = array(

            array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1), array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1), array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1), array('name' => 'APPROVED_AMOUNT', 'value' => $approved_amount, 'type' => '', 'length' => -1), array('name' => 'DONATION_FILE_SER', 'value' => $donation_file_ser, 'type' => '', 'length' => -1)

        );


        return $result;

    }

    function print_error_del($rid = 0, $msg = '')
    {
        $ret = $this->{$this->MODEL_NAME}->delete($rid);
        if ($ret == 1) $this->print_error('لم يتم الحفظ ' . $msg); else
            $this->print_error('لم يتم الحفظ ' . $msg);
    }

    function delete()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete($val);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete($id);
        }

        if ($msg == 1) {
            echo modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }

    function _postGroupsData($ser, $id, $group_person_id, $group_person_date, $emp_no, $status, $member_note, $ty = null)
    {

        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1), array('name' => 'GROUP_PERSON_ID', 'value' => $group_person_id, 'type' => '', 'length' => -1), array('name' => 'GROUP_PERSON_DATE', 'value' => $group_person_date, 'type' => '', 'length' => -1), array('name' => 'EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1), array('name' => 'SOURCE', 'value' => 1, 'type' => '', 'length' => -1), array('name' => 'STATUS', 'value' => $status, 'type' => 1, 'length' => -1), array('name' => 'MEMBER_NOTE', 'value' => $member_note, 'type' => 1, 'length' => -1));

        if ($ty == 'create') {
            array_shift($result);
        }

        return $result;

    }

    function _postDetailsDataEdit($ser, $class_id, $class_unit, $amount, $approved_amount, $donation_file_ser)
    {

        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1), array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1), array('name' => 'APPROVED_AMOUNT', 'value' => $approved_amount, 'type' => '', 'length' => -1), array('name' => 'DONATION_FILE_SER', 'value' => $donation_file_ser, 'type' => '', 'length' => -1)


        );


        return $result;

    }

    function public_get_details($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['rec_details'] = $this->receipt_class_input_detail_model->get_details_all($id);

        $this->load->view('receipt_class_input_detail_page', $data);
    }

    function public_get_details_records($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['rec_details'] = $this->receipt_class_input_detail_model->get_details_all($id);

        $this->load->view('receipt_class_input_record_detail_page', $data);
    }

    function public_get_group_details($id = 0)
    {
        echo 1;
        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($id, 1);

        $this->load->view('receipt_class_input_group_page', $data);
    }

    function public_get_group_adopt_details($id = 0, $record_case, $is_convert, $type)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($id, 1);
        $user_info = $this->users_model->get_user_info($this->user->id);
        $data['EMP_NO'] = $user_info[0]['EMP_NO'];
        $data['record_case'] = $record_case;
        $data['is_convert'] = $is_convert;
        $data['type'] = $type;

        $this->load->view('receipt_class_input_group_adopt_page', $data);
    }

    function public_get_receipt_orders($prm = array())
    {
        if (!$prm) $prm = array('text' => $this->input->get_post('text'), 'id' => $this->input->get_post('id'), 'name' => $this->input->get_post('name'), 'page' => $this->input->get_post('page'));

        $this->load->library('pagination');

        $page = $prm['page'] ? $prm['page'] : 1;

        $config['base_url'] = base_url("stores/classes/public_index/?text={$prm['text']}&id={$prm['id']}&name={$prm['name']}");

        $prm['id'] = $prm['id'] != -1 ? $prm['id'] : null;
        $prm['name'] = $prm['name'] != -1 ? $prm['name'] : null;

        $sql = " where record_case=2";
        if ($prm['id'] != '') $sql = $sql . " and order_id LIKE '%" . $prm['id'] . "%'";
        if ($prm['name'] != '') $sql = $sql . " and ((R.ORDER_ID LIKE '%" . $prm['name'] . "%') or (TO_CHAR(R.RECEIPT_CLASS_INPUT_DATE,'YYYY') LIKE '%" . $prm['name'] . "%') or (R.RECORD_ID LIKE '%" . $prm['name'] . "%'))";
        //   echo $sql;
        //   $count_rs = $this->{$this->MODEL_NAME}->get_count($prm['id'], $prm['name'], $prm['name_en']);
        // echo $sql;
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' receipt_class_input_tb ' . $sql);


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = 5;// $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));

        //   $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($prm['id'], $prm['name'] , $offset, $row );

        $data['get_all'] = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);

        $this->load->view('receipt_class_input_record_search_page', $data);
    }

    function public_get_projects($id = '')
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        // $this->print_error($id);
        $result = $this->receipt_class_input_model->get_account_project_by_order($id);
        //print_r($result);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));

    }

    function public_get_projects_details($id = '', $action = 'index')
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['class_units'] = $this->constant_details_model->get_list(29);
        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['action'] = $action;
        $this->_look_ups($data);
        $data['rec_details'] = $this->receipt_class_input_model->projects_file_det_customer_get($id);
        $this->load->view('receipt_class_input_detail_project', $data);
    }


    function general_manager_adopt($page = 1, $is_recorded = -1, $is_convert = -1, $is_return = -1, $has_refused_amounts = -1, $case = 1, $type = 1)
    {


        $data['help'] = $this->help;

        $data['title'] = 'اعتماد المدير العام | محاضر الفحص والاستلام';
        $data['content'] = 'receipt_class_input_manager_index';
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $data['page'] = $page;
        $data['is_recorded'] = $is_recorded;
        $data['is_convert'] = $is_convert;
        $data['is_return'] = $is_return;
        $data['has_refused_amounts'] = $has_refused_amounts;
        $data['action'] = 'edit';
        $data['type'] = 1;
        $data['case'] = 2;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);


    }


    function get_page_manager($page = 1, $is_recorded = -1, $is_convert = -1, $is_return = -1, $has_refused_amounts = -1, $case = 1, $type = 1)
    {
        $this->record_page = intval($this->input->get_post('case'));
        $this->type_page = intval($this->input->get_post('type'));

        $this->load->library('pagination');

        $is_recorded = $this->check_vars($is_recorded, 'is_recorded');
        $is_convert = $this->check_vars($is_convert, 'is_convert');
        $is_return = $this->check_vars($is_return, 'is_return');
        $type = $this->check_vars($type, 'type_page');
        $data['type'] = $type;
        $data['action'] = 'edit';
        $data['case'] = $this->check_vars($case, 'record_page');
        $has_refused_amounts = $this->check_vars($has_refused_amounts, 'has_refused_amounts');


        $sql = " where 1=1  ";

        $sql .= ($is_recorded != null) ? " and nvl(RECORD_CASE,2)= '{$is_recorded}' " : "";
        $sql .= ($is_convert != null) ? " and decode(is_convert,0,3,is_convert)= '{$is_convert}' " : "";
        $sql .= ($is_return != null) ? " and nvl(return_CASE,3)= '{$is_return}' " : "";
        $sql .= " and (SEND_CASE=2 or RECORD_CASE in (1,2))   and ( RECORD_CASE=2) ";
        //$sql .= "and CLASS_INPUT_CLASS_TYPE != 122";

        /*echo $sql;*/
        $count_rs = $this->{$this->MODEL_NAME}->get_count(" receipt_class_input_tb R" . $sql);

        $config['base_url'] = base_url('stores/Receipt_class_input/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;

        $config['per_page'] = $config['total_rows'];
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

        $result = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);

        //  echo "fff".$page." ".$offset ." ". $row;
        $data['get_all'] = $result;
        $this->date_format($data['get_all'], 'RECEIPT_CLASS_INPUT_DATE');
        $this->load->view('receipt_class_input_manager_page', $data);
    }

    //اعتماد ادارة للوزام والخدمات في حال كان اللجنة لوجسنية
    function SuppliesServicesManagment($page = 1, $is_recorded = -1, $is_convert = -1, $is_return = -1, $has_refused_amounts = -1, $case = 1, $type = 1)
    {
        $data['help'] = $this->help;

        $data['title'] = 'اعتماد ادارة اللوازم والخدمات| محاضر الفحص والاستلام';
        $data['content'] = 'receipt_class_input_services_index';
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $data['page'] = $page;
        $data['is_recorded'] = $is_recorded;
        $data['is_convert'] = $is_convert;
        $data['is_return'] = $is_return;
        $data['has_refused_amounts'] = $has_refused_amounts;
        $data['action'] = 'edit';
        $data['type'] = 1;
        $data['case'] = 2;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);

    }


    function get_page_SuppliesServices($page = 1, $is_recorded = -1, $is_convert = -1, $is_return = -1, $has_refused_amounts = -1, $case = 1, $type = 1)
    {
        $this->record_page = intval($this->input->get_post('case'));
        $this->type_page = intval($this->input->get_post('type'));
        $this->load->library('pagination');
        $is_recorded = $this->check_vars($is_recorded, 'is_recorded');
        $is_convert = $this->check_vars($is_convert, 'is_convert');
        $is_return = $this->check_vars($is_return, 'is_return');
        $type = $this->check_vars($type, 'type_page');
        $data['type'] = $type;
        $data['action'] = 'edit';
        $data['case'] = $this->check_vars($case, 'record_page');
        $sql = " where 1=1  ";
        $sql .= ($is_recorded != null) ? " and nvl(RECORD_CASE,2)= '{$is_recorded}' " : "";
        $sql .= ($is_convert != null) ? " and decode(is_convert,0,3,is_convert)= '{$is_convert}' " : "";
        $sql .= ($is_return != null) ? " and nvl(return_CASE,3)= '{$is_return}' " : "";
        $sql .= " and (SEND_CASE=2 or RECORD_CASE in (1,2))   and ( RECORD_CASE=2) ";
        $sql .= "and R.CLASS_INPUT_CLASS_TYPE = 123";
        //echo $sql;
        $count_rs = $this->{$this->MODEL_NAME}->get_count(" receipt_class_input_tb R" . $sql);
        $config['base_url'] = base_url('stores/Receipt_class_input/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $config['total_rows'];
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
        $result = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);
        $data['get_all'] = $result;
        $this->date_format($data['get_all'], 'RECEIPT_CLASS_INPUT_DATE');
        $this->load->view('receipt_class_input_services_page', $data);
    }

    //اعتماد المدير العام
    function general_manager_adopt_process()
    {
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'STORES_PKG';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rs = $this->rmodel->update('RECEIPT_CLASS_INPUT_TB_MANAGER', $this->_postedDatastatus(false));
            if (intval($rs) <= 0) {
                $this->print_error('error' . '<br>' . $rs);
            }
            echo 1;


        }
    }

    function _postedDatastatus($isCreate = true)
    {
        $record_case = 3;
        $result = array(array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $this->p_receipt_class_input_id, 'type' => '', 'length' => -1), array('name' => 'RECORD_CASE', 'value' => $record_case, 'type' => '', 'length' => -1),);

        if ($isCreate) array_shift($result);

        return $result;
    }

    //الغاء اعتماد المدير العام والارجاع للجنة
    function general_manager_return_process()
    {
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'STORES_PKG';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rs = $this->rmodel->update('RECEIPT_CLASS_CANCEL_MANAGER', $this->_postedDataReturnstatus(false));
            if (intval($rs) <= 0) {
                $this->print_error('error' . '<br>' . $rs);
            }
            echo 1;
        }
    }

    function _postedDataReturnstatus($isCreate = true)
    {
        $record_case = 1;
        $result = array(array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $this->p_receipt_class_input_id, 'type' => '', 'length' => -1), array('name' => 'RECORD_CASE', 'value' => $record_case, 'type' => '', 'length' => -1),);

        if ($isCreate) array_shift($result);

        return $result;
    }


    function public_committee_emails($id)
    {
        $data['committee_emails'] = $this->{$this->MODEL_NAME}->get_committee_emails($id);

        return $data['committee_emails'][0];


    }



}
?>