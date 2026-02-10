<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/12/19
 * Time: 09:37 ص
 */



class Branchs_adopt extends MY_Controller
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

    /************************************index*********************************************/

    function index($page = 1)
    {

        $data['title'] = 'بيانات المشتركين';
        $data['content'] = 'branchs_adopt_index';

        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['hide'] ='hidden';

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
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }



    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("collection_offices/branchs_adopt/get_page/");


        $sql = "";
        $sql .= isset($this->p_branch_no) && $this->p_branch_no ? " AND BRANCH= {$this->p_branch_no} " : '';
        $sql .= isset($this->p_branch_no_dp) && $this->p_branch_no_dp ? " AND BRANCH= {$this->p_branch_no_dp} " : '';
        $sql .= isset($this->p_disclosure_ser) && $this->p_disclosure_ser ? " AND SER= {$this->p_disclosure_ser} " : '';


        $count_rs = $this->get_table_count(" OUT_DISCLOSURE_TB M WHERE 1=1  {$sql}");

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

        //var_dump($sql);
        $data["rows"] = $this->rmodel->getList('OUT_DISCLOSURE_TB_LIST', " $sql ", $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('branchs_adopt_page', $data);
     }

    function adopt()
    {

        $rs = $this->rmodel->update('OUT_SUB_COLLECT_TB_UPDATE', $this->_postedDatastatus(false));
        if (intval($rs) <= 0) {
            $this->print_error('error' . '<br>' . $rs);
        }
        echo 1;

    }

    function _postedDatastatus($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_id, 'type' => '', 'length' => -1),
        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function get_sub(){

        $sub_no = isset($this->p_sub_ser) ? $this->p_sub_ser : 0;
        $data["sub"] = $this->rmodel->get('DIS_OUT_SUB_COLLECT_TB_GET', $sub_no);
        $data["page"] = 1 ;
        $this->load->view('subscriber_info_details', $data);

    }

    function adopt_sub()
    {

        $rs = $this->rmodel->update('DIS_OUT_SUB_COLLECT_TB_UPDATE', $this->_postedDatastatusSub(false));
        if (intval($rs) <= 0) {
            $this->print_error('error' . '<br>' . $rs);
        }
        echo 1;

    }

    function _postedDatastatusSub($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_id_ser, 'type' => '', 'length' => -1),
        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }











}
