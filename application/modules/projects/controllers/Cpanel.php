<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/2/14
 * Time: 10:00 AM
 */

class Cpanel extends  MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('projects_model');
        $this->load->model('adapter_model');
    }

    function index(){

        $data['title']='لوحة التحكم - النظام الفني';
        $data['content']='cpanel_index';
        $data['module'] = $this->module;

        add_js('flot/jquery.flot.js');
        add_js('flot/jquery.flot.resize.js');
        add_js('flot/jquery.flot.pie.js');
        add_js('flot/jquery.flot.stack.js');
        add_js('flot/jquery.flot.crosshair.js');

        add_js('gmaps.js');

        $data['project_set']=$this->projects_model->projects_file_tb_sat();

        $data["adapters"] = $this->adapter_model->get_list('', 0 , 2000 );


        $this->load->view('template/template',$data);
    }
}