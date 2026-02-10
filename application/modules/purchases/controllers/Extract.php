<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Extract extends MY_Controller
{

    var $MODEL_NAME = 'Extract_model';
    var $MODEL_ORDERS_NAME = 'orders_model';
    var $PAGE_URL = 'purchases/Extract/get_page';

    function __construct()
    {

        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->MODEL_ORDERS_NAME);

    }

    function index()
    {


        $data['title'] = 'إدارة المستخلصات';
        $data['help'] = $this->help;
        $data['content'] = 'extract_index';
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
        $where_sql = " where 1=1 ";
        $where_sql .= isset($this->p_order_id) && $this->p_order_id != null ? " AND  M.ORDER_ID ={$this->p_order_id}  " : "";
        $where_sql .= isset($this->p_order_id_text) && $this->p_order_id_text != null ? " AND  M.ORDER_ID_TEXT LIKE '%{$this->p_order_id_text}%' " : "";
        $where_sql .= isset($this->p_purchase_id) && $this->p_purchase_id != null ? " AND  M.PURCHASE_ID ={$this->p_purchase_id}  " : "";
        $where_sql .= isset($this->p_purchase_text) && $this->p_purchase_text != null ? " AND  M.PURCHASE_TEXT LIKE '%{$this->p_purchase_text}%' " : "";
        $where_sql .= isset($this->p_customer_resource_id) && $this->p_customer_resource_id != null ? " AND  M.CUSTOMER_ID LIKE '%{$this->p_customer_resource_id}%'" : "";


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count("EXTRACT_TB", $where_sql);
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
        $this->load->view('extract_page', $data);


    }

//////////////////////////////////////////////////////////////////////////////

    function _look_ups(&$data)
    {
        $this->load->model('settings/constant_details_model');
        $data['help'] = $this->help;
        $data['adopt_cons'] = $this->constant_details_model->get_list(454);//402
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');


    }

///////////////////////////////////////////////////////////////

    function create($id = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }
            echo intval($this->ser);

        } else {
            if ($id == null) {
                $data['content'] = 'stores/Stores_class_input_adopt_index';
                $data['title'] = 'سندات الإدخال المخزنية المعتمدة الخاصة بأوامر التوريد';
                $data['isCreate'] = true;
                $data['action'] = 'index';
                $data['page'] = 1;
                add_css('select2_metro_rtl.css');
                add_js('select2.min.js');
                add_css('combotree.css');
                $this->_look_ups($data);
                $this->load->view('template/template', $data);

            } else {

                $data['content'] = 'extract_show';
                $data['title'] = ' اعداد مستخلص ';
                $data['isCreate'] = true;
                $data['action'] = 'index';
                $data['next_adopt_email'] = "";
                $data['prev_adopt_email'] = "";
                $res = $this->{$this->MODEL_ORDERS_NAME}->get_class_order($id);
                $result = $this->{$this->MODEL_ORDERS_NAME}->get($res[0]['ORDER_ID_SER']);
                $data['next_adopt_email'] = "";
                $data['prev_adopt_email'] = "";
                if (!(count($result) == 1))
                    die();
                $data['result'] = $result;
                add_css('select2_metro_rtl.css');
                add_js('select2.min.js');
                $this->_look_ups($data);
                $this->load->view('template/template', $data);
            }
        }
    }

    ////////////////////
    function merge()
    {
        $id = $this->input->post('id');
        $first_val= $this->input->post('first_val');
        for ($x = 1; $x < count($first_val); $x++) {
           if($first_val[0] != $first_val[$x] )
           {
               $this->print_error(' لا يمكن دمج المستخلص !!' );
           }
        }

        $result = $this->{$this->MODEL_NAME}->merge_extracts($id);
        echo intval($result);
    }
    ///////////////////

    function _postedData($typ = null)
    {


        $result = array(
            array('name' => 'SER', 'value' => $this->p_extract_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_ID', 'value' => $this->p_class_input_id, 'type' => '', 'length' => -1),
            array('name' => 'DISCOUNT', 'value' => $this->p_discount, 'type' => '', 'length' => -1),
            array('name' => 'NOTE', 'value' => $this->p_extract_note, 'type' => '', 'length' => -1),
        );

        if ($typ == 'create')
            unset($result[0]);
        else
            unset($result[1]);

        return $result;
    }

/////////////////////////////////

    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        $data['result'] = $result;
        $data['content'] = 'extract_show';
        $data['title'] = ' اعداد مستخلص ';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $next_adopt = array(
            1 => 10,
            10 => 20,
            20 => 30
        );

        $prev_adopt = array(
            1 => 0,
            10 => 1,
            20 => 10,
            30 => 20,
            40 => 30
        );

        $data['next_adopt_email'] = $this->get_emails_by_code('12.' . (@$next_adopt[$result[0]['ADOPT']])); // old 202003 - ($result[0]['ADOPT']+20)

        $data['prev_adopt_email'] = $this->get_emails_by_code('12.' . (@$prev_adopt[$result[0]['ADOPT']])); // old 202003 - ($result[0]['ADOPT']+20)

        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

/// ///////////////////////////////

    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser = $this->{$this->MODEL_NAME}->edit($this->_postedData());

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }

            echo intval($this->ser);
        }
    }

    function public_get_order_extract_details($ser,$id, $class_input_id, $curr_name, $discount, $prev_paid)
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
            $data['curr_name'] = $curr_name;
            $data['discount'] = $discount;
            $data['prev_paid'] = $prev_paid;
            $data['rec_details'] = $this->orders_detail_model->adopt_stores_orders_detail_get($ser,$id, $class_input_id);
            echo($this->load->view('extract_class_details', $data));

        }
    }

    function public_get_prev_extract_details($ser, $order_id, $customer_id)
    {

        $data['rec_details'] = $this->{$this->MODEL_NAME}->extract_det_tb_get($ser, $order_id, $customer_id);

        echo($this->load->view('extract_prev_details', $data));


    }

    function adopt_0()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(-1);
        } else
            echo "لم يتم الاعتماد";
    }

    //الغاء المستخلص

    private function adopt($case, $note = '')
    {
        $res = $this->{$this->MODEL_NAME}->adopt($this->p_ser, $case, '');

        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        return 1;
    }

//اعتماد المستخلص طرف المعد

    function adopt_10()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(10);
        } else
            echo "لم يتم الاعتماد";
    }

    //اعتماد المستخلص طرف رئيس قسم الممارسات و العطاءات
    function adopt_20()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(20);
        } else
            echo "لم يتم الاعتماد";
    }

//اعتماد المستخلص طرف مدير المشتريات
    function adopt_30()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(30);
        } else
            echo "لم يتم الاعتماد";
    }

    //ارجاع المستخلص للمعد
    function adopt__10()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(-10);
        } else
            echo "لم يتم الاعتماد";
    }

    /////////////////////////////////////////////////////////////
    //ارجاع المستخلص لرئيس قسم الممارسات و العطاءات
    function adopt__20()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(-20);
        } else
            echo "لم يتم الاعتماد";
    }
    /////////////////////////////////////////////////////////////
    //ارجاع المستخلص لمدير المشتريات
    function adopt__30()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(-30);
        } else
            echo "لم يتم الاعتماد";
    }

}

?>
