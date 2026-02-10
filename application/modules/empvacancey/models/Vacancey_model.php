<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 06/02/2019
 * Time: 8:10 ص
 */
class Vacancey_model extends MY_Model
{
    var $PKG_NAME = "HR_VACANCY_PKG";
    var $TABLE_NAME = 'EMP_VACANCY_TB';
    var $GET_E = '';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMP_VACANCY_TB_HR_INSERT', $params);
        return $result['MSG_OUT'];
    }

    /****************************************اسعتلام*********************************************************/

    function get_list($sql, $offset, $row)
    {

        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EMPLOYEES_GET_ALL', $params);
        return $result;
    }

    /*******************************************************************************************/
    function adopt($id_vacancy, $case, $note)
    {
        $params = array(
            array('name' => ':ID_VACANCY', 'value' => $id_vacancy, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMP_VACANCY_TB_ADOPT', $params);
        return $result['MSG_OUT'];
    }

    function adoptNew($id_vacancy, $case, $note, $branch)
    {
        $params = array(
            array('name' => ':ID_VACANCY', 'value' => $id_vacancy, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),
            array('name' => ':BRANCH_IN', 'value' => $branch, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMP_VACANCY_TB_ADOPT_NEW', $params);
        return $result['MSG_OUT'];
    }


    /**********************************************للاستعلام الخارجي*******************************************/
    function get($emp_no = 0)
    {

        $params = array(
            array('name' => ':EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EMPLOYEES_GET_ALL', $params);
        return $result;
    }

    /*******************************************************************************************/
    function get_vacancey($id)
    {

        $params = array(
            array('name' => ':ID_VACANCY', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EMP_VACANCY_TB_GET', $params);

        return $result;
    }

    function get_vacancey_adopt($id)
    {

        $params = array(
            array('name' => ':ID_VACANCY', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EMP_VACANCY_ADOPT_TB_GET_LIST', $params);
        return $result;
    }

    /*********************/

    function delete($id)
    {

        $params = array(
            array('name' => ':ID_VACANCY_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMP_VACANCY_TB_DELETE', $params);

        return $result['MSG_OUT'];

    }

}




