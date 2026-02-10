<?php

class Permission  extends MY_Controller
{
    var $MODEL_NAME = 'Permission_model';

    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model($this->MODEL_NAME);

    }

    function create(){

        $data['user']=$_POST['user'];
        $data['role']=$_POST['role'];
        $data['add']=isset($_POST['add'] )?1:0;
        $data['edit']=isset($_POST['edit'])?1:0;
        $data['delete']=isset($_POST['delete'])?1:0;
        $data['created_user']=$_POST['created_user'];

        $msg = $this->Permission_model->insert_permission($data);
       // print_r($msg);die();
        if($msg==2){
               $msg='المستخدم لديه صلاحية administration';
            redirect('biunit/info/add_permission?msg='.$msg);
        }
        elseif ($msg==3){
            $msg='المستخدم لديه هذه الصلاحية';
            redirect('biunit/info/add_permission?msg='.$msg);
        }
        else
          redirect('biunit/info/permission');
    }

    function update(){

    }

    function delete(){
        $msg = $this->Permission_model->delete_permission($_GET['id']);
        redirect('biunit/info/permission');

    }

}