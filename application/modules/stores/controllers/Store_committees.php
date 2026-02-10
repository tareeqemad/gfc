<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 21/12/14
 * Time: 01:30 م
 */

class store_committees extends MY_Controller{

    var $MODEL_NAME= 'store_committees_model';
    var $PAGE_URL= 'stores/store_committees/get_page';
var $type;
    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->type= $this->input->get_post('type');
    }

    function index(){


        $data['type']= $this->type;
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all_by_type($this->type);
        $c_type=  $this->constant_details_model->get(65 ,$this->type);
        $data['type_name']= $c_type[0]['CON_NAME'];
        $data['title']=$data['type_name'];
        $data['count_all']= count($data['get_all']);
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $data['content']='store_committees_index';
        $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['type']= $this->type;
       $data['get_all']= $this->{$this->MODEL_NAME}->get_all_by_type($this->type);
        $this->load->view('store_committees_page',$data);
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
            array('name'=>'COMMITTEES_ID','value'=>$this->input->post('committees_id') ,'type'=>'','length'=>4),
            array('name'=>'COMMITTEES_NAME','value'=>$this->input->post('committees_name') ,'type'=>'','length'=>50),
            array('name'=>'COMITTESS_TYPE','value'=>$this->input->post('comittess_type') ,'type'=>'','length'=>1)
        );
        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }
}

?>
