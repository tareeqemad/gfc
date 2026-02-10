<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 19/01/2020
 * Time: 08:44 ص
 */
class Revenue_adopt_3 extends MY_Controller
{
    var $MODEL_NAME= 'exp_rev_model_3';
    var $PAGE_URL= 'budget/revenue_adopt_3/get_page';

    var $adopt = 1;

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('budget_chapter_model');
        $this->load->model('budget_section_model');
        $this->load->model('settings/gcc_structure_model');
        $this->load->model('budget_constant_model');
        $this->load->model('settings/gcc_branches_model');
        $this->adopt= intval($this->input->get('case'));
        $this->year= $this->budget_year;
        $this->branch= $this->input->post('branch');
        $this->type=$this->input->get_post('type');
        $this->type= ( $this->type ==null )? 1 : $this->type;
        $this->exp_rev_type= $this->type ;
    }


    function index(){
        $data['title']='اعتماد الايرادات';
        $data['content']='revenue_adopt_index_3';
       /* $data['select_position']= $this->select_position();*/
        $data['select_branch']= $this->select_branch();
        $data['consts']= $this->budget_constant_model->get_all();
        $data['year']= $this->year;
        $data['adopt']= $this->adopt;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->view('template/template',$data);
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
        $type= $this->input->post('type');
        $yyear= $this->year;
        $branch= $this->input->post('branch');
        $data['user_id']= $this->user->id;
       /* echo   $section_no;
        echo   $department_no;
        echo   $yyear;
        echo   $branch;*/

        if($branch!= null and $type!= null){
            $data['adopt']=$this->adopt;
            $data['get_total']= $this->{$this->MODEL_NAME}->get_total($section_no,$yyear,$branch,$this->exp_rev_type, $this->adopt);
            $this->load->view('revenue_adopt_page_3',$data);
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

    function select_branch(){
        $all= $this->gcc_branches_model->get_all();
        $select= "<select name='branch' id='id_branch' class='form-control'>
                    <option value='0' selected='selected'>اختر الفرع</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['NO']}'>{$row['NAME']}</option>";
        }
        $select.= "</select>";
        return $select;
    }

  /*  function select_position(){
        $all= $this->gcc_structure_model->get_level($this->user->position,  $this->adopt, $this->year, $this->exp_rev_type );
        $select= "<select name='user_position' id='txt_user_position' class='form-control'>
                    <option selected value='0' >اختر القسم</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['ST_ID']}'>{$row['ST_NAME']}</option>";
        }
        $select.= "</select>";
        return $select;
    }*/
    function public_select_section(){
        $chapters= $this->budget_chapter_model->get_all_permission_3($this->exp_rev_type,  $this->branch, $this->year);       
        $select= "<select name='section' id='txt_section' class='form-control'>
                    <option value='3' selected='selected'>اختر الفصل</option>";
        foreach ($chapters as $row){

            $select.= "<option value='{$row['NO']}'>{$row['T_SER']}:{$row['NAME']}</option>";
        }
        $select.= "</select>";
        echo $select;
    }
    //T_SER

    function adopt(){
        $yyear = $this->year;
        $branch = $this->input->post('branch');
        $action= $this->input->post('action');
        if($action==1) {
            //ser//
            $adopt_no= $this->input->post('adopt_no');
            $this->adopt= $this->input->post('adopt');
                foreach($adopt_no as $adopt_add){
                 $result= $this->{$this->MODEL_NAME}->adopt($adopt_add,$this->adopt,$yyear, $branch ,$action);
                   // $msg= $this->Is_success($result);
                    if ($result != 1){
                        $this->print_error('لم يتم  اعتماد جميع السجلات');
                    }
                }
            echo modules::run($this->PAGE_URL);
            }

        elseif ($action==0){
            $cancel_adopt= $this->input->post('cancel_adopt');
            $this->adopt= 1;
            foreach($cancel_adopt as $adopt_add){
                $result= $this->{$this->MODEL_NAME}->adopt($adopt_add,$this->adopt,$yyear, $branch ,$action);
                if ($result != 1){
                    $this->print_error('لم يتم الغاء اعتماد السجلات');
                }
            }
            echo modules::run($this->PAGE_URL);

        }
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

    function _postedData($no,$adopt, $yyear, $branch ){
        $result = array(
            array('name'=>'NO','value'=>$no ,'type'=>'','length'=>-1),
            array('name'=>'ADOPT','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>'YYEAR','value'=>$yyear,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$branch,'type'=>'','length'=>-1),
        );
        return $result;
    }
    function trans_adopt(){
        $yyear = $this->year;
        $branch = $this->input->post('branch');
        $section_no= $this->input->post('section');

                $result= $this->{$this->MODEL_NAME}->trans_up( $yyear, $branch,$section_no);
        if ($result != 1){
            $this->print_error('لم يتم الغاء اعتماد السجلات');
        }
     echo $result;
    }
    function cancel_trans_adopt(){
        $yyear = $this->year;
        $branch = $this->input->post('branch');
        $section_no= $this->input->post('section');

        $result= $this->{$this->MODEL_NAME}->cancel_trans_up( $yyear, $branch,$section_no);
        if ($result != 1){
            $this->print_error('لم يتم الغاء اعتماد السجلات');
        }
        echo $result;
    }
    function trans_projects(){
        $yyear = $this->year;
        $branch = $this->input->post('branch');
        $section_no= $this->input->post('section');

        $result= $this->{$this->MODEL_NAME}->trans_projects( $yyear, $branch,$section_no);
        if ($result != 1){
            $this->print_error('لم يتم ترحيل المشاريع للموازنة');
        }
        echo $result;
    }

}
