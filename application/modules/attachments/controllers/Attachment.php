<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/10/14
 * Time: 01:16 م
 */

class Attachment extends MY_Controller{

    var $MODEL_NAME= 'attachment_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function show_form(){
        $data['n']= $this->input->post('n');
        if($data['n']==1){
            $this->load->view('attachments/attachment_form',$data);
        }
    }

    function index($id , $categories){

        add_js('ajax_upload_file.js');

        $data['rows']= $this->get_table_count(" GFC_ATTACHMENT_TB WHERE (CATEGORY='{$categories}' or CATEGORY like '{$categories}_sub%') AND IDENTITY ='{$id}'  ");
        $data['id'] = $id;
        $data['categories'] = $categories;
        $this->load->view('attachment_index',$data);
    }

    function indexInline($id , $categories,$can_upload_priv=1){

        add_js('ajax_upload_file.js');
        $data['rows']= $this->get_table_count(" GFC_ATTACHMENT_TB WHERE CATEGORY='{$categories}' AND IDENTITY ='{$id}'  ");
        $data['id'] = $id;
        $data['categories'] = $categories;
        $data['can_upload_priv'] = $can_upload_priv;

        $this->load->view('attachment_inline_index',$data);
    }

    function indexInlineno($id , $categories,$can_upload_priv=1){

        add_js('ajax_upload_file.js');
        $data['rows']= $this->get_table_count(" GFC_ATTACHMENT_TB WHERE CATEGORY='{$categories}' AND IDENTITY ='{$id}'  ");
        $data['id'] = $id;
        $data['categories'] = $categories;
        $data['can_upload_priv'] = $can_upload_priv;

        $this->load->view('attachment_num_inline',$data);
    }

    function download($id , $categories){

        $rs = $this->attachment_model->get_list($id, $categories);

        echo json_encode($rs);
    }

    function upload_file(){
        $min= 60*15 ; // time out 15 min
        $attachment_time= $this->session->userdata('attachment_time');
        $attachment_name= $this->session->userdata('attachment_name');
        $attachment_identity= $this->session->userdata('attachment_identity');
        $attachment_category= $this->session->userdata('attachment_category');
        if($attachment_name!='' and $attachment_identity!='' and $attachment_category!='' and $attachment_time+$min > time() ){
            $config['upload_path']= './uploads/'.$this->year.'/'.$this->month.'/'.$attachment_category;
            $config['allowed_types']= 'word|doc|docx|xl|xls|xlsx|csv|pdf|zip|rar|tif';
            $config['max_size']= 1024*350; // KB
            $config['file_name']= $attachment_name;

            //create the folder if it's not already exists
            if(!is_dir($config['upload_path']))
                mkdir($config['upload_path'],0755,TRUE);

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')){
                print_r($this->upload->display_errors());
            }
            else{
                $file_data= $this->upload->data();
                $file_path=substr($file_data['full_path'],strpos($file_data['full_path'],'uploads'));
                $res= $this->create($this->_postedData($file_data['client_name'], $file_path, $attachment_identity, $attachment_category ));
                if(!$res or $res==1)
                    echo "تم رفع الملف بنجاح";
                else
                    echo "فشل في حفظ بيانات الملف";
            }
            $this->session->unset_userdata('attachment_time');
            $this->session->unset_userdata('attachment_name');
            $this->session->unset_userdata('attachment_identity');
            $this->session->unset_userdata('attachment_category');
        }else
            echo "لم يتم ارسال البيانات بطريقة صحيحة، اعد تحميل الصفحة وحاول مرة اخرى";
    }

    function create($data){
        $result= $this->{$this->MODEL_NAME}->create($data);
        return $this->Is_success($result);
    }

    function delete(){
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;

        $row= $this->{$this->MODEL_NAME}->get($id);
        $path= $row[0]['FILE_PATH'];

        if(count($row)==1 and $path!='' and strpos('123'.$path,'3uploads/')==2){
            $path= './'.$path;

            if(is_array($id)){
                foreach($id as $val){
                    $msg= $this->{$this->MODEL_NAME}->delete($val);
                }
            }else{
                $msg= $this->{$this->MODEL_NAME}->delete($id);
            }

            if($msg == 1){
                if(@unlink($path))
                    echo $msg;
                else
                    echo "تم حذف بيانات الملف، ولم يتم العثور عليه";
            }else{
                $this->print_error_msg($msg);
            }
        }else
            echo "لم يتم العثور على الملف";
    }

    function _postedData($file_name, $file_path, $identity, $category){
        $result = array(
            array('name'=>'FILE_NAME','value'=>$file_name ,'type'=>'','length'=>-1),
            array('name'=>'FILE_PATH','value'=>$file_path ,'type'=>'','length'=>-1),
            array('name'=>'IDENTITY','value'=>$identity ,'type'=>'','length'=>-1),
            array('name'=>'CATEGORY','value'=>$category ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function upload(){

        $res= $this->create($this->_postedData($file_data['client_name'], $file_path, $attachment_identity, $attachment_category ));
        if(!$res or $res==1)
            echo "تم رفع الملف بنجاح";
        else
            echo "فشل في حفظ بيانات الملف";
    }

    function public_upload($id , $categories,$can_upload_priv=1){
        add_js('ajax_upload_file.js');
        $data['content']='upload_view';
        $data['rows']= $this->attachment_model->get_list($id, $categories);
        $data['id'] = $id;
        $data['categories'] = $categories;
       $data['can_upload_priv'] = $can_upload_priv;
        $this->load->view('template/view',$data);
    }

    function public_count_attachment($id , $categories){
        $rows= $this->attachment_model->get_list($id, $categories);
        echo count($rows);
    }
}
