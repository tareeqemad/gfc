<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User: mkilani
 * Date: 10/10/21
 */
class Classification_tree extends MY_Controller
{

    var $PACKAGE_NAME = 'ARCH_FILE';

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = $this->PACKAGE_NAME;

    }
    /******************Scan Type**Tree************************/
    function index_Tree()
    {
        $this->load->helper('generate_list');

        $data['title'] = 'شجرة التصنيفات';
        $data['content'] = 'classification_tree_index';
        $resource = $this->_get_structure(0);
        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );
        $template = '<li ><span data-id="{TYPE_NO}" data-no="{TYPE_NO}"  data-title="{TYPE_NAME}" data-codetype="{TYPE_CODE}"  ondblclick="javascript:get_type();"><i class="fa fa-minus"></i> <input type="checkbox" class="checkboxes" value="{TYPE_NO}" />{TYPE_CODE} : {TYPE_NAME}  </span>{SUBS}</li>';
        $data['tree'] = '<ul class="tree" id="Tree_Type">' . generate_list($resource, $options, $template) . '</ul>';
        $this->load->view('template/template1', $data);
    }

    function public_get_scan_type()
    {
        $result = $this->rmodel->get('SCAN_TYPE_GET', $this->p_type_no);
        $this->return_json($result[0]);
    }

    function create_scan_type()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type_name = $this->input->post('type_name');
            $parent_no = $this->input->post('parent_no');
            $level_no = $this->input->post('level_no');
            $data_arr = array(
                array('name' => 'TYPE_NAME_IN', 'value' => $type_name, 'type' => '', 'length' => -1),
                array('name' => 'TYPE_NO_PARENT_IN', 'value' => $parent_no, 'type' => '', 'length' => -1),
                array('name' => 'LEVEL_NO_IN', 'value' => $level_no, 'type' => '', 'length' => -1),
            );
            $result = $this->rmodel->insert('SCAN_TYPE_INSERT', $data_arr);
            $this->Is_success($result);
            $this->return_json($result);
        }
    }


    function update_scan_type()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type_no = $this->input->post('type_no_up');
            $type_code = $this->input->post('type_code_up');
            $type_name = $this->input->post('type_name_up');
            $data_arr = array(
                array('name' => 'TYPE_NO_IN', 'value' => $type_no, 'type' => '', 'length' => -1),
                array('name' => 'TYPE_CODE_IN', 'value' => $type_code, 'type' => '', 'length' => -1),
                array('name' => 'TYPE_NAME_IN', 'value' => $type_name, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('SCAN_TYPE_UPDATE', $data_arr);
            if (intval($res) >= 1) {
                echo $res;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }

    function _get_structure($parent = -1)
    {
        $result = $this->rmodel->getID($this->PACKAGE_NAME, 'SCAN_TYPE_CHILD_GET', $parent);
        $i = 0;
        foreach ($result as $key => $item) {
            $result[$i]['subs'] = $this->_get_structure($item['TYPE_NO']);
            $i++;
        }
        return $result;
    }
    /*****************************************************/


}