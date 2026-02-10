<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 30/10/19
 * Time: 01:30 م
 */
class indicate_data extends MY_Controller{

    var $MODEL_NAME= 'indicator_model';
    var $PAGE_URL= 'indicator/indicate_data/get_page_data';
    var $PAGE_DISPLAY_URL= 'indicator/indicate_data/get_page_display_data';

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



    function data()
    {


        $data['title']='ادخال المحقق';
        $data['content']='indicate_data';
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);




    }
/**************************************************************************************************************************************/
    function get_page_data($page = 1)
    {

        $from_month=date("Ym");
        $data['branch'] =$this->p_branch;
        $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_data_manual($this->p_sector,$this->p_txt_for_month,$this->p_entry_way,$this->p_branch);
        /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
         die;*/
        $this->_look_ups($data);


        $this->load->view('indicator_data_page', $data);



    }
    /********************************************************************************************************************************/
    function public_get_is_adopt()
    {

        $result = $this->{$this->MODEL_NAME}->indecator_data_tb_is_adopt($this->p_sector,$this->p_txt_for_month,$this->p_entry_way,$this->p_branch);
        $this->return_json($result);

    }
    /*************************************************************************************************************************************/
    /********************************************************************************************************************************/
    function adopt()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_sector != '' ||$this->p_sector != 0) ) {
            $res = $this->{$this->MODEL_NAME}->adopt_data($this->p_sector,$this->p_txt_for_month,$this->p_entry_way,$this->p_adopt,$this->user->id,$this->p_branch);

            if (intval($res) <= 0) {
                echo intval($res);
            }
            else
                echo intval($res);

        } else
            echo "لم يتم ارسال رقم القطاع";
    }
    /********************************************************************************************************************************/
    function unadopt()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_sector != '' ||$this->p_sector != 0)) {

            $res = $this->{$this->MODEL_NAME}->adopt_data($this->p_sector,$this->p_txt_for_month,$this->p_entry_way,$this->p_adopt,$this->user->id,$this->p_branch);

            if (intval($res) <= 0) {
                echo intval($res);
            }
            else
                echo intval($res);

        } else
            echo "لم يتم ارسال رقم القطاع";
    }
    /*******************************************************************************************************************************/
    function save_all_data()
    {
        $x=0;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_indecator_ser); $i++)
            {


                if($this->p_value_branch_seq[$i]==0)
                {
                    $x=$this->{$this->MODEL_NAME}->create_data_branch($this->_postedData('create',null,$this->p_indecator_ser[$i],$this->p_branch,$this->p_for_month,$this->p_txt_value_branch[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }
                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_data_branch($this->_postedData('edit',$this->p_value_branch_seq[$i],$this->p_indecator_ser[$i],$this->p_branch,$this->p_for_month,$this->p_txt_value_branch[$i]));
                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }



            }
            echo intval($x);
        }

    }
    /*******************************************************************/
    /*******************************************************************************************************************************/
    function public_get_sector($sector=0){

        echo  modules::run($this->PAGE_URL);
        /* $sector = $this->input->post('sector')?$this->input->post('sector'):$sector;

          $arr = $this->{$this->MODEL_NAME}->indecator_info_tb_get($sector,date("Ym"));



          echo json_encode($arr);*/





    }
    /*****************************************************************************************************************************/
    function _postedData($type=null,$ser,$indecator_ser,$branch,$month,$value){


        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'INDECATOR_SER','value'=>$indecator_ser,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>'FOR_MONTH','value'=>$month,'type'=>'','length'=>-1),
            array('name'=>'THE_VALUE','value'=>$value,'type'=>'','length'=>-1),
            array('name'=>'USER_ID','value'=>$this->user->id,'type'=>'','length'=>-1),
            array('name'=>'ADOPT','value'=>1,'type'=>'','length'=>-1),





        );



        if($type=='create'){
            array_shift($result);
        }

        return $result;




    }
    /*******************************************************************************************************************************/
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

        $data['sector'] = $this->{$this->MODEL_NAME}->get_sectors('',0);;
        $data['enter_way'] = $this->constant_details_model->get_list(226);
        $data['found_target'] = $this->constant_details_model->get_list(227);
        $data['adopt_const'] = $this->constant_details_model->get_list(235);
        //$data['branches'] = $this->gcc_branches_model->get_all();
        $data['adopt_all'] = $this->constant_details_model->get_list(235);
        if ($this->user->branch==1)
         {


             $data['branches'] = $this->gcc_branches_model->get_all();
         }
         else
         {

             $data['branches'] =$this->gcc_branches_model->user_branch($this->user->id);

         }


    }
}