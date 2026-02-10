<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 09/09/14
 * Time: 09:41 ص
 */

class Notes extends MY_Controller{

    var $MODEL_NAME= 'notes_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function index(){
        $this->load->view('notes_index');
    }


    function public_get_page($source_Id,$source){

        $data['rows']= $this->{$this->MODEL_NAME}->get_all($source_Id,$source);

        $this->load->view('notes_page',$data);
    }



    function public_create(){
        if($this->p_notes !=''){
            $result= $this->{$this->MODEL_NAME}->create($this->_postedData());
            echo $result;
        }

    }




    function _postedData(){
        $result = array(
            array('name'=>'SOURCE_ID','value'=>$this->input->post('source_id') ,'type'=>'','length'=>-1),
            array('name'=>'SOURCE','value'=>$this->input->post('source') ,'type'=>'','length'=>-1),
            array('name'=>'notes','value'=>$this->input->post('notes') ,'type'=>'','length'=>-1),

        );

        return $result;
    }
}

