<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 29/07/15
 * Time: 08:57 ص
 */

class HighPowerPartition extends  MY_Controller{
    function  __construct(){
        parent::__construct();
        $this->load->model('HPP_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }


    function _lookUps_data(&$data){
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['HPARTITION_CASE'] = $this->constant_details_model->get_list(88);
        $data['FEEDER_LINE'] = $this->constant_details_model->get_list(90);

        $data['help']=$this->help;
    }
    /**
     *
     * index action perform all functions in view of hpp_show view
     */
    function index($page = 1){


        $data['title']='إدارة سكاكين الضغط العالي';
        $data['content']='hpp_index';
        $data['page']=$page;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $this->load->view('template/template',$data);

    }

    function get_page($page = 1 , $isPublic = false){

        $this->load->library('pagination');


        $sql =isset($this->p_branch) && $this->p_branch != null ?" AND M.BRANCH = {$this->p_branch} " : '';
        $sql .=isset($this->p_notes) && $this->p_notes != null ?" AND M.NOTES LIKE '%{$this->p_notes}%' " : '';
        $sql .=isset($this->p_feeder_line) && $this->p_feeder_line != null ?" AND M.FEEDER_LINE = {$this->p_feeder_line} " : '';

        $sql .=isset($this->p_x) && $this->p_x != null && isset($this->p_y) && $this->p_y != null?" AND M.X between  {$this->p_x} -  (( 1 / 6371) *57.2957795)   and {$this->p_x} +  (( 1 / 6371) *57.2957795)  AND M.Y BETWEEN {$this->p_y} + ((1 / 6371 / cos( (( M.X)*57.2957795)))*57.2957795) AND {$this->p_y} - ((1 / 6371 / cos( (( M.X)*57.2957795)))*57.2957795) " : '';


        //GIS_X between  :lat -  (( :rad / :R) *57.2957795)   and :lat +  (( :rad / :R) *57.2957795)

        $config['base_url'] = base_url("technical/highpowerpartition/get_page/");


        $count_rs = $this->get_table_count(' HIGH_POWER_PARTITION_TB M ');


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

        $data["rows"] = $this->HPP_model->get_list($sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        if(!$isPublic)
            $this->load->view('hpp_page',$data);
        else $this->load->view('hpp_public_page',$data);

    }

    /**
     * create action : insert new High Power Partition data ..
     * receive post data of High Power Partition
     *
     */
    function create(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result= $this->HPP_model->create($this->_postedData());

            if(intval($result) <=0){
                $this->print_error('فشل في حفظ البيانات');
            }


            echo $result;

        }else{
            $data['content']='hpp_show';
            $data['title']='إدارة سكاكين الضغط العالي  ';

            $this->_lookUps_data($data);

            $this->load->view('template/template',$data);
        }


    }

    /**
     * get project by id ..
     */
    function get($id){


        $result =  $this->HPP_model->get($id);


        $data['content']='hpp_show';
        $data['title']='إدارة سكاكين الضغط العالي  ';

        $data['result'] =$result;

        $data['can_edit']= count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id && HaveAccess(base_url('technical/highpowerpartition/edit') );


        $this->_lookUps_data($data,null);



        $this->load->view('template/template',$data);
    }

    /**
     * edit action : update exists High Power Partition data ..
     * receive post data of High Power Partition
     * depended on High Power Partition prm key
     */
    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result= $this->HPP_model->edit($this->_postedData(false));


            if(intval($result) <=0){
                $this->print_error('فشل في حفظ البيانات');
            }

            echo 1;

        }
    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData($isCreate = true){

        $result = array(
            array('name'=>'HIGH_POWER_PARTITION_SERIAL_IN','value'=>$this->p_high_power_partition_serial ,'type'=>'','length'=>-1),
            array('name'=>'HPARTITION_DIRECTION_IN','value'=>$this->p_hpartition_direction ,'type'=>'','length'=>-1),
            array('name'=>'HPARTITION_ID_IN','value'=>$this->p_hpartition_id ,'type'=>'','length'=>-1),
            array('name'=>'HPARTITION_ACTIVIATION_IN','value'=>$this->p_hpartition_activiation ,'type'=>'','length'=>-1),
            array('name'=>'HPARTITION_CUT_IN','value'=>$this->p_hpartition_cut ,'type'=>'','length'=>-1),
            array('name'=>'NOTES_IN','value'=>$this->p_notes ,'type'=>'','length'=>-1),
            array('name'=>'INSULATOR_TYPE_IN','value'=>$this->p_insulator_type ,'type'=>'','length'=>-1),
            array('name'=>'HPARTITION_CASE_IN','value'=>$this->p_hpartition_case ,'type'=>'','length'=>-1),
            array('name'=>'GROUND_IN','value'=>$this->p_ground ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_IN','value'=>$this->p_branch ,'type'=>'','length'=>-1),
            array('name'=>'X_IN','value'=>$this->p_x ,'type'=>'','length'=>-1),
            array('name'=>'Y_IN','value'=>$this->p_y ,'type'=>'','length'=>-1),
            array('name'=>'HINT_IN','value'=>$this->p_hint ,'type'=>'','length'=>-1),
            array('name'=>'FEEDER_LINE','value'=>$this->p_feeder_line ,'type'=>'','length'=>-1),
            array('name'=>'ADAPTER_SERIAL','value'=>$this->p_adapter_serial ,'type'=>'','length'=>-1),


        );

        if($isCreate){
            array_shift($result);
        }

        return $result;
    }


    function public_index($txt,$page = 1){


        $data['title']='إدارة  سكاكين الضغط العالي';
        $data['content']='hpp_public_index';
        $data['page']=$page;

        $this->load->model('settings/gcc_branches_model');

        $data['txt'] = $txt;

        $data['branches'] = $this->gcc_branches_model->get_all();

        $this->load->view('template/view',$data);

    }


}