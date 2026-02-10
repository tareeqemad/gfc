<?php

class Indicator extends MY_Controller
{
    var $MODEL_NAME = 'Indicator_model';

    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model($this->MODEL_NAME);

    }

    function create(){
        $msg = $this->Indicator_model->insert_indicator($_POST['title'],$_POST['val'],$_POST['act'],$_POST['btnradio_icon']);
        redirect('biunit/Biunit/show_indicators'.($msg==0?'?msg=حدث خطأ..لم يتم إضافة المؤشر.':''));
    }

    function update(){
        $msg = $this->Indicator_model->update_indicator($_POST['id'],$_POST['title'],$_POST['val'],$_POST['act'],$_POST['btn_icon']);
        redirect('biunit/Biunit/show_indicators'.($msg==0?'?msg=حدث خطأ..لم يتم تعديل المؤشر.':''));
    }

    function delete(){
        $msg = $this->Indicator_model->delete_indicator($_GET['id']);
        redirect('biunit/Biunit/show_indicators'.($msg==0?'?msg=حدث خطأ..لم يتم حذف المؤشر.':''));
    }

}