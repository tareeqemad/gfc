<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 10:00 ص
 */
class Customers extends MY_Controller
{

    var $MODEL_NAME = 'customers_model';
    var $PAGE_URL = 'payment/customers/get_page';
    var $PAGE_URL_PURCHASES = 'payment/customers/get_page_purchases';

    var $customer_seq, $customer_id, $customer_name, $customer_type, $account_id,
        $company_owner, $company_delegate_id, $customer_delegate_name, $company_work_scope, $company_level_scope,
        $company_field_work, $company_subscription_date,
        $customer_bank_account, $customer_bank_old_account,
        $phone, $mobil, $fax, $email, $web, $address,
        $iban;

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('customer_elect_details_model');
        $this->load->model('customer_activities_model');
        $this->load->model('customers_accounts_det_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('purchases/activity_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('financial/accounts_model');

        $this->customer_seq = $this->input->post('customer_seq');
        $this->customer_id = $this->input->post('customer_id');
        $this->customer_name = $this->input->post('customer_name');
        $this->customer_type = $this->input->post('customer_type');
        $this->account_id = $this->input->post('account_id');
        $this->company_owner = $this->input->post('company_owner');
        $this->company_delegate_id = $this->input->post('company_delegate_id');
        $this->customer_delegate_name = $this->input->post('customer_delegate_name');
        $this->company_work_scope = $this->input->post('company_work_scope');
        $this->company_level_scope = $this->input->post('company_level_scope');
        $this->company_field_work = $this->input->post('company_field_work');
        $this->company_subscription_date = $this->input->post('company_subscription_date');
        $this->bank = $this->input->post('bank');
        $this->customer_bank_account = $this->input->post('customer_bank_account');
        $this->customer_bank_old_account = $this->input->post('customer_bank_old_account');
        $this->phone = $this->input->post('phone');
        $this->mobil = $this->input->post('mobil');
        $this->fax = $this->input->post('fax');
        $this->email = $this->input->post('email');
        $this->web = $this->input->post('web');
        $this->address = $this->input->post('address');
        $this->iban = $this->input->post('iban');
        $this->account_type = $this->input->post('account_type');
        $this->t_ser_no = $this->input->post('t_ser_no');
        $this->t_date = $this->input->post('t_date');
        $this->t_total_value = $this->input->post('t_total_value');
        $this->t_rest_value = $this->input->post('t_rest_value');
        // arrays
        $this->ser = $this->input->post('SER');
        $this->elect_no = $this->input->post('elect_no');
        $this->notes = $this->input->post('notes');

        $this->a_ser = $this->input->post('A_SER');
        $this->customer_accounts_id = $this->input->post('customer_accounts_id');
        $this->account_det_type = $this->input->post('account_det_type');

        $this->act_ser = $this->input->post('act_ser');
        $this->activity_no = $this->input->post('activity_no');

    }

    function index($page = 1, $c_type = -1)
    {
        $data['content'] = 'customers_home';
        $data['page'] = $page;

        $data['account_cons'] = $this->accounts_model->getList('', 0, 1); // 2010202

        if ($c_type == 'sun') {
            $data['c_type'] = 7;
            $data['title'] = 'مستفيدي الطاقة الشمسية';
        } else {
            $data['c_type'] = -1;
            $data['title'] = 'المستفيدين';
        }
        $data['help'] = $this->help;
        $data['action'] = 'edit';

        $this->_look_ups($data);

        $this->load->view('template/template', $data);
    }

    function _look_ups(&$data)
    {
        $data['customer_type'] = $this->constant_details_model->get_list(28);
        $data['company_work_scope'] = $this->constant_details_model->get_list(25);
        $data['company_level_scope'] = $this->constant_details_model->get_list(26);
        $data['company_field_work'] = $this->constant_details_model->get_list(27);
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['t_type'] = $this->constant_details_model->get_list(152);
        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['account_det_type'] = json_encode($this->constant_details_model->get_list(154));

        $data['help'] = $this->help;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
    }

    function purchases_index()
    {
        $data['title'] = 'فهرس الموردون';
        $data['help'] = $this->help;
        $data['page'] = 1;
        $data['content'] = 'purchases_customers_home';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function public_index($text = null, $type = null, $id = '', $name = '', $page = 1)
    {
        $data['text'] = $this->input->get_post('text') ? $this->input->get_post('text') : $text;
        $data['id'] = $this->input->get_post('id') ? $this->input->get_post('id') : $id;
        $data['name'] = $this->input->get_post('name') ? $this->input->get_post('name') : $name;
        $data['type'] = $this->input->get_post('type') ? $this->input->get_post('type') : $type;
        $data['page'] = $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        $data['content'] = 'customers_index';
        $this->_look_ups($data);
        $this->load->view('template/view', $data);
    }

    function public_get_customers($prm = array())
    {
        if (!$prm)
            $prm = array('text' => $this->input->get_post('text'),
                'id' => isset($this->p_id) ? $this->p_id : null,
                'name' => isset($this->p_name) ? add_percent_sign($this->p_name) : null,
                'type' => isset($this->p_type) ? $this->p_type : null,
                'account_id' => isset($this->p_account_id) ? $this->p_account_id : null,
                'page' => isset($this->p_page) ? $this->p_page : null,
            );

        $this->load->library('pagination');

        $page = $prm['page'] ? $prm['page'] : 1;

        $config['base_url'] = base_url("payment/customers/public_index/?text={$prm['text']}&type={$prm['type']}&id={$prm['id']}&name={$prm['name']}");

        $prm['id'] = $prm['id'] != -1 ? $prm['id'] : null;
        $prm['name'] = $prm['name'] != -1 ? $prm['name'] : null;

        $count_rs = $this->{$this->MODEL_NAME}->get_count($prm['id'], $prm['name'], $prm['type'], $prm['account_id']);


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
        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($prm['id'], $prm['name'], $prm['type'], $prm['account_id'], $offset, $row);


        $this->load->view('customers_page', $data);
    }

    function public_get_customers_json()
    {

        if (isset($this->p_id)) {
            $rs = $this->{$this->MODEL_NAME}->get_list($this->p_id, null, null, 0, 1);
            $this->return_json($rs);
        }
    }

    function public_get_customer_balance(){
        $this->load->model('Root/New_rmodel');
        $params =array(
            array('name'=>':CUSTOMER_ID','value'=>$this->customer_id ,'type'=>'','length'=>-1),
            array(), array()
        );
        $result = $this->New_rmodel->general_get('PAYMENT_PKG', 'GET_CUSTOMER_BALANCE',$params);
        echo $result[0]['CUSTOMER_BALANCE'];
    }

    function get_page($page = 1, $c_type = 1)
    { // old $prm new $c_type

        $prm = array(
            'id' => isset($this->p_id) ? $this->p_id : null,
            'name' => isset($this->p_name) ? add_percent_sign($this->p_name) : null,
            'type' => isset($this->p_type) ? $this->p_type : $c_type,
            'account_id' => isset($this->p_account_id) ? $this->p_account_id : null,
            'page' => isset($this->p_page) ? $this->p_page : null,
        );


        $this->load->library('pagination');

        $count_rs = $this->{$this->MODEL_NAME}->get_count($prm['id'], $prm['name'], $prm['type'], $prm['account_id']);

        $config['base_url'] = base_url('payment/customers/index');
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
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($prm['id'], $prm['name'], $prm['type'], $prm['account_id'], $offset, $row);

        $this->load->view('customers_page', $data);
    }

    ///////////////////////////////////////////////////////////////////////////

    function get_page_purchases($page = 1)
    {

        $this->load->library('pagination');
        $where_sql = " where 1=1 and SOURCE=1 ";
        $where_sql .= isset($this->p_customer_name) && $this->p_customer_name != null ? " AND  M.CUSTOMER_NAME LIKE '%{$this->p_customer_name}%' " : "";
        $where_sql .= isset($this->p_customer_id) && $this->p_customer_id != null ? " AND  M.CUSTOMER_ID ='{$this->p_customer_id}'  " : "";
        $config['base_url'] = base_url($this->PAGE_URL_PURCHASES);
        $count_rs = $this->get_table_count("CUSTOMERS_TB " . $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count((array)$count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $this->load->view('purchases_customers_page', $data);


    }

    /// //////////////////////////////////////////////////////////////////////

    function public_get_details($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['elect_details'] = $this->customer_elect_details_model->get_list($id);
        $this->load->view('customer_elect_details_page', $data);
    }

//////////////////////////////////////////////////////////
    function public_get_activites($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['activities_details'] = $this->customer_activities_model->get_list($id);
        $data['activities_index'] = $this->activity_model->get_all();
        $this->load->view('customer_activites_details_page', $data);
    }

/// /////////////////////////////////////////////////////
    function public_get_details_2($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['accounts_details'] = $this->customers_accounts_det_model->get_list($id);
        $this->load->view('customers_accounts_details_page', $data);
    }

    function edit($is_purchases, $page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(true, $is_purchases);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData_Edit());
            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            } else {

                for ($i = 0; $i < count($this->customer_accounts_id); $i++) {
                    if ($this->customer_accounts_id[$i] != '' and $this->a_ser[$i] == 0) { // create
                        $detail_seq = $this->customers_accounts_det_model->create($this->_postedData_accounts_details(null, $this->customer_accounts_id[$i], $this->account_det_type[$i], 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->customer_accounts_id[$i] != '' and $this->a_ser[$i] != 0) { // edit
                        $detail_seq = $this->customers_accounts_det_model->edit($this->_postedData_accounts_details($this->a_ser[$i], $this->customer_accounts_id[$i], $this->account_det_type[$i], 'edit'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->customer_accounts_id[$i] == '' and $this->a_ser[$i] != 0) { // delete
                        $detail_seq = $this->customers_accounts_det_model->delete($this->a_ser[$i]);
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    }
                }

                for ($i = 0; $i < count($this->elect_no); $i++) {
                    if ($this->elect_no[$i] != '' and $this->ser[$i] == 0) { // create
                        $detail_seq = $this->customer_elect_details_model->create($this->_postedData_elect_details(null, $this->elect_no[$i], $this->notes[$i], 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->elect_no[$i] != '' and $this->ser[$i] != 0) { // edit
                        $detail_seq = $this->customer_elect_details_model->edit($this->_postedData_elect_details($this->ser[$i], $this->elect_no[$i], $this->notes[$i], 'edit'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->elect_no[$i] == '' and $this->ser[$i] != 0) { // delete
                        $detail_seq = $this->customer_elect_details_model->delete($this->ser[$i]);
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    }
                }
                for ($i = 0; $i < count($this->activity_no); $i++) {
                    if ($this->activity_no[$i] != '' and $this->act_ser[$i] == 0) { // create
                        $detail_seq = $this->customer_activities_model->create($this->_postedData_activite_details(null, $this->activity_no[$i], 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->activity_no[$i] != '' and $this->act_ser[$i] != 0) { // edit
                        $detail_seq = $this->customer_activities_model->edit($this->_postedData_activite_details($this->act_ser[$i], $this->activity_no[$i], 'edit'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }

    }

    function _postedData_activite_details($ser = null, $act_no, $typ = null)
    {
        $result = array(
            array('name' => 'SER_SEQ', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->customer_id, 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITY_NO', 'value' => $act_no, 'type' => '', 'length' => -1)
        );
        if ($typ == 'create')
            unset($result[0]);
        elseif ($typ == 'edit')
            unset($result[1]);

        return $result;
    }

    function _post_validation($isEdit = false, $is_purchases)
    {

        if (strlen($this->customer_id) == 11) {
            $id_is_char = 1;
            $id_char = substr($this->customer_id, 0, 2); // KM
            $id_num = substr($this->customer_id, 2); // 987654321
        } elseif (strlen($this->customer_id) == 10) {
            $id_is_char = 1;
            $id_char = substr($this->customer_id, 0, 1); // K
            $id_num = substr($this->customer_id, 1); // 987654321
        } else {
            $id_is_char = 0;
            $id_num = 0;
        }

        $types = array(5, 9); // انواع المستفيدين الذين يمكن ادخال رقم مسلسل بدل رقم الهوية

        if (($this->customer_seq == '' and $isEdit) or $this->customer_id == '' or $this->customer_name == '' or $this->customer_type == '' or ($this->account_id == '' and $this->account_type == 1 and $this->customer_type != 8)) {
            $this->print_error('يجب ادخال جميع البيانات');
        } else if ($this->customer_type == 8 and ($this->account_id != 0 or $this->account_type == 2)) {
            $this->print_error('لا يوجد حساب للمتعاونين مع الشركة');
        } else if (((!$id_is_char and !check_identity($this->customer_id) and !in_array($this->customer_type, $types)) or ($id_is_char and !in_array($this->customer_type, $types) and (!preg_match('/^[A-Z]{1,2}$/', $id_char) or !check_identity($id_num))))
            or (in_array($this->customer_type, $types) and (!preg_match('/^[0-9]{2,5}$/', $this->customer_id) and !check_identity($this->customer_id) and !check_identity($id_num)))) {
            $this->print_error('ادخل رقم مستفيد صحيح');
        } else if (($this->customer_type == 1 or $this->customer_type == 6) and $this->company_delegate_id != '' and !check_identity($this->company_delegate_id)) {
            $this->print_error('رقم هوية المفوض بالتواصل غير صحيح');
        } else if ($this->customer_bank_account != '' and !preg_match('/^[0-9]{5,15}$/', $this->customer_bank_account)) {
            $this->print_error('رقم الحساب البنكي الحالي غير صحيح');
        } else if ($this->customer_bank_old_account != '' and !preg_match('/^[0-9]{5,15}$/', $this->customer_bank_old_account)) {
            $this->print_error('رقم الحساب البنكي السابق غير صحيح');
        } else if ($this->phone == '' and $this->mobil == '' and $this->customer_type != 5) {
            $this->print_error('يجب ادخال الهاتف او الجوال');
        } else if ($this->phone != '' and !preg_match('/^[0-9]{5,15}$/', $this->phone)) {
            $this->print_error('ادخل رقم هاتف صحيح');
        } else if ($this->mobil != '' and !preg_match('/^[0-9]{5,15}$/', $this->mobil)) {
            $this->print_error('ادخل رقم جوال صحيح');
        } else if ($this->fax != '' and !preg_match('/^[0-9]{5,15}$/', $this->fax)) {
            $this->print_error('ادخل رقم فاكس صحيح');
        } else if ($this->email != '' and !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->print_error('ادخل بريد الكتروني صحيح');
        } else if ($this->web != '' and !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $this->web)) {
            $this->print_error('ادخل موقع الكتروني صحيح');
        } else if (!$isEdit and count($this->{$this->MODEL_NAME}->get($this->customer_id))) {
            $this->print_error('رقم المستفيد مدخل مسبقا');
            //}else if(!($this->elect_no) or count(array_filter($this->elect_no)) <= 0 ){
            //    $this->print_error('يجب ادخال اشتراك كهرباء واحد على الاقل ');
        } else if (1) {
            foreach ($this->elect_no as $elect_no) {
                if ($elect_no != '' and !preg_match('/^[0-9]{5,15}$/', $elect_no)) {
                    $this->print_error('ادخل رقم اشتراك صحيح');
                }
            }
        }

        if (!$is_purchases) {
            if ($this->account_type == 2 and (!($this->customer_accounts_id) or count(array_filter($this->customer_accounts_id)) <= 0)) {
                $this->print_error('يجب ادخال رقم حساب واحد على الاقل ');
            }
        }
        /*if ($is_purchases) {
            if (count(array_filter($this->activity_no)) <= 0) {
                $this->print_error_del('يجب ادخال نشاط واحد على الاقل!!');
            }
        }*/

        if ($this->customer_type != 1) { // نوع المستفيد ليس مورد
            $this->company_owner = null;
            $this->company_work_scope = null;
            $this->company_level_scope = null;
            $this->company_field_work = null;
            $this->company_subscription_date = null;
        }

        if ($this->customer_type != 1 and $this->customer_type != 6) {
            $this->company_delegate_id = null;
            $this->customer_delegate_name = null;
        }

        if ($this->account_type == 2) {
            $this->account_id = null;
        }
    }

    function get($id, $action = 'index')
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->date_format($result, 'COMPANY_SUBSCRIPTION_DATE');
        $data['customer_data'] = $result;
        $data['can_edit'] = count($result) > 0 ? ($action == 'edit') ? true : false : false; // ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')
        $data['action'] = $action;
        if ($result[0]['CUSTOMER_TYPE'] == 7) {
            $data['content'] = 'customers_show_sun';
            $data['title'] = 'بيانات مستفيد طاقة شمسية';
        } else {
            $data['content'] = 'customers_show';
            $data['title'] = 'بيانات المستفيد';
        }
        $data['is_purchases'] = 0;
        $data['purchase_order_class_types'] = $this->constant_details_model->get_list(69);
        $data['x'] = '';
        foreach ($data['purchase_order_class_types'] as $row) :
            $data['x'] = $data['x'] . "<option value='" . $row['CON_NO'] . "'>" . $row['CON_NAME'] . "</option>";
        endforeach;
        if ($result[0]['FINANCIAL'] == 2)
            $data['get_all'] = $this->{$this->MODEL_NAME}->get_purchases($result[0]['CUSTOMER_ID']);
        else
            die;
        $data['isCreate'] = true;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function get_purchases($id, $action = 'index')
    {

        $result = $this->{$this->MODEL_NAME}->get($id);
        if ($result[0]['SOURCE'] != 1) {
            die;
        } else {
            $this->date_format($result, 'COMPANY_SUBSCRIPTION_DATE');
            $data['customer_data'] = $result;
            $data['can_edit'] = count($result) > 0 ? ($action == 'edit') ? true : false : false; // ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')
            $data['action'] = $action;

            $data['content'] = 'customers_show';
            $data['title'] = 'بيانات المورد';
            $data['is_purchases'] = 1;
            $data['purchase_order_class_types'] = $this->constant_details_model->get_list(69);
            $data['x'] = '';
            foreach ($data['purchase_order_class_types'] as $row) :
                $data['x'] = $data['x'] . "<option value='" . $row['CON_NO'] . "'>" . $row['CON_NAME'] . "</option>";
            endforeach;
            $data['get_all'] = $this->{$this->MODEL_NAME}->get_purchases($result[0]['CUSTOMER_ID']);
            $data['isCreate'] = true;
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

    function _postedData_Edit()
    {
        $result = array(
            array('name' => 'CUSTOMER_SEQ', 'value' => $this->customer_seq, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_NAME', 'value' => $this->customer_name, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_TYPE', 'value' => $this->customer_type, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $this->account_type, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_ID', 'value' => $this->account_id, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_OWNER', 'value' => $this->company_owner, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_DELEGATE_ID', 'value' => $this->company_delegate_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_DELEGATE_NAME', 'value' => $this->customer_delegate_name, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_WORK_SCOPE', 'value' => $this->company_work_scope, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_LEVEL_SCOPE', 'value' => $this->company_level_scope, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_FIELD_WORK', 'value' => $this->company_field_work, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_SUBSCRIPTION_DATE', 'value' => $this->company_subscription_date, 'type' => '', 'length' => -1),
            array('name' => 'BANK', 'value' => $this->bank, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_BANK_ACCOUNT', 'value' => $this->customer_bank_account, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_BANK_OLD_ACCOUNT', 'value' => $this->customer_bank_old_account, 'type' => '', 'length' => -1),
            array('name' => 'PHONE', 'value' => $this->phone, 'type' => '', 'length' => -1),
            array('name' => 'MOBIL', 'value' => $this->mobil, 'type' => '', 'length' => -1),
            array('name' => 'FAX', 'value' => $this->fax, 'type' => '', 'length' => -1),
            array('name' => 'EMAIL', 'value' => $this->email, 'type' => '', 'length' => -1),
            array('name' => 'WEB', 'value' => $this->web, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->address, 'type' => '', 'length' => -1),
            array('name' => 'IBAN', 'value' => $this->iban, 'type' => '', 'length' => -1),
            array('name' => 'P_SER_NO', 'value' => $this->t_ser_no, 'type' => '', 'length' => -1),
            array('name' => 'P_DATE', 'value' => $this->t_date, 'type' => '', 'length' => -1),
            array('name' => 'P_TOTAL_VALUE', 'value' => $this->t_total_value, 'type' => '', 'length' => -1),
            array('name' => 'P_REST_VALUE', 'value' => $this->t_rest_value, 'type' => '', 'length' => -1),
            array('name' => 'QUOTES_ENTRANCE', 'value' => $this->p_quotes_entrance, 'type' => '', 'length' => -1),
            array('name' => 'QUOTES_ENTRANCE_HINTS', 'value' => $this->p_quotes_entrance_hints, 'type' => '', 'length' => -1)
        );
        return $result;
    }

    function create($c_type = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(false, $c_type);

            $this->customer_seq = $this->{$this->MODEL_NAME}->create($this->_postedData($c_type));
            if (intval($this->customer_seq) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->customer_seq);
            } else {
                for ($i = 0; $i < count($this->customer_accounts_id); $i++) {
                    if ($this->customer_accounts_id[$i] != '') {
                        $detail_seq = $this->customers_accounts_det_model->create($this->_postedData_accounts_details(null, $this->customer_accounts_id[$i], $this->account_det_type[$i], 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error_del($detail_seq);
                        }
                    }
                }

                for ($i = 0; $i < count($this->elect_no); $i++) {
                    if ($this->elect_no[$i] != '') {
                        $detail_seq = $this->customer_elect_details_model->create($this->_postedData_elect_details(null, $this->elect_no[$i], $this->notes[$i], 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error_del($detail_seq);
                        }
                    }
                }

                for ($i = 0; $i < count($this->activity_no); $i++) {
                    if ($this->activity_no[$i] != '' and $this->act_ser[$i] == 0) { // create
                        $detail_seq = $this->customer_activities_model->create($this->_postedData_activite_details(null, $this->activity_no[$i], 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }

        } else {
            if ($c_type == 'sun')
                $data['content'] = 'customers_show_sun';
            else
                $data['content'] = 'customers_show';
            if ($c_type == 'purchases') {
                $data['title'] = 'ادخال مورد جديد';
                $data['is_purchases'] = 1;
            } else {
                $data['title'] = 'ادخال مستفيد جديد';
                $data['is_purchases'] = 0;
            }
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $data['get_all'] = array();
            $data['x'] = '';
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

    function _postedData($c_type)
    {


        if ($c_type == 1) {

            $source = 1;
            $financial = 1;
        } else {

            $source = 2;
            $financial = 2;
        }

        $result = array(
            array('name' => 'CUSTOMER_ID', 'value' => $this->customer_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_NAME', 'value' => $this->customer_name, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_TYPE', 'value' => $this->customer_type, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $this->account_type, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_ID', 'value' => $this->account_id, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_OWNER', 'value' => $this->company_owner, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_DELEGATE_ID', 'value' => $this->company_delegate_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_DELEGATE_NAME', 'value' => $this->customer_delegate_name, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_WORK_SCOPE', 'value' => $this->company_work_scope, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_LEVEL_SCOPE', 'value' => $this->company_level_scope, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_FIELD_WORK', 'value' => $this->company_field_work, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_SUBSCRIPTION_DATE', 'value' => $this->company_subscription_date, 'type' => '', 'length' => -1),
            array('name' => 'BANK', 'value' => $this->bank, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_BANK_ACCOUNT', 'value' => $this->customer_bank_account, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_BANK_OLD_ACCOUNT', 'value' => $this->customer_bank_old_account, 'type' => '', 'length' => -1),
            array('name' => 'PHONE', 'value' => $this->phone, 'type' => '', 'length' => -1),
            array('name' => 'MOBIL', 'value' => $this->mobil, 'type' => '', 'length' => -1),
            array('name' => 'FAX', 'value' => $this->fax, 'type' => '', 'length' => -1),
            array('name' => 'EMAIL', 'value' => $this->email, 'type' => '', 'length' => -1),
            array('name' => 'WEB', 'value' => $this->web, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->address, 'type' => '', 'length' => -1),
            array('name' => 'IBAN', 'value' => $this->iban, 'type' => '', 'length' => -1),
            array('name' => 'P_SER_NO', 'value' => $this->t_ser_no, 'type' => '', 'length' => -1),
            array('name' => 'P_DATE', 'value' => $this->t_date, 'type' => '', 'length' => -1),
            array('name' => 'P_TOTAL_VALUE', 'value' => $this->t_total_value, 'type' => '', 'length' => -1),
            array('name' => 'P_REST_VALUE', 'value' => $this->t_rest_value, 'type' => '', 'length' => -1),
            array('name' => 'QUOTES_ENTRANCE', 'value' => $this->p_quotes_entrance, 'type' => '', 'length' => -1),
            array('name' => 'QUOTES_ENTRANCE_HINTS', 'value' => $this->p_quotes_entrance_hints, 'type' => '', 'length' => -1),
            array('name' => 'SOURCE', 'value' => $source, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL', 'value' => $financial, 'type' => '', 'length' => -1)
        );

        return $result;
    }

    function _postedData_accounts_details($ser = null, $accounts_id, $account_det_type, $typ = null)
    {
        $result = array(
            array('name' => 'SER_SEQ', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->customer_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNTS_ID', 'value' => $accounts_id, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $account_det_type, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);
        elseif ($typ == 'edit')
            unset($result[1]);

        return $result;
    }

    function print_error_del($msg = '')
    {
        $ret = $this->{$this->MODEL_NAME}->delete($this->customer_seq);
        if (intval($ret) > 0)
            $this->print_error('لم يتم حفظ المستفيد: ' . $msg);
        else
            $this->print_error('لم يتم حذف المستفيد: ' . $msg);
    }

    function _postedData_elect_details($ser = null, $elect_no, $notes = null, $typ = null)
    {
        $result = array(
            array('name' => 'SER_SEQ', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->customer_id, 'type' => '', 'length' => -1),
            array('name' => 'ELECT_NO', 'value' => $elect_no, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $notes, 'type' => '', 'length' => -1)
        );
        if ($typ == 'create')
            unset($result[0]);
        elseif ($typ == 'edit')
            unset($result[1]);

        return $result;
    }

    function add_pur()
    {
        $id = $this->input->post('id');
        $purchase_type = $this->input->post('purchase_type');
        $note = $this->input->post('note');

        $res = $this->{$this->MODEL_NAME}->add_purchase_type($id, $purchase_type, $note);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $res);
        }
        echo 1;

    }


    function create_pur()
    {
        $result = $this->{$this->MODEL_NAME}->create_pur($this->_postedDataPurchase('create'));
        //$this->Is_success($result);
        if (intval($result) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $result);
        }
        echo $result;// modules::run($this->PAGE_URL);
    }

    function _postedDataPurchase($typ = null)
    {

        if ($typ == 'create') {
            $result = array(

                array('name' => 'CUSTOMER_ID', 'value' => $this->input->post('customer_id'), 'type' => '', 'length' => -1),
                array('name' => 'PURCHASE_TYPE', 'value' => $this->input->post('purchase_type'), 'type' => '', 'length' => -1),
                array('name' => 'NOTE', 'value' => $this->input->post('note'), 'type' => '', 'length' => -1)
            );


        } else {
            $result = array(
                array('name' => 'SER', 'value' => $this->input->post('ser'), 'type' => '', 'length' => -1),
                array('name' => 'CUSTOMER_ID', 'value' => $this->input->post('customer_id'), 'type' => '', 'length' => -1),
                array('name' => 'PURCHASE_TYPE', 'value' => $this->input->post('purchase_type'), 'type' => '', 'length' => -1),
                array('name' => 'NOTE', 'value' => $this->input->post('note'), 'type' => '', 'length' => -1)
            );
        }


        return $result;
    }

    function edit_pur()
    {
        $result = $this->{$this->MODEL_NAME}->edit_pur($this->_postedDataPurchase());
        $this->Is_success($result);
        echo '1';// modules::run($this->PAGE_URL);
    }

    function delete_pur()
    {
        $ser = $this->input->post('ser');
        $result = $this->{$this->MODEL_NAME}->delete_pur($ser);
        // echo  $result;
        $this->Is_success($result);
        echo '1';// modules::run($this->PAGE_URL);
    }

}
