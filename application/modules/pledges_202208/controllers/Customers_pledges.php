<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/02/15
 * Time: 01:04 م
 */
class Customers_pledges extends MY_Controller{

    var $MODEL_NAME= 'customers_pledges_model';
    var $PAGE_URL= 'pledges/customers_pledges/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('stores/stores_model');
        $this->load->model('settings/gcc_branches_model');



        $this->file_id= $this->input->post('file_id');
        $this->customer_id= $this->input->post('customer_id');
        $this->room_id= $this->input->post('room_id');
        $this->customer_type= $this->input->post('customer_type');
        $this->customer_movment_type= $this->input->post('customer_movment_type');
        $this->room_movement_id= $this->input->post('room_movement_id');
        $this->department_st_id= $this->input->post('department_st_id');
        $this->cycle_st_id= $this->input->post('cycle_st_id');
        $this->manage_st_id= $this->input->post('manage_st_id');
        $this->recieved_date= $this->input->post('recieved_date');
        $this->source= $this->input->post('source');
        $this->status= $this->input->post('status');
        $this->class_id= $this->input->post('class_id');
        $this->class_unit= $this->input->post('class_unit');
        $this->exp_account_cust= $this->input->post('exp_account_cust');
        $this->class_output_id= $this->input->post('class_output_id');
        $this->amount= $this->input->post('amount');
        $this->price= $this->input->post('price');
        $this->notes= $this->input->post('notes');
        $this->destruction_type=$this->input->post('destruction_type');
        $this->destruction_account_id=$this->input->post('destruction_account_id');
        $this->destruction_percent=$this->input->post('destruction_percent');
        $this->average_life_span=$this->input->post('average_life_span');
        $this->average_life_span_type=$this->input->post('average_life_span_type');
        $this->class_code_ser=$this->input->post('class_code_ser');
        $this->customer_movment_id=$this->input->post('customer_movment_id');
        $this->customer_movment_id2=$this->input->post('customer_movment_id2');
        $this->store_id=$this->input->post('store_id');
        $this->class_type=$this->input->post('class_type');
        $this->movment_manage_st_id=$this->input->post('movment_manage_st_id');
        $this->movment_cycle_st_id=$this->input->post('movment_cycle_st_id');
        $this->movment_depart_st_id=$this->input->post('movment_depart_st_id');
        $this->d_file_id=$this->input->post('d_file_id');
        $this->note_class_id=$this->input->post('note_class_id');
        $this->emp_pledges= intval($this->input->post('emp_pledges'));
        $this->serial=$this->input->post('serial');
        $this->is_adopt=$this->input->post('is_adopt');
        $this->branch=$this->input->post('branch');
        $this->tdate=$this->input->post('tdate');
        $this->fdate=$this->input->post('fdate');
        $this->store_id=$this->input->post('store_id');
        $this->adopt=$this->input->post('adopt');
        $this->personal_custody_type=$this->input->post('personal_custody_type');
        $this->custody_type=$this->input->post('custody_type');
        $this->is_end_age=$this->input->post('is_end_age');
        $this->emp_pledges= ($this->emp_pledges!=0)? encryption_case($this->emp_pledges):0;
        if ($this->emp_pledges !=1 and $this->emp_pledges !=2 and $this->emp_pledges !=0)
            die('emp_pledges');
    }

    function index($page= 1, $emp_pledges=-1, $file_id= -1, $customer_id= -1, $source= -1, $status= -1, $class_id= -1, $class_unit= -1, $exp_account_cust= -1, $class_output_id= -1, $notes= -1, $class_code_ser= -1,$class_type=-1,$manage_st_id=-1,$cycle_st_id=-1,$department_st_id=-1,$serial=-1,$is_adopt=-1,$branch=-1,$tdate=-1,$fdate=-1,$store_id=-1,$adopt=-1,$personal_custody_type=-1,$custody_type=-1,$room_id=-1,$is_end_age=-1){

        if ($emp_pledges==1)
            $data['title']='العهد الخاصة بك';
        else
            $data['title']='العهد المصروفة للموظفين';


      //  $this->load->model('payment/customers_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/Gcc_structure_model');
      //  $data['source_all'] = $this->constant_details_model->get_list(60);
      //  $data['status_all'] = $this->constant_details_model->get_list(61);
      //  $data['class_type_all'] = $this->constant_details_model->get_list(41);
       // $data['customer_ids']=$this->customers_model->get_all_by_type(3);
        $data['manage_st_ids']=$this->Gcc_structure_model->getStructure(1);
        $data['cycle_st_ids']=$this->Gcc_structure_model->getStructure(2);
        /*GCC_STRUCTURE_TB_GET_LIST(
 USER_ID_IN      IN   USERS_PROG_TB.ID%TYPE,
 ST_PARENT_ID_IN IN   GCC_STRUCTURE_TB.ST_PARENT_ID%TYPE,
 REF_CUR_OUT out sys_refcursor,MSG_OUT OUT VARCHAR2 )*/
        $data['department_st_ids']=$this->Gcc_structure_model->getStructure(3);
        $data['stores'] = $this->stores_model->get_all();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['branches_link'] = $this->{$this->MODEL_NAME}->get_branch_all();

        //$data['entry_user_all'] = $this->get_entry_users('CUSTOMERS_PLEDGES');
       add_css('select2_metro_rtl.css');
       add_js('select2.min.js');



        $data['page']=$page;
        $data['file_id']= $file_id;
        $data['customer_id']= $customer_id;
        $data['source']= $source;
        $data['status']= $status;
        $data['class_id']= $class_id;
        $data['class_unit']= $class_unit;
        $data['exp_account_cust']= $exp_account_cust;
        $data['class_output_id']= $class_output_id;
        $data['notes']= $notes;
        $data['emp_pledges']= $emp_pledges;
        $data['class_code_ser']=$class_code_ser;
        $data['class_type']=$class_type;
        $data['manage_st_id']=$manage_st_id;
        $data['cycle_st_id']=$cycle_st_id;
        $data['department_st_id']=$department_st_id;
        $data['serial']=$serial;
        $data['is_adopt']=$is_adopt;
        $data['branch']=$branch;
        $data['tdate']=$tdate;
        $data['fdate']=$fdate;
        $data['store_id']=$store_id;
        $data['adopt']=$adopt;
        $data['personal_custody_type']=$personal_custody_type;
        $data['custody_type']=$custody_type;
        $data['room_id']=$room_id;
        $data['is_end_age']=$is_end_age;




        if ($emp_pledges !=1 and $emp_pledges !=2)
            die('emp_pledges_index');

        if ($emp_pledges==2 and !HaveAccess('pledges/customers_pledges/index/1/2'))
            die('NotHaveAccess');

        $data['content']='customers_pledges_index';

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $emp_pledges= -1, $file_id= -1, $customer_id= -1, $source= -1, $status= -1, $class_id= -1, $class_unit= -1, $exp_account_cust= -1, $class_output_id= -1, $notes= -1,$class_code_ser=-1,$class_type=-1,$manage_st_id=-1,$cycle_st_id=-1,$department_st_id=-1,$serial=-1,$is_adopt=-1,$branch=-1,$tdate=-1,$fdate=-1,$store_id=-1,$adopt=-1,$personal_custody_type=-1,$custody_type=-1,$room_id=-1,$is_end_age=-1){

        $this->load->library('pagination');

        $file_id= $this->check_vars($file_id,'file_id');
        $emp_pledges= $this->check_vars($emp_pledges,'emp_pledges');
        $customer_id= $this->check_vars($customer_id,'customer_id');
        //$customer_id2= $this->check_vars(-1,'customer_id2');
        $room_id= $this->check_vars($room_id,'room_id');
        $customer_type= $this->check_vars(-1,'customer_type');
        $source= $this->check_vars($source,'source');
        $status= $this->check_vars($status,'status');
        $class_id= $this->check_vars($class_id,'class_id');
        $class_unit= $this->check_vars($class_unit,'class_unit');
        $exp_account_cust= $this->check_vars($exp_account_cust,'exp_account_cust');
        $class_output_id= $this->check_vars($class_output_id,'class_output_id');
        $notes= $this->check_vars($notes,'notes');
        $class_code_ser= $this->check_vars($class_code_ser,'class_code_ser');
        $class_type= $this->check_vars($class_type,'class_type');
        $manage_st_id= $this->check_vars($manage_st_id,'manage_st_id');
        $cycle_st_id= $this->check_vars($cycle_st_id,'cycle_st_id');
        $department_st_id= $this->check_vars($department_st_id,'department_st_id');
        $serial= $this->check_vars($serial,'serial');
        $is_adopt= $this->check_vars($is_adopt,'is_adopt');
        $branch= $this->check_vars($branch,'branch');
        $fdate= $this->check_vars($fdate,'fdate');
        $tdate= $this->check_vars($tdate,'tdate');
        $store_id= $this->check_vars($store_id,'store_id');
        $adopt= $this->check_vars($adopt,'adopt');
        $personal_custody_type= $this->check_vars($personal_custody_type,'personal_custody_type');
        $custody_type= $this->check_vars($custody_type,'custody_type');
        $is_end_age= $this->check_vars($is_end_age,'is_end_age');

        if ($emp_pledges==2 and !HaveAccess('pledges/customers_pledges/index/1/2'))
            die('NotHaveAccess');

        $where_sql= ' and 1=1 ';

        $where_sql.= ($file_id!= null)? " and file_id= '{$file_id}' " : '';
        $where_sql.= ($emp_pledges!= null && $emp_pledges==1)? " and status=1  and customer_id in (SELECT ID FROM employees_tb a where   a.NO=".$this->user->emp_no." ) " : '';
        $where_sql.= ($customer_id!= null )? " and customer_id= '{$customer_id}' " : '';
      //  $where_sql.= ($customer_id2!= null and $customer_type==4)? " and customer_id= '{$customer_id2}' " : '';
        $where_sql.= ($room_id!= null )? " and room_id= '{$room_id}' " : '';
        $where_sql.= ($customer_type!= null)? " and customer_type= '{$customer_type}' " : '';
        $where_sql.= ($source!= null)? " and source= '{$source}' " : '';
        $where_sql.= ($status!= null)? " and status= '{$status}' " : '';
        $where_sql.= ($class_id!= null)? " and C.class_id like "."'".$class_id."%'"  : '';
        $where_sql.= ($class_unit!= null)? " and C.class_unit= '{$class_unit}' " : '';
        $where_sql.= ($exp_account_cust!= null)? " and C.exp_account_cust= '{$exp_account_cust}' " : '';
        $where_sql.= ($class_output_id!= null)? " and class_output_id= '{$class_output_id}' " : '';
        $where_sql.= ($notes!= null)? " and notes like '".add_percent_sign($notes)."' " : '';
        //$where_sql.= ($class_code_ser!= null)? " and class_code_ser LIKE "."'%".$class_code_ser."%'"  : '';
        $where_sql.= ($class_code_ser!= null)? " and '".$class_code_ser."' IN  ( TO_CHAR(C.class_code_ser),C.BARCODE ) "  : '';

        $where_sql.= ($class_type!= null)? " and class_type= '{$class_type}' " : '';
        $where_sql.= ($manage_st_id!= null)? " and manage_st_id= '{$manage_st_id}' " : '';
        $where_sql.= ($cycle_st_id!= null)? " and cycle_st_id= '{$cycle_st_id}' " : '';
        $where_sql.= ($department_st_id!= null)? " and department_st_id= '{$department_st_id}' " : '';
        $where_sql.= ($serial!= null)? " and FIXED_PKG.GET_SERIAL_CLASS_CODE_SER( C.CLASS_CODE_SER) LIKE "."'%".$serial."%'"  : '';
        $where_sql.= ($is_adopt!= null && $is_adopt== 1)? " and ((ADOPT=2 AND NVL(FOLLOW_FILE_ID,0)=0) AND(  STATUS=2  OR   STATUS=4 ) ) " : '';
        $where_sql.= ($is_adopt!= null && $is_adopt== 2)? " and ((ADOPT=2 AND NVL(FOLLOW_FILE_ID,0)!=0) AND(  STATUS=2  OR   STATUS=4 ) ) " : '';
        $where_sql.= ($branch!= null)? " and INVENTORY_PLEDGES_PKG.GET_BRANCH_PLEDGE(C.CUSTOMER_TYPE,C.CUSTOMER_ID)={$branch} " : '';
        $where_sql.= ($fdate!= null)? "  AND TRUNC( C.RECIEVED_DATE) >=to_date('{$fdate}','DD/MM/YYYY') "  : '';
        $where_sql.= ($tdate!= null)? "  AND TRUNC( C.RECIEVED_DATE) <=to_date('{$tdate}','DD/MM/YYYY') " : '';
        $where_sql.= ($store_id!= null)? "  AND store_id ={$store_id} " : '';
        $where_sql.= ($adopt!= null)? "  AND adopt = '{$adopt}' " : '';
        $where_sql.= ($personal_custody_type!= null)? "  AND M.personal_custody_type = '{$personal_custody_type}' " : '';
        $where_sql.= ($custody_type!= null)? "  AND M.custody_type = '{$custody_type}' " : '';
        $where_sql.= ($is_end_age!= null && $is_end_age== 1)? " and ( months_between(sysdate,TRUNC( C.RECIEVED_DATE))>=nvl(M.PLEDGE_AGE,0) ) " : '';
        $where_sql.= ($is_end_age!= null && $is_end_age== 2)? " and ( months_between(sysdate,TRUNC( C.RECIEVED_DATE))<=nvl(M.PLEDGE_AGE,0) ) " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CUSTOMERS_PLEDGES C, CLASS_TB M WHERE C.CLASS_ID=M.CLASS_ID '.$where_sql);

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
        $this->_look_ups($data);
        $this->load->view('customers_pledges_page',$data);
    }

    function check_vars($var, $c_var){
        if($c_var!='emp_pledges')
            // if post take it, else take the parameter
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;

        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
    function adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,2);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
        }
    function public_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,2);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }

    function stop(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->stop($this->p_id,3);

            if(intval($res) <= 0){
                $this->print_error('لم يتم تكهين العهدة'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }
    function stop2020(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->stop($this->p_id,6);

            if(intval($res) <= 0){
                $this->print_error('لم يتم تكهين العهدة'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }
    function lost(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->stop($this->p_id,5);

            if(intval($res) <= 0){
                $this->print_error('خطأ لم يتم تكهين العهدة حسب لجنة جرد 2020  '.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }

    function cancel_0(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->stop($this->p_id,0);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الإلغاء بنجاح'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }

    function onEmp(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->onEmp($this->p_id,1);

            if(intval($res) <= 0){
                $this->print_error('خطأ لم يتم تسجيل العهدة على العهدة'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }
    function cancel(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,1);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الارجاع'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }
    function get($id, $emp_pledges=0, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);

     //   print_r ($result);
       /* if(!(count($result)==1 ) or !HaveAccess('pledges/customers_pledges/index/1/2') )
            die();
*/
        $data['result_d']= $this->{$this->MODEL_NAME}->get_details($id);

        $data['pledge_data']=$result[0];
        $data['emp_pledges']= $emp_pledges ;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
    //  echo $result[0]['ENTRY_USER'] ;
        $data['action'] = $action;
        $data['content']='customers_pledges_show';
        $data['isCreate']= false;
        $data['title']='بيانات العهدة';
        $data['help']=$this->help;
        $this->load->view('template/template',$data);
    }

    function create($customer_id=-1){
       /* $this->load->model('payment/customers_model');
        $this->load->model('settings/constant_details_model');
        $data['source_all'] = $this->constant_details_model->get_list(60);
        $data['status_all'] = $this->constant_details_model->get_list(61);
        $data['customer_ids']=$this->customers_model->get_all_by_type(3);*/
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->file_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->file_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->file_id);
            }
            else
                echo intval($this->file_id);

        }else{

            $result=array();
            $data['pledge_data']=$result;
            $data['customer_id']=$customer_id;
            $data['content']='customers_pledges_shows';
            $data['title']= 'اضافة عهدة';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['help'] = $this->help;
           $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function edit($id=0){
        /* $this->load->model('payment/customers_model');
         $this->load->model('settings/constant_details_model');
         $data['source_all'] = $this->constant_details_model->get_list(60);
         $data['status_all'] = $this->constant_details_model->get_list(61);
         $data['customer_ids']=$this->customers_model->get_all_by_type(3);*/
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $res=  $this->{$this->MODEL_NAME}->edit($this->_postedData('edit'));
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }
            else
            echo 1;
        }else{
            $result= $this->{$this->MODEL_NAME}->get($id);

           /* if(!(count($result)==1 ) or !HaveAccess('pledges/customers_pledges/index/1/2') and $result[0]['EMP_NO']!=$this->user->emp_no)
                die();*/
            $data['pledge_data']=$result[0];
            $data['content']='customers_pledges_shows';
            $data['title']= 'تعديل عهدة موظف';
            $data['isCreate']= false;
            $data['action'] = 'edit';
            $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $data['action'] == 'edit')?true : false : false;
            $data['help'] = $this->help;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);

        }
    }

    function movement($id=0){
        /* $this->load->model('payment/customers_model');
         $this->load->model('settings/constant_details_model');
         $data['source_all'] = $this->constant_details_model->get_list(60);
         $data['status_all'] = $this->constant_details_model->get_list(61);
         $data['customer_ids']=$this->customers_model->get_all_by_type(3);*/

         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             if ($this->class_type=='')
             {
                 $this->print_error('يجب ادخال حالة الصنف');
             }
         else  if ($this->customer_movment_id=='' AND $this->movment_manage_st_id=='' AND $this->movment_cycle_st_id=='' AND $this->movment_depart_st_id=='' AND $this->room_movement_id=='')
            {
                $this->print_error('(يجب ادخال أحد البيانات التالية(الموظف المنقول له,الغرفة, الادارة, الدائرة, القسم');
            }

           $customer_movment_id_=$this->customer_movment_id; //($this->customer_movment_type==4)?$this->customer_movment_id2:$this->customer_movment_id;

           $res=  $this->{$this->MODEL_NAME}->move($this->file_id,$customer_movment_id_,$this->movment_manage_st_id,$this->movment_cycle_st_id,$this->movment_depart_st_id,$this->class_type,$this->customer_movment_type,$this->room_movement_id);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }
            else
                echo 1;
        }
        else{
            $result= $this->{$this->MODEL_NAME}->get($id);

           /* if(!(count($result)==1 ) or !HaveAccess('pledges/customers_pledges/index/1/2') and $result[0]['EMP_NO']!=$this->user->emp_no)
                die();*/
            $data['pledge_data']=$result[0];
            $data['content']='customers_pledges_movement';
            //$data['gcc_structure_show_cycle']==$this->Gcc_structure_model->getStructure(2);
            $data['title']= 'نقل عهدة موظف';
            $data['isCreate']= false;
            $data['action'] = 'movement';
           // $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $data['action'] == 'edit')?true : false : false;
            $data['help'] = $this->help;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);

        }
    }
    function excute(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->move_adopt($this->p_id);

            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }

    function _post_validation($isEdit = false){
       // $customer_id_= ($this->customer_type==4)?$this->customer_id2:$this->customer_id;
       /* if( ($this->file_id=='' and $isEdit) or ( $this->recieved_date=='' or $this->class_id==''  or $this->class_type=='')  ){
            $this->print_error('يجب ادخال جميع البيانات');
        }
        else if ($this->customer_id=='' AND $this->manage_st_id=='' AND $this->cycle_st_id=='' AND $this->department_st_id=='')
        {
            $this->print_error('(يجب ادخال أحد البيانات التالية(حساب المستفيد, الادارة, الدائرة, القسم');
        }*/
        if( ($this->file_id=='' and $isEdit) or ($this->customer_id=='' or $this->room_id=='' or $this->recieved_date=='' or $this->class_id==''  or $this->class_type=='')  ){
            $this->print_error('يجب ادخال جميع البيانات');
        }


    }

    function public_get_id(){


       $id = $this->input->post('id');
       // $this->print_error($id);
        $result = $this->{$this->MODEL_NAME}->get_bar_code_info($id);
        //print_r($result);
        //$this->print_error($result[0]);
        //
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function public_customer_url(){

        $id = $this->input->post('id');


        $result = $this->{$this->MODEL_NAME}->structre_emloyee_get($id);
        //print_r($result);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function return_store($id=0) {
        /* $this->load->model('payment/customers_model');
         $this->load->model('settings/constant_details_model');
         $data['source_all'] = $this->constant_details_model->get_list(60);
         $data['status_all'] = $this->constant_details_model->get_list(61);
         $data['customer_ids']=$this->customers_model->get_all_by_type(3);*/

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $res=  $this->{$this->MODEL_NAME}->return_store($this->file_id,$this->store_id,$this->class_type);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }
            else
                echo 1;
        }
        else{
            $result= $this->{$this->MODEL_NAME}->get($id);
            $this->load->model('stores/stores_model');
            $data['stores'] = $this->stores_model->get_all();
            /*if(!(count($result)==1 ) or !HaveAccess('pledges/customers_pledges/index/1/2') and $result[0]['EMP_NO']!=$this->user->emp_no)
                die();*/
            $data['pledge_data']=$result[0];
            $data['content']='customers_pledges_return';
            $data['title']= 'ارجاع عهدة الى المخزن';
            $data['isCreate']= false;
            $data['action'] = 'return_store';
            // $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $data['action'] == 'edit')?true : false : false;
            $data['help'] = $this->help;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);

        }
    }


    function return_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->return_adopt($this->p_id);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الارجاع'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }



    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $this->load->model('employees/employees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/Gcc_structure_model');

        $data['source_all'] = $this->constant_details_model->get_list(60);
        $data['status_all'] = $this->constant_details_model->get_list(61);
        $data['custody_types'] = $this->constant_details_model->get_list(305);
        $data['personal_custody_types'] = $this->constant_details_model->get_list(304);
        $data['customer_ids']=$this->employees_model->get_all();
        $data['manage_st_ids']=$this->Gcc_structure_model->getStructure(1);
        $data['cycle_st_ids']=$this->Gcc_structure_model->getStructure(2);
        $data['department_st_ids']=$this->Gcc_structure_model->getStructure(3);
        $data['class_type_all'] = $this->constant_details_model->get_list(41);
        $data['customer_type_cons']= $this->constant_details_model->get_list(370); // Mkilani
        $data['adopts']= $this->constant_details_model->get_list(373);
        $data['gcc_structure_show_cycle']=$this->Gcc_structure_model->getStructure(2);
        $data['rooms_cons']= $this->public_get_rooms('array'); // Mkilani

        //$data['gcc_structure_shows']=$this->Gcc_structure_model->getList($this->p_movment_manage_st_id,intval($this->user));//
        $data['class_code_ser']='';






    }



    function _postedData($typ= null){

        $customer_id_= ($this->customer_type==4)?$this->customer_id2:$this->customer_id;

        $result = array(
            array('name'=>'FILE_ID','value'=>$this->file_id ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ID','value'=>$customer_id_ ,'type'=>'','length'=>-1),
            array('name'=>'SOURCE','value'=>$this->source ,'type'=>'','length'=>-1),
            array('name'=>'STATUS','value'=>$this->status ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$this->class_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$this->class_unit ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_CODE_SER','value'=>$this->class_code_ser ,'type'=>'','length'=>-1),
            array('name'=>'PRICE','value'=>$this->price ,'type'=>'','length'=>-1),
            array('name'=>'EXP_ACCOUNT_CUST','value'=>$this->exp_account_cust ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_OUTPUT_ID','value'=>$this->class_output_id ,'type'=>'','length'=>-1),
            array('name'=>'RECIEVED_DATE','value'=>$this->recieved_date ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
            array('name'=>'DESTRUCTION_TYPE','value'=>$this->destruction_type ,'type'=>'','length'=>-1),
            array('name'=>'DESTRUCTION_ACCOUNT_ID','value'=>$this->destruction_account_id ,'type'=>'','length'=>-1),
            array('name'=>'DESTRUCTION_PERCENT','value'=>$this->destruction_percent ,'type'=>'','length'=>-1),
            array('name'=>'AVERAGE_LIFE_SPAN','value'=>$this->average_life_span ,'type'=>'','length'=>-1),
            array('name'=>'AVERAGE_LIFE_SPAN_TYPE','value'=>$this->average_life_span_type ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TYPE','value'=>$this->class_type ,'type'=>'','length'=>-1),
            array('name'=>'MANAGE_ST_ID','value'=>$this->manage_st_id ,'type'=>'','length'=>-1),
            array('name'=>'CYCLE_ST_ID','value'=>$this->cycle_st_id ,'type'=>'','length'=>-1),
            array('name'=>'DEPARTMENT_ST_ID','value'=>$this->department_st_id ,'type'=>'','length'=>-1),
            array('name'=>'D_FILE_ID','value'=>$this->d_file_id ,'type'=>'','length'=>-1),
            array('name'=>'NOTE_CLASS_ID','value'=>$this->note_class_id ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_TYPE','value'=>$this->customer_type ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_ID','value'=>$this->room_id ,'type'=>'','length'=>-1)


        );
        if($typ=='create')
            unset($result[0]);
       /* else
            unset($result[1]);*/


        return $result;
    }

function edit_barcode(){
    $result = $this->{$this->MODEL_NAME}->editBarcode($this->_postedDataBar());
    echo $result ;

}
    function _postedDataBar(){
        $result = array(
            array('name'=>'FILE_ID','value'=>$this->input->post('file_idd') ,'type'=>'','length'=>-1),
            array('name'=>'BARCODE','value'=>$this->input->post('class_code_serr') ,'type'=>'','length'=>-1)
        );
        return $result;
    }
    function public_get_info_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }
    function public_get_customer_class_file_ids(){
        $customer_type = $this->input->post('customer_type');
        $customer_id = $this->input->post('customer_id');
        $customer_idx = $this->input->post('customer_idx');
        $file_id = $this->input->post('file_id');

        if ($customer_type==1){
            $result = $this->{$this->MODEL_NAME}->customers_pledges_get_file_ids($customer_type,$customer_id,$file_id);

        }else{
            $result = $this->{$this->MODEL_NAME}->customers_pledges_get_file_ids($customer_type,$customer_idx,$file_id);

        }
     /*  $customer_id = $this->input->post('customer_id');
        $file_id = $this->input->post('file_id');
        $result = $this->{$this->MODEL_NAME}->customers_pledges_get_file_ids($customer_id,$file_id);
   */
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function public_gcc_structure_cycle()
    {
        $this->load->model('settings/Gcc_structure_model');
        $data['gcc_structure_show_c']=$this->Gcc_structure_model->getList($this->p_id,intval($this->user));//
        $data['action'] = 'edit';
        $data['content']='customers_pledges_cycle';
        $this->_look_ups($data);
        $result= $this->{$this->MODEL_NAME}->get($this->p_file_id);

        /* if(!(count($result)==1 ) or !HaveAccess('pledges/customers_pledges/index/1/2') and $result[0]['EMP_NO']!=$this->user->emp_no)
             die();*/
        $data['pledge_data']=$result[0];
        $this->load->view('customers_pledges_cycle',$data);


    }

    function public_gcc_structure_dep()
    {
        $this->load->model('settings/Gcc_structure_model');
        $data['gcc_structure_show_d']=$this->Gcc_structure_model->getList($this->p_id,intval($this->user));//
        $data['action'] = 'edit';
        $data['content']='customers_pledges_dep';
        $result= $this->{$this->MODEL_NAME}->get($this->p_file_id);

        /* if(!(count($result)==1 ) or !HaveAccess('pledges/customers_pledges/index/1/2') and $result[0]['EMP_NO']!=$this->user->emp_no)
             die();*/
        $data['pledge_data']=$result[0];
        $this->_look_ups($data);
        $this->load->view('customers_pledges_dep',$data);


    }


    function public_move_structure_cycle()
    {
        $this->load->model('settings/Gcc_structure_model');
        $data['gcc_structure_show_cycle']=$this->Gcc_structure_model->getList($this->p_id,intval($this->user));//
        $data['action'] = 'edit';
        $data['content']='customers_pledges_cycle_move';
        $result= $this->{$this->MODEL_NAME}->get($this->p_file_id);

        /* if(!(count($result)==1 ) or !HaveAccess('pledges/customers_pledges/index/1/2') and $result[0]['EMP_NO']!=$this->user->emp_no)
             die();*/
        $data['pledge_data']=$result[0];

        $this->_look_ups($data);
        $this->load->view('customers_pledges_cycle_move',$data);


    }

    function public_move_structure_dep()
    {
        $this->load->model('settings/Gcc_structure_model');
        $data['gcc_structure_show_dep']=$this->Gcc_structure_model->getList($this->p_id,intval($this->user));//

        $data['action'] = 'edit';
        $data['content']='customers_pledges_dep_move';
        $result= $this->{$this->MODEL_NAME}->get($this->p_file_id);

        /* if(!(count($result)==1 ) or !HaveAccess('pledges/customers_pledges/index/1/2') and $result[0]['EMP_NO']!=$this->user->emp_no)
             die();*/
        $data['pledge_data']=$result[0];

        //var_dump($result[0]);
       $this->_look_ups($data);
        $this->load->view('customers_pledges_dep_move',$data);


    }
    function set_code(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id!=''){
            $res = $this->{$this->MODEL_NAME}->set_code($this->p_id);
            if(intval($res) <= 0){
                $this->print_error('لم يتم إتمام عملية التكويد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم العهدة";
    }

    // Mkilani
    function public_get_rooms($data_type=''){
        $res = $this->{$this->MODEL_NAME}->get_rooms();
        if($data_type=='array'){
            return $res;
        }else{
            $this->return_json($res);
        }

    }

}




