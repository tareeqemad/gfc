<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 28/08/16
 * Time: 11:31 ص
 */
class budget_financial_ceil extends MY_Controller{

    var $MODEL_NAME= 'budget_financial_ceil_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/gcc_branches_model');
    //    $this->load->model('settings/gcc_structure_model');
        $this->load->model('budget_section_model');
        $this->load->model('budget_chapter_model');
        $this->load->model('budget_constant_model');
        $this->year= $this->budget_year;
        $this->type=$this->input->get_post('type');
       $this->type= ( $this->type ==null )? 1 : $this->type;
        $this->branch=$this->input->post('branch');
        $this->section=$this->input->post('section');

     //  echo  $this->type."fff";
        $this->adopt=$this->input->post('adopt');

    }

    function index(){
        $data['title']='السقف المالي لموازنة';
        $data['content']='budget_financial_ceil_index';
        $data['select_branch']= $this->select_branch();
        $data['select_section']= $this->public_select_section(1);
       $data['consts']= $this->budget_constant_model->get_all();
        $data['year']= $this->year;
            add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->view('template/template',$data);
    }
    function get_page($section=-1,$branch=-1){
        if(  $this->section!='' ){
         //   $total= $this->{$this->MODEL_NAME}->get_up_new($this->year, $this->section, $this->branch, $this->department_no);
          //  if(count($total)!=1)
          //      die();
         //   $data['total']= $total[0];
            $where_sql= " where 1=1 ";
            $section= $this->check_vars($section,'section');
            $branch= $this->check_vars($branch,'branch');

            $where_sql.= ($section!= null)? " and vv.section_no= {$section} " : '';
           $where_sql.= ($branch!= null)? " and vv.branch= {$branch} " : '';
//echo  $where_sql."fff";
            $data['get_list']= $this->{$this->MODEL_NAME}->get_list2( $this->year, $where_sql);
           // print_r(  $data['get_list']);
            $this->load->view('budget_financial_ceil_page',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= $this->{$c_var}? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
    function public_select_section($typ=0){

        $select= "<select name='section' id='txt_section' class='form-control'>";
        $select.= "<option value='0' selected='selected'>اختر الفصل</option>";
        $chapters= $this->budget_chapter_model->get_all_permission($this->type);

        foreach ($chapters as $chp){
            $select.= "<optgroup label='{$chp['NAME']}'>";
            $sections= $this->budget_section_model->get_list_permission($chp['NO']);
           // print_r($sections);
            foreach ($sections as $sec){
                $select.= "<option value='{$sec['NO']}'>{$sec['T_SER']}:{$sec['NAME']}</option>";
            }
            $select.="</optgroup>";
        }
        $select.="</select> ";

        if($typ==1)
            return $select;
        else
            echo $select;
    }
    function select_branch(){
        $all= $this->gcc_branches_model->get_all();
        $select= "<select name='branch' id='txt_branch' class='form-control'>
                    <option value='0' selected='selected'>جميع المقرات</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['NO']}'>{$row['NAME']}</option>";
        }
        $select.= "</select>";
        return $select;
    }

   /* function _post_validation(){
            for($i=0; $i<count($this->p_ser); $i++){

                if($this->p_ceil_value[$i]=='')
                    $this->print_error('أدخل المبلغ');

            }
     }*/

    function adopt1(){
      // $this->print_error(isset($this->p_adopt[0])? 2 :1);
        //exit;

        for($i=0; $i<count($this->p_ser); $i++){

            $adoptt=isset($this->adopt[$i])? 2 :1 ;
           // $this->print_error($adoptt." ".$i);
            if(($this->p_ser[$i]!= 0) and ($this->p_old_adopt[$i]== 2) and ($adoptt != 2)){
                $res = $this->{$this->MODEL_NAME}-> adopt($this->p_ser[$i],1);
                if(intval($res) <= 0){
                    $this->print_error($res.$i);
                }
            }
        }
        echo 1;
    }

    function adopt2(){

    for($i=0; $i<count($this->p_ser); $i++){
        $adoptt=(isset($this->adopt[$i]))? 2 :1 ;

        if(($this->p_ser[$i]!= 0) and ($this->p_old_adopt[$i]== 1) and ($adoptt== 2)){
            $res = $this->{$this->MODEL_NAME}-> adopt($this->p_ser[$i],2);
            if(intval($res) <= 0){
                $this->print_error($res.$i);
            }
        }
    }
    echo 1;
}
    function edit(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
          for($i=0; $i<count($this->p_ser); $i++){

              if (  $this->p_ceil_value[$i] !='' and  intval($this->p_ceil_value[$i]) >=0){

               if($this->p_ser[$i]== 0){
                   $detail_seq=   $this->{$this->MODEL_NAME}->create($this->_postDetailsDataInsert($this->p_branch[$i],$this->p_section[$i],$this->p_ceil_value[$i]));
                   if(intval($detail_seq) <= 0)
                       $this->print_error($detail_seq. $this->p_ceil_value[$i]);

               } else if(($this->p_ser[$i]!= 0) and ($this->p_old_adopt[$i]== 1) and ($this->p_old_ceil_value[$i] != $this->p_ceil_value[$i])){
                    $detail_seq=   $this->{$this->MODEL_NAME}->edit($this->_postDetailsDataEdit($this->p_ser[$i],$this->p_ceil_value[$i]));
                    if(intval($detail_seq) <= 0)
                    $this->print_error($detail_seq. $this->p_ceil_value[$i]);
                    }
              }/* else
             { $this->print_error('أدخل المبلغ'.$this->p_ceil_value[$i]);}*/
              }
            echo 1;
        }

        }

  function  _postDetailsDataInsert($branch,$section_no,$ceil_value){

    $result = array(
        array('name'=>'THE_YEAR','value'=>$this->year ,'type'=>'','length'=>-1),
        array('name'=>'BRANCH','value'=>$branch,'type'=>'','length'=>-1),
        array('name'=>'SECTION_NO','value'=>$section_no,'type'=>'','length'=>-1),
        array('name'=>'CEIL_VALUE','value'=>$ceil_value,'type'=>'','length'=>-1)
    );
    return $result;

}
    function  _postDetailsDataEdit($ser,$ceil_value){

        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'CEIL_VALUE','value'=>$ceil_value,'type'=>'','length'=>-1)
        );
        return $result;

    }

}