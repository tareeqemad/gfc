<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 07/05/23
 * Time: 09:00 ص
 */
class Smart_meter_barcodes extends MY_Controller {

    var $MODEL_NAME= 'Smart_meter_barcodes_model';
    var $PAGE_URL= 'stores/Smart_meter_barcodes/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('hr_attendance/hr_attendance_model');

        $this->ser = $this->input->post('ser');
        $this->branch_no = $this->input->post('branch_no');
        $this->receiving_party = $this->input->post('receiving_party');
        $this->class_output_id = $this->input->post('class_output_id');
        $this->ser_d = $this->input->post('ser_d');
        $this->barcode = $this->input->post('barcode');

    }

    function index()
    {
        $data['content']='smart_meter_barcodes_index';
        $data['title']='باركودات العدادات الذكية';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= "where 1 = 1";

        $where_sql.= ($this->ser!= null)? " AND M.SER= '{$this->ser}' " : '';
        $where_sql.= ($this->branch_no!= null)? " AND M.BRANCH_NO= '{$this->branch_no}' " : '';
        $where_sql.= ($this->receiving_party!= null)? " AND M.RECEIVING_PARTY= '{$this->receiving_party}' " : '';
        $where_sql.= ($this->class_output_id!= null)? " AND M.CLASS_OUTPUT_ID= '{$this->class_output_id}' " : '';
        $where_sql.= ($this->barcode!= null)? " AND M.BARCODE= '{$this->barcode}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('SMART_METER_BARCODES_TB M '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('smart_meter_barcodes_page',$data);
    }

    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
            $this->_post_validation(true);

            $items = array();
            for ($x = 0; $x < count($this->barcode); $x++) {
                $items[] = $this->barcode[$x];
            }

            $array = $items;
            $counts = array_count_values($array);
            foreach ($counts as $key => $count) {
                if ($count > 1) {
                    echo $this->print_error('هناك باركودات مكررة ، الرجاء التأكد من البيانات ');
                }
            }

            for($i=0; $i<count($this->barcode); $i++){
                if( $this->ser_d[$i]== 0 and $this->barcode[$i]!='' ){ // create
                    $req_seq = $this->{$this->MODEL_NAME}->create($this->_postedData($this->ser ,$this->branch_no ,$this->receiving_party ,$this->class_output_id ,null, $this->barcode[$i] ,'create'));
                    if(intval($req_seq) <= 0){
                        $this->print_error($req_seq);
                    }
                }
            }
            echo intval($req_seq);

        }else{
            $data['content']='smart_meter_barcodes_show';
            $data['title']='ادخال باركودات العدادات الذكية';
            $data['action'] = 'index';
            $data['isCreate']= true;
            $this->_look_ups($data);
            $this->load->view('template/template1',$data);
        }
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);

            $items = array();
            for ($x = 0; $x < count($this->barcode); $x++) {
                $items[] = $this->barcode[$x];
            }

            $array = $items;
            $counts = array_count_values($array);
            foreach ($counts as $key => $count) {
                if ($count > 1) {
                    echo $this->print_error('هناك باركودات مكررة ، الرجاء التأكد من البيانات ');
                }
            }

            for ($i = 0; $i < count($this->barcode); $i++) {
                if ( $this->ser_d[$i]== 0 and $this->barcode[$i]!='' ) { // create
                    $req_seq = $this->{$this->MODEL_NAME}->create($this->_postedData($this->ser  ,$this->branch_no ,$this->receiving_party ,$this->class_output_id ,null, $this->barcode[$i] ,'create'));
                    if (intval($req_seq) <= 0) {
                        $this->print_error($req_seq);
                    }
                } elseif ($this->ser_d[$i] != 0 and $this->barcode[$i]!='') { // edit
                    $req_seq = $this->{$this->MODEL_NAME}->edit($this->_postedData($this->ser ,$this->branch_no ,$this->receiving_party ,$this->class_output_id ,$this->ser_d[$i] ,$this->barcode[$i] ,'edit'));
                    if (intval($req_seq) <= 0) {
                        $this->print_error($req_seq);
                    }
                }
            }
            echo intval($req_seq);

        }
    }

    function get( $id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if((count($result)==0))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['content']='smart_meter_barcodes_show';
        $data['title']='بيانات باركودات العدادات الذكية';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function _look_ups(&$data){

        $this->load->model('hr_attendance_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['add_type'] = $this->constant_details_model->get_list(483);
        $data['receiving_party'] = $this->constant_details_model->get_list(504);
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['current_date'] = date("Ym");
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _post_validation($isEdit = false){

        for($i=0; $i<count($this->barcode); $i++) {
            if ( $this->receiving_party == 0 ){
                $this->print_error('يجب اختيار الجهة المستلمة ');
            }elseif($this->branch_no == 0 ){
                $this->print_error('يجب اختيار المقر..');
            }elseif($this->barcode[$i] == '' ){
                $this->print_error('يجب ادخال الباركود..');
            }
        }

    }

    function _postedData( $ser = null , $branch_no = null ,$receiving_party = null ,$class_output_id = null ,$ser_d = null ,$barcode = null ,$typ= null){
        $result = array(
            array('name'=>'SER_D','value'=> $ser_d,'type'=>'','length'=>-1),
            array('name'=>'SER','value'=> $ser,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_NO','value'=> $branch_no,'type'=>'','length'=>-1),
            array('name'=>'RECEIVING_PARTY','value'=> $receiving_party,'type'=>'','length'=>-1),
            array('name'=>'CLASS_OUTPUT_ID','value'=>$class_output_id ,'type'=>'','length'=>-1),
            array('name'=>'BARCODE','value'=> $barcode,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            array_shift($result);
        return $result;
    }
}