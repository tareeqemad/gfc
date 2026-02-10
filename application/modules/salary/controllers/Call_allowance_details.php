<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/02/23
 * Time: 11:00 ص
 */

class Call_allowance_details extends MY_Controller{

    var $MODEL_NAME= 'Call_allowance_details_model';
    var $PAGE_URL= 'salary/Call_allowance_details/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function get_page(){
        $data['tb_no']= $this->input->post('tb_no');
        $data['get_list']= $this->{$this->MODEL_NAME}->get_list($data['tb_no']);
        $this->_look_ups($data);
        $this->load->view('call_allowance_details_page',$data);
    }

    function public_get_id(){
        $tb_no= $this->input->post('tb_no');
        $id= $this->input->post('id');
        $result= $this->{$this->MODEL_NAME}->get($tb_no,$id);
        $result = json_encode($result);

        echo $result;
    }

    function public_get_id_com(){
        $tb_no= $this->input->post('tb_no');
        $id= $this->input->post('id');
        $result= $this->{$this->MODEL_NAME}->get($tb_no,$id);
        $result = json_encode($result);

        $result=str_replace('subs','children',$result);
        $result=str_replace('ACCOUNT_ID','id',$result);
        $result=str_replace('ACOUNT_NAME','text',$result);

        echo $result;
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
        $id= $this->input->post('id');
        $TB_NO= $this->input->post('tb_no');
        $this->IsAuthorized();
        $msg = 0;
        if(is_array($id)){
            foreach($id as $val){
                $msg= $this->{$this->MODEL_NAME}->delete($TB_NO,$val);
            }
        }else{
            $msg= $this->{$this->MODEL_NAME}->delete($TB_NO,$id);
        }

        if($msg == 1){
            echo  modules::run($this->PAGE_URL);
        }else{
            $this->print_error_msg($msg);
        }
    }

    function _look_ups(&$data)
    {
        $this->load->model('constants_sal_model');
        //$data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['w_no_admin_cons'] = $this->{$this->MODEL_NAME}->get_two_const(7,14);
    }

    function _postedData($typ=null){
        if($typ==null and $this->input->post('con_no')!= $this->input->post('new_con_no')){
            die("لا يمكن تعديل رقم الفئة..");
        }
        $result = array(
            array('name'=>'TB_NO','value'=>$this->input->post('tb_no') ,'type'=>'','length'=>4),
            array('name'=>'CON_NO','value'=>$this->input->post('con_no') ,'type'=>'','length'=>4),
            array('name'=>'NEW_CON_NO','value'=>$this->input->post('new_con_no') ,'type'=>'','length'=>4),
            array('name'=>'CON_NAME','value'=>$this->input->post('con_name') ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$this->input->post('account_id') ,'type'=>'','length'=>100)
        );
        if($typ=='create')
            unset($result[2]); // NEW_CON_NO
        return $result;
    }
}

?>
