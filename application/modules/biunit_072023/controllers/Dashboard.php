<?php

class Dashboard extends MY_Controller
{
    var $MODEL_NAME = 'Dashboard_model';

    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model($this->MODEL_NAME);

    }


    function create(){
        $data['DASHBOARD_TITLE']=$_POST['title'];
        $data['CATEGORY_ID']=$_POST['category_id'];
        $data['DASHBOARD_URL']=$_POST['url'];
        if(isset($_POST['users']))
        { // Retrieving each selected option
            foreach ($_POST['users'] as $user)
                $data['DASHBOARD_USERS'] =  $data['DASHBOARD_USERS'].$user.';';
        }
        if($_FILES["icon-file"]["name"] != '') { // Check icon file
            $target_dir = "assets/da3em/img/uploads/";
            $target_file = $target_dir . basename($_FILES["icon-file"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $uploadOk = 1;

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            if ($uploadOk == 0) {  // if there is an error, return error message...
                echo "Sorry, your file was not uploaded.";
            }
            else {   // if everything is ok, try to upload file
                $flag = move_uploaded_file($_FILES["icon-file"]["tmp_name"], $target_file);
                $data['ICON']=basename($_FILES["icon-file"]["name"]);
                 if(!$flag) {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
        else{
            $data['ICON']=$_POST['btnradio_icon'];
        }

        $msg = $this->Dashboard_model->insert_dashboard($data);
        redirect('biunit/Biunit/da3em_setting'.($msg==0?'?msg=حدث خطأ..لم يتم إضافة اللوحة.':''));
    }

    function update(){
        $data['ID'] = $_POST['id'];
        $data['DASHBOARD_TITLE'] = $_POST['title'];
        $data['CATEGORY_ID']=$_POST['category_id'];
        $data['DASHBOARD_URL']=$_POST['url'];
        if(isset($_POST['users']))
        { // Retrieving each selected option
            foreach ($_POST['users'] as $user)
                $data['DASHBOARD_USERS'] =  $data['DASHBOARD_USERS'].$user.';';
        }
        if($_FILES["icon-file"]["name"] != '') { // Check icon file
            $target_dir = "assets/da3em/img/uploads/";
            $target_file = $target_dir . basename($_FILES["icon-file"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $uploadOk = 1;

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            if ($uploadOk == 0) {  // if there is an error, return error message...
                echo "Sorry, your file was not uploaded.";
            }
            else {   // if everything is ok, try to upload file
                $flag = move_uploaded_file($_FILES["icon-file"]["tmp_name"], $target_file);
                $data['ICON']=basename($_FILES["icon-file"]["name"]);
                if(!$flag) {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
        else{
            $data['ICON']=$_POST['btnradio_icon'];
        }

        $msg = $this->Dashboard_model->update_dashboard($data);
        redirect('biunit/Biunit/da3em_setting'.($msg==0?'?msg=حدث خطأ..لم يتم تعديل اللوحة.':''));
    }
    function delete(){
        $msg = $this->Dashboard_model->delete_dashboard($_GET['id']);

        redirect('biunit/Biunit/da3em_setting'.($msg==0?'?msg=حدث خطأ..لم يتم حذف اللوحة.':''));
    }



}