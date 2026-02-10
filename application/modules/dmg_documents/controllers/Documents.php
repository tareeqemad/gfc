<?php

//if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 08/07/19
 * Time: 09:14 ص
 */

class documents extends MY_Controller
{

    var $PKG_NAME = "DMG_PKG";

    function __construct()
    {
        parent::__construct();

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
        $data['title'] = 'استعلام عن النماذج';
        $data['content'] = 'model_comm_index';

        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['hide'] ='hidden';

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->_lookup($data);
        $this->load->view('template/template', $data);

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
        $data['document_type'] = $this->constant_details_model->get_list(276);
        $data['desicion_dmg'] = $this->constant_details_model->get_list(282);
        $data['class_input_class_type'] = $this->store_committees_model->get_all_by_type(5);
        $data['branches'] = $this->gcc_branches_model->get_all();
        //$data['movement_purpose'] = $this->constant_details_model->get_list(268);

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    /**************************public_get_dmg_document************************************/

    function public_get_dmg_document($id = 0,$adopt=1)

    {
        $data['details'] = $this->rmodel->get('DMG_DOCUMENTS_TB_GET', $id);
        $data['adopt']=$adopt;
        $this->_lookup($data);
        $this->load->view('document_details', $data);
    }


    function public_get_dmg_document_comm($id = 0,$adopt=1)

    {
        $data['details'] = $this->rmodel->get('DMG_DOCUMENTS_TB_GET', $id);
        $data['adopt']=$adopt;
        $this->_lookup($data);
        $this->load->view('document_comm_details', $data);
    }

    /***********************************get**********************************************/

    function get($id)
    {

        $result = $this->rmodel->get('DMG_MODELS_TB_GET', $id);
        $data['title'] = 'تعديل بيانات النموذج';
        $data['content'] = 'document_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['hide'] = 'hidden';
        $data['action'] = 'edit';
        $this->_lookup($data);
       $this->load->view('template/template', $data);


    }


    function get_model_com($id)
    {

        $result = $this->rmodel->get('DMG_MODELS_TB_GET', $id);
        $data['title'] = 'بيانات النموذج';
        $data['content'] = 'document_comm_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['hide'] = 'hidden';
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);


    }


    function get_model($id)
    {

        $result = $this->rmodel->get('DMG_MODELS_TB_GET', $id);
        $data['title'] = 'بيانات النموذج';
        $data['content'] = 'document_show';
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

                    $serDet=$this->rmodel->insert('DMG_DOCUMENTS_TB_INSERT',$this->_posteddata_details
                        (null, $this->p_document_ser[$i], $this->p_from_receipt_no[$i], $this->p_to_receipt_no[$i],
                            $this->ser,'create')) ;

                }
            }

           /* for ($i = 0; $i < count($this->p_seq1); $i++)
            {

                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {

                $serDet=$this->rmodel->insert('DMG_DOCUMENTS_TB_INSERT',$this->_posteddata_details
                    (null, $this->p_document_ser[$i], $this->p_from_receipt_no[$i], $this->p_to_receipt_no[$i],
                        $this->ser,$this->user->branch,$this->user->id,'create') );

                }
            }*/


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
            $data['content'] = 'document_show';
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
            array('name' => 'MODEL_TYPE', 'value' => 1, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'DOCUMENT_YEAR', 'value' => $this->p_document_year, 'type' => '', 'length' => -1),
            array('name' => 'DOCUMENT_TYPE', 'value' => $this->p_document_type, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_NO', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_CLASS_TYPE', 'value' => $this->p_class_input_class_type, 'type' => '', 'length' => -1),
        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }


    /***************************************_posteddata_details****************************************/

    function _posteddata_details($ser = null, $document_ser = null, $from_receipt_no = null, $to_receipt_no = null,
                                 $model_no = null,$type)
    {

        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'DOCUMENT_SER', 'value' => $document_ser, 'type' => '', 'length' => -1),
            array('name' => 'FROM_RECEIPT_NO', 'value' => $from_receipt_no, 'type' => '', 'length' => -1),
            array('name' => 'TO_RECEIPT_NO', 'value' => $to_receipt_no, 'type' => '', 'length' => -1),
            array('name' => 'MODEL_NO', 'value' => $model_no, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_NO', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),


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

                    $x=$this->rmodel->insert('DMG_DOCUMENTS_TB_INSERT',$this->_posteddata_details

                        (null, $this->p_document_ser[$i], $this->p_from_receipt_no[$i], $this->p_to_receipt_no[$i],
                            $this->ser,'create') );
                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }
                } else {

                    $x=$this->rmodel->update('DMG_DOCUMENTS_TB_UPDATE',$this->_posteddata_details
                        ($this->p_seq1[$i], $this->p_document_ser[$i], $this->p_from_receipt_no[$i], $this->p_to_receipt_no[$i],
                            $this->ser,'edit') );

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

    /*******************************************get_page***********************************************/

    function get_page_comm($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("dmg_documents/documents/get_page_comm/");


        $sql = 'WHERE ADOPT = 3';

        $sql .= isset($this->p_ser) && $this->p_ser ? " AND SER= {$this->p_ser} " : '';
        $sql .= isset($this->p_model_type) && $this->p_model_type ? " AND MODEL_TYPE= {$this->p_model_type} " : '';
        $sql .= isset($this->p_model_date) && $this->p_model_date ? " AND MODEL_DATE like '%{$this->p_model_date}%' " : "";



        $count_rs = $this->get_table_count(" DMG_MODELS_TB M  {$sql}");


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
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data["rows"] = $this->rmodel->getList('DMG_MODELS_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('model_comm_page', $data);
    }

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





    //For table Group //Finish

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













}
