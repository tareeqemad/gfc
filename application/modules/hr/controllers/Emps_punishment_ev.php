<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/01/21
 * Time: 09:30 ص
 */

class Emps_punishment_ev extends MY_Controller{

    var $MODEL_NAME= 'Emps_punishment_ev_model';
    var $PAGE_URL= 'hr/Emps_punishment_ev/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->ser = $this->input->post('ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->punishment_type = $this->input->post('punishment_type');
        $this->punishment_year = $this->input->post('punishment_year');
        $this->evaluation_discount = $this->input->post('evaluation_discount');
        $this->notes = $this->input->post('notes');
        $this->adopt = $this->input->post('adopt');

    }

    function index()
    {
        $data['content']='emps_punishment_ev_index';
        $data['title']='عقوبات الموظفين';
        $data['isCreate']= true;
        //$data['hidden']= 'hidden';
        //$data['checked']= '';
        $data['action'] = 'index';
        $data['emp_no_selected'] = $this->user->emp_no;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }


    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";
        $where_sql.= ($this->emp_no!= null)? " and EMP_NO= '{$this->emp_no}' " : '';
        $where_sql.= ($this->punishment_type!= null)? " and PUNISHMENT_TYPE= '{$this->punishment_type}' " : '';
        $where_sql.= ($this->punishment_year!= null)? " and PUNISHMENT_YEAR= '{$this->punishment_year}' " : '';
        $where_sql.= ($this->adopt!= null)? " and ADOPT= '{$this->adopt}' " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('EMPS_PUNISHMENT_EV_TB'.$where_sql);
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

        $this->load->view('emps_punishment_ev_page',$data);

    }

    function create(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser) <= 0){
                $this->print_error($this->ser);
            }else{
                echo intval($this->ser);
            }
        }

    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
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
            echo "لم يتم ارسال رقم السند";
    }

    function get_id(){
        $id = $this->input->post('ser');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }


    function _look_ups(&$data){

        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['punishment_type'] = $this->constant_details_model->get_list(409);
        $data['adopt'] = $this->constant_details_model->get_list(410);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( '', 'hr_admin' );
        //$data['help'] = $this->help;

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=> $this->emp_no,'type'=>'','length'=>-1),
            array('name'=>'PUNISHMENT_TYPE','value'=> $this->punishment_type ,'type'=>'','length'=>-1),
            array('name'=>'PUNISHMENT_YEAR','value'=>$this->punishment_year,'type'=>'','length'=>-1),
            array('name'=>'EVALUATION_DISCOUNT','value'=> $this->evaluation_discount ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
        );

        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}