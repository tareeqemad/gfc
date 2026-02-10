<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/16/14
 * Time: 9:56 AM
 */

class Staff extends MY_Controller{

    function  __construct(){
        parent::__construct();

        $this->load->model('staff_model');

    }

    function index(){

        add_css('select2_metro_rtl.css');
        add_js('jquery.tree.js');
        add_js('select2.min.js');

        $data['title']=' تسكين الموظفين';
        $data['content']='staff_index';
        $data['employees']= $this->staff_model->get_list(null,null,null);

        $data['help']=$this->help;

        $this->load->view('template/template',$data);
    }

    function public_get_staff($ST_NO = '01'){

        $ST_NO =($this->input->post('st_no'))?$this->input->post('st_no'):$ST_NO;

        $data['employees']= $this->staff_model->get_list(null,null,$ST_NO);
        $this->load->view('staff_page',$data);

    }


    /**
     * update employee department no
     * receive post data of employee
     *
     */
    function create(){
        $EMP_NO= $this->input->post('emp_no');
        $ST_ID= $this->input->post('st_id');
        $work_desc= $this->input->post('work_desc');
        $flag= $this->input->post('flag');
        $flag= ($flag==1)?$flag:0;

        $result= $this->staff_model->create($this->_postedData($EMP_NO,$ST_ID,$work_desc,$flag));
        $this->Is_success($result);
        echo modules::run('settings/staff/public_get_staff',$ST_ID);

    }


    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData($EMP_NO,$ST_ID, $work_desc, $flag){

        $result = array(
            array('name'=>'NO','value'=>$EMP_NO ,'type'=>'','length'=>-1),
            array('name'=>'GCC_ST_NO','value'=>$ST_ID,'type'=>'','length'=>-1),
            array('name'=>'WORK_DESC','value'=>$work_desc,'type'=>'','length'=>-1),
            array('name'=>'FLAG','value'=>$flag,'type'=>'','length'=>-1),
        );

        return $result;
    }


    /**
     * delete action : update employee st_id  ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');
        $ST_ID= $this->input->post('st_id');
         $this->IsAuthorized();

        if(is_array($id)){
            foreach($id as $val){
                $this->staff_model->create($this->_postedData($val,null, null, 0));
            }
        }
        echo modules::run('settings/staff/public_get_staff',$ST_ID);

    }
}