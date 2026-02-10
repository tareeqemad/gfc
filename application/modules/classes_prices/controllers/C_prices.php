<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 15/12/20
 * Time: 09:24 ص
 */
class c_prices extends MY_Controller{

    var $MODEL_NAME= 'classes_prices_model';

    var $PAGE_URL= 'classes_prices/c_prices/get_page';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        //  $this->load->model('settings/constant_details_model');


        $this->type= $this->input->post('type');
        $this->from_price_date= $this->input->post('from_price_date');
        $this->to_price_date= $this->input->post('to_price_date');
        $this->h_class_id= $this->input->post('h_class_id');
        $this->class_id= $this->input->post('class_id');
        $this->order_id= $this->input->post('order_id');
        $this->buy_price_op= $this->input->post('buy_price_op');
        $this->sell_price_op= $this->input->post('sell_price_op');

        //  ECHO $this->order_purpose ;
    }
    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $data['help']=$this->help;
        $data['types'] = $this->constant_details_model->get_list(314);
        $data['compares'] = $this->constant_details_model->get_list(323);



        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_js('jquery.hotkeys.js');

    }
    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->ser);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حذف سعر السوق '.$msg);
        else
            $this->print_error('لم يتم حذف سعر السوق'.$msg);
    }

    function index($page= 1, $class_id=-1 ,$from_price_date=-1,$to_price_date=-1,$type=-1,$order_id=-1,$buy_price_op=-1,$sell_price_op=-1){
        $data['title']='أسعار الأصناف';
        $data['content']='c_prices_index';
        $data['page']=$page;

        $data['type']=$type;
        $data['from_price_date']=$from_price_date;
        $data['to_price_date']=$to_price_date;
        $data['class_id']=$class_id;
        $data['order_id']=$order_id;
        $data['buy_price_op']=$buy_price_op;
        $data['sell_price_op']=$sell_price_op;
        // die( $this->type);

        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }
    function get_page($page= 1, $class_id=-1 ,$from_price_date=-1,$to_price_date=-1,$type=-1,$order_id=-1,$buy_price_op=-1,$sell_price_op=-1){
        $this->load->library('pagination');


        $type= $this->check_vars($type,'type');
        $from_price_date= $this->check_vars($from_price_date,'from_price_date');
        $to_price_date= $this->check_vars($to_price_date,'to_price_date');
        $class_id= $this->check_vars($class_id,'class_id');
        $order_id= $this->check_vars($order_id,'order_id');
        $buy_price_op= $this->check_vars($buy_price_op,'buy_price_op');
        $sell_price_op= $this->check_vars($sell_price_op,'sell_price_op');
        $where_sql= "  " ;
        //echo("{$buy_price_op}");

        $where_sql.= ($type!= null )? " and type= {$type} " : '';
        $where_sql.= ($from_price_date!= null)? "  and TRUNC(price_date,'dd') >= '{$from_price_date}' " : '';//" and to_char(price_date,'YYYYMMDD')>= to_char('{$from_price_date}','YYYYMMDD') " : '';
        $where_sql.= ($to_price_date!= null)? " and TRUNC(price_date,'dd') <= '{$to_price_date}' " : '';
        $where_sql.= ($class_id!= null)? " and c.class_id= {$class_id} " : '';
        $where_sql.= ($order_id!= null)? " and order_id= {$order_id} " : '';
        //$where_sql.= ($buy_price_op!= null)? " and buy_price {$buy_price_op}  CLASS_PURCHASING " : '';get_compare_where($column1,$compare,$column2)
        $x=$this->{$this->MODEL_NAME}->get_compare_where("buy_price",$buy_price_op,"CLASS_PURCHASING");
        $where_sql.= ($buy_price_op!= null)? $x : '';
        $y=$this->{$this->MODEL_NAME}->get_compare_where("sell_price",$sell_price_op,"CLASS_PURCHASING");
        $where_sql.= ($sell_price_op!= null)? $y : '';

        $config['base_url'] = base_url( $this->PAGE_URL);
        $count_rs = $this->{$this->MODEL_NAME}->get_count( '  CLASS_TB C INNER JOIN classes_prices_tb D ON C.CLASSES_PRICES_SER=D.SER  where 1=1 '.$where_sql);

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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->gets_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('c_prices_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= $this->{$c_var}? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }









}
?>
