<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/10/17
 * Time: 01:29 م
 */

class planning extends MY_Controller{

    var $MODEL_NAME= 'plan_model';
    var $PAGE_URL= 'planning/planning/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');

    }

    function index(){


        $data['title']='تخطيط الأنشطة';
        $data['help']=$this->help;
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['content']='planning_index';
        $data['all_year']= $this->{$this->MODEL_NAME}->GET_YEAR();
        $data['all_project']= $this->{$this->MODEL_NAME}->get_project();
        $data['activity_type'] = $this->constant_details_model->get_list(193);
        $this->load->view('template/template',$data);
    }
    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('planning_page',$data);
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
    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
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
if ($this->input->post('type')==1)
    $project_id='';
        else if ($this->input->post('type')==2)
            $project_id=$this->input->post('project_id');
        $result = array(
            array('name'=>'ACTIVITY_NO','value'=>$this->input->post('activity_no') ,'type'=>'','length'=>-1),
            array('name'=>'OBJECTIVE','value'=>$this->input->post('objective') ,'type'=>'','length'=>-1),
            array('name'=>'GOAL','value'=>$this->input->post('goal') ,'type'=>'','length'=>-1),
            array('name'=>'ACTIVITY_NAME','value'=>$this->input->post('activity_name') ,'type'=>'','length'=>-1),
            array('name'=>'DETAILES','value'=>$this->input->post('detailes') ,'type'=>'','length'=>-1),
            array('name'=>'TYPE','value'=>$this->input->post('type') ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_ID','value'=>$project_id ,'type'=>'','length'=>-1),




        );


        // return $result;



        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }

}

?>
