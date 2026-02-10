<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 11:45 ص
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_Controller extends MY_Controller{

    var $MODEL_NAME='Room_model';
    var $PAGE_URL= 'gis/room_controller/get_page';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
                $this->OBJECTID=$this->input->post('OBJECTID');
                $this->ROOM__CODE=$this->input->post('ROOM__CODE');
                $this->ID=$this->input->post('ID');
                $this->ROOM_COMPO=$this->input->post('ROOM_COMPO');
                $this->GOVERNORAT=$this->input->post('GOVERNORAT');
                $this->STREET_NO=$this->input->post('STREET_NO');
                $this->STREET_NAM=$this->input->post('STREET_NAM');
                $this->DISTRICT_N=$this->input->post('DISTRICT_N');
                $this->AREA_NO=$this->input->post('AREA_NO');
                $this->PROJECT_NO=$this->input->post('PROJECT_NO');
                $this->ROOM_CONST=$this->input->post('ROOM_CONST');
                $this->ROOM_TYPE=$this->input->post('ROOM_TYPE');
                $this->BUILDING_N=$this->input->post('BUILDING_N');
                $this->TRANSFORME=$this->input->post('TRANSFORME');
                $this->TRANSFORM1=$this->input->post('TRANSFORM1');
                $this->RMU_NO=$this->input->post('RMU_NO');
                $this->DISTRIBUTI=$this->input->post('DISTRIBUTI');
                $this->DISTRIBUT1=$this->input->post('DISTRIBUT1');
                $this->LTL_SWITCH=$this->input->post('LTL_SWITCH');
                $this->CABLES_HOL=$this->input->post('CABLES_HOL');
                $this->LINE1_NAME=$this->input->post('LINE1_NAME');
                $this->LINE2_NAME=$this->input->post('LINE2_NAME');
                $this->LINE3_NAME=$this->input->post('LINE3_NAME');
                $this->SERVICE=$this->input->post('SERVICE');
                $this->ROOM_SIZE=$this->input->post('ROOM_SIZE');
                $this->ROOM_MV_RI=$this->input->post('ROOM_MV_RI');
                $this->ROOM_LV_RI=$this->input->post('ROOM_LV_RI');
                $this->CONSTRUCTI=$this->input->post('CONSTRUCTI');
                $this->ROOM_VENTI=$this->input->post('ROOM_VENTI');
                $this->ROOM_PAINT=$this->input->post('ROOM_PAINT');
                $this->ROOM_CLEAN=$this->input->post('ROOM_CLEAN');
                $this->FIRE_EXTIN=$this->input->post('FIRE_EXTIN');
                $this->ROOM_LIGHT=$this->input->post('ROOM_LIGHT');
                $this->SEPARATION=$this->input->post('SEPARATION');
                $this->WINDOWS=$this->input->post('WINDOWS');
                $this->DOORS_TYPE=$this->input->post('DOORS_TYPE');
                $this->DOORS_LOCK=$this->input->post('DOORS_LOCK');
                $this->DOORS_EART=$this->input->post('DOORS_EART');
                $this->SEPARATIO1=$this->input->post('SEPARATIO1');
                $this->WINDOWS_EA=$this->input->post('WINDOWS_EA');
                $this->DOCUMENTAT=$this->input->post('DOCUMENTAT');
                $this->MODIFIED_D=$this->input->post('MODIFIED_D');
                $this->DATA_DOCUM=$this->input->post('DATA_DOCUM');
                $this->DATA_ENTRY=$this->input->post('DATA_ENTRY');
                $this->NOTES=$this->input->post('NOTES');
                $this->PHOTO1_PAT=$this->input->post('PHOTO1_PAT');
                $this->SHAPE_AREA=$this->input->post('SHAPE_AREA');
                $this->ID1=$this->input->post('ID1');

    }
    /******************index function*****************************/
    function index($page= 1,$ROOM__CODE=-1){
        $data['content'] = 'ROOM';
        $data['title'] = 'الغرف';
        $data['page']=$page;
        $data['ROOM__CODE']=$ROOM__CODE;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);
    }
    /*********************get_page function*******************************/
    function public_get_page($page= 1,$ROOM__CODE= -1){
        $this->load->library('pagination');
        $ROOM__CODE=$this->check_vars($ROOM__CODE,'ROOM__CODE');
        /***************************where_sql*******************************/
        $where_sql= "where 1=1";
        $where_sql.= ($ROOM__CODE!= null)? " and ROOM__CODE= '{$ROOM__CODE}' " : '';
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
        $this->load->view('Room_show',$data);
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
    function get_Room_info($id){

        $result= $this->{$this->MODEL_NAME}->get_Room($id);
        $data['Room_data'] = $result;
        $data['content'] = 'add_Room';
        $data['title'] = 'عرض بيانات الغرفة';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /*****************************************************************************/
    function _postedData_info($type=null,$OBJECTID,$ROOM__CODE,$ID,$ROOM_COMPO,$GOVERNORAT,$STREET_NO,$STREET_NAM,$DISTRICT_N,
                              $AREA_NO,$PROJECT_NO,$ROOM_CONST,$ROOM_TYPE,$BUILDING_N,$TRANSFORME,$TRANSFORM1,$RMU_NO,$DISTRIBUTI,
                              $DISTRIBUT1,$LTL_SWITCH,$CABLES_HOL,$LINE1_NAME,$LINE2_NAME,$LINE3_NAME,$SERVICE,$ROOM_SIZE,$ROOM_MV_RI,
                              $ROOM_LV_RI,$CONSTRUCTI,$ROOM_VENTI,$ROOM_PAINT,$ROOM_CLEAN,$FIRE_EXTIN,$ROOM_LIGHT,$SEPARATION,$WINDOWS,
                             $DOORS_TYPE,$DOORS_LOCK,$DOORS_EART,$SEPARATIO1,$WINDOWS_EA,$DOCUMENTAT,$MODIFIED_D,$DATA_DOCUM,
                              $DATA_ENTRY,$NOTES,$PHOTO1_PAT,/*$SHAPE_LENG,*/$SHAPE_AREA,$ID1)

    {
        $result = array(
            array('name'=>'OBJECTID','value'=>$OBJECTID,'type'=>'','length'=>-1),
            array('name'=>'ROOM__CODE','value'=>$ROOM__CODE,'type'=>'','length'=>-1),
            array('name'=>'ID','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>'ROOM_COMPO','value'=>$ROOM_COMPO,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORAT','value'=>$GOVERNORAT,'type'=>'','length'=>-1),
            array('name'=>'STREET_NO','value'=>$STREET_NO,'type'=>'','length'=>-1),
            array('name'=>'STREET_NAM','value'=>$STREET_NAM,'type'=>'','length'=>-1),
            array('name'=>'DISTRICT_N','value'=>$DISTRICT_N,'type'=>'','length'=>-1),
            array('name'=>'AREA_NO','value'=>$AREA_NO,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_NO','value'=>$PROJECT_NO,'type'=>'','length'=>-1),
            array('name'=>'ROOM_CONST','value'=>$ROOM_CONST,'type'=>'','length'=>-1),
            array('name'=>'ROOM_TYPE','value'=>$ROOM_TYPE,'type'=>'','length'=>-1),
            array('name'=>'BUILDING_N','value'=>$BUILDING_N,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORME','value'=>$TRANSFORME,'type'=>'','length'=>-1),
            array('name'=>'TRANSFORM1','value'=>$TRANSFORM1,'type'=>'','length'=>-1),
            array('name'=>'RMU_NO','value'=>$RMU_NO,'type'=>'','length'=>-1),
            array('name'=>'DISTRIBUTI','value'=>$DISTRIBUTI,'type'=>'','length'=>-1),
            array('name'=>'DISTRIBUT1','value'=>$DISTRIBUT1,'type'=>'','length'=>-1),
            array('name'=>'LTL_SWITCH','value'=>$LTL_SWITCH,'type'=>'','length'=>-1),
            array('name'=>'CABLES_HOL','value'=>$CABLES_HOL,'type'=>'','length'=>-1),
            array('name'=>'LINE1_NAME','value'=>$LINE1_NAME,'type'=>'','length'=>-1),
            array('name'=>'LINE2_NAME','value'=>$LINE2_NAME,'type'=>'','length'=>-1),
            array('name'=>'LINE3_NAME','value'=>$LINE3_NAME,'type'=>'','length'=>-1),
            array('name'=>'SERVICE','value'=>$SERVICE,'type'=>'','length'=>-1),
            array('name'=>'ROOM_SIZE','value'=>$ROOM_SIZE,'type'=>'','length'=>-1),
            array('name'=>'ROOM_MV_RI','value'=>$ROOM_MV_RI,'type'=>'','length'=>-1),
            array('name'=>'ROOM_LV_RI','value'=>$ROOM_LV_RI,'type'=>'','length'=>-1),
            array('name'=>'CONSTRUCTI','value'=>$CONSTRUCTI,'type'=>'','length'=>-1),
            array('name'=>'ROOM_VENTI','value'=>$ROOM_VENTI,'type'=>'','length'=>-1),
            array('name'=>'ROOM_PAINT','value'=>$ROOM_PAINT,'type'=>'','length'=>-1),
            array('name'=>'ROOM_CLEAN','value'=>$ROOM_CLEAN,'type'=>'','length'=>-1),
            array('name'=>'FIRE_EXTIN','value'=>$FIRE_EXTIN,'type'=>'','length'=>-1),
            array('name'=>'ROOM_LIGHT','value'=>$ROOM_LIGHT,'type'=>'','length'=>-1),
            array('name'=>'SEPARATION','value'=>$SEPARATION,'type'=>'','length'=>-1),
            array('name'=>'WINDOWS','value'=>$WINDOWS,'type'=>'','length'=>-1),
            array('name'=>'DOORS_TYPE','value'=>$DOORS_TYPE,'type'=>'','length'=>-1),
            array('name'=>'DOORS_LOCK','value'=>$DOORS_LOCK,'type'=>'','length'=>-1),
            array('name'=>'DOORS_EART','value'=>$DOORS_EART,'type'=>'','length'=>-1),
            array('name'=>'SEPARATIO1','value'=>$SEPARATIO1,'type'=>'','length'=>-1),
            array('name'=>'WINDOWS_EA','value'=>$WINDOWS_EA,'type'=>'','length'=>-1),
            array('name'=>'DOCUMENTAT','value'=>$DOCUMENTAT,'type'=>'','length'=>-1),
            array('name'=>'MODIFIED_D','value'=>$MODIFIED_D,'type'=>'','length'=>-1),
            array('name'=>'DATA_DOCUM','value'=>$DATA_DOCUM,'type'=>'','length'=>-1),
            array('name'=>'DATA_ENTRY','value'=>$DATA_ENTRY,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$NOTES,'type'=>'','length'=>-1),
            array('name'=>'PHOTO1_PAT','value'=>$PHOTO1_PAT,'type'=>'','length'=>-1),
            //array('name'=>'SHAPE_LENG','value'=>$SHAPE_LENG,'type'=>'','length'=>-1),
            array('name'=>'SHAPE_AREA','value'=>$SHAPE_AREA,'type'=>'','length'=>-1),
            array('name'=>'ID1','value'=>$ID1,'type'=>'','length'=>-1),

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
                ('',$this->OBJECTID,$this->ROOM__CODE,$this->ID,$this->ROOM_COMPO,$this->GOVERNORAT,$this->STREET_NO,
                    $this->STREET_NAM,$this->DISTRICT_N,$this->AREA_NO,$this->PROJECT_NO,$this->ROOM_CONST,$this->ROOM_TYPE,
                    $this->BUILDING_N, $this->TRANSFORME,$this->TRANSFORM1,$this->RMU_NO,$this->DISTRIBUTI,$this->DISTRIBUT1,
                    $this->LTL_SWITCH,$this->CABLES_HOL,$this->LINE1_NAME,$this->LINE2_NAME,$this->LINE3_NAME,$this->SERVICE,
                    $this->ROOM_SIZE,$this->ROOM_MV_RI,$this->ROOM_LV_RI,$this->CONSTRUCTI,$this->ROOM_VENTI,$this->ROOM_PAINT,
                    $this->ROOM_CLEAN,$this->FIRE_EXTIN,$this->ROOM_LIGHT,$this->SEPARATION,$this->WINDOWS,$this->DOORS_TYPE,
                    $this->DOORS_LOCK,$this->DOORS_EART,$this->SEPARATIO1,$this->WINDOWS_EA,$this->DOCUMENTAT,$this->MODIFIED_D,
                    $this->DATA_DOCUM,$this->DATA_ENTRY,$this->NOTES,$this->PHOTO1_PAT,$this->SHAPE_AREA,$this->ID1)));
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




