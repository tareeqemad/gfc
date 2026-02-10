<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 06/02/19
 * Time: 11:33 ص
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class LV_Network_controller extends MY_Controller{

    var $MODEL_NAME='LV_Network_model';
    var $PAGE_URL= 'gis/lv_network_controller/get_page';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
            $this->ID=$this->input->post('ID');
            $this->ID_PK=$this->input->post('ID_PK');
            $this->OBJECT_ID=$this->input->post('OBJECT_ID');
            $this->NETWORK_ID_SER=$this->input->post('NETWORK_ID_SER');
            $this->NETWORK_CODE=$this->input->post('NETWORK_CODE');
            $this->NETWORK_TYPE=$this->input->post('NETWORK_TYPE');
            $this->PHASES_CONDUCTORS_MATERIAL=$this->input->post('PHASES_CONDUCTORS_MATERIAL');
            $this->LV_NETWORK_TYPE=$this->input->post('LV_NETWORK_TYPE');
            $this->NETWORK_PROPERTY=$this->input->post('NETWORK_PROPERTY');
            $this->SERVICE=$this->input->post('SERVICE');
            $this->LV_NETWORK_LENGTH_M=$this->input->post('LV_NETWORK_LENGTH_M');
            $this->LV_PHASE=$this->input->post('LV_PHASE');
            $this->OPRATING_VOLT=$this->input->post('OPRATING_VOLT');
            $this->START_LTL_SWITCH_CODE=$this->input->post('START_LTL_SWITCH_CODE');
            $this->START_POLE_CODE=$this->input->post('START_POLE_CODE');
            $this->END_POLE_CODE=$this->input->post('END_POLE_CODE');
            $this->FEEDER_CODE=$this->input->post('FEEDER_CODE');
            $this->TRANSFORMER_CODE=$this->input->post('TRANSFORMER_CODE');
            $this->TRANSFORMER_NAME_AR=$this->input->post('TRANSFORMER_NAME_AR');
            $this->TRANSFORMER_NAME_EN=$this->input->post('TRANSFORMER_NAME_EN');
            $this->NOTES=$this->input->post('NOTES');
            $this->INSTALLATION_DATE=$this->input->post('INSTALLATION_DATE');
            $this->STREET_NAME=$this->input->post('STREET_NAME');
            $this->DISTRICT_NUMBER=$this->input->post('DISTRICT_NUMBER');
            $this->GOVERNORATE_NAME=$this->input->post('GOVERNORATE_NAME');
            $this->DOCUMENTATION_DATE=$this->input->post('DOCUMENTATION_DATE');
            $this->DATA_DOCUMENTED_BY=$this->input->post('DATA_DOCUMENTED_BY');
            $this->DATA_ENTRY_BY=$this->input->post('DATA_ENTRY_BY');
            $this->PROJECT_NO=$this->input->post('PROJECT_NO');
            $this->PHOTO_PATH=$this->input->post('PHOTO_PATH');
            $this->X_COORDINATE=$this->input->post('X_COORDINATE');
            $this->Y_COORDINATE=$this->input->post('Y_COORDINATE');
    }
    /******************index function*****************************/
    function index($page= 1,$NETWORK_CODE=-1){
        $data['content'] = 'LV_Network';
        $data['title'] = 'شبكات الضغط المنخفض';
        $data['page']=$page;
        $data['NETWORK_CODE']=$NETWORK_CODE;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);

    }
    /*********************get_page function*******************************/
    function public_get_page($page= 1,$NETWORK_CODE= -1){
        $this->load->library('pagination');
        $NETWORK_CODE=$this->check_vars($NETWORK_CODE,'NETWORK_CODE');
        /***************************where_sql*******************************/
        $where_sql= "where 1=1";
        $where_sql.= ($NETWORK_CODE!= null)? " and NETWORK_CODE= '{$NETWORK_CODE}' " : '';
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
        $this->load->view('LV_Network_show',$data);
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
    function get_LV_Network_info($id){

        $result= $this->{$this->MODEL_NAME}->get_LV_Network($id);
        $data['LV_Network_data'] = $result;
        $data['content'] = 'add_LV_Network';
        $data['title'] = 'عرض شبكة الضغط المنخفض';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /*****************************************************************************/
    function _postedData_info($type=null,$ID,$ID_PK,$OBJECT_ID,$NETWORK_ID_SER,$NETWORK_CODE,$NETWORK_TYPE,$PHASES_CONDUCTORS_MATERIAL,
                              $LV_NETWORK_TYPE,$NETWORK_PROPERTY,$SERVICE,$LV_NETWORK_LENGTH_M,$LV_PHASE,$OPRATING_VOLT,$START_LTL_SWITCH_CODE,
                              $START_POLE_CODE,$END_POLE_CODE,$FEEDER_CODE,$TRANSFORMER_CODE,$TRANSFORMER_NAME_AR,$TRANSFORMER_NAME_EN,$NOTES,
                              $INSTALLATION_DATE,$STREET_NAME,$DISTRICT_NUMBER,$GOVERNORATE_NAME,$DOCUMENTATION_DATE,$DATA_DOCUMENTED_BY,$DATA_ENTRY_BY,
                              $PROJECT_NO,$PHOTO_PATH,$X_COORDINATE,$Y_COORDINATE)

    {
        $result = array(
            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'ID_PK','value'=>$ID_PK,'type'=>'','length'=>-1),
            array('name'=>'OBJECT_ID','value'=>$OBJECT_ID,'type'=>'','length'=>-1),
            array('name'=>'NETWORK_ID_SER','value'=>$NETWORK_ID_SER,'type'=>'','length'=>-1),
            array('name'=>'NETWORK_CODE','value'=>$NETWORK_CODE,'type'=>'','length'=>-1),
            array('name'=>'NETWORK_TYPE','value'=>$NETWORK_TYPE,'type'=>'','length'=>-1),
            array('name'=>'PHASES_CONDUCTORS_MATERIAL','value'=>$PHASES_CONDUCTORS_MATERIAL,'type'=>'','length'=>-1),
            array('name'=>'LV_NETWORK_TYPE','value'=>$LV_NETWORK_TYPE,'type'=>'','length'=>-1),
            array('name'=>'NETWORK_PROPERTY','value'=>$NETWORK_PROPERTY,'type'=>'','length'=>-1),
            array('name'=>'SERVICE','value'=>$SERVICE,'type'=>'','length'=>-1),
            array('name'=>'LV_NETWORK_LENGTH_M','value'=>$LV_NETWORK_LENGTH_M,'type'=>'','length'=>-1),
            array('name'=>'LV_PHASE','value'=>$LV_PHASE,'type'=>'','length'=>-1),
            array('name'=>'OPRATING_VOLT','value'=>$OPRATING_VOLT,'type'=>'','length'=>-1),
            array('name'=>'START_LTL_SWITCH_CODE','value'=>$START_LTL_SWITCH_CODE,'type'=>'','length'=>-1),
            array('name'=>'START_POLE_CODE','value'=>$START_POLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'END_POLE_CODE','value'=>$END_POLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'FEEDER_CODE','value'=>$FEEDER_CODE,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_CODE','value'=>$TRANSFORMER_CODE,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_AR','value'=>$TRANSFORMER_NAME_AR,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_EN','value'=>$TRANSFORMER_NAME_EN,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$NOTES,'type'=>'','length'=>-1),
            array('name'=>'INSTALLATION_DATE','value'=>$INSTALLATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),
            array('name'=>'DISTRICT_NUMBER','value'=>$DISTRICT_NUMBER,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORATE_NAME','value'=>$GOVERNORATE_NAME,'type'=>'','length'=>-1),
            array('name'=>'DOCUMENTATION_DATE','value'=>$DOCUMENTATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'DATA_DOCUMENTED_BY','value'=>$DATA_DOCUMENTED_BY,'type'=>'','length'=>-1),
            array('name'=>'DATA_ENTRY_BY','value'=>$DATA_ENTRY_BY,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_NO','value'=>$PROJECT_NO,'type'=>'','length'=>-1),
            array('name'=>'PHOTO_PATH','value'=>$PHOTO_PATH,'type'=>'','length'=>-1),
            array('name'=>'X_COORDINATE','value'=>$X_COORDINATE,'type'=>'','length'=>-1),
            array('name'=>'Y_COORDINATE','value'=>$Y_COORDINATE,'type'=>'','length'=>-1),
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
                ('',$this->ID,$this->ID_PK,$this->OBJECT_ID,$this->NETWORK_ID_SER,$this->NETWORK_CODE,$this->NETWORK_TYPE,
                    $this->PHASES_CONDUCTORS_MATERIAL,$this->LV_NETWORK_TYPE,$this->NETWORK_PROPERTY,$this->SERVICE,$this->LV_NETWORK_LENGTH_M,
                    $this->LV_PHASE,$this->OPRATING_VOLT,$this->START_LTL_SWITCH_CODE,$this->START_POLE_CODE,$this->END_POLE_CODE,$this->FEEDER_CODE,
                    $this->TRANSFORMER_CODE,$this->TRANSFORMER_NAME_AR,$this->TRANSFORMER_NAME_EN,$this->NOTES,$this->INSTALLATION_DATE,
                    $this->STREET_NAME,$this->DISTRICT_NUMBER,$this->GOVERNORATE_NAME,$this->DOCUMENTATION_DATE,$this->DATA_DOCUMENTED_BY,
                    $this->DATA_ENTRY_BY,$this->PROJECT_NO,$this->PHOTO_PATH,$this->X_COORDINATE,$this->Y_COORDINATE)));
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