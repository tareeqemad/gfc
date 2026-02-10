<?php
/****
 * Created by PhpStorm.
 * User: ljasser
 * Date: 11/02/19
 * Time: 12:46 م
 ****/
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Controller extends MY_Controller{
    var $MODEL_NAME='users_model';
    var $PAGE_URL= 'gis/user_controller/get_page';
    var $PAGE_ACT;
    /*********************construct function****************************************/
    function __construct() {
        parent::__construct();
            $this->load->model($this->MODEL_NAME);
                 $this->USER_ID=$this->input->post('USER_ID');
                $this->USER_NAME=$this->input->post('USER_NAME');
                $this->ID=$this->input->post('ID');
                $this->PASS_WORD=$this->input->post('PASS_WORD');
                $this->STATUS=$this->input->post('STATUS');
    }
    /******************index function*****************************/
    function index($page= 1,$USER_NAME= -1){
        $data['content'] = 'Add_Users';
        $data['title'] = 'مستخدمين الجيوميديا   ';
        $data['page']=$page;
        $data['USER_NAME']=$USER_NAME;

        $data['action'] = 'index';
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);
    }
   /***********************lookUps_data function****************************************/
    function _lookUps_data(&$data)
    {
        $this->load->model('settings/constant_details_model');
        $data['help'] = $this->help;
        $this->_loadDatePicker();
        add_css('datepicker3.css');
        add_js('bootstrap-datetimepicker.js');
        add_js('bootstrap.min.js');
        add_js('select2.min.js');
        add_css('jquery.dataTables.css');
        add_js('moment.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');
    }
    /**********************check_vars function*************************************/
    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
/************************get_list function***************************************/
    function public_get_page($page= 1,$USER_NAME= -1){
        $this->load->library('pagination');
        $USER_NAME=$this->check_vars($USER_NAME,'USER_NAME');
        /*******************************************WHERE_SQL*************************************************/
        $where_sql= "where 1=1";
        $where_sql.= ($USER_NAME!= null)? " and USER_NAME= '{$USER_NAME}' " : '';
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs =5;
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql,$offset,$row);
        $data['offset']=$offset+1;
        $data['page']=$page;
        $this->load->view('show_user',$data);
    }
    /*********************get_user_info function***************************************/
    function get_user_info($id){
        $result= $this->{$this->MODEL_NAME}->get_users($id);
        $data['users_data'] = $result;
        $data['content'] = 'change_password';
        $data['title'] = 'تعديل كلمة المرور';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /********************postedData_info function*****************************************/
    function _postedData_info($type=null,$ID,$PASS_WORD)
    {
        $result = array(
            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'PASS_WORD','value'=>$PASS_WORD,'type'=>'','length'=>-1),
        );

        //print_r($result);
        //die;
        if($type=='create'){
            array_shift($result);
        }
        return $result;
    }
    /***************************edit function******************************************************/
    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->{$this->MODEL_NAME}->edit(($this->_postedData_info
                ('',$this->ID,$this->PASS_WORD)));


            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            echo intval($this->ser);
        }
    }
    /*************************************************************************************/
    function _postedData(){
        $result = array(
            array('name'=>'USER_ID','value'=>$this->p_USER_ID_1 ,'type'=>'','length'=>-1),
            array('name'=>'USER_NAME','value'=>$this->p_USER_NAME_1 ,'type'=>'','length'=>-1),
            array('name'=>'PASS_WORD','value'=>$this->p_PASS_WORD_1 ,'type'=>'','length'=>-1),
        );

        return $result;
    }

    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
           $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData());
           if (intval($this->ser) <= 0) {
               $this->print_error('لم يتم الحفظ'.'<br>'.$this->ser);
           }
      echo intval($this->ser);

        }
        else
        {
            $data['content']='Add_Users';
            $data['title']='';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_lookUps_data($data);
            $this->load->view('template/template',$data);
        }
    }

    /*******************************************************/
    function adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ID!=''){
            $res = $this->{$this->MODEL_NAME}->adopt_info($this->ID,10);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo $res;
        }else
            echo "لم يتمم الاعتماد";
    }

    /*****************************unadopt function*******************************/
    function unadopt ()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ID!=''){
        $res = $this->{$this->MODEL_NAME}->adopt_info($this->ID,20);
        if(intval($res) <= 0) {
            $this->print_error('لم يتم الارجاع'.'<br>'.$res);
        }
        echo $res;
    }else
        echo "لم يتم فك الاعتماد";
    }
    /********************************End Controller***********************************************/

}

