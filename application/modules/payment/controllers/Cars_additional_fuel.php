<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 10:00 ص
 */
class cars_additional_fuel extends MY_Controller
{

    var $MODEL_NAME = 'cars_additional_fuel_model';

    function  __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('customer_elect_details_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
    }

    /**
     *
     * index action perform all functions in view of cars_additional_fuel_show view
     * from this view , can show cars_additional_fuel tree , insert new user , update exists one and delete other ..
     *
     */
    function index($page = 1, $action = 'index', $case = 1)
    {

        $data['title'] = '  طلبات الوقود الإضافي  ';
        $data['content'] = 'cars_additional_fuel_index';
        $data['page'] = $page;
        $data['action'] = $action;
        $data['case'] = $case;

        $this->_lookup($data);

        $this->load->view('template/template', $data);

    }


    function archive($page = 1, $action = 'archive', $case = 1)
    {

        $data['title'] = '  طلبات الوقود الإضافي  ';
        $data['content'] = 'cars_additional_fuel_index';
        $data['page'] = $page;
        $data['action'] = $action;
        $data['case'] = $case;

        $this->_lookup($data);

        $this->load->view('template/template', $data);

    }

    function  _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('combotree.css');

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['user_job'] = $this->constant_details_model->get_list(55);
        $data['used_purpose'] = $this->constant_details_model->get_list(56);
        $data['cars_additional_fuel_class'] = $this->constant_details_model->get_list(43);
        $data['fuel_type'] = $this->constant_details_model->get_list(58);

        $data['help'] = $this->help;

    }

    function get_page($page = 1, $action = 'index', $case = 1)
    {

        $this->load->library('pagination');

        $config['base_url'] = base_url("payment/cars_additional_fuel/get_page/");
        // تم دمج مقر الصيانة مع مقر غزة 202210
		$sql  = " AND ( DECODE(BRANCH_ID,8,2,BRANCH_ID) = {$this->user->branch} or  {$this->user->branch} = 1 )";
        $sql .= isset($this->p_no) && $this->p_no ? " AND  M.CARS_ADDITIONAL_FUEL_ID IN (SELECT CARS_ADDITIONAL_FUEL_ID FROM CARS_ADDITIONAL_FUEL_DET_TB WHERE CAR_NUM IN (SELECT CAR_FILE_ID FROM CARS_TB WHERE CAR_NUM = '{$this->p_no}')) " : "";
        $sql .= isset($this->p_name) && $this->p_name ? " AND   M.CARS_ADDITIONAL_FUEL_ID IN (SELECT CARS_ADDITIONAL_FUEL_ID FROM CARS_ADDITIONAL_FUEL_DET_TB WHERE CAR_NUM IN (SELECT CAR_FILE_ID FROM CARS_TB WHERE CAR_OWNER LIKE '%{$this->p_name}%' ))  " : "";
        $sql .= isset($this->p_hints) && $this->p_hints ? " AND M.DECLARATION like '%{$this->p_hints}%' " : "";
        $sql .= (isset($this->p_action) && $this->p_action == 'archive') || $action == 'archive' ? 'and   M.CARS_ADDITIONAL_FUEL > 1' : ($case == 1 ? "  AND   M.ENTRY_USER = {$this->user->id} " : " and   M.CARS_ADDITIONAL_FUEL = {$case}-1  ");

	  

        $count_rs = $this->get_table_count(" CARS_ADDITIONAL_FUEL_TB M   WHERE 1=1 {$sql}");

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

        $data["rows"] = $this->cars_additional_fuel_model->get_list($sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['action'] = $action;

        $this->load->view('cars_additional_fuel_page', $data);

    }

    /**
     * get car by id ..
     */
    function get($id, $action = 'index')
    {

        $result = $this->cars_additional_fuel_model->get($id);

        $data['title'] = ' الوقود الاضافي ';
        $data['content'] = 'cars_additional_fuel_show';
        $data['result'] = $result;

        $data['can_edit'] = $action != 'archive' && count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER'] && $result[0]['cars_additional_fuel'] <= 1) ? true : false : false;

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
            $rs = $this->cars_additional_fuel_model->create($this->_postedData());

            for ($i = 0; $i < count($this->p_ser); $i++) {
                if ($this->p_ser[$i] <= 0)
                    $this->cars_additional_fuel_model->create_details($this->_postedData_Details(
                        $rs,
                        $this->p_fuel_type[$i],
                        $this->p_car_num[$i],
                        $this->p_branch_amount[$i],
                        $this->p_hints[$i]
                    ));

            }

            if (intval($rs) <= 0)
                $this->print_error('فشل في حفظ البيانات ' . $rs);

            echo $rs;


        } else {

            $data['title'] = 'الوقود الاضافي';
            $data['content'] = 'cars_additional_fuel_show';
            $data['can_edit'] = true;
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    function validate_data()
    {

        return isset($this->p_car_num) && count($this->p_car_num) > 0 && $this->p_car_num[0] != ''
        && isset($this->p_branch_amount) && count($this->p_branch_amount) > 0 && $this->p_branch_amount[0] != ''
        && count($this->p_branch_amount) == count($this->p_car_num);
    }

    /**
     * edit action : update exists car data ..
     * receive post data of car
     * depended on car prm key
     */
    function edit()
    {


        if (!$this->validate_data()) {
            $this->print_error('تأكد من إدخال تفاصيل الطلب !');
            return;
        }
        $rs = $this->cars_additional_fuel_model->edit($this->_postedData(false));

        for ($i = 0; $i < count($this->p_ser); $i++) {
            if ($this->p_ser[$i] <= 0)
                $this->cars_additional_fuel_model->create_details($this->_postedData_Details(
                    $this->p_cars_additional_fuel_id,
                    $this->p_fuel_type[$i],
                    $this->p_car_num[$i],
                    $this->p_branch_amount[$i],
                    $this->p_hints[$i]
                ));
            else
                $this->cars_additional_fuel_model->edit_details($this->_postedData_Details(
                    $this->p_ser[$i],
                    $this->p_fuel_type[$i],
                    $this->p_car_num[$i],
                    $this->p_branch_amount[$i],
                    $this->p_hints[$i]
                ));
        }

        if (intval($rs) <= 0)
            $this->print_error('فشل في حفظ البيانات ' . $rs);

        echo $this->p_cars_additional_fuel_id;

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
                $msg = $this->cars_additional_fuel_model->delete($val);
            }
        } else {
            $msg = $this->cars_additional_fuel_model->delete($id);
        }

        if ($msg == 1) {
            echo modules::run('payment/cars_additional_fuel/get_page', 1);
        } else {

            $this->print_error_msg($msg);
        }
    }

    function _postedData($isCreate = true)
    {

        $result = array(
            array('name' => 'CARS_ADDITIONAL_FUEL_ID', 'value' => $this->p_cars_additional_fuel_id, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_ID', 'value' => $this->p_branch_id, 'type' => '', 'length' => -1),
            array('name' => 'DECLARATION', 'value' => $this->p_declaration, 'type' => '', 'length' => -1),


        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }


    function _postedData_Details($id, $fuel_type, $car_num, $branch_amount, $hinsts)
    {


        $result = array(
            array('name' => 'ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_NUM', 'value' => $car_num, 'type' => '', 'length' => -1),
            array('name' => 'FUEL_TYPE', 'value' => $fuel_type, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_AMOUNT', 'value' => $branch_amount, 'type' => '', 'length' => -1),
            array('name' => 'HINSTS', 'value' => $hinsts, 'type' => '', 'length' => -1),

        );

        return $result;
    }

    function public_get_cars_additional_fuel($id, $case = -1, $can_edit = false)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $data['details'] = $this->cars_additional_fuel_model->get_details_list($id);
        $data['fuel_type'] = $this->constant_details_model->get_list(58);
        $data['case'] = $case;
        $data['can_edit'] = $can_edit;

        $this->load->view('public_get_cars_additional_fuel_page', $data);
    }


    /*********************************** Adopts *********************************************/

    function adopt()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($this->p_cars_additional_fuel) && $this->p_cars_additional_fuel == 1) {

                $this->cars_additional_fuel_model->adopt($this->p_cars_additional_fuel_id, 0, 2, null, null);

            } else {
                $this->print_error('فشل في حفظ البيانات ');
            }

            echo $this->p_cars_additional_fuel_id;
        }
    }

    function adopt_fin($page = 1, $save = false)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_ser); $i++) {

                if ($this->p_financial_amount[$i] == '' || ($this->p_financial_note[$i] == '' && $this->p_financial_amount[$i] != $this->p_branch_amount[$i])) {
                    $this->print_error('يجب إدخال جميع البيانات');
                    break;
                }
                //if ($save) {
                $this->cars_additional_fuel_model->save($this->p_ser[$i], 3, $this->p_financial_amount[$i], $this->p_financial_note[$i]);
                // } else {
                $this->cars_additional_fuel_model->adopt($this->p_cars_additional_fuel_id, $this->p_ser[$i], 3, $this->p_financial_amount[$i], $this->p_financial_note[$i]);

                // }
            }


            echo $this->p_cars_additional_fuel_id;
        } else {

            $this->index($page, 'adopt_fin', 3);
        }
    }


    function adopt_branch($page = 1, $save = false)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_ser); $i++) {

                if ($this->p_branch_approve_amount[$i] == '' || ($this->p_manager_note[$i] == '' && $this->p_financial_amount[$i] != $this->p_branch_approve_amount[$i])) {
                    $this->print_error('يجب إدخال جميع البيانات');
                    break;
                }
                // if ($save) {
                $this->cars_additional_fuel_model->save($this->p_ser[$i], 4, $this->p_branch_approve_amount[$i], $this->p_manager_note[$i]);
                //  } else {
                $this->cars_additional_fuel_model->adopt($this->p_cars_additional_fuel_id, $this->p_ser[$i], 4, $this->p_branch_approve_amount[$i], $this->p_manager_note[$i]);

                // }
            }


            echo $this->p_cars_additional_fuel_id;
        } else {

            $this->index($page, 'adopt_branch', 4);
        }
    }


    function adopt_manager2($page = 1, $save = false)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_ser); $i++) {

                if ($this->p_manager2_amount[$i] == '' || ($this->p_manager2_note[$i] == '' && $this->p_branch_approve_amount[$i] != $this->p_manager2_amount[$i])) {
                    $this->print_error('يجب إدخال جميع البيانات');
                    break;
                }
                // if ($save) {
                $this->cars_additional_fuel_model->save($this->p_ser[$i], 5, $this->p_manager2_amount[$i], $this->p_manager2_note[$i]);
                //  } else {
                $this->cars_additional_fuel_model->adopt($this->p_cars_additional_fuel_id, $this->p_ser[$i], 5, $this->p_manager2_amount[$i], $this->p_manager2_note[$i]);

                //  }
            }


            echo $this->p_cars_additional_fuel_id;

        } else {

            $this->index($page, 'adopt_branch', 5);
        }
    }

    function adopt_manager3($page = 1, $save = false)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            for ($i = 0; $i < count($this->p_ser); $i++) {

                if ($this->p_manager3_amount[$i] == '' || ($this->p_manager4_note[$i] == '' && $this->p_manager2_amount[$i] != $this->p_manager3_amount[$i])) {
                    $this->print_error('يجب إدخال جميع البيانات');
                    break;
                }
                //  if ($save) {
                $this->cars_additional_fuel_model->save($this->p_ser[$i], 6, $this->p_manager3_amount[$i], $this->p_manager4_note[$i]);
                // } else {
                $this->cars_additional_fuel_model->adopt($this->p_cars_additional_fuel_id, $this->p_ser[$i], 6, $this->p_manager3_amount[$i], $this->p_manager4_note[$i]);

                // }
            }


            echo $this->p_cars_additional_fuel_id;
        } else {

            $this->index($page, 'adopt_branch', 6);
        }
    }

    function adopt_manager4($page = 1, $save = false)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_ser); $i++) {

                if ($this->p_manager4_amount[$i] == '' || ($this->p_manager5_note[$i] == '' && $this->p_manager3_amount[$i] != $this->p_manager4_amount[$i])) {
                    $this->print_error('يجب إدخال جميع البيانات');
                    break;
                }
                //  if ($save) {
                $this->cars_additional_fuel_model->save($this->p_ser[$i], 7, $this->p_manager4_amount[$i], $this->p_manager5_note[$i]);
                //  } else {
                $this->cars_additional_fuel_model->adopt($this->p_cars_additional_fuel_id, $this->p_ser[$i], 7, $this->p_manager4_amount[$i], $this->p_manager5_note[$i]);

                //  }
            }


            echo $this->p_cars_additional_fuel_id;

        } else {

            $this->index($page, 'adopt_branch', 7);
        }
    }



    function adopt_manager5($page = 1, $save = false)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_ser); $i++) {

                if ($this->p_manager5_amount[$i] == '' || ($this->p_manager6_note[$i] == '' && $this->p_manager4_amount[$i] != $this->p_manager5_amount[$i])) {
                    $this->print_error('يجب إدخال جميع البيانات');
                    break;
                }
                //  if ($save) {
                $this->cars_additional_fuel_model->save($this->p_ser[$i], 8, $this->p_manager5_amount[$i], $this->p_manager6_note[$i]);
                //  } else {
                $this->cars_additional_fuel_model->adopt($this->p_cars_additional_fuel_id, $this->p_ser[$i], 8, $this->p_manager5_amount[$i], $this->p_manager6_note[$i]);

                //  }
            }


            echo $this->p_cars_additional_fuel_id;

        } else {

            $this->index($page, 'adopt_branch', 8);
        }
    }
}
