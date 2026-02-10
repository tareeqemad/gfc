<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Salary_dues_types extends MY_Controller
{
    var $MODEL_NAME = 'salary_dues_types_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    /**
     * Index - tree view
     */
    function index()
    {
        $this->load->helper('generate_list');

        $data['title'] = 'شجرة أنواع مستحقات الموظفين';
        $data['content'] = 'salary_dues_types_index';

        $resource = $this->_get_structure();

        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );

        $template = '<li><span data-id="{TYPE_ID}" data-linetype="{LINE_TYPE}" class="tree-node lt-{LINE_TYPE}" ondblclick="javascript:dues_type_get(\'{TYPE_ID}\');"><i class="fa fa-minus tree-icon"></i> {TYPE_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="dues_types_tree">' . generate_list($resource, $options, $template) . '</ul>';
        $data['help'] = $this->help;

        $this->load->view('template/template1', $data);
    }

    /**
     * Build nested tree structure from flat TREE_LIST data
     */
    function _get_structure()
    {
        $flat = $this->{$this->MODEL_NAME}->getTreeList(1);
        if (!is_array($flat)) return array();
        return $this->_build_tree($flat, null);
    }

    /**
     * Recursively build tree from flat data
     */
    function _build_tree($flat, $parent_id)
    {
        $tree = array();
        foreach ($flat as $node) {
            $node_parent = isset($node['PARENT_ID']) ? $node['PARENT_ID'] : null;
            if ($node_parent === '' || $node_parent === '0') $node_parent = null;

            if (($parent_id === null && $node_parent === null)
                || ($parent_id !== null && $node_parent !== null && $node_parent == $parent_id)) {
                $node['subs'] = $this->_build_tree($flat, $node['TYPE_ID']);
                $tree[] = $node;
            }
        }
        return $tree;
    }

    /**
     * AJAX: get record by ID
     */
    function get_id()
    {
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * Create new type
     */
    function create()
    {
        $this->_post_validation('create');

        $result = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

        if (is_numeric($result) && intval($result) > 0) {
            $response = array(
                'msg'       => 1,
                'id'        => $result,
                'LINE_TYPE' => $this->p_line_type
            );
            $this->return_json($response);
        } else {
            $this->print_error($result);
        }
    }

    /**
     * Edit existing type
     */
    function edit()
    {
        $this->_post_validation('edit');

        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData('edit'));

        if (is_numeric($result) && intval($result) >= 1) {
            $response = array(
                'msg'       => 1,
                'LINE_TYPE' => $this->p_line_type
            );
            $this->return_json($response);
        } else {
            $this->print_error($result);
        }
    }

    /**
     * Delete (deactivate) type
     */
    function delete()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();

        $result = $this->{$this->MODEL_NAME}->delete($id);

        if (is_numeric($result) && intval($result) >= 1) {
            echo '1';
        } else {
            echo $result;
        }
    }

    /**
     * Validation
     */
    function _post_validation($action = 'create')
    {
        if ($action == 'edit' && $this->p_type_id == '') {
            $this->print_error('رقم البند مطلوب للتعديل');
        }

        if (trim($this->p_type_name) == '') {
            $this->print_error('يجب ادخال اسم البند');
        }

        if (!in_array($this->p_line_type, array('1', '2'))) {
            $this->print_error('يجب اختيار نوع البند (اضافة أو خصم)');
        }
    }

    /**
     * AJAX: يرجع الشجرة بصيغة JSON لـ EasyUI combotree
     * يُستخدم في صفحات المستحقات لاختيار نوع الدفع
     * only_leaf: 1 = فقط الأوراق قابلة للاختيار (للإدخال)
     */
    public function public_get_tree_json()
    {
        $only_active = 1;
        $flat = $this->{$this->MODEL_NAME}->getTreeList($only_active);
        if (!is_array($flat) || empty($flat)) {
            echo json_encode([]);
            return;
        }

        $tree = $this->_build_combotree_json($flat, null);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($tree));
    }

    /**
     * تحويل البيانات المسطحة إلى صيغة EasyUI combotree
     * [{id, text, state, children, attributes:{lineType, isLeaf}}]
     */
    private function _build_combotree_json($flat, $parent_id)
    {
        $nodes = array();
        foreach ($flat as $row) {
            $rp = isset($row['PARENT_ID']) ? $row['PARENT_ID'] : null;
            if ($rp === '' || $rp === '0') $rp = null;

            $match = ($parent_id === null && $rp === null)
                  || ($parent_id !== null && $rp !== null && $rp == $parent_id);

            if ($match) {
                $children = $this->_build_combotree_json($flat, $row['TYPE_ID']);
                $is_leaf = empty($children) ? 1 : 0;

                $node = array(
                    'id'   => $row['TYPE_ID'],
                    'text' => $row['TYPE_NAME'],
                    'state' => empty($children) ? 'open' : 'closed',
                    'attributes' => array(
                        'lineType' => $row['LINE_TYPE'],
                        'isLeaf'   => $is_leaf
                    )
                );

                if (!empty($children)) {
                    $node['children'] = $children;
                }

                $nodes[] = $node;
            }
        }
        return $nodes;
    }

    /**
     * Build posted data array
     */
    function _postedData($action = 'create')
    {
        if ($action == 'create') {
            // البروسيجر بياخد الرقم من الـ Sequence تلقائي
            $result = array(
                array('name' => 'TYPE_NAME', 'value' => $this->p_type_name, 'type' => '', 'length' => -1),
                array('name' => 'PARENT_ID', 'value' => $this->p_parent_id, 'type' => '', 'length' => -1),
                array('name' => 'LINE_TYPE', 'value' => $this->p_line_type, 'type' => '', 'length' => -1),
            );
        } else {
            $result = array(
                array('name' => 'TYPE_ID',   'value' => $this->p_type_id,   'type' => '', 'length' => -1),
                array('name' => 'TYPE_NAME', 'value' => $this->p_type_name, 'type' => '', 'length' => -1),
                array('name' => 'PARENT_ID', 'value' => $this->p_parent_id, 'type' => '', 'length' => -1),
                array('name' => 'LINE_TYPE', 'value' => $this->p_line_type, 'type' => '', 'length' => -1),
                array('name' => 'IS_ACTIVE', 'value' => $this->p_is_active, 'type' => '', 'length' => -1),
            );
        }

        return $result;
    }
}
