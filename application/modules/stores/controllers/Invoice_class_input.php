<?php

/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 13/12/14
 * Time: 10:53 ص
 */
class invoice_class_input extends MY_Controller
{
    var $record_page = 0;

    var $MODEL_NAME = 'invoice_class_input_model';
    var $PAGE_URL = 'stores/invoice_class_input/get_page';
    var $DETAIL_MODEL_NAME = 'invoice_class_input_detail_model';

    var $class_input_id, $class_input_date, $class_input_user, $record_id, $order_id, $store_id, $customer_resource_id, $class_input_case
    , $class_input_type, $class_input_user_trans_id, $class_input_user_trans_date, $declaration, $invoice_id, $invoice_date, $invoice_user_entry
    , $invoice_case, $invoice_audit_id, $invoice_audit_date, $invoice_trans_id, $invoice_trans_date, $curr_id, $curr_value, $descount_type
    , $descount_value, $vat_type, $vat_account_id, $vat_value, $enrty_date, $year_order, $class_input_class_type, $invoice_inbox, $invoice_date2, $account_type;

    var $class_id, $class_unit, $amount, $price, $ser, $taxable_det, $vat_type_det, $price_befor_vat, $class_price;

    var $type;

    function  __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAIL_MODEL_NAME);
        $this->load->model('stores_class_input_model');
        $this->load->model('stores_class_input_detail_model');

        $this->load->model('settings/currency_model');

        $this->load->model('settings/CustomerAccountInterface_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores/stores_model');


        $this->record_page = intval($this->input->get_post('case'));
//-----------------------------------------------------
        $this->class_input_id = $this->input->post('class_input_id');
        $this->class_input_date = $this->input->post('class_input_date');
        $this->class_input_user = $this->input->post('class_input_user');
        $this->record_id = $this->input->post('record_id');
        $this->order_id = $this->input->post('order_id');
        $this->store_id = $this->input->post('store_id');
        $this->customer_resource_id = $this->input->post('customer_resource_id');
        $this->class_input_case = $this->input->post('class_input_case');
        $this->class_input_type = $this->input->post('class_input_type');
        $this->class_input_user_trans_id = $this->input->post('class_input_user_trans_id');
        $this->class_input_user_trans_date = $this->input->post('class_input_user_trans_date');
        $this->declaration = $this->input->post('declaration');
        $this->invoice_id = $this->input->post('invoice_id');
        $this->invoice_date = $this->input->post('invoice_date');
        $this->invoice_user_entry = $this->input->post('invoice_user_entry');
        $this->invoice_case = $this->input->post('invoice_case');
        $this->invoice_audit_id = $this->input->post('invoice_audit_id');
        $this->invoice_audit_date = $this->input->post('invoice_audit_date');
        $this->invoice_trans_id = $this->input->post('invoice_trans_id');
        $this->invoice_trans_date = $this->input->post('invoice_trans_date');
        $this->vat_type = $this->input->post('vat_type');
        $this->vat_account_id = $this->input->post('vat_account_id');
        $this->vat_value = $this->input->post('vat_value');
        $this->enrty_date = $this->input->post('enrty_date');
        $this->year_order = $this->input->post('year_order');
        $this->class_input_class_type = $this->input->post('class_input_class_type');
        $this->invoice_inbox = $this->input->post('invoice_inbox');
        $this->invoice_date2 = $this->input->post('invoice_date2');
        $this->customer_account_type= $this->input->post('customer_account_type');

        $this->class_id = $this->input->post('h_class_id');
        $this->class_unit = $this->input->post('unit_class_id');
        $this->amount = $this->input->post('amount');
        $this->price = $this->input->post('price');
        $this->ser = $this->input->post('h_ser');
        $this->taxable_det = $this->input->post('taxable_det');
        $this->class_price = $this->input->post('price_class_id');

        $this->type = intval($this->input->get_post('case'));
    }

    function _look_ups(&$data)
    {

        $data['customer_type'] = $this->constant_details_model->get_list(18);
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        //   $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        //  $data['unit_class_id'] = $this->constant_details_model->get_list(29);
        //  $data['class_input_class_type'] = $this->store_committees_model->get_all();
        $data['currency'] = $this->currency_model->get_all();
        $data['class_input_type'] = $this->constant_details_model->get_list(31);
        $data['vat_account_id_c'] = $this->get_system_info('VAT_ACCOUNT_ID', '1'); //$this->constant_details_model->get_list(32);
        $data['vat_value_c'] = $this->get_system_info('VAT_VALUE', '1'); //$this->constant_details_model->get_list(33);
        $data['stores'] = $this->stores_model->get_all();
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(3);
        $data['help'] = $this->help;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');
        add_css('jquery.dataTables.css');

    }

    function index($page = 1)
    {


        $data['help'] = $this->help;

        $data['content'] = 'invoice_class_input_index';
        $data['title'] = 'فواتير الشراء';


        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $data['page'] = $page;
        $data['action'] = 'edit';
        //   $data['case'] = 1;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);

    }

    function transfer()
    {
        $id = $this->input->post('id');

        $result = $this->{$this->MODEL_NAME}->transfer($id);
        $this->return_json($result);
    }

    function get_page($page = 1, $case = 1)
    {
        //   $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        //    $this->load->view('receipt_class_input_page',$data);


        $this->load->library('pagination');
        $sql = ' where INVOICE_CASE !=0';
        $type = intval($this->input->get_post('case'));
        $data['case'] = $type;
        if ($type == 1) $sql .= " and  invoice_case  in (1,2) ";
        else if ($type == 2) $sql .= " and  invoice_case in (1,2) and nvl(invoice_id,'0')!= '0' ";
        else if ($type == 3) $sql .= " and  invoice_case in (2,3) and nvl(invoice_id,'0')!= '0' ";
        else if ($type == 4) $sql .= " ";
        //and nvl(invoice_id,0)!=0
        //   $sql = $case == 1? " where   ENTRY_USER={$this->user->id} " : " where 1=1 ";
        //   $sql .= " and FINANCIAL_PAYMENT_CASE between {$case}-1 and {$case}  ";
        //   echo $sql;
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' invoice_class_input_tb ' . $sql);

        $config['base_url'] = base_url('payment/financial_payment/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        //   echo $config['total_rows']."total_rows";
        //  $config['per_page'] =$this->page_size;
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
        //  echo $offset."off";
        $row = (($page * $config['per_page']));
        //   echo $config['per_page']."row";
        $result = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);
//echo $result;
        //  echo "fff".$page." ".$offset ." ". $row;
        $data['get_all'] = $result;
        $this->date_format($data['get_all'], 'CLASS_INPUT_DATE');

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('invoice_class_input_page', $data);

    }

    function get_id($id, $action = 'index', $case = 1)
    {

        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->date_format($result, 'CLASS_INPUT_DATE');

        $data['receipt_data'] = $result[0];
        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['CLASS_INPUT_USER'] && $result[0]['CLASS_INPUT_CASE'] == 1 && $action == 'edit') ? true : false : false;
        $data['action'] = $action;
        $data['case'] = $case;

        $data['content'] = 'invoice_class_input_show';

        $data['title'] = 'فواتير الشراء';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }


    function update_case()
    {
        $id = $this->input->post('id');
        $case = $this->input->post('case');

        $result = $this->{$this->MODEL_NAME}->update_case($id, $case);
        $this->return_json($result);
    }

    function back_case()
    {
        $id = $this->input->post('id');
        $case = $this->input->post('case');

        $result = $this->{$this->MODEL_NAME}->back_case($id, $case);
        $this->return_json($result);
    }

    function print_error_del($rid = 0, $msg = '')
    {
        $ret = $this->{$this->MODEL_NAME}->delete($rid);
        if ($ret == 1)
            $this->print_error('لم يتم الحفظ ' . $msg);
        else
            $this->print_error('لم يتم الحفظ ' . $msg);
    }

    function create($class_input_seqs = '')
    {
        /*   if(!$this->check_db_for_stores())
               die('CLOSED..');*/
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $class_id = $this->input->post('h_class_id');
            $customer_account_type= $this->input->post('customer_account_type');
            $account_type= $this->input->post('account_type');
            $class_unit = $this->input->post('unit_class_id');
            $amount = $this->input->post('amount');
            $price = $this->input->post('price');
            $taxable_det = $this->input->post('taxable_det');
            $class_price = $this->input->post('price_class_id');

            if (($account_type==2) and ($customer_account_type=='') ){
                $this->print_error('يجب اختيار نوع حساب المستفيد');
            }

            if (!($class_id) or count(array_filter($class_id)) <= 0 or count(array_filter($amount)) <= 0) {
                $this->print_error('يجب ادخال صنف واحد على الاقل ');
            } else if (count(array_filter($class_id)) != count(array_count_values(array_filter($class_id)))) {
                $this->print_error('يوجد تكرار في الاصناف');
            }

            $id = $this->{$this->MODEL_NAME}->create($this->_postedDataInsert());

            //  $this->Is_success($result);
            if (intval($id) <= 0) {
                $this->print_error('فشل في الحفظ' . $id);
            }

            /*   for($i = 0;$i<count($class_id);$i++){
                   if ($class_id[$i] !='' and $class_unit[$i] !='' and  $amount[$i] !='' and  $amount[$i] >0  and preg_match ('/^[0-9]{1,12}$/',$amount[$i])){



                       $did=  $this->invoice_class_input_detail_model->create($this->_postDetailsDataInsert($id,$class_id[$i],$class_unit[$i],$amount[$i],$price[$i],$taxable_det[$i],$class_price[$i]));
   //echo "<pre>"; print_r($this->_postDetailsDataInsert($id,$class_id[$i],$class_unit[$i],$amount[$i],0,0,0)); echo "</pre>";

                       if(intval($did) <= 0){
                           print_error_del($id,'');
                           $this->print_error('فشل في حفظ الأصناف');
                       }
                   } else{  print_error_del($id,'');
                       $this->print_error('يجب إدخال بيانات الأصناف');}
               }*/
            for ($i = 0; $i < count($class_id); $i++) {
                if ($class_id[$i] != '' and $class_unit[$i] != '' and $amount[$i] != '' and $amount[$i] > 0) {
                    $did = $this->invoice_class_input_detail_model->create($this->_postDetailsDataInsert($id, $class_id[$i], $class_unit[$i], $amount[$i], $price[$i], $taxable_det[$i], $class_price[$i]));

                    if (intval($did) <= 0)
                        $this->print_error_del($id, 'فشل في حفظ الأصناف' . $did);
                }
                //  else  $this->print_error_del($id,'يجب إدخال بيانات الأصناف');

            }

            $idx = $this->{$this->MODEL_NAME}->invoice_validate($id);
            if (intval($idx) <= 0) {
                $this->print_error('فشل في حفظ القيد ؟!' || $idx);
            }
            echo 1;

        } else {
            //  echo  modules::run($this->PAGE_URL);
            $data['class_input_seqs'] = $class_input_seqs;
            if ($class_input_seqs != '') {
                $result = $this->stores_class_input_model->storesInvoice($class_input_seqs);
                if (count($result) == 1) {
                    $this->date_format($result, 'CLASS_INPUT_DATE');
                    $data['receipt_data'] = $result[0];
                } else {
                    $this->print_error('تأكد من السندات المخزنية');
                    die();

                }
            }
            //storesInvoice($class_input_seqs)
            $data['title'] = 'إدخال فاتورة شراء جديدة';
            $data['help'] = $this->help;
            $data['content'] = 'invoice_class_input_show';
            $data['action'] = 'index';
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_css('combotree.css');

            add_css('jquery.dataTables.css');
            add_js('jquery.dataTables.js');

            //  add_css('jquery.dataTables.css');
            // add_js('jquery.dataTables.js');
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

    function  _postDetailsDataInsert($id, $class_id, $class_unit, $amount, $price, $taxable_det, $class_price)
    {

        $result = array(

            array('name' => 'STORES_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => 0, 'type' => '', 'length' => -1),
            array('name' => 'TAXABLE_DET', 'value' => $taxable_det, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_PRICE', 'value' => $class_price, 'type' => '', 'length' => -1)
        );


        return $result;

    }

    function  _postDetailsDataEdit($ser, $id, $class_id, $class_unit, $amount, $price, $taxable_det, $class_price)
    {

        $result = array(

            array('name' => 'STORES_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => 1, 'type' => '', 'length' => -1),
            array('name' => 'TAXABLE_DET', 'value' => $taxable_det, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_PRICE', 'value' => $class_price, 'type' => '', 'length' => -1)
        );

//PRINT_R($result);

        return $result;

    }

    function edit($page = 1)
    {
        /* if(!$this->check_db_for_stores())
             die('CLOSED..');*/

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (($this->account_type==2) and ($this->customer_account_type=='') ){
                $this->print_error('يجب اختيار نوع حساب المستفيد');
            }

            if (!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->amount)) <= 0) {
                $this->print_error('يجب ادخال صنف واحد على الاقل ');
            } else if (count(array_filter($this->class_id)) != count(array_count_values(array_filter($this->class_id)))) {
                $this->print_error('يوجد تكرار في الاصناف');
            }


            $rs = $this->{$this->MODEL_NAME}->edit($this->_postedDataEdit());

            if (intval($rs) <= 0) {
                $this->print_error('فشل في حفظ التعديل ؟!' . $rs);
            }
            for ($i = 0; $i < count($this->class_id); $i++) {
                if ($this->ser[$i] == 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->class_unit[$i] != '') { // create
                    $detail_seq = $this->invoice_class_input_detail_model->create($this->_postDetailsDataInsert($this->class_input_id, $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->price[$i], $this->taxable_det[$i], $this->class_price[$i]));

                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->ser[$i] != 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->class_unit[$i] != '') { // edit
                    //    ECHO($this->class_price[$i]."JJJ");
                    $detail_seq = $this->invoice_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i], $this->class_input_id, $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->price[$i], $this->taxable_det[$i], $this->class_price[$i]));
                    //  echo ($detail_seq."g");
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->ser[$i] != 0 and ($this->amount[$i] == '' or $this->amount[$i] == 0)) { // delete
                    $detail_seq = $this->invoice_class_input_detail_model->delete($this->ser[$i]);
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                }
            }

            /*   for($i = 0;$i<count($this->class_id);$i++){
                    if ($this->class_id[$i] =='' and $this->class_unit[$i] =='' and  $this->amount[$i] ==''){
                        $this->print_error('يجب إدخال بيانات الأصناف');
                    }else {



                        if($this->ser[$i] == 0){
                            $this->invoice_class_input_detail_model->create($this->_postDetailsDataInsert( $this->class_input_id,$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->price[$i],$this->taxable_det[$i],$this->class_price[$i]));

                        }  else{

                            $this->invoice_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i], $this->class_input_id,$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->price[$i],$this->taxable_det[$i],$this->class_price[$i]));
                        }
                    }


                }*/

            $idx = $this->{$this->MODEL_NAME}->invoice_validate($this->class_input_id);
            if (intval($idx) <= 0) {
                $this->print_error('فشل في حفظ القيد ؟!' . $idx);
            }
            echo "1";


        } else {
            $data['title'] = 'فاتورة الشراء';
            $data['content'] = 'invoice_class_input_index';
            $data['page'] = $page;
            $data['help'] = $this->help;
            $data['action'] = 'edit';
            $data['case'] = 1;
            $this->load->view('template/template', $data);
        }


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

    function _postedDataInsert()
    {
        $result = array(
            array('name' => 'CLASS_INPUT_DATE', 'value' => $this->input->post('class_input_date'), 'type' => '', 'length' => -1),
            array('name' => 'RECORD_ID', 'value' => $this->input->post('record_id'), 'type' => '', 'length' => -1),
            array('name' => 'ORDER_ID', 'value' => $this->input->post('order_id'), 'type' => '', 'length' => -1),
            array('name' => 'STORE_ID', 'value' => $this->input->post('store_id'), 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_RESOURCE_ID', 'value' => $this->input->post('customer_resource_id'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_TYPE', 'value' => $this->input->post('class_input_type'), 'type' => '', 'length' => -1),
            array('name' => 'DECLARATION', 'value' => $this->input->post('declaration'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_ID', 'value' => $this->input->post('invoice_id'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_CASE', 'value' => 1, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->input->post('curr_id'), 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $this->input->post('curr_value'), 'type' => '', 'length' => -1),
            array('name' => 'DESCOUNT_TYPE', 'value' => $this->input->post('descount_type'), 'type' => '', 'length' => -1),
            array('name' => 'DESCOUNT_VALUE', 'value' => $this->input->post('descount_value'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_TYPE', 'value' => $this->input->post('vat_type'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_ACCOUNT_ID', 'value' => $this->input->post('vat_account_id'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_VALUE', 'value' => $this->input->post('vat_value'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_CLASS_TYPE', 'value' => $this->input->post('class_input_class_type'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_INBOX', 'value' => $this->input->post('invoice_inbox'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_DATE2', 'value' => $this->input->post('invoice_date2'), 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $this->input->post('account_type'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_DATE', 'value' => $this->input->post('invoice_date'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_SEQS', 'value' => $this->input->post('class_input_seqs'), 'type' => '', 'length' => -1),
            array('name'=>'CUSTOMER_ACCOUNT_TYPE','value'=>$this->input->post('customer_account_type') ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function _postedDataEdit()
    {
        $result = array(

            array('name' => 'CLASS_INPUT_ID', 'value' => $this->input->post('class_input_id'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_DATE', 'value' => $this->input->post('class_input_date'), 'type' => '', 'length' => -1),
            array('name' => 'RECORD_ID', 'value' => $this->input->post('record_id'), 'type' => '', 'length' => -1),
            array('name' => 'ORDER_ID', 'value' => $this->input->post('order_id'), 'type' => '', 'length' => -1),
            array('name' => 'STORE_ID', 'value' => $this->input->post('store_id'), 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_RESOURCE_ID', 'value' => $this->input->post('customer_resource_id'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_TYPE', 'value' => $this->input->post('class_input_type'), 'type' => '', 'length' => -1),
            array('name' => 'DECLARATION', 'value' => $this->input->post('declaration'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_ID', 'value' => $this->input->post('invoice_id'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_CASE', 'value' => $this->input->post('invoice_case'), 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->input->post('curr_id'), 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $this->input->post('curr_value'), 'type' => '', 'length' => -1),
            array('name' => 'DESCOUNT_TYPE', 'value' => $this->input->post('descount_type'), 'type' => '', 'length' => -1),
            array('name' => 'DESCOUNT_VALUE', 'value' => $this->input->post('descount_value'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_TYPE', 'value' => $this->input->post('vat_type'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_ACCOUNT_ID', 'value' => $this->input->post('vat_account_id'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_VALUE', 'value' => $this->input->post('vat_value'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_CLASS_TYPE', 'value' => $this->input->post('class_input_class_type'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_INBOX', 'value' => $this->input->post('invoice_inbox'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_DATE2', 'value' => $this->input->post('invoice_date2'), 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $this->input->post('account_type'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_DATE', 'value' => $this->input->post('invoice_date'), 'type' => '', 'length' => -1),
            array('name'=>'CUSTOMER_ACCOUNT_TYPE','value'=>$this->input->post('customer_account_type') ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function public_get_details_invoice($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['rec_details'] = $this->invoice_class_input_detail_model->get_details_all($id);

        $this->load->view('stores_class_input_detail_invoice_page', $data);
    }

    function public_get_details_transfer($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;


        $data['rec_details'] = $this->stores_class_input_detail_model->get_details_all_stores($id);

        $this->load->view('stores_class_input_detail_invoice_page', $data);
    }

    function public_has_other_invoice($customer_resource_id = 0, $invoice_id = 0, $class_input_id = 0)
    {
        $customer_resource_id = $this->input->post('customer_resource_id') ? $this->input->post('customer_resource_id') : $customer_resource_id;
        $invoice_id = $this->input->post('invoice_id') ? $this->input->post('invoice_id') : $invoice_id;
        $class_input_id = $this->input->post('class_input_id') ? $this->input->post('class_input_id') : $class_input_id;
        $result = $this->invoice_class_input_model->customer_has_other_invoice($class_input_id, $customer_resource_id, $invoice_id);

        echo $result;

    }
    ////////////////////////////
    //custom invoice modalPopUp

    function public_invoice_popup($customer_resource_id){
        $this->_look_ups($data);
        $data['title']='فواتير الشراء';
        $sql = " where INVOICE_CASE !=0 and CUSTOMER_RESOURCE_ID='{$customer_resource_id}'";
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' invoice_class_input_tb ' . $sql);
        $result = $this->{$this->MODEL_NAME}->get_list($sql, 0, $count_rs[0]['NUM_ROWS']);
        $data['action'] = 'edit';
        $data['content'] = 'invoice_popup_index';
        $data['get_all'] = $result;
        $this->load->view('template/view',$data);
    }
}

?>
