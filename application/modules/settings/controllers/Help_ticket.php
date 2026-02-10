<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 09/09/14
 * Time: 09:41 ص
 */

class Help_ticket extends MY_Controller{

    var $MODEL_NAME= 'help_ticket_model';
    var $PAGE_URL= 'settings/help_ticket/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function index(){
        $data['title']='الدليل الارشادي';
        $data['content']='HELP_TICKET_index';
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $data['count_all']= count($data['get_all']);
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_js('ckeditor/ckeditor.js');
        $this->load->view('template/template',$data);
    }


    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('help_ticket_page',$data);
    }
    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function public_get(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get_list($id);
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
            array('name'=>'ID','value'=>$this->input->post('id') ,'type'=>'','length'=>10),
            array('name'=>'TITLE','value'=>$this->input->post('title') ,'type'=>'','length'=>250),
            array('name'=>'FORM_ID','value'=>$this->input->post('form_id') ,'type'=>'','length'=>100),
            array('name'=>'HELP_TEXT','value'=>$this->input->post('help_text') ,'type'=>'','length'=>-1)

        );
        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }
}

