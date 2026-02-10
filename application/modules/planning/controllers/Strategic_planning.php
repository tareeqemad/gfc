<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/11/17
 * Time: 09:22 ص
 */
class Strategic_planning extends MY_Controller
{

    var $MODEL_NAME = 'plan_model';
    var $DETAILS_MODEL_NAME = 'plan_detail_model';
    var $MODEL_NAME_ = 'Vision_mission_model';
    var $PAGE_URL = 'planning/Strategic_planning/get_page';
    var $PAGE_EVALUATE_URL = 'planning/Strategic_planning/get_page_evaluate';
    var $PAGE_REFRESH_URL = 'planning/Strategic_planning/get_page_refresh';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->MODEL_NAME_);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->year = $this->budget_year;
        $this->ser = $this->input->post('ser');

    }

    function index($page = 1)
    {


        $data['title'] = 'تخطيط الأنشطة';
        $data['help'] = $this->help;
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data["YEAR"] = date('Y') + 1;
        $this->_look_ups($data);
        $data['tech'] = 1;

        //$data['year_paln']=$this->{$this->MODEL_NAME_}->vision_mission_tb_last();//$data["YEAR"]=date('Y')+1;//$this->year;

        $data['content'] = 'Strategic_planning_index';
        $data['page'] = $page;
        $this->load->view('template/template', $data);
    }

    /*************************/

    function _look_ups(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');
        //add_css('simple-line-icons.min.css');
        add_css('jquery.dataTables.css');
        //add_css('components-md-rtl.css');
        // add_css('plugins-md-rtl.css');
        // add_css('layout-rtl.css');
        add_js('jquery.dataTables.js');


        // add_js('table-editable.js');


        $data['activity_class'] = $this->constant_details_model->get_list(193);
        $data['activity_class_no_tech'] = $this->{$this->MODEL_NAME}->get_list_no_tech();
        $data['finance_type'] = $this->constant_details_model->get_list(197);
        $data['activity_type'] = $this->constant_details_model->get_list(199);
        $data['is_end'] = $this->constant_details_model->get_list(205);
        $data['status'] = $this->constant_details_model->get_list(206);
        $data['adopt'] = $this->constant_details_model->get_list(207);
        $data['planning_dir'] = $this->constant_details_model->get_list(217);
        $data['type_project'] = $this->constant_details_model->get_list(423);
        $data['unit'] = $this->constant_details_model->get_list(230);
        $data['scale'] = $this->constant_details_model->get_list(231);
        $data['stratgic_plan'] = $this->{$this->MODEL_NAME_}->vision_mission_tb_list();
        $data['year_paln'] = $this->{$this->MODEL_NAME_}->vision_mission_tb_last();//$data["YEAR"]=date('Y')+1;//$this->year;

        if ($this->user->branch == 1) {

            $data['branches_follow'] = $this->gcc_branches_model->get_all();//$this->gcc_branches_model->user_branch($this->user->id);
            $data['branches'] = $this->gcc_branches_model->get_all();
            $data['all_project'] = $this->{$this->MODEL_NAME}->get_project(null, $data["YEAR"]);
            // $data['branches'] = $this->gcc_branches_model->user_branch($this->user->id);
        } else {
            $data['branches_follow'] = $this->gcc_branches_model->get(1);//$this->gcc_branches_model->user_branch($this->user->id);
            $data['branches'] = $this->gcc_branches_model->user_branch($this->user->id);
            $data['all_project'] = $this->{$this->MODEL_NAME}->get_project($this->user->branch, $data["YEAR"]);
        }

        $data['all_objective'] = $this->{$this->MODEL_NAME}->get_objective('', 0, $data['year_paln'][0]['FROM_YEAR']/*date('Y')+1*/);


        //  $data['all_project']= $this->{$this->MODEL_NAME}->get_project();


    }
    /**************/
    /*************************/

    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        //$months = $this->{$this->DETAILS_MODEL_NAME}->get_monthes($id);

        if ($this->user->branch == 1)

            $result = $this->{$this->MODEL_NAME}->get($id);
        else
            $result = $this->{$this->MODEL_NAME}->get($id, $this->user->branch);

        if (!(count($result) == 1)) {
            echo 'not in your permissions';
            die();

        }

        $achive_res = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['planning_data'] = $result[0];
        $data['YEAR'] = $result[0]['YEAR'];
        $data['achive_data'] = $achive_res;
        //$data['content'] = 'planning_show_no_tech';
        $data['content'] = 'planning_Strategic_projects';

        $data['action'] = 'edit';
        $data['isCreate'] = false;

        $data['title'] = '  موازنة المشاريع  ::.  الخطة الإستراتيجية   ::. ';
        $data['help'] = $this->help;
        $data['tech'] = 0;
        if ($this->uri->segment(4) != '') {

            $data['have_project_ser'] = true;

            if ($this->user->branch == 1)
                $data['project'] = $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4));
            else
                $data['project'] = $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4), $this->user->branch);

            /* if (!(count($data['project']) == 1))
             {
                 echo 'not in your permissions';
                 die();

             }*/

        } else {
            $data['have_project_ser'] = false;
            $data['project'] = array();
        }
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    /************/

    function public_get_plan_id($plan_id = 0)
    {


        $arr = $this->{$this->MODEL_NAME_}->get($plan_id);;
        return $arr;


    }

    /***************/
    ////////////

    function delete()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME_}->delete($val);
                //echo $msg;
                if ($msg == 1) {
                    echo 'تم حذف المشروع بنجاح' . '<br/>';
                    //echo $msg;
                    //echo modules::run($this->PAGE_URL);
                } else {
                    if ($msg == -1) {
                        echo '!! لا يمكن الغاء مشروع معتمد' . '<br/>';
                    } else {
                        echo $msg . '<br/>';;
                    }

                }
            }
        } else {
            $msg = $this->{$this->MODEL_NAME_}->delete($id);
            if ($msg == 1) {
                // echo $msg;
                echo 'تم حذف المشروع بنجاح' . '<br/>';
                //echo modules::run($this->PAGE_URL);
            } else {
                if ($msg == -1) {
                    echo '!! لا يمكن الغاء مشروع معتمد' . '<br/>';
                } else {
                    echo $msg . '<br/>';;
                }

            }
        }


    }

    /************/

    function public_get_plan_ser_id($from_year = 0, $to_year = 0)
    {


        $arr = $this->{$this->MODEL_NAME_}->VISION_MISSION_TB_YEARS($from_year, $to_year);
        return $arr;


    }

    /***************/

    function public_get_plan($from_year = 0, $to_year = 0)
    {

        $from_year = $this->input->post('from_year') ? $this->input->post('from_year') : $from_year;
        $to_year = $this->input->post('to_year') ? $this->input->post('to_year') : $to_year;
        $arr = $this->{$this->MODEL_NAME_}->VISION_MISSION_TB_YEARS($from_year, $to_year);


        echo json_encode($arr);


    }

    function public_get_obj($plan_id = 0, $father_id = 0)
    {

        $plan_id = $this->input->post('plan_id') ? $this->input->post('plan_id') : $plan_id;
        $father_id = $this->input->post('father_id') ? $this->input->post('father_id') : $father_id;
        $arr = $this->{$this->MODEL_NAME_}->get_objective($plan_id, $father_id);


        echo json_encode($arr);


    }

    function get_goal_t_project()
    {

        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get_goal_t_project($id);
        $this->return_json($result);

    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = " AND M.TYPE_PROJECT=2 and adopt<>0 ";
        //$this->user->branch=7;
        $this_year = $this->year;//date('Y')+1;
        $where_sql .= isset($this->p_FROM_YEAR) && $this->p_FROM_YEAR != null ? " AND  M.FROM_YEAR >={$this->p_FROM_YEAR}  " : " AND  FROM_YEAR >= {$this->p_FROM_YEAR}";
        $where_sql .= isset($this->p_TO_YEAR) && $this->p_TO_YEAR != null ? " AND  M.TO_YEAR <={$this->p_TO_YEAR}  " : " AND  TO_YEAR <= {$this->p_TO_YEAR}";
        //$where_sql .= isset($this->p_dp_plan) && $this->p_dp_plan != null ? " AND  M.TO_YEAR <={$this->p_dp_plan}  " :  " AND  TO_YEAR <= {$this->p_dp_plan}";
        if (isset($this->p_dp_objective) && $this->p_dp_objective != null)
            $where_sql .= isset($this->p_dp_objective) && $this->p_dp_objective != null ? " AND  OBJECTIVE ={$this->p_dp_objective}  " : " AND  OBJECTIVE = {$this->p_dp_objective}";
        if (isset($this->p_dp_goal) && $this->p_dp_goal != null)
            $where_sql .= isset($this->p_dp_goal) && $this->p_dp_goal != null ? " AND  M.GOAL ={$this->p_dp_goal}  " : " AND  GOAL = {$this->p_dp_goal}";
        if (isset($this->p_dp_goal_t) && $this->p_dp_goal_t != null)
            $where_sql .= isset($this->p_dp_goal_t) && $this->p_dp_goal_t != null ? " AND  GOAL_T ={$this->p_dp_goal_t}  " : " AND  GOAL_T = {$this->p_dp_goal_t}";
        $where_sql .= isset($this->p_activity_name) && $this->p_activity_name != null ? " AND  ACTIVITY_NAME LIKE '%{$this->p_activity_name}%' " : "";
        $where_sql .= isset($this->p_dp_manage_exe_id) && $this->p_dp_manage_exe_id != null ? " AND  M.MANAGE_EXE_ID ={$this->p_dp_manage_exe_id}" : "";
        $where_sql .= isset($this->p_dp_manage_exe_id) && $this->p_dp_manage_exe_id != null ? " AND  M.MANAGE_EXE_ID ={$this->p_dp_manage_exe_id}" : "";
        $where_sql .= isset($this->p_dp_cycle_exe_id) && $this->p_dp_cycle_exe_id != null ? " AND  M.CYCLE_EXE_ID ={$this->p_dp_cycle_exe_id}" : "";
        $where_sql .= isset($this->p_dp_department_exe_id) && $this->p_dp_department_exe_id != null ? " AND  M.DEPARTMENT_EXE_ID ={$this->p_dp_department_exe_id}" : "";


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('activities_plan_tb', $where_sql);
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

        $offset = (((($page) - 1) * $config['per_page']));
        $row = ((($page) * $config['per_page']));
        //echo $where_sql;
        $data['page_rows'] = $this->{$this->MODEL_NAME}->stratigic_list($where_sql, $offset, $row);
        $maxyear = array();
        foreach ($data['page_rows'] as $max) {
            $maxyear[] = $max['EXE_YEARS'];
        }


        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        //$data['year_paln']=$this->year;
        $data["YEAR"] = date('Y') + 1;
        $data['from_year'] = $this->p_FROM_YEAR;
        $data['to_year'] = $this->p_TO_YEAR;
        if (empty($maxyear))
            $data['max'] = 0;
        else
            $data['max'] = max($maxyear);
        $this->_look_ups($data);

        //$this->load->view('planning_page', $data);


        // $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list(' AND 1=1 ', 0, 1000);


        $this->load->view('Strategic_planning_page', $data);


    }

    function public_get_stratgic_projects($id = 0)
    {
        //  var_dump($id);
        //    die;
        //$data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_stratgic_projects($id);
        //$this->_look_ups($data);
        //$data['adopt']=$adopt;
        $this->load->view('stratgic_projects_details', $data);
    }

    function public_get_monthes($id, $month)
    {
        $months = $this->{$this->DETAILS_MODEL_NAME}->get_monthes($id, $month);
        return $months;

    }

    function public_get_details($id = 0)
    {
        //  var_dump($id);
        //    die;
        //$data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        //$this->_look_ups($data);
        //$data['details']='';
        $this->load->view('plan_details', $data);
    }

    function public_get_id_json()
    {


        $id = $this->input->post('id');
        // $this->print_error($id);
        $result = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        //print_r($result);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function public_get_details_branch($id = 0)
    {
        //  var_dump($id);
        //    die;
        //$data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['content'] = 'plan_branch_details';
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        $data['id'] = $id;
        $this->_look_ups($data);
        //$data['details']='';
        $this->load->view('template/modal', $data);
    }

    function public_get_achive($id = 0)
    {
        //  var_dump($id);
        //    die;
        $data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['id'] = $id;
        // $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        //$this->_look_ups($data);
        //$data['details']='';
        $this->load->view('achive_details', $data);
    }

    function public_get_mange($branch = 1)
    {


        $branch = $this->input->post('no') ? $this->input->post('no') : $branch;

        $arr = $this->Gcc_structure_model->get_Structure_branch(1, $branch);


        echo json_encode($arr);


    }

    function public_get_mange_b($branch = 1)
    {


        $branch = $this->input->post('no') ? $this->input->post('no') : $branch;

        $arr = $this->Gcc_structure_model->get_Structure_branch(1, $branch);


        return $arr;

    }

    function public_get_cycle($manage = 0)
    {


        $manage = $this->input->post('no') ? $this->input->post('no') : $manage;
        //var_dump($manage);
        $arr = $this->Gcc_structure_model->getList2($manage, 0);


        echo json_encode($arr);


    }

    function public_get_cycle_b($manage = 0)
    {

//var_dump($manage);
        //   die;
        //$manage = $this->input->post('no')?$this->input->post('no'):$manage;
        //var_dump($manage);
        //  die;
        $arr = $this->Gcc_structure_model->getList2($manage, 0);


        return $arr;

    }

    function public_get_dep($cycle = 0)
    {


        $cycle = $this->input->post('no') ? $this->input->post('no') : $cycle;

        $arr = $this->Gcc_structure_model->getList2($cycle, 0);


        echo json_encode($arr);


    }

    function public_get_dep_p($cycle = 0)
    {


        $cycle = $this->input->post('no') ? $this->input->post('no') : $cycle;

        $arr = $this->Gcc_structure_model->getList2($cycle, 0);


        return $arr;


    }

    function public_get_goal($objective = 0, $year = 0)
    {


        $objective = $this->input->post('no') ? $this->input->post('no') : $objective;
        $year = $this->input->post('year') ? $this->input->post('year') : $year;
        $arr = $this->{$this->MODEL_NAME}->get_objective('', $objective, $year);


        echo json_encode($arr);


    }

    function public_get_goal_p($objective = '', $year)
    {


        $arr = $this->{$this->MODEL_NAME}->get_objective('', $objective, $year);


        return $arr;


    }

    function public_get_projects_by_year($year = 0)
    {


        $year = $this->input->post('year') ? $this->input->post('year') : $year;


        if ($this->user->branch == 1) {

            $arr = $this->{$this->MODEL_NAME}->get_project(null, $year);
        } else {

            $arr = $this->{$this->MODEL_NAME}->get_project($this->user->branch, $year);
        }


        echo json_encode($arr);


    }

    function create_edit_sub_activites()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_ser); $i++) {
                if ($this->p_ser[$i] <= 0 || $this->p_ser[$i] == '') {
                    if ($this->p_branch[$i] != '') {


                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_sub_activites($this->_postData_sub_activities(null, $this->p_activity_no[$i], $this->p_planning_dir[$i], $this->p_branch[$i], $this->p_manage_id[$i], $this->p_cycle_id[$i], $this->p_department_id[$i], $this->p_activity_name[$i], $this->p_from_month[$i], $this->p_to_month[$i], $this->p_weight[$i],
                            'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);

                    }

                } else {


                    if ($this->p_branch[$i] != '') {

                        /*if ($this->p_part[$i] == '') {
                            $this->print_error('يجب ان ادخال حصة المقر');
                        }*/

                        $x = $this->{$this->DETAILS_MODEL_NAME}->edit_sub_activites($this->_postData_sub_activities($this->p_ser[$i], $this->p_activity_no[$i], $this->p_planning_dir[$i], $this->p_branch[$i], $this->p_manage_id[$i], $this->p_cycle_id[$i], $this->p_department_id[$i], $this->p_activity_name[$i], $this->p_from_month[$i], $this->p_to_month[$i], $this->p_weight[$i],
                            'edit'));


                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);

                    }


                }


            }

        }
    }
    /**********************************************************************************************************************************/
    /*                                                   sub_activities                                                                *
     *
    **********************************************************************************************************************************/

    function _postData_sub_activities($ser = null, $activity_no, $planning_dir = null, $branch = null, $manage_id = null, $cycle_id = null, $department_id = null, $activity_name = null, $from_month = null, $to_month = null, $weight = null, $type = null)

    {


        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITIES_PLAN_SER', 'value' => $activity_no, 'type' => '', 'length' => -1),
            array('name' => 'PLANNING_DIR', 'value' => $planning_dir, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $branch, 'type' => '', 'length' => -1),
            array('name' => 'MANAGE_ID', 'value' => $manage_id, 'type' => '', 'length' => -1),
            array('name' => 'CYCLE_ID', 'value' => $cycle_id, 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMENT_ID', 'value' => $department_id, 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITY_NAME', 'value' => $activity_name, 'type' => '', 'length' => -1),
            array('name' => 'FROM_MONTH', 'value' => $from_month, 'type' => '', 'length' => -1),
            array('name' => 'TO_MONTH', 'value' => $to_month, 'type' => '', 'length' => -1),
            array('name' => 'WEIGHT', 'value' => $weight, 'type' => '', 'length' => -1),


        );


        if ($type == 'create')
            unset($result[0]);


        return $result;
    }

    function public_get_sub_activities($objective = 0)
    {


        $objective = $this->input->post('no') ? $this->input->post('no') : $objective;

        $arr = $this->{$this->DETAILS_MODEL_NAME}->get_details_activites_all($objective);

//var_dump($arr);

        echo json_encode($arr);


    }

    function public_get_activities_details($id = 0, $adopt = 1)
    {
        //  var_dump($id);
        //    die;
        //$data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_activites_all($id);
        //$this->_look_ups($data);
        $data['adopt'] = $adopt;
        $this->load->view('plan_activites_details', $data);
    }
    /**************************************************************************************************************************************/
    /*  evaluate*/
    /**********************************************************************************************************************************/
    function evaluate()
    {


        $result = array();
        $data['planning_data'] = $result;
        $data['content'] = 'evaluate_plan';
        // $data['title'] = '   الخطة التشغلية   ::.  تقييم الخطة الشهرية';
        $data['title'] = '   الخطة التشغلية   ::.  تقيم انجاز الخطة الشهرية';
        $data['action'] = 'index';
        $data['help'] = $this->help;
        $data['isCreate'] = true;
        $data['branch_user'] = $this->user->branch;
        $data['tech'] = 1;


        /* if ($this->user->branch==1)
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4));
         else
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4),$this->user->branch);

         if (!(count($data['project']) == 1))
         {
             echo 'not in your permissions';
             die();

         }*/


        $this->_look_ups($data);

        $this->load->view('template/template', $data);


    }


    function get_page_evaluate($page = 1)
    {


        $from_month = isset($this->p_from_month) && $this->p_from_month != null ? $this->p_from_month : date('m');
        $data['page_rows'] = $this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month, $this->year);
        //echo var_dump($this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year));
        // die;
        $this->_look_ups($data);


        $this->load->view('planning_evaluate_page', $data);


    }

    function public_evaluate_project($id = 0)
    {
        //  var_dump($id);
        //    die;
        //$data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['content'] = 'plan_evaluate_details';
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        $data['id'] = $id;
        $this->_look_ups($data);
        //$data['details']='';
        $this->load->view('template/modal', $data);
    }

    function save_all_evaluate()
    {
        $x = 0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_persant); $i++) {
                if ($this->p_ser_plan[$i] <= 0 and $this->p_status[$i] != '' and $this->p_is_end[$i] and $this->p_persant[$i] != '') {

                    if ($this->p_status[$i] != 3) {

                        if ($this->p_notes[$i] == '') {
                            $this->print_error('في حال الخطة الغير منجزة يجب ادخال مبرر عدم الانجاز');
                            die;
                        }
                    } else if ($this->p_status[$i] == 3) {
                        if ($this->p_notes[$i] != '') {
                            $this->print_error('الخطة منجزة لا يحتاج لادخال مبرر');
                            //$this->p_notes[$i]='';
                            die;
                        }
                    }
                    $x = $this->{$this->DETAILS_MODEL_NAME}->create_achive_evaluate($this->_postData_achive_details(null, $this->p_seq[$i], $this->p_status[$i], $this->p_persant[$i], $this->p_notes[$i], $this->p_is_end[$i],
                        'create'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }

                } else if ($this->p_ser_plan[$i] > 0 and $this->p_status[$i] != '' and $this->p_is_end[$i] and $this->p_persant[$i] != '') {

                    if ($this->p_status[$i] != 3) {

                        if ($this->p_notes[$i] == '') {
                            $this->print_error('في حال الخطة الغير منجزة يجب ادخال مبرر عدم الانجاز');
                            die;
                        }
                    } else if ($this->p_status[$i] == 3) {
                        if ($this->p_notes[$i] != '') {
                            $this->print_error('الخطة منجزة لا يحتاج لادخال مبرر');
                            //$this->p_notes[$i]='';
                            die;
                        }
                    }
                    $x = $this->{$this->DETAILS_MODEL_NAME}->edit_achive_evaluate($this->_postData_achive_details($this->p_ser[$i], $this->p_ser_plan[$i], $this->p_status[$i], $this->p_persant[$i], $this->p_notes[$i], $this->p_is_end[$i],
                        'edit'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

            }
            echo intval($x);
        }

    }

    function _postData_achive_details($ser = null, $ser_plan, $status = null, $persant = null, $notes = null, $is_end = null, $type = null)

    {


        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'SER_PLAN', 'value' => $ser_plan, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $status, 'type' => '', 'length' => -1),
            array('name' => 'PERSANT', 'value' => $persant, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $notes, 'type' => '', 'length' => -1),
            array('name' => 'IS_END', 'value' => $is_end, 'type' => '', 'length' => -1),


        );


        if ($type == 'create')
            unset($result[0]);


        return $result;
    }

    /**************************************************************************************************************************************/
    /*  refresh*/
    /**********************************************************************************************************************************/

    function adopt_all_evaluate()
    {
        $x = '0';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_ser); $i++) {


                if ($this->p_ser[$i] != '' and $this->p_ser_plan[$i] != '' and $this->p_ser_status[$i] != '' and $this->p_ser_next_month_val[$i] != '') {
                    //if($this->p_ser[$i] !='') {

                    $x = $this->{$this->MODEL_NAME}->activities_plan_achive_adopt($this->p_ser[$i], $this->p_ser_plan[$i], $this->p_ser_status[$i], $this->p_ser_next_month_val[$i]);//$this->{$this->DETAILS_MODEL_NAME}->activities_plan_refrech_update($this->p_ser[$i],$this->p_from_month[$i]);

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

            }
            echo intval($x);
        }

    }

    function refresh()
    {
        $result = array();
        $data['planning_data'] = $result;
        $data['content'] = 'refresh_plan';
        $data['title'] = '   الخطة التشغلية   ::.  تحديث الخطة الشهرية';
        $data['action'] = 'index';
        $data['help'] = $this->help;
        $data['isCreate'] = true;
        $data['tech'] = 1;


        /* if ($this->user->branch==1)
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4));
         else
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4),$this->user->branch);

         if (!(count($data['project']) == 1))
         {
             echo 'not in your permissions';
             die();

         }*/


        $this->_look_ups($data);

        $this->load->view('template/template', $data);

    }

    function get_page_refresh($page = 1)
    {


        $data['page_rows'] = $this->{$this->MODEL_NAME}->activities_plan_refrech_get($this->year);

        $this->_look_ups($data);


        $this->load->view('planning_refresh_page', $data);


        // $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list(' AND 1=1 ', 0, 1000);


        // $this->load->view('planning_page', $data);
    }

    function save_all_refresh()
    {
        $x = '0';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_from_month); $i++) {


                if ($this->p_from_month[$i] != '') {


                    $x = $this->{$this->DETAILS_MODEL_NAME}->activities_plan_refrech_update($this->p_ser[$i], $this->p_from_month[$i]);

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

            }
            echo intval($x);
        }


    }
    /**************************************************************************************************************************************/
    /*  follow*/
    /**********************************************************************************************************************************/

    function adopt_all_refresh()
    {


        $x = '0';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_ser); $i++) {


                if ($this->p_ser[$i] != '' and $this->p_ser_plan[$i] != '' and $this->p_for_month[$i] != '') {

                    $x = $this->{$this->MODEL_NAME}->activities_plan_refrech_adopt($this->p_ser[$i], $this->p_ser_plan[$i], $this->p_for_month[$i]);//$this->{$this->DETAILS_MODEL_NAME}->activities_plan_refrech_update($this->p_ser[$i],$this->p_from_month[$i]);

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

            }
            echo intval($x);
        }


    }

    function follow()
    {


        $result = array();
        $data['planning_data'] = $result;
        $data['content'] = 'follow_plan';
        $data['title'] = '   الخطة التشغلية   ::.  متابعة الخطة الشهرية';
        $data['action'] = 'index';
        $data['help'] = $this->help;
        $data['isCreate'] = true;
        $data['branch_user'] = $this->user->branch;
        $data['tech'] = 1;


        /* if ($this->user->branch==1)
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4));
         else
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4),$this->user->branch);

         if (!(count($data['project']) == 1))
         {
             echo 'not in your permissions';
             die();

         }*/


        $this->_look_ups($data);

        $this->load->view('template/template', $data);


    }

    function get_page_follow($page = 1)
    {


        $from_month = isset($this->p_from_month) && $this->p_from_month != null ? $this->p_from_month : date('m');
        $data['page_rows'] = $this->{$this->MODEL_NAME}->activities_plan_follow_list($from_month, $this->year);
        //echo var_dump($this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year));
        // die;
        $this->_look_ups($data);


        // $this->load->view('planning_follow_page', $data);
        $this->load->view('planning_follow_page', $data);


    }

    /********************************************************************************************************************************/

    function public_follow_project($id = 0, $from_month = 0, $to_month = 0)
    {
        //  var_dump($id);
        //    die;
        //$data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['content'] = 'plan_follow_details';
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_activites_all($id);
        $data['id'] = $id;
        $data['main_from_month'] = $from_month;
        $data['main_to_month'] = $to_month;
        $this->_look_ups($data);
        //$data['details']='';
        $this->load->view('template/modal', $data);
    }

    function adopt()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_id != '' || $this->p_id != 0)) {
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id, $this->p_adopts);

            if (intval($res) <= 0) {
                echo intval($res);
            } else
                echo intval($res);

        } else
            echo "لم يتم ارسال رقم الخطة التشغيلية";
    }

    function public_get_id()
    {


        $id = $this->input->post('id');
        // $this->print_error($id);
        $result = $this->{$this->MODEL_NAME}->get_project_info($id);
        //print_r($result);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }


    function create_edit_plan()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_seq == '' || $this->p_seq == 0)) {
                $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData_no_teq_proj_mix('create'));
                /*for ($i = $this->input->post('from_month'); $i <= $this->input->post('to_month'); $i++)
                {
                $x=$this->{$this->MODEL_NAME}->create_achive_month($this->_postData_details_achive('',$this->ser,$i, '','create'));
                }
                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }*/

            } else {
                $this->ser = $this->{$this->MODEL_NAME}->edit($this->_postedData_no_teq_proj_mix());
                /* for ($i = $this->input->post('from_month'); $i < $this->input->post('to_month'); $i++)
                 {
                     $x=_postData_details_achive('',$this->ser,$this->input->post('from_month'), '','');
                 }
                 if (intval($x) <= 0) {
                     $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                 }*/


            }

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }


            echo intval($this->ser);

        }

    }

    function create()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // $this->_post_validation();

            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData_withoutcost('create'));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }


            echo intval($this->ser);

        } else {

            $result = array();
            $achive_res = array();
            //$data['year_paln']=date('Y')+1;//$this->year;
            $data['YEAR'] = date('Y') + 1;
            $data['planning_data'] = $result;
            $data['achive_data'] = $achive_res;
            //$data['content'] = 'planning_show_without_cost';
            $data['content'] = 'planning_Strategic_projects';
            $data['title'] = '  موازنة المشاريع  ::.  الخطة الإستراتيجية   ::. ';
            $data['action'] = 'index';
            $data['help'] = $this->help;
            $data['isCreate'] = true;
            $data['tech'] = 0;
            if ($this->uri->segment(4) != '') {
                $data['have_project_ser'] = true;

                if ($this->user->branch == 1)
                    $data['project'] = $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4));
                else
                    $data['project'] = $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4), $this->user->branch);

                if (!(count($data['project']) == 1)) {
                    echo 'not in your permissions';
                    die();

                }

            } else {
                $data['have_project_ser'] = false;
                $data['project'] = array();
            }
            $this->_look_ups($data);

            $this->load->view('template/template', $data);
        }

    }
    /**************************************************************************************************************************************/
    /*  weight */
    /**********************************************************************************************************************************/

    function _postedData_withoutcost($typ = null)
    {


        $result = array(
            array('name' => 'SEQ', 'value' => $this->input->post('seq'), 'type' => '', 'length' => -1),
            array('name' => 'YEAR', 'value' => $this->input->post('year'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS', 'value' => $this->input->post('class_name'), 'type' => '', 'length' => -1),
            array('name' => 'TYPE', 'value' => $this->input->post('type'), 'type' => '', 'length' => -1),
            array('name' => 'OBJECTIVE', 'value' => $this->input->post('objective'), 'type' => '', 'length' => -1),
            array('name' => 'GOAL', 'value' => $this->input->post('goal'), 'type' => '', 'length' => -1),
            array('name' => 'GOAL_T', 'value' => $this->input->post('goal_t'), 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITY_NAME', 'value' => $this->input->post('activity_name1'), 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_ID', 'value' => $this->input->post('project_id'), 'type' => '', 'length' => -1),
            array('name' => 'FINANCE', 'value' => $this->input->post('finance'), 'type' => '', 'length' => -1),
            array('name' => 'FINANCE_NAME', 'value' => $this->input->post('finance_name'), 'type' => '', 'length' => -1),
            array('name' => 'MANAGE_FOLLOW_ID', 'value' => $this->input->post('manage_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'CYCLE_FOLLOW_ID', 'value' => $this->input->post('cycle_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMENT_FOLLOW_ID', 'value' => $this->input->post('department_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'MANAGE_EXE_ID', 'value' => $this->input->post('manage_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'CYCLE_EXE_ID', 'value' => $this->input->post('cycle_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMENT_EXE_ID', 'value' => $this->input->post('department_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'FROM_MONTH', 'value' => $this->input->post('from_month'), 'type' => '', 'length' => -1),
            array('name' => 'TO_MONTH', 'value' => $this->input->post('to_month'), 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_FOLLOW_ID', 'value' => $this->input->post('branch_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_EXE_ID', 'value' => $this->input->post('branch_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'TOTAL_PRICE', 'value' => $this->input->post('total_price'), 'type' => '', 'length' => -1),
            array('name' => 'TYPE_PROJECT', 'value' => $this->input->post('type_project'), 'type' => '', 'length' => -1),
            array('name' => 'INCOME', 'value' => $this->input->post('income'), 'type' => '', 'length' => -1),
            array('name' => 'TARGET', 'value' => $this->input->post('target'), 'type' => '', 'length' => -1),
            array('name' => 'SCALE', 'value' => $this->input->post('scale'), 'type' => '', 'length' => -1),
            array('name' => 'UNIT', 'value' => $this->input->post('unit'), 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->input->post('notes'), 'type' => '', 'length' => -1),
            array('name' => 'FROM_YEAR', 'value' => $this->input->post('from_year'), 'type' => '', 'length' => -1),
            array('name' => 'TO_YEAR', 'value' => $this->input->post('to_year'), 'type' => '', 'length' => -1),
            array('name' => 'STRATGIC_TOTAL_PRICE', 'value' => $this->input->post('stratgic_total_price'), 'type' => '', 'length' => -1),
            array('name' => 'STRATGIC_INCOME', 'value' => $this->input->post('stratgic_income'), 'type' => '', 'length' => -1),


        );


        // return $result;


        if ($typ == 'create') {
            array_shift($result);
        }

        return $result;
    }

    function _postedData_no_teq_proj_mix($typ = null, $activity_name = '', $detailes = '', $status = 1)
    {


        $result = array(
            array('name' => 'SEQ', 'value' => $this->input->post('seq'), 'type' => '', 'length' => -1),
            array('name' => 'YEAR', 'value' => $this->year, 'type' => '', 'length' => -1),
            array('name' => 'CLASS', 'value' => $this->input->post('class_name'), 'type' => '', 'length' => -1),
            array('name' => 'TYPE', 'value' => $this->input->post('type'), 'type' => '', 'length' => -1),
            array('name' => 'OBJECTIVE', 'value' => $this->input->post('objective'), 'type' => '', 'length' => -1),
            array('name' => 'GOAL', 'value' => $this->input->post('goal'), 'type' => '', 'length' => -1),
            array('name' => 'GOAL_T', 'value' => $this->input->post('goal_t'), 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITY_NAME', 'value' => $this->input->post('activity_name'), 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_ID', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'FINANCE', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'FINANCE_NAME', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'MANAGE_FOLLOW_ID', 'value' => $this->input->post('manage_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'CYCLE_FOLLOW_ID', 'value' => $this->input->post('cycle_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMENT_FOLLOW_ID', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'MANAGE_EXE_ID', 'value' => $this->input->post('manage_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'CYCLE_EXE_ID', 'value' => $this->input->post('cycle_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMENT_EXE_ID', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'FROM_MONTH', 'value' => $this->input->post('from_month'), 'type' => '', 'length' => -1),
            array('name' => 'TO_MONTH', 'value' => $this->input->post('to_month'), 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_FOLLOW_ID', 'value' => $this->input->post('branch_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_EXE_ID', 'value' => $this->input->post('branch_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'TOTAL_PRICE', 'value' => $this->input->post('total_price'), 'type' => '', 'length' => -1),


        );


        // return $result;


        if ($typ == 'create') {
            array_shift($result);
        }
        return $result;
    }
    /**************************************************************************************************************************************/
    /*  collaboration */
    /**********************************************************************************************************************************/

    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$this->_post_validation('edit');


            $this->ser = $this->{$this->MODEL_NAME}->Stratgic_edit($this->_postedData_withoutcost());


            //var_dump($this->_postedData_withoutcost());

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }
            if (isset($this->p_stratgic_year)) {
                for ($i = 0; $i < count($this->p_stratgic_year); $i++) {
                    $x = $this->{$this->DETAILS_MODEL_NAME}->edit_($this->_postData_price_income($this->p_stratigic_seq[$i], $this->p_stratigic_price_det[$i], $this->p_stratigic_income_det[$i]), '');

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }


            }
            echo intval($this->ser);
        }
    }

    function _postData_price_income($stratigic_seq, $stratigic_price_det, $stratigic_income_det, $type = null)

    {


        $result = array(
            array('name' => 'SEQ', 'value' => $stratigic_seq, 'type' => '', 'length' => -1),
            array('name' => 'TOTAL_PRICE_BUDGET', 'value' => $stratigic_price_det, 'type' => '', 'length' => -1),
            array('name' => 'INCOME', 'value' => $stratigic_income_det, 'type' => '', 'length' => -1),
        );


        if ($type == 'create')
            unset($result[0]);


        return $result;
    }

    function create_edit_part()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_ser1); $i++) {
                if ($this->p_ser1[$i] <= 0 || $this->p_ser1[$i] == '') {
                    if ($this->p_branch[$i] != '') {

                        if ($this->p_part[$i] == '') {
                            $this->print_error('يجب ان ادخال حصة المقر');
                        }

                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_part($this->_postData_details(null, $this->p_activity_no_id[$i], $this->p_branch[$i], $this->p_part[$i],
                            'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);

                    }

                } else {


                    if ($this->p_branch[$i] != '') {

                        if ($this->p_part[$i] == '') {
                            $this->print_error('يجب ان ادخال حصة المقر');
                        }

                        $x = $this->{$this->DETAILS_MODEL_NAME}->edit_part($this->_postData_details($this->p_ser1[$i], $this->p_activity_no_id[$i], $this->p_branch[$i], $this->p_part[$i],
                            'edit'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);

                    }


                }


            }

        }
    }

    function _postData_details($ser = null, $activity_no, $branch = null, $part = null, $type = null)

    {


        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITY_NO', 'value' => $activity_no, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $branch, 'type' => '', 'length' => -1),
            array('name' => 'PART', 'value' => $part, 'type' => '', 'length' => -1),


        );


        if ($type == 'create')
            unset($result[0]);


        return $result;
    }

    /****************/

    function weight_projects()
    {


        $result = array();
        $data['planning_data'] = $result;
        $data['content'] = 'weight_project_plan';
        $data['title'] = '  الخطة التشغلية   ::.  أوزان المشاريع للادارات';
        $data['action'] = 'index';
        $data['help'] = $this->help;
        $data['isCreate'] = true;
        $data['branch_user'] = $this->user->branch;
        $data['tech'] = 1;


        /* if ($this->user->branch==1)
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4));
         else
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4),$this->user->branch);

         if (!(count($data['project']) == 1))
         {
             echo 'not in your permissions';
             die();

         }*/


        $this->_look_ups($data);

        $this->load->view('template/template', $data);


    }


    /*****************************************************************************************************************************/

    function get_page_weight($page = 1)
    {

//echo isset($this->p_branch_exe_id);
        $from_month = isset($this->p_branch_exe_id) && $this->p_branch_exe_id != null ? 1 : 1;
        $data['page_rows'] = $this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month, $this->year);
        //echo var_dump($this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year));
        // die;
        $this->_look_ups($data);


        $this->load->view('planning_weight_page', $data);


    }

    function collaboration()
    {


        $result = array();
        $data['planning_data'] = $result;
        $data['content'] = 'planning_collaboration';
        $data['title'] = '  الخطة التشغلية   ::.  المشاريع المشتركة';
        $data['action'] = 'index';
        $data['help'] = $this->help;
        $data['isCreate'] = true;
        $data['branch_user'] = $this->user->branch;
        $data['tech'] = 1;
        $data['year_paln'] = $this->year;


        /* if ($this->user->branch==1)
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4));
         else
             $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4),$this->user->branch);

         if (!(count($data['project']) == 1))
         {
             echo 'not in your permissions';
             die();

         }*/


        $this->_look_ups($data);

        $this->load->view('template/template', $data);


    }

    function get_page_collaboration($page = 1)
    {

//echo isset($this->p_branch_exe_id);
        $from_month = isset($this->p_branch_exe_id) && $this->p_branch_exe_id != null ? 1 : 1;
        //$data['page_rows'] = $this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year);
        $data['page_rows'] = array();
        //echo var_dump($this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year));
        // die;
        $this->_look_ups($data);


        $this->load->view('planning_collaboration_page', $data);


    }

    function public_get_seq($stratgic_ser, $year)
    {
        $a = array();
        $a = $this->{$this->MODEL_NAME}->GET_SEQ($stratgic_ser, $year);

        return $a;
    }

    function public_collobration_project($id = 0)
    {
        //  var_dump($id);
        //    die;
        //$data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['content'] = 'plan_collobration_details';
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        $data['id'] = $id;
        $this->_look_ups($data);
        //$data['details']='';
        $this->load->view('template/modal', $data);
    }

    function _post_validation($type = null)
    {
        $w_count = 0;
        if ($type != null) {
            $result = $this->{$this->MODEL_NAME}->get($this->p_seq);
            if ($result[0]['YEAR'] != $this->p_year) {

                $this->print_error(' لا يمكن تغير عام الخطة!!');

            }

        }

        if (count($this->p_exe_time) == 0) {


            $this->print_error(' يجب ادخال مدة التنفيذ على مدار ال12 شهر!!');

        }
        if ($this->p_type == 3) {
            if (count($this->p_ser) == 0) {
                $this->print_error(' يجب ادخال الأنشطة الفرعية!!');
            } else {
                for ($i = 0; $i < count($this->p_ser); $i++) {
                    if ($this->p_activity_no[$i] == '' || $this->p_activity_no[$i] == 0) {
                        $this->print_error('  !!');
                    }

                    if ($this->p_planning_dir[$i] == '' || $this->p_planning_dir[$i] == 0) {
                        $this->print_error('يجب ادخال الجهة !!');
                    }

                    if ($this->p_branch[$i] == '' || $this->p_branch[$i] == 0) {
                        $this->print_error('يجب ادخال المقر !!');
                    }

                    if ($this->p_manage_id[$i] == '' || $this->p_manage_id[$i] == 0) {

                        $this->print_error('يجب ادخال الادارة !!');
                    }

                    if ($this->p_cycle_id[$i] == '' || $this->p_cycle_id[$i] == 0) {
                        $this->print_error('يجب ادخال الدائرة !!');
                    }
                    if ($this->p_activity_name[$i] == '' || $this->p_activity_name[$i] == 0) {
                        $this->print_error('يجب ادخال اسم المشروع/نشاط !!');
                    }
                    if ($this->p_from_month[$i] == '' || $this->p_from_month[$i] == 0) {
                        $this->print_error('يجب ادخال من الشهر !!');
                    }
                    if ($this->p_to_month[$i] == '' || $this->p_to_month[$i] == 0) {
                        $this->print_error('يجب ادخال إلى الشهر !!');
                    }
                    if ($this->p_weight[$i] == '' || $this->p_weight[$i] == 0) {
                        $this->print_error('يجب ادخال الوزن !!');
                    }

                    if ($this->p_weight[$i] != '' || $this->p_weight[$i] != 0) {
                        $w_count += $this->p_weight[$i];
                    }

                }
                if ($w_count != 100) {
                    $this->print_error('يجب أن يكون مجموع الاوزان يساوي 100 !!');
                }
            }
        } else {

            if (isset($this->p_branch)) {
                if (count($this->p_branch) != 0) {
                    for ($i = 0; $i < count($this->p_branch); $i++) {
                        if ($type != null) {
                            if ($this->p_activity_no[$i] == '' || $this->p_activity_no[$i] == 0) {
                                $this->print_error('  !!');
                            }
                        }
                        if ($this->p_planning_dir[$i] == '' || $this->p_planning_dir[$i] == 0) {
                            $this->print_error('يجب ادخال الجهة !!');
                        }

                        if ($this->p_branch[$i] == '' || $this->p_branch[$i] == 0) {
                            $this->print_error('يجب ادخال المقر !!');
                        }

                        if ($this->p_manage_id[$i] == '' || $this->p_manage_id[$i] == 0) {

                            $this->print_error('يجب ادخال الادارة !!');
                        }

                        if ($this->p_cycle_id[$i] == '' || $this->p_cycle_id[$i] == 0) {
                            $this->print_error('يجب ادخال الدائرة !!');
                        }
                        if ($this->p_activity_name[$i] == '') {
                            $this->print_error('يجب ادخال اسم المشروع/نشاط !!');
                        }
                        if ($this->p_main_from_month[$i] == '') {
                            $this->print_error('يجب ادخال من الشهر !!');
                        }
                        if ($this->p_main_to_month[$i] == '') {
                            $this->print_error('يجب ادخال إلى الشهر !!');
                        }

                        if ($this->p_weight[$i] == '' || $this->p_weight[$i] == 0) {
                            $this->print_error('يجب ادخال الوزن !!');
                        }
                        if ($this->p_weight[$i] != '' || $this->p_weight[$i] != 0) {
                            $w_count += $this->p_weight[$i];
                        }


                    }
                    if ($w_count != 100) {
                        $this->print_error('يجب أن يكون مجموع الاوزان يساوي 100 !!');
                    }
                }
            }
        }
    }

    function _postedData($typ = null, $activity_name = '', $detailes = '', $status = 1)
    {

        if ($this->input->post('type') == 1)
            $project_id = '';
        else if ($this->input->post('type') == 2)
            $project_id = $this->input->post('project_id');


        $result = array(
            array('name' => 'SEQ', 'value' => $this->input->post('seq'), 'type' => '', 'length' => -1),
            array('name' => 'YEAR', 'value' => $this->input->post('year'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS', 'value' => $this->input->post('class'), 'type' => '', 'length' => -1),
            array('name' => 'TYPE', 'value' => $this->input->post('type'), 'type' => '', 'length' => -1),
            array('name' => 'OBJECTIVE', 'value' => $this->input->post('objective'), 'type' => '', 'length' => -1),
            array('name' => 'GOAL', 'value' => $this->input->post('goal'), 'type' => '', 'length' => -1),
            array('name' => 'GOAL_T', 'value' => $this->input->post('goal_t'), 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITY_NAME', 'value' => $activity_name, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_ID', 'value' => $this->input->post('project_id'), 'type' => '', 'length' => -1),
            array('name' => 'FINANCE', 'value' => $this->input->post('finance'), 'type' => '', 'length' => -1),
            array('name' => 'FINANCE_NAME', 'value' => $this->input->post('finance_name'), 'type' => '', 'length' => -1),
            array('name' => 'MANAGE_FOLLOW_ID', 'value' => $this->input->post('manage_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'CYCLE_FOLLOW_ID', 'value' => $this->input->post('cycle_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMENT_FOLLOW_ID', 'value' => $this->input->post('department_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'MANAGE_EXE_ID', 'value' => $this->input->post('manage_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'CYCLE_EXE_ID', 'value' => $this->input->post('cycle_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMENT_EXE_ID', 'value' => $this->input->post('department_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'FROM_MONTH', 'value' => $this->input->post('from_month'), 'type' => '', 'length' => -1),
            array('name' => 'TO_MONTH', 'value' => $this->input->post('to_month'), 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_FOLLOW_ID', 'value' => $this->input->post('branch_follow_id'), 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_EXE_ID', 'value' => $this->input->post('branch_exe_id'), 'type' => '', 'length' => -1),
            array('name' => 'TOTAL_PRICE', 'value' => '', 'type' => '', 'length' => -1),


        );


//var_dump($result);

        // return $result;


        if ($typ == 'create') {
            array_shift($result);
        }
        return $result;
    }

    function _postData_details_achive($ser = null, $activities_plan_ser, $month = null, $achive = null, $type = null)

    {


        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITIES_PLAN_SER', 'value' => $activities_plan_ser, 'type' => '', 'length' => -1),
            array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1),
            array('name' => 'ACHIVE', 'value' => $achive, 'type' => '', 'length' => -1),


        );


        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function _postData_monthes($ser, $month, $type = null)

    {
        $result = array(
            array('name' => 'SER_PLAN', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1),


        );


        /*if ($type == 'create')
            unset($result[0]);
*/


        return $result;

    }

}

?>
