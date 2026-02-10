<?php
//if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 04/03/21
 * Time: 11:04 ص
 */
class customers_room_move extends MY_Controller
{


    var $MODEL_NAME = 'customers_room_move_model';
    var $PAGE_URL = 'pledges/customers_room_move/get_page';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->ser = $this->input->post('ser');
        $this->employee_id = $this->input->post('employee_id');
        $this->from_room_id = $this->input->post('from_room_id');
        $this->to_room_id = $this->input->post('to_room_id');
        $this->notes = $this->input->post('notes');
        $this->adopt = $this->input->post('adopt');
        $this->employee_no = $this->input->post('employee_no');

        $this->fdate = $this->input->post('fdate');
        $this->tdate = $this->input->post('tdate');
        $this->branch=$this->input->post('branch');
    }
        function index($page= 1){
            $this->load->model('settings/gcc_branches_model');
            $data['title']=' نقل موظفين من غرفة لأخرى ';
            $data['page']=$page;
            $data['ser']= $this->ser;
            $data['content']='customers_room_move_index';
            $data['help'] = $this->help;
            $data['action'] = 'edit';
            $this->_look_ups($data);
            $data['branches'] = $this->gcc_branches_model->get_all();
            $this->load->view('template/template',$data);

        }
        function get_page($page= 1){ //echo ($this->notes != '') ?  $this->notes :'10';die;
            $this->load->library('pagination');
            $where_sql = ' ';
            $where_sql .= ($this->ser != 0) ? " and ser = {$this->ser} " : '';
            $where_sql .= ($this->employee_id != 0) ? " and employee_id = {$this->employee_id} " : '';
            $where_sql .= ($this->from_room_id != 0) ? " and from_room_id = {$this->from_room_id} " : '';
            $where_sql .= ($this->to_room_id != 0) ? " and to_room_id = {$this->to_room_id} " : '';
            $where_sql .= ($this->notes != '') ? " and notes like '".add_percent_sign($this->notes)."' " : '';
            $where_sql .= ($this->adopt != 0) ? " and adopt = {$this->adopt} " : '';
            $where_sql .= ($this->employee_no != 0) ? " and employee_no = {$this->employee_no} " : '';
            $where_sql.= ($this->fdate!= null)? "  AND TRUNC( entry_date) >=to_date('{$this->fdate}','DD/MM/YYYY') "  : '';
            $where_sql.= ($this->tdate!= null)? "  AND TRUNC( entry_date) <=to_date('{$this->tdate}','DD/MM/YYYY') " : '';
            $where_sql.= ($this->branch!= null)? " and INVENTORY_PLEDGES_PKG.GET_BRANCH_PLEDGE(1,employee_id)={$this->branch} " : '';

           // echo $where_sql."kkkkk";

            $config['base_url'] = base_url($this->PAGE_URL);
            $count_rs = $this->get_table_count(' CUSTOMERS_ROOM_MOVE_TB  WHERE 1=1 '.$where_sql);
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


            $this->load->view('customers_room_move_page', $data);
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
            $this->load->model('pledges/customers_pledges_model');

            $data['customer_ids']=$this->employees_model->get_all();
            $data['rooms_cons']= $this->customers_pledges_model->get_rooms();//$this->public_get_rooms('array');
            $data['adopts']= $this->constant_details_model->get_list(376);

        }

    function create(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();

            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            /////////////////////
            if(intval($this->ser) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->ser);
            }else{

                echo intval($this->ser);
            }
            ///////////////////////
        }else{
            $data['content']='customers_room_move_show';
            $data['title']=' نقل موظف من غرفة لأخرى ';

            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }
    function _post_validation($isEdit = false){
        if( ($this->ser=='' and $isEdit) or $this->employee_id=='' or $this->from_room_id=='' or $this->to_room_id==''){
            $this->print_error('يجب ادخال جميع البيانات');
        }
 }

    function get($id){

            $data['title']='بيانات نقل موظف من غرفة لأخرى';

        $result= $this->{$this->MODEL_NAME}->get($id);

        if(!(count($result)==1  ))
            die();

        $data['orders_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] )?true : false : false;
        $data['action'] = 'edit';
        $data['isCreate'] = false;

        $data['content']='customers_room_move_show';


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
                echo 1;
            }
        }
    }
    function adopt20(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt( $id,20);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function cancel_adopt20(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt( $id,10);
        if(intval($res) <= 0){
            $this->print_error('لم يتم إلغاء الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }


    function adopt30(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt( $id,30);
      //  echo $res;
       // exit;
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }else {
        echo 1;}

    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMPLOYEE_ID','value'=>$this->employee_id ,'type'=>'','length'=>-1),
            array('name'=>'FROM_ROOM_ID','value'=>$this->from_room_id ,'type'=>'','length'=>-1),
            array('name'=>'TO_ROOM_ID','value'=>$this->to_room_id ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
            array('name'=>'EMPLOYEE_NO','value'=>$this->employee_no ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);


        return $result;
    }

}
