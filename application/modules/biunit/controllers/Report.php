<?php

class Report extends MY_Controller
{
    function  __construct(){
        parent::__construct();
        /*load database libray manually*/
        $this->load->database();
        /*load Model*/
        $this->load->model('Report_model');
    }

    function create(){
        $data['branch_id']=$_POST['branch_id'];
        $data['month']=$_POST['month'];
        $data['user_id']=$_POST['user_id'];
        $data1=$_POST['indicator'];
      /*  for ($i = 0; $i < count($data1); $i++) {
            echo  $data1[$i];
            if(stripos($data1[$i],'₪')==0) {
                $data['value'] = str_replace("₪","",$data1[$i]);
            }else{
                $data['value']=$data1[$i];
            }
            $msg = $this->Report_model->insert_indicator($data);
             if ($msg==2)
                break;

        }*/
         foreach ($_POST['indicator'] as $key=>$val){
             $data['indicator_id']=$key;
            if(stripos($val,'₪')==0) {
                $data['value'] = str_replace("₪","",$val);
            }else{
                $data['value']=$val;
            }
            $msg = $this->Report_model->insert_indicator($data);
             if ($msg==2)
                break;
        }
		//$data['content']='Report_form';
		//$this->load->view('template/template',$data);
        redirect('/biunit/Biunit/report_form?month='.$_POST['month'].'&msg='.$msg);

    }


}