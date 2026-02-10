<?php


class Biunit extends MY_Controller{


    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model('User_model');
        $this->load->model('indicator_model');
        $this->load->model('news_model');
        $this->load->model('Category_model');
        $this->load->model('Dashboard_model');
    }

    function index(){
        $data['title']='وحدة ذكاء الأعمال';
        $data['content']='index';
        $data['is_admin'] = $this->User_model->is_admin(get_curr_user()->username);
        $data['indicators'] = $this->indicator_model->get_all_indicators();
        $data['news'] = $this->news_model->get_last_active();
        $this->load->view('template/template',$data);
    }
    function smartGrid(){
        $data['title']='وحدة ذكاء الأعمال';
        $data['content']='smartgrid';
        $this->load->view('template/template',$data);
    }

    function show_indicators(){
        $data['title']='وحدة ذكاء الأعمال';
        $data['content']='show_indicators';
        $data['is_admin'] = $this->User_model->is_admin(get_curr_user()->username);
        $data['indicators'] = $this->indicator_model->get_all_indicators();

        $this->load->view('biunit/setting',$data);

    }
    function show_news(){
        $data['title']='وحدة ذكاء الأعمال';
        $data['content']='show_news';
        $data['is_admin'] = $this->User_model->is_admin(get_curr_user()->username);
        $data['news'] = $this->news_model->get_all_news();
        $this->load->view('biunit/setting',$data);

    }

    function da3em_setting(){
        $data['title'] = 'منصة داعم لذكاء الأعمال';
        $data['content'] = 'Da3em/setting';
        $data['is_admin'] = $this->User_model->is_admin(get_curr_user()->username);
        $data['users'] = $this->User_model->get_users();
        $data['categories'] = $this->Category_model->get_all_categories();
        $data['dashboards'] = $this->Dashboard_model->get_all_dashboards();

        $this->load->view('biunit/setting', $data);
    }

    function add_dashboard(){
        $data['title'] = 'منصة داعم لذكاء الأعمال';
        $data['content'] = 'Da3em/add_dashboard';
        $data['is_admin'] = $this->User_model->is_admin(get_curr_user()->username);
        $data['users'] = $this->User_model->get_users();
        $data['categories'] = $this->Category_model->get_all_categories();
        $this->load->view('biunit/setting', $data);
    }

    function edit_dashboard(){
        $data['title'] = 'منصة داعم لذكاء الأعمال';
        $data['content'] = 'Da3em/edit_dashboard';
        $data['is_admin'] = $this->User_model->is_admin(get_curr_user()->username);
        $data['users'] = $this->User_model->get_users();
        $data['categories'] = $this->Category_model->get_all_categories();
        $data['dashboard'] = $this->Dashboard_model->get_dashboard($_GET['id']);

        $this->load->view('biunit/setting', $data);
    }

    function padmin(){
        $data['title'] = 'منصة داعم لذكاء الأعمال';
        $data['content'] = 'admin-page';
        $data['users'] = $this->User_model->get_users();
        $data['admins'] = $this->User_model->get_admins();
        $this->load->view('biunit/setting', $data);
    }












}