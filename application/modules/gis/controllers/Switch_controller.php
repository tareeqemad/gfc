<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 27/01/19
 * Time: 09:26 ص
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class switch_controller extends MY_Controller{
    var $MODEL_NAME='switch_model';
    var $PAGE_URL= 'gis/switch_controller/get_page_switch';
    var $PAGE_ACT;
    function __construct() {
     parent::__construct();
        $this->load->model($this->MODEL_NAME);
            $this->ID= $this->input->post('ID');
            $this->ISOLATING_SWITCH_CODE= $this->input->post('ISOLATING_SWITCH_CODE');
            $this->POLE_MATERIAL_ID= $this->input->post('POLE_MATERIAL_ID');
            $this->SWITCH_MATERIAL_ID= $this->input->post('SWITCH_MATERIAL_ID');
            $this->MV_POLE_CODE= $this->input->post('MV_POLE_CODE');
            $this->IS_MANUFACTURER= $this->input->post('IS_MANUFACTURER');
            $this->IS_SERVICE= $this->input->post('IS_SERVICE');
            $this->IS_OPERATION= $this->input->post('IS_OPERATION');
            $this->IS_CONTROL= $this->input->post('IS_CONTROL');
            $this->IS_INSULATOR= $this->input->post('IS_INSULATOR');
            $this->IS_DIRECTION= $this->input->post('IS_DIRECTION');
            $this->IS_CONDITION= $this->input->post('IS_CONDITION');
            $this->IS_HAND_EARTHING= $this->input->post('IS_HAND_EARTHING');
            $this->IS_INSTALLATION_DATE= $this->input->post('IS_INSTALLATION_DATE');
            $this->START_MV_NETWORK_CODE= $this->input->post('START_MV_NETWORK_CODE');
            $this->END_MV_NETWORK_CODE= $this->input->post('END_MV_NETWORK_CODE');
            $this->LINE_NAME= $this->input->post('LINE_NAME');
            $this->GOVERNORATE_NAME= $this->input->post('GOVERNORATE_NAME');
            $this->STREET_NO= $this->input->post('STREET_NO');
            $this->STREET_NAME= $this->input->post('STREET_NAME');
            $this->DISTRICT_NAME= $this->input->post('DISTRICT_NAME');
            $this->DOCUMENTATION_DATE= $this->input->post('DOCUMENTATION_DATE');
            $this->DATA_ENTRY_TEAM= $this->input->post('DATA_ENTRY_TEAM');
            $this->NOTES= $this->input->post('NOTES');
            $this->OBJECTID= $this->input->post('OBJECTID');
            $this->COORDGEOCODEPOINT= $this->input->post('COORDGEOCODEPOINT');
    }
    function index($page= 1,$ID=-1,$ISOLATING_SWITCH_CODE=-1,$POLE_MATERIAL_ID=-1,$SWITCH_MATERIAL_ID=-1,$MV_POLE_CODE=-1,
                   $IS_MANUFACTURER=-1,$IS_SERVICE=-1,$IS_OPERATION=-1,$IS_CONTROL=-1,$IS_INSULATOR=-1,$IS_DIRECTION=-1,
                   $IS_CONDITION=-1,$IS_HAND_EARTHING=-1,$IS_INSTALLATION_DATE=-1,$START_MV_NETWORK_CODE=-1,$END_MV_NETWORK_CODE=-1,
                   $LINE_NAME=-1,$GOVERNORATE_NAME=-1,$STREET_NO=-1,$STREET_NAME=-1,$DISTRICT_NAME=-1,$DOCUMENTATION_DATE=-1,$DATA_ENTRY_TEAM=-1,
                   $NOTES=-1,$OBJECTID=-1,$COORDGEOCODEPOINT=-1){
        $data['content'] = 'switche';
        $data['title'] = 'السكينة';
        $data['page']=$page;
        $data['ID']=$ID;
        $data['ISOLATING_SWITCH_CODE']=$ISOLATING_SWITCH_CODE;
        $data['POLE_MATERIAL_ID']=$POLE_MATERIAL_ID;
        $data['SWITCH_MATERIAL_ID']=$SWITCH_MATERIAL_ID;
        $data['MV_POLE_CODE']=$MV_POLE_CODE;
        $data['IS_MANUFACTURER']=$IS_MANUFACTURER;
        $data['IS_SERVICE']=$IS_SERVICE;
        $data['IS_OPERATION']=$IS_OPERATION;
        $data['IS_CONTROL']=$IS_CONTROL;
        $data['IS_INSULATOR']=$IS_INSULATOR;
        $data['IS_DIRECTION']=$IS_DIRECTION;
        $data['IS_CONDITION']=$IS_CONDITION;
        $data['IS_HAND_EARTHING']=$IS_HAND_EARTHING;
        $data['IS_INSTALLATION_DATE']=$IS_INSTALLATION_DATE;
        $data['START_MV_NETWORK_CODE']=$START_MV_NETWORK_CODE;
        $data['END_MV_NETWORK_CODE']=$END_MV_NETWORK_CODE;
        $data['LINE_NAME']=$LINE_NAME;
        $data['GOVERNORATE_NAME']=$GOVERNORATE_NAME;
        $data['STREET_NO']=$STREET_NO;
        $data['STREET_NAME']=$STREET_NAME;
        $data['DISTRICT_NAME']=$DISTRICT_NAME;
        $data['DOCUMENTATION_DATE']=$DOCUMENTATION_DATE;
        $data['DATA_ENTRY_TEAM']=$DATA_ENTRY_TEAM;
        $data['NOTES']=$NOTES;
        $data['OBJECTID']=$OBJECTID;
        $data['COORDGEOCODEPOINT']=$COORDGEOCODEPOINT;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);

}

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
    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
     function public_get_page_switch($page= 1,$SWITCH_MATERIAL_ID=-1){
        $this->load->library('pagination');
        $SWITCH_MATERIAL_ID=$this->check_vars($SWITCH_MATERIAL_ID,'SWITCH_MATERIAL_ID');
        /***************************where_sql*******************************/
        $where_sql= "where  1=1";
        $where_sql.= ($SWITCH_MATERIAL_ID!= null)? " and SWITCH_MATERIAL_ID= '{$SWITCH_MATERIAL_ID}' " : '';
        $config['base_url'] = base_url($this->PAGE_URL);
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_switch($where_sql,$offset,$row);
        $data['offset']=$offset+1;
        $data['page']=$page;
        $this->load->view('switch_show',$data);
    }


    function get_switch_info($id){
        $result= $this->{$this->MODEL_NAME}->get_switch($id);
       //print_r($result);
        $data['switch_data'] = $result;
        $data['content'] = 'add_switch';
        $data['title'] = 'عرض تفاصيل السكينة';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }








    function _postedData_info($type=null,$ID,$ISOLATING_SWITCH_CODE,$POLE_MATERIAL_ID,$SWITCH_MATERIAL_ID,$MV_POLE_CODE,
                              $IS_MANUFACTURER,$IS_SERVICE,$IS_OPERATION,$IS_CONTROL,$IS_INSULATOR,$IS_DIRECTION,
                              $IS_CONDITION,$IS_HAND_EARTHING,$IS_INSTALLATION_DATE,$START_MV_NETWORK_CODE,$END_MV_NETWORK_CODE,
                              $LINE_NAME,$GOVERNORATE_NAME,$STREET_NO,$STREET_NAME,$DISTRICT_NAME,$DOCUMENTATION_DATE,$DATA_ENTRY_TEAM,
                              $NOTES,$OBJECTID)
    {
        $result = array(
            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'ISOLATING_SWITCH_CODE','value'=>$ISOLATING_SWITCH_CODE,'type'=>'','length'=>-1),
            array('name'=>'POLE_MATERIAL_ID','value'=>$POLE_MATERIAL_ID,'type'=>'','length'=>-1),
            array('name'=>'SWITCH_MATERIAL_ID','value'=>$SWITCH_MATERIAL_ID,'type'=>'','length'=>-1),
            array('name'=>'MV_POLE_CODE','value'=>$MV_POLE_CODE,'type'=>'','length'=>-1),
            array('name'=>'IS_MANUFACTURER','value'=>$IS_MANUFACTURER,'type'=>'','length'=>-1),
            array('name'=>'IS_SERVICE','value'=>$IS_SERVICE,'type'=>'','length'=>-1),
            array('name'=>'IS_OPERATION','value'=>$IS_OPERATION,'type'=>'','length'=>-1),
            array('name'=>'IS_CONTROL','value'=>$IS_CONTROL,'type'=>'','length'=>-1),
            array('name'=>'IS_INSULATOR','value'=>$IS_INSULATOR,'type'=>'','length'=>-1),
            array('name'=>'IS_DIRECTION','value'=>$IS_DIRECTION,'type'=>'','length'=>-1),
            array('name'=>'IS_CONDITION','value'=>$IS_CONDITION,'type'=>'','length'=>-1),
            array('name'=>'IS_HAND_EARTHING','value'=>$IS_HAND_EARTHING,'type'=>'','length'=>-1),
            array('name'=>'IS_INSTALLATION_DATE','value'=>$IS_INSTALLATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'START_MV_NETWORK_CODE','value'=>$START_MV_NETWORK_CODE,'type'=>'','length'=>-1),
            array('name'=>'END_MV_NETWORK_CODE','value'=>$END_MV_NETWORK_CODE,'type'=>'','length'=>-1),
            array('name'=>'LINE_NAME','value'=>$LINE_NAME,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORATE_NAME','value'=>$GOVERNORATE_NAME,'type'=>'','length'=>-1),
            array('name'=>'STREET_NO','value'=>$STREET_NO,'type'=>'','length'=>-1),
            array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),
            array('name'=>'DISTRICT_NAME','value'=>$DISTRICT_NAME,'type'=>'','length'=>-1),
            array('name'=>'DOCUMENTATION_DATE','value'=>$DOCUMENTATION_DATE,'type'=>'','length'=>-1),
            array('name'=>'DATA_ENTRY_TEAM','value'=>$DATA_ENTRY_TEAM,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$NOTES,'type'=>'','length'=>-1),
            array('name'=>'OBJECTID','value'=>$OBJECTID,'type'=>'','length'=>-1),



        );
        if($type=='create'){
            array_shift($result);
        }
        return $result;
    }
    /***********************function edit****************************************/

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->{$this->MODEL_NAME}->edit(($this->_postedData_info
                ('',$this->ID,$this->ISOLATING_SWITCH_CODE,$this->POLE_MATERIAL_ID,
                    $this->SWITCH_MATERIAL_ID,$this->MV_POLE_CODE,
                    $this->IS_MANUFACTURER,$this->IS_SERVICE,
                    $this->IS_OPERATION, $this->IS_CONTROL,
                    $this->IS_INSULATOR ,$this->IS_DIRECTION,$this->IS_CONDITION,
                    $this->IS_HAND_EARTHING,$this->IS_INSTALLATION_DATE,$this->START_MV_NETWORK_CODE,
                    $this->END_MV_NETWORK_CODE,$this->LINE_NAME,
                    $this->GOVERNORATE_NAME,$this->STREET_NO,
                    $this->STREET_NAME,$this->DISTRICT_NAME,$this->DOCUMENTATION_DATE,$this->DATA_ENTRY_TEAM ,
                    $this->NOTES,$this->OBJECTID)));

            echo intval($this->ser);
        }
        }

}
