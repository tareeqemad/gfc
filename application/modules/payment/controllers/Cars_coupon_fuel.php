<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 10:00 ص
 */
class cars_coupon_fuel extends MY_Controller
{

    var $MODEL_NAME = 'cars_coupon_fuel_model';

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

        $data['title'] = 'كوبونات الوقود';
        $data['content'] = 'cars_coupon_fuel_index';
        $data['page'] = $page;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['action'] = 'index';
		$this->_lookup($data);

        $this->load->view('template/template', $data);

    }

    function  _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('combotree.css');


        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['user_job'] = $this->constant_details_model->get_list(55);
        $data['used_purpose'] = $this->constant_details_model->get_list(56);
        $data['cars_coupon_fuel_class'] = $this->constant_details_model->get_list(43);
        $data['fuel_type'] = $this->constant_details_model->get_list(58);
        $data['suppliers'] = $this->constant_details_model->get_list(250);
		$data['car_case'] = $this->constant_details_model->get_list(405);
        $data['coupon_case'] = $this->constant_details_model->get_list(144);

        $data['help'] = $this->help;

    }

    function get_page($page = 1)
    {
 
        $this->load->library('pagination');

        $config['base_url'] = base_url("payment/cars_coupon_fuel/get_page/");
        $sql = " AND (M.BRANCH_ID = {$this->user->branch} or  {$this->user->branch} = 1 )";
        $sql .= isset($this->p_coupon_fuel_id) && $this->p_coupon_fuel_id ? " AND COUPON_FUEL_ID ='{$this->p_coupon_fuel_id}' " : "";
        $sql .= isset($this->p_car_no) && $this->p_car_no ? " AND M.CAR_FILE_ID IN (SELECT CAR_FILE_ID FROM CARS_TB WHERE CAR_NUM = '{$this->p_car_no}') " : "";
        $sql .= isset($this->p_car_owner) && $this->p_car_owner ? " AND  M.CAR_FILE_ID IN (SELECT CAR_FILE_ID FROM CARS_TB WHERE CAR_OWNER LIKE '%{$this->p_car_owner}%')  " : "";
        $sql .= isset($this->p_emp_id_name) && $this->p_emp_id_name ? " AND  FINANCIAL_PKG.ACOUNTS_TB_GET_NAME_ALL(M.EMP_ID,2) LIKE '%{$this->p_emp_id_name}%' " : "";
        $sql .= isset($this->p_entry_user) && $this->p_entry_user !=null ? " AND  USER_PKG.GET_USER_NAME(M.ENTRY_USER) LIKE '%{$this->p_entry_user}%' " :"" ;

        $sql.= ($this->p_coupon_start_date!= null or $this->p_coupon_end_date!= null)? " AND TRUNC(COUPON_FUEL_DATE) between nvl('{$this->p_coupon_start_date}','01/01/1000') and nvl('{$this->p_coupon_end_date}','01/01/3000') " : '';
        $sql =$sql.(isset($this->p_coupon_case) && $this->p_coupon_case !=null ? " AND  M.CAR_FILE_ID IN (SELECT CAR_FILE_ID FROM CARS_TB WHERE COUPON_FUEL_CASE = {$this->p_coupon_case} ) " : '');
        $sql =$sql.(isset($this->p_branch) && $this->p_branch !=null ? " AND  M.CAR_FILE_ID IN (SELECT CAR_FILE_ID FROM CARS_TB WHERE BRANCH_ID = {$this->p_branch} ) " : '');

	
//BRANCH_ID
        $count_rs = $this->get_table_count(" cars_coupon_fuel_TB M WHERE 1=1 {$sql}");
        
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

        $data["rows"] = $this->cars_coupon_fuel_model->get_list($sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('cars_coupon_fuel_page', $data);

    }

    /**
     * get car by id ..
     */
    function get($id)
    {

        $result = $this->cars_coupon_fuel_model->get($id);

        $data['title'] = 'كوبونات الوقود';
        $data['content'] = 'cars_coupon_fuel_show';
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
            $rs = $this->cars_coupon_fuel_model->create($this->_postedData());


            if (intval($rs) <= 0)
                $this->print_error('فشل في حفظ البيانات ' . $rs);

            echo $rs;


        } else {

            $data['title'] = 'كوبونات الوقود ';
            $data['content'] = 'cars_coupon_fuel_show';

            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    function validate_data()
    {

        return isset($this->p_coupon_fuel_amount) && intval($this->p_coupon_fuel_amount) > 0;
    }

    /**
     * edit action : update exists car data ..
     * receive post data of car
     * depended on car prm key
     */
    function edit()
    {

        $rs = $this->cars_coupon_fuel_model->edit($this->_postedData(false));

        if (intval($rs) <= 0)
            $this->print_error('فشل في حفظ البيانات ');

        echo $this->p_ser;

    }

    /**
     * delete action : delete car data ..
     * receive prm key as request
     *
     */
    function delete()
    {

        $id = $this->input->post('id');

        $this->IsAuthorized();

        $msg = 0;

        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->cars_coupon_fuel_model->delete($val);
            }
        } else {
            $msg = $this->cars_coupon_fuel_model->delete($id);
        }

        if ($msg == 1) {
            echo modules::run('payment/cars_coupon_fuel/get_page', 1);
        } else {

            $this->print_error_msg($msg);
        }
    }

    function _postedData($isCreate = true)
    {


        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'CAR_FILE_ID', 'value' => $this->p_car_file_id, 'type' => '', 'length' => -1),
            array('name' => 'FUEL_TYPE', 'value' => $this->p_fuel_type, 'type' => '', 'length' => -1),
            array('name' => 'COUPON_FUEL_ID', 'value' => $this->p_cars_coupon_fuel_id, 'type' => '', 'length' => -1),
            array('name' => 'COUPON_FUEL_AMOUNT', 'value' => $this->p_coupon_fuel_amount, 'type' => '', 'length' => -1),
            array('name' => 'COUPON_FUEL_DATE', 'value' => $this->p_coupon_fuel_date, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $this->p_hints, 'type' => '', 'length' => -1),
            array('name' => 'EMP_ID', 'value' => $this->p_emp_id, 'type' => '', 'length' => -1),
            array('name' => 'SUPPLIER', 'value' => $this->p_supplier, 'type' => '', 'length' => -1),
            array('name' => 'METAR_COUNT', 'value' => $this->p_metar_count, 'type' => '', 'length' => -1),
			array('name' => 'CAR_CASE', 'value' => $this->p_car_case, 'type' => '', 'length' => -1),

        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }


    function cancel()
    {
        $rs = $this->cars_coupon_fuel_model->adopt($this->p_ser, 0);

        if (intval($rs) <= 0)
            $this->print_error('فشل في حفظ البيانات ' . $rs);

        echo $this->p_ser;
    }

    function paid()
    {
        $rs = $this->cars_coupon_fuel_model->adopt($this->p_ser, 2);

        if (intval($rs) <= 0)
            $this->print_error('فشل في حفظ البيانات ' . $rs);

        echo $this->p_ser;
    }

    function public_balance()
    {

        $rs = $this->cars_coupon_fuel_model->CARS_FUEL_AMOUNT_PROC($this->p_file_id, $this->p_date , $this->p_ser);
        $data['rows'] = $rs;
        $this->load->view('coupon_balance_view', $data);
    }

    function public_balance_get()
    {
        $data['content'] = 'coupon_balance_view';

        $rs = $this->cars_coupon_fuel_model->CARS_FUEL_AMOUNT_PROC($this->q_file_id, $this->q_date , $this->q_ser);
        $data['rows'] = $rs;
        $this->load->view('template/view', $data);
    }


}
