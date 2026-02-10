<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/05/23
 * Time: 07:40 ص
 */

class Subscriber_segmentation extends MY_Controller{

    var $MODEL_NAME= 'Subscriber_segmentation_model';
    var $PAGE_URL= 'reports_ebllc/Subscriber_segmentation/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Subscriber_segmentation_model');
        $this->load->model('root/rmodel');

        $this->branch_id = $this->input->post('branch_id');
        $this->subscriber_no_main = $this->input->post('subscriber_no_main');
        $this->subscriber_no_sub = $this->input->post('subscriber_no_sub');
        $this->vase_type = $this->input->post('vase_type');

    }

    function index()
    {
        $data['content']='subscriber_segmentation_index';
        $data['title']='حملة 60ألف عداد - تجزئة الاشتراكات';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= "";

        $where_sql.= ($this->branch_id!= null)? "AND S.BRANCH = '{$this->branch_id}' " : '';
        $where_sql.= ($this->subscriber_no_main!= null)? "AND S.SUBSCRIBER  = '{$this->subscriber_no_main}' " : '';
        $where_sql.= ($this->subscriber_no_sub!= null)? "AND D.SUB_NO  = '{$this->subscriber_no_sub}' " : '';
        $where_sql.= ($this->vase_type!= null)? "AND M.PHASE_TYPE  = '{$this->vase_type}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('TSG.REQUEST_APP_TB@data.world M , TSG.SHARE_SUB_TB@data.world S , TSG.SHARE_SUB_DET_TB@data.world D
        WHERE M.REQUEST_APP_SERIAL = S.REQUEST_APP_SERIAL
        AND S.SHARE_SER = D.SHARE_SER
        AND M.IS_CAMPAIGN in (8,9) 
        AND M.STATUS IN (1,2)'.$where_sql);


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
        $this->load->view('subscriber_segmentation_page',$data);
    }

    function _look_ups(&$data){

        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);
        $data['vase_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 15,0);
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
