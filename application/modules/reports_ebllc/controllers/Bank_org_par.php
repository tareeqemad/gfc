<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/01/23
 * Time: 09:00 ص
 */
class Bank_org_par extends MY_Controller {

    var $MODEL_NAME= 'Bank_org_par_model';
    var $PAGE_URL= 'reports_ebllc/Bank_org_par/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->load->model('Root/New_rmodel');
        $this->rmodel->package = 'REPORT_PKG';

        $this->b_type = $this->input->post('b_type');
        $this->bank_type_name = $this->input->post('bank_type_name');
        $this->pd_type = $this->input->post('pd_type');

    }

    function index()
    {
        $data['content']='bank_org_par_index';
        $data['title']='بنوك السداد';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->b_type!= null)? " and M.B_TYPE= '{$this->b_type}' " : '';
        $where_sql .= isset($this->bank_type_name) && $this->bank_type_name !=null ? " AND  M.BANK_TYPE_NAME like '%{$this->bank_type_name}%' " :"" ;
        $where_sql.= ($this->pd_type!= null)? " and M.PD_TYPE= '{$this->pd_type}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('BANK_ORG_PAR  M '.$where_sql);
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
        $this->load->view('bank_org_par_page',$data);
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
        $data['content']='bank_org_par_show';
        $data['title']='اضافة بنك سداد';
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
        $data['content']='bank_org_par_show';
        $data['title']='بيانات البنك ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function public_get_max()
    {
        $id = $this->input->post('id');
        $ret = $this->{$this->MODEL_NAME}->get_max($id);
        echo json_encode($ret);
    }

    function _post_validation($isEdit = false){

        if( $this->pd_type == ''){
            $this->print_error('يجب اختيار نوع السداد');
        }elseif( $this->b_type == '' ){
            $this->print_error('يجب ادخال رقم نوع البنك');
        }elseif( $this->bank_type_name == '' ){
            $this->print_error('يجب ادخال اسم البنك');
        }

    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['pd_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',497,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'B_TYPE','value'=> $this->b_type ,'type'=>'','length'=>-1),
            array('name'=>'BANK_TYPE_NAME','value'=> $this->bank_type_name ,'type'=>'','length'=>-1),
            array('name'=>'PD_TYPE','value'=> $this->pd_type ,'type'=>'','length'=>-1),
        );

        return $result;
    }

}