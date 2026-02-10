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
        $this->load->model('Cpanel_model');
        $this->load->model('WorkOrderAssignment_model');

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


        $data['title']='لوحة التحكم - النظام الفني';
        $data['content']='cpanel_index';


        $data['all_info'] = $this->Cpanel_model->count_info_all();
        $data['count_requests_tb'] = $this->Cpanel_model->count_requests_tb();
        $data['count_work_order_requests'] = $this->Cpanel_model->count_work_order_requests();
        $data['count_work_order_asss_requests'] = $this->Cpanel_model->count_work_order_asss_requests();

        $data["WorkOrderAssignment"] = $this->WorkOrderAssignment_model->get_list( " AND (M.BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ", 0, 1000);


        $this->load->view('template/template',$data);
    }
}