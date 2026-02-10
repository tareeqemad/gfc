<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/01/15
 * Time: 07:31 م
 */
class class_amount extends MY_Controller
{

    var $MODEL_NAME = 'class_amount_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->store_id = ($this->input->post('store_id'));
        $this->store_id2 = intval($this->input->post('store_id2'));
        $this->class_id = $this->input->post('class_id');
        $this->class_id2 = $this->input->post('class_id2');
        $this->class_type = $this->input->post('class_type');
        $this->class_acount_type = $this->input->post('class_acount_type');
        $this->action = $this->input->post('action');
        $this->reserve = $this->input->post('reserve');
        $this->source = $this->input->post('source');
        $this->pk = $this->input->post('pk');
        $this->from_date = $this->input->post('from_date');
        $this->to_date = $this->input->post('to_date');
        $this->account_source = $this->input->post('account_source');
        $this->account_type = $this->input->post('account_type');
        $this->account_id = $this->input->post('account_id');
        $this->class_min = $this->input->post('class_min');
        $this->order = $this->input->post('order');
        $this->buy_price_op= $this->input->post('buy_price_op');

        if( HaveAccess(base_url("stores/class_amount/show_european")) )
            $this->show_european= 1;
        else
            $this->show_european= 0;
    }

    function index()
    {
        $this->load->model('stores_model');
        $data['stores'] = $this->stores_model->get_all();
        $data['stores']= $this->remove_european_stores($data['stores']);
        $data['title'] = 'ارصدة الاصناف';
        $data['content'] = 'class_amount_index';
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->view('template/template', $data);
    }

    function get_page()
    {
        $this->store_id = $this->store_id == 0 ? null : $this->store_id;
        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($this->store_id, $this->class_id, $this->class_id2, $this->class_min, $this->reserve);
        $this->load->view('class_amount_page', $data);
    }


    function actions_index($page = 1, $store_id = -1, $store_id2 = -1, $class_id = -1, $class_type = -1, $action = -1, $reserve = -1, $source = -1, $pk = -1, $from_date = -1, $to_date = -1, $account_source = -1, $account_type = -1, $account_id = -1, $order = 1)
    {
        $data['title'] = 'حركات الاصناف';
        $data['content'] = 'class_amount_actions_index';

        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        $data['stores_all'] = $this->stores_model->get_all();
        $data['source_all'] = $this->constant_details_model->get_list(45);
        $data['class_type_all'] = $this->constant_details_model->get_list(41);
        $data['account_type_all'] = $this->constant_details_model->get_list(15);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['page'] = $page;
        $data['store_id'] = $store_id;
        $data['store_id2'] = $store_id2;
        $data['class_id'] = $class_id;
        $data['class_type'] = $class_type;
        $data['action'] = $action;
        $data['reserve'] = $reserve;
        $data['source'] = $source;
        $data['pk'] = $pk;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['account_source'] = $account_source;
        $data['account_type'] = $account_type;
        $data['account_id'] = $account_id;
        $data['order'] = $order;

        $data['help'] = $this->help;
        $this->load->view('template/template', $data);
    }

    function get_actions_page($page = 1, $store_id = -1, $store_id2 = -1, $class_id = -1, $class_type = -1, $action = -1, $reserve = -1, $source = -1, $pk = -1, $from_date = -1, $to_date = -1, $account_source = -1, $account_type = -1, $account_id = -1, $order = 1)
    {

        function array_to_char($stores)
        {
            if (!is_array($stores))
                return null;
            else {
                $stores = implode(",", $stores);
                return $stores;
            }
        }

        $this->load->library('pagination');

        $store_id = $this->check_vars($store_id, 'store_id');
        $store_id2 = $this->check_vars($store_id2, 'store_id2');
        $class_id = $this->check_vars($class_id, 'class_id');
        $class_type = $this->check_vars($class_type, 'class_type');
        $action = $this->check_vars($action, 'action');
        $reserve = $this->check_vars($reserve, 'reserve');
        $source = $this->check_vars($source, 'source');
        $pk = $this->check_vars($pk, 'pk');
        $from_date = $this->check_vars($from_date, 'from_date');
        $to_date = $this->check_vars($to_date, 'to_date');
        $account_source = $this->check_vars($account_source, 'account_source');
        $account_type = $this->check_vars($account_type, 'account_type');
        $account_id = $this->check_vars($account_id, 'account_id');
        $order = $this->check_vars($order, 'order');

        $where_sql = ' ';

        $where_sql .= ($store_id != null) ? " and c.store_id in (" . array_to_char($store_id) . ") " : '';
        //$where_sql.= ($store_id!= null)? " and c.store_id <= {$store_id2} " : '';
        $where_sql .= ($class_id != null) ? " and c.class_id like '{$class_id}%' " : '';
        $where_sql .= ($class_type != null) ? " and c.class_type= '{$class_type}' " : '';
        $where_sql .= ($action != null) ? " and c.action= {$action} " : '';
        $where_sql .= ($reserve == 1) ? " and c.source not in (7,8,9) " : '';
        $where_sql .= ($reserve == 2) ? " and c.source != 7 " : '';
        $where_sql .= ($source != null) ? " and c.source= {$source} " : '';
        $where_sql .= ($pk != null) ? " and c.pk= {$pk} " : '';
        $where_sql2 = $where_sql;
        $where_sql .= ($from_date != null) ? " and TRUNC(c.adopt_date,'dd') >= '{$from_date}' " : '';
        $where_sql2 .= ($from_date != null) ? " and TRUNC(c.adopt_date,'dd') < '{$from_date}' " : '';
        $where_sql .= ($to_date != null) ? " and TRUNC(c.adopt_date,'dd') <= '{$to_date}' " : '';

        if ($account_source == 1 and $account_type != null and $account_id != null) {
            //$where_sql.= ' and qf_pkg.get_qeed( DECODE(c.source,5,17, 6,18, 3,19, 2,21, 0) , c.PK) in ';
            if (!$this->check_db_for_stores() and False) { // db_2015 - disabled in 10/1/2017
                $where_sql .= ' and qf_pkg.CLASS_AMOUNT_ACCOUNT_QEED_CNT(c.PK,c.source,' . $account_type . ",'" . $account_id . "%') >= 1 ";
            } else {// db_2016
                $where_sql .= ' and qf_pkg.QUEED_NO_FOR_CLASS_AMOUNT( c.source, c.PK) in ';
                $where_sql .= ' ( select d.financial_chains_id ';
                $where_sql .= ' from gfc.financial_chains_detail_tb d ';
                $where_sql .= ' where d.account_type= ' . $account_type . " and d.account_id like '" . $account_id . "%') ";
            }
        } elseif ($account_source == 2) {
            $where_sql .= ($account_type != null) ? " and c.account_type= '{$account_type}' " : '';
            $where_sql .= ($account_id != null) ? " and c.account_id like '{$account_id}%' " : '';
        }

        if ($order == 4)
            $order_sql = ' order by c.PRICE_DATE asc , c.PK asc ';
        elseif ($order == 3)
            $order_sql = ' order by to_number(c.CLASS_ID) asc ';
        elseif ($order == 2)
            $order_sql = ' order by c.ADOPT_DATE asc ';
        else
            $order_sql = ' order by c.ADOPT_DATE desc ';

        if ($class_id != null and $order == 2 and $action == null and $source == null and $pk == null) {
            $data['show_balance'] = 1;
            if ($from_date != null) {
                $balance = $this->{$this->MODEL_NAME}->get_balance($where_sql2);
                $data['balance'] = $balance[0]['BALANCE'];
            } else
                $data['balance'] = 0;
        } else
            $data['show_balance'] = 0;

        $config['base_url'] = base_url('stores/class_amount/actions_index');
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' class_amount c where 1=1 ' . $where_sql);
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

        if (isset($this->p_print_rep) and $this->p_print_rep == 1) {
            $this->session->set_userdata('class_amount_actions_where', $where_sql);
            $this->session->set_userdata('class_amount_actions_order', $order_sql);
        } else {
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_actions($where_sql, $order_sql, $offset, $row);
            $data['offset'] = $offset + 1;
            $data['page'] = $page;

            $this->load->view('class_amount_actions_page', $data);
        }

    }

    function public_class_movements($class_id = -1, $action = 1, $source = 2)
    {


        $where_sql = ' ';


        $where_sql .= ($class_id != null) ? " and c.class_id like '{$class_id}%' " : '';
        $where_sql .= ($action != null) ? " and c.action= {$action} " : '';
        $where_sql .= ($source != null) ? " and c.source= {$source} " : '';


        $config['base_url'] = base_url('stores/class_amount/actions_index');
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' class_amount c where 1=1 ' . $where_sql);

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = 1000;
        $config['num_links'] = 20;
        $config['cur_page'] = 1;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data['show_balance'] = 1;
        $data['balance'] = $this->{$this->MODEL_NAME}->get_balance($where_sql)[0]['BALANCE'];

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_actions($where_sql, "order by c.ADOPT_DATE asc", 0, 1000);
        $data['offset'] = 1001;
        $data['page'] = 1;
        $data['show_page'] = 1;

        $data['content'] = 'class_amount_actions_page';
        $this->load->view('template/view', $data);

    }


    function stores_actions_index($page = 1, $store_id = -1, $action = -1, $source = -1, $pk = -1, $from_date = -1, $to_date = -1)
    {
        $data['title'] = 'الحركات المخزنية';
        $data['content'] = 'stores_actions_index';

        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        $data['stores_all'] = $this->stores_model->get_all();
        $data['source_all'] = $this->constant_details_model->get_list(45);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['page'] = $page;
        $data['store_id'] = $store_id;
        $data['action'] = $action;
        $data['source'] = $source;
        $data['pk'] = $pk;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $data['help'] = $this->help;
        $this->load->view('template/template', $data);
    }

    function get_stores_actions_page($page = 1, $store_id = -1, $action = -1, $source = -1, $pk = -1, $from_date = -1, $to_date = -1)
    {
        $this->load->library('pagination');

        $store_id = $this->check_vars($store_id, 'store_id');
        $action = $this->check_vars($action, 'action');
        $source = $this->check_vars($source, 'source');
        $pk = $this->check_vars($pk, 'pk');
        $from_date = $this->check_vars($from_date, 'from_date');
        $to_date = $this->check_vars($to_date, 'to_date');

        $where_sql = ' where 1=1 ';

        $where_sql .= ($store_id != null) ? " and store_id= {$store_id} " : '';
        $where_sql .= ($action != null) ? " and action= {$action} " : '';
        $where_sql .= ($source != null) ? " and source= {$source} " : '';
        $where_sql .= ($pk != null) ? " and pk= {$pk} " : '';
        $where_sql .= ($from_date != null) ? " and TRUNC(adopt_date,'dd') >= '{$from_date}' " : '';
        $where_sql .= ($to_date != null) ? " and TRUNC(adopt_date,'dd') <= '{$to_date}' " : '';

        $config['base_url'] = base_url('stores/class_amount/stores_actions_index');
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' stores_actions_vw ' . $where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->actions_list($where_sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('stores_actions_page', $data);
    }


    function stores_val_index()
    {


        $this->load->model('stores_model');
        $this->load->model('settings/constant_details_model');
        $data['stores'] = $this->stores_model->get_all();
        $data['class_acount_type_all'] = $this->constant_details_model->get_list(36);
        $data['class_type_all'] = $this->constant_details_model->get_list(41);
        $data['compares'] = $this->constant_details_model->get_list(323);
        $data['title'] = 'تقدير المخزون ';
        $data['content'] = 'stores_val_index';
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        $this->load->view('template/template', $data);
    }

    function stores_val_page()
    {    $this->load->model('classes_prices/classes_prices_model');
        $where_sql = ' ';
        $where_sql .= ($this->store_id != 0) ? " and c.store_id = {$this->store_id} " : '';
        $where_sql .= ($this->to_date != null) ? " and TRUNC(C.ADOPT_DATE,'DD') <= '{$this->to_date}' " : '';
        $where_sql .= ($this->class_acount_type != 0) ? " and s.class_acount_type = {$this->class_acount_type} " : '';
        $where_sql .= ($this->class_type != 0) ? " and c.class_type = {$this->class_type} " : '';
        $where_sql .= ($this->reserve == 1) ? " and c.source not in (7,8,9) " : '';
        $where_sql .= ($this->reserve == 2) ? " and c.source != 7 " : '';


        $where_sql.= ($this->buy_price_op != null)? $this->classes_prices_model->get_compare_where("buy_price",$this->buy_price_op,"CLASS_PURCHASING") : '';
       // echo $where_sql ;
        $data['get_list'] = $this->{$this->MODEL_NAME}->stores_val_list($where_sql);
        $this->load->view('stores_val_page', $data);
    }


    function reports_form()
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        $data['stores_all'] = $this->stores_model->get_all();
        $data['stores_all'] = $this->remove_european_stores($data['stores_all']);
        $data['class_acount_type_all'] = $this->constant_details_model->get_list(36);
        $data['class_type_all'] = $this->constant_details_model->get_list(41);
        $data['account_type_all'] = $this->constant_details_model->get_list(15);
        $data['content'] = 'class_amount_reports';
        $this->load->view('template/view', $data);
    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = $this->{$c_var} ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function remove_european_stores($stores_arr){
        if($this->show_european==1){
            return $stores_arr;
        }else{
            foreach($stores_arr as $subKey => $subArray){
                if( in_array($subArray["STORE_NO"], array(800,809,810,814) ) ){ // remove this stores
                    unset($stores_arr[$subKey]);
                }
            }
            return $stores_arr;
        }
    }

}