<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 07/02/19
 * Time: 11:13 ص
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class LV_Switches_controller extends MY_Controller{

    var $MODEL_NAME='LV_Switches_model';
    var $PAGE_URL= 'gis/lv_switches_controller/get_page';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
            $this->ID=$this->input->post('ID');
            $this->OBJECT_ID=$this->input->post('OBJECT_ID');
            $this->MV_POLE_CODE=$this->input->post('MV_POLE_CODE');
            $this->LTL_SWITCH_CODE=$this->input->post('LTL_SWITCH_CODE');
            $this->POLE_MATERIAL_ID=$this->input->post('POLE_MATERIAL_ID');
            $this->SWITCH_MATERIAL_ID=$this->input->post('SWITCH_MATERIAL_ID');
            $this->LTL_SWITCH_DIRECTION=$this->input->post('LTL_SWITCH_DIRECTION');
            $this->LTL_SWITCH_PROPERTY=$this->input->post('LTL_SWITCH_PROPERTY');
            $this->LTL_SERVICE=$this->input->post('LTL_SERVICE');
            $this->LTL_OUTDOOR_CABINET=$this->input->post('LTL_OUTDOOR_CABINET');
            $this->LTL_SWITCH_TYPE=$this->input->post('LTL_SWITCH_TYPE');
            $this->LTL_SWITCH_BASE=$this->input->post('LTL_SWITCH_BASE');
            $this->LTL_FUSES_RATE=$this->input->post('LTL_FUSES_RATE');
            $this->LTL_SWITCH_INNER_CABLE_SIZE=$this->input->post('LTL_SWITCH_INNER_CABLE_SIZE');
            $this->LTL_SWITCH_OUTER_CABLE_SIZE=$this->input->post('LTL_SWITCH_OUTER_CABLE_SIZE');
            $this->LTL_SWITCH_CONDITION=$this->input->post('LTL_SWITCH_CONDITION');
            $this->PROJECT_NO=$this->input->post('PROJECT_NO');
            $this->LTL_SWITCH_INSTALLATION_DATE=$this->input->post('LTL_SWITCH_INSTALLATION_DATE');
            $this->MV_POLE_OR_ROOM_CODE=$this->input->post('MV_POLE_OR_ROOM_CODE');
            $this->TRANSFORMER_NAME_AR=$this->input->post('TRANSFORMER_NAME_AR');
            $this->TRANSFORMER_NAME_EN=$this->input->post('TRANSFORMER_NAME_EN');
            $this->DISTRIBUTION_PANEL_CODE=$this->input->post('DISTRIBUTION_PANEL_CODE');
            $this->STREET_NAME=$this->input->post('STREET_NAME');
            $this->DISTRICT_NAME=$this->input->post('DISTRICT_NAME');
            $this->GOVERNORATE_NAME=$this->input->post('GOVERNORATE_NAME');
            $this->DOCUMENTATION_DATE=$this->input->post('DOCUMENTATION_DATE');
            $this->DATA_DOCUMENTATION_TEAM=$this->input->post('DATA_DOCUMENTATION_TEAM');
            $this->DATA_ENTRY_TEAM=$this->input->post('DATA_ENTRY_TEAM');
            $this->NOTES=$this->input->post('NOTES');
    }
    /******************index function*****************************/
    function index($page= 1,$SWITCH_MATERIAL_ID=-1){
        $data['content'] = 'LV_SWITCH';
        $data['title'] = 'قواطع الضغط المنخفط';
        $data['page']=$page;
        $data['SWITCH_MATERIAL_ID']=$SWITCH_MATERIAL_ID;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);
    }
    /*********************get_page function*******************************/
    function public_get_page($page= 1,$SWITCH_MATERIAL_ID= -1){
        $this->load->library('pagination');
        $SWITCH_MATERIAL_ID=$this->check_vars($SWITCH_MATERIAL_ID,'SWITCH_MATERIAL_ID');
        /***************************where_sql*******************************/
        $where_sql= "where 1=1";
        $where_sql.= ($SWITCH_MATERIAL_ID!= null)? " and SWITCH_MATERIAL_ID= '{$SWITCH_MATERIAL_ID}' " : '';
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
        $this->load->view('LV_SWITCH_show',$data);
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
    function get_lv_switch_info($id){

        $result= $this->{$this->MODEL_NAME}->get_lv_switch($id);
        $data['LV_Network_data'] = $result;
        $data['content'] = 'add_lv_switch';
        $data['title'] = 'عرض قواطع الضغط المنخفض';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /*****************************************************************************/
    function _postedData_info($type=null,$ID,$OBJECT_ID,$MV_POLE_CODE,$LTL_SWITCH_CODE,$POLE_MATERIAL_ID,$SWITCH_MATERIAL_ID,
                              $LTL_SWITCH_DIRECTION,$LTL_SWITCH_PROPERTY,$LTL_SERVICE,$LTL_OUTDOOR_CABINET,$LTL_SWITCH_TYPE,
                              $LTL_SWITCH_BASE,$LTL_FUSES_RATE,$LTL_SWITCH_INNER_CABLE_SIZE,$LTL_SWITCH_OUTER_CABLE_SIZE,$LTL_SWITCH_CONDITION,
                              $PROJECT_NO,$LTL_SWITCH_INSTALLATION_DATE,$MV_POLE_OR_ROOM_CODE,$TRANSFORMER_NAME_AR,$TRANSFORMER_NAME_EN,
                              $DISTRIBUTION_PANEL_CODE,$STREET_NAME,$DISTRICT_NAME,$GOVERNORATE_NAME,$DOCUMENTATION_DATE,$DATA_DOCUMENTATION_TEAM,
                             $DATA_ENTRY_TEAM,$NOTES)

    {
        $result = array(
            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'OBJECT_ID','value'=>$OBJECT_ID,'type'=>'','length'=>-1),
            array('name'=>'MV_POLE_CODE','value'=>$MV_POLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_CODE','value'=>$LTL_SWITCH_CODE,'type'=>'','length'=>-1),
            array('name'=>'POLE_MATERIAL_ID','value'=>$POLE_MATERIAL_ID,'type'=>'','length'=>-1),
            array('name'=>'SWITCH_MATERIAL_ID','value'=>$SWITCH_MATERIAL_ID,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_DIRECTION','value'=>$LTL_SWITCH_DIRECTION,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_PROPERTY','value'=>$LTL_SWITCH_PROPERTY,'type'=>'','length'=>-1),
            array('name'=>'LTL_SERVICE','value'=>$LTL_SERVICE,'type'=>'','length'=>-1),
            array('name'=>'LTL_OUTDOOR_CABINET','value'=>$LTL_OUTDOOR_CABINET,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_TYPE','value'=>$LTL_SWITCH_TYPE,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_BASE','value'=>$LTL_SWITCH_BASE,'type'=>'','length'=>-1),
            array('name'=>'LTL_FUSES_RATE','value'=>$LTL_FUSES_RATE,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_INNER_CABLE_SIZE','value'=>$LTL_SWITCH_INNER_CABLE_SIZE,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_OUTER_CABLE_SIZE','value'=>$LTL_SWITCH_OUTER_CABLE_SIZE,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_CONDITION','value'=>$LTL_SWITCH_CONDITION,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_NO','value'=>$PROJECT_NO,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH_INSTALLATION_DATE','value'=>$LTL_SWITCH_INSTALLATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'MV_POLE_OR_ROOM_CODE','value'=>$MV_POLE_OR_ROOM_CODE,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_AR','value'=>$TRANSFORMER_NAME_AR,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_EN','value'=>$TRANSFORMER_NAME_EN,'type'=>'','length'=>-1),
            array('name'=>'DISTRIBUTION_PANEL_CODE','value'=>$DISTRIBUTION_PANEL_CODE,'type'=>'','length'=>-1),
            array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),
            array('name'=>'DISTRICT_NAME','value'=>$DISTRICT_NAME,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORATE_NAME','value'=>$GOVERNORATE_NAME,'type'=>'','length'=>-1),
            array('name'=>'DOCUMENTATION_DATE','value'=>$DOCUMENTATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'DATA_DOCUMENTATION_TEAM','value'=>$DATA_DOCUMENTATION_TEAM,'type'=>'','length'=>-1),
            array('name'=>'DATA_ENTRY_TEAM','value'=>$DATA_ENTRY_TEAM,'type'=>'','length'=>-1),
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
                ('',$this->ID,$this->OBJECT_ID,$this->MV_POLE_CODE,$this->LTL_SWITCH_CODE,$this->POLE_MATERIAL_ID,$this->SWITCH_MATERIAL_ID,
                    $this->LTL_SWITCH_DIRECTION,$this->LTL_SWITCH_PROPERTY,$this->LTL_SERVICE,$this->LTL_OUTDOOR_CABINET,$this->LTL_SWITCH_TYPE,
                    $this->LTL_SWITCH_BASE,$this->LTL_FUSES_RATE,$this->LTL_SWITCH_INNER_CABLE_SIZE,$this->LTL_SWITCH_OUTER_CABLE_SIZE,
                    $this->LTL_SWITCH_CONDITION,$this->PROJECT_NO,$this->LTL_SWITCH_INSTALLATION_DATE,$this->MV_POLE_OR_ROOM_CODE,
                    $this->TRANSFORMER_NAME_AR,$this->TRANSFORMER_NAME_EN,$this->DISTRIBUTION_PANEL_CODE,$this->STREET_NAME,$this->DISTRICT_NAME,
                    $this->GOVERNORATE_NAME,$this->DOCUMENTATION_DATE,$this->DATA_DOCUMENTATION_TEAM,$this->DATA_ENTRY_TEAMNOTES)));
            //var_dump( $this->SER);
            //die();
            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            //die;
            echo intval($this->ser);
        }
    }









}






















