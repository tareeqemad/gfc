<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 28/12/14
 * Time: 05:15 م
 */

class Stores_class_output extends MY_Controller{

    var $MODEL_NAME= 'stores_class_output_model';
    var $DETAILS_MODEL_NAME= 'stores_class_output_det_model';
    var $PAGE_URL= 'stores/stores_class_output/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);

        $this->class_output_id= $this->input->post('class_output_id');
        $this->source= $this->input->post('source');
        $this->request_no= $this->input->post('request_no');
        $this->from_store_id= $this->input->post('from_store_id');
        $this->class_output_type= $this->input->post('class_output_type');
        $this->class_output_account_id= $this->input->post('class_output_account_id');
        $this->customer_account_type= $this->input->post('customer_account_type');
        $this->note= $this->input->post('note');
        $this->cancel_note= $this->input->post('cancel_note');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        $this->branch_from_user= $this->input->post('branch_from_user');
        $this->room_id= $this->input->post('room_id');
        // arrays
        $this->ser= $this->input->post('ser');
        $this->class_id= $this->input->post('class_id');
        $this->amount= $this->input->post('amount');
        $this->class_type= $this->input->post('class_type');
        $this->personal_custody_type= $this->input->post('personal_custody_type');
        $this->class_code_ser= $this->input->post('class_code_ser');

        $this->user_branch= ($this->user->branch ==8)? 2:$this->user->branch; // تم دمج مقر الصيانة مع مقر غزة 202210
        //$this->user_branch= $this->user->branch;

    }

    function index($page= 1, $class_output_id= -1, $source= -1, $request_no= -1, $from_store_id= -1, $class_output_type= -1, $class_output_account_id= -1, $note= -1, $adopt= -1, $entry_user= -1){
        $data['title']='سندات صرف اصناف';
        $data['content']='stores_class_output_index';

        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');

        $data['stores_all'] = $this->stores_model->get_all();
        $data['request_side_all'] = $this->constant_details_model->get_list(15);
        $data['adopt_all'] = $this->constant_details_model->get_list(42);
        $data['entry_user_all'] = $this->get_entry_users('STORES_CLASS_OUTPUT_TB');
        //add_css('select2_metro_rtl.css');
        //add_js('select2.min.js');

        $data['page']=$page;
        $data['class_output_id'] =$class_output_id;
        $data['source'] =$source;
        $data['request_no'] =$request_no;
        $data['from_store_id'] =$from_store_id;
        $data['class_output_type'] =$class_output_type;
        $data['class_output_account_id'] =$class_output_account_id;
        $data['note'] =$note;
        $data['adopt'] =$adopt;
        $data['entry_user'] =$entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template',$data);
    }

    function get_page($page = 1, $class_output_id= -1, $source= -1, $request_no= -1, $from_store_id= -1, $class_output_type= -1, $class_output_account_id= -1, $note= -1, $adopt= -1, $entry_user= -1){
        $this->load->library('pagination');

        $class_output_id= $this->check_vars($class_output_id,'class_output_id');
        $source= $this->check_vars($source,'source');
        $request_no= $this->check_vars($request_no,'request_no');
        $from_store_id= $this->check_vars($from_store_id,'from_store_id');
        $class_output_type= $this->check_vars($class_output_type,'class_output_type');
        $class_output_account_id= $this->check_vars($class_output_account_id,'class_output_account_id');
        $note= $this->check_vars($note,'note');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= ' where 1=1 ';
        if(!HaveAccess(base_url("stores/stores_class_output/all_branches")))
            $where_sql.= " and DECODE(branch,8,2,branch)= {$this->user_branch} ";  // تم دمج مقر الصيانة مع مقر غزة 202210

        $default_where_sql= $where_sql;

        $where_sql.= ($class_output_id!= null)? " and class_output_id= {$class_output_id} " : '';
        $where_sql.= ($source!= null)? " and source= {$source} " : '';
        $where_sql.= ($request_no!= null)? " and request_no= {$request_no} " : '';
        $where_sql.= ($from_store_id!= null)? " and from_store_id= {$from_store_id} " : '';
        $where_sql.= ($class_output_type!= null)? " and class_output_type= {$class_output_type} " : '';
        $where_sql.= ($class_output_account_id!= null)? " and class_output_account_id= {$class_output_account_id} " : '';
        $where_sql.= ($note!= null)? " and note like '".add_percent_sign($note)."' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= {$adopt} " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= {$entry_user} " : '';

        if( !$this->input->is_ajax_request() and ($request_no== -1 or $request_no==null)){
            $adopt_url= 'stores/stores_class_output';
            $adopt_where_sql=' ';
            if(HaveAccess(base_url("$adopt_url/create")))
                $adopt_where_sql= " and adopt= 1 and entry_user= {$this->user->id} ";

            if(HaveAccess(base_url("$adopt_url/adopt_10")))
                $adopt_where_sql= ' and adopt= 1 and STORES_PKG.GET_NEED_TEC_ADOPT(CLASS_OUTPUT_ID)= 1 ';

            if(HaveAccess(base_url("$adopt_url/adopt")))
                $adopt_where_sql= ' and ( (adopt= 1 and STORES_PKG.GET_NEED_TEC_ADOPT(CLASS_OUTPUT_ID)=0) OR adopt= 10 ) ';

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

        $this->load->view('stores_class_output_page',$data);
    }

    function check_vars($var, $c_var){
        if($c_var=='adopt')
        // if post take it, else take the parameter
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create($source=0 , $request_no=0){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->class_output_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->class_output_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->class_output_id);
            }else{
                for($i=0; $i<count($this->class_id); $i++){
                    if($this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_type[$i]!='' ){
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->class_type[$i], $this->class_code_ser[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->class_output_id);
            }

        }else{
            if($source==1){
                $this->load->model('stores_payment_request_model');
                $result= $this->stores_payment_request_model->get($request_no);
            }else if($source==2){
                $this->load->model('stores_class_transport_model');
                $result= $this->stores_class_transport_model->get($request_no);
            }else
                $result=array();
            if(count($result)!=1)
                die();
            $data['source']= $source;
            $data['request_data']= $result[0];
            $data['content']='stores_class_output_show';
            $data['title']= 'ادخال سند صرف';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->class_output_id=='' and $isEdit) or $this->source=='' or $this->request_no=='' or $this->from_store_id=='' or $this->class_output_type=='' or $this->class_output_account_id=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif($this->class_output_type==2 and $this->customer_account_type==''){
            $this->print_error('يجب اختيار نوع المستفيد');
        }elseif($this->room_id=='' and $this->customer_account_type==10){
            //$this->print_error('يجب تسكين الموظف على غرفة'); // تم الالغاء 202210
        }else if(!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->amount)) <= 0 ){
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        }else if (1){
            $all_class= array();
            for($i=0;$i<count($this->amount);$i++){
                $all_class[]= $this->class_id[$i].'-'.$this->class_type[$i];
                if($this->amount[$i]!='' and $this->class_id[$i]=='' )
                    $this->print_error('اختر الصنف');
                elseif($this->amount[$i]!='' and $this->class_type[$i]=='' )
                    $this->print_error('اختر الحالة');
                elseif($this->customer_account_type==3 and $this->amount[$i]!='' and $this->personal_custody_type[$i]!=1 and $this->personal_custody_type[$i]!=2 )
                    $this->print_error('يجب ان يكون الصنف ' .$this->class_id[$i].' عهد شخصية او ادارة');
            }
        }

        if( count(array_filter($all_class)) !=  count(array_count_values(array_filter($all_class))) ){
            $this->print_error('يوجد تكرار في الاصناف');
        }

        if($this->class_output_type!=2){
            $this->customer_account_type= null;
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if($result[0]['BRANCH']==8) $result[0]['BRANCH']=2; // تم دمج مقر الصيانة مع مقر غزة 202210
        if(!(count($result)==1 and (HaveAccess(base_url("stores/stores_class_output/all_branches")) or $result[0]['BRANCH']== $this->user_branch)))
            die();
        $data['output_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='stores_class_output_show';
        $data['isCreate']= true;
        $data['title']='بيانات سند الصرف';
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
                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_type[$i]!='' ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->class_type[$i], $this->class_code_ser[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and $this->class_type[$i]!='' ){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->class_id[$i], $this->amount[$i], $this->class_type[$i], $this->class_code_ser[$i], 'edit'));
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_output_id!='' and $this->class_output_type!='' and $this->class_output_account_id!=''){
            if($this->class_output_type==2 and $this->customer_account_type==''){
                $this->print_error('يجب اختيار نوع المستفيد');
            }
            if($this->class_output_type!=2){
                $this->customer_account_type= null;
            }
            if($this->customer_account_type==3 or $this->customer_account_type==10 ){
                $this->print_error('لا يمكن اختيار موظف');
            }
            $res = $this->{$this->MODEL_NAME}->edit_account($this->class_output_id, $this->class_output_type, $this->class_output_account_id, $this->customer_account_type);
            if(intval($res) <= 0){
                $this->print_error('لم يتم التعديل'.'<br>'.$res);
            }
            echo 1;
        }else
            $this->print_error("لم يتم ارسال رقم السند والحساب");
    }

    function adopt(){ // adopt_20
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_output_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->class_output_id, 20);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_10(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_output_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->class_output_id, 10);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function cancel(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_output_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->class_output_id, 0, $this->cancel_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الالغاء'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function set_barcodes(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            $res = $this->{$this->MODEL_NAME}->set_barcodes($this->request_no);
            if(intval($res) <= 0){
                $this->print_error('خطأ'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function public_get_details($output_id = 0, $source=0, $request_no=0, $adopt= 0){
        //$id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['output_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($output_id);
        $data['adopt'] = $adopt;
        if($source==1){
            $this->load->model('stores_payment_request_det_model');
            $data['request_details'] = $this->stores_payment_request_det_model->get_list($request_no);
        }elseif($source==2){
            die();
            $this->load->model('stores_class_transport_det_model');
            $data['transport_details'] = $this->stores_class_transport_det_model->get_list($request_no);
        }
        $this->load->view('stores_class_output_details',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        $data['stores'] = $this->stores_model->get_all();
        //$data['type'] = $this->constant_details_model->get_list(35);
        $data['output_type'] = $this->constant_details_model->get_list(15);
        $data['customer_account_type'] = $this->constant_details_model->get_list(154);
        $this->load->model('settings/gcc_branches_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->class_output_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'CLASS_OUTPUT_ID','value'=>$this->class_output_id ,'type'=>'','length'=>-1),
            array('name'=>'SOURCE','value'=>$this->source ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_NO','value'=>$this->request_no ,'type'=>'','length'=>-1),
            array('name'=>'FROM_STORE_ID','value'=>$this->from_store_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_OUTPUT_TYPE','value'=>$this->class_output_type ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_OUTPUT_ACCOUNT_ID','value'=>$this->class_output_account_id ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ACCOUNT_TYPE','value'=>$this->customer_account_type ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_FROM_USER','value'=>$this->branch_from_user ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_ID','value'=>$this->room_id ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        return($result);
    }

    function _postedData_details($ser= null, $class_id, $amount= null, $class_type= null, $class_code_ser= null, $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_OUTPUT_ID','value'=>$this->class_output_id ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_NO','value'=>$this->request_no ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT','value'=>$amount ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TYPE','value'=>$class_type ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_CODE_SER','value'=>$class_code_ser ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[2]);
        return $result;
    }

}
