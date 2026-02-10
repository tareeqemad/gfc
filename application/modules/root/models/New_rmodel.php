<?php

/**
 * Created by PhpStorm.
 * User: MKilani
 * Date: 30/12/2021
 */

class New_rmodel extends MY_Model
{
    
    function general_get($package, $procedure, $params, $replace_last_params= 1){
	
		if($this->conn->user!='GFC_PAK'){
            $this->conn = new DBConn();
        }
		
		/*
		if( getenv('HTTP_X_FORWARDED_FOR')== '192.168.40.69' ){ 
			//usleep(16500);
		}
		*/

        // delete last 2 element from $params, then add it from here
        if($replace_last_params){
            $param_cursor= array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor');
            $param_msg= array('name'=> ':MSG_OUT', 'value'=> 'MSG_OUT', 'type'=> SQLT_CHR, 'length'=> 500);
            array_splice($params,-2);
            array_push($params, $param_cursor);
            array_push($params, $param_msg);
        }
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        if($replace_last_params){
            return $result['CUR_RES'];
        }else{
            return $result;
        }

    }
	
/*
    // for old Conn.. 
    function general_get_old($package, $procedure, $params, $replace_last_params= 1){

        // delete last 2 element from $params, then add it from here
        if($replace_last_params){
            $cursor = $this->db->get_cursor();
            $param_cursor= array('name'=> ':REF_CURSOR_OUT', 'value'=> $cursor, 'type'=> OCI_B_CURSOR);
            $param_msg= array('name'=> ':MSG_OUT', 'value'=> 'MSG_OUT', 'type'=> SQLT_CHR, 'length'=> 500);
            array_splice($params,-2);
            array_push($params, $param_cursor);
            array_push($params, $param_msg);
        }
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        if($replace_last_params){
            return $result[(int)$cursor];
        }else{
            return $result;
        }

    }
*/


    // insert, update, delete, adopt for gfc 202302
    function general_transactions($pkg, $procedure, $data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($pkg, $procedure, $params);
        return $result['MSG_OUT'];
    }

	
    // for bills 202211
    function general_bills_get($package, $procedure, $params, $replace_last_params= 1){

        $this->conn = new BillsConn();

        // delete last 2 element from $params, then add it from here
        if($replace_last_params){
            $param_cursor= array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor');
            $param_msg= array('name'=> ':MSG_OUT', 'value'=> 'MSG_OUT', 'type'=> SQLT_CHR, 'length'=> 500);
            array_splice($params,-2);
            array_push($params, $param_cursor);
            array_push($params, $param_msg);
        }
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        if($replace_last_params){
            return $result['CUR_RES'];
        }else{
            return $result;
        }

    }

    // insert, update, delete, adopt for bills 202301
    function general_bills_transactions($pkg, $procedure, $data){
        $params =array();
        $this->_extract_data($params,$data);
        $this->conn = new BillsConn();
        $result = $this->conn->excuteProcedures($pkg, $procedure,$params);
        return $result['MSG_OUT'];
    }



}
