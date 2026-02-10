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
    /******************************************************************************************************************************************************/
    /*                                                             Goal && Objective                                                                      */
    /******************************************************************************************************************************************************/
    function index(){


        $result=array();
        $achive_res=array();
        $data['year_paln']=$this->year;
        $data['content'] = 'planning_goal_objective_index';
        $data['title'] = 'ادارة الرؤية و الرسالة و الغايات الاستراتيجية';
        $data['action'] = 'index';
        $data['help']=$this->help;
        $data['isCreate']= true;
        $this->_look_ups($data);

        $this->load->view('template/template', $data);
    }
    function get_page($page = 1)
    {


        $from_month=isset($this->p_from_month) && $this->p_from_month != null ? $this->p_from_month : date('m');
        $data['page_rows'] = $this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year);
        //echo var_dump($this->{$this->MODEL_NAME}->activities_plan_achive_tb_list($from_month,$this->year));
        // die;
        $this->_look_ups($data);


        $this->load->view('planning_unit_goal_objective_page', $data);



    }
    function create_goal(){


        $result=array();
        $achive_res=array();
        $data['year_paln']=$this->year;
        $data['content'] = 'planning_goal_objective';
        $data['title'] = 'ادارة الرؤية و الرسالة و الغايات الاستراتيجية';
        $data['action'] = 'index';
        $data['help']=$this->help;
        $data['isCreate']= true;
        $this->_look_ups($data);

        $this->load->view('template/template', $data);
    }


    function public_get_Objective($id=0)
    {
        //  var_dump($id);
        //    die;
        //$data['achive_res'] = $this->{$this->MODEL_NAME}->get_achive($id);
        $data['content'] = 'plan_objective_details';
        $data['details'] = array();//$this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        $data['id']=$id;
        $this->_look_ups($data);
        //$data['details']='';
        $this->load->view('template/modal', $data);
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
        //  add_css('simple-line-icons.min.css');
        add_css('jquery.dataTables.css');
        //add_css('components-md-rtl.css');
        //  add_css('plugins-md-rtl.css');
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









}

?>
