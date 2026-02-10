<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/21/14
 * Time: 9:15 AM
 */

class Accounts_permission extends  MY_Controller{
    function  __construct(){
        parent::__construct();

        $this->load->model('accounts_permission_model');

    }

    /**
     *
     * index action perform all functions in view of user menus_show view
     * from this view , can show user menus tree , insert new system menu , update exists one and delete other ..
     *
     */

    function index(){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/users_model');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_js('jquery.tree.js');
        add_js('select2.min.js');

        $data['title']='صلاحيات الحسابات';
        $data['content']='accounts_permission_index';

        $data['users'] = $this->users_model->get_all();
        $data['types']= $this->constant_details_model->get_list(4);

        $this->load->view('template/template',$data);
    }

    /**
     * get accounts permission by id ..
     */
    function get_id(){

        $id = $this->input->post('id');
        $user = $this->input->post('user');
        $result = $this->accounts_permission_model->get($id,$user);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }


    /**
     * edit action : insert exists system menu data ..
     * receive post data of system menu
     * depended on system menu prm key
     */
    function create(){



        $output='';

        $ACCOUNT= $this->input->post('accounts');
        $USER_ID= $this->input->post('user_id');
        $income_type = $this->input->post('income_type');

        $this->_delete_permission($USER_ID,$ACCOUNT);

        if(is_array($ACCOUNT)){
            foreach($ACCOUNT as $val){

                $result = array(
                    array('name'=>'USER_ID_IN','value'=>$USER_ID,'type'=>'','length'=>-1),
                    array('name'=>'ACCOUNT_ID_IN','value'=>$val,'type'=>'','length'=>-1),
                    array('name'=>'INCOME_TYPE','value'=>$income_type,'type'=>'','length'=>-1)

                );

                $output = $this->accounts_permission_model->create($result);
            }
        }

        //$this->Is_success($output);
        $this->return_json($output);

    }

    /**
     * delete action : delete system menu data ..
     * receive prm key as request
     *
     */
    function delete(){

        $ACCOUNT= $this->input->post('accounts');
        $USER_ID= $this->input->post('user_id');


        $this->_delete_permission($USER_ID,$ACCOUNT);


    }

    /**
     * delete action : delete system menu data ..
     * receive prm key as request
     *
     */
    function _delete_permission($USER_ID,$ACCOUNT){



        $this->IsAuthorized();

        if(is_array($ACCOUNT)){
            foreach($ACCOUNT as $val){
                $this->accounts_permission_model->delete($USER_ID,$val);
            }}
    }

    /**
     * return User Accounts depend on income type , prefix of account , currency ..
     */
    function public_get_user_accounts($prefix='00',$type=null,$curr_id=null,$user = null,$case = 0,$account_id = null){

        $prefix= $this->input->post('perfix') ? $this->input->post('perfix') : $prefix;
        $type= $this->input->post('type') ? $this->input->post('type') : $type;
        $curr_id= $this->input->post('curr_id') ? $this->input->post('curr_id') : $curr_id;

        $user= $this->input->post('user') ? $this->input->post('user') : $user;
        $case= $this->input->post('case') ? $this->input->post('case') : $case;

        $account_id= $this->input->post('account_id') ? $this->input->post('account_id') : $account_id;


        $result= $this->accounts_permission_model->get_user_accounts($prefix,$type,$curr_id,$user,$case,$account_id);


        $result = json_encode($result);
        $result=str_replace('subs','children',$result);
        $result=str_replace('ACOUNT_ID','id',$result);
        $result=str_replace('ACOUNT_NAME','text',$result);
        echo $result;

    }


}