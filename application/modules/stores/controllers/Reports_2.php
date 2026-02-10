<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2019-09-07
 * Time: 01:36 PM
 */

class reports_2 extends MY_Controller{

    function  __construct()
    {
        parent::__construct();
    }

    function index(){
        $data['title']='التقارير';
        $data['content']='reports_2_index';

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template',$data);
    }


}