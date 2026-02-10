<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 08/08/20
 * Time: 09:03 ص
 */


class draft_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($procedure,$sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG',$procedure,$params);

        return $result;
    }
    /************************************************/
    function adopt_info($SUB_NO,$SUB_NAME,$ID,$SER_ACTION,$TYPE,$user_id,$branch,$adopt){

        $params =array(
            array('name'=>':SUB_NO_IN','value'=>$SUB_NO,'type'=>'','length'=>-1),
            array('name'=>':SUB_NAME_IN','value'=>$SUB_NAME,'type'=>'','length'=>-1),
            array('name'=>':ID_IN','value'=>$ID,'type'=>'','length'=>-1),
            array('name'=>':SER_ACTION_IN','value'=>$SER_ACTION,'type'=>'','length'=>-1),
            array('name'=>':TYPE_IN','value'=>$TYPE,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_USER_IN','value'=>$user_id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_BRANCH_USER_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('LAW_PKG','CONTRI_PAID_ADOPT',$params);
        return $result['MSG_OUT'];

    }
}