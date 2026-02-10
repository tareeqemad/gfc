<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 11/10/15
 * Time: 02:16 م
 */

class Donation_file_model extends MY_Model{
    var $PKG_NAME= "PAYMENT_PKG";
    var $TABLE_NAME= 'DONATION_FILE_TB';
    var $PKG_NAME_STORE= "STORES_PKG";
    var $TABLE_NAME_STORE= 'STORES_TB_GET_DONATION';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id= 0){

        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_store_donation(){

        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME_STORE, $this->TABLE_NAME_STORE,$params);
        return $result;
    }

    function get_list($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        // echo  $result['MSG_OUT'];
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }
    function donation_file_get_id($id= 0){

        $params =array(
            array('name'=>':DONATION_ACCOUNT_IN','value'=>$id ,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'DONATION_FILE_TB_GET_ID',$params);
        return $result;
    }

    function donation_file_get_id_by_store($id= 0){

        $params =array(
            array('name'=>':STORE_ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'DONATION_FILE_TB_GET_BY_STORE',$params);
        return $result;
    }

    function adopt($id,$donation_account,$store_id,$adopt){

        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':DONATION_ACCOUNT_IN','value'=>$donation_account,'type'=>'','length'=>-1),
            array('name'=>':STORE_ID_IN','value'=>$store_id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT',$params);;

        return $result['MSG_OUT'];

    }
    function adopt_close($id,$adopt){

        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':DONATION_FILE_CASE_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT_CLOSE',$params);;

        return $result['MSG_OUT'];

    }

    function un_adopt_store($id,$adopt){

        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':DONATION_FILE_CASE_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UN_ADOPT_STORE',$params);;

        return $result['MSG_OUT'];

    }



    function get_store_donation_adopt($id,$acount_donation,$adopt){

        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':DONATION_ACCOUNT_IN','value'=>$acount_donation,'type'=>'','length'=>-1),
            array('name'=>':DONATION_FILE_CASE_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_GET_STORE_DONATION_ADOPT',$params);;

        return $result['MSG_OUT'];

    }


    function donation_file_det_tb_check($ser){

        $params =array(
            array('name'=>':SER_IN','value'=>$ser,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'DONATION_FILE_DET_TB_CHECK',$params);;

        return $result['MSG_OUT'];

    }

    function get_count($did,$id, $name_ar, $name_en,$parent_id,$grand_id,$type=null){


        $params =array(
             array('name'=>':DONATION_FILE_ID_IN','value'=>$did,'type'=>'','length'=>-1),
            array('name'=>':CLASS_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NAME_AR_IN','value'=>"{$name_ar}",'type'=>'','length'=>-1),
            array('name'=>':NAME_EN_IN','value'=>"{$name_en}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_PARENT_ID_IN','value'=>"{$parent_id}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_GRAND_ID_IN','value'=>"{$grand_id}",'type'=>'','length'=>-1),
            array('name'=>':CALSS_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_COUNT',$params);
        return $result;
    }

    function get_lists($did,$id, $name_ar, $name_en,$parent_id,$grand_id, $offset, $row,$type=null){


        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>$did,'type'=>'','length'=>-1),
            array('name'=>':CLASS_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NAME_AR_IN','value'=>"{$name_ar}",'type'=>'','length'=>-1),
            array('name'=>':NAME_EN_IN','value'=>$name_en,'type'=>'','length'=>-1),
            array('name'=>':CLASS_PARENT_ID_IN','value'=>$parent_id,'type'=>'','length'=>-1),
            array('name'=>':CLASS_GRAND_ID_IN','value'=>"{$grand_id}",'type'=>'','length'=>-1),
            array('name'=>':CALSS_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        //    print_r($params);
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LISTS',$params);
       //echo $result['MSG_OUT'];
        return $result;
    }

}