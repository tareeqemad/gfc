<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 28/12/19
 * Time: 11:19 ص
 */


class Pay_order extends MY_Controller
{

    var $PKG_NAME = "OUT_COLLECT_PKG";

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'OUT_COLLECT_PKG';
        //this for constant using
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
    }

    /************************************index*********************************************/

    function index($page = 1)
    {
        $data['title'] = 'أمر دفع';
        $data['content'] = 'pay_order_index';

        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    /*************************************_lookup****************************************/

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['company_cons'] = $this->rmodel->getData('COLLECTION_OFFICES_TB_GET_ALL');

        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("collection_offices/pay_order/get_page/");


        $sql = " and ADOPT = 4 and NOTI_STATUS = 2 and SER_OFFICE > 0";
        $sql .= isset($this->p_branch_no) && $this->p_branch_no ? " AND BRANCH= {$this->p_branch_no} " : '';
        $sql .= isset($this->p_company_no) && $this->p_company_no ? " AND SER_OFFICE= {$this->p_company_no} " : '';
        $sql .= isset($this->p_branch_no_dp) && $this->p_branch_no_dp ? " AND BRANCH= {$this->p_branch_no_dp} " : '';
        $sql .= isset($this->p_sub_no) && $this->p_sub_no ? " AND SUBSCRIBER = {$this->p_sub_no} " : '';

        $count_rs = $this->get_table_count(" OUT_SUB_COLLECT_TB M  WHERE 1 = 1  {$sql}");


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
        $data["rows"] = $this->rmodel->getList('OUT_SUB_COLLECT_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('pay_order_page', $data);
    }





    function pay()
    {


        $id = $this->input->post('subs_no');
        if($this->p_pay_type == 2 )
            $new_val = ($this->p_net_to_pay * $this->p_pay_value ) / 100 ;
        else
            $new_val = $this->p_pay_value;


            $params = array(
            array('name' => 'SUB_SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'PAY_TYPE', 'value' => $this->p_pay_type, 'type' => '', 'length' => -1),
            array('name' => 'PAY_VALUE', 'value' => $new_val , 'type' => '', 'length' => -1),
            array('name' => 'PAY_DATE', 'value' => $this->p_pay_date, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'FOR_MONTH', 'value' => $this->p_for_month, 'type' => '', 'length' => -1),
        );

        $this->ser= $this->rmodel->insert('PAY_ORDER_TB_INSERT',$params);

        if ($this->ser < 1) {
            $this->print_error('لم يتم الحفظ' . '<br>'.$this->ser);
        } else {
            echo intval($this->ser);
        }



    }










}
