<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 10:34 ص
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_cables_Controller extends MY_Controller{

    var $MODEL_NAME='Customer_cables_model';
    var $PAGE_URL= 'gis/customer_cables_controller/get_page';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->ID=$this->input->post('ID');
        $this->ID_PK=$this->input->post('ID_PK');
        $this->OBJECT_ID=$this->input->post('OBJECT_ID');
        $this->CABLE_TYPE=$this->input->post('CABLE_TYPE');
        $this->TRANSFORMER_NAME_AR=$this->input->post('TRANSFORMER_NAME_AR');
        $this->TRANSFORMER_NAME_EN=$this->input->post('TRANSFORMER_NAME_EN');
        $this->POLE_CODE=$this->input->post('POLE_CODE');
        $this->STREET_NAME=$this->input->post('STREET_NAME');
        $this->DISTRICT_NUMBER=$this->input->post('DISTRICT_NUMBER');
        $this->GOVERNORATE_NAME=$this->input->post('GOVERNORATE_NAME');
        $this->DOCUMENTATION_DATE=$this->input->post('DOCUMENTATION_DATE');
        $this->DATA_DOCUMENTED_BY=$this->input->post('DATA_DOCUMENTED_BY');
        $this->DATA_ENTRY_BY=$this->input->post('DATA_ENTRY_BY');
        $this->CABLE_LENGTH=$this->input->post('CABLE_LENGTH');
        $this->NOTES=$this->input->post('NOTES');

    }
    /******************index function*****************************/
    function index($page= 1,$CABLE_TYPE=-1){
        $data['content'] = 'Customer_cables';
        $data['title'] = 'كابل المشترك';
        $data['page']=$page;
        $data['CABLE_TYPE']=$CABLE_TYPE;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);
    }
    /*********************get_page function*******************************/
    function public_get_page($page= 1,$CABLE_TYPE= -1){
        $this->load->library('pagination');
        $CABLE_TYPE=$this->check_vars($CABLE_TYPE,'CABLE_TYPE');
        /***************************where_sql*******************************/
        $where_sql= "where 1=1";
        $where_sql.= ($CABLE_TYPE!= null)? " and CABLE_TYPE= '{$CABLE_TYPE}' " : '';
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
        $this->load->view('Customer_cables_show',$data);
    }
    /*****************************************************************************************/
    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
    /***************************lookUps_data function****************************/
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
    /******************************************************************************************/
    function get_Customer_cables_info($id){

        $result= $this->{$this->MODEL_NAME}->get_Customer_cables($id);
        $data['Customer_cables_data'] = $result;
        $data['content'] = 'add_Customer_cables';
        $data['title'] = 'عرض بيانات كابل المشترك';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /*****************************************************************************/
    function _postedData_info($type=null,$ID,$ID_PK,$OBJECT_ID,$CABLE_TYPE,$TRANSFORMER_NAME_AR,$TRANSFORMER_NAME_EN,
                            $POLE_CODE,$STREET_NAME,$DISTRICT_NUMBER,$GOVERNORATE_NAME,$DOCUMENTATION_DATE,$DATA_DOCUMENTED_BY,
                            $DATA_ENTRY_BY,$CABLE_LENGTH,$NOTES)

    {
        $result = array(
            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'ID_PK','value'=>$ID_PK,'type'=>'','length'=>-1),
            array('name'=>'OBJECT_ID','value'=>$OBJECT_ID,'type'=>'','length'=>-1),
            array('name'=>'CABLE_TYPE','value'=>$CABLE_TYPE,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_AR','value'=>$TRANSFORMER_NAME_AR,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_EN','value'=>$TRANSFORMER_NAME_EN,'type'=>'','length'=>-1),
            array('name'=>'POLE_CODE','value'=>$POLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),
            array('name'=>'DISTRICT_NUMBER','value'=>$DISTRICT_NUMBER,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORATE_NAME','value'=>$GOVERNORATE_NAME,'type'=>'','length'=>-1),
            array('name'=>'DOCUMENTATION_DATE','value'=>$DOCUMENTATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'DATA_DOCUMENTED_BY','value'=>$DATA_DOCUMENTED_BY,'type'=>'','length'=>-1),
            array('name'=>'DATA_ENTRY_BY','value'=>$DATA_ENTRY_BY,'type'=>'','length'=>-1),
            array('name'=>'CABLE_LENGTH','value'=>$CABLE_LENGTH,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$NOTES,'type'=>'','length'=>-1),

        );
        if($type=='create'){
            array_shift($result);
        }
        return $result;
    }
    /*********************************************************************************/
    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->{$this->MODEL_NAME}->edit(($this->_postedData_info
                ('',$this->ID,$this->ID_PK,$this->OBJECT_ID,$this->CABLE_TYPE,$this->TRANSFORMER_NAME_AR,$this->TRANSFORMER_NAME_EN,
                    $this->POLE_CODE,$this->STREET_NAME,$this->DISTRICT_NUMBER,$this->GOVERNORATE_NAME,$this->DOCUMENTATION_DATE,
                    $this->DATA_DOCUMENTED_BY,$this->DATA_ENTRY_BY,$this->CABLE_LENGTH,$this->NOTES)));
            //var_dump( $this->SER);
            //die();
            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            //die;
            echo intval($this->ser);
        }
    }
    /***************************************************************************************/









}




