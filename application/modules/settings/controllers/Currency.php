<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/08/14
 * Time: 11:31 ص
 */

class currency extends MY_Controller{

    var $MODEL_NAME= 'currency_model';
    var $PAGE_URL= 'settings/currency/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function index(){
        $data['title']='العملات';
        $data['content']='currency_index';
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $data['count_all']= count($data['get_all']);
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('currency_page',$data);
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
            array('name'=>'CURR_ID','value'=>$this->input->post('curr_id') ,'type'=>'','length'=>2),
            array('name'=>'CURR_NAME','value'=>$this->input->post('curr_name') ,'type'=>'','length'=>20),
            array('name'=>'CURR_CODE','value'=>$this->input->post('curr_code') ,'type'=>'','length'=>5)
        );
        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }

    function public_get_currency_date($date = null){

        $date = isset($this->p_date)?$this->p_date : $date;
        $result=  $this->currency_model->get_all_date($date);

        $this->return_json($result);
    }
}

