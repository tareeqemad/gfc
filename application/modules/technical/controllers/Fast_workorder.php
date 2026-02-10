<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 22/08/16
 * Time: 08:43 ص
 */
class Fast_workorder extends MY_Controller
{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('Fast_workorder_model');
        $this->load->model('settings/constant_details_model');
    }


    function _lookUps_data(&$data)
    {

        $data['REQUESTS_TYPE'] = $this->constant_details_model->get_list(105);
        $data['help'] = $this->help;
        $this->_loadDatePicker();
    }

    function index($page = 1)
    {

        $data['title'] = 'أمر عمل لأغراض الصيانة';
        $data['content'] = 'Fast_workorder_index';
        $data['page'] = $page;
        $data['action'] = 'index';

        $this->_loadDatePicker();
        $this->_lookUps_data($data);

        $this->load->view('template/template', $data);

    }

    function get_page($page = 1, $action = 'get')
    {

        $this->load->library('pagination');

        $sql = '';

        $config['base_url'] = base_url("technical/fast_workorder/get_page/");

        $count_rs = $this->get_table_count(' FAST_WORKORDER_TB M WHERE 1 = 1 ' . $sql);

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

        $data["rows"] = $this->Fast_workorder_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['action'] = $action;


        $this->load->view('Fast_workorder_page', $data);

    }

    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $result = $this->Fast_workorder_model->create($this->_postedData());

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            echo $result;

        } else {
            $data['content'] = 'Fast_workorder_show';
            $data['title'] = 'أمر عمل لأغراض الصيانة';
            $data['action'] = 'index';
            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }


    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->Fast_workorder_model->edit($this->_postedData(false));


            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            echo $this->p_ser;

        }
    }

    function generate()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->Fast_workorder_model->generate($this->p_ser);



            echo $this->p_ser;

        }
    }

    function _postedData($isCreate = true)
    {


        $adapter_sers = isset($this->p_adapter_name) ? implode(',', $this->p_adapter_name) : '';
        $adapter_ser_ids = isset($this->p_adapter) ? implode(',', $this->p_adapter) : '';

        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'TITLE', 'value' => $this->p_title, 'type' => '', 'length' => -1),
            array('name' => 'FROM_DATE', 'value' => $this->p_from_date, 'type' => '', 'length' => -1),
            array('name' => 'TO_DATE', 'value' => $this->p_to_date, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_TYPE', 'value' => 5, 'type' => '', 'length' => -1),
            array('name' => 'JOB_ID', 'value' => $this->p_job_id, 'type' => '', 'length' => -1),
            array('name' => 'ADAPTER_SERS', 'value' => $adapter_sers, 'type' => '', 'length' => -1),
            array('name' => 'ADAPTER_SERS_ID', 'value' => $adapter_ser_ids, 'type' => '', 'length' => -1),


        );

        if ($isCreate) {
            array_shift($result);
        }


        return $result;
    }

    function get($id, $action = 'create')
    {


        $result = $this->Fast_workorder_model->get($id);


        $data['content'] = 'Fast_workorder_show';
        $data['title'] = 'أمر عمل لأغراض الصيانة';

        $data['result'] = $result;

        $data['can_edit'] = count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id && HaveAccess(base_url('technical/fast_workorder/edit'));

        $this->_lookUps_data($data, null);

        $data['action'] = $action;

        $this->load->view('template/template', $data);
    }


}