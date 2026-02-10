<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 06/02/19
 * Time: 08:45 ص
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class LV_POLE_controller extends MY_Controller{

    var $MODEL_NAME='LV_POLE_model';
    var $PAGE_URL= 'gis/lv_pole_controller/get_page';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
            $this->ID=$this->input->post('ID');
            $this->ID_PK=$this->input->post('ID_PK');
            $this->ID_M=$this->input->post('ID_M');
            $this->OBJECT_ID=$this->input->post('OBJECT_ID');
            $this->POLE_MATERLAL_ID=$this->input->post('POLE_MATERLAL_ID');
            $this->POLE_ID_SER=$this->input->post('POLE_ID_SER');
            $this->POLE_CODE=$this->input->post('POLE_CODE');
            $this->POLE_TYPE=$this->input->post('POLE_TYPE');
            $this->POLE_SIZE=$this->input->post('POLE_SIZE');
            $this->POLE_HEIGHT=$this->input->post('POLE_HEIGHT');
            $this->POLE_PROPERTY=$this->input->post('POLE_PROPERTY');
            $this->POLE_CONDITION=$this->input->post('POLE_CONDITION');
            $this->POLE_EARTH=$this->input->post('POLE_EARTH');
            $this->USED_ARMS=$this->input->post('USED_ARMS');
            $this->UNUSED_ARMS=$this->input->post('UNUSED_ARMS');
            $this->USED_LV_PIN_INSULATORS_NO=$this->input->post('USED_LV_PIN_INSULATORS_NO');
            $this->UNUSED_LV_PIN_INSULATORS_NO=$this->input->post('UNUSED_LV_PIN_INSULATORS_NO');
            $this->BROKEN_LV_PIN_INSULATORS_NO=$this->input->post('BROKEN_LV_PIN_INSULATORS_NO');
            $this->STAY=$this->input->post('STAY');
            $this->SUPPORT_POLE=$this->input->post('SUPPORT_POLE');
            $this->CABLE_GUARDS_NO=$this->input->post('CABLE_GUARDS_NO');
            $this->LV_OVERHEAD_FEEDERS_SOURCES_NO=$this->input->post('LV_OVERHEAD_FEEDERS_SOURCES_NO');
            $this->NO_OF_LV_UNDERGROUND_FEEDERS=$this->input->post('NO_OF_LV_UNDERGROUND_FEEDERS');
            $this->NO_OF_SINGLE_PHASE_CUSTOMERS=$this->input->post('NO_OF_SINGLE_PHASE_CUSTOMERS');
            $this->NO_OF_THREE_PHASE_CUSTOMERS=$this->input->post('NO_OF_THREE_PHASE_CUSTOMERS');
            $this->TRANSFORMER_NAME_AR=$this->input->post('TRANSFORMER_NAME_AR');
            $this->TRANSFORMER_NAME_EN=$this->input->post('TRANSFORMER_NAME_EN');
            $this->NOTES=$this->input->post('NOTES');
            $this->TRANSFORMER_CODE=$this->input->post('TRANSFORMER_CODE');
            $this->INSTALLATION_DATE=$this->input->post('INSTALLATION_DATE');
            $this->SERVICE=$this->input->post('SERVICE');
            $this->STREET_NAME=$this->input->post('STREET_NAME');
            $this->DISTRICT_NUMBER=$this->input->post('DISTRICT_NUMBER');
            $this->GOVERNORATE_NAME=$this->input->post('GOVERNORATE_NAME');
            $this->DOCUMENTATION_DATE=$this->input->post('DOCUMENTATION_DATE');
            $this->DATA_DOCUMENTATION_TEAM=$this->input->post('DATA_DOCUMENTATION_TEAM');
            $this->DATA_ENTRY_TEAM=$this->input->post('DATA_ENTRY_TEAM');
            $this->PROJECT_NO=$this->input->post('PROJECT_NO');
            $this->PHOTO_PAH=$this->input->post('PHOTO_PAH');
            $this->X_COORDINATE=$this->input->post('X_COORDINATE');
            $this->Y_COORDINATE=$this->input->post('Y_COORDINATE');
    }
    /******************index function*****************************/
    function index($page= 1,$POLE_MATERLAL_ID=-1){
        $data['content'] = 'LV_Poles';
        $data['title'] = 'أعمدة الضغط المنخفض';
        $data['page']=$page;
        $data['POLE_MATERLAL_ID']=$POLE_MATERLAL_ID;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);

    }
    /*********************get_page function*******************************/
   function public_get_page($page= 1,$POLE_MATERLAL_ID= -1){
        $this->load->library('pagination');
       $POLE_MATERLAL_ID=$this->check_vars($POLE_MATERLAL_ID,'POLE_MATERLAL_ID');
        /***************************where_sql*******************************/
        $where_sql= "where 1=1";
        $where_sql.= ($POLE_MATERLAL_ID!= null)? " and POLE_MATERLAL_ID= '{$POLE_MATERLAL_ID}' " : '';
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
        $this->load->view('LV_Poles_show',$data);
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
    function get_LV_Poles_info($id){

        $result= $this->{$this->MODEL_NAME}->get_LV_Poles($id);
        $data['LV_Poles_data'] = $result;
        $data['content'] = 'Add_LV_Poles';
        $data['title'] = 'عرض عمود ضغط منخفض ';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /***********************************************************************************************************/
    function _postedData_info($type=null,$ID,$ID_PK,$ID_M,$OBJECT_ID,$POLE_MATERLAL_ID,$POLE_ID_SER,$POLE_CODE,$POLE_TYPE,
                              $POLE_SIZE,$POLE_HEIGHT,$POLE_PROPERTY,$POLE_CONDITION,$POLE_EARTH,$USED_ARMS,$UNUSED_ARMS,
                              $USED_LV_PIN_INSULATORS_NO,$UNUSED_LV_PIN_INSULATORS_NO,$BROKEN_LV_PIN_INSULATORS_NO,$STAY,
                              $SUPPORT_POLE,$CABLE_GUARDS_NO,$LV_OVERHEAD_FEEDERS_SOURCES_NO,$NO_OF_LV_UNDERGROUND_FEEDERS,
                              $NO_OF_SINGLE_PHASE_CUSTOMERS,$NO_OF_THREE_PHASE_CUSTOMERS,$TRANSFORMER_NAME_AR,$TRANSFORMER_NAME_EN,
                              $NOTES,$TRANSFORMER_CODE,$INSTALLATION_DATE,$SERVICE,$STREET_NAME,$DISTRICT_NUMBER,$GOVERNORATE_NAME,
                              $DOCUMENTATION_DATE,$DATA_DOCUMENTATION_TEAM,$DATA_ENTRY_TEAM,$PROJECT_NO,$PHOTO_PAH,$X_COORDINATE,
                              $Y_COORDINATE)
    {
        $result = array(

            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'ID_PK','value'=>$ID_PK,'type'=>'','length'=>-1),
            array('name'=>'ID_M','value'=>$ID_M,'type'=>'','length'=>-1),
            array('name'=>'OBJECT_ID','value'=>$OBJECT_ID,'type'=>'','length'=>-1),
            array('name'=>'POLE_MATERLAL_ID','value'=>$POLE_MATERLAL_ID,'type'=>'','length'=>-1),
            array('name'=>'POLE_ID_SER','value'=>$POLE_ID_SER,'type'=>'','length'=>-1),
            array('name'=>'POLE_CODE','value'=>$POLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'POLE_TYPE','value'=>$POLE_TYPE,'type'=>'','length'=>-1),
            array('name'=>'POLE_SIZE','value'=>$POLE_SIZE,'type'=>'','length'=>-1),
            array('name'=>'POLE_HEIGHT','value'=>$POLE_HEIGHT,'type'=>'','length'=>-1),
            array('name'=>'POLE_PROPERTY','value'=>$POLE_PROPERTY,'type'=>'','length'=>-1),
            array('name'=>'POLE_CONDITION','value'=>$POLE_CONDITION,'type'=>'','length'=>-1),
            array('name'=>'POLE_EARTH','value'=>$POLE_EARTH,'type'=>'','length'=>-1),
            array('name'=>'USED_ARMS','value'=>$USED_ARMS,'type'=>'','length'=>-1),
            array('name'=>'UNUSED_ARMS','value'=>$UNUSED_ARMS,'type'=>'','length'=>-1),
            array('name'=>'USED_LV_PIN_INSULATORS_NO','value'=>$USED_LV_PIN_INSULATORS_NO,'type'=>'','length'=>-1),
            array('name'=>'UNUSED_LV_PIN_INSULATORS_NO','value'=>$UNUSED_LV_PIN_INSULATORS_NO,'type'=>'','length'=>-1),
            array('name'=>'BROKEN_LV_PIN_INSULATORS_NO','value'=>$BROKEN_LV_PIN_INSULATORS_NO,'type'=>'','length'=>-1),
            array('name'=>'STAY','value'=>$STAY,'type'=>'','length'=>-1),
            array('name'=>'SUPPORT_POLE','value'=>$SUPPORT_POLE,'type'=>'','length'=>-1),
            array('name'=>'CABLE_GUARDS_NO','value'=>$CABLE_GUARDS_NO,'type'=>'','length'=>-1),
            array('name'=>'LV_OVERHEAD_FEEDERS_SOURCES_NO','value'=>$LV_OVERHEAD_FEEDERS_SOURCES_NO,'type'=>'','length'=>-1),
            array('name'=>'NO_OF_LV_UNDERGROUND_FEEDERS','value'=>$NO_OF_LV_UNDERGROUND_FEEDERS,'type'=>'','length'=>-1),
            array('name'=>'NO_OF_SINGLE_PHASE_CUSTOMERS','value'=>$NO_OF_SINGLE_PHASE_CUSTOMERS,'type'=>'','length'=>-1),
            array('name'=>'NO_OF_THREE_PHASE_CUSTOMERS','value'=>$NO_OF_THREE_PHASE_CUSTOMERS,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_AR','value'=>$TRANSFORMER_NAME_AR,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_NAME_EN','value'=>$TRANSFORMER_NAME_EN,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$NOTES,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORMER_CODE','value'=>$TRANSFORMER_CODE,'type'=>'','length'=>-1),
            array('name'=>'INSTALLATION_DATE','value'=>$INSTALLATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'SERVICE','value'=>$SERVICE,'type'=>'','length'=>-1),
            array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),
            array('name'=>'DISTRICT_NUMBER','value'=>$DISTRICT_NUMBER,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORATE_NAME','value'=>$GOVERNORATE_NAME,'type'=>'','length'=>-1),
            array('name'=>'DOCUMENTATION_DATE','value'=>$DOCUMENTATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'DATA_DOCUMENTATION_TEAM','value'=>$DATA_DOCUMENTATION_TEAM,'type'=>'','length'=>-1),
            array('name'=>'DATA_ENTRY_TEAM','value'=>$DATA_ENTRY_TEAM,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_NO','value'=>$PROJECT_NO,'type'=>'','length'=>-1),
            array('name'=>'PHOTO_PAH','value'=>$PHOTO_PAH,'type'=>'','length'=>-1),
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
                ('',$this->ID,$this->ID_PK,$this->ID_M,$this->OBJECT_ID,$this->POLE_MATERLAL_ID,$this->POLE_ID_SER,$this->POLE_CODE,
                    $this->POLE_TYPE,$this->POLE_SIZE,$this->POLE_HEIGHT,$this->POLE_PROPERTY,$this->POLE_CONDITION,$this->POLE_EARTH,
                    $this->USED_ARMS,$this->UNUSED_ARMS,$this->USED_LV_PIN_INSULATORS_NO,$this->UNUSED_LV_PIN_INSULATORS_NO,
                    $this->BROKEN_LV_PIN_INSULATORS_NO,$this->STAY,$this->SUPPORT_POLE,$this->CABLE_GUARDS_NO,$this->LV_OVERHEAD_FEEDERS_SOURCES_NO,
                    $this->NO_OF_LV_UNDERGROUND_FEEDERS,$this->NO_OF_SINGLE_PHASE_CUSTOMERS,$this->NO_OF_THREE_PHASE_CUSTOMERS,$this->TRANSFORMER_NAME_AR,
                    $this->TRANSFORMER_NAME_EN,$this->NOTES,$this->TRANSFORMER_CODE,$this->INSTALLATION_DATE,$this->SERVICE,$this->STREET_NAME,
                    $this->DISTRICT_NUMBER,$this->GOVERNORATE_NAME,$this->DOCUMENTATION_DATE,$this->DATA_DOCUMENTATION_TEAM,$this->DATA_ENTRY_TEAM,
                    $this->PROJECT_NO,$this->PHOTO_PAH,$this->X_COORDINATE,$this->Y_COORDINATE)));
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