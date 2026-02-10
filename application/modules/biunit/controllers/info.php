<?php

class info extends MY_Controller
{
    var $MODEL_NAME = 'Document_model';
    var $MODEL_NAME2 = 'DocCategory_model';
    var $MODEL_NAME3 = 'Permission_model';

    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->MODEL_NAME2);
        $this->load->model($this->MODEL_NAME3);

    }

    function index(){ 
        $data['title'] = 'نظام المعلومات والتوثيق';
        $data['content'] = 'info/index';
        $data['sideMenu'] = 'info/sideMenu';
        $data['categories'] = $this->DocCategory_model->get_all_categories();
      //  print_r($data['categories']);die();
        $this->load->view('biunit/info/template', $data);
    }

    function show(){
        $data['title'] = 'نظام المعلومات والتوثيق';
        $data['content'] = 'info/show';
        $data['sideMenu'] = 'info/sideMenu';
        //print_r(get_curr_user());die();
        $data['user_permissions'] = $this->Permission_model->get_user_permissions(get_curr_user()->username);
       // print_r($data['user_permissions']);die();
        $data['documents'] = $this->Document_model->get_all_documents($_GET['category_id']);
        $data['category'] =  $this->DocCategory_model->get_category($_GET['category_id']);
      //  print_r($data['documents']);die();
        $this->load->view('biunit/info/template', $data);
    }

    function add(){
        $data['title'] = 'نظام المعلومات والتوثيق';
        $data['content'] = 'info/add';
        $data['category'] =  $this->DocCategory_model->get_category($_GET['category_id']);
        $data['sideMenu'] = 'info/sideMenu';
        $this->load->view('biunit/info/template', $data);
    }

    function edit(){
        $data['title'] = 'نظام المعلومات والتوثيق';
        $data['content'] = 'info/edit';
        $data['document'] = $this->Document_model->get_document($_GET['id']);
        //$data['category'] =  $this->DocCategory_model->get_category($_GET['category_id']);
        $data['sideMenu'] = 'info/sideMenu';
        $this->load->view('biunit/info/template', $data);
    }

    function approve(){
        $data['title'] = 'نظام المعلومات والتوثيق';
        $data['content'] = 'info/approve';
        $data['sideMenu'] = 'info/sideMenu';
        $data['documents'] = $this->Document_model->get_documents_To_approve();

        //  print_r( $data['documents']);die();
        $this->load->view('biunit/info/template', $data);
    }

    function permission(){
        $data['title'] = 'نظام المعلومات والتوثيق';
        $data['content'] = 'info/permissions/index';
        $data['sideMenu'] = 'info/sideMenu';
        $data['permissions'] = $this->Permission_model->get_all_permissions();
        //print_r($data['permissions']);die();

        //  print_r( $data['documents']);die();
        $this->load->view('biunit/info/template', $data);
    }

    function add_permission(){
        $data['title'] = 'نظام المعلومات والتوثيق';
        $data['content'] = 'info/permissions/add';
        $data['sideMenu'] = 'info/sideMenu';
        $data['users'] = $this->Permission_model->get_all_users();
        $data['roles'] = $this->Permission_model->get_all_roles();

        //  print_r( $data['documents']);die();
        $this->load->view('biunit/info/template', $data);
    }



}