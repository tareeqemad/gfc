<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/10/14
 * Time: 10:02 ص
 */

class Revenue_adopt extends MY_Controller{

    var $MODEL_NAME= 'exp_rev_model';
    var $PAGE_URL= 'budget/revenue_adopt/get_page';

    var $adopt = 1;

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('budget_chapter_model');
        $this->load->model('budget_section_model');
        $this->load->model('settings/gcc_structure_model');
        $this->load->model('budget_constant_model');

        $this->adopt= intval($this->input->get('case'));
        $this->year= $this->budget_year;

        $this->type=$this->input->get_post('type');
        $this->type= ( $this->type ==null )? 1 : $this->type;
        $this->exp_rev_type= $this->type ;
    }

    function index(){
        $data['title']='اعتماد الايرادات';
        $data['content']='revenue_adopt_index';
        $data['select_position']= $this->select_position();
        $data['consts']= $this->budget_constant_model->get_all();
        $data['year']= $this->year;
        $data['adopt']= $this->adopt;

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        if($this->adopt == 2 or $this->adopt == 3 ){
            if($this->check_position_type($this->adopt)){
                $this->load->view('template/template',$data);
            }else{
                if($this->adopt == 2 )
                    $desc= 'لدائرة';
                else if($this->adopt == 3 )
                    $desc= 'لادارة';
                else
                    $desc= '';
                $data2['title']='اعتماد الايرادات';
                echo "<script type='text/javascript'>alert('لا يمكن الدخول للصفحة، يجب ان تكون تابع $desc ');</script>";
                $this->load->view('template/template',$data2);
            }
        }
    }

    function check_position_type($adopt){
        $row= $this->gcc_structure_model->get($this->user->position);
        if( ($adopt==2 and $row[0]['TYPE']==2) or ($adopt==3 and $row[0]['TYPE']==1) ){
            return true;
        }
        return false;
        //  return true;
    }

    function get_page(){
        $this->adopt= $this->input->post('adopt');
        $section_no= $this->input->post('section');
        $department_no= $this->input->post('user_position');
        $yyear= $this->year;
        $branch= $this->user->branch;
        $data['user_id']= $this->user->id;
        if($section_no!= null and $department_no!= null){
            $data['adopt']=$this->adopt;
            $data['get_total']= $this->{$this->MODEL_NAME}->get_total($section_no,$department_no,$yyear,$branch,$this->exp_rev_type, $this->adopt);
            $this->load->view('revenue_adopt_page',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function get_details(){
        $item_no= $this->input->post('items');
        $adopt= $this->input->post('adopt');
        $adopt_emp_no= $this->input->post('adopt_emp_no');
        if($adopt_emp_no==0)
            $adopt_emp_no= null;
        $mmonth= null;
        $department_no= $this->input->post('user_position');
        $yyear= $this->year;
        $branch= $this->user->branch;
        if($item_no!= null and $department_no!= null){
            $data['get_list']= $this->{$this->MODEL_NAME}->get_list($item_no,$mmonth,$department_no,$yyear,$branch,$this->exp_rev_type,$adopt,$adopt_emp_no);
            $this->load->view('revenue_adopt_details',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function select_position(){
        $all= $this->gcc_structure_model->get_level($this->user->position,  $this->adopt, $this->year, $this->exp_rev_type );
        $select= "<select name='user_position' id='txt_user_position' class='form-control'>
                    <option selected value='0' >اختر القسم</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['ST_ID']}'>{$row['ST_NAME']}</option>";
        }
        $select.= "</select>";
        return $select;
    }

    function select_section(){
        $position= $this->input->post('user_position');
        $select= "<select name='section' id='txt_section' class='form-control'>";
        $select.= "<option value='0' selected='selected'>اختر الفصل</option>";
        $chapters= $this->budget_chapter_model->get_all_permission($this->exp_rev_type, 1, $position, $this->year);
        foreach ($chapters as $chp){
            $select.= "<optgroup label='{$chp['NAME']}'>";
            $sections= $this->budget_section_model->get_list_permission($chp['NO'], 1, $position, $this->year, $this->user->branch);
            foreach ($sections as $sec){
                $select.= "<option value='{$sec['NO']}'>{$sec['NAME']}</option>";
            }
            $select.="</optgroup>";
        }
        $select.="</select> ";
        echo $select;

        echo "
            <script type='text/javascript'>
                $(document).ready(function() {
                    $('#txt_section').select2();
                });
            </script>
            ";
    }

    function adopt(){
        $item_no= $this->input->post('item_no');
        $this->adopt= $this->input->post('adopt');
        $department_no= $this->input->post('user_position');
        $action= $this->input->post('action');
        $yyear= $this->year;
        $branch= $this->user->branch;
        if($action==0)
            $this->adopt--;

        if($item_no!='' and $this->adopt!='' and $department_no!='' and $action!='' and $yyear!='' and $branch!='' ){
            foreach($item_no as $item){
                $result= $this->{$this->MODEL_NAME}->adopt($this->_postedData($item, $department_no, $yyear, $branch, $this->exp_rev_type, $this->adopt, $action));
                $msg= $this->Is_success($result);
            }
            echo modules::run($this->PAGE_URL);
        }else
            echo 'خطأ في ارسال البيانات';
    }

    function attachment_get(){
        $item= $this->input->post('item');
        $user_position= $this->input->post('user_position');
        if($item!='' and $item >0 and $user_position!='' and $user_position >0 ){
            $attachment_identity='rev_'.$this->user->branch.'_'.$user_position.'_'.($this->year).'_'.$item;
            $attachment_category='budget';

            $this->load->model('attachments/attachment_model');
            $data['user_id']= $this->user->id;
            $data['adopt']= 1;
            $data['get_list']= $this->attachment_model->get_list($attachment_identity, $attachment_category);
            $this->load->view('attachments/attachment_page',$data);
        }else
            echo 'يجب اختيار القسم والبند';
    }

    function _postedData($item_no, $department_no, $yyear, $branch, $exp_rev_type, $adopt, $action){
        $result = array(
            array('name'=>'ITEM_NO','value'=>$item_no ,'type'=>'','length'=>-1),
            array('name'=>'DEPARTMENT_NO','value'=>$department_no ,'type'=>'','length'=>-1),
            array('name'=>'YYEAR','value'=>$yyear,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>'EXP_REV_TYPE','value'=>$exp_rev_type,'type'=>'','length'=>-1),
            array('name'=>'ADOPT','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>'ACTION','value'=>$action,'type'=>'','length'=>-1)
        );
        return $result;
    }

}