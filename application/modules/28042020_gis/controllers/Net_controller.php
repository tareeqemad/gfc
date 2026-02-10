<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 22/01/19
 * Time: 08:41 ص
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class net_controller extends MY_Controller{

    var $MODEL_NAME='network_model';
    var $PAGE_URL= 'gis/net_controller/get_page_network';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
            $this->ID1= $this->input->post('ID1');
            //$this->GEOMETRY=$this->input->post('GEOMETRY');
            $this->NETWORK_INST_TYPE  = $this->input->post('NETWORK_INST_TYPE');
            $this->PHASES_COND_MATERIAL  = $this->input->post('PHASES_COND_MATERIAL');
            $this->PHASE_COND_TYPE  = $this->input->post('PHASE_COND_TYPE');
            $this->EARTH_L_COND_MATERIAL  = $this->input->post('EARTH_L_COND_MATERIAL');
            $this->EARTH_L_COND_TYPE  = $this->input->post('EARTH_L_COND_TYPE');
            $this->TOTAL_OF_JOINTS_NO  = $this->input->post('TOTAL_OF_JOINTS_NO');
            $this->TOTAL_OF_CLAMPS_NO  = $this->input->post('TOTAL_OF_CLAMPS_NO');
            $this->OHL_CROSSING_AREA  = $this->input->post('OHL_CROSSING_AREA');
            $this->OHL_SAG  = $this->input->post('OHL_SAG');
            $this->OHL_EARTH_L_CONDITION  = $this->input->post('OHL_EARTH_L_CONDITION');
            $this->OHL_LENGTH_M  = $this->input->post('OHL_LENGTH_M');
            $this->UGC_VOLTAGE  = $this->input->post('UGC_VOLTAGE');
            $this->R_OHM_PER_KM  = $this->input->post('R_OHM_PER_KM');
            $this->X_OHM_PER_KM  = $this->input->post('X_OHM_PER_KM');
            $this->UGC_STRAIGHT_JOINT_NO  = $this->input->post('UGC_STRAIGHT_JOINT_NO');
            $this->UGC_TERMINATION_KIT_NO  = $this->input->post('UGC_TERMINATION_KIT_NO');
            $this->UGC_START_LIGHTNING  = $this->input->post('UGC_START_LIGHTNING');
            $this->UGC_END_LIGHTNING  = $this->input->post('UGC_END_LIGHTNING');
            $this->UGC_START_PROTECTION  = $this->input->post('UGC_START_PROTECTION');
            $this->UGC_END_PROTECTION  = $this->input->post('UGC_END_PROTECTION');
            $this->UGC_LENGTH_M  = $this->input->post('UGC_LENGTH_M');
            $this->NETWORK_DESC  = $this->input->post('NETWORK_DESC');
            $this->S_MV_POLE_ROOM_CODE  = $this->input->post('S_MV_POLE_ROOM_CODE');
            $this->E_MV_POLE_ROOM_CODE  = $this->input->post('E_MV_POLE_ROOM_CODE');
            $this->GOVERNORAT  = $this->input->post('GOVERNORAT');
            $this->DISTRICT_NAME  = $this->input->post('DISTRICT_NAME');
            $this->STREET_NAME  = $this->input->post('STREET_NAME');
            $this->STREET_NO  = $this->input->post('STREET_NO');
            $this->DOC_DATE  = $this->input->post('DOC_DATE');
            $this->DATA_DOC_TEAM  = $this->input->post('DATA_DOC_TEAM');
           $this->DATA_ENTRY_TEAM  = $this->input->post('DATA_ENTRY_TEAM');
            $this->SERVICE  = $this->input->post('SERVICE');
            $this->LINE_NAME  = $this->input->post('LINE_NAME');
            $this->PROJECT_NO  = $this->input->post('PROJECT_NO');
            $this->NOTES  = $this->input->post('NOTES');
            $this->PHOTO_PATH  = $this->input->post('PHOTO_PATH');
    }
    function index($page= 1,$ID1= -1,$NETWORK_INST_TYPE= -1,$PHASES_COND_MATERIAL= -1,$PHASE_COND_TYPE= -1,
                   $EARTH_L_COND_MATERIAL= -1,$EARTH_L_COND_TYPE= -1,$TOTAL_OF_JOINTS_NO= -1,
                   $TOTAL_OF_CLAMPS_NO= -1,$OHL_CROSSING_AREA= -1,$OHL_SAG= -1,$OHL_EARTH_L_CONDITION= -1,
                   $OHL_LENGTH_M= -1,$UGC_VOLTAGE= -1,$R_OHM_PER_KM= -1,$X_OHM_PER_KM= -1,
                   $UGC_STRAIGHT_JOINT_NO= -1,$UGC_TERMINATION_KIT_NO= -1,$UGC_START_LIGHTNING= -1,
                   $UGC_END_LIGHTNING= -1,$UGC_START_PROTECTION= -1,$UGC_END_PROTECTION= -1,$UGC_LENGTH_M= -1,
                   $NETWORK_DESC= -1,$S_MV_POLE_ROOM_CODE= -1,$E_MV_POLE_ROOM_CODE= -1,$GOVERNORAT= -1,$DISTRICT_NAME= -1,
                   $STREET_NAME= -1,$STREET_NO= -1,$DOC_DATE= -1,$DATA_DOC_TEAM=-1,$DATA_ENTRY_TEAM=-1,$SERVICE=-1,$LINE_NAME=-1,
                   $PROJECT_NO=-1,$NOTES=-1,$PHOTO_PATH=-1){
        $data['content'] = 'Network';
        $data['title'] = 'الشبكات';
        $data['page']=$page;
        $data['ID1']=$ID1;
        $data['NETWORK_INST_TYPE']=$NETWORK_INST_TYPE;
        $data['PHASES_COND_MATERIAL']=$PHASES_COND_MATERIAL;
        $data['PHASE_COND_TYPE']=$PHASE_COND_TYPE;
        $data['EARTH_L_COND_MATERIAL']=$EARTH_L_COND_MATERIAL;
        $data['EARTH_L_COND_TYPE']=$EARTH_L_COND_TYPE;
        $data['TOTAL_OF_JOINTS_NO']=$TOTAL_OF_JOINTS_NO;
        $data['TOTAL_OF_CLAMPS_NO']=$TOTAL_OF_CLAMPS_NO;
        $data['OHL_CROSSING_AREA']=$OHL_CROSSING_AREA;
        $data['OHL_SAG']=$OHL_SAG;
        $data['OHL_EARTH_L_CONDITION']=$OHL_EARTH_L_CONDITION;
        $data['OHL_LENGTH_M']=$OHL_LENGTH_M;
        $data['UGC_VOLTAGE']=$UGC_VOLTAGE;
        $data['R_OHM_PER_KM']=$R_OHM_PER_KM;
        $data['X_OHM_PER_KM']=$X_OHM_PER_KM;
        $data['UGC_STRAIGHT_JOINT_NO']=$UGC_STRAIGHT_JOINT_NO;
        $data['UGC_TERMINATION_KIT_NO']=$UGC_TERMINATION_KIT_NO;
        $data['UGC_START_LIGHTNING']=$UGC_START_LIGHTNING;
        $data['UGC_END_LIGHTNING']=$UGC_END_LIGHTNING;
        $data['UGC_START_PROTECTION']=$UGC_START_PROTECTION;
        $data['UGC_END_PROTECTION']=$UGC_END_PROTECTION;
        $data['UGC_LENGTH_M']=$UGC_LENGTH_M;
        $data['NETWORK_DESC']=$NETWORK_DESC;
        $data['S_MV_POLE_ROOM_CODE']=$S_MV_POLE_ROOM_CODE;
        $data['E_MV_POLE_ROOM_CODE']=$E_MV_POLE_ROOM_CODE;
        $data['GOVERNORAT']=$GOVERNORAT;
        $data['DISTRICT_NAME']=$DISTRICT_NAME;
        $data['STREET_NAME']=$STREET_NAME;
        $data['STREET_NO']=$STREET_NO;
        $data['DOC_DATE']=$DOC_DATE;
        $data['DATA_DOC_TEAM']=$DATA_DOC_TEAM;
        $data['DATA_ENTRY_TEAM']=$DATA_ENTRY_TEAM;
        $data['SERVICE']=$SERVICE;
        $data['LINE_NAME']=$LINE_NAME;
        $data['PROJECT_NO']=$PROJECT_NO;
        $data['NOTES']=$NOTES;
        $data['PHOTO_PATH']=$PHOTO_PATH;
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
    /**********************************get_page_network function*********************************************/
   public function public_get_list_network($page= 1,$S_MV_POLE_ROOM_CODE=-1,$E_MV_POLE_ROOM_CODE=-1,$NETWORK_INST_TYPE=-1){
        $this->load->library('pagination');
        $S_MV_POLE_ROOM_CODE=$this->check_vars($S_MV_POLE_ROOM_CODE,'S_MV_POLE_ROOM_CODE');
        $E_MV_POLE_ROOM_CODE=$this->check_vars($E_MV_POLE_ROOM_CODE,'E_MV_POLE_ROOM_CODE');
        $NETWORK_INST_TYPE=$this->check_vars($NETWORK_INST_TYPE,'NETWORK_INST_TYPE');
        /***************************where_sql*******************************/
        $where_sql= "where  1=1";
        $where_sql.= ($S_MV_POLE_ROOM_CODE!= null)? " and S_MV_POLE_ROOM_CODE= '{$S_MV_POLE_ROOM_CODE}' " : '';
        $where_sql.= ($E_MV_POLE_ROOM_CODE!= null)? " and E_MV_POLE_ROOM_CODE= '{$E_MV_POLE_ROOM_CODE}' " : '';
        $where_sql.= ($NETWORK_INST_TYPE!= null)? " and NETWORK_INST_TYPE= '{$NETWORK_INST_TYPE}' " : '';
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_network($where_sql,$offset,$row);
        $data['offset']=$offset+1;
        $data['page']=$page;
        $this->load->view('network_show',$data);
    }
    /************************************************************************************/

    function get_network_info($id){
//$id='1';
        $result= $this->{$this->MODEL_NAME}->get_network($id);
        $data['network_data'] = $result[0];
        $data['content'] = 'add_network';
        $data['title'] = 'عرض تفاصيل الشبكة';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);
    }
    /*********************************************************************************/

    function _postedData_info($type=null,$ID1,$NETWORK_INST_TYPE,$PHASES_COND_MATERIAL,$PHASE_COND_TYPE,
                              $EARTH_L_COND_MATERIAL,$EARTH_L_COND_TYPE,$TOTAL_OF_JOINTS_NO,
                              $TOTAL_OF_CLAMPS_NO,$OHL_CROSSING_AREA,$OHL_SAG,$OHL_EARTH_L_CONDITION,
                              $OHL_LENGTH_M,$UGC_VOLTAGE,$R_OHM_PER_KM,$X_OHM_PER_KM,
                              $UGC_STRAIGHT_JOINT_NO,$UGC_TERMINATION_KIT_NO,$UGC_START_LIGHTNING,
                              $UGC_END_LIGHTNING,$UGC_START_PROTECTION,$UGC_END_PROTECTION,$UGC_LENGTH_M,
                              $NETWORK_DESC,$S_MV_POLE_ROOM_CODE,$E_MV_POLE_ROOM_CODE,$GOVERNORAT,$DISTRICT_NAME,
                              $STREET_NAME,$STREET_NO,$DOC_DATE,$DATA_DOC_TEAM,$DATA_ENTRY_TEAM,$SERVICE,$LINE_NAME,
                              $PROJECT_NO,$NOTES,$PHOTO_PATH)
    {
        $result = array(

            array('name'=>'ID1','value'=>$ID1,'type'=>'','length'=>-1),
            array('name'=>'NETWORK_INST_TYPE','value'=>$NETWORK_INST_TYPE,'type'=>'','length'=>-1),
            array('name'=>'PHASES_COND_MATERIAL','value'=>$PHASES_COND_MATERIAL,'type'=>'','length'=>-1),
           array('name'=>'PHASE_COND_TYPE','value'=>$PHASE_COND_TYPE,'type'=>'','length'=>-1),
           array('name'=>'EARTH_L_COND_MATERIAL','value'=>$EARTH_L_COND_MATERIAL,'type'=>'','length'=>-1),
             array('name'=>'EARTH_L_COND_TYPE','value'=>$EARTH_L_COND_TYPE,'type'=>'','length'=>-1),
            array('name'=>'TOTAL_OF_JOINTS_NO','value'=>$TOTAL_OF_JOINTS_NO,'type'=>'','length'=>-1),
             array('name'=>'TOTAL_OF_CLAMPS_NO','value'=>$TOTAL_OF_CLAMPS_NO,'type'=>'','length'=>-1),
            array('name'=>'OHL_CROSSING_AREA','value'=>$OHL_CROSSING_AREA,'type'=>'','length'=>-1),
            array('name'=>'OHL_SAG','value'=>$OHL_SAG,'type'=>'','length'=>-1),
             array('name'=>'OHL_EARTH_L_CONDITION','value'=>$OHL_EARTH_L_CONDITION,'type'=>'','length'=>-1),
             array('name'=>'OHL_LENGTH_M','value'=>$OHL_LENGTH_M,'type'=>'','length'=>-1),
            array('name'=>'UGC_VOLTAGE','value'=>$UGC_VOLTAGE,'type'=>'','length'=>-1),
            array('name'=>'R_OHM_PER_KM','value'=>$R_OHM_PER_KM,'type'=>'','length'=>-1),
            array('name'=>'X_OHM_PER_KM','value'=>$X_OHM_PER_KM,'type'=>'','length'=>-1),






            array('name'=>'UGC_STRAIGHT_JOINT_NO','value'=>$UGC_STRAIGHT_JOINT_NO,'type'=>'','length'=>-1),


             array('name'=>'UGC_TERMINATION_KIT_NO','value'=>$UGC_TERMINATION_KIT_NO,'type'=>'','length'=>-1),
             array('name'=>'UGC_START_LIGHTNING','value'=>$UGC_START_LIGHTNING,'type'=>'','length'=>-1),
            array('name'=>'UGC_END_LIGHTNING','value'=>$UGC_END_LIGHTNING,'type'=>'','length'=>-1),
            array('name'=>'UGC_START_PROTECTION','value'=>$UGC_START_PROTECTION,'type'=>'','length'=>-1),
            array('name'=>'UGC_END_PROTECTION','value'=>$UGC_END_PROTECTION,'type'=>'','length'=>-1),
            array('name'=>'UGC_LENGTH_M','value'=>$UGC_LENGTH_M,'type'=>'','length'=>-1),
             array('name'=>'NETWORK_DESC','value'=>$NETWORK_DESC,'type'=>'','length'=>-1),
            array('name'=>'S_MV_POLE_ROOM_CODE','value'=>$S_MV_POLE_ROOM_CODE,'type'=>'','length'=>-1),
            array('name'=>'E_MV_POLE_ROOM_CODE','value'=>$E_MV_POLE_ROOM_CODE,'type'=>'','length'=>-1),
            array('name'=>'GOVERNORAT','value'=>$GOVERNORAT,'type'=>'','length'=>-1),
            array('name'=>'DISTRICT_NAME','value'=>$DISTRICT_NAME,'type'=>'','length'=>-1),
            array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),

            array('name'=>'STREET_NO','value'=>$STREET_NO,'type'=>'','length'=>-1),
            array('name'=>'DOC_DATE','value'=>$DOC_DATE,'type'=>'','length'=>-1),
            array('name'=>'DATA_DOC_TEAM','value'=>$DATA_DOC_TEAM,'type'=>'','length'=>-1),
            array('name'=>'DATA_ENTRY_TEAM','value'=>$DATA_ENTRY_TEAM,'type'=>'','length'=>-1),


            array('name'=>'SERVICE','value'=>$SERVICE,'type'=>'','length'=>-1),
            array('name'=>'LINE_NAME','value'=>$LINE_NAME,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_NO','value'=>$PROJECT_NO,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$NOTES,'type'=>'','length'=>-1),
            array('name'=>'PHOTO_PATH','value'=>$PHOTO_PATH,'type'=>'','length'=>-1),

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
                ('',$this->ID1,$this->NETWORK_INST_TYPE,$this->PHASES_COND_MATERIAL,$this->PHASE_COND_TYPE,
                    $this->EARTH_L_COND_MATERIAL,$this->EARTH_L_COND_TYPE,$this->TOTAL_OF_JOINTS_NO,
                    $this->TOTAL_OF_CLAMPS_NO,$this->OHL_CROSSING_AREA,$this->OHL_SAG,$this->OHL_EARTH_L_CONDITION,
                    $this->OHL_LENGTH_M,$this->UGC_VOLTAGE,$this->R_OHM_PER_KM,$this->X_OHM_PER_KM,
                    $this->UGC_STRAIGHT_JOINT_NO,$this->UGC_TERMINATION_KIT_NO,$this->UGC_START_LIGHTNING,
                    $this->UGC_END_LIGHTNING,$this->UGC_START_PROTECTION,$this->UGC_END_PROTECTION,$this->UGC_LENGTH_M,
                    $this->NETWORK_DESC,$this->S_MV_POLE_ROOM_CODE,$this->E_MV_POLE_ROOM_CODE,$this->GOVERNORAT,$this->DISTRICT_NAME,
                    $this->STREET_NAME,$this->STREET_NO,$this->DOC_DATE,$this->DATA_DOC_TEAM,$this->DATA_ENTRY_TEAM,$this->SERVICE,$this->LINE_NAME,
                    $this->PROJECT_NO,$this->NOTES,$this->PHOTO_PATH)));

           /* if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }*/
            //die;
            echo intval($this->ser);
        }
    }

}