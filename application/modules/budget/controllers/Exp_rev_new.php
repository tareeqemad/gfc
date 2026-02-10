<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 24/02/15
 * Time: 02:07 م
 */

class Exp_rev_new extends MY_Controller{

    var $MODEL_NAME= 'exp_rev_model';
    var $PAGE_URL= 'budget/exp_rev_new/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('budget_chapter_model');
        $this->load->model('budget_section_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('budget_constant_model');

        $this->exp_rev_type= $this->input->post('exp_rev_type'); // 2 exp - 1 rev
        $this->year= $this->budget_year;
        $this->chapter= $this->input->post('chapter');
        $this->section= $this->input->post('section');
        $this->branch= $this->input->post('branch');
        $this->department_no= $this->input->post('department_no');
        // arrays
        $this->no= $this->input->post('no');
        $this->item_no= $this->input->post('item_no');
        $this->new_count= $this->input->post('new_count');
        $this->new_price= $this->input->post('new_price');

        if( HaveAccess( base_url("budget/exp_rev_new/all_branches") ) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index(){
        $data['title']='اعادة تنسيب البنود '.$this->year;
        $data['content']='exp_rev_new_index';
        $data['year']= $this->year;
        $data['all_branches']= $this->all_branches;
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['consts']= $this->budget_constant_model->get_all();

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('ajax_upload_file.js');

        $this->load->view('template/template',$data);
    }

    function public_chapter(){
        $this->_reset_vars();
        $this->return_json( $this->budget_chapter_model->get_list_new($this->exp_rev_type,$this->year, $this->branch, $this->department_no) );
    }

    function public_section(){
        $this->_reset_vars();
        $this->return_json( $this->budget_section_model->get_list_new($this->chapter,$this->year, $this->branch, $this->department_no) );
    }

    function public_department(){
        $this->return_json( $this->{$this->MODEL_NAME}->get_department_new($this->exp_rev_type,$this->year) );
    }

    function _reset_vars(){
        if(!$this->all_branches){
            $this->branch= null;
            $this->department_no= null;
        }
    }

    function get_page(){
        $this->_reset_vars();
        if(($this->exp_rev_type==1 or $this->exp_rev_type==2) and $this->section!='' ){
            $total= $this->{$this->MODEL_NAME}->get_up_new($this->year, $this->section, $this->branch, $this->department_no);
            if(count($total)!=1)
                die();
            $data['total']= $total[0];
            $data['get_list']= $this->{$this->MODEL_NAME}->get_list_new($this->exp_rev_type, $this->year, $this->section, $this->branch, $this->department_no);
            $this->load->view('exp_rev_new_page',$data);
        }else
            echo "خطأ في الاستعلام";
    }

    function edit(){
        $cnt=0;
        if(($this->exp_rev_type==1 or $this->exp_rev_type==2) and $this->section!='' ){
            for($i=0; $i<count($this->no); $i++){
                $no_item_no= explode( ',', $this->no[$i] );
                $no= $no_item_no[0];
                $item_no= $no_item_no[1];
                if($no!='' and $item_no!='' and (($this->new_count[$i]!='' and $this->new_count[$i]>0) or $this->new_count[$i]=='') and (($this->new_price[$i]!='' and $this->new_price[$i]>0) or $this->new_price[$i]=='') ){
                    $ret= $this->{$this->MODEL_NAME}->edit_new($this->_postedData($no, $item_no, $this->new_count[$i], $this->new_price[$i]));
                    if(intval($ret) <= 0){
                        $this->print_error($ret);
                    }
                    $cnt++;
                }
            }
            echo $cnt;
        }else
            echo 'خطأ';
    }

    function adopt1(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->exp_rev_type!='' and $this->branch!='' and $this->section!='' ){
            $res = $this->{$this->MODEL_NAME}->adopt_new($this->exp_rev_type, $this->year, $this->branch, $this->department_no, $this->section, 1);
            if(intval($res) <= 0){
                $this->print_error('لم يتم '.'<br>'.$res);
            }
            echo 1;
        }else
            echo "خطأ";
    }

    function adopt2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->exp_rev_type!='' and $this->section!='' ){
            $res = $this->{$this->MODEL_NAME}->adopt_new($this->exp_rev_type, $this->year, null, null, $this->section, 2);
            if(intval($res) <= 0){
                $this->print_error('لم يتم '.'<br>'.$res);
            }
            echo 1;
        }else
            echo "خطأ";
    }

    function adopt3(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->exp_rev_type!='' and $this->branch!='' and $this->section!='' ){
            $res = $this->{$this->MODEL_NAME}->adopt_new($this->exp_rev_type, $this->year, $this->branch, $this->department_no, $this->section, 3);
            if(intval($res) <= 0){
                $this->print_error('لم يتم '.'<br>'.$res);
            }
            echo 1;
        }else
            echo "خطأ";
    }

    function attachment_get(){
        if($this->exp_rev_type==1)
            $type='rev_';
        elseif($this->exp_rev_type==2)
            $type='exp_';
        else
            die();

        if($this->branch!='' and $this->branch > 0)
            $branch= $this->branch;
        else
            $branch= $this->user->branch;

        $item= $this->item_no;
        $user_position= $this->department_no;

        if($item!='' and $item >0 and $user_position!='' and $user_position >0 ){
            $attachment_name= $type.$branch.'_'.$user_position.'_'.$item.'_'.time();
            $attachment_identity= $type.$branch.'_'.$user_position.'_'.$this->year.'_'.$item.'_new';
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
            echo 'خطأ';
    }

    function _postedData($no, $item_no, $new_count, $new_price){
        $result = array(
            array('name'=>'NO_IN','value'=>$no ,'type'=>'','length'=>-1),
            array('name'=>'ITEM_NO_IN','value'=>$item_no ,'type'=>'','length'=>-1),
            array('name'=>'NEW_COUNT_IN','value'=>$new_count ,'type'=>'','length'=>-1),
            array('name'=>'NEW_PRICE_IN','value'=>$new_price ,'type'=>'','length'=>-1)
        );
        return $result;
    }

}
