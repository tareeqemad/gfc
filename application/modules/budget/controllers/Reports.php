<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 22/10/14
 * Time: 08:37 ص
 */

class Reports extends MY_Controller{

    function  __construct(){
        parent::__construct();

        $this->load->model('settings/gcc_branches_model');

        $this->load->model('settings/gcc_structure_model');
        $this->load->model('budget_constant_model');
        $this->load->model('budget/budget_chapter_model');
        $this->load->model('budget/exp_rev_model');
        $this->load->model('budget/budget_model');
        $this->load->model('budget_chapter_model');
        $this->load->model('budget_section_model');
        $this->load->model('budget_items_model');

        $this->year= $this->budget_year;

    }

    function index(){
        add_css('combotree.css');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['chapters'] = $this->budget_chapter_model->get_all();
        $data['sections'] = $this->budget_section_model->get_all();
        $data['consts']= $this->budget_constant_model->get_all();
        $data['title']='تقارير الموازنة';
        $data['content']='reports_index';

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->view('template/template',$data);

    }

    function public_get_gcc(){
        $result = json_encode($this->gcc_structure_model->get_type(null,$this->budget_year));
        $result=str_replace('subs','children',$result);
        $result=str_replace('ST_ID','id',$result);
        $result=str_replace('ST_NAME','text',$result);
        echo $result;
    }

 /*   function get_page($section=-1,$branch=-1){
        if(  $this->section!='' ){
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
*/
    function budget_class_index(){
   $data['branches'] = $this->gcc_branches_model->get_all();
       $data['chapters'] = $this->budget_chapter_model->get_all();
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('ajax_upload_file.js');
$data['year']=$this->year;
        $data['title']='إجماليات جميع البنود لعام '.$this->year;
        $data['content']='budget_class_index';

        $this->load->view('template/template',$data);

    }
function class_get_page(){
    $this->chapter= $this->input->post('chapter');
    $this->section= $this->input->post('section');
    $this->branch= $this->input->post('branch');
    $this->user_position= $this->input->post('user_position');
   // echo( $this->user_position."ffffffff");

    $wsql_sec="";
    $wsql_items="";
    $wsql="";
    if ($this->chapter>0){
        $wsql_sec=$wsql_sec." and chapter_no=".$this->chapter;
        $wsql_items=$wsql_items." and chapter_no=".$this->chapter;
        $wsql=$wsql." and section_no in (select no from budget_section_tb where chapter_no=".$this->chapter.")";
    }
    if ($this->section>0){
        $wsql_sec=$wsql_sec." and no = ".$this->section;
        $wsql_items=$wsql_items."and section_no=".$this->section;
        $wsql=$wsql." and section_no=".$this->section;

    }
    if ($this->user_position>0){
         $wsql=$wsql." and department_no  like '".$this->user_position."%'";
    }
    if ($this->branch>0) $wsql=$wsql." and branch=".$this->branch;
  $data['col']=$this->budget_section_model-> budget_sections_get_list($wsql_sec);
    $data['rows']=$this->budget_items_model-> budget_itemss_tb_get_list($wsql_items);

    $data['exp_rev']=$this->budget_model-> budget_exp_rev_sec_items($this->year,$wsql);
 //   print_r(  $data['exp_rev']);
//echo $wsql;

    $data['sum_ccount']=array();
    $data['sum_price']=array();
    foreach($data['exp_rev'] as $row) :
        $data['sum_ccount'][$row['SECTION_NO']][$row['ITEM_NO']]=$row['SUM_CCOUNT'];
        $data['sum_price'][$row['SECTION_NO']][$row['ITEM_NO']]=$row['SUM_PRICE'];
    endforeach;
     $this->load->view('budget_class_detail',$data);
}


}