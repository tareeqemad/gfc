<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 20/02/23
 * Time: 09:40 ص
 */
class Month_active_target extends MY_Controller {

    var $MODEL_NAME= 'Month_active_target_model';
    var $PAGE_URL= 'ratio_emp_lost/Month_active_target/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('Root/New_rmodel');

        $this->target_no = $this->input->post('target_no');
        $this->branch_no = $this->input->post('branch_no');
        $this->activity_no = $this->input->post('activity_no');
        $this->the_month = $this->input->post('the_month');
        $this->monthly_target = $this->input->post('monthly_target');
        $this->discount_value = $this->input->post('discount_value');

    }

    function index()
    {
        $data['content']='month_active_target_index';
        $data['title']='المستهدف الشهري للأنشطة';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->target_no!= null)? " and M.TARGET_NO= '{$this->target_no}' " : '';
        $where_sql.= ($this->branch_no!= null)? " and M.BRANCH_NO= '{$this->branch_no}' " : '';
        $where_sql.= ($this->activity_no!= null)? "and M.ACTIVITY_NO = '{$this->activity_no}' " : '';
        $where_sql.= ($this->the_month!= null)? "and M.THE_MONTH= '{$this->the_month}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('MONTH_ACTIVE_TARGET_TB  M'.$where_sql);
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
        $this->load->view('month_active_target_page',$data);

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
        $data['content']='month_active_target_show';
        $data['title']='المستهدف الشهري للانشطة';
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
        $data['content']='month_active_target_show';
        $data['title']='بيانات المستهدف الشهري للانشطة ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->target_no, $case);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->target_no!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _post_validation($isEdit = false){

        if( $this->branch_no == ''){
            $this->print_error('يجب اختيار المقر');
        }else if( $this->activity_no == '' ){
            $this->print_error('يجب اختيار النشاط');
        }else if( $this->the_month == '' ){
            $this->print_error('يجب ادخال الشهر');
        }else if( $this->monthly_target == '' ){
            $this->print_error('يجب ادخال المستهدف الشهري');
        }else if( $this->discount_value == '' ){
            $this->print_error('يجب ادخال قيمة الخصم');
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');

        $data['activity_no'] = $this->constant_details_model->get_list(492);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['branches'] = $this->gcc_branches_model->get_all();

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'TARGET_NO','value'=> $this->target_no ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_NO','value'=> $this->branch_no ,'type'=>'','length'=>-1),
            array('name'=>'ACTIVITY_NO','value'=> $this->activity_no ,'type'=>'','length'=>-1),
            array('name'=>'THE_MONTH','value'=> $this->the_month ,'type'=>'','length'=>-1),
            array('name'=>'MONTHLY_TARGET','value'=> $this->monthly_target ,'type'=>'','length'=>-1),
            array('name'=>'DISCOUNT_VALUE','value'=> $this->discount_value ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}