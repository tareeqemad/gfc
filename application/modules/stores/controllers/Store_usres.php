<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 25/01/15
 * Time: 12:05 م
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class store_usres extends MY_Controller{

    var $MODEL_NAME= 'store_users_model';
    var $PAGE_URL= 'stores/stores/get_page_users';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }



    function public_get_id(){

        $id= $this->input->post('ser');
        $result= $this->{$this->MODEL_NAME}->get($id);
        $result = json_encode($result);

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
        $ser= $this->input->post('ser');
        $store_id= $this->input->post('store_id');

     /*
        $USER_ID= $this->input->post('user_id');
        $this->IsAuthorized();
        $msg = 0;
        if(is_array($USER_ID)){
            foreach($USER_ID as $val){
                $msg= $this->{$this->MODEL_NAME}->delete($ser);
            }
        }else{

            $msg= $this->{$this->MODEL_NAME}->delete($ser);
        }

        $msg= $this->{$this->MODEL_NAME}->delete($ser);
        if($msg == 1){
          echo  modules::run($this->PAGE_URL);
            //   $this->print_error_msg($msg);
        }else{
            $this->print_error_msg($msg);
        }*/
        $result = $this->{$this->MODEL_NAME}->delete($ser);
       // echo  $result;
       $this->Is_success($result);
       echo  modules::run($this->PAGE_URL);
    }

    function _postedData($typ=null){

        if ($typ=='create'){
            $result = array(

                  array('name'=>'USER_ID','value'=>$this->input->post('user_id') ,'type'=>'','length'=>-1),
              array('name'=>'STORE_ID','value'=>$this->input->post('store_id') ,'type'=>'','length'=>-1),
                array('name'=>'STORE_LEVEL','value'=>$this->input->post('store_level') ,'type'=>'','length'=>-1),
                array('name'=>'TEL','value'=>$this->input->post('tel') ,'type'=>'','length'=>-1),
                array('name'=>'EMAIL','value'=>$this->input->post('email') ,'type'=>'','length'=>-1)
            );


        }else{
            $result = array(




                array('name'=>'SER','value'=>$this->input->post('ser') ,'type'=>'','length'=>-1),
                array('name'=>'USER_ID','value'=>$this->input->post('user_id') ,'type'=>'','length'=>-1),
                array('name'=>'STORE_ID','value'=>$this->input->post('store_id') ,'type'=>'','length'=>-1),
                array('name'=>'STORE_LEVEL','value'=>$this->input->post('store_level') ,'type'=>'','length'=>-1),
                array('name'=>'TEL','value'=>$this->input->post('tel') ,'type'=>'','length'=>-1),
                array('name'=>'EMAIL','value'=>$this->input->post('email') ,'type'=>'','length'=>-1)
            );
        }
        return $result;
    }
}

?>
