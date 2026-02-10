<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * adapter: Ahmed Barakat
 * Date: 18/01/15
 * Time: 09:22 ص
 */
class Project_close_request extends MY_Controller
{

    var $MODEL_NAME = 'Project_close_request_model';


    function  __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
    }

    /**
     *
     * index action perform all functions in view of adapters_show view
     * from this view , can show adapters tree , insert new adapter , update exists one and delete other ..
     *
     */
    function index($page = 1)
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->Project_close_request_model->adopt($this->p_id, 1);
        } else {
            $data['title'] = 'طلب إغلاق مشروع';
            $data['content'] = 'Project_close_request_index';
            $data['page'] = $page;

            $this->load->model('settings/gcc_branches_model');

            $data['branches'] = $this->gcc_branches_model->get_all();
            $data['action'] = 'index';
            $data['case'] = -1;

            $this->load->view('template/template', $data);
        }
    }

    function archive($page = 1)
    {
        $data['title'] = 'طلب إغلاق مشروع';
        $data['content'] = 'Project_close_request_index';
        $data['page'] = $page;

        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['action'] = 'archive';
        $data['case'] = -1;
        $this->load->view('template/template', $data);

    }


    function delivery($page = 1)
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($this->p_action) && $this->p_action == 'adopt') {
                echo $this->Project_close_request_model->adopt($this->p_id, 2);
            } else if (isset($this->p_action) && $this->p_action == 'cancel') {
                echo $this->Project_close_request_model->adopt($this->p_id, -2);
            } else {
                $rs = $this->Project_close_request_model->delivery($this->p_ser, $this->p_close_type, $this->p_hints,$this->p_company_name,$this->p_visit_date);

                if (intval($rs) <= 0) {
                    $this->print_error('فشل في حفظ البيانات');
                }

                echo $this->p_ser;
            }


        } else {
            $data['title'] = 'طلب إغلاق مشروع';
            $data['content'] = 'Project_close_request_index';
            $data['page'] = $page;

            $this->load->model('settings/gcc_branches_model');

            $data['branches'] = $this->gcc_branches_model->get_all();
            $data['action'] = 'delivery';
            $data['case'] = 1;

            $this->load->view('template/template', $data);
        }
    }

    function tec($page = 1)
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($this->p_action) && $this->p_action == 'adopt') {
                echo $this->Project_close_request_model->adopt($this->p_id, 3);
            }

            if (isset($this->p_action) && $this->p_action == 'cancel') {
                echo $this->Project_close_request_model->adopt($this->p_id, -1);
            }

        } else {
            $data['title'] = 'طلب إغلاق مشروع';
            $data['content'] = 'Project_close_request_index';
            $data['page'] = $page;

            $this->load->model('settings/gcc_branches_model');

            $data['branches'] = $this->gcc_branches_model->get_all();
            $data['action'] = 'tec';
            $data['case'] = 2;

            $this->load->view('template/template', $data);
        }
    }

    function get_page($page = 1, $action = 'index')
    {

        $this->load->library('pagination');


        $config['base_url'] = base_url("projects/project_close_request/get_page/");

        $sql = 'WHERE 1=1 ';
        $sql .= $action == 'index' ? " AND PROJECT_CLOSE_CASE < 1" : '';
        $sql .= $action == 'delivery' ? " AND PROJECT_CLOSE_CASE = 1 AND (CASE WHEN PROJECT_TEC_CODE LIKE 'SP%' AND P.BRANCH = {$this->user->branch} THEN 1 WHEN PROJECT_TEC_CODE NOT LIKE 'SP%' AND {$this->user->branch} = 1 THEN 1 ELSE 0 END) = 1 " : '';
        $sql .= $action == 'tec' ? " AND PROJECT_CLOSE_CASE = 2 " : '';



        $count_rs = $this->get_table_count(" PROJECT_CLOSE_REQUEST M JOIN PROJECTS_FILE_TB P ON M.PROJECT_SERIAL = P.PROJECT_SERIAL {$sql}");

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

        $data["rows"] = $this->Project_close_request_model->get_list($sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['action'] = $action;


        $this->load->view('Project_close_request_page', $data);

    }


    function public_index($txt, $page = 1)
    {


        $data['title'] = 'طلب إغلاق مشروع';
        $data['content'] = 'Project_close_request_public_index';
        $data['page'] = $page;

        $this->load->model('settings/gcc_branches_model');

        $data['txt'] = $txt;

        $data['branches'] = $this->gcc_branches_model->get_all();

        $this->load->view('template/view', $data);

    }

    function public_get_page($page = 1)
    {

        $this->load->library('pagination');

        $config['base_url'] = base_url("projects/project_close_request/public_get_page/");

        $sql = ($this->user->branch != 1 ? " AND  BRANCH = {$this->user->branch} " : "");

        $sql .= (isset($this->p_no) && $this->p_no != null ? " and Project_close_request_SERIAL ={$this->p_no} " : "");
        $sql .= (isset($this->p_name) && $this->p_name != null ? " and Project_close_request_NAME like '%{$this->p_name}%' " : "");
        $sql .= (isset($this->p_branch) && $this->p_branch != null ? " and BRANCH ={$this->p_branch} " : "");

        $count_rs = $this->Project_close_request_model->get_count($sql);

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

        $data["rows"] = $this->Project_close_request_model->get_list($sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('Project_close_request_public_page', $data);

    }

    /**
     * get adapter by id ..
     */
    function get($id = 0, $action)
    {

        $id = isset($this->p_id) ? $this->p_id : $id;
        $result = $this->Project_close_request_model->get($id);


        $data['content'] = 'Project_close_request_show';
        $data['title'] = 'طلب إغلاق مشروع - إدارة المشاريع';

        $data['result'] = $result;
        $data['action'] = $action;

        $data['can_edit'] = count($result) > 0 && ($result[0]['ENTRY_USER'] == $this->user->id);


        $this->_look_ups($data, null);

        $this->load->view('template/template', $data);

    }

    // mkilani- data of adapter
    function profile()
    {
        $data['title'] = 'بياناتي';
        $data['content'] = 'adapters_profile';
        $data['data'] = $this->Project_close_request_model->get_Project_close_request_info($this->adapter->id);
        $this->load->view('template/template', $data);
    }


    /**
     * create action : insert new adapter data ..
     * receive post data of adapter
     *
     */
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->Project_close_request_model->create($this->_postedData());

            echo $result;
            if (intval($result) <= 0)
                $this->print_error('فشل في حفظ البيانات');

        } else {

            $data['content'] = 'Project_close_request_show';
            $data['title'] = 'طلب إغلاق مشروع - إدارة المشاريع';
            $data['action'] = 'index';

            $this->_look_ups($data);

            $this->load->view('template/template', $data);
        }
    }

    function _look_ups(&$data, $date = null)
    {

        $data['help'] = $this->help;
        $data['OIL_ID_TYPE'] = $this->constant_details_model->get_list(81);
        $data['Project_close_request_VOLTAGE'] = $this->constant_details_model->get_list(97);
        $this->_loadDatePicker();

    }

    /**
     * edit action : update exists adapter data ..
     * receive post data of adapter
     * depended on adapter prm key
     */
    function edit()
    {

        $result = $this->Project_close_request_model->edit($this->_postedData(false));


        $this->Is_success($result);

        echo $this->p_ser;

    }

    /**
     * delete action : delete adapter data ..
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
                $msg = $this->Project_close_request_model->delete($val);
            }
        } else {
            $msg = $this->Project_close_request_model->delete($id);
        }

        if ($msg == 1) {
            echo modules::run('projects/project_close_request/get_page', 1);
        } else {

            $this->print_error_msg($msg);
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
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_SERIAL', 'value' => $this->p_project_serial, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_DATE', 'value' => $this->p_request_date, 'type' => '', 'length' => -1),
            array('name' => 'TITLES', 'value' => $this->p_titles, 'type' => '', 'length' => -1),
            array('name' => 'CLOSE_TYPE', 'value' => isset($this->p_close_type) ? $this->p_close_type : 1, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_hints, 'type' => '', 'length' => -1),
            array('name' => 'ATTCH', 'value' => isset($this->p_attach) ? implode(',', $this->p_attach) : null, 'type' => '', 'length' => -1),

        );


        if ($isCreate)
            array_shift($result);


        return $result;
    }

    function _postedPartitionData($Project_close_request_serial, $partition_direction, $partition_id, $partition_power, $partition_capacity, $installation_date, $hint, $isCreate, $ser = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'Project_close_request_SERIAL', 'value' => $Project_close_request_serial, 'type' => '', 'length' => -1),
            array('name' => 'PARTITION_DIRECTION', 'value' => $partition_direction, 'type' => '', 'length' => -1),
            array('name' => 'PARTITION_ID', 'value' => $partition_id, 'type' => '', 'length' => -1),
            array('name' => 'PARTITION_POWER', 'value' => $partition_power, 'type' => '', 'length' => -1),
            array('name' => 'PARTITION_CAPACITY', 'value' => $partition_capacity, 'type' => '', 'length' => -1),
            array('name' => 'INSTALLATION_DATE', 'value' => $installation_date, 'type' => '', 'length' => -1),
            array('name' => 'HINT', 'value' => $hint, 'type' => '', 'length' => -1),

        );

        if ($isCreate)
            array_shift($result);


        return $result;
    }


    function public_get_partitions($id)
    {
        $data['power_Project_close_request_direction'] = $this->constant_details_model->get_list(48);
        $data['details'] = $this->Project_close_request_model->partitions_list($id);

        $this->load->view('Project_close_request_partitions', $data);
    }

    function project_close_hinst_list($id)
    {

        $data["rows"] = $this->Project_close_request_model->project_close_hinst_list(" WHERE M.PROJECT_CLOSE_SER = {$id} ", 0, 1000);


        $this->load->view('project_close_hinst_list', $data);
    }



    function delete_partition()
    {
        echo $this->Project_close_request_model->partition_delete($this->p_id);
    }


}