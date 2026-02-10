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

    }

    function index(){

        add_js('flot/jquery.flot.js');
        add_js('flot/jquery.flot.resize.js');
        add_js('flot/jquery.flot.pie.js');
        add_js('flot/jquery.flot.stack.js');
        add_js('flot/jquery.flot.crosshair.js');
        add_js('jshashtable-2.1.js');
        add_js('jquery.formatNumber-0.1.1.min.js');

        add_js('moment.js');
        add_js('fullcalendar/fullcalendar.min.js');
        add_js('fullcalendar/ar-sa.js');

        add_css('cupertino/jquery-ui.min.css');
        add_css('fullcalendar/fullcalendar.min.css');
        //add_css('fullcalendar/fullcalendar.print.css');


        $data['title']='لوحة التحكم - نظام gis';
        $data['content']='cpanel_index';

        $this->load->view('template/template',$data);
    }
}