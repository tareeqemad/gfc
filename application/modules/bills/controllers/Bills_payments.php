<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 21/12/14
 * Time: 09:05 ص
 */

class Bills_payments extends MY_Controller{

    var $MODEL_NAME= 'bills_payments_model';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/gcc_branches_model');
    }

    function index(){

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');




        $data['title']='كشف مراجعة السداد اليومي';
        $data['content']='bills_payments_index';

        $date = isset($this->p_entry_date)?  DateTime::createFromFormat('d/m/Y',$this->p_entry_date )->format('Ymd') :  DateTime::createFromFormat('d/m/Y',date('d/m/Y') )->format('Ymd');

        $branch = isset($this->p_branch) ?$this->p_branch:($this->user->branch == 1?null : $this->user->branch);

        $data['rows'] =  $this->bills_payments_model->get_list($date,$branch);


        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->view('template/template',$data);
    }

    function details($id = 0){

        $data['title']='كشف مراجعة السداد اليومي';
        $data['content']='bills_payments_details';


        $data['rows'] =  $this->bills_payments_model->get_details($id);

        $this->load->view('template/view',$data);

    }


}
