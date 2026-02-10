<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/22/14
 * Time: 11:33 AM
 */

class History extends MY_Controller{


    function __construct(){
        parent::__construct();
        $this->load->model('history_model');
        $this->load->model('settings/staff_model');
        $this->load->model('settings/gcc_branches_model');
    }

    function  index(){
        add_js('jquery.tree.js');


        $data['title']='بيانات تاريخية';
        $data['content']='history_index';

        $data['help']=$this->help;

        $this->_lookUps($data);

        $data['showBranch'] = $this->user->branch == 1;

        $this->load->view('template/template',$data);
    }

   function _lookUps(&$data){
       $data['branches'] = $this->gcc_branches_model->get_all();
   }

    function get_history($item = 0){

        $item= $this->input->post('item_no')?$this->input->post('item_no'):$item;

        $branch =isset($this->p_branch) && $this->user->branch == 1 ?$this->p_branch :$this->user->branch;

        $data['history'] = $this->history_model->get_list($item,$branch);

        $this->load->view('history_page',$data);

    }


    /**
     * get history by year , item no
     */
    function get_id(){

        $item= $this->input->post('item_no')?$this->input->post('item_no'):0;
        $year= $this->input->post('year')?$this->input->post('year'):0;

        $branch =isset($this->p_branch)&& $this->user->branch == 1 ?$this->p_branch :$this->user->branch;

        $result = $this->history_model->get($year,$item,$branch);

        $this->return_json($result);

    }

    /**
     * create action : insert new history data ..
     * receive post data of history
     *
     */
    function create(){

        $item_no= $this->input->post('item_no');

        $result= $this->history_model->create($this->_postedData('create'));

        $this->Is_success($result);

        echo  modules::run('budget/history/get_history',$item_no);

    }


    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData(){

        $yyear = $this->input->post('yyear');
        $re_estimate_l= $this->input->post('re_estimate_l');
        $re_estimate_f= $this->input->post('re_estimate_f');
        $item_no= $this->input->post('item_no');
        $estimated_value= $this->input->post('estimated_value');
        $actual_value= $this->input->post('actual_value');

        $branch =isset($this->p_branch) && $this->user->branch == 1 ?$this->p_branch :$this->user->branch;

        $result = array(
            array('name'=>'ITEM_NO','value'=>$item_no ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$branch,'type'=>'','length'=>100),
            array('name'=>'YYEAR','value'=>$yyear,'type'=>'','length'=>-1),
            array('name'=>'ESTIMATED_VALUE','value'=>$estimated_value,'type'=>'','length'=>-1),
            array('name'=>'RE_ESTIMATE_F','value'=>$re_estimate_f,'type'=>'','length'=>-1),
            array('name'=>'RE_ESTIMATE_L','value'=>$re_estimate_l,'type'=>'','length'=>-1),
            array('name'=>'ACTUAL_VALUE','value'=>$actual_value,'type'=>'','length'=>-1)

        );



        return $result;
    }




    /**
     * edit action : update exists history data ..
     * receive post data of history
     * depended on history prm key
     */
    function edit(){
        $item_no= $this->input->post('item_no');

        $result = $this->history_model->edit($this->_posteddata());

        $this->is_success($result);

        echo  modules::run('budget/history/get_history',$item_no);

    }

    /**
     * delete action : delete history data ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');
        $item_no= $this->input->post('item_no');


        $this->IsAuthorized();

        $msg = 0;

        if(is_array($id)){
            foreach($id as $val){
                $msg=  $this->history_model->delete($val,$item_no,$this->user->branch);
            }
        }else{
            $msg=   $this->history_model->delete($id,$item_no,$this->user->branch);
        }

        if($msg == 1){
            echo  modules::run('budget/history/get_history',$item_no);
        }else{

            $this->print_error_msg($msg);
        }
    }




}