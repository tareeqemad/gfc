<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 21/12/14
 * Time: 01:31 م
 */

class store_members extends MY_Controller{

    var $MODEL_NAME= 'store_members_model';
    var $PAGE_URL= 'stores/store_members/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function get_page(){

        $data['committees_id']= $this->input->post('committees_id');

        $data['get_list']= $this->{$this->MODEL_NAME}->get_list($data['committees_id']);
        $this->load->view('store_members_page',$data);
    }

    function public_get_id(){
       $tb_no= $this->input->post('committees_id');
        $id= $this->input->post('ser');
        $result= $this->{$this->MODEL_NAME}->get($id);
        $result = json_encode($result);

        echo $result;
    }

  /*  function public_get_id_com(){
        $tb_no= $this->input->post('tb_no');
        $id= $this->input->post('id');
        $result= $this->{$this->MODEL_NAME}->get($tb_no,$id);
        $result = json_encode($result);

        $result=str_replace('subs','children',$result);
        $result=str_replace('ACCOUNT_ID','id',$result);
        $result=str_replace('ACOUNT_NAME','text',$result);

        echo $result;
    }*/



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
        $committees_id= $this->input->post('committees_id');
        $EMP_NO= $this->input->post('emp_no');
        $this->IsAuthorized();
        $msg = 0;
        if(is_array($EMP_NO)){
            foreach($EMP_NO as $val){
                $msg= $this->{$this->MODEL_NAME}->delete($committees_id,$val);
            }
        }else{
            $msg= $this->{$this->MODEL_NAME}->delete($committees_id,$EMP_NO);
        }

        if($msg == 1){
            echo  modules::run($this->PAGE_URL);
        }else{
            $this->print_error_msg($msg);
        }
    }

    function _postedData($typ=null){
     /*  if($typ==null and $this->input->post('emp_no')!= $this->input->post('new_emp_no')){
            die("لا يمكن تعديل رقم ");
        }*/
        if ($typ=='create'){
            $result = array(





                array('name'=>'EMP_NO','value'=>$this->input->post('emp_no') ,'type'=>'','length'=>-1),
                array('name'=>'ID_NO','value'=>$this->input->post('id_no') ,'type'=>'','length'=>-1),
                array('name'=>'NAME','value'=>$this->input->post('name') ,'type'=>'','length'=>-1),
                array('name'=>'COMMITTEES_ID','value'=>$this->input->post('committees_id') ,'type'=>'','length'=>100),
                array('name'=>'ORDER_NO','value'=>$this->input->post('order_no') ,'type'=>'','length'=>-1)
            );


        }else{
        $result = array(




            array('name'=>'SER','value'=>$this->input->post('ser') ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$this->input->post('new_emp_no') ,'type'=>'','length'=>-1),
            array('name'=>'ID_NO','value'=>$this->input->post('id_no') ,'type'=>'','length'=>-1),
            array('name'=>'NAME','value'=>$this->input->post('name') ,'type'=>'','length'=>-1),
            array('name'=>'COMMITTEES_ID','value'=>$this->input->post('committees_id') ,'type'=>'','length'=>100),
            array('name'=>'ORDER_NO','value'=>$this->input->post('order_no') ,'type'=>'','length'=>-1)
        );
        }
        return $result;
    }
}

?>
