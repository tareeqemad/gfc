<?php



class Checks extends MY_Controller{

    var $MODEL_NAME= 'checks_model';
    var $MODULE_NAME= 'issues';
    var $TB_NAME= 'checks';
    var $PAGE_URL= 'issues/checks/get_page';
	var $DETAILS_MODEL_NAME = 'actions_model';
    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
		$this->load->model($this->DETAILS_MODEL_NAME);

        $this->ser= $this->input->post('ser');
        $this->check_no= $this->input->post('check_no');
        $this->check_year= $this->input->post('check_year');
        $this->check_date= $this->input->post('check_date');
        $this->check_name= $this->input->post('check_name');
        $this->check_value= $this->input->post('check_value');
        $this->check_action= $this->input->post('check_action');
        $this->bank_name= $this->input->post('bank_name');
        $this->bank_no= $this->input->post('bank_no');
        $this->address= $this->input->post('address');
        $this->complaint_no= $this->input->post('complaint_no');
        $this->complaint_year= $this->input->post('complaint_year');
        $this->complaint_date= $this->input->post('complaint_date');
        $this->complaint_date= $this->input->post('complaint_date');
        $this->complainant_name= $this->input->post('complainant_name');
        $this->branch= $this->input->post('branch');
        $this->notes= $this->input->post('notes');
        $this->adopt= $this->input->post('adopt');
        $this->sub_no= $this->input->post('sub_no');
        $this->sub_name= $this->input->post('sub_name');
        $this->id= $this->input->post('id');
        $this->for_month= $this->input->post('for_month');
        $this->net_pay= $this->input->post('net_pay');
        $this->type_name= $this->input->post('type_pa');
        $this->address= $this->input->post('address');
        $this->paid_val= $this->input->post('paid_val');
		$this->deliv_date= $this->input->post('deliv_date');
        $this->action_date= $this->input->post('action_date');
        $this->complainant_id= $this->input->post('complainant_id');
        $this->action_date_paid= $this->input->post('action_date_paid');
        $this->ser_check= $this->input->post('ser_check');
        $this->check_customer= $this->input->post('check_customer');





        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->load->model('indicator/indicator_model');
    }


    /********************************************************************************************************************************/


    function public_get_payments_check_details($id = 0,$adopt=1)

    {
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_check_all($id);
        $data['adopt']=$adopt;
        $this->_look_ups($data);

        $this->load->view('check_payment_details', $data);
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
        $data['type_check'] = $this->constant_details_model->get_list(265);
        $data['banks'] = $this->constant_details_model->get_list(9);


        if ($this->user->branch==1)
        {

              $data['branches'] = $this->gcc_branches_model->get_all();
         }
        else
        {
            $data['branches'] =$this->gcc_branches_model->user_branch($this->user->id);

        }





    }

    /********************************************************************************************************************************/

    function check_vars($var, $c_var){
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        $var= $var == -1 ?null:$var;
        return $var;
    }

    /********************************************************************************************************************************/

    function index($page=1,$check_no= -1,$check_year= -1,$complaint_no= -1,$complaint_year= -1,$branch=-1,$adopt=-1){

        $data['page'] = $page;
        $data['title']='الشيكات المرجعة';
        $data['content']='checks_portfolio_index';
        $data['check_no']= $check_no;
        $data['check_year']= $check_year;
        $data['complaint_no']= $complaint_no;
        $data['complaint_year']= $complaint_year;
        $data['adopt']= $adopt;
        $data['branch']= $branch;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }
/*******************************************************************************************************************************************/
 function public_index($id='', $name ='',$page= 1){
        $id= $id==-1 ? null: $id;
        $name= $name==-1 ? null: urldecode($name);
        $data['id']=   $this->input->get_post('id')   ? $this->input->get_post('id')   : $id;
        $data['name']= $this->input->get_post('name') ? $this->input->get_post('name') : $name;
        $data['content']='subscriber';
        $data['page']= $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        add_js('jquery.hotkeys.js');
        $this->load->view('template/view',$data);
}
/*******************************************************************************************************************************************/
 function public_get_subscribers($prm= array()){
         add_js('jquery.hotkeys.js');
        if(!$prm) //add_percent_sign

            $prm= array(
                'id'=>$this->input->get_post('id'),
                'name'=>$this->input->get_post('name'),
                'page'=>$this->input->get_post('page')
            );

        $this->load->library('pagination');

        $page= $prm['page'] ? $prm['page']: 1;
        $config['base_url'] = base_url("issues/checks/public_index?id={$prm['id']}&name={$prm['name']}");
        $prm['id']= $prm['id'] != -1 ? $prm['id']: null;
        $prm['name']= $prm['name'] !=-1 ? add_percent_sign($prm['name']): null;

        $count_rs = $this->{$this->MODEL_NAME}->get_count($prm['id'], $prm['name']);

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

        $data['get_list'] =$this->{$this->MODEL_NAME}->get_lists($prm['id'], $prm['name'] , $offset, $row );

        $this->load->view('subscriber_page',$data);
        

}


    /********************************************************************************************************************************/

    function get_check_info($id){
        $result= $this->{$this->MODEL_NAME}->get_check($id);

        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_check_payment($id);
        $data['check_data'] = $result;

        $data['details_check']=$result;

        $data['content'] = 'check_gedco';
        $data['title'] = 'بيانات الشيك';
        $data['action'] = 'edit';
        $data['isCreate'] = false;
        $data['isCreate2'] = false;
        $data['hide']='hidden';
        $data['hide_div_action']='hidden';
        $data['hide_div_paid']='hidden';
		$data['hide_div_action_del']='hidden';
        $data['hide_div_action_date_paid']='hidden';
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    /********************************************************************************************************************************/


    function create($id=0){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){


                $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

                if (intval($this->ser) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
                }



        if($this->p_check_action == 3){
            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                    if ($this->p_pay_date[$i] != '' ) {

                        $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions_checks($this->_postData_sub_actions(null,$this->ser, $this->p_pay_date[$i],$this->p_pay_value[$i],
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

        }


            echo intval($this->ser);



        }else{

            if($id!=0){

                $data['details']=array();
                $data['details_check']= $this->{$this->MODEL_NAME}->get_ret_check($id);
                $data['check_data'] = array();
                $data['content'] = 'check_gedco';
                $data['title'] = 'الشيكات المرجعة';
                $data['action'] = 'create';
                $data['isCreate'] = true;
                $data['isCreate2'] = false;
                $data['hide']='hidden';
                $data['hide_div_action']='hidden';
                $data['hide_div_paid']='hidden';
		$data['hide_div_action_del']='hidden';
                $data['hide_div_action_date_paid']='hidden';
                $data['help'] = $this->help;
                $this->_look_ups($data);
                $this->load->view('template/template', $data);

            }else{
                $result=array();
                $data['details_check']=$result;
                $data['details']=$result;
                $data['check_data'] = array();
                $data['title']='الشيكات المرجعة';
                $data['content']='check_index';
                $data['action'] = 'index';
                $data['isCreate']= true;
                $data['isCreate2'] = true;
                $data['hide']='hidden';
                $data['hide_div_action']='hidden';
                $data['hide_div_paid']='hidden';
		$data['hide_div_action_del']='hidden';
                $data['hide_div_action_date_paid']='hidden';
                $data['help']=$this->help;

                $this->_look_ups($data);
                $this->load->view('template/template',$data);
            }




        }

    }


    /********************************************************************************************************************************/

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $this->ser= $this->{$this->MODEL_NAME}->edit(($this->_postedData('edit')));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }


            if($this->p_check_action == 3){
            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                    if ($this->p_pay_date[$i] != '' ) {

                        $x=$this->{$this->DETAILS_MODEL_NAME}->create_sub_actions_checks($this->_postData_sub_actions(null,$this->ser, $this->p_pay_date[$i],$this->p_pay_value[$i],
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

                        $x=$this->{$this->DETAILS_MODEL_NAME}->edit_sub_actions_checks($this->_postData_sub_actions($this->p_seq1[$i],$this->ser, $this->p_pay_date[$i],$this->p_pay_value[$i],
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
            }
            }

            echo intval($this->ser);
        }
    }

    /********************************************************************************************************************************/

    function _postedData($isCreate)
    {
        $url="issues/checks/get_check_info/";
        $table_name='CHECKS_GEDCO_TB';

        $result = array(
            array('name' => 'SER', 'value' => $this->ser, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_NO', 'value' => $this->check_no, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_YEAR', 'value' => $this->check_year, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_DATE', 'value' => $this->check_date, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_NAME', 'value' => $this->check_name, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_VALUE', 'value' => $this->check_value, 'type' => '', 'length' => -1),
            array('name' => 'BANK_NAME', 'value' => $this->bank_name, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_ACTION', 'value' => $this->check_action, 'type' => '', 'length' => -1),
            array('name' => 'COMPLAINT_NO', 'value' => $this->complaint_no, 'type' => '', 'length' => -1),
            array('name' => 'COMPLAINT_YEAR', 'value' => $this->complaint_year, 'type' => '', 'length' => -1),
            array('name' => 'COMPLAINT_DATE', 'value' => $this->complaint_date, 'type' => '', 'length' => -1),
            array('name' => 'COMPLAINANT_NAME', 'value' => $this->complainant_name, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->notes, 'type' => '', 'length' => -1),
            array('name' => 'URL', 'value' => $url, 'type' => '', 'length' => -1),
            array('name' => 'TABLE_NAME', 'value' => $table_name, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_BRANCH_USER', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'SUB_NO', 'value' => $this->sub_no, 'type' => '', 'length' => -1),
            array('name' => 'SUB_NAME', 'value' => $this->sub_name, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $this->id, 'type' => '', 'length' => -1),
            array('name' => 'FOR_MONTH', 'value' => $this->for_month, 'type' => '', 'length' => -1),
            array('name' => 'NET_PAY', 'value' => $this->net_pay, 'type' => '', 'length' => -1),
            array('name' => 'TYPE_NAME', 'value' => $this->type_name, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->address, 'type' => '', 'length' => -1),
            array('name' => 'PAID_VAL', 'value' => $this->paid_val, 'type' => '', 'length' => -1),
            array('name' => 'DELIV_DATE', 'value' => $this->deliv_date, 'type' => '', 'length' => -1),
            array('name' => 'ACTION_DATE', 'value' => $this->action_date, 'type' => '', 'length' => -1),
            array('name' => 'COMPLAINANT_ID', 'value' => $this->complainant_id, 'type' => '', 'length' => -1),
            array('name' => 'ACTION_DATE_PAID', 'value' => $this->action_date_paid, 'type' => '', 'length' => -1),
            array('name' => 'SER_CHECK', 'value' => $this->ser_check, 'type' => '', 'length' => -1),
            array('name' => 'BANK_NO', 'value' => $this->bank_no, 'type' => '', 'length' => -1),


        );

        if ($isCreate=='create') {
            array_shift($result);
        }

        return $result;

    }

    /********************************************************************************************************************************/

    function _postData_sub_actions($ser = null,$check_ser= null,$pay_date=null, $pay_value=null,$type)
    {

        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_SER', 'value' => $check_ser, 'type' => '', 'length' => -1),
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
    function public_get_court($branch =0){
        $branch = $this->input->post('no')?$this->input->post('no'):$branch;
        $arr = $this->indicator_model->indecator_info(251,$branch);
        echo json_encode($arr);
    }

    /********************************************************************************************************************************/
    function public_get_court_b($branch =0){
        $arr = $this->indicator_model->indecator_info(251,$branch);
        return $arr;
    }

    /********************************************************************************************************************************/

    function public_get_page_check($page = 1,$check_no= -1,$check_date= -1,$check_value= -1,$bank_name= -1, $check_customer=''){

        $this->load->library('pagination');
        $check_no=$this->check_vars($check_no,'check_no');
        $bank_name=$this->check_vars($bank_name,'bank_name');
        $check_date=$this->check_vars($check_date,'check_date');
        $check_value=$this->check_vars($check_value,'check_value');
        $check_customer=$this->check_vars($check_customer,'check_customer');
        $where_sql = "where 1=1";
        $where_sql= ($check_no!= null)? " and C.CHECK_ID= '{$check_no}' " : '';
        $where_sql.= ($bank_name!= null)? " and C.CHECK_BANK_ID= '{$bank_name}' " : '';
        $where_sql.= ($check_value!= null)? " and CRIDET= '{$check_value}' " : '';
        $where_sql.= ($check_date!= null)? " and C.CHECK_DATE= '{$check_date}' " : '';
        $where_sql.= ($check_customer!= null)? " and C.CHECK_CUSTOMER like '".add_percent_sign($check_customer)."' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        /***/$count_rs = $this->get_table_count('CHECKS_PORTFOLIO_TB', $where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_pay_list($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('check_page', $data);


    }


    /********************************************************************************************************************************/
    function adopt(){
        /**/$table_name='CHECKS_GEDCO_TB';

        $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id,2,$table_name,$this->user->branch,$this->user->id);

        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
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
    function unadopt ()
    {
        /**/$table_name='CHECKS_GEDCO_TB';

        $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id,1,$table_name,$this->user->branch,$this->user->id);

        if(intval($res) <= 0) {
            $this->print_error('لم يتم الارجاع'.'<br>'.$res);
        }
        else
            echo 1;

    }

    /********************************************************************************************************************************/
    function get_page($page = 1,$check_no= -1,$check_year= -1,$complaint_no= -1,$complaint_year= -1,$adopt=-1,$branch=-1)
    {
        $this->load->library('pagination');
        $check_no=$this->check_vars($check_no,'check_no');
        $check_year=$this->check_vars($check_year,'check_year');
        $complaint_no=$this->check_vars($complaint_no,'complaint_no');
        $complaint_year=$this->check_vars($complaint_year,'complaint_year');
        $adopt=$this->check_vars($adopt,'adopt');
        $branch=$this->check_vars($branch,'branch');

        $where_sql = "where 1=1";
        $where_sql.= ($check_no!= null)? " and check_no= '{$check_no}' " : '';
        $where_sql.= ($check_year!= null)? " and check_year= '{$check_year}' " : '';
        $where_sql.= ($complaint_no!= null)? " and complaint_no= '{$complaint_no}' " : '';
        $where_sql.= ($complaint_year!= null)? " and complaint_year= '{$complaint_year}' " : '';

        $where_sql .= isset($this->adopt) && $this->adopt != null ? " AND  adopt ={$this->adopt}" : "";
        $where_sql .= isset($this->branch) && $this->branch != null ? " AND  BRANCH ={$this->branch}" : "";



        $config['base_url'] = base_url($this->PAGE_URL);
        /***/$count_rs = $this->get_table_count('CHECKS_GEDCO_TB', $where_sql);
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
        $this->load->view('checks_portfolio_page', $data);

    }


    function get_ret_check_info($id){

        $result= $this->{$this->MODEL_NAME}->get_ret_check($id);
        $data['ret_check_data'] = $result;
        $data['content'] = 'check_gedco';
        $data['title'] = 'الشيكات المرجعة';
        $data['action'] = 'create';
        $data['isCreate'] = false;
        $data['isCreate2'] = false;
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);

    }




}