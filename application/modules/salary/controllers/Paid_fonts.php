<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/02/23
 * Time: 11:40 ص
 */
class Paid_fonts extends MY_Controller {

    var $MODEL_NAME= 'Paid_fonts_model';
    var $PAGE_URL= 'salary/Paid_fonts/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->ser = $this->input->post('ser');
        $this->jawal_no = $this->input->post('jawal_no');
        $this->emp_no = $this->input->post('emp_no');
        $this->branch_id = $this->input->post('branch_id');
        $this->approved_amount = $this->input->post('approved_amount');
        $this->bill_value = $this->input->post('bill_value');
        $this->beneficiary = $this->input->post('beneficiary');
        $this->slide_type = $this->input->post('slide_type');
        $this->note = $this->input->post('note');
        $this->line_status = $this->input->post('line_status');


    }

    function index()
    {
        $data['content']='paid_fonts_index';
        $data['title']='بيانات الخطوط المدفوعة';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->emp_no!= null)? " and M.EMP_NO= '{$this->emp_no}' " : '';
        $where_sql.= ($this->branch_id!= null)? "AND EMP_PKG.GET_EMP_BRANCH (M.EMP_NO) = '{$this->branch_id}' " : '';
        $where_sql.= ($this->line_status!= null)? " and M.LINE_STATUS= '{$this->line_status}' " : '';
        $where_sql.= ($this->slide_type!= null)? " and M.SLIDE_TYPE= '{$this->slide_type}' " : '';
        $where_sql.= ($this->beneficiary!= null)? " and M.BENEFICIARY= '{$this->beneficiary}' " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('PAID_FONTS_TB  M'.$where_sql);
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
        $this->load->view('paid_fonts_page',$data);

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
        $data['content']='paid_fonts_show';
        $data['title']='إضافة خط مدفوع';
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
        $data['content']='paid_fonts_show';
        $data['title']='بيانات الخط المدفوع ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function _post_validation($isEdit = false){

        if( $this->jawal_no == '' ){
            $this->print_error('يجب ادخال رقم الجوال');
        }else if( strlen( $this->jawal_no ) != 10 ){
            $this->print_error('الرجاء كتابة رقم الجوال بشكل صحيح');
        }else if( substr($this->jawal_no, 0, 3) != '059' and substr($this->jawal_no, 0, 3) != '056'){
            $this->print_error('يجب ان يبدأ رقم الجوال ب059 أو 056');
        }else if( $this->emp_no == ''){
             $this->print_error('يجب اختيار الموظف');
        }else if($this->approved_amount == '' ){
            $this->print_error('يجب ادخال المبلغ المعمتد');
        }else if($this->bill_value == '' ){
            $this->print_error('يجب ادخال قيمة الفاتورة');
        }/*else if($this->beneficiary == '' ){
            $this->print_error('يجب اختيار الجهة المستفيدة');
        }*/else if($this->slide_type == '' ){
            $this->print_error('يجب اختيار نوع الشريحة');
        }else if($this->line_status == '' ){
            $this->print_error('يجب اختيار حالة الخط');
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('constants_sal_model');
        $this->load->model('root/Rmodel');

        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['slide_type'] = $this->constant_details_model->get_list(510);
        $data['line_status'] = $this->constant_details_model->get_list(229);
        $data['beneficiary'] = $this->Rmodel->getAll('SALARY_EMP_PKG', 'GET_BENEFICIARY_LIST');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=> $this->emp_no ,'type'=>'','length'=>-1),
            array('name'=>'JAWAL_NO','value'=> $this->jawal_no ,'type'=>'','length'=>-1),
            array('name'=>'APPROVED_AMOUNT','value'=> $this->approved_amount ,'type'=>'','length'=>-1),
            array('name'=>'BILL_VALUE','value'=> $this->bill_value ,'type'=>'','length'=>-1),
            array('name'=>'BENEFICIARY','value'=> $this->beneficiary ,'type'=>'','length'=>-1),
            array('name'=>'SLIDE_TYPE','value'=> $this->slide_type ,'type'=>'','length'=>-1),
            array('name'=>'LINE_STATUS','value'=> $this->line_status ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=> $this->note ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            unset($result[0]);
        return $result;
    }



}