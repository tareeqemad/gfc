<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 08/12/18
 * Time: 11:32 ص
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class main extends MY_Controller{

    var $MODEL_NAME='poles_model';
    var $PAGE_URL= 'gis/main/get_page';
    var $PAGE_ACT;

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
            $this->ID  = $this->input->post('ID');
            $this->POLE_MATERIAL_ID  = $this->input->post('POLE_MATERIAL_ID');//
            $this->POLE_ID_SER  = $this->input->post('POLE_ID_SER');//
            $this->POLE_COMPONENT_ID  = $this->input->post('POLE_COMPONENT_ID');//
            $this->MMV_POLE_CODE=$this->input->post('MMV_POLE_CODE');
            $this->MV_POLE_TYPE  = $this->input->post('MV_POLE_TYPE');//نوع العمود
            $this->MV_POLE_SIZE  = $this->input->post('MV_POLE_SIZE');//مقاس العمود
            $this->MV_POLE_HEIGHT  = $this->input->post('MV_POLE_HEIGHT');//ارتفاع العمود
            $this->MV_POLE_CONDITION  = $this->input->post('MV_POLE_CONDITION');//حالة العامود
            $this->BASE_TYPE  = $this->input->post('BASE_TYPE');//نوع القاعدة
            $this->BASE_CONDITION  = $this->input->post('BASE_CONDITION');//حالة القاعدة
            $this->POLE_EARTHING  = $this->input->post('POLE_EARTHING');//تأريض الأرض
            $this->SURGE_ARRESTORS_NO  = $this->input->post('SURGE_ARRESTORS_NO');//عدد مانعات الصواعق المستخدمة
            $this->D_SURGE_ARRESTORS_NO  = $this->input->post('D_SURGE_ARRESTORS_NO');//عدد مانعات الصواعق التالفة
            $this->USED_OMEGA_NO  = $this->input->post('USED_OMEGA_NO');//عدد مانعات الأوميجا المستخدمة
            $this->ISOLATING_SWITCHES_NO  = $this->input->post('ISOLATING_SWITCHES_NO');//عدد سكاكين الضغط
            $this->UN_P_INSULATORS_NO=$this->input->post('UN_P_INSULATORS_NO');
            $this->U_P_PORCELAIN_INSULATORS_NO = $this->input->post('U_P_PORCELAIN_INSULATORS_NO');//عدد عوازل تعليق البروسولان المستخدمة
            $this->U_P_POLYMER_INSULATORS_NO  = $this->input->post('U_P_POLYMER_INSULATORS_NO');//عدد عوازل التعليق البولمير المستخدم
            $this->U_PIN_INSULATORS_NO  = $this->input->post(' U_PIN_INSULATORS_NO');//عدد عوازل التعليق البروسولان الغير مستخدم
            $this->B_PIN_INSULATORS_NO  = $this->input->post('  B_PIN_INSULATORS_NO');//عدد عوازل التعليق المكسورة
            $this->U_T_GLASS_INSULATORS_NO  = $this->input->post(' U_T_GLASS_INSULATORS_NO');//عدد عوازل الشد الزجاجية المستخدم
            $this->U_T_POLYMER_INSULATORS_NO  = $this->input->post(' U_T_POLYMER_INSULATORS_NO');//عدد عوازل الشد  البولمير المستخدم
            $this->U_T_PORCELAIN_INSULATORS_NO=$this->input->post('U_T_PORCELAIN_INSULATORS_NO');
            $this->D_T_POLYMER_INSULATORS_NO  = $this->input->post(' D_T_POLYMER_INSULATORS_NO');// عدد عوازل الشد البولمير التالفة
            $this->B_T_PORCELAIN_INSULATORS_NO  = $this->input->post(' B_T_PORCELAIN_INSULATORS_NO');
            $this->B_T_GLASS_INSULATORS_NO=$this->input->post('B_T_GLASS_INSULATORS_NO');
            $this->N_T_GLASS_INSULATORS_NO  = $this->input->post(' N_T_GLASS_INSULATORS_NO');//عدد عوازل الشد الزجاجية الغير مستخدمة
            $this->N_T_PORCELAIN_INSULATORS_NO  = $this->input->post(' N_T_PORCELAIN_INSULATORS_NO');//عدد عوازل الشد البورسلان الغير مستخدمة
            $this->MV_POLE_STAY  = $this->input->post(' MV_POLE_STAY');//ستاي العامود
            $this->SUPPORT_POLE  = $this->input->post(' SUPPORT_POLE');//دعمة عامود
            $this->TYPE_OF_WIRE_PULLING  = $this->input->post(' TYPE_OF_WIRE_PULLING');//نوع سحب السلك
            $this->TENSION_UNIT_NO  = $this->input->post(' TENSION_UNIT_NO');//عدد وحدات الشد
            $this->MV_OH_FEEDERS_NO  = $this->input->post(' MV_OH_FEEDERS_NO');//  عدد الفيدرات الهوائية على العامود
            $this->MV_UG_FEEDERS_NO  = $this->input->post(' MV_UG_FEEDERS_NO');//عدد فيدرات الكوابل الأرضية
            $this->PROJECT_NO  = $this->input->post(' PROJECT_NO');//رقم المشروع
            $this->INSTALLATION_DATE  = $this->input->post(' INSTALLATION_DATE');//تاريخ التركيب
            $this->SERVICE  = $this->input->post(' SERVICE');//  داخل/خارج الخدمة
            $this->GOVERNORATE_NAME   = $this->input->post('GOVERNORATE_NAME ');// المحافظة
            $this->AREA_NO   = $this->input->post(' AREA_NO ');//المنطقة
            $this->STREET_NO   = $this->input->post(' STREET_NO ');//رقم الشارع
            $this->DOCUMENTATION_DATE   = $this->input->post(' DOCUMENTATION_DATE ');//تاريخ التوثيق
            $this->DISTRICT_NAME=$this->input->post('DISTRICT_NAME');
            $this->B_P_INSULATORS_NO=$this->input->post('B_P_INSULATORS_NO');
            $this->USED_ARMS   = $this->input->post(' USED_ARMS ');//الأذرع المستخدمة
            $this->UNUSED_ARMS   = $this->input->post(' UNUSED_ARMS ');//الأذرع الغير مستخدمة
            $this->MISSED_ARMS   = $this->input->post(' MISSED_ARMS ');//الأذرع الناقصة
            $this->STREET_NAME   = $this->input->post(' STREET_NAME ');//اسم الشارع
            $this->DATA_DOCUMENTATION_TEAM   = $this->input->post(' DATA_DOCUMENTATION_TEAM ');//اسم فريق التوثيق
            $this->DATA_ENTRY_TEAM = $this->input->post('  DATA_ENTRY_TEAM ');//اسم فريق الادخال
            $this->NOTES   = $this->input->post(' NOTES ');//ملاحظات*/
            $this->UN_T_GLASS_INSULATORS_NO = $this->input->post('UN_T_GLASS_INSULATORS_NO');
            $this->UN_T_POLYMER_INSULATORS_NO=$this->input->post(' UN_T_POLYMER_INSULATORS_NO');
            $this->TRANSFORMERS_NAMES_AR=$this->input->post('TRANSFORMERS_NAMES_AR');
            $this->PHOTO_PATH=$this->input->post('PHOTO_PATH');



    }

    /******************index function*****************************/
    function index($page= 1,$id= -1,$POLE_MATERIAL_ID= -1,$POLE_COMPONENT_ID= -1,$MV_POLE_TYPE= -1,$MV_POLE_HEIGHT= -1,$MV_POLE_CONDITION= -1,
                   $BASE_TYPE= -1,$BASE_CONDITION= -1,$POLE_EARTHING= -1,$SURGE_ARRESTORS_NO= -1,$D_SURGE_ARRESTORS_NO= -1,$USED_OMEGA_NO= -1,$ISOLATING_SWITCHES_NO= -1,
                   $U_P_PORCELAIN_INSULATORS_NO= -1 ,$U_P_POLYMER_INSULATORS_NO= -1,$U_PIN_INSULATORS_NO= -1,$B_PIN_INSULATORS_NO= -1,$U_T_GLASS_INSULATORS_NO= -1
                   ,$U_T_POLYMER_INSULATORS_NO= -1,$D_T_POLYMER_INSULATORS_NO= -1,$B_T_PORCELAIN_INSULATORS_NO= -1,$N_T_GLASS_INSULATORS_NO= -1,
                   $N_T_PORCELAIN_INSULATORS_NO= -1,$MV_POLE_STAY= -1,$SUPPORT_POLE= -1,$TYPE_OF_WIRE_PULLING= -1,$TENSION_UNIT_NO=-1,$MV_OH_FEEDERS_NO= -1
                   ,$MV_UG_FEEDERS_NO= -1,$PROJECT_NO= -1,$INSTALLATION_DATE= -1,$SERVICE= -1,$GOVERNORATE= -1,$DISTRICT=-1,$STREET_NO=-1,$DOCUMENTATION_DATE= -1
                    ,$USED_ARMS= -1,$UNUSED_ARMS= -1,$MISSED_ARMS= -1,$STREET_NAME= -1,$DATA_DOCUMENTATION_TEAM= -1,$DATA_ENTRY_TEAM= -1,$NOTES= -1,$PHOTO_PATH=-1)
    {
        $data['content'] = 'poles';
        $data['title'] = 'الصفحة الرئيسية';
        $data['page']=$page;
        $data['id']=$id;
        $data['POLE_MATERIAL_ID']=$POLE_MATERIAL_ID;
        $data['POLE_COMPONENT_ID']  = $POLE_COMPONENT_ID;
        $data['MV_POLE_TYPE']  = $MV_POLE_TYPE ;
        $data['MV_POLE_HEIGHT']  = $MV_POLE_HEIGHT;
        $data['MV_POLE_CONDITION']  = $MV_POLE_CONDITION;
        $data['BASE_TYPE']  = $BASE_TYPE;
        $data['BASE_CONDITION']  = $BASE_CONDITION;
        $data['POLE_EARTHING']  = $POLE_EARTHING;
        $data['SURGE_ARRESTORS_NO']  = $SURGE_ARRESTORS_NO;
        $data['D_SURGE_ARRESTORS_NO']  = $D_SURGE_ARRESTORS_NO;
        $data['USED_OMEGA_NO']  = $USED_OMEGA_NO;
        $data['ISOLATING_SWITCHES_NO']  = $ISOLATING_SWITCHES_NO;
        $data['U_P_PORCELAIN_INSULATORS_NO'] = $U_P_PORCELAIN_INSULATORS_NO;
        $data['U_P_POLYMER_INSULATORS_NO']  = $U_P_POLYMER_INSULATORS_NO;
        $data['U_PIN_INSULATORS_NO']  = $U_PIN_INSULATORS_NO;
        $data[' B_PIN_INSULATORS_NO']  = $B_PIN_INSULATORS_NO;
        $data['U_T_GLASS_INSULATORS_NO']  = $U_T_GLASS_INSULATORS_NO;
        $data['U_T_POLYMER_INSULATORS_NO '] = $U_T_POLYMER_INSULATORS_NO;
        $data['D_T_POLYMER_INSULATORS_NO']  = $D_T_POLYMER_INSULATORS_NO;
        $data['B_T_PORCELAIN_INSULATORS_NO']  = $B_T_PORCELAIN_INSULATORS_NO;
        $data['N_T_GLASS_INSULATORS_NO']  = $N_T_GLASS_INSULATORS_NO;
        $data['N_T_PORCELAIN_INSULATORS_NO']  = $N_T_PORCELAIN_INSULATORS_NO;
        $data['MV_POLE_STAY']  = $MV_POLE_STAY;
        $data['SUPPORT_POLE']  = $SUPPORT_POLE;
        $data['TYPE_OF_WIRE_PULLING']  = $TYPE_OF_WIRE_PULLING;
        $data['TENSION_UNIT_NO']  = $TENSION_UNIT_NO;
        $data['MV_OH_FEEDERS_NO']  = $MV_OH_FEEDERS_NO;
        $data['MV_UG_FEEDERS_NO']  = $MV_UG_FEEDERS_NO;
        $data['PROJECT_NO']  = $PROJECT_NO;
        $data['INSTALLATION_DATE']  = $INSTALLATION_DATE;
        $data['SERVICE']  = $SERVICE;
        $data['GOVERNORATE']   = $GOVERNORATE;
        $data['DISTRICT']   = $DISTRICT ;
        $data['STREET_NO']   = $STREET_NO;
        $data['DOCUMENTATION_DATE']   = $DOCUMENTATION_DATE ;
        $data['USED_ARMS']   = $USED_ARMS ;
        $data['UNUSED_ARMS']   = $UNUSED_ARMS ;
        $data['MISSED_ARMS']   = $MISSED_ARMS;
        $data['STREET_NAME']  = $STREET_NAME ;
        $data['DATA_DOCUMENTATION_TEAM']   = $DATA_DOCUMENTATION_TEAM ;
        $data['DATA_ENTRY_TEAM'] = $DATA_ENTRY_TEAM;
        $data['NOTES']   = $NOTES;
        $data['PHOTO_PATH']=$PHOTO_PATH;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);
    }
  /*********************get_page function*******************************/
    function public_get_page($page= 1,$POLE_MATERIAL_ID= -1){
        $this->load->library('pagination');
        $POLE_MATERIAL_ID=$this->check_vars($POLE_MATERIAL_ID,'POLE_MATERIAL_ID');
  /***************************where_sql*******************************/
        $where_sql= "1=1";
        $where_sql.= ($POLE_MATERIAL_ID!= null)? " and POLE_MATERIAL_ID= '{$POLE_MATERIAL_ID}' " : '';
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs =5; //$this->get_table_count('POLES_ALL_FINAL_A'.$where_sql);
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
        $this->load->view('poles_show',$data);
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
    /************************************create function******************************************************/
    function get_poles_info($id){

        $result= $this->{$this->MODEL_NAME}->get_poles($id);
        $data['poles_data'] = $result;
        $data['content'] = 'add_poles';
        $data['title'] = 'عرض العمود';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /******************************************************************************/
    function _postedData_info($type=null,$POLE_MATERIAL_ID,$POLE_ID_SER,$POLE_COMPONENT_ID,$MV_POLE_CODE ,
                              $MV_POLE_TYPE,$MV_POLE_SIZE,$MV_POLE_HEIGHT,$MV_POLE_CONDITION,$BASE_TYPE,
                              $BASE_CONDITION,$USED_ARMS,$UNUSED_ARMS,$MISSED_ARMS,$POLE_EARTHING,
                              $SURGE_ARRESTORS_NO,$D_SURGE_ARRESTORS_NO,$USED_OMEGA_NO,
                              $ISOLATING_SWITCHES_NO,$U_P_PORCELAIN_INSULATORS_NO,$U_P_POLYMER_INSULATORS_NO,
                              $UN_P_INSULATORS_NO,$B_P_INSULATORS_NO,$U_T_GLASS_INSULATORS_NO,$U_T_POLYMER_INSULATORS_NO,
                              $U_T_PORCELAIN_INSULATORS_NO,$B_T_GLASS_INSULATORS_NO,$D_T_POLYMER_INSULATORS_NO,$B_T_PORCELAIN_INSULATORS_NO,
                              $UN_T_GLASS_INSULATORS_NO,$UN_T_POLYMER_INSULATORS_NO,$MV_POLE_STAY,$SUPPORT_POLE,$TYPE_OF_WIRE_PULLING,
                              $TENSION_UNIT_NO,$MV_OH_FEEDERS_NO,$MV_UG_FEEDERS_NO,$SERVICE,$GOVERNORATE_NAME,$AREA_NO,$STREET_NO,
                              $STREET_NAME,$DISTRICT_NAME,$TRANSFORMERS_NAMES_AR,$PROJECT_NO,$NOTES,$PHOTO_PATH)
   {
       $result = array(
           array('name'=>'POLE_MATERIAL_ID','value'=>$POLE_MATERIAL_ID,'type'=>'','length'=>-1),
           array('name'=>'POLE_ID_SER','value'=>$POLE_ID_SER,'type'=>'','length'=>-1),
           array('name'=>'POLE_COMPONENT_ID','value'=>$POLE_COMPONENT_ID,'type'=>'','length'=>-1),
           array('name'=>'MV_POLE_CODE','value'=>$MV_POLE_CODE,'type'=>'','length'=>-1),
           array('name'=>'MV_POLE_TYPE','value'=>$MV_POLE_TYPE,'type'=>'','length'=>-1),
           array('name'=>'MV_POLE_SIZE','value'=>$MV_POLE_SIZE,'type'=>'','length'=>-1),
           array('name'=>'MV_POLE_HEIGHT','value'=>$MV_POLE_HEIGHT,'type'=>'','length'=>-1),
           array('name'=>'MV_POLE_CONDITION','value'=>$MV_POLE_CONDITION,'type'=>'','length'=>-1),
           array('name'=>'BASE_TYPE','value'=>$BASE_TYPE,'type'=>'','length'=>-1),
           array('name'=>'BASE_CONDITION','value'=>$BASE_CONDITION,'type'=>'','length'=>-1),
           array('name'=>'USED_ARMS','value'=>$USED_ARMS,'type'=>'','length'=>-1),
           array('name'=>'UNUSED_ARMS','value'=>$UNUSED_ARMS,'type'=>'','length'=>-1),
           array('name'=>'MISSED_ARMS','value'=>$MISSED_ARMS,'type'=>'','length'=>-1),
           array('name'=>'POLE_EARTHING','value'=>$POLE_EARTHING,'type'=>'','length'=>-1),
           array('name'=>'SURGE_ARRESTORS_NO','value'=>$SURGE_ARRESTORS_NO,'type'=>'','length'=>-1),
           array('name'=>'D_SURGE_ARRESTORS_NO','value'=>$D_SURGE_ARRESTORS_NO,'type'=>'','length'=>-1),
           array('name'=>'USED_OMEGA_NO','value'=>$USED_OMEGA_NO,'type'=>'','length'=>-1),
           array('name'=>'ISOLATING_SWITCHES_NO','value'=>$ISOLATING_SWITCHES_NO,'type'=>'','length'=>-1),
           array('name'=>'U_P_PORCELAIN_INSULATORS_NO','value'=>$U_P_PORCELAIN_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'U_P_POLYMER_INSULATORS_NO','value'=>$U_P_POLYMER_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'UN_P_INSULATORS_NO','value'=>$UN_P_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'B_P_INSULATORS_NO','value'=>$B_P_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'U_T_GLASS_INSULATORS_NO','value'=>$U_T_GLASS_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'U_T_POLYMER_INSULATORS_NO','value'=>$U_T_POLYMER_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'U_T_PORCELAIN_INSULATORS_NO','value'=>$U_T_PORCELAIN_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'B_T_GLASS_INSULATORS_NO','value'=>$B_T_GLASS_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'D_T_POLYMER_INSULATORS_NO','value'=>$D_T_POLYMER_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'B_T_PORCELAIN_INSULATORS_NO','value'=>$B_T_PORCELAIN_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'UN_T_GLASS_INSULATORS_NO','value'=>$UN_T_GLASS_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'UN_T_POLYMER_INSULATORS_NO','value'=>$UN_T_POLYMER_INSULATORS_NO,'type'=>'','length'=>-1),
           array('name'=>'MV_POLE_STAY','value'=>$MV_POLE_STAY,'type'=>'','length'=>-1),
           array('name'=>'MV_POLE_STAY','value'=>$MV_POLE_STAY,'type'=>'','length'=>-1),
           array('name'=>'SUPPORT_POLE','value'=>$SUPPORT_POLE,'type'=>'','length'=>-1),
           array('name'=>'TYPE_OF_WIRE_PULLING','value'=>$TYPE_OF_WIRE_PULLING,'type'=>'','length'=>-1),
           array('name'=>'TENSION_UNIT_NO','value'=>$TENSION_UNIT_NO,'type'=>'','length'=>-1),
           array('name'=>'MV_OH_FEEDERS_NO','value'=>$MV_OH_FEEDERS_NO,'type'=>'','length'=>-1),
           array('name'=>'MV_UG_FEEDERS_NO','value'=>$MV_UG_FEEDERS_NO,'type'=>'','length'=>-1),
           array('name'=>'SERVICE','value'=>$SERVICE,'type'=>'','length'=>-1),
           array('name'=>'GOVERNORATE_NAME','value'=>$GOVERNORATE_NAME,'type'=>'','length'=>-1),
           array('name'=>'AREA_NO','value'=>$AREA_NO,'type'=>'','length'=>-1),
           array('name'=>'STREET_NO','value'=>$STREET_NO,'type'=>'','length'=>-1),
           array('name'=>'STREET_NAME','value'=>$STREET_NAME,'type'=>'','length'=>-1),
           array('name'=>'DISTRICT_NAME','value'=>$DISTRICT_NAME,'type'=>'','length'=>-1),
           array('name'=>'TRANSFORMERS_NAMES_AR','value'=>$TRANSFORMERS_NAMES_AR,'type'=>'','length'=>-1),
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
                ('',$this->ID,$this->POLE_MATERIAL_ID,$this->POLE_ID_SER,$this->POLE_COMPONENT_ID,$this->MMV_POLE_CODE,
                    $this->MV_POLE_TYPE,$this->MV_POLE_SIZE,$this->MV_POLE_HEIGHT,$this->MV_POLE_CONDITION,
                    $this->BASE_TYPE,$this->BASE_CONDITION,$this->USED_ARMS,$this->UNUSED_ARMS,$this->MISSED_ARMS,
                    $this->POLE_EARTHING,$this->SURGE_ARRESTORS_NO,$this->D_SURGE_ARRESTORS_NO,$this->USED_OMEGA_NO,
                    $this->ISOLATING_SWITCHES_NO,$this->U_P_PORCELAIN_INSULATORS_NO,$this->U_P_POLYMER_INSULATORS_NO,
                    $this->UN_P_INSULATORS_NO,$this->B_P_INSULATORS_NO,$this->U_T_GLASS_INSULATORS_NO,$this->U_T_POLYMER_INSULATORS_NO,
                    $this->U_T_PORCELAIN_INSULATORS_NO,$this->B_T_GLASS_INSULATORS_NO,$this->D_T_POLYMER_INSULATORS_NO,
                    $this->B_T_PORCELAIN_INSULATORS_NO,$this->UN_T_GLASS_INSULATORS_NO,$this->UN_T_POLYMER_INSULATORS_NO,$this->MV_POLE_STAY,
                    $this->SUPPORT_POLE,$this->TYPE_OF_WIRE_PULLING,$this->TENSION_UNIT_NO,$this->MV_OH_FEEDERS_NO,$this->MV_UG_FEEDERS_NO,
                    $this->SERVICE,$this->GOVERNORATE_NAME,$this->AREA_NO,$this->STREET_NO,$this->STREET_NAME,$this->DISTRICT_NAME,
                    $this->TRANSFORMERS_NAMES_AR,$this->PROJECT_NO,$this->NOTES,$this->PHOTO_PATH)));
            //var_dump( $this->SER);
            //die();
            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            //die;
            echo intval($this->ser);
        }
    }
 /***********************************Network**************************************/
    function network($page= 1,$Start_MV_Pole_or_Room_Code= -1,$End_MV_Pole_or_Room_Code= -1,$Network_Installation_Type= -1){
        $data['content'] = 'Network';
        $data['title'] = 'الشبكات ';
        $data['page']=$page;
        $data['Start_MV_Pole_or_Room_Code']=$Start_MV_Pole_or_Room_Code;
        $data['End_MV_Pole_or_Room_Code']=$End_MV_Pole_or_Room_Code;
        $data['Network_Installation_Type']=$Network_Installation_Type;
        $this->_lookUps_data($data);
        $this->load->view('template/template',$data);
    }
    /**********************************get_page_network function*********************************************/
    function get_page_network($page= 1,$Start_MV_Pole_or_Room_Code=-1,$End_MV_Pole_or_Room_Code=-1,$Network_Installation_Type=-1){
        $this->load->library('pagination');
        $Start_MV_Pole_or_Room_Code=$this->check_vars($Start_MV_Pole_or_Room_Code,'Start_MV_Pole_or_Room_Code');
        $End_MV_Pole_or_Room_Code=$this->check_vars($End_MV_Pole_or_Room_Code,'End_MV_Pole_or_Room_Code');
        $Network_Installation_Type=$this->check_vars($Network_Installation_Type,'Network_Installation_Type');
        /***************************where_sql*******************************/
        $where_sql= "1=1";
        $where_sql.= ($Start_MV_Pole_or_Room_Code!= null)? " and Start_MV_Pole_or_Room_Code= '{$Start_MV_Pole_or_Room_Code}' " : '';
        $where_sql.= ($End_MV_Pole_or_Room_Code!= null)? " and End_MV_Pole_or_Room_Code= '{$End_MV_Pole_or_Room_Code}' " : '';
        $where_sql.= ($Network_Installation_Type!= null)? " and Network_Installation_Type= '{$Network_Installation_Type}' " : '';
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs =10;
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
    /*****************************************************************/
    function get_network_info($id){
        $result= $this->{$this->MODEL_NAME}->get_poles($id);
        $data['$poles_data'] = $result;
        $data['content'] = 'add_poles';
        $data['title'] = 'عرض العمود';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_lookUps_data($data);
        $this->load->view('template/template', $data);
    }
    /********************************End network****************************************************/








}