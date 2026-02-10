<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 10:34 AM
 */


class Accounts extends  MY_Controller{

    var $ACOUNT_PARENT_ID;
    var $ACOUNT_ID;
    var $ACOUNT_NAME;
    var $ACOUNT_TYPE;
    var $CURR_ID;
    var $ACOUNT_FOLLOW;
    var $ACOUNT_CASH_FLOW;



    function  __construct(){
        parent::__construct();

        $this->load->model('accounts_model');

    }

    /**
     *
     * index action perform all functions in view of accounts_show view
     * from this view , can show accounts tree , insert new account , update exists one and delete other ..
     *
     */
    function index(){
        $this->load->helper('generate_list');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');

        // add_css('jquery-hor-tree.css');
        add_css('combotree.css');
        add_js('jquery.tree.js');


        $data['title']=' شجرة الحسابات';
        $data['content']='accounts_index';


        $resource =  $this->_get_structure(0);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{ACOUNT_ID}" class="adapt_{ADOPT}" ondblclick="javascript:account_get(\'{ACOUNT_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{ACOUNT_ID} : {ACOUNT_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="accounts">'.generate_list($resource, $options, $template).'</ul>';
        // $data['tree'] = '<ul class="tree" id="accounts"><li class="parent_li"><span data-id="0" >شركة الكهرباء</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';

        $data['currency'] = $this->currency_model->get_all();
        $data['follow'] = $this->constant_details_model->get_list(1);
        $data['cash_flow'] = $this->constant_details_model->get_list(2);
        $data['help']=$this->help;

        $this->load->view('template/template',$data);
    }


    function public_select_accounts($txt){
        $this->load->helper('generate_list');

        add_css('combotree.css');
        add_js('jquery.tree.js');


        $data['title']=' شجرة الحسابات';
        $data['content']='accounts_select';


        $resource =  $this->accounts_model->getList(null,null,4);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{ACOUNT_ID}" data-curr="{CURR_ID}"  ondblclick="javascript:select_account(\'{ACOUNT_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{ACOUNT_ID} : {ACOUNT_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="accounts">'.generate_list($resource, $options, $template).'</ul>';
        $data['txt']=$txt;

        $this->load->view('template/view',$data);
    }

    /**
     * get account by id ..
     */
    function get_id(){

        $id = $this->input->post('id');
        $result = $this->accounts_model->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get accounts tree structure ..
     *
     */
    function _get_structure($parent = 0,$user = null,$level = 0) {
        $result = $this->accounts_model->getList($parent,$user,$level);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['ACOUNT_ID'],$user,$level);
            $i++;
        }
        return $result;
    }

    /**
     * create action : insert new account data ..
     * receive post data of account
     *
     */
    function create(){

        $result= $this->accounts_model->create($this->_postedData());
        $this->Is_success($result);
        $this->return_json($result);

    }



    function get_accounts($click = false){

        $user =($this->input->post('user'))?$this->input->post('user'):0;
        $click =($this->input->post('click'))?$this->input->post('click'):false;


        $this->load->helper('generate_list');

        $resource =  $this->_get_user_accounts($user,0,0,$click);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );



        $template = '<li><span data-id="{ACOUNT_ID}"  {ONDBLCLICK}>{IS_CHECKED}{ACOUNT_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="accounts_tree">'.generate_list($resource, $options, $template).'</ul></li></ul>';


        $data['user']=$user;
        $this->load->view('accounts_tree',$data);

    }


    /**
     * @return mixed
     * return gcc structure tree json type
     */
    function public_get_accounts_json($add=0, $start=0){

        if($add==1){
            $arr= array(array('ACOUNT_PARENT_ID'=>0,'ACOUNT_ID'=>'-1','ACOUNT_NAME'=>'لا يوجد حساب', 'subs'=>array()));
            $array=$this->_get_structure($start);
            array_splice($array, 0, 0, $arr);
            $result = json_encode($array);
        }else{
            $result = json_encode($this->_get_structure(0));
        }
        $result=str_replace('subs','children',$result);
        $result=str_replace('ACOUNT_ID','id',$result);
        $result=str_replace('ACOUNT_NAME','text',$result);
        echo $result;

    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get user tree accounts ..
     *
     */
    function _get_user_accounts($user,$parent ,$level = 0,$click=false) {

        $result = $this->accounts_model->getList($parent,$user);
        $i = 0;
        $level++;

        foreach($result as $key => $item)
        {
            if($level >= 5 && $item['ACOUNT_TYPE'] == '2')
                $result[$i]['IS_CHECKED'] ="<input  type=\"checkbox\" name=\"account_no\" {$item['IS_CHECKED']} value=\"{$item['ACOUNT_ID']}\" />";
            if($click && $level >= 5 && $item['ACOUNT_TYPE'] == '2')
                $result[$i]['ONDBLCLICK'] = " ondblclick=\"javascript:account_get('{$item['ACOUNT_ID']}',this);\" ";

            $result[$i]['subs'] = $this->_get_user_accounts($user,$item['ACOUNT_ID'],$level,$click);
            $i++;
        }



        return $result;
    }


    /**
     * update adapt
     *
     */
    function update_adapt(){

        $ADAPT = $this->input->post('adapt');
        $this->ACOUNT_ID= $this->input->post('acount_id');

        $ADAPT =intval($ADAPT) == 1?0:1;

        $result = array(
            array('name'=>'ACOUNT_ID','value'=>$this->ACOUNT_ID ,'type'=>'','length'=>-1),
            array('name'=>'ADOPT','value'=>$ADAPT,'type'=>'','length'=>-1)
        );

        $result= $this->accounts_model->update_adapt($result);
        $this->Is_success($result);
        $this->return_json($result);

    }


    /**
     * edit action : update exists account data ..
     * receive post data of account
     * depended on account prm key
     */
    function edit(){

        echo $this->accounts_model->edit($this->_postedData());

    }

    /**
     * delete action : delete account data ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');

        $this->IsAuthorized();

        if(is_array($id)){
            foreach($id as $val){
                echo   $this->accounts_model->delete($val);
            }
        }else{
            echo $this->accounts_model->delete($id);
        }

    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData(){

        $this->ACOUNT_PARENT_ID = $this->input->post('acount_parent_id');
        $this->ACOUNT_ID= $this->input->post('acount_id');
        $this->ACOUNT_NAME= $this->input->post('acount_name');
        $this->ACOUNT_TYPE= $this->input->post('acount_type');
        $this->CURR_ID= $this->input->post('curr_id');
        $this->ACOUNT_FOLLOW= $this->input->post('acount_follow');
        $this->ACOUNT_CASH_FLOW= $this->input->post('acount_cash_flow');
        $BUDGET_ACCOUNT =  $this->input->post('budget_account');
        $result = array(
            array('name'=>'ACOUNT_PARENT_ID','value'=>$this->ACOUNT_PARENT_ID ,'type'=>'','length'=>11),
            array('name'=>'ACOUNT_ID','value'=>$this->ACOUNT_ID,'type'=>'','length'=>11),
            array('name'=>'ACOUNT_NAME','value'=>$this->ACOUNT_NAME,'type'=>'','length'=>100),
            array('name'=>'ACOUNT_TYPE','value'=>$this->ACOUNT_TYPE,'type'=>'','length'=>1),
            array('name'=>'CURR_ID','value'=>$this->CURR_ID,'type'=>'','length'=>2),
            array('name'=>'ACOUNT_FOLLOW','value'=>$this->ACOUNT_FOLLOW,'type'=>'','length'=>2),
            array('name'=>'ACOUNT_CASH_FLOW','value'=>$this->ACOUNT_CASH_FLOW,'type'=>'','length'=>2),
            array('name'=>'BUDGET_ACCOUNT','value'=>$BUDGET_ACCOUNT,'type'=>'','length'=>-1)
        );

        return $result;
    }
}