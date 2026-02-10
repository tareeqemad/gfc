<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/02/23
 * Time: 10:20 ص
 */

class Call_allowance extends MY_Controller{

    var $MODEL_NAME= 'Call_allowance_model';
    var $PAGE_URL= 'salary/Call_allowance/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function index(){
        $data['title']='فئات بدل الاتصال';
        $data['content']='call_allowance_index';
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $data['count_all']= count($data['get_all']);
        $this->load->view('template/template1',$data);
    }

    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('call_allowance_page',$data);
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
            array('name'=>'TB_NO','value'=>$this->input->post('tb_no') ,'type'=>'','length'=>5),
            array('name'=>'TB_NAME','value'=>$this->input->post('tb_name') ,'type'=>'','length'=>-1),
            array('name'=>'TB_AMOUNT','value'=>$this->input->post('tb_amount') ,'type'=>'','length'=>-1)
        );

        return $result;
    }

}

?>
