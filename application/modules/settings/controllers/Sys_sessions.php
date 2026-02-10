<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 31/01/15
 * Time: 04:46 م
 */

class Sys_sessions extends MY_Controller{

    var $MODEL_NAME= 'sys_sessions_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->user_id= intval($this->input->post('user_id'));
        $this->branch= intval($this->input->post('branch'));
        $this->date_from= $this->input->post('date_from');
        $this->date_to= $this->input->post('date_to');
        $this->status= $this->input->post('status');
    }

    function index(){
        if($this->user->id!=111 and $this->user->id!=584 and $this->user->id!=906)
            die();
        $data['title']='المتواجدين الآن';
        $data['content']='sys_sessions_index';
        $data['all_users'] = $this->get_entry_users('SYS_SESSIONS_TB','a.user_id= u.id');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->view('template/template',$data);
    }

    function get_page(){
        if($this->user->id!=111 and $this->user->id!=584 and $this->user->id!=906)
            die();
        $this->user_id= $this->user_id ==0 ? null : $this->user_id;
        $this->branch= $this->branch ==0 ? null : $this->branch;
        $this->date_from= $this->date_from ==0 ? null : $this->date_from;
        $this->date_to= $this->date_to ==0 ? null : $this->date_to;

        if (!$this->input->is_ajax_request()){
            $this->date_from = date('d/m/Y');
            $this->date_to = date('d/m/Y');
        }

        $where_sql= ' ';
        $where_sql.= ($this->user_id!= null)? " and s.user_id= '{$this->user_id}' " : '';
        $where_sql.= ($this->branch!= null)? " and u.branch= '{$this->branch}' " : '';
        $where_sql.= ($this->date_from!= null)? " and TRUNC(s.last_activity,'dd') >= '{$this->date_from}' " : '';
        $where_sql.= ($this->date_to!= null)? " and TRUNC(s.last_activity,'dd') <= '{$this->date_to}' " : '';
        $where_sql.= ' order by s.last_activity desc ';

        $data['get_list']= $this->{$this->MODEL_NAME}->get_list($where_sql);
        $this->load->view('sys_sessions_page',$data);
    }

    function edit_status(){
        if($this->user->id!=111 and $this->user->id!=584)
            die();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->user_id!='' and $this->status!=''){
            $res = $this->{$this->MODEL_NAME}->edit_status($this->user_id, $this->status);
            if(intval($res) <= 0){
                $this->print_error('لم تتم العملية'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال البيانات";
    }

    function public_check_session(){
        $user = $this->session->userdata('user_data');

        if(isset($user)){
            echo 1;
        }else{
            echo 0;
        }
    }

}