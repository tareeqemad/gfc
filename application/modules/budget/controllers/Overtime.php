<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/13/14
 * Time: 8:56 AM
 */

class Overtime extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('overtime_model');
        $this->load->model('settings/staff_model');
    }

    function index(){

        add_css('select2_metro_rtl.css');

        add_js('select2.min.js');

        $sql = "";

        $data['case'] = $this->input->get('case');

        /* switch($data['case']){
             case 1:
                 $sql = " SB.OVER_TIME_CAN_EDIT = 1 ";
                 break;
             case 2:
                 $sql = " SB.OVER_TIME_DEPT_DIRECT_ADOPT = 1 ";
                 break;

             case 3:
                 $sql = " SB.OVER_TIME_MAIN_DIRECT_ADOPT = 1 ";
                 break;
             case 4:
                 $sql = " SB.OVER_TIME_BRANCH_DIRECT_ADOPT = 1 ";
                 break;

         }*/

        if($data['case'] !=1)
            $sql .= "  EMPLOYEES_tb.no in (select EMP_NO from BUDGET_OVERTIME_TB where adopt = {$data['case']} or adopt = ".($data['case'] -1)."  )";
        else $sql .="  EMPLOYEES_tb.no not in (select EMP_NO from BUDGET_OVERTIME_TB where adopt > {$data['case']} ) ";

        $branch = $this->user->branch;
        $user = $this->user->id;

        if($data['case'] >= 3){
            $branch =null;
            $user =null;

        }

        $data['title']='الوقت الإضافي';
        $data['content']='overtime_index';
        $data['employees']= $this->staff_model->get_emp_st_list($branch,$user,null,$sql);



        $data['help']=$this->help;


        $this->load->view('template/template',$data);
    }

    function get_page($user = 0){

        $user =($this->input->post('user'))?$this->input->post('user'):$user;

        $overtimes= $this->overtime_model->get_list($user,null,$this->year+1);

        $user =($this->input->post('user'))?$this->input->post('user'):$user;
        $data['overtimes'] = count($overtimes) == 12 ?$overtimes : $this->_get_init_month_data();
        $data['overtimes_h'] = $this->overtime_model->get_list_history($user,$this->year );


        $this->load->view('overtime_page',$data);
    }

    function _get_init_month_data(){

        $months = array();

        for($i = 1;$i<=12;$i++){
            $m = str_pad($i, 2, '0', STR_PAD_LEFT);
            $year = $this->year+1;
            array_push($months,array('MONTH'=>"{$year}{$m}",'CALCULATED_HOURS'=>0,'CALCULATED_VAL'=>0));
        }

        return $months;

    }

    /**
     *
     * Update Adopt of overtime depended on case number
     *
     */
    function update_adapt(){

        $USER_ID= $this->input->post('user');
        $CASE= $this->input->post('case');

        $result = array(
            array('name'=>'CASE_IN','value'=>$CASE,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO_IN','value'=>$USER_ID,'type'=>'','length'=>-1),
            array('name'=>'YEAR_IN','value'=>$this->year+1,'type'=>'','length'=>-1)
        );

        echo $this->overtime_model->update_adapt($result);

    }

    /**
     * edit action : insert exists overtime data ..
     * receive post data of overtime
     * depended on overtime prm key
     */
    function create(){

        $USER_ID= $this->input->post('user');
        $MONTH= $this->input->post('months');
        $HOURS= $this->input->post('hours');
        $VALS= $this->input->post('vals');
        $DEPT = $this->input->post('dept');

        $this->_delete_overtime($USER_ID,$MONTH);

        $i = 0;

        if(is_array($MONTH)){
            foreach($MONTH as $val){

                $result = array(
                    array('name'=>'EMP_NO_IN','value'=>$USER_ID,'type'=>'','length'=>-1),
                    array('name'=>'CALCULATED_HOURS_IN','value'=>$HOURS[$i],'type'=>'','length'=>-1),
                    array('name'=>'CALCULATED_VAL_IN','value'=>$VALS[$i],'type'=>'','length'=>-1),
                    array('name'=>'MONTH_IN','value'=>$val,'type'=>'','length'=>-1),
                    array('name'=>'BRANCH_IN','value'=>$this->user->branch,'type'=>'','length'=>-1),
                    array('name'=>'DEPARTMENT_NO_IN','value'=>$DEPT,'type'=>'','length'=>-1)
                );

                $i++;


                $this->overtime_model->create($result);
            }
        }

        echo modules::run('budget/overtime/get_page',$USER_ID);

    }

    /**
     * delete action : delete overtime data ..
     * receive prm key as request
     *
     */
    function delete(){


        $USER_ID= $this->input->post('user');
        $MONTH= $this->input->post('month');

        $this->_delete_overtime($USER_ID,$MONTH);


    }

    /**
     * delete action : delete overtime data ..
     * receive prm key as request
     *
     */
    function _delete_overtime($EMP_NO,$MONTH){

        $this->IsAuthorized();

        if(is_array($MONTH)){
            foreach($MONTH as $val){
                $this->overtime_model->delete($EMP_NO,$val);
            }}
    }
}