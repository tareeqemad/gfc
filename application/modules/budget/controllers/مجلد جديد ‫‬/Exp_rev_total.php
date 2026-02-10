<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/10/14
 * Time: 08:42 ص
 */

class Exp_rev_total extends MY_Controller{

    var $MODEL_NAME= 'exp_rev_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/gcc_structure_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('budget_constant_model');
        $this->year= $this->budget_year;
    }

    function index(){
        $data['title']='اجمالي النفقات والايرادات';
        $data['content']='exp_rev_total_index';
         $data['select_branch']= $this->select_branch();
        $data['year']= $this->year;
        $data['consts']= $this->budget_constant_model->get_all();
        $data['BUDGET_TYPES'] = $this->constant_details_model->get_list(124);
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->view('template/template',$data);
    }

    function get_chapters(){
        $type= $this->input->post('type');  // 2 exp - 1 rev
        $branch= $this->input->post('branch');
        $department_no= $this->input->post('position');
        $budget_ttype= $this->input->post('budget_ttype');
        if($department_no=='' or $department_no==0)
            $department_no= null;
        if($branch=='' or $branch==0)
            $branch= null;
        if($budget_ttype=='' or $budget_ttype==0)
            $budget_ttype= null;

        $data['type']= $type;
        if($type>0){
            $data['department']= $department_no;
            $data['chp_total']= $this->{$this->MODEL_NAME}->get_chp_total($this->year, $branch, $department_no, $type,$budget_ttype);
            $this->load->view('exp_rev_total_page',$data);
        }else  if($type==0)
        {
            $data['department']= $department_no;
            $data['chp_total_rev']= $this->{$this->MODEL_NAME}->get_chp_total($this->year, $branch, $department_no, 1,$budget_ttype);
            $data['chp_total_exp']= $this->{$this->MODEL_NAME}->get_chp_total($this->year, $branch, $department_no, 2,$budget_ttype);
            $data['chp_total_ban']= $this->{$this->MODEL_NAME}->get_chp_total($this->year, $branch, $department_no, 3,$budget_ttype);
          //////////
            $this->load->view('exp_rev_totals_page',$data);
        }
    }

    function get_total_branches_chapters(){
        $type= $this->input->post('type');  // 2 exp - 1 rev
        $branch= $this->input->post('branch');
        $chapter= $this->input->post('chapter');
        $department_no= $this->input->post('position');
        $budget_ttype= $this->input->post('budget_ttype');

        if($budget_ttype=='' or $budget_ttype==0)
            $budget_ttype= null;

        if($department_no=='' or $department_no==0)
            $department_no= null;
        if($branch=='' or $branch==0)
            $branch= null;
        if($type>0 or $type==0){
            $data['department']= $department_no;
            $data['chp_total']= $this->{$this->MODEL_NAME}->get_chp_bran_total($this->year, $branch, $department_no, $chapter,$budget_ttype);
            $this->load->view('exp_rev_total_branch_page',$data);
        }else
            echo "خطأ في الاستعلام";
    }
    function get_total_branches_section(){
        $type= $this->input->post('type');  // 2 exp - 1 rev
        $branch= $this->input->post('branch');
        $section_no= $this->input->post('section_no');
        $department_no= $this->input->post('position');
        $adopt= $this->input->post('adopt');
        $budget_ttype= $this->input->post('budget_ttype');

        if($budget_ttype=='' or $budget_ttype==0)
            $budget_ttype= null;
        if($department_no=='' or $department_no==0)
            $department_no= null;
        if($branch=='' or $branch==0)
            $branch= null;
        if($type>0 or $type==0){
            $data['department']= $department_no;
            $data['chp_total']= $this->{$this->MODEL_NAME}->get_sec_bran_total($this->year, $branch, $department_no, $section_no,$adopt,$budget_ttype);
            $this->load->view('exp_rev_total_branch_sec_page',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function get_sections(){
        $type= $this->input->post('type');
        $branch= $this->input->post('branch');
        $chapter= $this->input->post('chapter');
        $adopt= $this->input->post('adopt');
        $department_no= $this->input->post('position');
        $budget_ttype= $this->input->post('budget_ttype');

        if($budget_ttype=='' or $budget_ttype==0)
            $budget_ttype= null;
        if($department_no=='' or $department_no==0)
            $department_no= null;
        if($branch=='' or $branch==0)
            $branch= null;

        if(($type==1 or $type==2 or $type==3) and $chapter!= '' and ($adopt==1 or $adopt==0)){
            $data['adopt']= $adopt;
            $data['branch']= $branch;
            $data['page']= 'sections';
            $data['department']= $department_no;
            $data['type']= $type;
            $data['sec_total']= $this->{$this->MODEL_NAME}->get_sec_total($this->year, $branch, $department_no, $type, $chapter, $adopt,$budget_ttype);
            $this->load->view('exp_rev_total_details',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function get_items(){
        $type= $this->input->post('type');
        $branch= $this->input->post('branch');
        $section= $this->input->post('section');
        $adopt= $this->input->post('adopt');
        $department_no= $this->input->post('position');
        if($department_no=='' or $department_no==0)
            $department_no= null;
        if($branch=='' or $branch==0)
            $branch= null;

        if( $section!= '' and ($adopt==1 or $adopt==0)){ //($type==1 or $type==2) and
            $data['adopt']= $adopt;
            $data['type']= $type;
            $data['branch']= $branch;
            $data['page']= 'items';
            $data['itm_total']= $this->{$this->MODEL_NAME}->get_itm_total($this->year, $branch, $department_no, $type, $section, $adopt);
            $this->load->view('exp_rev_total_details',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function get_history(){
        $branch= $this->input->post('branch');
        $section= $this->input->post('section');
        if($branch=='' or $branch==0)
            $branch= null;

        if($section!= ''){
            $data['history_total']= $this->{$this->MODEL_NAME}->get_history_total($section, $branch);
            $this->load->view('exp_rev_total_history',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function select_branch(){
        $all= $this->gcc_branches_model->get_all();
        $select= "<select name='branch' id='txt_branch' class='form-control'>
                    <option value='0' selected='selected'>جميع المقرات</option>";
        foreach ($all as $row){
            $select.= "<option value='{$row['NO']}'>{$row['NAME']}</option>";
        }
        $select.= "</select>";
        return $select;
    }

    function select_position(){
        $type= $this->input->post('type');  // 2 exp - 1 rev
        $branch= $this->input->post('branch');
        if(  $branch!= '' and $branch > 0){  //($type==1 or $type==2)
            // الدوائر المدخل لها ولاقسامها سجلات في النفقات والايرادات
            $all= $this->gcc_structure_model->get_type(null, $this->year,$branch, $type);
            $select= "<select name='position' id='txt_position' class='form-control'>
                    <option value='0' >جميع الدوائر</option>";
            foreach ($all as $row){
                $select.= "<option value='{$row['ST_ID']}'>{$row['ST_NAME']}</option>";
            }
            $select.= "</select>";
        }
        echo $select;

        echo "
            <script type='text/javascript'>
                $(document).ready(function() {
                    $('#txt_position').select2();
                    $('#txt_position').change(function(){
                         $('#container #data').text('');
                    });
                });
            </script>
            ";
    }

    function attachment_get(){
        $type= $this->input->post('type');
        $branch= $this->input->post('branch');
        $item= $this->input->post('item');
        if($type==1)
            $type= 'rev';
        elseif($type==2)
            $type= 'exp';
        elseif($type==3)
            $type= 'ban';
        else
            $type= '';

        if($item!='' and $item >0 ){
            $attachment_identity= $type.'_'.$branch.'_'.($this->year).'_'.$item;
            $attachment_category='budget';

            $this->load->model('attachments/attachment_model');
            $data['user_id']= $this->user->id;
            $data['adopt']= 1;
            $data['get_list']= $this->attachment_model->get_list($attachment_identity, $attachment_category, 3); // 3 cut identity
            $this->load->view('attachments/attachment_page',$data);
        }else
            echo 'خطأ في الاستعلام';
    }

}
