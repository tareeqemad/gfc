<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/11/17
 * Time: 09:22 ص
 */
class planning extends MY_Controller
{

    var $MODEL_NAME = 'plan_model';
    var $DETAILS_MODEL_NAME = 'plan_detail_model';
    var $PAGE_URL = 'planning/planning/get_page';
    var $PAGE_EVALUATE_URL = 'planning/planning/get_page_evaluate';
    var $PAGE_REFRESH_URL = 'planning/planning/get_page_refresh';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->load->model('settings/users_model');
        $this->year = $this->budget_year;
        $this->ser = $this->input->post('ser');

    }

    function index($page = 1)
    {


        $data['title'] = 'الخطة السنوية / التشغيلية';
        $data['help'] = $this->help;
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data["YEAR"] = date('Y') + 1;
        $this->_look_ups($data);
        $data['tech'] = 1;
        $data['year_paln'] = $data["YEAR"] = date('Y'); //+ 1;//$this->year;

        $data['content'] = 'planning_index';
        $data['page'] = $page;
        // $data['all_year']= $this->{$this->MODEL_NAME}->GET_YEAR();
        // $data['all_project']= $this->{$this->MODEL_NAME}->get_project();

        $this->load->view('template/template', $data);
    }

    function _look_ups(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap.min.js');
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


        add_js('bootstrap.min.js');
        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');


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
        $data['dp_type_emp'] = $this->constant_details_model->get_list(514);

        $data['user_info'] = $this->users_model->get_user_info($this->user->id);


        if ($this->user->branch == 1) {

            $data['branches_follow'] = $this->gcc_branches_model->get_all();//$this->gcc_branches_model->user_branch($this->user->id);
            $data['branches'] = $this->gcc_branches_model->get_all();
            $data['exe_branches'] = $this->gcc_branches_model->user_branch($this->user->id);
            $data['all_project'] = $this->{$this->MODEL_NAME}->get_project(null, $data["YEAR"]);
            // $data['branches'] = $this->gcc_branches_model->user_branch($this->user->id);
        } else {
            $data['branches_follow'] = $this->gcc_branches_model->get(1);//$this->gcc_branches_model->user_branch($this->user->id);
            $data['branches'] = $this->gcc_branches_model->user_branch($this->user->id);
            $data['all_project'] = $this->{$this->MODEL_NAME}->get_project($this->user->branch, $data["YEAR"]);
        }

        $data['all_objective'] = $this->{$this->MODEL_NAME}->get_objective('', 0, date('Y') + 1);


        //  $data['all_project']= $this->{$this->MODEL_NAME}->get_project();


    }

    function get($id)
    {
        if ($this->user->branch == 1)

            $result = $this->{$this->MODEL_NAME}->get($id); else
            $result = $this->{$this->MODEL_NAME}->get($id, $this->user->branch);

        if (!(count($result) == 1)) {
            echo 'not in your permissions';
            die();

        }
        $achive_res = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['planning_data'] = $result[0];
        $data['achive_data'] = $achive_res;
        $data['content'] = 'planning_show';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['have_project_ser'] = true;
        $data['tech'] = 1;
        $data['title'] = ' ادارة المشاريع الفنية السنوية';
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function tree()
    {

        $this->load->helper('generate_list');

        // add_css('jquery-hor-tree.css');
        add_css('combotree.css');
        add_css('tabs.css');
        add_js('jquery.tree.js');
        add_js('tabs.js');
        $data['year_paln'] = $this->year;
        $data['title'] = ' شجرة المشاريع ';
        $data['content'] = 'plan_tree_view';


        $resource = $this->_get_structure(0, $this->year);

        $options = array('template_head' => '<ul>', 'template_foot' => '</ul>', 'use_top_wrapper' => false);

        $template = '<li ><span data-id="{ID}" ondblclick="javascript:get_project(\'{ID}\',\'{IS_ACTIVE}\');"><i class="glyphicon glyphicon-minus-sign"></i><div0 data-is-active="{IS_ACTIVE}" class="is_active"> </div0> {ID_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="plan_tree">' . generate_list($resource, $options, $template) . '</ul>';
        // $data['tree'] = '<ul class="tree" id="accounts"><li class="parent_li"><span data-id="0" >شركة الكهرباء</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';


        $data['help'] = $this->help;

        $this->load->view('template/template', $data);
    }

    function _get_structure($parent, $year)
    {
        $result = $this->{$this->MODEL_NAME}->get_tree_goal_t($parent, $year);


        $i = 0;

        foreach ($result as $key => $item) {


            $result[$i]['subs'] = $this->_get_structure($item['ID'], $year);
            // var_dump($result[$i]['subs']);


            $i++;


        }


        return $result;
    }

    function get_goal_t_project()
    {

        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get_goal_t_project($id);
        $this->return_json($result);

    }

    /********************************************************************************************************************************/

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = " and adopt <>0 ";
        //$this->user->branch=7;
        $this_year = $this->year;//date('Y')+1;
        if ($this->p_branch == null && $this->p_dp_manage_exe_id == null && $this->p_dp_cycle_exe_id == null) {
        } else {
            if ($this->p_year == null) $this->print_error('يجب تحديد العام !!');

            else if ($this->p_branch == null) $this->print_error('يجب تحديد مسؤلية التنفيذ!!');

            else if ($this->p_dp_manage_exe_id == null) $this->print_error('يجب تحديد الإدارة !!');

            /*else if($this->p_dp_cycle_exe_id == null)
           $this->print_error('يجب تحديد الدائرة!!');*/
        }

        $where_sql .= isset($this->p_PROJECT_ID) && $this->p_PROJECT_ID != null ? " AND  M.PROJECT_ID  =(select PROJECT_SERIAL from PROJECTS_FILE_TB where PROJECT_TEC_CODE='{$this->p_PROJECT_ID}')  " : "";
        $where_sql .= isset($this->p_activity_no) && $this->p_activity_no != null ? " AND  M.ACTIVITY_NO ='{$this->p_activity_no}'  " : "";
        $where_sql .= isset($this->p_type_emp) && $this->p_type_emp != null ? " AND  M.TYPE_EMP ={$this->p_type_emp} " : "";
        $where_sql .= isset($this->p_year) && $this->p_year != null ? " AND  M.YEAR ={$this->p_year}  " : " AND  YEAR = {$this->p_year}";
        $where_sql .= isset($this->p_project_name) && $this->p_project_name != null ? " AND  PROJECT_NAME LIKE '%{$this->p_project_name}%' " : "";
        $where_sql .= isset($this->p_activity_name) && $this->p_activity_name != null ? " AND  DECODE(CLASS,1,(select P.PROJECT_NAME from gfc.projects_file_tb P WHERE P.PROJECT_SERIAL=M.PROJECT_ID),M.ACTIVITY_NAME) LIKE '%{$this->p_activity_name}%' " : "";
        $where_sql .= isset($this->p_class_name) && $this->p_class_name != null ? " AND  M.CLASS ={$this->p_class_name} " : "";
        $where_sql .= isset($this->p_type) && $this->p_type != null ? " AND  M.TYPE ={$this->p_type} " : "";
        $where_sql .= isset($this->p_planning_dir) && $this->p_planning_dir != null ? " AND  PLANNING_DIR ={$this->p_planning_dir} " : "";
        $where_sql .= isset($this->p_finance) && $this->p_finance != null ? " AND  DECODE(CLASS,1,PROJECTS_PKG.PROJECTS_FILE_DON_TYPE((select P.PROJECT_TEC_TYPE from gfc.projects_file_tb P WHERE P.PROJECT_SERIAL=M.PROJECT_ID)),M.FINANCE) ={$this->p_finance} " : "";
        $where_sql .= isset($this->p_branch) && $this->p_branch != null ? " AND BRANCH IN (SELECT BRANCH
                                   FROM PLAN.FOLLOW_PLANNING_DIR_TB
                                  WHERE ACTIVITIES_PLAN_SER = M.SEQ
                                    AND BRANCH ={$this->p_branch})" : "";
        $where_sql .= isset($this->p_from_month) && $this->p_from_month != null ? " AND  M.FROM_MONTH ={$this->p_from_month}" : "";
        $where_sql .= isset($this->p_branch_follow_id) && $this->p_branch_follow_id != null ? " AND  M.BRANCH_FOLLOW_ID ={$this->p_branch_follow_id}" : "";
        $where_sql .= isset($this->p_manage_follow_id) && $this->p_manage_follow_id != null ? " AND  M.MANAGE_FOLLOW_ID ={$this->p_manage_follow_id}" : "";
        $where_sql .= isset($this->p_dp_manage_exe_id) && $this->p_dp_manage_exe_id != null ? " AND MANAGE_ID IN (SELECT MANAGE_ID
                                   FROM PLAN.FOLLOW_PLANNING_DIR_TB
                                  WHERE ACTIVITIES_PLAN_SER = M.SEQ
                                    AND MANAGE_ID ={$this->p_dp_manage_exe_id})" : "";
        $where_sql .= isset($this->p_dp_cycle_exe_id) && $this->p_dp_cycle_exe_id != null ? "  AND CYCLE_ID IN (SELECT CYCLE_ID
                                  FROM PLAN.FOLLOW_PLANNING_DIR_TB
                                 WHERE ACTIVITIES_PLAN_SER = M.SEQ
                                   AND CYCLE_ID={$this->p_dp_cycle_exe_id})" : "";

        $where_sql .= isset($this->p_dp_department_exe_id) && $this->p_dp_department_exe_id != null ? " AND DEPARTMENT_ID IN
               (SELECT DEPARTMENT_ID
                  FROM PLAN.FOLLOW_PLANNING_DIR_TB
                 WHERE ACTIVITIES_PLAN_SER = M.SEQ
                   AND DEPARTMENT_ID ={$this->p_dp_department_exe_id})" : "";
        $where_sql .= isset($this->p_dp_objective) && $this->p_dp_objective != null ? " AND  M.OBJECTIVE ={$this->p_dp_objective}" : "";
        $where_sql .= isset($this->p_dp_goal) && $this->p_dp_goal != null ? " AND  M.GOAL ={$this->p_dp_goal}" : "";
        $where_sql .= isset($this->p_dp_goal_t) && $this->p_dp_goal_t != null ? " AND  M.GOAL_T ={$this->p_dp_goal_t}" : "";
        $where_sql .= isset($this->p_dp_type_project) && $this->p_dp_type_project != null ? " AND  M.TYPE_PROJECT ={$this->p_dp_type_project}" : "";
        $where_sql .= isset($this->p_income) && $this->p_income != null ? " AND  M.INCOME ='{$this->p_income}'  " : "";
        $where_sql .= isset($this->p_total_price) && $this->p_total_price != null ? " AND  M.TOTAL_PRICE_BUDGET ='{$this->p_total_price}'  " : "";
        if ($this->p_achive_period == null || $this->p_achive_period == 0) $where_sql .= isset($this->p_achive_period) && $this->p_achive_period != null ? " AND  (SELECT NVL(SUM((TASK_ACHIVE/100)*WEIGHT),0) 
                FROM SUB_ACTIVITY_TB R
               WHERE R.ACTIVITIES_PLAN_SER = M.SEQ) =0 " : "";
        if ($this->p_achive_period == 1) $where_sql .= isset($this->p_achive_period) && $this->p_achive_period != null ? " AND  (SELECT NVL(SUM((TASK_ACHIVE/100)*WEIGHT),0) 
                FROM SUB_ACTIVITY_TB R
               WHERE R.ACTIVITIES_PLAN_SER = M.SEQ) >0  " : "";
        /*if ($this->user->branch<>1 and (isset($this->p_branch) && $this->p_branch == null)){
           // echo $this->user->branch;
            $where_sql .= " AND  (M.BRANCH_FOLLOW_ID ={$this->user->branch} OR M.BRANCH_EXE_ID={$this->user->branch} OR D.BRANCH ={$this->user->branch} OR M.ENTRY_BRANCH ={$this->user->branch})";
           // echo $where_sql;

        }*/

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
        /* if ($this->user->branch<>1 )
        {
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_branch($where_sql, $offset, $row);
       }
         else */
        if ($this->p_branch == null && $this->p_dp_manage_exe_id == null && $this->p_dp_cycle_exe_id == null) {

            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_all($where_sql, $offset, $row);
         //   echo '<pre>' , var_dump($data['page_rows'][0]) , '</pre>';
            $data['show_dept'] = 0;
            $data['show'] = 1;
        } else {
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
            $data['show_dept'] = 1;
            $data['show'] = 0;
        }

        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['year_paln'] = $this->p_year;
        $data["YEAR"] = $this->p_year;
        $this->_look_ups($data);
        //$this->load->view('planning_page', $data);

        //echo '<pre>' , var_dump($data['page_rows'][0]) , '</pre>';
        //$data['page_rows'] = $this->{$this->MODEL_NAME}->get_list(' AND 1=1 ', 0, 1000);

        //echo $where_sql;

        $this->load->view('planning_page_2', $data);


    }

    /*********/

    function public_get_url($id = 0)
    {

        $data['content'] = 'popup_tasks_page';
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->SUB_ACTIVITY_TB_GET_ALL($id);
        $data['adopt'] = 1;
        //$this->_look_ups($data);
        $this->load->view('template/modal', $data);
    }

    function create_without_cost()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_post_validation();

            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData_withoutcost('create'));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }

            if (isset($this->p_exe_time)) {
                for ($i = 0; $i < count($this->p_exe_time); $i++) {
                    $x = $this->{$this->DETAILS_MODEL_NAME}->create_monthes($this->_postData_monthes($this->ser, $this->p_exe_time[$i], 'create'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }


            }

            if (count($this->p_exe_time) == 0) {

                $x = $this->{$this->DETAILS_MODEL_NAME}->delete_monthes($this->ser);

                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }
            }

            for ($i = 0; $i < count($this->p_f_seq1); $i++) {


                if ($this->p_f_seq1[$i] <= 0 || $this->p_f_seq1[$i] == '') {


                    if ($this->p_f_branch[$i] != '') {


                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_follow_dir($this->_postData_follow_dir(null, $this->ser, $this->p_f_planning_dir[$i], $this->p_f_branch[$i], $this->p_f_manage_id[$i], $this->p_f_cycle_id[$i], $this->p_f_department_id[$i], $this->p_dir_weight[$i], 'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }


                }


            }

            for ($i = 0; $i < count($this->p_t_seq); $i++) {


                if ($this->p_t_seq[$i] <= 0 || $this->p_t_seq[$i] == '') {


                    if ($this->p_target[$i] != '') {


                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_target_dir($this->_postData_target(null, $this->ser, $this->p_target[$i], $this->p_scale[$i], $this->p_unit[$i], $this->p_t_result[$i], 'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }


                }


            }
            if (isset($this->p_class_ser_id)) {
                for ($i = 0; $i < count($this->p_class_id); $i++) {

                    if ($this->p_class_ser_id[$i] <= 0 || $this->p_class_ser_id[$i] == '') {
                        if ($this->p_class_id[$i] != '') {
                            $this->{$this->DETAILS_MODEL_NAME}->create_class_items_details($this->_postData_class_items_details(true, null, $this->ser, $this->p_class_id[$i], $this->p_class_unit[$i], $this->p_request_amount[$i], $this->p_price[$i], $this->p_pnotes[$i], $this->p_class_type[$i]));

                        }
                    }
                }
            }


            /*for ($i = 0; $i < count($this->p_seq1); $i++)
            {




                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {


                    if ($this->p_branch[$i] != '') {



                        $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_activites($this->_postData_sub_activities(null,$this->ser, $this->p_planning_dir[$i], $this->p_branch[$i],$this->p_manage_id[$i],$this->p_cycle_id[$i],$this->p_department_id[$i],$this->p_activity_name[$i],$this->p_main_from_month[$i],$this->p_main_to_month[$i],$this->p_weight[$i],
                            'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }




                }





            }*/

            echo intval($this->ser);

        } else {

            $result = array();
            $achive_res = array();
            $data['year_paln'] = date('Y') + 1;//$this->year;
            $data['YEAR'] = date('Y') + 1;
            $data['planning_data'] = $result;
            $data['achive_data'] = $achive_res;
            //$data['content'] = 'planning_show_without_cost';
            $data['content'] = 'planning_year_projects';
            $data['title'] = ' ادارة المشاريع السنوية ';
            $data['action'] = 'index';
            $data['help'] = $this->help;
            $data['isCreate'] = true;
            $data['tech'] = 0;
            if ($this->uri->segment(4) != '') {
                $data['have_project_ser'] = true;

                if ($this->user->branch == 1) $data['project'] = $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4)); else
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

    function _post_validation($type = null)
    {
        $w_count = 0;
        if ($type != null) {
            $result = $this->{$this->MODEL_NAME}->get($this->p_seq);
            if ($result[0]['YEAR'] != $this->p_year) {

                $this->print_error(' لا يمكن تغير عام الخطة!!');

            }

        }

        //if ($this->p_month_count==0 || count($this->p_exe_time) ==0) {
        if (empty($this->p_exe_time)) {

            $this->print_error(' يجب ادخال مدة التنفيذ على مدار ال12 شهر!!');

        } else {
            $prevmonths = array();
            $Reindexresult = array();
            $CanDo = 1;
            $prevmonths = $this->{$this->DETAILS_MODEL_NAME}->get_monthes_list($this->p_seq);
            for ($i = 0; $i < count($prevmonths); $i++) {
                $prevmonths[$i] = $prevmonths[$i]['MONTH'];
            }

            $currmonthes = $this->p_exe_time;

            $result = array_diff($prevmonths, $currmonthes);
            foreach ($result as $v) {
                $Reindexresult[] = $v;

            }


            $HasTasks = $this->{$this->DETAILS_MODEL_NAME}->get_details_activites_list($this->p_seq);
            if (count($HasTasks) != 0) {
                for ($i = 0; $i < count($Reindexresult); $i++) {
                    for ($j = 0; $j < count($HasTasks); $j++) {


                        if ($HasTasks[$j]['FROM_MONTH'] == $Reindexresult[$i]) {
                            $this->print_error(' يوجد مهمة مدخلة خلال شهر !!' . $Reindexresult[$i]);
                            exit;
                        } else if ($HasTasks[$j]['TO_MONTH'] == $Reindexresult[$i]) {
                            $this->print_error(' يوجد مهمة مدخلة خلال شهر !!' . $Reindexresult[$i]);
                            exit;
                        }
                    }
                }

            }

        }
        /*for ($i = 0; $i < count($this->p_t_seq); $i++)
        {


                if(!intval($this->p_target[$i]))
        {
$this->print_error(' يجب ان يكون المستهدف قيمة !!');
        }
        if($this->p_target[$i] =='' )
        {
         $this->print_error('يجب ادخال المستهدف !!');
        }

        if($this->p_scale[$i] =='' || $this->p_scale[$i] ==0)
        {
           $this->print_error('يجب ادخال الصيغة !!');
        }

        if($this->p_unit[$i]=='')
        {

     $this->print_error('يجب ادخال الوحدة !!');
        }

        if($this->p_t_result[$i]=='')
        {
            $this->print_error('يجب ادخال اسم المخرج(النتائج) !!');
        }





    }*/


        /*if($this->p_type ==3 )
        {
        if (count($this->p_ser) ==0) {
            $this->print_error(' يجب ادخال الأنشطة الفرعية!!');
        }
            else
            {*/
        for ($i = 0; $i < count($this->p_f_seq1); $i++) {

            if ($this->p_f_planning_dir[$i] == '' || $this->p_f_planning_dir[$i] == 0) {
                $this->print_error('يجب ادخال الجهة !!');
            }

            if ($this->p_f_branch[$i] == '' || $this->p_f_branch[$i] == 0) {
                $this->print_error('يجب ادخال المقر !!');
            }

            if ($this->p_f_manage_id[$i] == '' || $this->p_f_manage_id[$i] == 0) {

                $this->print_error('يجب ادخال الادارة !!');
            }

            if ($this->p_f_cycle_id[$i] == '' || $this->p_f_cycle_id[$i] == 0) {
                $this->print_error('يجب ادخال الدائرة !!');
            }
            /*if ($this->p_f_department_id[$i] == '' || $this->p_f_department_id[$i] == 0) {
                $this->print_error('يجب ادخال القسم !!');
            }*/
            if ($this->p_dir_weight[$i] == '') {
                $this->print_error('يجب ادخال الوزن النسبي !!');
            }
            /* if($this->p_activity_name[$i]=='' || $this->p_activity_name[$i] ==0)
             {
                 $this->print_error('يجب ادخال اسم المشروع/نشاط !!');
             }
            if($this->p_from_month[$i]=='' || $this->p_from_month[$i] ==0)
            {
                $this->print_error('يجب ادخال من الشهر !!');
            }
            if($this->p_to_month[$i]=='' || $this->p_to_month[$i] ==0)
            {
                $this->print_error('يجب ادخال إلى الشهر !!');
            }
            if($this->p_weight[$i]=='' || $this->p_weight[$i] ==0)
            {
                $this->print_error('يجب ادخال الوزن !!');
            }

             if($this->p_weight[$i]!='' || $this->p_weight[$i] !=0)
             {
                 $w_count+=$this->p_weight[$i];
             }

         }
         if($w_count!=100)
         {
             $this->print_error('يجب أن يكون مجموع الاوزان يساوي 100 !!');
         }
     }*/
        }
        /*  else
          {

  if(isset($this->p_branch))
  {
              if (count($this->p_branch) !=0 ) {
                  for ($i = 0; $i < count($this->p_branch); $i++)
                  {
                       if($type!=null)
                     {
                      if($this->p_activity_no[$i] =='' || $this->p_activity_no[$i] ==0)
                      {
                          $this->print_error('  !!');
                      }
  }
                      if($this->p_planning_dir[$i] =='' || $this->p_planning_dir[$i] ==0)
                      {
                          $this->print_error('يجب ادخال الجهة !!');
                      }

                      if($this->p_branch[$i] =='' || $this->p_branch[$i] ==0)
                      {
                          $this->print_error('يجب ادخال المقر !!');
                      }

                      if($this->p_manage_id[$i]=='' || $this->p_manage_id[$i] ==0)
                      {

                          $this->print_error('يجب ادخال الادارة !!');
                      }

                      if($this->p_cycle_id[$i]=='' || $this->p_cycle_id[$i] ==0)
                      {
                          $this->print_error('يجب ادخال الدائرة !!');
                      }
                      if($this->p_activity_name[$i]=='')
                      {
                          $this->print_error('يجب ادخال اسم المشروع/نشاط !!');
                      }
                      if($this->p_main_from_month[$i]=='' )
                      {
                          $this->print_error('يجب ادخال من الشهر !!');
                      }
                      if($this->p_main_to_month[$i]=='' )
                      {
                          $this->print_error('يجب ادخال إلى الشهر !!');
                      }

                      if($this->p_weight[$i]=='' || $this->p_weight[$i] ==0)
                      {
                          $this->print_error('يجب ادخال الوزن !!');
                      }
                      if($this->p_weight[$i]!='' || $this->p_weight[$i] !=0)
                      {
                          $w_count+=$this->p_weight[$i];
                      }


                  }
                  if($w_count!=100)
                  {
                      $this->print_error('يجب أن يكون مجموع الاوزان يساوي 100 !!');
                  }
              }
              }
              }*/
    }

    function create()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }


            /* for ($i = 0; $i < count($this->p_ser1); $i++)
             {
                 if ($this->p_ser1[$i] <= 0) {
                     if ($this->p_branch[$i] != '') {

                         if ($this->p_part[$i] == '') {
                             $this->print_error('يجب ان ادخال حصة المقر');
                         }

                         $x=$this->{$this->DETAILS_MODEL_NAME}->create_part($this->_postData_details(null,$this->ser, $this->p_branch[$i], $this->p_part[$i],
                             'create'));

                         if (intval($x) <= 0) {
                             $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                         }

                     }




                 }
             }*/

            for ($i = 0; $i < count($this->p_seq1); $i++) {


                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i] == '') {


                    if ($this->p_branch[$i] != '') {


                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_sub_activites($this->_postData_sub_activities(null, $this->ser, $this->p_planning_dir[$i], $this->p_branch[$i], $this->p_manage_id[$i], $this->p_cycle_id[$i], $this->p_department_id[$i], $this->p_activity_name[$i], $this->p_main_from_month[$i], $this->p_main_to_month[$i], $this->p_weight[$i], $this->p_TASK_ACHIVE[$i], 'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }


                }
            }
            echo intval($this->ser);

        } else {

            $result = array();
            $achive_res = array();
            $data['planning_data'] = $result;
            $data['achive_data'] = $achive_res;
            $data['year_paln'] = $this->year;
            $data['content'] = 'planning_show';
            $data['title'] = 'ادارة المشاريع الفنية السنوية';
            $data['action'] = 'index';
            $data['help'] = $this->help;
            $data['isCreate'] = true;
            $data['tech'] = 1;

            if ($this->uri->segment(4) != '') {
                $data['have_project_ser'] = true;

                if ($this->user->branch == 1) $data['project'] = $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4)); else
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

    function _postedData($typ = null, $activity_name = '', $detailes = '', $status = 1)
    {

        if ($this->input->post('type') == 1) $project_id = ''; else if ($this->input->post('type') == 2) $project_id = $this->input->post('project_id');


        $result = array(array('name' => 'SEQ', 'value' => $this->input->post('seq'), 'type' => '', 'length' => -1), array('name' => 'YEAR', 'value' => $this->input->post('year'), 'type' => '', 'length' => -1), array('name' => 'CLASS', 'value' => $this->input->post('class'), 'type' => '', 'length' => -1), array('name' => 'TYPE', 'value' => $this->input->post('type'), 'type' => '', 'length' => -1), array('name' => 'OBJECTIVE', 'value' => $this->input->post('objective'), 'type' => '', 'length' => -1), array('name' => 'GOAL', 'value' => $this->input->post('goal'), 'type' => '', 'length' => -1), array('name' => 'GOAL_T', 'value' => $this->input->post('goal_t'), 'type' => '', 'length' => -1), array('name' => 'ACTIVITY_NAME', 'value' => $activity_name, 'type' => '', 'length' => -1), array('name' => 'PROJECT_ID', 'value' => $this->input->post('project_id'), 'type' => '', 'length' => -1), array('name' => 'FINANCE', 'value' => $this->input->post('finance'), 'type' => '', 'length' => -1), array('name' => 'FINANCE_NAME', 'value' => $this->input->post('finance_name'), 'type' => '', 'length' => -1), array('name' => 'MANAGE_FOLLOW_ID', 'value' => $this->input->post('manage_follow_id'), 'type' => '', 'length' => -1), array('name' => 'CYCLE_FOLLOW_ID', 'value' => $this->input->post('cycle_follow_id'), 'type' => '', 'length' => -1), array('name' => 'DEPARTMENT_FOLLOW_ID', 'value' => $this->input->post('department_follow_id'), 'type' => '', 'length' => -1), array('name' => 'MANAGE_EXE_ID', 'value' => $this->input->post('manage_exe_id'), 'type' => '', 'length' => -1), array('name' => 'CYCLE_EXE_ID', 'value' => $this->input->post('cycle_exe_id'), 'type' => '', 'length' => -1), array('name' => 'DEPARTMENT_EXE_ID', 'value' => $this->input->post('department_exe_id'), 'type' => '', 'length' => -1), array('name' => 'FROM_MONTH', 'value' => $this->input->post('from_month'), 'type' => '', 'length' => -1), array('name' => 'TO_MONTH', 'value' => $this->input->post('to_month'), 'type' => '', 'length' => -1), array('name' => 'BRANCH_FOLLOW_ID', 'value' => $this->input->post('branch_follow_id'), 'type' => '', 'length' => -1), array('name' => 'BRANCH_EXE_ID', 'value' => $this->input->post('branch_exe_id'), 'type' => '', 'length' => -1), array('name' => 'TOTAL_PRICE', 'value' => '', 'type' => '', 'length' => -1),


        );


//var_dump($result);

        // return $result;


        if ($typ == 'create') {
            array_shift($result);
        }
        return $result;
    }

    function _postData_sub_activities($ser = null, $activity_no, $planning_dir = null, $branch = null, $manage_id = null, $cycle_id = null, $department_id = null, $activity_name = null, $from_month = null, $to_month = null, $weight = null, $F_SEQ__, $TASK_ACHIVE = null, $type = null)

    {


        $result = array(array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'ACTIVITIES_PLAN_SER', 'value' => $activity_no, 'type' => '', 'length' => -1), array('name' => 'PLANNING_DIR', 'value' => $planning_dir, 'type' => '', 'length' => -1), array('name' => 'BRANCH', 'value' => $branch, 'type' => '', 'length' => -1), array('name' => 'MANAGE_ID', 'value' => $manage_id, 'type' => '', 'length' => -1), array('name' => 'CYCLE_ID', 'value' => $cycle_id, 'type' => '', 'length' => -1), array('name' => 'DEPARTMENT_ID', 'value' => $department_id, 'type' => '', 'length' => -1), array('name' => 'ACTIVITY_NAME', 'value' => $activity_name, 'type' => '', 'length' => -1), array('name' => 'FROM_MONTH', 'value' => $from_month, 'type' => '', 'length' => -1), array('name' => 'TO_MONTH', 'value' => $to_month, 'type' => '', 'length' => -1), array('name' => 'WEIGHT', 'value' => $weight, 'type' => '', 'length' => -1), array('name' => 'F_SEQ__IN', 'value' => $F_SEQ__, 'type' => '', 'length' => -1), array('name' => 'TASK_ACHIVE_IN', 'value' => $TASK_ACHIVE, 'type' => '', 'length' => -1),


        );


        if ($type == 'create') unset($result[0]);


        return $result;
    }

    function _postedData_withoutcost($typ = null)
    {


        $result = array(array('name' => 'SEQ', 'value' => $this->input->post('seq'), 'type' => '', 'length' => -1), array('name' => 'YEAR', 'value' => $this->input->post('year'), 'type' => '', 'length' => -1), array('name' => 'CLASS', 'value' => $this->input->post('class_name_id'), 'type' => '', 'length' => -1), array('name' => 'TYPE', 'value' => $this->input->post('type'), 'type' => '', 'length' => -1), array('name' => 'OBJECTIVE', 'value' => $this->input->post('objective'), 'type' => '', 'length' => -1), array('name' => 'GOAL', 'value' => $this->input->post('goal'), 'type' => '', 'length' => -1), array('name' => 'GOAL_T', 'value' => $this->input->post('goal_t'), 'type' => '', 'length' => -1), array('name' => 'ACTIVITY_NAME', 'value' => $this->input->post('activity_name1'), 'type' => '', 'length' => -1), array('name' => 'PROJECT_ID', 'value' => $this->input->post('project_id'), 'type' => '', 'length' => -1), array('name' => 'FINANCE', 'value' => $this->input->post('finance'), 'type' => '', 'length' => -1), array('name' => 'FINANCE_NAME', 'value' => $this->input->post('finance_name'), 'type' => '', 'length' => -1), array('name' => 'MANAGE_FOLLOW_ID', 'value' => $this->input->post('manage_follow_id'), 'type' => '', 'length' => -1), array('name' => 'CYCLE_FOLLOW_ID', 'value' => $this->input->post('cycle_follow_id'), 'type' => '', 'length' => -1), array('name' => 'DEPARTMENT_FOLLOW_ID', 'value' => $this->input->post('department_follow_id'), 'type' => '', 'length' => -1), array('name' => 'MANAGE_EXE_ID', 'value' => $this->input->post('manage_exe_id'), 'type' => '', 'length' => -1), array('name' => 'CYCLE_EXE_ID', 'value' => $this->input->post('cycle_exe_id'), 'type' => '', 'length' => -1), array('name' => 'DEPARTMENT_EXE_ID', 'value' => $this->input->post('department_exe_id'), 'type' => '', 'length' => -1), array('name' => 'FROM_MONTH', 'value' => $this->input->post('from_month'), 'type' => '', 'length' => -1), array('name' => 'TO_MONTH', 'value' => $this->input->post('to_month'), 'type' => '', 'length' => -1), array('name' => 'BRANCH_FOLLOW_ID', 'value' => $this->input->post('branch_follow_id'), 'type' => '', 'length' => -1), array('name' => 'BRANCH_EXE_ID', 'value' => $this->input->post('branch_exe_id'), 'type' => '', 'length' => -1), array('name' => 'TOTAL_PRICE', 'value' => $this->input->post('total_price'), 'type' => '', 'length' => -1), array('name' => 'TYPE_PROJECT', 'value' => $this->input->post('type_project'), 'type' => '', 'length' => -1), array('name' => 'INCOME', 'value' => $this->input->post('income'), 'type' => '', 'length' => -1), array('name' => 'TARGET', 'value' =>/*$this->input->post('target')*/
            null, 'type' => '', 'length' => -1), array('name' => 'SCALE', 'value' =>/*$this->input->post('scale')*/
            null, 'type' => '', 'length' => -1), array('name' => 'UNIT', 'value' =>/*$this->input->post('unit')*/
            null, 'type' => '', 'length' => -1), array('name' => 'NOTES', 'value' => $this->input->post('notes'), 'type' => '', 'length' => -1), array('name' => 'FROM_YEAR', 'value' => $this->input->post('from_year'), 'type' => '', 'length' => -1), array('name' => 'TO_YEAR', 'value' => $this->input->post('to_year'), 'type' => '', 'length' => -1), array('name' => 'STRATGIC_TOTAL_PRICE', 'value' => $this->input->post('stratgic_total_price'), 'type' => '', 'length' => -1), array('name' => 'STRATGIC_INCOME', 'value' => $this->input->post('stratgic_income'), 'type' => '', 'length' => -1), array('name' => 'TYPE_EMP', 'value' => $this->input->post('type_emp'), 'type' => '', 'length' => -1),


        );


        // return $result;


        if ($typ == 'create') {
            array_shift($result);
        }


        return $result;
    }

    function _postData_monthes($ser, $month, $type = null)

    {
        $result = array(array('name' => 'SER_PLAN', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1),


        );


        /*if ($type == 'create')
            unset($result[0]);
*/


        return $result;

    }

    function _postData_follow_dir($ser = null, $activity_no, $planning_dir = null, $branch = null, $manage_id = null, $cycle_id = null, $department_id = null, $weghit = null, $type = null)

    {


        $result = array(array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'ACTIVITIES_PLAN_SER', 'value' => $activity_no, 'type' => '', 'length' => -1), array('name' => 'PLANNING_DIR', 'value' => $planning_dir, 'type' => '', 'length' => -1), array('name' => 'BRANCH', 'value' => $branch, 'type' => '', 'length' => -1), array('name' => 'MANAGE_ID', 'value' => $manage_id, 'type' => '', 'length' => -1), array('name' => 'CYCLE_ID', 'value' => $cycle_id, 'type' => '', 'length' => -1), array('name' => 'DEPARTMENT_ID', 'value' => $department_id, 'type' => '', 'length' => -1), array('name' => 'WEGHIT', 'value' => $weghit, 'type' => '', 'length' => -1),

        );


        if ($type == 'create') unset($result[0]);


        return $result;
    }

    function _postData_target($ser = null, $activity_no, $target = null, $SCALE = null, $UNIT = null, $T_RESULT = null, $type = null)

    {


        $result = array(array('name' => 'T_SEQ', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'ACTIVITIES_PLAN_SER', 'value' => $activity_no, 'type' => '', 'length' => -1), array('name' => 'TARGET', 'value' => $target, 'type' => '', 'length' => -1), array('name' => 'SCALE', 'value' => $SCALE, 'type' => '', 'length' => -1), array('name' => 'UNIT', 'value' => $UNIT, 'type' => '', 'length' => -1), array('name' => 'T_RESULT', 'value' => $T_RESULT, 'type' => '', 'length' => -1),

        );


        if ($type == 'create') unset($result[0]);


        return $result;
    }

    function _postData_class_items_details($create = true, $ser, $project_serial, $class_id, $class_unit, $amount, $price, $notes, $class_type)
    {
        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'PROJECT_SERIAL', 'value' => $project_serial, 'type' => '', 'length' => -1), array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1), array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1), array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1), array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1), array('name' => 'NOTES', 'value' => $notes, 'type' => '', 'length' => -1), array('name' => 'CLASS_TYPE', 'value' => $class_type, 'type' => '', 'length' => -1),


        );

        if ($create) {
            array_shift($result);

        } else {
            unset($result[8]);

        }

        return $result;
    }

    function edit_without_cost()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$this->_post_validation();


            $this->ser = $this->{$this->MODEL_NAME}->edit($this->_postedData_withoutcost());

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }
            //die;
            for ($i = 0; $i < count($this->p_ser1); $i++) {
//var_dump(count($this->p_ser1));

                if ($this->p_ser1[$i] <= 0) {

                    if ($this->p_branch[$i] != '') {

                        if ($this->p_part[$i] == '') {
                            $this->print_error('يجب ان ادخال حصة المقر');
                        }

                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_part($this->_postData_details(null, $this->ser, $this->p_branch[$i], $this->p_part[$i], 'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }


                } else {

                    if ($this->p_branch[$i] != '') {

                        if ($this->p_part[$i] == '') {
                            $this->print_error('يجب ان ادخال حصة المقر');
                        }

                        $y = $this->{$this->DETAILS_MODEL_NAME}->edit_part($this->_postData_details($this->p_ser1[$i], $this->p_activity_no[$i], $this->p_branch[$i], $this->p_part[$i], 'edit'));

                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }

                    }


                }


            }
            echo intval($this->ser);
        }
    }

    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$this->_post_validation();


            $this->ser = $this->{$this->MODEL_NAME}->edit($this->_postedData());

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }
            //die;
            for ($i = 0; $i < count($this->p_seq1); $i++) {


                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i] == '') {


                    if ($this->p_branch[$i] != '') {


                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_sub_activites($this->_postData_sub_activities(null, $this->ser, $this->p_planning_dir[$i], $this->p_branch[$i], $this->p_manage_id[$i], $this->p_cycle_id[$i], $this->p_department_id[$i], $this->p_activity_name[$i], $this->p_main_from_month[$i], $this->p_main_to_month[$i], $this->p_weight[$i], $this->p_TASK_ACHIVE[$i], 'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }


                } else {

                    if ($this->p_branch[$i] != '') {


                        $y = $this->{$this->DETAILS_MODEL_NAME}->edit_sub_activites($this->_postData_sub_activities($this->p_seq1[$i], $this->ser, $this->p_planning_dir[$i], $this->p_branch[$i], $this->p_manage_id[$i], $this->p_cycle_id[$i], $this->p_department_id[$i], $this->p_activity_name[$i], $this->p_main_from_month[$i], $this->p_main_to_month[$i], $this->p_weight[$i], $this->p_TASK_ACHIVE[$i], 'edit'));

                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }

                    }


                }


            }
            echo intval($this->ser);
        }
    }

    function _postData_details($ser = null, $activity_no, $branch = null, $part = null, $type = null)

    {


        $result = array(array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'ACTIVITY_NO', 'value' => $activity_no, 'type' => '', 'length' => -1), array('name' => 'BRANCH', 'value' => $branch, 'type' => '', 'length' => -1), array('name' => 'PART', 'value' => $part, 'type' => '', 'length' => -1),


        );


        if ($type == 'create') unset($result[0]);


        return $result;
    }

    function edit_no_tech()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation('edit');


            $this->ser = $this->{$this->MODEL_NAME}->edit($this->_postedData_withoutcost());


            //var_dump($this->_postedData_withoutcost());

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }
            if (isset($this->p_exe_time)) {
                for ($i = 0; $i < count($this->p_exe_time); $i++) {
                    $x = $this->{$this->DETAILS_MODEL_NAME}->create_monthes($this->_postData_monthes($this->ser, $this->p_exe_time[$i], 'create'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }


            }
            if (count($this->p_exe_time) == 0) {

                $x = $this->{$this->DETAILS_MODEL_NAME}->delete_monthes($this->ser);

                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }
            }

            for ($i = 0; $i < count($this->p_f_seq1); $i++) {


                if ($this->p_f_seq1[$i] <= 0 || $this->p_f_seq1[$i] == '') {


                    if ($this->p_f_branch[$i] != '') {


                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_follow_dir($this->_postData_follow_dir(null, $this->ser, $this->p_f_planning_dir[$i], $this->p_f_branch[$i], $this->p_f_manage_id[$i], $this->p_f_cycle_id[$i], $this->p_f_department_id[$i], $this->p_dir_weight[$i], 'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }


                } else {

                    if ($this->p_f_branch[$i] != '') {


                        $y = $this->{$this->DETAILS_MODEL_NAME}->edit_follow_dir($this->_postData_follow_dir($this->p_f_seq1[$i], $this->ser, $this->p_f_planning_dir[$i], $this->p_f_branch[$i], $this->p_f_manage_id[$i], $this->p_f_cycle_id[$i], $this->p_f_department_id[$i], $this->p_dir_weight[$i], 'edit'));

                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }

                    }


                }


            }

            for ($i = 0; $i < count($this->p_t_seq); $i++) {


                if ($this->p_t_seq[$i] <= 0 || $this->p_t_seq[$i] == '') {


                    if ($this->p_target[$i] != '') {


                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_target_dir($this->_postData_target(null, $this->ser, $this->p_target[$i], $this->p_scale[$i], $this->p_unit[$i], $this->p_t_result[$i], 'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }


                } else {

                    if ($this->p_target[$i] != '') {


                        $y = $this->{$this->DETAILS_MODEL_NAME}->edit_target_dir($this->_postData_target($this->p_t_seq[$i], $this->ser, $this->p_target[$i], $this->p_scale[$i], $this->p_unit[$i], $this->p_t_result[$i], ''));

                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }


                    }


                }


            }
            if (isset($this->p_class_ser_id)) {
                for ($i = 0; $i < count($this->p_class_id); $i++) {

                    if ($this->p_class_ser_id[$i] <= 0 || $this->p_class_ser_id[$i] == '') {
                        if ($this->p_class_id[$i] != '') {
                            $items = $this->{$this->DETAILS_MODEL_NAME}->create_class_items_details($this->_postData_class_items_details(true, null, $this->ser, $this->p_class_id[$i], $this->p_class_unit[$i], $this->p_request_amount[$i], $this->p_price[$i], $this->p_pnotes[$i], $this->p_class_type[$i]));
                            if (intval($items) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $items);
                            }


                        }
                    } else {


                        if ($this->p_class_id[$i] != '') {
                            $items = $this->{$this->DETAILS_MODEL_NAME}->edit_class_items_details($this->_postData_class_items_details(false, $this->p_class_ser_id[$i], $this->ser, $this->p_class_id[$i], $this->p_class_unit[$i], $this->p_request_amount[$i], $this->p_price[$i], $this->p_pnotes[$i], $this->p_class_type[$i]));
                            if (intval($items) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $items);
                            }


                        }


                    }
                }


            }
            /*for ($i = 0; $i < count($this->p_seq1); $i++)
             {




                 if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {


                     if ($this->p_branch[$i] != '') {



                         $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_activites($this->_postData_sub_activities(null,$this->ser, $this->p_planning_dir[$i], $this->p_branch[$i],$this->p_manage_id[$i],$this->p_cycle_id[$i],$this->p_department_id[$i],$this->p_activity_name[$i],$this->p_main_from_month[$i],$this->p_main_to_month[$i],$this->p_weight[$i],
                             'create'));

                         if (intval($x) <= 0) {
                             $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                         }

                     }




                 }

                 else
                 {

                     if ($this->p_branch[$i] != '') {



                         $y=$this->{$this->DETAILS_MODEL_NAME}->edit_sub_activites($this->_postData_sub_activities($this->p_seq1[$i],$this->ser, $this->p_planning_dir[$i], $this->p_branch[$i],$this->p_manage_id[$i],$this->p_cycle_id[$i],$this->p_department_id[$i],$this->p_activity_name[$i],$this->p_main_from_month[$i],$this->p_main_to_month[$i],$this->p_weight[$i],
                             'edit'));

                         if (intval($y) <= 0) {
                             $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                         }

                     }


                 }



             }*/
            echo intval($this->ser);
        }
    }

    function delete_details()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->DETAILS_MODEL_NAME}->delete($this->p_id);
            echo intval($res);
        } else
            echo "لم يتم ارسال رقم الخطة";
    }

////////////

    function delete()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete($val);
                //echo $msg;
                if ($msg == 1) {
                    echo 'تم حذف المشروع بنجاح' . '<br/>';
                    //echo $msg;
                    //echo modules::run($this->PAGE_URL);
                } else {
                    if ($msg == -1) {
                        echo '!! لا يمكن الغاء مشروع معتمد' . '<br/>';
                    } elseif ($msg == -2) {
                        echo '!! المشروع استراتيجي يرجى مراجعه وحدة التخطيط لالغاءوه' . '<br/>';
                    } elseif ($msg == -3) {
                        echo 'لا يمكن الغاء مشروع فني !!' . '<br/>';
                    } elseif ($msg == -4) {
                        echo 'لا يمكن الغاء مشروع لم تقوم بادخاله !!' . '<br/>';
                    } elseif ($msg == -5) {
                        echo 'المشروع يحتوي على مهام مدخلة مسبقا يرجى مراجعة وحدة التخطيط لالغاؤه !!' . '<br/>';
                    } else {
                        echo $msg . '<br/>';;
                    }

                }
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete($id);
            if ($msg == 1) {
                // echo $msg;
                echo 'تم حذف المشروع بنجاح' . '<br/>';
                //echo modules::run($this->PAGE_URL);
            } else {
                if ($msg == -1) {
                    echo '!! لا يمكن الغاء مشروع معتمد' . '<br/>';
                } elseif ($msg == -2) {
                    echo '!! المشروع استراتيجي يرجى مراجعه وحدة التخطيط لالغاءوه' . '<br/>';
                } elseif ($msg == -3) {
                    echo 'لا يمكن الغاء مشروع فني !!' . '<br/>';
                } elseif ($msg == -4) {
                    echo 'لا يمكن الغاء مشروع لم تقوم بادخاله !!' . '<br/>';
                } elseif ($msg == -5) {
                    echo 'المشروع يحتوي على مهام مدخلة مسبقا يرجى مراجعة وحدة التخطيط لالغاؤه !!' . '<br/>';
                } else {
                    echo $msg . '<br/>';;
                }

            }
        }


    }

///////////
    function delete_unit()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete_unit($val);
                //echo $msg;
                if ($msg == 1) {
                    echo 'تم حذف المشروع بنجاح' . '<br/>';
                    //echo $msg;
                    //echo modules::run($this->PAGE_URL);
                } else {
                    if ($msg == -1) {
                        echo '!! لا يمكن الغاء مشروع معتمد' . '<br/>';
                    } elseif ($msg == -2) {
                        echo '!! المشروع استراتيجي يرجى الغاؤه من شاشة الخطة الاستراتيجية' . '<br/>';
                    } elseif ($msg == -3) {
                        echo 'لا يمكن الغاء مشروع فني !!' . '<br/>';
                    } else {
                        echo $msg . '<br/>';;
                    }

                }
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete_unit($id);
            if ($msg == 1) {
                // echo $msg;
                echo 'تم حذف المشروع بنجاح' . '<br/>';
                //echo modules::run($this->PAGE_URL);
            } else {
                if ($msg == -1) {
                    echo '!! لا يمكن الغاء مشروع معتمد' . '<br/>';
                } elseif ($msg == -2) {
                    echo '!! المشروع استراتيجي يرجى الغاؤه من شاشة الخطة الاستراتيجية' . '<br/>';
                } elseif ($msg == -3) {
                    echo 'لا يمكن الغاء مشروع فني !!' . '<br/>';
                } else {
                    echo $msg . '<br/>';;
                }

            }
        }


    }

///////////
    function delete_sub_details()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->DETAILS_MODEL_NAME}->delete_activites($this->p_id);
            echo intval($res);
        } else
            echo "لم يتم ارسال رقم الخطة";
    }

    function get_without_cost($id)
    {
        if ($this->user->branch == 1)

            $result = $this->{$this->MODEL_NAME}->get($id); else
            $result = $this->{$this->MODEL_NAME}->get($id, $this->user->branch);

        if (!(count($result) == 1)) {
            echo 'not in your permissions';
            die();

        }
        $achive_res = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['planning_data'] = $result[0];
        $data['achive_data'] = $achive_res;
        $data['content'] = 'planning_show_without_cost';
        $data['action'] = 'edit_without_cost';
        $data['isCreate'] = false;
        $data['title'] = '  ادارة المشاريع السنوية   ::. ';
        $data['help'] = $this->help;
        $data['tech'] = 0;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function get_tech_cost($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        //$months = $this->{$this->DETAILS_MODEL_NAME}->get_monthes($id);

        if ($this->user->branch == 1)

            $result = $this->{$this->MODEL_NAME}->get($id); else
            $result = $this->{$this->MODEL_NAME}->get($id, $this->user->branch);

        if (!(count($result) == 1)) {
            echo 'not in your permissions';
            die();

        }

        $achive_res = $this->{$this->MODEL_NAME}->get_achive($id);
        $have_emp = $this->{$this->MODEL_NAME}->HAVE_EMPS_STRUCTURE_TB_GET();
        $data['planning_data'] = $result[0];
        $data['YEAR'] = $result[0]['YEAR'];
        $data['achive_data'] = $achive_res;
        $data['HAVE_EMP'] = $have_emp;
        //var_dump($result[0]['ADOPT_USER']);
        //$data['content'] = 'planning_show_no_tech';
        $data['content'] = 'planning_year_projects';

        $data['action'] = 'edit_no_tech';
        $data['isCreate'] = false;

        $data['title'] = 'ادارة المشاريع السنوية';
        $data['help'] = $this->help;
        $data['tech'] = 0;
        if ($this->uri->segment(4) != '') {

            $data['have_project_ser'] = true;

            if ($this->user->branch == 1) $data['project'] = $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4)); else
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

    ///////////////////////////////

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

    function public_get_mange_exe($branch = 1)
    {


        /*$branch = $this->input->post('no')?$this->input->post('no'):$branch;

        $arr = $this->Gcc_structure_model->get_Structure_branch(1,$branch);
        */

        $user_info = $this->users_model->get_user_info($this->user->id);
        $arr = $this->{$this->DETAILS_MODEL_NAME}->get_Structure_emp($user_info[0]['EMP_ID'], $user_info[0]['BRANCH']);
        echo json_encode($arr);


    }

    function public_get_mange_b($branch = 1)
    {


        $branch = $this->input->post('no') ? $this->input->post('no') : $branch;

        $arr = $this->Gcc_structure_model->get_Structure_branch(1, $branch);


        return $arr;

    }
    /**********************************************************************************************************************************/
    /*                                                   sub_activities                                                                *
     *
    **********************************************************************************************************************************/

    function public_get_mange_b_($id = 0, $branch = 0)
    {


        $arr = $this->{$this->DETAILS_MODEL_NAME}->get_Structure_emp($id, $branch);

        return $arr;

    }

    function public_get_cycle_exe($manage = 0)
    {

        $user_info = $this->users_model->get_user_info($this->user->id);
        $get_type = $this->{$this->DETAILS_MODEL_NAME}->get_Structure_emp($user_info[0]['EMP_ID'], $user_info[0]['BRANCH']);
        if ($get_type[0]['TYPE'] == 1) {
            $manage = $this->input->post('no') ? $this->input->post('no') : $manage;
            //var_dump($manage);
            $arr = $this->Gcc_structure_model->getList2($manage, 0);
        } else {
            $arr = $this->{$this->DETAILS_MODEL_NAME}->get_cycle_emp($user_info[0]['EMP_ID'], $user_info[0]['BRANCH']);
        }


        echo json_encode($arr);


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

    function public_get_cycle_b_($id = 0, $branch = 0)
    {


        $arr = $this->{$this->DETAILS_MODEL_NAME}->get_cycle_emp($id, $branch);

        return $arr;

    }

    function public_get_dep($cycle = 0)
    {


        $cycle = $this->input->post('no') ? $this->input->post('no') : $cycle;

        $arr = $this->Gcc_structure_model->getList2($cycle, 0);


        echo json_encode($arr);


    }

    /***************************************************/

    function public_get_dep_p($cycle = 0)
    {


        $cycle = $this->input->post('no') ? $this->input->post('no') : $cycle;

        $arr = $this->Gcc_structure_model->getList2($cycle, 0);


        return $arr;


    }

    /************************************************/

    function public_get_goal($objective = 0, $year = 0)
    {


        $objective = $this->input->post('no') ? $this->input->post('no') : $objective;
        $year = $this->input->post('year') ? $this->input->post('year') : $year;
        $arr = $this->{$this->MODEL_NAME}->get_objective('', $objective, $year);


        echo json_encode($arr);


    }
    /**************************************************************************************************************************************/
    /*  evaluate*/
    /**********************************************************************************************************************************/

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

                    if ($this->p_activity_name[$i] != '') {


                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_sub_activites($this->_postData_sub_activities(null, $this->p_activity_no[$i], $this->p_planning_dir[$i], $this->p_branch[$i], $this->p_manage_id[$i], $this->p_cycle_id[$i], $this->p_department_id[$i], $this->p_activity_name[$i], $this->p_from_month[$i], $this->p_to_month[$i], $this->p_weight[$i], $this->p_F_SEQ__[$i], $this->p_TASK_ACHIVE[$i], 'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);

                    }

                } else {


                    if ($this->p_activity_name[$i] != '') {

                        /*if ($this->p_part[$i] == '') {
                            $this->print_error('يجب ان ادخال حصة المقر');
                        }*/

                        $x = $this->{$this->DETAILS_MODEL_NAME}->edit_sub_activites($this->_postData_sub_activities($this->p_ser[$i], $this->p_activity_no[$i], $this->p_planning_dir[$i], $this->p_branch[$i], $this->p_manage_id[$i], $this->p_cycle_id[$i], $this->p_department_id[$i], $this->p_activity_name[$i], $this->p_from_month[$i], $this->p_to_month[$i], $this->p_weight[$i], $this->p_F_SEQ__[$i], $this->p_TASK_ACHIVE[$i], 'edit'));


                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);

                    }


                }


            }

        }
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
    /*  refresh*/
    /**********************************************************************************************************************************/

    function public_get_follow_details($id = 0)
    {

        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_follow_all($id);

        $this->load->view('plan_follow_dir', $data);
    }

    function public_get_target($id = 0)
    {
        $data['target_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_target_list($id);

        $this->load->view('target_activities_plan_page', $data);
    }

    function public_get_all_follow_exe($id = 0, $adopt)
    {
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_follow_exe_all($id);
        $data['user_info'] = $this->users_model->get_user_info($this->user->id);
        $data['struc_user_info'] = $this->{$this->DETAILS_MODEL_NAME}->get_Structure_emp($data['user_info'][0]['EMP_ID'], $data['user_info'][0]['BRANCH']);
        $data['cycle_user_info'] = $this->{$this->DETAILS_MODEL_NAME}->get_cycle_emp($data['user_info'][0]['EMP_ID'], $data['user_info'][0]['BRANCH']);
        $data['adopt'] = $adopt;
        $this->load->view('plan_exe_follow_dir', $data);
    }

    function public_get_tasks($id, $plan_no, $adopt)
    {
        $data['content'] = 'plan_tasks_details';
        $result = $this->{$this->DETAILS_MODEL_NAME}->FLLOW_EXE_DIR_GET_1($plan_no, $id);
        $months = $this->{$this->DETAILS_MODEL_NAME}->get_monthes_list($plan_no);
        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        $data['info'] = $result[0]; //$this->{$this->MODEL_NAME_}->get_child_objective($plan_no,$id);
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_activites_all($plan_no, $id);
        $data['total_weghit'] = $this->{$this->DETAILS_MODEL_NAME}->SUB_ACTIVITY_TB_W_GET($plan_no, $id);
        $data['id'] = $id;
        $data['plan_no'] = $plan_no;
        $data['adopt'] = $adopt;
        $data['months'] = $months;

        //$this->_look_ups($data);
        //$data['details']='';
        $this->load->view('template/modal', $data);
    }
    /**************************************************************************************************************************************/
    /*  follow*/
    /**********************************************************************************************************************************/

    function public_get_class_details($id = 0)
    {


        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_class_details($id);
        $data['class_unit'] = $this->constant_details_model->get_list(29);
        $data['class_type'] = $this->constant_details_model->get_list(41);
        $data['id'] = $id;
        $this->load->view('projects_class_details', $data);
    }

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

    /********************************************************************************************************************************/

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
                    $x = $this->{$this->DETAILS_MODEL_NAME}->create_achive_evaluate($this->_postData_achive_details(null, $this->p_seq[$i], $this->p_status[$i], $this->p_persant[$i], $this->p_notes[$i], $this->p_is_end[$i], 'create'));

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
                    $x = $this->{$this->DETAILS_MODEL_NAME}->edit_achive_evaluate($this->_postData_achive_details($this->p_ser[$i], $this->p_ser_plan[$i], $this->p_status[$i], $this->p_persant[$i], $this->p_notes[$i], $this->p_is_end[$i], 'edit'));

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


        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'SER_PLAN', 'value' => $ser_plan, 'type' => '', 'length' => -1), array('name' => 'STATUS', 'value' => $status, 'type' => '', 'length' => -1), array('name' => 'PERSANT', 'value' => $persant, 'type' => '', 'length' => -1), array('name' => 'NOTES', 'value' => $notes, 'type' => '', 'length' => -1), array('name' => 'IS_END', 'value' => $is_end, 'type' => '', 'length' => -1),


        );


        if ($type == 'create') unset($result[0]);


        return $result;
    }

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
    /**************************************************************************************************************************************/
    /*  weight */
    /**********************************************************************************************************************************/

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
    /*  collaboration */
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

    /****************/

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


    /*****************************************************************************************************************************/

    function adopt()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_id != '' || $this->p_id != 0)) {
            if ($this->p_adopts = 2022) $adopt = 70; else
                $adopt = 10;
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id, $adopt);

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

    function _postedData_no_teq_proj_mix($typ = null, $activity_name = '', $detailes = '', $status = 1)
    {


        $result = array(array('name' => 'SEQ', 'value' => $this->input->post('seq'), 'type' => '', 'length' => -1), array('name' => 'YEAR', 'value' => $this->year, 'type' => '', 'length' => -1), array('name' => 'CLASS', 'value' => $this->input->post('class_name'), 'type' => '', 'length' => -1), array('name' => 'TYPE', 'value' => $this->input->post('type'), 'type' => '', 'length' => -1), array('name' => 'OBJECTIVE', 'value' => $this->input->post('objective'), 'type' => '', 'length' => -1), array('name' => 'GOAL', 'value' => $this->input->post('goal'), 'type' => '', 'length' => -1), array('name' => 'GOAL_T', 'value' => $this->input->post('goal_t'), 'type' => '', 'length' => -1), array('name' => 'ACTIVITY_NAME', 'value' => $this->input->post('activity_name'), 'type' => '', 'length' => -1), array('name' => 'PROJECT_ID', 'value' => '', 'type' => '', 'length' => -1), array('name' => 'FINANCE', 'value' => '', 'type' => '', 'length' => -1), array('name' => 'FINANCE_NAME', 'value' => '', 'type' => '', 'length' => -1), array('name' => 'MANAGE_FOLLOW_ID', 'value' => $this->input->post('manage_follow_id'), 'type' => '', 'length' => -1), array('name' => 'CYCLE_FOLLOW_ID', 'value' => $this->input->post('cycle_follow_id'), 'type' => '', 'length' => -1), array('name' => 'DEPARTMENT_FOLLOW_ID', 'value' => '', 'type' => '', 'length' => -1), array('name' => 'MANAGE_EXE_ID', 'value' => $this->input->post('manage_exe_id'), 'type' => '', 'length' => -1), array('name' => 'CYCLE_EXE_ID', 'value' => $this->input->post('cycle_exe_id'), 'type' => '', 'length' => -1), array('name' => 'DEPARTMENT_EXE_ID', 'value' => '', 'type' => '', 'length' => -1), array('name' => 'FROM_MONTH', 'value' => $this->input->post('from_month'), 'type' => '', 'length' => -1), array('name' => 'TO_MONTH', 'value' => $this->input->post('to_month'), 'type' => '', 'length' => -1), array('name' => 'BRANCH_FOLLOW_ID', 'value' => $this->input->post('branch_follow_id'), 'type' => '', 'length' => -1), array('name' => 'BRANCH_EXE_ID', 'value' => $this->input->post('branch_exe_id'), 'type' => '', 'length' => -1), array('name' => 'TOTAL_PRICE', 'value' => $this->input->post('total_price'), 'type' => '', 'length' => -1),


        );


        // return $result;


        if ($typ == 'create') {
            array_shift($result);
        }
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

                        $x = $this->{$this->DETAILS_MODEL_NAME}->create_part($this->_postData_details(null, $this->p_activity_no_id[$i], $this->p_branch[$i], $this->p_part[$i], 'create'));

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

                        $x = $this->{$this->DETAILS_MODEL_NAME}->edit_part($this->_postData_details($this->p_ser1[$i], $this->p_activity_no_id[$i], $this->p_branch[$i], $this->p_part[$i], 'edit'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);

                    }


                }


            }

        }
    }

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

    function _postData_details_achive($ser = null, $activities_plan_ser, $month = null, $achive = null, $type = null)

    {


        $result = array(array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => 'ACTIVITIES_PLAN_SER', 'value' => $activities_plan_ser, 'type' => '', 'length' => -1), array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1), array('name' => 'ACHIVE', 'value' => $achive, 'type' => '', 'length' => -1),


        );


        if ($type == 'create') unset($result[0]);

        return $result;
    }

}

?>
