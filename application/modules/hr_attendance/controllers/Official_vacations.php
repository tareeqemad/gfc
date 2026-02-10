<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani + ljasser
 * Date: 03/11/18
 * Time: 11:13 ص
 */

class official_vacations extends MY_Controller{
    var $MODEL_NAME= 'official_vacations_model';
    var $PAGE_URL= 'hr_attendance/official_vacations/get_page';
    var $PAGE_ACT;
    /************************************************************************************/
    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->ser= $this->input->post('ser');
        $this->v_date= $this->input->post('v_date');
        $this->v_note= $this->input->post('v_note');
        $this->entry_user= $this->input->post('entry_user');
        $this->entry_date= $this->input->post('entry_date');
        $this->status= $this->input->post('status');
    }
    /*************************************************************************************/
    function index($page= 1,$ser=-1, $v_date= -1,$v_note=-1,$entry_user=-1){
        $data['title']='الاجازات الرسمية';
        $data['content']='official_vacations_index';
        $data['help']=$this->help;
        $data['entry_user_all'] = $this->get_entry_users('OFFICIAL_VACATIONS_TB');
        $data['page']=$page;
        $data['ser']=$ser;
        $data['v_date']= $v_date;
        $data['v_note']= $v_note;
        $data['entry_user']=$entry_user;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }
    /**************************************************************************************/
    function get_page($page= 1,$ser= -1,$v_date= -1,$v_note= -1,$entry_user= -1){
        $this->load->library('pagination');
        $ser=$this->check_vars($ser,'ser');
        $v_date=$this->check_vars($v_date,'v_date');
        $v_note=$this->check_vars($v_note,'v_note');
        $entry_user=$this->check_vars($entry_user,'entry_user');
        /***************where_sql******************/
        $where_sql= " where status=1 ";
        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($v_date!= null)? " and v_date= '{$v_date}' " : '';
        $where_sql.= ($v_note!= null)? " and v_note like '".add_percent_sign($v_note)."' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' OFFICIAL_VACATIONS_TB '.$where_sql);
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
        $this->load->view('official_vacations_page',$data);
    }
    /*****************************************************************************************/
    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
    /*************************************************************************************/
    function _look_ups($data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');
        add_css('jquery.dataTables.css');
    }
    /******************************************************************************************/
    function _postedData(){
        $result = array(
            array('name'=>'V_DATE','value'=>$this->v_date ,'type'=>'','length'=>-1),
            array('name'=>'V_NOTE','value'=>$this->v_note ,'type'=>'','length'=>-1),
        );
        return $result;
    }
    /******************************************************************************************/
    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData());
            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->ser);
            }
            echo intval($this->ser);
        }else{
            $data['content']='official_vacations_show';
            $data['title']='اضافة اجازة';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }
    /*****************************Status function*******************************/
    function status(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            $res = $this->{$this->MODEL_NAME}->status($this->ser);
            if(intval($res) <= 0){
                $this->print_error('error'.'<br>'.$res);
            }
            echo 1;
        }
    }

}
