<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 10/08/17
 * Time: 09:55 ص
 */

// ser, monthly_cpayments_id,contractor_file_id,contractor_id,contractor_name,car_num,workdate_count,car_daily_cost,evworkdate_count,evcar_daily_cost,car_hovertime_count,car_hovertime_cost,workdate_branch,evworkdate_branch,overtime_branch,workdate_finance,evworkdate_finance,overtime_finance,net_salary
// مراجعة المستشار بخصوص القيد المالي

class Monthly_payments_det extends MY_Controller{
    var $MODEL_NAME= 'monthly_payments_det_model';
    var $MODEL_DET_NAME= 'monthly_payments_deduction_model';
    var $PAGE_URL= 'rental/monthly_payments_det/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->MODEL_DET_NAME);

        $this->ser= $this->input->post('ser');
        $this->monthly_cpayments_id= $this->input->post('monthly_cpayments_id');
        $this->contractor_file_id= $this->input->post('contractor_file_id');
        $this->contractor_id= $this->input->post('contractor_id');
        $this->contractor_name= $this->input->post('contractor_name');
        $this->car_num= $this->input->post('car_num');
        $this->net_salary= $this->input->post('net_salary');
        $this->adopt= $this->input->post('adopt');

        $this->workdate_branch= $this->input->post('workdate_branch');
        $this->evworkdate_branch= $this->input->post('evworkdate_branch');
        $this->overtime_branch= $this->input->post('overtime_branch');
        $this->workdate_finance= $this->input->post('workdate_finance');
        $this->evworkdate_finance= $this->input->post('evworkdate_finance');
        $this->overtime_finance= $this->input->post('overtime_finance');

        if( HaveAccess(base_url("rental/monthly_payments_det/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index($page= 1, $ser= -1, $monthly_cpayments_id= -1, $contractor_file_id= -1, $contractor_id= -1, $contractor_name= -1, $car_num= -1, $net_salary= -1 ){

        $data['title']='رواتب العقود المستأجرة ';
        $data['content']='monthly_payments_det_index';

        $data['page']=$page;
        $data['ser']= $ser;
        $data['monthly_cpayments_id']= $monthly_cpayments_id;
        $data['contractor_file_id']= $contractor_file_id;
        $data['contractor_id']= $contractor_id;
        $data['contractor_name']= $contractor_name;
        $data['car_num']= $car_num;
        $data['net_salary']= $net_salary;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $ser= -1, $monthly_cpayments_id= -1, $contractor_file_id= -1, $contractor_id= -1, $contractor_name= -1, $car_num= -1, $net_salary= -1 ){
        $this->load->library('pagination');

        $ser= $this->check_vars($ser,'ser');
        $monthly_cpayments_id= $this->check_vars($monthly_cpayments_id,'monthly_cpayments_id');
        $contractor_file_id= $this->check_vars($contractor_file_id,'contractor_file_id');
        $contractor_id= $this->check_vars($contractor_id,'contractor_id');
        $contractor_name= $this->check_vars($contractor_name,'contractor_name');
        $car_num= $this->check_vars($car_num,'car_num');
        $net_salary= $this->check_vars($net_salary,'net_salary');

        $where_sql= " where 1=1 ";

        if(!$this->all_branches)
            $where_sql.= " and branch_id= ".(  ($this->user->branch ==8)? 2:$this->user->branch  );

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($monthly_cpayments_id!= null)? " and monthly_cpayments_id= '{$monthly_cpayments_id}' " : '';
        $where_sql.= ($contractor_file_id!= null)? " and contractor_file_id= '{$contractor_file_id}' " : '';
        $where_sql.= ($contractor_id!= null)? " and contractor_id= '{$contractor_id}' " : '';
        $where_sql.= ($contractor_name!= null)? " and contractor_name like '".add_percent_sign($contractor_name)."' " : '';
        $where_sql.= ($car_num!= null)? " and car_num= '{$car_num}' " : '';
        $where_sql.= ($net_salary!= null)? " and net_salary between {$net_salary}-50 and {$net_salary}+50 " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' MONTHLY_PAYMENTS_DET_TB '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('monthly_payments_det_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function public_create(){ // لا تلزم
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

        }else{
            $data['content']='monthly_payments_det_show';
            $data['title']='بيانات الراتب';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){ //////////////////////////////////
        //$this->print_error('يجب ادخال جميع البيانات');
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] =count($result) > 0? true : false;
        $data['action'] = $action;
        $data['content']='monthly_payments_det_show';
        $data['title']='بيانات الراتب ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit_branch(){
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

    function edit_finance(){
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

    function edit(){ //////
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                // التعديل على التفاصيل
                echo 1;
            }
        }
    }

    function public_get_details($id= 0, $adopt= 0){
        $data['adopt'] = $adopt;
        $data['details'] = $this->{$this->MODEL_DET_NAME}->get_list($id);
        $this->load->view('monthly_payments_det_details',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        //$data['con__'] = $this->constant_details_model->get_list(0);
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

    function _postedData($typ= null){
        if($this->adopt==2){
            $result = array(
                array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
                array('name'=>'ADOPT','value'=>$this->adopt ,'type'=>'','length'=>-1),
                array('name'=>'WORKDATE_COUNT','value'=>$this->workdate_branch ,'type'=>'','length'=>-1),
                array('name'=>'EVWORKDATE_COUNT','value'=>$this->evworkdate_branch ,'type'=>'','length'=>-1),
                array('name'=>'HOVERTIME_COUNT','value'=>$this->overtime_branch ,'type'=>'','length'=>-1),
            );
        }elseif($this->adopt==3){
            $result = array(
                array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
                array('name'=>'ADOPT','value'=>$this->adopt ,'type'=>'','length'=>-1),
                array('name'=>'WORKDATE_COUNT','value'=>$this->workdate_finance ,'type'=>'','length'=>-1),
                array('name'=>'EVWORKDATE_COUNT','value'=>$this->evworkdate_finance ,'type'=>'','length'=>-1),
                array('name'=>'HOVERTIME_COUNT','value'=>$this->overtime_finance ,'type'=>'','length'=>-1),
            );
        }else{
            $result = array();
        }
        return $result;
    }

}
