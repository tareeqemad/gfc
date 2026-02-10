<?php

/*
 * fmamluk
 * 202106
 */

class ev_audit_objection_mark extends MY_Controller{

    var $MODEL_NAME= 'ev_audit_objection_mark_model';

    function  __construct(){

        parent::__construct();

        $this->load->model($this->MODEL_NAME);
        $this->load->model('Evaluation_emp_order_model');

        $this->ser= $this->input->post('ser');
        $this->evaluation_order_id= $this->input->post('evaluation_order_id');
        $this->order_level= $this->input->post('order_level');
        $this->emp_no= $this->input->post('emp_no');
        $this->emp_name= $this->input->post('emp_name');
        $this->emp_mark = $this->input->post('emp_mark');
        $this->evaluation_order_serial = $this->input->post('ev_order_serial');
        $this->notes = $this->input->post('notes');

    }

    function index(){

        $data['title']=' ادخال درجات الموظفين بعد التعديل ';
        $data['content']='ev_audit_objection_mark_index';

        $get_active = $this->ev_audit_objection_mark_model->get_active();

        if( count($get_active) !=1 ){
            $error_msg= "يجب ان يكون امر تقييم واحد فعال";
        }elseif($get_active[0]['LEVEL_ACTIVE'] != 3  and $get_active[0]['LEVEL_ACTIVE'] != 5  ){
            $error_msg= "لا يوجد امر تقييم في حالة التدقيق او التظلم";
        }else{
            $error_msg= '';
        }

        $data['error_msg']= $error_msg;
        $data['get_active']= $get_active[0];

        $this->load->view('template/template',$data);

    }

    function get_page(){
        //$data['page_rows'] = $this->{$this->MODEL_NAME}->get_page(" and m.evaluation_order_id= {$this->evaluation_order_id} ", 0 , 999999 );
        $data['page_rows']= $this->Evaluation_emp_order_model->get_archives_list(" and m.evaluation_order_id= {$this->evaluation_order_id} ", 0 , 999999 );
        $this->load->view('ev_audit_objection_mark_page',$data);
    }

    function create(){

        $this->_post_validation();
        $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

        if(intval($this->ser) <= 0){
            $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->ser);
        }else{
            echo intval($this->ser);
        }

    }

    function edit(){

        $this->_post_validation();
        $this->ser= $this->{$this->MODEL_NAME}->edit($this->_postedData_edit());

        if(intval($this->ser) <= 0){
            $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->ser);
        }else{
            echo intval($this->ser);
        }
   }


    function _postedData($type= null){

        if ($type=='create'){
            $result = array(
                array('name'=>'EVALUATION_ORDER_ID','value'=>$this->evaluation_order_id,'type'=>'','length'=>-1),
                array('name'=>'ORDER_LEVEL','value'=>$this->order_level,'type'=>'','length'=>-1),
                array('name'=>'EMP_NO','value'=>$this->emp_no,'type'=>'','length'=>-1),
                array('name'=>'EMP_MARK','value'=>$this->emp_mark,'type'=>'','length'=>-1),
                array('name'=>'NOTES','value'=>$this->notes,'type'=>'','length'=>-1),
                array('name'=>'EVALUATION_ORDER_SERIAL','value'=>$this->evaluation_order_serial,'type'=>'','length'=>-1)
            );
        }

        return $result;
    }


    function _postedData_edit(){

        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$this->emp_no,'type'=>'','length'=>-1),
            array('name'=>'EMP_MARK','value'=>$this->emp_mark,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes,'type'=>'','length'=>-1)
        );

        return $result;
    }

        function _post_validation(){
        if( $this->emp_mark=='' ){
            $this->print_error('يجب ادخال الدرجة');
        }elseif($this->emp_mark < 0 or $this->emp_mark > 100){
            $this->print_error('ادخل الدرجة بشكل صحيح');
        }
    }

}

