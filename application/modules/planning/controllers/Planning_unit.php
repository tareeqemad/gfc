<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/03/18
 * Time: 08:40 ص
 */
class planning_unit extends MY_Controller{

    var $MODEL_NAME= 'plan_model';
    var $MODEL_NAME_= 'Vision_mission_model';
    var $DETAILS_MODEL_NAME = 'plan_detail_model';
    var $PAGE_URL= 'planning/planning/get_page';
    var $PAGE_EVALUATE_URL='planning/planning/get_page_evaluate';
    var $PAGE_REFRESH_URL='planning/planning/get_page_refresh';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model($this->MODEL_NAME_);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->year= $this->budget_year;
        $this->ser=$this->input->post('ser');

    }
    /******************************************************************************************************************************************************/
    /*                                                             Goal && Objective                                                                      */
    /******************************************************************************************************************************************************/
    function index(){


        $result=array();
        $achive_res=array();
        //$data['year_paln']=$this->year;
        $data['year_paln']=date('Y')+1;
        $data['content'] = 'planning_goal_objective_index';
        $data['title'] =  'إدارة الإطار الإستراتيجي';
        $data['action'] = 'index';
        $data['help']=$this->help;
        $data['isCreate']= true;


        $this->_look_ups($data);

        $this->load->view('template/template', $data);
    }
    function get_page()
    {

        $data['str_plan'] =$this->{$this->MODEL_NAME_}->vision_mission_tb_last();
	if(!empty($data['str_plan']))
	{
        $data['objectives']= $this->{$this->MODEL_NAME_}->get_objective($data['str_plan'][0]['SER'],0);

        $this->load->view('planning_unit_goal_objective_page', $data);
}

    }

    function get_page2()
    {


            $data['str_plan'] =$this->{$this->MODEL_NAME_}->get($this->p_page);



        $data['objectives']= $this->{$this->MODEL_NAME_}->get_objective($data['str_plan'][0]['SER'],0);

       $response= $this->load->view('planning_unit_goal_objective_page', $data,true);
       echo $response;



    }
    function public_get_goal_p($father){
        $arr = $this->{$this->MODEL_NAME_}->get_objective_($father);



        return $arr;





    }
    function create(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_post_validation();
            $this->ser = $this->{$this->MODEL_NAME_}->create($this->_postedData('create'));
            if (intval($this->ser) <= 0) {
                $this->print_error(' لم يتم الحفظ الجدول الاساسي ' . '<br>' . $this->ser);
            } else {

                for ($j = 0; $j < count($this->p_v_id); $j++)
                {
                    if ($this->p_v_id[$j] <= 0 || $this->p_v_id[$j]=='' ) {

                        if( $this->p_v_name[$j] != ''){
                            $serValues=$this->{$this->DETAILS_MODEL_NAME}->create_values($this->_postedData_values(null,$this->p_v_lable[$j],$this->p_v_name[$j],
                                $this->ser,'create'));

                        if (intval($serValues) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $serValues);
                        }

                    }
                }



                }
                echo intval($this->ser);
            }
        } else {

            $data['content']         = 'planning_goal_objective';
            $data['title']           = 'الإطار الإستراتيجي';
            $data['isCreate']        = true;
            $data['action']          = 'index';
            $data['year_paln']=$this->year;
            $data['help']=$this->help;
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }


    }

    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_post_validation();
            $res = $this->{$this->MODEL_NAME_}->edit($this->_postedData(''));

            $v_flag = 1;

            for ($j = 0; $j < count($this->p_v_id); $j++)
            {

                if ($this->p_v_id[$j] <= 0 || $this->p_v_id[$j]=='' ) {

                    if( $this->p_v_name[$j] != ''){

                        $serAxis=$this->{$this->DETAILS_MODEL_NAME}->create_values($this->_postedData_values(null,$this->p_v_lable[$j],$this->p_v_name[$j],
                            $res,'create'));
                        if(intval($serAxis) <= 0)
                            $v_flag = 0;
                    }
                } else {
                    if( $this->p_v_name[$j] != ''){
                        $serAxis=$this->{$this->DETAILS_MODEL_NAME}->edit_values($this->_postedData_values($this->p_v_id[$j],$this->p_v_lable[$j],$this->p_v_name[$j],
                            $this->p_plan_no[$j],'edit'));
                        if(intval($serAxis) <= 0)
                            $v_flag = 0;
                    }
                    if (intval($v_flag) <= 0) {
                        $this->print_error($serAxis);
                    }
                }
            }
            $flag = 1;
            for ($j = 0; $j < count($this->p_id); $j++)
            {
                if ($this->p_id[$j] <= 0 || $this->p_id[$j]=='' ) {

                    if( $this->p_id_name[$j] != ''){
                        $serAxis=$this->{$this->DETAILS_MODEL_NAME}->create_objective($this->_postedData_no_teq_proj_mix(null,$this->p_id_label[$j],$this->p_id_name[$j],0,
                            $this->p_plan_no[$j],$this->p_weight[$j],'create'));
                        if(intval($serAxis) <= 0)
                            $flag = 0;
                    }
                } else {
                    if( $this->p_id_name[$j] != ''){
                       $serAxis=$this->{$this->DETAILS_MODEL_NAME}->edit_objective($this->_postedData_no_teq_proj_mix( $this->p_id[$j],$this->p_id_label[$j],$this->p_id_name[$j],0,
                            $this->p_plan_no[$j],$this->p_weight[$j],'edit'));
                        if(intval($serAxis) <= 0)
                            $flag = 0;
                    }
                    if (intval($flag) <= 0) {
                        $this->print_error($serAxis);
                    }
                }
            }



            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            }

              echo 1;



        }
    }
function StrategicPlan()
{
      $data['str_plan'] =$this->{$this->MODEL_NAME_}->vision_mission_get_last();
  
	if(!empty($data['str_plan']))
	{
	$result = $this->{$this->MODEL_NAME_}->get($data['str_plan'][0]['SER']);
   if (!(count($result) == 1))
            die('get');
        $data['master_tb_data'] = $result;
        $data['can_edit']       = 1;
        $data['action']         = 'get';
        $data['isCreate']        = false;
        $data['help']=$this->help;
        $data['content']         = 'planning_goal_objective';
        $data['title']           = 'الإطار الإستراتيجي';
		$this->_look_ups($data);
        $this->load->view('template/template', $data);    
	}
else
{
die;
}	



}
    function get($id)
    {
        $result = $this->{$this->MODEL_NAME_}->get($id);
        if (!(count($result) == 1))
            die('get');
        $data['master_tb_data'] = $result;
        $data['can_edit']       = 1;
        $data['action']         = 'edit';
        $data['isCreate']        = false;
        $data['help']=$this->help;
        $data['content']         = 'planning_goal_objective';
        $data['title']           = 'الإطار الإستراتيجي';
		$this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    ///////////////////////////////////////////////////////////////
    function adopt()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {

            if (HaveAccess(base_url("planning/planning_unit/adopt"))) {

                $res = $this->{$this->MODEL_NAME_}->adopt($this->p_ser, 2);

					if($res == -1)
				{
				$this->print_error('أوزان المحاور لا تساوي 100!!' . '<br>' . $res);
				die;
				}
				else if ($res==-2)
				{
				$this->print_error('الاهداف الاستراتجية الخاصة غير متزنة!!' . '<br>' . $res);
				die;
				}
				else if ($res==-3)
				{
				$this->print_error('الاهداف الاستراتجية العامة غير متزنة!!' . '<br>' . $res);
				die;
				}
					else if ($res==-4)
				{
				$this->print_error('لم يتم الاعتماد!!' . '<br>' . $res);
				die;
				}
							else if ($res==-5)
				{
				$this->print_error('لم يتم الغاء الاعتماد!!' . '<br>' . $res);
				die;
				}
				else if ($res==1)
				{
				 echo 1;
				die;
				}
                if (intval($res) <= 0) {
				
				
				
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
					die;
			   
			    
                }
		
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function un_adopt()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_ser != '') {
          if (HaveAccess(base_url("planning/planning_unit/un_adopt"))) {

          $res = $this->{$this->MODEL_NAME_}->adopt($this->p_ser, 1);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الغاء الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الغاء الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
	/////////////////////////////////////////////////
	    function delete_goals()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->DETAILS_MODEL_NAME}->delete_goals($this->p_id,0);
             if (intval($res) <= 0) {
			 if(intval($res) == -1)
                     echo 'لا يمكن الحذف الخطة معتمدة!!';
			  else if(intval($res) == -2)
                     echo '!!لا يمكن الحذف المحور يحتوي على أهداف استراتجية';
			  else if(intval($res) == -3)
                     echo ' لا يمكن الحذف !! يحتوي على اهداف استراتجية خاصة   ';
				 else if(intval($res) == -4)
                     echo ' لا يمكن الحذف !! يحتوي على مشاريع مدخلة' ;	 
					 
                }
				
				
			else
			echo 1;
               
        } else
             echo "اجراء خاطئ فشل في عملية الحذف!!";
    }
	/////////////////////////////////////////////////
	    function delete_objective()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->DETAILS_MODEL_NAME}->delete_goals($this->p_id,1);
             if (intval($res) <= 0) {
			 if(intval($res) == -1)
                     echo 'لا يمكن الحذف الخطة معتمدة!!';
			  else if(intval($res) == -2)
                     echo '!!لا يمكن الحذف المحور يحتوي على أهداف استراتجية';
			  else if(intval($res) == -3)
                     echo ' لا يمكن الحذف !! يحتوي على اهداف استراتجية خاصة   ';
				 else if(intval($res) == -4)
                     echo ' لا يمكن الحذف !! يحتوي على مشاريع مدخلة' ;	 
					 
                }
				
				
			else
			echo 1;
               
        } else
             echo "اجراء خاطئ فشل في عملية الحذف!!";
    }
	///////////////////////////////////////////////////
		    function delete_spefic_objective()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->DETAILS_MODEL_NAME}->delete_goals($this->p_id,2);
             if (intval($res) <= 0) {
			 if(intval($res) == -1)
                     echo 'لا يمكن الحذف الخطة معتمدة!!';
			  else if(intval($res) == -2)
                     echo '!!لا يمكن الحذف المحور يحتوي على أهداف استراتجية';
			  else if(intval($res) == -3)
                     echo ' لا يمكن الحذف !! يحتوي على اهداف استراتجية خاصة   ';
				 else if(intval($res) == -4)
                     echo ' لا يمكن الحذف !! يحتوي على مشاريع مدخلة' ;	 
					 
                }
				
				
			else
			echo 1;
               
        } else
             echo "اجراء خاطئ فشل في عملية الحذف!!";
    }
	
/********************************************************************************************/
    function public_get_goal($id = 0){
        $data['goals_res'] = $this->{$this->MODEL_NAME_}->get_objective($id,0);
        $data['id']=$id;
       // $this->_lookup($data);
        $this->load->view('plan_goals_details', $data);
    }
/***********************************************************************************************/
    function public_get_Objective($type,$id,$plan_no)
    {
	   if($type==1)
	    {
        $data['refrence'] = 'PLAN';
		}
		else if ($type==2)
		{
		 $data['refrence'] = 'StrategicPlan';
		}
		else
		die;
		$data['content'] = 'plan_objective_details';
        $data['details'] = $this->{$this->MODEL_NAME_}->get_objective($plan_no,$id);
		$data['id']=$id;
        $data['plan']=$plan_no;
        $this->_look_ups($data);
        //$data['details']='';
        $this->load->view('template/modal', $data);
    }
    /***************************************************/
    function public_get_stratgic($type,$id,$plan_no)
    {
	    if($type==1)
	    {
        $data['refrence'] = 'PLAN';
		}
		else if ($type==2)
		{
		 $data['refrence'] = 'StrategicPlan';
		}
		else
		die;
        $data['content'] = 'plan_stratgic_details';
        $data['child_objective'] = $this->{$this->MODEL_NAME_}->get_child_objective($plan_no,$id);
        $data['details'] = $this->{$this->MODEL_NAME_}->get_objective($plan_no,$id);
        $data['id']=$id;
        $data['plan']=$plan_no;
		$this->_look_ups($data);
        //$data['details']='';
        $this->load->view('template/modal', $data);
    }
    /********************************************************************************************/
    function public_get_values($id = 0){
        $data['values_res'] = $this->{$this->MODEL_NAME_}->get_values($id);
        $data['id']=$id;
        // $this->_lookup($data);
        $this->load->view('plan_values_details', $data);
    }
/***************************************************************************************************/
    function create_edit_stratgic_goals(){


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            for ($i = 0; $i < count($this->p_id); $i++)
            {
                if ($this->p_id[$i] <= 0 || $this->p_id[$i]=='' ) {

                    if ($this->p_id_name[$i] == '' || $this->p_id_label[$i] == '' || $this->p_plan_no[$i] == '' || $this->p_id_father[$i] == '' ) {
                        $this->print_error('يتوجب ادخال جميع الحقول!!');
                    }

                        $x=$this->{$this->DETAILS_MODEL_NAME}->create_objective($this->_postedData_no_teq_proj_mix(null,$this->p_id_label[$i],$this->p_id_name[$i],$this->p_id_father[$i],
                            $this->p_plan_no[$i],$this->p_weight[$i],'create'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);



                }
                else
                {
            if ($this->p_id_name[$i] == '' || $this->p_id_label[$i] == '' || $this->p_plan_no[$i] == '' || $this->p_id_father[$i] == '' ) {
                        $this->print_error('يتوجب ادخال جميع الحقول!!');
                    }


                        $x=$this->{$this->DETAILS_MODEL_NAME}->edit_objective($this->_postedData_no_teq_proj_mix( $this->p_id[$i],$this->p_id_label[$i],$this->p_id_name[$i],$this->p_id_father[$i],
                            $this->p_plan_no[$i],$this->p_weight[$i],'edit'));

                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                        echo intval($x);




                }


            }

        }
    }
/********************************************************************************************************/
    function show_plan_details($id=0)
    {
        $result=array();
        $achive_res=array();
        $data['year_paln']=$this->year;
        $data['content'] = 'show_plan_scheduler';
        $data['title'] = 'عرض الخطة التفصيلية للمشاريع';
        $data['action'] = 'index';
        $data['help']=$this->help;
        $data['isCreate']= true;



        //$this->_look_ups($data);

        $this->load->view('template/CustomTemplate', $data);

    }
    function public_get_timeline($no=0){


         $arr = $this->{$this->MODEL_NAME}->get_timeline($this->year);



        echo json_encode($arr);





    }

    function public_get_Children($no=0){


        $arr = $this->{$this->DETAILS_MODEL_NAME}->get_details_activites_all($no);



        echo json_encode($arr);





    }
    /*************************************************/
    function create_edit_plan(){



        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_id == '' ||$this->p_id == 0) ) {
                $res = 1;//$this->{$this->MODEL_NAME_}->create_goals($this->_postedData_no_teq_proj_mix('create',0));
                }
            else
            {
                $res = 1;//$this->{$this->MODEL_NAME_}->edit_goals($this->_postedData_no_teq_proj_mix(null,0));

            }

            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            }



            echo intval($res);

        }

    }
    /*******************************************************************************************************************************/
    function Permission($id=0)
    {
        




    }
    /********************************************************************************************************************************/
    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');

        /*add_css('Newfullcalendar/lib/fullcalendar.min.css');
        add_css('Newfullcalendar/lib/fullcalendar.print.min.css');
        add_css('Newfullcalendar/scheduler.min.css');
        add_js('Newfullcalendar/lib/moment.min.js');
        add_js('Newfullcalendar/lib/jquery.min.js');
        add_js('Newfullcalendar/lib/fullcalendar.min.js');
        add_js('Newfullcalendar/scheduler.js');
        //add_js('Newfullcalendar/lang-all.js');*/
        $data['activity_class'] = $this->constant_details_model->get_list(193);
        $data['activity_class_no_tech'] = $this->{$this->MODEL_NAME}->get_list_no_tech();
        $data['finance_type'] = $this->constant_details_model->get_list(197);
        $data['activity_type'] = $this->constant_details_model->get_list(199);
        $data['is_end'] = $this->constant_details_model->get_list(205);
        $data['status'] = $this->constant_details_model->get_list(206);
        $data['adopt'] = $this->constant_details_model->get_list(207);
        $data['stratgic_plan'] =$this->{$this->MODEL_NAME_}->vision_mission_tb_list();  //$this->gcc_branches_model->get_all();//$this->gcc_branches_model->user_branch($this->user->id);
        if ($this->user->branch==1)
        {


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

        $data['all_objective']= $this->{$this->MODEL_NAME}->get_objective('',0,date('Y')+1);


        //  $data['all_project']= $this->{$this->MODEL_NAME}->get_project();






    }
/**************************************************************/
    function _post_validation()
    {

            if ($this->p_title == '' && $this->p_from_year == '' && $this->p_to_year == '' && $this->p_vision == '' && $this->p_mission == '' && $this->p_valuess == '' ) {
                $this->print_error('يتوجب ادخال جميع الحقول!!');
            }


    }
/**************************************************************/
    function _postedData($isCreate)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'TITLE','value' => $this->p_title,'type' => '','length' => -1),
            array('name' => 'FROM_YEAR','value' => $this->p_from_year,'type' => '','length' => -1),
            array('name' => 'TO_YEAR','value' => $this->p_to_year,'type' => '','length' => -1),
            array('name' => 'VISION','value' => $this->p_vision,'type' => '','length' => -1),
            array('name' => 'MISSION','value' => $this->p_mission,'type' => '','length' => -1)

        );
        if ($isCreate == 'create') {
            array_shift($result);
        }

        return $result;
    }
/*************************************************************************/
    function _postedData_no_teq_proj_mix($id = null,$id_label, $id_name=null, $id_father,$plan_no,$weight,$type=null){



        $res_details = array(
            array('name' => 'ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'ID_LABEL','value' => $id_label,'type' => '','length' => -1),
            array('name' => 'ID_NAME','value' => $id_name,'type' => '','length' => -1),
            array('name' => 'ID_FATHER','value' => $id_father,'type' => '','length' => -1),
            array('name' => 'PLAN_NO','value' => $plan_no,'type' => '','length' => -1),
            array('name' => 'WEIGHT','value' => $weight,'type' => '','length' => -1),
        );
        if ($type == 'create') {

            unset($res_details[0]);
        }

        return $res_details;


    }
    /******************************************************/
    function _postedData_values($id = null,$v_label, $v_name=null,$plan_no,$type=null){



        $res_details = array(
            array('name' => 'SER', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'V_LABEL','value' => $v_label,'type' => '','length' => -1),
            array('name' => 'V_NAME','value' => $v_name,'type' => '','length' => -1),
            array('name' => 'PLAN_NO','value' => $plan_no,'type' => '','length' => -1),

        );
        if ($type == 'create') {

            unset($res_details[0]);
        }

        return $res_details;


    }
}

?>



