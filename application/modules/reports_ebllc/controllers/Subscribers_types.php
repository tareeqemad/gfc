<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 05/01/23
 * Time: 09:00 ص
 */
class Subscribers_types extends MY_Controller {

    var $MODEL_NAME= 'Subscribers_types_model';
    var $PAGE_URL= 'reports_ebllc/Subscribers_types/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'REPORT_PKG';

        $this->ser = $this->input->post('ser');
        $this->subscriber_type = $this->input->post('subscriber_type');
        $this->subscribe_fees = $this->input->post('subscribe_fees');
        $this->bill_type = $this->input->post('bill_type');

    }

    function index()
    {
        $data['content']='subscribers_types_index';
        $data['title']='أنواع اشتراكات حسب العداد';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->ser!= null)? " and M.NO= '{$this->ser}' " : '';
        $where_sql .= isset($this->subscriber_type) && $this->subscriber_type !=null ? " AND  M.SUBSCRIBER_TYPE like '%{$this->subscriber_type}%' " :"" ;
        $where_sql.= ($this->bill_type!= null)? " and M.BILL_TYPE= '{$this->bill_type}' " : '';
        $where_sql.= ($this->subscribe_fees!= null)? " and M.SUBSCRIBE_FEES= '{$this->subscribe_fees}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('SUBSCRIBERS_TYPES_TB  M '.$where_sql);
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
        $this->load->view('subscribers_types_page',$data);
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if(intval($this->ser) <= 0){
                $this->print_error($this->ser);
            }else{
                echo intval($this->ser);
            }
        }
        $data['content']='subscribers_types_show';
        $data['title']='اضافة نوع اشتراك';
        $data['action'] = 'index';
        $data['isCreate']= true;
        $id = $this->input->post('id');
        $data['get_max']= $this->{$this->MODEL_NAME}->get($id);
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['content']='subscribers_types_show';
        $data['title']='بيانات نوع الاشتراك ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function _post_validation($isEdit = false){

        if( $this->bill_type == 10 && ($this->ser >= 200 || $this->ser == 0) ){
            $this->print_error('يجب ان يكون الرقم اقل من 200');
        }elseif( ($this->bill_type == 20 || $this->bill_type == 30) && ($this->ser < 300 || $this->ser >= 400)  ){
            $this->print_error('يجب ان يكون الرقم اكبر من 300 واقل من 400');
        }elseif( $this->bill_type == 40  && ($this->ser < 200 || $this->ser >= 300)  ){
            $this->print_error('يجب ان يكون الرقم اكبر من 200 واقل من 300');
        }

    }

    function public_get_max()
    {
        $id = $this->input->post('id');
        $ret = $this->{$this->MODEL_NAME}->get_max($id);
        echo json_encode($ret);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['bill_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',497,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'NO','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBER_TYPE','value'=> $this->subscriber_type ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBE_FEES','value'=> $this->subscribe_fees ,'type'=>'','length'=>-1),
            array('name'=>'BILL_TYPE','value'=> $this->bill_type ,'type'=>'','length'=>-1),
        );

        return $result;
    }

}