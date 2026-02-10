<?php

class Admin extends MY_Controller
{
    var $MODEL_NAME = 'User_model';

    function __construct()
    {
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model($this->MODEL_NAME);

    }

    function create()
    {
        $msg = $this->User_model->insert_admin($_POST['uname'],$_POST['status']);
        redirect('Biunit/da3em_setting/index'.($msg==0?'?msg=حدث خطأ..لم يتم إضافة المستخدم.':''));
    }
    function update(){
        $msg = $this->User_model->update_admin($_POST['uname'],$_POST['status']);
        redirect('biunit/padmin'.($msg==0?'?msg=حدث خطأ..لم يتم تعديل بيانات المستخدم.':''));
    }
    function delete(){
        $msg = $this->User_model->delete_admin($_GET['username']);
        redirect('biunit/padmin'.($msg==0?'?msg=حدث خطأ..لم يتم حذف المستخدم.':''));
    }
}