<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 18/06/20
 */

// b_ser, ser, monthly_cpayments_id, contractor_file_id, bonus_bill_id, bonus_value, note

class Monthly_payments_bonus extends MY_Controller
{
    var $MODEL_NAME = 'Monthly_payments_bonus_model';
    var $PAGE_URL = 'rental/Monthly_payments_bonus/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model($this->MODEL_NAME);

        $this->b_ser = $this->input->post('b_ser');
        $this->ser = $this->input->post('ser');
        $this->monthly_cpayments_id = $this->input->post('monthly_cpayments_id');
        $this->contractor_file_id = $this->input->post('contractor_file_id');
        $this->bonus_bill_id = $this->input->post('bonus_bill_id');
        $this->bonus_value = $this->input->post('bonus_value');
        $this->note = $this->input->post('note');
        $this->entry_user = $this->input->post('entry_user');
    }

    function index($page= 1, $b_ser= -1, $ser= -1, $monthly_cpayments_id= -1, $contractor_file_id= -1, $bonus_bill_id= -1, $bonus_value= -1, $note= -1, $entry_user= -1 ){

        $data['title']='الاضافات';
        $data['content']='monthly_payments_bonus_index';

        $data['entry_user_all'] = $this->get_entry_users('MONTHLY_PAYMENTS_BONUS_TB');

        $data['page']=$page;
        $data['b_ser']= $b_ser;
        $data['ser']= $ser;
        $data['monthly_cpayments_id']= $monthly_cpayments_id;
        $data['contractor_file_id']= $contractor_file_id;
        $data['bonus_bill_id']= $bonus_bill_id;
        $data['bonus_value']= $bonus_value;
        $data['note']= $note;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $b_ser= -1, $ser= -1, $monthly_cpayments_id= -1, $contractor_file_id= -1, $bonus_bill_id= -1, $bonus_value= -1, $note= -1, $entry_user= -1 ){
        $this->load->library('pagination');

        $b_ser= $this->check_vars($b_ser,'b_ser');
        $ser= $this->check_vars($ser,'ser');
        $monthly_cpayments_id= $this->check_vars($monthly_cpayments_id,'monthly_cpayments_id');
        $contractor_file_id= $this->check_vars($contractor_file_id,'contractor_file_id');
        $bonus_bill_id= $this->check_vars($bonus_bill_id,'bonus_bill_id');
        $bonus_value= $this->check_vars($bonus_value,'bonus_value');
        $note= $this->check_vars($note,'note');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";

        $where_sql.= ($b_ser!= null)? " and b_ser= '{$b_ser}' " : '';
        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($monthly_cpayments_id!= null)? " and monthly_cpayments_id= '{$monthly_cpayments_id}' " : '';
        $where_sql.= ($contractor_file_id!= null)? " and contractor_file_id= '{$contractor_file_id}' " : '';
        $where_sql.= ($bonus_bill_id!= null)? " and bonus_bill_id= '{$bonus_bill_id}' " : '';
        $where_sql.= ($bonus_value!= null)? " and bonus_value= '{$bonus_value}' " : '';
        $where_sql.= ($note!= null)? " and note like '".add_percent_sign($note)."' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' MONTHLY_PAYMENTS_BONUS_TB '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->list_($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('monthly_payments_bonus_page',$data);
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
            $this->b_ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->b_ser) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->b_ser);
            }else{
                echo intval($this->b_ser);
            }
        }else{
            $data['content']='monthly_payments_bonus_show';
            $data['title']='اضافة مبلغ ';
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
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='monthly_payments_bonus_show';
        $data['title']='بيانات الاضافة ';
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
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['bonus_bill_id_cons'] = $this->constant_details_model->get_list(349);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'B_SER','value'=>$this->b_ser ,'type'=>'','length'=>-1),
            array('name'=>'MONTHLY_CPAYMENTS_ID','value'=>$this->monthly_cpayments_id ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_FILE_ID','value'=>$this->contractor_file_id ,'type'=>'','length'=>-1),
            array('name'=>'BONUS_BILL_ID','value'=>$this->bonus_bill_id ,'type'=>'','length'=>-1),
            array('name'=>'BONUS_VALUE','value'=>$this->bonus_value ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}
