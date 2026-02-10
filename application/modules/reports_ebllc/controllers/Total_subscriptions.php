<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/12/22
 * Time: 09:00 ص
 */

class Total_subscriptions extends MY_Controller{

    var $MODEL_NAME= 'Total_subscriptions_model';
    var $PAGE_URL= 'reports_ebllc/Total_subscriptions/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Total_subscriptions_model');
        $this->load->model('root/rmodel');

        $this->branch_id = $this->input->post('branch_id');
        $this->the_month = $this->input->post('the_month');
        $this->from_the_date = $this->input->post('from_the_date');
        $this->to_the_date = $this->input->post('to_the_date');
        $this->subscriber_type = $this->input->post('subscriber_type');

    }

    function index()
    {
        $data['content']='total_subscriptions_index';
        $data['title']='إجمالي الاشتراكات';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " ";

        if ($this->subscriber_type != ''){

            $where_sql.= ($this->subscriber_type!= null)? " AND I.TYPE IN (SELECT NO FROM SUBSCRIBERS_TYPES_TB S WHERE S.BILL_TYPE='{$this->subscriber_type}'  ) " : '';
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql ,$this->the_month ,$this->from_the_date ,$this->to_the_date ,$this->subscriber_type);

        }else{

            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $this->the_month ,$this->from_the_date,$this->to_the_date ,0);

        }

        $data['page'] = $page;
        $this->load->view('total_subscriptions_page',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['subscriber_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',497,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
