<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/12/15
 * Time: 12:14 م
 */

class feeder_groups extends MY_Controller{

    var $MODEL_NAME= 'feeder_groups_model';
    var $DETAILS_MODEL_NAME= 'layers_group_adapters_model';
    var $PAGE_URL= 'technical/feeder_groups/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        // vars
        $this->group_id= $this->input->post('group_id');
        $this->group_ser= $this->input->post('group_ser');
        $this->group_name= $this->input->post('group_name');
        $this->branch= $this->input->post('branch');
        $this->line_feeder= $this->input->post('line_feeder');
        // arrays
        $this->ser= $this->input->post('ser');
        $this->adapter_serial= $this->input->post('adapter_serial');
    }

    function index($page= 1, $group_id= -1, $group_ser= -1, $group_name= -1, $branch= -1, $line_feeder= -1 ){
        $data['title']= 'المجموعات على الخطوط المغذية';
        $data['content']='feeder_groups_index';
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branch_all']= $this->gcc_branches_model->get_all();
        $data['line_feeder_all'] = $this->constant_details_model->get_list(90);

        $data['page']=$page;
        $data['group_id']= $group_id;
        $data['group_ser']= $group_ser;
        $data['group_name']= $group_name;
        $data['branch']= $branch;
        $data['line_feeder']= $line_feeder;

        $data['help'] = $this->help;
        $data['action'] = 'edit';

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $group_id= -1, $group_ser= -1, $group_name= -1, $branch= -1, $line_feeder= -1 ){

        $this->load->library('pagination');

        $group_id= $this->check_vars($group_id,'group_id');
        $group_ser= $this->check_vars($group_ser,'group_ser');
        $group_name= $this->check_vars($group_name,'group_name');
        $branch= $this->check_vars($branch,'branch');
        $line_feeder= $this->check_vars($line_feeder,'line_feeder');

        $where_sql= " where 1=1 ";

        $where_sql.= ($group_id!= null)? " and group_id= '{$group_id}' " : '';
        $where_sql.= ($group_ser!= null)? " and group_ser= '{$group_ser}' " : '';
        $where_sql.= ($group_name!= null)? " and group_name like '".add_percent_sign($group_name)."' " : '';
        $where_sql.= ($branch!= null)? " and branch= '{$branch}' " : '';
        $where_sql.= ($line_feeder!= null)? " and line_feeder= '{$line_feeder}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' feeder_groups_tb '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('feeder_groups_page',$data);
    }

    function public_index($txt, $page= 1){
        $data['title']= 'المجموعات على الخطوط المغذية';
        $data['content']='feeder_groups_popup_i';
        $data['page']=$page;
        $this->load->model('settings/gcc_branches_model');
        $data['txt'] = $txt;
        $data['branch_all'] = $this->gcc_branches_model->get_all();
        $this->load->view('template/view',$data);
    }

    function public_get_page($page= 1){
        $this->load->library('pagination');

        $where_sql= " where 1=1 ";
        //$where_sql .=($this->user->branch != 1? " AND  BRANCH = {$this->user->branch} ":"");
        $where_sql .= (isset($this->p_group_id) && $this->p_group_id !=null?" and group_id ={$this->p_group_id} ":"");
        $where_sql .= (isset($this->p_group_ser) && $this->p_group_ser !=null?" and group_ser ={$this->p_group_ser} ":"");
        $where_sql .= (isset($this->p_group_name) && $this->p_group_name !=null?" and group_name like '".add_percent_sign($this->p_group_name)."' ":"");
        $where_sql .= (isset($this->p_branch) && $this->p_branch !=null?" and BRANCH ={$this->p_branch} ":"");

        $count_rs = $this->get_table_count(' feeder_groups_tb '.$where_sql);

        $config['base_url'] = base_url("technical/feeder_groups/public_get_page");
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('feeder_groups_popup_p',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->group_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->group_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->group_id);
            }else{
                for($i=0; $i<count($this->adapter_serial); $i++){
                    if($this->adapter_serial[$i]!='' and $this->adapter_serial[$i]>0 ){
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->adapter_serial[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->group_id);
            }
        }else{
            $data['content']='feeder_groups_show';
            $data['title']= 'ادخال مجموعة جديدة';
            $data['branch']= $this->user->branch;
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }

    }

    function _post_validation($isEdit = false){
        if( ($this->group_id=='' and $isEdit) or ($this->branch=='' and !$isEdit) or $this->group_name=='' or $this->line_feeder=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }else if( !($this->adapter_serial) or count(array_filter($this->adapter_serial)) <= 0 ){
            $this->print_error('يجب ادخال محول واحد على الاقل ');
        }else if( count(array_filter($this->adapter_serial)) !=  count(array_count_values(array_filter($this->adapter_serial)))  ){
            $this->print_error('يوجد تكرار في المحولات');
        }
    }

    function get($id, $action= 'index'){

        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!count($result)==1)
            die('get');
        $data['group_data']=$result;
        $data['branch']= $this->user->branch;
        $data['can_edit'] =count($result) > 0?  ($action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='feeder_groups_show';
        $data['title']='بيانات المجموعة';

        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                for($i=0; $i<count($this->adapter_serial); $i++){
                    if($this->ser[$i]== 0 and $this->adapter_serial[$i]!='' and $this->adapter_serial[$i]>0 ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->adapter_serial[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and $this->adapter_serial[$i]!='' and $this->adapter_serial[$i]>0 ){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->adapter_serial[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and $this->adapter_serial[$i]=='' ){ // delete
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }
    }

    function public_get_details($id= 0){
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $this->load->view('feeder_groups_details',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['line_feeders'] = $this->constant_details_model->get_list(90);
        $data['help']=$this->help;
        add_js('jquery.hotkeys.js');
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->group_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'GROUP_ID','value'=>$this->group_id ,'type'=>'','length'=>-1),
            array('name'=>'GROUP_NAME','value'=>$this->group_name ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$this->branch ,'type'=>'','length'=>-1),
            array('name'=>'LINE_FEEDER','value'=>$this->line_feeder ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

    function _postedData_details($ser= null, $adapter_serial, $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'GROUP_ID','value'=>$this->group_id ,'type'=>'','length'=>-1),
            array('name'=>'ADAPTER_SERIAL','value'=>$adapter_serial ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}