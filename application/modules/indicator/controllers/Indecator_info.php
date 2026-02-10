<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/09/18
 * Time: 12:48 م
 */

class indecator_info extends MY_Controller{

    var $MODEL_NAME= 'indicator_model';
    var $DETAILS_MODEL_NAME = 'indecator_calc_model';
    var $PAGE_URL= 'indicator/indicate_target/get_page';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->year= $this->budget_year;
        $this->ser=$this->input->post('ser');

    }
    /******************************************************************************************************************************************************/
    /*                                                             Manage Indicator                                                                      */
    /******************************************************************************************************************************************************/



    function index()
    {


        $data['title']='ادارة وثيقة المؤشرات ';
        $data['content']='indicate_info_index';
        $data['help']=$this->help;
        //$data['year_indicator']=$this->year;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);




    }
    /***************************************************************************************************************************************/

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = " AND  EFFECT=1 ";

        $where_sql .= isset($this->p_sector) && $this->p_sector!= null ? " AND  SECTOR  ={$this->p_sector}  " : "";
        $where_sql .= isset($this->p_class) && $this->p_class!= null ? " AND  CLASS  ={$this->p_class}  " : "";
        $where_sql .= isset($this->p_second_class) && $this->p_second_class!= null ? " AND  SECOND_CLASS  ={$this->p_second_class}  " : "";
        $where_sql .= isset($this->p_indecator_flag) && $this->p_indecator_flag!= null ? " AND  INDECATOR_FLAGE  ={$this->p_indecator_flag}  " : "";
        $where_sql .= isset($this->p_adopt) && $this->p_adopt!= null ? " AND  ADOPT  ={$this->p_adopt}  " : "";
        $where_sql .= isset($this->p_entry_target_way) && $this->p_entry_target_way!= null ? " AND  ENTERY_TARGET_WAY  ={$this->p_entry_target_way}  " : "";
        $where_sql .= isset($this->p_indecator_name) && $this->p_indecator_name != null ? " AND  INDECATOR_NAME LIKE '%{$this->p_indecator_name}%' " : "";


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('INDECATOR_INFO_TB', $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = (((($page) - 1) * $config['per_page']));
        $row = ((($page) * $config['per_page']));
        $data['page_rows']=$this->{$this->MODEL_NAME}->get_list_indicatior($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('indicatior_page', $data);


    }

    /***********************************************************************************************/
    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->_post_target_validation();
            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData_info
                ('create',$this->p_indecator_ser,$this->p_sector,$this->p_class,$this->p_indecator_order,
                    $this->p_indecator_name,/*$this->p_effect*/1,$this->p_indecator_flag,$this->p_unit,$this->p_scale,
                    $this->p_period,$this->p_note,$this->p_branches,$this->p_manage_follow_id,$this->p_cycle_follow_id,
                    $this->p_is_target,$this->p_entry_target_way,$this->p_entry_target_time,$this->p_effect_flag,
                    $this->p_effect_value,$this->p_weight,$this->p_equation_target));
            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->ser);
            }
            if($this->p_entery_value_way == 2){
                for ($i = 0; $i < count($this->p_ser_calc); $i++)
                {

                    $x=$this->{$this->DETAILS_MODEL_NAME}->create_calc_indecatior($this->_postedData_calc('create',null, $this->p_indecator_ser_val[$i], $this->p_for_month_calc[$i],$this->p_sumarize[$i],$this->p_oper1[$i],$this->p_is_value[$i],
                        $this->p_value_calc[$i],$this->p_oper2[$i],$this->p_calc_type[$i],$this->ser));
                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }



                }
            }
            if($this->p_entry_target_way == 2){
                for ($i = 0; $i< count($this->p_target_ser_calc); $i++)
                {


                    if ($this->p_target_ser_calc[$i] <= 0 || $this->p_target_ser_calc[$i]=='' ) {


                        $insert_calc_target=$this->{$this->DETAILS_MODEL_NAME}->create_calc_indecatior($this->_postedData_calc('create',null, $this->p_target_indecator_ser[$i], $this->p_target_for_month_calc[$i],$this->p_target_sumarize[$i],$this->p_target_oper1[$i],$this->p_target_is_value[$i],
                            $this->p_target_value_calc[$i],$this->p_target_oper2[$i],$this->p_target_calc_type[$i],$this->ser));
                        if (intval($insert_calc_target) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $insert_calc_target);
                        }

                    }


                }

            }
            echo intval($this->ser);
        }
        else {
            $result=array();
            $data['year_paln']=$this->year;
            $data['indicatior_data']=$result;
            $data['content'] = 'indicatiors';
            $data['title'] = 'نظام المعلومات ومؤشرات الأداء';
            $data['action'] = 'index';
            $data['help']=$this->help;
            $data['isCreate']= true;
            $data['tech']= 0;
			$data['heddin']='';
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }
    /********************************************************************************/
    function get_indecator_info($id)
    {
        $result= $this->{$this->MODEL_NAME}->get_indecator($id);
        $data['indicatior_data'] = $result;
        $data['content'] = 'indicatiors';
        $data['title'] = 'وثيقة مؤشرات الاداء';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
		$data['heddin']='hidden';
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    /*******************************/


    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->_post_target_validation();
            $this->ser  = $this->{$this->MODEL_NAME}->edit(($this->_postedData_info
                ('',$this->p_indecator_ser,$this->p_sector,$this->p_class,$this->p_indecator_order,
                    $this->p_indecator_name,/*$this->p_effect*/1,$this->p_indecator_flag,$this->p_unit,$this->p_scale,
                    $this->p_period,$this->p_note,$this->p_branches,$this->p_manage_follow_id,$this->p_cycle_follow_id,
                    $this->p_is_target,$this->p_entry_target_way,$this->p_entry_target_time,$this->p_effect_flag,
                    $this->p_effect_value,$this->p_weight,$this->p_equation_target,$this->p_second_class,$this->p_entery_value_way,$this->p_equation_value,'','')));
            //var_dump($this->_postedData_withoutcost());
            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            if($this->p_entery_value_way == 2){

                for ($j = 0; $j< count($this->p_ser_calc); $j++)
                {


                    if ($this->p_ser_calc[$j] <= 0 || $this->p_ser_calc[$j]=='' ) {


                        $y=$this->{$this->DETAILS_MODEL_NAME}->create_calc_indecatior($this->_postedData_calc('create',null, $this->p_indecator_ser_val[$j], $this->p_for_month_calc[$j],$this->p_sumarize[$j],$this->p_oper1[$j],$this->p_is_value[$j],
                            $this->p_value_calc[$j],$this->p_oper2[$j],$this->p_calc_type[$j],$this->ser));
                        if (intval($y) <= 0) {
                            $this->print_error($y);
                        }

                    }
                    else
                    {


                        $x=$this->{$this->DETAILS_MODEL_NAME}->edit_calc_indecatior($this->_postedData_calc('',$this->p_ser_calc[$j], $this->p_indecator_ser_val[$j], $this->p_for_month_calc[$j],$this->p_sumarize[$j],$this->p_oper1[$j],$this->p_is_value[$j],
                            $this->p_value_calc[$j],$this->p_oper2[$j],$this->p_calc_type[$j],$this->p_indecator_calc[$j]));
                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                    }

                }
            }
            if($this->p_entry_target_way == 2){
                for ($i = 0; $i< count($this->p_target_ser_calc); $i++)
                {


                    if ($this->p_target_ser_calc[$i] <= 0 || $this->p_target_ser_calc[$i]=='' ) {


                        $insert_calc_target=$this->{$this->DETAILS_MODEL_NAME}->create_calc_indecatior($this->_postedData_calc('create',null, $this->p_target_indecator_ser[$i], $this->p_target_for_month_calc[$i],$this->p_target_sumarize[$i],$this->p_target_oper1[$i],$this->p_target_is_value[$i],
                            $this->p_target_value_calc[$i],$this->p_target_oper2[$i],$this->p_target_calc_type[$i],$this->ser));
                        if (intval($insert_calc_target) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $insert_calc_target);
                        }

                    }
                    else
                    {


                        $update_calc_target=$this->{$this->DETAILS_MODEL_NAME}->edit_calc_indecatior($this->_postedData_calc('',$this->p_target_ser_calc[$i], $this->p_target_indecator_ser[$i], $this->p_target_for_month_calc[$i],$this->p_target_sumarize[$i],$this->p_target_oper1[$i],$this->p_target_is_value[$i],
                            $this->p_target_value_calc[$i],$this->p_target_oper2[$i],$this->p_target_calc_type[$i],$this->p_target_indecator_calc[$i]));
                        if (intval($update_calc_target) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $update_calc_target);
                        }

                    }

                }
            }
            echo intval($this->ser);
        }
    }
    /*******************************************************************/
    function adopt(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id,2);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتمم الاعتماد";
    }
    /*****************************************************/
    function unadopt (){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id,1);

            if(intval($res) <= 0) {
                $this->print_error('لم يتم الارجاع'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم فك الاعتماد";
    }
    /*************************************************************/
    function flag(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->flag_info($this->p_id,1);

            if(intval($res) <= 0){
                $this->print_error('لم يتم التفعيل '.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم التعيل";
    }
    /*****************************************************/
    function unflag (){

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){

            $res = $this->{$this->MODEL_NAME}->flag_info($this->p_id,2);

            if(intval($res) <= 0) {
                $this->print_error('لم يتم الارجاع'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم فك الاعتماد";
    }
  /*****************************************************/
    function Refreash(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $res =$this->{$this->MODEL_NAME}->Refreash();

            if(intval($res) <= 0) {
                $this->print_error('لم يتم التحديث!!'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم التحديث!!";
    }
    /********************************************************************************************************************************/
    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_js('bootstrap.min.js');
        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');

        $data['sector'] = $this->constant_details_model->get_list(223);
        $data['effect'] = $this->constant_details_model->get_list(228);
        $data['indecator_flag'] = $this->constant_details_model->get_list(229);
        $data['unit'] = $this->constant_details_model->get_list(230);
        $data['scale'] = $this->constant_details_model->get_list(231);
        $data['period'] = $this->constant_details_model->get_list(232);
        $data['is_target'] = $this->constant_details_model->get_list(233);
        $data['entry_target_way'] = $this->constant_details_model->get_list(226);
        $data['entry_target_time'] = $this->constant_details_model->get_list(234);
        $data['effect_flag'] = $this->constant_details_model->get_list(242);
        $data['class'] = $this->constant_details_model->get_list(224);
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['all_sectors']= $this->{$this->MODEL_NAME}->get_sectors('',0);
        $data['adopt_all'] = $this->constant_details_model->get_list(235);
        $data['indecator']= $this->{$this->MODEL_NAME}->get_indecator_calc();
        $data['for_month_calc'] = $this->constant_details_model->get_list(292);
        $data['sumarize'] = $this->constant_details_model->get_list(293);
        $data['oper'] = $this->constant_details_model->get_list(294);
        $data['is_value'] = $this->constant_details_model->get_list(296);




    }






    /*****************************************************************************************************************************/
    function _postedData($type=null,$t_ser,$t_indecator_ser,$t_branch,$t_month,$t_value){


        $result = array(
            array('name'=>'T_SER','value'=>$t_ser ,'type'=>'','length'=>-1),
            array('name'=>'T_INDECATOR_SER','value'=>$t_indecator_ser,'type'=>'','length'=>-1),
            array('name'=>'T_BRANCH','value'=>$t_branch,'type'=>'','length'=>-1),
            array('name'=>'T_MONTH','value'=>$t_month,'type'=>'','length'=>-1),
            array('name'=>'T_VALUE','value'=>$t_value,'type'=>'','length'=>-1),





        );






        if($type=='create'){
            array_shift($result);
        }

        return $result;




    }



    /****************************************************************************************/
    function _postedData_info($type=null,$INDECATOR_SER,$SECTOR,$CLASS,$INDECATOR_ORDER,$INDECATOR_NAME,$EFFECT,
                              $INDECATOR_FLAGE,$UNIT,$SCALE,$PERIOD,$NOTE,$BRANCH,$MANAGE_FOLLOW_ID,
                              $CYCLE_FOLLOW_ID,$IS_TARGET,$ENTERY_TARGET_WAY,$ENTERY_TARGET_TIME,
                              $EFFECT_FLAG,$EFFECT_VALUE,$WEIGHT,$EQUATION_TARGET,$second_class,$entery_value_way,$equation_value,$display_info,$display_rep)
    {

        $result = array(
            array('name'=>'INDECATOR_SER','value'=>$INDECATOR_SER ,'type'=>'','length'=>-1),
            array('name'=>'SECTOR','value'=>$SECTOR,'type'=>'','length'=>-1),
            array('name'=>'CLASS','value'=>$CLASS,'type'=>'','length'=>-1),
            array('name'=>'INDECATOR_ORDER','value'=>$INDECATOR_ORDER,'type'=>'','length'=>-1),
            array('name'=>'INDECATOR_NAME','value'=>$INDECATOR_NAME,'type'=>'','length'=>-1),
            array('name'=>'EFFECT','value'=>$EFFECT,'type'=>'','length'=>-1),
            array('name'=>'INDECATOR_FLAGE','value'=>$INDECATOR_FLAGE,'type'=>'','length'=>-1),
            array('name'=>'UNIT','value'=>$UNIT,'type'=>'','length'=>-1),
            array('name'=>'SCALE','value'=>$SCALE,'type'=>'','length'=>-1),
            array('name'=>'PERIOD','value'=>$PERIOD,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$NOTE,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$BRANCH,'type'=>'','length'=>-1),
            array('name'=>'MANAGE_FOLLOW_ID','value'=>$MANAGE_FOLLOW_ID,'type'=>'','length'=>-1),
            array('name'=>'CYCLE_FOLLOW_ID','value'=>$CYCLE_FOLLOW_ID,'type'=>'','length'=>-1),
            array('name'=>'IS_TARGET','value'=>$IS_TARGET,'type'=>'','length'=>-1),
            array('name'=>'ENTERY_TARGET_WAY','value'=>$ENTERY_TARGET_WAY,'type'=>'','length'=>-1),
            array('name'=>'ENTERY_TARGET_TIME','value'=>$ENTERY_TARGET_TIME,'type'=>'','length'=>-1),
            array('name'=>'EFFECT_FLAG','value'=>$EFFECT_FLAG,'type'=>'','length'=>-1),
            array('name'=>'EFFECT_VALUE','value'=>$EFFECT_VALUE,'type'=>'','length'=>-1),
            array('name'=>'WEIGHT','value'=>$WEIGHT,'type'=>'','length'=>-1),
            array('name'=>'EQUATION_TARGET','value'=>$EQUATION_TARGET,'type'=>'','length'=>-1),
            array('name'=>'USER','value'=>$this->user->id,'type'=>'','length'=>-1),
            array('name'=>'SECOND_CLASS','value'=>$second_class,'type'=>'','length'=>-1),
            array('name'=>'ENTERY_VALUE_WAY','value'=>$entery_value_way,'type'=>'','length'=>-1),
            array('name'=>'EQUATION_VALUE','value'=>$equation_value,'type'=>'','length'=>-1),
            array('name'=>'DISPLAY_INFO','value'=>$display_info,'type'=>'','length'=>-1),
            array('name'=>'DISPLAY_REP','value'=>$display_rep,'type'=>'','length'=>-1)

        );
        if($type=='create'){
            array_shift($result);
        }

        return $result;
    }

/******************************************/

    /************************************************/

    function _postedData_calc($type=null,$ser,$indecator_ser,$for_month,$sumarize,$oper1,$is_value,$value,
                              $oper2,$calc_type,$indecator_calc)
    {

        $result = array(
            array('name'=>'SER_IN','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'INDECATOR_SER_IN','value'=>$indecator_ser,'type'=>'','length'=>-1),
            array('name'=>'FOR_MONTH_IN','value'=>$for_month,'type'=>'','length'=>-1),
            array('name'=>'SUMARIZE_IN','value'=>$sumarize,'type'=>'','length'=>-1),
            array('name'=>'OPER1_IN','value'=>$oper1,'type'=>'','length'=>-1),
            array('name'=>'IS_VALUE_IN','value'=>$is_value,'type'=>'','length'=>-1),
            array('name'=>'VALUE_IN','value'=>$value,'type'=>'','length'=>-1),
            array('name'=>'OPER2_IN','value'=>$oper2,'type'=>'','length'=>-1),
            array('name'=>'CALC_TYPE_IN','value'=>$calc_type,'type'=>'','length'=>-1),
            array('name'=>'INDECATOR_CALC_IN','value'=>$indecator_calc,'type'=>'','length'=>-1),
            array('name'=>'USER','value'=>$this->user->id,'type'=>'','length'=>-1),


        );
        if($type=='create'){
            array_shift($result);
        }

        return $result;
    }
    /************************************************/


    function public_get_details($id = 0,$adopt=1)

    {
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_calc_all($id,1);
        $data['adopt']=$adopt;
        $this->_look_ups($data);
        $this->load->view('indecators_details', $data);
    }


    function public_get_target_details($id = 0,$adopt=1)

    {
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_calc_all($id,2);
        $data['adopt']=$adopt;
        $this->_look_ups($data);
        $this->load->view('indecators_target_details', $data);
    }

    function update_indecator_info_status()

    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!='' ){
            $res = $this->{$this->MODEL_NAME}->update_status_info($this->p_id);

            if(intval($res) <= 0) {
                $this->print_error('لم تتم عملية الاستيراد بنجاح'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم تتم عملية الاستيراد بنجاح";
}

    function public_class_list_p($objective =''){


        $arr = $this->{$this->MODEL_NAME}->get_sectors('',$objective);



        return $arr;





    }
    function public_get_sectors($objective =0){


        $objective = $this->input->post('no')?$this->input->post('no'):$objective;

        $arr = $this->{$this->MODEL_NAME}->get_sectors('',$objective);



        echo json_encode($arr);





    }
function public_permition ()
{
$data['details'] = 0;
 $data['content'] = 'permission';
$this->load->view('template/modal', $data);
}

function indecatior2 ()
{
 $result=array();
            $achive_res=array();
            $data['year_paln']=$this->year;
            $data['indicatior_data']=$result;
            $data['content'] = 'indicatiors2';
            $data['title'] = 'نظام المعلومات ومؤشرات الأداء';
            $data['action'] = 'index';
            $data['help']=$this->help;
            $data['isCreate']= true;
            $data['tech']= 0;
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
}
    function _post_validation(){

        $prev_opre2='';

        if($this->p_entery_value_way == 2){

            for ($i = 0; $i < count($this->p_ser_calc); $i++)
            {

                if ( ($this->p_calc_type[$i] == '' or $this->p_calc_type[$i] ==0) )
                {
                    $this->print_error('خطأ' );
                }

                if($i== 0)
                {

                    if ( ($this->p_indecator_ser_val[$i] == '' or $this->p_indecator_ser_val[$i] ==0) or ($this->p_for_month_calc[$i] == '' or $this->p_for_month_calc[$i] ==0))
                    {
                        $this->print_error('يتوجب عليك ادخال المؤشر و الفترة' );
                    }


                    if ( ($this->p_oper1[$i] != '' or $this->p_oper1[$i] !=0))
                    {


                        if( ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0))
                        {

                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }
                        elseif ( ( $this->p_is_value[$i] ==0))
                        {

                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }
                        /*if ( ($this->p_oper1[$i] == '' or $this->p_oper1[$i] ==0) and ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0) and ($this->p_value_calc[$i] == ''))
                        {


                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }*/

                    }
                    if( ($this->p_is_value[$i] != '' or $this->p_is_value[$i] !=0))
                    {

                        //$this->print_error('kik');
                        if ( ($this->p_oper1[$i] == ''))
                        {



                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }
                        elseif ( ($this->p_value_calc[$i] == '' or $this->p_value_calc[$i] ==0))
                        {
                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }

                    }
                    if( ($this->p_value_calc[$i] != ''))
                    {

                        if ( ($this->p_oper1[$i] == '' ))
                        {


                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }
                        elseif ( ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0))
                        {
                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }

                    }
                    if ( ($this->p_oper2[$i] == '' )  )
                    {
                        if ( ($i < (count($this->p_ser_calc)-1)))
                            $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                    }
                    if ( ($this->p_oper2[$i] != '' ) )
                    {

                        if($i == (count($this->p_ser_calc)-1))
                        {
                            $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                        }

                        $prev_opre2=$this->p_oper2[$i];



                    }
                }

                else
                {
                    if($prev_opre2!='')
                    {

                        if ( ($this->p_indecator_ser_val[$i] != '' or $this->p_indecator_ser_val[$i] !=0) and ($this->p_for_month_calc[$i] != '' or $this->p_for_month_calc[$i] !=0))
                        {
                            if ( ($this->p_oper1[$i] != ''))
                            {
                                if( ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                elseif ( ( $this->p_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                /*if ( ($this->p_oper1[$i] == '' or $this->p_oper1[$i] ==0) and ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0) and ($this->p_value_calc[$i] == ''))
                                {


                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }*/

                            }
                            if ( ($this->p_oper1[$i] != '' ))
                            {
                                if( ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                elseif ( ( $this->p_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                /*if ( ($this->p_oper1[$i] == '' or $this->p_oper1[$i] ==0) and ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0) and ($this->p_value_calc[$i] == ''))
                                {


                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }*/

                            }
                            if( ($this->p_is_value[$i] != '' or $this->p_is_value[$i] !=0))
                            {
                                //$this->print_error('kik');
                                if ( ($this->p_oper1[$i] == '' ))
                                {


                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                elseif ( ($this->p_value_calc[$i] == '' or $this->p_value_calc[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }

                            }
                            if( ($this->p_value_calc[$i] != ''))
                            {
                                if ( ($this->p_oper1[$i] == ''))
                                {


                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                elseif ( ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }

                            }
                            if ( ($this->p_oper2[$i] == null )  )
                            {
                                if ( ($i < (count($this->p_ser_calc)-1)))
                                    $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                            }
                            if ( ($this->p_oper2[$i] != '' ) )
                            {

                                if($i == (count($this->p_ser_calc)-1))
                                {
                                    $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                                }

                                $prev_opre2=$this->p_oper2[$i];



                            }
                        }
                        elseif( (($this->p_indecator_ser_val[$i] != '' or $this->p_indecator_ser_val[$i] !=0) and ($this->p_for_month_calc[$i] == '' or $this->p_for_month_calc[$i] ==0)) or (($this->p_indecator_ser_val[$i] == '' or $this->p_indecator_ser_val[$i] ==0) and ($this->p_for_month_calc[$i] != '' or $this->p_for_month_calc[$i] !=0)) )
                        {
                            $this->print_error('يتوجب عليك ادخال المؤشر و الفترة' );
                        }
                        else
                        {
                            if ( ($this->p_oper1[$i] != '' and $prev_opre2!=''))
                            {
                                $this->print_error('الغاء العملية' );

                            }



                            if( ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0))
                            {
                                $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                            }
                            elseif ( ( $this->p_is_value[$i] ==0))
                            {
                                $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                            }
                            /*if ( ($this->p_oper1[$i] == '' or $this->p_oper1[$i] ==0) and ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0) and ($this->p_value_calc[$i] == ''))
                            {


                                $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                            }*/


                            if( ($this->p_is_value[$i] != '' or $this->p_is_value[$i] !=0))
                            {
                                if ( ($this->p_value_calc[$i] == '' or $this->p_value_calc[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }

                            }
                            if( ($this->p_value_calc[$i] != ''))
                            {
                                if ( ($this->p_is_value[$i] == '' or $this->p_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }

                            }
                            if ( ($this->p_oper2[$i] == '' )  )
                            {
                                if ( ($i < (count($this->p_ser_calc)-1)))
                                    $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                            }
                            if ( ($this->p_oper2[$i] != '' ) )
                            {

                                if($i == (count($this->p_ser_calc)-1))
                                {
                                    $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                                }

                                $prev_opre2=$this->p_oper2[$i];



                            }
                        }

                    }
                }



            }
        }

    }

    function _post_target_validation(){

        $prev_opre2='';

        if($this->p_entry_target_way == 2){

            for ($i = 0; $i < count($this->p_target_ser_calc); $i++)
            {

                if ( ($this->p_target_calc_type[$i] == '' or $this->p_target_calc_type[$i] ==0) )
                {
                    $this->print_error('خطأ' );
                }

                if($i== 0)
                {

                    if ( ($this->p_target_indecator_ser[$i] == '' or $this->p_target_indecator_ser[$i] ==0) or ($this->p_target_for_month_calc[$i] == '' or $this->p_target_for_month_calc[$i] ==0))
                    {
                        $this->print_error('يتوجب عليك ادخال المؤشر و الفترة' );
                    }


                    if ( ($this->p_target_oper1[$i] != '' or $this->p_target_oper1[$i] !=0))
                    {


                        if( ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0))
                        {

                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }
                        elseif ( ( $this->p_target_is_value[$i] ==0))
                        {

                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }
                        /*if ( ($this->p_target_oper1[$i] == '' or $this->p_target_oper1[$i] ==0) and ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0) and ($this->p_target_value_calc[$i] == ''))
                        {


                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }*/

                    }
                    if( ($this->p_target_is_value[$i] != '' or $this->p_target_is_value[$i] !=0))
                    {

                        //$this->print_error('kik');
                        if ( ($this->p_target_oper1[$i] == ''))
                        {



                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }
                        elseif ( ($this->p_target_value_calc[$i] == '' or $this->p_target_value_calc[$i] ==0))
                        {
                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }

                    }
                    if( ($this->p_target_value_calc[$i] != ''))
                    {

                        if ( ($this->p_target_oper1[$i] == '' ))
                        {


                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }
                        elseif ( ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0))
                        {
                            $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                        }

                    }
                    if ( ($this->p_target_oper2[$i] == '' )  )
                    {
                        if ( ($i < (count($this->p_target_ser_calc)-1)))
                            //$this->print_error('kik');

                            $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                    }
                    if ( ($this->p_target_oper2[$i] != '' ) )
                    {

                        if($i == (count($this->p_target_ser_calc)-1))
                        {
                           // $this->print_error('kik');
                            $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                        }

                        $prev_opre2=$this->p_target_oper2[$i];



                    }
                }

                else
                {
                    if($prev_opre2!='')
                    {

                        if ( ($this->p_target_indecator_ser[$i] != '' or $this->p_target_indecator_ser[$i] !=0) and ($this->p_target_for_month_calc[$i] != '' or $this->p_target_for_month_calc[$i] !=0))
                        {
                            if ( ($this->p_target_oper1[$i] != ''))
                            {
                                if( ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                elseif ( ( $this->p_target_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                /*if ( ($this->p_target_oper1[$i] == '' or $this->p_target_oper1[$i] ==0) and ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0) and ($this->p_target_value_calc[$i] == ''))
                                {


                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }*/

                            }
                            if ( ($this->p_target_oper1[$i] != '' ))
                            {
                                if( ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                elseif ( ( $this->p_target_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                /*if ( ($this->p_target_oper1[$i] == '' or $this->p_target_oper1[$i] ==0) and ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0) and ($this->p_target_value_calc[$i] == ''))
                                {


                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }*/

                            }
                            if( ($this->p_target_is_value[$i] != '' or $this->p_target_is_value[$i] !=0))
                            {
                                //$this->print_error('kik');
                                if ( ($this->p_target_oper1[$i] == '' ))
                                {


                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                elseif ( ($this->p_target_value_calc[$i] == '' or $this->p_target_value_calc[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }

                            }
                            if( ($this->p_target_value_calc[$i] != ''))
                            {
                                if ( ($this->p_target_oper1[$i] == ''))
                                {


                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }
                                elseif ( ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }

                            }
                            if ( ($this->p_target_oper2[$i] == null )  )
                            {
                                if ( ($i < (count($this->p_target_ser_calc)-1)))
                                    $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                            }
                            if ( ($this->p_target_oper2[$i] != '' ) )
                            {

                                if($i == (count($this->p_target_ser_calc)-1))
                                {
                                    $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                                }

                                $prev_opre2=$this->p_target_oper2[$i];



                            }
                        }
                        elseif( (($this->p_target_indecator_ser[$i] != '' or $this->p_target_indecator_ser[$i] !=0) and ($this->p_target_for_month_calc[$i] == '' or $this->p_target_for_month_calc[$i] ==0)) or (($this->p_target_indecator_ser[$i] == '' or $this->p_target_indecator_ser[$i] ==0) and ($this->p_target_for_month_calc[$i] != '' or $this->p_target_for_month_calc[$i] !=0)) )
                        {
                            $this->print_error('يتوجب عليك ادخال المؤشر و الفترة' );
                        }
                        else
                        {
                            if ( ($this->p_target_oper1[$i] != '' and $prev_opre2!=''))
                            {
                                $this->print_error('الغاء العملية' );

                            }



                            if( ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0))
                            {
                                $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                            }
                            elseif ( ( $this->p_target_is_value[$i] ==0))
                            {
                                $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                            }
                            /*if ( ($this->p_target_oper1[$i] == '' or $this->p_target_oper1[$i] ==0) and ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0) and ($this->p_target_value_calc[$i] == ''))
                            {


                                $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                            }*/


                            if( ($this->p_target_is_value[$i] != '' or $this->p_target_is_value[$i] !=0))
                            {
                                if ( ($this->p_target_value_calc[$i] == '' or $this->p_target_value_calc[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }

                            }
                            if( ($this->p_target_value_calc[$i] != ''))
                            {
                                if ( ($this->p_target_is_value[$i] == '' or $this->p_target_is_value[$i] ==0))
                                {
                                    $this->print_error('يتوجب عليك ادخال عملية و عملية على نسبة أو قيمة و نسبة معا' );
                                }

                            }
                            if ( ($this->p_target_oper2[$i] == '' )  )
                            {
                                if ( ($i < (count($this->p_target_ser_calc)-1)))
                                    $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                            }
                            if ( ($this->p_target_oper2[$i] != '' ) )
                            {

                                if($i == (count($this->p_target_ser_calc)-1))
                                {
                                    $this->print_error('لا يتوجب عليك سجل احتساب او الغاء اخر حقل خاص بالعملية' );
                                }

                                $prev_opre2=$this->p_target_oper2[$i];



                            }
                        }

                    }
                }



            }
        }

    }
}

?>
