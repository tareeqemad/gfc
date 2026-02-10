<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Civil_works extends MY_Controller
{

    var $MODEL_NAME = 'Civil_works_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Civil_works_model');
    }


    function index()
    {

        $this->load->helper('generate_list');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('budget/budget_section_model');
        add_css('combotree.css');
        add_css('tabs.css');
        add_js('jquery.tree.js');
        add_js('tabs.js');
        $data['title'] = ' شجرة التعاقدات اللوجستية ';
        $data['content'] = 'civil_works_indexes';

        $resource = $this->_get_structure(-1);

        $options = array('template_head' => '<ul>', 'template_foot' => '</ul>', 'use_top_wrapper' => false);

        $template = '<li ><span data-id="{CLASS_ID}" data-name="{CLASS_NAME}" ondblclick="javascript:class_get(\'{CLASS_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{CLASS_ID} : {CLASS_NAME_AR}</span>{SUBS}</li>';
        $data['tree'] = '<ul class="tree" id="class">' . generate_list($resource, $options, $template) . '</ul>';
        $data['currency'] = $this->currency_model->get_all();
        $data['class_unit'] = $this->constant_details_model->get_list(22);//$this->constant_details_model->get_list(487);
        $data['status'] = $this->constant_details_model->get_list(488);
        $data['class_type_con'] = $this->constant_details_model->get_list(21);
        $data['class_tree_con'] = $this->constant_details_model->get_list(506);
        $data['help'] = $this->help;

        $this->load->view('template/template', $data);
    }

    function _get_structure($parent = 0)
    {
        $result = $this->Civil_works_model->getList($parent);

        $i = 0;
        foreach ($result as $key => $item) {
            $result[$i]['subs'] = $this->_get_structure($item['CLASS_ID']);
            $i++;
        }

        return $result;
    }

    function get_id()
    {

        $id = $this->input->post('id');
        $result = $this->Civil_works_model->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function public_get_id()
    {

        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $result = $this->class_model->get($id, $type);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function create()
    {

        $result = $this->Civil_works_model->create($this->_postedData("create"));
        if (intval($result) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $result);
        } else
            $this->return_json($result);

    }

    function _postedData($typ = null)
    {

        $this->class_id = $this->input->post('class_id');
        $this->class_parent_id = $this->input->post('class_parent_id');
        $this->class_name = $this->input->post('class_name');
        $this->class_unit = $this->input->post('class_unit');
        $this->class_quantity = $this->input->post('class_quantity');
        $this->class_price = $this->input->post('class_price');
        $this->curr_id = $this->input->post('curr_id');
        $this->status = $this->input->post('status');
        $this->class_name_en = $this->input->post('class_name_en');
        $this->class_description = $this->input->post('class_description');
        $this->class_type = $this->input->post('class_type');
        $this->level = $this->input->post('level');
        $this->class_tree = $this->input->post('class_tree');


        $result = array(

                        array('name' => 'class_id', 'value' => $this->class_id, 'type' => '', 'length' => -1),
                        array('name' => 'class_parent_id', 'value' => $this->class_parent_id, 'type' => '', 'length' => -1),
                        array('name' => 'class_name', 'value' => $this->class_name, 'type' => '', 'length' => -1),
                        array('name' => 'class_unit', 'value' => $this->class_unit, 'type' => '', 'length' => -1),
                        array('name' => 'class_quantity', 'value' => $this->class_quantity, 'type' => '', 'length' => -1),
                        array('name' => 'class_price', 'value' => $this->class_price, 'type' => '', 'length' => -1),
                        array('name' => 'curr_id', 'value' => $this->curr_id, 'type' => '', 'length' => -1),
                        array('name' => 'status', 'value' => $this->status, 'type' => '', 'length' => -1),
                        array('name' => 'class_name_en', 'value' => $this->class_name_en, 'type' => '', 'length' => -1),
                        array('name' => 'class_description', 'value' => $this->class_description, 'type' => '', 'length' => -1),
                        array('name' => 'class_type', 'value' => $this->class_type, 'type' => '', 'length' => -1),
                        array('name' => 'level', 'value' => $this->level, 'type' => '', 'length' => -1),
                        array('name' => 'class_tree', 'value' => $this->class_tree, 'type' => '', 'length' => -1),

        );
        if ($typ == 'create') {
            array_shift($result);
        }
        return $result;

    }

    function edit()
    {

        if ($this->input->post('class_id') == 0) $this->print_error('!!لا يمكن التعديل على شجرة الأعمال المدنية'); else
            $result = $this->Civil_works_model->edit($this->_postedData());
        if (intval($result) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $result);
        } else
            echo intval($result);


    }

    function delete()
    {
        $id = $this->input->post('id');

        if ($id == 0) {

            $this->print_error('!!لا يمكن حذف الشجرة');
        }

        $this->IsAuthorized();

        if (is_array($id)) {
            foreach ($id as $val) {
                $result = $this->Civil_works_model->delete($val);

                if (intval($result) <= 0) {
                    $this->print_error('!!لم تتم عملية الحذف' . '<br>' . $result);
                }

                echo intval($result);
            }
        } else {
            $result = $this->Civil_works_model->delete($id);

            if (intval($result) <= 0) {
                $this->print_error('!!لم تتم عملية الحذف' . '<br>' . $result);
            }

            echo intval($result);
        }


    }
}