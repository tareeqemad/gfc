<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2019-09-21
 * Time: 10:05 PM
 */

class Prepaid_agents_reports extends MY_Controller{

    function  __construct(){
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'QF_PKG';
    }

    function index(){
        $data['title']='التقارير';
        $data['content']='prepaid_agents_reports_index';
        $data['agent'] = $this->rmodel->getListBySQL('POINTOFSALES_LIST','');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->_lookUps_data($data);
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->view('template/template',$data);
    }
    
    function _lookUps_data(&$data){
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['charge_company'] = $this->constant_details_model->get_all('301');
        $data['paid_type'] = $this->constant_details_model->get_all('303');
        $data['balance_type'] = $this->constant_details_model->get_all('243');
        $data['charge_type'] = $data['balance_type'];
        $data['is_cancel'] = $this->constant_details_model->get_all('288');
    }


    function public_get_where_collect() {
        header("Content-type: application/json");

        $meter_type = $this->p_meter_type;
        $sub_branch = $this->p_sub_branch;
        $charge_company = $this->p_charge_company;

        $sql = '';
        $sql .= isset($charge_company) && $charge_company != null ? " AND COMP_SER = {$charge_company} " : '';
        $sql .= isset($sub_branch) && $sub_branch != null ? " AND BRANCHES = {$sub_branch} " : '';
        $sql .= isset($meter_type) && $meter_type != null && $meter_type == 1 ? " AND SER > 0 " : '';
        $sql .= isset($meter_type) && $meter_type != null && $meter_type == 2 ? " AND SER < 0 " : '';
        $ret= $this->rmodel->getListBySQL('POINTOFSALES_LIST',$sql);
        echo json_encode($ret);

    }

}