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
     * Delete (deactivate) type
     */
    function delete($type_id)
    {
        $params = array(
            array('name' => ':TYPE_ID_IN', 'value' => $type_id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT',    'value' => 'MSG_OUT','type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SALARY_DUES_TYPES_TB_DELETE', $params);
        return $result['MSG_OUT'];
    }
}
