<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/10/15
 * Time: 09:06 ص
 */
class Purchase_class extends MY_Controller{

    var $MODEL_NAME= 'purchase_class_model';
    var $PAGE_URL= 'purchases/purchase_class/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->ser= $this->input->post('ser');
        $this->class_name_ar= $this->input->post('class_name_ar');
        $this->calss_description= $this->input->post('calss_description');
        $this->notes= $this->input->post('notes');
        $this->purchase_class_name= $this->input->post('purchase_class_name');
        $this->class_id= $this->input->post('class_id');
        $this->purchase_notes= $this->input->post('purchase_notes');
        $this->adopt= $this->input->post('adopt');
        $this->purchase_price1= $this->input->post('purchase_price1');
        $this->purchase_price2= $this->input->post('purchase_price2');
        $this->purchase_price3= $this->input->post('purchase_price3');
        $this->class_id= $this->input->post('class_id');



    }



function index($page= 1,$ser=-1 ,$class_name_ar=-1,$calss_description=-1,$purchase_class_name=-1,$purchase_notes=-1,$class_id=-1,$adopt=-1 ){



        $data['page']=$page;
        $data['ser']= $ser;
        $data['class_name_ar']= $class_name_ar;
        $data['calss_description']= $calss_description;
        $data['purchase_class_name']= $purchase_class_name;
        $data['purchase_notes']= $purchase_notes;
        $data['class_id']= $class_id;
        $data['adopt']= $adopt;


        $data['help'] = $this->help;
        $data['action'] = 'edit';
    $data['title']='طلبات تعريف الاصناف';
    $data['content']='purchase_class_index';
    $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }
    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function get_page($page= 1,$ser=-1 ,$class_name_ar=-1,$calss_description=-1,$purchase_class_name=-1,$purchase_notes=-1,$class_id=-1,$adopt=-1 ){

        $this->load->library('pagination');

        $ser = $this->check_vars($ser,'ser');
        $class_name_ar = $this->check_vars($class_name_ar,'class_name_ar');
        $calss_description= $this->check_vars($calss_description,'calss_description');
        $purchase_class_name= $this->check_vars($purchase_class_name,'purchase_class_name');
        $purchase_notes= $this->check_vars($purchase_notes,'purchase_notes');
        $class_id= $this->check_vars($class_id,'class_id');
        $adopt = $this->check_vars($adopt,'adopt');


        $where_sql= " where 1=1 ";



        $where_sql.= ($ser!= null )? " and ser ={$ser} " : '';

        $where_sql.= ($class_name_ar!= null)? " and class_name_ar like '".add_percent_sign($class_name_ar)."' " : '';
        $where_sql.= ($calss_description!= null)? " and calss_description like '".add_percent_sign($calss_description)."' " : '';
        $where_sql.= ($purchase_class_name!= null)? " and purchase_class_name like '".add_percent_sign($purchase_class_name)."' " : '';
        $where_sql.= ($purchase_notes!= null)? " and purchase_notes like '".add_percent_sign($purchase_notes)."' " : '';
        $where_sql.= ($class_id!= null)? " and class_id = ".$class_id." " : '';
        $where_sql.= ($adopt!= null)? " and adopt = ".$adopt." " : '';
 //echo $where_sql;


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' purchase_class_tb '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
//print_r($data['page_rows'][0]);
        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('purchase_class_page',$data);
    }


    function create(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->ser);
            }
            else
                echo intval($this->ser);

        }else{

            $result=array();
            $data['purchase_class_data']=$result;
            $data['content']='purchase_class_show';
            $data['title']= 'اضافة صنف جديد';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['help'] = $this->help;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function accounts($id=0){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res=  $this->{$this->MODEL_NAME}->c_edit($this->c_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }
            else
                echo  $this->ser;

        }
    }

    function purchases($id=0){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res=  $this->{$this->MODEL_NAME}->p_edit($this->p_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }
            else
                echo  $this->ser;

        }
    }

    function edit($id=0){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$this->_post_validation();
            $res=  $this->{$this->MODEL_NAME}->edit($this->_postedData('edit'));
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }
            else
                echo  $this->ser;
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        //$this->print_error('لم يتم الاعتماد'.'<br>'.$id);
        if(!(count($result)==1 ))
            die();
        $data['purchase_class_data']=$result[0];

        if($result[0]['ADOPT']==1)
        {
          $data['content']='purchase_class_show';
            $data['action'] ='edit' ;
            $data['title']= 'عرض بيانات الصنف';
        }
        else   if($result[0]['ADOPT']==2)
        {
         $data['content']='purchase_class_p_show';
            $data['action'] ='purchases' ;
            $data['title']= 'توصيف المشتريات للصنف';


        }
        else
        {
         $data['content']='purchase_class_c_show';
            $data['action'] ='accounts' ;
            $data['title']= 'شاشة الحسابات';
        }


            $data['isCreate']= false;



        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTERY_USER'] && $data['action'] == 'edit')?true : false : false;
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }

    function adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,2);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال الرقم المتسلسل";
    }

    function p_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,3);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال الرقم المتسلسل";
    }
    function c_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,4);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال الرقم المتسلسل";
    }

    function _post_validation($isEdit = false){
        if( ($this->ser=='' and $isEdit ) or ($this->class_name_ar=='' or $this->calss_description=='' or $this->notes=='' )  ){
            $this->print_error('يجب ادخال جميع البيانات');
        }

    }

    function _look_ups(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->model('settings/constant_details_model');
        $data['adopt_all'] = $this->constant_details_model->get_list(99);
      }
    function _postedData($typ= null){

       $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_NAME_AR','value'=>$this->class_name_ar ,'type'=>'','length'=>-1),
            array('name'=>'CALSS_DESCRIPTION','value'=>$this->calss_description ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1)


        );
        if($typ=='create')
            unset($result[0]);
        /* else
             unset($result[1]);*/
        return $result;
    }

    function p_postedData(){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'PURCHASE_CLASS_NAME','value'=>$this->purchase_class_name ,'type'=>'','length'=>-1),
            array('name'=>'PURCHASE_NOTES','value'=>$this->purchase_notes ,'type'=>'','length'=>-1),
            array('name'=>'PURCHASE_PRICE1','value'=>$this->purchase_price1 ,'type'=>'','length'=>-1),
            array('name'=>'PURCHASE_PRICE2','value'=>$this->purchase_price2 ,'type'=>'','length'=>-1),
            array('name'=>'PURCHASE_PRICE3','value'=>$this->purchase_price3 ,'type'=>'','length'=>-1)


        );

        return $result;
    }

    function c_postedData(){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$this->class_id ,'type'=>'','length'=>-1)


        );

        return $result;
    }
}