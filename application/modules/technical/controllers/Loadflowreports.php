<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 24/01/18
 * Time: 11:30 ص
 */
class loadFlowReports extends MY_Controller{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }

    function index(){
        $data['title']='Load Flow Reports';
        $data['content']='loadflowreports_index';

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->_lookUps_data($data);

        $this->load->view('template/template',$data);
    }

       function _lookUps_data(&$data){
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['study_type'] = $this->constant_details_model->get_list(204);
        $data['donation_name'] = $this->constant_details_model->get_list(210);

    }

}