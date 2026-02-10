<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/12/14
 * Time: 09:31 ص
 */

class Stores_payment_request extends MY_Controller{

    var $MODEL_NAME= 'stores_payment_request_model';
    var $DETAILS_MODEL_NAME= 'stores_payment_request_det_model';
    var $PAGE_URL= 'stores/stores_payment_request/get_page';
    //var $ALL_BRANCHES= 'stores/stores_payment_request/all_branches';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('root/New_rmodel');

        $this->request_input= encryption_case($this->input->post('request_input'));
        $this->request_no= $this->input->post('request_no');
        $this->book_no= $this->input->post('book_no');
        $this->project_serial= $this->input->post('project_serial');
        $this->request_side= $this->input->post('request_side');
        $this->request_side_account= $this->input->post('request_side_account');
        $this->customer_account_type= $this->input->post('customer_account_type');
        $this->request_store_from= $this->input->post('request_store_from');
        $this->request_type= $this->input->post('request_type');
        $this->project_id= $this->input->post('project_id');
        $this->notes= $this->input->post('notes');
        $this->notes2= $this->input->post('notes2');
        $this->store_serv_req= $this->input->post('store_serv_req');
        $this->request_case= $this->input->post('request_case');
        $this->adopt= $this->input->post('adopt');
        $this->adopt2= $this->input->post('adopt2');
        $this->entry_user= $this->input->post('entry_user');
        $this->cancel_note= $this->input->post('cancel_note');
        $this->archive= $this->input->post('archive');
        $this->donation_file_id= $this->input->post('donation_file_id');
        $this->room_id= $this->input->post('room_id');

        // arrays
        $this->ser= $this->input->post('ser');
        $this->class_id= $this->input->post('class_id');
        $this->request_amount= $this->input->post('request_amount');
        $this->class_unit= $this->input->post('class_unit');
        $this->class_type= $this->input->post('class_type');

        $this->subscriber_no= $this->input->post('subscriber_no');

        $this->user_branch= ($this->user->branch ==8)? 2:$this->user->branch; // تم دمج مقر الصيانة مع مقر غزة 202210
        //$this->user_branch= $this->user->branch;

        if($this->request_input!=1 and $this->request_input!=2 and $this->request_input!=3 and $this->request_input!=4)
            die();

        if( HaveAccess(base_url("stores/stores_payment_request/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

        if( HaveAccess(base_url("stores/stores_payment_request/select_all_stores")) )
            $this->select_all_stores= 1;
        else
            $this->select_all_stores= 0;


        // صلاحية كل المقرات بشكل مؤقت - خاص رياض ابو قاسم
        if( $this->input->post('session_all_branches')== 1 and $this->user->id==788 ){
            if($this->session->userdata('payment_request_all_branches')== 1 )
                $this->session->set_userdata('payment_request_all_branches',0);
            else
                $this->session->set_userdata('payment_request_all_branches',1);
        }
        if( $this->session->userdata('payment_request_all_branches')== 1 ){
            $this->all_branches= 1;
        }

        // صلاحية الصرف من مخازن الحاسوب
        if( HaveAccess(base_url("stores/stores_payment_request/it_stores")) )
            $this->it_stores= 1;
        else
            $this->it_stores= 0;

    }

    function index($page= 1, $archive=-1, $request_no= -1, $book_no= -1, $request_side= -1, $request_side_account= -1, $request_store_from= -1, $request_type= -1, $project_id= -1, $store_serv_req= -1, $notes= -1, $adopt= -1, $request_case= -1, $adopt2= -1, $entry_user= -1 ){
        if($archive==1)
            $data['title']='أرشيف طلبات صرف اصناف';
        else
            $data['title']='طلبات صرف اصناف';

        $data['content']='stores_payment_request_index';

        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        $data['stores_all'] = $this->stores_model->get_all();
        $data['request_type_all'] = $this->constant_details_model->get_list(35);
        $data['request_side_all'] = $this->constant_details_model->get_list(15);
        $data['request_case_all'] = $this->constant_details_model->get_list(85);
        $data['entry_user_all'] = $this->get_entry_users('STORES_PAYMENT_REQUEST_TB');
        if($this->all_branches){
            $data['adopt_all'] = $this->constant_details_model->get_list(39);
            $data['adopt_all2'] = $this->constant_details_model->get_list(52);
        }elseif($this->user_branch==1){
            $data['adopt_all'] = $this->constant_details_model->get_list(39);
        }else{
            $data['adopt_all'] = $this->constant_details_model->get_list(52);
        }

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['page']=$page;
        $data['archive']=$archive;
        $data['request_no'] =$request_no;
        $data['book_no']= $book_no;
        $data['request_side'] =$request_side;
        $data['request_side_account'] =$request_side_account;
        $data['request_store_from'] =$request_store_from;
        $data['request_type'] =$request_type;
        $data['project_id'] =$project_id;
        $data['store_serv_req']= $store_serv_req;
        $data['notes'] =$notes;
        $data['adopt'] =$adopt;
        $data['request_case']= $request_case;
        $data['adopt2']= $adopt2;
        $data['entry_user'] =$entry_user;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template',$data);
    }

    function get_page($page = 1, $archive=-1, $book_no= -1, $request_no= -1, $request_side= -1, $request_side_account= -1, $request_store_from= -1,  $request_type= -1, $project_id= -1, $store_serv_req= -1, $notes= -1, $request_case= -1, $adopt= -1, $adopt2= -1, $entry_user= -1){
        $this->load->library('pagination');

        $archive= $this->check_vars($archive,'archive');
        $request_no= $this->check_vars($request_no,'request_no');
        $book_no= $this->check_vars($book_no,'book_no');
        $request_side= $this->check_vars($request_side,'request_side');
        $request_side_account= $this->check_vars($request_side_account,'request_side_account');
        $request_store_from= $this->check_vars($request_store_from,'request_store_from');
        $request_type= $this->check_vars($request_type,'request_type');
        $project_id= $this->check_vars($project_id,'project_id');
        $store_serv_req= $this->check_vars($store_serv_req,'store_serv_req');
        $notes= $this->check_vars($notes,'notes');
        $request_case= $this->check_vars($request_case,'request_case');
        $adopt= $this->check_vars($adopt,'adopt');
        $adopt2= $this->check_vars($adopt2,'adopt2');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= ' where 1=1 ';
        if ($archive== null or $archive!=1){

            if(!$this->all_branches and !$this->select_all_stores)   // تم دمج مقر الصيانة مع مقر غزة 202210
                $where_sql.= " and DECODE(branch,8,2,branch)= {$this->user_branch} ";
            elseif(!$this->all_branches and $this->select_all_stores)   // تم دمج مقر الصيانة مع مقر غزة 202210
                $where_sql.= " and ( DECODE(branch,8,2,branch)= {$this->user_branch} or entry_user= {$this->user->id} ) ";
        }
        $default_where_sql= $where_sql;

        $where_sql.= ($request_no!= null)? " and request_no= '{$request_no}' " : '';
        $where_sql.= ($book_no!= null)? " and book_no like '".add_percent_sign($book_no)."' " : '';
        $where_sql.= ($request_side!= null)? " and request_side= {$request_side} " : '';
        $where_sql.= ($request_side_account!= null)? " and request_side_account= {$request_side_account} " : '';
        $where_sql.= ($request_store_from!= null)? " and request_store_from= {$request_store_from} " : '';
        $where_sql.= ($request_type!= null)? " and request_type= {$request_type} " : '';
        $where_sql.= ($project_id!= null)? " and project_id like '".add_percent_sign($project_id)."' " : '';
        $where_sql.= ($store_serv_req!= null)? " and store_serv_req= '{$store_serv_req}' " : '';
        $where_sql.= ($notes!= null)? " and notes like '".add_percent_sign($notes)."' " : '';
        $where_sql.= ($request_case!= null)? " and STORES_PKG.STORES_PAYMENT_REQ_DET_COUNT(request_no)= '{$request_case}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= {$entry_user} " : '';
        if($this->all_branches){
            if($adopt==6)
                $where_sql.= ($adopt!= null)? " and branch=1 and adopt= 5 and general_manager= 2 " : '';
            elseif($adopt==5)
                $where_sql.= ($adopt!= null)? " and branch=1 and adopt= 5 and general_manager!= 2 " : '';
            else
                $where_sql.= ($adopt!= null)? " and branch=1 and adopt= {$adopt} " : '';

            $where_sql.= ($adopt2!= null)? " and branch!=1 and adopt= '{$adopt2}' " : '';
        }else{
            if($adopt==6)
                $where_sql.= ($adopt!= null)? " and adopt= 5 and general_manager= 2 " : '';
            elseif($adopt==5)
                $where_sql.= ($adopt!= null)? " and adopt= 5 and general_manager!= 2 " : '';
            else
                $where_sql.= ($adopt!= null)? " and adopt= {$adopt} " : '';
        }

        if( !$this->input->is_ajax_request() and $archive!=1 and ($request_no== -1 or $request_no==null)){
            $adopt_url= 'stores/stores_payment_request';
            $adopt_where_sql=' ';
            if(HaveAccess(base_url("$adopt_url/create")))
                $adopt_where_sql= " and adopt= 1 and entry_user= {$this->user->id} ";

            if(HaveAccess(base_url("$adopt_url/bs_adopt")))
                $adopt_where_sql= ' and adopt= 1 and branch!=1 ';

            if(HaveAccess(base_url("$adopt_url/bf_adopt")))
                $adopt_where_sql= ' and adopt= 2 and branch!=1 ';

            if(HaveAccess(base_url("$adopt_url/bm_adopt")))
                $adopt_where_sql= ' and adopt= 3 and branch!=1 ';


            if(HaveAccess(base_url("$adopt_url/technical_adopt")))
                $adopt_where_sql= ' and adopt= 1 and branch=1 ';

            if(HaveAccess(base_url("$adopt_url/store_adopt")))
                $adopt_where_sql= ' and adopt= 2 and branch=1 ';

            if(HaveAccess(base_url("$adopt_url/account_adopt")))
                $adopt_where_sql= ' and adopt= 3 and branch=1 ';

            if(HaveAccess(base_url("$adopt_url/manger_adopt")))
                $adopt_where_sql= ' and adopt= 5 and general_manager= 1 ';

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

        $this->load->view('stores_payment_request_page',$data);
    }

    function check_vars($var, $c_var){
        /* OLD CODE
         * $request_no= $this->request_no? $this->request_no:$request_no;
         * $request_no = $request_no == -1 ?null:$request_no;
         */

        if($c_var=='adopt' or $c_var=='adopt2')
            // if post take it, else take the parameter
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create($project_serial=0, $request_input=0){ //$request_input=2 مشروع   $request_input=3  مشروع مواد مورد  $request_input=4 طلب صيانة حاسوب
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->request_no= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->request_no) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->request_no);
            }else{
                for($i=0; $i<count($this->class_id); $i++){
                    if($this->class_id[$i]!='' and $this->request_amount[$i]!='' and $this->request_amount[$i]>0 and ( ($this->request_input==2 or $this->request_input==3) or ( ($this->request_input==1 or $this->request_input==4) and $this->class_unit[$i]!='' and $this->class_type[$i]!='')) ){
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->request_amount[$i], $this->class_unit[$i], $this->class_type[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->request_no);
            }

        }else{
            $data['project_data']= array();
            $data['branch']= $this->user_branch;

            if($request_input==4)
                $this->request_input= 4;
            else
                $this->request_input= 1;

            if($project_serial >0 and ($request_input==2 or $request_input==3) ){
                $this->load->model('projects/projects_model');
                $result= $this->projects_model->get($project_serial);
                if(count($result)!=1 ) // or $this->user_branch!=1
                    die();
                $data['project_data']= $result[0];
                $this->request_input= $request_input;
                $data['branch']= 1;
            }

            if($project_serial >0 and $request_input==4){
                $data['maintenance_ser']= $project_serial;
                $data['title']= 'ادخال طلب صرف - من طلب صيانة';
            }else{
                $data['maintenance_ser']='';
                $data['title']= 'ادخال طلب صرف';
            }

            $data['request_input']= $this->request_input;
            $data['content']='stores_payment_request_show';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->request_no=='' and $isEdit) or $this->request_input=='' or ($this->project_serial=='' and $this->request_input!=1) or $this->request_side=='' or $this->request_side_account=='' or $this->request_store_from=='' or $this->request_type=='' or ($this->request_type==1 and $this->project_id=='') ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif($this->request_side==2 and $this->customer_account_type==''){
            $this->print_error('يجب اختيار نوع المستفيد');
        }elseif(!$this->it_stores and in_array($this->request_store_from, array(9,14,33,37,41,45)) ){
            $this->print_error('ليس لديك صلاحية ادخال طلبات من مخازن الحاسوب');
        }elseif($this->request_type==1 and $this->request_input==1 and $this->user_branch==1){
            $this->print_error('يجب ادخال طلب الصرف من المشاريع');
        }else if(!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->request_amount)) <= 0 ){
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        }else if (1){
            $all_class= array();
            for($i=0;$i<count($this->request_amount);$i++){
                $all_class[]= $this->class_id[$i].'-'.$this->class_type[$i];
                if($this->request_amount[$i]!='' and $this->class_id[$i]=='' )
                    $this->print_error('اختر الصنف');
                elseif($this->request_amount[$i]!='' and $this->class_unit[$i]=='' and ($this->request_input==1 or $this->request_input==4) )
                    $this->print_error('ادخل وحدة الصنف');
                elseif($this->request_amount[$i]!='' and $this->class_type[$i]=='' and ($this->request_input==1 or $this->request_input==4) )
                    $this->print_error('ادخل حالة الصنف');
            }
        }

        if( count(array_filter($all_class)) !=  count(array_count_values(array_filter($all_class))) ){
            $this->print_error('يوجد تكرار في الاصناف');
        }

        if($this->request_type!=1){ // نوع الطلب ليس لمشروع
            $this->project_id= null;
        }

        if($this->request_side!=2){
            $this->customer_account_type= null;
        }

    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if($result[0]['BRANCH']==8) $result[0]['BRANCH']=2; // تم دمج مقر الصيانة مع مقر غزة 202210

        if(!(count($result)==1 and
        ( $this->all_branches
            or $action=='archive'
            or $result[0]['BRANCH']== $this->user_branch
            or ($this->select_all_stores and $this->user->id == $result[0]['ENTRY_USER'] ) )
        ))
            die();

        $data['request_data']=$result;
        $data['branch']= $this->user_branch;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['eng_emails']= $this->get_emails_by_code(1, $result[0]['BRANCH']);
        $data['g_manager_email']= $this->get_emails_by_code(2);
        $data['class_min_email']= $this->get_emails_by_code(6);
        //$data['subscribers_list']= $this->New_rmodel->general_get('STORES_PKG', 'S_PAY_REQ_SUBSCRIBERS_LIST',  array( array('name'=>':REQUEST_NO','value'=>$id ,'type'=>'','length'=>-1), array(), array() ) );
        $data['content']='stores_payment_request_show';
        $data['isCreate']= true;
        $data['title']='بيانات طلب الصرف';
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
                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->request_amount[$i]!='' and $this->request_amount[$i]>0 and ($this->class_unit[$i]!='' or ($this->request_input==1 or $this->request_input==4) ) and $this->class_type[$i]!='' ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->request_amount[$i], $this->class_unit[$i], $this->class_type[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0  and $this->class_id[$i]!='' and $this->request_amount[$i]!='' and $this->request_amount[$i]>0 and ($this->class_unit[$i]!='' or ($this->request_input==2 or $this->request_input==3) ) and $this->class_type[$i]!='' ){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->class_id[$i], $this->request_amount[$i], $this->class_unit[$i], $this->class_type[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0 and ($this->request_amount[$i]=='' or $this->request_amount[$i]==0) ){ // delete
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


    function cancel_request(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            echo $this->adopt(0);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function technical_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            $ret= $this->adopt(2);
            if($ret==1) $this->_notify('store_adopt','طلب صرف رقم '.$this->request_no);
            echo $ret;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function store_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            $ret= $this->adopt(3);
            if($ret==1) $this->_notify('account_adopt','طلب صرف رقم '.$this->request_no);
            echo $ret;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function account_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            echo $this->adopt(4);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function financial_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            echo $this->adopt(5);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function manger_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            echo $this->adopt(6);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function bs_adopt(){ // جهة طالبة
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function bf_adopt(){ // مدير مالي
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            $ret= $this->adopt(11);
            if($ret==1) $this->_notify('bm_adopt','طلب صرف رقم '.$this->request_no);
            echo $ret;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function bm_adopt(){ // مدير الفرع
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            echo $this->adopt(12);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    private function adopt($case){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        $res = $this->{$this->MODEL_NAME}->adopt($this->request_no, $case, $this->cancel_note);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function cancel_adopt(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            $res = $this->{$this->MODEL_NAME}->cancel_adopt($this->request_no, $this->cancel_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الارجاع'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function reserve(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->request_no!=''){
            $res = $this->{$this->MODEL_NAME}->reserve($this->request_no);
            if(intval($res) <= 0){
                $this->print_error('لم تتم العملية '.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function public_get_details($request_input, $id = 0, $project_serial=0, $adopt= 0){
        $data['request_input'] =$request_input;
        $data['adopt'] = $adopt;
        if($request_input== -1){
            $this->load->model('stores_class_transport_det_model');
            $data['transport_details'] = $this->stores_class_transport_det_model->get_list($id);
            $data['request_details']= array();
            $data['project_details']= array();
            $data['donate_details']= array();
        }elseif($request_input== -2){
            $this->load->model('donations/donation_detail_model');
            $data['donate_details'] = $this->donation_detail_model->get_details_for_stores($id);
            $data['request_details']= array();
            $data['project_details']= array();
            $data['transport_details']= array();
        }else{
            $data['request_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
            $this->load->model('projects/projects_model');
            $data['project_details'] = $this->projects_model->get_details($project_serial,'1,3,4',$request_input); // $request_input 2 or 3
            // تم اضافة الحالة 4 بتاريخ 27/10/2019 بناء على طلب احمد بركات
            $data['donate_details']= array();
        }
        $this->load->view('stores_payment_request_details',$data);
    }

    function serv_get($id=0){
        $result= $this->{$this->MODEL_NAME}->serv_get($id);
        if(!(count($result)==1))
            die(' رقم الطلبية خاطئ');
        $data['serv_data']=$result[0];
        $data['content']='serv_store_request_show';
        $this->load->view('template/view',$data);
    }

    function public_serv_get_details($id=0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->serv_get_list($id);
        $this->load->view('serv_store_request_details',$data);
    }

    function insert_subscriber(){
        if ($_SERVER['REQUEST_METHOD'] != 'POST' or $this->request_no==''){
            die('post');
        }

        $this->subscriber_no= array_filter($this->subscriber_no);
        $cnt_all= count($this->subscriber_no);
        $inserted=0;
        $failed='';

        foreach( $this->subscriber_no as $subscriber_no){
            $data= array(
                array('name'=>'REQUEST_NO','value'=>$this->request_no ,'type'=>'','length'=>-1),
                array('name'=>'SUBSCRIBER_NO','value'=>$subscriber_no ,'type'=>'','length'=>-1)
            );
            $res= $this->New_rmodel->general_transactions('STORES_PKG', 'S_PAY_REQ_SUBSCRIBERS_INSERT', $data);
            if(intval($res)>0){
                $inserted++;
            }else{
                $failed.= $subscriber_no.',';
            }
        }

        if($cnt_all==$inserted){
            echo "تم ادخال جميع الاشتراكات بنجاح";
        }else{
            echo "تم ادخال {$inserted} اشتراك من اصل {$cnt_all} اشتراك";
            echo "<br/> الاشتراكات الغير مدخلة هي <br/>".$failed;
        }

    }

    function delete_subscriber(){
        $ser= $this->input->post('ser');
        $data= array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1)
        );
        $res= $this->New_rmodel->general_transactions('STORES_PKG', 'S_PAY_REQ_SUBSCRIBERS_DELETE', $data);
        if(intval($res)==1){
            echo 1;
        }else{
            $res;
        }
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
        $data['customer_stores'] = $this->stores_model->get_customer_stores(2);
        $data['request_type'] = $this->constant_details_model->get_list(35);
        $data['request_side'] = $this->constant_details_model->get_list(15);
        $data['customer_account_type'] = $this->constant_details_model->get_list(154);
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        $data['class_type'] = json_encode($this->constant_details_model->get_list(41));
        //$data['stores_prefix'] =  $this->get_system_info('STORES_PREFIX','1');
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');
        add_js('moment.js');
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->request_no);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ الطلب: '.$msg);
        else
            $this->print_error('لم يتم حذف الطلب: '.$msg);
    }

    function _notify($action, $message, $id = null){
        $this->_notifyMessage($action, "stores/stores_payment_request/get/{$this->request_no}", $message);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'REQUEST_NO','value'=>$this->request_no ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_INPUT','value'=>$this->request_input ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_SERIAL','value'=>$this->project_serial ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_SIDE','value'=>$this->request_side ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_SIDE_ACCOUNT','value'=>$this->request_side_account ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ACCOUNT_TYPE','value'=>$this->customer_account_type ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_STORE_FROM','value'=>$this->request_store_from ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_TYPE','value'=>$this->request_type ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_ID','value'=>$this->project_id ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
            array('name'=>'NOTES2','value'=>$this->notes2 ,'type'=>'','length'=>-1),
            array('name'=>'STORE_SERV_REQ','value'=>$this->store_serv_req ,'type'=>'','length'=>-1),
            array('name'=>'DONATION_FILE_ID','value'=>$this->donation_file_id ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_ID','value'=>$this->room_id ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        else
            unset($result[1]);
        return $result;
    }

    function _postedData_details($ser= null, $class_id, $request_amount= null, $class_unit= null ,$class_type= null , $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_NO','value'=>$this->request_no ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_SERIAL','value'=>$this->project_serial ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_AMOUNT','value'=>$request_amount ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$class_unit ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TYPE','value'=>$class_type ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[2]);
        return $result;
    }

}
