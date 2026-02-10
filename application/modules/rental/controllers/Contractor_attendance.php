<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 11:39 ص
 */

// ser,contractor_file_id,car_num,signature_date,signature_time_in,signature_time_out,signature_source,note,entry_user , signature_type

class Contractor_attendance extends MY_Controller{

    var $MODEL_NAME= 'contractor_attendance_model';
    var $PAGE_URL= 'rental/contractor_attendance/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->ser= $this->input->post('ser');
        $this->contractor_file_id= $this->input->post('contractor_file_id');
        $this->car_num= $this->input->post('car_num');
        $this->signature_date= $this->input->post('signature_date');
        $this->signature_date_2= $this->input->post('signature_date_2');
        $this->signature_time_in= $this->input->post('signature_time_in');
        $this->signature_time_out= $this->input->post('signature_time_out');
        $this->signature_source= $this->input->post('signature_source');
        $this->note= $this->input->post('note');
        $this->entry_user= $this->input->post('entry_user');
        $this->signature_type= $this->input->post('signature_type');
        $this->overtime_hours= $this->input->post('overtime_hours');

        $this->branch_id= $this->input->post('branch_id');

        if( HaveAccess(base_url("rental/contractor_attendance/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index($page= 1, $ser= -1, $contractor_file_id= -1, $car_num= -1, $signature_date= -1, $signature_date_2= -1, $signature_source= -1, $note= -1, $branch_id= -1, $entry_user= -1 ){

        $data['title']='الحضور والانصراف';
        $data['content']='contractor_attendance_index';

        $data['entry_user_all'] = $this->get_entry_users('CONTRACTOR_ATTENDANCE_TB');

        $data['page']=$page;
        $data['ser']= $ser;
        $data['contractor_file_id']= $contractor_file_id;
        $data['car_num']= $car_num;
        $data['signature_date']= $signature_date;
        $data['signature_date_2']= $signature_date_2;
        $data['signature_source']= $signature_source;
        $data['note']= $note;
        $data['branch_id']= $branch_id;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $ser= -1, $contractor_file_id= -1, $car_num= -1, $signature_date= -1, $signature_date_2= -1, $signature_source= -1, $note= -1, $branch_id= -1, $entry_user= -1 ){
        $this->load->library('pagination');

        $ser= $this->check_vars($ser,'ser');
        $contractor_file_id= $this->check_vars($contractor_file_id,'contractor_file_id');
        $car_num= $this->check_vars($car_num,'car_num');
        $signature_date= $this->check_vars($signature_date,'signature_date');
        $signature_date_2= $this->check_vars($signature_date_2,'signature_date_2');
        $signature_source= $this->check_vars($signature_source,'signature_source');
        $note= $this->check_vars($note,'note');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";

        if(!$this->all_branches)
            $where_sql.= " and SERV_RENT_PKG.GET_CONTRACTOR_BRANCH(contractor_file_id)= ".(  ($this->user->branch ==8)? 2:$this->user->branch  );

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($contractor_file_id!= null)? " and contractor_file_id= '{$contractor_file_id}' " : '';
        $where_sql.= ($car_num!= null)? " and car_num= '{$car_num}' " : '';
        $where_sql.= ($signature_date!= null)? " and signature_date >= '{$signature_date}' " : '';
        $where_sql.= ($signature_date_2!= null)? " and signature_date <= '{$signature_date_2}' " : '';
        $where_sql.= ($signature_source!= null)? " and signature_source= '{$signature_source}' " : '';
        $where_sql.= ($note!= null)? " and note like '".add_percent_sign($note)."' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';
        $where_sql.= ($branch_id!= null)? " and SERV_RENT_PKG.GET_CONTRACTOR_BRANCH(contractor_file_id)= '{$branch_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CONTRACTOR_ATTENDANCE_TB '.$where_sql);
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

        $this->load->view('contractor_attendance_page',$data);
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
            $contractor_file_id_arr= array_filter( explode(",",$this->contractor_file_id) ) ;
            foreach( $contractor_file_id_arr as $contractor_file_id){
                $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create', $contractor_file_id));
                if(intval($this->ser) <= 0){
                    $this->print_error(' لم يتم حفظ جميع البيانات '.'<br>'.$this->ser);
                }
            }
            echo intval($this->ser);
        }else{
            $data['content']='contractor_attendance_show';
            $data['title']='اضافة الحضور والانصراف';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( $this->contractor_file_id=='' or $this->signature_date=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }else if( $this->signature_time_in=='' and $this->overtime_hours=='' ){
            $this->print_error('ادخل وقت الحضور');
        }else if( $this->signature_time_in!='' and !$this->check_time($this->signature_time_in) ){
            $this->print_error('ادخل وقت حضور صحيح');
        }else if( $this->signature_time_out!='' and !$this->check_time($this->signature_time_out) ){
            $this->print_error('ادخل وقت انصراف صحيح');
        }else if( $this->signature_time_out!='' and $this->signature_type==1 and intval(str_replace(':','',$this->signature_time_in)) >= intval(str_replace(':','',$this->signature_time_out))  ){
            $this->print_error('وقت الحضور بعد وقت الانصراف');
        }
    }

    function check_time($time){ // 21:30
        return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        //$data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['can_edit'] =count($result) > 0? true : false;
        $data['action'] = $action;
        $data['content']='contractor_attendance_show';
        $data['title']='بيانات الحضور والانصراف ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData('edit',$this->contractor_file_id));
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function delete(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $res = $this->{$this->MODEL_NAME}->delete($this->ser);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['signature_source_cons'] = $this->constant_details_model->get_list(183);
        $data['signature_type_cons'] = $this->constant_details_model->get_list(192);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null, $contractor_file_id=0){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_FILE_ID','value'=>$contractor_file_id ,'type'=>'','length'=>-1),
            array('name'=>'SIGNATURE_DATE','value'=>$this->signature_date ,'type'=>'','length'=>-1),
            array('name'=>'SIGNATURE_TIME_IN','value'=>$this->signature_time_in ,'type'=>'','length'=>-1),
            array('name'=>'SIGNATURE_TIME_OUT','value'=>$this->signature_time_out ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
            array('name'=>'SIGNATURE_TYPE','value'=>$this->signature_type ,'type'=>'','length'=>-1),
            array('name'=>'OVERTIME_HOURS','value'=>$this->overtime_hours ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}