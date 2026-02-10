<?php

class Document extends MY_Controller
{
    var $MODEL_NAME = 'Document_model';

    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model($this->MODEL_NAME);

    }

    function create(){
        $data['DOC_NAME']=$_POST['title'];
        $data['CATEGORY_ID']=$_POST['category_id'];
        $data['IS_ACTIVE']=$_POST['is_active'];
        $data['FILES_CODE']=$_POST['unique_id'];

        $msg = $this->Document_model->insert_document($data);
        redirect('biunit/info/show?category_id='.$_POST['category_id']);
    }

    function update(){
        $data['ID']=$_POST['id'];
        $data['DOC_NAME']=$_POST['title'];
        $data['CATEGORY_ID']=$_POST['category_id'];
        $data['IS_ACTIVE']=$_POST['is_active'];
        $data['IS_VALID']=$_POST['is_valid'];

        $msg = $this->Document_model->update_document($data);
        redirect('biunit/info/show?category_id='.$_POST['category_id']);
    }
    function approve(){
     //  print_r($_POST);die();
        if(isset($_POST['accept']) ){
            $data['status'] = 1;
            if(isset($_POST['DOC']) && is_array($_POST['DOC'])) {
                foreach ($_POST['DOC'] as $key=>$checkbox) {
                    $data['ID'] = $key;
                    $this->Document_model->approve_document($data);
                }
            }
        }
        elseif (isset($_POST['reject'])){
            $data['status'] = 2;
            if(isset($_POST['DOC']) && is_array($_POST['DOC'])) {
                foreach ($_POST['DOC'] as $key=>$checkbox) {
                    $data['ID'] = $key;
                    $this->Document_model->approve_document($data);
                }
            }
        }

        redirect('biunit/info/approve');
    }

    function delete(){
        $msg = $this->Document_model->delete_document($_GET['id']);
        redirect('biunit/info/show?category_id='.$_GET['category_id']);

    }


}