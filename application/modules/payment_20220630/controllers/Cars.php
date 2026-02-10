<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 10:00 ص
 */
class Cars extends MY_Controller
{
     function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'fleet_pkg';

        //this for constant using
        $this->load->model('customer_elect_details_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
    }

    /**
     *
     * index action perform all functions in view of cars_show view
     * from this view , can show cars tree , insert new user , update exists one and delete other ..
     *
     */
    function index($page = 1)
    {

        $data['title'] = 'فهرس السيارات ';
        $data['content'] = 'cars_index';
        $data['page'] = $page;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();

        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['user_job'] = $this->constant_details_model->get_list(55);
        $data['used_purpose'] = $this->constant_details_model->get_list(56);
        $data['car_class'] = $this->constant_details_model->get_list(43);
        $data['fuel_type'] = $this->constant_details_model->get_list(58);
        $data['car_ownership_list'] = $this->constant_details_model->get_list(272);
        $data['custody_type_list'] = $this->constant_details_model->get_list(271);
        $data['license_combany_list'] = $this->constant_details_model->get_list(177);
        $data['license_type_list'] = $this->constant_details_model->get_list(274);
        $data['insurance_type_list'] = $this->constant_details_model->get_list(178);
		$data['car_case'] = $this->constant_details_model->get_list(405);
        $data['machine_case'] = $this->constant_details_model->get_list(414);
        $data['help'] = $this->help;

    }

    function get_page($page = 1)
    {

        $this->load->library('pagination');

        $config['base_url'] = base_url("payment/cars/get_page/");

        $sql = isset($this->p_no) && $this->p_no ? " AND CAR_NUM like '%{$this->p_no}%' " : "";
        $sql .= isset($this->p_name) && $this->p_name ? " AND CAR_OWNER LIKE '%{$this->p_name}%' " : "";
        $sql .= isset($this->p_machine_case) && $this->p_machine_case ? " AND MACHINE_CASE LIKE '%{$this->p_machine_case}%' " : "";
        $sql .= isset($this->p_car_case) && $this->p_car_case ? " AND CAR_CASE LIKE '%{$this->p_car_case}%' " : "";

        $count_rs = $this->get_table_count(" CARS_TB where 1 = 1 {$sql}");

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
        $data["rows"] = $this->rmodel->getList('CARS_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('cars_page', $data);
    }

    /** get car by id ..*/
    function get_id($id)
    {
        $result = $this->rmodel->get('CARS_TB_GET', $id);
        $data['title'] = 'تعديل بيانات السيارة  ';
        $data['content'] = 'car_show';
        $data['result'] = $result;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }


    function public_select_car($txt = null)
    {

        $data['title'] = 'فهرس السيارات ';
        $data['content'] = 'car_select_view';
        $offset = 0;
        $row = 500;
        $result = $this->rmodel->getList('CARS_TB_LIST',"AND (BRANCH_ID = {$this->user->branch} or  {$this->user->branch} = 1 )", $offset, $row);
        $data['rows'] = $result;
        $data['txt'] = $txt;
        $this->load->view('template/view', $data);

    }


    function public_select_car_num($txt = null)
    {

        $data['title'] = 'فهرس السيارات ';
        $data['content'] = 'car_num_select';
        $offset = 0;
        $row = 1500;
        $result = $this->rmodel->getList('CAR_DRIVE_NAME_LIST',"", $offset, $row);
        $data['rows'] = $result;
        $data['txt'] = $txt;
        $this->load->view('template/view', $data);

    }

    /**
     * create action : insert new car data ..
     * receive post data of car
     *
     */
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $car_no = $this->rmodel->insert('CARS_TB_INSERT', $this->_postedData());

            if ($car_no > 0) {
                echo $car_no;
            }

        } else {

            $data['title'] = 'تعديل بيانات السيارة  ';
            $data['action'] = 'index';
            $data['content'] = 'car_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }
    }
    /**
     * edit action : update exists car data ..
     * receive post data of car
     * depended on car prm key
     */
    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $rs =  $this->rmodel->update('CARS_TB_UPDATE', $this->_postedData(false));

            if(intval($rs) <=0){
                $this->print_error(' فشل بحفظ البيانات '.$rs);
            }
                echo $rs;
        }

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
                $msg = $this->cars_model->delete($val);
            }
        } else {
            $msg = $this->cars_model->delete($id);
        }

        if ($msg == 1) {
            echo modules::run('payment/cars/get_page', 1);
        } else {

            $this->print_error_msg($msg);
        }
    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'CAR_FILE_ID', 'value' => $this->p_car_file_id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_NUM', 'value' => $this->p_car_num, 'type' => '', 'length' => -1),
            array('name' => 'CAR_CLASS', 'value' => $this->p_car_class, 'type' => '', 'length' => -1),
            array('name' => 'CAR_MODEL', 'value' => $this->p_car_model, 'type' => '', 'length' => -1),
            array('name' => 'FUEL_TYPE', 'value' => $this->p_fuel_type, 'type' => '', 'length' => -1),
            array('name' => 'MONTHLY_ALLOCATED', 'value' => $this->p_monthly_allocated, 'type' => '', 'length' => -1),
            array('name' => 'MONTHLY_ALLOCATED_DATE', 'value' => $this->p_monthly_allocated_date, 'type' => '', 'length' => -1),
            array('name' => 'CAR_OWNER', 'value' => $this->p_car_owner, 'type' => '', 'length' => -1),
            array('name' => 'CAR_OWNER_NO', 'value' => $this->p_car_owner_no, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_ID', 'value' => $this->p_branch_id, 'type' => '', 'length' => -1),
            array('name' => 'YEAR_PRODUCE', 'value' => $this->p_year_produce, 'type' => '', 'length' => -1),
            array('name' => 'ENGINE_POWER', 'value' => $this->p_engine_power, 'type' => '', 'length' => -1),
            array('name' => 'DEFINITION_CODE', 'value' => $this->p_definition_code, 'type' => '', 'length' => -1),
            array('name' => 'USER_JOB', 'value' => $this->p_user_job, 'type' => '', 'length' => -1),
            array('name' => 'USED_PURPOSE', 'value' => $this->p_used_purpose, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $this->p_hints, 'type' => '', 'length' => -1),
            array('name' => 'CAR_COST_HOURE', 'value' => $this->p_car_cost_houre, 'type' => '', 'length' => -1),
            array('name' => 'CAR_OWNERSHIP', 'value' => $this->p_car_ownership, 'type' => '', 'length' => -1),
            array('name' => 'PRODUCTION_DATE', 'value' => $this->p_production_date, 'type' => '', 'length' => -1),
            array('name' => 'SERVICE_DATE', 'value' => $this->p_service_date, 'type' => '', 'length' => -1),
            array('name' => 'SELF_WEIGHT', 'value' => $this->p_self_weight, 'type' => '', 'length' => -1),
            array('name' => 'TOTAL_WEIGHT', 'value' => $this->p_total_weight, 'type' => '', 'length' => -1),
            array('name' => 'LICENSE_TYPE', 'value' => $this->p_license_type, 'type' => '', 'length' => -1),
            array('name' => 'CUSTODY_TYPE', 'value' => $this->p_custody_type, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'PRICE', 'value' => $this->p_price, 'type' => '', 'length' => -1),
            array('name' => 'DEPRECIATION_RATE', 'value' => $this->p_depreciation_rate, 'type' => '', 'length' => -1),
            array('name' => 'INSURANCE_START_DATE', 'value' => $this->p_insurance_start_date, 'type' => '', 'length' => -1),
            array('name' => 'INSURANCE_END_DATE', 'value' => $this->p_insurance_end_date, 'type' => '', 'length' => -1),
            array('name' => 'INSURANCE_VALUE', 'value' => $this->p_insurance_value, 'type' => '', 'length' => -1),
            array('name' => 'LETTER_PER_KILOMETER', 'value' => $this->p_letter_per_kilometer, 'type' => '', 'length' => -1),
            array('name' => 'CAR_LICENSE_START', 'value' => $this->p_car_license_start, 'type' => '', 'length' => -1),
            array('name' => 'CAR_LICENSE_END', 'value' => $this->p_car_license_end, 'type' => '', 'length' => -1),
            array('name' => 'CAR_LICENSE_VALUE', 'value' =>$this->p_car_license_value, 'type' => '', 'length' => -1),
            array('name' => 'CAR_LICENSE_NUMBER', 'value' => $this->p_car_license_number, 'type' => '', 'length' => -1),
            array('name' => 'DATE_OF_LICENSE', 'value' => $this->p_date_of_license, 'type' => '', 'length' => -1),
            array('name' => 'INSURANCE_NUMBER', 'value' => $this->p_insurance_number, 'type' => '', 'length' => -1),
            array('name' => 'INSURANCE_TYPE', 'value' => $this->p_insurance_type, 'type' => '', 'length' => -1),
            array('name' => 'INSURANCE_DATE', 'value' => $this->p_insurance_date, 'type' => '', 'length' => -1),
            array('name' => 'LICENSE_COMBANY', 'value' =>$this->p_license_combany, 'type' => '', 'length' => -1),
			array('name' => 'CAR_CASE', 'value' =>$this->p_car_case, 'type' => '', 'length' => -1),
            array('name' => 'MACHINE_CASE', 'value' =>$this->p_machine_case, 'type' => '', 'length' => -1),
        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function public_get_cars($id = 0)
    {

        $id = isset($this->p_id) ? $this->p_id : $id;

        $data['rows'] = $this->rmodel->get('CARS_AUDIT_FUEL_TB_GET', $id);

        $this->load->view('cars_audit_fuel_page', $data);
    }

    function public_get_car_insurance($id = 0)
    {

        $id = isset($this->p_id) ? $this->p_id : $id;

        $data['rows'] = $this->rmodel->get('CAR_INSURANCE_TB_GET', $id);

        $this->load->view('car_insurance_page', $data);
    }


 function report()
    {

        $data['title'] = 'تقرير الوقود';
        $data['content'] = 'cars_fuel_report';
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['fuel_type'] = $this->constant_details_model->get_list(58);
		$data['suppliers'] = $this->constant_details_model->get_list(250);
		
		add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template', $data);

    }

    function public_get_car_licences($id = 0)
    {

        $id = isset($this->p_id) ? $this->p_id : $id;

        $data['rows'] = $this->rmodel->get('CAR_LICENSE_TB_GET', $id);

        $this->load->view('car_license_page', $data);
    }


    function public_get_car_depreciation($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['rows'] = $this->rmodel->get('CAR_DEPRECIATION_TB_GET', $id);
        $this->load->view('car_depreciation_page', $data);

    }



}
