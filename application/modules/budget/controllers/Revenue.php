<?PHP
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/10/14
 * Time: 10:02 ص
 */

class Revenue extends MY_Controller{

    var $MODEL_NAME= 'exp_rev_model';
    var $PAGE_URL= 'budget/revenue/get_page';
    var $exp_rev_type= 1; // 2 exp - 1 rev

    function  __construct(){
        parent::__construct();
		
        $this->load->model($this->MODEL_NAME); 
        $this->load->model('budget_chapter_model'); 
        $this->load->model('budget_section_model');
        $this->load->model('budget_items_model');
        $this->load->model('budget_financial_ceil_model');
        $this->load->model('settings/gcc_structure_model');
        $this->load->model('budget_constant_model');
        $this->load->model('settings/constant_details_model'); 
        //branch
        $this->load->model('settings/gcc_branches_model');
        $this->branch= $this->input->post('branch');
        //
        $this->year= $this->budget_year;

        $this->type=$this->input->get_post('type');
        $this->type= ( $this->type ==null )? 1 : $this->type;
        $this->exp_rev_type= $this->type ;
    }

    function index(){
        $data['title']='الايرادات';
        if ($this->year >= 2017)
        $data['content']='revenue_index2017';
        else
        $data['content']='revenue_index';
        $data['select_position']= $this->select_position();
        //Branch
        $data['select_branch']= $this->gcc_branches_model->get_all();
        //
        $data['select_section']= $this->public_select_section(1);
        $data['year']= $this->year;
        $data['consts']= $this->budget_constant_model->get_all();
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('ajax_upload_file.js');

        $this->load->view('template/template',$data);
    }

    function get_page(){
        $item_no= $this->input->post('items');
        $section_no= $this->input->post('section');
        $mmonth= null;

        $branch= $this->input->post('branch');
        if (HaveAccess('show_branches') &&  $this->user->branch==1 && $branch != $this->user->branch){
            $department_no1= $this->constant_details_model->get(311,$branch);
            $department_no=$department_no1[0]['CON_NAME'];
        }
        else {
            $department_no= $this->input->post('user_position');

        }

        /*  if(!HaveAccess(base_url('budget/revenue/position')))
              $department_no= $this->user->position;*/
        $yyear= $this->year;
        //$branch= $this->user->branch;

        $data['user_id']= $this->user->id;

     /*   and b.yyear=2017 and b.BRANCH = 1 and b.department_no='010101' where section_no=98
        and active=1 and special=2 AND HAS_HISTORY = 2 */
              if ($yyear >= 2017){
                  $where= "  and b.yyear={$yyear} and  b.BRANCH = {$branch}  and  b.department_no='{$department_no}' where section_no={$section_no} and active=1  "; //and special=2 AND HAS_HISTORY = 2
                    if ($item_no !=0 )  $where=$where." and a.no={$item_no}";

             $data['get_list']= $this->budget_items_model->get_list2($where);

                 $this->load->view('revenue_page2017',$data);
        } else {
                  if($item_no!= null and $department_no!= null){
                    $data['item_data']=$this->budget_items_model->get($item_no);
                      $data['get_list']= $this->{$this->MODEL_NAME}->get_list($item_no,$mmonth,$department_no,$yyear,$branch,$this->exp_rev_type);
               //    print_r(  $data['get_list']);
                     $this->load->view('revenue_page',$data);
                  }else
                      echo "خطأ في الاستعلام";
            }

    }

    function receive_data(){
        $yyear= $this->year;
        $create_count= 0;
        $edit_count= 0;

        $section_no= $this->input->post('section');

        $branch= $this->input->post('branch');

        if (HaveAccess('show_branches') &&  $this->user->branch==1 && $branch != $this->user->branch){
            $department_no1= $this->constant_details_model->get(311,$branch);
            $department_no=$department_no1[0]['CON_NAME'];
        }
        else {
            $department_no= $this->input->post('user_position');

        }

        /*  if(!HaveAccess(base_url('budget/expenses/position')))
              $department_no= $this->user->position;*/

        //$branch= $this->user->branch;

        // arrays
        $no= $this->input->post('no');

        $ccount= $this->input->post('ccount');
        $price= $this->input->post('price');
        $notes= $this->input->post('notes');
        $adopt= $this->input->post('adopt');
        $entry_user= $this->input->post('entry_user');
        $total =$this->input->post('h_total');
        if ($yyear<2017) {
              $mmonth= $this->input->post('mmonth');
            $item_no= $this->input->post('items');
        //  $a_ceil_val= $this->budget_financial_ceil_model->active_ceil_value($section_no,$branch,$yyear,1) ;
    //    $this->print_error($a_ceil_val." ".$total);
     // if ($a_ceil_val<=$total){
        if(count($no)==12 and count($mmonth)==12 and $item_no!= null and $item_no!= 0 and $department_no!='' ){
            for($i=0;$i<12;$i++){
                if($no[$i]==0 and $mmonth[$i]== $i+1 and $ccount[$i]!='' and $price[$i]!='' and $ccount[$i]>0 and $price[$i]>0 ){ // create
                    $error= $this->create($this->_postedData($no[$i],$item_no, $ccount[$i], $mmonth[$i], $department_no, $yyear, $price[$i], $notes[$i], $branch, $this->exp_rev_type,'create'));
                    if(!$error)
                        $create_count++;
                }else if ($no[$i]!='' and $no[$i]!=0 and $mmonth[$i]== $i+1 and $adopt[$i]== 1 and $entry_user[$i]== $this->user->id and $ccount[$i]!='' and $price[$i]!='' and $ccount[$i]>0 and $price[$i]>0){ // edit
                    $error= $this->edit($this->_postedData($no[$i],$item_no, $ccount[$i], $mmonth[$i], $department_no, $yyear, $price[$i], $notes[$i], $branch, $this->exp_rev_type,'edit'));
                    if(!$error)
                        $edit_count++;
                }
            }
        }else{
            echo "لم يتم الارسال بطريقة صحيحة";
        }
    //  } else
      //    $this->print_error("لقد تجاوزت المبلغ المتبقي من السقف المالي وقيمة المتبقي = ".$a_ceil_val);



        }else {
            $item_no= $this->input->post('item_no');
            for($i=0;$i<count($item_no);$i++){
                if($no[$i]==0  and $ccount[$i]!='' and $price[$i]!='' and $ccount[$i]>0 and $price[$i]>0 ){ // create
                    $error= $this->create($this->_postedData($no[$i],$item_no[$i], $ccount[$i],1, $department_no, $yyear, $price[$i], $notes[$i], $branch, $this->exp_rev_type,'create'));
                    if(!$error)
                        $create_count++;
                }else if ($no[$i]!='' and $no[$i]!=0 and $adopt[$i]== 1 and $entry_user[$i]== $this->user->id and $ccount[$i]!='' and $price[$i]!='' and $ccount[$i]>0 and $price[$i]>0){ // edit
                    $error= $this->edit($this->_postedData($no[$i],$item_no[$i], $ccount[$i], 1, $department_no, $yyear, $price[$i], $notes[$i], $branch, $this->exp_rev_type,'edit'));
                    if(!$error)
                        $edit_count++;
                }
            }
        }

        if($create_count>0 and $edit_count>0)
            $msg="تم ادخال $create_count سجلات وتعديل $edit_count سجلات بنجاح";
        elseif($create_count==0 and $edit_count==0)
            $msg="لم تقم بتغيير البيانات";
        elseif($create_count==0)
            $msg="تم تعديل $edit_count سجلات بنجاح";
        elseif($edit_count==0)
            $msg="تم ادخال $create_count سجلات بنجاح";
        else
            $msg='لا يوجد تغيرات';

        echo "<script type='text/javascript'> success_msg('رسالة','$msg'); </script>";
        echo  modules::run($this->PAGE_URL);
    }

    function create($data){
        $result= $this->{$this->MODEL_NAME}->create($data);
        return $this->Is_success($result);
    }

    function edit($data){
        $result = $this->{$this->MODEL_NAME}->edit($data);
        return $this->Is_success($result);
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
            echo "1";
         //  echo  modules::run($this->PAGE_URL);
        }else{
          //  $this->print_error_msg($msg);
        }
    }


    function select_position(){
        if (HaveAccess(base_url('budget/revenue/position'))){
            $case=1;
            $disabled='';
        }
        else{
            $case=0;
            $disabled= 'disabled';
        }

        $all= $this->gcc_structure_model->get_level($this->user->position, $case, $this->year, $this->exp_rev_type );
        $select= "<select {$disabled} name='user_position' id='txt_user_position' class='form-control'>
                    <option value='0' >اختر القسم</option>";
        foreach ($all as $row){
            if($row['ST_ID']==$this->user->position)
                $selected= 'selected' ;
            else
                $selected= '';
            $select.= "<option {$selected} value='{$row['ST_ID']}'>{$row['ST_NAME']}</option>";
        }
        $select.= "</select>";
        return $select;
    }

    function public_select_section($typ=0){ //onchange='change_section();'

        $select= "<select  onchange='change_section();' name='section' id='txt_section' class='form-control'>";
        $select.= "<option value='0' selected='selected'>اختر الفصل</option>";
        $chapters= $this->budget_chapter_model->get_all_permission($this->type);

        foreach ($chapters as $chp){
            $select.= "<optgroup label='{$chp['NAME']}'>";
            $sections= $this->budget_section_model->get_list_permission($chp['NO'],0,0, $this->year,$this->branch);
            // print_r($sections);
            foreach ($sections as $sec){
                $select.= "<option value='{$sec['NO']}'  data-cval='{$sec['CEIL_VALUE']}' data-aval='{$sec['A_CEIL_VALUE']}' >{$sec['NAME']}</option>";
            }
            $select.="</optgroup>";
        }
        $select.="</select> ";

        if($typ==1)
            return $select;
        else
            echo $select;


        /*   $items_url= base_url('budget/revenue/select_items');

     /*    echo "
               <script type='text/javascript'>
                   $(document).ready(function() {
           /////////////
           $('#txt_section').change(function(){

         $('#section_cval').text(' السقف المالي : '+$('#txt_section').find(':selected').attr('data-cval')+' شيكل ' +' المتبقي : '+$('#txt_section').find(':selected').attr('data-aval')+' شيكل ');
            $('#s_cval').val($('#txt_section').find(':selected').attr('data-cval'));
                if($('#txt_section').val() !=0 ){
                    get_data('$items_url', {section:$('#txt_section').val()}, function(ret){ $('#items').html(ret); }, 'html');

                }
           });
   });
   </script>
   ";*/
///////////////////

    }


 /*   function select_section(){
        $select= "<select name='section' id='txt_section' class='form-control'>";
        $select.= "<option value='0' selected='selected'>اختر الفصل</option>";
        $chapters= $this->budget_chapter_model->get_all_permission($this->exp_rev_type);
        foreach ($chapters as $chp){
            $select.= "<optgroup label='{$chp['NAME']}'>";
            $sections= $this->budget_section_model->get_list_permission($chp['NO'], 0, 0, $this->year, 0);
         //   echo "<pre>"; print_r($sections);
         //   exit;
            foreach ($sections as $sec){
                $select.= "<option value='{$sec['NO']}' data-cval='{$sec['CEIL_VALUE']}' data-aval='{$sec['A_CEIL_VALUE']}' >{$sec['NAME']}</option>";
            }
            $select.="</optgroup>";
        }
        $select.="</select> ";

        return $select;
    }
*/
    function select_items(){
        $all= $this->budget_items_model->get_list($this->input->post('section'), 0, 0, 0); // not has_history , not special
        $select= "<select name='items' id='txt_items' class='form-control'>
                    <option value='0' selected='selected'>اختر البند</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['NO']}'>{$row['T_SER']}:{$row['NAME']}</option>";
        }
        $select.= "</select>";
        echo $select;
        $item_url= base_url("budget/revenue/item_data");
        echo "
            <script type='text/javascript'>
                $(document).ready(function() {
                    $('#txt_items').select2();

                    $('#txt_items').change(function(){
                        $('#item_data').text('');
                        if($('#txt_items').val() !=0 ){
                            get_data('$item_url', {item:$('#txt_items').val()}, function(ret){ $('#item_data').html(ret); }, 'html');
                        }
                    });

                });
            </script>
            ";
    }

    function item_data(){
        $data= $this->budget_items_model->get($this->input->post('item'));
        echo "سعر البند: ".$data[0]['PRICE']." الوحدة: ".$data[0]['UNIT'];
        echo "<input id='item_price' type='hidden' value='{$data[0]['PRICE']}' />";
    }

    function attachment_get(){
        $item= $this->input->post('item');
        $user_position= $this->input->post('user_position');
        if($item!='' and $item >0 and $user_position!='' and $user_position >0 ){
            $attachment_name='rev_'.$this->branch.'_'.$user_position.'_'.$item.'_'.time();
            $attachment_identity='rev_'.$this->branch.'_'.$user_position.'_'.($this->year).'_'.$item;
            $attachment_category='budget';

            $this->load->model('attachments/attachment_model');
            $data['user_id']= $this->user->id;
            $data['adopt']= 0;
            $data['get_list']= $this->attachment_model->get_list($attachment_identity, $attachment_category);
            $this->session->set_userdata('attachment_time',time());
            $this->session->set_userdata('attachment_name',$attachment_name);
            $this->session->set_userdata('attachment_identity',$attachment_identity);
            $this->session->set_userdata('attachment_category',$attachment_category);
            $this->load->view('attachments/attachment_page',$data);
        }else
            echo 'يجب اختيار القسم والبند';
    }
     function public_get_active_val(){
       $sec_no= $this->input->post('sec_no');
         $type= $this->input->post('type');
         $branch= $this->input->post('branch');
         echo ( $this->{$this->MODEL_NAME}->active_ceil_value($sec_no,$branch,$this->year,$type));

     }
    function _postedData($no, $item_no, $ccount, $mmonth, $department_no, $yyear, $price, $notes, $branch, $exp_rev_type,$typ= null){
        if($typ=='create'){
            $result = array(
                array('name'=>'ITEM_NO','value'=>$item_no ,'type'=>'','length'=>-1),
                array('name'=>'CCOUNT','value'=>$ccount ,'type'=>'','length'=>-1),
                array('name'=>'MMONTH','value'=>$mmonth ,'type'=>'','length'=>-1),
                array('name'=>'DEPARTMENT_NO','value'=>$department_no ,'type'=>'','length'=>-1),
                array('name'=>'YYEAR','value'=>$yyear,'type'=>'','length'=>-1),
                array('name'=>'PRICE','value'=>$price ,'type'=>'','length'=>-1),
                array('name'=>'NOTES','value'=>$notes ,'type'=>'','length'=>-1),
                array('name'=>'BRANCH','value'=>$branch,'type'=>'','length'=>-1),
                array('name'=>'EXP_REV_TYPE','value'=>$exp_rev_type,'type'=>'','length'=>-1)
            );
        }elseif($typ=='edit'){
            $result = array(
                array('name'=>'NO','value'=>$no ,'type'=>'','length'=>-1),
                array('name'=>'CCOUNT','value'=>$ccount ,'type'=>'','length'=>-1),
                array('name'=>'PRICE','value'=>$price ,'type'=>'','length'=>-1),
                array('name'=>'NOTES','value'=>$notes ,'type'=>'','length'=>-1)
            );
        }
        return $result;
    }
}
