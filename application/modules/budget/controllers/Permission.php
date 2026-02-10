<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/7/14
 * Time: 11:53 AM
 */

class Permission extends MY_Controller{

    function  __construct(){
        parent::__construct();

        $this->load->model('permission_model');

    }

    /**
     *
     * index action perform all functions in view of user menus_show view
     * from this view , can show user menus tree , insert new system menu , update exists one and delete other ..
     *
     */

    function index(){



        $this->load->model('settings/users_model');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_js('jquery.tree.js');
        add_js('select2.min.js');

        $data['title']='صلاحيات الموازنة';
        $data['content']='permission_index';

        $data['users'] = $this->users_model->get_all();

        $this->load->view('template/template',$data);
    }

    /**
     * get permission by id ..
     */
    function get_id(){

        $SECTION_NO = $this->input->post('sec_no');
        $USER_NO= $this->input->post('user');

        $result = $this->permission_model->get($SECTION_NO,$USER_NO);

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

        $SEC_NO= $this->input->post('sec_no');
        $USER_ID= $this->input->post('user_id');
        $DEPT_DIRECT_ADOPT= $this->input->post('dept_direct_adopt');
        $BRANCH_DIRECT_ADOPT= $this->input->post('branch_direct_adopt');
        $MAIN_DIRECT_ADOPT= $this->input->post('main_direct_adopt');
        $CAN_EDIT= $this->input->post('can_edit');

        $this->_delete_permission($USER_ID,$SEC_NO);

        if(is_array($SEC_NO)){
            foreach($SEC_NO as $val){

                $result = array(
                    array('name'=>'SECTION_NO','value'=>$val,'type'=>'','length'=>-1),
                    array('name'=>'USER_NO','value'=>$USER_ID,'type'=>'','length'=>-1),
                    array('name'=>'DEPT_DIRECT_ADOPT','value'=>$DEPT_DIRECT_ADOPT,'type'=>'','length'=>-1),
                    array('name'=>'BRANCH_DIRECT_ADOPT','value'=>$BRANCH_DIRECT_ADOPT,'type'=>'','length'=>-1),
                    array('name'=>'MAIN_DIRECT_ADOPT','value'=>$MAIN_DIRECT_ADOPT,'type'=>'','length'=>-1),
                    array('name'=>'CAN_EDIT','value'=>$CAN_EDIT,'type'=>'','length'=>-1)
                );

                $output = $this->permission_model->create($result);
            }
        }

        echo $output;

    }

    /**
     * delete action : delete system menu data ..
     * receive prm key as request
     *
     */
    function delete(){


        $SEC_NO= $this->input->post('sec_no');
        $USER_ID= $this->input->post('user_id');

        $this->_delete_permission($USER_ID,$SEC_NO);


    }

    /**
     * delete action : delete system menu data ..
     * receive prm key as request
     *
     */
    function _delete_permission($USER_ID,$SEC_NO){



        $this->IsAuthorized();

        if(is_array($SEC_NO)){
            foreach($SEC_NO as $val){
                $this->permission_model->delete($USER_ID,$val);
            }}
    }



}