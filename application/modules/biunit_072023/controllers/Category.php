<?php

class Category extends MY_Controller
{
    var $MODEL_NAME = 'Category_model';

    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model($this->MODEL_NAME);

    }

    function create(){
        $msg = $this->Category_model->insert_category($_POST['cat_title']);
        redirect('biunit/Biunit/da3em_setting/index'.($msg==0?'?msg=حدث خطأ..لم يتم إضافة المجلد.':''));
    }
    function update(){
        $msg = $this->Category_model->update_category($_POST['id'],$_POST['cat_title']);
        redirect('biunit/Biunit/da3em_setting/index'.($msg==0?'?msg=حدث خطأ..لم يتم تعديل المجلد.':''));
    }
    function delete(){
        $msg = $this->Category_model->delete_category($_GET['id']);
        redirect('biunit/Biunit/da3em_setting/index'.($msg==0?'?msg=حدث خطأ..لم يتم حذف المجلد.':''));
    }


}