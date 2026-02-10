<?php

/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/03/15
 * Time: 10:11 ص
 */
class suppliers_offers extends MY_Controller
{

    var $MODEL_NAME = 'suppliers_offers_model';
    var $DETAILS_MODEL_NAME; //= 'suppliers_offers_det_model';
    //  var $DETAILS_MODEL_NAME_ITEM= 'suppliers_offers_det_model';
    var $PAGE_URL = 'purchases/suppliers_offers/get_page';
    var $PAGE_DETAIL_URL = 'purchases/suppliers_offers/public_get_details';


    function  __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        // $this->load->model($this->DETAILS_MODEL_NAME);
        //  $this->load->model($this->DETAILS_MODEL_NAME_ITEM);
        $this->load->model('settings/currency_model');
        $this->load->model('purchase_order_detail_model');
        $this->load->model('purchase_order_items_model');
        $this->load->model('purchase_order_model');
        $this->load->model('suppliers_offers_delay_model');
        $this->load->model('stores/receipt_class_input_group_model');

        $this->order_purpose = $this->input->get_post('order_purpose');
//echo ($this->order_purpose);
        $this->suppliers_offers_id = $this->input->post('suppliers_offers_id');
        $this->adopt = $this->input->post('adopt');
        $this->entry_user = $this->input->post('entry_user');
        $this->purchase_order_id = $this->input->post('purchase_order_id');
        $this->customer_id = $this->input->post('customer_id');
        $this->customer_curr_id = $this->input->post('customer_curr_id');
        $this->curr_id = $this->input->post('curr_id');
        $this->offer_notes = $this->input->post('offer_notes');
        $this->curr_value = $this->input->post('curr_value');
        $this->offers_case = $this->input->post('offers_case');
        $this->offers_note = $this->input->post('offers_note');
        $this->user_in_date = $this->input->post('user_in_date');

        /*  detail */
        $this->ser = $this->input->post('h_ser');
        $this->class_id = $this->input->post('h_class_id');
        $this->unit_class_id = $this->input->post('unit_class_id');
        $this->amount = $this->input->post('amount');
        $this->customer_price = $this->input->post('customer_price');
        $this->price = $this->input->post('price');
        $this->approved_amount = $this->input->post('approved_amount');
        $this->note = $this->input->post('note');
        $this->order_colum = $this->input->post('order_colum');
        /*  detail items */
        $this->purchase_ser = $this->input->post('purchase_ser');

        if ($this->order_purpose != null and $this->order_purpose != 1 and $this->order_purpose != 2)
            die('construct');

        //  echo $this->order_purpose."fff";

        if ($this->order_purpose == 1)
            $this->DETAILS_MODEL_NAME = 'suppliers_offers_det_model';
        else if ($this->order_purpose == 2)
            $this->DETAILS_MODEL_NAME = 'suppliers_offers_items_model';
        else
            $this->DETAILS_MODEL_NAME = 'suppliers_offers_items_model';
        $this->load->model($this->DETAILS_MODEL_NAME);
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/constant_details_model');
        $data['help'] = $this->help;
        $data['currency'] = $this->currency_model->get_all();

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        //   add_css('select2_metro_rtl.css');
        //    add_js('select2.min.js');
        //   add_css('combotree.css');
        add_js('jquery.hotkeys.js');
        //   add_css('jquery.dataTables.css');
    }

    function print_error_del($msg = '')
    {
        $ret = $this->{$this->MODEL_NAME}->delete($this->suppliers_offers_id);
        if (intval($ret) > 0)
            $this->print_error('لم يتم حفظ كشف التفريغ ' . $msg);
        else
            $this->print_error('لم يتم حذف كشف التفريغ ' . $msg);
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

    function index($page = 1, $suppliers_offers_id = -1, $purchase_order_id = -1, $curr_id = -1, $customer_id = -1, $customer_curr_id = -1)
    {

        if ($this->order_purpose == 1)
            $data['title'] = 'كشف تفريغ - مشتريات';
        else
            if ($this->order_purpose == 2)
                $data['title'] = 'كشف تفريغ - أعمال مدنية';
        //  $data['title']='كشف تفريغ - أعمال مدنية';


        $data['content'] = 'suppliers_offers_index';
        $data['page'] = $page;
        $data['suppliers_offers_id'] = $suppliers_offers_id;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['order_purpose'] = $this->order_purpose;
        $data['customer_id'] = $customer_id;

        $data['curr_id'] = $curr_id;
        $data['customer_curr_id'] = $customer_curr_id;

        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function get_page($page = 1, $suppliers_offers_id = -1, $purchase_order_id = -1, $curr_id = -1, $customer_id = -1, $customer_curr_id = -1, $order_purpose = -1)
    {
        $this->load->library('pagination');
        //   $this->order_purpose=$order_purpose;
        $suppliers_offers_id = $this->check_vars($suppliers_offers_id, 'suppliers_offers_id');
        $purchase_order_id = $this->check_vars($purchase_order_id, 'purchase_order_id');
        $customer_id = $this->check_vars($customer_id, 'customer_id');
        $order_purpose = $this->check_vars($order_purpose, 'order_purpose');
        $this->order_purpose = $order_purpose;
        $curr_id = $this->check_vars($curr_id, 'curr_id');
        $customer_curr_id = $this->check_vars($customer_curr_id, 'customer_curr_id');

        $where_sql = "  AND P.ORDER_PURPOSE= " . $this->order_purpose;
//echo $where_sql;
        $where_sql .= ($suppliers_offers_id != null) ? " and SUPPLIERS_OFFERS_id= {$suppliers_offers_id} " : '';
        $where_sql .= ($purchase_order_id != null) ? " and p.purchase_order_id= {$purchase_order_id} " : '';
        $where_sql .= ($customer_id != null) ? " and customer_id= {$customer_id} " : '';

        $where_sql .= ($curr_id != null) ? " and P.QUOTE_CURR_ID= {$curr_id} " : '';
        $where_sql .= ($customer_curr_id != null) ? " and customer_curr_id= {$customer_curr_id} " : '';
//echo $where_sql;
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' SUPPLIERS_OFFERS_TB M,PURCHASE_ORDER_TB p  WHERE M.purchase_order_id=P.purchase_order_id ' . $where_sql);
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


        $this->load->view('suppliers_offers_page', $data);
    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = $this->{$c_var} ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function create($purchase_order_id = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
//print_r($this->_postedData('create'));
            //    exit;
            $this->suppliers_offers_id = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            /////////////////////
            if (intval($this->suppliers_offers_id) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->suppliers_offers_id);
            } else {
                for ($i = 0; $i < count($this->class_id); $i++) {
                    if ($this->class_id[$i] != '' and $this->amount[$i] != '' and $this->customer_price[$i] != '' and $this->price[$i] != '') {
                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details_insert($this->class_id[$i], $this->unit_class_id[$i], $this->amount[$i], $this->customer_price[$i], $this->price[$i], $this->note[$i], $this->purchase_ser[$i], $this->order_colum[$i]));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->suppliers_offers_id);
            }
            ///////////////////////
        } else {
            if ($purchase_order_id != '') {
                $data['purchase_order_id'] = $purchase_order_id;
                $purchase_data = $this->purchase_order_model->get($purchase_order_id);
                $data['purchase_data'] = $purchase_data[0];

            }
            $data['content'] = 'suppliers_offers_show';
            if ($this->order_purpose == 1)
                $data['title'] = 'إدخال كشف تفريغ-مشتريات';
            else
                if ($this->order_purpose == 2)
                    $data['title'] = 'إدخال كشف تفريغ-أعمال مدنية';

            $data['isCreate'] = true;
            $data['action'] = 'index';
            //   $this->order_purpose= $this->input->get_post('type');
            //   echo "dddd".$this->order_purpose;
            $data['order_purpose'] = $this->order_purpose;
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

    function _post_validation($isEdit = false)
    {

        if ($this->suppliers_offers_id == '' and $isEdit) {
            $this->print_error('يجب ادخال جميع البيانات');

        } else if (!($this->class_id) or count(array_filter($this->class_id)) <= 0) {
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        } else if (1) {
            $all_class = array();
            for ($i = 0; $i < count($this->class_id); $i++) {
                $all_class[] = $this->class_id[$i];
                if ($this->class_id[$i] == '')
                    $this->print_error('اختر الصنف');
                elseif ($this->unit_class_id[$i] == '')
                    $this->print_error('اختر الوحدة');
                elseif ($this->amount[$i] == '')
                    $this->print_error('أدخل الكمية ');

            }
        }

        if (count(array_filter($all_class)) != count(array_count_values(array_filter($all_class)))) {
            $this->print_error('يوجد تكرار في الاصناف');
        }
    }

    function get($id, $order_purpose)
    {
        if ($order_purpose == 1) {
            $this->DETAILS_MODEL_NAME = 'suppliers_offers_det_model';
            $data['title'] = 'بيانات كشف تفريغ - مشتريات';
        } else if ($order_purpose == 2) {
            $this->DETAILS_MODEL_NAME = 'suppliers_offers_items_model';
            $data['title'] = 'بيانات كشف تفريغ - أعمال مدنية';
        } else {
            $this->DETAILS_MODEL_NAME = 'suppliers_offers_items_model';
            die('erorr get');
        }

        $this->load->model($this->DETAILS_MODEL_NAME);


        $result = $this->{$this->MODEL_NAME}->get($id);
        if (!(count($result) == 1))
            die();
        $data['orders_data'] = $result;
        $data['order_purpose'] = $order_purpose;
        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER']) ? true : false : false;
        $data['action'] = 'edit';
        $data['content'] = 'suppliers_offers_show';
        $data['isCreate'] = true;


        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            } else {
                for ($i = 0; $i < count($this->class_id); $i++) {

                    if ($this->ser[$i] == 0 and $this->class_id[$i] != '' and $this->customer_price[$i] != '' and $this->price[$i] != '') { // create

                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details_insert($this->class_id[$i], $this->unit_class_id[$i], $this->amount[$i], $this->customer_price[$i], $this->price[$i], $this->note[$i], $this->purchase_ser[$i], $this->order_colum[$i]));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->ser[$i] != 0 and $this->class_id[$i] != '' and $this->customer_price[$i] != '' and $this->price[$i] != '') { // edit
                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details_update($this->ser[$i], $this->class_id[$i], $this->unit_class_id[$i], $this->amount[$i], $this->customer_price[$i], $this->price[$i], $this->note[$i], 0, null));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    }
                    /*elseif($this->ser[$i]!= 0 and ($this->approved_amount[$i]=='' or $this->approved_amount[$i]==0) ){ // delete
                                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser[$i]);
                                            if(intval($detail_seq) <= 0){
                                                $this->print_error($detail_seq);
                                            }
                                        }*/
                }
                echo 1;
            }
        }
    }

    function adopt1_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 1);
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

    function adopt1_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 1);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function adopt2_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id, 2);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function public_get_details($id = 0, $purchase_order_id = 0)
    {


        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $purchase_order_id;
        if ($id == 0) {

            $data['rec_details'] = $this->purchase_order_detail_model->get_details_all($purchase_order_id);
            echo($this->load->view('suppliers_offers_purchase_detail', $data));

        } else {
            //   echo $this->DETAILS_MODEL_NAME;
            $data['rec_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
            //   print_r( $data['rec_details']);
            // echo $id;
            echo($this->load->view('suppliers_offers_detail', $data));

        }
    }

    function public_get_det_items($id = 0, $purchase_order_id = 0)
    {


        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $purchase_order_id;

        if ($id == 0) {

            $data['rec_details'] = $this->purchase_order_items_model->get_details_all($purchase_order_id);
            //   print_r($data['rec_details']);
            echo($this->load->view('suppliers_offers_purchase_item', $data));

        } else {
            //  echo $this->DETAILS_MODEL_NAME;
            $data['rec_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
            //  print_r( $data['rec_details']);
            // echo $id;
            echo($this->load->view('suppliers_offers_item', $data));

        }
    }

    function public_getCurVal()
    {
        $curr_id = $this->input->get_post('curr_id');
        $customer_curr_id = $this->input->post('customer_curr_id');

        $result = $this->{$this->MODEL_NAME}->getCurrVal($curr_id, $customer_curr_id);
        echo $result;
    }

    function _postedData($typ = null)
    {

        $result = array(
            array('name' => 'SUPPLIERS_OFFERS_ID', 'value' => $this->suppliers_offers_id, 'type' => '', 'length' => -1),
            array('name' => 'PURCHASE_ORDER_ID', 'value' => $this->purchase_order_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->customer_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_CURR_ID', 'value' => $this->customer_curr_id, 'type' => '', 'length' => -1),
            array('name' => 'OFFER_NOTES', 'value' => $this->offer_notes, 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $this->curr_value, 'type' => '', 'length' => -1),
            array('name' => 'OFFERS_CASE', 'value' => $this->offers_case, 'type' => '', 'length' => -1),
            array('name' => 'OFFERS_NOTE', 'value' => $this->offers_note, 'type' => '', 'length' => -1),
            array('name' => 'USER_IN_DATE', 'value' => $this->user_in_date, 'type' => '', 'length' => -1)

        );
        if ($typ == 'create')
            unset($result[0]);


        return $result;
    }


    function _postedData_details_insert($class_id = null, $unit_class_id = null, $amount = null, $customer_price = null, $price = null, $note = null, $purchase_ser = null, $order_colum = null)
    {

        if ($this->order_purpose == 1)
            $result = array(
                array('name' => 'SUPPLIERS_OFFERS_ID', 'value' => $this->suppliers_offers_id, 'type' => '', 'length' => -1),
                array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
                array('name' => 'CLASS_UNIT', 'value' => $unit_class_id, 'type' => '', 'length' => -1),
                array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
                array('name' => 'CUSTOMER_PRICE', 'value' => $customer_price, 'type' => '', 'length' => -1),
                array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
                array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1),
                array('name' => 'ORDER_COLUM', 'value' => $order_colum, 'type' => '', 'length' => -1)
            );
        else if ($this->order_purpose == 2)

            $result = array(
                array('name' => 'SUPPLIERS_OFFERS_ID', 'value' => $this->suppliers_offers_id, 'type' => '', 'length' => -1),
                array('name' => 'ITEM', 'value' => $class_id, 'type' => '', 'length' => -1),
                array('name' => 'CLASS_UNIT', 'value' => $unit_class_id, 'type' => '', 'length' => -1),
                array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
                array('name' => 'CUSTOMER_PRICE', 'value' => $customer_price, 'type' => '', 'length' => -1),
                array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
                array('name' => 'CLASS_HINTS', 'value' => $note, 'type' => '', 'length' => -1),
                array('name' => 'PURCHASE_SER', 'value' => $purchase_ser, 'type' => '', 'length' => -1)
            );
        else
            die('error create');

        return $result;
    }

    function _postedData_details_update($ser = null, $class_id = null, $unit_class_id = null, $amount = null, $customer_price = null, $price = null, $note = null, $approved_amount = null, $award_hints = null)
    {

        if ($this->order_purpose == 1)
            $result = array(
                array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
                array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
                array('name' => 'CLASS_UNIT', 'value' => $unit_class_id, 'type' => '', 'length' => -1),
                array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
                array('name' => 'CUSTOMER_PRICE', 'value' => $customer_price, 'type' => '', 'length' => -1),
                array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
                array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1),
                array('name' => 'APPROVED_AMOUNT', 'value' => $approved_amount, 'type' => '', 'length' => -1),
                array('name' => 'AWARD_HINTS', 'value' => $award_hints, 'type' => '', 'length' => -1)
            );
        else if ($this->order_purpose == 2)

            $result = array(
                array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
                array('name' => 'ITEM', 'value' => $class_id, 'type' => '', 'length' => -1),
                array('name' => 'CLASS_UNIT', 'value' => $unit_class_id, 'type' => '', 'length' => -1),
                array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
                array('name' => 'CUSTOMER_PRICE', 'value' => $customer_price, 'type' => '', 'length' => -1),
                array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
                array('name' => 'CLASS_HINTS', 'value' => $note, 'type' => '', 'length' => -1)
            );
        else
            die('error create');

        return $result;
    }

    function public_envelopment($purchase_order_id)
    {

        $result = $this->purchase_order_model->get($purchase_order_id);
        $data['purchase_data'] = $result;

        $data['content'] = 'suppliers_offers_public_envelope_show';

        $data['title'] = 'عرض كشوف التفريغ';
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function envelopment1($purchase_order_id)
    {

        $result = $this->purchase_order_model->get($purchase_order_id);
        $data['purchase_data'] = $result;
        // $data['purchase_emails']
        $all = $this->receipt_class_input_group_model->get_emails($purchase_order_id, 2);
        //  echo "ffffffff";
        //  print_r( $emails);
        //------------------------

        $emails = '';
        foreach ($all as $row) {
            $emails .= $row['EMAIL'] . ',';
        }
        $data['purchase_emails'] = substr($emails, 0, -1);
        //  echo "ffffffff"||$data['purchase_emails'];


        //------------------------
        $data['content'] = 'suppliers_offers_envelope_show';

        $data['title'] = 'محضر لجنة فتح المظاريف';
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function envelopment2($purchase_order_id)
    {

        $result = $this->purchase_order_model->get($purchase_order_id);
        $data['purchase_data'] = $result;

        $data['content'] = 'suppliers_offers_envelope_show';

        $data['title'] = 'محضر لجنة فتح مظاريف - أعمال مدنية';
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function public_get_all_envelope_details($purchase_order_id = 0)
    {

        $this->load->model('suppliers_offers_det_model');
        $data['cust_amount'] = array();
        $data['cust_cust_price'] = array();
        $data['cust_price'] = array();
        $data['note'] = array();
        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_list_by_pur($purchase_order_id);
        $data['rec_details'] = $this->purchase_order_detail_model->get_details_all($purchase_order_id);
        $suppliers_details = $this->suppliers_offers_det_model->get_details_all_by_purchase_a($purchase_order_id);
        foreach ($suppliers_details as $row) :
            $data['cust_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['AMOUNT'];
            $data['cust_cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['CUSTOMER_PRICE'];
            $data['cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['PRICE'];
            $data['note'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['NOTE'];
        endforeach;

        $this->load->view('suppliers_offers_public_envelope_detail', $data);


    }

    function public_get_offer_notes($purchase_order_id = 0)
    {
        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_list_by_pur($purchase_order_id);
        $this->load->view('suppliers_offers_customer_offer_notes', $data);
    }

    function public_get_envelope_details($purchase_order_id = 0)
    {
        $this->load->model('suppliers_offers_det_model');
        $data['cust_amount'] = array();
        $data['cust_cust_price'] = array();
        $data['cust_price'] = array();
        $data['note'] = array();
        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_lists($purchase_order_id);
        $data['rec_details'] = $this->purchase_order_detail_model->get_details_all($purchase_order_id);
        $suppliers_details = $this->suppliers_offers_det_model->get_details_all_by_purchase($purchase_order_id);

        foreach ($suppliers_details as $row) :
            $data['cust_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['AMOUNT'];
            $data['cust_cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['CUSTOMER_PRICE'];
            $data['cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['PRICE'];
            $data['note'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['NOTE'];
        endforeach;

        $this->load->view('suppliers_offers_envelope_detail', $data);


    }

    function public_get_envelope_items($purchase_order_id = 0)
    {
        $this->load->model('suppliers_offers_items_model');
        $data['cust_amount'] = array();
        $data['cust_cust_price'] = array();
        $data['cust_price'] = array();
        $data['note'] = array();

        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_lists($purchase_order_id);
        $data['rec_details'] = $this->purchase_order_items_model->get_details_all($purchase_order_id);

        $suppliers_details = $this->suppliers_offers_items_model->get_details_all_by_purchase($purchase_order_id);
        foreach ($suppliers_details as $row) :
            //  ECHO $row['PURCHASE_SER'] ;
            $data['cust_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['AMOUNT'];
            $data['cust_cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CUSTOMER_PRICE'];
            $data['cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['PRICE'];
            $data['note'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CLASS_HINTS'];
        endforeach;


        $this->load->view('suppliers_offers_envelope_items', $data);


    }

    function public_get_all_envelope_items($purchase_order_id = 0)
    {
        $this->load->model('suppliers_offers_items_model');
        $data['cust_amount'] = array();
        $data['cust_cust_price'] = array();
        $data['cust_price'] = array();
        $data['note'] = array();

        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_list_by_pur($purchase_order_id);
        $data['rec_details'] = $this->purchase_order_items_model->get_details_all($purchase_order_id);

        $suppliers_details = $this->suppliers_offers_items_model->get_items_all_by_purchase($purchase_order_id);
        foreach ($suppliers_details as $row) :
            //  ECHO $row['PURCHASE_SER'] ;
            $data['cust_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['AMOUNT'];
            $data['cust_cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CUSTOMER_PRICE'];
            $data['cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['PRICE'];
            $data['note'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CLASS_HINTS'];
        endforeach;


        $this->load->view('suppliers_offers_public_envelope_items', $data);


    }

    function public_get_award_details($purchase_order_id = 0)
    {
        $this->load->model('suppliers_offers_det_model');
        $data['award_delay_decisions'] = $this->constant_details_model->get_list(73);
        $data['cust_amount'] = array();
        $data['cust_class_discount'] = array();
        $data['cust_class_discount_value'] = array();
        $data['cust_approved_price'] = array();
        $data['cust_cust_price'] = array();
        $data['cust_price'] = array();
        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_lists($purchase_order_id);

        $data['rec_details'] = $this->purchase_order_detail_model->get_details_all($purchase_order_id);
        $suppliers_details = $this->suppliers_offers_det_model->get_details_all_by_purchase($purchase_order_id);
        foreach ($suppliers_details as $row) :
            $data['cust_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['AMOUNT'];
            $data['cust_class_discount'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['CLASS_DISCOUNT'];
            $data['cust_class_discount_value'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['CLASS_DISCOUNT_VALUE'];
            $data['cust_approved_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['APPROVED_PRICE'];
            $data['cust_cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['CUSTOMER_PRICE'];
            $data['cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['PRICE'];
            $data['approved_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['APPROVED_AMOUNT'];
            $data['award_hints'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['AWARD_HINTS'];
        endforeach;
        $this->load->view('suppliers_offers_award_detail', $data);
    }

    function award1($purchase_order_id)
    {

        $result = $this->purchase_order_model->get($purchase_order_id);
        $data['purchase_data'] = $result;
        $data['purchase_emails'] = $this->get_emails_by_code(5);
        $data['purchase_cancel_emails'] = $this->purchase_order_model->get_emails($purchase_order_id);
        //  echo ( $data['purchase_cancel_emails']);

        $data['content'] = 'suppliers_offers_award_show';

        $data['title'] = 'محضر لجنة البت والترسية';
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function public_get_award_items($purchase_order_id = 0)
    {
        $this->load->model('suppliers_offers_items_model');
        $data['award_delay_decisions'] = $this->constant_details_model->get_list(73);
        $data['cust_amount'] = array();
        $data['cust_class_discount'] = array();
        $data['cust_class_discount_value'] = array();
        $data['cust_approved_price'] = array();
        $data['cust_cust_price'] = array();
        $data['cust_price'] = array();
        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_lists($purchase_order_id);

        $data['rec_details'] = $this->purchase_order_items_model->get_details_all($purchase_order_id);
        $suppliers_details = $this->suppliers_offers_items_model->get_details_all_by_purchase($purchase_order_id);
        foreach ($suppliers_details as $row) :

            $data['cust_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['AMOUNT'];
            $data['cust_class_discount'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CLASS_DISCOUNT'];
            $data['cust_class_discount_value'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CLASS_DISCOUNT_VALUE'];
            $data['cust_approved_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['APPROVED_PRICE'];
            $data['cust_cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CUSTOMER_PRICE'];
            $data['cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['PRICE'];
            $data['approved_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['APPROVED_AMOUNT'];
            $data['award_hints'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['AWARD_HINTS'];
        endforeach;
        $this->load->view('suppliers_offers_award_items', $data);
    }

    function award2($purchase_order_id)
    {

        $result = $this->purchase_order_model->get($purchase_order_id);
        $data['purchase_data'] = $result;
        $data['purchase_emails'] = $this->get_emails_by_code(5);
        $data['content'] = 'suppliers_offers_award_show';

        $data['title'] = 'محضر لجنة البت والترسية';
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }


    function delay1($purchase_order_id = 0, $delay_id = 0)
    {
        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $purchase_order_id;
        $delay_id = $this->input->post('delay_id') ? $this->input->post('delay_id') : $delay_id;

        // $data['delay_id']=''
        if ($delay_id == 0) {
            $result = $this->purchase_order_model->get($purchase_order_id);
            $data['purchase_data'] = $result;
            $data['delay_id'] = '';
        } else {
            $result = $this->suppliers_offers_delay_model->get($delay_id);
            $data['purchase_data'] = $result;
            $data['delay_id'] = $delay_id;
        }
        $data['content'] = 'suppliers_offers_delay_record_show';

        $data['title'] = 'محضر بت وترسية لأصناف مؤجلة';
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function delay2($purchase_order_id = 0, $delay_id = 0)
    {
        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $purchase_order_id;
        $delay_id = $this->input->post('delay_id') ? $this->input->post('delay_id') : $delay_id;

        if ($delay_id == 0) {
            $result = $this->purchase_order_model->get($purchase_order_id);
            $data['purchase_data'] = $result;
            $data['delay_id'] = '';
        } else {
            $result = $this->suppliers_offers_delay_model->get($delay_id);
            $data['purchase_data'] = $result;
            $data['delay_id'] = $delay_id;
        }
        $data['content'] = 'suppliers_offers_delay_record_show';

        $data['title'] = 'محضر بت و ترسية لأصناف مؤجلة - أعمال مدنية';
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

//-----------
    function public_get_delay_details($purchase_order_id = 0, $delay_id = 0)
    {
        //    $delay_id = $this->input->get_post('delay_id') ? $this->input->get_post('delay_id') :$delay_id;
        $this->load->model('suppliers_offers_det_model');
        $data['award_delay_decisions'] = $this->constant_details_model->get_list(73);
        $data['cust_amount'] = array();
        $data['cust_class_discount'] = array();
        $data['cust_class_discount_value'] = array();
        $data['cust_cust_price'] = array();
        $data['cust_price'] = array();
        $data['cust_approved_price'] = array();
        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_lists($purchase_order_id);
        ////////////

        if (intval($delay_id) > 0) {
            $data['rec_details'] = $this->purchase_order_detail_model->get_lists(" AND M.PURCHASE_ORDER_ID={$purchase_order_id} AND M.AWARD_DELAY_DECISION=3 AND M.DELAY_ID={$delay_id}");
            $suppliers_details = $this->suppliers_offers_det_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$purchase_order_id} AND P.AWARD_DELAY_DECISION=3 AND P.DELAY_ID={$delay_id}");

        } else {
            $data['rec_details'] = $this->purchase_order_detail_model->get_lists(" AND M.PURCHASE_ORDER_ID={$purchase_order_id} AND M.AWARD_DELAY_DECISION=2");
            $suppliers_details = $this->suppliers_offers_det_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$purchase_order_id} AND P.AWARD_DELAY_DECISION=2 ");

        }
        if (count($data['rec_details']) <= 0) die();
        //    $data['rec_details']= $this->purchase_order_detail_model->get_details_all($purchase_order_id);
        ///////////////

        foreach ($suppliers_details as $row) :
            $data['cust_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['AMOUNT'];
            $data['cust_class_discount'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['CLASS_DISCOUNT'];
            $data['cust_class_discount_value'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['CLASS_DISCOUNT_VALUE'];
            $data['cust_cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['CUSTOMER_PRICE'];
            $data['cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['PRICE'];
            $data['approved_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['APPROVED_AMOUNT'];
            $data['award_hints'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['AWARD_HINTS'];
            $data['cust_approved_price'][$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']] = $row['APPROVED_PRICE'];
        endforeach;
        $this->load->view('suppliers_offers_delay_record_detail', $data);
    }

    function public_get_delay_items($purchase_order_id = 0, $delay_id = 0)
    {
        //    $delay_id = $this->input->get_post('delay_id') ? $this->input->get_post('delay_id') :$delay_id;

        $this->load->model('suppliers_offers_items_model');
        $data['award_delay_decisions'] = $this->constant_details_model->get_list(73);
        $data['cust_amount'] = array();
        $data['cust_class_discount'] = array();
        $data['cust_class_discount_value'] = array();
        $data['cust_cust_price'] = array();
        $data['cust_price'] = array();
        $data['cust_approved_price'] = array();
        $data['suppliers_offers_data'] = $this->{$this->MODEL_NAME}->get_lists($purchase_order_id);
        ////////////

        if (intval($delay_id) > 0) {
            $data['rec_details'] = $this->purchase_order_items_model->get_lists(" AND M.PURCHASE_ORDER_ID={$purchase_order_id} AND M.AWARD_DELAY_DECISION=3 AND M.DELAY_ID={$delay_id}");
            $suppliers_details = $this->suppliers_offers_items_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$purchase_order_id} AND P.AWARD_DELAY_DECISION=3 AND P.DELAY_ID={$delay_id}");

        } else {
            $data['rec_details'] = $this->purchase_order_items_model->get_lists(" AND M.PURCHASE_ORDER_ID={$purchase_order_id} AND M.AWARD_DELAY_DECISION=2");
            $suppliers_details = $this->suppliers_offers_items_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$purchase_order_id} AND P.AWARD_DELAY_DECISION=2 ");

        }
        if (count($data['rec_details']) <= 0) die();
        //    $data['rec_details']= $this->purchase_order_detail_model->get_details_all($purchase_order_id);
        ///////////////

        foreach ($suppliers_details as $row) :
            $data['cust_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['AMOUNT'];
            $data['cust_class_discount'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CLASS_DISCOUNT'];
            $data['cust_class_discount_value'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CLASS_DISCOUNT_VALUE'];
            $data['cust_cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['CUSTOMER_PRICE'];
            $data['cust_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['PRICE'];
            $data['approved_amount'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['APPROVED_AMOUNT'];
            $data['award_hints'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['AWARD_HINTS'];
            $data['cust_approved_price'][$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']] = $row['APPROVED_PRICE'];
        endforeach;
        $this->load->view('suppliers_offers_delay_record_items', $data);
    }

    // mkilani - sent notification to committee users
    function committee_notify()
    {
        $purchase_order_id = $this->input->post('purchase_order_id');
        $order_purpose = $this->input->post('order_purpose');
        $committee_case = $this->input->post('committee_case');

        if ($committee_case == 2) $action = 'envelopment' . $order_purpose;
        elseif ($committee_case == 4) $action = 'award' . $order_purpose;
        else                        die('Error');

        $this->_notifyMessage($action, "purchases/suppliers_offers/{$action}/{$purchase_order_id}", 'محضر طلب شراء رقم ' . $purchase_order_id);
        echo 1;
    }


}