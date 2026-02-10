<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 13/09/23
 * Time: 11:00 ص
 */

class Social_affairs extends MY_Controller{

    var $MODEL_NAME= 'Social_affairs_model';
    var $PAGE_URL= 'reports_ebllc/Social_affairs/get_page';

    function  __construct(){
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->load->model($this->MODEL_NAME);

        $this->file_ser_no = $this->input->post('file_ser_no');
        $this->id = $this->input->post('id');
        $this->subscriber_no = $this->input->post('subscriber_no');
        $this->subscriber_name = $this->input->post('subscriber_name');
        $this->subscriber_type = $this->input->post('subscriber_type');
        $this->use_type = $this->input->post('use_type');
        $this->subscriber_status = $this->input->post('subscriber_status');
        $this->branch_id = $this->input->post('branch_id');
        $this->complaint_status = $this->input->post('complaint_status');

    }

    function index()
    {
        $data['content']='social_affairs_index';
        $data['title']='الشؤون الإجتماعية';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= "AND 1 = 1";

        $where_sql.= ($this->file_ser_no!= null)? " AND G.FILE_SER_NO= '{$this->file_ser_no}' " : '';
        $where_sql.= ($this->subscriber_no!= null)? " AND G.SUB_NO= '{$this->subscriber_no}' " : '';
        $where_sql.= ($this->subscriber_type!= null)? " AND S.TYPE= '{$this->subscriber_type}' " : '';
        $where_sql.= ($this->use_type!= null)? " AND S.USE_TYPE= '{$this->use_type}' " : '';
        $where_sql.= ($this->id!= null)? " AND G.SSN= '{$this->id}' " : '';
        $where_sql.= ($this->branch_id!= null)? " AND S.BRANCH= '{$this->branch_id}' " : '';
        $where_sql.= ($this->subscriber_status!= null)? " AND REPORT_PKG.IS_AGREE_SOCIAL(G.SSN) = '{$this->subscriber_status}' " : '';
        $where_sql .= isset($this->subscriber_name) && $this->subscriber_name !=null ? " AND  G.SUB_NAME like '%{$this->subscriber_name}%' " :"" ;
        $where_sql.= ($this->complaint_status!= null)? " AND REPORT_PKG.GET_COMPLAINT_STATUS(G.SUB_NO)= '{$this->complaint_status}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('TSG.GOV_GAZA_V@DATA.WORLD  G  , SUBSCRIBERS S ,COMPLAINTS_TB C
         WHERE G.SUB_NO=S.NO AND G.SUB_NO = C.SUBSCRIBER_NO(+)
         AND IS_ACTIVE<>255'.$where_sql);


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
        $this->load->view('social_affairs_page',$data);
    }

    function add_complaint()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $subscriber_no = $this->input->post('subscriber_no_m');
            $complaint = $this->input->post('complaint_m');

            $data_arr = array(
                array('name' => 'SUBSCRIBER_NO', 'value' => $subscriber_no, 'type' => '', 'length' => -1),
                array('name' => 'COMPLAINT', 'value' => $complaint, 'type' => '', 'length' => -1)
            );

            $res = $this->{$this->MODEL_NAME}->create($data_arr);

            if(intval($res) <= 0){
                $this->print_error($res);
            }else{
                echo intval($res);
            }

        }
    }

    function _look_ups(&$data){

        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['subscriber_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',11,1);
        $data['use_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 13,0);
        $data['subscriber_status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET', 544,0);
        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);
        $data['complaint_status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',545,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
