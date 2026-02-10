<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 06/03/23
 * Time: 08:00 ص
 */


class All_services_master extends MY_Controller{

    var $MODEL_NAME= 'All_services_master_model';
    var $PAGE_URL= 'reports_ebllc/All_services_master/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('All_services_master_model');
        $this->load->model('root/rmodel');

        $this->branch_id = $this->input->post('branch_id');
        $this->for_month = $this->input->post('for_month');
        $this->is_aly = $this->input->post('is_aly');
        $this->status = $this->input->post('status');
        $this->status_type = $this->input->post('status_type');
        $this->bill_type = $this->input->post('bill_type');

    }

    function index()
    {
        $data['content']='all_services_master_index';
        $data['title']='حركات الاضافة والخصم';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        function array_to_char($stores)
        {
            if (!is_array($stores))
                return null;
            else {
                $stores = implode(",", $stores);
                return $stores;
            }
        }

        $where_sql= "";

        $where_sql.= ($this->branch_id!= null)? "AND U.BRANCH = '{$this->branch_id}' " : '';
        $where_sql.= ($this->for_month!= null)? "AND S.FOR_MONTH = '{$this->for_month}' " : '';
        $where_sql.= ($this->for_month!= null)? "AND S.FOR_MONTH = '{$this->for_month}' " : '';
        $where_sql.= ($this->is_aly!= null)? "AND IT_MAN.IS_ALY1(U.NO) = '{$this->is_aly}' " : '';
        $where_sql.= ($this->status!= null)? "AND A.SERVICE_NO = '{$this->status}' " : '';
        $where_sql.= ($this->status_type!= null)? "AND A.SERVICE_TYPE =  '{$this->status_type}'" : '';
        $where_sql .= ($this->bill_type != null) ? " AND BILL_TYPE IN (" . array_to_char($this->bill_type) . ") " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('IT_MAN.ALL_SERVICES A ,EBLLC.BILLS S ,EBLLC.SUBSCRIBERS U ,SUBSCRIBERS_TYPES_TB ST
        WHERE U.TYPE=ST.NO  AND A.MONTH=S.FOR_MONTH AND  A.SUBSCRIBER_NO=S.SUBSCRIBER AND  A.SUBSCRIBER_NO=U.NO  '.$where_sql);

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('all_services_master_page',$data);
    }

    function public_get_id($type)
    {
        $ret= $this->{$this->MODEL_NAME}->getID($type);
        echo json_encode($ret);
    }

    function _look_ups(&$data){

        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);
        $data['status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',170,0);
        $data['is_aly'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',509,0);
        $data['bill_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',497,0);
        $data['status_type'] = $this->New_rmodel->general_bills_get('REPORT_PKG', 'SERVICES_TYPES_BILLS_LIST' ,  array( array(),array() ) );

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
