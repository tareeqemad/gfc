<?php

class Gcc_tree_structure extends MY_Controller
{
    var $MODEL_NAME = 'Gcc_tree_structure_model';
    var $PKG_NAME = "CONTRACTS_SERVICES_PKG";


    function __construct()
    {
        parent::__construct();

        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');


        $this->load->model('settings/constant_details_model');


        //VAR FOR CREATE GCC
        // vars
        $this->gcc_id = $this->input->post('gcc_id');
        //$this->t_ser = $this->input->post('t_ser');


        $this->gcc_st_no = $this->input->post('gcc_st_no');
        $this->gcc_parent_id = $this->input->post('gcc_parent_id');


        //
        $this->gcc_con_parent_id = $this->input->post('gcc_con_parent_id');


        $this->contract_name = $this->input->post('contract_name');
        $this->contract_detail = $this->input->post('contract_detail');
        $this->contract_type = $this->input->post('contract_type');

    }

    function index()
    {

        $this->load->helper('generate_list');
        $this->load->model('employees/Employees_model');
        add_css('combotree.css');
        add_js('jquery.tree.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['title'] = ' شجرة إدارة  التعاقدات - المشتريات';
        $data['content'] = 'Gcc_tree_index';
        //الادارات
        $this->rmodel->package = 'SETTING_PKG';
        $data['structure_all'] = $this->rmodel->get('GCC_STRUCTURE_TB_GET_BY_TYPE', 1);
        //
        $data['contracts_type'] = $this->constant_details_model->get_list(330);


        $resource = $this->_get_structure(-1);
        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );
        $template = '<li ><span data-id="{GCC_ID}" data-tser="{T_SER}" data-gcc="{GCC_PARENT_ID}" data-gcc-no="{{GCC_ST_NO}}" ondblclick="javascript:contract_get(\'{GCC_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{GCC_ID} : {CONTRACT_NAME}  {GCC_NAME}  </span>{SUBS}</li>';
        $data['tree'] = '<ul class="tree" id="gcc_tree_structure">' . generate_list($resource, $options, $template) . '</ul>';
        $data['help'] = $this->help;
        $this->load->view('template/template', $data);
    }

    function _get_structure($parent = 0)
    {
        $result = $this->{$this->MODEL_NAME}->get_child($parent);
        $i = 0;
        foreach ($result as $key => $item) {
            $result[$i]['subs'] = $this->_get_structure($item['GCC_ID']);
            $i++;
        }
        return $result;
    }

    function create()
    {
        $result = $this->{$this->MODEL_NAME}->create($this->_postedData());
        $this->Is_success($result);
        $this->return_json($result);
    }

    function create_contract()
    {
        $result = $this->{$this->MODEL_NAME}->create_contracts($this->_postedData_contract());
        if (intval($result) > 1)
            echo $result;
        else
            $this->print_error($result);
    }


    function get()
    {

        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function public_get_id()
    {

        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }


    /**
     * delete action : delete account data ..
     * receive prm key as request
     *
     */
    function delete()
    {

        $id = $this->input->post('id');

        $this->IsAuthorized();

        if (is_array($id)) {
            foreach ($id as $val) {
                echo $this->{$this->MODEL_NAME}->delete($val);
            }
        } else {
            echo $this->{$this->MODEL_NAME}->delete($id);
        }

    }

    function edit()
    {
        //if(!$this->check_db_for_stores())
        //die('CLOSED..');
        echo $this->{$this->MODEL_NAME}->edit($this->_postedData_edit_contract());

    }

    function _postedData()
    {
        $result = array(

            array('name' => 'GCC_PARENT_ID', 'value' => $this->gcc_parent_id, 'type' => '', 'length' => -1),
            array('name' => 'GCC_ST_NO', 'value' => $this->gcc_st_no, 'type' => '', 'length' => -1),
        );
        return $result;
    }


    function _postedData_contract()
    {
        $result = array(
            //  array('name'=>'GCC_ID','value'=>$this->gcc_id ,'type'=>'','length'=>-1),
            array('name' => 'GCC_PARENT_ID', 'value' => $this->gcc_con_parent_id, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_NAME', 'value' => $this->contract_name, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_TYPE', 'value' => $this->contract_type, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_DETAIL', 'value' => $this->contract_detail, 'type' => '', 'length' => -1),
            //array('name'=>'T_SER','value'=>$this->t_ser,'type'=>'','length'=>-1),

        );
        return $result;
    }


    function _postedData_edit_contract()
    {
        $result = array(
            array('name' => 'GCC_ID', 'value' => $this->gcc_id, 'type' => '', 'length' => -1),
            array('name' => 'GCC_PARENT_ID', 'value' => $this->gcc_con_parent_id, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_NAME', 'value' => $this->contract_name, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_TYPE', 'value' => $this->contract_type, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_DETAIL', 'value' => $this->contract_detail, 'type' => '', 'length' => -1),
        );
        return $result;
    }


}