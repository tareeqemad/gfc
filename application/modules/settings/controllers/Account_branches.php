<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/12/14
 * Time: 08:48 ص
 */

class Account_branches extends  MY_Controller{

    var $MODEL_NAME= 'account_branches_model';
    var $PAGE_URL= 'settings/account_branches/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function index(){
        add_js('jquery.tree.js');
        $data['title']='صلاحيات الحسابات للفروع';
        $data['content']='account_branches_index';
        $data['branches'] = $this->select_branch();
        $this->load->view('template/template',$data);
    }

    function select_branch(){
        $this->load->model('gcc_branches_model');
        $all= $this->gcc_branches_model->get_all();
        $select= "<select name='branch' id='txt_branch' class='form-control'>
                    <option></option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['NO']}'>{$row['NAME']}</option>";
        }
        $select.= "</select>";
        return $select;
    }

    function get_accounts(){
        $branch= $this->input->post('branch');
        $accounts= $this->{$this->MODEL_NAME}->get_list($branch);
        $this->return_json($accounts);
    }

    function create(){
        $branch = $this->input->post('branch');
        $account = $this->input->post('account');
        $del= $this->_delete($branch);

        if(is_array($account) and $del==1){
            foreach($account as $val){
                $result = array(
                    array('name'=>'ACCOUNT_IN','value'=>$val,'type'=>'','length'=>-1),
                    array('name'=>'BRANCHES_IN','value'=>$branch,'type'=>'','length'=>-1),
                );
                $output = $this->{$this->MODEL_NAME}->create($result);
                $this->Is_success($output);
            }
        }
        echo 1;
    }

    function _delete($id){
        $this->IsAuthorized();
        return $this->{$this->MODEL_NAME}->delete($id);
    }


}