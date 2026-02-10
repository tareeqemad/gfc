<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 29/06/2022
 * Time: 13:18 pm
 */
class No_signed_reasons extends MY_Controller
{

    var $PAGE_URL = 'payroll_data/no_signed_reasons/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

    }

    function index($page = 1)
    {

        $data['title'] = 'اسباب عدم الانصراف بدون بصمة';
        $data['content'] = 'no_signed_reasons_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');

        $where_sql = '';

        $where_sql .= isset($this->p_reson) && $this->p_reson != null ? " AND  M.RESON like '".$this->p_reson."%'" : "";
        $where_sql .= isset($this->p_is_active) && $this->p_is_active != null ? " AND  M.IS_ACTIVE = '{$this->p_is_active}'  " : "";


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.NO_SIGNED_RESON  M WHERE 1 =1 ' . $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $data["page_rows"] = $this->rmodel->getList('NO_SIGNED_RESON_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('no_signed_reasons_page', $data);

    }


    function public_get_id($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $result = $this->rmodel->get('NO_SIGNED_RESON_GET', $id);
        echo json_encode($result);
    }


    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->rmodel->insert('NO_SIGNED_RESON_INSERT', $this->_postedData());
            if (intval($result) <= 0) {
                $this->print_error($result);
            }
            echo 1;
        }
    }//end create

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->Rmodel->package = $this->PackageService;
            $result = $this->rmodel->update('NO_SIGNED_RESON_UPDATE', $this->_postedData(false));
            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }
            echo 1;
        }
    }//end edit

    function _postedData($isCreate = true)
    {

        $result = array(
            array('name' => 'NO_IN', 'value' => $this->p_h_no, 'type' => '', 'length' => -1),
            array('name' => 'RESON_IN', 'value' => $this->p_reson_m, 'type' => '', 'length' => -1),
            array('name' => 'IS_ACTIVE', 'value' => $this->p_is_active_m, 'type' => '', 'length' => -1),
        );
        if ($isCreate) {
            array_shift($result);
        }
        return $result;
    }


    function _post_validation()
    {
        if ($this->p_reson == '' || $this->p_is_active == '') {
            $this->print_error('يجب ادخال جميع البيانات');
        }
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/constant_details_model');
        $data['is_active_cons'] = $this->constant_details_model->get_list(442);

    }


}

