<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 26/09/18
 * Time: 08:11 ص
 */

class advertising extends MY_Controller{

    var $MODEL_NAME= 'advertising_model';
    var $PAGE_URL= 'purchases/advetising/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->load->model('settings/constant_details_model');

    }

    function index(){


        $data['title']=' إدارة الإعلانات';
        $data['help']=$this->help;
        $data['content']='advertising_index';
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $data['count_all']= count($data['get_all']);
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['adver_type'] = $this->constant_details_model->get_list(236);
       $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('advertising_page',$data);
    }

    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);//$result
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
            array('name'=>'ADVER_NO','value'=>$this->input->post('adver_no') ,'type'=>'','length'=>-1),
            array('name'=>'TITLE','value'=>$this->input->post('title') ,'type'=>'','length'=>-1),
            array('name'=>'BODY','value'=>$this->input->post('body') ,'type'=>'','length'=>-1),
            array('name'=>'ADVER_TYPE','value'=>$this->input->post('adver_type') ,'type'=>'','length'=>-1)
        );

        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }
}

?>
