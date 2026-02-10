<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 03/08/15
 * Time: 08:32 ص
 */
class WorkOrder extends MY_Controller
{
    function  __construct()
    {
        parent::__construct();
        $this->load->model('WorkOrder_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('Requests_model');
        $this->load->model('technical_jobs_plane_model');
        $this->source_id = $this->input->post('source_id');

    }

    function _lookUps_data(&$data)
    {
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['WORK_ORDER_TYPE'] = $this->constant_details_model->get_list(105);
        $data['worker_jobs'] = $this->constant_details_model->get_list(86);
        $data['cars'] = $this->constant_details_model->get_list(87);

        $data['help'] = $this->help;
        $this->_loadDatePicker();
    }

    /**
     *
     * index action perform all functions in view of WorkOrder_show view
     */
    function index($page = 1, $action = 'get', $source_id = -1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo $this->WorkOrder_model->adopt($this->p_id, 1);
        } else {
            $data['title'] = 'إدارة أوامر العمل ';
            $data['content'] = 'workorder_index';
            $data['page'] = $page;
            $data['source_id'] = $source_id;

            $this->_loadDatePicker();

            $this->_lookUps_data($data);

            $data['action'] = 'get';

            $this->load->view('template/template', $data);
        }

    }

    function public_index($txt = null, $page = 1)
    {


        $data['title'] = 'إدارة أوامر العمل ';
        $data['content'] = 'workorder_public_index';
        $data['page'] = $page;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $data['action'] = 'get';
        $data['txt'] = $txt;

        $this->load->view('template/view', $data);

    }

    function public_get_page($txt = null, $page = 1, $action = 'get')
    {

        $this->load->library('pagination');


        $sql = " AND (M.BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        $sql .= isset($this->p_work_order_type) && $this->p_work_order_type != null ? " AND R.REQUEST_TYPE = {$this->p_work_order_type} " : '';
        $sql .= isset($this->p_workorder_title) && $this->p_workorder_title != null ? " AND M.WORKORDER_TITLE like '%{$this->p_workorder_title}%'   " : '';
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  TRUNC(WORD_ORDER_DATE) >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  TRUNC(WORD_ORDER_DATE) <= '{$this->p_to_date}' " : "";

        $sql .= isset($this->p_work_order_code) && $this->p_work_order_code != null ? " AND M.work_order_code like  UPPER('{$this->p_work_order_code}%') " : '';

        $sql .= " AND M.WORK_ORDER_CASE != -1 AND M.WORK_ORDER_CASE < 3 ";
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND M.BRANCH_ID ={$this->p_branch} " : '';


        $config['base_url'] = base_url("technical/workorder/public_get_page/$txt");


        $count_rs = $this->get_table_count(' WORK_ORDER_TB M JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID  WHERE 1=1 ' . $sql);


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

        $data["rows"] = $this->WorkOrder_model->get_list($sql, $offset, $row);

        $data['txt'] = $txt;

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['action'] = $action;

        $this->load->view('workorder_public_page', $data);

    }

    /**
     *
     * index action perform all functions in view of WorkOrder_show view
     */
    function permit_index($page = 1, $source_id = -1)
    {


        $data['title'] = 'إدارة أوامر العمل ';
        $data['content'] = 'workorder_index';
        $data['page'] = $page;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $data['action'] = 'permit';

        $data['source_id'] = $source_id;

        $this->load->view('template/template', $data);

    }

    function get_page($page = 1, $action = 'get', $source_id = -1)
    {

        $this->load->library('pagination');

        $source_id = $this->check_vars($source_id, 'source_id');

        $sql = " AND (M.BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        $sql .= isset($this->p_work_order_type) && $this->p_work_order_type != null ? " AND R.REQUEST_TYPE = {$this->p_work_order_type} " : '';
        $sql .= isset($this->p_workorder_title) && $this->p_workorder_title != null ? " AND M.WORKORDER_TITLE like '%{$this->p_workorder_title}%'   " : '';
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  TRUNC(WORD_ORDER_DATE) >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  TRUNC(WORD_ORDER_DATE) <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_task) && $this->p_task != null ? " AND (TECHNICAL_PKG.TECHNICAL_JOBS_TB_NAME(M.JOB_ID) like '%{$this->p_task}%' or to_char(M.JOB_ID) = '{$this->p_task}' ) " : '';
        $sql .= isset($this->p_work_order_code) && $this->p_work_order_code != null ? " AND M.work_order_code like  UPPER('{$this->p_work_order_code}%') " : '';
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND M.BRANCH_ID ={$this->p_branch} " : '';

        $sql .= ($source_id != null) ? "  and ( source_id= '{$source_id}' AND R.REQUEST_TYPE in (2,3,4,5) ) " : '';

        $sql .= $action == 'get' ? "" : " AND WORK_PERMIT IS NULL ";

        $sql .= isset($this->p_work_code) && $this->p_work_code != null ? " AND M.WORK_ORDER_CODE = '{$this->p_work_code}' " : '';


        $config['base_url'] = base_url("technical/workorder/get_page/");


        $count_rs = $this->get_table_count(' WORK_ORDER_TB M JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID  WHERE 1=1 ' . $sql);


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

        $data["rows"] = $this->WorkOrder_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['action'] = $action;

        $this->load->view('workorder_page', $data);

    }



    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = (isset($this->{$c_var}) and $this->{$c_var} != null) ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    /**
     * create action : insert new WorkOrder data ..
     * receive post data of WorkOrder
     *
     */
    function create($request = 0)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_validateData();

            $result = $this->WorkOrder_model->create($this->_postedData());

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }

            for ($i = 0; $i < count($this->p_w_ser); $i++) {
                if ($this->p_w_ser[$i] <= 0)
                    $this->WorkOrder_model->works_create($this->_postedData_Team(
                        $result,
                        $this->p_w_worker_job_id[$i],
                        $this->p_w_worker_count[$i],
                        $this->p_w_task[$i],


                        true
                    ));
            }
            ///********************************************

            if (isset($this->p_class_id))
                for ($i = 0; $i < count($this->p_class_id); $i++) {
                    if ($this->p_t_ser[$i] <= 0)
                         $this->WorkOrder_model->tools_create($this->_postedData_Tools(
                            $result,
                            $this->p_class_id[$i],
                            $this->p_d_class_unit[$i],
                            $this->p_class_count[$i],
                            $this->p_class_type[$i],
                            true
                        ));

                }


            if (isset($this->p_car_id))
                for ($i = 0; $i < count($this->p_car_id); $i++) {
                    if ($this->p_c_ser[$i] <= 0)
                        $this->WorkOrder_model->cars_create($this->_postedData_Cars(
                            $result,
                            $this->p_car_id[$i],
                            $this->p_car_count[$i],
                            $this->p_need_description[$i],
                            true
                        ));

                }


            echo $result;

        } else {
            $data['content'] = 'workorder_show';
            $data['title'] = 'إدارة أوامر العمل';
            $data['action'] = 'index';
            $data['request_data'] = $request != null ? $this->Requests_model->get($request) : null;


            if ($data['request_data'] == null)
                redirect('technical/workorder');

            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }


    }

    /**
     * get project by id ..
     */
    function get($id)
    {


        $result = $this->WorkOrder_model->get($id);


        $data['content'] = 'workorder_show';
        $data['title'] = 'إدارة أوامر العمل';

        $data['result'] = $result;

        $data['can_edit'] = count($result) > 0 && $result[0]['WORK_ORDER_CASE'] == -1 && $result[0]['ENTRY_USER'] == $this->user->id && HaveAccess(base_url('technical/workorder/edit'));

        $this->_lookUps_data($data, null);

        $data['action'] = 'create';

        $data['plans'] = $this->technical_jobs_plane_model->get_list((count($result) > 0 ? $result[0]['JOB_ID'] : 0));

        $data['TEAM_COST_ROWS'] = $this->WorkOrder_model->WORK_ORDER_JOB_COST_GET($id);
        $data['TOOLS_COST_ROWS'] = $this->WorkOrder_model->WORK_ORDER_TOOL_COST_GET($id);
        $data['CARS_COST_ROWS'] = $this->WorkOrder_model->WORK_ORDER_CAR_COST_GET($id);

        $data['request_data'] = null;

        $this->load->view('template/template', $data);
    }

    /**
     * edit action : update exists WorkOrder data ..
     * receive post data of WorkOrder
     * depended on WorkOrder prm key
     */
    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_validateData();

            $result = $this->WorkOrder_model->edit($this->_postedData(false));


            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            for ($i = 0; $i < count($this->p_w_ser); $i++) {
                if ($this->p_w_ser[$i] <= 0)
                     $this->WorkOrder_model->works_create($this->_postedData_Team(
                        $this->p_work_order_id,
                        $this->p_w_worker_job_id[$i],
                        $this->p_w_worker_count[$i],
                        $this->p_w_task[$i],


                        true
                    ));
                else
                    $this->WorkOrder_model->works_edit($this->_postedData_Team(
                        $this->p_work_order_id,
                        $this->p_w_worker_job_id[$i],
                        $this->p_w_worker_count[$i],
                        $this->p_w_task[$i],


                        false,
                        $this->p_w_ser[$i]
                    ));
            }
            ///********************************************


            for ($i = 0; $i < count($this->p_class_id); $i++) {
                if ($this->p_t_ser[$i] <= 0)
                     $this->WorkOrder_model->tools_create($this->_postedData_Tools(
                        $this->p_work_order_id,
                        $this->p_class_id[$i],
                        $this->p_d_class_unit[$i],
                        $this->p_class_count[$i],
                        $this->p_class_type[$i],
                        true
                    ));
                else
                    $this->WorkOrder_model->tools_edit($this->_postedData_Tools(
                        $this->p_work_order_id,
                        $this->p_class_id[$i],
                        $this->p_d_class_unit[$i],
                        $this->p_class_count[$i],
                        $this->p_class_type[$i],
                        false,
                        $this->p_t_ser[$i]
                    ));
            }


            for ($i = 0; $i < count($this->p_car_id); $i++) {
                if ($this->p_c_ser[$i] <= 0)
                    $this->WorkOrder_model->cars_create($this->_postedData_Cars(
                        $this->p_work_order_id,
                        $this->p_car_id[$i],
                        $this->p_car_count[$i],
                        $this->p_need_description[$i],
                        true
                    ));
                else
                    $this->WorkOrder_model->cars_edit($this->_postedData_Cars(
                        $this->p_work_order_id,
                        $this->p_car_id[$i],
                        $this->p_car_count[$i],
                        $this->p_need_description[$i],
                        false,
                        $this->p_c_ser[$i]
                    ));
            }


            echo $this->p_work_order_id;

        }
    }

    /**
     * edit action : update exists WorkOrder data ..
     * receive post data of WorkOrder
     * depended on WorkOrder prm key
     */
    function permit($id = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->WorkOrder_model->edit_permit($this->_postedData_permit(false));


            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            echo $this->p_work_order_id;

        } else {
            $result = $this->WorkOrder_model->get($id);


            $data['content'] = 'workorder_show';
            $data['title'] = 'إدارة أوامر العمل';

            $data['result'] = $result;

            $data['can_edit'] = count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id && HaveAccess(base_url('technical/workorder/edit'));

            $data['plans'] = $this->technical_jobs_plane_model->get_list((count($result) > 0 ? $result[0]['JOB_ID'] : 0));

            $this->_lookUps_data($data, null);

            $data['action'] = 'permit';
            $data['request_data'] = NULL;
            $this->load->view('template/template', $data);
        }
    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData($isCreate = true)
    {


        $p_cycle_case = isset($this->p_cycle_case) ? $this->p_cycle_case : null;
        $p_cycle_type = isset($this->p_cycle_type) ? $this->p_cycle_type : null;
        $p_alarm_date_count = isset($this->p_alarm_date_count) ? $this->p_alarm_date_count : null;
        $source_id = isset($this->p_source_id) ? $this->p_source_id : null;

        $result = array(
            array('name' => 'WORK_ORDER_ID', 'value' => $this->p_work_order_id, 'type' => '', 'length' => -1),

            array('name' => 'CYCLE_CASE', 'value' => $p_cycle_case, 'type' => '', 'length' => -1),
            array('name' => 'CYCLE_TYPE', 'value' => $p_cycle_type, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_START_DATE', 'value' => $this->p_work_order_start_date, 'type' => '', 'length' => -1),

            array('name' => 'WORK_ORDER_END_DATE', 'value' => $this->p_work_order_end_date, 'type' => '', 'length' => -1),
            array('name' => 'ALARM_DATE_COUNT', 'value' => $p_alarm_date_count, 'type' => '', 'length' => -1),
            array('name' => 'JOB_ID', 'value' => $this->p_job_id, 'type' => '', 'length' => -1),
            array('name' => 'JOB_TIME_EXPECTED', 'value' => $this->p_job_time_expected, 'type' => '', 'length' => -1),
            array('name' => 'INSTRUCTIONS', 'value' => $this->p_instructions, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $this->p_hints, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_ID', 'value' => $this->p_request_id, 'type' => '', 'length' => -1),
            array('name' => 'WORKORDER_TITLE', 'value' => $this->p_workorder_title, 'type' => '', 'length' => -1),
            array('name' => 'SOURCE_ID', 'value' => $source_id, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_EXCHANGE_IN', 'value' => $this->p_request_exchange, 'type' => '', 'length' => -1),
            array('name' => 'JOB_COUNT', 'value' => $this->p_job_count, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_WORK_TYPE', 'value' => $this->p_project_work_type, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACTOR_NO', 'value' => $this->p_contractor_no, 'type' => '', 'length' => -1),

        );


        if ($isCreate) {
            array_shift($result);

        } else {
            unset($result[10]);
        }

        return $result;
    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData_permit($isCreate = true)
    {

        $result = array(
            array('name' => 'WORK_ORDER_ID', 'value' => $this->p_work_order_id, 'type' => '', 'length' => -1),
            array('name' => 'WORK_PERMIT', 'value' => $this->p_work_permit, 'type' => '', 'length' => -1),
            array('name' => 'WORK_PERMIT_CONDITIONS', 'value' => $this->p_work_permit_conditions, 'type' => '', 'length' => -1),


        );

        if ($isCreate) {
            array_shift($result);
        }


        return $result;
    }

    function _postedData_Team($WorkOrder_id, $worker_job_id, $worker_count, $task, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_ID', 'value' => $WorkOrder_id, 'type' => '', 'length' => -1),
            array('name' => 'WORKER_JOB_ID', 'value' => $worker_job_id, 'type' => '', 'length' => -1),
            array('name' => 'WORKER_COUNT', 'value' => $worker_count, 'type' => '', 'length' => -1),
            array('name' => 'TASK', 'value' => $task, 'type' => '', 'length' => -1),


        );

        if (!$isCreate) {
            unset($result[1]);
        } else {
            unset($result[0]);
        }

        return $result;
    }

    function _postedData_Tools($WorkOrder_id, $class_id, $class_unit, $class_count, $class_type, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'WorkOrder_ID', 'value' => $WorkOrder_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_COUNT', 'value' => $class_count, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_TYPE', 'value' => $class_type, 'type' => '', 'length' => -1),

        );


        if (!$isCreate) {
            unset($result[1]);
        } else {
            unset($result[0]);
        }


        return $result;
    }

    function _postedData_Cars($WorkOrder_id, $car_id, $car_count, $need_description, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'WorkOrder_ID', 'value' => $WorkOrder_id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_ID', 'value' => $car_id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_COUNT', 'value' => $car_count, 'type' => '', 'length' => -1),
            array('name' => 'NEED_DESCRIPTION', 'value' => $need_description, 'type' => '', 'length' => -1),


        );


        if (!$isCreate) {
            unset($result[1]);
        } else {
            unset($result[0]);
        }
        return $result;
    }

    function public_get_tools($id)
    {

        $data['details'] = $this->WorkOrder_model->tools_list($id);


        $this->load->view('workorder_tools', $data);
    }

    function public_get_request_tools($id)
    {

        $data['details'] = $this->Requests_model->tools_list($id);


        $this->load->view('workorder_requests_tools', $data);
    }


    function public_get_request_team($id)
    {

        $data['details'] = $this->Requests_model->requests_team_tb_count_workjob($id);


        $this->load->view('workorder_requests_team', $data);
    }

    function public_get_team($id)
    {

        $data['details'] = $this->WorkOrder_model->team_list($id);

        $this->load->view('workorder_team', $data);
    }

    function public_get_cars($id)
    {

        $data['details'] = $this->WorkOrder_model->cars_list($id);

        $this->load->view('workorder_cars', $data);
    }

    function public_get_workorder_data_json()
    {

        $details_1 = array();
        $details_2 = array();
        $details_3 = array();
        $details_4 = array();

        foreach ($this->p_id as $id) {
            foreach ($this->WorkOrder_model->tools_list($id) as $row1)
                array_push($details_1, $row1);

            foreach ($this->WorkOrder_model->request_team_list($id) as $row2)
                array_push($details_2, $row2);

            foreach ($this->WorkOrder_model->cars_list($id) as $row2)
                array_push($details_3, $row2);

            foreach ($this->WorkOrder_model->WORK_ORDER_TB_GET_JOB($id) as $row3)
                array_push($details_4, array("id" => $id, "PLANE_STEP" => $row3['PLANE_STEP']));
        }


        //$all = array('TOOLS' => unique_multidim_array($details_1, 'CLASS_ID'), 'TEAMS' => ($details_2), 'CARS' => unique_multidim_array($details_3, 'CAR_ID'), 'JOBS' => ($details_4));

        $all = array('TOOLS' => $details_1, 'TEAMS' => ($details_2), 'CARS' => $details_3, 'JOBS' => ($details_4));

        $this->return_json($all);
    }

    function delete_tools()
    {
        echo $this->WorkOrder_model->delete_tools($this->p_id);
    }

    function delete_team()
    {
        echo $this->WorkOrder_model->delete_works($this->p_id);
    }

    function delete_cars()
    {
        echo $this->WorkOrder_model->delete_cars($this->p_id);
    }

    function cancel()
    {
        echo $this->WorkOrder_model->adopt($this->p_id, 0);
    }

    function _validateData()
    {


        if(( date_create_from_format('d/m/Y H:i',$this->p_work_order_start_date)  >  date_create_from_format('d/m/Y H:i',$this->p_work_order_end_date))    )
            $this->print_error('يجب مراجعة التاريخ ');


        if (!isset($this->p_source_id) || $this->p_source_id == '') {
            $this->print_error('يجب إدخال المحول');
        }

        if (!isset($this->p_w_ser) || count($this->p_w_ser) < 0) {
            $this->print_error('يجب تحديد فرق العمل المطلوب ');


        } else {
            for ($i = 0; $i < count($this->p_w_ser); $i++) {
                if (!isset($this->p_w_worker_count[$i]) || $this->p_w_worker_count[$i] == '') {
                    $this->print_error('يجب تحديد فرق العمل المطلوب ');
                }

            }
        }

        if (isset($this->p_car_id)) {
            for ($i = 0; $i < count($this->p_car_id); $i++) {


                if (!isset($this->p_need_description[$i]) || $this->p_need_description[$i] == '') {
                    $this->print_error('يجب إخال وصف احتياج الاليات');
                }
            }
        }

        if (isset($this->p_class_id)) {
            for ($i = 0; $i < count($this->p_class_id); $i++) {


                if ((!isset($this->p_class_count[$i]) || $this->p_class_count[$i] == '') && count($this->p_class_id) > 1) {
                    $this->print_error('يجب إدخال العدد المطلوب من المواد');
                }
            }
        }
    }
}