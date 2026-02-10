<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 9:27 AM
 */

class Gcc_structure extends  MY_Controller{

    var $ST_PARENT_ID;
    var $ST_ID;
    var $ST_NAME;



    function  __construct(){
        parent::__construct();

        $this->load->model('gcc_structure_model');
		$this->load->helper('generate_list');
        $this->load->model('settings/constant_details_model');
    }

    /**
     *
     * index action perform all functions in view of gcc_structure_show view
     * from this view , can show gcc_structure tree , insert new gcc_structure , update exists one and delete other ..
     *
     */
    function index(){
       

        // add_css('jquery-hor-tree.css');
        add_js('jquery.tree.js');


        $data['title']='هيكلية الشركة';
        $data['content']='gcc_structure_index';


        $resource =  $this->_get_structure(0);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li><span data-id="{ST_ID}" ondblclick="javascript:gcc_structure_get(\'{ST_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{ST_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="gcc_structure">'.generate_list($resource, $options, $template).'</ul>';
        $data['st_types'] = $this->constant_details_model->get_list(3);

        $this->load->view('template/template',$data);
    }

    /**
     * get gcc_structure by id ..
     */
    function get_id(){

        $id = $this->input->post('id');
        $result = $this->gcc_structure_model->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * @return mixed
     * return gcc structure tree json type
     */
    function public_get_structure_json(){


        $result = json_encode($this->_get_structure(0));
        $result=str_replace('subs','children',$result);
        $result=str_replace('ST_ID','id',$result);
        $result=str_replace('ST_NAME','text',$result);
        echo $result;


    }


    /**
     * @return mixed
     * return gcc structure tree json type
     */
    function public_get_structure_tree(){



        $resource =  $this->_get_structure(0);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li><span data-id="{ST_ID}" ondblclick="javascript:gcc_structure_get(\'{ST_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{ST_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="gcc_structure">'.generate_list($resource, $options, $template).'</ul>';
        echo $data['tree'];
    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get gcc_structure tree structure ..
     *
     */
    function _get_structure($parent = 0) {
        $result = $this->gcc_structure_model->getList($parent);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['ST_ID']);
            $i++;
        }
        return $result;
    }

    /**
     * create action : insert new gcc_structure data ..
     * receive post data of gcc_structure
     *
     */
    function create(){

        $result= $this->gcc_structure_model->create($this->_postedData());
        $this->Is_success($result);
        $this->return_json($result);

    }

    /**
     * edit action : update exists gcc_structure data ..
     * receive post data of gcc_structure
     * depended on gcc_structure prm key
     */
    function edit(){

        echo $this->gcc_structure_model->edit($this->_postedData());

    }

    /**
     * delete action : delete gcc_structure data ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');

        $this->IsAuthorized();

        if(is_array($id)){
            foreach($id as $val){

                echo   $this->gcc_structure_model->delete($val);
            }
        }else{
            echo $this->gcc_structure_model->delete($id);
        }

    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData(){

        $this->ST_PARENT_ID = $this->input->post('st_parent_id');
        $this->ST_ID= $this->input->post('st_id');
        $this->ST_NAME= $this->input->post('st_name');
        $type =  $this->input->post('type');

        $result = array(
            array('name'=>'ST_PARENT_ID','value'=>$this->ST_PARENT_ID ,'type'=>'','length'=>-1),
            array('name'=>'ST_ID','value'=>$this->ST_ID,'type'=>'','length'=>-1),
            array('name'=>'ST_NAME','value'=>$this->ST_NAME,'type'=>'','length'=>100),
            array('name'=>'TYPE','value'=>$type,'type'=>'','length'=>-1)
        );

        return $result;
    }
}