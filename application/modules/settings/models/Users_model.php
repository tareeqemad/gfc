<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:35 AM
 */
class Users_model extends MY_Model
{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    /**
     * @return array
     *
     * return all users data ..
     */
    function get_all($SQL = '')
    {

        

        $params = array(
            array('name' => ':XSQL', 'value' => $SQL, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );

        $result = $this->New_rmodel->general_get('USER_PKG', 'USERS_PROG_TB_GET_ALL', $params);

        return $result;
    }


    /**
     * @return array
     *
     * return all users data ..
     */
    function get_count($user_id, $user_name, $user_position)
    {

        

        $params = array(

            array('name' => ':USER_ID_IN', 'value' => $user_id, 'type' => '', 'length' => -1),
            array('name' => ':USER_NAME_IN', 'value' => "{$user_name}", 'type' => '', 'length' => -1),
            array('name' => ':USER_POSITION_IN', 'value' => $user_position, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('USER_PKG', 'USERS_PROG_TB_GET_COUNT', $params);

        return $result;
    }

    function teller_id()
    {
        
        $params = array(
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500)
        );
        $result = $this->New_rmodel->general_get('USER_PKG', 'TELLER_ID', $params);
        return $result;
    }

    /**
     * @return array
     *
     * return all users data ..
     */
    function get_list($user_id, $user_name, $user_position, $offset, $row)
    {

        

        $params = array(

            array('name' => ':USER_ID_IN', 'value' => $user_id, 'type' => '', 'length' => -1),
            array('name' => ':USER_NAME_IN', 'value' => "{$user_name}", 'type' => '', 'length' => -1),
            array('name' => ':USER_POSITION_IN', 'value' => $user_position, 'type' => '', 'length' => -1),
            array('name' => ':offset', 'value' => "{$offset}", 'type' => '', 'length' => -1),
            array('name' => ':row', 'value' => "{$row}", 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('USER_PKG', 'USERS_PROG_TB_GET_LIST', $params);

        return $result;
    }


    /**
     * @param int $id
     * @return mixed
     * return one user ..
     */
    function get($id = '0')
    {

        

        $params = array(
            array('name' => ':USER_ID_in', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('USER_PKG', 'USERS_PROG_TB_GET', $params);

        return $result;
    }

    function get_user_info($id = 0, $email = '')
    {
        
        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':EMAIL_in', 'value' => $email, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->New_rmodel->general_get('QF_PKG', 'GET_USER_INFO', $params);
        return $result;
    }


    function user_system_admin_insert($user, $system)
    {
        $params = array(
            array('name' => ':USER_ID_in', 'value' => $user, 'type' => '', 'length' => -1),
            array('name' => ':SYSTEM_ID_IN', 'value' => $system, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('USER_PKG', 'USER_SYSTEM_ADMIN_INSERT', $params);

        return $result['MSG_OUT'];
    }


    function user_system_admin_delete($id)
    {
        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),

            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('USER_PKG', 'USER_SYSTEM_ADMIN_DELETE', $params);

        return $result['MSG_OUT'];
    }

    function get_user_system()
    {
        
        $params = array(

            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->New_rmodel->general_get('USER_PKG', 'USER_SYSTEM_ADMIN_GET_ALL', $params);
        return $result;
    }

    /**
     * @param $data
     *
     * create new user ..
     */
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('USER_PKG', 'USERS_PROG_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists user ..
     *
     */
    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('USER_PKG', 'USERS_PROG_TB_UPDATE', $params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete user ..
     */
    function delete($id)
    {

        $params = array(
            array('name' => ':USER_ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('USER_PKG', 'USERS_PROG_TB_DELETE', $params);

        return $result['MSG_OUT'];

    }

    function change_pass($user_id = null, $old_pass = null, $new_pass = null)
    {
        $params = array();
        $data = array(
            array('name' => 'USER_ID', 'value' => $user_id, 'type' => '', 'length' => -1),
            array('name' => 'USER_PWD_NEW', 'value' => $new_pass, 'type' => '', 'length' => -1),
            array('name' => 'USER_PWD_OLD', 'value' => $old_pass, 'type' => '', 'length' => -1)
        );
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('USER_PKG', 'USERS_PROG_TB_CHANGE_PASS', $params);
        return $result['MSG_OUT'];
    }


    function get_name_gov($id, $n1, $n2, $n3, $n4)
    {
        $params = array();
        $data = array(
            array('name' => 'ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'N1', 'value' => $n1, 'type' => '', 'length' => -1),
            array('name' => 'N2', 'value' => $n2, 'type' => '', 'length' => -1),
            array('name' => 'N3', 'value' => $n3, 'type' => '', 'length' => -1),
            array('name' => 'N4', 'value' => $n4, 'type' => '', 'length' => -1)

        );
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TASK_ADMIN', 'UPDATE_PART_NAMEE', $params);
        return $result['MSG_OUT'];
    }



}