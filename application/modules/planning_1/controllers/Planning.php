<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/11/17
 * Time: 09:22 ص
 */
class planning extends MY_Controller{

    var $MODEL_NAME= 'plan_model';
    var $DETAILS_MODEL_NAME = 'plan_detail_model';
    var $PAGE_URL= 'planning/planning/get_page';
    var $PAGE_EVALUATE_URL='planning/planning/get_page_evaluate';
    var $PAGE_REFRESH_URL='planning/planning/get_page_refresh';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->year= $this->budget_year;
        $this->ser=$this->input->post('ser');

    }

    function index(){


        $data['title']='تخطيط الأنشطة';
        $data['help']=$this->help;
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->_look_ups($data);
        $data['tech']= 1;
        $data['year_paln']=$this->year;
        $data['content']='planning_index';
        // $data['all_year']= $this->{$this->MODEL_NAME}->GET_YEAR();
        // $data['all_project']= $this->{$this->MODEL_NAME}->get_project();

        $this->load->view('template/template',$data);
    }

    function delete()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete($val);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete($id);
        }


        if ($msg == 1) {
            echo modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = "";
        //$this->user->branch=7;
      $this_year=$this->year;//date('Y')+1;

        $where_sql .= isset($this->p_activity_no) && $this->p_activity_no != null ? " AND  M.ACTIVITY_NO ='{$this->p_activity_no}'  " : "";
        $where_sql .= isset($this->p_year) && $this->p_year != null ? " AND  M.YEAR ={$this->p_year}  " :  " AND  YEAR = {$this_year}";
        $where_sql .= isset($this->p_project_name) && $this->p_project_name != null ? " AND  PROJECT_NAME LIKE '%{$this->p_project_name}%' " : "";
        $where_sql .= isset($this->p_activity_name) && $this->p_activity_name != null ? " AND  ACTIVITY_NAME LIKE '%{$this->p_activity_name}%' " : "";
        $where_sql .= isset($this->p_class_name) && $this->p_class_name != null ? " AND  M.CLASS ={$this->p_class_name} " : "";
        $where_sql .= isset($this->p_type) && $this->p_type != null ? " AND  M.TYPE ={$this->p_type} " : "";
        $where_sql .= isset($this->p_finance) && $this->p_finance != null ? " AND  M.FINANCE ={$this->p_finance} " : "";
        $where_sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  M.BRANCH_EXE_ID ={$this->p_branch}" : "";
        $where_sql .= isset($this->p_from_month) && $this->p_from_month != null ? " AND  M.FROM_MONTH ={$this->p_from_month}" : "";
        $where_sql .=isset($this->p_branch_follow_id) && $this->p_branch_follow_id != null ? " AND  M.BRANCH_FOLLOW_ID ={$this->p_branch_follow_id}" : "";
        $where_sql .=isset($this->p_manage_follow_id) && $this->p_manage_follow_id != null ? " AND  M.MANAGE_FOLLOW_ID ={$this->p_manage_follow_id}" : "";
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
        if ($this->user->branch<>1 )
        {
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_branch($where_sql, $offset, $row);


        }
         else
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('planning_page', $data);


       // $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list(' AND 1=1 ', 0, 1000);


       // $this->load->view('planning_page', $data);
    }

    function create(){



        if ($_SERVER['REQUEST_METHOD'] == 'POST') {



            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }


            for ($i = 0; $i < count($this->p_ser1); $i++)
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
            }

            echo intval($this->ser);

        } else {

            $result=array();
            $achive_res=array();
            $data['planning_data']=$result;
            $data['achive_data']=$achive_res;
            $data['year_paln']=$this->year;
            $data['content'] = 'planning_show';
            $data['title'] = '  موازنة الأنشطة  ::.  الخطة التشغلية   ::.  المشروع الفني';
            $data['action'] = 'index';
            $data['help']=$this->help;
            $data['isCreate']= true;
            $data['tech']= 1;

if($this->uri->segment(4)!='')
{
            $data['have_project_ser']= true;

            if ($this->user->branch==1)
                $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4));
            else
                $data['project']= $this->{$this->MODEL_NAME}->get_project_info($this->uri->segment(4),$this->user->branch);

            if (!(count($data['project']) == 1))
            {
                echo 'not in your permissions';
                die();

            }

}
            else
            {
                $data['have_project_ser']= false;
                $data['project']=array();
            }
            $this->_look_ups($data);

            $this->load->view('template/template', $data);
        }

    }
    function create_without_cost(){



        if ($_SERVER['REQUEST_METHOD'] == 'POST') {



            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData_withoutcost('create'));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }


            for ($i = 0; $i < count($this->p_ser1); $i++)
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
            }

            echo intval($this->ser);

        } else {

            $result=array();
           $achive_res=array();
            $data['year_paln']=$this->year;
            $data['planning_data']=$result;
            $data['achive_data']=$achive_res;
            $data['content'] = 'planning_show_without_cost';
            $data['title'] = '  موازنة الأنشطة  ::.  الخطة التشغلية   ::.  المشروع  بدون تكلفة';
            $data['action'] = 'index';
            $data['help']=$this->help;
            $data['isCreate']= true;
            $data['tech']= 0;
            $this->_look_ups($data);

            $this->load->view('template/template', $data);
        }

    }
    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$this->_post_validation();


            $this->ser  = $this->{$this->MODEL_NAME}->edit($this->_postedData());

            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            //die;
            for ($i = 0; $i < count($this->p_ser1); $i++)
            {
//var_dump(count($this->p_ser1));

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
                else
                {

                    if ($this->p_branch[$i] != '') {

                        if ($this->p_part[$i] == '') {
                            $this->print_error('يجب ان ادخال حصة المقر');
                        }

                        $y=$this->{$this->DETAILS_MODEL_NAME}->edit_part($this->_postData_details( $this->p_ser1[$i],$this->p_activity_no[$i], $this->p_branch[$i], $this->p_part[$i],
                            'edit'));

                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }

                    }


                }



            }
            echo intval($this->ser);
        }
    }

    function edit_without_cost()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$this->_post_validation();


            $this->ser  = $this->{$this->MODEL_NAME}->edit($this->_postedData_withoutcost());

            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            //die;
            for ($i = 0; $i < count($this->p_ser1); $i++)
            {
//var_dump(count($this->p_ser1));

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
                else
                {

                    if ($this->p_branch[$i] != '') {

                        if ($this->p_part[$i] == '') {
                            $this->print_error('يجب ان ادخال حصة المقر');
                        }

                        $y=$this->{$this->DETAILS_MODEL_NAME}->edit_part($this->_postData_details( $this->p_ser1[$i],$this->p_activity_no[$i], $this->p_branch[$i], $this->p_part[$i],
                            'edit'));

                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }

                    }


                }



            }
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
    function get($id)
    {
        if ($this->user->branch==1)

        $result = $this->{$this->MODEL_NAME}->get($id);
    else
        $result = $this->{$this->MODEL_NAME}->get($id,$this->user->branch);

        if (!(count($result) == 1))
        {
            echo 'not in your permissions';
            die();

        }
        $achive_res= $this->{$this->MODEL_NAME}->get_achive($id);
        $data['planning_data'] = $result[0];
        $data['achive_data']=$achive_res;
        $data['content'] = 'planning_show';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['have_project_ser']= true;
        $data['tech']= 1;
        $data['title'] = '  موازنة الأنشطة  ::.  الخطة التشغلية   ::.  المشروع الفني';
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }
    function get_without_cost($id)
    {
	 
		
        if ($this->user->branch==1)

            $result = $this->{$this->MODEL_NAME}->get($id);
        else
            $result = $this->{$this->MODEL_NAME}->get($id,$this->user->branch);

        if (!(count($result) == 1))
        {
            echo 'not in your permissions';
            die();

        }
        $achive_res= $this->{$this->MODEL_NAME}->get_achive($id);
        $data['planning_data'] = $result[0];
        $data['achive_data']=$achive_res;
        $data['content'] = 'planning_show_without_cost';
        $data['action'] = 'edit_without_cost';
        $data['isCreate'] = false;
        $data['title'] = '  موازنة الأنشطة  ::.  الخطة التشغلية   ::.  المشروع  بدون تكلفة';
        $data['help'] = $this->help;
        $data['tech']= 0;
        $this->_look_ups($data);
		
		$this->load->view('template/template', $data);
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

    function public_get_achive($id = 0)
    {
        //  var_dump($id);
        //    die;
        $data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
       // $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        //$this->_look_ups($data);
        //$data['details']='';
        $this->load->view('achive_details', $data);
    }


    function public_get_mange($branch =1){


        $branch = $this->input->post('no')?$this->input->post('no'):$branch;

        $arr = $this->Gcc_structure_model->get_Structure_branch(1,$branch);



        echo json_encode($arr);





    }

    function public_get_mange_b($branch =1){


        $branch = $this->input->post('no')?$this->input->post('no'):$branch;

        $arr = $this->Gcc_structure_model->get_Structure_branch(1,$branch);







        return $arr;

    }

    function public_get_cycle($manage =0){


        $manage = $this->input->post('no')?$this->input->post('no'):$manage;
        //var_dump($manage);
        $arr = $this->Gcc_structure_model->getList2($manage,0);



        echo json_encode($arr);





    }

    function public_get_cycle_b($manage =0){

//var_dump($manage);
        //   die;
        //$manage = $this->input->post('no')?$this->input->post('no'):$manage;
        //var_dump($manage);
        //  die;
        $arr = $this->Gcc_structure_model->getList2($manage,0);



        return $arr;

    }

    function public_get_dep($cycle =0){


        $cycle = $this->input->post('no')?$this->input->post('no'):$cycle;

        $arr = $this->Gcc_structure_model->getList2($cycle,0);



        echo json_encode($arr);





    }

    function public_get_dep_p($cycle =0){


        $cycle = $this->input->post('no')?$this->input->post('no'):$cycle;

        $arr = $this->Gcc_structure_model->getList2($cycle,0);



        return $arr;





    }

    function public_get_goal($objective =0){


        $objective = $this->input->post('no')?$this->input->post('no'):$objective;

        $arr = $this->{$this->MODEL_NAME}->get_objective('',$objective);



        echo json_encode($arr);





    }

    function public_get_goal_p($objective =''){


        $arr = $this->{$this->MODEL_NAME}->get_objective('',$objective);



        return $arr;





    }

/**************************************************************************************************************************************/
    /*  evaluate*/
    /**********************************************************************************************************************************/
    function evaluate()
    {


        $result=array();
        $data['planning_data']=$result;
        $data['content'] = 'evaluate_plan';
        $data['title'] = '   الخطة التشغلية   ::.  تقييم الخطة الشهرية';
        $data['action'] = 'index';
        $data['help']=$this->help;
        $data['isCreate']= true;
        $data['branch_user']=$this->user->branch;
        $data['tech']= 1;



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


        $from_month=isset($this->p_from_month) && $this->p_from_month != null ? $this->p_from_month : date('m');
        $data['page_rows'] = $this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year);
        //echo var_dump($this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year));
       // die;
        $this->_look_ups($data);


        $this->load->view('planning_evaluate_page', $data);



    }
    function save_all_evaluate()
    {
        $x=0;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_persant); $i++)
            {
                if ($this->p_ser_plan[$i] <= 0 and $this->p_status[$i]!='' and $this->p_is_end[$i] and $this->p_persant[$i]!='' ) {

                     if ($this->p_status[$i] != 3) {

                         if ($this->p_notes[$i] == '') {
                             $this->print_error('في حال الخطة الغير منجزة يجب ادخال مبرر عدم الانجاز');
                             die;
                         }
                     }
                         else if($this->p_status[$i] == 3)
                         {
                             if ($this->p_notes[$i]!= '') {
                                 $this->print_error('الخطة منجزة لا يحتاج لادخال مبرر');
                                 //$this->p_notes[$i]='';
                                 die;
                             }
                         }
                    $x=$this->{$this->DETAILS_MODEL_NAME}->create_achive_evaluate($this->_postData_achive_details(null,$this->p_seq[$i], $this->p_status[$i], $this->p_persant[$i], $this->p_notes[$i], $this->p_is_end[$i],
                        'create'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }

                }

                else if($this->p_ser_plan[$i] > 0 and $this->p_status[$i]!='' and $this->p_is_end[$i] and $this->p_persant[$i]!='' ) {

                    if ($this->p_status[$i] != 3) {

                        if ($this->p_notes[$i] == '') {
                            $this->print_error('في حال الخطة الغير منجزة يجب ادخال مبرر عدم الانجاز');
                            die;
                        }
                    }
                    else if($this->p_status[$i] == 3)
                    {
                        if ($this->p_notes[$i]!= '') {
                            $this->print_error('الخطة منجزة لا يحتاج لادخال مبرر');
                            //$this->p_notes[$i]='';
                            die;
                        }
                    }
                    $x=$this->{$this->DETAILS_MODEL_NAME}->edit_achive_evaluate($this->_postData_achive_details($this->p_ser[$i],$this->p_ser_plan[$i], $this->p_status[$i], $this->p_persant[$i], $this->p_notes[$i], $this->p_is_end[$i],
                        'edit'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }



                }

            }
            echo intval($x);
        }

    }
    function adopt_all_evaluate()
    {
        $x='0';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_ser); $i++)
            {



                if($this->p_ser[$i] !='' and $this->p_ser_plan[$i]!='' and $this->p_ser_status[$i]!='' and $this->p_ser_next_month_val[$i]!='') {
                //if($this->p_ser[$i] !='') {

                    $x=$this->{$this->MODEL_NAME}->activities_plan_achive_adopt($this->p_ser[$i],$this->p_ser_plan[$i],$this->p_ser_status[$i],$this->p_ser_next_month_val[$i]);//$this->{$this->DETAILS_MODEL_NAME}->activities_plan_refrech_update($this->p_ser[$i],$this->p_from_month[$i]);

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }



                }

            }
            echo intval($x);
        }

    }

    /**************************************************************************************************************************************/
    /*  refresh*/
    /**********************************************************************************************************************************/
    function refresh()
    {
        $result=array();
        $data['planning_data']=$result;
        $data['content'] = 'refresh_plan';
        $data['title'] = '   الخطة التشغلية   ::.  تحديث الخطة الشهرية';
        $data['action'] = 'index';
        $data['help']=$this->help;
        $data['isCreate']= true;
        $data['tech']= 1;



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
        $x='0';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_from_month); $i++)
            {




              if($this->p_from_month[$i] !='' ) {


                    $x=$this->{$this->DETAILS_MODEL_NAME}->activities_plan_refrech_update($this->p_ser[$i],$this->p_from_month[$i]);

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }



                }

            }
            echo intval($x);
        }


    }
    function adopt_all_refresh()
    {



        $x='0';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_ser); $i++)
            {




                if($this->p_ser[$i] !='' and $this->p_ser_plan[$i]!='' and $this->p_for_month[$i]!='') {

                 $x=$this->{$this->MODEL_NAME}->activities_plan_refrech_adopt($this->p_ser[$i],$this->p_ser_plan[$i],$this->p_for_month[$i]);//$this->{$this->DETAILS_MODEL_NAME}->activities_plan_refrech_update($this->p_ser[$i],$this->p_from_month[$i]);

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
    function follow()
    {


        $result=array();
        $data['planning_data']=$result;
        $data['content'] = 'follow_plan';
        $data['title'] = '   الخطة التشغلية   ::.  متابعة الخطة الشهرية';
        $data['action'] = 'index';
        $data['help']=$this->help;
        $data['isCreate']= true;
        $data['branch_user']=$this->user->branch;
        $data['tech']= 1;



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


        $from_month=isset($this->p_from_month) && $this->p_from_month != null ? $this->p_from_month : date('m');
        $data['page_rows'] = $this->{$this->MODEL_NAME}->activities_plan_follow_list($from_month,$this->year);
        //echo var_dump($this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year));
        // die;
        $this->_look_ups($data);


        $this->load->view('planning_follow_page', $data);



    }
    /********************************************************************************************************************************/
    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');



        $data['activity_class'] = $this->constant_details_model->get_list(193);
        $data['finance_type'] = $this->constant_details_model->get_list(197);
        $data['activity_type'] = $this->constant_details_model->get_list(199);
        $data['is_end'] = $this->constant_details_model->get_list(205);
        $data['status'] = $this->constant_details_model->get_list(206);
        $data['adopt'] = $this->constant_details_model->get_list(207);

        if ($this->user->branch==1)
        {

            $data['branches_follow'] =  $this->gcc_branches_model->get_all();//$this->gcc_branches_model->user_branch($this->user->id);
            $data['branches'] = $this->gcc_branches_model->get_all();
            $data['all_project']= $this->{$this->MODEL_NAME}->get_project(null,$this->year);
            // $data['branches'] = $this->gcc_branches_model->user_branch($this->user->id);
        }
        else
        {
            $data['branches_follow'] = $this->gcc_branches_model->get(1);//$this->gcc_branches_model->user_branch($this->user->id);
            $data['branches'] =$this->gcc_branches_model->user_branch($this->user->id);
            $data['all_project']= $this->{$this->MODEL_NAME}->get_project($this->user->branch,$this->year);
        }

        $data['all_objective']= $this->{$this->MODEL_NAME}->get_objective('',0);


        //  $data['all_project']= $this->{$this->MODEL_NAME}->get_project();






    }
    function adopt()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_id != '' ||$this->p_id != 0) ) {
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,$this->p_adopts);

            if (intval($res) <= 0) {
                echo intval($res);
            }
            else
                echo intval($res);

        } else
            echo "لم يتم ارسال رقم الخطة التشغيلية";
    }

    function public_get_id(){


        $id = $this->input->post('id');
        // $this->print_error($id);
        $result =$this->{$this->MODEL_NAME}->get_project_info($id);
        //print_r($result);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function _postedData($typ= null,$activity_name='',$detailes='',$status=1){

        if ($this->input->post('type')==1)
            $project_id='';
        else if ($this->input->post('type')==2)
            $project_id=$this->input->post('project_id');


        $result = array(
            array('name'=>'SEQ','value'=>$this->input->post('seq') ,'type'=>'','length'=>-1),
            array('name'=>'YEAR','value'=>$this->input->post('year') ,'type'=>'','length'=>-1),
            array('name'=>'CLASS','value'=>$this->input->post('class') ,'type'=>'','length'=>-1),
            array('name'=>'TYPE','value'=>$this->input->post('type') ,'type'=>'','length'=>-1),
            array('name'=>'OBJECTIVE','value'=>$this->input->post('objective') ,'type'=>'','length'=>-1),
            array('name'=>'GOAL','value'=>$this->input->post('goal') ,'type'=>'','length'=>-1),
            array('name'=>'GOAL_T','value'=>$this->input->post('goal_t') ,'type'=>'','length'=>-1),
            array('name'=>'ACTIVITY_NAME','value'=>$activity_name ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_ID','value'=>$this->input->post('project_id') ,'type'=>'','length'=>-1),
            array('name'=>'FINANCE','value'=>$this->input->post('finance') ,'type'=>'','length'=>-1),
            array('name'=>'FINANCE_NAME','value'=>$this->input->post('finance_name') ,'type'=>'','length'=>-1),
            array('name'=>'MANAGE_FOLLOW_ID','value'=>$this->input->post('manage_follow_id') ,'type'=>'','length'=>-1),
            array('name'=>'CYCLE_FOLLOW_ID','value'=>$this->input->post('cycle_follow_id') ,'type'=>'','length'=>-1),
            array('name'=>'DEPARTMENT_FOLLOW_ID','value'=>$this->input->post('department_follow_id') ,'type'=>'','length'=>-1),
            array('name'=>'MANAGE_EXE_ID','value'=>$this->input->post('manage_exe_id') ,'type'=>'','length'=>-1),
            array('name'=>'CYCLE_EXE_ID','value'=>$this->input->post('cycle_exe_id') ,'type'=>'','length'=>-1),
            array('name'=>'DEPARTMENT_EXE_ID','value'=>$this->input->post('department_exe_id') ,'type'=>'','length'=>-1),
            array('name'=>'FROM_MONTH','value'=>$this->input->post('from_month') ,'type'=>'','length'=>-1),
            array('name'=>'TO_MONTH','value'=>$this->input->post('to_month') ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_FOLLOW_ID','value'=>$this->input->post('branch_follow_id') ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_EXE_ID','value'=>$this->input->post('branch_exe_id') ,'type'=>'','length'=>-1),





        );

//var_dump($result);

        // return $result;



        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }


    function _postData_details($ser = null,$activity_no, $branch=null, $part=null,$type=null)

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

    function _postedData_withoutcost($typ= null,$activity_name='',$detailes='',$status=1){


        $result = array(
            array('name'=>'SEQ','value'=>$this->input->post('seq') ,'type'=>'','length'=>-1),
            array('name'=>'YEAR','value'=>$this->input->post('year') ,'type'=>'','length'=>-1),
            array('name'=>'CLASS','value'=>3 ,'type'=>'','length'=>-1),
            array('name'=>'TYPE','value'=>$this->input->post('type') ,'type'=>'','length'=>-1),
            array('name'=>'OBJECTIVE','value'=>$this->input->post('objective') ,'type'=>'','length'=>-1),
            array('name'=>'GOAL','value'=>$this->input->post('goal') ,'type'=>'','length'=>-1),
            array('name'=>'GOAL_T','value'=>$this->input->post('goal_t') ,'type'=>'','length'=>-1),
            array('name'=>'ACTIVITY_NAME','value'=>$this->input->post('activity_name') ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_ID','value'=>$this->input->post('project_id') ,'type'=>'','length'=>-1),
            array('name'=>'FINANCE','value'=>$this->input->post('finance') ,'type'=>'','length'=>-1),
            array('name'=>'FINANCE_NAME','value'=>$this->input->post('finance_name') ,'type'=>'','length'=>-1),
            array('name'=>'MANAGE_FOLLOW_ID','value'=>$this->input->post('manage_follow_id') ,'type'=>'','length'=>-1),
            array('name'=>'CYCLE_FOLLOW_ID','value'=>$this->input->post('cycle_follow_id') ,'type'=>'','length'=>-1),
            array('name'=>'DEPARTMENT_FOLLOW_ID','value'=>$this->input->post('department_follow_id') ,'type'=>'','length'=>-1),
            array('name'=>'MANAGE_EXE_ID','value'=>$this->input->post('manage_exe_id') ,'type'=>'','length'=>-1),
            array('name'=>'CYCLE_EXE_ID','value'=>$this->input->post('cycle_exe_id') ,'type'=>'','length'=>-1),
            array('name'=>'DEPARTMENT_EXE_ID','value'=>$this->input->post('department_exe_id') ,'type'=>'','length'=>-1),
            array('name'=>'FROM_MONTH','value'=>$this->input->post('from_month') ,'type'=>'','length'=>-1),
            array('name'=>'TO_MONTH','value'=>$this->input->post('to_month') ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_FOLLOW_ID','value'=>$this->input->post('branch_follow_id') ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_EXE_ID','value'=>$this->input->post('branch_exe_id') ,'type'=>'','length'=>-1),





        );



        // return $result;



        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }

    function _postData_achive_details($ser = null,$ser_plan, $status=null, $persant=null,$notes=null,$is_end=null,$type=null)

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

}

?>
