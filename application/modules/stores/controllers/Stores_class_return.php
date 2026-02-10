<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 04/01/15
 * Time: 07:08 م
 */

class Stores_class_return extends MY_Controller{

    var $MODEL_NAME= 'stores_class_return_model';
    var $DETAILS_MODEL_NAME= 'stores_class_return_det_model';
    var $PAGE_URL= 'stores/stores_class_return/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);

        $this->class_return_id= $this->input->post('class_return_id');
        $this->to_store_id= $this->input->post('to_store_id');
        $this->class_return_type= $this->input->post('class_return_type');
        $this->account_type= $this->input->post('account_type');
        $this->class_return_account_id= $this->input->post('class_return_account_id');
        $this->customer_account_type= $this->input->post('customer_account_type');
        $this->notes= $this->input->post('notes');
        $this->cancel_note= $this->input->post('cancel_note');
        $this->store_serv_req= $this->input->post('store_serv_req');
        $this->room_id= $this->input->post('room_id');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        // arrays
        $this->ser= $this->input->post('ser');
        $this->class_id= $this->input->post('class_id');
        $this->amount= $this->input->post('amount');
        $this->class_unit= $this->input->post('class_unit');
        $this->class_type= $this->input->post('class_type');
        $this->class_code_ser= $this->input->post('class_code_ser');

        $this->user_branch= ($this->user->branch ==8)? 2:$this->user->branch; // تم دمج مقر الصيانة مع مقر غزة 202210
        //$this->user_branch= $this->user->branch;

        if( HaveAccess(base_url("stores/stores_class_return/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

        if( HaveAccess(base_url("stores/stores_class_return/select_all_stores")) )
            $this->select_all_stores= 1;
        else
            $this->select_all_stores= 0;


        // صلاحية الارجاع لمخازن الحاسوب
        if( HaveAccess(base_url("stores/stores_class_return/it_stores")) )
            $this->it_stores= 1;
        else
            $this->it_stores= 0;

    }

    function index($page= 1, $class_return_id= -1, $to_store_id= -1, $class_return_type= -1, $class_return_account_id= -1, $notes= -1, $adopt= -1, $entry_user= -1){
        $data['title']='ارجاع اصناف الى المخازن';
        $data['content']='stores_class_return_index';

        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');

        $data['stores_all'] = $this->stores_model->get_all();
        $data['request_type_all'] = $this->constant_details_model->get_list(35);
        $data['adopt_all'] = $this->constant_details_model->get_list(44);
        $data['entry_user_all'] = $this->get_entry_users('STORES_CLASS_RETURN_TB');
        //add_css('select2_metro_rtl.css');
        //add_js('select2.min.js');

        $data['page']=$page;
        $data['class_return_id']= $class_return_id;
        $data['to_store_id']= $to_store_id;
        $data['class_return_type']= $class_return_type;
        $data['class_return_account_id']= $class_return_account_id;
        $data['notes']= $notes;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template',$data);
    }

    function get_page($page = 1, $class_return_id= -1, $to_store_id= -1, $class_return_type= -1, $class_return_account_id= -1, $notes= -1, $adopt= -1, $entry_user= -1){
        $this->load->library('pagination');

        $class_return_id= $this->check_vars($class_return_id,'class_return_id');
        $to_store_id= $this->check_vars($to_store_id,'to_store_id');
        $class_return_type= $this->check_vars($class_return_type,'class_return_type');
        $class_return_account_id= $this->check_vars($class_return_account_id,'class_return_account_id');
        $notes= $this->check_vars($notes,'notes');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= ' where 1=1 ';
        if(!$this->all_branches and !$this->select_all_stores)   // تم دمج مقر الصيانة مع مقر غزة 202210
            $where_sql.= " and  DECODE(branch,8,2,branch) = {$this->user_branch} ";
        elseif(!$this->all_branches and $this->select_all_stores)   // تم دمج مقر الصيانة مع مقر غزة 202210
            $where_sql.= " and ( DECODE(branch,8,2,branch)= {$this->user_branch} or entry_user= {$this->user->id} ) ";

        $default_where_sql= $where_sql;

        $where_sql.= ($class_return_id!= null)? " and class_return_id= '{$class_return_id}' " : '';
        $where_sql.= ($to_store_id!= null)? " and to_store_id= '{$to_store_id}' " : '';
        $where_sql.= ($class_return_type!= null)? " and class_return_type= '{$class_return_type}' " : '';
        $where_sql.= ($class_return_account_id!= null)? " and class_return_account_id= '{$class_return_account_id}' " : '';
        $where_sql.= ($notes!= null)? " and notes like '".add_percent_sign($notes)."' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        if( !$this->input->is_ajax_request() ){
            $adopt_url= 'stores/stores_class_return';
            $adopt_where_sql=' ';
            if(HaveAccess(base_url("$adopt_url/create")))
                $adopt_where_sql= " and adopt= 1 and entry_user= {$this->user->id} ";

            if(HaveAccess(base_url("$adopt_url/adopt")))
                $adopt_where_sql= ' and adopt= 1 ';

            $default_where_sql.= $adopt_where_sql;
            $where_sql= $default_where_sql;
        }

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->{$this->MODEL_NAME}->get_count($where_sql);
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

        $this->load->view('stores_class_return_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->class_return_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->class_return_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->class_return_id);
            }else{
                for($i=0; $i<count($this->class_id); $i++){
                    if($this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' and $this->class_type[$i]!=''){
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->class_unit[$i], $this->class_type[$i], $this->class_code_ser[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->class_return_id);
            }

        }else{
            $data['content']='stores_class_return_show';
            $data['title']= 'ادخال ارجاع اصناف';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['show_code_sers']= 0;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->class_return_id=='' and $isEdit) or $this->to_store_id=='' or $this->class_return_type=='' or $this->account_type=='' or $this->class_return_account_id=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif($this->account_type==2 and $this->customer_account_type==''){
            $this->print_error('يجب اختيار نوع المستفيد');
        }elseif(!$this->it_stores and in_array($this->to_store_id, array(9,14,33,37,41,45)) ){
            $this->print_error('ليس لديك صلاحية ادخال سندات لمخازن الحاسوب');
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
            }
        }

        if( count(array_filter($all_class)) !=  count(array_count_values(array_filter($all_class))) ){
            $this->print_error('يوجد تكرار في الاصناف');
        }

        if($this->account_type!=2){
            $this->customer_account_type= null;
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if($result[0]['BRANCH']==8) $result[0]['BRANCH']=2; // تم دمج مقر الصيانة مع مقر غزة 202210
        if(!(count($result)==1 and ( $this->all_branches or $result[0]['BRANCH']== $this->user_branch or ($this->select_all_stores and $this->user->id == $result[0]['ENTRY_USER'] ) ) ))
            die();
        $data['return_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='stores_class_return_show';
        $data['isCreate']= true;
        $data['show_code_sers']= ($result[0]['CLASS_RETURN_TYPE']==24 and ($result[0]['ACCOUNT_TYPE']==2 OR $result[0]['ACCOUNT_TYPE']==4 ))?1:0;
        $data['title']='بيانات الارجاع';
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
                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' and $this->class_type[$i]!='' ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->class_unit[$i], $this->class_type[$i], $this->class_code_ser[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_unit[$i]!='' and $this->class_type[$i]!=''){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->class_id[$i], $this->amount[$i], $this->class_unit[$i], $this->class_type[$i], $this->class_code_ser[$i], 'edit'));
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

    // تعديل الحساب بعد الاعتماد وتحديث القيد
    function edit_account(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_return_id!='' and $this->account_type!='' and $this->class_return_account_id!=''){
            if($this->account_type==2 and $this->customer_account_type==''){
                $this->print_error('يجب اختيار نوع المستفيد');
            }
            if($this->account_type!=2){
                $this->customer_account_type= null;
            }
            if($this->customer_account_type==3 or $this->customer_account_type==10 ){
                $this->print_error('لا يمكن اختيار موظف');
            }
            $res = $this->{$this->MODEL_NAME}->edit_account($this->class_return_id, $this->account_type, $this->class_return_account_id, $this->customer_account_type);
            if(intval($res) <= 0){
                $this->print_error('لم يتم التعديل'.'<br>'.$res);
            }
            echo 1;
        }else
            $this->print_error("لم يتم ارسال رقم السند والحساب");
    }

    function adopt(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_return_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->class_return_id,2);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم سند الارجاع";
    }

    function cancel(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_return_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->class_return_id, 0, $this->cancel_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الالغاء'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم سند الارجاع";
    }

    function public_get_details($id = 0, $adopt= 0, $show_code_sers=0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['return_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $data['adopt'] = $adopt;
        $data['show_code_sers'] = $show_code_sers;
        $this->load->view('stores_class_return_details',$data);
    }

    function serv_get($id=0){
        $this->load->model('stores_payment_request_model');
        $result= $this->stores_payment_request_model->serv_get($id);
        if(!(count($result)==1))
            die(' رقم الطلبية خاطئ');
        $data['serv_data']=$result[0];
        $data['content']='serv_store_request_show';
        $this->load->view('template/view',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        if($this->user_branch==1 or $this->select_all_stores){
            $branch= null;
        }else{
            $branch= $this->user_branch;
        }
        $data['stores'] = $this->stores_model->get_list($branch); // if branch is null -> get all
        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['return_type'] = $this->constant_details_model->get_list(35);
        $data['customer_account_type'] = $this->constant_details_model->get_list(154);
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        $data['class_type'] = json_encode($this->constant_details_model->get_list(41));
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->class_return_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'CLASS_RETURN_ID','value'=>$this->class_return_id ,'type'=>'','length'=>-1),
            array('name'=>'TO_STORE_ID','value'=>$this->to_store_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_RETURN_TYPE','value'=>$this->class_return_type ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_TYPE','value'=>$this->account_type ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_RETURN_ACCOUNT_ID','value'=>$this->class_return_account_id ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ACCOUNT_TYPE','value'=>$this->customer_account_type ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
            array('name'=>'STORE_SERV_REQ','value'=>$this->store_serv_req ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_ID','value'=>$this->room_id ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        return($result);
    }

    function _postedData_details($ser= null, $class_id, $amount= null, $class_unit= null, $class_type= null, $class_code_ser= null, $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_RETURN_ID','value'=>$this->class_return_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT','value'=>$amount ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$class_unit ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TYPE','value'=>$class_type ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_CODE_SER','value'=>$class_code_ser ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[1]);
        return $result;
    }

}
