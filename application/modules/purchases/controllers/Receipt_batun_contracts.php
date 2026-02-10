<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Receipt_batun_contracts extends MY_Controller
{

    var $MODEL_NAME = 'Receipt_batun_contracts_model';
    var $MODEL_NAME_DET = 'Receipt_batun_detail_model';
    var $PAGE_URL = 'purchases/Receipt_batun_contracts/get_page';


    //var $MODEL_NAME_GROUP = 'Receipt_batun_group_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Receipt_batun_contracts_model');
        $this->load->model('Receipt_batun_detail_model');
        $this->load->model('Receipt_batun_group_model');
        $this->load->model('orders_items_model');


    }


    function index()
    {
        $data['title'] = 'إدارة محاضر الفحص و الاستلام للأعمال المدنية-(باطون)';
        $data['help'] = $this->help;
        $data['content'] = 'receipt_batun_contracts_index';
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->view('template/template', $data);

    }

    //////////////////////////////////////////////////////////////////////////////
    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = " where RECEIPT_CLASS_INPUT_ID in (select RECEIPT_CLASS_INPUT_ID_SER from gfc.receipt_batun_group_tb t WHERE EMP_NO=USER_PKG.GET_USER_EMP_NO)";
        $where_sql .= isset($this->p_order_id) && $this->p_order_id != null ? " AND  M.ORDER_ID ={$this->p_order_id}  " : "";
        $where_sql .= isset($this->p_order_id_text) && $this->p_order_id_text != null ? " AND  M.ORDER_ID_TEXT LIKE '%{$this->p_order_id_text}%' " : "";
        $where_sql .= isset($this->p_real_order_id) && $this->p_real_order_id != null ? " AND  REAL_ORDER_ID LIKE '%{$this->p_real_order_id}%'  " : "";
        $where_sql .= isset($this->p_customer_resource_id) && $this->p_customer_resource_id != null ? " AND  M.CUSTOMER_RESOURCE_ID ={$this->p_customer_resource_id}  " : "";


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count("RECEIPT_BATUN_CONTRACTS_TB", $where_sql);
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

        $offset = (((($page) - 1) * $config['per_page']));
        $row = ((($page) * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_all($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('receipt_batun_contracts_page', $data);


    }

    function _look_ups(&$data)
    {
        add_css('combotree.css');
        add_css('datepicker3.css');
        add_js('jquery.hotkeys.js');
        add_js('moment.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('font-awesome.min.css');
        add_js('bootstrap.min.js');
        add_js('bootstrap-datetimepicker.js');
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores/store_committees_model');
        $data['class_type'] = $this->constant_details_model->get_list(507);
        $data['record_declaration_list'] = $this->constant_details_model->get_list(508);
        $data['class_input_class_type'] = $this->store_committees_model->get_all_by_type(1);
        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['check_result'] = $this->constant_details_model->get_list(519);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $member_absants = 0;

            for ($abs = 0; $abs < count($this->p_group_person_id); $abs++) {
                $abs_status = (isset($this->p_status[$abs + 1])) ? 1 : 2;
                if ($abs_status == 2) {
                    $member_absants++;

                }

            }

            /*if ((count($this->p_group_person_id) - $member_absants) < 5) {
                $this->print_error('عدد الأعضاء يتوجب ان يكونوا 5 فما فوق !!' . ($member_absants));
            } else {*/

            $result = $this->{$this->MODEL_NAME}->edit($this->_postedData('edit'));


            if (intval($result) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $result);
            }

            for ($i = 0; $i < count($this->p_planning_mount); $i++) {

                if ($this->p_h_ser[$i] <= 0)
                    $detail_seq = $this->Receipt_batun_detail_model->create($this->_postedData_details($this->p_h_ser[$i], $result, $this->p_h_item[$i], $this->p_planning_mount[$i], $this->p_customer_type[$i], $this->p_h_civil_customer_accounts_name[$i], $this->p_item_recipt_no[$i],
                        $this->p_txt_civil_project_tec_code[$i], $this->p_item_recipt_mount[$i], $this->p_diff_mount[$i], $this->p_prev_mount[$i], $this->p_total_mount[$i], $this->p_to_pour_date[$i], $this->p_check_date[$i], $this->p_check_result[$i], $this->p_item_price[$i], $this->p_total[$i],
                        'create'));
                else
                    $detail_seq = $this->Receipt_batun_detail_model->edit($this->_postedData_details($this->p_h_ser[$i], $result, $this->p_h_item[$i], $this->p_planning_mount[$i], $this->p_customer_type[$i], $this->p_h_civil_customer_accounts_name[$i], $this->p_item_recipt_no[$i],
                        $this->p_txt_civil_project_tec_code[$i], $this->p_item_recipt_mount[$i], $this->p_diff_mount[$i], $this->p_prev_mount[$i], $this->p_total_mount[$i], $this->p_to_pour_date[$i], $this->p_check_date[$i], $this->p_check_result[$i], $this->p_item_price[$i], $this->p_total[$i],
                        'edit'));


                if (intval($detail_seq) <= 0) {
                    $this->print_error_del($detail_seq);
                }

            }


            for ($i = 0; $i < count($this->p_group_person_id); $i++) {
                $status = (isset($this->p_status[$i + 1])) ? 1 : 2; // $this->input->post('checkbox',TRUE)==null ? 1 : 2;
                if ($this->p_h_group_ser[$i] <= 0)
                    $detail_group_seq = $this->Receipt_batun_group_model->create($this->_postedData_group_details($this->p_h_group_ser[$i], $result, $this->p_group_person_id[$i], $this->p_group_person_date[$i], $this->p_emp_no[$i], $status, $this->p_member_note[$i], 'create'));
                else
                    $detail_group_seq = $this->Receipt_batun_group_model->edit($this->_postedData_group_details($this->p_h_group_ser[$i], $result, $this->p_group_person_id[$i], $this->p_group_person_date[$i], $this->p_emp_no[$i], $status, $this->p_member_note[$i], 'edit'));


                if (intval($detail_group_seq) <= 0) {
                    $this->print_error_del($detail_group_seq);
                }

            }


            echo intval($result);

        }
    }

    // }

    function _post_validation()
    {

        if ($this->p_order_id == '' and $this->p_order_id_ser == '') {
            $this->print_error('يجب ادخال رقم أمر التوريد !!');
        } else if ($this->p_real_order_id == '') {
            $this->print_error('يجب ادخال رقم أمر التوريد الفعلي !!');
        } else if ($this->p_customer_resource_id == '' and $this->p_customer_name == '') {
            $this->print_error('يجب ادخال المورد !!');
        } else if ($this->p_class_type == '' || $this->p_class_type == 0) {
            $this->print_error('يجب اختيار نوع الأعمال !!');
        } else if ($this->p_note_list == '' || $this->p_note_list == 0) {
            $this->print_error('يجب اختيار قائمة الملاحظات !!');
        } else if ($this->p_committees_id == '' || $this->p_committees_id == 0) {
            $this->print_error('يجب اختيار نوع اللجنة !!');
        } /*else if (count(array_filter($this->p_customer_type)) <= 0) {
            $this->print_error('يجب ادخال بيانات المورد ');
        }*/ else if (count(array_filter($this->p_emp_no)) <= 0) {
            $this->print_error('يجب ادخال بيانات الأعضاء ');
        }
        /*for ($i = 0; $i < count($this->p_amount); $i++) {

            if ($this->p_amount[$i] == '' || $this->p_amount[$i] < 0)
                $this->print_error('يجب ادخال الكمية المستلمة!!' . ' #بند رقم ' . $i + 1);
            else if ($this->p_amount[$i] > 0) {
                if ($this->p_customer_type[$i] == '' || $this->p_customer_type == 0)
                    $this->print_error('اختر نوع المستفيد!!' . ' #بند رقم ' . $i + 1);
                elseif ($this->p_customer_accounts_name[$i] == '' and $this->p_customer_accounts_id[$i] == '')
                    $this->print_error('!!أدخل اسم المستفيد	 ' . ' #بند رقم ' . $i + 1);
                else if ($this->p_class_type == 3) {
                    if ($this->p_item_recipt_no[$i] == '' || $this->p_item_recipt_no[$i] < 0)
                        $this->print_error('!!أدخل الإرسالية ' . ' #بند رقم ' . $i + 1);
                }
            } else if ($this->p_amount[$i] == 0) {
                echo 10;
                if ($this->p_customer_type[$i] != '' || $this->p_customer_type != 0)
                    $this->print_error('الكمية المستلمة صفر و بالتالي لا يتوجب عليك اختيار نوع الحساب!!' . ' #بند رقم ' . $i + 1);
                elseif ($this->p_customer_accounts_name[$i] == '' and $this->p_customer_accounts_id[$i] == '')
                    $this->print_error('!!لكمية المستلمة صفر و بالتالي لا يتوجب عليك ادخال اسم المستفيد	 ' . ' #بند رقم ' . $i + 1);

                if ($this->p_item_recipt_no[$i] == '' || $this->p_item_recipt_no[$i] < 0)
                    $this->print_error('!!لكمية المستلمة صفر و بالتالي لا يتوجب عليك ادخال الإرسالية ' . ' #بند رقم ' . $i + 1);
            }
        }


        for ($j = 0; $j < count($this->p_emp_no); $j++) {
            if ($this->p_emp_no[$j] == '' || $this->p_emp_no[$j] <= 0)
                $this->print_error('يجب ادخال الرقم الوظيفي!!' . ' #عضو رقم ' . $j + 1);
            elseif ($this->p_group_person_id[$j] == '')
                $this->print_error('يجب ادخال رقم الهوية!!' . ' #عضو رقم ' . $j + 1);
            elseif ($this->p_group_person_date[$j] == '')
                $this->print_error('أدخل اسم العضو	!! ' . ' #عضو رقم ' . $j + 1);

        }*/

    }

    function _postedData($typ = null)
    {

        $result = array(array('name' => 'RECEIPT_CLASS_INPUT_ID_IN', 'value' => $this->p_receipt_class_input_id, 'type' => '', 'length' => -1),
            array('name' => 'ORDER_ID_IN', 'value' => $this->p_order_id_ser, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_TYPE_IN', 'value' => $this->p_class_type, 'type' => '', 'length' => -1),
            array('name' => 'NOTE_LIST_IN', 'value' => $this->p_note_list, 'type' => '', 'length' => -1),
            array('name' => 'COMMITTEES_ID_IN', 'value' => $this->p_committees_id, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);

        return $result;
    }

    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $member_absants = 0;

            for ($abs = 0; $abs < count($this->p_group_person_id); $abs++) {
                $abs_status = (isset($this->p_status[$abs + 1])) ? 1 : 2;
                if ($abs_status == 2) {
                    $member_absants++;

                }

            }

            /* if ((count($this->p_group_person_id) - $member_absants) < 5) {
                 $this->print_error('عدد الأعضاء يتوجب ان يكونوا 5 فما فوق !!');
             } else {*/
            $result_ser = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if (intval($result_ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $result_ser);
            }

            for ($i = 0; $i < count($this->p_planning_mount); $i++) {

                $detail_seq = $this->Receipt_batun_detail_model->create($this->_postedData_details($this->p_h_ser[$i], $result_ser, $this->p_h_item[$i], $this->p_planning_mount[$i], $this->p_customer_type[$i], $this->p_h_civil_customer_accounts_name[$i], $this->p_item_recipt_no[$i],
                    $this->p_txt_civil_project_tec_code[$i], $this->p_item_recipt_mount[$i], $this->p_diff_mount[$i], $this->p_prev_mount[$i], $this->p_total_mount[$i], $this->p_to_pour_date[$i], $this->p_check_date[$i], $this->p_check_result[$i], $this->p_item_price[$i], $this->p_total[$i],
                    'create'));


                if (intval($detail_seq) <= 0) {
                    $this->print_error($detail_seq);
                }

            }

            for ($i = 0; $i < count($this->p_group_person_id); $i++) {

                $status = (isset($this->p_status[$i + 1])) ? 1 : 2; // $this->input->post('checkbox',TRUE)==null ? 1 : 2;
                $detail_group_seq = $this->Receipt_batun_group_model->create($this->_postedData_group_details($this->p_h_group_ser[$i], $result_ser, $this->p_group_person_id[$i], $this->p_group_person_date[$i], $this->p_emp_no[$i], $status, $this->p_member_note[$i], 'create'));

                if (intval($detail_group_seq) <= 0) {
                    $this->print_error_del($detail_group_seq);
                }

            }

            echo intval($result_ser);

            // }
        } else {
            $data['content'] = 'receipt_batun_contracts_show';
            $data['title'] = 'ادخال محاضر الفحص و الاستلام للأعمال المدنية-(باطون)';
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $data['page'] = 1;
            $data['help'] = $this->help;
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }


    }

    function _postedData_details($ser = null, $receipt_class_input_id_ser, $item_id, $amount, $customer_account_type, $customer_id, $item_recipt_no = null, $project_tec_code, $item_recipt_mount, $diff_mount, $prev_mount, $total_mount, $to_pour_date, $check_date, $check_result, $item_price, $total, $typ)
    {
        $result = array(array('name' => 'SER_IN', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'RECEIPT_CLASS_INPUT_ID_SER_IN', 'value' => $receipt_class_input_id_ser, 'type' => '', 'length' => -1),
            array('name' => 'ITEM_ID_IN', 'value' => $item_id, 'type' => '', 'length' => -1),
            array('name' => 'PLANNING_MOUNT_IN', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE_IN', 'value' => $customer_account_type, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID_IN', 'value' => $customer_id, 'type' => '', 'length' => -1),
            array('name' => 'ITEM_RECIPT_NO_IN', 'value' => $item_recipt_no, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC_CODE_IN', 'value' => $project_tec_code, 'type' => '', 'length' => -1),
            array('name' => 'ITEM_RECIPT_MOUNT_IN', 'value' => $item_recipt_mount, 'type' => '', 'length' => -1),
            array('name' => 'DIFF_MOUNT_IN', 'value' => $diff_mount, 'type' => '', 'length' => -1),
            array('name' => 'PREV_MOUNT_IN', 'value' => $prev_mount, 'type' => '', 'length' => -1),
            array('name' => 'TOTAL_MOUNT_IN', 'value' => $total_mount, 'type' => '', 'length' => -1),
            array('name' => 'TO_POUR_DATE_IN', 'value' => $to_pour_date, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_DATE_IN', 'value' => $check_date, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_RESULT_IN', 'value' => $check_result, 'type' => '', 'length' => -1),
            array('name' => 'ITEM_PRICE_IN', 'value' => $item_price, 'type' => '', 'length' => -1),
            array('name' => 'TOTAL_IN', 'value' => $total, 'type' => '', 'length' => -1),
        );

        if ($typ == 'create')
            unset($result[0]);

        return $result;

    }

    function _postedData_group_details($group_ser = null, $receipt_class_input_id_ser, $group_person_id, $group_person, $emp_no, $status, $member_note = null, $typ)
    {
        $result = array(array('name' => 'SER_IN', 'value' => $group_ser, 'type' => '', 'length' => -1),
            array('name' => 'RECEIPT_CLASS_INPUT_ID_SER_IN', 'value' => $receipt_class_input_id_ser, 'type' => '', 'length' => -1),
            array('name' => 'GROUP_PERSON_ID_IN', 'value' => $group_person_id, 'type' => '', 'length' => -1),
            array('name' => 'GROUP_PERSON_IN', 'value' => $group_person, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO_IN', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => 'STATUS_IN', 'value' => $status, 'type' => '', 'length' => -1),
            array('name' => 'MEMBER_NOTE_IN', 'value' => $member_note, 'type' => '', 'length' => -1),

        );

        if ($typ == 'create')
            unset($result[0]);

        return $result;


    }

    function public_items_recept($id = 0, $class_type = 0)
    {


        $id = $this->input->post('order_id') ? $this->input->post('order_id') : $id;
        $class_type = $this->input->post('class_type') ? $this->input->post('class_type') : $class_type;

        if ($class_type == 3)
            $data['readonly'] = "";
        else
            $data['readonly'] = "readonly";
        $data['rec_details'] = $this->orders_items_model->get_details_all($id);
        $data['rec_logistic_details'] = $this->Receipt_batun_detail_model->get($id);

        if (count($data['rec_logistic_details']) > 0) {
            $result = $this->{$this->MODEL_NAME}->get($id);
            $class_type = $result[0]['CLASS_TYPE'];
            if ($class_type == 3)
                $data['readonly'] = "";
            else
                $data['readonly'] = "readonly";
        }
        $data['rec_ser'] = $id;
        $this->_look_ups($data);
        $this->load->view('receipt_batun_contracts_detail_page', $data);


    }

    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        $data['result'] = $result;
        $data['content'] = 'receipt_batun_contracts_show';
        $data['title'] = ' محاضر الفحص و الاستلام للأعمال المدنية-(باطون) ';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_look_ups($data);

        $this->load->view('template/template', $data);
    }

    function public_group_recept($id = 0)
    {
        $id = $this->input->post('committees_id') ? $this->input->post('committees_id') : $id;
        $data['rec_ser'] = $id;
        $data['rec_groups'] = $this->Receipt_batun_group_model->get_group_list($id);
        $data['group_rec_logistic_details'] = $this->Receipt_batun_group_model->get($id);
        $data['EMP_NO'] = $this->user->emp_no;
        $this->load->view('receipt_batun_contracts_group_page', $data);

    }

    function record_record_member()
    {

        $data['EMP_NO'] = $this->user->emp_no;
        for ($c = 0; $c < count($this->p_group_person_id); $c++) {
            if ($this->p_emp_no[$c] == $data['EMP_NO']) {
                $result = $this->Receipt_batun_group_model->member_record($this->p_receipt_class_input_id, $this->p_committees_id, $this->p_member_note[$c]);
            }
        }

        if ($result == -1) {

            $this->print_error('يجب ادخال ملاحظات أعضاء محضر الفحص والاستلام !!');
        }

        if ($result == -3) {
            $this->print_error('يجب اعتماد رئيس اللجنة أولا!!');

        } else {
            if (intval($result)) {

                echo $result;
            } else {
                echo $result;
            }

        }


    }

    function adopt_reacept()
    {
        $id = $this->input->post('receipt_class_input_id');
        $result = $this->{$this->MODEL_NAME}->adopt($id, 10);
        if (intval($result) <= 0) {
            $this->print_error('فشل في العملية!!');
        }
        echo $result;
    }

    function unadopt_commitee_recept()
    {

        $id = $this->input->post('receipt_class_input_id');

        $result = $this->{$this->MODEL_NAME}->adopt($id, -101);
        if (intval($result) <= 0) {
            $this->print_error('فشل في العملية!!');
        }
        echo $result;
    }

    function cancel_reacept()
    {
        $id = $this->input->post('receipt_class_input_id');
        $result = $this->{$this->MODEL_NAME}->adopt($id, -1);
        if (intval($result) <= 0) {
            $this->print_error('فشل في العملية!!');
        }
        echo $result;
    }

    ////////////////////
    function send_to_adopt()
    {
        $id = $this->input->post('id');
        $first_val = $this->input->post('first_val');
        for ($x = 1; $x < count($first_val); $x++) {
            if ($first_val[0] != $first_val[$x]) {
                $this->print_error(' لا يمكن ترحيل المحاضر لانها لا تتبع لنفس امر التوريد !!');
            }
        }




        $result = $this->{$this->MODEL_NAME}->send_to_adopt($id);
        $to =$this->get_emails_by_code('16.20');

        if (intval($result)) {

          $this->load->library('email');

            $config['mailtype'] = 'html';
            $this->email->set_newline('\r\n');
            $this->email->set_crlf('\r\n');
            $this->email->initialize($config);
            $send_text = 'يرجى اعتماد محضر فحص و استلام اعمال مدنية -(باطون) رقم  : ' . $result;
            $subject= 'يرجى اعتماد محضر فحص و استلام اعمال مدنية -(باطون) رقم  : ' . $result;
            $text = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv='content-type' content='text/html' charset='UTF-8' />
                <title>[Webinar] GS</title>
            </head>
            <body>
            <table style=\"width: 100%; direction: rtl; color: royalblue; font-size: 16px; font-weight: bold; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;\">
                <tr>
                    <td>
                     " . urldecode($send_text) . "
                    </td>
                </tr>
            </table>
            <br/><br/>
                <div>SID" . ($this->user->emp_no) . "</div>
            </body>
            </html>
        ";
            $this->email->from('admin@gedco.ps', 'النظام الموحد');
            $this->email->to($to); // can be an array as: array('m@m','A@a') or m@m,A@a
            // $this->email->subject(urldecode($subject));
            $this->email->message($text);
            $this->email->subject(urldecode($subject));
            $this->email->send();
        }
        echo intval($result);
    }

        ////////////////////////////////////////////////
        //اعتماد المحضر طرف مدير دائرة المشاريع
        function adopt_30()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
                $result = $this->{$this->MODEL_NAME}->manager_adopt($this->p_ser, 30);

                if (intval($result) <= 0) {
                    $this->print_error($this->p_ser);
                }
                echo $result;
            } else
                echo "لم يتم الاعتماد";
        }

        //اعتماد المحضر طرف مدير الادارة الفنية
        function adopt_40()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
                $result = $this->{$this->MODEL_NAME}->manager_adopt($this->p_ser, 40);
                if (intval($result) <= 0) {
                    $this->print_error('فشل في العملية!!');
                }
                echo $result;
            } else
                echo "لم يتم الاعتماد";
        }

//اعتماد المحضر طرف المدير العام
        function adopt_50()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
                $result = $this->{$this->MODEL_NAME}->manager_adopt($this->p_ser, 50);
                if (intval($result) <= 0) {
                    $this->print_error('فشل في العملية!!');
                }
                echo $result;
            } else
                echo "لم يتم الاعتماد";
        }

//////////////////////////////////////////
        function adopt__20()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
                $result = $this->{$this->MODEL_NAME}->manager_adopt($this->p_ser, -20);
                if (intval($result) <= 0) {
                    $this->print_error('فشل في العملية!!');
                }
                echo $result;
            } else
                echo "لم يتم الارجاع";
        }

        /////////////////////////////////////////////////////////////
        //ارجاع المستخلص لرئيس قسم الممارسات و العطاءات
        function adopt__30()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
                $result = $this->{$this->MODEL_NAME}->manager_adopt($this->p_ser, -30);
                if (intval($result) <= 0) {
                    $this->print_error('فشل في العملية!!');
                }
                echo $result;
            } else
                echo "لم يتم الارجاع";
        }

        /////////////////////////////////////////////////////////////
        //ارجاع المستخلص لمدير المشتريات
        function adopt__40()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
                $result = $this->{$this->MODEL_NAME}->manager_adopt($this->p_ser, -40);
                if (intval($result) <= 0) {
                    $this->print_error('فشل في العملية!!');
                }
                echo $result;
            } else
                echo "لم يتم الارجاع";
        }

        ////////////////////////////////////////////
        function Receipt_batun_adopt()
        {
            $data['title'] = '  اعتماد محاضر الفحص و الاستلام للأعمال المدنية-(باطون)';
            $data['help'] = $this->help;
            $data['content'] = 'receipt_batun_contracts_adopt_index';
            add_css('combotree.css');
            add_css('jquery.dataTables.css');
            add_js('jquery.dataTables.js');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            $this->load->view('template/template', $data);
        }

        //////////////////////////////////////////////////////////////////////////////
        function get_adopt_page($page = 1)
        {
            $this->load->library('pagination');
            $where_sql = " AND T.ADOPT >= 20 AND T.SER_ADOPT IS NOT NULL  ";
            $where_sql .= isset($this->p_order_id) && $this->p_order_id != null ? " AND  ORDER_ID ={$this->p_order_id}  " : "";
            $where_sql .= isset($this->p_order_id_text) && $this->p_order_id_text != null ? " AND ORDER_ID_TEXT LIKE '%{$this->p_order_id_text}%' " : "";
            $where_sql .= isset($this->p_real_order_id) && $this->p_real_order_id != null ? " AND  REAL_ORDER_ID LIKE '%{$this->p_real_order_id}%'  " : "";
            $where_sql .= isset($this->p_customer_resource_id) && $this->p_customer_resource_id != null ? " AND  CUSTOMER_RESOURCE_ID ={$this->p_customer_resource_id}  " : "";


            $config['base_url'] = base_url($this->PAGE_URL);
            $count_rs = $this->get_table_count("RECEIPT_BATUN_CONTRACTS_TB", $where_sql);
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

            $offset = (((($page) - 1) * $config['per_page']));
            $row = ((($page) * $config['per_page']));
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_all_adopt($where_sql, $offset, $row);
            $data['offset'] = $offset + 1;
            $data['page'] = $page;
            $this->_look_ups($data);
            $this->load->view('receipt_batun_contracts_adopt_page', $data);


        }

        ////////////////////
        function back_to_prepare()
        {
            $id = $this->input->post('id');
            $first_val = $this->input->post('first_val');
            for ($x = 1; $x < count($first_val); $x++) {
                if ($first_val[0] != $first_val[$x]) {
                    $this->print_error(' لا يمكن ارجاع المحاضر لانها لا تتبع لنفس امر التوريد !!');
                }
            }

            $result = $this->{$this->MODEL_NAME}->back_to_prepare($id);
            echo intval($result);
        }

///////////////////////////////////////////////

        function public_items_recept_adopt($id = 0)
        {


            $id = $id;
            $data['rec_logistic_details'] = $this->Receipt_batun_detail_model->get_adopt($id);

            $this->_look_ups($data);
            $this->load->view('receipt_batun_contracts_adopt_detail_page', $data);


        }

        /////////////////////////////////////////////////////////

        function get_adopt($id)
        {
            $result = $this->{$this->MODEL_NAME}->get_adopt($id);
            $data['result'] = $result;
            $data['content'] = 'receipt_batun_contracts_adopt_show';
            $data['title'] = ' محاضر الفحص و الاستلام للأعمال المدنية-(باطون) ';
            $data['action'] = 'edit';
            $data['isCreate'] = false;
            $data['help'] = $this->help;
            $next_adopt = array(
                20 => 30,
                30 => 40,
                40=>  50
            );

            $data['next_adopt_email'] = $this->get_emails_by_code('16.' . (@$next_adopt[$result[0]['ADOPT']])); // old 202003 - ($result[0]['ADOPT']+20)
            $data['prev_adopt_email'] = $this->get_emails_by_code('16.0'); // old 202003 - ($result[0]['ADOPT']+20)
            $this->_look_ups($data);

            $this->load->view('template/template', $data);
        }

        //////////////////////////////////////////////////////
        function public_committee_emails($id)
        {
            $data['committee_emails'] = $this->Receipt_batun_group_model->get_committee_emails($id);

            return $data['committee_emails'][0];


        }

    }