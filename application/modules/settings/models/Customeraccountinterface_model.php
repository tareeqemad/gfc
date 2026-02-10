<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 9:31 AM
 */
class CustomerAccountInterface_model extends MY_Model
{

    /**
     * @return array
     *
     * return all gcc structure  data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function getAll()
    {


        $params = array(

            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );

        $result = $this->New_rmodel->general_get('PAYMENT_PKG', 'CUSTOMERS_ACCOUNT_INTERFACE_V', $params);

        return $result;
    }


    function customers_account_interf_acc($interface_no)
    {

        $params = array(
            array('name' => ':INTERFACE_NO_IN', 'value' => $interface_no, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );

        $result = $this->New_rmodel->general_get('PAYMENT_PKG', 'CUSTOMERS_ACCOUNT_INTERF_ACC', $params);

        return $result;
    }



    /**
     * @param $data
     *
     * create new gcc_structure ..
     */
    function create($interface_no, $account_no)
    {

        $params = array(
            array('name' => ':interface_no_in', 'value' => $interface_no, 'type' => '', 'length' => -1),
            array('name' => ':account_no_in', 'value' => $account_no, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('PAYMENT_PKG', 'CUSTOMERS_ACCOUNT_INTERFACE_IN', $params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $id
     * delete gcc_structure ..
     */
    function delete($id)
    {


        $params = array(
            array('name' => ':ST_ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('PAYMENT_PKG', 'CUSTOMERS_ACCOUNT_INTERFACE_DE', $params);

        return $result['MSG_OUT'];

    }


}