<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 26/11/14
 * Time: 08:42 ص
 */

class Payroll extends MY_Controller{

    function  __construct(){
        parent::__construct();
        $this->load->model('payroll_payment_model');
        $this->load->model('payroll_payment_detail_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('financial/accounts_model');


    }

    function index($page = 1){

        $data['title']='حوالة الرواتب';
        $data['content']='payroll_index';

        $data['page']=$page;

        $data['case']=1;

        $data['action'] = 'index';

        $data['help'] = $this->help;

        $this->load->view('template/template',$data);

    }


    function adopt($page = 1){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->payroll_payment_model->adopt($this->p_payroll_payment_id,2);

        }else{
            $data['title']='الحوالة المالية : إعتماد';
            $data['content']='payroll_index';
            $data['page']=$page;
            $data['case']= 2;
            $data['action'] = 'adopt';
            $data['help'] = $this->help;
            $this->load->view('template/template',$data);
        }

    }


    function review($page = 1){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id,2);

        }else{
            $data['title']='الحوالة المالية : تدقيق';
            $data['content']='payroll_index';
            $data['page']=$page;
            $data['case']= 3;
            $data['action'] = 'review';
            $data['help'] = $this->help;
            $this->load->view('template/template',$data);
        }

    }

    function _look_ups(&$data){
        add_css('combotree.css');

        add_js('jquery.hotkeys.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['title']='حوالة الرواتب';
        $data['content']='payroll_show';
        $data['help']=$this->help;

        $data['currency'] =  $this->currency_model->get_all();

    }

    function create(){


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->payroll_payment_model->create($this->_postedData());



            if(intval($id) <= 0){
                $this->print_error('فشل في حفظ السند');
            }

            for($i = 0;$i < count($this->p_account_id);$i++){
                $this->payroll_payment_model->create_details($this->_postedData_details($id,
                    $this->p_account_id[$i],
                    $this->p_root_account_id[$i],
                    $this->p_debit[$i],
                    $this->p_credit[$i]));
            }

            echo 1;

        }else{


            $this->_look_ups($data);
            $data['action'] = $this->action;

            $this->load->view('template/template',$data);
        }
    }


    function edit(){


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->payroll_payment_model->edit($this->_postedData_edit());

            if(intval($id) <= 0){
                $this->print_error('فشل في حفظ السند');
            }

            echo 1;

        }
    }

    /**
     * get payroll by id ..
     */
    function get($id,$action = 'edit'){


        $result = $this->payroll_payment_model->get($id);


        $data['content']='payroll_show';
        $data['title']='حوالة الراتب ';
        $data['action'] = $action;
        $data['result'] =$result;

        $data['can_edit']= count($result) > 0 && $result[0]['PAYROLL_PAYMENT_CASE'] == 1 && $result[0]['ENTRY_USER'] == $this->user->id;

        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }


    function get_page($page = 1,$case = 1){

        $this->load->library('pagination');

        $prv_case = $case -1;

        $count_rs = $this->get_table_count(" PAYROLL_PAYMENT_TB where  PAYROLL_PAYMENT_CASE BETWEEN {$prv_case} AND {$case} ");

        $config['base_url'] = base_url('payment/payroll/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] =count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
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

        $result=$this->payroll_payment_model->get_list(" AND M.PAYROLL_PAYMENT_CASE  BETWEEN {$prv_case} AND {$case}  ", $offset , $row);

        $this->date_format($result,'PAYROLL_PAYMENT_DATE');

        $data["rows"] =$result;
        $data['offset'] =$offset;
        $data['action'] =$this->action;

        $this->load->view('payroll_rows_page',$data);

    }

    function _postedData($create = null){

        $result = array(
            array('name'=>'PAYROLL_PAYMENT_ID','value'=>$this->p_payroll_payment_id ,'type'=>'','length'=>-1),
            array('name'=>'PAYROLL_PAYMENT_DATE','value'=>$this->p_payroll_payment_date ,'type'=>'','length'=>-1),
            array('name'=>'CURR_ID','value'=>$this->p_curr_id ,'type'=>'','length'=>-1),
            array('name'=>'CURR_VALUE','value'=>$this->p_curr_value ,'type'=>'','length'=>-1),
            array('name'=>'PAYROLL_PAYMENT_MONTH','value'=>$this->stringDateToDate($this->p_payroll_payment_month) ,'type'=>'','length'=>-1),
            array('name'=>'TRANSFER_NUMBER','value'=>$this->p_transfer_number ,'type'=>'','length'=>-1),
            array('name'=>'TRANASFER_HINT','value'=>$this->p_tranasfer_hint ,'type'=>'','length'=>-1),
            array('name'=>'TRANSFER_DATE','value'=>$this->p_transfer_date ,'type'=>'','length'=>-1),

        );

        if($create == null){
            array_shift($result);
        }

        return $result;
    }

    function _postedData_edit(){

        $result = array(
            array('name'=>'PAYROLL_PAYMENT_ID','value'=>$this->p_payroll_payment_id ,'type'=>'','length'=>-1),
            array('name'=>'TRANSFER_NUMBER','value'=>$this->p_transfer_number ,'type'=>'','length'=>-1),
            array('name'=>'TRANASFER_HINT','value'=>$this->p_tranasfer_hint ,'type'=>'','length'=>-1),
            array('name'=>'TRANSFER_DATE','value'=>$this->p_transfer_date ,'type'=>'','length'=>-1),

        );


        return $result;
    }

    function _postedData_details($pay_id,$account_id , $root_account_id ,$debit ,$credit){

        $result = array(
            array('name'=>'PAYROLL_PAYMENT_ID','value'=>$pay_id ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$account_id ,'type'=>'','length'=>-1),
            array('name'=>'ROOT_ACCOUNT','value'=>$root_account_id ,'type'=>'','length'=>-1),
            array('name'=>'BEBIT_VALUE','value'=>$debit ,'type'=>'','length'=>-1),
            array('name'=>'CREDIT_VALUE','value'=>$credit ,'type'=>'','length'=>-1),

        );

        return $result;
    }

    function public_get_details($id = 0){

        if(isset($this->p_month)){
            $result = $this->payroll_payment_model->get_payroll_details($this->p_month,$this->p_curr_id);
            $data['details'] = $result;
        }
        else{
            $result = $this->payroll_payment_model->get_details($id);

            $data['rows'] = $result;
        }
        $this->load->view('payroll_details',$data);
    }


}