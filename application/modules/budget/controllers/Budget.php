<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/31/14
 * Time: 8:42 AM
 */
class Budget extends MY_Controller{


    function  __construct(){
        parent::__construct();
        $this->load->model('budget_model');
        $this->load->model('budget_constant_model');
        $this->load->model('budget_chapter_model');
        $this->load->model('budget_section_model');
        $this->load->model('budget_items_model');
        $this->load->model('budget_items_details_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('budget_tree_model');

    }

    /**
     *
     * index action perform all functions in view of budget_index view
     * from this view , can show budget tree , insert new (chapter,section,item) , update exists one and delete other ..
     *
     */
    function index(){

        $this->load->helper('generate_list');

        add_js('jquery.tree.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['title']='الموازنة';
        $data['content']='budget_index';

        $data['tree']= $this->public_get_bedget();

        $data['UNIT_NOS'] = $this->constant_details_model->get_list(133);
        $data['ITEM_TYPE'] = $this->constant_details_model->get_list(96);
        $data['BUDGET_TYPES'] = $this->constant_details_model->get_list(124);
        $data['CHAIN_TYPES'] = $this->constant_details_model->get_list(13);

        $this->load->view('template/template',$data);

    }

    function table(){

        $this->load->model('settings/gcc_branches_model');

        add_css('combotree.css');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');


        $data['rows'] =$this->budget_model->budget_balance_no_branch(null,null,null,null,$this->START_DATE,$this->END_DATE);
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['chapters'] = $this->budget_chapter_model->get_all();

        $data['title']='فهرس الموازنة';
        $data['content']='table_index';
        $this->load->view('template/template',$data);

    }

    function public_table_details($cno = null){

        $cno = isset($this->p_cno)?$this->p_cno : $cno;
        $data['rows'] =$this->budget_model->budget_exp_rev_up_tb_balance(null,$cno);
        $data['title']='فهرس الموازنة';
        $this->load->view('table_details_index',$data);

    }

    function public_get_bedget($has_history =-1){


        $resource =$this->_get_structure($has_history);


        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li><span data-id="{PPK}"  data-tser="{T_SER}" ondblclick="javascript:budget_get(\'{PPK}\');"><i class="glyphicon glyphicon-minus-sign"></i>{T_SER} :{NAME}</span>{SUBS}</li>';
        return '<ul class="tree" id="budget">'.generate_list($resource, $options, $template).'</ul>';

      //  return   '<ul class="tree" id="budget"><li><span data-id="0"><i class="glyphicon glyphicon-minus-sign"></i>الموزانة</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';

    }
    function public_get_bedget_history(){


        $resource =$this->_get_structure_history();


        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

       $template = '<li><span data-id="{NO}"  data-tser="{T_SER}" ondblclick="javascript:budget_get(\'{NO}\');"><i class="glyphicon glyphicon-minus-sign"></i>{T_SER} :{NAME}</span>{SUBS}</li>';
        return '<ul class="tree" id="budget">'.generate_list($resource, $options, $template).'</ul>';

        //  return   '<ul class="tree" id="budget"><li><span data-id="0"><i class="glyphicon glyphicon-minus-sign"></i>الموزانة</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';

    }

    /**
     * @return mixed
     * return budget tree json type
     */
    function public_get_budget_json($has_history =-1){


        $arr =$this->_get_structure($has_history);

        array_unshift($arr, array('NO'=>'','NAME'=>''));

        $result = json_encode($arr);
        $result=str_replace('subs','children',$result);
        $result=str_replace('T_SER','id',$result);
        $result=str_replace('NAME','text',$result);
        echo $result;


    }

    /**
     * @return mixed
     * return budget tree json type
     */
    function public_get_sections($chapter = 0){


        $chapter = $this->input->post('no')?$this->input->post('no'):$chapter;

        $arr = $this->budget_section_model->get_list($chapter);

        array_unshift($arr, array('NO'=>'','NAME'=>''));

        $result = json_encode($arr);

        $result=str_replace('subs','children',$result);
        $result=str_replace('NO','id',$result);
        $result=str_replace('NAME','text',$result);

        echo $result;


    }

    function public_get_items($section = 0){

        $section = $this->input->post('no')?$this->input->post('no'):$section;

        $arr = $this->budget_items_model->get_list($section);

        array_unshift($arr, array('NO'=>'','NAME'=>''));

        $result = json_encode($arr);

        $result=str_replace('subs','children',$result);
        $result=str_replace('NO','id',$result);
        $result=str_replace('NAME','text',$result);

        echo $result;
    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get  tree structure ..
     *
     */

    function _get_structure($parent ) {
        $result = $this->budget_tree_model->getList($parent);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['T_SER']);
            $i++;
        }
        return $result;
    }


    function _get_structure_history() {

        //chapters
        $result = $this->budget_chapter_model->get_all(1);

        $i = 0;
        foreach($result as $key => $item)
        {

            $parent = $item['NO'];

            $ix=0;
            //sections
            $result2 = $this->budget_section_model->get_list($parent,0,1);

            foreach($result2 as $key => $item)
            {
                $parent2 = $item['NO'];

                //items
                if(false){
                    $result3 = $this->budget_items_model->get_list($parent2,0,1);

                    $result2[$ix]['subs'] = $result3;

                    $ix++;
                }

            }
            $result[$i]['subs'] = $result2;


            $i++;
        }

        return $result;
    }


  /* function _get_structure($has_history = null,$includeItem = true) {

        //chapters
        $result = $this->budget_chapter_model->get_all($has_history);

        $i = 0;
        foreach($result as $key => $item)
        {

            $parent = $item['NO'];

            $ix=0;
            //sections
            $result2 = $this->budget_section_model->get_list($parent,0,$has_history);

            foreach($result2 as $key => $item)
            {
                $parent2 = $item['NO'];

                //items
                if($includeItem){
                    $result3 = $this->budget_items_model->get_list($parent2,0,$has_history);

                    $result2[$ix]['subs'] = $result3;

                    $ix++;
                }

            }
            $result[$i]['subs'] = $result2;


            $i++;
        }

        return $result;
    }
*/

    function get_budget(){

        $user =($this->input->post('user'))?$this->input->post('user'):0;


        $this->load->helper('generate_list');

        $resource =  $this->_get_user_structure($user);


        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li><span data-id="{NO}" {ONDBLCLICK}><input {DATA_TYPE} type="checkbox" name="section_no" {IS_CHECKED} value="{NO}" />{NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="budget"><li><span data-id="0"><i class="glyphicon glyphicon-minus-sign"></i>الموزانة</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';


        $data['user']=$user;
        $this->load->view('permission_tree',$data);

    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get user tree structure ..
     *
     */
    function _get_user_structure($user) {

        //chapters
        $result = $this->budget_chapter_model->get_all();

        $i = 0;
        foreach($result as $key => $item)
        {

            $parent = $item['NO'];
            $result[$i]['DATA_TYPE'] = 'data-type="chapter"';

            //sections
            $result2 = $this->budget_section_model->get_list($parent,$user);
            $ix = 0;
            foreach($result2 as $key => $item)
            {
                $result2[$ix]['ONDBLCLICK'] = " ondblclick=\"javascript:budget_permission('{$item['NO']}',this);\" ";
                $result[$i]['subs'] = $result2;
                $ix++;
            }
            $i++;
        }


        return $result;
    }



    /**
     * delete action : delete (chapter , section , item) data ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');
        $level =$this->input->post('level');

        $this->IsAuthorized();
       // $this->print_error($id."..".$level);
        if($level == 2){
            echo  $this->budget_constant_model->delete($id);
        }else if($level == 3){
            echo  $this->budget_chapter_model->delete($id);
        }else if($level == 4){
            echo $this->budget_section_model->delete($id);
        }else if($level == 5){
            echo $this->budget_items_model->delete($id);
        }/*else if($level == 4){
            echo $this->budget_items_details_model->delete($id);
        }*/

    }


    /**
     * get budget (chapter , section , item ) by id ..
     */
    function get_id(){

        $id = $this->input->post('id');
     //   echo $id."DDD";
        $level = $this->input->post('level') ;
//echo $level;
        if($level == 2){

            $result = $this->budget_constant_model->get($id);
          //  print_r( $result);
        }else if($level == 3){
            $result = $this->budget_chapter_model->get($id);
        }else  if($level == 4){
            $result = $this->budget_section_model->get($id);
        }else if($level == 5){
            $result = $this->budget_items_model->get($id);
            $result['details'] = $this->budget_items_details_model->get_list($id);
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }


    /**
     * create action : insert new budget data ..
     * receive post data of budget
     *
     */
    function create(){

        $level =$this->input->post('level');
       // $this->print_error($level);

        if($level == 1){
            $result= $this->budget_constant_model->create($this->_constant_postedData('create'));
        }else if($level == 2){
            $result= $this->budget_chapter_model->create($this->_chapter_postedData('create'));
        }else if($level == 3){
            $result= $this->budget_section_model->create($this->_section_postedData('create'));
        }else if($level == 4){
            $result= $this->budget_items_model->create($this->_item_postedData('create'));
        } if($level == 5){
            $result= $this->budget_items_details_model->create($this->_item_details_postedData('create'));
        }

        $this->Is_success($result);
        $this->return_json($result);

    }

    /**
     * edit action : update exists budget data ..
     * receive post data of budget
     *
     */
    function edit(){

        $level =$this->input->post('level');
       // $BUDGET_TYPE= $this->input->post('budget_type');
      //  $this->print_error("fff".$this->p_budget_type[0]);
     //   exit;

        if($level == 2){

            $result= $this->budget_constant_model->edit($this->_constant_postedData());
            $this->Is_success($result);
            echo $result;
        }else if($level == 3){

            $result= $this->budget_chapter_model->edit($this->_chapter_postedData());
            $this->Is_success($result);
            echo $result;
        }else if($level == 4){
            $result= $this->budget_section_model->edit($this->_section_postedData());
            $this->Is_success($result);
            echo $result;
        }else if($level == 5){
            $result= $this->budget_items_model->edit($this->_item_postedData());
            $this->Is_success($result);
            echo $result;
        }else  if($level == 6){
            $result= $this->budget_items_details_model->edit($this->_item_details_postedData());
            $this->Is_success($result);
            echo $result;
        }


    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _constant_postedData($create = null){


        $NO= $this->input->post('no');
        $NAME= $this->input->post('name');


        $result = array(
            array('name'=>'NO','value'=>$NO ,'type'=>'','length'=>-1),
            array('name'=>'NAME','value'=>$NAME,'type'=>'','length'=>100)

        );

        if($create == 'create'){
            array_shift($result);
        }

        return $result;
    }
    function _chapter_postedData($create = null){

        $SER = $this->input->post('ser');
        $NO= $this->input->post('no');
        $NAME= $this->input->post('name');
        $TYPE= $this->input->post('type');

        $result = array(
            array('name'=>'NO','value'=>$NO ,'type'=>'','length'=>-1),
            array('name'=>'NAME','value'=>$NAME,'type'=>'','length'=>100),
            array('name'=>'TYPE','value'=>$TYPE,'type'=>'','length'=>-1),
            array('name'=>'SER','value'=>$SER,'type'=>'','length'=>-1)
        );

        if($create == 'create'){
            array_shift($result);
        }

        return $result;
    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _section_postedData($create = null){

        $CHAPTER_NO = $this->input->post('chapter_no');
        $NO= $this->input->post('no');
        $NAME= $this->input->post('name');
        $OPER_TYPE= $this->input->post('oper_type');
        $ACTIVITY_TYPE= $this->input->post('activity_type');
        $GRANT_TYPE= $this->input->post('grant_type');
        $CON_NO= $this->input->post('con_no');
        $budget_type= $this->input->post('budget_type');
        $chain_type= $this->input->post('chain_type');

        $txt_account_id= $this->input->post('txt_account_id');
		if ($NAME =='')  $NAME=  $txt_account_id ;

        $result = array(
            array('name'=>'NO','value'=>$NO ,'type'=>'','length'=>-1),
            array('name'=>'NAME','value'=>$NAME,'type'=>'','length'=>100),
            array('name'=>'CHAPTER_NO','value'=>$CHAPTER_NO,'type'=>'','length'=>-1),

            array('name'=>'OPER_TYPE','value'=>$OPER_TYPE,'type'=>'','length'=>-1),
            array('name'=>'ACTIVITY_TYPE','value'=>$ACTIVITY_TYPE,'type'=>'','length'=>-1),
            array('name'=>'GRANT_TYPE','value'=>$GRANT_TYPE,'type'=>'','length'=>-1),
            array('name'=>'CON_NO','value'=>$CON_NO,'type'=>'','length'=>-1),
            array('name'=>'COMPETENT_SIDE','value'=>$this->p_competent_side,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$this->p_account_id,'type'=>'','length'=>-1),
            array('name'=>'BUDGET_TYPE','value'=>$budget_type,'type'=>'','length'=>-1),
            array('name'=>'CHAIN_TYPE','value'=>$chain_type,'type'=>'','length'=>-1)
        );

        if($create == 'create'){
            array_shift($result);
        }

        return $result;
    }


    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _item_postedData($create = null){

        $SECTION_NO = $this->input->post('section_no');
        $NO= $this->input->post('no');
        $NAME= $this->input->post('name');
        $PRICE= $this->input->post('price');
        $UNIT_NO= $this->input->post('unit_no');
        $SPECIAL= $this->input->post('special');
        $HAS_DETAILS= $this->input->post('has_details');
        $HAS_HISTORY= $this->input->post('has_history');
        $con_id = $this->input->post('con_id');

        $result = array(
            array('name'=>'NO','value'=>$NO ,'type'=>'','length'=>-1),
            array('name'=>'NAME','value'=>$NAME,'type'=>'','length'=>100),
            array('name'=>'SECTION_NO','value'=>$SECTION_NO,'type'=>'','length'=>-1),
            array('name'=>'PRICE','value'=>$PRICE,'type'=>'','length'=>-1),
            array('name'=>'UNIT_NO','value'=>$UNIT_NO,'type'=>'','length'=>-1),
            array('name'=>'SPECIAL','value'=>$SPECIAL,'type'=>'','length'=>-1),
            array('name'=>'HAS_DETAILS','value'=>$HAS_DETAILS,'type'=>'','length'=>-1),
            array('name'=>'HAS_HISTORY','value'=>$HAS_HISTORY,'type'=>'','length'=>-1),
            array('name'=>'CON_ID','value'=>$con_id,'type'=>'','length'=>-1),
            array('name'=>'ITEM_TYPE','value'=>$this->p_item_type,'type'=>'','length'=>-1),
            array('name'=>'ITEM_NO','value'=>$this->p_item_no,'type'=>'','length'=>-1),
            array('name'=>'ACTIVE','value'=>$this->p_active,'type'=>'','length'=>-1),
        );

        if($create == 'create'){
            array_shift($result);
        }

        return $result;
    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _item_details_postedData($create = null){

        $SPECIAL_ITEM_NO = $this->input->post('special_item_no');
        $NO= $this->input->post('no');
        $ITEM_NO= $this->input->post('item_no');
        $CCOUNT= $this->input->post('ccount');


        $result = array(
            array('name'=>'NO','value'=>$NO ,'type'=>'','length'=>-1),
            array('name'=>'SPECIAL_ITEM_NO','value'=>$SPECIAL_ITEM_NO,'type'=>'','length'=>-1),
            array('name'=>'ITEM_NO','value'=>$ITEM_NO,'type'=>'','length'=>-1),
            array('name'=>'CCOUNT','value'=>$CCOUNT,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>0,'type'=>'','length'=>-1)
        );

        if($create == 'create'){
            array_shift($result);
        }

        return $result;
    }


}