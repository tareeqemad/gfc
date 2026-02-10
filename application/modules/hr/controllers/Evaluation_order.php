<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani + mtaqia
 * Date: 08/06/16
 * Time: 01:50 م
 */

class Evaluation_order extends MY_Controller{
    var $MODEL_NAME= 'Evaluation_order_model';
    var $PAGE_URL= 'hr/evaluation_order/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        // vars
        $this->evaluation_order_id= $this->input->post('evaluation_order_id');
        $this->order_type= $this->input->post('order_type');
        $this->note= $this->input->post('note');
        $this->order_start= $this->input->post('order_start');
        $this->order_end= $this->input->post('order_end');
        $this->conditions= $this->input->post('conditions');
        $this->time_table= $this->input->post('time_table');
        $this->entry_user= $this->input->post('entry_user');
        $this->status= $this->input->post('status');
        $this->level_active= $this->input->post('level_active');
        $this->adopt_note= $this->input->post('extention_note');
        $this->grievance_start= $this->input->post('grievance_start');
        $this->grievance_end= $this->input->post('grievance_end');
        $this->order_end_extention= $this->input->post('order_end_extention');

    }

    function index($page= 1, $evaluation_order_id= -1, $order_type= -1, $note= -1, $entry_user= -1, $status= -1, $level_active= -1){
        $data['title']='أوامر التقييم';
        $data['content']='evaluation_order_index';
        $this->load->model('settings/constant_details_model');
        $data['order_type_all'] = $this->constant_details_model->get_list(118);
        $data['status_all'] = $this->constant_details_model->get_list(117);
        $data['level_active_all'] = $this->constant_details_model->get_list(119);
        $data['entry_user_all'] = $this->get_entry_users('EVALUATION_ORDER_TB');

        $data['page']=$page;
        $data['evaluation_order_id']= $evaluation_order_id;
        $data['order_type']= $order_type;
        $data['note']= $note;
        $data['entry_user']= $entry_user;
        $data['status']= $status;
        $data['level_active']= $level_active;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['help'] = $this->help;
        $data['action'] = 'edit';

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $evaluation_order_id= -1, $order_type= -1, $note= -1, $entry_user= -1, $status= -1, $level_active= -1){
        $this->load->library('pagination');

        $evaluation_order_id= $this->check_vars($evaluation_order_id,'evaluation_order_id');
        $order_type= $this->check_vars($order_type,'order_type');
        $note= $this->check_vars($note,'note');
        $entry_user= $this->check_vars($entry_user,'entry_user');
        $status= $this->check_vars($status,'status');
        $level_active= $this->check_vars($level_active,'level_active');

        $where_sql= " where 1=1 ";
        $where_sql.= ($evaluation_order_id!= null)? " and evaluation_order_id= '{$evaluation_order_id}' " : '';
        $where_sql.= ($order_type!= null)? " and order_type= '{$order_type}' " : '';
        $where_sql.= ($note!= null)? " and note like '".add_percent_sign($note)."' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';
        $where_sql.= ($status!= null)? " and status= '{$status}' " : '';
        $where_sql.= ($level_active!= null)? " and level_active= '{$level_active}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' EVALUATION_ORDER_TB '.$where_sql);
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

        $this->load->view('evaluation_order_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->evaluation_order_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->evaluation_order_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->evaluation_order_id);
            }else{
                echo intval($this->evaluation_order_id);
            }
        }else{
            $data['content']='evaluation_order_show';
            $data['title']= 'ادخال امر تقييم';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->evaluation_order_id=='' and $isEdit) or $this->order_type=='' or $this->order_start=='' or $this->order_end=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif(char_to_time($this->order_start) >= char_to_time($this->order_end)){
            $this->print_error(' ادخل فترة صحيحة ');
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1 ))
            die('get');
        $data['order_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;

        $this->load->model('settings/constant_details_model');
        $data['level_active_all'] = $this->constant_details_model->get_list(119);

        $data['content']='evaluation_order_show';
        $data['title']='بيانات أمر التقييم';

        $this->_look_ups($data);
        $this->load->view('template/template',$data);
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

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $data['order_types'] = $this->constant_details_model->get_list(118);
        $data['statuss'] = $this->constant_details_model->get_list(117);
        $data['level_actives'] = $this->constant_details_model->get_list(119);
        $data['help']=$this->help;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

    private function adopt($case){
        $res = $this->{$this->MODEL_NAME}->adopt($this->evaluation_order_id, $case, $this->adopt_note,$this->order_end_extention);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_0(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_id!=''){
            echo $this->adopt(0);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_id!=''){
            echo $this->adopt(2);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_3(){
            if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_id!=''){
                echo $this->adopt(3);
            }else{
                echo "لم يتم ارسال رقم السند";
            }
    }

    private function level($case){
        $res = $this->{$this->MODEL_NAME}->level($this->evaluation_order_id, $case, $this->grievance_start, $this->grievance_end);
        if(intval($res) <= 0){
            $this->print_error('لم يتم إجراء العملية'.'<br>'.$res);
        }
        return 1;
    }

    function level_3(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_id!=''){
            echo $this->level(3);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function level_4(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_id!=''){
            echo $this->level(4);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function level_5(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_id!=''){
            echo $this->level(5);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function level_6(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_id!=''){
            echo $this->level(6);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function level_7(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_id!=''){
            echo $this->level(7);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'EVALUATION_ORDER_ID','value'=>$this->evaluation_order_id ,'type'=>'','length'=>-1),
            array('name'=>'ORDER_TYPE','value'=>$this->order_type ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
            array('name'=>'ORDER_START','value'=>$this->order_start ,'type'=>'','length'=>-1),
            array('name'=>'ORDER_END','value'=>$this->order_end ,'type'=>'','length'=>-1),
            array('name'=>'CONDITIONS','value'=>$this->conditions ,'type'=>'','length'=>-1),
            array('name'=>'TIME_TABLE','value'=>$this->time_table ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}
