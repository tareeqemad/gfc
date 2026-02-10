<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Modified by ..
 * User: Ahmed Barakat
 * Date: 6/7/14
 * Time: 1:20 PM
 */

class DBConn{
    // private $ip   = '192.168.1.5' ;
    public $user = 'GFC_PAK' ;
    private $pass = 'pNCSFuxMArxf' ;
    private $instance = '213.244.76.204:1521/HR'; // GFCARCH
    private $encode =  'AL32UTF8';
    private $CI;
    private $conn = '';
    private $output = array();
    private $lob = array();
    private $cur_ref = array('cur','cr','cursor','refcursor',SQLT_RSET);

    function __construct(){
        $this->CI =& get_instance();

      // mkilani- change DB..
        $db_ins=$this->CI->session->userdata('db_instance');
        if($db_ins and strlen($db_ins)>0){
            //$this->instance= $db_ins;
        }
 
 
        if($this->CI->session->userdata('user_data')){
            $current_user = $this->CI->session->userdata('user_data');
            $db_pass = 'A'.substr(md5($current_user->db_pwd),3);

            $this->conn = oci_new_connect( $current_user->username , $db_pass ,$this->instance , $this->encode ) or die ('حدث مشكلة أثناء عملية الإتصال - تأكد من صحة البيانات');
        } else {
            $this->conn = oci_new_connect( $this->user , $this->pass ,$this->instance , $this->encode ) or die ('حدث مشكلة أثناء عملية الإتصال - تأكد من صحة البيانات');
        }
	 

    }

    function getCI()

    {
        return $this->CI;
    }

    function close(){
        oci_close($this->conn);
    }

    /*
    function set_connection_parms($user,$pass){
        $this->user = $user;
        $this->pass = $pass;
        $this->instance = 'GFC';
        $this->encode = 'AL32UTF8';
        //$this->conn = oci_pconnect( $this->user , $this->pass ,$this->ip.'/'.$this->instance , $this->encode ) or die ('حدث مشكلة أثناء عملية الإتصال - تأكد من صحة البيانات');
        $this->conn = oci_pconnect( $this->user , $this->pass ,$this->instance , $this->encode ) or die ('حدث مشكلة أثناء عملية الإتصال - تأكد من صحة البيانات');
    }
    */
    function excuteProcedures($package,$procedure,$params){

        $package = $package==''?'':$package.'.';
        $sql = "begin $package$procedure(";
        foreach ($params as $param)
        {
            $sql .= $param['name'] . ",";
        }
        $sql = trim($sql, ",") . "); end;";


        $stmt = oci_parse($this->conn,$sql);
        $refcur = $this->bind_params($stmt,$params);
        $exc = @oci_execute($stmt, OCI_DEFAULT);
        if(!$exc)
            $this->error_handeling($exc,$stmt);
        if($refcur){
            $this->excuteCursor($params,$refcur);
        }

        if(count($this->lob) > 0){
            $this->excuteBLOB($params,$this->lob);
        }
        oci_commit($this->conn);

        return $this->output;
    }



    function bind_params($stmt,$params)
    {
        $i=0; $outCount = 0; $this->output = array(); $refcur = ''; $lob_counter = 0;
        foreach ($params as $param)
        {
            if(!in_array($param['type'],$this->cur_ref) && $param['type'] != 'wblob' && $param['type'] != 'rblob' && $param['type'] != 'sblob'){
                if(!is_array($param['value'])){
                    $this->output[$param['value']] = $param['value'];
                    $bind = @oci_bind_by_name($stmt, $param['name'],$this->output[$param['value']],$param['length'],($param['type'] == '')?NULL:$param['type']);
                    $this->error_handeling($bind,$stmt);
                }
                else{
                    $size = count($param['value'])==0?500:count($param['value']);
                    $item_length = count($param['value'])==0?500:-1;
                    $bind = @oci_bind_array_by_name($stmt, $param['name'],$param['value'],$size,$item_length,($param['type'] == '')?NULL:$param['type']);


                    $this->error_handeling($bind,$stmt);
                }
            }
            else if($param['type'] == 'wblob' || $param['type'] == 'rblob' || $param['type'] == 'sblob'){
                $this->lob[$lob_counter] = oci_new_descriptor($this->conn, OCI_D_LOB);
                $bind = @oci_bind_by_name($stmt,$param['name'], $this->lob[$lob_counter], -1, OCI_B_BLOB);
                $this->error_handeling($bind,$stmt);
                $lob_counter++;
            }
            else{
                $refcur= ocinewcursor ($this->conn);
			 
                $bind = @oci_bind_by_name($stmt, $param['name'], $refcur, -1, OCI_B_CURSOR);
                $this->error_handeling($bind,$stmt);
                $i++;
            }
        }

        return $refcur;
    }

    function excuteCursor($params,$refcur){
        $cursorCounter = 0;
        foreach ($params as $param)
        {
            if(in_array($param['type'],$this->cur_ref)){
                $exc = @oci_execute($refcur, OCI_DEFAULT);
                //$this->error_handeling($exc,$refcur[$cursorCounter]);
                if($exc){
                    $fetch = @oci_fetch_all($refcur, $this->output[$param['value']], null, null, OCI_FETCHSTATEMENT_BY_ROW);
                }
                else
                    $this->output[(int)$param['value']] = array();
                //$this->error_handeling($fetch,$refcur[$cursorCounter]);
                $cursorCounter ++;
            }
        }
    }

    function excuteBLOB($params,$lob){
        $lobCounter = 0;
        foreach ($params as $param)
        {
            if($param['type']== 'wblob' || $param['type']== 'rblob' || $param['type']== 'sblob'){
                if($param['type']== 'wblob' && $param['value'] != ''){
                    $this->lob[$lobCounter]->savefile($param['value']);
                }
                else if($param['type'] == 'sblob' && $param['value'] != '')
                    $this->lob[$lobCounter]->save($param['value']);
                else
                    $this->output[$param['value']] = $this->lob[$lobCounter];
                $lobCounter ++;
            }
        }
    }

    function error_handeling($exc,$stmt){
        if (!$exc) {
            $controller = $this->CI->router->class;
            $err =oci_error($stmt);
            $res = array(
                'success'=>0,
                'err_msg'=>$err['message']
            );
            $this->output['success'] = 0;
            $this->output['err_msg'] = $err['message'];

            if($controller == 'ajax_handler'){
                header('Content-type: text/json');
                echo json_encode($this->output);
            }
            else{
                //echo '<pre>';
                print_r($res['err_msg']);
              //   echo '<pre/>';

                return $res['err_msg'];
            }
            // exit;
        }
    }
}