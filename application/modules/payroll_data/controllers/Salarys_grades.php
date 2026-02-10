<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 19/06/22
 * Time: 08:20 ص
 */

class Salarys_grades extends MY_Controller{

    var $MODEL_NAME= 'Salarys_grades_model';
    var $PAGE_URL= 'payroll_data/Salarys_grades/get_item';

    function  __construct(){
        parent::__construct();

        $this->load->model($this->MODEL_NAME);

        $this->ser_arch = $this->input->post('ser_arch');
        $this->gradesn_name_arch = $this->input->post('gradesn_name_arch');
        $this->basic_salary_arch = $this->input->post('basic_salary_arch');
        $this->emp_type_arch = $this->input->post('emp_type_arch');
        $this->start_arch_date = $this->input->post('start_arch_date');
        $this->end_arch_date = $this->input->post('end_arch_date');
    }

    function index(){

        $data['title']='سلم الرواتب';
        $data['content']='salarys_grades_index';
        $data['get_final']= $this->{$this->MODEL_NAME}->get_all();

        $get_max = $this->{$this->MODEL_NAME}->get_max_no();
        foreach($get_max as $row) {
            $data['maxes']= $row['MAXCONST'];
        }
        $this->load->model('salary/constants_sal_model');
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(37);

        $this->load->view('template/template1',$data);

    }

    function get_item(){
        $data['get_final']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('salarys_grades_page',$data);
    }

    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function create(){
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function edit(){
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function delete(){
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if(is_array($id)){
            foreach($id as $val){
                $msg= $this->{$this->MODEL_NAME}->delete($val);
            }
        }else{
            $msg= $this->{$this->MODEL_NAME}->delete($id);
        }

        if($msg == 1){
            echo  modules::run($this->PAGE_URL);
        }else{
            $this->print_error_msg($msg);
        }
    }

    function archives()
    {
        $data['content']='salarys_grades_archives_index';
        $data['title']='ارشيف - سلم الرواتب';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->ser_arch!= null)? " and M.NO= '{$this->ser_arch}' " : '';
        $where_sql .= isset($this->gradesn_name_arch) && $this->gradesn_name_arch !=null ? " AND  M.NAME like '%{$this->gradesn_name_arch}%' " :"" ;
        $where_sql.= ($this->basic_salary_arch!= null)? " and M.BASIC_SALARY= '{$this->basic_salary_arch}' " : '';
        $where_sql.= ($this->emp_type_arch!= null)? " and M.TYPE= '{$this->emp_type_arch}' " : '';
        $where_sql.= ($this->start_arch_date!= null or $this->end_arch_date!= null)? " and TRUNC(GFC_REF_DATE) between nvl('{$this->start_arch_date}','01/01/1000') and nvl('{$this->end_arch_date}','01/01/3000') " : '';

        $config['base_url'] = base_url('payroll_data/Bonuses/get_page');
        $count_rs = $this->get_table_count('GRADESN_ARCH_TB  M'.$where_sql);
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_archives($where_sql ,$offset ,$row );
        $this->load->view('salarys_grades_archives_page',$data);
    }

    function _look_ups(&$data){

        $this->load->model('root/Rmodel');
        $this->load->model('salary/constants_sal_model');
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(37);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'NO','value'=>$this->input->post('ser') ,'type'=>'','length'=>-1),
            array('name'=>'NAME','value'=>$this->input->post('gradesn_name') ,'type'=>'','length'=>-1),
            array('name'=>'BASIC_SALARY','value'=>$this->input->post('basic_salary') ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->input->post('notes') ,'type'=>'','length'=>-1),
            array('name'=>'TYPE','value'=>$this->input->post('emp_type') ,'type'=>'','length'=>-1)
        );

        return $result;
    }

}
