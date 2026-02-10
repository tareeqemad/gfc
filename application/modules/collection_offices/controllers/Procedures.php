<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 05/07/20
 * Time: 01:43 م
 */



class Procedures extends MY_Controller
{

    var $PKG_NAME = "OUT_COLLECT_PKG";

    function __construct()
    {
        parent::__construct();


        $this->load->model('root/rmodel');
        $this->rmodel->package = 'OUT_COLLECT_PKG';
        //this for constant using
        $this->load->model('stores/store_committees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model("stores/receipt_class_input_group_model");
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
    }

    function public_get_details($id = 0, $type = 0)
    {
        $type = isset($this->p_type) ? $this->p_type : $type;
        $sql = ' ';
        $sql .= isset($this->p_id) && $this->p_id ? " AND AGREEMENT_NO = {$this->p_id} " : '';
        $sql .= isset($this->p_type) && $this->p_type ? " AND AGREEMENT_TYPE = {$this->p_type} " : '';
        $data["details"] = $this->rmodel->getList('OUT_CHECKS_TB_LIST', " $sql ", 0, 100);

        if($type == 2){
            $this->load->view('Procmangment_details', $data);
        }else{
            $this->load->view('Procmangment_drafts_details', $data);
        }
    }

    /************************************index*********************************************/

    function index($page = 1)
    {

        $data['title'] = 'التسويات ';
        $data['content'] = 'procedures_index';

        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');


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
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['company_cons'] = $this->rmodel->getData('COLLECTION_OFFICES_TB_GET_ALL');
        $data['pro_type'] = $this->constant_details_model->get_list(326);
        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Procedures/get_page/");

        $sql = ' ';
        $sql .= isset($this->p_company_no) && $this->p_company_no ? " AND SER_OFFICE = {$this->p_company_no} " : '';
        $sql .= isset($this->p_sub_no) && $this->p_sub_no ? " AND SUBSCRIBER = {$this->p_sub_no} " : '';
        $sql .= isset($this->p_for_month) && $this->p_for_month ? " AND FOR_MONTH = {$this->p_for_month} " : '';
        $sql .= isset($this->p_proc_type) && $this->p_proc_type ? " AND TYPE = {$this->p_proc_type} " : '';


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = 2000; //count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $data["rows"] = $this->rmodel->getList('OUT_AGREEMENT_TB_LIST',   $sql  , $offset, $row);



        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('procedures_page', $data);
    }








}
