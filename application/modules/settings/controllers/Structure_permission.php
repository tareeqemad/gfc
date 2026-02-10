<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/17/14
 * Time: 9:28 AM
 */

class Structure_permission extends MY_Controller{



    function  __construct(){
        parent::__construct();

        $this->load->model('structure_permission_model');

    }

    /**
     *
     * index action perform all functions in view of user menus_show view
     * from this view , can show user menus tree , insert new structure permission , update exists one and delete other ..
     *
     */

    function index($id = 0){

        $this->load->model('users_model');

        $this->load->model('structure_permission_model');
        add_css('select2_metro_rtl.css');
        add_js('jquery.tree.js');
        add_js('select2.min.js');

        $data['title']='صلاحيات مستخدم للهيكلية';
        $data['content']='structure_permission_index';

        $data['users'] = $this->users_model->get_all();

        $this->load->view('template/template',$data);
    }

    function get_structure(){

        $user =($this->input->post('user'))?$this->input->post('user'):0;

        $this->load->helper('generate_list');

        $resource =  $this->_get_structure(0,'get_list',$user);


        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );


        $template = '<li><span data-id="{ST_ID}" ondblclick="javascript:structure_permission_get(\'{ST_ID}\',this);" ><input type="checkbox" name="st_id" {IS_CHECKED} value="{ST_ID}" />{ST_NAME}</span>{SUBS}</li>';

        // $data['tree'] = '<ul class="tree" id="structure_permission">'.generate_list($resource, $options, $template).'</ul>';
        $data['tree'] = '<ul class="tree" id="structure_permission"><li><span>القوائم</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';
        $data['user']=$user;
        $this->load->view('structure_permission_tree',$data);

    }

    /**
     * get structure permission by id ..
     */
    function get_id(){

        $id = $this->input->post('st_id');
        $user = $this->input->post('user');
        $result = $this->structure_permission_model->get($id,$user);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get user menus tree structure ..
     *
     */
    function _get_structure($parent = 0,$fun,$user) {
        $result = $this->structure_permission_model->{$fun}($parent,$user);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['ST_ID'],$fun,$user);
            $i++;
        }
        return $result;
    }

    /**
     * edit action : insert exists structure permission data ..
     * receive post data of structure permission
     * depended on structure permission prm key
     */
    function create(){



        $output='';

        $ST_ID= $this->input->post('st_id');
        $USER_ID= $this->input->post('user_id');

        $OVER_TIME_DEPT_DIRECT_ADOPT= $this->input->post('over_time_dept_direct_adopt');
        $OVER_TIME_BR_DIR_ADOPT= $this->input->post('over_time_branch_direct_adopt');
        $OVER_TIME_MAIN_DIRECT_ADOPT= $this->input->post('over_time_main_direct_adopt');
        $OVER_TIME_CAN_EDIT= $this->input->post('over_time_can_edit');

        $this->delete($USER_ID);

        if(is_array($ST_ID)){
            foreach($ST_ID as $val){
                $result = array(
                    array('name'=>'USER_NO','value'=>$USER_ID,'type'=>'','length'=>-1),
                    array('name'=>'ST_ID','value'=>$val,'type'=>'','length'=>-1),
                    array('name'=>'OVER_TIME_DEPT_DIRECT_ADOPT','value'=>$OVER_TIME_DEPT_DIRECT_ADOPT,'type'=>'','length'=>-1),
                    array('name'=>'OVER_TIME_BR_DIR_ADOPT','value'=>$OVER_TIME_BR_DIR_ADOPT,'type'=>'','length'=>-1),
                    array('name'=>'OVER_TIME_MAIN_DIRECT_ADOPT','value'=>$OVER_TIME_MAIN_DIRECT_ADOPT,'type'=>'','length'=>-1),
                    array('name'=>'OVER_TIME_CAN_EDIT','value'=>$OVER_TIME_CAN_EDIT,'type'=>'','length'=>-1)
                );


                $output = $this->structure_permission_model->create($result);
            }
        }

        echo $output;
    }



    /**
     * delete action : delete structure permission data ..
     * receive prm key as request
     *
     */
    function delete(){


        $SEC_NO= $this->input->post('st_id');
        $USER_ID= $this->input->post('user_id');

        $this->_delete_permission($USER_ID,$SEC_NO);


    }

    /**
     * delete action : delete structure permission data ..
     * receive prm key as request
     *
     */
    function _delete_permission($USER_ID,$ST_ID){



        $this->IsAuthorized();

        if(is_array($ST_ID)){
            foreach($ST_ID as $val){
                $this->structure_permission_model->delete($USER_ID,$val);
            }}
    }

}