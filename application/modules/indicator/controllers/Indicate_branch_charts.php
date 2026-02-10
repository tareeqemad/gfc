<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 16/08/18
 * Time: 12:30 م
 */
class indicate_branch_charts extends MY_Controller{

    var $MODEL_NAME= 'indicator_model';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->year= $this->budget_year;
        $this->ser=$this->input->post('ser');

    }
    /******************************************************************************************************************************************************/
    /*                                                             Manage Indicator                                                                      */
    /******************************************************************************************************************************************************/
    function index()
    {


        $data['title']='عرض مؤشرات أداء المقرات';
        $data['content']='indicator_branches_charts_index';
        $data['help']=$this->help;
        add_css('components-md-rtl.css');
        add_js('flot/jquery.flot.js');
        add_js('flot/jquery.flot.resize.js');
        add_js('flot/jquery.flot.pie.js');
        add_js('flot/jquery.flot.stack.js');
        add_js('flot/jquery.flot.crosshair.js');
        add_js('flot/jquery.canvasjs.min.js');
        add_js('flot/canvasjs.min.js');
        add_js('jshashtable-2.1.js');
        add_js('echart/echarts.min.js');
        add_js('metronic.js');
        add_css('datepicker3.css');
        add_js('bootstrap.min.js');


        add_js('jquery.formatNumber-0.1.1.min.js');




        $this->load->view('template/template',$data);
        // $this->_look_ups($data);

        // $this->load->view('template/template', $data);

    }
}