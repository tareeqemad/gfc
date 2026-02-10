<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/12/14
 * Time: 10:54 ص
 */

class Stores_class_transport extends MY_Controller{

    var $MODEL_NAME= 'stores_class_transport_model';
    var $DETAILS_MODEL_NAME= 'stores_class_transport_det_model';
    var $PAGE_URL= 'stores/Stores_class_transport/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);

        $this->transport_type= encryption_case($this->input->post('transport_type'));
        $this->class_transport_id= $this->input->post('class_transport_id');
        $this->request_no= $this->input->post('request_no');
        $this->from_store_id= $this->input->post('from_store_id');
        $this->to_store_id= $this->input->post('to_store_id');
        $this->note= $this->input->post('note');
        $this->cancel_note= $this->input->post('cancel_note');
        $this->request_side= $this->input->post('request_side');
        $this->class_transport_account_id= $this->input->post('class_transport_account_id');
        $this->customer_account_type= $this->input->post('customer_account_type');
        $this->class_transport_type= $this->input->post('class_transport_type');
        $this->broker_emp_no= $this->input->post('broker_emp_no');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        $this->archive= $this->input->post('archive');
        // arrays
        $this->ser= $this->input->post('ser');
        $this->class_id= $this->input->post('class_id');
        $this->amount= $this->input->post('amount');
        $this->class_unit= $this->input->post('class_unit');
        $this->class_type= $this->input->post('class_type');
        $this->class_code_ser= $this->input->post('class_code_ser');

        $this->user_branch= ($this->user->branch ==8)? 2:$this->user->branch; // تم دمج مقر الصيانة مع مقر غزة 202210
        //$this->user_branch= $this->user->branch;

        if($this->transport_type!=1 and $this->transport_type!=2)
            die();

        if( HaveAccess(base_url("stores/stores_class_transport/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

        if( HaveAccess(base_url("stores/stores_class_transport/select_all_stores")) )
            $this->select_all_stores= 1;
        else
            $this->select_all_stores= 0;
    }

    function index($page= 1, $archive=-1, $class_transport_id= -1, $request_no= -1, $from_store_id= -1, $to_store_id= -1, $note= -1, $request_side= -1, $class_transport_account_id= -1, $class_transport_type= -1,  $adopt= -1, $entry_user= -1){
        if($archive==1)
            $data['title']='ارشيف سندات مناقلة أصناف بين المخازن';
        else
            $data['title']='سندات مناقلة أصناف بين المخازن';
        $data['content']='stores_class_transport_index';

        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        $data['stores_all'] = $this->stores_model->get_all();
        $data['request_type_all'] = $this->constant_details_model->get_list(35);
        $data['request_side_all'] = $this->constant_details_model->get_list(15);
        $data['entry_user_all'] = $this->get_entry_users('STORES_CLASS_TRANSPORT_TB');
        $data['adopt_all'] = $this->constant_details_model->get_list(40);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['archive']=$archive;
        $data['page']=$page;
        $data['class_transport_id'] =$class_transport_id;
        $data['request_no'] =$request_no;
        $data['from_store_id'] =$from_store_id;
        $data['to_store_id'] =$to_store_id;
        $data['note'] =$note;
        $data['request_side'] =$request_side;
        $data['class_transport_account_id'] =$class_transport_account_id;
        $data['class_transport_type'] =$class_transport_type;
        $data['adopt'] =$adopt;
        $data['entry_user'] =$entry_user;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $archive=-1, $class_transport_id= -1, $request_no= -1, $from_store_id= -1, $to_store_id= -1, $note= -1, $request_side= -1, $class_transport_account_id= -1, $class_transport_type= -1,  $adopt= -1, $entry_user= -1){
        $this->load->library('pagination');

        $archive= $this->check_vars($archive,'archive');
        $class_transport_id= $this->check_vars($class_transport_id,'class_transport_id');
        $request_no= $this->check_vars($request_no,'request_no');
        $from_store_id= $this->check_vars($from_store_id,'from_store_id');
        $to_store_id= $this->check_vars($to_store_id,'to_store_id');
        $note= $this->check_vars($note,'note');
        $request_side= $this->check_vars($request_side,'request_side');
        $class_transport_account_id= $this->check_vars($class_transport_account_id,'class_transport_account_id');
        $class_transport_type= $this->check_vars($class_transport_type,'class_transport_type');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= ' where 1=1 ';
        if ($archive== null or $archive!=1){
            if( !$this->all_branches and !$this->select_all_stores )  // تم دمج مقر الصيانة مع مقر غزة 202210
                $where_sql.= " and ( DECODE(branch,8,2,branch)= {$this->user_branch} or (stores_pkg.get_branch_for_store( to_store_id ) = {$this->user_branch} and adopt >=2 ) ) ";
            elseif( !$this->all_branches and $this->select_all_stores )  // تم دمج مقر الصيانة مع مقر غزة 202210
                $where_sql.= " and ( DECODE(branch,8,2,branch)= {$this->user_branch} or (stores_pkg.get_branch_for_store( to_store_id ) = {$this->user_branch} and adopt >=2 ) or entry_user= {$this->user->id} ) ";
        }
        $default_where_sql= $where_sql;

        $where_sql.= ($class_transport_id!= null)? " and class_transport_id= {$class_transport_id} " : '';
        $where_sql.= ($request_no!= null)? " and request_no= {$request_no} " : '';
        $where_sql.= ($from_store_id!= null)? " and from_store_id= {$from_store_id} " : '';
        $where_sql.= ($to_store_id!= null)? " and to_store_id= {$to_store_id} " : '';
        $where_sql.= ($note!= null)? " and note like '".add_percent_sign($note)."' " : '';
        $where_sql.= ($request_side!= null)? " and request_side= {$request_side} " : '';
        $where_sql.= ($class_transport_account_id!= null)? " and class_transport_account_id= {$class_transport_account_id} " : '';
        $where_sql.= ($class_transport_type!= null)? " and class_transport_type= {$class_transport_type} " : '';
        $where_sql.= ($adopt!= null)? " and adopt= {$adopt} " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= {$entry_user} " : '';

        if( !$this->input->is_ajax_request() and $archive!=1 and ($request_no== -1 or $request_no==null)){
            $adopt_url= 'stores/stores_class_transport';
            $adopt_where_sql=' ';
            if(HaveAccess(base_url("$adopt_url/create")))
                $adopt_where_sql= " and adopt= 1 and entry_user= {$this->user->id} ";

            if(HaveAccess(base_url("$adopt_url/adopt")))
                $adopt_where_sql= ' and adopt= 1 ';

            if(HaveAccess(base_url("$adopt_url/receipt")))
                $adopt_where_sql= ' and adopt= 2 ';

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

        $this->load->view('stores_class_transport_page',$data);
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

    function create($request_no=0){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->class_transport_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->class_transport_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->class_transport_id);
            }else{
                for($i=0; $i<count($this->class_id); $i++){
                    if($this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 and ($this->transport_type==1 or ($this->transport_type==2 and @$this->class_unit[$i]!='' and $this->class_type[$i]!='')) ){
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], @$this->class_unit[$i], $this->class_type[$i], @$this->class_code_ser[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->class_transport_id);
            }

        }else{
            $data['request_data']= array();
            $this->transport_type= 2;

            if($request_no >0){
                $this->load->model('stores_payment_request_model');
                $result= $this->stores_payment_request_model->get($request_no);
                if(count($result)!=1)
                    die();
                $data['request_data']= $result[0];
                $this->transport_type= 1;
            }
            $data['transport_type']= $this->transport_type;
            $data['content']='stores_class_transport_show';
            $data['title']= 'مناقلة اصناف بين المخازن';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->class_transport_id=='' and $isEdit) or ($this->request_no=='' and $this->transport_type==1) or $this->from_store_id=='' or $this->to_store_id=='' or $this->broker_emp_no=='' or ($this->transport_type==2 and ($this->request_side=='' or $this->class_transport_account_id=='' or $this->class_transport_type=='') ) ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif($this->request_side==2 and $this->customer_account_type==''){
            $this->print_error('يجب اختيار نوع المستفيد');
        }else if($this->to_store_id==$this->from_store_id){
            $this->print_error('لا يمكن عمل مناقلة لنفس المخزن');
        }else if( !preg_match ('/^[0-9]{1,6}$/',$this->broker_emp_no) ){
            $this->print_error('ادخل رقم موظف صحيح');
        }else if(!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->amount)) <= 0 ){
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        }else if (1){
            $all_class= array();
            for($i=0;$i<count($this->amount);$i++){
                $all_class[]= $this->class_id[$i].'-'.$this->class_type[$i];
                if($this->amount[$i]!='' and $this->class_id[$i]=='' )
                    $this->print_error('اختر الصنف');
                elseif($this->amount[$i]!='' and @$this->class_unit[$i]=='' and $this->transport_type==2 )
                    $this->print_error('اختر الوحدة');
                elseif($this->amount[$i]!='' and $this->class_type[$i]=='' and $this->transport_type==2 )
                    $this->print_error('اختر الحالة');
            }
        }

        if( count(array_filter($all_class)) !=  count(array_count_values(array_filter($all_class))) ){
            $this->print_error('يوجد تكرار في الاصناف');
        }

        if($this->request_side!=2){
            $this->customer_account_type= null;
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if($result[0]['BRANCH']==8) $result[0]['BRANCH']=2; // تم دمج مقر الصيانة مع مقر غزة 202210
        if(!(count($result)==1 and
            ($this->all_branches or $action=='archive' or
                $result[0]['BRANCH']== $this->user_branch  or
                ($result[0]['BRANCH_FOR_TO_STORE']== $this->user_branch and $result[0]['ADOPT'] >= 2 ) or
                ($this->select_all_stores and $this->user->id == $result[0]['ENTRY_USER'] )
            )))
            die();
        $data['transport_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='stores_class_transport_show';
        $data['isCreate']= true;
        $data['title']='بيانات المناقلة';
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
                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->amount[$i], $this->class_unit[$i],$this->class_type[$i], @$this->class_code_ser[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0  and $this->class_id[$i]!='' and $this->amount[$i]!='' and $this->amount[$i]>0 ){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->class_id[$i], $this->amount[$i], $this->class_unit[$i], $this->class_type[$i], @$this->class_code_ser[$i], 'edit'));
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_transport_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->class_transport_id,2);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم المناقلة";
    }

    function receipt(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_transport_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->class_transport_id,3);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاستلام'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم المناقلة";
    }

    function cancel(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->class_transport_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->class_transport_id, 0, $this->cancel_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الالغاء'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم المناقلة";
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

    function public_get_details($transport_type, $transport_id= 0, $request_no=0, $adopt= 0){
        $data['transport_type'] =$transport_type;
        $data['adopt'] =$adopt;
        $data['transport_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($transport_id);
        $this->load->model('stores_payment_request_det_model');
        $data['request_details'] = $this->stores_payment_request_det_model->get_list($request_no);
        $this->load->view('stores_class_transport_details',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');

        if($this->user_branch==1 or $this->select_all_stores){
            $branch= null;
        }else{
            $branch= $this->user_branch;
        }
        //$data['stores'] = $this->stores_model->get_list($branch); // if branch is null -> get all
        $data['stores'] = $this->stores_model->get_without_donation(2);
        $data['stores_all'] = $this->stores_model->get_list();
        $data['request_side'] = $this->constant_details_model->get_list(15);
        $data['class_transport_type'] = $this->constant_details_model->get_list(35);
        $data['customer_account_type'] = $this->constant_details_model->get_list(154);
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        $data['class_type'] = json_encode($this->constant_details_model->get_list(41));
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->class_transport_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ المناقلة: '.$msg);
        else
            $this->print_error('لم يتم حذف المناقلة: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'CLASS_TRANSPORT_ID','value'=>$this->class_transport_id ,'type'=>'','length'=>-1),
            array('name'=>'TRANSPORT_TYPE','value'=>$this->transport_type ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_NO','value'=>$this->request_no ,'type'=>'','length'=>-1),
            array('name'=>'FROM_STORE_ID','value'=>$this->from_store_id ,'type'=>'','length'=>-1),
            array('name'=>'TO_STORE_ID','value'=>$this->to_store_id ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_SIDE','value'=>$this->request_side ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TRANSPORT_ACCOUNT_ID','value'=>$this->class_transport_account_id ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ACCOUNT_TYPE','value'=>$this->customer_account_type ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TRANSPORT_TYPE','value'=>$this->class_transport_type ,'type'=>'','length'=>-1),
            array('name'=>'BROKER_EMP_NO','value'=>$this->broker_emp_no ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        else
            unset($result[1]);
        return $result;
    }

    function _postedData_details($ser= null, $class_id= null, $amount= null, $class_unit= null, $class_type= null, $class_code_ser= null, $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TRANSPORT_ID','value'=>$this->class_transport_id ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_NO','value'=>$this->request_no ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT','value'=>$amount ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$class_unit ,'type'=>'','length'=>-1),
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
