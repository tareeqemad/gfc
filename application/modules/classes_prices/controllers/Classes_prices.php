<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 15/12/20
 * Time: 09:24 ص
 */
class classes_prices extends MY_Controller{

    var $MODEL_NAME= 'classes_prices_model';

    var $PAGE_URL= 'classes_prices/classes_prices/get_page';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

     //  $this->load->model('settings/constant_details_model');

        $this->ser= $this->input->post('ser');
        $this->type= $this->input->get_post('type');
        $this->price_date= $this->input->post('price_date');
        $this->price= $this->input->post('price');
        $this->notes= $this->input->get_post('notes');
        $this->h_class_id= $this->input->post('h_class_id');
        $this->class_id= $this->input->post('class_id');
        $this->adopt= $this->input->post('adopt');


        //  ECHO $this->order_purpose ;
    }
    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $data['help']=$this->help;
        $data['adopts'] = $this->constant_details_model->get_list(317);



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
    function delete(){
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if(is_array($id)){
            foreach($id as $val){
                $msg= $this->{$this->MODEL_NAME}->delete($val);
            }
        }else{
            $msg= $this->{$this->MODEL_NAME}->delete($id);
        }

        if($msg == 1){
            echo  1;
        }else{
            $this->print_error_msg($msg);
        }
    }
    function index($page= 1, $ser=-1,$type=-1,$price_date=-1,$class_id=-1,$adopt=-1){
        if($this->type==1)   $data['title']='تسعير الأصناف - تحديث الأصناف';
      else  if($this->type==2)   $data['title']='تسعير الأصناف -  سعر السوق ';
      else if($this->type==3)   $data['title']='تسعير الأصناف -  أوامر التوريد  ';
      else if($this->type==0)   $data['title']='تسعير الأصناف - أرشيف ';
        $data['content']='classes_prices_index';
        $data['page']=$page;
        $data['ser']=$ser;
        $data['type']=$this->type;
        $data['price_date']=$price_date;
        $data['class_id']=$class_id;
        //  echo $data['order_purpose']."dddd";
        $data['adopt']=$adopt;

       // die( $this->type);

        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }
    function get_page($page= 1, $ser=-1,$type=-1,$price_date=-1,$class_id=-1,$adopt=-1){
        $this->load->library('pagination');

        $ser= $this->check_vars($ser,'ser');
        $type= $this->check_vars($type,'type');
        $price_date= $this->check_vars($price_date,'price_date');
        $class_id= $this->check_vars($class_id,'class_id');
         $adopt= $this->check_vars($adopt,'adopt');

        $where_sql= "  " ;

        $where_sql.= ($ser!= null)? " and ser= {$ser} " : '';
        $where_sql.= ($type!= null && $type!= 0)? " and type= {$type} " : '';
        $where_sql.= ($price_date!= null)? " and TRUNC(price_date,'dd') = '{$price_date}' " : '';
        //  $where_sql.= ($order_purpose!= null)? " and order_purpose= {$order_purpose} " : '';
        $where_sql.= ($class_id!= null)? " and class_id= {$class_id} " : '';
        $where_sql.= ($adopt!= null)? " and adopt= {$adopt} " : '';

        $config['base_url'] = base_url( $this->PAGE_URL);
        $count_rs = $this->{$this->MODEL_NAME}->get_count( ' CLASSES_PRICES_TB where 1=1 '.$where_sql);

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

        $this->load->view('classes_prices_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= $this->{$c_var}? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
//print_r($this->_postedData('create'));
            //    exit;
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            /////////////////////
            if(intval($this->ser) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->ser);
            }else{

                echo intval($this->ser);
            }
            ///////////////////////
        }else{
            $data['content']='classes_prices_show';

                    $data['title']=' أسعار السوق ';

            $data['isCreate']= true;
            $data['action'] = 'index';
            //   $this->order_purpose= $this->input->get_post('type');
            //   echo "dddd".$this->order_purpose;
            $data['type'] =   $this->type;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }
    function _post_validation($isEdit = false){

        if( $this->ser=='' and $isEdit ){
            $this->print_error('يجب ادخال جميع البيانات');


        }else if($this->class_id=='') {
            $this->print_error('يجب ادخال رقم الصنف ');
        } elseif( $this->price_date =='' ){
            $this->print_error('أدخل التاريخ ');
            }
        elseif( $this->price =='' and $this->price <0 ){
            $this->print_error('أدخل سعر السوق ');
        }





    }

    function get($id){

        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1  ))
            die();


            if($result[0]['TYPE']==1)   $data['title']='تسعير الأصناف - تحديث الأصناف';
            else  if($result[0]['TYPE']==2)   $data['title']='تسعير الأصناف -  سعر السوق ';
            else if($result[0]['TYPE']==3)   $data['title']='تسعير الأصناف -  أوامر التوريد  ';

        $data['orders_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] )?true : false : false;
        $data['action'] = 'edit';
        $data['isCreate'] = false;

        $data['content']='classes_prices_show';


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
    function adopt1(){
        $id = $this->input->post('id');
        $adopt = 1;
        $res = $this->{$this->MODEL_NAME}->adopt( $id,$adopt);
        if(intval($res) <= 0){
            $this->print_error('لم يتم إلغاء الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adopt2(){
        $id = $this->input->post('id');
        $adopt = 2;
        $res = $this->{$this->MODEL_NAME}->adopt( $id,$adopt);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adopt3(){
        $id = $this->input->post('id');
        $adopt = 3;
        $res = $this->{$this->MODEL_NAME}->adopt( $id,$adopt);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'TYPE','value'=>$this->type ,'type'=>'','length'=>-1),
            array('name'=>'PRICE_DATE','value'=>$this->price_date ,'type'=>'','length'=>-1),
            array('name'=>'PRICE','value'=>$this->price ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
            array('name'=>'class_id','value'=>$this->h_class_id ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);


        return $result;
    }




}
?>
