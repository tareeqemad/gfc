<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 08:48 ص
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_Controller extends MY_Controller{

    var $MODEL_NAME='Customers_model';
    var $PAGE_URL= 'gis/customers_controller/get_page';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
            $this->ID=$this->input->post('ID');
            $this->ID_PK=$this->input->post('ID_PK');
            $this->OBJECT_ID=$this->input->post('OBJECT_ID');
            $this->METER_NO=$this->input->post('METER_NO');
            $this->CUSTOMER_SUBSCRIPTION_NO=$this->input->post('CUSTOMER_SUBSCRIPTION_NO');
            $this->CUSTOMER_NAME=$this->input->post('CUSTOMER_NAME');
            $this->SUBSCRIPTION_TYPE=$this->input->post('SUBSCRIPTION_TYPE');
            $this->SUBSCRIPTION_METER_TYPE=$this->input->post('SUBSCRIPTION_TYPE');
            $this->SUBSCRIPTION_FEEDING_TYPE=$this->input->post('SUBSCRIPTION_FEEDING_TYPE');
            $this->SUBSCRIPTION_BUILDING_NO=$this->input->post('SUBSCRIPTION_BUILDING_NO');
            $this->CUSTOMER_PROPERTY_DESCRIPTION=$this->input->post('CUSTOMER_PROPERTY_DESCRIPTION');
            $this->CUSTOMER_CABLE_CODE=$this->input->post('CUSTOMER_CABLE_CODE');
            $this->POLE_CODE=$this->input->post('POLE_CODE');
            $this->LTL_SWITCH_CODE=$this->input->post('LTL_SWITCH_CODE');
            $this->CUSTOMER_STATUS=$this->input->post('CUSTOMER_STATUS');
            $this->TRANSFORMER_NAME_AR=$this->input->post('TRANSFORMER_NAME_AR');
            $this->TRANSFORMER_NAME_EN=$this->input->post('TRANSFORMER_NAME_EN');
            $this->STREET_NAME=$this->input->post('STREET_NAME');
            $this->DISTRICT_NUMBER=$this->input->post('DISTRICT_NUMBER');
            $this->GOVERNORATE_NAME=$this->input->post('GOVERNORATE_NAME');
            $this->DOCUMENTATION_DATE=$this->input->post('DOCUMENTATION_DATE');
            $this->DATA_DOCUMENTED_BY=$this->input->post('DATA_DOCUMENTED_BY');
            $this->DATA_ENTRY_BY=$this->input->post('DATA_ENTRY_BY');
            $this->NOTES=$this->input->post('NOTES');
            $this->SUBSCRIPTION_PHASE_CONNECTION=$this->input->post('SUBSCRIPTION_PHASE_CONNECTION');
            $this->TRANSFORMER_CODE=$this->input->post('TRANSFORMER_CODE');

    }
    /******************index function*****************************/
    function index($page= 1,$CUSTOMER_SUBSCRIPTION_NO=-1){
        $data['content'] = 'Customers';
        $data['title'] = 'المشتركين';
        $data['page']=$page;
        $data['CUSTOMER_SUBSCRIPTION_NO']=$CUSTOMER_SUBSCRIPTION_NO;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);
    }
    /*********************get_page function*******************************/
    function public_get_page($page= 1,$CUSTOMER_SUBSCRIPTION_NO= -1){
        $this->load->library('pagination');
        $CUSTOMER_SUBSCRIPTION_NO=$this->check_vars($CUSTOMER_SUBSCRIPTION_NO,'CUSTOMER_SUBSCRIPTION_NO');
        /***************************where_sql*******************************/
        $where_sql= "where 1=1";
        $where_sql.= ($CUSTOMER_SUBSCRIPTION_NO!= null)? " and CUSTOMER_SUBSCRIPTION_NO= '{$CUSTOMER_SUBSCRIPTION_NO}' " : '';
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
        $this->load->view('Customers_show',$data);
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
    function get_Customers_info($id){

        $result= $this->{$this->MODEL_NAME}->get_Customers($id);
        $data['Customers_data'] = $result;
        $data['content'] = 'add_Customers';
        $data['title'] = 'عرض بيانات المشترك';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /*****************************************************************************/
    function _postedData_info($type=null,$ID,$ID_PK,$OBJECT_ID,$METER_NO,$CUSTOMER_SUBSCRIPTION_NO,$CUSTOMER_NAME,$SUBSCRIPTION_TYPE,
                              $SUBSCRIPTION_METER_TYPE,$SUBSCRIPTION_FEEDING_TYPE,$SUBSCRIPTION_BUILDING_NO,$CUSTOMER_PROPERTY_DESCRIPTION,
                              $CUSTOMER_CABLE_CODE,$POLE_CODE,$LTL_SWITCH_CODE,$CUSTOMER_STATUS,$TRANSFORMER_NAME_AR,$TRANSFORMER_NAME_EN,
                              $STREET_NAME,$DISTRICT_NUMBER,$GOVERNORATE_NAME,$DOCUMENTATION_DATE,$DATA_DOCUMENTED_BY,$DATA_ENTRY_BY,$NOTES,
                              $SUBSCRIPTION_PHASE_CONNECTION,$TRANSFORMER_CODE)

    {
        $result = array(
            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'ID_PK','value'=>$ID_PK,'type'=>'','length'=>-1),
            array('name'=>'OBJECT_ID','value'=>$OBJECT_ID,'type'=>'','length'=>-1),
            array('name'=>'METER_NO','value'=>$METER_NO,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_SUBSCRIPTION_NO','value'=>$CUSTOMER_SUBSCRIPTION_NO,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_NAME','value'=>$CUSTOMER_NAME,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIPTION_TYPE','value'=>$SUBSCRIPTION_TYPE,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIPTION_METER_TYPE','value'=>$SUBSCRIPTION_METER_TYPE,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIPTION_FEEDING_TYPE','value'=>$SUBSCRIPTION_FEEDING_TYPE,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIPTION_BUILDING_NO','value'=>$SUBSCRIPTION_BUILDING_NO,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_PROPERTY_DESCRIPTION','value'=>$CUSTOMER_PROPERTY_DESCRIPTION,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_CABLE_CODE','value'=>$CUSTOMER_CABLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'POLE_CODE','value'=>$POLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_CODE','value'=>$LTL_SWITCH_CODE,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_STATUS','value'=>$CUSTOMER_STATUS,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_AR','value'=>$TRANSFORMER_NAME_AR,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_EN','value'=>$TRANSFORMER_NAME_EN,'type'=>'','length'=>-1),
            array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),
            array('name'=>'DISTRICT_NUMBER','value'=>$DISTRICT_NUMBER,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORATE_NAME','value'=>$GOVERNORATE_NAME,'type'=>'','length'=>-1),
            array('name'=>'DOCUMENTATION_DATE','value'=>$DOCUMENTATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'DATA_DOCUMENTED_BY','value'=>$DATA_DOCUMENTED_BY,'type'=>'','length'=>-1),
            array('name'=>'DATA_ENTRY_BY','value'=>$DATA_ENTRY_BY,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$NOTES,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIPTION_PHASE_CONNECTION','value'=>$SUBSCRIPTION_PHASE_CONNECTION,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_CODE','value'=>$TRANSFORMER_CODE,'type'=>'','length'=>-1),



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
                ('',$this->ID,$this->ID_PK,$this->OBJECT_ID,$this->METER_NO,$this->CUSTOMER_SUBSCRIPTION_NO,$this->CUSTOMER_NAME,$this->SUBSCRIPTION_TYPE,
                    $this->SUBSCRIPTION_METER_TYPE,$this->SUBSCRIPTION_FEEDING_TYPE,$this->SUBSCRIPTION_BUILDING_NO,$this->CUSTOMER_PROPERTY_DESCRIPTION,
                    $this->CUSTOMER_CABLE_CODE,$this->POLE_CODE,$this->LTL_SWITCH_CODE,$this->CUSTOMER_STATUS,$this->TRANSFORMER_NAME_AR,$this->TRANSFORMER_NAME_EN,
                    $this-> STREET_NAME,$this->DISTRICT_NUMBER,$this->GOVERNORATE_NAME,$this->DOCUMENTATION_DATE,$this->DATA_DOCUMENTED_BY,$this->DATA_ENTRY_BY,$this->NOTES,
                    $this->SUBSCRIPTION_PHASE_CONNECTION,$this->TRANSFORMER_CODE)));
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




