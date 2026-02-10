<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/03/23
 * Time: 09:10 ص
 */

class Exceptional_credit extends MY_Controller {

    var $MODEL_NAME= 'Exceptional_credit_model';
    var $PAGE_URL= 'salary/Exceptional_credit/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('Root/New_rmodel');
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'SALARY_EMP_PKG';

        $this->ser = $this->input->post('ser');
        $this->branch_no = $this->input->post('branch_no');
        $this->the_month = $this->input->post('the_month');
        $this->balance = $this->input->post('balance');
        $this->category = $this->input->post('category');

    }

    function index()
    {
        $data['content']='exceptional_credit_index';
        $data['title']='الرصيد الاستثنائي - مقرات';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->ser!= null)? " and M.SER= '{$this->ser}' " : '';
        $where_sql.= ($this->branch_no!= null)? "and M.BRANCH_ID= '{$this->branch_no}' " : '';
        $where_sql.= ($this->category!= null)? "and M.CATEGORY= '{$this->category}' " : '';
        $where_sql.= ($this->the_month!= null)? "and M.THE_MONTH= '{$this->the_month}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('EXCEPTIONAL_CREDIT_TB  M'.$where_sql);
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
        $this->load->view('exceptional_credit_page',$data);

    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $this->target_no= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if(intval($this->target_no) <= 0){
                $this->print_error($this->target_no);
            }else{
                echo intval($this->target_no);
            }
        }
        $data['content']='exceptional_credit_show';
        $data['title']='الرصيد الاستثنائي - مقرات';
        $data['action'] = 'index';
        $data['isCreate']= true;
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
        $data['content']='exceptional_credit_show';
        $data['title']='الرصيد الاستثنائي - مقرات';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }


    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _post_validation($isEdit = false){

        if( $this->branch_no == ''){
            $this->print_error('يجب اختيار المقر');
        }else if( $this->the_month == '' ){
            $this->print_error('يجب ادخال الشهر');
        }else if( $this->balance == '' ){
            $this->print_error('يجب ادخال الرصيد');
        }else if( $this->category == '' ){
            $this->print_error('يجب اختيار فئة الاتصال');
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['category'] = $this->constant_details_model->get_list(503);
        $data['current_date'] = date("Ym");
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_ID','value'=> $this->branch_no ,'type'=>'','length'=>-1),
            array('name'=>'THE_MONTH','value'=> $this->the_month ,'type'=>'','length'=>-1),
            array('name'=>'BALANCE','value'=> $this->balance ,'type'=>'','length'=>-1),
            array('name'=>'CATEGORY','value'=> $this->category ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}