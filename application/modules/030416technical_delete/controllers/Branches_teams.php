<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 30/08/15
 * Time: 10:52 ص
 */

class branches_teams extends MY_Controller{

    var $MODEL_NAME= 'branches_teams_model';
    var $DETAILS_MODEL= 'worker_structure_teams_model';
    var $PAGE_URL= 'technical/branches_teams/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL);

        // vars
        $this->team_ser= $this->input->post('team_ser');
        $this->branch_id= $this->input->post('branch_id');
        $this->team_id= $this->input->post('team_id');
        $this->team_name= $this->input->post('team_name');
        $this->entry_user= $this->input->post('entry_user');

        // arrays
        $this->ser= $this->input->post('ser');
        $this->customer_id= $this->input->post('customer_id');
        $this->worker_job= $this->input->post('worker_job');
    }

    function index($page= 1, $team_ser= -1, $branch_id= -1, $team_id= -1, $team_name= -1, $entry_user= -1){
        $data['title']='فرق العمل في المقرات';
        $data['content']= 'branches_teams_index';

        $this->load->model('settings/gcc_branches_model');
        $data['branch_all']= $this->gcc_branches_model->get_all();
        $data['entry_user_all'] = $this->get_entry_users('BRANCHES_TEAMS_TB');

        $data['page']= $page;
        $data['team_ser']= $team_ser;
        $data['branch_id']= $branch_id;
        $data['team_id']= $team_id;
        $data['team_name']= $team_name;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $team_ser= -1, $branch_id= -1, $team_id= -1, $team_name= -1, $entry_user= -1){
        $this->load->library('pagination');

        $team_ser= $this->check_vars($team_ser,'team_ser');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $team_id= $this->check_vars($team_id,'team_id');
        $team_name= $this->check_vars($team_name,'team_name');

        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 and (branch_id= '{$this->user->branch}' or {$this->user->branch} = 1)";
        $where_sql.= ($team_ser!= null)? " and team_ser= '{$team_ser}' " : '';
        //$where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';
        $where_sql.= ($team_id!= null)? " and team_id= '{$team_id}' " : '';
        $where_sql.= ($team_name!= null)? " and team_name like '".add_percent_sign($team_name)."' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' branches_teams_tb '.$where_sql);
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

        $this->load->view('branches_teams_page',$data);
    }

    function public_index($text= null, $page= 1, $team_ser= -1, $team_name= -1){
        $data['content']='branches_teams_popup_i';
        $data['text']=$text;
        $data['page']=$page;
        $data['team_ser']= $team_ser;
        $data['team_name']= $team_name;
        $this->load->view('template/view',$data);
    }

    function public_get_page($text= null, $page= 1, $team_ser= -1, $team_name= -1){

        $this->load->library('pagination');

        $team_ser= $this->check_vars($team_ser,'team_ser');
        $team_name= $this->check_vars($team_name,'team_name');

        $where_sql= " where 1=1 and branch_id= '{$this->user->branch}' ";
        $where_sql.= ($team_ser!= null)? " and team_ser= '{$team_ser}' " : '';
        $where_sql.= ($team_name!= null)? " and team_name like '".add_percent_sign(urldecode($team_name))."' " : '';

        $config['base_url'] = base_url("technical/branches_teams/public_index/$text");
        $count_rs = $this->get_table_count(' branches_teams_tb '.$where_sql);
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

        $this->load->view('branches_teams_popup_p',$data);
    }

    function public_team_index($text= null, $team_ser= -1){
        $data['team_all']= $this->{$this->MODEL_NAME}->get_list(" where (branch_id= '{$this->user->branch}' or '{$this->user->branch}' = 1) ", 0 , 99999999999 );
        $data['content']='branches_teams_popup_t_i';
        $data['text']=$text;
        $data['team_ser']= $team_ser;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->view('template/view',$data);
    }

    function public_team_get_page($text= null, $team_ser= -1){
        $team_ser= $this->check_vars($team_ser,'team_ser');
        $data['page_rows'] = $this->{$this->DETAILS_MODEL}->get_list($team_ser);
        $this->load->view('branches_teams_popup_t_p',$data);
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
            $this->team_ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->team_ser) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->team_ser);
            }else{
                for($i=0; $i<count($this->customer_id); $i++){
                    if($this->customer_id[$i]!='' and $this->worker_job[$i]!=''){
                        $detail_seq= $this->{$this->DETAILS_MODEL}->create($this->_postedData_details(null, $this->customer_id[$i], $this->worker_job[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->team_ser);
            }

        }else{
            $data['content']='branches_teams_show';
            $data['title']= 'ادخال فرق العمل';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->team_ser=='' and $isEdit) or $this->team_name==''){
            $this->print_error('يجب ادخال جميع البيانات');
        }

        if(!($this->customer_id) or count(array_filter($this->customer_id)) <= 0 or count(array_filter($this->worker_job)) <= 0 ){
            $this->print_error('يجب ادخال الموظفين ');
        }

        for($i=0;$i<count($this->customer_id);$i++){
            if($this->customer_id[$i]!='' and $this->worker_job[$i]=='' )
                $this->print_error('اختر الوظيفة');
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1 ))
            die('get');
        $data['team_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='branches_teams_show';
        $data['title']='بيانات الفريق ';

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

                for($i=0; $i<count($this->customer_id); $i++){
                    if($this->ser[$i]== 0 and $this->customer_id[$i]!='' and $this->worker_job[$i]!=''){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL}->create($this->_postedData_details(null, $this->customer_id[$i], $this->worker_job[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and $this->customer_id[$i]!='' and $this->worker_job[$i]!=''){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL}->edit($this->_postedData_details($this->ser[$i], $this->customer_id[$i], $this->worker_job[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and $this->customer_id[$i]==''){ // delete
                        $detail_seq= $this->{$this->DETAILS_MODEL}->delete($this->ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }
    }

    function public_get_details($id= 0, $adopt= 0){
        $data['adopt'] = $adopt;
        $data['details'] = $this->{$this->DETAILS_MODEL}->get_list($id);
        $this->load->view('branches_teams_details',$data);
    }

    function public_get_details_json(){
        $id= $this->input->post('id');
        $res = $this->{$this->DETAILS_MODEL}->get_list($id);
        $this->return_json($res);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $data['worker_jobs'] = json_encode($this->constant_details_model->get_list(86));
        $this->load->model('settings/gcc_branches_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['help']=$this->help;
        add_js('jquery.hotkeys.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->team_ser);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'TEAM_SER','value'=>$this->team_ser ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_ID','value'=>$this->user->branch ,'type'=>'','length'=>-1),
            array('name'=>'TEAM_NAME','value'=>$this->team_name ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        else
            unset($result[1]);
        return $result;
    }

    function _postedData_details($ser= null, $customer_id, $worker_job, $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'TEAM_SER','value'=>$this->team_ser ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ID','value'=>$customer_id ,'type'=>'','length'=>-1),
            array('name'=>'WORKER_JOB','value'=>$worker_job ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}
