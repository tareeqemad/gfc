<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 21/02/2019
 * Time: 09:31 ص
 */

class Dependent extends MY_Controller {

    var $MODEL_NAME = 'dependent_model';
    var $PAGE_URL = 'hr/dependent/get_page';
    var $PAGE_URL_R = 'hr/dependent/get_page_r';

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'DEPENDENT_PKG';
        $this->load->model($this->MODEL_NAME);

        $this->ser = $this->input->post('ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->emp_id = $this->input->post('emp_id');
        $this->adopt= $this->input->post('adopt');

        $this->from_date= $this->input->post('from_date');
        $this->to_date= $this->input->post('to_date');
        // array
        $this->res_data = $this->input->post('res_data');

        if( HaveAccess(base_url("hr/dependent/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

    }
    /***************************************************************************************************************/
    function index($page = 1, $act='')
    {
        $data['title'] = 'الشئون الادارية - المعالين';
        $data['content'] = 'dependent_index';
        $data['page'] = $page;
        $data['act'] = $act;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function index_con($page = 1,$ser= -1,$emp_no=-1,$emp_id=-1)
    {
        $data['title'] = 'الرقابة الداخلية - المعالين';
        $data['content'] = 'dependent_con_index';
        $data['ser']= $ser;
        $data['emp_no']= $emp_no;
        $data['$emp_id']= $emp_id;
        $data['page']=$page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    /**********************************************************************************************************************/
    function _postedData($idno_relative ,$fname_arb , $sname_arb , $tname_arb , $lname_arb,
                         $sex_cd , $relative_cd ,$social_status_cd , $date_status , $status_live , $deth_dt,
                         $travel_date )
    {
        $result = array(
            array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => 'EMP_ID', 'value' => $this->emp_id, 'type' => '', 'length' => -1),
            array('name' => 'IDNO_RELATIVE', 'value' => $idno_relative, 'type' => '', 'length' => -1),
            array('name' => 'FNAME_ARB', 'value' => $fname_arb, 'type' => '', 'length' => -1),
            array('name' => 'SNAME_ARB', 'value' => $sname_arb, 'type' => '', 'length' => -1),
            array('name' => 'TNAME_ARB', 'value' => $tname_arb, 'type' => '', 'length' => -1),
            array('name' => 'LNAME_ARB', 'value' => $lname_arb, 'type' => '', 'length' => -1),
            array('name' => 'SEX_CD', 'value' => $sex_cd, 'type' => '', 'length' => -1),
            array('name' => 'RELATIVE_CD', 'value' => $relative_cd, 'type' => '', 'length' => -1),
            array('name' => 'SOCIAL_STATUS_CD', 'value' => $social_status_cd, 'type' => '', 'length' => -1),
            array('name' => 'DATE_STATUS', 'value' => $date_status, 'type' => '', 'length' => -1),
            array('name' => 'STATUS_LIVE', 'value' => $status_live, 'type' => '', 'length' => -1),
            array('name' => 'DETH_DT', 'value' => $deth_dt, 'type' => '', 'length' => -1),
            array('name' => 'TRAVEL_DATE', 'value' => $travel_date, 'type' => '', 'length' => -1),
        );
        return $result;
    }
    /********************************************************************************************************************/

    ////استعلام عن بيانات موظف
    function get_page()
    {
        $emp_no = $this->input->post('emp_no');
        $data['page_rows'] = $this->rmodel->get('DEPENDENT_TB_GET_ALL', $emp_no);
        $this->load->view('dependent_page', $data);
    }
    /***************************************************************************/
    function get_page_r()
    {
        $emp_no = $this->input->post('emp_no');
        $data['page_relative'] =  $this->rmodel->get('RELATIVES_2019_GET', $emp_no);
        $this->load->view('dependent_old_page', $data);
    }

    /***********************************************************************/
    function _look_ups(&$data)
    {
        $this->load->model('employees/employees_model');
        $user_branch= ($this->all_branches)?'':$this->user->branch;
        $data["employee"] = $this->employees_model->get_all_from_data($user_branch);
        add_js('date_functions.js');
    }

    /************************check_var***********************/
    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = ($this->{$c_var}) ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }
    /*****************************************************************/
    function delete()
    {
        $id = $this->input->post('id');
        $msg = 0;

        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete($val);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete($id);
        }

        if ($msg == 1) {
            return 1;
        } else {

            $this->print_error_msg($msg);
        }
    }

    /*******************************************************/

    function update_dates(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!='' and $this->from_date!='' ){
            $res= $this->{$this->MODEL_NAME}->update_dates($this->ser, $this->from_date, $this->to_date);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الادخال'.'<br>'.$res);
            }
            echo $res;
        }else
            echo "يجب ادخال تاريخ بداية الاحتساب";
    }

    /*********************************************************************************************/
    private function adopt($case){
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم السند";
    }
    /******************************************************************************************/
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cnt_arr= [];
            for ($i = 0; $i < count($this->res_data); $i++) {

                if ($this->emp_no != '' and  $this->emp_id != '' and $this->res_data[$i]['IDNO_RELATIVE'] != '' ) {

                    $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData(
                        $this->res_data[$i]['IDNO_RELATIVE'],
                        $this->res_data[$i]['FNAME_ARB'],
                        $this->res_data[$i]['SNAME_ARB'],
                        $this->res_data[$i]['TNAME_ARB'],
                        $this->res_data[$i]['LNAME_ARB'],
                        $this->res_data[$i]['SEX_CD'],
                        $this->res_data[$i]['RELATIVE_CD'],
                        $this->res_data[$i]['SOCIAL_STATUS_CD'],
                        $this->res_data[$i]['DATE_STATUS'] ,
                        '', //$this->res_data[$i]['STATUS_LIVE'] ,
                        $this->res_data[$i]['DETH_DT']  ,
                        '' //$this->res_data[$i]['TRAVEL_DATE']
                    ));

                    if (intval($this->ser) <= 0){
                        $this->print_error(' لم يتم الحفظ الجدول الاساسي ' . '<br>' . $this->ser);
                    }else{
                        $cnt_arr[]= $this->ser;
                    }

                }
            }
            echo count($cnt_arr);

        }
    }
    /*********************************************************************************************************/



    ////////////////////////// mkilani 2020-01 //////////////////////////
    /*  dependent_from_mtit_tb  */

    /*
    select 'array('||a.no||','||a.id||'),'
    from data.employees   a
    where a.is_active = 1
    and a.no BETWEEN 1 and 100
    order by a.no
    */

    function public_get_mtit($id=0){
        $res = $this->{$this->MODEL_NAME}->get_mtit($id);
        $res = array('DATA'=>$res);
        $this->return_json($res);
    }

    function public_emps_mtit(){
        $emps= array(
            array(9999,8044224230),
            array(9999,8044224230),
        );

        foreach($emps as $emp){
            $this->create_mtit($emp[0],$emp[1]);
        }

        echo "<br>END #".count($emps)."<br>";

    }

    function create_mtit($emp_no=0, $emp_id=0){
        if($this->user->id!=111){
            die();
        }
        if($emp_id==0 or $emp_no==0){
            die('0');
        }

        $cnt= 0;
        $mtit_url= 'http://eservices.mtit.gov.ps/ws/gov-services/ws/getData';
        $relative_parm = '{"WB_USER_NAME_IN":"GEDCO4GOVDATA","WB_USER_PASS_IN":"DBDFB888518471E658FED26FED","DATA_IN": {"package":"MOI_GENERAL_PKG","procedure":"REL_CTZN_INFO_DET","user_id":'.$emp_id.'} }';
        if(strlen($emp_id)==9){
            $ret_relative= json_decode( $this->getHtml($mtit_url, $relative_parm) , true) ;
            $cnt_ret = count($ret_relative['DATA']);
            if($cnt_ret > 0){
                foreach($ret_relative['DATA'] as $data_row){
                    $ser= $this->{$this->MODEL_NAME}->create_mtit($this->_postedData_mtit(
                        $emp_no, $emp_id, $data_row['IDNO_RELATIVE'], $data_row['RELATIVE_CD'], $data_row['RELATIVE_DESC'], $data_row['FNAME_ARB'], $data_row['SNAME_ARB'], $data_row['TNAME_ARB'], $data_row['LNAME_ARB'], $data_row['MOTHER_ARB'], $data_row['PREV_LNAME_ARB'], $data_row['DETH_DT'], $data_row['ENG_NAME'], $data_row['BIRTH_DT'], $data_row['STREET_ARB'], $data_row['SEX_CD'], $data_row['SOCIAL_STATUS_CD'], $data_row['REGION_CD'], $data_row['CITY_CD'], $data_row['RELIGION_CD'], $data_row['BIRTH_MAIN_CD'], $data_row['BIRTH_SUB_CD'], $data_row['SEX'], $data_row['SOCIAL_STATUS'], $data_row['CI_REGION'], $data_row['CI_CITY'], $data_row['CI_RELIGION'], $data_row['BIRTH_PMAIN'], $data_row['BIRTH_PSUB']
                    ));
                    //echo '<html><head> <meta charset="utf-8"> </head> <body> <pre>'; print_r();

                    if(intval($ser) > 0){
                        $cnt++;
                    }
                }

                if( $cnt_ret == $cnt){
                    echo 'Done Emp '.$emp_no.' All #'.$cnt.'<br>';
                }else{
                    echo 'Done Error Emp '.$emp_no.' Part #'.$cnt.'/'.$cnt_ret.'<br>';
                }

            }else{
                echo 'Error No DATA ------ emp_no= '.$emp_no.'<br>';
            }

        }else{
            echo 'Error Emp id ------ emp_id= '.$emp_id.'<br>';
        }
    }


    function getHtml($url, $post = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds

        if(!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function _postedData_mtit( $emp_no, $emp_id, $idno_relative, $relative_cd, $relative_desc, $fname_arb, $sname_arb, $tname_arb, $lname_arb, $mother_arb, $prev_lname_arb, $deth_dt, $eng_name, $birth_dt, $street_arb, $sex_cd, $social_status_cd, $region_cd, $city_cd, $religion_cd, $birth_main_cd, $birth_sub_cd, $sex, $social_status, $ci_region, $ci_city, $ci_religion, $birth_pmain, $birth_psub ){
        $result = array(
            array('name'=>'EMP_NO','value'=>$emp_no ,'type'=>'','length'=>-1),
            array('name'=>'EMP_ID','value'=>$emp_id ,'type'=>'','length'=>-1),
            array('name'=>'IDNO_RELATIVE','value'=>$idno_relative ,'type'=>'','length'=>-1),
            array('name'=>'RELATIVE_CD','value'=>$relative_cd ,'type'=>'','length'=>-1),
            array('name'=>'RELATIVE_DESC','value'=>$relative_desc ,'type'=>'','length'=>-1),
            array('name'=>'FNAME_ARB','value'=>$fname_arb ,'type'=>'','length'=>-1),
            array('name'=>'SNAME_ARB','value'=>$sname_arb ,'type'=>'','length'=>-1),
            array('name'=>'TNAME_ARB','value'=>$tname_arb ,'type'=>'','length'=>-1),
            array('name'=>'LNAME_ARB','value'=>$lname_arb ,'type'=>'','length'=>-1),
            array('name'=>'MOTHER_ARB','value'=>$mother_arb ,'type'=>'','length'=>-1),
            array('name'=>'PREV_LNAME_ARB','value'=>$prev_lname_arb ,'type'=>'','length'=>-1),
            array('name'=>'DETH_DT','value'=>$deth_dt ,'type'=>'','length'=>-1),
            array('name'=>'ENG_NAME','value'=>$eng_name ,'type'=>'','length'=>-1),
            array('name'=>'BIRTH_DT','value'=>$birth_dt ,'type'=>'','length'=>-1),
            array('name'=>'STREET_ARB','value'=>$street_arb ,'type'=>'','length'=>-1),
            array('name'=>'SEX_CD','value'=>$sex_cd ,'type'=>'','length'=>-1),
            array('name'=>'SOCIAL_STATUS_CD','value'=>$social_status_cd ,'type'=>'','length'=>-1),
            array('name'=>'REGION_CD','value'=>$region_cd ,'type'=>'','length'=>-1),
            array('name'=>'CITY_CD','value'=>$city_cd ,'type'=>'','length'=>-1),
            array('name'=>'RELIGION_CD','value'=>$religion_cd ,'type'=>'','length'=>-1),
            array('name'=>'BIRTH_MAIN_CD','value'=>$birth_main_cd ,'type'=>'','length'=>-1),
            array('name'=>'BIRTH_SUB_CD','value'=>$birth_sub_cd ,'type'=>'','length'=>-1),
            array('name'=>'SEX','value'=>$sex ,'type'=>'','length'=>-1),
            array('name'=>'SOCIAL_STATUS','value'=>$social_status ,'type'=>'','length'=>-1),
            array('name'=>'CI_REGION','value'=>$ci_region ,'type'=>'','length'=>-1),
            array('name'=>'CI_CITY','value'=>$ci_city ,'type'=>'','length'=>-1),
            array('name'=>'CI_RELIGION','value'=>$ci_religion ,'type'=>'','length'=>-1),
            array('name'=>'BIRTH_PMAIN','value'=>$birth_pmain ,'type'=>'','length'=>-1),
            array('name'=>'BIRTH_PSUB','value'=>$birth_psub ,'type'=>'','length'=>-1),
        );
        return $result;
    }


    ////////////////////////// mkilani 2020-01 //////////////////////////


	
    // جلب هوية الاب والزوجة للاشتراكات 202306
    function sub_chk_tmp(){
        $this->load->model('root/New_rmodel');

        $all_ids= array( 800549925,800549925 );

        foreach ($all_ids as $id){

            $father=0;
            $wife=0;
            $father_arr= array();
            $wife_arr= array();
			
			usleep(200000);

            $mtit_url= 'http://eservices.mtit.gov.ps/ws/gov-services/ws/getData';
            $relative_parm = '{"WB_USER_NAME_IN":"GEDCO4GOVDATA","WB_USER_PASS_IN":"DBDFB888518471E658FED26FED","DATA_IN": {"package":"MOI_GENERAL_PKG","procedure":"REL_CTZN_INFO_DET","user_id":'.$id.'} }';
            if(strlen($id)==9){
                $ret_relative= json_decode( $this->getHtml($mtit_url, $relative_parm) , true) ;
                $cnt_ret = count($ret_relative['DATA']);
                if($cnt_ret > 0){
                    foreach($ret_relative['DATA'] as $data_row){
                        if($data_row['RELATIVE_CD']==1){
                            $father_arr[]= $data_row['IDNO_RELATIVE'];
                        }else if($data_row['RELATIVE_CD']==4){
                            $wife_arr[]= $data_row['IDNO_RELATIVE'];
                        }
                    }

                    if(count($father_arr)==1){
                        $father= $father_arr[0];
                    }else{
                        $father= count($father_arr);
                    }
                    if(count($wife_arr)>0){
                        $wife= $wife_arr[0];
                        $wife_arr[]= null;$wife_arr[]= null;$wife_arr[]= null;$wife_arr[]= null;
                    }else{
                        $wife= count($wife_arr);
                    }
                }
            }

            $params =array(
                array('name'=>'id','value'=>$id,'type'=>'','length'=>-1),
                array('name'=>'father','value'=>$father,'type'=>'','length'=>-1),
                array('name'=>'wife','value'=>$wife,'type'=>'','length'=>-1),
                array('name'=>'wife2','value'=>$wife_arr[1],'type'=>'','length'=>-1),
                array('name'=>'wife3','value'=>$wife_arr[2],'type'=>'','length'=>-1),
                array('name'=>'wife4','value'=>$wife_arr[3],'type'=>'','length'=>-1),
            );
            $result = $this->New_rmodel->general_transactions('DEPENDENT_PKG','SUB_CHK_TMP_UPDATE',$params);
            echo $id.'-'.$result['MSG_OUT'].'<br>';
        }
    } // fun sub_chk_tmp

}
