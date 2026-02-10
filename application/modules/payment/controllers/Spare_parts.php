<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/03/22
 * Time: 08:10 ص
 */

class Spare_parts extends MY_Controller{

    var $MODEL_NAME= 'Spare_parts_model';
    var $PAGE_URL= 'payment/Spare_parts/get_item';


    function  __construct(){
        parent::__construct();
        $this->load->model('Spare_parts_model');
        $this->load->model('settings/constant_details_model');
    }

    function get_all(){

        $data['title']=' قطع الغيار والصيانات';
        $data['content']='spare_parts_index';
        $data['get_final']= $this->{$this->MODEL_NAME}->get_all();
        $data['count_all']= count($data['get_final']);
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $data['class_status'] = $this->constant_details_model->get_list(512);
        $data['class_unit'] = $this->constant_details_model->get_list(513);
        $this->load->view('template/template',$data);

    }

    function get_item(){

        $data['get_final']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('spare_parts_page',$data);

    }

    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function create(){
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function edit(){
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function delete(){
        $id = $this->input->post('id');
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

    function _postedData($typ= null){
        $result = array(
            array('name'=>'CLASS_NO','value'=>$this->input->post('class_no') ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_NAME','value'=>$this->input->post('class_name') ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$this->input->post('class_unit') ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_STATUS','value'=>$this->input->post('class_status') ,'type'=>'','length'=>-1)
        );
        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }

}
