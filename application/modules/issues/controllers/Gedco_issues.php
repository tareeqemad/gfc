<?php



class gedco_issues extends MY_Controller{

    var $MODEL_NAME= 'issues_gedco_model';
    var $MODULE_NAME= 'issues';

    var $TB_NAME= 'gedco_issues';
    var $PAGE_URL= 'issues/gedco_issues/get_page';
    var $DETAILS_MODEL_NAME = 'issues_gedco_details_model';
    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->ser= $this->input->post('ser');
        $this->name= $this->input->post('name');
        $this->address= $this->input->post('address');
        $this->agent= $this->input->post('agent');
        $this->branch= $this->input->post('branch');
        $this->insert_branch_user= $this->input->post('insert_branch_user');
        $this->court_name= $this->input->post('court_name');
        $this->issue_branch= $this->input->post('issue_branch');
        $this->issue_no= $this->input->post('issue_no');
        $this->issue_year= $this->input->post('issue_year');
        $this->issue_type= $this->input->post('issue_type');
        $this->issue_value= $this->input->post('issue_value');
        $this->issues_notes= $this->input->post('issues_notes');
        $this->currency= $this->input->post('currency');

        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->load->model('indicator/indicator_model');
    }

    /********************************************************************************************************************************/

    function index($page = 1,$sub_no= -1,$sub_name= -1,$id= -1,$issue_no= -1,$issue_year= -1,$issue_type= -1,$exe_issue_no= -1,$exe_issue_year= -1,$status=-1,$adopt=-1,$branches=-1,$for_month=-1){
        $data['page'] = $page;
        $data['title']='القضايا القانونية المرفوعة ضد الشركة';
        $data['content']='gedco_issues_index';
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
        $url="issues/gedco_issues/get_issue_info/";

        $result = array(
            array('name' => 'SER', 'value' => $this->ser, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $this->name, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->address, 'type' => '', 'length' => -1),
            array('name' => 'AGENT', 'value' => $this->agent, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
            array('name' => 'COURT_NAME', 'value' => $this->court_name, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_NO', 'value' => $this->issue_no, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_YEAR', 'value' => $this->issue_year, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_TYPE', 'value' => $this->issue_type, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_VALUE', 'value' => $this->issue_value, 'type' => '', 'length' => -1),
            array('name' => 'ISSUES_NOTES', 'value' =>$this->issues_notes, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'INSERT_BRANCH_USER', 'value' =>$this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'URL', 'value' => $url, 'type' => '', 'length' => -1),
            array('name' => 'CURRENCY', 'value' => $this->currency, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $this->p_endedstatus, 'type' => '', 'length' => -1),


        );

       // var_dump($result);


        if ($isCreate=='create') {
            array_shift($result);
        }



        return $result;

    }

    /********************************************************************************************************************************/

    function public_get_gedco_action_details($id = 0,$adopt=1)

    {

        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_gedco_issues_actions_all($id);
        $data['adopt']=$adopt;
        $this->_look_ups($data);
        $this->load->view('gedco_action_details', $data);
    }

    /********************************************************************************************************************************/

    function public_get_request_details($id = 0,$adopt=1)

    {

        $data['details_ins'] = $this->{$this->DETAILS_MODEL_NAME}->get_gedco_issue_requests_all($id);
        $data['adopt']=$adopt;
        $this->_look_ups($data);
        $this->load->view('request_details', $data);
    }
    /********************************************************************************************************************************/

    function public_defendant_gedco_action_details($id = 0,$adopt=1)

    {

        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_defendant_tb_all($id);
        $data['adopt']=$adopt;
        $this->_look_ups($data);
        $this->load->view('defendant_gedco_action_details', $data);
    }
    /********************************************************************************************************************************/

    function create($id=0){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }

            for ($i = 0; $i < count($this->p_d_ser); $i++)
            {
                if ($this->p_d_ser[$i] <= 0)
                {
                    $x=$this->{$this->DETAILS_MODEL_NAME}->create_defendant_tb($this->_postData_defendant_tb(null,$this->ser, $this->p_d_name[$i], $this->p_d_addres[$i],$this->p_d_branch[$i],'create'));
                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }

                }
                else if($this->p_d_ser[$i] > 0)
                {
                    $x=$this->{$this->DETAILS_MODEL_NAME}->edit_defendant_tb($this->_postData_defendant_tb($this->p_d_ser[$i],$this->p_h_txt_issue_ser[$i], $this->p_d_name[$i], $this->p_d_addres[$i],$this->p_d_branch[$i],'edit'));
                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                }
            }

      for ($i = 0; $i < count($this->p_seq12); $i++)
            {
                if ($this->p_seq12[$i] <= 0)
                {
                    if ($this->p_req_no[$i]!='' && $this->p_req_year[$i]!='' && $this->p_req_type[$i] !='')
                    {
                    $x=$this->{$this->DETAILS_MODEL_NAME}->create_gedco_issue_requests($this->_postData_gedco_issue_requests(null,$this->ser, $this->p_req_no[$i], $this->p_req_year[$i],$this->p_req_type[$i],'create'));
                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                    }


                }
                else if($this->p_seq12[$i] > 0)
                {
                    $x=$this->{$this->DETAILS_MODEL_NAME}->edit_gedco_issue_requests($this->_postData_gedco_issue_requests($this->p_seq12[$i],$this->p_h_txt_issue_req_ser[$i], $this->p_req_no[$i], $this->p_req_year[$i],$this->p_req_type[$i],'edit'));
                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                }
            }


            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0)
                {
                    $x=$this->{$this->DETAILS_MODEL_NAME}->create_gedco_issues_actions($this->_postdata_gedco_issues_actions(null,$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],'create'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }

                }
                else if($this->p_seq1[$i] > 0)
                {
                    $x=$this->{$this->DETAILS_MODEL_NAME}->edit_gedco_issues_actions($this->_postdata_gedco_issues_actions($this->p_seq1[$i],$this->p_h_txt_issue_ser[$i], $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],'edit'));
                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }
            }


            echo intval($this->ser);



        }else{

                $result=array();
                //$data['details']=$result;
                $data['issues_data']=$result;
                $data['content'] = 'gedco_issues';
                $data['title']=' القضايا القانونية المرفوعة ضد الشركة';
                $data['action'] = 'index';
                $data['isCreate']= true;
                $data['isCreate2']= true;
                $data['help']=$this->help;
                $this->_look_ups($data);
                $this->load->view('template/template',$data);


        }
    }

    /********************************************************************************************************************************/
    /********************************************************************************************************************************/

    function edit(){
        $this->_post_validation();
        $this->ser= $this->{$this->MODEL_NAME}->edit($this->_postedData('edit'));
        if (intval($this->ser) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
        }


        for ($i = 0; $i < count($this->p_d_ser); $i++)
        {

            if ($this->p_d_ser[$i] <= 0)
            {
                $x=$this->{$this->DETAILS_MODEL_NAME}->create_defendant_tb($this->_postData_defendant_tb(null,$this->ser, $this->p_d_name[$i], $this->p_d_addres[$i],$this->p_d_branch[$i],'create'));
                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }

            }
            else if($this->p_d_ser[$i] > 0)
            {

                $x=$this->{$this->DETAILS_MODEL_NAME}->edit_defendant_tb($this->_postData_defendant_tb($this->p_d_ser[$i],$this->p_h_txt_issue_ser[$i], $this->p_d_name[$i], $this->p_d_addres[$i],$this->p_d_branch[$i],'edit'));
                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }
            }
        }

       for ($i = 0; $i < count($this->p_seq12); $i++)
        {
            if ($this->p_seq12[$i] <= 0)
            {
                if ($this->p_req_no[$i]!='' && $this->p_req_year[$i]!='' && $this->p_req_type[$i] !='')
                {
                $x=$this->{$this->DETAILS_MODEL_NAME}->create_gedco_issue_requests($this->_postData_gedco_issue_requests(null,$this->ser, $this->p_req_no[$i], $this->p_req_year[$i],$this->p_req_type[$i],'create'));
                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }
                }

            }
            else if($this->p_seq12[$i] > 0)
            {
                $x=$this->{$this->DETAILS_MODEL_NAME}->edit_gedco_issue_requests($this->_postData_gedco_issue_requests($this->p_seq12[$i],$this->p_h_txt_issue_req_ser[$i], $this->p_req_no[$i], $this->p_req_year[$i],$this->p_req_type[$i],'edit'));
                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }
            }
        }


       for ($i = 0; $i < count($this->p_seq1); $i++)
        {
            if ($this->p_seq1[$i] <= 0)
            {
                $x=$this->{$this->DETAILS_MODEL_NAME}->create_gedco_issues_actions($this->_postdata_gedco_issues_actions(null,$this->ser, $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],'create'));
                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }

            }
            else if($this->p_seq1[$i] > 0)
            {
                $x=$this->{$this->DETAILS_MODEL_NAME}->edit_gedco_issues_actions($this->_postdata_gedco_issues_actions($this->p_seq1[$i],$this->p_h_txt_issue_ser[$i], $this->p_issue_date_action[$i], $this->p_type[$i],$this->p_j_date[$i],$this->p_j_notes[$i],$this->p_hints[$i],'edit'));
                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }


            }
        }


        echo intval($this->ser);
    }
    /**********************************************************************************************************************************/
    function _post_validation(){
       if(  $this->name=='' or $this->address==''  or $this->issue_no=='' or $this->issue_year=='' or $this->issue_type=='' or $this->issue_value==''){
            $this->print_error('يجب ادخال جميع البيانات');
        }

        for ($i = 0; $i < count($this->p_d_ser); $i++)
        {


               if ($this->p_d_name[$i]=='' or $this->p_d_addres[$i]=='' or $this->p_d_branch[$i]=='')
                {

                    $this->print_error('يجب ادخال جميع حقول بيانات المدعي عليه!!');
                }



    }

        for ($i = 0; $i < count($this->p_seq12); $i++)
        {
            if ($this->p_req_no[$i]!='' or $this->p_req_year[$i]!='' or $this->p_req_type[$i] !='')
            {
                if ($this->p_req_no[$i]=='' or $this->p_req_year[$i]=='' or $this->p_req_type[$i] =='')
                {
                    $this->print_error('يجب ادخال جميع حقول الطلبات!!');

                }


            }

        }

        for ($i = 0; $i < count($this->p_seq1); $i++)
        {

                if( $this->p_issue_date_action[$i]=='' or $this->p_type[$i]=='')
            {
                $this->print_error('يجب ادخال حقل تاريخ الجلسة و الاجراء على الاقل!!');
            }



        }
    }
    /**********************************************************************************************************************************/
    function _postData_defendant_tb($d_ser = null,$issue_ser, $d_name, $d_addres,$d_branch,$type=null)

    {


        $result = array(
            array('name' => 'D_SER', 'value' => $d_ser, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_SER', 'value' => $issue_ser, 'type' => '', 'length' => -1),
            array('name' => 'D_NAME', 'value' => $d_name, 'type' => '', 'length' => -1),
            array('name' => 'D_ADDRES', 'value' => $d_addres, 'type' => '', 'length' => -1),
            array('name' => 'D_BRANCH', 'value' => $d_branch, 'type' => '', 'length' => -1),
            array('name' => 'USER_ID', 'value' => $this->user->id, 'type' => '', 'length' => -1),


        );



        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    /**********************************************************************************************************************************/
    function _postData_gedco_issue_requests($ser = null,$issues_ser, $req_no, $req_year,$req_type,$type=null)

    {


        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ISSUES_SER', 'value' => $issues_ser, 'type' => '', 'length' => -1),
            array('name' => 'REQ_NO', 'value' => $req_no, 'type' => '', 'length' => -1),
            array('name' => 'REQ_YEAR', 'value' => $req_year, 'type' => '', 'length' => -1),
            array('name' => 'REQ_TYPE', 'value' => $req_type, 'type' => '', 'length' => -1),
            array('name' => 'USER_ID', 'value' => $this->user->id, 'type' => '', 'length' => -1),


        );



        if ($type == 'create')
            unset($result[0]);

        return $result;
    }
    /**********************************************************************************************************************************/
    function _postdata_gedco_issues_actions($ser = null,$issue_ser, $issue_date_action,$action_type, $j_date,$j_notes,$hints,$type=null)

    {

       $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_SER', 'value' => $issue_ser, 'type' => '', 'length' => -1),
            array('name' => 'ISSUE_DATE_ACTION', 'value' => $issue_date_action, 'type' => '', 'length' => -1),
            array('name' => 'TYPE', 'value' => $action_type, 'type' => '', 'length' => -1),
            array('name' => 'J_DATE', 'value' => $j_date, 'type' => '', 'length' => -1),
            array('name' => 'J_NOTES', 'value' => $j_notes, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'USER_ID', 'value' => $this->user->id, 'type' => '', 'length' => -1),


        );



        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    /*******************************************************************/
    function adopt(){
        $table_name='ISSUE_TO_GEDCO_TB';

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
        $table_name='ISSUE_TO_GEDCO_TB';

        $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id,1,$table_name,$this->user->branch,$this->user->id);

        if(intval($res) <= 0) {
            $this->print_error('لم يتم الغاء الاعتماد'.'<br>'.$res);
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
        $result=$this->{$this->MODEL_NAME}->get_issue($id);
        $data['details']=$result;
        $data['issues_data']=$result;
        $data['content'] = 'gedco_issues';
        $data['title'] = 'تعديل قضايا القانونية المرفوعة ضد الشركة';
        $data['action'] = 'edit';
        $data['isCreate']= false;
        $data['isCreate2']= false;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);


    }



    /********************************************************************************************************************************/

    function _look_ups(&$data){
        add_css('combotree.css');
        add_css('datepicker3.css');
        add_js('jquery.hotkeys.js');
        add_js('moment.js');
        add_js('bootstrap.min.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['types'] = $this->constant_details_model->get_list(258);
        $data['issue_type'] = $this->constant_details_model->get_list(252);
        $data['court_name'] = $this->constant_details_model->get_list(251);
        $data['paid'] = $this->constant_details_model->get_list(259);
        $data['status'] = $this->constant_details_model->get_list(253);
        $data['adopt'] = $this->constant_details_model->get_list(254);
        $data['gedco_types'] = $this->constant_details_model->get_list(345);
        $data['currency'] = $this->constant_details_model->get_list(364);
        $data['endedstatus'] = $this->constant_details_model->get_list(256);//$this->constant_details_model->get_list(532);


        $data['branches_all'] = $this->gcc_branches_model->get_all();
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

function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = " where 1=1 ";


        $where_sql .= isset($this->p_issue_no) && $this->p_issue_no != null ? " AND  M.ISSUE_NO ='{$this->p_issue_no}'  " : "";
        $where_sql .= isset($this->p_issue_year) && $this->p_issue_year != null ? " AND   M.ISSUE_YEAR ={$this->p_issue_year}  " : "";
        $where_sql .= isset($this->p_court_name) && $this->p_court_name != null ? " AND   M.COURT_NAME ={$this->p_court_name}  " :  "";
        $where_sql .= isset($this->p_name) && $this->p_name != null ? " AND  NAME LIKE '%{$this->p_name}%' " : "";
        $where_sql .= isset($this->p_adopt) && $this->p_adopt != null ? " AND  M.ADOPT ='{$this->p_adopt}'  " : "";
        $where_sql .= isset($this->p_endedstatus) && $this->p_endedstatus != null ? " AND  M.STATUS ='{$this->p_endedstatus}'  " : "";


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('ISSUE_TO_GEDCO_TB', $where_sql);
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
       /* if ($this->user->branch<>1 )
        {
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list_branch($where_sql, $offset, $row);


        }
        else
            $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
*/

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
        $data['curr_page_rows'] = $this->{$this->MODEL_NAME}->get_curr_list($where_sql);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('gedco_issue_page', $data);




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