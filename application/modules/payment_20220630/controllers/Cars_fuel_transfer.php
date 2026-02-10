<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/06/17
 * Time: 09:20 ص
 */
class cars_fuel_transfer extends MY_Controller
{

    var $MODEL_NAME = 'cars_fuel_transfer_model';

    function  __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('customer_elect_details_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
    }


    function index($page = 1)
    {

        $data['title'] = 'تحويل الوقود';
        $data['content'] = 'cars_fuel_transfer_index';
        $data['page'] = $page;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['action'] = 'index';

        $this->load->view('template/template', $data);

    }

    function  _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('combotree.css');


        $data['help'] = $this->help;

    }

    function get_page($page = 1)
    {

        $this->load->library('pagination');

        $config['base_url'] = base_url("payment/cars_fuel_transfer/get_page/");

        $sql = isset($this->p_from_file_id) && $this->p_from_file_id ? " AND M.FROM_FILE_ID  IN (SELECT CAR_FILE_ID FROM CARS_TB WHERE CAR_NUM = '{$this->p_from_file_id}')   " : "";
        $sql .= isset($this->p_to_file_id) && $this->p_to_file_id ? " AND M.TO_FILE_ID   IN (SELECT CAR_FILE_ID FROM CARS_TB WHERE CAR_NUM = '{$this->p_to_file_id}')    " : "";


        $count_rs = $this->get_table_count(" CARS_FUEL_TRANSFER_TB M WHERE 1=1 {$sql}");

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

        $data["rows"] = $this->cars_fuel_transfer_model->get_list($sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('cars_fuel_transfer_page', $data);

    }

    /**
     * get car by id ..
     */
    function get($id)
    {

        $result = $this->cars_fuel_transfer_model->get($id);

        $data['title'] = 'تحويل الوقود';
        $data['content'] = 'cars_fuel_transfer_show';
        $data['result'] = $result;
        $this->_lookup($data);

        $this->load->view('template/template', $data);


    }

    /**
     * create action : insert new car data ..
     * receive post data of car
     *
     */
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            if (!$this->validate_data()) {
                $this->print_error('تأكد من إدخال تفاصيل الطلب !');
                return;
            }
            $rs = $this->cars_fuel_transfer_model->create($this->_postedData());


            if (intval($rs) <= 0)
                $this->print_error('فشل في حفظ البيانات ' . $rs);

            echo $rs;


        } else {

            $data['title'] = 'كوبونات الوقود ';
            $data['content'] = 'cars_fuel_transfer_show';

            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    function validate_data()
    {

        return isset($this->p_the_count) && $this->p_the_count > 0;
    }

    /**
     * edit action : update exists car data ..
     * receive post data of car
     * depended on car prm key
     */
    function edit()
    {

        $rs = $this->cars_fuel_transfer_model->edit($this->_postedData(false));

        if (intval($rs) <= 0)
            $this->print_error('فشل في حفظ البيانات ');

        echo $this->p_ser;

    }


    function _postedData($isCreate = true)
    {


        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'FROM_FILE_ID', 'value' => $this->p_from_file_id, 'type' => '', 'length' => -1),
            array('name' => 'TO_FILE_ID', 'value' => $this->p_to_file_id, 'type' => '', 'length' => -1),
            array('name' => 'THE_COUNT', 'value' => $this->p_the_count, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $this->p_hints, 'type' => '', 'length' => -1),

        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }


    function cancel()
    {
        $rs = $this->cars_fuel_transfer_model->adopt($this->p_ser, 0);

        if (intval($rs) <= 0)
            $this->print_error('فشل في حفظ البيانات ' . $rs);

        echo $this->p_ser;
    }


}
