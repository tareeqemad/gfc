<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 19/06/22
 * Time: 12:40 ص
 */

class Bonuses extends MY_Controller{

    var $MODEL_NAME= 'Bonuses_model';
    var $PAGE_URL= 'payroll_data/Bonuses/get_item';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->workn_no_arch = $this->input->post('workn_no_arch');
        $this->gradesn_name_arch = $this->input->post('gradesn_name_arch');
        $this->allownce_arch = $this->input->post('allownce_arch');
        $this->bonus_type_arch = $this->input->post('bonus_type_arch');
        $this->start_arch_date = $this->input->post('start_arch_date');
        $this->end_arch_date = $this->input->post('end_arch_date');

    }

    function index(){
        $data['title']='العلاوات';
        $data['content']='bonuses_index';
        $data['get_final']= $this->{$this->MODEL_NAME}->get_all();

        $get_max = $this->{$this->MODEL_NAME}->get_max_no();
        foreach($get_max as $row) {
            $data['maxes']= $row['MAXCONST'];
        }
        $this->load->model('salary/constants_sal_model');
        $data['bonus_type_cons'] = $this->constants_sal_model->get_list(38);
        $this->load->view('template/template1',$data);
    }

    function get_item(){
        $data['get_final']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('bonuses_page',$data);
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
        $data['content']='bonuses_archives_index';
        $data['title']='ارشيف - العلاوات';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->workn_no_arch!= null)? " and M.W_NO= '{$this->workn_no_arch}' " : '';
        $where_sql .= isset($this->gradesn_name_arch) && $this->gradesn_name_arch !=null ? " AND  M.W_NAME like '%{$this->gradesn_name_arch}%' " :"" ;
        $where_sql.= ($this->allownce_arch!= null)? " and M.ALLOWNCE= '{$this->allownce_arch}' " : '';
        $where_sql.= ($this->bonus_type_arch!= null)? " and M.TYPE= '{$this->bonus_type_arch}' " : '';
        $where_sql.= ($this->start_arch_date!= null or $this->end_arch_date!= null)? " and TRUNC(GFC_REF_DATE) between nvl('{$this->start_arch_date}','01/01/1000') and nvl('{$this->end_arch_date}','01/01/3000') " : '';

        $config['base_url'] = base_url('payroll_data/Bonuses/get_page');
        $count_rs = $this->get_table_count('WORKN_ARCH_TB  M'.$where_sql);
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
        $this->load->view('bonuses_archives_page',$data);
    }

    function _look_ups(&$data){

        $this->load->model('root/Rmodel');
        $this->load->model('salary/constants_sal_model');
        $data['bonus_type_cons'] = $this->constants_sal_model->get_list(38);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'W_NO','value'=>$this->input->post('workn_no') ,'type'=>'','length'=>-1),
            array('name'=>'W_NAME','value'=>$this->input->post('gradesn_name') ,'type'=>'','length'=>-1),
            array('name'=>'ALLOWNCE','value'=>$this->input->post('allownce') ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->input->post('notes') ,'type'=>'','length'=>-1),
            array('name'=>'TYPE','value'=>$this->input->post('bonus_type') ,'type'=>'','length'=>-1)
        );
        return $result;
    }

}
