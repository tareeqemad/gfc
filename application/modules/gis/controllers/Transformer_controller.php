<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 02/02/19
 * Time: 12:06 م
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class transformer_controller extends MY_Controller{

    var $MODEL_NAME='transformer_model';
    var $PAGE_URL= 'gis/transformer_controller/get_page';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->ID  = $this->input->post('ID');
        //$this->SHAPE=$this->input->post('SHAPE');
        $this->TR_RATING_KVA=$this->input->post('TR_RATING_KVA');
        $this->POLE_MATERIAL_ID=$this->input->post('POLE_MATERIAL_ID');
        $this->TRANS_MATERIAL_ID=$this->input->post('TRANS_MATERIAL_ID');
        $this->MV_POLE_CODE=$this->input->post('MV_POLE_CODE');
        $this->TRANSFORMER_CODE=$this->input->post('TRANSFORMER_CODE');
        $this->RMU_CODE=$this->input->post('RMU_CODE');
        $this->MV_SWITCH_CODE=$this->input->post('MV_SWITCH_CODE');
        $this->TRANSFORMER_NAME_AR=$this->input->post('TRANSFORMER_NAME_AR');
        $this->TRANSFORMER_NAME_EN=$this->input->post('TRANSFORMER_NAME_EN');
        $this->GOVERNORATE_NAME=$this->input->post('GOVERNORATE_NAME');
        $this->MAX_LOAD_PERCENTAGE=$this->input->post('MAX_LOAD_PERCENTAGE');
        $this->HOUSEHOLD_PERCENTAGE=$this->input->post('HOUSEHOLD_PERCENTAGE');
        $this->COMMERCIAL_PERCENTAGE=$this->input->post('COMMERCIAL_PERCENTAGE');
        $this->INDUSTRIAL_PERCENTAGE=$this->input->post('INDUSTRIAL_PERCENTAGE');
        $this->AGRICULTURE_PERCENTAGE=$this->input->post('AGRICULTURE_PERCENTAGE');
        $this->INTITUTION_PERCENTAGE=$this->input->post('INTITUTION_PERCENTAGE');
        $this->TURNS_RATIO=$this->input->post('TURNS_RATIO');
        $this->IMPEDANCE_PERCENTAGE=$this->input->post('IMPEDANCE_PERCENTAGE');
        $this->X_R_RATIO=$this->input->post('X_R_RATIO');
        $this->FEEDER_PLAN=$this->input->post('FEEDER_PLAN');
        $this->TR_CHANGER_COUNT=$this->input->post('TR_CHANGER_COUNT');
        $this->TR_MANUFACTURER=$this->input->post('TR_MANUFACTURER');
        $this->TR_SERIAL_NO=$this->input->post('TR_SERIAL_NO');
        $this->TR_DATE_OF_MANUFACTURE=$this->input->post('TR_DATE_OF_MANUFACTURE');
        $this->TR_COUNTRY_OF_ORIGIN=$this->input->post('TR_COUNTRY_OF_ORIGIN');
        $this->TAP_CHANGER_PLUS=$this->input->post('TAP_CHANGER_PLUS');
        $this->TAP_CHANGER_MINUS=$this->input->post('TAP_CHANGER_MINUS');
        $this->CURRENT_TAP_CHANGER=$this->input->post('CURRENT_TAP_CHANGER');
        $this->FUSE_HOLDER_TYPE=$this->input->post('FUSE_HOLDER_TYPE');
        $this->FUSE_HOLDER_INSULATOR_TYP=$this->input->post('FUSE_HOLDER_INSULATOR_TYP');
        $this->DROP_OUT=$this->input->post('DROP_OUT');
        $this->TR_POSITION=$this->input->post('TR_POSITION');
        $this->TR_DIRECTION=$this->input->post('TR_DIRECTION');
        $this->TR_OIL_TYPE=$this->input->post('TR_OIL_TYPE');
        $this->TR_PRIMARY_VOLTAGE=$this->input->post('TR_PRIMARY_VOLTAGE');
        $this->TR_SECONDARY_VOLTAGE=$this->input->post('TR_SECONDARY_VOLTAGE');
        $this->TR_PROPERTY=$this->input->post('TR_PROPERTY');
        $this->TYPE_OF_FEEDING_CONDUCTOR=$this->input->post('TYPE_OF_FEEDING_CONDUCTOR');
        $this->MEASURED_PRIMARY_VOLTAGE=$this->input->post('MEASURED_PRIMARY_VOLTAGE');
        $this->MEASURED_SECONDARY_VOLTAGE=$this->input->post('MEASURED_SECONDARY_VOLTAGE');
        $this->MEASURED_POWER_FACTOR=$this->input->post('MEASURED_POWER_FACTOR');
        $this->ACTIVE_SURGE_ARRESTERS=$this->input->post('ACTIVE_SURGE_ARRESTERS');
        $this->TR_BODY_EARTHING=$this->input->post('TR_BODY_EARTHING');
        $this->TR_BODY_CONDITION=$this->input->post('TR_BODY_CONDITION');
        $this->MAINTAINANCE_NUMBER=$this->input->post('MAINTAINANCE_NUMBER');
        $this->TR_NEUTRAL_EARTHING=$this->input->post('TR_NEUTRAL_EARTHING');
        $this->MEASURED_ERTHING_RESISTANCE=$this->input->post('MEASURED_ERTHING_RESISTANCE');
        $this->EARTHING_RESIS_MEASUR_DATE=$this->input->post('EARTHING_RESIS_MEASUR_DATE');
        $this->INSTALLATION_DATE=$this->input->post('INSTALLATION_DATE');
        $this->WORK_ORDER_ID=$this->input->post('WORK_ORDER_ID');
        $this->REGISTRATON_DATA=$this->input->post('REGISTRATON_DATA');
        $this->WORK_ORDER_DATA=$this->input->post('WORK_ORDER_DATA');
        $this->STREET_NAME=$this->input->post('STREET_NAME');
        $this->MV_FUSES_TYPE=$this->input->post('MV_FUSES_TYPE');
        $this->DISTRICT_NO=$this->input->post('DISTRICT_NO');
        $this->ENTRY_USER=$this->input->post('ENTRY_USER');
        $this->DOCUMENTATION_DATE=$this->input->post('DOCUMENTATION_DATE');
        $this->DATA_DOCUMENTATION_TEAM=$this->input->post('DATA_DOCUMENTATION_TEAM');
        $this->DATA_ENTRY_TEAM=$this->input->post('DATA_ENTRY_TEAM');
        $this->PHOTO_PATH=$this->input->post('PHOTO_PATH');
        $this->NOTES=$this->input->post('NOTES');

    }
    /**************************************function index********************************************************/

    function index($page= 1,$ID=-1,$TR_RATING_KVA=-1,$POLE_MATERIAL_ID=-1,$TRANS_MATERIAL_ID=-1,
                  $MV_POLE_CODE=-1,$TRANSFORMER_CODE=-1,$RMU_CODE=-1,$MV_SWITCH_CODE=-1,$TRANSFORMER_NAME_AR=-1,
                  $TRANSFORMER_NAME_EN=-1,$GOVERNORATE_NAME=-1,$MAX_LOAD_PERCENTAGE=-1,$HOUSEHOLD_PERCENTAGE=-1,$COMMERCIAL_PERCENTAGE=-1,
                  $INDUSTRIAL_PERCENTAGE=-1,$AGRICULTURE_PERCENTAGE=-1,$INTITUTION_PERCENTAGE=-1,$TURNS_RATIO=-1,$IMPEDANCE_PERCENTAGE=-1,
                  $X_R_RATIO=-1,$FEEDER_PLAN=-1,$TR_CHANGER_COUNT=-1,$TR_MANUFACTURER=-1,$TR_SERIAL_NO=-1,$TR_DATE_OF_MANUFACTURE=-1,
                  $TR_COUNTRY_OF_ORIGIN=-1,$TAP_CHANGER_PLUS=-1,$TAP_CHANGER_MINUS=-1,$CURRENT_TAP_CHANGER=-1,$FUSE_HOLDER_TYPE=-1,
                  $FUSE_HOLDER_INSULATOR_TYP=-1,$DROP_OUT=-1,$MV_FUSES_TYPE=-1,$TR_POSITION=-1,$TR_DIRECTION=-1,$TR_OIL_TYPE=-1,$TR_PRIMARY_VOLTAGE=-1,
                  $TR_SECONDARY_VOLTAGE=-1,$TR_PROPERTY=-1,$TYPE_OF_FEEDING_CONDUCTOR=-1,$MEASURED_PRIMARY_VOLTAGE=-1,$MEASURED_SECONDARY_VOLTAGE=-1,
                  $MEASURED_POWER_FACTOR=-1,$TR_BODY_EARTHING=-1,$TR_BODY_CONDITION=-1,$MAINTAINANCE_NUMBER=-1,$TR_NEUTRAL_EARTHING=-1,
                  $MEASURED_ERTHING_RESISTANCE=-1,$EARTHING_RESIS_MEASUR_DATE=-1,$INSTALLATION_DATE=-1,$WORK_ORDER_ID=-1,$REGISTRATON_DATA=-1,
                  $WORK_ORDER_DATA=-1,$STREET_NAME=-1,$DISTRICT_NO=-1,$ENTRY_USER=-1,$DOCUMENTATION_DATE=-1,$DATA_DOCUMENTATION_TEAM=-1,
                  $DATA_ENTRY_TEAM=-1,$PHOTO_PATH=-1,$NOTES=-1){
        $data['content'] = 'transformer';
        $data['title'] = ' المحولات';
        $data['page']=$page;
        $data['ID']=$ID;
        $data['TR_RATING_KVA']=$TR_RATING_KVA;
        $data['POLE_MATERIAL_ID']=$POLE_MATERIAL_ID;
        $data['TRANS_MATERIAL_ID']=$TRANS_MATERIAL_ID;
        $data['MV_POLE_CODE']=$MV_POLE_CODE;
        $data['TRANSFORMER_CODE']=$TRANSFORMER_CODE;
        $data['RMU_CODE']=$RMU_CODE;
        $data['MV_SWITCH_CODE']=$MV_SWITCH_CODE;
        $data['TRANSFORMER_NAME_AR']=$TRANSFORMER_NAME_AR;
        $data['TRANSFORMER_NAME_EN']=$TRANSFORMER_NAME_EN;
        $data['GOVERNORATE_NAME']=$GOVERNORATE_NAME;
        $data['MAX_LOAD_PERCENTAGE']=$MAX_LOAD_PERCENTAGE;
        $data['HOUSEHOLD_PERCENTAGE']=$HOUSEHOLD_PERCENTAGE;
        $data['COMMERCIAL_PERCENTAGE']=$COMMERCIAL_PERCENTAGE;
        $data['INDUSTRIAL_PERCENTAGE']=$INDUSTRIAL_PERCENTAGE;
        $data['AGRICULTURE_PERCENTAGE']=$AGRICULTURE_PERCENTAGE;
        $data['INTITUTION_PERCENTAGE']=$INTITUTION_PERCENTAGE;
        $data['TURNS_RATIO']=$TURNS_RATIO;
        $data['IMPEDANCE_PERCENTAGE']=$IMPEDANCE_PERCENTAGE;
        $data['X_R_RATIO']=$X_R_RATIO;
        $data['FEEDER_PLAN']=$FEEDER_PLAN;
        $data['TR_CHANGER_COUNT']=$TR_CHANGER_COUNT;
        $data['TR_MANUFACTURER']=$TR_MANUFACTURER;
        $data['TR_SERIAL_NO']=$TR_SERIAL_NO;
        $data['TR_DATE_OF_MANUFACTURE']=$TR_DATE_OF_MANUFACTURE;
        $data['TR_COUNTRY_OF_ORIGIN']=$TR_COUNTRY_OF_ORIGIN;
        $data['TAP_CHANGER_PLUS']=$TAP_CHANGER_PLUS;
        $data['TAP_CHANGER_MINUS']=$TAP_CHANGER_MINUS;
        $data['CURRENT_TAP_CHANGER']=$CURRENT_TAP_CHANGER;
        $data['FUSE_HOLDER_TYPE']=$FUSE_HOLDER_TYPE;
        $data['FUSE_HOLDER_INSULATOR_TYP']=$FUSE_HOLDER_INSULATOR_TYP;
        $data['DROP_OUT']=$DROP_OUT;
        $data['MV_FUSES_TYPE']=$MV_FUSES_TYPE;
        $data['TR_POSITION']=$TR_POSITION;
        $data['TR_DIRECTION']=$TR_DIRECTION;
        $data['TR_OIL_TYPE']=$TR_OIL_TYPE;
        $data['TR_PRIMARY_VOLTAGE']=$TR_PRIMARY_VOLTAGE;
        $data['TR_SECONDARY_VOLTAGE']=$TR_SECONDARY_VOLTAGE;
        $data['TR_PROPERTY']=$TR_PROPERTY;
        $data['TYPE_OF_FEEDING_CONDUCTOR']=$TYPE_OF_FEEDING_CONDUCTOR;
        $data['MEASURED_PRIMARY_VOLTAGE']=$MEASURED_PRIMARY_VOLTAGE;
        $data['MEASURED_SECONDARY_VOLTAGE']=$MEASURED_SECONDARY_VOLTAGE;
        $data['MEASURED_POWER_FACTOR']=$MEASURED_POWER_FACTOR;
        $data['TR_BODY_EARTHING']=$TR_BODY_EARTHING;
        $data['TR_BODY_CONDITION']=$TR_BODY_CONDITION;
        $data['MAINTAINANCE_NUMBER']=$MAINTAINANCE_NUMBER;
        $data['TR_NEUTRAL_EARTHING']=$TR_NEUTRAL_EARTHING;
        $data['MEASURED_ERTHING_RESISTANCE']=$MEASURED_ERTHING_RESISTANCE;
        $data['EARTHING_RESIS_MEASUR_DATE']=$EARTHING_RESIS_MEASUR_DATE;
        $data['INSTALLATION_DATE']=$INSTALLATION_DATE;
        $data['WORK_ORDER_ID']=$WORK_ORDER_ID;
        $data['REGISTRATON_DATA']=$REGISTRATON_DATA;
        $data['WORK_ORDER_DATA']=$WORK_ORDER_DATA;
        $data['STREET_NAME']=$STREET_NAME;
        $data['DISTRICT_NO']=$DISTRICT_NO;
        $data['ENTRY_USER']=$ENTRY_USER;
        $data['DOCUMENTATION_DATE']=$DOCUMENTATION_DATE;
        $data['DATA_DOCUMENTATION_TEAM']=$DATA_DOCUMENTATION_TEAM;
        $data['DATA_ENTRY_TEAM']=$DATA_ENTRY_TEAM;
        $data['PHOTO_PATH']=$PHOTO_PATH;
        $data['NOTES']=$NOTES;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);

    }
    /******************************function check_vars***********************************************************/
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
    /*********************************************FUNCTION GET_PAGE*******************************************************/
     function public_get_page($page= 1,$TRANS_MATERIAL_ID= -1){
        $this->load->library('pagination');
        $TRANS_MATERIAL_ID=$this->check_vars($TRANS_MATERIAL_ID,'TRANS_MATERIAL_ID');
   /*******************************************WHERE_SQL*************************************************/
        $where_sql= "where 1=1";
        $where_sql.= ($TRANS_MATERIAL_ID!= null)? " and TRANS_MATERIAL_ID= '{$TRANS_MATERIAL_ID}' " : '';
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
        $this->load->view('transformer_show',$data);
    }
    /**********************************************************************************************/
    function get_transformer_info($id){

        $result= $this->{$this->MODEL_NAME}->get_transformer($id);
        $data['transformer_data'] = $result;
        $data['content'] = 'add_NewTransformer';
        $data['title'] = ' عرض المحول';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
	/*****************************************************************************/
	 public function public_test(){

        /*$result= $this->{$this->MODEL_NAME}->get_transformer($id);
        $data['transformer_data'] = $result;
        $data['content'] = 'add_NewTransformer';
        $data['title'] = ' عرض المحول';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);*/
		$data['title'] = $this->input->post('title');;
		$data['id'] = $this->input->post('id');;
        echo $this->load->view('test',$data);
    }
    /***************************************************************************/
    function _postedData_info($type=null,$ID,$TR_RATING_KVA,$POLE_MATERIAL_ID,$TRANS_MATERIAL_ID,$MV_POLE_CODE,$TRANSFORMER_CODE,
                              $RMU_CODE,$MV_SWITCH_CODE,$TRANSFORMER_NAME_AR,$TRANSFORMER_NAME_EN,$GOVERNORATE_NAME,$MAX_LOAD_PERCENTAGE,
                              $HOUSEHOLD_PERCENTAGE,$COMMERCIAL_PERCENTAGE,$INDUSTRIAL_PERCENTAGE,$AGRICULTURE_PERCENTAGE,$INTITUTION_PERCENTAGE,
                              $TURNS_RATIO,$IMPEDANCE_PERCENTAGE,$X_R_RATIO,$FEEDER_PLAN,$TR_CHANGER_COUNT,$TR_MANUFACTURER,$TR_SERIAL_NO,
                              $TR_DATE_OF_MANUFACTURE,$TR_COUNTRY_OF_ORIGIN,$TAP_CHANGER_PLUS,$TAP_CHANGER_MINUS,$CURRENT_TAP_CHANGER,$FUSE_HOLDER_TYPE,
                              $FUSE_HOLDER_INSULATOR_TYP,$DROP_OUT,$MV_FUSES_TYPE,$TR_POSITION,$TR_DIRECTION,$TR_OIL_TYPE,$TR_PRIMARY_VOLTAGE,
                              $TR_SECONDARY_VOLTAGE,$TR_PROPERTY,$TYPE_OF_FEEDING_CONDUCTOR,$MEASURED_PRIMARY_VOLTAGE,$MEASURED_SECONDARY_VOLTAGE,
                              $MEASURED_POWER_FACTOR,$ACTIVE_SURGE_ARRESTERS,$TR_BODY_EARTHING,$TR_BODY_CONDITION,$MAINTAINANCE_NUMBER,$TR_NEUTRAL_EARTHING,
                              $MEASURED_ERTHING_RESISTANCE,$EARTHING_RESIS_MEASUR_DATE,$INSTALLATION_DATE,$WORK_ORDER_ID,$REGISTRATON_DATA,
                              $WORK_ORDER_DATA,$STREET_NAME,$DISTRICT_NO,$ENTRY_USER,$DOCUMENTATION_DATE,$DATA_DOCUMENTATION_TEAM,
                              $DATA_ENTRY_TEAM,$PHOTO_PATH,$NOTES)
    {
        $result = array(
            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'TR_RATING_KVA','value'=>$TR_RATING_KVA,'type'=>'','length'=>-1),
            array('name'=>'POLE_MATERIAL_ID','value'=>$POLE_MATERIAL_ID,'type'=>'','length'=>-1),
            array('name'=>'TRANS_MATERIAL_ID','value'=>$TRANS_MATERIAL_ID,'type'=>'','length'=>-1),
            array('name'=>'MV_POLE_CODE','value'=>$MV_POLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_CODE','value'=>$TRANSFORMER_CODE,'type'=>'','length'=>-1),
            array('name'=>'RMU_CODE','value'=>$RMU_CODE,'type'=>'','length'=>-1),
            array('name'=>'MV_SWITCH_CODE','value'=>$MV_SWITCH_CODE,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_AR','value'=>$TRANSFORMER_NAME_AR,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_EN','value'=>$TRANSFORMER_NAME_EN,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORATE_NAME','value'=>$GOVERNORATE_NAME,'type'=>'','length'=>-1),
            array('name'=>'MAX_LOAD_PERCENTAGE','value'=>$MAX_LOAD_PERCENTAGE,'type'=>'','length'=>-1),
            array('name'=>'HOUSEHOLD_PERCENTAGE','value'=>$HOUSEHOLD_PERCENTAGE,'type'=>'','length'=>-1),
            array('name'=>'COMMERCIAL_PERCENTAGE','value'=>$COMMERCIAL_PERCENTAGE,'type'=>'','length'=>-1),
            array('name'=>'INDUSTRIAL_PERCENTAGE','value'=>$INDUSTRIAL_PERCENTAGE,'type'=>'','length'=>-1),
            array('name'=>'AGRICULTURE_PERCENTAGE','value'=>$AGRICULTURE_PERCENTAGE,'type'=>'','length'=>-1),
            array('name'=>'INTITUTION_PERCENTAGE','value'=>$INTITUTION_PERCENTAGE,'type'=>'','length'=>-1),
            array('name'=>'TURNS_RATIO','value'=>$TURNS_RATIO,'type'=>'','length'=>-1),
            array('name'=>'IMPEDANCE_PERCENTAGE','value'=>$IMPEDANCE_PERCENTAGE,'type'=>'','length'=>-1),
            array('name'=>'X_R_RATIO','value'=>$X_R_RATIO,'type'=>'','length'=>-1),
            array('name'=>'FEEDER_PLAN','value'=>$FEEDER_PLAN,'type'=>'','length'=>-1),
            array('name'=>'TR_CHANGER_COUNT','value'=>$TR_CHANGER_COUNT,'type'=>'','length'=>-1),
            array('name'=>'TR_MANUFACTURER','value'=>$TR_MANUFACTURER,'type'=>'','length'=>-1),
            array('name'=>'TR_SERIAL_NO','value'=>$TR_SERIAL_NO,'type'=>'','length'=>-1),
            array('name'=>'TR_DATE_OF_MANUFACTURE','value'=>$TR_DATE_OF_MANUFACTURE,'type'=>'','length'=>-1),
            array('name'=>'TR_COUNTRY_OF_ORIGIN','value'=>$TR_COUNTRY_OF_ORIGIN,'type'=>'','length'=>-1),
          array('name'=>'TAP_CHANGER_PLUS','value'=>$TAP_CHANGER_PLUS,'type'=>'','length'=>-1),
            array('name'=>'TAP_CHANGER_MINUS','value'=>$TAP_CHANGER_MINUS,'type'=>'','length'=>-1),
             array('name'=>'CURRENT_TAP_CHANGER','value'=>$CURRENT_TAP_CHANGER,'type'=>'','length'=>-1),
             array('name'=>'FUSE_HOLDER_TYPE','value'=>$FUSE_HOLDER_TYPE,'type'=>'','length'=>-1),
             array('name'=>'FUSE_HOLDER_INSULATOR_TYP','value'=>$FUSE_HOLDER_INSULATOR_TYP,'type'=>'','length'=>-1),
             array('name'=>'DROP_OUT','value'=>$DROP_OUT,'type'=>'','length'=>-1),
            array('name'=>'MV_FUSES_TYPE','value'=>$MV_FUSES_TYPE,'type'=>'','length'=>-1),
             array('name'=>'TR_POSITION','value'=>$TR_POSITION,'type'=>'','length'=>-1),
             array('name'=>'TR_DIRECTION','value'=>$TR_DIRECTION,'type'=>'','length'=>-1),
            array('name'=>'TR_OIL_TYPE','value'=>$TR_OIL_TYPE,'type'=>'','length'=>-1),
             array('name'=>'TR_PRIMARY_VOLTAGE','value'=>$TR_PRIMARY_VOLTAGE,'type'=>'','length'=>-1),
             array('name'=>'TR_SECONDARY_VOLTAGE','value'=>$TR_SECONDARY_VOLTAGE,'type'=>'','length'=>-1),
             array('name'=>'TR_PROPERTY','value'=>$TR_PROPERTY,'type'=>'','length'=>-1),
            array('name'=>'TYPE_OF_FEEDING_CONDUCTOR','value'=>$TYPE_OF_FEEDING_CONDUCTOR,'type'=>'','length'=>-1),
             array('name'=>'MEASURED_PRIMARY_VOLTAGE','value'=>$MEASURED_PRIMARY_VOLTAGE,'type'=>'','length'=>-1),
             array('name'=>'MEASURED_SECONDARY_VOLTAGE','value'=>$MEASURED_SECONDARY_VOLTAGE,'type'=>'','length'=>-1),
             array('name'=>'MEASURED_POWER_FACTOR','value'=>$MEASURED_POWER_FACTOR,'type'=>'','length'=>-1),
             array('name'=>'ACTIVE_SURGE_ARRESTERS','value'=>$ACTIVE_SURGE_ARRESTERS,'type'=>'','length'=>-1),
             array('name'=>'TR_BODY_EARTHING','value'=>$TR_BODY_EARTHING,'type'=>'','length'=>-1),
             array('name'=>'TR_BODY_CONDITION','value'=>$TR_BODY_CONDITION,'type'=>'','length'=>-1),
             array('name'=>'MAINTAINANCE_NUMBER','value'=>$MAINTAINANCE_NUMBER,'type'=>'','length'=>-1),
            array('name'=>'TR_NEUTRAL_EARTHING','value'=>$TR_NEUTRAL_EARTHING,'type'=>'','length'=>-1),
             array('name'=>'MEASURED_ERTHING_RESISTANCE','value'=>$MEASURED_ERTHING_RESISTANCE,'type'=>'','length'=>-1),
             array('name'=>'EARTHING_RESIS_MEASUR_DATE','value'=>$EARTHING_RESIS_MEASUR_DATE,'type'=>'','length'=>-1),
             array('name'=>'INSTALLATION_DATE','value'=>$INSTALLATION_DATE,'type'=>'','length'=>-1),
             array('name'=>'WORK_ORDER_ID','value'=>$WORK_ORDER_ID,'type'=>'','length'=>-1),
             array('name'=>'REGISTRATON_DATA','value'=>$REGISTRATON_DATA,'type'=>'','length'=>-1),
             array('name'=>'WORK_ORDER_DATA','value'=>$WORK_ORDER_DATA,'type'=>'','length'=>-1),
             array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),
             array('name'=>'DISTRICT_NO','value'=>$DISTRICT_NO,'type'=>'','length'=>-1),
             array('name'=>'ENTRY_USER','value'=>$ENTRY_USER,'type'=>'','length'=>-1),
             array('name'=>'DOCUMENTATION_DATE','value'=>$DOCUMENTATION_DATE,'type'=>'','length'=>-1),
             array('name'=>'DATA_DOCUMENTATION_TEAM','value'=>$DATA_DOCUMENTATION_TEAM,'type'=>'','length'=>-1),
             array('name'=>'DATA_ENTRY_TEAM','value'=>$DATA_ENTRY_TEAM,'type'=>'','length'=>-1),
             array('name'=>'PHOTO_PATH','value'=>$PHOTO_PATH,'type'=>'','length'=>-1),
             array('name'=>'NOTES','value'=>$NOTES,'type'=>'','length'=>-1),
        );
        if($type=='create'){
            array_shift($result);
        }
        return $result;
    }
    /**************************************************************************/

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->{$this->MODEL_NAME}->edit(($this->_postedData_info
                ('',$this->ID,$this->TR_RATING_KVA,$this->POLE_MATERIAL_ID,$this->TRANS_MATERIAL_ID,$this->MV_POLE_CODE,$this->TRANSFORMER_CODE,
                    $this->RMU_CODE,$this->MV_SWITCH_CODE,$this->TRANSFORMER_NAME_AR,$this->TRANSFORMER_NAME_EN,$this->GOVERNORATE_NAME,$this->MAX_LOAD_PERCENTAGE,
                    $this->HOUSEHOLD_PERCENTAGE,$this->COMMERCIAL_PERCENTAGE,$this->INDUSTRIAL_PERCENTAGE,$this->AGRICULTURE_PERCENTAGE,$this->INTITUTION_PERCENTAGE,
                    $this->TURNS_RATIO,$this->IMPEDANCE_PERCENTAGE,$this->X_R_RATIO,$this->FEEDER_PLAN,$this->TR_CHANGER_COUNT,$this->TR_MANUFACTURER,$this->TR_SERIAL_NO,
                    $this->TR_DATE_OF_MANUFACTURE,$this->TR_COUNTRY_OF_ORIGIN,$this->TAP_CHANGER_PLUS,$this->TAP_CHANGER_MINUS,$this->CURRENT_TAP_CHANGER,$this->FUSE_HOLDER_TYPE,
                    $this->FUSE_HOLDER_INSULATOR_TYP,$this->DROP_OUT,$this->MV_FUSES_TYPE,$this->TR_POSITION,$this->TR_DIRECTION,$this->TR_OIL_TYPE,$this->TR_PRIMARY_VOLTAGE,
                    $this->TR_SECONDARY_VOLTAGE,$this->TR_PROPERTY,$this->TYPE_OF_FEEDING_CONDUCTOR,$this->MEASURED_PRIMARY_VOLTAGE,$this->MEASURED_SECONDARY_VOLTAGE,
                    $this->MEASURED_POWER_FACTOR,$this->ACTIVE_SURGE_ARRESTERS,$this->TR_BODY_EARTHING,$this->TR_BODY_CONDITION,$this->MAINTAINANCE_NUMBER,$this->TR_NEUTRAL_EARTHING,
                    $this->MEASURED_ERTHING_RESISTANCE,$this->EARTHING_RESIS_MEASUR_DATE,$this->INSTALLATION_DATE,$this->WORK_ORDER_ID,$this->REGISTRATON_DATA,
                    $this->WORK_ORDER_DATA,$this->STREET_NAME,$this->DISTRICT_NO,$this->ENTRY_USER,$this->DOCUMENTATION_DATE,$this->DATA_DOCUMENTATION_TEAM,
                    $this->DATA_ENTRY_TEAM,$this->PHOTO_PATH,$this->NOTES)));
            //var_dump( $this->SER);
            //die();
            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            //die;
            echo intval($this->ser);
        }
    }
    /********************************************************/


}