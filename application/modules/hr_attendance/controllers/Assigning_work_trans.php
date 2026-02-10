<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/03/19
 * Time: 12:54 م
 */

//SER, BRANCH_ID, MONTH, ADOPT, ENTRY_USER

class assigning_work_trans extends MY_Controller{

    var $MODEL_NAME= 'assigning_work_trans_model';
    var $PAGE_URL= 'hr_attendance/assigning_work_trans/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->ser= $this->input->post('ser');
        $this->branch_id= $this->input->post('branch_id');
        $this->month= $this->input->post('month');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');

        if( HaveAccess(base_url("hr_attendance/assigning_work_trans/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

    }

    function index($page= 1, $ser= -1, $branch_id= -1, $month= -1, $adopt= -1, $entry_user= -1 ){
        $data['title']= 'ترحيل تكاليف العمل';
        $data['content']='assigning_work_trans_index';
        $data['entry_user_all'] = $this->get_entry_users('ASSIGNING_WORK_TRANS_TB');

        $data['page']=$page;
        $data['ser']= $ser;
        $data['branch_id']= $branch_id;
        $data['month']= $month;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;

        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $ser= -1, $branch_id= -1, $month= -1, $adopt= -1, $entry_user= -1 ){
        $this->load->library('pagination');

        $ser= $this->check_vars($ser,'ser');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $month= $this->check_vars($month,'month');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";

        if(!$this->all_branches)
            $where_sql.= " and branch_id= {$this->user->branch} ";

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';
        $where_sql.= ($month!= null)? " and month= '01/{$month}' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' ASSIGNING_WORK_TRANS_TB '.$where_sql);
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

        $this->load->view('assigning_work_trans_page',$data);

    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        if($c_var=='adopt')
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->ser);
            }else{
                echo intval($this->ser);
            }
        }else{
            $data['content']='assigning_work_trans_show';
            $data['title']= 'ادخال ترحيل تكاليف العمل';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['user_branch'] = $this->user->branch;
            $data['all_branches'] = $this->all_branches;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( $this->branch_id=='' or $this->month=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['all_branches'] = $this->all_branches;
        $data['content']='assigning_work_trans_show';
        $data['title']='بيانات الترحيل ';
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

    private function adopt($case){
        //$this->print_error('الخاصية معطلة'); $res = 0;
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-10);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['adopt_cons'] = $this->constant_details_model->get_list(266);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_ID','value'=>$this->branch_id ,'type'=>'','length'=>-1),
            array('name'=>'MONTH','value'=>$this->month ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}