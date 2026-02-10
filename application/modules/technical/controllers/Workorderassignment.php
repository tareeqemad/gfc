<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 03/08/15
 * Time: 08:32 ص
 */
class WorkOrderAssignment extends MY_Controller
{
    function  __construct()
    {
        parent::__construct();
        $this->load->model('WorkOrderAssignment_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }


    function _lookUps_data(&$data)
    {
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['WORK_ORDER_TYPE'] = $this->constant_details_model->get_list(105);
        $data['worker_jobs'] = $this->constant_details_model->get_list(86);
        $data['cars'] = $this->constant_details_model->get_list(87);
        $data['WORK_ORDER_DEPARTMENT'] = $this->constant_details_model->get_list(98);
        $data['help'] = $this->help;
        $this->_loadDatePicker();
    }

    /**
     *
     * index action perform all functions in view of WorkOrderAssignment_show view
     */
    function index($page = 1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo $this->WorkOrderAssignment_model->adopt($this->p_id, 1);
        } else {
            $data['title'] = 'إدارة تكليفات العمل ';
            $data['content'] = 'workorderassignment_index';
            $data['page'] = $page;

            $this->_loadDatePicker();

            $this->_lookUps_data($data);
            $data['case'] = -1;
            $data['action'] = 'index';

            $this->load->view('template/template', $data);
        }

    }

    function archive($page = 1)
    {

        $data['title'] = 'إدارة تكليفات العمل ';
        $data['content'] = 'workorderassignment_archive';
        $data['page'] = $page;
        $data['case'] = 1;
        $data['action'] = 'index';
        $this->_loadDatePicker();
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);

    }

    function adopt($page = 1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo $this->WorkOrderAssignment_model->adopt($this->p_id, 2);
        } else {
            $data['title'] = 'إدارة تكليفات العمل ';
            $data['content'] = 'workorderassignment_index';
            $data['page'] = $page;

            $this->_loadDatePicker();

            $this->_lookUps_data($data);
            $data['case'] = 1;
            $data['action'] = 'adopt';

            $this->load->view('template/template', $data);
        }

    }

    function feedbackRegisterAdopt($page = 1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_validateData();

            $this->feedback();

            echo $this->WorkOrderAssignment_model->adopt($this->p_work_order_assignment_id, 3);


        } else {
            $data['title'] = 'إدارة تكليفات العمل ';
            $data['content'] = 'workorderassignment_index';
            $data['page'] = $page;

            $this->_loadDatePicker();

            $this->_lookUps_data($data);
            $data['case'] = 2;
            $data['action'] = 'feedbackRegisterAdopt';

            $this->load->view('template/template', $data);
        }

    }

    function _validateData()
    {



        if (!isset($this->p_time_return) || $this->p_time_return == '') {
            $this->print_error('يجب إدخال تاريخ العودة');
        }

        if (isset($this->p_w_ser))
            for ($i = 0; $i < count($this->p_w_ser); $i++) {

                if ((!isset($this->p_action_end[$i]) || $this->p_action_end[$i] == '') && $this->p_not_done[$i] == 1) {
                    $this->print_error('يجب إدخال وقت النهاية لجميع أوامر العمل');
                }
            }


        if (isset($this->p_tl_ser))
            for ($i = 0; $i < count($this->p_tl_ser); $i++) {

                if (!isset($this->p_class_count_used[$i]) || $this->p_class_count_used[$i] == '' || floatval($this->p_class_count_used[$i]) < 0) {
                    $this->print_error('يجب إدخال الكمية المستخدمة للمواد ');
                }
            }

    }

    function feedbackadopt($page = 1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo $this->WorkOrderAssignment_model->adopt($this->p_id, 4);
        } else {
            $data['title'] = 'إدارة تكليفات العمل ';
            $data['content'] = 'workorderassignment_index';
            $data['page'] = $page;

            $this->_loadDatePicker();

            $this->_lookUps_data($data);
            $data['case'] = 3;
            $data['action'] = 'feedbackadopt';

            $this->load->view('template/template', $data);
        }

    }

    /**
     *
     * index action perform all functions in view of WorkOrderAssignment_show view
     */
    function permit_index($page = 1)
    {


        $data['title'] = 'إدارة تكليفات العمل ';
        $data['content'] = 'workorderassignment_index';
        $data['page'] = $page;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $data['action'] = 'permit';

        $this->load->view('template/template', $data);

    }

    function get_page($page = 1, $case = 1, $action = 'index')
    {

        $this->load->library('pagination');

        $case = isset($this->p_case) ? $this->p_case : $case;
        $action = isset($this->p_action) ? $this->p_action : $action;

        $sql = " AND (M.BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        $sql .= " AND (M.WORK_ORDER_CASE = {$case} or ({$case} = -1 and M.WORK_ORDER_CASE = 1) )";
        $sql .= isset($this->p_work_assignment_code) && $this->p_work_assignment_code != null ? " AND M.WORK_ASSIGNMENT_CODE like  UPPER('{$this->p_work_assignment_code}%') " : '';
        $sql .= isset($this->p_title) && $this->p_title != null ? " AND M.TITLE like  UPPER('%{$this->p_title}%') " : '';
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND BRANCH_ID ={$this->p_branch} " : '';

        $sql .= isset($this->p_x) && $this->p_x != null && isset($this->p_y) && $this->p_y != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID WHERE M.X between  {$this->p_x} -  (( 1 / 6371) *57.2957795)   and {$this->p_x} +  (( 1 / 6371) *57.2957795)  AND M.Y BETWEEN {$this->p_y} + ((1 / 6371 / cos( (( M.X)*57.2957795)))*57.2957795) AND {$this->p_y} - ((1 / 6371 / cos( (( M.X)*57.2957795)))*57.2957795) ) " : '';
        $sql .= isset($this->p_adapter_serial) && $this->p_adapter_serial != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB  M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID   JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID WHERE R.REQUEST_TYPE IN (1,2, 3, 5,8) AND M.SOURCE_ID = '{$this->p_adapter_serial}' ) " : '';
        $sql .= isset($this->p_work_order_type) && $this->p_work_order_type != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB  M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID  JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID WHERE R.REQUEST_TYPE = {$this->p_work_order_type}) " : '';

        $config['base_url'] = base_url("technical/workorderassignment/get_page/");

        $count_rs = $this->get_table_count(" WORK_ORDER_ASSIGNMENT_TB M WHERE 1=1 {$sql} ");


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

        $data["rows"] = $this->WorkOrderAssignment_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['action'] = $action;

        $this->load->view('workorderassignment_page', $data);

    }

    function get_page_archive($page = 1, $case = 1, $action = 'index')
    {

        $this->load->library('pagination');

        $case = isset($this->p_case) ? $this->p_case : $case;
        $action = isset($this->p_action) ? $this->p_action : $action;


        $sql = " AND (M.BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        $sql .= isset($this->p_work_assignment_code) && $this->p_work_assignment_code != null ? " AND M.WORK_ASSIGNMENT_CODE like  UPPER('{$this->p_work_assignment_code}%') " : '';
        $sql .= isset($this->p_title) && $this->p_title != null ? " AND M.TITLE like  UPPER('%{$this->p_title}%') " : '';
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND BRANCH_ID ={$this->p_branch} " : '';
        $sql .= isset($this->p_x) && $this->p_x != null && isset($this->p_y) && $this->p_y != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID WHERE M.X between  {$this->p_x} -  (( 1 / 6371) *57.2957795)   and {$this->p_x} +  (( 1 / 6371) *57.2957795)  AND M.Y BETWEEN {$this->p_y} + ((1 / 6371 / cos( (( M.X)*57.2957795)))*57.2957795) AND {$this->p_y} - ((1 / 6371 / cos( (( M.X)*57.2957795)))*57.2957795) ) " : '';
        $sql .= isset($this->p_adapter_serial) && $this->p_adapter_serial != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB  M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID   JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID WHERE R.REQUEST_TYPE IN (1,2, 3, 5,8) AND M.SOURCE_ID = '{$this->p_adapter_serial}' ) " : '';
        $sql .= isset($this->p_work_order_type) && $this->p_work_order_type != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB  M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID  JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID WHERE R.REQUEST_TYPE = {$this->p_work_order_type}) " : '';
        $sql .= isset($this->p_project_tec) && $this->p_project_tec != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB  M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID   JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID WHERE R.REQUEST_TYPE IN (4) AND LOWER(M.SOURCE_ID) = LOWER('{$this->p_project_tec}') )  " : '';
        $sql .= isset($this->p_project_name) && $this->p_project_name != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB  M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID   JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID WHERE R.REQUEST_TYPE IN (4) AND LOWER(PROJECTS_PKG.PROJECTS_FILE_NAME_BY_TEC_CODE(M.SOURCE_ID)) like LOWER('%{$this->p_project_name}%') )  " : '';
        $sql .= isset($this->p_request_code) && $this->p_request_code != null ? " AND WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB  M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID   JOIN REQUESTS_TB R ON M.REQUEST_ID = R.REQUEST_ID WHERE    LOWER(R.REQUEST_CODE) = LOWER('{$this->p_request_code}') )  " : '';

        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  TRUNC(ENTRY_DATE) >= '{$this->p_from_date}' " : "";

        $sql .= isset($this->p_task) && $this->p_task != null ? " AND  WORK_ORDER_ASSIGNMENT_ID IN (SELECT O.WORK_ORDER_ASSIGNMENT_ID FROM WORK_ORDER_TB  M JOIN WORK_ORDER_ASSIGNMENT_ORDER_TB O ON M.WORK_ORDER_ID = O.WORK_ORDER_ID WHERE TECHNICAL_PKG.TECHNICAL_JOBS_TB_NAME(M.JOB_ID) like '%{$this->p_task}%' or to_char(M.JOB_ID) = '{$this->p_task}' ) " : '';



        $config['base_url'] = base_url("technical/workorderassignment/get_page_archive/");

        $count_rs = $this->get_table_count(" WORK_ORDER_ASSIGNMENT_TB M WHERE 1=1 {$sql} ");


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

        $data["rows"] = $this->WorkOrderAssignment_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['action'] = $action;

        $this->load->view('workorderassignment_page', $data);

    }

    /**
     * create action : insert new WorkOrderAssignment data ..
     * receive post data of WorkOrderAssignment
     *
     */
    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->WorkOrderAssignment_model->create($this->_postedData());


            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            //Work Orders
            for ($i = 0; $i < count($this->p_w_ser); $i++) {
                if ($this->p_w_ser[$i] <= 0)
                    $this->WorkOrderAssignment_model->workOrder_create($this->_postedData_WorkOrder(
                        $result,
                        $this->p_work_order_id[$i],
                        $this->p_action_procedure[$i],
                        isset($this->p_action_start) ? $this->p_action_start[$i] : $this->p_time_out,
                        isset($this->p_action_end) ? $this->p_action_end[$i] : null,
                        $this->p_w_hints[$i],
                        true
                    ));
            }

            //Teams
            for ($i = 0; $i < count($this->p_t_ser); $i++) {
                if ($this->p_t_ser[$i] <= 0)
                    $this->WorkOrderAssignment_model->team_create($this->_postedData_Team(
                        $result,
                        $this->p_t_worker_job_id[$i],
                        $this->p_t_worker_job[$i],
                        $this->p_t_task[$i],
                        true
                    ));
            }


            //Tools

            for ($i = 0; $i < count($this->p_tl_ser); $i++) {
                if ($this->p_tl_ser[$i] <= 0)
                    $this->WorkOrderAssignment_model->tools_create($this->_postedData_Tools(
                        $result,
                        $this->p_class_id[$i],
                        $this->p_d_class_unit[$i],
                        $this->p_class_count[$i],
                        isset($this->p_class_count_used) ? $this->p_class_count_used[$i] : null,
                        $this->p_class_type[$i],
                        $this->p_tl_work_order[$i],
                        true
                    ));
            }

            //Cars

            for ($i = 0; $i < count($this->p_c_ser); $i++) {
                if ($this->p_c_ser[$i] <= 0)
                    $this->WorkOrderAssignment_model->cars_create($this->_postedData_Cars(
                        $result,
                        $this->p_car_id[$i],
                        $this->p_car_count[$i],
                        $this->p_need_description[$i],
                        true
                    ));
            }

            echo $result;

        } else {
            $data['content'] = 'workorderassignment_show';
            $data['title'] = 'إدارة تكليفات العمل ';
            $data['action'] = 'index';
            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }


    }

    /**
     * get work order assignment by id ..
     */
    function get($id, $action = 'index')
    {


        $result = $this->WorkOrderAssignment_model->get($id);


        $data['content'] = $action == 'feedbackregisteradopt' || $action == 'feedbackadopt' ? 'feedback_show' : 'workorderassignment_show';
        $data['title'] = 'إدارة تكليفات العمل ';

        $data['result'] = $result;

        $data['can_edit'] = count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id && $result[0]['WORK_ORDER_CASE'] == -1 && HaveAccess(base_url('technical/workorderassignment/edit'));

        $this->_lookUps_data($data, null);

        $data['action'] = $action;

        if ($action == 'feedbackRegisterAdopt' || $action == 'feedbackadopt') {
            $data['can_edit'] = count($result) > 0 && $result[0]['WORK_ORDER_CASE'] == 2 && HaveAccess(base_url('technical/workorderassignment/feedback'));

            $wid = count($result) > 0 ? $result[0]['WORK_ORDER_ASSIGNMENT_ID'] : 0;
            $data['details_tools'] = $this->WorkOrderAssignment_model->tools_list($wid);
            $data['details_teams'] = $this->WorkOrderAssignment_model->team_list($wid);
            $data['details_cars'] = $this->WorkOrderAssignment_model->cars_list($wid);
            $data['details_orders'] = $this->WorkOrderAssignment_model->workOrder_list($wid);
        }


        $data['TEAM_COST_ROWS'] = $this->WorkOrderAssignment_model->WORKER_ORDER_ASSIG_TEAM_COST($id);
        $data['TOOLS_COST_ROWS'] = $this->WorkOrderAssignment_model->WORK_ORDER_ASSIGNMENT_TOL_COST($id);
        $data['CARS_COST_ROWS'] = $this->WorkOrderAssignment_model->WORK_ORDER_ASSIGNMENT_CAR_COST($id);

        $data['RETURN_TOOLS'] = $this->WorkOrderAssignment_model->WORK_ORDER_RETURN_TOOLS_GET($id);

        $this->load->view('template/template', $data);
    }


    /**
     * get work order assignment by id ..
     */
    function feedback($id = 0, $action = 'index')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            if(( date_create_from_format('d/m/Y H:i',$this->p_time_out)  >  date_create_from_format('d/m/Y H:i',$this->p_time_return))    )
                $this->print_error('تاريخ العودة غير صحيح');

            $rs = $this->WorkOrderAssignment_model->FEED_BAK_WORK_ORDER_ASSIGNMENT($this->p_work_order_assignment_id, $this->p_time_return, $this->p_hints);

            //Work Orders
            if (isset($this->p_w_ser))
                for ($i = 0; $i < count($this->p_w_ser); $i++) {

                    if($this->p_action_end[$i] != '' && ( date_create_from_format('d/m/Y H:i',$this->p_time_out)  >  date_create_from_format('d/m/Y H:i',$this->p_action_end[$i]))    )
                        $this->print_error('تاريخ امر العمل غير صحيح');

                    $this->WorkOrderAssignment_model->FEED_BAK_ORDER_ASSIG_ORDER($this->p_w_ser[$i], $this->p_action_end[$i], $this->p_w_hints[$i], $this->p_source_id[$i]);
                }

            //tools
            if (isset($this->p_tl_ser))
                for ($i = 0; $i < count($this->p_tl_ser); $i++) {

                    $this->WorkOrderAssignment_model->FEED_BAK_ORDER_ASSIG_TOOLS($this->p_tl_ser[$i], $this->p_class_count_used[$i]);
                }

            if (isset($this->p_c_ser))
                for ($i = 0; $i < count($this->p_c_ser); $i++) {

                    $this->WorkOrderAssignment_model->WORK_ORDER_ASSIG_CARS_UP_TIME($this->p_c_ser[$i], $this->p_the_time_minute[$i]);
                }


            if (isset($this->p_r_ser))
                for ($i = 0; $i < count($this->p_r_ser); $i++) {

                    if ($this->p_r_ser[$i] == 0)
                        $this->WorkOrderAssignment_model->WORK_ORDER_RETURN_TOOLS_INSERT($this->p_work_order_assignment_id,$this->p_return_workorder[$i], $this->p_r_class_id[$i], $this->p_r_class_unit[$i], $this->p_r_class_count[$i], $this->p_r_class_type[$i], $this->p_notes[$i]);
                    else
                        $this->WorkOrderAssignment_model->WORK_ORDER_RETURN_TOOLS_UPDATE($this->p_r_ser[$i], $this->p_work_order_assignment_id,$this->p_return_workorder[$i], $this->p_r_class_id[$i], $this->p_r_class_unit[$i], $this->p_r_class_count[$i], $this->p_r_class_type[$i], $this->p_notes[$i]);
                }

            echo $rs;

        } else {

            $result = $this->WorkOrderAssignment_model->get($id);


            $data['content'] = 'feedback_show';
            $data['title'] = 'إدارة تكليفات العمل ';

            $data['result'] = $result;

            $data['can_edit'] = count($result) > 0 && $result[0]['WORK_ORDER_CASE'] == 2 && HaveAccess(base_url('technical/workorderassignment/feedback'));

            $this->_lookUps_data($data, null);

            $data['action'] = $action;

            $wid = count($result) > 0 ? $result[0]['WORK_ORDER_ASSIGNMENT_ID'] : 0;
            $data['details_tools'] = $this->WorkOrderAssignment_model->tools_list($wid);
            $data['details_teams'] = $this->WorkOrderAssignment_model->team_list($wid);
            $data['details_cars'] = $this->WorkOrderAssignment_model->cars_list($wid);
            $data['details_orders'] = $this->WorkOrderAssignment_model->workOrder_list($wid);
            $data['RETURN_TOOLS'] = $this->WorkOrderAssignment_model->WORK_ORDER_RETURN_TOOLS_GET($id);
            
            $this->load->view('template/template', $data);
        }
    }


    /**
     * edit action : update exists WorkOrderAssignment data ..
     * receive post data of WorkOrderAssignment
     * depended on WorkOrderAssignment prm key
     */
    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->WorkOrderAssignment_model->edit($this->_postedData(false));


            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            //Work Orders
            for ($i = 0; $i < count($this->p_w_ser); $i++) {
                if ($this->p_w_ser[$i] <= 0)
                    $this->WorkOrderAssignment_model->workOrder_create($this->_postedData_WorkOrder(
                        $this->p_work_order_assignment_id,
                        $this->p_work_order_id[$i],
                        $this->p_action_procedure[$i],
                        isset($this->p_action_start) ? $this->p_action_start[$i] : $this->p_time_out,
                        isset($this->p_action_end) ? $this->p_action_end[$i] : null,
                        $this->p_w_hints[$i],
                        true
                    ));
                else
                    $this->WorkOrderAssignment_model->workOrder_edit($this->_postedData_WorkOrder(
                        $result,
                        $this->p_work_order_id[$i],
                        $this->p_action_procedure[$i],
                        isset($this->p_action_start) ? $this->p_action_start[$i] : $this->p_time_out,
                        isset($this->p_action_end) ? $this->p_action_end[$i] : null,
                        $this->p_w_hints[$i],
                        false,
                        $this->p_w_ser[$i]
                    ));
            }

            //Teams
            for ($i = 0; $i < count($this->p_t_ser); $i++) {
                if ($this->p_t_ser[$i] <= 0)
                    $this->WorkOrderAssignment_model->team_create($this->_postedData_Team(
                        $this->p_work_order_assignment_id,
                        $this->p_t_worker_job_id[$i],
                        $this->p_t_worker_job[$i],
                        $this->p_t_task[$i],
                        true
                    ));
                else
                    $this->WorkOrderAssignment_model->team_edit($this->_postedData_Team(
                        $result,
                        $this->p_t_worker_job_id[$i],
                        $this->p_t_worker_job[$i],
                        $this->p_t_task[$i],
                        false,
                        $this->p_t_ser[$i]
                    ));
            }


            //Tools

            for ($i = 0; $i < count($this->p_tl_ser); $i++) {
                if ($this->p_tl_ser[$i] <= 0)
                    $this->WorkOrderAssignment_model->tools_create($this->_postedData_Tools(
                        $this->p_work_order_assignment_id,
                        $this->p_class_id[$i],
                        $this->p_d_class_unit[$i],
                        $this->p_class_count[$i],
                        isset($this->p_class_count_used) ? $this->p_class_count_used[$i] : null,
                        $this->p_class_type[$i],
                        $this->p_tl_work_order[$i],
                        true
                    ));
                else
                    $this->WorkOrderAssignment_model->tools_edit($this->_postedData_Tools(
                        $result,
                        $this->p_class_id[$i],
                        $this->p_d_class_unit[$i],
                        $this->p_class_count[$i],
                        isset($this->p_class_count_used) ? $this->p_class_count_used[$i] : null,
                        $this->p_class_type[$i],
                        $this->p_tl_work_order[$i],
                        false,
                        $this->p_tl_ser[$i]
                    ));
            }

            //Cars

            for ($i = 0; $i < count($this->p_c_ser); $i++) {
                if ($this->p_c_ser[$i] <= 0)
                    $this->WorkOrderAssignment_model->cars_create($this->_postedData_Cars(
                        $this->p_work_order_assignment_id,
                        $this->p_car_id[$i],
                        $this->p_car_count[$i],
                        $this->p_need_description[$i],
                        true
                    ));
                else
                    $this->WorkOrderAssignment_model->cars_edit($this->_postedData_Cars(
                        $result,
                        $this->p_car_id[$i],
                        $this->p_car_count[$i],
                        $this->p_need_description[$i],
                        false,
                        $this->p_c_ser[$i]
                    ));
            }

            echo $this->p_work_order_assignment_id;

        }
    }

    /**
     * edit action : update exists WorkOrderAssignment data ..
     * receive post data of WorkOrderAssignment
     * depended on WorkOrderAssignment prm key
     */
    function permit($id = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->WorkOrderAssignment_model->edit_permit($this->_postedData_permit(false));


            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            echo $result;

        } else {
            $result = $this->WorkOrderAssignment_model->get($id);


            $data['content'] = 'workorderassignment_show';
            $data['title'] = 'إدارة تكليفات العمل ';

            $data['result'] = $result;

            $data['can_edit'] = count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id && HaveAccess(base_url('technical/workorderassignment/edit'));

            $this->_lookUps_data($data, null);

            $data['action'] = 'permit';

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

        $p_time_return = isset($this->p_time_return) ? $this->p_time_return : null;

        $result = array(
            array('name' => 'WORK_ORDER_ASSIGNMENT_ID', 'value' => $this->p_work_order_assignment_id, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_DEPARTMENT', 'value' => $this->p_work_order_department, 'type' => '', 'length' => -1),
            array('name' => 'TEAM_ID', 'value' => $this->p_team_id, 'type' => '', 'length' => -1),
            array('name' => 'TIME_OUT', 'value' => $this->p_time_out, 'type' => '', 'length' => -1),
            array('name' => 'TIME_RETURN', 'value' => $p_time_return, 'type' => '', 'length' => -1),
            array('name' => 'MANAGER_EXPLAIN', 'value' => $this->p_manager_explain, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $this->p_hints, 'type' => '', 'length' => -1),
            array('name' => 'TITLE', 'value' => $this->p_title, 'type' => '', 'length' => -1),


        );


        if ($isCreate) {
            array_shift($result);
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

    function _postedData_Team($WorkOrderAssignment_id, $worker_job_id, $worker_job, $task, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_ASSIGNMENT_ID', 'value' => $WorkOrderAssignment_id, 'type' => '', 'length' => -1),
            array('name' => 'WORKER_JOB_ID', 'value' => $worker_job_id, 'type' => '', 'length' => -1),
            array('name' => 'WORKER_JOB', 'value' => $worker_job, 'type' => '', 'length' => -1),
            array('name' => 'TASK', 'value' => $task, 'type' => '', 'length' => -1),


        );

        if (!$isCreate) {
            unset($result[1]);
        } else {
            unset($result[0]);
        }


        return $result;
    }

    function _postedData_WorkOrder($WorkOrderAssignment_id, $work_order_id, $action_procedure, $action_start, $action_end, $w_hints, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_ASSIGNMENT_ID', 'value' => $WorkOrderAssignment_id, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_ID', 'value' => $work_order_id, 'type' => '', 'length' => -1),
            array('name' => 'ACTION_PROCEDURE', 'value' => $action_procedure, 'type' => '', 'length' => -1),
            array('name' => 'ACTION_START', 'value' => $action_start, 'type' => '', 'length' => -1),
            array('name' => 'ACTION_END', 'value' => $action_end, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $w_hints, 'type' => '', 'length' => -1),


        );

        if (!$isCreate) {
            unset($result[1]);
        } else {
            unset($result[0]);
        }

        return $result;
    }

    function _postedData_Tools($WorkOrderAssignment_id, $class_id, $class_unit, $class_count, $class_count_used, $class_type, $work_order_id, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_ASSIGNMENT_ID', 'value' => $WorkOrderAssignment_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_COUNT', 'value' => $class_count, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_COUNT_USED', 'value' => $class_count_used, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_TYPE', 'value' => $class_type, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_ID', 'value' => $work_order_id, 'type' => '', 'length' => -1),

        );


        if (!$isCreate) {
            unset($result[1]);
            unset($result[7]);
        } else {
            unset($result[0]);
        }


        return $result;
    }

    function _postedData_Cars($WorkOrderAssignment_id, $car_id, $car_count, $need_description, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'WorkOrderAssignment_ID', 'value' => $WorkOrderAssignment_id, 'type' => '', 'length' => -1),
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

        $data['details'] = $this->WorkOrderAssignment_model->tools_list($id);


        $this->load->view('workorderassignment_tools', $data);
    }

    function public_get_team($id)
    {

        $data['details'] = $this->WorkOrderAssignment_model->team_list($id);

        $this->load->view('workorderassignment_team', $data);
    }


    function public_get_cars($id)
    {

        $data['details'] = $this->WorkOrderAssignment_model->cars_list($id);

        $this->load->view('workorderassignment_cars', $data);
    }

    function public_get_WOrder($id)
    {

        $data['details'] = $this->WorkOrderAssignment_model->workOrder_list($id);

        $this->load->view('workorderassignment_workorder', $data);
    }

    function delete_WOrder()
    {
        echo $this->WorkOrderAssignment_model->delete_WOrder($this->p_id);
    }

    function delete_tools()
    {
        echo $this->WorkOrderAssignment_model->delete_tools($this->p_id);
    }

    function delete_team()
    {
        echo $this->WorkOrderAssignment_model->delete_works($this->p_id);
    }

    function delete_cars()
    {
        echo $this->WorkOrderAssignment_model->delete_cars($this->p_id);
    }

    function delete_return_item()
    {
        echo $this->WorkOrderAssignment_model->WORK_ORDER_RETURN_TOOLS_DELETE($this->p_id);
    }

    function cancel()
    {
        echo $this->WorkOrderAssignment_model->adopt($this->p_id, 0);
    }

    function public_return()
    {
        echo $this->WorkOrderAssignment_model->adopt($this->p_id, -1);
    }

    function public_return_feedback()
    {
        echo $this->WorkOrderAssignment_model->adopt($this->p_id, 2);
    }

}