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
    var $PAGE_URL= 'indicator/indicate_target/get_page';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
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
        $where_sql = "";

        //$where_sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  M.BRANCH  ={$this->p_branch}  " : "";
      $where_sql .= isset($this->p_sector) && $this->p_sector!= null ? " AND  SECTOR  ={$this->p_sector}  " : "";
              //(SELECT T.INDECATOR_SER FROM INDECATOR_INFO_TB T WHERE T.SECTOR ={$this->p_sector} )  " :  " ";
       /* $where_sql .= isset($this->p_for_month) && $this->p_for_month != null ? " AND M.FOR_MONTH + 1 ={$this->p_for_month} " : " AND M.FOR_MONTH + 1 =". date("Ym");
*/
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

            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData_info
                ('create',$this->p_indecator_ser,$this->p_sector,$this->p_class,$this->p_indecator_order,
                    $this->p_indecator_name,$this->p_effect,$this->p_indecator_flag,$this->p_unit,$this->p_scale,
                    $this->p_period,$this->p_note,$this->p_branches,$this->p_manage_follow_id,$this->p_cycle_follow_id,
                    $this->p_is_target,$this->p_entry_target_way,$this->p_entry_target_time,$this->p_effect_flag,
                    $this->p_effect_value,$this->p_weight,$this->p_equation_target));
            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->ser);
            }
            echo intval($this->ser);
        }
        else {
            $result=array();
            $achive_res=array();
            $data['year_paln']=$this->year;
            $data['indicatior_data']=$result;
            $data['content'] = 'indicatiors';
            $data['title'] = 'وثيقة مؤشرات الاداء';
            $data['action'] = 'index';
            $data['help']=$this->help;
            $data['isCreate']= true;
            $data['tech']= 0;
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
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    /*******************************/


    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$this->_post_validation();
            $this->ser  = $this->{$this->MODEL_NAME}->edit(($this->_postedData_info
                ('',$this->p_indecator_ser,$this->p_sector,$this->p_class,$this->p_indecator_order,
                    $this->p_indecator_name,$this->p_effect,$this->p_indecator_flag,$this->p_unit,$this->p_scale,
                    $this->p_period,$this->p_note,$this->p_branches,$this->p_manage_follow_id,$this->p_cycle_follow_id,
                    $this->p_is_target,$this->p_entry_target_way,$this->p_entry_target_time,$this->p_effect_flag,
                    $this->p_effect_value,$this->p_weight,$this->p_equation_target)));
            //var_dump($this->_postedData_withoutcost());
            if (intval($this->ser ) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' .$this->ser );
            }
            //die;

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
                              $EFFECT_FLAG,$EFFECT_VALUE,$WEIGHT,$EQUATION_TARGET)
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
            array('name'=>'USER','value'=>$this->user->id,'type'=>'','length'=>-1)

        );
        if($type=='create'){
            array_shift($result);
        }

        return $result;
    }
}

?>
