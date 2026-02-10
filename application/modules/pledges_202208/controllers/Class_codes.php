<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 13/05/15
 * Time: 11:34 ص
 */

class Class_codes extends MY_Controller{

    var $MODEL_NAME= 'class_codes_model';
    var $PAGE_URL= 'pledges/class_codes/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores/stores_model');


        $this->receipt_class_input_id= $this->input->post('receipt_class_input_id');
        $this->class_code_ser= $this->input->post('class_code_ser');
        $this->class_id= $this->input->post('class_id');
        $this->store_id= $this->input->post('store_id');
        $this->code_case= $this->input->post('code_case');

        //database year post
        $this->entry_date= $this->input->post('entry_date');
    }

    function index($input_id=0, $store_id='', $class_id='',$code_case=0){
        $data['title']='اكواد العهد';
        $data['content']='class_codes_index';
        $data['stores'] = $this->stores_model->get_all();

        $data['input_id']=$input_id;
        $data['store_id']=$store_id;
        $data['class_id']=$class_id;
        $data['code_case']=$code_case;
       // $data['entry_date']=$entry_date;
        $data['code_case_all'] = $this->constant_details_model->get_list(80);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['help'] = $this->help;
        $this->load->view('template/template',$data);
    }

    function get_page(){
        // $sql=" and to_char(M.entry_date,'yyyy')=".$this->database_year;
        $sql ="";
         if ($this->entry_date!='' )
        $sql=$sql." and to_char(M.ENTRY_DATE,'yyyy')=".$this->entry_date;
       /* echo $this->entry_date;*/
       /* echo $sql;*/
        // $sql="";
        if ($this->receipt_class_input_id!='' AND $this->receipt_class_input_id!=0)
            $sql=$sql." AND M.RECEIPT_CLASS_INPUT_ID = ".$this->receipt_class_input_id;

        if ($this->class_code_ser!='')
            $sql=$sql." AND '".$this->class_code_ser."' IN  ( TO_CHAR( M.class_code_ser),M.BARCODE )";
           // $sql=$sql." AND M.class_code_ser = ".$this->class_code_ser;

        if ($this->class_id!='')
            $sql=$sql." AND M.class_id = ".$this->class_id;

        if ($this->store_id!='')
            $sql=$sql." AND M.store_id = ".$this->store_id;


             if ($this->code_case!=0)
                 $sql=$sql." AND M.code_case = ".$this->code_case;

        $result= $this->{$this->MODEL_NAME}->get_list($sql);
      //  if(count($result)==0)
        //    die('error');
        $data['get_list']=$result;
        $this->load->view('class_codes_page',$data);
    }

    function public_get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function edit(){
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
      echo $result ;
      // echo ( $this->Is_success($result));
      //  echo  modules::run('pledges/class_codes/get_page');
    }

    function _postedData(){
        $result = array(
            array('name'=>'SER','value'=>$this->input->post('ser') ,'type'=>'','length'=>-1),
            array('name'=>'SERIAL','value'=>$this->input->post('serial') ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function print_case(){
      //  if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->receipt_class_input_id!='' and $this->class_code_ser==''){

            $res = $this->{$this->MODEL_NAME}->print_case($this->receipt_class_input_id, 2, $this->class_id, $this->store_id);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الطباعة'.'<br>'.$res);
            }

            echo 1;
      //  }else
         //   echo "لم يتم ارسال رقم السند";
    }
    function print_case_code(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'  and $this->class_code_ser!=''){
            $res = $this->{$this->MODEL_NAME}->print_case_code($this->class_code_ser);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الطباعة'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }
    function print_case_codes(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'  and $this->class_code_ser!=''){
            $res = $this->{$this->MODEL_NAME}->print_case_codes($this->class_code_ser);
            if(intval($res) <= 0){
              //  $this->print_error('لم يتم الطباعة'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }

}
