<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/11/15
 * Time: 12:57 م
 */

class Jobs_hours_cost extends MY_Controller{

    var $MODEL_NAME= 'jobs_hours_cost_model';
    var $PAGE_URL= 'technical/jobs_hours_cost/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->jobs_cost_type= $this->input->post('jobs_cost_type');
        // array
        $this->worker_job_id= $this->input->post('worker_job_id');
        $this->worker_job_hour_cost= $this->input->post('worker_job_hour_cost');
        $this->note= $this->input->post('note');
        $this->is_exists= $this->input->post('is_exists');
    }

    function index($jobs_cost_type=0){
        $this->jobs_cost_type= $jobs_cost_type;
        $data['jobs_cost_type']= $jobs_cost_type;
        if($jobs_cost_type==1)
            $data['title']='تكلفة ساعة العمل للوظائف الفنية';
        elseif($jobs_cost_type==2)
            $data['title']='تكلفة ساعة العمل للاليات';
        else
            die("jobs_cost_type:$jobs_cost_type");
        $data['content']= 'jobs_hours_cost_index';
        $data['help'] = $this->help;
        $this->load->view('template/template',$data);
    }

    function get_page($jobs_cost_type=0){
        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($jobs_cost_type);
        $this->load->view('jobs_hours_cost_page',$data);
    }

    function save(){
        for($i=0; $i<count($this->worker_job_id); $i++){
            if($this->is_exists[$i]){
                $res= $this->{$this->MODEL_NAME}->edit($this->_postedData($this->worker_job_id[$i],$this->worker_job_hour_cost[$i],$this->note[$i]));
            }else{
                $res= $this->{$this->MODEL_NAME}->create($this->_postedData($this->worker_job_id[$i],$this->worker_job_hour_cost[$i],$this->note[$i]));
            }
            if($res!=1) $this->print_error('لم يتم الحفظ بشكل صحيح '.$i+1);
        }
        echo  modules::run($this->PAGE_URL,$this->jobs_cost_type);
    }

    function _postedData($worker_job_id,$worker_job_hour_cost,$note){
        $result = array(
            array('name'=>'JOBS_COST_TYPE_IN','value'=>$this->jobs_cost_type ,'type'=>'','length'=>-1),
            array('name'=>'WORKER_JOB_ID_IN','value'=>$worker_job_id ,'type'=>'','length'=>-1),
            array('name'=>'WORKER_JOB_HOUR_COST_IN','value'=>$worker_job_hour_cost ,'type'=>'','length'=>-1),
            array('name'=>'NOTE_IN','value'=>$note ,'type'=>'','length'=>-1),
        );
        return $result;
    }


}