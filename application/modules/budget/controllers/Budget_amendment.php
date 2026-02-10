<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 31/05/15
 * Time: 10:12 ص
 */

class Budget_amendment extends MY_Controller{

    var $MODEL_NAME= 'budget_amendment_model';
    var $DETAILS_MODEL_NAME= 'budget_amendment_det_model';
    var $PAGE_URL= 'budget/budget_amendment_model/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);

        // amendment_id,branch,amendment_type,adopt,entry_user
        $this->amendment_id= $this->input->post('amendment_id');
        //$this->branch= $this->input->post('branch');
        //$this->to_branch= $this->input->post('to_branch');
        $this->year= $this->budget_year;
        $this->note= $this->input->post('note');
        $this->amendment_type= $this->input->post('amendment_type');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        $this->adopt_note= $this->input->post('adopt_note');
        $this->section= $this->input->post('section');
        // arrays
        $this->ser= $this->input->post('ser');
        $this->section_add= $this->input->post('section_add');
        $this->amount_add= $this->input->post('amount_add');
        $this->section_remove= $this->input->post('section_remove');
        $this->amount_remove= $this->input->post('amount_remove');

        $this->from_branch= $this->input->post('from_branch'); // remove
        $this->to_branch= $this->input->post('to_branch'); // add
    }

    function index($page= 1, $amendment_id= -1, $branch= -1, $to_branch= -1, $amendment_type= -1, $adopt= -1, $entry_user= -1,$section=-1,$note=-1 ){
        $data['title']='تعديل مخصصات';
        $data['content']='budget_amendment_index';

        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $data['branch_all']= $this->gcc_branches_model->get_all();
        $data['amendment_type_all'] = $this->constant_details_model->get_list(83);
        $data['entry_user_all'] = $this->get_entry_users('BUDGET_AMENDMENT_TB');
        $data['adopt_all'] = $this->constant_details_model->get_list(84);
        $data['year'] = $this->year;

        $data['page']=$page;
        $data['amendment_id']= $amendment_id;
        $data['branch']= $branch;
        $data['to_branch']= $to_branch;
        $data['amendment_type']= $amendment_type;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;
        $data['section']= $section;
        $data['note']= $note;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $amendment_id= -1, $branch= -1, $to_branch= -1, $amendment_type= -1, $adopt= -1, $entry_user= -1 ,$section=-1,$note=-1){
        $this->load->library('pagination');

        $amendment_id= $this->check_vars($amendment_id,'amendment_id');
        //$branch= $this->check_vars($branch,'branch');
        //$to_branch= $this->check_vars($to_branch,'to_branch');
        $amendment_type= $this->check_vars($amendment_type,'amendment_type');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');
        $section= $this->check_vars($section,'section');
      //  echo "dddd".$section."ddd";
        $note= $this->check_vars($note,'note');

        $where_sql= ' where 1=1 ';

        $where_sql.= ($amendment_id!= null)? " and amendment_id= '{$amendment_id}' " : '';
        //$where_sql.= ($branch!= null)? " and branch= '{$branch}' " : '';
        //$where_sql.= ($to_branch!= null)? " and to_branch= '{$to_branch}' " : '';
        $where_sql.= ($amendment_type!= null)? " and amendment_type= '{$amendment_type}' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';
        $where_sql.= ($section!= null)? " and AMENDMENT_ID in (SELECT amendment_id from BUDGET_AMENDMENT_DET_TB where nvl(section_add,0)={$section} or nvl(section_remove,0)={$section} ) " : '';
        $where_sql.= ($note!= null)? " and note like '%{$note}%' " : '';

       // echo $where_sql ;

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' budget_amendment_tb '.$where_sql);
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

        $this->load->view('budget_amendment_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= $this->{$c_var}? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->amendment_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->amendment_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->amendment_id);
            }else{
                if($this->amendment_type==1){
                    for($i=0; $i<count($this->section_add); $i++){
                        if($this->section_add[$i]!='' and $this->amount_add[$i]!='' and $this->amount_add[$i]>0 and $this->to_branch[$i]!='' ){
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->section_add[$i], null, $this->amount_add[$i], null, null, $this->to_branch[$i], 'create'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error_del($detail_seq);
                            }
                        }
                    }
                }elseif($this->amendment_type==2){
                    for($i=0; $i<count($this->section_remove); $i++){
                        if($this->section_remove[$i]!='' and $this->amount_remove[$i]!='' and $this->amount_remove[$i]>0 and $this->from_branch[$i]!=''){
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, null, $this->section_remove[$i], null, $this->amount_remove[$i], $this->from_branch[$i], null, 'create'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error_del($detail_seq);
                            }
                        }
                    }
                }elseif($this->amendment_type==3){
                    for($i=0; $i<count($this->section_add); $i++){
                        if($this->section_add[$i]!='' and $this->section_remove[$i]!='' and $this->amount_add[$i]!='' and $this->amount_add[$i]>0 and $this->amount_remove[$i]!='' and $this->amount_remove[$i]>0 and $this->from_branch[$i]!='' and $this->to_branch[$i]!=''){
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->section_add[$i], $this->section_remove[$i], $this->amount_add[$i], $this->amount_remove[$i], $this->from_branch[$i], $this->to_branch[$i], 'create'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error_del($detail_seq);
                            }
                        }
                    }
                }

                echo intval($this->amendment_id);
            }

        }else{
            $data['content']='budget_amendment_show';
            $data['title']= 'تعديل مخصصات';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->amendment_id=='' and $isEdit) or $this->amendment_type=='' or $this->year=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }else if($this->amendment_type==1 and (count(array_filter($this->section_add)) <= 0 or count(array_filter($this->amount_add)) <= 0) ){
            $this->print_error('يجب ادخال فصل واحد على الاقل ');
        }else if($this->amendment_type==2 and (count(array_filter($this->section_remove)) <= 0 or count(array_filter($this->amount_remove)) <= 0) ){
            $this->print_error('يجب ادخال فصل واحد على الاقل ');
        }else if($this->amendment_type==3 and (count(array_filter($this->section_add)) <= 0 or count(array_filter($this->amount_add)) <= 0 or count(array_filter($this->section_remove)) <= 0 or count(array_filter($this->amount_remove)) <= 0) ){
            $this->print_error('يجب ادخال فصل واحد على الاقل ');
        }
        else if (1){
            if($this->amendment_type==1){
                for($i=0;$i<count($this->amount_add);$i++){
                    if($this->amount_add[$i]!='' and $this->section_add[$i]=='' )
                        $this->print_error('اختر الفصل');
                    elseif($this->amount_add[$i]!='' and $this->to_branch[$i]=='' )
                        $this->print_error('اختر الفرع');
                }
            }elseif($this->amendment_type==2){
                for($i=0;$i<count($this->amount_remove);$i++){
                    if($this->amount_remove[$i]!='' and $this->section_remove[$i]=='' )
                        $this->print_error('اختر الفصل');
                    elseif($this->amount_remove[$i]!='' and $this->from_branch[$i]=='' )
                        $this->print_error('اختر الفرع');
                }
            }elseif($this->amendment_type==3){
                for($i=0;$i<count($this->amount_add);$i++){
                    if($this->amount_add[$i]!='' and $this->section_add[$i]=='' )
                        $this->print_error('اختر فصل الالحاق');
                    elseif($this->amount_add[$i]!='' and $this->to_branch[$i]=='' )
                        $this->print_error('اختر فرع الالحاق');
                    elseif($this->amount_remove[$i]!='' and $this->section_remove[$i]=='' )
                        $this->print_error('اختر فصل التخفيض');
                    elseif($this->amount_remove[$i]!='' and $this->from_branch[$i]=='' )
                        $this->print_error('اختر فرع التخفيض');
                    elseif($this->amount_add[$i]!='' and $this->amount_remove[$i]=='' )
                        $this->print_error('ادخل قيمة التخفيض');
                    elseif($this->amount_remove[$i]!='' and $this->amount_add[$i]=='' )
                        $this->print_error('ادخل قيمة الالحاق');
                    elseif($this->amount_add[$i] != $this->amount_remove[$i])
                        $this->print_error('يجب ان يكون المبلغ المضاف مساوي لمبلغ التخفيض');
                }
            }
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die();
        $data['amendment_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['next_adopt_email'] = $this->get_emails_by_code('11.' . ($result[0]['ADOPT']+2));
        $data['action'] = $action;
        $data['content']='budget_amendment_show';
        $data['isCreate']= true;
        $data['title']='بيانات سند تعديلات الموازنة ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData('edit'));
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                if($this->amendment_type==1){
                    for($i=0; $i<count($this->section_add); $i++){
                        if($this->ser[$i]== 0 and $this->section_add[$i]!='' and $this->amount_add[$i]!='' and $this->amount_add[$i]>0 and $this->to_branch[$i]!=''){ // create
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->section_add[$i], null, $this->amount_add[$i], null, null, $this->to_branch[$i], 'create'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }elseif($this->ser[$i]!= 0 and $this->section_add[$i]!='' and $this->amount_add[$i]!='' and $this->amount_add[$i]>0 and $this->to_branch[$i]!=''){ // edit
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->section_add[$i], null, $this->amount_add[$i], null, null, $this->to_branch[$i], 'edit'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }elseif($this->ser[$i]!= 0 and ($this->amount_add[$i]=='' or $this->amount_add[$i]==0) ){ // delete
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser[$i]);
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }
                    }
                }elseif($this->amendment_type==2){
                    for($i=0; $i<count($this->section_remove); $i++){
                        if($this->ser[$i]== 0 and $this->section_remove[$i]!='' and $this->amount_remove[$i]!='' and $this->amount_remove[$i]>0 and $this->from_branch[$i]!='' ){ // create
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, null, $this->section_remove[$i], null, $this->amount_remove[$i], $this->from_branch[$i], null, 'create'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }elseif($this->ser[$i]!= 0 and $this->section_remove[$i]!='' and $this->amount_remove[$i]!='' and $this->amount_remove[$i]>0 and $this->from_branch[$i]!='' ){ // edit
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], null, $this->section_remove[$i], null, $this->amount_remove[$i], $this->from_branch[$i], null, 'edit'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }elseif($this->ser[$i]!= 0 and ($this->amount_remove[$i]=='' or $this->amount_remove[$i]==0) ){ // delete
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser[$i]);
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }
                    }
                }elseif($this->amendment_type==3){
                    for($i=0; $i<count($this->section_add); $i++){
                        if($this->ser[$i]== 0 and $this->section_add[$i]!='' and $this->section_remove[$i]!='' and $this->amount_add[$i]!='' and $this->amount_add[$i]>0 and $this->amount_remove[$i]!='' and $this->amount_remove[$i]>0 and $this->from_branch[$i]!='' and $this->to_branch[$i]!='' ){ // create
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->section_add[$i], $this->section_remove[$i], $this->amount_add[$i], $this->amount_remove[$i], $this->from_branch[$i], $this->to_branch[$i], 'create'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }elseif($this->ser[$i]!= 0 and $this->section_add[$i]!='' and $this->section_remove[$i]!='' and $this->amount_add[$i]!='' and $this->amount_add[$i]>0 and $this->amount_remove[$i]!='' and $this->amount_remove[$i]>0 and $this->from_branch[$i]!='' and $this->to_branch[$i]!=''){ // edit
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->section_add[$i], $this->section_remove[$i], $this->amount_add[$i], $this->amount_remove[$i], $this->from_branch[$i], $this->to_branch[$i], 'edit'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }elseif($this->ser[$i]!= 0 and ($this->amount_add[$i]=='' or $this->amount_add[$i]==0 or $this->amount_remove[$i]=='' or $this->amount_remove[$i]==0) ){ // delete
                            $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser[$i]);
                            if(intval($detail_seq) <= 0){
                                $this->print_error($detail_seq);
                            }
                        }
                    }
                }
                echo 1;
            }
        }
    }
    /***********اعتماد المدخل*/
    function adopt_1(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->amendment_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->amendment_id, 1, '');

            if(intval($res) <= 0){
                $this->print_error('لم يتم الغاء الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }
    /*****اعتماد الموازنة**********/
    function adopt_2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->amendment_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->amendment_id, 2, $this->adopt_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }
    /******اعتماد الادارة المالية**/
    function adopt_3(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->amendment_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->amendment_id, 3, $this->adopt_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }
    /**اعتماد الرقابة الداخلية ***/
    function adopt_4(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->amendment_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->amendment_id, 4, $this->adopt_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }


    /**اعتماد المدير العام***/
    function adopt_5(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->amendment_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->amendment_id, 5, $this->adopt_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }


    /**اعتماد  رئيس هيئة المديرين***/
    function adopt_6(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->amendment_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->amendment_id, 6, $this->adopt_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('budget_section_model');
        //$data['branches']= $this->gcc_branches_model->get_all();
        $data['branches']= json_encode($this->gcc_branches_model->get_all());
        $data['amendment_types'] = $this->constant_details_model->get_list(83);
        $data['adopts'] = $this->constant_details_model->get_list(84);
        $data['sections'] = json_encode($this->budget_section_model->get_all());
        $data['sectionss'] = $this->budget_section_model->get_all();
        $data['year'] = $this->year;
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');
        add_js('moment.js');
    }

    function public_get_details($id = 0, $adopt= 0, $amendment_type=null){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $adopt = $this->input->post('adopt') ? $this->input->post('adopt') : $adopt;
        $amendment_type = $this->input->post('amendment_type') ? $this->input->post('amendment_type') : $amendment_type;
        if($amendment_type==null or $amendment_type<=0 or $amendment_type>3)
            die('اختر نوع السند لعرض سجلات الفصول');
        $data['amendment_details']= $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $this->load->model('settings/gcc_branches_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['adopt']= $adopt;
        $data['amendment_type']= $amendment_type;
        $this->load->view('budget_amendment_details',$data);
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->amendment_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'AMENDMENT_ID','value'=>$this->amendment_id ,'type'=>'','length'=>-1),
            array('name'=>'YEAR','value'=>$this->year ,'type'=>'','length'=>-1),
            array('name'=>'AMENDMENT_TYPE','value'=>$this->amendment_type ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[1],$result[2]);
        return($result);
    }

    function _postedData_details($ser= null, $section_add, $section_remove, $amount_add, $amount_remove, $from_branch, $to_branch, $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'AMENDMENT_ID','value'=>$this->amendment_id ,'type'=>'','length'=>-1),
            array('name'=>'SECTION_ADD','value'=>$section_add ,'type'=>'','length'=>-1),
            array('name'=>'SECTION_REMOVE','value'=>$section_remove ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT_ADD','value'=>$amount_add ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT_REMOVE','value'=>$amount_remove ,'type'=>'','length'=>-1),
            array('name'=>'FROM_BRANCH','value'=>$from_branch ,'type'=>'','length'=>-1),
            array('name'=>'TO_BRANCH','value'=>$to_branch ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[1]);
        return $result;
    }

}
