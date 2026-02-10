<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 21/12/14
 * Time: 09:05 ص
 */
class Projects extends MY_Controller
{

    var $MODEL_NAME = 'projects_model';
    var $url = "projects/projects/get";

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'PROJECTS_PKG';

        $this->load->model('adapter_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');


    }

    function _lookUps_data(&$data)
    {
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['project_tec_type'] = $this->constant_details_model->get_list(51);
        $data['currency'] = $this->currency_model->get_list();
    }

    /**
     *
     * index action perform all functions in view of projects_show view
     * from this view , can show projects tree , insert new project , update exists one and delete other ..
     *
     */
    function index($page = 1)
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res = $this->projects_model->adopt($this->p_id, 1);
            if ($res >= 1) {
                echo 1;
                $this->_notify("tec", "المشروع : {$this->p_title}");
            } else {
                echo $res;
            }


           /* echo $this->projects_model->adopt($this->p_id, 1);

            $this->_notify("tec", "المشروع : {$this->p_title}");*/

        } else {

            $data['title'] = 'إدارة المشاريع';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = -1;
            $data['action'] = 'index';

            $this->_loadDatePicker();

            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }
    }

    /**
     *
     * index action perform all functions in view of projects_show view
     * from this view , can show projects tree , insert new project , update exists one and delete other ..
     *
     */
    function archive($page = 1)
    {

        $data['title'] = 'إدارة ارشيف المشاريع';
        $data['content'] = 'projects_archive';
        $data['page'] = $page;
        $data['case'] = 1;
        $data['action'] = 'index';
        $this->_loadDatePicker();
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);

    }

    //case 2
    function tec($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 2);
            $this->_notify("plan", "المشروع : {$this->p_title}", 'tec');
        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 2;

            $data['action'] = 'tec';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 3
    function plan($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 4);
            $this->_notify("plan_admin", "المشروع : {$this->p_title}", 'plan');

        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 3;
            $data['action'] = 'plan';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 4
    function plan_admin($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 5);
            $this->_notify("com", "المشروع : {$this->p_title}", 'plan_admin');
        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 4;
            $data['action'] = 'plan_admin';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 3
    function planSCADA($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 4);
            $this->_notify("plan_admin", "المشروع : {$this->p_title}", 'plan');

        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 3;
            $data['action'] = 'planSCADA';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 4
    function plan_adminSCADA($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 5);
            $this->_notify("com", "المشروع : {$this->p_title}", 'plan_admin');
        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 4;
            $data['action'] = 'plan_adminSCADA';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    // case 5
    function com($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt_com($this->p_id, $this->p_company_value_type, $this->p_company_value);
            $this->_notify("branch_admin", "المشروع : {$this->p_title}", 'com');
        } else {
            $data['title'] = 'إدارة المشاريع : اعتماد لجنة المساهمة';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 5;
            $data['action'] = 'com';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 6
    function branch_admin($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 7);
            $this->_notify("low", "المشروع : {$this->p_title}", 'branch_admin');
        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 6;
            $data['action'] = 'branch_admin';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 7
    function low($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 8);
            $this->_notify("Maintenance", "المشروع : {$this->p_title}", 'low');
        } else {
            $data['title'] = 'إدارة المشاريع : اعتماد الدائرة القانونية';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 7;
            $data['action'] = 'low';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 8
    function Maintenance($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 9);
            $this->_notify("Maintenance_admin", "المشروع : {$this->p_title}", 'Maintenance');
        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 8;
            $data['action'] = 'Maintenance';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 9
    function Maintenance_admin($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 10);
            $this->_notify("tec_admin", "المشروع : {$this->p_title}", 'Maintenance_admin');
        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 9;
            $data['action'] = 'Maintenance_admin';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 10
    function tec_admin($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 11);
            $this->_notify("accounts", "المشروع : {$this->p_title}", 'tec_admin');
        } else {
            $data['title'] = 'إدارة المشاريع : تعديلات الدائرة الفنية للتكلفة واعتماد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 10;
            $data['action'] = 'tec_admin';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 11
    function accounts($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_project_account_id); $i++) {
                $rs = $this->projects_model->edit_accounts($this->_postData_accounts($this->p_SER[$i], $this->p_project_account_id[$i]));
                if (intval($rs) <= 0)
                    $this->print_error("فشل في حفظ الحساب {$this->p_project_account_id[$i]}");
            }

            echo 1;

        } else {
            $data['title'] = 'إدارة المشاريع : إفتتاح الحسابات';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 11;
            $data['action'] = 'accounts';
            $data['help'] = $this->help;
            $this->load->view('template/template', $data);
        }
    }

    //case 12
    function exchange($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id, 12);

        } else {
            $data['title'] = 'إدارة المشاريع : اعتماد صرف المواد';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 11;
            $data['action'] = 'exchange';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    //case 15 -- mkilani - close project
    function close_project($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo $this->projects_model->adopt($this->p_id, 16);
        } else {
            $data['title'] = 'إدارة المشاريع : اغلاق المشاريع';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 16;
            $data['action'] = 'close_project';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    function gis($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo $this->projects_model->adopt($this->p_id, 15);
        } else {
            $data['title'] = 'إدارة المشاريع : اغلاق المشاريع';
            $data['content'] = 'projects_index';
            $data['page'] = $page;
            $data['case'] = 15;
            $data['action'] = 'gis';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }
    }

    function edit_accounts($id)
    {

        $data['title'] = 'إدارة المشاريع : تعديل بيانات الحسابات';
        $data['content'] = 'accounts_index';
        $data['help'] = $this->help;
        $data['rows'] = $this->projects_model->get_accounts($id);

        $this->load->view('template/template', $data);

    }

    function public_return()
    {
        echo $this->projects_model->adopt($this->p_id, 0);
    }

    function _look_ups(&$data, $date = null)
    {


        $data['project_type'] = $this->constant_details_model->get_list(47);
        $data['power_adapter_direction'] = $this->constant_details_model->get_list(48);
        $data['power_adapter_network'] = $this->constant_details_model->get_list(49);
        $data['company_value_type'] = $this->constant_details_model->get_list(50);
        $data['project_tec_type'] = $this->constant_details_model->get_list(51);
        $data['design_cost'] = $this->get_system_info('DESIGN_FOLLOW_COST', '1');
        $data['supervision_cost'] = $this->get_system_info('IMP_INSTALLATION_FEES', '1');
        $data['POWER_TYPE'] = $this->constant_details_model->get_list(64);

        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['help'] = $this->help;

        $data['currency'] = $this->currency_model->get_all_date($date);

        $this->_loadDatePicker();

    }

    function prices($page = 1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_class_id); $i++) {

                if (intval($this->p_ser[$i]) <= 0)
                    $this->projects_model->insert_price($this->_postData_prices(true, 0, $this->p_class_id[$i], $this->p_sale_price[$i]));
                else
                    $this->projects_model->update_price($this->_postData_prices(false, $this->p_ser[$i], $this->p_class_id[$i], $this->p_sale_price[$i]));
            }

            echo 1;

        } else {
            $data['content'] = 'projects_prices';
            $data['title'] = 'إدارة المشاريع : بيانات الأسعار';
            //$data['rows'] = $this->projects_model->get_project_items();
            $this->_look_ups($data);
            $data['page'] = $page;
            $this->load->view('template/template', $data);
        }


    }

    function delete_price()
    {
        // $this->IsAuthorized();
        echo $this->projects_model->delete_price($this->p_id);
    }

    function get_page($page = 1, $case = 1, $action = 'index')
    {

        $this->load->library('pagination');

        $case = isset($this->p_case) ? $this->p_case : $case;
        $action = isset($this->p_action) ? $this->p_action : $action;

        // mkilani
        if ($case == 15)
            $sql = " ";
        else
            $sql = " AND (PROJECT_CASE = {$case} or ({$case} = 2 and PROJECT_CASE =1) )";

        $sql .= isset($this->p_tec_num) && $this->p_tec_num != null ? " AND  LOWER(PROJECT_TEC_CODE) like LOWER('%{$this->p_tec_num}')  " : "";
        $sql .= isset($this->p_project_name) && $this->p_project_name != null ? " AND  PROJECT_NAME LIKE '%{$this->p_project_name}%' " : "";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  PROJECT_DATE >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  PROJECT_DATE <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  BRANCH={$this->p_branch}  " : "";
        $sql .= isset($this->p_project_tec_type) && $this->p_project_tec_type != null ? " AND  PROJECT_TEC_TYPE={$this->p_project_tec_type}  " : "";
        $sql .= isset($this->p_project_case) && $this->p_project_case != null ? " AND PROJECT_CASE = {$this->p_project_case}" : "";
        $sql .= $this->user->branch != 1 ? " AND DECODE(BRANCH,8,2,BRANCH) ={$this->user->branch}  " : ""; // تم دمج مقر الصيانة مع مقر غزة 202210

        if ($case == 3 || $case == 5)
            $sql .= strpos($action, 'SCADA') !== false ? " AND LOWER(PROJECT_TEC_CODE) like LOWER('%isc%')  " : "  AND LOWER(PROJECT_TEC_CODE) not like LOWER('%isc%') ";
        // mkilani
        if (!$this->input->is_ajax_request() and $case == 15) {
            $sql = " AND PROJECT_CASE = {$case} ";
        }

        $config['base_url'] = base_url("projects/projects/get_page/");


        $count_rs = $this->projects_model->get_count($sql);


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

        $data["rows"] = $this->projects_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['action'] = $action;

        $this->load->view('projects_page', $data);

    }

    function get_page_archive($page = 1, $case = 1, $action = 'index')
    {

        $this->load->library('pagination');

        $case = isset($this->p_case) ? $this->p_case : $case;
        $action = isset($this->p_action) ? $this->p_action : $action;

        $sql = ($this->user->branch != 1 ? " AND DECODE(BRANCH,8,2,BRANCH) = {$this->user->branch} " : ""); // تم دمج مقر الصيانة مع مقر غزة 202210
        $sql .= $case > 1 ? " AND PROJECT_CASE = {$case} " : "";

        $sql .= isset($this->p_tec_num) && $this->p_tec_num != null ? " AND   LOWER(PROJECT_TEC_CODE) LIKE LOWER('%{$this->p_tec_num}')   " : "";
        $sql .= isset($this->p_project_name) && $this->p_project_name != null ? " AND  PROJECT_NAME LIKE '%{$this->p_project_name}%' " : "";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  PROJECT_DATE >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  PROJECT_DATE <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  DECODE(BRANCH,8,2,BRANCH)={$this->p_branch}  " : ""; // تم دمج مقر الصيانة مع مقر غزة 202210
        $sql .= isset($this->p_project_tec_type) && $this->p_project_tec_type != null ? " AND  PROJECT_TEC_TYPE={$this->p_project_tec_type}  " : "";
        $sql .= isset($this->p_project_case) && $this->p_project_case != null ? " AND PROJECT_CASE = {$this->p_project_case}" : "";

        $sql .= isset($this->p_old_tec_num) && $this->p_old_tec_num != null ? " AND   LOWER(OLD_PROJECT_TEC_CODE) LIKE LOWER('%{$this->p_old_tec_num}')   " : "";
        $sql .= isset($this->p_customer_id) && $this->p_customer_id != null ? " AND  CUSTOMER_ID LIKE '%{$this->p_customer_id}%' " : "";



        $config['base_url'] = base_url("projects/projects/get_page_archive/");

        $count_rs = $this->projects_model->get_count($sql);

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

        $data["rows"] = $this->projects_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['action'] = $action;

        $this->load->view('projects_page', $data);

    }

    function get_items_page($page = 1)
    {

        $this->load->library('pagination');


        $sql = isset($this->p_id) && $this->p_id != null ? " AND  CLASS_ID= {$this->p_id} " : "";
        $sql .= isset($this->p_name) && $this->p_name != null ? " AND  STORES_PKG.CLASS_TB_GET_NAME_AR(CLASS_ID) LIKE '%{$this->p_name}%' " : "";

        $config['base_url'] = base_url("projects/projects/prices/");


        $count_rs = $this->get_table_count(" PROJECTS_ITEM WHERE 1=1 $sql");


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

        $data["rows"] = $this->projects_model->get_project_items($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;


        $this->load->view('project_prices_page', $data);

    }

    function public_select_project_accounts($txt, $type = null, $page = 1)
    {
        $data['title'] = ' إختيار حساب المشروع';
        $data['content'] = 'projects_account_select';
        $data['page'] = $page;
        $data['txt'] = $txt;
        $data['case'] = 1;
        $data['type'] = $type;
        $data['action'] = 'index';
        $this->load->view('template/view', $data);

    }

    function public_select_civil_project_accounts($txt, $type = null, $page = 1)
    {
        $data['title'] = ' إختيار حساب المشروع';
        $data['content'] = 'projects_account_civil_select';
        $data['page'] = $page;
        $data['txt'] = $txt;
        $data['case'] = 1;
        $data['type'] = $type;
        $data['action'] = 'index';
        $this->load->view('template/view', $data);

    }

    function public_select_project_accounts_page($page = 1, $type = null)
    {
        $this->load->library('pagination');

        $type = isset($this->p_type) ? $this->p_type : $type;

        $sql = isset($this->p_id) && $this->p_id != null ? " AND AC.PROJECT_ID = {$this->p_id} " : '';
        $sql .= isset($this->p_name) ? " AND AC.PROJECT_ACCOUNT_NAME LIKE '%{$this->p_name}%' " : '';
        $sql .= $type != null ? " AND AC.PROJECT_ACCOUNT_TYPE = {$type} " : "";
        $sql .= isset($this->p_project_ser) ? "  AND LOWER(P.PROJECT_TEC_CODE) like LOWER('%{$this->p_project_ser}') " : '';

        $config['base_url'] = base_url("projects/projects/public_select_project_accounts_page/");

        $count_rs = $this->get_table_count(" PROJECTS_ACCOUNTS_TB AC  JOIN PROJECTS_FILE_TB P ON AC.PROJECT_SERIAL = P.PROJECT_SERIAL WHERE 1=1  {$sql} ");


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

        $data["rows"] = $this->projects_model->get_accounts_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;


        $this->load->view('projects_account_select_page', $data);

    }

    function public_select_project_civil_accounts_page($page = 1, $type = null)
    {
        $this->load->library('pagination');

        $type = isset($this->p_type) ? $this->p_type : $type;

        $sql = isset($this->p_id) && $this->p_id != null ? " AND AC.PROJECT_ID = {$this->p_id} " : '';
        $sql .= isset($this->p_name) ? " AND AC.PROJECT_ACCOUNT_NAME LIKE '%{$this->p_name}%' " : '';
        $sql .= $type != null ? " AND AC.PROJECT_ACCOUNT_TYPE = {$type} " : "";
        $sql .= isset($this->p_project_ser) ? "  AND LOWER(P.PROJECT_TEC_CODE) like LOWER('%{$this->p_project_ser}') " : '';

        $config['base_url'] = base_url("projects/projects/public_select_project_civil_accounts_page/");

        $count_rs = $this->get_table_count(" PROJECTS_ACCOUNTS_TB AC  JOIN PROJECTS_FILE_TB P ON AC.PROJECT_SERIAL = P.PROJECT_SERIAL WHERE 1=1  {$sql} ");


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

        $data["rows"] = $this->projects_model->get_accounts_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;


        $this->load->view('projects_account_select_civil_page', $data);

    }

    function public_select_project($txt, $type = null, $page = 1)
    {
        $data['title'] = ' إختيار حساب المشروع';
        $data['content'] = 'projects_select';
        $data['page'] = $page;
        $data['txt'] = $txt;
        $data['case'] = 1;
        $data['type'] = $type;
        $data['action'] = 'index';
        $this->load->view('template/view', $data);

    }

    function public_select_project_page($page = 1, $type = 1)
    {
        $this->load->library('pagination');

        $type = isset($this->p_type) ? $this->p_type : $type;

        $sql = isset($this->p_tec_num) && $this->p_tec_num != null ? " AND  LOWER(PROJECT_TEC_CODE) like LOWER('%{$this->p_tec_num}')  " : "";
        $sql .= isset($this->p_project_name) && $this->p_project_name != null ? " AND  PROJECT_NAME LIKE '%{$this->p_project_name}%' " : "";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  PROJECT_DATE >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  PROJECT_DATE <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  BRANCH={$this->p_branch}  " : "";
        $sql .= isset($this->p_project_tec_type) && $this->p_project_tec_type != null ? " AND  PROJECT_TEC_TYPE={$this->p_project_tec_type}  " : "";

        $sql .= $this->user->branch != 1 ? " AND  BRANCH={$this->user->branch}  " : "";
        $config['base_url'] = base_url("projects/projects/public_select_project_page/");

        $count_rs = $this->projects_model->get_count($sql);


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

        $data["rows"] = $this->projects_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;


        $this->load->view('projects_select_page', $data);

    }

    /**
     * get project by id ..
     */
    function get($id, $action = 'index')
    {


        $result = $this->projects_model->get($id);

        if (count($result) <= 0 ||
            (count($result) > 0 && $result[0]['PROJECT_CASE'] < 11 && $action == 'update_items')
        )
            redirect($_SERVER['HTTP_REFERER']);

        $data['content'] = 'projects_show';
        $data['title'] = 'إدارة المشاريع : بيانات المشروع ';
        $data['action'] = $action;
        $data['result'] = $result;

        $data['can_edit'] = count($result) > 0 && (($result[0]['PROJECT_CASE'] == -1 || $result[0]['PROJECT_CASE'] == 8) && $result[0]['ENTRY_USER'] == $this->user->id) ||
            ((($result[0]['PROJECT_TEC_TYPE'] == 4 ||
                        $result[0]['PROJECT_TEC_TYPE'] == 8 ||
                        $result[0]['PROJECT_TEC_TYPE'] == 9 ||
                        $result[0]['PROJECT_TEC_TYPE'] == 13 ||
                        $result[0]['PROJECT_TEC_TYPE'] == 21

                    ) &&
                    HaveAccess(base_url('projects/projects/s_edit'))) || (HaveAccess(base_url('projects/projects/ss_edit')) && $result[0]['PROJECT_CASE'] < 11));


        $data['loadFlow'] = count($result) <= 0 ? false : $this->_loadFlowCheckAccess($result[0]['PROJECT_CASE'] + 1, $result[0]['TEC_CODE'], $result[0]['LOAD_FLOW']);

        $this->_look_ups($data, count($result) > 0 ? $result[0]['PROJECT_DATE'] : null);


        $this->load->view('template/template', $data);
    }

    function _loadFlowCheckAccess($case, $tecCode, $loadFlow)
    {

        // print_r($tecCode);die;


        if ($loadFlow == 1) {

            return false;
        }

        switch (strtoupper($tecCode)) {

            case "SP":
            case "R" :
                //case "PM" :
                return ($case == 2 && $loadFlow == 0);

            //case "T":
            //case "IT" :
            case "RSP" :
                //case "P":
                return ($case == 4 && $loadFlow == 0);

            default:
                return false;
        }


    }

    /**
     * get project by id ..
     */
    function get_last($id, $action = 'archive_last')
    {


        $result = $this->projects_model->get_last($id);

        $data['content'] = 'projects_show';
        $data['title'] = 'إدارة المشاريع : بيانات المشروع ';
        $data['action'] = $action;
        $data['result'] = $result;

        $data['can_edit'] = count($result) > 0 && $result[0]['PROJECT_CASE'] == 1 && $result[0]['ENTRY_USER'] == $this->user->id;

        $this->_look_ups($data);

        $this->load->view('template/template', $data);
    }

    /**
     * create action : insert new project data ..
     * receive post data of project
     *
     */
    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!isset($this->p_request_id) || $this->p_request_id == null || $this->p_request_id == '') {
                $this->print_error('يجب إدخال رقم الطلب');
            }

            $result = $this->projects_model->create($this->_postedData());

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ المشروع');
            }


            for ($i = 0; $i < count($this->p_class_id); $i++)
                $this->projects_model->create_details($this->_postData_details(true, null, $result,
                    $this->p_class_id[$i],
                    $this->p_class_unit[$i],
                    $this->p_amount[$i],
                    $this->p_price[$i],
                    $this->p_sal_price[$i],
                    $this->p_notes[$i],
                    $this->p_class_type[$i],
                    $this->p_befor_up_sal_price[$i],
                    1,
                    $this->p_customer_amount[$i],
                    $this->p_befor_up_buy_price[$i]
                ));

            echo 1;

        } else {
            $data['content'] = 'projects_show';
            $data['title'] = 'إدارة المشاريع : بيانات المشروع ';
            $data['action'] = 'index';

            $this->_look_ups($data);

            $this->load->view('template/template', $data);
        }


    }

    /**
     * edit action : update exists project data ..
     * receive post data of project
     * depended on project prm key
     */
    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->projects_model->edit($this->_postedData(false));


            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ المشروع');
            }


            for ($i = 0; $i < count($this->p_class_id); $i++) {
                if ($this->p_SER[$i] <= 0)
                    $this->projects_model->create_details($this->_postData_details(true, null, $this->p_project_serial,
                        $this->p_class_id[$i],
                        $this->p_class_unit[$i],
                        $this->p_amount[$i],
                        $this->p_price[$i],
                        $this->p_sal_price[$i],
                        $this->p_notes[$i],
                        $this->p_class_type[$i],
                        $this->p_befor_up_sal_price[$i],
                        1,
                        $this->p_customer_amount[$i],
                        $this->p_befor_up_buy_price[$i]
                    ));
                else
                    $this->projects_model->edit_details($this->_postData_details(false, $this->p_SER[$i], $this->p_project_serial,
                        $this->p_class_id[$i],
                        $this->p_class_unit[$i],
                        $this->p_amount[$i],
                        $this->p_price[$i],
                        $this->p_sal_price[$i],
                        $this->p_notes[$i],
                        $this->p_class_type[$i],
                        $this->p_befor_up_sal_price[$i],
                        1,
                        $this->p_customer_amount[$i],
                        $this->p_befor_up_buy_price[$i]
                    ));
            }

            echo 1;

        }
    }

    function Customeritem($id = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $rs = $this->projects_model->CHECK_PROJECT_FIN_COUNT(array(
                array('name' => 'SER', 'value' => $this->p_project_serial, 'type' => '', 'length' => -1)));

            if (intval($rs) > 0) {
                $this->print_error('لا يمكن التعديل علي بيانات المشروع , يوجد عليه حركات مالية');
            }

            for ($i = 0; $i < count($this->p_class_id); $i++) {

                if (intval($this->p_customer_amount[$i]) > intval($this->p_amount[$i])) {
                    $this->print_error("{$this->p_class_id[$i]} : {$this->p_class_id_name[$i]} <br> كمية العميل اكبر من الكمية المسموح بها ");
                    break;
                }
            }

            for ($i = 0; $i < count($this->p_class_id); $i++) {

                $this->projects_model->edit_details_customer(

                    array(
                        array('name' => 'SER', 'value' => $this->p_SER[$i], 'type' => '', 'length' => -1),
                        array('name' => 'CUSTOMER_AMOUNT', 'value' => $this->p_customer_amount[$i], 'type' => '', 'length' => -1)
                    ));
            }

            echo 1;

        } else {

            $result = $this->projects_model->get($id);

            if (count($result) > 0 && $result[0]['PROJECT_TYPE'] == 3) {

                $this->print_error('الا يمكن التعديل علي مشاريع علي نفقة الشركة');
            }

            $data['content'] = 'projects_customer_show';
            $data['title'] = 'إدارة المشاريع : مواد المواطنين ';
            $data['result'] = $result;
            $this->_look_ups($data, count($result) > 0 ? $result[0]['PROJECT_DATE'] : null);

            $this->load->view('template/template', $data);
        }
    }

    function Returnitems($id = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_class_id); $i++) {

                if (intval($this->p_count[$i]) <= 0 || intval($this->p_price[$i]) <= 0) {
                    $this->print_error('يجب إدخال جميع الكميات المرجعة و الاسعار');
                    break;
                }
            }
            for ($i = 0; $i < count($this->p_class_id); $i++) {


                $this->projects_model->project_return_iu(

                    array(
                        array('name' => 'SER', 'value' => $this->p_SER[$i], 'type' => '', 'length' => -1),
                        array('name' => 'PROJECT_SER_IN', 'value' => $this->p_project_serial, 'type' => '', 'length' => -1),
                        array('name' => 'CLASS_ID_IN', 'value' => $this->p_class_id[$i], 'type' => '', 'length' => -1),
                        array('name' => 'COUNT_IN', 'value' => $this->p_count[$i], 'type' => '', 'length' => -1),
                        array('name' => 'PRICE_IN', 'value' => $this->p_price[$i], 'type' => '', 'length' => -1),
                        array('name' => 'TYPE_IN', 'value' => $this->p_class_type[$i], 'type' => '', 'length' => -1),

                    ));
            }

             $this->projects_model->project_return_value_iu(

                array(
                    array('name' => 'PROJECT_SER_IN', 'value' => $this->p_project_serial, 'type' => '', 'length' => -1),
                ));
            echo 1;

        } else {

            $result = $this->projects_model->get($id);


            $data['content'] = 'projects_return_show';
            $data['title'] = 'إدارة المشاريع : المواد المرجعة ';
            $data['result'] = $result;
            $this->_look_ups($data, count($result) > 0 ? $result[0]['PROJECT_DATE'] : null);

            $this->load->view('template/view', $data);
        }
    }

    /**
     * delete action : delete project data ..
     * receive prm key as request
     *
     */
    function delete_details()
    {
        $result = $this->projects_model->delete_details($this->p_id);
        if (intval($result) > 0)
            echo 1;
        else $this->print_error('حدث مشكلة قد يكون الصنف صرف منه!');
    }


    function delete_return_item_details()
    {
        $result = $this->projects_model->delete_return_item_details($this->p_id);
        if (intval($result) > 0) {

            echo $this->projects_model->project_return_value_iu(
                array(
                    array('name' => 'PROJECT_SER_IN', 'value' => $this->p_proj, 'type' => '', 'length' => -1),
                ));


        } else echo $result;
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
            array('name' => 'PROJECT_SERIAL', 'value' => $this->p_project_serial, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC_CODE', 'value' => $this->p_project_tec_code, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_NAME', 'value' => $this->p_project_name, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_DATE', 'value' => date('d/m/Y'), 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_DESIGN_DATE', 'value' => $this->p_project_design_date, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_DESIGN_VALID_DATE', 'value' => $this->p_project_design_valid_date, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TYPE', 'value' => $this->p_project_type, 'type' => '', 'length' => -1),
            array('name' => 'POWER_ADAPTER_NAME', 'value' => $this->p_power_adapter_name, 'type' => '', 'length' => -1),
            array('name' => 'POWER_ADAPTER_DIRECTION', 'value' => $this->p_power_adapter_direction, 'type' => '', 'length' => -1),
            array('name' => 'POWER_ADAPTER_NETWORK', 'value' => $this->p_power_adapter_network, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->p_address, 'type' => '', 'length' => -1),
            array('name' => 'POWER_CONNECTION_TYPE', 'value' => $this->p_power_connection_type, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_VALUE_TYPE', 'value' => $this->p_company_value_type, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_VALUE', 'value' => $this->p_company_value, 'type' => '', 'length' => -1),
            array('name' => 'DESIGN_COST', 'value' => $this->p_design_cost, 'type' => '', 'length' => -1),
            array('name' => 'SUPERVISION_COST', 'value' => $this->p_supervision_cost, 'type' => '', 'length' => -1),
            array('name' => 'EXTRA_COST', 'value' => $this->p_extra_cost, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC_TYPE', 'value' => $this->p_project_tec_type, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->p_customer_id, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->p_curr_id, 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $this->p_curr_value, 'type' => '', 'length' => -1),
            array('name' => 'OLD_ACCOUNT_ID', 'value' => $this->p_old_account_id, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $this->p_hints, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->p_branch, 'type' => '', 'length' => -1),
            array('name' => 'RETURN_COST', 'value' => $this->p_return_cost, 'type' => '', 'length' => -1),
            array('name' => 'POWER_TYPE', 'value' => $this->p_power_type, 'type' => '', 'length' => -1),
            array('name' => 'DONOR_NAME', 'value' => $this->p_donor_name, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_ID', 'value' => $this->p_request_id, 'type' => '', 'length' => -1),
            array('name' => 'CIVIL_WORKS', 'value' => $this->p_civil_works, 'type' => '', 'length' => -1),
            array('name' => 'TAX', 'value' => $this->p_tax, 'type' => '', 'length' => -1),
            array('name' => 'UNDER_TAX', 'value' => $this->p_under_tax, 'type' => '', 'length' => -1),
            array('name' => 'MONITORING_COST', 'value' => $this->p_monitoring_cost, 'type' => '', 'length' => -1),

        );

        if ($isCreate) {
            array_shift($result);
        }


        return $result;
    }

    function public_get_details($id = 0, $can_edit = false, $action = 'index')
    {

        if ($action != 'archive_last') {
            $data['details'] = $this->projects_model->get_details($id);
            $data['can_edit'] = $can_edit;
        } else {
            $data['details'] = $this->projects_model->get_details_last($id);
            $data['can_edit'] = false;
        }

        $this->load->view('projects_details', $data);
    }

    function public_get_customer_details($id = 0)
    {
        $data['details'] = $this->projects_model->get_details($id);
        $this->load->view('projects_customer_details', $data);
    }

    function public_get_return_items($id = 0)
    {
        $data['details'] = $this->projects_model->get_return_items($id);
        $this->load->view('projects_return_details', $data);
    }

    function _postData_prices($create = true, $ser, $class_id, $price)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'SALE_PRICE', 'value' => $price, 'type' => '', 'length' => -1),
        );

        if ($create) {
            array_shift($result);
        }

        return $result;
    }

    function _postData_details($create = true, $ser, $project_serial, $class_id, $class_unit, $amount, $price, $sal_price, $notes, $class_type, $befor_up_sal_price = null, $inuse = 1, $customer_amount = 0,$befor_up_buy_price = null)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_SERIAL', 'value' => $project_serial, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
            array('name' => 'SAL_PRICE', 'value' => $sal_price, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $notes, 'type' => '', 'length' => -1),
            array('name' => 'ITEM_CASE', 'value' => $inuse, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_TYPE', 'value' => $class_type, 'type' => '', 'length' => -1),
            array('name' => 'BEFOR_UP_SAL_PRICE', 'value' => $befor_up_sal_price, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_AMOUNT', 'value' => $customer_amount == null ? 0 : $customer_amount, 'type' => '', 'length' => -1),
            array('name' => 'BEFORE_UP_BUY_PRICE', 'value' => $befor_up_buy_price, 'type' => '', 'length' => -1),
            /* array('name' => 'TAX', 'value' => $this->p_tax, 'type' => '', 'length' => -1),
             array('name' => 'UNDER_TAX', 'value' => $this->p_under_tax, 'type' => '', 'length' => -1),*/
        );

        if ($create) {
            array_shift($result);

        } else {
            unset($result[8]);

        }

        return $result;
    }


    function _postData_accounts($ser, $account_id)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_ACCOUNT_ID', 'value' => $account_id, 'type' => '', 'length' => -1),

        );
        return $result;
    }

    /************************** last years ***********************/
    /**
     *
     * index action perform all functions in view of projects_show view
     * from this view , can show projects tree , insert new project , update exists one and delete other ..
     *
     */
    function archive_last($page = 1)
    {


        $data['title'] = 'إدارة ارشيف المشاريع';
        $data['content'] = 'projects_archive_last';
        $data['page'] = $page;
        $data['case'] = 1;
        $data['action'] = 'archive_last';
        $this->_loadDatePicker();
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);

    }

    function get_page_archive_last($page = 1, $case = 1, $action = 'archive_last')
    {

        $this->load->library('pagination');

        $sql = ($this->user->branch != 1 ? " AND  BRANCH = {$this->user->branch} " : "");


        $sql .= isset($this->p_tec_num) && $this->p_tec_num != null ? " AND  LOWER(PROJECT_TEC_CODE)=LOWER('{$this->p_tec_num}')  " : "";
        $sql .= isset($this->p_project_name) && $this->p_project_name != null ? " AND  PROJECT_NAME LIKE '%{$this->p_project_name}%' " : "";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  PROJECT_DATE >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  PROJECT_DATE <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  BRANCH={$this->p_branch}  " : "";


        $config['base_url'] = base_url("projects/projects/get_page_archive_last/");

        $count_rs = $this->get_table_count(" PROJECTS_FILE_ARCHIVE_TB where 1=1 {$sql} ");

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

        $data["rows"] = $this->projects_model->get_list_archive($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['action'] = $action;

        $data['row_count'] = $config['total_rows'];

        $this->load->view('projects_page', $data);

    }

    function transfer()
    {
        echo $this->projects_model->transfer($this->p_id, $this->p_adopt, $this->p_adopter_id);
    }

    function update_items($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            for ($i = 0; $i < count($this->p_class_id); $i++) {
                if ($this->p_SER[$i] <= 0)
                    echo $this->projects_model->create_details($this->_postData_details(true, null, $this->p_project_serial,
                        $this->p_class_id[$i],
                        $this->p_class_unit[$i],
                        $this->p_amount[$i],
                        $this->p_price[$i],
                        $this->p_sal_price[$i],
                        $this->p_notes[$i],
                        $this->p_class_type[$i],
                        $this->p_befor_up_sal_price[$i],
                        3,
                        '',
                        $this->p_befor_up_buy_price[$i]
                    ));
                if ($this->p_ITEM_CASE[$i] == 3)
                    $this->projects_model->edit_details($this->_postData_details(false, $this->p_SER[$i], $this->p_project_serial,
                        $this->p_class_id[$i],
                        $this->p_class_unit[$i],
                        $this->p_amount[$i],
                        $this->p_price[$i],
                        $this->p_sal_price[$i],
                        $this->p_notes[$i],
                        $this->p_class_type[$i],
                        $this->p_befor_up_sal_price[$i],
                        '',
                        $this->p_befor_up_buy_price[$i]
                    ));

            }

            echo 1;

        } else {
            $data['title'] = 'إستبدال أصناف ';
            $data['content'] = 'projects_archive';
            $data['page'] = $page;
            $data['case'] = 11;
            $data['action'] = 'update_items';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template', $data);
        }

    }

    function update_inUse()
    {
        $rs = $this->projects_model->update_inUse($this->p_id, $this->p_notes);
        if (intval($rs) > 0) {
            echo 1;
        } else {
            $this->print_error('فشل في إستبدال الصنف');
        }
    }

    function delete()
    {
        echo $this->projects_model->delete($this->p_id);
    }

    function table($page = 1)
    {

        $data['title'] = 'فهرس المشاريع';
        $data['content'] = 'projects_table';
        $data['page'] = $page;
        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $this->load->view('template/template', $data);
    }

    function get_page_table($page = 1)
    {

        $this->load->library('pagination');

        $sql = " AND (P.PROJECT_CASE = 11  )";

        $sql .= isset($this->p_tec_num) && $this->p_tec_num != null ? " AND  LOWER(P.PROJECT_TEC_CODE) like LOWER('%{$this->p_tec_num}')  " : "";
        $sql .= isset($this->p_project_name) && $this->p_project_name != null ? " AND  P.PROJECT_NAME LIKE '%{$this->p_project_name}%' " : "";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  P.PROJECT_DATE >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  P.PROJECT_DATE <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  P.BRANCH={$this->p_branch}  " : "";
        $sql .= isset($this->p_project_tec_type) && $this->p_project_tec_type != null ? " AND  PROJECT_TEC_TYPE={$this->p_project_tec_type}  " : "";
        $sql .= isset($this->p_account_id) && $this->p_account_id != null ? " AND  AC.PROJECT_ID={$this->p_account_id}  " : "";
        $sql .= $this->user->branch != 1 ? " AND  P.BRANCH={$this->user->branch}  " : "";

        $config['base_url'] = base_url("projects/projects/get_page_table/");


        $count_rs = $this->get_table_count("  PROJECTS_ACCOUNTS_TB AC JOIN PROJECTS_FILE_TB P ON AC.PROJECT_SERIAL = P.PROJECT_SERIAL WHERE 1=1 {$sql}");


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

        $data["rows"] = $this->projects_model->projects_accounts_fina_list($sql, $offset, $row);

        $data['offset'] = $offset + 1;

        $data['page'] = $page;

        $this->load->view('table_page', $data);

    }

    function _notify($action, $message, $prev_action = null)
    {
        $prev_action = $prev_action != null ? "{$this->url}/{$this->p_id}/{$prev_action}" : null;
        $this->_notifyMessage($action, "{$this->url}/{$this->p_id}/{$action}", $message, $prev_action);
    }

    function public_get_project_index($project_serial = '', $text = null)
    {

        $project_serial = str_ireplace("%20", "", $project_serial);

        $data['project_serial'] = $this->input->get_post('project_serial') ? $this->input->get_post('project_serial') : $project_serial;


        $data['content'] = 'classes_project_items_index';

        $data['text'] = $text;

        $this->load->view('template/view', $data);
    }

    function public_get_project_items($prm = array())
    {

        if (!$prm) //add_percent_sign

            $prm = array('text' => $this->input->get_post('text'),
                'project_serial' => $this->input->get_post('project_serial')
            );

        $data['get_list'] = $this->projects_model->get_details_code($prm['project_serial']);

        $this->load->view('class_project_page', $data);
    }

    /**
     * return project details by technical code
     */
    function publicGetProjectByTec()
    {

        $result = $this->projects_model->get_list(" AND lower(PROJECT_TEC_CODE) = lower('{$this->p_project_tec}') ", 0, 1);

        if (count($result) > 0) echo json_encode($result[0]);
        else echo '';
    }


    function  change_calculation_cable(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data_arr = array(
                array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
                array('name' => 'IS_CALCULATE', 'value' => $this->p_is_calculate, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('IS_CALCULATE_UPDATE', $data_arr);
            if(intval($res) == 1 ){
                echo 1;
            }else {
                $this->print_error('Error_'.$res);
            }
        }
    }

    //صلاحية خاصة ارجاع المشروع الى مرحلة الاعداد
    function return_project_preparid(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data_arr = array(
                array('name' => 'PROJECT_SERIAL', 'value' => $this->p_project_serial, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('RETURN_PROJECT_PREPARID', $data_arr);
            if(intval($res) == 1 ){
                echo 1;
            }else {
                $this->print_error($res);
            }
        }
    }


}
