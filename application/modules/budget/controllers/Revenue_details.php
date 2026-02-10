<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/10/14
 * Time: 12:13 م
 */

class Revenue_details extends MY_Controller{

    var $MODEL_NAME= 'revenue_details_model';
    var $PAGE_URL= 'budget/revenue_details/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $type= intval($this->uri->segment(4));
        $this->year= $this->budget_year;
        $this->type= encryption_case($type);
        if(($this->type!=1 and $this->type!=2) or strlen($type)!=6)
            die('type');
    }

    function index(){
        $this->load->model('settings/gcc_branches_model');
        if($this->type==2)
            $data['title']='تحصيلات فاتورة كهرباء بعد التنسيب';
        else
            $data['title']='تحصيلات فاتورة كهرباء';
        $data['content']='revenue_details_index';
        $data['year']= $this->year;
        $data['type']= encryption_case($this->type,1);
        $data['months']= $this->select_month();
        $branch_data= $this->gcc_branches_model->get($this->user->branch);
        $data['cash_percent']= $branch_data[0]['BUDGET_CASH_PERCENT'];
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->view('template/template',$data);
    }

    function get_page(){
        $month= $this->input->post('month');
        $year= $this->year;
        $branch= $this->user->branch;
        $data['user_id']= $this->user->id;
        if($month!= null and $month!= 0){
            $this->load->model('settings/constant_details_model');
            $data['collection_type']= $this->constant_details_model->get_list(5);
            $data['total']= $this->{$this->MODEL_NAME}->get_total($year ,$month ,$branch, $this->type);
            $data['get_list']= $this->{$this->MODEL_NAME}->get_list($year, $month, $branch, $this->type);
            $this->load->view('revenue_details_page',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function receive_data(){
        // vars
        $month= $this->input->post('month');
        $sum_rate=0;
        // arrays
        $no= $this->input->post('no');
        $collection_type= $this->input->post('collection_type');
        $rate= $this->input->post('rate');
        $note= $this->input->post('note');

        foreach($rate as $val){ // array_sum
            $sum_rate+= floatval($val)*1000;
        }
        $sum_rate= floatval($sum_rate/1000);

        if($sum_rate != 100)
            $this->print_error('مجموع النسب '.$sum_rate);
        else{
            for($i=0;$i<count($no);$i++){
                if($no[$i]==0 and $collection_type[$i]>0 and $rate[$i]!='' and $rate[$i]>0 ) // create
                    $this->create($this->_postedData($no[$i],$month, $collection_type[$i], $rate[$i], $note[$i],'create'));
                else if ($no[$i]!='' and $no[$i]!=0 and $collection_type[$i]>0 and $rate[$i]!='' and $rate[$i]>0 ) // edit
                    $this->edit($this->_postedData($no[$i],$month, $collection_type[$i], $rate[$i], $note[$i]));
                else if ($no[$i]!='' and $no[$i]!=0 and $collection_type[$i]==0 and $rate[$i]=='' ) // delete
                    $this->delete($this->_postedData($no[$i],$month, null, null, null,'delete'));
            }
            echo modules::run($this->PAGE_URL);
        }
    }

    function select_month(){
        $select= "<select name='month' id='txt_month' class='form-control'>";
        $select.= "<option value='0' selected='selected'>اختر الشهر</option>";
        $all= $this->{$this->MODEL_NAME}->get_total($this->year ,null ,$this->user->branch, $this->type);
        foreach ($all as $month){
            $select.= "<option value='{$month['MONTH']}'>".months($month['MONTH'])."</option>";
        }
        $select.="</select> ";
        return $select;
    }

    function create($data){
        $result= $this->{$this->MODEL_NAME}->create($data);
        return $this->Is_success($result);
    }

    function edit($data){
        $result = $this->{$this->MODEL_NAME}->edit($data);
        return $this->Is_success($result);
    }

    function delete($data=false){
        $month= $this->input->post('month');
        if($data)
            $this->{$this->MODEL_NAME}->delete($data);
        else{
            $result = $this->{$this->MODEL_NAME}->delete($this->_postedData(null, $month, null, null, null,'delete'));
            $this->Is_success($result);
            echo modules::run($this->PAGE_URL);
        }
    }

    function _postedData($no,$month, $collection_type, $rate, $note, $typ= null){
        $result = array(
            array('name'=>'NO','value'=>$no ,'type'=>'','length'=>-1),
            array('name'=>'YEAR','value'=>$this->year ,'type'=>'','length'=>-1),
            array('name'=>'MONTH','value'=>$month ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$this->user->branch ,'type'=>'','length'=>-1),
            array('name'=>'COLLECTION_TYPE','value'=>$collection_type ,'type'=>'','length'=>-1),
            array('name'=>'RATE','value'=>$rate ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$note ,'type'=>'','length'=>-1),
            array('name'=>'TYPE','value'=>$this->type ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='delete')
            unset($result[4], $result[5], $result[6]);

        return $result;
    }

}
