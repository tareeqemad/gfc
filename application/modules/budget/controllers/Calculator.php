<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/10/14
 * Time: 07:25 ص
 */

class Calculator extends MY_Controller{

    function  __construct(){
        parent::__construct();

        $this->load->model('calculator_model');


    }

    function index(){


        add_js('circle-progress.js');

        $data['title']='إحتساب الموازنة';
        $data['content']='calculator_index';


        $_budget_per= $this->calculator_model->budget_history_items_count($this->year+1);

        $data['sal_per'] = $this->calculator_model->salary_count($this->year+1)[0]['C'];
        $data['overtime_per'] = $this->calculator_model->overtime_count($this->year+1)[0]['C'];
        $data['budget_per'] = $_budget_per[0]['C'];
        $data['year'] = $this->year;

        $this->load->view('template/template',$data);

    }

    function  salary_calc(){

        echo $this->calculator_model->salary_calc($this->year+1);
    }

    function  budget_calc(){

        echo $this->calculator_model->budget_calc($this->year+1);
    }



    function salary_count(){
        echo $this->calculator_model->salary_count($this->year+1)[0]['C'];
    }



    function  overtime_calc(){

        echo $this->calculator_model->overtime_calc($this->year+1);
    }

    function overtime_count(){

        echo $this->calculator_model->overtime_count($this->year+1)[0]['C'];
    }

    function budget_history_items_count(){
        echo $this->calculator_model->budget_history_items_count($this->year+1)[0]['C'];
    }

    function budget_update_prices(){
        echo $this->calculator_model->budget_update_prices($this->year+1);
    }


}