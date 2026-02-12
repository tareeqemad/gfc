<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Salary_dues_types_model extends MY_Model
{
    var $PKG_NAME = "SALARY_DUES_PKG";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    /**
     * Get full tree (flat, from CONNECT BY)
     */
    function getTreeList($only_active = 1)
    {
        $params = array(
            array('name' => ':ONLY_ACTIVE_IN', 'value' => $only_active, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT',    'value' => 'cursor',     'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',        'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'SALARY_DUES_TYPES_TREE_LIST', $params);
    }

    /**
     * Get single record by TYPE_ID
     */
    function get($type_id)
    {
        $params = array(
            array('name' => ':TYPE_ID_IN',   'value' => $type_id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT','type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'SALARY_DUES_TYPES_TB_GET', $params);
    }

    /**
     * Insert new type
     */
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SALARY_DUES_TYPES_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    /**
     * Update existing type
     */
    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SALARY_DUES_TYPES_TB_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    /**
     * مستوى العقدة في الشجرة: 1 = جذر، 2 = ابن، 3 = حفيد. أكثر من 3 ممنوع.
     * يرجع 0 إذا type_id فارغ أو العقدة غير موجودة.
     */
    public function get_node_depth($type_id)
    {
        if ($type_id === null || $type_id === '') {
            return 0;
        }
        $flat = $this->getTreeList(1);
        if (!is_array($flat)) {
            return 0;
        }
        $by_id = array();
        foreach ($flat as $row) {
            $id = isset($row['TYPE_ID']) ? $row['TYPE_ID'] : null;
            if ($id !== null && $id !== '') {
                $by_id[$id] = $row;
            }
        }
        $depth = 0;
        $current_id = $type_id;
        while ($current_id !== null && $current_id !== '') {
            if (!isset($by_id[$current_id])) {
                return 0;
            }
            $depth++;
            $parent = isset($by_id[$current_id]['PARENT_ID']) ? $by_id[$current_id]['PARENT_ID'] : null;
            if ($parent === '' || $parent === '0') {
                $parent = null;
            }
            $current_id = $parent;
        }
        return $depth;
    }

    /**
     * Delete (deactivate) type
     * البروسيجر: TYPE_ID_IN IN NUMBER, MSG_OUT OUT VARCHAR2
     */
    function delete($type_id)
    {
        $params = array(
            array('name' => ':TYPE_ID_IN', 'value' => (int)$type_id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT',    'value' => 'MSG_OUT',       'type' => SQLT_CHR, 'length' => 500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SALARY_DUES_TYPES_TB_DELETE', $params);
        return isset($result['MSG_OUT']) ? $result['MSG_OUT'] : $result;
    }
}
