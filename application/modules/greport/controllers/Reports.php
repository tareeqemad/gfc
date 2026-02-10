<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 18/06/15
 * Time: 09:39 ص
 */
class Reports extends MY_Controller{

    function  __construct(){
        parent::__construct();
        $this->load->model('treasury/income_voucher_model');
    }

    function public_income_voucher($id){
        $data['content'] = 'income_voucher';
        $data['rows'] = $this->income_voucher_model->income_voucher_tb_rep2($id,$this->user->branch);
        $data['branch'] = $this->user->branch;
        if($this->user->branch != 1)
            $this->load->view('income_voucher',$data);
        else
            $this->load->view('income_voucher_main',$data);

    }

    // mkilani- print class barcodes for stores_class_input
    function public_class_barcode($id, $class_id='', $store_id=''){
        $this->load->model('pledges/class_codes_model');
        $data['rows'] = $this->class_codes_model->get_barcode($id, $class_id, $store_id);
        $this->load->view('class_barcode',$data);
    }
    function public_class_barcode_tlp($id,$entry_date ,$class_id='', $store_id='', $class_code_ser='', $code_case=''){
        $this->load->model('pledges/class_codes_model');
        if ($id==0) $id='';
        if($class_code_ser==0 || $class_code_ser==-1)  $class_code_ser='';
        if ($class_id==-1) $class_id='';
        if ($store_id==-1) $store_id='';
        if ($code_case==0) $code_case='';
       // if ($entry_date==0) $entry_date='';
//die($code_case);
        //$sql="";

         $sql=" and to_char(M.entry_date,'yyyy')=".$entry_date;
        if ($id!='' )
            $sql=$sql." AND M.RECEIPT_CLASS_INPUT_ID = ".$id;

        if ($class_code_ser!='')
            $sql=$sql." AND M.class_code_ser = ".$class_code_ser;

        if ($class_id!='')
            $sql=$sql." AND M.class_id = ".$class_id;

        if ($store_id!='')
            $sql=$sql." AND M.store_id = ".$store_id;


        if ($code_case!=0 and $code_case!=''){
            $sql=$sql." AND M.code_case = ".$code_case;

        }

        $data['rows']= $this->class_codes_model->get_list($sql);
        $this->class_codes_model->set_printed($sql);
        $this->load->view('class_barcode_tlp2844',$data);
    }

    function public_class_barcode_tlp1($id,$entry_date ,$class_id='', $store_id='', $class_code_ser='', $code_case=''){
        $this->load->model('pledges/class_codes_model');
        if ($id==0) $id='';
        if($class_code_ser==0 || $class_code_ser==-1)  $class_code_ser='';
        if ($class_id==-1) $class_id='';
        if ($store_id==-1) $store_id='';
        if ($code_case==0) $code_case='';
        // if ($entry_date==0) $entry_date='';
//die($code_case);
        //$sql="";

        $sql=" and to_char(M.entry_date,'yyyy')=".$entry_date;
        if ($id!='' )
            $sql=$sql." AND M.RECEIPT_CLASS_INPUT_ID = ".$id;

        if ($class_code_ser!='')
          $sql=$sql." AND '".$class_code_ser."' IN  ( TO_CHAR(M.class_code_ser),M.BARCODE )";
           // $sql=$sql." AND M.class_code_ser = ".$class_code_ser;

        if ($class_id!='')
            $sql=$sql." AND M.class_id = ".$class_id;

        if ($store_id!='')
            $sql=$sql." AND M.store_id = ".$store_id;


        if ($code_case!=0 and $code_case!=''){
            $sql=$sql." AND M.code_case = ".$code_case;

        }

        $data['rows']= $this->class_codes_model->get_list($sql);
        $this->class_codes_model->set_printed($sql);
        $this->load->view('class_barcode_tlp2844_NEW',$data);
    }

    // MKilani - طباعة الباركودات من سندات الصرف المخزنية
    function public_stores_barcode_tlp1($barcodes){
        $data['rows']= explode("_:_", $barcodes); // txt to array
        $this->load->view('stores_barcode_tlp1',$data);
    }

    // mkilani- print class amount actions
    function public_class_amount_actions(){
        $where_sql= $this->session->userdata('class_amount_actions_where');
        $order_sql= $this->session->userdata('class_amount_actions_order');
        $offset= 0;
        $row= 999999999999999999999999;
        $data['sn']= report_sn();
        $this->load->model('stores/class_amount_model');
        $data['rows'] = $this->class_amount_model->get_actions($where_sql ,$order_sql, $offset , $row );
        $this->load->view('class_amount_actions',$data);
    }
    function public_customers_pledges_barcode($id){
        $this->load->model('pledges/customers_pledges_model');
        $data['rows'] = $this->customers_pledges_model->get($id);
        $this->load->view('customers_pledges_barcode',$data);
    }
    function public_inventory_pledges_barcode($id){
        $this->load->model('pledges/inventory_pledges_model');
        $data['rows'] = $this->inventory_pledges_model->get($id);
        $this->load->view('inventory_pledges_barcode',$data);
    }
}
