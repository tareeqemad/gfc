<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/07/15
 * Time: 11:02 ص
 */

class Technical_jobs extends MY_Controller{

    var $MODEL_NAME= 'technical_jobs_model';
    var $DETAILS_MODEL_1_WORKERS= 'technical_jobs_workers_model';
    var $DETAILS_MODEL_2_CARS= 'technical_jobs_cars_model';
    var $DETAILS_MODEL_3_TOOLS= 'technical_jobs_tools_model';
    var $DETAILS_MODEL_4_PLANE= 'technical_jobs_plane_model';
    var $PAGE_URL= 'technical/technical_jobs/get_page';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_1_WORKERS);
        $this->load->model($this->DETAILS_MODEL_2_CARS);
        $this->load->model($this->DETAILS_MODEL_3_TOOLS);
        $this->load->model($this->DETAILS_MODEL_4_PLANE);

        // vars
        $this->job_id= $this->input->post('job_id');
        $this->job_name= $this->input->post('job_name');
        $this->job_description= $this->input->post('job_description');
        $this->specified_time= $this->input->post('specified_time');
        $this->notes= $this->input->post('notes');
        $this->entry_user= $this->input->post('entry_user');

        // arrays
        $this->w_ser= $this->input->post('w_ser');
        $this->worker_job_id= $this->input->post('worker_job_id');
        $this->worker_count= $this->input->post('worker_count');
        $this->task= $this->input->post('task');

        $this->c_ser= $this->input->post('c_ser');
        $this->car_id= $this->input->post('car_id');
        $this->car_count= $this->input->post('car_count');
        $this->need_description= $this->input->post('need_description');

        $this->t_ser= $this->input->post('t_ser');
        $this->class_id= $this->input->post('class_id');
        $this->class_unit= $this->input->post('class_unit');
        $this->class_count= $this->input->post('class_count');

        $this->p_ser= $this->input->post('p_ser');
        $this->plane_step_ser = $this->input->post('plane_step_ser');
        $this->plane_step= $this->input->post('plane_step');
        $this->time_estimated = $this->input->post('time_estimated');
    }

    function index($page= 1, $job_id= -1, $job_name= -1, $job_description= -1, $notes= -1, $entry_user= -1){
        $data['title']='المهمات';
        $data['content']= 'technical_jobs_index';

        $data['entry_user_all'] = $this->get_entry_users('TECHNICAL_JOBS_TB');

        $data['page']= $page;
        $data['job_id']= $job_id;
        $data['job_name']= $job_name;
        $data['job_description']= $job_description;
        $data['notes']= $notes;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template',$data);
    }

    function get_page($page = 1, $job_id= -1, $job_name= -1, $job_description= -1, $notes= -1, $entry_user= -1){
        $this->load->library('pagination');

        $job_id= $this->check_vars($job_id,'job_id');
        $job_name= $this->check_vars($job_name,'job_name');
        $job_description= $this->check_vars($job_description,'job_description');
        $notes= $this->check_vars($notes,'notes');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";
        $where_sql.= ($job_id!= null)? " and job_id= '{$job_id}' " : '';
        $where_sql.= ($job_name!= null)? " and job_name like '".add_percent_sign($job_name)."' " : '';
        $where_sql.= ($job_description!= null)? " and job_description like '".add_percent_sign($job_description)."' " : '';
        $where_sql.= ($notes!= null)? " and notes like '".add_percent_sign($notes)."' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' technical_jobs_tb '.$where_sql);
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

        $this->load->view('technical_jobs_page',$data);
    }

    function public_index($text= null, $page= 1, $job_id= -1, $job_name= -1){
        $data['content']='technical_jobs_popup_i';
        $data['text']=$text;
        $data['page']=$page;
        $data['job_id']= $job_id;
        $data['job_name']= $job_name;
        $this->load->view('template/view',$data);
    }

    function public_get_page($text= null, $page= 1, $job_id= -1, $job_name= -1){

        $this->load->library('pagination');

        $job_id= $this->check_vars($job_id,'job_id');
        $job_name= $this->check_vars($job_name,'job_name');

        $where_sql= " where 1=1 ";
        $where_sql.= ($job_id!= null)? " and job_id= '{$job_id}' " : '';
        $where_sql.= ($job_name!= null)? " and job_name like '".add_percent_sign(urldecode($job_name))."' " : '';

        $config['base_url'] = base_url("technical/technical_jobs/public_index/$text");
        $count_rs = $this->get_table_count(' technical_jobs_tb '.$where_sql);
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

        $this->load->view('technical_jobs_popup_p',$data);
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
            $this->job_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->job_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->job_id);
            }else{

                for($i=0; $i<count($this->worker_job_id); $i++){
                    if($this->worker_job_id[$i]!='' and $this->worker_count[$i]!='' and $this->worker_count[$i]>0 and $this->task[$i]!=''){
                        $detail_seq= $this->{$this->DETAILS_MODEL_1_WORKERS}->create($this->_postedData_details_1(null, $this->worker_job_id[$i], $this->worker_count[$i], $this->task[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }

                for($i=0; $i<count($this->car_id); $i++){
                    if($this->car_id[$i]!='' and $this->car_count[$i]!='' and $this->car_count[$i]>0 and $this->need_description[$i]!=''){
                        $detail_seq= $this->{$this->DETAILS_MODEL_2_CARS}->create($this->_postedData_details_2(null, $this->car_id[$i], $this->car_count[$i], $this->need_description[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }

                for($i=0; $i<count($this->class_id); $i++){
                    if($this->class_id[$i]!='' and $this->class_count[$i]!='' and $this->class_count[$i]>0 ){
                        $detail_seq= $this->{$this->DETAILS_MODEL_3_TOOLS}->create($this->_postedData_details_3(null, $this->class_id[$i], $this->class_count[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }


                for($i=0; $i<count($this->plane_step_ser); $i++){
                    if($this->plane_step_ser[$i]>0 and $this->plane_step[$i]!='' and $this->time_estimated[$i]>0 ){
                        $detail_seq= $this->{$this->DETAILS_MODEL_4_PLANE}->create($this->_postedData_details_4(null, $this->plane_step_ser[$i], $this->plane_step[$i], $this->time_estimated[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->job_id);
            }

        }else{
            $data['content']='technical_jobs_show';
            $data['title']= 'ادخال مهمة';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->job_id=='' and $isEdit) or $this->job_name=='' or $this->job_description=='' or $this->specified_time=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }
/*
        if(!($this->worker_job_id) or count(array_filter($this->worker_job_id)) <= 0 or count(array_filter($this->worker_count)) <= 0 or count(array_filter($this->task)) <= 0 ){
            $this->print_error('يجب ادخال فريق المهام');
        }
*/
        for($i=0;$i<count($this->worker_count);$i++){
            if($this->worker_count[$i]!='' and $this->worker_job_id[$i]=='' )
                $this->print_error('اختر الوظيفة');
            elseif($this->worker_count[$i]!='' and $this->task[$i]=='' )
                $this->print_error('ادخل دوره في المهمة');
        }
/*
        if(!($this->car_id) or count(array_filter($this->car_id)) <= 0 or count(array_filter($this->car_count)) <= 0 or count(array_filter($this->need_description)) <= 0 ){
            $this->print_error('يجب ادخال الاليات');
        }
*/
        for($i=0;$i<count($this->car_count);$i++){
            if($this->car_count[$i]!='' and $this->car_id[$i]=='' )
                $this->print_error('اختر الالية');
            elseif($this->car_count[$i]!='' and $this->need_description[$i]=='' )
                $this->print_error('ادخل وصف الاحتياج');
        }
/*
        if(!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->class_count)) <= 0 ){
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        }
*/
        $all_class= array();
        for($i=0;$i<count($this->class_count);$i++){
            $all_class[]= $this->class_id[$i];
            if($this->class_count[$i]!='' and $this->class_id[$i]=='' )
                $this->print_error('اختر الصنف');
        }

        if( count(array_filter($all_class)) !=  count(array_count_values(array_filter($all_class))) ){
            $this->print_error('يوجد تكرار في الاصناف');
        }
/*
        if(!($this->plane_step_ser) or count(array_filter($this->plane_step_ser)) <= 0  ){
            $this->print_error('يجب ادخال مخطط المهام');
        }

        for($i=0;$i<count($this->plane_step_ser);$i++){
            if( $this->plane_step[$i]=='' )
                $this->print_error('أدخل الإجراء');
            elseif($this->time_estimated[$i]=='' or $this->time_estimated[$i]<=0 )
                $this->print_error('أدخل الوقت المحدد للتنفيذ بالدقائق');
        }
*/
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1 ))
            die('get');
        $data['job_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='technical_jobs_show';
        $data['title']='بيانات المهمة ';

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

                for($i=0; $i<count($this->worker_job_id); $i++){
                    if($this->w_ser[$i]== 0 and $this->worker_job_id[$i]!='' and $this->worker_count[$i]!='' and $this->worker_count[$i]>0 and $this->task[$i]!=''){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_1_WORKERS}->create($this->_postedData_details_1(null, $this->worker_job_id[$i], $this->worker_count[$i], $this->task[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->w_ser[$i]!= 0 and $this->worker_job_id[$i]!='' and $this->worker_count[$i]!='' and $this->worker_count[$i]>0 and $this->task[$i]!=''){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_1_WORKERS}->edit($this->_postedData_details_1($this->w_ser[$i], $this->worker_job_id[$i], $this->worker_count[$i], $this->task[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->w_ser[$i]!= 0 and ($this->worker_count[$i]=='' or $this->worker_count[$i]==0) ){ // delete
                        $detail_seq= $this->{$this->DETAILS_MODEL_1_WORKERS}->delete($this->w_ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }

                for($i=0; $i<count($this->car_id); $i++){
                    if($this->c_ser[$i]== 0 and $this->car_id[$i]!='' and $this->car_count[$i]!='' and $this->car_count[$i]>0 and $this->need_description[$i]!=''){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_2_CARS}->create($this->_postedData_details_2(null, $this->car_id[$i], $this->car_count[$i], $this->need_description[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->c_ser[$i]!= 0 and $this->car_id[$i]!='' and $this->car_count[$i]!='' and $this->car_count[$i]>0 and $this->need_description[$i]!=''){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_2_CARS}->edit($this->_postedData_details_2($this->c_ser[$i], $this->car_id[$i], $this->car_count[$i], $this->need_description[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->c_ser[$i]!= 0 and ($this->car_count[$i]=='' or $this->car_count[$i]==0) ){ // delete
                        $detail_seq= $this->{$this->DETAILS_MODEL_2_CARS}->delete($this->c_ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }

                for($i=0; $i<count($this->class_id); $i++){
                    if($this->t_ser[$i]== 0 and $this->class_id[$i]!='' and $this->class_count[$i]!='' and $this->class_count[$i]>0 ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_3_TOOLS}->create($this->_postedData_details_3(null, $this->class_id[$i], $this->class_count[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->t_ser[$i]!= 0 and $this->class_id[$i]!='' and $this->class_count[$i]!='' and $this->class_count[$i]>0 ){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_3_TOOLS}->edit($this->_postedData_details_3($this->t_ser[$i], $this->class_id[$i], $this->class_count[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->t_ser[$i]!= 0 and ($this->class_count[$i]=='' or $this->class_count[$i]==0) ){ // delete
                        $detail_seq= $this->{$this->DETAILS_MODEL_3_TOOLS}->delete($this->t_ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }

               for($i=0; $i<count($this->plane_step_ser); $i++){
                    if($this->p_ser[$i]== 0 and $this->plane_step[$i]!='' and $this->plane_step_ser[$i]>0 and $this->time_estimated[$i]>0 ){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_4_PLANE}->create($this->_postedData_details_4(null, $this->plane_step_ser[$i], $this->plane_step[$i], $this->time_estimated[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->p_ser[$i]!= 0 and $this->plane_step[$i]!='' and $this->plane_step_ser[$i]>0 and $this->time_estimated[$i]>0 ){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_4_PLANE}->edit($this->_postedData_details_4($this->p_ser[$i], $this->plane_step_ser[$i], $this->plane_step[$i], $this->time_estimated[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->p_ser[$i]!= 0 and ($this->plane_step_ser[$i]=='' or $this->plane_step_ser[$i]==0) ){ // delete
                        $detail_seq= $this->{$this->DETAILS_MODEL_4_PLANE}->delete($this->p_ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }

                echo 1;
            }
        }
    }

    function public_get_details_1($id= 0, $adopt= 0){
        $data['adopt'] = $adopt;
        $data['details_1'] = $this->{$this->DETAILS_MODEL_1_WORKERS}->get_list($id);
        $this->load->view('technical_jobs_det_workers',$data);
    }

    function public_get_details_2($id= 0, $adopt= 0){
        $data['adopt'] = $adopt;
        $data['details_2'] = $this->{$this->DETAILS_MODEL_2_CARS}->get_list($id);
        $this->load->view('technical_jobs_det_cars',$data);
    }

    function public_get_details_3($id= 0, $adopt= 0){
        $data['adopt'] = $adopt;
        $data['details_3'] = $this->{$this->DETAILS_MODEL_3_TOOLS}->get_list($id);
        $this->load->view('technical_jobs_det_tools',$data);
    }
    function public_get_details_4($id= 0, $adopt= 0){
        $data['adopt'] = $adopt;
        $data['details_4'] = $this->{$this->DETAILS_MODEL_4_PLANE}->get_list($id);
        $this->load->view('technical_jobs_det_plane',$data);
    }

    function public_get_all_details(){
        $id = $this->input->post('id');
        $details_1 = $this->{$this->DETAILS_MODEL_1_WORKERS}->get_list($id);
        $details_2 = $this->{$this->DETAILS_MODEL_2_CARS}->get_list($id);
        $details_3 = $this->{$this->DETAILS_MODEL_3_TOOLS}->get_list($id);
        $details_4 = $this->{$this->DETAILS_MODEL_4_PLANE}->get_list($id);
        $all= array('WORKERS'=>$details_1, 'CARS'=>$details_2, 'TOOLS'=>$details_3, 'PLANE'=>$details_4);
        $this->return_json($all);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $data['worker_jobs'] = json_encode($this->constant_details_model->get_list(86));
        $data['cars'] = json_encode($this->constant_details_model->get_list(87));
        $data['help']=$this->help;
        add_js('jquery.hotkeys.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->job_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'JOB_ID','value'=>$this->job_id ,'type'=>'','length'=>-1),
            array('name'=>'JOB_NAME','value'=>$this->job_name ,'type'=>'','length'=>-1),
            array('name'=>'JOB_DESCRIPTION','value'=>$this->job_description ,'type'=>'','length'=>-1),
            array('name'=>'SPECIFIED_TIME','value'=>$this->specified_time ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

    function _postedData_details_1($w_ser= null, $worker_job_id, $worker_count, $task, $typ= null){
        $result = array(
            array('name'=>'W_SER','value'=>$w_ser ,'type'=>'','length'=>-1),
            array('name'=>'JOB_ID','value'=>$this->job_id ,'type'=>'','length'=>-1),
            array('name'=>'WORKER_JOB_ID','value'=>$worker_job_id ,'type'=>'','length'=>-1),
            array('name'=>'WORKER_COUNT','value'=>$worker_count ,'type'=>'','length'=>-1),
            array('name'=>'TASK','value'=>$task ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

    function _postedData_details_2($c_ser= null, $car_id, $car_count, $need_description, $typ= null){
        $result = array(
            array('name'=>'C_SER','value'=>$c_ser ,'type'=>'','length'=>-1),
            array('name'=>'JOB_ID','value'=>$this->job_id ,'type'=>'','length'=>-1),
            array('name'=>'CAR_ID','value'=>$car_id ,'type'=>'','length'=>-1),
            array('name'=>'CAR_COUNT','value'=>$car_count ,'type'=>'','length'=>-1),
            array('name'=>'NEED_DESCRIPTION','value'=>$need_description ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

    function _postedData_details_3($t_ser= null, $class_id, $class_count, $typ= null){
    $result = array(
        array('name'=>'T_SER','value'=>$t_ser ,'type'=>'','length'=>-1),
        array('name'=>'JOB_ID','value'=>$this->job_id ,'type'=>'','length'=>-1),
        array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
        array('name'=>'CLASS_COUNT','value'=>$class_count ,'type'=>'','length'=>-1),
    );
    if($typ=='create')
        unset($result[0]);
    return $result;
}
    function _postedData_details_4($p_ser= null, $plane_step_ser, $plane_step, $time_estimated, $typ= null){
        $result = array(
            array('name'=>'P_SER','value'=>$p_ser ,'type'=>'','length'=>-1),
            array('name'=>'JOB_ID','value'=>$this->job_id ,'type'=>'','length'=>-1),
            array('name'=>'PLANE_STEP_SER','value'=>$plane_step_ser ,'type'=>'','length'=>-1),
            array('name'=>'PLANE_STEP','value'=>$plane_step ,'type'=>'','length'=>-1),
            array('name'=>'TIME_ESTIMATED','value'=>$time_estimated ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }
    function delete_details_plane()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->DETAILS_MODEL_4_PLANE}->delete_plane($this->p_id);

            echo intval($res);
        } else
            echo "لم يتم ارسال رقم الإجراء";
    }
}