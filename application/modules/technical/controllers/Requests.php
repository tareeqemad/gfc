<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 03/08/15
 * Time: 08:32 ص
 */
class Requests extends MY_Controller
{
    function  __construct()
    {
        parent::__construct();
        $this->load->model('Requests_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        die('يتم العمل من خلال النظام الفني الجديد | الادارة الفنية ');
    }


    function _lookUps_data(&$data)
    {
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['REQUESTS_TYPE'] = $this->constant_details_model->get_list(105);
        $data['worker_jobs'] = $this->constant_details_model->get_list(86);
        $data['cars'] = $this->constant_details_model->get_list(87);
        $data['SUBSCRIBERS_TYPES'] = $this->constant_details_model->get_list(141);
        $data['PHASE_TYPE'] = $this->constant_details_model->get_list(140);
        $data['important_degree'] = $this->constant_details_model->get_list(157);

        $data['IS_MAIN_BRANCH'] = $this->user->branch == 1;

        $data['help'] = $this->help;
        $this->_loadDatePicker();
    }

    /**
     *
     * index action perform all functions in view of Requests_show view
     */
    function index($page = 1, $type = null)
    {


        $data['title'] = 'إدارة الطلبات الفنية ';
        $data['content'] = 'requests_index';
        $data['page'] = $page;

        $this->_loadDatePicker();
        $this->_lookUps_data($data);

        $data['action'] = $this->action;
        $data['type'] = $type;


        $this->load->view('template/template', $data);

    }

    function archive($page = 1)
    {


        $data['title'] = 'إدارة الطلبات الفنية ';
        $data['content'] = 'requests_index';
        $data['page'] = $page;

        $this->_loadDatePicker();
        $this->_lookUps_data($data);

        $data['action'] = 'index';
        $data['type'] = null;
        $data['isArchive'] = true;

        $this->load->view('template/template', $data);

    }

    function PlaningRequests()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->Requests_model->create_request($this->p_id);

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }
            echo $result;

        } else {

            $data['title'] = 'طلبات الخدمات';
            $data['content'] = 'planingrequests_index';

            $this->load->view('template/template', $data);
        }
    }


    function Citizen_complaints($page = 1)
    {

        $this->index($page, 1);
    }

    function Corrective_maintenance($page = 1)
    {
        $this->index($page, 2);
    }

    function turn_Corrective_maintenance()
    {


        $result = $this->Requests_model->change_type($this->p_id, 2);

        if (intval($result) <= 0) {
            $this->print_error('فشل في حفظ البيانات');
        }
        echo $result;

    }

    function convert_workOrder()
    {

        redirect("technical/WorkOrder/create/{$this->p_id}");

    }

    function feedback()
    {


        $result = $this->Requests_model->feadback($this->p_id, $this->p_notes);

        if (intval($result) <= 0) {
            $this->print_error('فشل في حفظ البيانات');
        }
        echo $result;

    }

    function Preventive_maintenance($page = 1)
    {
        $this->index($page, 3);
    }

    function project_exection($page = 1)
    {
        $this->index($page, 4);
    }

    function Measuring_loads($page = 1)
    {
        $this->index($page, 5);
    }

    function equ_maintenance($page = 1)
    {
        $this->index($page, 6);
    }

    function pc_maintenance($page = 1)
    {
        $this->index($page, 7);
    }

    function plan($page = 1)
    {
        $this->index($page, 8);
    }


    function Infringements($page = 1)
    {
        $this->index($page, 9);
    }

    function public_index($txt = null, $type = null, $page = 1)
    {


        $data['title'] = 'إدارة الطلبات الفنية ';
        $data['content'] = 'requests_public_index';
        $data['page'] = $page;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $data['action'] = 'get';
        $data['txt'] = $txt;
        $data['type'] = $type;

        $this->load->view('template/view', $data);

    }

    /**
     *
     * index action perform all functions in view of Requests_show view
     */
    function permit_index($page = 1)
    {


        $data['title'] = 'إدارة الطلبات الفنية ';
        $data['content'] = 'requests_index';
        $data['page'] = $page;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $data['action'] = 'permit';

        $this->load->view('template/template', $data);

    }

    function get_page($page = 1, $action = 'get', $type = null)
    {

        $this->load->library('pagination');

        $type = isset($this->p_type) && $this->p_type != null ? $this->p_type : (isset($this->p_requests_type) ? $this->p_requests_type : $type);

        $sql = $type != null ? " AND REQUEST_TYPE ={$type}  " : '';
        $sql .= ' AND REQUEST_CASE <= 1 ';
        $sql .= " AND (BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  TRUNC(REQUEST_DATE) >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  TRUNC(REQUEST_DATE) <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_citizen_name) && $this->p_citizen_name != null ? " AND  CITIZEN_NAME like '%{$this->p_citizen_name}%' " : "";
        $sql .= isset($this->p_purpose_description) && $this->p_purpose_description != null ? " AND  PURPOSE_DESCRIPTION like '%{$this->p_purpose_description}%' " : "";
        $sql .= isset($this->p_request_code) && $this->p_request_code != null ? " AND M.request_code like UPPER('{$this->p_request_code}%') " : '';
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND BRANCH_ID ={$this->p_branch} " : '';
        $sql .= isset($this->p_user_name) && $this->p_user_name != null ? " AND USER_PKG.GET_USER_NAME(M.USER_ENTRY) like '%{$this->p_user_name}%' " : '';

        $config['base_url'] = base_url("technical/requests/get_page/");


        $count_rs = $this->get_table_count(' REQUESTS_TB M WHERE 1 = 1 ' . $sql);


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

        $data["rows"] = $this->Requests_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['action'] = $action;

        $this->load->view('requests_page', $data);

    }

    function get_page_archive($page = 1, $action = 'get', $type = null)
    {

        $this->load->library('pagination');

        $type = (isset($this->p_requests_type) ? $this->p_requests_type : $type);

        $sql = $type != null ? " AND REQUEST_TYPE ={$type}  " : '';
        if ($this->user->branch == 8){
            $sql .= " AND (BRANCH_ID = 2) ";
        }else {
            $sql .= " AND (BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        }
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  TRUNC(REQUEST_DATE) >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  TRUNC(REQUEST_DATE) <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_citizen_name) && $this->p_citizen_name != null ? " AND  CITIZEN_NAME like '%{$this->p_citizen_name}%' " : "";
        $sql .= isset($this->p_purpose_description) && $this->p_purpose_description != null ? " AND  PURPOSE_DESCRIPTION like '%{$this->p_purpose_description}%' " : "";
        $sql .= isset($this->p_request_code) && $this->p_request_code != null ? " AND M.request_code like UPPER('{$this->p_request_code}%') " : '';
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND BRANCH_ID ={$this->p_branch} " : '';
        $sql .= isset($this->p_user_name) && $this->p_user_name != null ? " AND USER_PKG.GET_USER_NAME(M.USER_ENTRY) like '%{$this->p_user_name}%' " : '';

        $config['base_url'] = base_url("technical/requests/get_page_archive/");

        $count_rs = $this->get_table_count(' REQUESTS_TB M where 1=1 ' . $sql);

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

        $data["rows"] = $this->Requests_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['action'] = $action;

        $this->load->view('Requests_page', $data);

    }
	
    function public_get_page($txt = null, $page = 1, $type = null)
    {

        $this->load->library('pagination');

        $type = (isset($this->p_type) ? $this->p_type : $type);

        $sql = $type != null ? " AND REQUEST_TYPE ={$type} AND REQUEST_ID NOT IN (SELECT NVL(REQUEST_ID,0) FROM PROJECTS_FILE_TB) AND REQUEST_ID IN (SELECT REQUEST_ID FROM WORK_ORDER_ASSIGNMENT_VIEW WHERE WORK_ORDER_CASE >= 4 AND REQUEST_TYPE ={$type}) " : '';
        $sql .= " AND (BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  TRUNC(REQUEST_DATE) >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  TRUNC(REQUEST_DATE) <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_citizen_name) && $this->p_citizen_name != null ? " AND  CITIZEN_NAME like '%{$this->p_citizen_name}%' " : "";
        $sql .= isset($this->p_purpose_description) && $this->p_purpose_description != null ? " AND  PURPOSE_DESCRIPTION like '%{$this->p_purpose_description}%' " : "";
        $sql .= isset($this->p_request_code) && $this->p_request_code != null ? " AND M.request_code like UPPER('{$this->p_request_code}%') " : '';
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND BRANCH_ID ={$this->p_branch} " : '';

        $config['base_url'] = base_url("technical/requests/public_get_page/");

        $count_rs = $this->get_table_count(' REQUESTS_TB M where 1=1 ' . $sql);

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

        $data["rows"] = $this->Requests_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['type'] = $type;

        $this->load->view('requests_public_page', $data);

    }

    /**
     * create action : insert new Requests data ..
     * receive post data of Requests
     *
     */
    function create($type = -1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_validateData();
            $result = $this->Requests_model->create($this->_postedData());

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            echo $result;

        } else {
            $data['content'] = 'requests_show';
            $data['title'] = 'إدارة الطلبات الفنية';
            $data['action'] = 'index';
            $data['type'] = $type;
            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }


    }

    function _validateData()
    {

        if ($this->p_requests_type == 4 && (!isset($this->p_project_serial) || $this->p_project_serial == '')) {
            $this->print_error('يجب إدخال رقم المشروع الفني');
        }
    }

    /**
     * get project by id ..
     */
    function get($id, $action = 'create')
    {


        $result = $this->Requests_model->get($id);


        $data['content'] = 'requests_show';
        $data['title'] = 'إدارة الطلبات الفنية';

        $data['result'] = $result;

        $data['can_edit'] = count($result) > 0 && $result[0]['REQUEST_CASE'] == 1 && $result[0]['USER_ENTRY'] == $this->user->id && HaveAccess(base_url('technical/requests/edit'));

        $this->_lookUps_data($data, null);

        $data['action'] = HaveAccess(base_url('technical/requests/feedback_workorder')) ? 'feedback_workorder' : $action;

        $this->load->view('template/template', $data);
    }

    /**
     * edit action : update exists Requests data ..
     * receive post data of Requests
     * depended on Requests prm key
     */
    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->Requests_model->edit($this->_postedData(false));


            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            echo $result;

        }
    }

    /**
     * edit action : update exists Requests data ..
     * receive post data of Requests
     * depended on Requests prm key
     */
    function feedback_workorder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->Requests_model->change_type($this->p_request_id, 2);
            $this->Requests_model->feadback($this->p_request_id, $this->p_action_hints, $this->p_time_in, $this->p_time_out, $this->p_adapter_serial, null);

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }


            for ($i = 0; $i < count($this->p_t_ser); $i++) {
                if ($this->p_t_ser[$i] <= 0)
                    $this->Requests_model->team_create($this->_postedData_Team(
                        $this->p_request_id,
                        $this->p_t_worker_job_id[$i],
                        true
                    ));
                else
                    $this->Requests_model->team_edit($this->_postedData_Team(
                        $this->p_request_id,
                        $this->p_t_worker_job_id[$i],
                        false,
                        $this->p_t_ser[$i]
                    ));
            }
            ///********************************************


            for ($i = 0; $i < count($this->p_class_id); $i++) {
                if ($this->p_tl_ser[$i] <= 0)
                    $this->Requests_model->tools_create($this->_postedData_Tools(
                        $this->p_request_id,
                        $this->p_class_id[$i],

                        $this->p_class_count[$i],
                        true
                    ));
                else
                    $this->Requests_model->tools_edit($this->_postedData_Tools(
                        $this->p_request_id,
                        $this->p_class_id[$i],

                        $this->p_class_count[$i],
                        false,
                        $this->p_tl_ser[$i]
                    ));
            }

            echo $this->p_request_id;

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


        $result = array(
            array('name' => 'REQUEST_ID', 'value' => $this->p_request_id, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_TYPE', 'value' => $this->p_requests_type, 'type' => '', 'length' => -1),
            array('name' => 'PURPOSE_DESCRIPTION', 'value' => $this->p_purpose_description, 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMEN_ID', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'ACTION_HINTS', 'value' => (isset($this->p_action_hints) ? $this->p_action_hints : null), 'type' => '', 'length' => -1),
            array('name' => 'NOTS', 'value' => $this->p_nots, 'type' => '', 'length' => -1),
            array('name' => 'CITIZEN_NAME', 'value' => $this->p_citizen_name, 'type' => '', 'length' => -1),

            array('name' => 'ADDRRSS', 'value' => $this->p_addrrss, 'type' => '', 'length' => -1),
            array('name' => 'JAWAL_NO', 'value' => $this->p_jawal_no, 'type' => '', 'length' => -1),
            array('name' => 'TEL_NO', 'value' => $this->p_tel_no, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_SERIAL', 'value' => $this->p_project_serial, 'type' => '', 'length' => -1),
            array('name' => 'CAR_ID', 'value' => $this->p_car_id, 'type' => '', 'length' => -1),
            array('name' => 'COMPUTER_ID', 'value' => $this->p_computer_id, 'type' => '', 'length' => -1),
            array('name' => 'ADAPTER_SERIAL', 'value' => $this->p_adapter_serial, 'type' => '', 'length' => -1),
            array('name' => 'PHASE_TYPE', 'value' => $this->p_phase_type, 'type' => '', 'length' => -1),
            array('name' => 'SUBSCRIBERS_TYPES', 'value' => $this->p_subscribers_types, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_ID', 'value' => isset($this->p_branch) ? $this->p_branch : null, 'type' => '', 'length' => -1),
            array('name' => 'IMPORTANT_DEGREE', 'value' => isset($this->p_important_degree) ? $this->p_important_degree : null, 'type' => '', 'length' => -1),

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

    function _postedData_Team($Requests_id, $worker_job_id, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'REQUESTS_ID', 'value' => $Requests_id, 'type' => '', 'length' => -1),
            array('name' => 'WORKER_JOB_ID', 'value' => $worker_job_id, 'type' => '', 'length' => -1),

        );

        if (!$isCreate) {
            unset($result[1]);
        } else {
            unset($result[0]);
        }

        return $result;
    }

    function _postedData_Tools($Requests_id, $class_id, $class_count, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'Requests_ID', 'value' => $Requests_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_COUNT', 'value' => $class_count, 'type' => '', 'length' => -1),


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

        $data['details'] = $this->Requests_model->tools_list($id);


        $this->load->view('Requests_tools', $data);
    }

    function public_get_team($id)
    {

        $data['details'] = $this->Requests_model->team_list($id);

        $this->load->view('Requests_team', $data);
    }

    function delete_tools()
    {
        echo $this->Requests_model->delete_tools($this->p_id);
    }

    function delete_team()
    {
        echo $this->Requests_model->delete_team($this->p_id);
    }

    function sms()
    {

        $rs = $this->Requests_model->sms($this->p_id, $this->p_sms, $this->p_mob);

        if (intval($rs) > 0) {

            $msg = $this->p_sms;
            $msg = $this->_hex_chars($msg);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://api.mtcsms.com/sendsms.aspx?username=CS-IT-Gedco&password=itgedcosms000&from=TECH-Gedco&to=' . $this->p_mob . '&msg=' . $msg . '&type=1');
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);

            //$this->print_error( 'http://api.mtcsms.com/sendsms.aspx?username=CS-IT-Gedco&password=itgedcosms000&from=TECH-Gedco&to='.$this->p_mob.'&msg='.$msg.'&type=1');
            echo 1;

        } else {

            $this->print_error('فشل في حفظ البيانات');
        }
    }

    function _hex_chars($data)
    {
        $mb_hex = '';
        for ($i = 0; $i < mb_strlen($data, 'UTF-8'); $i++) {
            $c = mb_substr($data, $i, 1, 'UTF-8');
            $o = unpack('N', mb_convert_encoding($c, 'UCS-4BE', 'UTF-8'));
            $mb_hex .= sprintf('%04X', $o[1]);
        }
        return $mb_hex;

    }

}