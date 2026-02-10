<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/08/23
 * Time: 09:00 ص
 */

class Tariff extends MY_Controller{

    var $MODEL_NAME= 'Tariff_model';
    var $PAGE_URL= 'reports_ebllc/Tariff/get_page';

    function  __construct(){
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->load->model($this->MODEL_NAME);

        $this->branch_id = $this->input->post('branch_id');
        $this->subscriber_no = $this->input->post('subscriber_no');
        $this->subscriber_name = $this->input->post('subscriber_name');
        $this->bill_type = $this->input->post('bill_type');
        $this->ratio = $this->input->post('ratio');
    }

    function index()
    {
        $data['content']='tariff_index';
        $data['title']='مقارنة التعرفة بين الهولي و الفواتير';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " AND 1=1 ";

        $where_sql.= ($this->branch_id!= null)? " AND S.BRANCH= '{$this->branch_id}' " : '';
        $where_sql.= ($this->subscriber_no!= null)? " AND S.NO= '{$this->subscriber_no}' " : '';
        $where_sql .= isset($this->subscriber_name) && $this->subscriber_name !=null ? " AND  S.NAME like '%{$this->subscriber_name}%' " :"" ;
        $where_sql.= ($this->bill_type!= null)? " AND S.TYPE= '{$this->bill_type}' " : '';
        $where_sql .= ($this->ratio!= null )? " AND  PR.KW_PRICE $this->ratio RATE  " : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('SUBSCRIBERS S ,SUBSCRIBERS_TYPES_TB T ,  EBLLC.BRANCHES BR,EBLLC.PRICES PR,HOLLEY_PRICES HPR
        WHERE  S.TYPE=T.NO
        AND S.BRANCH = BR.BR_NO AND T.BILL_TYPE=20
        AND S.TYPE(+)=PR.SUBSCRIBER_TYPE AND S.LINK_TYPE(+)=PR.LINK_TYPE
        AND S.NO=HPR.SUB_NO(+) AND  PR.KW_PRICE<>RATE  '.$where_sql);

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
        $this->load->view('tariff_page',$data);
    }


    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);
        $data['bill_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',11,1);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
