<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 15/07/20
 * Time: 11:50 ص
 */


class Training_model extends MY_Model
{
    public $package;

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
	function getTwoID( $procedure, $id1, $id2)
    {
        
        $params = array(
            array('name' => ':ID1', 'value' => $id1, 'type' => '', 'length' => -1),
            array('name' => ':ID2', 'value' => $id2, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);

        return $result;
    }

    function getList_Para($procedure, $sql, $offset, $row, $month)
    {

        
        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':offset', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':row', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':month', 'value' => $month, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );

        //var_dump($month); die;
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);

        return $result;
    }

    function getList_twoColumn($procedure, $sql1 , $sql2, $offset, $row)
    {

        
        $params = array(
            array('name' => ':INXSQL1', 'value' => "{$sql1}", 'type' => '', 'length' => -1),
            array('name' => ':INXSQL2', 'value' => "{$sql2}", 'type' => '', 'length' => -1),
            array('name' => ':offset', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':row', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);

        return $result;
    }

    function public_get_twoDet($num){
        $params = array(
            array('name'=>':TB_NO_IN','value'=>$num,'type'=>'','length'=>-1),
            array('name'=>':msgs_cur', 'value'=>'msgs_cur', 'type'=>'cur', 'length' => -1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->package,'CONSTANTS_DETAIL_GET',$params);
        return $result['msgs_cur'];
    }

    function get_threeCol($procedure, $id, $con_no, $for_month)
    {
        
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':CON_NO', 'value' => $con_no, 'type' => '', 'length' => -1),
            array('name' => ':FOR_MONTH', 'value' => $for_month, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);

        return $result;
    }

}