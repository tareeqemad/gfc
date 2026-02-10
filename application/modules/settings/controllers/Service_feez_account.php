<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/10/14
 * Time: 10:58 ص
 */

class Service_feez_account extends MY_Controller{

    var $MODEL_NAME= 'service_feez_account_model';
    var $PAGE_URL= 'settings/service_feez_account/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function index(){
        $data['title']='ارقام حسابات الخدمات';
        $data['content']='service_feez_account_index';
        $this->load->model('gcc_branches_model');
        $data['branch_data']= $this->gcc_branches_model->get($this->user->branch);
        $list= $this->{$this->MODEL_NAME}->get_list($this->user->branch);
        $services= array();



        foreach ($list as $row){
            $services[]= $row['SERVICE_NO'];
        }
        $data['max_service_no']= count($list) > 0? max($services):0;

        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['get_list']= $this->{$this->MODEL_NAME}->get_list($this->user->branch);

        $this->load->view('service_feez_account_page',$data);
    }

    function get_id(){
        $service_no = $this->input->post('service_no');
        $result = $this->{$this->MODEL_NAME}->get($this->user->branch ,$service_no);
        $this->return_json($result);
    }

    function create(){
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData());
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function edit(){
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function delete(){
        $service_no = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if(is_array($service_no)){
            foreach($service_no as $val){
                $msg= $this->{$this->MODEL_NAME}->delete($this->user->branch, $val);
            }
        }else{
            $msg= $this->{$this->MODEL_NAME}->delete($this->user->branch, $service_no);
        }

        if($msg == 1){
            echo  modules::run($this->PAGE_URL);
        }else{
            $this->print_error_msg($msg);
        }
    }

    function _postedData(){
        $account_id= $this->input->post('account_id');
        if($account_id==-1)
            $account_id='';
        $result = array(
            array('name'=>'BRANCH_ID','value'=>$this->user->branch ,'type'=>'','length'=>-1),
            array('name'=>'SERVICE_NO','value'=>$this->input->post('service_no') ,'type'=>'','length'=>-1),
            array('name'=>'SERVICE_NAME','value'=>$this->input->post('service_name') ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$account_id ,'type'=>'','length'=>-1)
        );
        return $result;
    }

}
