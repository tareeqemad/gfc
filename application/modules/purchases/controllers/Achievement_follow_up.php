<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Achievement_follow_up extends MY_Controller{

    var $MODEL_NAME= 'Achievement_follow_up_model';
    var $PAGE_URL= 'purchases/Achievement_follow_up/get_page';

    function  __construct(){
        parent::__construct();

        $this->load->model($this->MODEL_NAME);
    }

    function index(){
        $data['title']='ثوابت متابعة إنجاز المعاملات';
        $data['content']='achievement_follow_up_index';
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $data['count_all']= count($data['get_all']);
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->_look_ups($data);
        echo $this->load->view('achievement_follow_up_page',$data);
    }
    function _look_ups(&$data)
    {
        $this->load->model('settings/constant_details_model');
        $data['help'] = $this->help;
        $data['status'] = $this->constant_details_model->get_list(494);
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');


    }
    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function create(){
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
        if (intval($result) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $result);
        }
        //$this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function edit(){
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        if (intval($result) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $result);
        }
        //$this->Is_success($result);
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
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITY_NAME_IN', 'value' => $this->p_activity_name, 'type' => '', 'length' => -1),
            array('name' => 'STATUS_IN', 'value' => $this->p_status, 'type' => '', 'length' => -1),
            array('name' => 'PROIORTY_IN', 'value' =>$this->p_proiorty , 'type' => '', 'length' => -1),
        );

        if ($typ == 'create')
            unset($result[0]);

        return $result;
    }
}

?>
