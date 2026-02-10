<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 10:34 AM
 */


class Classes extends  MY_Controller{
    var $CLASS_ID;
    var $CLASS_PARENT_ID;
    var $CLASS_NAME_EN;
    var $CLASS_NAME_AR;
    var $CALSS_DESCRIPTION;
    var $CALSS_TYPE;
    var $CLASS_UNIT;
    var $CLASS_MIN;
    var $CLASS_MAX;
    var $CLASS_OUTDOOR;
    var $ACCOUNT_ID;
    var $CLASS_PURCHASING;
    var $CLASS_PAYMENT;
    var $CODE;
    var $OLD_CLASS_ID;
    var $CURR_ID;
    var $RESPONSIBLE_IN_OUT;
    var $CLASS_UNIT_SUB;
    var $CLASS_UNIT_COUNT;
    var $CLASS_ACOUNT_TYPE;
    var $ACCOUNT_TYPE;
    var $IS_BUDGET;
    var $THE_YEAR;
    var $AVERAGE_LIFE_SPAN ;
    var $MODEL_NAME= 'class_model';
    function  __construct(){
        parent::__construct();

        $this->load->model('class_model');
        $this->CLASS_ID = $this->input->post('class_id');
        $this->CLASS_PARENT_ID= $this->input->post('class_parent_id');
        $this->CLASS_NAME_EN= $this->input->post('class_name_en');
        $this->CLASS_NAME_AR= $this->input->post('class_name_ar');
        $this->CALSS_DESCRIPTION= $this->input->post('calss_description');
        $this->CALSS_TYPE= $this->input->post('calss_type');
        $this->CLASS_MIN= $this->input->post('class_min');
        $this->CLASS_UNIT= $this->input->post('class_unit');
        $this->CLASS_MAX= $this->input->post('class_max');
        $this->CLASS_OUTDOOR= $this->input->post('class_outdoor');
        $this->ACCOUNT_ID= $this->input->post('account_id');
        $this->CLASS_PURCHASING= $this->input->post('class_purchasing');
        $this->CLASS_PAYMENT= $this->input->post('class_payment');
        $this->CODE= $this->input->post('code');
        $this->OLD_CLASS_ID= $this->input->post('old_class_id');
        $this->CURR_ID= $this->input->post('curr_id');
        $this->RESPONSIBLE_IN_OUT= $this->input->post('responsible_in_out');
        $this->CLASS_UNIT_SUB= $this->input->post('class_unit_sub');
        $this->CLASS_UNIT_COUNT= $this->input->post('class_unit_count');
        $this->CLASS_ACOUNT_TYPE= $this->input->post('class_acount_type');
        $this->ACCOUNT_TYPE= $this->input->post('account_type');
        $this->EXP_ACCOUNT_CUST= $this->input->post('exp_account_cust');
        $this->DESTRUCTION_TYPE= $this->input->post('destruction_type');
        $this->DESTRUCTION_PERCENT= $this->input->post('destruction_percent');
        $this->DESTRUCTION_ACCOUNT_ID= $this->input->post('destruction_account_id');
        $this->IS_BUDGET= $this->input->post('is_budget');
        $this->SECTION_NO= $this->input->post('section_no');
        $this->THE_YEAR= $this->input->post('the_year');
        $this->CLASS_MIN_REQUEST= $this->input->post('class_min_request');


    }

    /**
     *
     * index action perform all functions in view of accounts_show view
     * from this view , can show accounts tree , insert new account , update exists one and delete other ..
     *
     */
    function index(){
        $this->load->helper('generate_list');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('budget/budget_section_model');

        // add_css('jquery-hor-tree.css');
        add_css('combotree.css');
        add_css('tabs.css');
        add_js('jquery.tree.js');
        add_js('tabs.js');

        $data['title']=' شجرة الأصناف ';
        $data['content']='class_indexes';


        $resource =  $this->_get_structure(-1);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{CLASS_ID}" data-classtype="{CALSS_TYPE}"  class="adapt_{ADOPT}" ondblclick="javascript:class_get(\'{CLASS_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{CLASS_ID} : {CLASS_NAME_AR}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="class">'.generate_list($resource, $options, $template).'</ul>';
        // $data['tree'] = '<ul class="tree" id="accounts"><li class="parent_li"><span data-id="0" >شركة الكهرباء</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';

        $data['currency'] = $this->currency_model->get_all();
        $data['calss_type'] = $this->constant_details_model->get_list(21);
        $data['class_unit'] = $this->constant_details_model->get_list(22);
        $data['class_unit_sub'] = $this->constant_details_model->get_list(29);
        $data['class_outdoor'] = $this->constant_details_model->get_list(23);
        $data['responsible_in_out'] = $this->constant_details_model->get_list(24);
        $data['is_budget'] = $this->constant_details_model->get_list(95);
        $data['class_acount_type'] = $this->constant_details_model->get_list(36);
        $data['destruction_type'] = $this->constant_details_model->get_list(82);
        $data['average_life_span_type'] = $this->constant_details_model->get_list(89);
        $data['average_life_span'] = $this->constant_details_model->get_list(129);
        $data['class_status'] = $this->constant_details_model->get_list(111);
        $data['PERSONAL_CUSTODY_TYPE'] = $this->constant_details_model->get_list(304);
        $data['CUSTODY_TYPE'] = $this->constant_details_model->get_list(305);
        $data['sections']= $this->budget_section_model->get_all();
        $data['help']=$this->help;

        $this->load->view('template/template',$data);
    }

    // mkilani- فهرس الاصناف
    function table(){
        $this->output->cache(1440); // cache for one day
        $data['title']= 'فهرس الاصناف';
        $data['content']='classes_table';
        $data['classes'] = $this->{$this->MODEL_NAME}->get_list_table();
        $this->load->view('template/template',$data);
    }

    // table header
    function public_table_h(){
        $this->load->view('classes_table_h');
    }

    // mkilani - get all amount or one..
    function public_get_class_amount(){
        $class_id= $this->input->post('class_id');
        $amount = $this->{$this->MODEL_NAME}->get_class_amount($class_id);
        $this->return_json($amount);
    }

    // mkilani - delete all cache files from cache folder
    function public_delete_cache_files(){
        $action= $this->input->post('action');
        if($action!='del')
            die();
        $this->load->helper('directory');
        $path= './application/cache/';
        $map= directory_map($path);
        $original_files= array('.htaccess','index.html');
        $files_to_del= array_diff($map,$original_files);

        foreach($files_to_del as $file){
            @unlink($path.$file);
        }
        echo 1;
    }


    function public_select_class($txt){
        $this->load->helper('generate_list');

        add_css('combotree.css');

        add_js('jquery.tree.js');


        $data['title']=' شجرة الأصناف ';
        $data['content']='class_select';


        //     $resource =  $this->class_model->getList(null,null,4);
        $resource =  $this->class_model->getList(null);
        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{CLASS_ID}" data-curr="{CURR_ID}"  data-classtype="{CALSS_TYPE}"  ondblclick="javascript:select_class(\'{CLASS_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{CLASS_ID} : {CLASS_NAME_AR}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="class">'.generate_list($resource, $options, $template).'</ul>';
        $data['txt']=$txt;

        $this->load->view('template/view',$data);
    }

    /**
     * get account by id ..
     */
    function get_id(){

        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $result = $this->class_model->get($id,$type);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }
    function public_get_id(){

        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $result = $this->class_model->get($id,$type);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }
    /**
     * @param int $parent
     * @return mixed
     *
     * get accounts tree structure ..
     *
     */
    function _get_structure($parent = 0) {
        $result = $this->class_model->getList($parent);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['CLASS_ID']);
            $i++;
        }
        return $result;
    }
    function _post_validation(){
//the_year
     //   $this->print_error(date("Y"));
        $this->load->model('settings/constant_details_model');
        $per = $this->constant_details_model->get_list(130);

        if( ($this->CLASS_NAME_AR=='' ) or ($this->CLASS_UNIT=='' ) or $this->CLASS_UNIT_SUB=='' or $this->CLASS_UNIT_COUNT=='' or $this->CLASS_MIN=='' or $this->CLASS_MAX==''  or $this->CLASS_PURCHASING=='')  {
            $this->print_error('يجب ادخال جميع البيانات');

        }
         if ($this->CALSS_TYPE==1 and strlen($this->CLASS_ID)<8) {

            $this->print_error('الصنف الفرعي يتكون من 8 أرقام');
        }

        if ($this->IS_BUDGET==1 and $this->SECTION_NO=='')
            $this->print_error('يجب اختيار فصل الموازنة');

        if (($this->THE_YEAR==date("Y")) and ($this->CLASS_ACOUNT_TYPE==1) and ($this->CLASS_PURCHASING<$per[0]['CON_NAME'])) //متداول وسعره اقل من او يساوي 100
            $this->print_error('يرجى تعديل حالة الصنف الى متداول');

        if (($this->THE_YEAR==date("Y")) and ($this->CLASS_ACOUNT_TYPE==1) and ($this->CLASS_PURCHASING>=$per[0]['CON_NAME']) and ($this->AVERAGE_LIFE_SPAN==1)) //متداول وسعره اقل من او يساوي 100
            $this->print_error('يجب أن يكون الصنف متداول حسب عمره الإنتاجي');

        if (($this->CLASS_ACOUNT_TYPE==1) and ($this->ACCOUNT_ID=='') ) //صنف ثابت يجب تحديد رقم الحساب
            $this->print_error('يجب إدخال رقم الحساب الرئيسي للصنف الثابت');

        if (($this->p_custody_type ==3)  and ($this->p_pledge_age=='') )
            $this->print_error('يجب إدخال العمر الزمني لانتهاء الصنف بالشهور ');
    }
    /**
     * create action : insert new account data ..
     * receive post data of account
     *
     */

    function create(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');
        $this->_post_validation();
        $result= $this->class_model->create($this->_postedData());
        $this->Is_success($result);
        $this->return_json($result);

    }



    function get_class($click = false){

        $user =($this->input->post('user'))?$this->input->post('user'):0;
        $click =($this->input->post('click'))?$this->input->post('click'):false;


        $this->load->helper('generate_list');

        //   $resource =  $this->_get_user_class($user,0,0,$click);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );



        $template = '<li><span data-id="{CLASS_ID}" data-classtype="{CALSS_TYPE}"  {ONDBLCLICK}>{IS_CHECKED}{CLASS_NAME_AR}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="class_tree">'.generate_list($resource, $options, $template).'</ul></li></ul>';


        $data['user']=$user;
        $this->load->view('class_tree',$data);

    }


    /**
     * @return mixed
     * return gcc structure tree json type
     */
    function public_get_class_json($add=0, $start=0){

        if($add==1){
            $arr= array(array('CLASS_PARENT_ID'=>0,'CLASS_ID'=>'-1','CLASS_NAME_AR'=>' لا يوجد صنف ', 'subs'=>array()));
            $array=$this->_get_structure($start);
            array_splice($array, 0, 0, $arr);
            $result = json_encode($array);
        }else{
            $result = json_encode($this->_get_structure(0));
        }
        $result=str_replace('subs','children',$result);
        $result=str_replace('CLASS_ID','id',$result);
        $result=str_replace('CLASS_NAME_AR','text',$result);
        echo $result;

    }

    function public_index($text= null, $class_status= null, $type =null, $id='', $name ='',$parent_id='',$grand_id='',$page= 1){
        $type= $type==-1 ? null: $type;
        $id= $id==-1 ? null: $id;
        $name= $name==-1 ? null: urldecode($name);

        $data['text']= $this->input->get_post('text') ? $this->input->get_post('text') : $text;
        $data['id']=   $this->input->get_post('id')   ? $this->input->get_post('id')   : $id;
        $data['name_ar']= $this->input->get_post('name_ar') ? $this->input->get_post('name_ar') : $name;
        $data['name_en']= $this->input->get_post('name_en') ? $this->input->get_post('name_en') : $type;
        $data['parent_id']= $this->input->get_post('parent_id') ? $this->input->get_post('parent_id') : $parent_id;
        $data['grand_id']= $this->input->get_post('grand_id') ? $this->input->get_post('grand_id') : $grand_id;
        $data['class_status']= $this->input->get_post('class_status') ? $this->input->get_post('class_status') : $class_status;
        $data['page']= $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        $data['class_parent_id'] = $this->{$this->MODEL_NAME}->getAllParents();



        $data['grands'] = $this->{$this->MODEL_NAME}->getAllGrandsClasses();
        //   $x= (isset( $grand_id) && $grand_id !='')?  $grand_id:$grand_id ;
        $data['class_parent_id'] = $this->{$this->MODEL_NAME}->getAllParentsClasses(null);




        $data['content']='classes_index';
        add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['txt']=$text;
        $this->load->view('template/view',$data);
    }
    function public_get_parents($grand_id=''){
        $grand_id= $this->input->get_post('grand_id') ? $this->input->get_post('grand_id') : $grand_id;
        $x= (isset( $grand_id) && $grand_id !='')?  $grand_id :null ;
        $parent_ids = $this->{$this->MODEL_NAME}->getAllParentsProjects($x);

        $select= "  <option></option>";
        foreach($parent_ids as $row) {
            $select.= "<option value='{$row['PARENT_ID']}'>"."{$row['PARENT_ID']}:{$row['CLASS_NAME_AR']}"."</option>";
        }
        echo $select;

    }

    function public_get_parents_classes($grand_id=''){
        $grand_id= $this->input->get_post('grand_id') ? $this->input->get_post('grand_id') : $grand_id;
        $x= (isset( $grand_id) && $grand_id !='')?  $grand_id :null ;
        $parent_ids = $this->{$this->MODEL_NAME}->getAllParentsClasses($x);

        $select= "  <option></option>";
        foreach($parent_ids as $row) {
            $select.= "<option value='{$row['PARENT_ID']}'>"."{$row['PARENT_ID']}:{$row['CLASS_NAME_AR']}"."</option>";
        }
        echo $select;

    }

    function public_project_index($text= null, $type =null, $id='', $name ='',$parent_id='',$grand_id='', $page= 1){
        $data['text']= $this->input->get_post('text') ? $this->input->get_post('text') : $text;
        $data['id']=   $this->input->get_post('id')   ? $this->input->get_post('id')   : $id;
        $data['name_ar']= $this->input->get_post('name_ar') ? $this->input->get_post('name_ar') : $name;
        $data['name_en']= $this->input->get_post('name_en') ? $this->input->get_post('name_en') : $type;
        $data['parent_id']= $this->input->get_post('parent_id') ? $this->input->get_post('parent_id') : $parent_id;
        $data['grand_id']= $this->input->get_post('grand_id') ? $this->input->get_post('grand_id') : $grand_id;


        $data['page']= $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        $data['grands'] = $this->{$this->MODEL_NAME}->getAllGrands();
        //   $x= (isset( $grand_id) && $grand_id !='')?  $grand_id:$grand_id ;
        $data['class_parent_id'] = $this->{$this->MODEL_NAME}->getAllParentsProjects(null);
        /*  $d="";
                 foreach($data['class_parent_id'] as $row) {
            $d.= "<option value='{$row['PARENT_ID']}'>"."{$row['PARENT_ID']}:{$row['CLASS_NAME_AR']}"."</option>";
         }
             $data['d']=$d;*/
        $data['content']='classes_project_index';
        add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['txt']=$text;
        $this->load->view('template/view',$data);
    }
    function public_get_project_index($project_serial='',$text= null, $type =null, $id='', $name ='',$parent_id='',$grand_id='', $page= 1){

        $project_serial = str_ireplace("%20","",$project_serial);

        $data['text']= $this->input->get_post('text') ? $this->input->get_post('text') : $text;
        $data['id']=   $this->input->get_post('id')   ? $this->input->get_post('id')   : $id;
        $data['name_ar']= $this->input->get_post('name_ar') ? $this->input->get_post('name_ar') : $name;
        $data['name_en']= $this->input->get_post('name_en') ? $this->input->get_post('name_en') : $type;
        $data['parent_id']= $this->input->get_post('parent_id') ? $this->input->get_post('parent_id') : $parent_id;
        $data['grand_id']= $this->input->get_post('grand_id') ? $this->input->get_post('grand_id') : $grand_id;
        $data['project_serial']= $this->input->get_post('project_serial') ? $this->input->get_post('project_serial') : $project_serial;

     //   echo $project_serial;

        $data['page']= $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        $data['grands'] = $this->{$this->MODEL_NAME}->getAllGrands();
        //   $x= (isset( $grand_id) && $grand_id !='')?  $grand_id:$grand_id ;
        $data['class_parent_id'] = $this->{$this->MODEL_NAME}->getAllParentsProjects(null);
        /*  $d="";
                 foreach($data['class_parent_id'] as $row) {
            $d.= "<option value='{$row['PARENT_ID']}'>"."{$row['PARENT_ID']}:{$row['CLASS_NAME_AR']}"."</option>";
         }
             $data['d']=$d;*/
        $data['content']='classes_project_items_index';
        add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['txt']=$text;
        $this->load->view('template/view',$data);
    }
    function project_index($text= null, $type =null, $id='', $name ='',$parent_id='',$grand_id='', $page= 1){
        $data['text']= $this->input->get_post('text') ? $this->input->get_post('text') : $text;
        $data['id']=   $this->input->get_post('id')   ? $this->input->get_post('id')   : $id;
        $data['name_ar']= $this->input->get_post('name_ar') ? $this->input->get_post('name_ar') : $name;
        $data['name_en']= $this->input->get_post('name_en') ? $this->input->get_post('name_en') : $type;
        $data['parent_id']= $this->input->get_post('parent_id') ? $this->input->get_post('parent_id') : $parent_id;
        $data['grand_id']= $this->input->get_post('grand_id') ? $this->input->get_post('grand_id') : $grand_id;


        $data['page']= $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        $data['grands'] = $this->{$this->MODEL_NAME}->getAllGrands();
        //   $x= (isset( $grand_id) && $grand_id !='')?  $grand_id:$grand_id ;
        $data['class_parent_id'] = $this->{$this->MODEL_NAME}->getAllParentsProjects(null);
        /*  $d="";
                 foreach($data['class_parent_id'] as $row) {
            $d.= "<option value='{$row['PARENT_ID']}'>"."{$row['PARENT_ID']}:{$row['CLASS_NAME_AR']}"."</option>";
         }
             $data['d']=$d;*/
       // $data['page']=$page;
        $data['title']='أصناف المشاريع';
        $data['content']='classes_projects_index';
        add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['txt']=$text;
        $this->load->view('template/template',$data);
    }

    function public_get_classes($prm= array()){
        add_js('jquery.hotkeys.js');
        if(!$prm) //add_percent_sign

            $prm= array('text'=>$this->input->get_post('text'),
                'id'=>$this->input->get_post('id'),
                'name_ar'=>$this->input->get_post('name_ar'),
                'name_en'=>$this->input->get_post('name_en'),
                'parent_id'=>$this->input->get_post('parent_id'),
                'grand_id'=>$this->input->get_post('grand_id'),
                'class_status'=>$this->input->get_post('class_status'),
                'page'=>$this->input->get_post('page')
            );

        $this->load->library('pagination');

        $page= $prm['page'] ? $prm['page']: 1;

        $config['base_url'] = base_url("stores/classes/public_index/?class_status={$prm['class_status']}&text={$prm['text']}&id={$prm['id']}&name_ar={$prm['name_ar']}&name_en={$prm['name_en']}&parent_id={$prm['parent_id']}&grand_id={$prm['grand_id']}");

        $prm['id']= $prm['id'] != -1 ? $prm['id']: null;
        $prm['name_ar']= $prm['name_ar'] !=-1 ? add_percent_sign($prm['name_ar']): null;
        $prm['name_en']= $prm['name_en'] !=-1 ? $prm['name_en']: null;
        $prm['parent_id']= $prm['parent_id'] !=-1 ? $prm['parent_id']: null;
        $prm['grand_id']= $prm['grand_id'] !=-1 ? $prm['grand_id']: null;
        $prm['class_status']= $prm['class_status'] !=-1 ? $prm['class_status']: null;

        $count_rs = $this->{$this->MODEL_NAME}->get_count($prm['id'], $prm['name_ar'], $prm['name_en'], $prm['parent_id'], $prm['grand_id'], $prm['class_status'],1);

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;

        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;
        $config['page_query_string']=true;
        $config['query_string_segment']='page';

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['get_list'] =$this->{$this->MODEL_NAME}->get_lists($prm['id'], $prm['name_ar'] , $prm['name_en'], $prm['parent_id'], $prm['grand_id'], $prm['class_status'], $offset, $row,1 );

        $this->load->view('class_page',$data);
    }
    function public_get_project_classes($prm= array()){
    add_js('jquery.hotkeys.js');
    if(!$prm) //add_percent_sign

        $prm= array('text'=>$this->input->get_post('text'),
            'id'=>$this->input->get_post('id'),
            'name_ar'=>$this->input->get_post('name_ar'),
            'name_en'=>$this->input->get_post('name_en'),
            'parent_id'=>$this->input->get_post('parent_id'),
            'grand_id'=>$this->input->get_post('grand_id'),
            'page'=>$this->input->get_post('page')
        );
//print_r($prm);
    $sql ="";

    $sql .= $prm['name_ar'] !=null ?" and C.CLASS_NAME_AR like '%{$prm['name_ar']}%' ":"";
    $sql .= $prm['name_en'] !=null ?" and upper(C.CLASS_NAME_EN) like upper('%{$prm['name_en']}%') ":"";
    $sql .= $prm['id'] !=null ?" and C.CLASS_ID like '%{$prm['id']}%' ":"";
    $sql .= $prm['parent_id'] !=null ?" and C.CLASS_PARENT_ID  = '{$prm['parent_id']}' ":"";
    //$sql .= isset($prm['parent_id']) && $prm['parent_id'] !=null ? " and C.CLASS_ID like '{$prm['parent_id']}%' ":"";
    $sql .= $prm['grand_id'] !=null ?" and C.CLASS_ID like '{$prm['grand_id']}%' ":"";


    $this->load->library('pagination');

    $page= $prm['page'] ? $prm['page']: 1;

    $config['base_url'] = base_url("stores/classes/public_project_index/?text={$prm['text']}&id={$prm['id']}&name_ar={$prm['name_ar']}&name_en={$prm['name_en']}&parent_id={$prm['parent_id']}&grand_id={$prm['grand_id']}");

    $prm['id']= $prm['id'] != -1 ? $prm['id']: null;
    $prm['name_ar']= $prm['name_ar'] !=-1 ? $prm['name_ar']: null;
    $prm['name_en']= $prm['name_en'] !=-1 ? $prm['name_en']: null;
    $prm['parent_id']= $prm['parent_id'] !=-1 ? $prm['parent_id']: null;
    $count_rs = $this->{$this->MODEL_NAME}->get_project_item_count($sql);


    $config['use_page_numbers'] = TRUE;
    $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
    $config['per_page'] = $this->page_size;
    $config['num_links'] = 20;
    $config['cur_page']=$page;
    $config['page_query_string']=true;
    $config['query_string_segment']='page';

    $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
    $config['full_tag_close'] = '</ul></div>';
    $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
    $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><span><b>';
    $config['cur_tag_close'] = "</b></span></li>";

    $this->pagination->initialize($config);

    $offset = ((($page-1) * $config['per_page']) );
    $row = (($page * $config['per_page'])  );

    $data['get_list'] = $this->{$this->MODEL_NAME}->get_project_item_list($sql, $offset, $row );


    $this->load->view('class_project_page',$data);
}

    function public_get_project_items($prm= array()){
        add_js('jquery.hotkeys.js');
        if(!$prm) //add_percent_sign

            $prm= array('text'=>$this->input->get_post('text'),
                'id'=>$this->input->get_post('id'),
                'name_ar'=>$this->input->get_post('name_ar'),
                'name_en'=>$this->input->get_post('name_en'),
                'parent_id'=>$this->input->get_post('parent_id'),
                'grand_id'=>$this->input->get_post('grand_id'),
                'project_serial'=>$this->input->get_post('project_serial'),
                'page'=>$this->input->get_post('page')
            );
//print_r($prm);
        $sql ="";

        $sql .= $prm['name_ar'] !=null ?" and C.CLASS_NAME_AR like '%{$prm['name_ar']}%' ":"";
        $sql .= $prm['name_en'] !=null ?" and upper(C.CLASS_NAME_EN) like upper('%{$prm['name_en']}%') ":"";
        $sql .= $prm['id'] !=null ?" and C.CLASS_ID like '%{$prm['id']}%' ":"";
        $sql .= $prm['parent_id'] !=null ?" and C.CLASS_PARENT_ID  = '{$prm['parent_id']}' ":"";
        //$sql .= isset($prm['parent_id']) && $prm['parent_id'] !=null ? " and C.CLASS_ID like '{$prm['parent_id']}%' ":"";
        $sql .= $prm['grand_id'] !=null ?" and C.CLASS_ID like '{$prm['grand_id']}%' ":"";
        $sql .= $prm['project_serial'] !=null ?" and  C.CLASS_ID in (SELECT D.CLASS_ID FROM PROJECTS_FILE_TB PR JOIN PROJECTS_FILE_DETAIL_TB D ON PR.PROJECT_SERIAL = D.PROJECT_SERIAL WHERE PR.PROJECT_TEC_CODE = '{$prm['project_serial']}' )  ":"";

        $this->load->library('pagination');

        $page= $prm['page'] ? $prm['page']: 1;

        $config['base_url'] = base_url("stores/classes/public_get_project_index/?text={$prm['text']}&id={$prm['id']}&name_ar={$prm['name_ar']}&name_en={$prm['name_en']}&parent_id={$prm['parent_id']}&grand_id={$prm['grand_id']}&project_serial={$prm['project_serial']}");

        $prm['id']= $prm['id'] != -1 ? $prm['id']: null;
        $prm['name_ar']= $prm['name_ar'] !=-1 ? $prm['name_ar']: null;
        $prm['name_en']= $prm['name_en'] !=-1 ? $prm['name_en']: null;
        $prm['parent_id']= $prm['parent_id'] !=-1 ? $prm['parent_id']: null;
        $prm['project_serial']= $prm['project_serial'] !=-1 ? $prm['project_serial']: null;
        $count_rs = $this->{$this->MODEL_NAME}->get_project_items_count($sql);


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;
        $config['page_query_string']=true;
        $config['query_string_segment']='page';

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['get_list'] = $this->{$this->MODEL_NAME}->get_project_items_list($sql, $offset, $row );


        $this->load->view('class_project_page',$data);
    }

    function get_project_classes($prm= array()){
        add_js('jquery.hotkeys.js');
        if(!$prm) //add_percent_sign

            $prm= array('text'=>$this->input->get_post('text'),
                'id'=>$this->input->get_post('id'),
                'name_ar'=>$this->input->get_post('name_ar'),
                'name_en'=>$this->input->get_post('name_en'),
                'parent_id'=>$this->input->get_post('parent_id'),
                'grand_id'=>$this->input->get_post('grand_id'),
                'page'=>$this->input->get_post('page')
            );

        $sql ="";

        $sql .= $prm['name_ar'] !=null ?" and C.CLASS_NAME_AR like '%{$prm['name_ar']}%' ":"";
        $sql .= $prm['name_en'] !=null ?" and upper(C.CLASS_NAME_EN) like upper('%{$prm['name_en']}%') ":"";
        $sql .= $prm['id'] !=null ?" and C.CLASS_ID like '%{$prm['id']}%' ":"";
        $sql .= $prm['parent_id'] !=null ?" and C.CLASS_PARENT_ID  = '{$prm['parent_id']}' ":"";
        //$sql .= isset($prm['parent_id']) && $prm['parent_id'] !=null ? " and C.CLASS_ID like '{$prm['parent_id']}%' ":"";
        $sql .= $prm['grand_id'] !=null ?" and C.CLASS_ID like '{$prm['grand_id']}%' ":"";


        $this->load->library('pagination');

        $page= $prm['page'] ? $prm['page']: 1;

        $config['base_url'] = base_url("stores/classes/project_index/?text={$prm['text']}&id={$prm['id']}&name_ar={$prm['name_ar']}&name_en={$prm['name_en']}&parent_id={$prm['parent_id']}&grand_id={$prm['grand_id']}");

        $prm['id']= $prm['id'] != -1 ? $prm['id']: null;
        $prm['name_ar']= $prm['name_ar'] !=-1 ? $prm['name_ar']: null;
        $prm['name_en']= $prm['name_en'] !=-1 ? $prm['name_en']: null;
        $prm['parent_id']= $prm['parent_id'] !=-1 ? $prm['parent_id']: null;
        $count_rs = $this->{$this->MODEL_NAME}->get_project_item_count($sql);


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;
        $config['page_query_string']=true;
        $config['query_string_segment']='page';

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['get_list'] = $this->{$this->MODEL_NAME}->get_project_item_list($sql, $offset, $row );


        $this->load->view('class_projects_page',$data);
    }
    // Barakat
    function project_item_price(){
        $result = $this->{$this->MODEL_NAME}->get_project_item_price($this->p_id );

        $this->return_json($result);

    }
    /**
     * @param int $parent
     * @return mixed
     *
     * get user tree accounts ..
     *

    function _get_user_accounts($user,$parent ,$level = 0,$click=false) {

    $result = $this->accounts_model->getList($parent,$user);
    $i = 0;
    $level++;

    foreach($result as $key => $item)
    {
    if($level >= 5 && $item['ACOUNT_TYPE'] == '2')
    $result[$i]['IS_CHECKED'] ="<input  type=\"checkbox\" name=\"account_no\" {$item['IS_CHECKED']} value=\"{$item['ACOUNT_ID']}\" />";
    if($click && $level >= 5 && $item['ACOUNT_TYPE'] == '2')
    $result[$i]['ONDBLCLICK'] = " ondblclick=\"javascript:account_get('{$item['ACOUNT_ID']}',this);\" ";

    $result[$i]['subs'] = $this->_get_user_accounts($user,$item['ACOUNT_ID'],$level,$click);
    $i++;
    }



    return $result;
    }
     */

    /**
     * update adapt
     *

    function update_adapt(){

    $ADAPT = $this->input->post('adapt');
    $this->ACOUNT_ID= $this->input->post('class_id');

    $ADAPT =intval($ADAPT) == 1?0:1;

    $result = array(
    array('name'=>'ACOUNT_ID','value'=>$this->ACOUNT_ID ,'type'=>'','length'=>-1),
    array('name'=>'ADOPT','value'=>$ADAPT,'type'=>'','length'=>-1)
    );

    $result= $this->accounts_model->update_adapt($result);
    $this->Is_success($result);
    $this->return_json($result);

    }
     */

    /**
     * edit action : update exists account data ..
     * receive post data of account
     * depended on account prm key
     */
    function edit(){
        //if(!$this->check_db_for_stores())
            //die('CLOSED..');
        $this->_post_validation();
        echo $this->class_model->edit($this->_postedData());

    }

    /**
     * delete action : delete account data ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');

        $this->IsAuthorized();

        if(is_array($id)){
            foreach($id as $val){
                echo   $this->class_model->delete($val);
            }
        }else{
            echo $this->class_model->delete($id);
        }

    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData(){

        $this->CLASS_ID = $this->input->post('class_id');
        $this->CLASS_PARENT_ID= $this->input->post('class_parent_id');
        $this->CLASS_NAME_EN= $this->input->post('class_name_en');
        $this->CLASS_NAME_AR= $this->input->post('class_name_ar');
        $this->CALSS_DESCRIPTION= $this->input->post('calss_description');
        $this->CALSS_TYPE= $this->input->post('calss_type');
        $this->CLASS_MIN= $this->input->post('class_min');
        $this->CLASS_UNIT= $this->input->post('class_unit');
        $this->CLASS_MAX= $this->input->post('class_max');
        $this->CLASS_OUTDOOR= $this->input->post('class_outdoor');
        $this->ACCOUNT_ID= $this->input->post('account_id');
        $this->CLASS_PURCHASING= $this->input->post('class_purchasing');
        $this->CLASS_PAYMENT= $this->input->post('class_payment');
        $this->CODE= $this->input->post('code');
        $this->OLD_CLASS_ID= $this->input->post('old_class_id');
        $this->CURR_ID= $this->input->post('curr_id');
        $this->RESPONSIBLE_IN_OUT= $this->input->post('responsible_in_out');
        $this->CLASS_UNIT_SUB= $this->input->post('class_unit_sub');
        $this->CLASS_UNIT_COUNT= $this->input->post('class_unit_count');
        $this->CLASS_ACOUNT_TYPE= $this->input->post('class_acount_type');
        $this->ACCOUNT_TYPE= $this->input->post('account_type');
        $this->EXP_ACCOUNT_CUST= $this->input->post('exp_account_cust');
        $this->DESTRUCTION_TYPE= $this->input->post('destruction_type');
        $this->DESTRUCTION_PERCENT= $this->input->post('destruction_percent');
        $this->DESTRUCTION_ACCOUNT_ID= $this->input->post('destruction_account_id');
        $this->AVERAGE_LIFE_SPAN= $this->input->post('average_life_span');
        $this->AVERAGE_LIFE_SPAN_TYPE= $this->input->post('average_life_span_type');
        $this->IS_BUDGET= $this->input->post('is_budget');
        $this->CLASS_STATUS= $this->input->post('class_status');
        $this->SECTION_NO= $this->input->post('section_no');
        $this->CLASS_MIN_REQUEST= $this->input->post('class_min_request');
        $this->USED_PERCENT= $this->input->post('used_percent');

        $result = array(
            array('name'=>'CLASS_ID','value'=>$this->CLASS_ID ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_PARENT_ID','value'=>$this->CLASS_PARENT_ID,'type'=>'','length'=>-1),
            array('name'=>'CLASS_NAME_EN','value'=>$this->CLASS_NAME_EN,'type'=>'','length'=>-1),
            array('name'=>'CLASS_NAME_AR','value'=>$this->CLASS_NAME_AR,'type'=>'','length'=>-1),
            array('name'=>'CALSS_DESCRIPTION','value'=>$this->CALSS_DESCRIPTION,'type'=>'','length'=>-1),
            array('name'=>'CALSS_TYPE','value'=>$this->CALSS_TYPE,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$this->CLASS_UNIT,'type'=>'','length'=>-1),
            array('name'=>'CLASS_MIN','value'=>$this->CLASS_MIN,'type'=>'','length'=>-1),
            array('name'=>'CLASS_MAX','value'=>$this->CLASS_MAX,'type'=>'','length'=>-1),
            array('name'=>'CLASS_OUTDOOR','value'=>$this->CLASS_OUTDOOR,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$this->ACCOUNT_ID,'type'=>'','length'=>-1),
            array('name'=>'CLASS_PURCHASING','value'=>$this->CLASS_PURCHASING,'type'=>'','length'=>-1),
            array('name'=>'CLASS_PAYMENT','value'=>$this->CLASS_PAYMENT,'type'=>'','length'=>-1),
            array('name'=>'CODE','value'=>$this->CODE,'type'=>'','length'=>-1),
            array('name'=>'OLD_CLASS_ID','value'=>$this->OLD_CLASS_ID,'type'=>'','length'=>-1),
            array('name'=>'CURR_ID','value'=>$this->CURR_ID,'type'=>'','length'=>-1),
            array('name'=>'RESPONSIBLE_IN_OUT','value'=>$this->RESPONSIBLE_IN_OUT,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT_SUB','value'=>$this->CLASS_UNIT_SUB,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT_COUNT','value'=>$this->CLASS_UNIT_COUNT,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ACOUNT_TYPE','value'=>$this->CLASS_ACOUNT_TYPE,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_TYPE','value'=>$this->ACCOUNT_TYPE,'type'=>'','length'=>-1),
            array('name'=>'EXP_ACCOUNT_CUST','value'=>$this->EXP_ACCOUNT_CUST,'type'=>'','length'=>-1),
            array('name'=>'DESTRUCTION_TYPE','value'=>$this->DESTRUCTION_TYPE,'type'=>'','length'=>-1),
            array('name'=>'DESTRUCTION_PERCENT','value'=>$this->DESTRUCTION_PERCENT,'type'=>'','length'=>-1),
            array('name'=>'DESTRUCTION_ACCOUNT_ID','value'=>$this->DESTRUCTION_ACCOUNT_ID,'type'=>'','length'=>-1),
            array('name'=>'AVERAGE_LIFE_SPAN','value'=>$this->AVERAGE_LIFE_SPAN,'type'=>'','length'=>-1),
            array('name'=>'AVERAGE_LIFE_SPAN_TYPE','value'=>$this->AVERAGE_LIFE_SPAN_TYPE,'type'=>'','length'=>-1),
            array('name'=>'IS_BUDGET','value'=>$this->IS_BUDGET,'type'=>'','length'=>-1),
            array('name'=>'CLASS_STATUS','value'=>$this->CLASS_STATUS,'type'=>'','length'=>-1),
            array('name'=>'SECTION_NO','value'=>$this->SECTION_NO,'type'=>'','length'=>-1),
            array('name'=>'CLASS_MIN_REQUEST','value'=>$this->CLASS_MIN_REQUEST,'type'=>'','length'=>-1),
            array('name'=>'CUSTODY_TYPE','value'=>$this->p_custody_type,'type'=>'','length'=>-1),
            array('name'=>'PERSONAL_CUSTODY_TYPE','value'=>$this->p_personal_custody_type,'type'=>'','length'=>-1),
            array('name'=>'USED_PERCENT','value'=>$this->p_used_percent,'type'=>'','length'=>-1),
            array('name'=>'PLEDGE_AGE','value'=>$this->p_pledge_age,'type'=>'','length'=>-1),
        );

        return $result;
    }
}