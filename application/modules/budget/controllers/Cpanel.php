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
        $this->load->model('budget_model');
    }

    function index(){

        $data['title']='لوحة التحكم - النظام الموازنة';
        $data['content']='cpanel_index';
        $data['module'] = $this->module;

        add_js('flot/jquery.flot.js');
        add_js('flot/jquery.flot.resize.js');
        add_js('flot/jquery.flot.pie.js');
        add_js('flot/jquery.flot.stack.js');
        add_js('flot/jquery.flot.crosshair.js');


        $data['budget_statistic']=$this->budget_model->budget_statistic($this->year);
        $data['budget_statistic_branch']=$this->budget_model->budget_statistic_branch($this->year);
        $data['budget_a_exp_statistic']=$this->budget_model->budget_exp_rev_statistic(2,$this->year+1,4)[0]["VAL"];
        $data['budget_a_rev_statistic']=$this->budget_model->budget_exp_rev_statistic(1,$this->year+1,4)[0]["VAL"];
        $data['budget_exp_statistic']=$this->budget_model->budget_exp_rev_statistic(2,$this->year+1,1)[0]["VAL"];
        $data['budget_rev_statistic']=$this->budget_model->budget_exp_rev_statistic(1,$this->year+1,1)[0]["VAL"];

        $this->load->view('template/template',$data);
    }
}