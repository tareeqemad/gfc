<?php
//if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 03/03/15
 * Time: 12:07 م
 */
class Orders extends MY_Controller
{

    var $MODEL_NAME = 'orders_model';
    var $DETAILS_MODEL_NAME;//= 'orders_detail_model';
    //var $ITEMS_MODEL_NAME= 'orders_items_model';
    var $PAGE_URL = 'purchases/orders/get_page';


    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        //   $this->load->model($this->DETAILS_MODEL_NAME);
        //   $this->load->model($this->ITEMS_MODEL_NAME);
        $this->load->model('settings/currency_model');
        $this->load->model('purchase_order_detail_model');
        $this->load->model('purchase_order_items_model');
        $this->load->model('purchase_order_model');

        $this->order_id = $this->input->post('order_id');
        $this->adopt = $this->input->post('adopt');
        $this->entry_user = $this->input->post('entry_user');
        $this->purchase_order_id = $this->input->post('purchase_order_id');
        $this->purchase_order_id1 = $this->input->get_post('purchase_order_id1');
        $this->customer_id = $this->input->post('customer_id');
        $this->curr_id = $this->input->post('curr_id');
        $this->customer_curr_id = $this->input->post('customer_curr_id');
        $this->notes = $this->input->post('notes');
        $this->order_purpose = $this->input->get_post('order_purpose');
        $this->curr_value = $this->input->post('curr_value');
        $this->order_text_t = $this->input->post('order_text_t');
        $this->bank_id = $this->input->post('bank_id');
        $this->account_id = $this->input->post('account_id');
        $this->transform_date = $this->input->post('transform_date');
        $this->order_stat = $this->input->post('order_stat');
        $this->real_order_id = $this->input->post('real_order_id');


        /*  detail */
        $this->ser = $this->input->post('h_ser');
        $this->class_id = $this->input->post('h_class_id');
        $this->unit_class_id = $this->input->post('unit_class_id');
        $this->customer_price = $this->input->post('customer_price');
        $this->amount = $this->input->post('amount');
        $this->price = $this->input->post('price');
        $this->approved_amount = $this->input->post('approved_amount');
        $this->note = $this->input->post('note');
        $this->order_date = $this->input->post('order_date');


        $this->from_date = $this->input->post('from_date');
        $this->to_date = $this->input->post('to_date');

        /*  detail items */
        $this->purchase_ser = $this->input->post('purchase_ser');

        if ($this->order_purpose != null and $this->order_purpose != 1 and $this->order_purpose != 2) die('construct');

        if ($this->order_purpose == 1) $this->DETAILS_MODEL_NAME = 'orders_detail_model'; else if ($this->order_purpose == 2) $this->DETAILS_MODEL_NAME = 'orders_items_model'; else
            $this->DETAILS_MODEL_NAME = 'orders_items_model';
        $this->load->model($this->DETAILS_MODEL_NAME);

        //  ECHO $this->order_purpose ;
    }

    function index($page = 1, $order_id = -1, $purchase_order_id = -1, $customer_id = -1, $order_purpose = -1, $curr_id = -1, $customer_curr_id = -1, $order_text_t = -1, $adopt = -1, $from_date = -1, $to_date = -1)
    {
        if ($this->order_purpose == 1) $data['title'] = ' أوامر التوريد- مشتريات ';
        /* else
             if ($this->order_purpose==2 )
                 $data['title']=' أوامر التوريد - أعمال مدنية ';*/

        $data['content'] = 'orders_index';
        $data['page'] = $page;
        $data['order_id'] = $order_id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['customer_id'] = $customer_id;
        $data['adopt'] = $adopt;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['order_purpose'] = $this->order_purpose;
        //  echo $data['order_purpose']."dddd";
        $data['curr_id'] = $curr_id;
        $data['customer_curr_id'] = $customer_curr_id;
        $data['order_text_t'] = $order_text_t;

        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/constant_details_model');
        $data['help'] = $this->help;
        $data['order_purposes'] = $this->constant_details_model->get_list(66);
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['currency'] = $this->currency_model->get_all();
        $data['adopts'] = $this->constant_details_model->get_list(325);
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        //   add_css('select2_metro_rtl.css');
        //    add_js('select2.min.js');
        //   add_css('combotree.css');
        add_js('jquery.hotkeys.js');
        //   add_css('jquery.dataTables.css');
    }

    function get_page($page = 1, $order_id = -1, $purchase_order_id = -1, $customer_id = -1, $order_purpose = -1, $curr_id = -1, $customer_curr_id = -1, $order_text_t = -1, $adopt = -1, $from_date = -1, $to_date = -1)
    {
        $this->load->library('pagination');

        $order_id = $this->check_vars($order_id, 'order_id');
        $purchase_order_id = $this->check_vars($purchase_order_id, 'purchase_order_id');
        $customer_id = $this->check_vars($customer_id, 'customer_id');
        //echo($customer_id);
        //exit;
        $order_purpose = $this->check_vars($order_purpose, 'order_purpose');
        $this->order_purpose = $order_purpose;
        $curr_id = $this->check_vars($curr_id, 'curr_id');
        $customer_curr_id = $this->check_vars($customer_curr_id, 'customer_curr_id');
        $order_text_t = $this->check_vars($order_text_t, 'order_text_t');
        $adopt = ($this->input->post('adopt') != '') ? $this->input->post('adopt') : $adopt;//$this->check_vars($adopt,'adopt');
        $from_date = $this->check_vars($from_date, 'from_date');
        $to_date = $this->check_vars($to_date, 'to_date');


        //$where_sql= "  AND P.ORDER_PURPOSE= ".$this->order_purpose ;
        $where_sql = "  AND 1=1 ";
        $where_sql .= ($order_id != null) ? " and order_id= {$order_id} " : '';
        $where_sql .= ($purchase_order_id != null) ? " and P.PURCHASE_ORDER_ID= {$purchase_order_id} " : '';
        $where_sql .= ($customer_id != null) ? " and customer_id= '{$customer_id}' " : '';
        //  $where_sql.= ($order_purpose!= null)? " and order_purpose= {$order_purpose} " : '';
        $where_sql .= ($curr_id != null) ? " and P.QUOTE_CURR_ID= {$curr_id} " : '';
        $where_sql .= ($customer_curr_id != null) ? " and customer_curr_id= {$customer_curr_id} " : '';
        $where_sql .= ($order_text_t != null) ? " and TO_CHAR(M.ENTRY_DATE,'YYYY')||'/'||M.ORDER_TEXT  like '%{$order_text_t}%' " : '';
        $where_sql .= ($adopt != '-1') ? " and M.adopt= {$adopt} " : '';
        $where_sql .= ($from_date != null) ? "   AND TRUNC( M.ENTRY_DATE) >=to_date('{$from_date}','DD/MM/YYYY') " : '';
        $where_sql .= ($to_date != null) ? "  AND TRUNC( M.ENTRY_DATE) <=to_date('{$to_date}','DD/MM/YYYY') " : '';
        //echo $where_sql;
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' ORDERS_TB  M,PURCHASE_ORDER_TB p  WHERE M.purchase_order_id=P.purchase_order_id ' . $where_sql);

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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('orders_page', $data);
    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = $this->{$c_var} ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            $result = $this->purchase_order_model->get($this->purchase_order_id);
            if ($result[0]['ORDER_PURPOSE'] == 1) {

                $this->DETAILS_MODEL_NAME = 'orders_detail_model';

            } else if ($result[0]['ORDER_PURPOSE'] == 2) {
                $this->DETAILS_MODEL_NAME = 'orders_items_model';

            } else {
                die('erorr get');
            }
            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            } else {

                for ($i = 0; $i < count($this->class_id); $i++) {
                   if ($this->ser[$i] == 0 and $this->class_id[$i] != '' and $this->customer_price[$i] != '' and $this->price[$i] != '' and $this->approved_amount[$i] > 0) { // create

                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_posteddata_details_insert($this->class_id[$i], $this->unit_class_id[$i], $this->customer_price[$i], $this->price[$i], $this->approved_amount[$i], $this->note[$i], $this->purchase_ser[$i], $this->order_date[$i], $result[0]['ORDER_PURPOSE'],$this->p_amount[$i]));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->ser[$i] != 0 and $this->class_id[$i] != '' and $this->customer_price[$i] != '' and $this->price[$i] != '' and $this->approved_amount[$i] > 0) {  // edit
                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details_update($this->ser[$i], $this->class_id[$i], $this->unit_class_id[$i], $this->customer_price[$i], $this->price[$i], $this->approved_amount[$i], $this->note[$i], $this->order_date[$i], $result[0]['ORDER_PURPOSE']));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->ser[$i] != 0 and ($this->approved_amount[$i] == '' or $this->approved_amount[$i] == 0)) { // delete
                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser[$i]);
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }
    }

    function _post_validation($isEdit = false)
    {


        if ($this->order_id == '' and $isEdit) {
            $this->print_error('يجب ادخال جميع البيانات');

        }
        if ($this->real_order_id == '') {
            $this->print_error('يجب ادخال رقم أمر التوريد!!');
        } /*else  if( $this->account_id=='' and  $this->bank_id=='' ){
            $this->print_error('يجب إدخال بيانات الحساب');

        }
*/ else if (!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->approved_amount)) <= 0) {
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        } else if (1) {
            $all_class = array();
            for ($i = 0; $i < count($this->class_id); $i++) {

                $all_class[] = $this->class_id[$i];
                if ($this->class_id[$i] == '') $this->print_error('اختر الصنف'); elseif ($this->unit_class_id[$i] == '') $this->print_error('اختر الوحدة');
                elseif ($this->p_approved_amount[$i] == '') $this->print_error('ادخل كمية الترسية !!');
                elseif ($this->p_approved_amount[$i] >$this->p_amount[$i]) $this->print_error('كمية الترسية أكبر من كمية طلب الشراء !!');
                elseif ($this->p_customer_price[$i] == '') $this->print_error('ادخل السعر من المورد !!');
                elseif ($this->p_price[$i] == '') $this->print_error('ادخل السعر الموحد!!');
                elseif ($this->order_date[$i] == '') $this->print_error('أدخل تاريخ التوريد');

            }
        }

        if (count(array_filter($all_class)) != count(array_count_values(array_filter($all_class)))) {
            $this->print_error('يوجد تكرار في الاصناف');
        }
    }

    function _postedData($typ = null)
    {

        $result = array(array('name' => 'ORDER_ID', 'value' => $this->order_id, 'type' => '', 'length' => -1), array('name' => 'PURCHASE_ORDER_ID', 'value' => $this->purchase_order_id, 'type' => '', 'length' => -1), array('name' => 'CUSTOMER_ID', 'value' => $this->customer_id, 'type' => '', 'length' => -1), array('name' => 'CUSTOMER_CURR_ID', 'value' => $this->customer_curr_id, 'type' => '', 'length' => -1), array('name' => 'NOTES', 'value' => $this->notes, 'type' => '', 'length' => -1), array('name' => 'CURR_VALUE', 'value' => $this->curr_value, 'type' => '', 'length' => -1), array('name' => 'BANK_ID', 'value' => $this->bank_id, 'type' => '', 'length' => -1), array('name' => 'ACCOUNT_ID', 'value' => $this->account_id, 'type' => '', 'length' => -1), array('name' => 'TRANSFORM_DATE', 'value' => $this->transform_date, 'type' => '', 'length' => -1), array('name' => 'ORDER_STAT', 'value' => $this->order_stat, 'type' => '', 'length' => -1), array('name' => 'REAL_ORDER_ID', 'value' => $this->real_order_id, 'type' => '', 'length' => -1));
        if ($typ == 'create') unset($result[0]);


        return $result;
    }

    function get($id, $order_purpose)
    {


        $result = $this->{$this->MODEL_NAME}->get($id);
        if (!(count($result) == 1)) die();
        $p_result = $this->purchase_order_model->get($result[0]['PURCHASE_ORDER_ID']);
        if ($p_result[0]['ORDER_PURPOSE'] == 1) {
            $this->DETAILS_MODEL_NAME = 'orders_detail_model';

        } else if ($p_result[0]['ORDER_PURPOSE'] == 2) {
            $this->DETAILS_MODEL_NAME = 'orders_items_model';

        } else {
            die('erorr get');
        }
        $this->load->model($this->DETAILS_MODEL_NAME);
        $data['title'] = ' أمر  التوريد- مشتريات ';
        $data['order_purpose'] = $p_result[0]['ORDER_PURPOSE'];//$order_purpose;
        $data['orders_data'] = $result;
        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER']) ? true : false : false;
        $data['action'] = 'edit';
        $data['isCreate'] = false;

        $data['content'] = 'orders_show';


        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
//print_r($this->_postedData('create'));
            //    exit;
            $this->order_id = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            $result = $this->purchase_order_model->get($this->purchase_order_id);
            if ($result[0]['ORDER_PURPOSE'] == 1) {

                $this->DETAILS_MODEL_NAME = 'orders_detail_model';

            } else if ($result[0]['ORDER_PURPOSE'] == 2) {

                $this->DETAILS_MODEL_NAME = 'orders_items_model';

            } else {
                die('erorr get');
            }
            $this->load->model($this->DETAILS_MODEL_NAME);
            /////////////////////
            if (intval($this->order_id) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->order_id);
            } else {

                for ($i = 0; $i < count($this->class_id); $i++) {


                    if ($this->class_id[$i] != '' and $this->customer_price[$i] != '' and $this->price[$i] != '' and $this->approved_amount[$i] > 0) {
                        if ($result[0]['ORDER_PURPOSE'] == 1) {
                            $detail_seq = $this->orders_detail_model->create($this->_posteddata_details_insert($this->p_h_class_id[$i], $this->p_unit_class_id[$i], $this->p_customer_price[$i], $this->p_price[$i], $this->p_approved_amount[$i], $this->p_note[$i], $this->p_purchase_ser[$i], $this->p_order_date[$i], $result[0]['ORDER_PURPOSE'],$this->p_amount[$i]));

                        } else if ($result[0]['ORDER_PURPOSE'] == 2) {
                            $detail_seq = $this->orders_items_model->create($this->_posteddata_details_insert($this->p_h_class_id[$i], $this->p_unit_class_id[$i], $this->p_customer_price[$i], $this->p_price[$i], $this->p_approved_amount[$i], $this->p_note[$i], $this->p_purchase_ser[$i], $this->p_order_date[$i], $result[0]['ORDER_PURPOSE'],$this->p_amount[$i]));


                        }
                        // $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_posteddata_details_insert( $this->class_id[$i], $this->unit_class_id[$i], $this->customer_price[$i], $this->price[$i], $this->approved_amount[$i], $this->note[$i],$this->purchase_ser[$i], $this->order_date[$i], $result[0]['ORDER_PURPOSE']));

                        if (intval($detail_seq) <= 0) {
                            $this->print_error_del($detail_seq);
                        }
                    }
                }

                echo intval($this->order_id);
            }
            ///////////////////////
        } else {
            $data['content'] = 'orders_show';
            $data['title'] = ' إدخال أمر  التوريد- مشتريات ';
            /*   if ($this->order_purpose==1 )
                   $data['title']=' إدخال أمر  التوريد- مشتريات ';
               /*else
                   if ($this->order_purpose==2 )
                       $data['title']=' إدخال أمر التوريد - أعمال مدنية ';*/

            $data['isCreate'] = true;
            $data['action'] = 'index';
            //   $this->order_purpose= $this->input->get_post('type');
            //   echo "dddd".$this->order_purpose;
            $data['order_purpose'] = 1;  //$this->order_purpose;
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

    function _posteddata_details_insert($class_id = null, $unit_class_id = null, $customer_price = null, $price = null, $approved_amount = null, $note = null, $purchase_ser = null, $order_date = null, $order_purpose,$mount)
    {

        if ($order_purpose == 1)

            $result = array(array('name' => 'ORDER_ID', 'value' => $this->order_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_UNIT', 'value' => $unit_class_id, 'type' => '', 'length' => -1), array('name' => 'CUSTOMER_PRICE', 'value' => $customer_price, 'type' => '', 'length' => -1), array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1), array('name' => 'APPROVED_AMOUNT', 'value' => $approved_amount, 'type' => '', 'length' => -1), array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1), array('name' => 'ORDER_DATE', 'value' => $order_date, 'type' => '', 'length' => -1), array('name' => 'MOUNT', 'value' => $mount, 'type' => '', 'length' => -1));
        else if ($order_purpose == 2)

            $result = array(array('name' => 'ORDER_ID', 'value' => $this->order_id, 'type' => '', 'length' => -1), array('name' => 'ITEM', 'value' => $class_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_UNIT', 'value' => $unit_class_id, 'type' => '', 'length' => -1), array('name' => 'CUSTOMER_PRICE', 'value' => $customer_price, 'type' => '', 'length' => -1), array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1), array('name' => 'APPROVED_AMOUNT', 'value' => $approved_amount, 'type' => '', 'length' => -1), array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1), array('name' => 'PURCHASE_SER', 'value' => $purchase_ser, 'type' => '', 'length' => -1), array('name' => 'ORDER_DATE', 'value' => $order_date, 'type' => '', 'length' => -1)); else
            die('error create');


        return $result;


    }

    /*function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            $result = $this->purchase_order_model->get($this->purchase_order_id);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                for($i=0; $i<count($this->class_id); $i++){

                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->customer_price[$i]!='' and $this->price[$i]!='' and $this->approved_amount[$i]>0 ){ // create
                        if ($result[0]['ORDER_PURPOSE'] == 1) {
                            $detail_seq= $this->orders_detail_model->create($this->_posteddata_details_insert( $this->p_h_class_id[$i], $this->p_unit_class_id[$i], $this->p_customer_price[$i], $this->p_price[$i], $this->p_approved_amount[$i], $this->p_note[$i],$this->p_purchase_ser[$i], $this->p_order_date[$i], $result[0]['ORDER_PURPOSE']));

                        } else if ($result[0]['ORDER_PURPOSE'] == 2) {
                            $detail_seq= $this->orders_items_model->create($this->_posteddata_details_insert( $this->p_h_class_id[$i], $this->p_unit_class_id[$i], $this->p_customer_price[$i], $this->p_price[$i], $this->p_approved_amount[$i], $this->p_note[$i],$this->p_purchase_ser[$i], $this->p_order_date[$i], $result[0]['ORDER_PURPOSE']));

                        }
                        //  $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_posteddata_details_insert( $this->class_id[$i], $this->unit_class_id[$i], $this->customer_price[$i], $this->price[$i], $this->approved_amount[$i], $this->note[$i],$this->purchase_ser[$i], $this->order_date[$i]));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0   and $this->class_id[$i]!='' and $this->customer_price[$i]!='' and $this->price[$i]!='' and $this->approved_amount[$i]>0 ){  // edit
                        if ($result[0]['ORDER_PURPOSE'] == 1) {
                            $detail_seq= $this->orders_detail_model->edit($this->_postedData_details_update($this->ser[$i],$this->p_h_class_id[$i], $this->p_unit_class_id[$i], $this->p_customer_price[$i], $this->p_price[$i], $this->p_approved_amount[$i], $this->p_note[$i],$this->p_purchase_ser[$i], $this->p_order_date[$i], $result[0]['ORDER_PURPOSE']));

                        } else if ($result[0]['ORDER_PURPOSE'] == 2) {
                            $detail_seq = $this->orders_items_model->edit($this->_posteddata_details_update($this->ser[$i],$this->p_h_class_id[$i], $this->p_unit_class_id[$i], $this->p_customer_price[$i], $this->p_price[$i], $this->p_approved_amount[$i], $this->p_note[$i],$this->p_purchase_ser[$i], $this->p_order_date[$i], $result[0]['ORDER_PURPOSE']));

                        }
                        //$detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details_update($this->ser[$i], $this->class_id[$i], $this->unit_class_id[$i], $this->customer_price[$i], $this->price[$i], $this->approved_amount[$i], $this->note[$i], $this->order_date[$i]));

                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and ($this->approved_amount[$i]=='' or $this->approved_amount[$i]==0) ){ // delete
                        if ($result[0]['ORDER_PURPOSE'] == 1) {
                            $detail_seq= $this->orders_detail_model->delete($this->ser[$i]);
                        } else if ($result[0]['ORDER_PURPOSE'] == 2) {
                            $detail_seq= $this->orders_items_model->delete($this->ser[$i]);
                        }
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }
    }*/

    function print_error_del($msg = '')
    {
        $ret = $this->{$this->MODEL_NAME}->delete($this->order_id);
        if (intval($ret) > 0) $this->print_error('لم يتم حفظ أمر التوريد' . $msg); else
            $this->print_error('لم يتم حذف أمر التوريد' . $msg);
    }

    function delete()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->cancel_adopt($val, 0);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->cancel_adopt($id, 0);
        }

        if ($msg == 1) {
            echo 1;//modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }

    function cancel_adopt()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->cancel_adopt($id, 2);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function _postedData_details_update($ser = null, $class_id = null, $unit_class_id = null, $customer_price = null, $price = null, $approved_amount = null, $note = null, $order_date = null, $order_purpose)
    {

        if ($order_purpose == 1)


            $result = array(array('name' => 'SER1', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'ORDER_ID', 'value' => $this->order_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_UNIT', 'value' => $unit_class_id, 'type' => '', 'length' => -1), array('name' => 'CUSTOMER_PRICE', 'value' => $customer_price, 'type' => '', 'length' => -1), array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1), array('name' => 'APPROVED_AMOUNT', 'value' => $approved_amount, 'type' => '', 'length' => -1), array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1), array('name' => 'ORDER_DATE', 'value' => $order_date, 'type' => '', 'length' => -1)

            ); else if ($order_purpose == 2)

            $result = array(array('name' => 'SER2', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'ITEM', 'value' => $class_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_UNIT', 'value' => $unit_class_id, 'type' => '', 'length' => -1), array('name' => 'CUSTOMER_PRICE', 'value' => $customer_price, 'type' => '', 'length' => -1), array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1), array('name' => 'APPROVED_AMOUNT', 'value' => $approved_amount, 'type' => '', 'length' => -1), array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1), array('name' => 'ORDER_DATE', 'value' => $order_date, 'type' => '', 'length' => -1)); else
            die('error create');
//print_r($result);
        return $result;
    }

    function return_adopt2_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->return_adopt($id, 2);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function return_adopt3_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->return_adopt($id, 3);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function return_adopt4_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->return_adopt($id, 4);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function cancel_adopt2_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->cancel_adopt($id, 1);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function cancel_adopt3_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->cancel_adopt($id, 2);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function cancel_adopt4_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->cancel_adopt($id, 3);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function cancel_adopt5_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->cancel_adopt($id, 4);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function adopt2_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 2);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function adopt3_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 3);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function adopt4_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 4);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function adopt5_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 5);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function adopt1_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 1);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    /* function public_get_details($id= 0){


        $data['rec_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
         $this->load->view('orders_detail',$data);
     }*/

    function adopt2_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 2);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function adopt3_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 3);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function update_real_order_id()
    {
        $id = $this->input->post('id');
        $real_order = $this->input->post('real_order');

        $res = $this->{$this->MODEL_NAME}->orders_tb_real_order_update($id, $real_order);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $res);
        }
        echo 1;

    }

    function public_get_details($id = 0, $purchase_order_id = 0)
    {

        // $this->_look_ups($data);
        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $purchase_order_id;
        $result = $this->purchase_order_model->get($purchase_order_id);

        if (count($result) == 0) {
            $data['rec_details'] = $this->purchase_order_detail_model->get_details_all($purchase_order_id);
            echo($this->load->view('orders_detail', $data));
        } else {
            // $this->print_error('ادخال خاطئ لرقم طلب الشراء!!');
            if ($result[0]['ORDER_PURPOSE'] == 2) {
                $this->public_get_det_items($id, $purchase_order_id);
            } else {


                if ($id == 0) {

                    $data['rec_details'] = $this->purchase_order_detail_model->get_details_all($purchase_order_id);
                    echo($this->load->view('orders_detail', $data));

                } else {
                    //   echo $this->DETAILS_MODEL_NAME;
                    $data['rec_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
                    // print_r( $data['rec_details']);
                    // echo $id;
                    echo($this->load->view('orders_detail', $data));

                }
            }
        }
    }

    function public_get_det_items($id = 0, $purchase_order_id = 0)
    {


        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $purchase_order_id;
        if ($id == 0) {

            $data['rec_details'] = $this->purchase_order_items_model->get_details_all($purchase_order_id);
            //var_dump($purchase_order_id);
            // print_r($data['rec_de
            // print_r( $data['rec_details']);
            echo($this->load->view('orders_item', $data));

        } else {
            // echo $id;
            $data['rec_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
            //   print_r( $data['rec_details']);
            // echo $id;
            echo($this->load->view('orders_item', $data));

        }
    }

    function public_index_orders()
    {

        $data['title'] = ' أوامر التوريد';
        $data['content'] = 'orders_suppliers_index';
        $data['page'] = 1;

        $data['purchase_order_id'] = $this->input->get_post('purchase_order_id');
        $data['customer_id'] = $this->input->get_post('customer_id');
        $data['customer_curr_id'] = $this->input->get_post('customer_curr_id');
        $data['action'] = 'edit';
        $this->load->view('template/view', $data);
    }

    function public_get_page_orders($page = 1, $purchase_order_id = -1, $customer_id = -1, $customer_curr_id = -1)
    {
        $purchase_order_id = $this->check_vars($purchase_order_id, 'purchase_order_id');
        $customer_id = $this->check_vars($customer_id, 'customer_id');
        $customer_curr_id = $this->check_vars($customer_curr_id, 'customer_curr_id');
        $where_sql = '  ';
        $where_sql .= ($purchase_order_id != null) ? " and M.purchase_order_id= {$purchase_order_id} " : '';
        $where_sql .= ($customer_id != null) ? " and customer_id= {$customer_id} " : '';
        $where_sql .= ($customer_curr_id != null) ? " and customer_curr_id= {$customer_curr_id} " : '';
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql . ' and M.ADOPT !=0 ', 0, 100);
        $data['offset'] = 1;//$offset+1;
        $this->load->view('orders_suppliers_page', $data);
    }


    function public_index()
    {
        $this->load->model('payment/customers_model');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->_look_ups($data);
        $data['title'] = ' أوامر التوريد';
        $data['content'] = 'orderss_index';
        $data['customers'] = $this->customers_model->get_all_by_type(1);
        $data['currency'] = $this->currency_model->get_all();
        $data['page'] = 1;
        $data['order_text_t'] = $this->input->get_post('order_text_t');
        $data['customer_id'] = $this->input->get_post('customer_id');
        $data['customer_curr_id'] = $this->input->get_post('customer_curr_id');
        $data['action'] = 'edit';

        $this->load->view('template/view', $data);
    }

    function public_get_orders($page = 1, $order_text_t = -1, $customer_id = -1, $customer_curr_id = -1)
    {
        $order_text_t = $this->check_vars($order_text_t, 'order_text_t');
        $customer_id = $this->check_vars($customer_id, 'customer_id');
        $customer_curr_id = $this->check_vars($customer_curr_id, 'customer_curr_id');
        $where_sql = '  ';
        $where_sql .= ($order_text_t != null) ? " and TO_CHAR(M.ENTRY_DATE,'YYYY')||'/'||M.ORDER_TEXT like '%{$order_text_t}%' " : '';
        $where_sql .= ($customer_id != null) ? " and customer_id= {$customer_id} " : '';
        $where_sql .= ($customer_curr_id != null) ? " and customer_curr_id= {$customer_curr_id} " : '';
        //  echo $where_sql ;
        $this->load->library('pagination');

        $page = $page ? $page : 1;

        $count_rs = $this->{$this->MODEL_NAME}->get_count("orders_tb M where 1=1 " . $where_sql . " and M.ADOPT !=0 ");

//print_r($count_rs);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;

        $config['per_page'] = $this->page_size;
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
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($where_sql . " and M.ADOPT !=0 ", $offset, $row);
        // echo $where_sql."A ".COUNT($data['get_list'])." B".$offset." C".$row ;

        $this->load->view('orderss_page', $data);
    }

    function public_get_order_receipt_details($id = 0)
    {
        $this->load->model('orders_detail_model');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->model('settings/constant_details_model');

        $data['class_unit'] = $this->constant_details_model->get_list(29);
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        if ($id != 0) {
            $data['action'] = 'edit';
            $data['rec_details'] = $this->orders_detail_model->get_details_all($id);
            echo($this->load->view('receipt_class_input_detail_page', $data));

        }
    }


    //custom order modalPopUp

    function public_index_modify()
    {
        $this->load->model('payment/customers_model');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->_look_ups($data);
        $data['title'] = ' أوامر التوريد';
        $data['content'] = 'orderss_modify_index';
        $data['customers'] = $this->customers_model->get_all_by_type(1);
        $data['currency'] = $this->currency_model->get_all();
        $data['page'] = 1;
        $data['order_text_t'] = $this->input->get_post('order_text_t');
        $data['real_order_id'] = $this->input->get_post('real_order_id');
        $data['customer_id'] = $this->input->get_post('customer_id');
        $data['customer_curr_id'] = $this->input->get_post('customer_curr_id');
        $data['action'] = 'edit';

        $this->load->view('template/view', $data);
    }

    function public_get_orders_modify($page = 1, $order_text_t = -1, $real_order_id = -1, $customer_id = -1, $customer_curr_id = -1)
    {
        $order_text_t = $this->check_vars($order_text_t, 'order_text_t');
        $real_order_id = $this->check_vars($real_order_id, 'real_order_id');
        $customer_id = $this->check_vars($customer_id, 'customer_id');
        $customer_curr_id = $this->check_vars($customer_curr_id, 'customer_curr_id');
        $where_sql = '  ';
        $where_sql .= ($order_text_t != null) ? " and TO_CHAR(M.ENTRY_DATE,'YYYY')||'/'||M.ORDER_TEXT like '%{$order_text_t}%' " : '';
        $where_sql .= ($customer_id != null) ? " and customer_id= '{$customer_id}' " : '';
        $where_sql .= ($real_order_id != null) ? " and real_order_id like '%{$real_order_id}%' " : '';
        $where_sql .= ($customer_curr_id != null) ? " and customer_curr_id= {$customer_curr_id} " : '';
        $where_sql .= " and M.ADOPT= 5";
        // echo $where_sql ;
        $this->load->library('pagination');

        $page = $page ? $page : 1;

        $count_rs = $this->{$this->MODEL_NAME}->get_count("orders_tb M where 1=1 " . $where_sql . " and M.ADOPT !=0 ");


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;

        $config['per_page'] = $this->page_size;
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
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($where_sql . " and M.ADOPT !=0 ", $offset, $row);
        // echo $where_sql."A ".COUNT($data['get_list'])." B".$offset." C".$row ;

        $this->load->view('orderss_modify_page', $data);
    }

    //////////////////////////////////////////
    function public_index_modify_()
    {
        $this->load->model('payment/customers_model');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->_look_ups($data);
        $data['title'] = ' أوامر التوريد';
        $data['content'] = 'orderss_modify_entry_index';
        $data['customers'] = $this->customers_model->get_all_by_type(1);
        $data['currency'] = $this->currency_model->get_all();
        $data['page'] = 1;
        $data['order_text_t'] = $this->input->get_post('order_text_t');
        $data['real_order_id'] = $this->input->get_post('real_order_id');
        $data['customer_id'] = $this->input->get_post('customer_id');
        $data['customer_curr_id'] = $this->input->get_post('customer_curr_id');
        $data['action'] = 'edit';

        $this->load->view('template/view', $data);
    }

    /////////////////////////////////////////////////
    function public_get_orders_modify_($page = 1, $order_text_t = -1, $real_order_id = -1, $customer_id = -1, $customer_curr_id = -1)
    {
        $order_text_t = $this->check_vars($order_text_t, 'order_text_t');
        $real_order_id = $this->check_vars($real_order_id, 'real_order_id');
        $customer_id = $this->check_vars($customer_id, 'customer_id');
        $customer_curr_id = $this->check_vars($customer_curr_id, 'customer_curr_id');
        $where_sql = '  ';
        $where_sql .= ($order_text_t != null) ? " and TO_CHAR(M.ENTRY_DATE,'YYYY')||'/'||M.ORDER_TEXT like '%{$order_text_t}%' " : '';
        $where_sql .= ($customer_id != null) ? " and customer_id= '{$customer_id}' " : '';
        $where_sql .= ($real_order_id != null) ? " and real_order_id like '%{$real_order_id}%' " : '';
        $where_sql .= ($customer_curr_id != null) ? " and customer_curr_id= {$customer_curr_id} " : '';
        $where_sql .= " and M.ADOPT= 5";

        $this->load->library('pagination');

        $page = $page ? $page : 1;

        $count_rs = $this->{$this->MODEL_NAME}->get_count("orders_tb M where 1=1 " . $where_sql . " and M.ADOPT !=0 and M.purchase_order_id in (select purchase_order_id from PURCHASE_ORDER_TB x where ORDER_PURPOSE=2)");


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;

        $config['per_page'] = $this->page_size;
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
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($where_sql . " and M.ADOPT !=0 and M.purchase_order_id in (select purchase_order_id from PURCHASE_ORDER_TB x where ORDER_PURPOSE=2)", $offset, $row);
        // echo $where_sql."A ".COUNT($data['get_list'])." B".$offset." C".$row ;

        $this->load->view('orderss_modify_page', $data);
    }


}