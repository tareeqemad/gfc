<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 22/01/15
 * Time: 08:48 ص
 */

class Stores_adjustment extends MY_Controller{

    var $MODEL_NAME= 'stores_adjustment_model';
    var $DETAILS_MODEL_NAME= 'stores_adjustment_det_model';
    var $PAGE_URL= 'stores/stores_adjustment/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);

        $this->stores_adjustment_id= $this->input->post('stores_adjustment_id');
        $this->stores_adjustment_type= $this->input->post('stores_adjustment_type');
        $this->store_id= $this->input->post('store_id');
        $this->account_id= $this->input->post('account_id');
        $this->note= $this->input->post('note');
        $this->adjustment_date= $this->input->post('adjustment_date');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        // arrays
        $this->ser= $this->input->post('ser');
        $this->class_id= $this->input->post('class_id');
        $this->amount= $this->input->post('amount');
        $this->class_unit= $this->input->post('class_unit');
        $this->class_type= $this->input->post('class_type');
        $this->price= $this->input->post('price');

        $this->user_branch= ($this->user->branch ==8)? 2:$this->user->branch; // تم دمج مقر الصيانة مع مقر غزة 202210
        //$this->user_branch= $this->user->branch;

        if($this->user_branch!=1)
            die('الصفحة متاحة فقط للمقر الرئيسي');
    }

    function index($page= 1, $stores_adjustment_id= -1, $stores_adjustment_type= -1, $store_id= -1, $account_id= -1, $note= -1, $adopt= -1, $entry_user= -1 ){
        $data['title']='تسويات المخازن';
        $data['content']='stores_adjustment_index';

        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        $data['stores_all'] = $this->stores_model->get_all();
        $data['adjustment_type_all'] = $this->constant_details_model->get_list(53);
        $data['entry_user_all'] = $this->get_entry_users('STORES_ADJUSTMENT_TB');
        $data['adopt_all'] = $this->constant_details_model->get_list(54);

        $data['page']=$page;
        $data['stores_adjustment_id']= $stores_adjustment_id;
        $data['stores_adjustment_type']= $stores_adjustment_type;
        $data['store_id']= $store_id;
        $data['account_id']= $account_id;
        $data['note']= $note;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template',$data);
    }

    function get_page($page = 1, $stores_adjustment_id= -1, $stores_adjustment_type= -1, $store_id= -1, $account_id= -1, $note= -1, $adopt= -1, $entry_user= -1 ){
        $this->load->library('pagination');

        $stores_adjustment_id= $this->check_vars($stores_adjustment_id,'stores_adjustment_id');
        $stores_adjustment_type= $this->check_vars($stores_adjustment_type,'stores_adjustment_type');
        $store_id= $this->check_vars($store_id,'store_id');
        $account_id= $this->check_vars($account_id,'account_id');
        $note= $this->check_vars($note,'note');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= ' where 1=1 ';
        /*
        if(!HaveAccess(base_url("stores/stores_adjustment/all_branches")))
            $where_sql.= " and branch= {$this->user->branch} ";
        */

        $default_where_sql= $where_sql;

        $where_sql.= ($stores_adjustment_id!= null)? " and stores_adjustment_id= '{$stores_adjustment_id}' " : '';
        $where_sql.= ($stores_adjustment_type!= null)? " and stores_adjustment_type= '{$stores_adjustment_type}' " : '';
        $where_sql.= ($store_id!= null)? " and store_id= '{$store_id}' " : '';
        $where_sql.= ($account_id!= null)? " and account_id= '{$account_id}' " : '';
        $where_sql.= ($note!= null)? " and note like '".add_percent_sign($note)."' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        if( !$this->input->is_ajax_request() ){
            $adopt_url= 'stores/stores_adjustment';
            $adopt_where_sql=' ';
            if(HaveAccess(base_url("$adopt_url/create")))
                $adopt_where_sql= " and adopt= 1 and entry_user= {$this->user->id} ";

            if(HaveAccess(base_url("$adopt_url/adopt")))
                $adopt_where_sql= ' and adopt= 1 ';

            $default_where_sql.= $adopt_where_sql;
            $where_sql= $default_where_sql;
        }

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' stores_adjustment_tb '.$where_sql);
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

        $this->load->view('stores_adjustment_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= $this->{$c_var}? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->stores_adjustment_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->stores_adjustment_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->stores_adjustment_id);
            }else{
                for($i=0; $i<count($this->class_id); $i++){
                    if($this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' and $this->class_type[$i]!='' and $this->price[$i]!=''){
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->class_unit[$i], $this->class_type[$i], $this->price[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->stores_adjustment_id);
            }

        }else{
            $data['content']='stores_adjustment_show';
            $data['title']= 'ادخال سند تسوية';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->stores_adjustment_id=='' and $isEdit) or $this->stores_adjustment_type=='' or $this->store_id=='' or $this->account_id=='' or $this->adjustment_date=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }else if(!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->amount)) <= 0 ){
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        }else if (1){
            $all_class= array();
            for($i=0;$i<count($this->amount);$i++){
                $all_class[]= $this->class_id[$i].'-'.$this->class_type[$i];
                if($this->amount[$i]!='' and $this->class_id[$i]=='' )
                    $this->print_error('اختر الصنف');
                elseif($this->amount[$i]!='' and $this->class_unit[$i]=='' )
                    $this->print_error('ادخل وحدة الصنف');
                elseif($this->amount[$i]!='' and $this->class_type[$i]=='' )
                    $this->print_error('ادخل حالة الصنف');
                elseif($this->amount[$i]!='' and $this->price[$i]=='' )
                    $this->print_error('ادخل سعر الصنف');
            }
        }

        if( count(array_filter($all_class)) !=  count(array_count_values(array_filter($all_class))) ){
            $this->print_error('يوجد تكرار في الاصناف');
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        //if(!(count($result)==1 and (HaveAccess(base_url("stores/stores_adjustment/all_branches")) or $result[0]['BRANCH']== $this->user->branch)))
        if(!(count($result)==1))
            die();
        $data['adjustment_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='stores_adjustment_show';
        $data['isCreate']= true;
        $data['title']='بيانات سند التسوية ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                for($i=0; $i<count($this->class_id); $i++){
                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' and $this->class_type[$i]!='' and $this->price[$i]!='' ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->class_unit[$i], $this->class_type[$i], $this->price[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' and $this->class_type[$i]!='' and $this->price[$i]!=''){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->class_id[$i], $this->amount[$i], $this->class_unit[$i], $this->class_type[$i], $this->price[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and ($this->amount[$i]=='' or $this->amount[$i]==0) ){ // delete
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

    function adopt(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->stores_adjustment_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->stores_adjustment_id, 2);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }


    function cancel(){
        //if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->stores_adjustment_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->stores_adjustment_id, 0);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الإلغاء'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        if($this->user_branch==1){
            $branch= null;
        }else{
            $branch= $this->user_branch;
        }
        $data['stores'] = $this->stores_model->get_list($branch); // if branch is null -> get all
        $data['adjustment_type'] = $this->constant_details_model->get_list(53);
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        $data['class_type'] = json_encode($this->constant_details_model->get_list(41));
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function public_get_details($id = 0, $adopt= 0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['adjustment_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $data['adopt'] = $adopt;
        $this->load->view('stores_adjustment_details',$data);
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->stores_adjustment_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'STORES_ADJUSTMENT_ID','value'=>$this->stores_adjustment_id ,'type'=>'','length'=>-1),
            array('name'=>'STORES_ADJUSTMENT_TYPE','value'=>$this->stores_adjustment_type ,'type'=>'','length'=>-1),
            array('name'=>'STORE_ID','value'=>$this->store_id ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$this->account_id ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
            array('name'=>'ADJUSTMENT_DATE','value'=>$this->adjustment_date ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        return($result);
    }

    function _postedData_details($ser= null, $class_id, $amount= null, $class_unit= null ,$class_type= null , $price= null , $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'STORES_ADJUSTMENT_ID','value'=>$this->stores_adjustment_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT','value'=>$amount ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$class_unit ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TYPE','value'=>$class_type ,'type'=>'','length'=>-1),
            array('name'=>'PRICE','value'=>$price ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[1]);
        return $result;
    }

}
