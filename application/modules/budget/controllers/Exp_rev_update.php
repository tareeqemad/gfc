<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/11/14
 * Time: 02:01 م
 */

class Exp_rev_update extends MY_Controller{

    var $MODEL_NAME= 'exp_rev_update_model';
    var $PAGE_URL= 'budget/exp_rev_update/get_page';
    var $exp_rev_type= 0; // 2 exp - 1 rev
    var $adopt= 0;
    var $branch= 0;
    var $department='';
    var $adopt_array= array(4,5,6);

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/gcc_branches_model');
        $this->exp_rev_type= intval($this->input->post('type')); // 2 exp - 1 rev
        $this->adopt= intval($this->input->get_post('case')); // 4-5-6
        $this->year= $this->budget_year;
        $this->branch= intval($this->input->post('branch'));
        $this->department= $this->input->post('department');
    }

    function index(){
        $data['title']='اعتماد الموازنة';
        $data['content']='exp_rev_update_index';
        $data['select_branch']= $this->select_branch();
        $data['year']= $this->year;
        $data['adopt']= encryption_case($this->adopt,1);
        if( in_array($this->adopt,$this->adopt_array) ){
            $this->load->view('template/template',$data);
        }
    }

    function select_branch(){
        $all= $this->gcc_branches_model->get_all();
        $select= "<select name='branch' id='txt_branch' class='form-control'>
                    <option value='0' selected='selected'>اختر الفرع</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['NO']}'>{$row['NAME']}</option>";
        }
        $select.= "</select>";
        return $select;
    }

    function select_department(){ // الادارات الخاصة بالمقر الرئيسي
        $this->load->model('settings/gcc_structure_model');
        $all= $this->gcc_structure_model->get_type(1, $this->year ,1, $this->exp_rev_type);
        $select= "<select name='department' id='txt_department' class='form-control'>
                    <option value='0' selected='selected'>اختر الادارة</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['ST_ID']}'>{$row['ST_NAME']}</option>";
        }
        $select.= "</select>";
        echo $select;
    }

    function select_chapter(){ // الابواب حسب الشروط
        $all= $this->{$this->MODEL_NAME}->get_chapter($this->year, $this->branch, $this->department, $this->exp_rev_type);
        $select= "<select name='chapter' id='txt_chapter' class='form-control'>
                    <option value='0' selected='selected'>اختر الباب</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['NO']}'>{$row['NAME']}</option>";
        }
        $select.= "</select>";
        echo $select;
    }

    function get_page(){
        $chapter= $this->input->post('chapter');
        $data['user_id']= $this->user->id;
        $data['adopt']= encryption_case($this->adopt);
        if($this->exp_rev_type!= 0 and $this->branch!= 0 and ($this->branch!= 1 or ($this->branch== 1 and $this->department!='')) and in_array($data['adopt'],$this->adopt_array) and $chapter!='' and $chapter> 0 ){
            $data['get_list']= $this->{$this->MODEL_NAME}->get_list($this->year, $this->branch, $this->department , $chapter, $this->exp_rev_type, null);
            $this->load->view('exp_rev_update_page',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function get_details(){
        $section= $this->input->post('section');
        if($this->exp_rev_type!= 0 and $this->branch!= 0 and $section!='' ){
            $this->load->model('exp_rev_model');
            $data['dpt_total']= $this->exp_rev_model->get_dpt_total($this->year, $this->branch, $this->exp_rev_type, $section, $this->department);
            $this->load->view('exp_rev_update_details',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function adopt(){
        $no= $this->input->post('no');
        $total_update= $this->input->post('total_update');
        $note= $this->input->post('note');
        $adopt= encryption_case($this->adopt);
        $action= $this->input->post('action');

        if($action==0)
            $adopt--;

        if($no!='' and $adopt!='' and $action!='' ){
            $result= $this->{$this->MODEL_NAME}->edit($this->_postedData($no, $total_update, $note, $adopt, $action));
            $this->Is_success($result);
            echo modules::run($this->PAGE_URL);
        }else
            echo 'خطأ في ارسال البيانات';
    }

    function delete(){
        $id = $this->input->post('no');
        $this->IsAuthorized();
        $msg = 0;
        if(is_array($id)){
            foreach($id as $val){
                $msg= $this->{$this->MODEL_NAME}->delete($val);
            }
        }else{
            $msg= $this->{$this->MODEL_NAME}->delete($id);
        }

        if($msg == 1){
            echo  modules::run($this->PAGE_URL);
        }else{
            $this->print_error_msg($msg);
        }
    }

    function _postedData($no, $total_update, $note, $adopt, $action){
        $result = array(
            array('name'=>'NO','value'=>$no ,'type'=>'','length'=>-1),
            array('name'=>'TOTAL_UPDATE','value'=>$total_update ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$note,'type'=>'','length'=>-1),
            array('name'=>'ADOPT','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>'ACTION','value'=>$action,'type'=>'','length'=>-1)
        );
        return $result;
    }

}
