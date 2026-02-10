<?php

class Da3em extends MY_Controller{

    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model('User_model');
        $this->load->model('Category_model');
        $this->load->model('Dashboard_model');

    }

    function index(){
        $data['title'] = 'منصة داعم لذكاء الأعمال';
        $data['content'] = 'Da3em/index';
        $data['is_admin'] = $this->User_model->is_admin(get_curr_user()->username);
        $data['categories'] = $this->Category_model->get_all_categories();
        $data['dashboards'] = $this->Dashboard_model->get_all_dashboards();

        $this->load->view('biunit/Da3em/template', $data);
    }

    function show_setting(){
        $data['title'] = 'منصة داعم لذكاء الأعمال';
        $data['content'] = 'Da3em/setting';
        $data['is_admin'] = $this->User_model->is_admin(get_curr_user()->username);
        $data['users'] = $this->User_model->get_users();
        $data['categories'] = $this->Category_model->get_all_categories();
        $data['dashboards'] = $this->Dashboard_model->get_all_dashboards();

        $this->load->view('biunit/setting', $data);
    }


    function show_dashboard(){
        $data['title'] = 'منصة داعم لذكاء الأعمال';
        $data['content'] = 'Da3em/show';
        $data['dashboard'] = $this->Dashboard_model->get_dashboard($_GET['id']);

        $this->load->view('biunit/Da3em/template', $data);
    }



    function redirect(){
        $this->load->view('biunit/Da3em/redirect');
    }





}