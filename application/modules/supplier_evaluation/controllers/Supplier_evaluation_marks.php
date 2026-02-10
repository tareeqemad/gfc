<?php

class supplier_evaluation_marks extends MY_Controller
{

    var $MODEL_NAME = 'supplier_evaluation_marks_model';
	var $PAGE_URL='';

    function __construct()
    {

        parent::__construct();

        $this->load->model($this->MODEL_NAME);
        $this->load->model('purchases/orders_model');
        $this->load->model('purchases/extract_model');
        $this->load->model('payment/customers_model');
        $this->load->model('purchases/customers_activity_model');


    }

    function index()
    {
        $data['title'] = 'تقييم مورد سنوي';
        $data['content'] = 'supplier_evaluation_marks_index';
        $data['help'] = $this->help;
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

//////////////////////////////////////////////////////////////////////////////

    function _look_ups(&$data)
    {

        $this->load->model('settings/constant_details_model');

        // $data['customer_ids'] = $this->customers_model->get_all_by_type(1);
        $data['customer_ids'] = $this->customers_activity_model->get_customer_type(1);
        $data['report_type'] = $this->constant_details_model->get_list(485);
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

    }

    function get_page($page = 1)
    {

        $this->load->library('pagination');
        $where_sql = "  ";
        $where_sql .= isset($this->p_customer_id) && $this->p_customer_id != null ? " AND  CUSTOMER_ID ='{$this->customer_id}'  " : "";
        switch ($this->p_report_type) {
            case 1:
                $where_sql .= isset($this->p_for_month) && $this->p_for_month != null ? " AND  FOR_MONTH ='{$this->p_for_month}'  " : "";
                break;
            case 2:
                $from_month_1 = $this->p_year . '01';
                $from_month_2 = $this->p_year . '04';
                $where_sql .= isset($this->p_year) && $this->p_year != null ? " AND  FOR_MONTH between '{$from_month_1}'  and  '{$from_month_2}'" : "";
                break;
            case 3:
                $from_month_3 = $this->p_year . '05';
                $from_month_4 = $this->p_year . '08';
                $where_sql .= isset($this->p_year) && $this->p_year != null ? " AND  FOR_MONTH between '{$from_month_3}'  and  '{$from_month_4}'" : "";
                break;
            case 4:
                $from_month_5 = $this->p_year . '09';
                $from_month_6 = $this->p_year . '12';
                $where_sql .= isset($this->p_year) && $this->p_year != null ? " AND  FOR_MONTH between '{$from_month_5}'  and  '{$from_month_6}'" : "";
                break;
            case 5:
                $from_month_7 = $this->p_year . '01';
                $from_month_8 = $this->p_year . '06';
                $where_sql .= isset($this->p_year) && $this->p_year != null ? " AND  FOR_MONTH between '{$from_month_7}'  and  '{$from_month_8}'" : "";
                break;
            case 6:
                $from_month_9 = $this->p_year . '07';
                $from_month_10 = $this->p_year . '12';
                $where_sql .= isset($this->p_year) && $this->p_year != null ? " AND  FOR_MONTH between '{$from_month_9}'  and  '{$from_month_10}'" : "";
                break;
            case 7:
                $where_sql .= isset($this->p_year) && $this->p_year != null ? " AND  YEAR = '{$this->p_year}' " : "";
                break;
        }

        $config['base_url'] ='';//base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count("SUPPLIER_EVALUATION_INFO_TB", $where_sql);
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
        if ($this->p_report_type == '1') $data['page_rows'] = $this->{$this->MODEL_NAME}->m_get_list_all($where_sql, $offset, $row); else
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_all($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['type'] = $this->p_report_type;
        $this->_look_ups($data);
        $this->load->view('supplier_evaluation_marks_page', $data);


    }

    function create($orderid=0,$extract_id=0)
    {

$error1=false;
$error2=false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_weight); $i++) {

                if ($this->p_f_id[$i] == '' || $this->p_c_id[$i] == '' || $this->p_ev_weight[$i] == '') {
                    $error1 = true;


                } else {
                    if ($this->p_ev_weight[$i] > $this->p_weight[$i]) {
                        $error2 = true;


                    }

                }
            }
            if ($error1) {
                $this->print_error('يجب تعبئة جميع بنود النموذج!!');
                die;
            }
            if ($error2) {
                $this->print_error('!! الوزن لابد ان يكون اقل من او يساوي بند وزن التققيم');
                die;
            }

            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData());

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            } else {
                for ($i = 0; $i < count($this->p_weight); $i++) {
                    if ($this->p_f_id[$i] != '' && $this->p_c_id[$i] != '') {
                        $detail_seq = $this->{$this->MODEL_NAME}->create_marks($this->_postedData_details(null, $this->p_f_id[$i], $this->p_c_id[$i], $this->p_ev_weight[$i], $this->ser, 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error_del($detail_seq);
                        }
                    }
                }

                echo intval($this->ser);
            }
        } else {


            $data['content'] = 'supplier_evaluation_marks_show';
            $data['title'] = 'نموذج تقييم مورد';
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $data['orderinfo']=$this->orders_model->get($orderid);
            $data['extract_id']=$this->extract_model->get($extract_id);
            if(count($data['extract_id'])==0 and count($data['orderinfo']))
            {
                echo 'إجراء خاطئ!!';
                die;
            }
            else {
                if($data['orderinfo'][0]['ORDER_ID'] == $data['extract_id'][0]['ORDER_ID']) {
                    $result = $this->{$this->MODEL_NAME}->get_list(0);
                    if (!(count($result) > 0)) die();
                    $data['AXIS_Form'] = $result;
                    $data['next_adopt_email'] = "";
                    $data['prev_adopt_email'] = "";
                    add_css('select2_metro_rtl.css');
                    add_js('select2.min.js');
                    $this->_look_ups($data);
                    $this->load->view('template/template', $data);
                }
                else
                {
                    echo 'إجراء خاطئ!!';
                    die;
                }
            }
        }


    }

    function _postedData($isCreate = true)
    {
        $result = array(array('name' => 'ser', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
                  /*array('name' => 'cust_id', 'value' => $this->p_cust_id, 'type' => '', 'length' => -1)*/
                        array('name' => 'order_id_ser', 'value' => $this->p_order_id_ser, 'type' => '', 'length' => -1),
                        array('name' => 'ext_no', 'value' => $this->p_ext_no, 'type' => '', 'length' => -1)
            );

        if ($isCreate) {
            array_shift($result);
        }
        return $result;

    }

/// ///////////////////////////////

    function _postedData_details($ser, $f_id, $c_id, $item_mark, $ser_evaluation, $type)
    {
        echo $ser;
        $result = array(array('name' => 'ser', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'axis_id', 'value' => $f_id, 'type' => '', 'length' => -1), array('name' => 'item_id', 'value' => $c_id, 'type' => '', 'length' => -1), array('name' => 'item_mark', 'value' => $item_mark, 'type' => '', 'length' => -1), array('name' => 'ser_evaluation', 'value' => $ser_evaluation, 'type' => '', 'length' => -1));
        if ($type == 'create') {
            array_shift($result);
        }
        return $result;

    }

    function edit()
    {
	$error1=false;
    $error2=false;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_weight); $i++) {

                if ($this->p_f_id[$i] == '' || $this->p_c_id[$i] == '' || $this->p_ev_weight[$i] == '') {
                    $error1 = true;


                } else {
                    if ($this->p_ev_weight[$i] > $this->p_weight[$i]) {
                        $error2 = true;


                    }

                }
            }
            if ($error1) {
                $this->print_error('يجب تعبئة جميع بنود النموذج!!');
                die;
            }
            if ($error2) {
                $this->print_error('!! الوزن لابد ان يكون اقل من او يساوي بند وزن التققيم');
                die;
            }

            $this->ser = $this->{$this->MODEL_NAME}->edit($this->_postedData(false));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            } else {
                for ($i = 0; $i < count($this->p_weight); $i++) {
                    if ($this->p_f_id[$i] != '' && $this->p_c_id[$i] != '') {

                        $detail_seq = $this->{$this->MODEL_NAME}->edit_marks($this->_postedData_details($this->p_child_ser[$i], $this->p_f_id[$i], $this->p_c_id[$i], $this->p_ev_weight[$i], $this->ser, 'edit'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error_del($detail_seq);
                        }
                    }


                }
                echo 1;
            }
        }
    }

    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        $result2 = $this->{$this->MODEL_NAME}->get_list(0);
        if (!(count($result) > 0)) die();
        $data['AXIS_Form'] = $result2;
        $data['result'] = $result;
        $data['content'] = 'supplier_evaluation_marks_show';
        $data['title'] = 'نموذج تقييم مورد';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $next_adopt = array(1 => 10, 10 => 20, 20 => 30);

        $prev_adopt = array(1 => 0, 10 => 1, 20 => 10, 30 => 20);

        $data['next_adopt_email'] = $this->get_emails_by_code('12.' . (@$next_adopt[$result[0]['ADOPT']]));

        $data['prev_adopt_email'] = $this->get_emails_by_code('12.' . (@$prev_adopt[$result[0]['ADOPT']]));
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function public_get_items($ser, $id, $type)
    {
        echo $type;
        if ($type == 0) return $this->{$this->MODEL_NAME}->get_list($id); else
            return $this->{$this->MODEL_NAME}->get_marks($ser, $id);


    }

    function public_get_purchase_order()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_purchase_order_num != '') {
            $result = $this->{$this->MODEL_NAME}->purchase_order_get($this->p_purchase_order_num);
            echo json_encode($result);
        } else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_0()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(-1);
        } else
            echo "لم يتم الاعتماد";
    }

    //الغاء التقييم

    private function adopt($case, $note = '')
    {
        $res = $this->{$this->MODEL_NAME}->adopt($this->p_ser, $case, $note);

        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        return 1;
    }

//اعتماد التقييم طرف المعد

    function adopt_10()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(10);
        } else
            echo "لم يتم الاعتماد";
    }

    //اعتماد التقييم طرف رئيس قسم الممارسات و العطاءات
    function adopt_20()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(20);
        } else
            echo "لم يتم الاعتماد";
    }

//اعتماد التقييم طرف مدير المشتريات
    function adopt_30()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(30);
        } else
            echo "لم يتم الاعتماد";
    }

    //ارجاع التقييم للمعد
    function adopt__10()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(-10);
        } else
            echo "لم يتم الاعتماد";
    }

    /////////////////////////////////////////////////////////////
    //ارجاع التقييم لرئيس قسم الممارسات و العطاءات
    function adopt__20()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
            echo $this->adopt(-20);
        } else
            echo "لم يتم الاعتماد";
    }

}
