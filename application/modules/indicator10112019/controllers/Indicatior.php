<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/08/18
 * Time: 08:42 ص
 */
class indicatior extends MY_Controller{

    var $MODEL_NAME= 'indicator_model';
    var $PAGE_URL= 'indicator/indicate_target/get_page';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->year= $this->budget_year;
        $this->ser=$this->input->post('ser');

    }
    /******************************************************************************************************************************************************/
    /*                                                             Manage Indicator                                                                      */
    /******************************************************************************************************************************************************/



    function index()
    {


        $data['title']='الاستعلام عن المؤشرات ';
        $data['content']='indicate_index';
        $data['help']=$this->help;
        //$data['year_indicator']=$this->year;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);




    }
    /***************************************************************************************************************************************/

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = "";

         $where_sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  M.BRANCH  ={$this->p_branch}  " : "";
        $where_sql .= isset($this->p_sector) && $this->p_sector!= null ? " AND  M.INDECATOR_SER IN
              (SELECT T.INDECATOR_SER FROM INDECATOR_INFO_TB T WHERE T.SECTOR ={$this->p_sector} )  " :  " ";
        $where_sql .= isset($this->p_for_month) && $this->p_for_month != null ? " AND M.FOR_MONTH + 1 ={$this->p_for_month} " : " AND M.FOR_MONTH + 1 =". date("Ym");

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('INDECATOR_DATA_TB', $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = (((($page) - 1) * $config['per_page']));
        $row = ((($page) * $config['per_page']));
        $data['page_rows']=$this->{$this->MODEL_NAME}->get_list_indicatior($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('indicatior_page', $data);


    }

    /********************************************************************************************************************************/

    /********************************************************************************************************************************/
    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
add_js('bootstrap.min.js');
        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');

        $data['sector'] = $this->constant_details_model->get_list(223);
        $data['branches'] = $this->gcc_branches_model->get_all();

    }





    /*****************************************************************************************************************************/
    function _postedData($type=null,$t_ser,$t_indecator_ser,$t_branch,$t_month,$t_value){


        $result = array(
            array('name'=>'T_SER','value'=>$t_ser ,'type'=>'','length'=>-1),
            array('name'=>'T_INDECATOR_SER','value'=>$t_indecator_ser,'type'=>'','length'=>-1),
            array('name'=>'T_BRANCH','value'=>$t_branch,'type'=>'','length'=>-1),
            array('name'=>'T_MONTH','value'=>$t_month,'type'=>'','length'=>-1),
            array('name'=>'T_VALUE','value'=>$t_value,'type'=>'','length'=>-1),





        );






        if($type=='create'){
            array_shift($result);
        }

        return $result;




    }


/**********************************************************************************************************/
    function public_get_sector($sector =0){

        $sector = $this->input->post('no')?$this->input->post('no'):$sector;

        $arr = $this->indicator_model->indecator_info(224,$sector);

        echo json_encode($arr);

    }
    /**********************************************/

     function _postedData_info($type=null,$INDECATOR_SER,$SECTOR,$CLASS,$INDECATOR_NAME,$EFFECT,
                                       $INDECATOR_FLAGE,$UNIT,$SCALE,$PERIOD,$NOTE,$BRANCH,$MANAGE_FOLLOW_ID,
						  $CYCLE_FOLLOW_ID,$IS_TARGET,$ENTERY_TARGET_WAY,$ENTERY_TARGET_TIME,
						  $EFFECT_FLAG,$EFFECT_VALUE,$WEIGHT,$EQUATION_TARGET)
{
                              $result = array(
                                  array('name'=>'INDECATOR_SER','value'=>$INDECATOR_SER ,'type'=>'','length'=>-1),
                                  array('name'=>'SECTOR','value'=>$SECTOR,'type'=>'','length'=>-1),
                                  array('name'=>'CLASS','value'=>$CLASS,'type'=>'','length'=>-1),
                                  array('name'=>'INDECATOR_NAME','value'=>$INDECATOR_NAME,'type'=>'','length'=>-1),
                                  array('name'=>'EFFECT','value'=>$EFFECT,'type'=>'','length'=>-1),
                                  array('name'=>'INDECATOR_FLAGE','value'=>$INDECATOR_FLAGE,'type'=>'','length'=>-1),
                                  array('name'=>'UNIT','value'=>$UNIT,'type'=>'','length'=>-1),
                                  array('name'=>'SCALE','value'=>$SCALE,'type'=>'','length'=>-1),
                                  array('name'=>'PERIOD','value'=>$PERIOD,'type'=>'','length'=>-1),
                                  array('name'=>'NOTE','value'=>$NOTE,'type'=>'','length'=>-1),
                                  array('name'=>'BRANCH','value'=>$BRANCH,'type'=>'','length'=>-1),
                                  array('name'=>'MANAGE_FOLLOW_ID','value'=>$MANAGE_FOLLOW_ID,'type'=>'','length'=>-1),
                                  array('name'=>'CYCLE_FOLLOW_ID','value'=>$CYCLE_FOLLOW_ID,'type'=>'','length'=>-1),
                                  array('name'=>'IS_TARGET','value'=>$IS_TARGET,'type'=>'','length'=>-1),
                                  array('name'=>'ENTERY_TARGET_WAY','value'=>$ENTERY_TARGET_WAY,'type'=>'','length'=>-1),
                                  array('name'=>'ENTERY_TARGET_TIME','value'=>$ENTERY_TARGET_TIME,'type'=>'','length'=>-1),
                                  array('name'=>'EFFECT_FLAG','value'=>$EFFECT_FLAG,'type'=>'','length'=>-1),
                                  array('name'=>'EFFECT_VALUE','value'=>$EFFECT_VALUE,'type'=>'','length'=>-1),
                                  array('name'=>'WEIGHT','value'=>$WEIGHT,'type'=>'','length'=>-1),
                                  array('name'=>'EQUATION_TARGET','value'=>$EQUATION_TARGET,'type'=>'','length'=>-1),
                              );
                              if($type=='create'){
                                  array_shift($result);
                              }
                              return $result;


  }
    function public_s()

    {
        $config = array();
//$config['protocol'] = "smtp";
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "192.168.0.70";
        $config['smtp_port'] = "25";
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;

        $this->load->library('email');

        $this->email->initialize($config);

        $this->email->from('lanakh@gedco.ps','');

        $list = array('dkhazendar@mol.ps','lanakh@gedco.ps');

        $this->email->to('lanakh@gedco.ps');

        $title ='lana';
        $ser='';
		$VOUCHER_VALUE='1';
		$VOUCHER_DATE='1';
		$TYPE='1';
		$VOUCHER_ID='';
		$CHARGE_TYPE='';
		$HINTS='';
        $BANK='';
        $company=' مقادمة ';
        $adopt_type=' اعتمتاد ';
        $user=' فضل الجماصي ';
        $this->email->subject($title);
        $this->email->set_mailtype("html");
      /*  $this->email->message('<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta http-equiv="refresh" content="30">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/bootstrap/js/jquery.min.js"></script>
  <script src="bootstrap/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">


 <table class="table table-bordered" style="font-size:20px;">
نفيد سيادتكم علما بان الموظف '.$user.' قام بعملية '.$adopt_type.' شحنة لصالح شركة '.$company.' في البيانات التالية	<tr>
	    <td><span lang="ar-ae">المسلسل</span></td>
		<td width="149"><span lang="ar-ae">المبلغ </span></td>
		<td width="145"><span lang="ar-ae">تاريخ السند</span></td>
		<td width="118"><span lang="ar-ae">نوع سند</span></td>
		<td width="101"><span lang="ar-ae">رقم السند</span></td>
		<td width="116"><span lang="ar-ae">نوع الشحنة</span></td>
		<td width="116"><span lang="ar-ae">البنك</span></td>
		< td><span lang="ar-ae">الملاحظات</span></td>
	</tr>
	<tr>
	    <td width="149">'.$ser.'</td>
		<td width="149">'.$VOUCHER_VALUE.'</td>
		<td width="145">'.$VOUCHER_DATE.'</td>
		<td width="118">'.$TYPE.'</td>
		<td width="101">'.$VOUCHER_ID.'</td>
		<td width="116">'.$CHARGE_TYPE.'</td>
		<td width="116">'.$BANK.'</td>
		<td>'.$HINTS.'</td>
	</tr>
</table>
<p><span lang="ar-ae">هذا للعلم ,,</span></p>
</body>

</html> ');*/


        $this->email->message('<!DOCTYPE html>
<html>
<body>

<table style="width: 100%; direction: rtl; color: royalblue; font-size: 16px; font-weight: bold; ">
  <tr>
    <th>Month</th>
    <th>Savings</th>
  </tr>
  <tr>
    <td>January</td>
    <td>$100</td>
  </tr>
  <tr>
    <td>February</td>
    <td>$80</td>
  </tr>
</table>

<p><b>Note:</b> The border attribute is not supported in HTML5. Use CSS instead.</p>

</body>
</html>
');
        $this->email->send();

    }


}

?>
