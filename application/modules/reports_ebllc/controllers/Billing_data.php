<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 13/12/22
 * Time: 09:00 ص
 */

class Billing_data extends MY_Controller{

    var $MODEL_NAME= 'Billing_data_model';
    var $PAGE_URL= 'reports_ebllc/Billing_data/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Billing_data_model');
        $this->load->model('root/rmodel');

        $this->for_month = $this->input->post('for_month');
        $this->branch_id = $this->input->post('branch_id');
        $this->subscriber_no = $this->input->post('subscriber_no');
        $this->payment_status = $this->input->post('payment_status');
        $this->region = $this->input->post('region');
        $this->enterprise_key = $this->input->post('enterprise_key');
        $this->municipal = $this->input->post('municipal');
        $this->vase_type = $this->input->post('vase_type');
        $this->arrears = $this->input->post('arrears');
        $this->required = $this->input->post('required');
        $this->ratio = $this->input->post('ratio');
        $this->consumption = $this->input->post('consumption');
        $this->institutions = $this->input->post('institutions');
        $this->classification_institutions = $this->input->post('classification_institutions');

    }

    function index()
    {
        $data['content']='billing_data_index';
        $data['title']='بيانات الفواتير';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " ";

        if ($this->payment_status == 10){
            $where_sql.= ($this->for_month!= null)? "  AND EXISTS  (SELECT B.SUBSCRIBER FROM CACHIER_PAYMENTS B WHERE B.FOR_MONTH = 202211 AND B.PAYMENT_TYPE <> 40  AND B.SUBSCRIBER=M.SUBSCRIBER
            AND B.BANK  NOT IN(SELECT G.BANK_NO FROM G_USER.PAID_TYPE_BANKS G WHERE  G.BANK_NO = B.BANK AND G.PAID_TYPE <> 20) AND B.BANK NOT IN(3810,3820,3680)) " : '';

        }else if ($this->payment_status == 20) {
            $where_sql .= ($this->for_month != null) ? " AND NOT EXISTS  (SELECT B.SUBSCRIBER FROM CACHIER_PAYMENTS B WHERE B.FOR_MONTH = 202211 AND B.PAYMENT_TYPE <> 40  AND B.SUBSCRIBER=M.SUBSCRIBER
            AND B.BANK  NOT IN(SELECT G.BANK_NO FROM G_USER.PAID_TYPE_BANKS G WHERE  G.BANK_NO = B.BANK AND G.PAID_TYPE <> 20) AND B.BANK NOT IN(3810,3820,3680)) " : '';
        }

        $where_sql.= ($this->vase_type!= null)? " AND M.PHASE_TYPE= '{$this->vase_type}' " : '';
        $where_sql.= ($this->branch_id!= null)? " AND M.BRANCH= '{$this->branch_id}' " : '';
        $where_sql.= ($this->region!= null)? " AND M.REGION= '{$this->region}' " : '';
        $where_sql.= ($this->municipal!= null)? " AND M.DISTRICT= '{$this->municipal}' " : '';
        $where_sql.= ($this->institutions!= null)? " AND M.ORG= '{$this->institutions}' " : '';
        $where_sql.= ($this->required!= null)? " AND M.NET_TO_PAY= '{$this->required}' " : '';
        $where_sql.= ($this->subscriber_no!= null)? " AND M.SUBSCRIBER= '{$this->subscriber_no}' " : '';
        $where_sql.= ($this->arrears!= null)? " AND M.REMAINDER $this->ratio {$this->arrears} " : '';
        $where_sql.= ($this->required!= null)? " AND M.NET_TO_PAY $this->ratio {$this->required} " : '';
        $where_sql.= ($this->consumption!= null)? " AND M.KW_CONSUMES $this->ratio {$this->consumption} " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('BILLS  M where M.FOR_MONTH = '.$this->for_month.' '.$where_sql);
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($this->for_month,$where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('billing_data_page',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);
        $data['institutions'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 12,0);
        $data['classification_institutions'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 13,0);
        $data['vase_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 15,0);
        $data['municipal'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 17,0);
        $data['region'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 18,0);
        $data['payment_status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 489,0);
        $data['enterprise_key'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 490,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function public_get_twoDet($branch)
    {
        $this->rmodel->package = 'TSG_SETTING_PKG';
        $ret = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 17,$branch);
        echo json_encode($ret);
    }

    function public_get_two($branch)
    {
        $this->rmodel->package = 'TSG_SETTING_PKG';
        $ret = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 12,$branch);
        echo json_encode($ret);
    }

    function public_get_two_region($branch)
    {
        $this->rmodel->package = 'TSG_SETTING_PKG';
        $ret = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 18,$branch);
        echo json_encode($ret);
    }
}
