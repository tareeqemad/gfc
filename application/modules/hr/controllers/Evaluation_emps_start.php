<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 13/06/16
 * Time: 11:43 ص
 */

class Evaluation_emps_start extends MY_Controller {
    var $MODEL_NAME= 'Evaluations_emps_structure_model';
    var $FORM_MODEL_NAME= 'evaluation_form_model';
    var $AXES_MODEL_NAME= 'evaluation_form_axes_model';
    var $ASK_MODEL_NAME= 'evaluation_form_axes_ask_model';
    var $AXES_MARKS_MODEL_NAME= 'evaluation_form_axes_marks_model';
    var $EXTRA_AXES_MODEL_NAME= 'evaluation_extra_axes_model';
    var $EXTRA_AXES_ASK_MODEL_NAME= 'evaluation_extra_axes_ask_model';
    var $ORDER_MODEL_NAME= 'evaluation_order_model';
    var $EMP_ORDER_MODEL_NAME= 'evaluation_emp_order_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->FORM_MODEL_NAME);

        $this->load->model($this->ORDER_MODEL_NAME);
        $where_sql= " where status>= '2' and level_active= '1' ";
        $this->order_row = $this->{$this->ORDER_MODEL_NAME}->get_list($where_sql, 0, 10);
        if(count($this->order_row) != 1){
            die('Error : NO Activated Order');
        }
        $this->user_id = $this->user->emp_no;
        //$this->user_id = 740; //////////////////**** //////////////////
    }

    function index($manager_no=0){
        $eval_emps_url= base_url("hr/emps_structure_tree/eval_emps");
        $data['show_btn']=1;
        if($manager_no!=0 ){
            if(HaveAccess($eval_emps_url)){
                $this->user_id = $manager_no;
            }
            $data['show_btn']=0;
        }

        // هذا الشرط هام جدا جدا لا يجب الغاءه في اي حال من الاحوال والا سوف تندمون
        if( !isset($this->order_row[0]['LEVEL_ACTIVE']) or $this->order_row[0]['LEVEL_ACTIVE']>=7){
            die('Error : LEVEL_ACTIVE_7'); // امر التقييم معتمد اعتماد نهائي
        }

        $data['title']='بدء التقييم';
        $data['content']='evaluation_emps_start_index';
        $data['get_child']= $this->{$this->MODEL_NAME}->get_child($this->user_id);
        $data['get_brother']= $this->{$this->MODEL_NAME}->get_brother($this->user_id);
        $data['get_grandson']= $this->{$this->MODEL_NAME}->get_grandson($this->user_id);

        if(count($this->order_row) != 1)
            $data['effective_order']= -1;
        else
            $data['effective_order']=$this->order_row[0];
        $this->load->view('template/template',$data);
    }

    function get_form_by_emp($emp_no= 0, $type= 0){
        $res= $this->{$this->FORM_MODEL_NAME}->get_form_by_emp($this->user_id,$emp_no,$type);
        if(count($res)==1)
            return $res[0]['FORM_ID'];
        else
            return 0;
    }

    function get_extra_by_emp($emp_no= 0, $type= 0){
        $this->load->model($this->EXTRA_AXES_MODEL_NAME);
        $res= $this->{$this->EXTRA_AXES_MODEL_NAME}->get_extra_by_emp($emp_no,$type);
        if($res=='NO_EEXTRA_ENTER')
            return -3;
        elseif($res=='ERROR_TOTAL_NOT_100')
            return -2;
        elseif($res=='NO_EEXTRA')
            return -1;
        elseif(count($res)==1)
            return $res[0]['EEXTRA_ID'];
        else
            return 0;
    }

    //Check if employee had evaluate or not
    function public_get_serial($id,$type){
        $this->load->model($this->EMP_ORDER_MODEL_NAME);
        return $this->{$this->EMP_ORDER_MODEL_NAME}->get_serial($id,$type);
    }

    // return status of adopt
    function public_get($id){
        $this->load->model($this->EMP_ORDER_MODEL_NAME);
        $res = $this->{$this->EMP_ORDER_MODEL_NAME}->get_adopt($id);
        return $res[0]['ADOPT'];
    }

    function show_form_asks($emp_no= 0, $type= 0){

        if(count($this->public_get_serial($emp_no,$type))==1){die('show_form_asks');}
        $this->load->model($this->AXES_MODEL_NAME);
        $this->load->model('employees/employees_model');
        $emp_data= $this->employees_model->get($emp_no);
        // رقم النموذج
        $form_id= $this->get_form_by_emp($emp_no, $type);

        if($type==1){
            // رقم المحور الاشرافي ان وجد
            $data['eextra_id'] = $this->get_extra_by_emp($emp_no, 1); // test $emp_no 740
            $form_data= $this->{$this->FORM_MODEL_NAME}->get($form_id);
            if(count($form_data)==1)
                $form_name= $form_data[0]['EVALUATION_FORM_NAME'];
            else
                $form_name='';

            if(intval($form_id) <= 0)
                $data['axes']= array();
            else
                $data['axes']= $this->{$this->AXES_MODEL_NAME}->get_list($form_id);

        }elseif($type==2){
            $form_name='تقييم الزملاء';
            $data['brother_form']= $form_id;
        }elseif($type==3){
            $form_name='تقييم المرؤسيين';
            $data['grandson_form']= $form_id;
        }

        $data['FORM_ID']= $form_id;
        $data['EVALUATION_ORDER_ID']= $this->order_row[0]['EVALUATION_ORDER_ID'];
        $data['MANAGER_NO']= $this->user_id;
        $data['EMP_NO']= $emp_no;
        $data['FORM_TYPE']= $type;
        $data['title']='تقييم الموظف '.' / '.$emp_data[0]['NAME'].' - '.$form_name;
        $data['content']='evaluation_emps_start_show';
        $data['help']=$this->help;

        $this->load->view('template/template',$data);
    }

    function get_asks($efa_id=0, $form_id){
        $this->load->library('user_agent');
        $data['browser']= $this->agent->browser();
        $this->load->model($this->ASK_MODEL_NAME);
        $data['FORM_ID'] =$form_id;
        $data['page_rows'] =$this->{$this->ASK_MODEL_NAME}->get_list($efa_id);
        $this->load->view('evaluation_emps_start_page',$data);
    }

    function get_ask_mark($ask_id=0){
        $this->load->model($this->AXES_MARKS_MODEL_NAME);
        $res= $this->{$this->AXES_MARKS_MODEL_NAME}->get_list($ask_id);
        echo json_encode($res);
    }

    function get_extra_asks($eextra_id=0){
        $this->load->library('user_agent');
        $data['browser']= $this->agent->browser();
        $this->load->model($this->EXTRA_AXES_ASK_MODEL_NAME);
        $data['FORM_ID'] =$eextra_id;
        $data['page_rows'] =$this->{$this->EXTRA_AXES_ASK_MODEL_NAME}->get($eextra_id);
        $this->load->view('evaluation_emps_start_extra_page',$data);
    }

}