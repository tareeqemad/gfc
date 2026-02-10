<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 09/10/14
 * Time: 09:51 ص
 */

class Budget_model extends  MY_Model{

    /**
     *
     * return statistic of budget by years
     * @param $year
     * @return mixed
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function budget_statistic($year){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>$year ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('Statistics_PKG', 'BUDGET_HISTORY_TB_SAT', $params);
        return $result;
    }
    function budget_statistic_branch($year){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>$year ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('Statistics_PKG', 'BUDGET_HISTORY_GCC_BRANCHES_TB', $params);
        return $result;
    }

    function budget_exp_rev_statistic($type,$year,$adopt){

        $params =array(
            array('name'=>':EXP_REV_TYPE_IN','value'=>$type ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':YYEAR_IN','value'=>$year ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('Statistics_PKG', 'BUDGET_EXP_REV_TB_SAT', $params);
        return $result;
    }

    function  budget_exp_rev_up_tb_balance($branch = null,$chapter_no = null,$section_no = null,$account_id = null,$from_date = null,$to_date = null){ // New_rmodel Done

        $params =array(
            array('name'=>':BRANCH_IN','value'=>$branch ,'type'=>'','length'=>-1),
            array('name'=>':CHAPTER_NO_IN','value'=>$chapter_no ,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section_no ,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID_IN','value'=>$account_id ,'type'=>'','length'=>-1),
            array('name'=>':FROM_DATE','value'=>$from_date ,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$to_date ,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),

        );
        $result = $this->New_rmodel->general_get('MV_PKG', 'BUDGET_EXP_REV_UP_TB_BALANCE', $params,0);
        return $result['CUR_RES'];
    }

    function  budget_balance_chapter($branch = null,$chapter_no = null,$section_no = null,$account_id = null,$from_date = null,$to_date = null){ // New_rmodel Done

        $params =array(
            array('name'=>':BRANCH_IN','value'=>$branch ,'type'=>'','length'=>-1),
            array('name'=>':CHAPTER_NO_IN','value'=>$chapter_no ,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section_no ,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID_IN','value'=>$account_id ,'type'=>'','length'=>-1),
            array('name'=>':FROM_DATE','value'=>$from_date ,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$to_date ,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),

        );
        $result = $this->New_rmodel->general_get('MV_PKG', 'BUDGET_BALANCE_CHAPTER', $params,0);
        return $result['CUR_RES'];
    }

    function  budget_balance_no_branch($branch = null,$chapter_no = null,$section_no = null,$account_id = null,$from_date = null,$to_date = null){ // New_rmodel Done

        $params =array(
            array('name'=>':BRANCH_IN','value'=>$branch ,'type'=>'','length'=>-1),
            array('name'=>':CHAPTER_NO_IN','value'=>$chapter_no ,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section_no ,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID_IN','value'=>$account_id ,'type'=>'','length'=>-1),
            array('name'=>':FROM_DATE','value'=>$from_date ,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$to_date ,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),

        );
        $result = $this->New_rmodel->general_get('MV_PKG', 'BUDGET_BALANCE_NO_BRANCH', $params,0);
        return $result['CUR_RES'];
    }



    function budget_exp_rev_sec_items($year,$wsql){

        $params =array(
            array('name'=>':THE_YEAR_IN','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':INSQL','value'=>$wsql ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('BUDGET_PKG', 'BUDGET_EXP_REV_SEC_ITEMS', $params);
        return $result;
    }

}