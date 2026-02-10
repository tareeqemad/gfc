<?php

class Contract_gcc extends MY_Controller
{

    var $MODEL_NAME = 'Contract_gcc_model';
    var $PKG_NAME = "CONTRACTS_SERVICES_PKG";
    var $PAGE_URL = 'contracts_services/contract_gcc/get_page';


    function __construct()
    {
        parent::__construct();

        //
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'CONTRACTS_SERVICES_PKG';

        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model($this->MODEL_NAME);

        $this->ser = $this->input->post('ser');
        $this->gcc_no = $this->input->post('gcc_no');
        $this->contract_no = $this->input->post('contract_no');
        $this->contract_pro = $this->input->post('contract_pro');
        $this->contract_start = $this->input->post('contract_start');
        $this->contract_end = $this->input->post('contract_end');
        $this->duration_day = $this->input->post('duration_day');
        $this->branch_id = $this->input->post('branch_id');
        $this->customer_id = $this->input->post('customer_id');
        $this->notes = $this->input->post('notes');
        $this->adopt = $this->input->post('adopt');
        $this->adopt_note = $this->input->post('adopt_note');
        $this->status = $this->input->post('status');

        //contracts_services/contract_gcc/get
        //CONTRACT_SERVICES_REQ_TB
        //VAR FOR CREATE GCC
        // vars


    }


    function index($page = 1, $ser = -1, $gcc_no = -1, $contract_start = -1, $contract_end = -1, $customer_id = -1, $branch_id = -1, $adopt = -1, $status = -1)
    {


        $data['title'] = 'طلبات التعاقد';
        $data['content'] = 'Contract_gcc_index';
        $data['page'] = $page;
        $data['gcc_no'] = $gcc_no;
        $data['ser'] = $ser;
        $data['contract_start'] = $contract_start;
        $data['contract_end'] = $contract_end;
        $data['customer_id'] = $customer_id;
        $data['branch_id'] = $branch_id;
        $data['adopt'] = $adopt;
        $data['status'] = $status;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_lookup($data);

        $this->load->view('template/template', $data);
    }

    function get_page($page = 1, $ser = -1, $gcc_no = -1, $contract_start = -1, $contract_end = -1, $customer_id = -1, $branch_id = -1, $adopt = -1, $status = -1)
    {
        $this->load->library('pagination');

        $ser = $this->check_vars($ser, 'ser');
        $gcc_no = $this->check_vars($gcc_no, 'gcc_no');
        $contract_start = $this->check_vars($contract_start, 'contract_start');
        $contract_end = $this->check_vars($contract_end, 'contract_end');
        $customer_id = $this->check_vars($customer_id, 'customer_id');
        $branch_id = $this->check_vars($branch_id, 'branch_id');
        $adopt = $this->check_vars($adopt, 'adopt');
        $status = $this->check_vars($adopt, 'status');

        $where_sql = " where 1=1 ";
        $where_sql .= ($ser != null) ? " and ser= '{$ser}' " : '';
        $where_sql .= ($gcc_no != null) ? " and gcc_no= '{$gcc_no}' " : '';
        $where_sql .= ($contract_start != null) ? " and contract_start= '{$contract_start}' " : '';
        $where_sql .= ($contract_end != null) ? " and contract_end= '{$contract_end}' " : '';
        $where_sql .= ($customer_id != null) ? " and customer_id= '{$customer_id}' " : '';
        $where_sql .= ($branch_id != null) ? " and branch_id= '{$branch_id}' " : '';
        $where_sql .= ($adopt != null) ? " and adopt= '{$adopt}' " : '';
        $where_sql .= ($status != null) ? " and status= '{$status}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CONTRACT_SERVICES_REQ_TB ' . $where_sql);
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
        // echo $where_sql;
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('Contract_gcc_page', $data);

    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        if ($c_var == 'sex')
            $var = isset($this->{$c_var}) ? $this->{$c_var} : $var;
        else
            $var = ($this->{$c_var}) ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }


    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation('true');
            $this->ser_insert = $this->rmodel->insert('CONTRACT_SERVICES_REQTB_INSERT', $this->_postedData('create'));
            if ($this->ser_insert < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                for ($i = 0; $i < count($this->p_seq1); $i++) {
                    if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i] == '') {
                        if ($this->p_class_name[$i] != 0 || $this->p_class_name[$i] != '') {
                            $serDet = $this->rmodel->insert('CONTRACT_SERVICES_DETTB_INSERT', $this->_posteddata_details
                            (null, $this->ser_insert, $this->p_class_name[$i], $this->p_class_detail[$i], $this->p_class_unit[$i], $this->p_class_qty[$i], $this->p_curr[$i], 'create'));
                        }
                    }
                }

                echo intval($this->ser_insert);
            }

        } else {
            $data['title'] = ' اضافة تعاقد ';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['content'] = 'Contract_gcc_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }
    }


    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(true);
            $this->serUpdate = $this->rmodel->update('CONTRACT_SERVICES_REQTB_UPDATE', $this->_postedData());
            $x = 0;
            for ($i = 0; $i < count($this->p_seq1); $i++) {

                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i] == '') {

                    if ($this->p_class_name[$i] != 0 || $this->p_class_name[$i] != '') {
                        $serDet = $this->rmodel->insert('CONTRACT_SERVICES_DETTB_INSERT', $this->_posteddata_details
                        (null, $this->ser, $this->p_class_name[$i],
                            $this->p_class_detail[$i], $this->p_class_unit[$i], $this->p_class_qty[$i], $this->p_curr[$i], 'create'));

                        if (intval($serDet) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                        }

                    }

                } else {
                    $x = $this->rmodel->update('CONTRACT_SERVICES_DETTB_UPDATE', $this->_posteddata_details
                    ($this->p_seq1[$i], $this->ser, $this->p_class_name[$i], $this->p_class_detail[$i], $this->p_class_unit[$i], $this->p_class_qty[$i], $this->p_curr[$i], 'edit'));
                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                }
            }

            echo intval($x) != 0 ? intval($x) : intval($serDet);

        }
    }

    function _posteddata($typ = null)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->ser, 'type' => '', 'length' => -1),
            array('name' => 'GCC_NO', 'value' => $this->gcc_no, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_PRO', 'value' => $this->contract_pro, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_NO', 'value' => $this->contract_no, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_START', 'value' => $this->contract_start, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_END', 'value' => $this->contract_end, 'type' => '', 'length' => -1),
            array('name' => 'DURATION_DAY', 'value' => $this->duration_day, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_ID', 'value' => $this->branch_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->customer_id, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->notes, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);
        return $result;
    }

    function _posteddata_details($ser = null, $ser_det = null, $class_name = null,
                                 $class_detail = null, $class_unit = null, $class_qty = null, $curr = null, $type)
    {
        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'SER_DET', 'value' => $ser_det, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_NAME', 'value' => $class_name, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_DETAIL', 'value' => $class_detail, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_QTY', 'value' => $class_qty, 'type' => '', 'length' => -1),
            array('name' => 'CURR', 'value' => $curr, 'type' => '', 'length' => -1),
        );
        if ($type == 'create')
            unset($result[0]);
        return $result;
    }

    function get($id)
    {
        $result = $this->rmodel->get('CONTRACT_SERVICES_REQTB_GET', $id);
        if (!(count($result) == 1))
            die('get');
        $data['master_tb_data'] = $result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content'] = 'Contract_gcc_show';
        $data['title'] = 'بيانات التعاقد   ';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function public_get_detail($id = 0)
    {
        $this->rmodel->package = 'CONTRACTS_SERVICES_PKG';
        $data['details'] = $this->rmodel->get('CONTRACT_SERVICES_DETTB_GET', $id);
        $this->_lookup($data);
        $this->load->view('Contract_gcc_det', $data);
    }

    function public_get_data_contracts()
    {
        $gcc_id = $this->input->post('gcc_id');
        if (intval($gcc_id) > 0) {
            $contracts_name = $this->rmodel->get('CONTRACT_SERVICES_TB_CHILD', $gcc_id);
            echo json_encode($contracts_name);
        }
    }

    function public_get_data_id()
    {
        $data_id = $this->input->post('data_id');
        if (intval($data_id) > 0) {
            $data = $this->rmodel->get('CONTRACT_SERVICES_TB_GET', $data_id);
            echo json_encode($data);
        }
    }

    function adopt($case)
    {
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case, $this->adopt_note);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        return 1;
    }

    function adopt_2()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser != '') {
            echo $this->adopt(2);
        } else
            echo "لم يتم الاعتماد";
    }

    function public_get_adopt_detail()
    {
        $ser = $this->input->post('ser');
        if (intval($ser) > 0) {
            $ret = $this->{$this->MODEL_NAME}->get($ser);
            echo json_encode($ret);
        }
    }


    function _post_validation($isEdit = false)
    {
        if ($this->customer_id == '') {
            $this->print_error('يجب ادخال المورد');
        } elseif ($this->contract_start == '') {
            $this->print_error('يجب ادخال تاريخ بداية التعاقد');
        } elseif ($this->contract_end == '') {
            $this->print_error('يجب ادخال تاريخ بداية التعاقد');
        }
    }

    function _lookup(&$data)
    {
        $this->load->model('settings/currency_model');
        $data['curr_id_cons'] = $this->currency_model->get_all();
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['contracts_type'] = $this->constant_details_model->get_list(330);
        $data['adopt_cons'] = $this->constant_details_model->get_list(331);
        $data['status_cons'] = $this->constant_details_model->get_list(332);
        $data['class_unit_cons'] = $this->constant_details_model->get_list(29);
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->rmodel->package = 'CONTRACTS_SERVICES_PKG';
        $data['gcc_id_parent'] = $this->rmodel->getData('CONTRACT_SERVICES_TB_GET_ALL');
        $this->rmodel->package = 'PAYMENT_PKG';
        $data['customers'] = $this->rmodel->get('CUSTOMERS_TB_GET_ALL_BY_TYPE', 1);
        $data['help'] = $this->help;
    }
}//CONTRACT_SERVICES_DETTB_INSERT