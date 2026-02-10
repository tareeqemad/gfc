<?php



class Issues extends MY_Controller{

    var $MODEL_NAME= 'issues_model';
    var $MODULE_NAME= 'issues';

    var $TB_NAME= 'issues';
    var $PAGE_URL= 'issues/issues/get_page';
	var $DETAILS_MODEL_NAME = 'actions_model';
    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
		$this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('bonds_model');
        $this->ser= $this->input->post('ser');
        $this->issue_branch= $this->input->post('issue_branch');
        $this->sub_no= $this->input->post('sub_no');
        $this->sub_name= $this->input->post('sub_name');
        $this->id= $this->input->post('id');
        $this->for_month= $this->input->post('for_month');
        $this->net_pay= $this->input->post('net_pay');
        $this->type_name= $this->input->post('type_pa');
        $this->address= $this->input->post('address');
        $this->d_id= $this->input->post('d_id');
        $this->d_name= $this->input->post('d_name');
        $this->d_tel= $this->input->post('d_tel');
        $this->d_mobile= $this->input->post('d_mobile');
        $this->d_address= $this->input->post('d_address');
        $this->issue_no= $this->input->post('issue_no');
        $this->issue_year= $this->input->post('issue_year');
        $this->pe_id= $this->input->post('pe_id');
        $this->pe_name= $this->input->post('pe_name');
        $this->f_notes= $this->input->post('f_notes');
        $this->fees= $this->input->post('fees');
        $this->issue_date= $this->input->post('issue_date');
        $this->court_name= $this->input->post('court_name');
        $this->issue_type= $this->input->post('issue_type');
        $this->exe_issue_no= $this->input->post('exe_issue_no');
        $this->exe_issue_year= $this->input->post('exe_issue_year');
        $this->exe_pe_id= $this->input->post('exe_pe_id');
        $this->exe_pe_name= $this->input->post('exe_pe_name');
        $this->exe_fees= $this->input->post('exe_fees');
        $this->exe_f_notes= $this->input->post('exe_f_notes');
        $this->exe_issue_date= $this->input->post('exe_issue_date');
        $this->exe_court_name= $this->input->post('exe_court_name');
        $this->paid_value= $this->input->post('paid_value');
        $this->status= $this->input->post('status');
        $this->adopt= $this->input->post('adopt');
        $this->branches= $this->input->post('branches');
        $this->ser_issue_type= $this->input->post('ser_issue_type');
        $this->issues_notes= $this->input->post('issues_notes');



        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->load->model('indicator/indicator_model');
    }

    /********************************************************************************************************************************/

    function index($page = 1,$sub_no= -1,$sub_name= -1,$id= -1,$issue_no= -1,$issue_year= -1,$issue_type= -1,$exe_issue_no= -1,$exe_issue_year= -1,$status=-1,$adopt=-1,$branches=-1,$for_month=-1){
        $data['page'] = $page;
        $data['title']='القضايا القانونية';
        $data['content']='issues_index';
        $data['sub_no']= $sub_no;
        $data['sub_name']= $sub_name;
        $data['id']= $id;
        $data['issue_no']= $issue_no;
        $data['issue_year']= $issue_year;
        $data['issue_type']= $issue_type;
        $data['exe_issue_no']= $exe_issue_no;
        $data['exe_issue_year']= $exe_issue_year;
        $data['status']= $status;
        $data['adopt']= $adopt;
        $data['branches']= $branches;
        $data['for_month']= $for_month;

        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }
/*****************************************************************************************************************************************/
function bound()
{
$result=array();
        $result2=array();
        $result_exec=array();
        $data['details']=$result;
        $data['details_ins']=$result2;
        $data['issues_data']=$result;
        $data['issues_data_exec'] = $result_exec;
         $data['content'] = 'structured_bond';
        $data['title']='سند دين منظم';
        $data['is_low']=1;
        $data['hide']='hidden';
        $data['hide_add']='';
        $data['hide_ins']='hidden';
        $data['action'] = 'index';
        $data['isCreate']= true;
        $data['isCreate2']= true;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
}
    /******************************************************************************************/
    function public_get_court($branch =0){
        $branch = $this->input->post('no')?$this->input->post('no'):$branch;
        $arr = $this->indicator_model->indecator_info(251,$branch);
        echo json_encode($arr);
    }
    /*********************************************************************************/
    function public_get_court_b($branch =0){
        if($branch == 1)
        {

        $arr = $this->constant_details_model->get_list(251);
        }
        else
        {
        $arr = $this->indicator_model->indecator_info(251,$branch);
        }
        return $arr;
    }
    /********************************************************************************************************************************/

    function _postedData($isCreate)
    {
        $url="issues/issues/get_issue_info/";
        $table_name='ISSUE_GEDCO_TB';
        $status_sub=0;

        if($this->p_from_source == 1)
        {
            $bonds_ser=$this->p_bonds_ser;
            $inspection_ser='';


        }
        else if ($this->p_from_source == 2)
        {
            $bonds_ser='';
            $inspection_ser=$this->p_inspection_ser;
        }
        else
        {
            $bonds_ser='';
            $inspection_ser='';
        }

      if(($this->p_status == 1) || ($this->p_status == '') || ($this->p_status == 7) || ($this->p_status == 8))
            $status_sub=1;
                else
                    $status_sub=2;
        $result = array(
            array('name' => 'SER', 'value' => $this->ser, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_BRANCH', 'value' => $this->issue_branch, 'type' => '', 'length' => -1),
            array('name' => 'SUB_NO', 'value' => $this->sub_no, 'type' => '', 'length' => -1),
            array('name' => 'SUB_NAME', 'value' => $this->sub_name, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $this->id, 'type' => '', 'length' => -1),
            array('name' => 'FOR_MONTH', 'value' => $this->for_month, 'type' => '', 'length' => -1),
            array('name' => 'NET_PAY', 'value' => $this->net_pay, 'type' => '', 'length' => -1),
            array('name' => 'TYPE_NAME', 'value' => $this->type_name, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->address, 'type' => '', 'length' => -1),
            array('name' => 'D_ID', 'value' => $this->d_id, 'type' => '', 'length' => -1),
            array('name' => 'D_NAME', 'value' => $this->d_name, 'type' => '', 'length' => -1),
            array('name' => 'D_TEL', 'value' => $this->d_tel, 'type' => '', 'length' => -1),
            array('name' => 'D_MOBILE', 'value' => $this->d_mobile, 'type' => '', 'length' => -1),
            array('name' => 'D_ADDRESS', 'value' => $this->d_address, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_NO', 'value' => $this->issue_no, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_YEAR', 'value' => $this->issue_year, 'type' => '', 'length' => -1),
            array('name' => 'PE_ID', 'value' => $this->pe_id, 'type' => '', 'length' => -1),
            array('name' => 'PE_NAME', 'value' => $this->pe_name, 'type' => '', 'length' => -1),
            array('name' => 'FEES', 'value' => $this->fees, 'type' => '', 'length' => -1),
            array('name' => 'F_NOTES', 'value' => $this->f_notes, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_DATE', 'value' => $this->issue_date, 'type' => '', 'length' => -1),
            array('name' => 'COURT_NAME', 'value' => $this->court_name, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_TYPE', 'value' => $this->issue_type, 'type' => '', 'length' => -1),
            array('name' => 'EXE_ISSUE_NO', 'value' => $this->exe_issue_no, 'type' => '', 'length' => -1),
            array('name' => 'EXE_ISSUE_YEAR', 'value' => $this->exe_issue_year, 'type' => '', 'length' => -1),
          //  array('name' => 'EXE_PE_ID', 'value' => $this->exe_pe_id, 'type' => '', 'length' => -1),
          // array('name' => 'EXE_PE_NAME', 'value' => $this->exe_pe_name, 'type' => '', 'length' => -1),
          //  array('name' => 'EXE_FEES', 'value' => $this->exe_fees, 'type' => '', 'length' => -1),
          //  array('name' => 'EXE_F_NOTES', 'value' => $this->exe_f_notes, 'type' => '', 'length' => -1),
          //  array('name' => 'EXE_ISSUE_DATE', 'value' => $this->exe_issue_date, 'type' => '', 'length' => -1),
          //  array('name' => 'EXE_COURT_NAME', 'value' => $this->exe_court_name, 'type' => '', 'length' => -1),
            array('name' => 'PAID_VALUE', 'value' => $this->paid_value, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $this->status, 'type' => '', 'length' => -1),
            array('name' => 'STATUS_SUB', 'value' => $status_sub, 'type' => '', 'length' => -1),
            array('name' => 'URL', 'value' => $url, 'type' => '', 'length' => -1),
            array('name' => 'TABLE_NAME', 'value' => $table_name, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_USER', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'SER_ISSUE_TYPE', 'value' =>  $this->ser_issue_type, 'type' => '', 'length' => -1),
            array('name' => 'ISSUES_NOTES', 'value' =>  $this->issues_notes, 'type' => '', 'length' => -1),
            array('name' => 'BONDS_SER', 'value' =>  $bonds_ser, 'type' => '', 'length' => -1),
            array('name' => 'INSPECTION_SER', 'value' =>  $inspection_ser, 'type' => '', 'length' => -1),


        );

         if ($isCreate=='create') {
            array_shift($result);
        }



        return $result;

    }

    /********************************************************************************************************************************/

    function public_get_action_details($id = 0,$adopt=1)

    {

        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_actions_all($id);
        $data['adopt']=$adopt;
		$this->_look_ups($data);
        $this->load->view('action_details', $data);
    }

    /********************************************************************************************************************************/

    function public_get_payments_details($id = 0,$adopt=1)

    {

        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_actions_all($id);
        $data['adopt']=$adopt;
        $this->_look_ups($data);
        $this->load->view('payments_details', $data);
    }

    /********************************************************************************************************************************/

    function create($id=0,$type=0){
		
		  if($_SERVER['REQUEST_METHOD'] == 'POST'){
              for ($i = 0; $i < count($this->p_seq1); $i++){
                  if ($this->p_type[$i] != '' && $this->p_issue_date_action[$i]!='' ) {
                     $test_var=1;
                  }
                  else
                      $test_var=0;
              }

              if($test_var == 1){
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }}else{
              $this->print_error('فشل في حفظ القضية');
          }





              for ($i = 0; $i < count($this->p_seq1); $i++)
              {
                  if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                      if ($this->p_type[$i] != '' && $this->p_issue_date_action[$i]!='' ) {
                          if($this->p_type[$i]==2)
                          { // حكم
                              if ($this->p_j_date[$i] != '' && $this->p_j_notes[$i] != '' ) {
                                  $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions($this->_postData_sub_actions(null,$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                      'create'));
                                  if (intval($x) <= 0) {
                                      $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                                  }

                              }
                              else
                              {
                                  $this->print_error('لم يتم الحفظ');
                              }
                          }elseif($this->p_type[$i]==3){

                              $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions($this->_postData_sub_actions(null,$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                  'create'));
                              if (intval($x) <= 0) {
                                  $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                              }

                                      for ($j = 0; $j < count($this->p_seq12); $j++){
                                          if ($this->p_pay_value[$j] != '' && $this->p_pay_date[$j]!='' ) {

                                               $pay=$this->{$this->DETAILS_MODEL_NAME}->create_sub_payments($this->_postData_sub_payments(null,$this->ser, $x, $this->p_pay_date[$j], $this->p_pay_value[$j],$this->p_type_pay[$j],$this->p_value_pay[$j],
                                                  'create'));
                                              if (intval($pay) <= 0) {
                                                  $this->print_error('لم يتم الحفظ' . '<br>' . $pay);
                                               }

                                          }
                                      }
                          }
                          else
                          {
                              $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions($this->_postData_sub_actions(null,$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                  'create'));
                              if (intval($x) <= 0) {
                                  $this->print_error('لم يتم الحفظ');
                                  //$this->print_error('لم يتم الحفظ' . '<br>' . $x);
                              }
                          }
                      }
                      else
                      {

                          $this->print_error('فشل في حفظ المستند');
                      }
                  }
              }

            echo intval($this->ser);
			


        }else{
              if($id==0)
              {
        $result=array();
        $result2=array();
        $result_exec=array();
        $data['details']=$result;
        $data['details_ins']=$result2;
        $data['issues_data']=$result;
        $data['issues_data_exec'] = $result_exec;
         $data['content'] = 'issues';
        $data['title']='القضايا القانونية';
        $data['is_low']=1;
        $data['hide']='hidden';
        $data['hide_add']='';
        $data['hide_ins']='hidden';
        $data['action'] = 'index';
        $data['isCreate']= true;
        $data['isCreate2']= true;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
              }
              else
              {
                  if ($type == 0)
                  {

                  //$result=array();
                  $result= $this->{$this->MODEL_NAME}->get_issue($id);
                  $result_exec=array();
                  $data['issues_data_exec'] = $result_exec;

                  $result2=array();
                  $data['details']=$result;
                 $data['details_ins']=$result2;
                  $data['issues_data']=$result;

                  $data['content'] = 'exec_issue';
                  $data['title']='قضية تنفيذية تابعة لقضية حقوقية';
                  $data['is_low']=1;
                  $data['hide']='hidden';
                  $data['hide_add']='';
                  $data['hide_ins']='hidden';
                  $data['action'] = 'index';
                  $data['isCreate']= true;
                  $data['isCreate2']= true;
                  $data['help']=$this->help;
                  $this->_look_ups($data);
                  $this->load->view('template/template',$data);

              }
                  else
                  {
                      if ($type == 1)
                      {

                          //$result=array();
                          $resultinfo= $this->bonds_model->get_bond($id);
                          $result=$this->{$this->MODEL_NAME}->get_sub_info($resultinfo[0]['SUB_NO']);
                          $result2=array();
                          $result_exec=array();
                          $data['details']=$result;
                          $data['details_ins']=$result2;
                          $data['issues_data']=$result;
                          $data['issues_data_exec'] = $result_exec;
                          $data['content'] = 'issues';
                          $data['title']='القضايا القانونية';
                          $data['is_low']=1;
                          $data['hide']='hidden';
                          $data['hide_add']='';
                          $data['hide_ins']='hidden';
                          $data['action'] = 'index';
                          $data['isCreate']= true;
                          $data['isCreate2']= true;
                          $data['help']=$this->help;
                          $this->_look_ups($data);
                          $this->load->view('template/template',$data);

                      }
                      else if($type == 2)
                      {

                      }
                  }

              }
    }
	}

    /********************************************************************************************************************************/
    /********************************************************************************************************************************/

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           $this->ser= $this->{$this->MODEL_NAME}->edit(($this->_postedData('edit')));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }







            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                    if ($this->p_type[$i] != '' && $this->p_issue_date_action[$i]!='' ) {
                        if($this->p_type[$i]==2)
                        { // حكم
                            if ($this->p_j_date[$i] != '' && $this->p_j_notes[$i] != '' ) {
                                $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions($this->_postData_sub_actions(null,$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                    'create'));
                                if (intval($x) <= 0) {
                                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                                }

                            }
                            else
                            {
                                $this->print_error('لم يتم الحفظ');
                            }
                        }elseif($this->p_type[$i]==3){

                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions($this->_postData_sub_actions(null,$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                'create'));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }

                            for ($j = 0; $j < count($this->p_seq12); $j++){
                                if ($this->p_pay_value[$j] != '' && $this->p_pay_date[$j]!='' ) {

                                    $pay=$this->{$this->DETAILS_MODEL_NAME}->create_sub_payments($this->_postData_sub_payments(null,$this->ser, $x, $this->p_pay_date[$j], $this->p_pay_value[$j],$this->p_type_pay[$j],$this->p_value_pay[$j],
                                        'create'));
                                    if (intval($pay) <= 0) {
                                        $this->print_error('لم يتم الحفظ' . '<br>' . $pay);
                                    }

                                }
                            }
                        }
                        else
                        {
                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions($this->_postData_sub_actions(null,$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                'create'));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ');
                                //$this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }
                    }
                    else
                    {

                        $this->print_error('لم يتم الحفظ');
                    }
                }
                else
                {


                    ///////////////////////////////
                    if ($this->p_type[$i] != '' && $this->p_issue_date_action[$i]!='' ) {
                        if($this->p_type[$i]==2)
                        { // حكم
                            if ($this->p_j_date[$i] != '' && $this->p_j_notes[$i] != '' ) {
                                $x=$this->{$this->DETAILS_MODEL_NAME}->edit_sub_actions($this->_postData_sub_actions($this->p_seq1[$i],$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                    'edit'));
                                if (intval($x) <= 0) {
                                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                                }




                            }
                            else
                            {
                                $this->print_error('لم يتم الحفظ');
                            }
                        }elseif($this->p_type[$i]==3){



                            $x=$this->{$this->DETAILS_MODEL_NAME}->edit_sub_actions($this->_postData_sub_actions($this->p_seq1[$i],$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                'edit'));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }

                            for ($j = 0; $j < count($this->p_seq12); $j++){
                                if ($this->p_seq12[$j] <= 0 || $this->p_seq12[$j]=='' ) {
                                    if ($this->p_pay_value[$j] != '' && $this->p_pay_date[$j]!='' ) {


                                        $pay=$this->{$this->DETAILS_MODEL_NAME}->create_sub_payments($this->_postData_sub_payments(null,$this->ser, $x, $this->p_pay_date[$j], $this->p_pay_value[$j],$this->p_type_pay[$j],$this->p_value_pay[$j],
                                            'create'));
                                        if (intval($pay) <= 0) {
                                            $this->print_error('لم يتم الحفظ' . '<br>' . $pay);
                                        }

                                    }
                                    else
                                    {


                                    }
                                }
                                else
                                {



                                    $pay=$this->{$this->DETAILS_MODEL_NAME}->edit_sub_payments($this->_postData_sub_payments($this->p_seq12[$j],$this->ser, $x, $this->p_pay_date[$j], $this->p_pay_value[$j],$this->p_type_pay[$j],$this->p_value_pay[$j],
                                        'edit'));
                                    if (intval($pay) <= 0) {
                                        $this->print_error('لم يتم الحفظ' . '<br>' . $pay);
                                    }


                                }
                            }
                        }
                        else
                        {
                            $x=$this->{$this->DETAILS_MODEL_NAME}->edit_sub_actions($this->_postData_sub_actions($this->p_seq1[$i],$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],
                                'edit'));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ');
                                //$this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }
                    }
                    else
                    {

                        $this->print_error('لم يتم الحفظ');
                    }

                    ///////////////////////





                }
            }

            echo intval($this->ser);
        }
    }
    /**********************************************************************************************************************************/

    function _postData_sub_actions($ser = null,$issue_ser= null,$issue_date_action=null, $type_issue=null,$j_date=null, $j_notes=null, $hints=null,$type)
    {

        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_SER', 'value' => $issue_ser, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_DATE_ACTION', 'value' => $issue_date_action, 'type' => '', 'length' => -1),
            array('name' => 'TYPE', 'value' => $type_issue, 'type' => '', 'length' => -1),
            array('name' => 'J_DATE', 'value' => $j_date, 'type' => '', 'length' => -1),
            array('name' => 'J_NOTES', 'value' => $j_notes, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_USER', 'value' => $this->user->branch, 'type' => '', 'length' => -1),

        );


        if ($type == 'create')
            unset($result[0]);

//var_dump($result);
     //   die;
        return $result;
    }

    /********************************************************************************************************************************/

    function _postData_sub_payments($ser = null,$issue_ser= null,$action_ser= null,$pay_date=null, $pay_value=null,$type_pay=null, $value_pay=null,$type)
    {

        $result = array(
            array('name' => 'SEQ12', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_SER', 'value' => $issue_ser, 'type' => '', 'length' => -1),
            array('name' => 'ACTION_SER', 'value' => $action_ser, 'type' => '', 'length' => -1),
            array('name' => 'PAY_DATE', 'value' => $pay_date, 'type' => '', 'length' => -1),
            array('name' => 'PAY_VALUE', 'value' => $pay_value, 'type' => '', 'length' => -1),
            array('name' => 'TYPE_PAY', 'value' => $type_pay, 'type' => '', 'length' => -1),
            array('name' => 'VALUE_PAY', 'value' => $value_pay, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_USER', 'value' => $this->user->branch, 'type' => '', 'length' => -1),


        );



        if ($type == 'create')
            unset($result[0]);

//var_dump($result);
        //   die;
        return $result;
    }


    /*******************************************************************/
    function adopt(){
        $table_name='ISSUE_GEDCO_TB';

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
        $table_name='ISSUE_GEDCO_TB';

            $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id,1,$table_name,$this->user->branch,$this->user->id);

            if(intval($res) <= 0) {
                $this->print_error('لم يتم الارجاع'.'<br>'.$res);
            }
        else
            echo 1;

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

    function get_issue_info($id){


        $result= $this->{$this->MODEL_NAME}->get_issue($id);
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_issue_action($id);
        $data['details_ins'] = $this->{$this->DETAILS_MODEL_NAME}->get_issue_ins($id);
        $data['issues_data'] = $result;
        $data['content'] = 'issues';
        $data['title'] = 'تعديل بيانات القضية';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['isCreate2'] = false;
        $data['hide']='hidden';
        $data['hide_ins']='';
        $data['hide_add']='';
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);


    }


    function get_exec_issue_info($id){


        $result= $this->{$this->MODEL_NAME}->get_issue($id);
        $result_exec= $this->{$this->MODEL_NAME}->get_issue($id);
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_issue_action($id);
        $data['details_ins'] = $this->{$this->DETAILS_MODEL_NAME}->get_issue_ins($id);
        $data['issues_data'] = $result;
        $data['issues_data_exec'] = $result_exec;
        $data['content'] = 'exec_issue';
        $data['title'] = 'تعديل بيانات القضية';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['isCreate2'] = false;
        $data['hide']='hidden';
        $data['hide_ins']='hidden';
        $data['hide_add']='';
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);


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

    function get_page($page = 1,$sub_no= -1,$sub_name= -1,$d_id= -1,$issue_no= -1,$issue_year= -1,$issue_type= -1,$exe_issue_no= -1,$exe_issue_year= -1,$status=-1,$adopt=-1,$branches=-1)
    {
        $this->load->library('pagination');
        $sub_no=$this->p_sub_no;
        $sub_name=$this->p_sub_name;
        $d_id=$this->p_id;
        $issue_no=$this->p_issue_no;
        $issue_year=$this->p_issue_year;
        $issue_type=$this->p_issue_type;
        $exe_issue_no=$this->p_exe_issue_no;
        $exe_issue_year=$this->p_exe_issue_year;
        $status=$this->p_status;
        $adopt=$this->p_adopt;
        $branches=$this->p_branches;

        $where_sql = "where 1=1";
       // $where_sql.= ($sub_no!= null)? " and $sub_no like '".add_percent_sign($sub_no)."' " : '';
        $where_sql.= ($sub_name!= null)? " and sub_name like '".add_percent_sign($sub_name)."' " : '';
        $where_sql.= ($sub_no!= null)? " and sub_no= '{$sub_no}' " : '';
       // $where_sql.= ($sub_name!= null)? " and sub_name= '{$sub_name}' " : '';
        $where_sql.= ($d_id!= null)? " and d_id= '{$d_id}' " : '';
        $where_sql.= ($issue_no!= null)? " and issue_no= '{$issue_no}' " : '';
        $where_sql.= ($issue_year!= null)? " and issue_year= '{$issue_year}' " : '';
        $where_sql.= ($exe_issue_no!= null)? " and exe_issue_no= '{$exe_issue_no}' " : '';
        $where_sql.= ($exe_issue_year!= null)? " and exe_issue_year= '{$exe_issue_year}' " : '';

        $where_sql .= isset($issue_type) && $this->issue_type != null ? " AND  issue_type ={$issue_type}" : "";
        $where_sql .= isset($status) && $this->status != null ? " AND  status ={$status}" : "";

        $where_sql .= isset($adopt) && $this->adopt != null ? " AND  adopt ={$adopt}" : "";
        $where_sql .= isset($branches) && $this->branches != null ? " AND  ISSUE_BRANCH ={$branches}" : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('ISSUE_GEDCO_TB', $where_sql);
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
        $this->load->view('issue_page', $data);

    }

    /********************************************************************************************************************************/

    function check_vars($var, $c_var){
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        $var= $var == -1 ?null:$var;
        return $var;
    }



    /********************************************************************************************************************************/

    function sub_issue($page=1,$sub_no= -1,$sub_name= -1,$id= -1,$for_month= -1,$branches=-1){

        $data['page'] = $page;
        $data['title']='القضايا القانونية للمشتركين';
        $data['content']='subscribers_index';
        $data['sub_no']= $sub_no;
        $data['sub_name']= $sub_name;
        $data['id']= $id;
        $data['for_month']= $for_month;
        $data['branches']= $branches;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }

    /********************************************************************************************************************************/

    function get_page_sub($page = 1,$sub_no= -1,$sub_name= -1,$id= -1,$for_month= -1)
    {
        $this->load->library('pagination');
        $sub_no=$this->check_vars($sub_no,'sub_no');
        $sub_name=$this->check_vars($sub_name,'sub_name');
        $id=$this->check_vars($id,'id');
        $for_month=$this->check_vars($for_month,'for_month');

        $where_sql = "where 1=1";
        $where_sql.= ($sub_name!= null)? " and sub_name like '".add_percent_sign($sub_name)."' " : '';
        $where_sql.= ($sub_no!= null)? " and sub_no= '{$sub_no}' " : '';
        $where_sql.= ($id!= null)? " and id= '{$id}' " : '';
        $where_sql.= ($for_month!= null)? " and for_month= '{$for_month}' " : '';



        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('LAW_SUBSCRIBER_INFO_TB', $where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_sub($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('subscribers_page', $data);

    }

    /********************************************************************************************************************************/






}