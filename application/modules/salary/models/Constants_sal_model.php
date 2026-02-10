<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/07/19
 * Time: 11:37 ص
 */

class constants_sal_model extends MY_Model{
    var $PKG_NAME= "SALARY_EMP_PKG";
    var $TABLE_NAME= 'CONSTANTS_SAL_DET';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($id= 0){

        $params =array(
            array('name'=>':TB_NO','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }

}
