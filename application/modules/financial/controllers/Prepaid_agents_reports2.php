<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2019-09-21
 * Time: 10:05 PM
 */

class Prepaid_agents_reports2 extends MY_Controller{

    function  __construct(){
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'QF_PKG';
    }

    function index(){
        $data['title']='تقارير مطابقة الأرصدة';
        $data['content']='prepaid_agents_reports_2_index';

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->_lookUps_data($data);
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->view('template/template',$data);
    }

    function _lookUps_data(&$data){
        //$data['branches'] = $this->gcc_branches_model->get_all();
        $data['charge_company'] = $this->constant_details_model->get_all('301');
    }

}

?>