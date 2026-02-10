<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 13/04/20
 * Time: 09:19 ص
 */
class morning_delay_model extends MY_Model
{

    var $PKG_NAME = "TRANSACTION_PKG";
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function update_is_active($p_ser,$is_active)
    {
        $params = array(
            array('name' => ':P_SER', 'value' => $p_ser, 'type' => '', 'length' => -1),
            array('name' => ':IS_ACTIVE', 'value' => $is_active, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DELAY_EMP_UPDATE', $params);
        return $result['MSG_OUT'];
    }

}



