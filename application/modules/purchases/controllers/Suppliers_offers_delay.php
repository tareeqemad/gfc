<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/03/15
 * Time: 10:11 ص
 */

class suppliers_offers_delay extends MY_Controller{

    var $MODEL_NAME= 'suppliers_offers_delay_model';
   var $PAGE_URL= 'purchases/suppliers_offers/get_page';
    var $PAGE_DETAIL_URL='purchases/suppliers_offers/public_get_details';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('purchase_order_detail_model');
        $this->load->model('purchase_order_items_model');
        $this->load->model('purchase_order_model');

        $this->purchase_order_id= $this->input->post('purchase_order_id');
        $this->delay_id= $this->input->post('delay_id');
        $this->award_case= $this->input->post('award_case');
        $this->order_purpose= $this->input->get_post('order_purpose');

        if($this->order_purpose!=null and $this->order_purpose!=1 and $this->order_purpose!=2)
            die('construct');
    }
    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $data['help']=$this->help;

        add_js('jquery.hotkeys.js');

    }
    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->suppliers_offers_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ   '.$msg);
        else
            $this->print_error('لم يتم حذف   '.$msg);
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
            echo  modules::run($this->PAGE_URL);
        }else{
            $this->print_error_msg($msg);
        }
    }

    function index($page= 1, $delay_id=-1 ,$purchase_order_id=-1, $award_case=-1){
        if ($this->order_purpose==1 )
            $data['title']='محاضر لجنة البت والترسية لأصناف مؤجلة';
        else
            if ($this->order_purpose==2 )
                $data['title']='محاضر لجنة البت و الترسية لأصناف مؤجلة-أعمال مدنية';

        $data['content']='suppliers_offers_delay_index';
        $data['page']=$page;
        //$data['suppliers_offers_id']=$delay_id;
        $data['purchase_order_id']=$purchase_order_id;
        $data['delay_id']=$delay_id;
        $data['award_case']= $award_case;

        $data['order_purpose']=$this->order_purpose;

        $this->load->model('settings/constant_details_model');
        $data['award_case_all'] = $this->constant_details_model->get_list(77);

        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $delay_id=-1 ,$purchase_order_id=-1, $award_case=-1,$order_purpose=-1){
        $this->load->library('pagination');

        $delay_id= $this->check_vars($delay_id,'delay_id');
        $purchase_order_id= $this->check_vars($purchase_order_id,'purchase_order_id');
        $award_case= $this->check_vars($award_case,'award_case');
        $order_purpose= $this->check_vars($order_purpose,'order_purpose');
        $this->order_purpose=$order_purpose;
        if($order_purpose ==1)
        $where_sql= "  AND P.ORDER_PURPOSE=$order_purpose and  m.DELAY_ID in ( SELECT DISTINCT a.delay_id from gfc.purchase_order_detail_tb a where a.purchase_order_id= M.purchase_order_id and a.award_delay_decision= 3  ) " ;
else
        if($order_purpose ==2)
        $where_sql= "  AND P.ORDER_PURPOSE=$order_purpose and  m.DELAY_ID in ( SELECT DISTINCT a.delay_id from gfc.purchase_order_items_tb a where a.purchase_order_id= M.purchase_order_id and a.award_delay_decision= 3  ) " ;

        $where_sql.= ($delay_id!= null)? " and DELAY_ID= {$delay_id} " : '';
        $where_sql.= ($purchase_order_id!= null)? " and p.purchase_order_id= {$purchase_order_id} " : '';
        $where_sql.= ($award_case!= null)? " and m.award_case= '{$award_case}' " : '';

//echo $where_sql;
        $config['base_url'] = $this->PAGE_URL;
        $count_rs = $this->{$this->MODEL_NAME}->get_count( ' SUPPLIERS_OFFERS_DELAY_TB M,PURCHASE_ORDER_TB p  WHERE M.purchase_order_id=P.purchase_order_id '.$where_sql);
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

        $this->load->view('suppliers_offers_delay_page',$data);
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
            $this->p_delay_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            /////////////////////
            if(intval($this->p_delay_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->p_delay_id);
            }else{
                for($i=0; $i<count($this->class_id); $i++){
                    if($this->class_id[$i]!='' and $this->amount[$i]!='' and $this->customer_price[$i]!='' and $this->price[$i]!=''  ){
                    //    $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details_insert($this->class_id[$i], $this->unit_class_id[$i], $this->amount[$i], $this->customer_price[$i], $this->price[$i], $this->note[$i]));
                   //     if(intval($detail_seq) <= 0){
                     //       $this->print_error_del($detail_seq);
                     //   }
                    }
                }
                echo intval($this->p_delay_id);
            }
            ///////////////////////
        }else{
            $data['content']='suppliers_offers_delay_show';
             $data['title']='إدخال كشف تفريغ-أعمال مدنية';

            $data['isCreate']= true;
            $data['action'] = 'index';
            //   $this->order_purpose= $this->input->get_post('type');
            //   echo "dddd".$this->order_purpose;
            $data['order_purpose'] =   $this->g_order_purpose;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }
    function _post_validation($isEdit = false){

        if( $this->suppliers_offers_id=='' and $isEdit  ){
            $this->print_error('يجب ادخال جميع البيانات');

        }else if(!($this->class_id) or count(array_filter($this->class_id)) <= 0  ){
            $this->print_error('يجب ادخال صنف واحد على الاقل ');
        }else if (1){
            $all_class= array();
            for($i=0;$i<count($this->class_id);$i++){
                $all_class[]= $this->class_id[$i];
                if( $this->class_id[$i]=='' )
                    $this->print_error('اختر الصنف');
                elseif( $this->unit_class_id[$i]=='' )
                    $this->print_error('اختر الوحدة');
                elseif( $this->amount[$i]=='' )
                    $this->print_error('أدخل الكمية ');

            }
        }

        if( count(array_filter($all_class)) !=  count(array_count_values(array_filter($all_class))) ){
            $this->print_error('يوجد تكرار في الاصناف');
        }
    }
    function get($id,$order_purpose){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1  ))
            die();
        $data['orders_data']=$result;
        $data['order_purpose']=$order_purpose;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] )?true : false : false;
        $data['action'] = 'edit';
        $data['content']='suppliers_offers_delay_show';
        $data['isCreate']= true;
       $data['title']='تأجيل أصناف';
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
                for($i=0; $i<count($this->class_id); $i++){

                    if($this->ser[$i]== 0  and $this->class_id[$i]!='' and $this->customer_price[$i]!='' and $this->price[$i]!='' ){ // create

                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details_insert($this->class_id[$i], $this->unit_class_id[$i], $this->amount[$i], $this->customer_price[$i], $this->price[$i], $this->note[$i]));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser[$i]!= 0   and $this->class_id[$i]!='' and $this->customer_price[$i]!='' and $this->price[$i]!=''  ){  // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details_update($this->ser[$i], $this->class_id[$i], $this->unit_class_id[$i], $this->amount[$i], $this->customer_price[$i], $this->price[$i], $this->note[$i], 0,null));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }/*elseif($this->ser[$i]!= 0 and ($this->approved_amount[$i]=='' or $this->approved_amount[$i]==0) ){ // delete
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }*/
                }
                echo 1;
            }
        }
    }
    function adopt1_1(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id,1);
        if(intval($res) <= 0){
            $this->print_error('لم يتم إلغاء الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adopt1_2(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt($id,1);
        if(intval($res) <= 0){
            $this->print_error('لم يتم إلغاء الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adopt2_1(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt( $id,2);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adopt2_2(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt( $id,2);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adopt3_1(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt( $id,3);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adopt3_2(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt( $id,3);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adoptc3_1(){
        $id = $this->input->post('id');

        $res = $this->{$this->MODEL_NAME}->adopt( $id,2);

        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function adoptc3_2(){
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->adopt( $id,2);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo 1;

    }
    function public_get_details($id=0,$purchase_order_id=0){


        $id = $this->input->post('id') ? $this->input->post('id') :$id;

        $purchase_order_id = $this->input->post('purchase_order_id') ? $this->input->post('purchase_order_id') : $purchase_order_id;
        if ($id ==0){

            $data['rec_details'] = $this->purchase_order_detail_model->get_details_all($purchase_order_id);
            echo ( $this->load->view('suppliers_offers_purchase_detail',$data));

        }  else{
            // echo $id;
            $data['rec_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
            //   print_r( $data['rec_details']);
            // echo $id;
            echo ( $this->load->view('suppliers_offers_detail',$data));

        }
    }


    function _postedData($typ= null){
        $result = array(
            array('name'=>'DELAY_ID','value'=>$this->p_delay_id ,'type'=>'','length'=>-1),
            array('name'=>'PURCHASE_ORDER_ID','value'=>$this->p_purchase_order_id ,'type'=>'','length'=>-1),
            array('name'=>'AWARD_NOTES','value'=>$this->p_award_notes ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);


        return $result;
    }


    function do_order_delay(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id!='' and $this->p_delay_id!=''){
            $res = $this->{$this->MODEL_NAME}->do_order_delay($this->purchase_order_id,$this->p_delay_id);
            if(intval($res) <= 0){
                $this->print_error('لم يتم التحويل'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }
    function do_order_items_delay(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->purchase_order_id!='' and $this->p_delay_id!=''){
            $res = $this->{$this->MODEL_NAME}->do_order_items_delay($this->purchase_order_id,$this->p_delay_id);
            if(intval($res) <= 0){
                $this->print_error('لم يتم التحويل'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

}
