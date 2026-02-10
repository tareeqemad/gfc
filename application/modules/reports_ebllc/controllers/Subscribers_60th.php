<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/02/23
 * Time: 08:20 ص
 */


class Subscribers_60th extends MY_Controller{

    var $MODEL_NAME= 'Subscribers_60th_model';
    var $PAGE_URL= 'reports_ebllc/Subscribers_60th/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Subscribers_60th_model');
        $this->load->model('root/rmodel');

        $this->branch_id = $this->input->post('branch_id');
        $this->subscriber_no = $this->input->post('subscriber_no');
        $this->form_month = $this->input->post('form_month');
        $this->to_month = $this->input->post('to_month');
        $this->status = $this->input->post('status');
        $this->bill_type = $this->input->post('bill_type');
        $this->classification_institutions = $this->input->post('classification_institutions');
        $this->vase_type = $this->input->post('vase_type');
        $this->counter_type = $this->input->post('counter_type');
        $this->ratio = $this->input->post('ratio');
        $this->arrears = $this->input->post('arrears');
        $this->consumption = $this->input->post('consumption');
        $this->counter_status = $this->input->post('counter_status');
        $this->installation_status = $this->input->post('installation_status');
        $this->targeting_status = $this->input->post('targeting_status');

    }

    function index()
    {
        $data['content']='subscribers_60th_index';
        $data['title']='حملة 60ألف عداد';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

         $where_sql= "  ";
         $where_sql.= ($this->branch_id!= null)? "AND M.BRANCH = '{$this->branch_id}' " : '';
         $where_sql.= ($this->subscriber_no!= null)? "AND M.SUB = '{$this->subscriber_no}' " : '';
         $where_sql.= ($this->status!= null)? "AND M.STATUS = '{$this->status}' " : '';
         $where_sql.= ($this->form_month!= null)? "AND M.MIN_SMART_MON >= '{$this->form_month}' " : '';
         $where_sql.= ($this->to_month!= null)? "AND M.MIN_SMART_MON <= '{$this->to_month}' " : '';
         $where_sql.= ($this->bill_type!= null)? "AND M.TYPE = '{$this->bill_type}' " : '';
         $where_sql.= ($this->vase_type!= null)? "AND PHASE_TYPE = '{$this->vase_type}' " : '';
         $where_sql.= ($this->classification_institutions!= null)? "AND M.USE_TYPE = '{$this->classification_institutions}' " : '';
         $where_sql .= ($this->arrears!= null )? " AND  remainder $this->ratio {$this->arrears}  " : "";
         $where_sql .= ($this->consumption!= null )? " AND  REPORT_PKG.GET_KW_CONSUME_FROM_MONTH(M.SUB,M.MIN_SMART_MON) $this->ratio {$this->consumption}  " : "";

        if ($this->counter_status == 1){
            $where_sql.= ($this->counter_status!= null)? "AND M.STATUS IN (1,3,4) " : '';
        }else if($this->counter_status == 2){
            $where_sql.= ($this->counter_status!= null)? "AND M.STATUS = 2 " : '';
        }else{
            $where_sql.= ($this->counter_status!= null)? "AND M.STATUS IN (1,2,3,4) " : '';
        }

        $where_sql.= ($this->installation_status!= null)? "AND NVL2(R.SUBSCRIBER,1,2) = '{$this->installation_status}' " : '';
        $where_sql.= ($this->targeting_status!= null)? "AND M.FLAG = '{$this->targeting_status}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);

        $count_rs = $this->get_table_count_bills(' SUBSCRIBERS_SMART_VW M,
            (SELECT SUBSCRIBER,SUM(NVL(PAID_VALUE,0)) PAID_VALUE,SUM((NVL(CHARGE_VALUE,0) + NVL(CHARGE_ADD,0))) CHARGE_VALUE
            FROM   SMART.CHAREG_PAID_TB WHERE STATUS >1 and for_month >= 202210 GROUP BY SUBSCRIBER) C ,G_USER.M_METERS_60 G
            WHERE M.SUB = C.SUBSCRIBER(+) AND M.KW_COUNTER_NO=G.NO(+) '.$where_sql);

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
        $this->load->view('subscribers_60th_page',$data);
    }

    public function public_get_detail(){
        $subscriber = $this->input->post('subscriber');
        $from_month = $this->input->post('from_month');
        if (intval($subscriber) > 0) {
            $data['rertMainAdopt'] = $this->{$this->MODEL_NAME}->get($subscriber,$from_month);
            $data['from_month_'] = $from_month;
            $this->load->view('get_details_view',$data);
        }
    }

    function _look_ups(&$data){

        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);
        $data['status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',507,0);
        $data['vase_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 15,0);
        $data['bill_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',11,1);
        $data['counter_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',497,0);

        $data['counter_status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',538,0);
        $data['installation_status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',539,0);
        $data['targeting_status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',540,0);

        $data['classification_institutions'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 13,0);
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
