<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 03/08/15
 * Time: 08:32 ص
 */


class Holders extends MY_Controller{
    function  __construct(){
        parent::__construct();
        $this->load->model('Holders_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }


    function _lookUps_data(&$data){
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['FEEDER_LINE'] = $this->constant_details_model->get_list(90);
        $data['help']=$this->help;
    }
    /**
     *
     * index action perform all functions in view of Holders_show view
     */
    function index($page = 1){


        $data['title']='إدارة الأعمدة';
        $data['content']='Holders_index';
        $data['page']=$page;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $this->load->view('template/template',$data);

    }

    function get_page($page = 1){

        $this->load->library('pagination');


        $sql =isset($this->p_branch) && $this->p_branch != null ?" AND M.BRANCH = {$this->p_branch} " : '';
        $sql .=isset($this->p_notes) && $this->p_notes != null ?" AND M.NOTES LIKE '%{$this->p_notes}%' " : '';
        $sql .=isset($this->p_feeder_line) && $this->p_feeder_line != null ?" AND M.FEEDER_LINE = {$this->p_feeder_line} " : '';

        $sql .=isset($this->p_x) && $this->p_x != null && isset($this->p_y) && $this->p_y != null?" AND M.X between  {$this->p_x} -  (( 1 / 6371) *57.2957795)   and {$this->p_x} +  (( 1 / 6371) *57.2957795)  AND M.Y BETWEEN {$this->p_y} + ((1 / 6371 / cos( (( M.X)*57.2957795)))*57.2957795) AND {$this->p_y} - ((1 / 6371 / cos( (( M.X)*57.2957795)))*57.2957795) " : '';


        $config['base_url'] = base_url("technical/holders/get_page/");


        $count_rs = $this->get_table_count(' HOLDERS_TB M ');


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

        $data["rows"] = $this->Holders_model->get_list($sql, $offset , $row );


        $data['offset']=$offset+1;
        $data['page']=$page;


        $this->load->view('Holders_page',$data);

    }

    /**
     * create action : insert new Holders data ..
     * receive post data of Holders
     *
     */
    function create(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result= $this->Holders_model->create($this->_postedData());

            if(intval($result) <=0){
                $this->print_error('فشل في حفظ البيانات');
            }

            for($i = 0;$i< count($this->p_d_class_id);$i++){
                if($this->p_SER[$i] <= 0)
                    $this->Holders_model->equipments_create($this->_postedData_Equipments(
                        $result,
                        $this->p_d_class_id[$i],
                        $this->p_base_d_class_id[$i],
                        $this->p_d_feeder_line[$i],
                        $this->p_d_notes[$i],

                        true
                    ));

            }

            echo $result;

        }else{
            $data['content']='Holders_show';
            $data['title']='إدارة الأعمدة';

            $this->_lookUps_data($data);

            $this->load->view('template/template',$data);
        }


    }

    /**
     * get project by id ..
     */
    function get($id){


        $result =  $this->Holders_model->get($id);


        $data['content']='Holders_show';
        $data['title']='إدارة الأعمدة';

        $data['result'] =$result;

        $data['can_edit']= count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id && HaveAccess(base_url('technical/holders/edit') );


        $this->_lookUps_data($data,null);



        $this->load->view('template/template',$data);
    }

    /**
     * edit action : update exists Holders data ..
     * receive post data of Holders
     * depended on Holders prm key
     */
    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result= $this->Holders_model->edit($this->_postedData(false));


            if(intval($result) <=0){
                $this->print_error('فشل في حفظ البيانات');
            }

            for($i = 0;$i< count($this->p_d_class_id);$i++){
                if($this->p_SER[$i] <= 0)
                    $this->Holders_model->equipments_create($this->_postedData_Equipments(
                        $this->p_holder_id,
                        $this->p_d_class_id[$i],
                        $this->p_base_d_class_id[$i],
                        $this->p_d_feeder_line[$i],
                        $this->p_d_notes[$i],

                        true
                    ));
                else
                     $this->Holders_model->equipments_edit($this->_postedData_Equipments(
                      $this->p_holder_id,
                        $this->p_d_class_id[$i],
                        $this->p_base_d_class_id[$i],
                        $this->p_d_feeder_line[$i],
                        $this->p_d_notes[$i],
                        false,
                        $this->p_SER[$i]
                    ));
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
            array('name'=>'HOLDER_ID','value'=>$this->p_holder_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$this->p_class_id ,'type'=>'','length'=>-1),
            array('name'=>'COMPANY_NAME','value'=>$this->p_company_name ,'type'=>'','length'=>-1),
            array('name'=>'BASE_CLASS_ID','value'=>$this->p_base_class_id ,'type'=>'','length'=>-1),
            array('name'=>'FEEDER_LINE','value'=>$this->p_feeder_line ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->p_notes ,'type'=>'','length'=>-1),
            array('name'=>'HOLDER_CASE','value'=>$this->p_holder_case ,'type'=>'','length'=>-1),
            array('name'=>'BASE_HOLDER_CASE','value'=>$this->p_base_holder_case ,'type'=>'','length'=>-1),
            array('name'=>'GROUND','value'=>$this->p_ground ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$this->p_branch ,'type'=>'','length'=>-1),
            array('name'=>'X_IN','value'=>$this->p_x ,'type'=>'','length'=>-1),
            array('name'=>'Y_IN','value'=>$this->p_y ,'type'=>'','length'=>-1),
            array('name'=>'HINT_IN','value'=>$this->p_hint ,'type'=>'','length'=>-1),

        );

        if($isCreate){
            array_shift($result);
        }

        return $result;
    }


    function _postedData_Equipments($holder_id,$class_id,$base_class_id,$feeder_line,$notes,$isCreate ,$ser = null){

        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'HOLDER_ID','value'=>$holder_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'BASE_CLASS_ID','value'=>$base_class_id ,'type'=>'','length'=>-1),
            array('name'=>'FEEDER_LINE','value'=>$feeder_line ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$notes ,'type'=>'','length'=>-1),


        );

        if($isCreate){
            array_shift($result);
        }

        return $result;
    }

    function public_get_equipments($id){
        $data['CLASS_TYPE'] = $this->constant_details_model->get_list(91);
        $data['details'] = $this->Holders_model->equipments_list($id);

        $this->load->view('Holder_Equipments',$data);
    }

    function delete_equipments(){
        echo  $this->Holders_model->delete_equipments($this->p_id);
    }
}