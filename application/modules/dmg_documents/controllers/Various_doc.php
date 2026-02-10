<?php

//if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/07/19
 * Time: 10:07 ص
 */

class various_doc extends MY_Controller
{

    var $PKG_NAME = "DMG_PKG";

    function __construct()
    {
        parent::__construct();

        $this->group_person_id= $this->input->post('group_person_id');
        $this->group_person_date= $this->input->post('group_person_date');
        $this->group_ser= $this->input->post('h_group_ser');
        $this->group_person_id= $this->input->post('group_person_id');
        $this->group_person_date= $this->input->post('group_person_date');
        $this->group_ser= $this->input->post('h_group_ser');

        $this->emp_no= $this->input->post('emp_no');
        $this->status= $this->input->post('status');
        $this->member_note= $this->input->post('member_note');

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'DMG_PKG';
        //this for constant using
        $this->load->model('stores/store_committees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model("stores/receipt_class_input_group_model");
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
    }

    /************************************index*********************************************/

    function index($page = 1)
    {

    }

    /*************************************_lookup****************************************/

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['model_type'] = $this->constant_details_model->get_list(275);
        $data['doc_type'] = $this->constant_details_model->get_list(277);

        $data['desicion_dmg'] = $this->constant_details_model->get_list(282);

        $data['class_input_class_type'] = $this->store_committees_model->get_all_by_type(5);
        $data['branches'] = $this->gcc_branches_model->get_all();
        //$data['movement_purpose'] = $this->constant_details_model->get_list(268);

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    /****************************************public_get_dmg_mails*******************************************/

    function public_get_dmg_var_doc($id = 0,$adopt=1)

    {
        $data['details'] = $this->rmodel->get('DMG_VARIOUS_DOC_TB_GET', $id);

        $data['adopt']=$adopt;
        $this->_lookup($data);
        $this->load->view('various_doc_details', $data);
    }




    /**********************************************get*****************************************************/

    function get($id)
    {

        $result = $this->rmodel->get('DMG_MODELS_TB_GET', $id);
        $data['title'] = 'تعديل بيانات النموذج';
        $data['content'] = 'various_doc_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['hide'] = 'hidden';
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }


    /**************************************create*****************************************/

    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser = $this->rmodel->insert('DMG_MODELS_TB_INSERT', $this->_postedData());

            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {

                    $serDet=$this->rmodel->insert('DMG_VARIOUS_DOC_TB_INSERT',$this->_posteddata_details
                        (null, $this->p_body[$i], $this->p_doc_type[$i], $this->p_doc_date[$i], $this->p_doc_number[$i]
                            , $this->p_notes_doc[$i],$this->ser,$this->user->branch,$this->user->id,'create') );

                }
            }


            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {

                echo intval($this->ser);
            }

        } else {

            $data['title'] = 'نموذج جديد';
            $data['action'] = 'index';
            $data['hide'] = 'hidden';
            $data['isCreate'] = true;
            $data['content'] = 'various_doc_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    /*************************************_postedData*******************************************/

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'MODEL_DATE', 'value' => $this->p_model_date, 'type' => '', 'length' => -1),
            array('name' => 'MODEL_TYPE', 'value' => 5, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'DOCUMENT_YEAR', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'DOCUMENT_TYPE', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_NO', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_CLASS_TYPE', 'value' => $this->p_class_input_class_type, 'type' => '', 'length' => -1),
        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }


    /***************************************_posteddata_details****************************************/




    function _posteddata_details($ser = null, $body = null, $doc_type = null, $doc_date = null,$doc_number=null,$notes_doc=null,
                                 $model_no=null,$entry_branch=null,$entry_user=null,$type)
    {




        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'BODY', 'value' => $body, 'type' => '', 'length' => -1),
            array('name' => 'DOC_TYPE', 'value' => $doc_type, 'type' => '', 'length' => -1),
            array('name' => 'DOC_DATE', 'value' => $doc_date, 'type' => '', 'length' => -1),
            array('name' => 'DOC_NUMBER', 'value' => $doc_number, 'type' => '', 'length' => -1),
            array('name' => 'NOTES_DOC', 'value' => $notes_doc, 'type' => '', 'length' => -1),
            array('name' => 'MODEL_NO', 'value' => $model_no, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_NO', 'value' => $entry_branch, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $entry_user, 'type' => '', 'length' => -1),


        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }



    /********************************************edit*********************************************/

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->rmodel->update('DMG_MODELS_TB_UPDATE', $this->_postedData(false));


            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }

            for ($i = 0; $i < count($this->p_seq1); $i++)
            {

                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {

                    $x=$this->rmodel->insert('DMG_VARIOUS_DOC_TB_INSERT',$this->_posteddata_details
                        (null, $this->p_body[$i], $this->p_doc_type[$i], $this->p_doc_date[$i], $this->p_doc_number[$i]
                            , $this->p_notes_doc[$i],$this->ser,$this->user->branch,$this->user->id,'create') );

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                } else {


                    $x=$this->rmodel->insert('DMG_VARIOUS_DOC_TB_UPDATE',$this->_posteddata_details
                        ($this->p_seq1[$i], $this->p_body[$i], $this->p_doc_type[$i], $this->p_doc_date[$i], $this->p_doc_number[$i]
                            , $this->p_notes_doc[$i],$this->ser,$this->user->branch,$this->user->id,'edit') );

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                }
            }

            echo intval($this->ser);
        }
    }

    /****************************************adopt*************************************************/

    function adopt(){
        $res= $this->rmodel->update('DMG_MODELS_TB_ADOPT', $this->_postedData_adopt($this->p_id,2,false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }


    /****************************************unadopt*************************************************/

    function unadopt(){
        $res= $this->rmodel->update('DMG_MODELS_TB_ADOPT', $this->_postedData_adopt($this->p_id,1,false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }


    /*****************************************adopt_commitees********************************************/

    function adopt_commitees(){
        $res= $this->rmodel->update('DMG_MODELS_TB_ADOPT', $this->_postedData_adopt($this->p_id,3,false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }


    function _postedData_adopt($ser = null, $adopt = null,$type)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ADOPT', 'value' => $adopt, 'type' => '', 'length' => -1),
        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }


    /****************************************get_model_var_doc_com*************************************************/

    function get_model_var_doc_com($id)
    {
        $result = $this->rmodel->get('DMG_MODELS_TB_GET', $id);
        $data['title'] = 'بيانات النموذج';
        $data['content'] = 'various_doc_comm_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['hide'] = 'hidden';
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    /****************************************public_get_dmg_var_doc_comm*************************************************/

    function public_get_dmg_var_doc_comm($id = 0,$adopt=1)

    {

        $data['details'] = $this->rmodel->get('DMG_VARIOUS_DOC_TB_GET', $id);
        $data['adopt']=$adopt;
        $this->_lookup($data);
        $this->load->view('various_doc_comm_details', $data);
    }


    /*************************************************اللجان*************************************************/

    /***************************************public_get_group_receipt*****************************************/

    function public_get_group_receipt($id = 0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($id,5);
        $this->load->view('commitees_member',$data);
    }





    function edit_comm_role(){

        $this->ser = $this->rmodel->update('DMG_MODELS_TB_COMM_UPDATE', $this->_postGroupsData_comm($this->p_ser,$this->p_desicion_dmg,$this->p_notes_comm,'edit'));


        for ($c = 0; $c < count($this->group_person_id); $c++) {

            $status = (isset($this->status[$c])) ? 1 : 2;

            if ($this->group_ser[$c] == 0){

                $rs= $this->receipt_class_input_group_model->create($this->_postGroupsData(null,
                    $this->p_ser, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c],
                    $status, $this->member_note[$c], 'create'));

            } else {

                $rs= $this->receipt_class_input_group_model->edit($this->_postGroupsData($this->group_ser[$c], $this->p_ser, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));

            }
        }
        echo "1" ;

    }


    function _postGroupsData_comm($ser, $desicion_dmg, $notes_comm, $ty = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'DESICION_DMG', 'value' => $desicion_dmg, 'type' => '', 'length' => -1),
            array('name' => 'NOTES_COMM', 'value' => $notes_comm, 'type' => '', 'length' => -1)
        );

        if ($ty == 'create') {
            array_shift($result);
        }

        return $result;

    }



    function _postGroupsData($ser, $id, $group_person_id, $group_person_date, $emp_no, $status, $member_note, $ty = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'GROUP_PERSON_ID', 'value' => $group_person_id, 'type' => '', 'length' => -1),
            array('name' => 'GROUP_PERSON_DATE', 'value' => $group_person_date, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => 'SOURCE', 'value' => 5, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $status, 'type' => '', 'length' => -1),
            array('name' => 'MEMBER_NOTE', 'value' => $member_note, 'type' => '', 'length' => -1)
        );

        if ($ty == 'create') {
            array_shift($result);
        }

        return $result;

    }


    /*********************************************************************************************/





}
