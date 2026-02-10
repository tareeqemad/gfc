<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/06/18
 * Time: 01:26 م
 */

class finger_attendance_model extends MY_Model
{
    var $PKG_NAME = "HR_ATTENDANCE_PKG";
    var $TABLE_NAME = 'DATA.ATTENDANCE';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }


    function get_list($sql,$offset,$row){
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'FINGER_ATTENDANCE_LIST',$params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'FINGER_ATTENDANCE_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function delete($no,$emp_no){
        $params =array(
            array('name'=>':NO','value'=>$no,'type'=>'','length'=>-1),
            array('name'=>':EMPLOYEENO','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'FINGER_ATTENDANCE_DELETE',$params);
        return $result['MSG_OUT'];
    }


    function auto_attendance($emp_no, $status){
        $params = array(
            array('name'=>':EMPLOYEENO_IN','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':STATUS_IN','value'=>$status,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'FINGER_ATTENDANCE_AUTO', $params);

        return $result['MSG_OUT']; // '1' نجاح، غير هيك رسالة خطأ عربي
    }

    function get_last_status($emp_no){
        $params = array(
            array('name'=>':EMPLOYEENO_IN','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':STATUS_OUT','value'=>'STATUS_OUT','type'=>'','length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_UI_ATT_STATUS_P', $params);

        // رجع رقم (0/1/4)
        return isset($result['STATUS_OUT']) ? (int)$result['STATUS_OUT'] : 0;
    }

    function get_last_in_out_24h($emp_no){
        $params = array(
            array('name'=>':EMPLOYEENO_IN','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':LAST_IN_OUT','value'=>'LAST_IN_OUT','type'=>'','length'=>500),
            array('name'=>':LAST_OUT_OUT','value'=>'LAST_OUT_OUT','type'=>'','length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_LAST_IN_OUT_24H_P', $params);

        return array(
            'last_in'  => isset($result['LAST_IN_OUT']) ? $result['LAST_IN_OUT'] : null,
            'last_out' => isset($result['LAST_OUT_OUT']) ? $result['LAST_OUT_OUT'] : null,
        );
    }
}
