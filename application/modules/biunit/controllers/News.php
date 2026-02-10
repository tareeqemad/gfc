<?php

class News extends MY_Controller
{
    var $MODEL_NAME = 'News_model';

    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model($this->MODEL_NAME);

    }

    function create(){
        $msg = $this->News_model->insert_news($_POST['title'],$_POST['date'],$_POST['status'],(isset($_POST['link'])==1)?$_POST['month']:0);
        redirect('biunit/Biunit/show_news'.($msg==0?'?msg=حدث خطأ..لم يتم إضافة الخبر.':''));
    }

    function update(){
        $msg = $this->News_model->update_news($_POST['id'],$_POST['title2'],$_POST['date2'],$_POST['status2'],(isset($_POST['link2'])==1)?$_POST['month2']:0);
        redirect('biunit/Biunit/show_news'.($msg==0?'?msg=حدث خطأ..لم يتم تعديل الخبر.':''));
    }

    function delete(){
        $msg = $this->News_model->delete_news($_GET['id']);
        redirect('biunit/Biunit/show_news'.($msg==0?'?msg=حدث خطأ..لم يتم حذف الخبر.':''));
    }

}