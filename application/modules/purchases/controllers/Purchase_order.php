<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/02/15
 * Time: 10:06 ص
 */
class Purchase_order extends MY_Controller
{

    var $MODEL_NAME = 'purchase_order_model';
    var $DETAILS_MODEL_NAME = 'purchase_order_detail_model';
    var $DET_ITEMS_MODEL_NAME = 'purchase_order_items_model';
    var $PAGE_URL = 'purchases/purchase_order/get_page';
    var $ALL_BRANCHES = 'purchases/purchase_order/all_branches';
    var $ADOPTS = 'purchases/purchase_order/adopt_';
    var $ADV_MODEL_NAME = 'advertising_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model($this->DET_ITEMS_MODEL_NAME);
        $this->load->model($this->ADV_MODEL_NAME);
        $this->load->model('Civil_works_model');
        $this->load->model("stores/receipt_class_input_group_model");
        $this->load->model("payment/Customers_model");


        // get_post vars
        $this->order_purpose = $this->input->get_post('order_purpose');
        $this->quote = $this->input->get_post('quote');
        // vars
        $this->committees = $this->input->post('committees');

        $this->purchase_order_id = $this->input->post('purchase_order_id');
        $this->purchase_order_num = $this->input->post('purchase_order_num');
        $this->purchase_order_class_type = $this->input->post('purchase_order_class_type');
        $this->committee_case = $this->input->post('committee_case');
        $this->committee_envelopes = $this->input->post('committee_envelopes');
        $this->committee_award = $this->input->post('committee_award');
        $this->purchase_type = $this->input->post('purchase_type');
        $this->quote_curr_id = $this->input->post('quote_curr_id');
        $this->quote_start_date = $this->input->post('quote_start_date');
        $this->quote_end_date = $this->input->post('quote_end_date');
        $this->quote_condition = $this->input->post('quote_condition');
        $this->notes = $this->input->post('notes');
        $this->adopt_note = $this->input->post('adopt_note');
        $this->reversion_case = $this->input->post('reversion_case');
        $this->adopt = $this->input->post('adopt');
        $this->entry_user = $this->input->post('entry_user');
        $this->design_quote_user = $this->input->post('design_quote_user');
        $this->design_quote_case = $this->input->post('design_quote_case');
        $this->delay_case = $this->input->post('delay_case');
        $this->convert_case = $this->input->post('convert_case');
        $this->branch = $this->input->post('branch'); // branch_from_user
        $this->section_no = $this->input->post('section_no');
        // arrays
        $this->ser = $this->input->post('ser');
        $this->class_id = $this->input->post('class_id');
        $this->amount = $this->input->post('amount');
        $this->approved = $this->input->post('approved');
        $this->buy_price = $this->input->post('buy_price');
        $this->class_price = $this->input->post('class_price');
        $this->note = $this->input->post('note');
        $this->order_date = $this->input->post('order_date');
        $this->order_colum = $this->input->post('order_colum');
        $this->section_no = $this->input->post('section_no');

        $this->item = $this->input->post('item');
        $this->class_unit = $this->input->post('class_unit');

        $this->group_person_id = $this->input->post('group_person_id');
        $this->group_person_date = $this->input->post('group_person_date');
        $this->g_ser = $this->input->post('h_group_ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->status = $this->input->post('status');
        $this->member_note = $this->input->post('member_note');

        $this->approved_amount = $this->input->post('approved_amount');
        $this->award_hints = $this->input->post('award_hints');
        $this->award_notes = $this->input->post('award_notes');
        $this->suppliers_discount = $this->input->post('suppliers_discount');
        $this->class_discount = $this->input->post('class_discount');
        $this->approved_price = $this->input->post('approved_price');
        $this->class_discount_value = $this->input->post('c_discount_value');
        $this->suppliers_discount_value = $this->input->post('discount_value');

        $this->from_date = $this->input->post('from_date');
        $this->to_date = $this->input->post('to_date');

        if ($this->order_purpose != null and $this->order_purpose != 1 and $this->order_purpose != 2) die('construct');

        if ($this->quote != 1) $this->quote == 0;

    }

    function index($page = 1, $committees = -1, $committee_case = -1, $purchase_order_id = -1, $purchase_order_num = -1, $purchase_type = -1, $purchase_order_class_type = -1, $notes = -1, $entry_user = -1, $adopt = -1, $design_quote_user = -1, $design_quote_case = -1, $delay_case = -1, $convert_case = -1, $from_date = -1, $to_date = -1)
    {
        $this->_look_ups($data);
        if ($this->order_purpose != 1 and $this->order_purpose != 2) die('index');

        if ($this->quote == 1) $data['title'] = 'عرض الاسعار'; elseif ($committees == 1 and $committee_case == 2) $data['title'] = 'محاضر لجنة فتح المظاريف';
        elseif ($committees == 1 and $committee_case == 4) $data['title'] = 'محاضر لجنة البت والترسية';
        //elseif ($this->order_purpose == 2) $data['title'] = 'طلبات الاعمال المدنية';
        //else
        $data['title'] = 'طلبات الشراء';

        $data['content'] = 'purchase_order_index';
        $data['order_purpose'] = $this->order_purpose;
        $data['quote'] = $this->quote;

        $this->load->model('settings/constant_details_model');
        $data['purchase_type_all'] = $this->constant_details_model->get_list(71);
        $data['purchase_order_class_type_all'] = $this->constant_details_model->get_list(69);
        $data['entry_user_all'] = $this->get_entry_users('PURCHASE_ORDER_TB');
        $data['adopt_all'] = $this->constant_details_model->get_list(68);
        $data['design_quote_user_all'] = $this->get_entry_users('PURCHASE_ORDER_TB', ' a.design_quote_user= u.id ');
        $data['design_quote_case_all'] = $this->constant_details_model->get_list(76);
        $data['committee_case_all'] = $this->constant_details_model->get_list(70);
        $data['delay_case_all'] = $this->constant_details_model->get_list(73);

        $data['page'] = $page;
        $data['committees'] = $committees;
        $data['committee_case'] = $committee_case;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['purchase_order_num'] = $purchase_order_num;
        $data['purchase_type'] = $purchase_type;
        $data['purchase_order_class_type'] = $purchase_order_class_type;
        $data['notes'] = $notes;
        $data['entry_user'] = $entry_user;
        $data['adopt'] = $adopt;
        $data['design_quote_user'] = $design_quote_user;
        $data['design_quote_case'] = $design_quote_case;
        $data['delay_case'] = $delay_case;
        $data['convert_case'] = $convert_case;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['help'] = $this->help;
        $data['action'] = 'edit';

        $this->load->view('template/template', $data);
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/currency_model');
        $this->load->model('budget/budget_section_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores/store_committees_model');
        $this->load->model('Civil_works_model');
        $data['currencies'] = $this->currency_model->get_all();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['sections'] = $this->budget_section_model->get_all();
        $data['committee_awards'] = $this->store_committees_model->get_all_by_type(2);
        $data['committee_envelopess'] = $this->store_committees_model->get_all_by_type(3);
        if (count($data['committee_awards']) != 1 or count($data['committee_envelopess']) != 1) {
            //die('committee!!');
        }
        $data['adopts'] = $this->constant_details_model->get_list(68);
        $data['purchase_order_class_types'] = $this->constant_details_model->get_list(69);
        $data['committee_cases'] = $this->constant_details_model->get_list(70);
        $data['purchase_types'] = $this->constant_details_model->get_list(71);
        $data['conditions'] = $this->constant_details_model->get_list(74);
        $data['design_quote_cases'] = $this->constant_details_model->get_list(76);
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        $data['civil_type'] = $this->Civil_works_model->get_parent(0);
        $data['help'] = $this->help;
        add_js('jquery.hotkeys.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

    }

    function get_page($page = 1, $committees = -1, $committee_case = -1, $purchase_order_id = -1, $purchase_order_num = -1, $purchase_type = -1, $purchase_order_class_type = -1, $notes = -1, $entry_user = -1, $adopt = -1, $design_quote_user = -1, $design_quote_case = -1, $delay_case = -1, $convert_case = -1, $from_date = -1, $to_date = -1)
    {
        if ($this->order_purpose != 1 and $this->order_purpose != 2) die('get_page');

        $this->load->library('pagination');

        $committees = $this->check_vars($committees, 'committees');
        $committee_case = $this->check_vars($committee_case, 'committee_case');
        $purchase_order_id = $this->check_vars($purchase_order_id, 'purchase_order_id');
        $purchase_order_num = $this->check_vars($purchase_order_num, 'purchase_order_num');
        $purchase_type = $this->check_vars($purchase_type, 'purchase_type');
        $purchase_order_class_type = $this->check_vars($purchase_order_class_type, 'purchase_order_class_type');
        $notes = $this->check_vars($notes, 'notes');
        $entry_user = $this->check_vars($entry_user, 'entry_user');
        $adopt =$this->check_vars($adopt, 'adopt');
        $design_quote_user = $this->check_vars($design_quote_user, 'design_quote_user');
        $design_quote_case = $this->check_vars($design_quote_case, 'design_quote_case');
        $delay_case = $this->check_vars($delay_case, 'delay_case');
        $convert_case = $this->check_vars($convert_case, 'convert_case');
        $from_date = $this->check_vars($from_date, 'from_date');
        $to_date = $this->check_vars($to_date, 'to_date');

        //  $where_sql = " where order_purpose='{$this->order_purpose}' ";
        $where_sql = " where 1=1  ";

        if ($this->quote == 1) $where_sql .= " and purchase_type in (2,3) and adopt=70 ";
        if (!HaveAccess(base_url($this->ALL_BRANCHES))) $where_sql .= " and branch= {$this->user->branch} ";

        $default_where_sql = $where_sql;

        if ($committee_case < 0) {
            $committee_case = $committee_case * -1;
            $def_case = 1;
        } else {
            $def_case = 0;
        }

        if (!$this->input->is_ajax_request()) {
            $def_case = 1;
        }

        $where_sql .= ($committee_case != null and $committees == 1 and $def_case == 1) ? " and committee_case >={$committee_case} and purchase_type!=1 " : '';
        $where_sql .= ($committee_case != null and $committees == 1 and $def_case == 0) ? " and committee_case ={$committee_case} and purchase_type!=1 " : '';
        $where_sql .= ($committee_case != null and $committees == null) ? " and committee_case = '{$committee_case}' " : '';

        $where_sql .= ($purchase_order_id != null) ? " and purchase_order_id= '{$purchase_order_id}' " : '';
        $where_sql .= ($purchase_order_num != null) ? " and purchase_order_num like '" . add_percent_sign($purchase_order_num) . "' " : '';
        $where_sql .= ($purchase_type != null) ? " and purchase_type= '{$purchase_type}' " : '';
        $where_sql .= ($purchase_order_class_type != null) ? " and purchase_order_class_type= '{$purchase_order_class_type}' " : '';
        $where_sql .= ($notes != null) ? " and notes like '" . add_percent_sign($notes) . "' " : '';
        $where_sql .= ($entry_user != null) ? " and entry_user= '{$entry_user}' " : '';
       // $where_sql .= ($adopt != null) ? " and adopt= '{$adopt}' " : '';
        $where_sql .= isset($this->p_adopt) && $this->p_adopt != null ? " AND  adopt ={$this->p_adopt}  " : "";
        $where_sql .= ($design_quote_user != null) ? " and design_quote_user= '{$design_quote_user}' " : '';
        $where_sql .= ($design_quote_case != null) ? " and design_quote_case= '{$design_quote_case}' " : '';
        $where_sql .= ($delay_case != null) ? " and PURCHASE_PKG.PURCHASE_ORDER_DET_HAS_AWARD(purchase_order_id,{$delay_case}) > 0 " : '';
        $where_sql .= ($convert_case != null) ? " and PURCHASE_PKG.SUPPLIERS_OFFERS_HAS_ORDER(purchase_order_id)= '{$convert_case}' " : '';
        $where_sql .= ($from_date != null) ? "   AND TRUNC( ENTRY_DATE) >=to_date('{$from_date}','DD/MM/YYYY') " : '';
        $where_sql .= ($to_date != null) ? "  AND TRUNC( ENTRY_DATE) <=to_date('{$to_date}','DD/MM/YYYY') " : '';

        if (!$this->input->is_ajax_request() and $committees == null and $this->quote != 1) {
            $adopt_where_sql = ' ';
            /*if (HaveAccess(base_url("purchases/purchase_order/create?order_purpose={$this->order_purpose}")))*/
            if (HaveAccess(base_url("purchases/purchase_order/create?order_purpose=1"))) $adopt_where_sql = " and adopt= 10 and entry_user= {$this->user->id} ";
            $ADOPT_VAL_PRIV = 0;
            $priv = $this->{$this->MODEL_NAME}->get_priv();
            if (count($priv) > 0) {
                $ADOPT_VAL_PRIV = $priv[0]['PR'];//substr($priv[0]['PR'],-2);
                //   print_r(substr($priv[0]['PR'],-2));
                $adopt_where_sql = ' and adopt=' . $ADOPT_VAL_PRIV;
            }
            /* for($i=15;$i<=70;$i+5){
                 if(HaveAccess(base_url($this->ADOPTS.$i))){
                    if ($i=15)  $adopt_where_sql= ' and adopt= 10 ';
                    else $adopt_where_sql= ' and adopt= '.($i-10).' ';

                     //heeeeeere
                 }
             }*/

            if ($ADOPT_VAL_PRIV == 40 || ($this->user->id == 477) || ($this->user->id == 116)) $adopt_where_sql .= " and purchase_pkg.ASSISTENT_MANAGER(PURCHASE_ORDER_ID)=1 ";
            $default_where_sql .= $adopt_where_sql;
            $where_sql = $default_where_sql;
        }


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' purchase_order_tb ' . $where_sql);
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
//echo $where_sql;
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('purchase_order_page', $data);
    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = ($this->{$c_var}) ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function public_index($text = null, $page = 1, $committees = -1, $committee_case = -1, $purchase_order_id = -1, $purchase_order_num = -1, $notes = -1)
    {
        if ($this->order_purpose != 1 and $this->order_purpose != 2) die('public_index');

        $data['content'] = 'purchase_order_popup_i';
        $data['order_purpose'] = $this->order_purpose;

        $data['text'] = $text;
        $data['page'] = $page;
        $data['committees'] = $committees;
        $data['committee_case'] = $committee_case;
        $data['purchase_order_id'] = $purchase_order_id;
        $data['purchase_order_num'] = $purchase_order_num;
        $data['notes'] = $notes;

        $this->load->view('template/view', $data);
    }

    function public_get_page($text = null, $page = 1, $committees = -1, $committee_case = -1, $purchase_order_id = -1, $purchase_order_num = -1, $notes = -1)
    {
        if ($this->order_purpose != 1 and $this->order_purpose != 2) die('public_get_page');

        $this->load->library('pagination');

        $committee_case = $this->check_vars($committee_case, 'committee_case');
        $purchase_order_id = $this->check_vars($purchase_order_id, 'purchase_order_id');
        $purchase_order_num = $this->check_vars($purchase_order_num, 'purchase_order_num');
        $notes = $this->check_vars($notes, 'notes');

        //  $where_sql = " where order_purpose='{$this->order_purpose}' ";
        $where_sql = " where 1=1 ";
        if ($this->quote == 1) $where_sql .= " and purchase_type in (2,3) and design_quote_date is not null ";
        if (!HaveAccess(base_url($this->ALL_BRANCHES))) $where_sql .= " and branch= {$this->user->branch} ";

        $where_sql .= ($committee_case != null) ? " and committee_case IN ({$committee_case},{$committee_case}+1) " : '';
        $where_sql .= ($purchase_order_id != null) ? " and purchase_order_id= '{$purchase_order_id}' " : '';
        $where_sql .= ($purchase_order_num != null) ? " and purchase_order_num like '" . add_percent_sign($purchase_order_num) . "' " : '';
        $where_sql .= ($notes != null) ? " and notes like '" . add_percent_sign(urldecode($notes)) . "' " : '';


        $config['base_url'] = base_url("purchases/purchase_order/public_index/$text");
        $count_rs = $this->get_table_count(' purchase_order_tb ' . $where_sql);
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
        $data['order_purpose'] = $this->order_purpose;

        $this->load->view('purchase_order_popup_p', $data);
    }

    function edit_quote()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res = $this->{$this->MODEL_NAME}->edit_quote($this->_postedData_quote());
            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            } elseif ($this->order_purpose == 1) {
                for ($i = 0; $i < count($this->ser); $i++) {
                    if ($this->ser[$i] != 0) {
                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->quote_order_date($this->ser[$i], $this->order_date[$i]);
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    }
                }
            }
            echo 1;
        }
    }

    function _postedData_quote()
    {
        $result = array(array('name' => 'PURCHASE_ORDER_ID', 'value' => $this->purchase_order_id, 'type' => '', 'length' => -1), array('name' => 'QUOTE_CURR_ID', 'value' => $this->quote_curr_id, 'type' => '', 'length' => -1), array('name' => 'QUOTE_START_DATE', 'value' => $this->quote_start_date, 'type' => '', 'length' => -1), array('name' => 'QUOTE_END_DATE', 'value' => $this->quote_end_date, 'type' => '', 'length' => -1), array('name' => 'QUOTE_CONDITION', 'value' => $this->quote_condition, 'type' => '', 'length' => -1),);
        return $result;
    }

    function edit_approved()
    {
        if (0 and $_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->ser); $i++) {
                if ($this->ser[$i] != 0 and $this->approved[$i] != '') {
                    $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->edit_approved($this->_postedData_details($this->ser[$i], null, $this->approved[$i], null, null, 'approved'));
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                }
            }
            echo 1;
        }
    }

    function _postedData_details($ser = null, $class_id, $amount = null, $buy_price = null, $note = null, $order_colum = null, $section_no = null, $typ = null)
    {
        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'PURCHASE_ORDER_ID', 'value' => $this->purchase_order_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1), array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1), array('name' => 'PRICE', 'value' => str_replace(",", "", $buy_price), 'type' => '', 'length' => -1), array('name' => 'NOTE', 'value' => $note, 'type' => '', 'length' => -1), array('name' => 'ORDER_COLUM', 'value' => $order_colum, 'type' => '', 'length' => -1), array('name' => 'SECTION_NO', 'value' => $section_no, 'type' => '', 'length' => -1));
        if ($typ == 'create') unset($result[0]);
        if ($typ == 'approved') unset($result[2], $result[4], $result[5], $result[7]);
        return $result;
    }

    function public_get_details($id = 0, $adopt = 0, $quote = 0)
    {
        $data['adopt'] = $adopt;
        $data['quote'] = $quote;
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $this->load->view('purchase_order_details', $data);
    }

    function public_get_sub_civil_type($civil_type = 0)
    {


        $civil_type = $this->input->post('civil_type') ? $this->input->post('civil_type') : $civil_type;
        $arr = $this->Civil_works_model->get_parent($civil_type);


        echo json_encode($arr);


    }

    function public_get_sub_civil_type_p($civil_type = 0)
    {

        $arr = $this->Civil_works_model->get_parent($civil_type);
        return $arr;
    }

    function public_get_det_items()
    {

        /* $id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $id;
         $adopt = $this->input->post('adopt') ? $this->input->post('adopt') : $adopt;
         $quote = $this->input->post('quote') ? $this->input->post('quote') : $quote;*/ //$civil_type = $this->input->post('civil_type');

        //$data['adopt'] = 1;//$adopt;
        $civil_type = $this->input->post('civil_type');
        $purchase_order_id = $this->input->post('purchase_order_id');
        $data['show_civil_items'] = $this->Civil_works_model->get_parent($civil_type);
        $data['details'] = $this->{$this->DET_ITEMS_MODEL_NAME}->get_list($purchase_order_id);

        $this->_look_ups($data);
        $this->load->view('purchase_order_det_items', $data);
    }

    function public_get_det_display_items($civil_type, $purchase_order_id)
    {

        $data['show_civil_items'] = $this->Civil_works_model->get_parent($civil_type);
        $data['details'] = $this->{$this->DET_ITEMS_MODEL_NAME}->get_list($purchase_order_id);

        $this->_look_ups($data);
        $this->load->view('purchase_order_det_items', $data);
    }

    function public_get_section_data()
    {
        $section_data = $this->{$this->MODEL_NAME}->get_section_data($this->branch, $this->section_no);
        if (count($section_data) == 1 || 1) $this->return_json($section_data[0]); else
            $this->return_json(array());
    }

    function adopt_0()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            if ($this->adopt_note=='')
                $this->print_error('!!يجب ادخال سبب الارجاع');
            else
            echo $this->adopt(0);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_20()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->adopt(20);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    private function adopt($case)
    {
        $res = $this->{$this->MODEL_NAME}->adopt($this->purchase_order_id, $case, $this->adopt_note, $this->purchase_type, $this->quote_curr_id, $this->section_no, $this->committee_envelopes, $this->committee_award);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        if ($case < 70) $this->_notify('adopt_' . ($case + 1), 'طلب شراء رقم ' . $this->purchase_order_id);
        return 1;
    }

    function _notify($action, $message, $id = null)
    {
        $this->_notifyMessage($action, "purchases/purchase_order/get/{$this->purchase_order_id}", $message);
    }

    function adopt_30()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->adopt(30);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_40()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->adopt(40);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_45()
    { // new - 07/05/2020
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->adopt(45);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_50()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->adopt(50);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_60()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->adopt(60);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_15()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->adopt(15);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_70()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            if ($this->purchase_type == 1 or ($this->committee_envelopes != '' and $this->committee_award != '')) {
                if ($this->purchase_type == 1) {
                    $this->committee_envelopes = null;
                    $this->committee_award = null;
                }
                echo $this->adopt(70);
            } else
                echo "اختر اللجان";
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_600()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->adopt(600);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }


    function adopt_reversion()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            $res = $this->{$this->MODEL_NAME}->adopt_reversion($this->purchase_order_id, $this->adopt_note, $this->reversion_case);
            if (intval($res) <= 0) {
                $this->print_error('لم يتم الارجاع' . '<br>' . $res);
            }
            if ($this->reversion_case == 1) $this->_notify('create?order_purpose=1', 'تم ارجاع طلب شراء رقم ' . $this->purchase_order_id);
            echo 1;
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    // الغاء اعتماد المشتريات من عرض السعر

    function quote_case_2()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->quote_case(2);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    private function quote_case($case)
    {
        $res = $this->{$this->MODEL_NAME}->quote_case($this->purchase_order_id, $case, $this->adopt_note);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        return 1;
    }

    function quote_case_3()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->quote_case(3);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function quote_case_4()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->quote_case(4);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function quote_case_5()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            echo $this->quote_case(5);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function do_order()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            $res = $this->{$this->MODEL_NAME}->do_order($this->purchase_order_id);
            if (intval($res) <= 0) {
                $this->print_error('لم يتم التحويل' . '<br>' . $res);
            }
            echo 1;
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function do_order_items()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            $res = $this->{$this->MODEL_NAME}->do_order_items($this->purchase_order_id);
            if (intval($res) <= 0) {
                $this->print_error('لم يتم التحويل' . '<br>' . $res);
            }
            echo 1;
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function public_get_purchase_order()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id != '') {
            $result = $this->{$this->MODEL_NAME}->get($this->purchase_order_id);
            echo json_encode($result);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function get($id, $action = 'index')
    {
        if ($action == 'quote') $this->quote = 1; else
            $this->quote = 0;
        $result = $this->{$this->MODEL_NAME}->get($id);
        if (!(count($result) == 1 and (HaveAccess(base_url($this->ALL_BRANCHES)) or $result[0]['BRANCH'] == $this->user->branch) and ($this->quote == 0 or ($this->quote == 1 and $result[0]['ADOPT'] == 70)))) die('get');
        $data['order_data'] = $result;
        $data['order_purpose'] = $result[0]['ORDER_PURPOSE'];
        $data['adopt_val'] = $result[0]['ADOPT'];
        $data['branch'] = $this->user->branch;
        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit') ? true : false : false;
       // $next_adopt = array(10 => 20, 15 => 30, 20 => 40, 30 => 45, 40 => 50, 45 => 60, 50 => 70, 60 => 0, 70 => 0, 0 => 0);
        $next_adopt = array(10 => 20, 15 => 0, 20 => 30, 30 => 40, 40 => 45, 45 => 50, 50 => 60, 60 => 70, 70 => 0, 0 => 0);

       // echo $result[0]['ADOPT'];
//$print=60;
//echo $print;
        //echo '....'.$this->get_emails_by_code('3.' . ($next_adopt[$print]));//$next_adopt[$result[0]['ADOPT']];
        //echo $result[0]['ADOPT'];
        //echo '....'.$this->get_emails_by_code('3.' . ($next_adopt[$result[0]['ADOPT']]));

        $data['next_adopt_email'] = $this->get_emails_by_code('3.' . ($next_adopt[$result[0]['ADOPT']]));
        // old 202003 - ($result[0]['ADOPT']+20)
        if ($result[0]['ADOPT'] == 30) {
            $data['next_adopt_email'] = $result[0]['ASSISTENT_MANAGER_EMAIL'];
        }
        if ($result[0]['ADOPT'] == 40 and $result[0]['TOTAL_COST'] < 3001) {
            $data['next_adopt_email'] = $this->get_emails_by_code('3.50');
        }


        /*echo $result[0]['ADOPT'];
        echo $data['next_adopt_email'];*/

        $data['purchase_emails'] = $this->get_emails_by_code(4);
        $data['action'] = $action;
        $data['quote'] = $this->quote;
        $data['content'] = 'purchase_order_show';
        //($branch=null,$chapter=null, $section= null,$account_id=null,$from_date=null,$to_date=null,$purchase_order_id)
        $data['balances'] = $this->{$this->MODEL_NAME}->budget_balance_no_branch($this->branch, null, null, null, null, null, $id);
        //$data['isCreate']= true;
        if ($this->quote == 1) {
            $data['title'] = 'بيانات عرض السعر';
        } elseif ($data['order_purpose'] == 2) {
            $data['title'] = 'بيانات طلب اعمال مدنية';
        } else {
            $data['title'] = 'بيانات طلب الشراء';
        }

        $this->_look_ups($data);
        $data['advs'] = $this->{$this->ADV_MODEL_NAME}->get_list_by_adver_type(2);

        $this->load->view('template/template', $data);
    }

    function public_get_group_envelope($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($id, 3);
        $this->load->view('receipt_class_input_group_page', $data);
    }

    function public_get_group_receipt($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($id, 2);
        $this->load->view('receipt_class_input_group_page', $data);
    }

    function public_get_group_delay($purchase_order_id, $delay_id = 0)
    {
        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $purchase_order_id;
        $delay_id = $this->input->post('delay_id') ? $this->input->post('delay_id') : $delay_id;
        if (intval($delay_id) > 0) $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($delay_id, 4); else
            $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($purchase_order_id, 2);

        $this->load->view('receipt_class_input_group_page', $data);
    }

    function edit_envelope_1()
    {
        for ($c = 0; $c < count($this->group_person_id); $c++) {
            $status = (isset($this->status[$c])) ? 1 : 2;
            // if ($this->group_person_id[$c]!='' ){
            if ($this->g_ser[$c] == 0) {
                $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataEnvelope(null, $this->purchase_order_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));
                if (intval($f) <= 0) {
                    $this->print_error($f);
                }
            } else {
                $e = $this->receipt_class_input_group_model->edit($this->_postGroupsDataEnvelope($this->g_ser[$c], $this->purchase_order_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));
                if (intval($e) <= 0) {
                    $this->print_error($e);
                }
            }
            //  }else{
            //      $this->receipt_class_input_group_model->delete($this->g_ser[$c]);
            //  }
        }
        echo "1";
    }

    /*function _postedData_items_details($ser= null, $item, $class_unit, $amount= null, $buy_price= null, $note= null, $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'PURCHASE_ORDER_ID','value'=>$this->purchase_order_id ,'type'=>'','length'=>-1),
            array('name'=>'ITEM','value'=>$item ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$class_unit ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT','value'=>$amount ,'type'=>'','length'=>-1),
            array('name'=>'PRICE','value'=>$buy_price ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        //if($typ=='approved') unset($result[2],$result[3],$result[5],$result[6]);
        return $result;
    }*/

    /*
     * تسنيم
     */

    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->purchase_order_id = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if (intval($this->purchase_order_id) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->purchase_order_id);
            } else {
                if ($this->purchase_order_class_type != 11) {
                    for ($i = 0; $i < count($this->class_id); $i++) {
                        if ($this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->buy_price[$i] != '' and $this->buy_price[$i] > 0) {
                            $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->buy_price[$i], $this->note[$i], $this->order_colum[$i], $this->section_no[$i], 'create'));
                            if (intval($detail_seq) <= 0) {
                                $this->print_error_del($detail_seq);
                            }
                        }
                    }
                } elseif ($this->p_purchase_order_class_type == 11) {
                    for ($i = 0; $i < count($this->p_service_amount); $i++) {
                        $detail_seq = $this->{$this->DET_ITEMS_MODEL_NAME}->create($this->_postedData_items_details(null, $this->p_service_item_id[$i], $this->p_service_amount[$i], $this->p_service_price[$i], $this->p_service_note[$i], 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error_del($detail_seq);
                        }
                        /* if($this->item[$i]!='' and $this->class_unit[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->buy_price[$i]!='' and $this->buy_price[$i]>0 ){
                             $detail_seq= $this->{$this->DET_ITEMS_MODEL_NAME}->create($this->_postedData_items_details(null, $this->item[$i], $this->class_unit[$i], $this->amount[$i], $this->buy_price[$i], $this->note[$i], 'create'));
                             if(intval($detail_seq) <= 0){
                                 $this->print_error_del($detail_seq);
                             }
                         }*/
                    }
                }
                echo intval($this->purchase_order_id);
            }

        } else {
            if ($this->order_purpose != 1 and $this->order_purpose != 2) die('create');
            $data['content'] = 'purchase_order_show';
            $data['title'] = ($this->order_purpose == 2) ? 'ادخال طلب اعمال مدنية' : 'ادخال طلب الشراء';
            $data['order_purpose'] = $this->order_purpose;
            $data['quote'] = $this->quote;
            $data['branch'] = $this->user->branch;
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

    function _post_validation($isEdit = false)
    {
        if (($this->purchase_order_id == '' and $isEdit) or $this->order_purpose == '' or $this->purchase_order_class_type == '') {
            $this->print_error('يجب ادخال جميع البيانات');
        } elseif ($this->p_purchase_order_class_type == 11) {
            if ($this->p_civil_type == 0 || $this->p_civil_type == '') {
                $this->print_error('يجل ادخال التعاقدات اللوجستية!!');
            }
            if ($this->p_sub_civil_type == 0 || $this->p_sub_civil_type == '') {
                $this->print_error('يجل ادخال تصنيف التعاقدات اللوجستية!!');
            }
        } elseif (0 and ($this->committee_envelopes == '' or $this->committee_award == '')) {
            $this->print_error('يجب اختيار اللجان');
        } else if (/*$this->order_purpose == 1*/$this->purchase_order_class_type != 11 and (!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->amount)) <= 0)) {
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        } else if (/*$this->order_purpose == 2*/$this->purchase_order_class_type == 11 and (!($this->item) or count(array_filter($this->item)) <= 0 or count(array_filter($this->amount)) <= 0)) {
            $this->print_error('يجب ادخال بند واحد على الاقل ');
        }

        if ($this->p_purchase_order_class_type == 11) {

            $this->load->model('settings/constant_details_model');
            $per = $this->constant_details_model->get_list(128);
            $all_class = array();
            for ($i = 0; $i < count($this->p_service_amount); $i++) {

                if ($this->p_service_amount[$i] == '') $this->print_error('ادخل الكمية #' . ($i + 1));
                if ($this->p_service_price[$i] == '' || $this->p_service_price[$i] == 0) $this->print_error('ادخل السعر #' . ($i + 1));
                /* $all_class[]= $this->class_id[$i];
                 if($this->amount[$i]!='' and $this->class_id[$i]=='' )
                     $this->print_error('اختر الصنف #'.($i+1));
                 elseif($this->amount[$i]!='' and ($this->buy_price[$i]=='' or $this->buy_price[$i]<=0) )
                     $this->print_error('ادخل السعر #'.($i+1));
                 elseif($this->buy_price[$i]>$this->class_price[$i] and $this->note[$i]=='' )
                     $this->print_error('ادخل ملاحظات الصنف #'.($i+1));*/
                /*  elseif($this->buy_price[$i]<($this->class_price[$i]-($per[0]['CON_NAME']*$this->class_price[$i])))
                      $this->print_error('تأكد من سعر السوق #'.($i+1));*/
            }
        } else if ($this->p_purchase_order_class_type != 11) {
            $all_class = array();
            for ($i = 0; $i < count($this->item); $i++) {
                $all_class[] = $this->item[$i];
                if ($this->amount[$i] != '' and $this->item[$i] == '') $this->print_error('ادخل البند');
                if ($this->amount[$i] != '' and $this->class_unit[$i] == '') $this->print_error('اختر الوحدة'); elseif ($this->amount[$i] != '' and ($this->buy_price[$i] == '' or $this->buy_price[$i] <= 0)) $this->print_error('ادخل السعر');
            }
        } else $this->print_error('خطأ!!');

        if (count(array_filter($all_class)) != count(array_count_values(array_filter($all_class)))) {
            $this->print_error('يوجد تكرار في الاصناف');
        }
    }

    function _postedData($typ = null)
    {
        if ($this->purchase_order_class_type == 11) $this->order_purpose = 2;
        $result = array(array('name' => 'PURCHASE_ORDER_ID', 'value' => $this->purchase_order_id, 'type' => '', 'length' => -1),
                        array('name' => 'ORDER_PURPOSE', 'value' => $this->order_purpose, 'type' => '', 'length' => -1),
                        array('name' => 'PURCHASE_ORDER_CLASS_TYPE', 'value' => $this->purchase_order_class_type, 'type' => '', 'length' => -1),
                        array('name' => 'COMMITTEE_ENVELOPES', 'value' => $this->committee_envelopes, 'type' => '', 'length' => -1),
                        array('name' => 'COMMITTEE_AWARD', 'value' => $this->committee_award, 'type' => '', 'length' => -1),
                        array('name' => 'NOTES', 'value' => $this->notes, 'type' => '', 'length' => -1),
                        array('name' => 'BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
                        array('name' => 'CIVIL_TYPE', 'value' => $this->p_civil_type, 'type' => '', 'length' => -1),
                        array('name' => 'SUB_CIVIL_TYPE', 'value' => $this->p_sub_civil_type, 'type' => '', 'length' => -1));
        if ($typ == 'create')
            unset($result[0]);
        /*else
            unset($result[0]);*/

        return $result;
    }

    function print_error_del($msg = '')
    {
        $ret = $this->{$this->MODEL_NAME}->delete($this->purchase_order_id);
        if (intval($ret) > 0) $this->print_error('لم يتم حفظ الطلب: ' . $msg); else
            $this->print_error('لم يتم حذف الطلب: ' . $msg);
    }

    function _postedData_items_details($ser = null, $service_item_id, $service_amount, $service_price, $service_note, $typ = null)
    {
        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'PURCHASE_ORDER_ID', 'value' => $this->purchase_order_id, 'type' => '', 'length' => -1), array('name' => 'ITEM_ID', 'value' => $service_item_id, 'type' => '', 'length' => -1), array('name' => 'AMOUNT', 'value' => $service_amount, 'type' => '', 'length' => -1), array('name' => 'PRICE', 'value' => $service_price, 'type' => '', 'length' => -1), array('name' => 'NOTE', 'value' => $service_note, 'type' => '', 'length' => -1),);
        if ($typ == 'create') unset($result[0]);

        return $result;
    }

    function _postGroupsDataEnvelope($ser, $id, $group_person_id, $group_person_date, $emp_no, $status, $member_note, $ty = null)
    {
        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1), array('name' => 'GROUP_PERSON_ID', 'value' => $group_person_id, 'type' => '', 'length' => -1), array('name' => 'GROUP_PERSON_DATE', 'value' => $group_person_date, 'type' => '', 'length' => -1), array('name' => 'EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1), array('name' => 'SOURCE', 'value' => 3, 'type' => '', 'length' => -1), array('name' => 'STATUS', 'value' => $status, 'type' => 1, 'length' => -1), array('name' => 'MEMBER_NOTE', 'value' => $member_note, 'type' => 1, 'length' => -1));
        if ($ty == 'create') {
            array_shift($result);
        }
        return $result;
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(true);
            $update_res = $this->{$this->MODEL_NAME}->get($this->purchase_order_id);

            if (count($update_res) != 0) {
                $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $res);
                } else {

                    if ($update_res[0]["PURCHASE_ORDER_CLASS_TYPE"] != 11) {
                        for ($i = 0; $i < count($this->class_id); $i++) {

                            if ($this->ser[$i] == 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->buy_price[$i] != '' and $this->buy_price[$i] > 0) { // create
                                $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->buy_price[$i], $this->note[$i], $this->order_colum[$i], $this->section_no[$i], 'create'));
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error($detail_seq);
                                }
                            } elseif ($this->ser[$i] != 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->buy_price[$i] != '' and $this->buy_price[$i] > 0) { // edit
                                $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->class_id[$i], $this->amount[$i], $this->buy_price[$i], $this->note[$i], $this->order_colum[$i], $this->section_no[$i], 'edit'));
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error($detail_seq);
                                }
                            } elseif ($this->ser[$i] != 0 and ($this->amount[$i] == '' or $this->amount[$i] == 0)) { // delete
                                $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser[$i]);
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error($detail_seq);
                                }
                            }
                        }
                    } elseif ($update_res[0]["PURCHASE_ORDER_CLASS_TYPE"] == 11) {
                        for ($i = 0; $i < count($this->p_service_amount); $i++) {
                            if ($this->p_service_ser[$i] == 0) {
                                $detail_seq = $this->{$this->DET_ITEMS_MODEL_NAME}->create($this->_postedData_items_details(null, $this->p_service_item_id[$i], $this->p_service_amount[$i], $this->p_service_price[$i], $this->p_service_note[$i], 'create'));
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error_del($detail_seq);
                                }
                            } elseif ($this->p_service_ser[$i] != 0 and ($this->p_service_amount[$i] != 0)) {

                                $detail_seq = $this->{$this->DET_ITEMS_MODEL_NAME}->edit($this->_postedData_items_details($this->p_service_ser[$i], $this->p_service_item_id[$i], $this->p_service_amount[$i], $this->p_service_price[$i], $this->p_service_note[$i], 'edit'));
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error_del($detail_seq);
                                }
                            } elseif ($this->p_service_ser[$i] != 0 and ($this->p_service_amount[$i] == '' or $this->p_service_amount[$i] == 0)) {// delete

                                $detail_seq = $this->{$this->DET_ITEMS_MODEL_NAME}->delete($this->p_service_ser[$i]);
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error_del($detail_seq);
                                }
                            }
                        }
                    }

                    /*elseif ($res[0]['purchase_order_class_type'] == 11) {
                        for ($i = 0; $i < count($this->item); $i++) {
                            if ($this->ser[$i] == 0 and $this->item[$i] != '' and $this->class_unit[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->buy_price[$i] != '' and $this->buy_price[$i] > 0) { // create
                                $detail_seq = $this->{$this->DET_ITEMS_MODEL_NAME}->create($this->_postedData_items_details(null, $this->item[$i], $this->class_unit[$i], $this->amount[$i], $this->buy_price[$i], $this->note[$i], 'create'));
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error($detail_seq);
                                }
                            } elseif ($this->ser[$i] != 0 and $this->item[$i] != '' and $this->class_unit[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->buy_price[$i] != '' and $this->buy_price[$i] > 0) { // edit
                                $detail_seq = $this->{$this->DET_ITEMS_MODEL_NAME}->edit($this->_postedData_items_details($this->ser[$i], $this->item[$i], $this->class_unit[$i], $this->amount[$i], $this->buy_price[$i], $this->note[$i], 'edit'));
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error($detail_seq);
                                }
                            } elseif ($this->ser[$i] != 0 and ($this->amount[$i] == '' or $this->amount[$i] == 0)) { // delete
                                $detail_seq = $this->{$this->DET_ITEMS_MODEL_NAME}->delete($this->ser[$i]);
                                if (intval($detail_seq) <= 0) {
                                    $this->print_error($detail_seq);
                                }
                            }
                        }
                    }*/
                    echo 1;
                }
            }
            else
            {
                $this->print_error('إجراء خاطئ!!');
            }
        }
    }

    function edit_envelope_2()
    {
        for ($c = 0; $c < count($this->group_person_id); $c++) {
            $status = (isset($this->status[$c])) ? 1 : 2;
            // if ($this->group_person_id[$c]!='' ){
            if ($this->g_ser[$c] == 0) {
                $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataEnvelope(null, $this->purchase_order_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));
                if (intval($f) <= 0) {
                    $this->print_error($f);
                }
            } else {
                $e = $this->receipt_class_input_group_model->edit($this->_postGroupsDataEnvelope($this->g_ser[$c], $this->purchase_order_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));
                if (intval($e) <= 0) {
                    $this->print_error($e);
                }
            }
            //  }else{
            //      $this->receipt_class_input_group_model->delete($this->g_ser[$c]);
            //  }
        }
        echo "1";
    }

    function edit_award_1()
    {
        $this->load->model("suppliers_offers_det_model");
        $this->load->model("suppliers_offers_model");
//right table tas
        $rec_details = $this->purchase_order_detail_model->get_details_all($this->purchase_order_id);
        foreach ($rec_details as $row) :
            $s = $this->purchase_order_detail_model->update_award($row['CLASS_ID'], $row['CLASS_UNIT'], $this->p_award_delay_decision[$row['CLASS_ID']], $this->p_award_delay_decision_hint[$row['CLASS_ID']], 0);
            if (intval($s) <= 0) {
                $this->print_error($s);
            }
        endforeach;

        //left tables  tas
        $suppliers_details = $this->suppliers_offers_det_model->get_details_all_by_purchase($this->purchase_order_id);

        foreach ($suppliers_details as $row) :
            //     echo $this->award_hints[$row['CUSTOMER_ID']][$row['CLASS_ID']];
            //   print_r($this->p_award_delay_decision);

            $msg = $this->suppliers_offers_det_model->award($row['SER'], $row['CLASS_ID'], $this->approved_amount[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->class_discount[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->approved_price[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->class_discount_value[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']]);
            if (intval($msg) <= 0) {
                $this->print_error($msg);
            }
            //  echo    $row['SER']."ffff" ;
        endforeach;

//PRINT_R($this->award_notes);
        //first table tas
        $suppliers_offers_data = $this->suppliers_offers_model->get_lists($this->purchase_order_id);

        foreach ($suppliers_offers_data as $row1) :

            $msg1 = $this->suppliers_offers_model->award_notes($row1['SUPPLIERS_OFFERS_ID'], $this->award_notes[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount_value[$row1['SUPPLIERS_OFFERS_ID']]);
            if (intval($msg1) <= 0) {
                $this->print_error($msg1);
            }
        endforeach;

//groups tas
        for ($c = 0; $c < count($this->group_person_id); $c++) {
            $status = (isset($this->status[$c])) ? 1 : 2;
            //  echo $status."j";
            //    if ($this->group_person_id[$c]!='' ){
            if ($this->g_ser[$c] == 0) {
                $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataAward(null, $this->purchase_order_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));
                if (intval($f) <= 0) {
                    $this->print_error($f);
                }
            } else {
                $e = $this->receipt_class_input_group_model->edit($this->_postGroupsDataAward($this->g_ser[$c], $this->purchase_order_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));
                if (intval($e) <= 0) {
                    $this->print_error($e);
                }
            }
            //    }else{
            //     $this->receipt_class_input_group_model->delete($this->g_ser[$c]);
            //  }
        }
        echo "1";
    }

    function _postGroupsDataAward($ser, $id, $group_person_id, $group_person_date, $emp_no, $status, $member_note, $ty = null)
    {
        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1), array('name' => 'GROUP_PERSON_ID', 'value' => $group_person_id, 'type' => '', 'length' => -1), array('name' => 'GROUP_PERSON_DATE', 'value' => $group_person_date, 'type' => '', 'length' => -1), array('name' => 'EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1), array('name' => 'SOURCE', 'value' => 2, 'type' => '', 'length' => -1), array('name' => 'STATUS', 'value' => $status, 'type' => 1, 'length' => -1), array('name' => 'MEMBER_NOTE', 'value' => $member_note, 'type' => 1, 'length' => -1));
        if ($ty == 'create') {
            array_shift($result);
        }
        // print_r ($result);
        return $result;
    }

    function edit_award_2()
    {
        $this->load->model("suppliers_offers_items_model");
        $this->load->model("suppliers_offers_model");

        $rec_details = $this->purchase_order_items_model->get_details_all($this->purchase_order_id);
        //  PRINT_R($rec_details);
        foreach ($rec_details as $row) :
            $s = $this->purchase_order_items_model->update_award($row['SER'], $row['CLASS_UNIT'], $this->p_award_delay_decision[$row['SER']], $this->p_award_delay_decision_hint[$row['SER']], 0);
            if (intval($s) <= 0) {
                $this->print_error($s);
            }
        endforeach;
        $suppliers_details = $this->suppliers_offers_items_model->get_details_all_by_purchase($this->purchase_order_id);
////////////////HERRRRRRRRRRRRRRRRE-------------

        foreach ($suppliers_details as $row) :
            //     echo $this->award_hints[$row['CUSTOMER_ID']][$row['CLASS_ID']];
            //   print_r($this->p_award_delay_decision);

            $msg = $this->suppliers_offers_items_model->award($row['SER'], $this->approved_amount[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->class_discount[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->approved_price[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->class_discount_value[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']]);
            if (intval($msg) <= 0) {
                $this->print_error($msg);
            }
            //  echo    $row['SER']."ffff" ;
        endforeach;

//PRINT_R($this->award_notes);
        $suppliers_offers_data = $this->suppliers_offers_model->get_lists($this->purchase_order_id);

        foreach ($suppliers_offers_data as $row1) :

            $msg1 = $this->suppliers_offers_model->award_notes($row1['SUPPLIERS_OFFERS_ID'], $this->award_notes[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount_value[$row1['SUPPLIERS_OFFERS_ID']]);
            if (intval($msg1) <= 0) {
                $this->print_error($msg1);
            }
        endforeach;


        for ($c = 0; $c < count($this->group_person_id); $c++) {
            $status = (isset($this->status[$c])) ? 1 : 2;
            //  echo $status."j";
            //    if ($this->group_person_id[$c]!='' ){
            if ($this->g_ser[$c] == 0) {
                $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataAward(null, $this->purchase_order_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));
                if (intval($f) <= 0) {
                    $this->print_error($f);
                }
            } else {
                $e = $this->receipt_class_input_group_model->edit($this->_postGroupsDataAward($this->g_ser[$c], $this->purchase_order_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));
                if (intval($e) <= 0) {
                    $this->print_error($e);
                }
            }
            //    }else{
            //     $this->receipt_class_input_group_model->delete($this->g_ser[$c]);
            //  }
        }
        echo "1";
    }

    function committee_adopt2_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 2);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adopt2_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 2);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adopt3_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 3);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adopt3_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 3);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adopt4_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 4);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adopt4_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 4);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adopt5_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 5);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }

        $this->_notifyMessage('adopt_5', "purchases/suppliers_offers/award1/{$id}", "اعتماد المدير العام لبت وترسية طلب شراء رقم {$id}");
        echo 1;

    }

    function committee_adopt6_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 6);

        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adoptc6_1()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 5);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adopt5_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 5);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        $this->_notifyMessage('adopt_5', "purchases/suppliers_offers/award2/{$id}", "اعتماد المدير العام لبت وترسية طلب شراء رقم {$id}");

        echo 1;

    }

    function committee_adopt6_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 6);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function committee_adoptc6_2()
    {
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->committee_adopt($id, 5);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم إلغاء الاعتماد' . '<br>' . $res);
        }
        echo 1;

    }

    function edit_delay1()
    {
        $this->load->model("suppliers_offers_det_model");
        $this->load->model("suppliers_offers_model");
        $this->load->model("suppliers_offers_delay_model");

        if (intval($this->p_delay_id) > 0) {
            $this->p_delay_id = $this->suppliers_offers_delay_model->edit($this->_postedDataDelay('edit'));
            // -------
            if (intval($this->p_delay_id) <= 0) $this->print_error($this->p_delay_id . "فشل في حفظ التعديل ");
            // -------
            $rec_details = $this->purchase_order_detail_model->get_lists(" AND M.AWARD_DELAY_DECISION=3 AND M.DELAY_ID={$this->p_delay_id}");
            $suppliers_details = $this->suppliers_offers_det_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$this->p_purchase_order_id} AND P.AWARD_DELAY_DECISION=3 AND P.DELAY_ID={$this->p_delay_id}");
            foreach ($rec_details as $row) :
                $s = $this->purchase_order_detail_model->update_award($row['CLASS_ID'], $row['CLASS_UNIT'], $this->p_award_delay_decision[$row['CLASS_ID']], $this->p_award_delay_decision_hint[$row['CLASS_ID']], $this->p_delay_id);
                if (intval($s) <= 0) {
                    $this->print_error($s . "s");
                }
            endforeach;
            // -------
            //   $suppliers_details= $this->suppliers_offers_det_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$this->p_purchase_order_id} AND P.AWARD_DELAY_DECISION=3 AND P.DELAY_ID={$this->p_delay_id}");
//print_r($suppliers_details);
            //print_r($suppliers_details);
            //  $x="";
            foreach ($suppliers_details as $row) :
//echo $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']]."d";

                //   $x.= $row['SUPPLIERS_OFFERS_ID']."ii";
                $msg = $this->suppliers_offers_det_model->award($row['SER'], $row['CLASS_ID'], $this->approved_amount[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->class_discount[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->approved_price[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->class_discount_value[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']]);

                if (intval($msg) <= 0) {
                    $this->print_error($msg . "msg");
                }

            endforeach;
            // $this->print_error($x);
            $suppliers_offers_data = $this->suppliers_offers_model->get_lists($this->purchase_order_id);

            foreach ($suppliers_offers_data as $row1) :
                $msg1 = $this->suppliers_offers_model->award_notes($row1['SUPPLIERS_OFFERS_ID'], $this->award_notes[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount_value[$row1['SUPPLIERS_OFFERS_ID']]);
                if (intval($msg1) <= 0) {
                    $this->print_error($msg1 . "msg1");
                }
            endforeach;

            for ($c = 0; $c < count($this->group_person_id); $c++) {
                $status = (isset($this->status[$c])) ? 1 : 2;

                if ($this->g_ser[$c] == 0) {
                    $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataDelay(null, $this->p_delay_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));

                    if (intval($f) <= 0) {
                        $this->print_error($f . "f");
                    }
                } else {
                    $e = $this->receipt_class_input_group_model->edit($this->_postGroupsDataDelay($this->g_ser[$c], $this->p_delay_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));
                    if (intval($e) <= 0) {
                        $this->print_error($e);
                    }
                }
            }
            //----------------------
            //------------------------------------------ELSE----------
        } else {
            if (!in_array(3, $this->p_award_delay_decision)) $this->print_error("عليك تنفيذ أصناف أولا");

            $this->p_delay_id = $this->suppliers_offers_delay_model->create($this->_postedDataDelay('create'));
            // -------
            if (intval($this->p_delay_id) <= 0) $this->print_error($this->p_delay_id . "فشل في حفظ طلب تأجيل");
            // -------
            $rec_details = $this->purchase_order_detail_model->get_lists(" AND M.AWARD_DELAY_DECISION=2");
            $suppliers_details = $this->suppliers_offers_det_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$this->p_purchase_order_id} AND P.AWARD_DELAY_DECISION=2");

            foreach ($rec_details as $row) :
                $s = $this->purchase_order_detail_model->update_award($row['CLASS_ID'], $row['CLASS_UNIT'], $this->p_award_delay_decision[$row['CLASS_ID']], $this->p_award_delay_decision_hint[$row['CLASS_ID']], $this->p_delay_id);
                if (intval($s) <= 0) {
                    $this->print_error($s . "s");
                }
            endforeach;
            // -------
            //   $suppliers_details= $this->suppliers_offers_det_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$this->p_purchase_order_id} AND P.AWARD_DELAY_DECISION=3 AND P.DELAY_ID={$this->p_delay_id}");
//print_r($suppliers_details);
            foreach ($suppliers_details as $row) :
//echo $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']]."d";
                $msg = $this->suppliers_offers_det_model->award($row['SER'], $row['CLASS_ID'], $this->approved_amount[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->class_discount[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->approved_price[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']], $this->class_discount_value[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']]);
                if (intval($msg) <= 0) {
                    $this->print_error($msg . "msg");
                }

            endforeach;

            $suppliers_offers_data = $this->suppliers_offers_model->get_lists($this->purchase_order_id);

            foreach ($suppliers_offers_data as $row1) :
                $msg1 = $this->suppliers_offers_model->award_notes($row1['SUPPLIERS_OFFERS_ID'], $this->award_notes[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount_value[$row1['SUPPLIERS_OFFERS_ID']]);
                if (intval($msg1) <= 0) {
                    $this->print_error($msg1 . "msg1");
                }
            endforeach;

            for ($c = 0; $c < count($this->group_person_id); $c++) {
                $status = (isset($this->status[$c])) ? 1 : 2;
                //  echo $status."j";
                //    if ($this->group_person_id[$c]!='' ){
                //   if($this->g_ser[$c] == 0){
                $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataDelay(null, $this->p_delay_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));

                if (intval($f) <= 0) {
                    $this->print_error($f . "f");
                }
                //   }else{
                //       $e= $this->receipt_class_input_group_model->edit($this->_postGroupsDataDelay($this->g_ser[$c],$this->p_delay_id,$this->group_person_id[$c],$this->group_person_date[$c],$this->emp_no[$c],$status,'edit'));
                //       if(intval($e) <= 0){
                //           $this->print_error($e);
                //        }
                //    }
                //    }else{
                //     $this->receipt_class_input_group_model->delete($this->g_ser[$c]);
                //  }
            }
            //----------------------

        }


        echo $this->p_delay_id;
    }

    function _postedDataDelay($typ = null)
    {
        $result = array(array('name' => 'DELAY_ID', 'value' => $this->p_delay_id, 'type' => '', 'length' => -1), array('name' => 'PURCHASE_ORDER_ID', 'value' => $this->p_purchase_order_id, 'type' => '', 'length' => -1), array('name' => 'AWARD_NOTES', 'value' => $this->p_award_notess, 'type' => '', 'length' => -1));
        if ($typ == 'create') unset($result[0]);


        return $result;
    }

    function _postGroupsDataDelay($ser, $id, $group_person_id, $group_person_date, $emp_no, $status, $member_note, $ty = null)
    {
        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1), array('name' => 'GROUP_PERSON_ID', 'value' => $group_person_id, 'type' => '', 'length' => -1), array('name' => 'GROUP_PERSON_DATE', 'value' => $group_person_date, 'type' => '', 'length' => -1), array('name' => 'EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1), array('name' => 'SOURCE', 'value' => 4, 'type' => '', 'length' => -1), array('name' => 'STATUS', 'value' => $status, 'type' => 1, 'length' => -1), array('name' => 'MEMBER_NOTE', 'value' => $member_note, 'type' => 1, 'length' => -1)

        );
        if ($ty == 'create') {
            array_shift($result);
        }
        // print_r ($result);
        return $result;
    }

//-------------------------------------------

    function edit_delay2()
    {
        $this->load->model("suppliers_offers_items_model");
        $this->load->model("suppliers_offers_model");
        $this->load->model("suppliers_offers_delay_model");

        if (intval($this->p_delay_id) > 0) {
            $this->p_delay_id = $this->suppliers_offers_delay_model->edit($this->_postedDataDelay('edit'));
            // -------
            if (intval($this->p_delay_id) <= 0) $this->print_error($this->p_delay_id . "فشل في حفظ التعديل ");
            // -------
            $rec_details = $this->purchase_order_items_model->get_lists(" AND M.AWARD_DELAY_DECISION=3 AND M.DELAY_ID={$this->p_delay_id}");
            $suppliers_details = $this->suppliers_offers_items_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$this->p_purchase_order_id} AND P.AWARD_DELAY_DECISION=3 AND P.DELAY_ID={$this->p_delay_id}");
            foreach ($rec_details as $row) :
                $s = $this->purchase_order_items_model->update_award($row['SER'], $row['CLASS_UNIT'], $this->p_award_delay_decision[$row['SER']], $this->p_award_delay_decision_hint[$row['SER']], $this->p_delay_id);
                if (intval($s) <= 0) {
                    $this->print_error($s . "s");
                }
            endforeach;
            // -------
            //   $suppliers_details= $this->suppliers_offers_det_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$this->p_purchase_order_id} AND P.AWARD_DELAY_DECISION=3 AND P.DELAY_ID={$this->p_delay_id}");
//print_r($suppliers_details);
            //print_r($suppliers_details);
            //  $x="";
            foreach ($suppliers_details as $row) :
//echo $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']]."d";

                //   $x.= $row['SUPPLIERS_OFFERS_ID']."ii";
                $msg = $this->suppliers_offers_items_model->award($row['SER'], $this->approved_amount[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->class_discount[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->approved_price[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->class_discount_value[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']]);
                if (intval($msg) <= 0) {
                    $this->print_error($msg . "msg");
                }

            endforeach;
            // $this->print_error($x);
            $suppliers_offers_data = $this->suppliers_offers_model->get_lists($this->purchase_order_id);

            foreach ($suppliers_offers_data as $row1) :
                $msg1 = $this->suppliers_offers_model->award_notes($row1['SUPPLIERS_OFFERS_ID'], $this->award_notes[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount_value[$row1['SUPPLIERS_OFFERS_ID']]);
                if (intval($msg1) <= 0) {
                    $this->print_error($msg1 . "msg1");
                }
            endforeach;

            for ($c = 0; $c < count($this->group_person_id); $c++) {
                $status = (isset($this->status[$c])) ? 1 : 2;

                if ($this->g_ser[$c] == 0) {
                    //   PRINT_R($this->_postGroupsDataDelay(null,$this->p_delay_id,$this->group_person_id[$c],$this->group_person_date[$c],$this->emp_no[$c],$status,$this->member_note[$c],'create'));
                    $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataDelay(null, $this->p_delay_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));

                    if (intval($f) <= 0) {
                        $this->print_error($f . "f");
                    }
                } else {
                    $e = $this->receipt_class_input_group_model->edit($this->_postGroupsDataDelay($this->g_ser[$c], $this->p_delay_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));
                    if (intval($e) <= 0) {
                        $this->print_error($e);
                    }
                }
            }
            //----------------------
            //------------------------------------------ELSE----------
        } else {
            if (!in_array(3, $this->p_award_delay_decision)) $this->print_error("عليك تنفيذ أصناف أولا");

            $this->p_delay_id = $this->suppliers_offers_delay_model->create($this->_postedDataDelay('create'));
            // -------
            if (intval($this->p_delay_id) <= 0) $this->print_error($this->p_delay_id . "فشل في حفظ طلب تأجيل");
            // -------
            $rec_details = $this->purchase_order_items_model->get_lists(" AND M.AWARD_DELAY_DECISION=2");
            $suppliers_details = $this->suppliers_offers_items_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$this->p_purchase_order_id} AND P.AWARD_DELAY_DECISION=2");

            foreach ($rec_details as $row) :
                $s = $this->purchase_order_items_model->update_award($row['SER'], $row['CLASS_UNIT'], $this->p_award_delay_decision[$row['SER']], $this->p_award_delay_decision_hint[$row['SER']], $this->p_delay_id);
                if (intval($s) <= 0) {
                    $this->print_error($s . "s");
                }
            endforeach;
            // -------
            //   $suppliers_details= $this->suppliers_offers_det_model->get_details_all_by_SQL("  AND P.PURCHASE_ORDER_ID={$this->p_purchase_order_id} AND P.AWARD_DELAY_DECISION=3 AND P.DELAY_ID={$this->p_delay_id}");
//print_r($suppliers_details);
            foreach ($suppliers_details as $row) :
//echo $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['CLASS_ID']]."d";
                $msg = $this->suppliers_offers_items_model->award($row['SER'], $this->approved_amount[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->award_hints[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->class_discount[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->approved_price[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']], $this->class_discount_value[$row['SUPPLIERS_OFFERS_ID']][$row['PURCHASE_SER']]);
                if (intval($msg) <= 0) {
                    $this->print_error($msg . "msg");
                }

            endforeach;

            $suppliers_offers_data = $this->suppliers_offers_model->get_lists($this->purchase_order_id);

            foreach ($suppliers_offers_data as $row1) :
                $msg1 = $this->suppliers_offers_model->award_notes($row1['SUPPLIERS_OFFERS_ID'], $this->award_notes[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount[$row1['SUPPLIERS_OFFERS_ID']], $this->suppliers_discount_value[$row1['SUPPLIERS_OFFERS_ID']]);
                if (intval($msg1) <= 0) {
                    $this->print_error($msg1 . "msg1");
                }
            endforeach;

            for ($c = 0; $c < count($this->group_person_id); $c++) {
                $status = (isset($this->status[$c])) ? 1 : 2;
                //  echo $status."j";
                //    if ($this->group_person_id[$c]!='' ){
                //   if($this->g_ser[$c] == 0){
                $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataDelay(null, $this->p_delay_id, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));

                if (intval($f) <= 0) {
                    $this->print_error($f . "f");
                }
                //   }else{
                //       $e= $this->receipt_class_input_group_model->edit($this->_postGroupsDataDelay($this->g_ser[$c],$this->p_delay_id,$this->group_person_id[$c],$this->group_person_date[$c],$this->emp_no[$c],$status,'edit'));
                //       if(intval($e) <= 0){
                //           $this->print_error($e);
                //        }
                //    }
                //    }else{
                //     $this->receipt_class_input_group_model->delete($this->g_ser[$c]);
                //  }
            }
            //----------------------

        }


        echo $this->p_delay_id;
    }

    function merge()
    {
        $id = $this->input->post('id');

        $result = $this->{$this->MODEL_NAME}->merge_purchase_order($id);
        //  $this->return_json($result);
        echo intval($result);
    }

    function divide_purchase_order()
    {
        $id = $this->input->post('id');
        $purchase_order_id = $this->input->post('purchase_order_id');
        $result = $this->{$this->MODEL_NAME}->distribute_purchase_order($purchase_order_id, $id);
        //  $this->return_json($result);
        echo intval($result);
    }


    function add_adv()
    {
        $id = $this->input->post('id');
        $adver_no = $this->input->post('adver_no');
        $serial = $this->input->post('serial');

        $res = $this->{$this->MODEL_NAME}->add_advertisement($id, $adver_no, $serial);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $res);
        }
        echo 1;

    }

    function sms()
    {

        $message = $this->input->post('sms_text');
        $message = urlencode($message);

        // $mobile="0599498294,0595034800";
        $mobile = $this->input->post('mobile');
        //  $url="http://www.hotsms.ps/sendbulksms.php?user_name=IT-GEDCO-1&user_pass=6454236&sender=GEDCO&mobile=".$mobile."&type=0&text=".$message;
        $url = "https://im-server.gedco.ps:8005/apis/v1/TrandersSms?mobile=" . $mobile . "&message=" . $message;
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        // grab URL and pass it to the browser
        curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);


    }

    function _hex_chars($data)
    {
        $mb_hex = '';
        for ($i = 0; $i < mb_strlen($data, 'UTF-8'); $i++) {
            $c = mb_substr($data, $i, 1, 'UTF-8');
            $o = unpack('N', mb_convert_encoding($c, 'UCS-4BE', 'UTF-8'));
            $mb_hex .= sprintf('%04X', $o[1]);
        }
        return $mb_hex;

    }

    function index_sms($page = 1, $purchase_type = -1)
    {

        $data['title'] = ' إرسال رسائل SMS للموردين';
        $data['page'] = $page;
        $data['content'] = 'purchase_order_sms_index';

        $this->load->model('settings/constant_details_model');
        $data['purchase_order_class_type_all'] = $this->constant_details_model->get_list(69);

        $data['purchase_type'] = $purchase_type;
        $data['help'] = $this->help;
        $data['action'] = 'edit';

        $this->load->view('template/template', $data);
    }

    function get_page_sms($page = 1, $purchase_type = -1)
    {


        $this->load->library('pagination');

        $purchase_type = ($this->input->post('purchase_type') != null) ? $this->input->post('purchase_type') : array();

        // $purchase_type= (isset ($purchase_type) && $purchase_type !='') ? $this->check_vars($purchase_type,'purchase_type') : array();
        $purchase_type_t = "0";


        for ($i = 0; $i < count($purchase_type); $i++) {

            if ($purchase_type[$i] != 0) $purchase_type_t = $purchase_type_t . "," . $purchase_type[$i];
        }
        //  print_r( $purchase_type_t);
        $where_sql = "  ";
        $where_sql .= ($purchase_type_t != "0") ? " and M.purchase_type in ( {$purchase_type_t} )" : '';

        $config['base_url'] = base_url('purchases/purchase_order/get_page_sms');
        $count_rs = $this->get_table_count(' CUSTOMERS_PURCHASE_TB M WHERE 1=1  ' . $where_sql);

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

        $data['page_rows'] = $this->Customers_model->customers_purchase_get_list($where_sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('purchase_order_sms_page', $data);
    }


}
