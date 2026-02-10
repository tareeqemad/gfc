<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 16/03/19
 * Time: 08:37 ص
 */



class TBonds extends MY_Controller{

    var $MODEL_NAME= 'bonds_model';
    var $MODULE_NAME= 'issues';

    var $TB_NAME= 'bonds';
    var $PAGE_URL= 'issues/bonds/get_page';
    var $DETAILS_MODEL_NAME = 'actions_model';
    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->ser= $this->input->post('ser');
        $this->sub_no= $this->input->post('sub_no');
        $this->sub_name= $this->input->post('sub_name');
        $this->id= $this->input->post('id');
        $this->bond_no= $this->input->post('bond_no');
        $this->bond_year= $this->input->post('bond_year');
        $this->bond_value= $this->input->post('bond_value');
        $this->bond_date= $this->input->post('bond_date');
        $this->court_name= $this->input->post('court_name');
        $this->branch= $this->input->post('branch');
        $this->notes= $this->input->post('notes');
        $this->installment_value= $this->input->post('installment_value');
        $this->from_installment_date= $this->input->post('from_installment_date');
        $this->adopt= $this->input->post('adopt');
		$this->sub_info_data= $this->input->post('sub_info_data');
		$this->sub_name_id= $this->input->post('sub_name_id');
		$this->sub_info_id= $this->input->post('sub_info_id');





        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->load->model('indicator/indicator_model');
    }
   public function charge_index($sub_no){
      $data['page_rows'] = $this->{$this->MODEL_NAME}->PRE_PAID_POWER_GET($sub_no);
       $data['content']='charge_vw';
       $this->load->view('template/view',$data);
     
    }

    /********************************************************************************************************************************/

    public function index($page = 1,$sub_no= -1,$sub_name= -1,$id= -1,$bond_no= -1,$bond_year= -1,$bond_value= -1,$bond_date= -1,$court_name= -1,$branch=-1,$adopt=-1){
        $data['page'] = $page;
        $data['title']='سند دين منظم';
        $data['content']='structured_bond_index';
        $data['sub_no']= $sub_no;
        $data['sub_name']= $sub_name;
        $data['id']= $id;
        $data['action'] = 'index';

        $data['bond_no']= $bond_no;
        $data['bond_year']= $bond_year;
        $data['bond_value']= $bond_value;
        $data['bond_date']= $bond_date;
        $data['court_name']= $court_name;
        $data['branch']= $branch;
        $data['adopt']= $adopt;


        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }
    /*****************************************************************************************************************************************/

    function public_get_court($branch =0){
        $branch = $this->input->post('no')?$this->input->post('no'):$branch;
        $arr = $this->indicator_model->indecator_info(251,$branch);
        echo json_encode($arr);
    }
    /*********************************************************************************/
    function public_get_court_b($branch =0){
        $arr = $this->indicator_model->indecator_info(251,$branch);
        return $arr;
    }
    /********************************************************************************************************************************/
 /*****************************************************************************************************************************************/

    function public_subscribers_tb_get($id =-100){
        $id = $this->input->post('id')?$this->input->post('id'):$id;
        $arr = $this->{$this->MODEL_NAME}->SUBSCRIBERS_TB_GET($id);
        echo json_encode($arr);
    }
	/***********************************************/
	    /*********************************************************************************/
    function public_subscribers_tb_get_b($id =-100){
        $arr = $this->{$this->MODEL_NAME}->SUBSCRIBERS_TB_GET($id);
        return $arr;
    }
	 /*****************************************************************************************************************************************/

    function public_subscribers_tb_sub_get($id =-100){
        $no = $this->input->post('no')?$this->input->post('no'):$id;
        $arr = $this->{$this->MODEL_NAME}->SUBSCRIBERS_TB_SUB_GET($no);
        echo json_encode($arr);
    }
	/****************************************************************************************/
    function _postedData($isCreate)
    {
        $url="issues/bonds/get_bond_info/";
        $table_name='STRUCTURED_BOND_TB';
        $status_sub=0;
     /*   if(($this->p_status == 1) || ($this->p_status == ''))
            $status_sub=1;
        else

           $STATUS_SUB=2;*/

		   if($this->sub_info_data == 1)
		   {
		   $sub_id_info=$this->id;
		   $sub_name_info=$this->sub_name;
		   $sub_no_info=$this->sub_no;
		   
		   }
		   elseif($this->sub_info_data == 2)
		   {
		   $sub_id_info=$this->id;
		   $sub_name_info=$this->sub_name_id;
		   $sub_no_info=$this->sub_info_id;
		   }
        $result = array(
            array('name' => 'SER', 'value' => $this->ser, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
            array('name' => 'SUB_NO', 'value' => $sub_no_info, 'type' => '', 'length' => -1),
            array('name' => 'SUB_NAME', 'value' => $sub_name_info, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $sub_id_info, 'type' => '', 'length' => -1),
            array('name' => 'BOND_NO', 'value' => $this->bond_no, 'type' => '', 'length' => -1),
            array('name' => 'BOND_YEAR', 'value' => $this->bond_year, 'type' => '', 'length' => -1),
            array('name' => 'BOND_VALUE', 'value' => $this->bond_value, 'type' => '', 'length' => -1),
            array('name' => 'BOND_DATE', 'value' => $this->bond_date, 'type' => '', 'length' => -1),
            array('name' => 'COURT_NAME', 'value' => $this->court_name, 'type' => '', 'length' => -1),
            array('name' => 'INSTALLMENT_VALUE', 'value' => $this->installment_value, 'type' => '', 'length' => -1),
            array('name' => 'FROM_INSTALLMENT_DATE', 'value' => $this->from_installment_date, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->notes, 'type' => '', 'length' => -1),
			array('name' => 'WAY_SUB_DATA', 'value' =>$this->sub_info_data, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_BRANCH_USER', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'URL', 'value' => $url, 'type' => '', 'length' => -1),
            array('name' => 'TABLE_NAME', 'value' => $table_name, 'type' => '', 'length' => -1),
        );

        if ($isCreate=='create') {
            array_shift($result);
        }

        return $result;

    }




    /********************************************************************************************************************************/

    function public_get_payments_bond_details($id = 0,$adopt=1)

    {
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_bond_all($id);
        $data['adopt']=$adopt;
        $this->_look_ups($data);

        $this->load->view('payments_bond_details', $data);
    }


    /********************************************************************************************************************************/


    function get_bond_info($id){

        $result= $this->{$this->MODEL_NAME}->get_bond($id);
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_bond_payment($id);
        $data['bond_data'] = $result;
        $data['content'] = 'structured_bond';
        $data['title'] = 'تعديل بيانات السند';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);

    }






    /********************************************************************************************************************************/

    public function  public_create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            /*for ($i = 0; $i < count($this->p_seq1); $i++){
                if ($this->p_pay_date[$i] != ''  ) {
                    $test_var=1;
                }
                else
                    $test_var=0;
            }

            if($test_var == 1){*/
                $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));


                if (intval($this->ser) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
                }

           /* }else{
                $this->print_error('فشل في حفظ السند');
            }
*/


          /* for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                    if ($this->p_pay_date[$i] != '' ) {

                                $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions_bonds($this->_postData_sub_actions(null,$this->ser, $this->p_pay_date[$i],$this->p_pay_value[$i],
                                    'create'));
                                if (intval($x) <= 0) {
                                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                                }
                    }
                    else
                    {

                        $this->print_error('فشل في حفظ المستند');
                    }
                }
            }
*/
            echo intval($this->ser);



        }else{
			
            $result=array();
            $data['details']=$result;
            $data['content'] = 'tstructured_bond';
            $data['title']='سند دين منظم';
            $data['action'] = 'index';
            $data['isCreate']= true;
            $data['help']=$this->help;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);


        }




    }


    /********************************************************************************************************************************/

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->{$this->MODEL_NAME}->edit(($this->_postedData('edit')));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }


/*
            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                        if ($this->p_pay_date[$i] != '' ) {

                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions_bonds($this->_postData_sub_actions(null,$this->ser, $this->p_pay_date[$i],$this->p_pay_value[$i],
                                'create'));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }
                        else
                        {

                            $this->print_error('فشل في حفظ المستند');
                        }



                } else {


                   if ($this->p_pay_date[$i] != '' ) {

                        $x=$this->{$this->DETAILS_MODEL_NAME}->edit_sub_actions_bonds($this->_postData_sub_actions($this->p_seq1[$i],$this->ser, $this->p_pay_date[$i],$this->p_pay_value[$i],
                            'edit'));
                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }
                    }
                    else
                    {

                        $this->print_error('فشل في حفظ المستند');
                    }






                }
            }*/

            echo intval($this->ser);
        }
    }









    /********************************************************************************************************************************/
    function _postData_sub_actions($ser = null,$bond_ser= null,$pay_date=null, $pay_value=null,$type)
    {

        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'BOND_SER', 'value' => $bond_ser, 'type' => '', 'length' => -1),
            array('name' => 'PAY_DATE', 'value' => $pay_date, 'type' => '', 'length' => -1),
            array('name' => 'PAY_VALUE', 'value' => $pay_value, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_BRANCH_USER', 'value' => $this->user->branch, 'type' => '', 'length' => -1),

        );


        if ($type == 'create')
            unset($result[0]);


        return $result;
    }
    /********************************************************************************************************************************/




    /*******************************************************************/
    function adopt(){
        $table_name='STRUCTURED_BOND_TB';

        $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id,2,$table_name,$this->user->branch,$this->user->id);

        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;

    }
    /*********************************************************************************/
    function unadopt ()
    {
        $table_name='STRUCTURED_BOND_TB';

        $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id,1,$table_name,$this->user->branch,$this->user->id);

        if(intval($res) <= 0) {
            $this->print_error('لم يتم الارجاع'.'<br>'.$res);
        }
        else
            echo 1;

    }






    /********************************************************************************************************************************/

    function _look_ups(&$data){
        add_css('combotree.css');
        add_css('datepicker3.css');
        add_js('jquery.hotkeys.js');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['types'] = $this->constant_details_model->get_list(258);
        $data['issue_type'] = $this->constant_details_model->get_list(252);
        $data['court_name'] = $this->constant_details_model->get_list(251);
        $data['paid'] = $this->constant_details_model->get_list(259);
        $data['status'] = $this->constant_details_model->get_list(253);
        $data['adopt'] = $this->constant_details_model->get_list(254);
		$data['way_sub_info_data'] = $this->constant_details_model->get_list(406);
		


        if ($this->user->branch==1)
        {

            $data['branches'] = $this->gcc_branches_model->get_all();
        }
        else
        {
            $data['branches'] =$this->gcc_branches_model->user_branch($this->user->id);

        }





    }
    /***********************************************************************************************/


    /********************************************************************************************************************************/

    function check_vars($var, $c_var){
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        $var= $var == -1 ?null:$var;
        return $var;
    }


    /********************************************************************************************************************************/
    function public_sub_info(){


        $id = $this->input->post('id');


        // $this->print_error($id);
        $result =$this->{$this->MODEL_NAME}->get_sub_info($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /********************************************************************************************************************************/


    function get_page($page = 1,$sub_no= -1,$sub_name= -1,$id= -1,$bond_no= -1,$bond_year= -1,$adopt=-1,$branch=-1)
    {

        $this->load->library('pagination');
        $sub_no=$this->check_vars($sub_no,'sub_no');
        $sub_name=$this->check_vars($sub_name,'sub_name');
        $id=$this->check_vars($id,'id');
        $bond_no=$this->check_vars($bond_no,'bond_no');
        $bond_year=$this->check_vars($bond_year,'bond_year');
        $adopt=$this->check_vars($adopt,'adopt');
        $branch=$this->check_vars($branch,'branch');

        $where_sql = " where 1=1 ";
        $where_sql.= ($sub_name!= null)? " and sub_name like '".add_percent_sign($sub_name)."' " : '';
        $where_sql.= ($sub_no!= null)? " and sub_no= '{$sub_no}' " : '';
        $where_sql.= ($id!= null)? " and id= '{$id}' " : '';
        $where_sql.= ($bond_no!= null)? " and bond_no= '{$bond_no}' " : '';
        $where_sql.= ($bond_year!= null)? " and bond_year= '{$bond_year}' " : '';

        $where_sql .= isset($this->adopt) && $this->adopt != null ? " AND  adopt ={$this->adopt}" : "";
        $where_sql .= isset($this->branch) && $this->branch != null ? " AND  BRANCH ={$this->branch}" : "";
        //echo $where_sql.'<br/>';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('STRUCTURED_BOND_TB', $where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('structured_bond_page', $data);

    }



}
