<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/10/16
 * Time: 11:30 ص
 */

class Eval_etest_order extends MY_Controller {
    var $MODEL_NAME= 'eval_etest_order_model';
    var $DET_MODEL_NAME= 'eval_etest_order_det_model';
    var $FORM_MODEL_NAME= 'evaluation_form_model';
    var $AXES_MODEL_NAME= 'evaluation_form_axes_model';
    var $ASK_MODEL_NAME= 'evaluation_form_axes_ask_model';
    var $AXES_MARKS_MODEL_NAME= 'evaluation_form_axes_marks_model';
    var $EXTRA_AXES_MODEL_NAME= 'evaluation_extra_axes_model';
    var $EXTRA_AXES_ASK_MODEL_NAME= 'evaluation_extra_axes_ask_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DET_MODEL_NAME);
        $this->load->model($this->FORM_MODEL_NAME);

        $this->user_emp_no = $this->user->emp_no;
        $this->year = $this->year;

        // master vars
        $this->etest_order_serial= $this->input->post('etest_order_serial');

        // detail vars
        $this->ser= $this->input->post('ser');
        $this->eval_form_id= $this->input->post('eval_form_id');
        $this->eval_form_type= $this->input->post('eval_form_type');
        $this->efa_id= $this->input->post('efa_id');
        $this->element_id= $this->input->post('element_id');
        $this->mark= $this->input->post('mark');
        $this->achievement= $this->input->post('achievement');
        $this->hint= $this->input->post('hint');
    }

    function index(){
        // check if eval exists by emp no and year, ret eval id else ret zero..
        $res= $this->{$this->MODEL_NAME}->get_ser($this->user_emp_no,$this->year);
        $eval_id= $res[0]['ETEST_ORDER_SERIAL'];
        if($eval_id==0){
            //show new form
            echo modules::run('hr/eval_etest_order/show_form_asks');
        }else{
            //show curr eval
            echo modules::run('hr/eval_etest_order/get',$eval_id,'edit');
        }
    }

    function get_form_by_emp(){
        $res= $this->{$this->FORM_MODEL_NAME}->get_form_by_emp($this->user_emp_no,$this->user_emp_no,1);
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

    function show_form_asks(){
        $emp_no= $this->user_emp_no;
        $type= 1;
        $this->load->model($this->AXES_MODEL_NAME);
        $this->load->model('employees/employees_model');
        $emp_data= $this->employees_model->get($emp_no);
        // رقم النموذج
        $form_id= $this->get_form_by_emp($emp_no, $type);

        if($type==1){
            // رقم المحور الاشرافي ان وجد
            $data['eextra_id'] = $this->get_extra_by_emp($emp_no, 1);
            $form_data= $this->{$this->FORM_MODEL_NAME}->get($form_id);
            if(count($form_data)==1)
                $form_name= $form_data[0]['EVALUATION_FORM_NAME'];
            else
                $form_name='';

            if(intval($form_id) <= 0)
                $data['axes']= array();
            else
                $data['axes']= $this->{$this->AXES_MODEL_NAME}->get_list($form_id);
        }

        $data['FORM_ID']= $form_id;
        $data['FORM_TYPE']= $type;
        $data['title']='التقييم التجريبي للموظف '.' / '.$emp_data[0]['NAME'].' - '.$form_name.' للعام '.$this->year;
        $data['content']='eval_etest_order_create';
        $data['help']=$this->help;
        $this->load->view('template/template',$data);
    }

    function get_asks($efa_id=0, $form_id){
        $this->load->library('user_agent');
        $data['browser']= $this->agent->browser();
        $this->load->model($this->ASK_MODEL_NAME);
        $data['FORM_ID'] =$form_id;
        $data['page_rows'] =$this->{$this->ASK_MODEL_NAME}->get_list($efa_id);
        $this->load->view('eval_etest_order_page',$data);
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
        $this->load->view('eval_etest_order_extra_page',$data);
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->etest_order_serial= $this->{$this->MODEL_NAME}->create($this->_postedData());
            if(intval($this->etest_order_serial) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->etest_order_serial);
            }else{
                for($i=0; $i<count($this->element_id); $i++){
                    if($this->eval_form_id[$i]!=null and $this->eval_form_type[$i]!=null and ($this->efa_id[$i]!=null or $this->eval_form_type[$i]==2) and $this->element_id[$i]!=null and $this->mark[$i]!=null){
                        $evaluation_form_id= $this->{$this->DET_MODEL_NAME}->create($this->_postedData_details($this->eval_form_id[$i],$this->efa_id[$i],$this->element_id[$i],$this->mark[$i],$this->achievement[$i],$this->hint[$i]));
                        if(intval($evaluation_form_id) <= 0){
                            $this->print_error_del($evaluation_form_id);
                        }
                    }
                }
            }
            echo intval($this->etest_order_serial);
        }
    }

    function edit(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            for($i=0; $i<count($this->ser); $i++){
                if($this->ser[$i]!=null and $this->element_id[$i]!=null and $this->mark[$i]!=null){
                    $res= $this->{$this->DET_MODEL_NAME}->edit($this->_postedData_details_edit($this->ser[$i],$this->element_id[$i],$this->mark[$i],$this->achievement[$i],$this->hint[$i]));
                    if(intval($res) <= 0){
                        $this->print_error($res);
                    }
                }
            }
            echo 1;
        }
    }

    function get($id,$action=null){
        // الموظف نفسه او مديره المباشر
        $data['result'] = $this->{$this->MODEL_NAME}->get($id);
        $emp_no = $data['result'][0]['EMP_ID'];

        if($action=='edit' and $emp_no==$this->user_emp_no){
            // do nothing
        }elseif($action=='manager' and HaveAccess(base_url('hr/evaluation_emps_start/index'))){
            // التاكد انه المستخدم هو المدير المباشر لصاحب التقييم - مش مهم كتير
        }else{
            die('else');
        }

        $data['action']=$action;
        $data['result_details'] = $this->{$this->DET_MODEL_NAME}->get_list($id);

        $data['title']='التقييم التجريبي للموظف';
        $data['help']=$this->help;
        $data['browser']= $this->agent->browser();
        $data['content']='eval_etest_order_show';
        $this->load->view('template/template',$data);
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->etest_order_serial);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ التقييم: '.$msg);
        else
            $this->print_error('لم يتم حذف التقييم: '.$msg);
    }

    function _postedData(){
        $result = array(
            array('name'=>'EMP_NO','value'=>$this->user_emp_no ,'type'=>'','length'=>-1),
            array('name'=>'THE_YEAR','value'=>$this->year ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function _postedData_details($eval_form_id,$efa_id,$element_id,$mark,$achievement,$hint){
        $result = array(
            array('name'=>'ETEST_ORDER_SERIAL','value'=>$this->etest_order_serial ,'type'=>'','length'=>-1),
            array('name'=>'EVALUATION_FORM_ID','value'=>$eval_form_id ,'type'=>'','length'=>-1),
            array('name'=>'EAXES_ID','value'=>$efa_id ,'type'=>'','length'=>-1),
            array('name'=>'ELEMENT_ID','value'=>$element_id ,'type'=>'','length'=>-1),
            array('name'=>'MARK','value'=>$mark ,'type'=>'','length'=>-1),
            array('name'=>'ACHIEVEMENT','value'=>$achievement ,'type'=>'','length'=>-1),
            array('name'=>'HINT','value'=>$hint ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function _postedData_details_edit($ser,$element_id,$mark,$achievement,$hint){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'ELEMENT_ID','value'=>$element_id ,'type'=>'','length'=>-1),
            array('name'=>'MARK','value'=>$mark ,'type'=>'','length'=>-1),
            array('name'=>'ACHIEVEMENT','value'=>$achievement ,'type'=>'','length'=>-1),
            array('name'=>'HINT','value'=>$hint ,'type'=>'','length'=>-1)
        );
        return $result;
    }

}