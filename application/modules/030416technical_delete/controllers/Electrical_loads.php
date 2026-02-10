<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/03/16
 * Time: 10:04 ص
 */

class Electrical_loads extends MY_Controller{

    var $MODEL_NAME= 'electrical_loads_model';
    var $DETAILS_MODEL_NAME= 'electrical_loads_det_model';
    var $PAGE_URL= 'technical/electrical_loads/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);

        $this->eload_id= $this->input->post('eload_id');
        $this->branch= $this->input->post('branch');
        $this->eload_type= $this->input->post('eload_type');
        $this->adopt= $this->input->post('adopt');
        $this->eload_date= $this->input->post('eload_date');
        $this->note= $this->input->post('note');
        $this->entry_user= $this->input->post('entry_user');

        // arrays
        $this->ser= $this->input->post('ser');
        $this->adapter_serial= $this->input->post('adapter_serial');
        $this->from_hour= $this->input->post('from_hour');
        $this->to_hour= $this->input->post('to_hour');
        $this->notes= $this->input->post('notes');
    }

    function index($page= 1, $eload_type= -1, $eload_id= -1, $branch= -1, $eload_date= -1, $note= -1, $adopt= -1, $entry_user= -1){
        $index_url= base_url("technical/electrical_loads/index/1");
        if(HaveAccess($index_url."/$eload_type")){
            if($eload_type==1){
                $data['title']= 'التسويات على الاحمال - احمال اضافية';
            }elseif($eload_type==2){
                $data['title']= 'التسويات على الاحمال - عجز احمال';
            }elseif($eload_type==3){
                $data['title']= 'التسويات على الاحمال - فصل خطوط';
            }else{
                die('eload_type');
            }
        }else{
            die('NoAccess');
        }

        $data['content']='electrical_loads_index';
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branch_all']= $this->gcc_branches_model->get_all();
        $data['eload_type_all'] = $this->constant_details_model->get_list(109);
        $data['adopt_all'] = $this->constant_details_model->get_list(110);
        $data['entry_user_all'] = $this->get_entry_users('ELECTRICAL_LOADS_TB');
        //add_css('select2_metro_rtl.css');
        //add_js('select2.min.js');

        $data['page']=$page;
        $data['eload_id']= $eload_id;
        $data['branch']= $branch;
        $data['eload_type']= $eload_type;
        $data['eload_date']= $eload_date;
        $data['note']= $note;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $eload_type= -1, $eload_id= -1, $branch= -1, $eload_date= -1, $note= -1, $adopt= -1, $entry_user= -1){

        $this->load->library('pagination');

        $eload_id= $this->check_vars($eload_id,'eload_id');
        $branch= $this->check_vars($branch,'branch');
        $eload_type= $this->check_vars($eload_type,'eload_type');
        $eload_date= $this->check_vars($eload_date,'eload_date');
        $note= $this->check_vars($note,'note');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= ' where 1=1 ';
        $where_sql.= ($eload_id!= null)? " and eload_id= '{$eload_id}' " : '';
        $where_sql.= ($branch!= null)? " and branch= '{$branch}' " : '';
        $where_sql.= ($eload_type!= null)? " and eload_type= '{$eload_type}' " : '';
        $where_sql.= ($eload_date!= null)? " and eload_date= '{$eload_date}' " : '';
        $where_sql.= ($note!= null)? " and note like '".add_percent_sign($note)."' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        if($eload_type!=1 and $eload_type!=2 and $eload_type!=3)
            die('eload_type');

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' electrical_loads_tb '.$where_sql);
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

        $this->load->view('electrical_loads_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= (isset($this->{$c_var}) and $this->{$c_var}!=null)? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create($eload_type=0){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->eload_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->eload_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->eload_id);
            }else{
                for($i=0; $i<count($this->adapter_serial); $i++){
                    if($this->adapter_serial[$i]!='' and $this->from_hour[$i]!='' and $this->to_hour[$i]!=''){
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->adapter_serial[$i], $this->from_hour[$i], $this->to_hour[$i], $this->notes[$i], 'create', ($this->eload_type==3)?1:0));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->eload_id);
            }

        }else{
            if($eload_type!=1 and $eload_type!=2 and $eload_type!=3)
                die('eload_type');
            $data['content']='electrical_loads_show';
            $data['title']= 'ادخال تسويات احمال';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['eload_type'] = $eload_type;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->eload_id=='' and $isEdit) or $this->eload_type=='' or $this->eload_date=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }else if(!($this->adapter_serial) or count(array_filter($this->adapter_serial)) <= 0 or count(array_filter($this->from_hour)) <= 0 ){
            $this->print_error('يجب ادخال محول واحد على الاقل ');
        }else if (1){
            for($i=0;$i<count($this->adapter_serial);$i++){
                if($this->adapter_serial[$i]!='' and ($this->from_hour[$i]=='' or !$this->check_time($this->from_hour[$i])) )
                    $this->print_error('ادخل وقت بداية صحيح #:'.($i+1));
                elseif($this->adapter_serial[$i]!='' and ($this->to_hour[$i]=='' or !$this->check_time($this->to_hour[$i])) )
                    $this->print_error('ادخل وقت نهاية صحيح #:'.($i+1));
                elseif($this->adapter_serial[$i]!='' and intval(str_replace(':','',$this->from_hour[$i])) >= intval(str_replace(':','',$this->to_hour[$i])) )
                    $this->print_error('ادخل فترة صحيحة #:'.($i+1));
            }
        }

        if( count(array_filter($this->adapter_serial)) !=  count(array_count_values(array_filter($this->adapter_serial))) ){
            $this->print_error('يوجد تكرار في المحولات');
        }
    }

    function check_time($time){ // 21:30
        return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1 and ( $result[0]['BRANCH']== $this->user->branch  ) ))
            die();
        $data['eload_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='electrical_loads_show';
        $data['isCreate']= true;
        $data['title']='بيانات سند التسوية';
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
                    if($this->ser[$i]== 0  and $this->adapter_serial[$i]!='' and $this->from_hour[$i]!='' and $this->to_hour[$i]!='' ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->adapter_serial[$i], $this->from_hour[$i], $this->to_hour[$i], $this->notes[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0  and $this->adapter_serial[$i]!='' and $this->from_hour[$i]!='' and $this->to_hour[$i]!='' ){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser[$i], $this->adapter_serial[$i], $this->from_hour[$i], $this->to_hour[$i], $this->notes[$i], 'edit'));
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

    function adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->eload_id!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->eload_id,2);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم سند ";
    }

    function public_get_details($id = 0, $adopt= 0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $data['adopt'] = $adopt;
        $this->load->view('electrical_loads_details',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['eload_types'] = $this->constant_details_model->get_list(109);
        $data['adopts'] = $this->constant_details_model->get_list(110);
        $data['help']=$this->help;

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->eload_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'ELOAD_ID','value'=>$this->eload_id ,'type'=>'','length'=>-1),
            //array('name'=>'BRANCH','value'=>$this->branch ,'type'=>'','length'=>-1),
            array('name'=>'ELOAD_TYPE','value'=>$this->eload_type ,'type'=>'','length'=>-1),
            array('name'=>'ELOAD_DATE','value'=>$this->eload_date ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return($result);
    }

    function _postedData_details($ser= null, $adapter_serial, $from_hour= null, $to_hour= null, $notes= null, $typ= null, $group=0){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'ELOAD_ID','value'=>$this->eload_id ,'type'=>'','length'=>-1),
            array('name'=>'ADAPTER_SERIAL','value'=>$adapter_serial ,'type'=>'','length'=>-1),
            array('name'=>'FROM_HOUR','value'=>$from_hour ,'type'=>'','length'=>-1),
            array('name'=>'TO_HOUR','value'=>$to_hour ,'type'=>'','length'=>-1),
            array('name'=>'notes','value'=>$notes ,'type'=>'','length'=>-1),
            array('name'=>'group','value'=>$group ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[1],$result[6]);
        return $result;
    }

}