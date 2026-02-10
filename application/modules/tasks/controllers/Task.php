<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 08/04/18
 * Time: 08:54 ص
 */
class Task extends MY_Controller
{
    var $PAGE_URL= 'tasks/task/public_get_tasks';
    var $PAGE_ACT;


    function  __construct()
    {
        
         parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TASK_PKG';
        $this->load->library("pagination");


        // task_no ,parent_task ,emp_no ,task_title ,task_text ,priority ,task_direction_type, done_date
        $this->task_no = $this->input->post('task_no');
        $this->parent_task = $this->input->post('parent_task');

        $this->emp_no = $this->user->emp_no;
        $this->task_title = $this->input->post('task_title');
        $this->task_text = $this->input->post('task_text');
        $this->priority = $this->input->post('priority');
        $this->task_direction_type = $this->input->post('task_direction_type');
        $this->done_date = $this->input->post('done_date');
        $this->attachment_ser = $this->input->post('attachment_ser');
        $this->search_task=$this->input->post('search_task');
        // array
        $this->cat_no = $this->input->post('cat_no');
        $this->item_desc = $this->input->post('item_desc');
        $this->item_emp_no = $this->input->post('item_emp_no');
    }



    function index($page= 1,$parent_task = 0)
    {

        add_css('task.css');
        add_css('tabletask.css');
        add_css('todo-2-rtl.min.css');
       

        $data['parent_task'] = $parent_task;

        $data['content'] = 'task_popup';
        $data['title'] = 'المهام والمراسلات';
        $data['page']=$page;
        $this->_lookUps_data($data);
        $this->_loadDatePicker();
        $this->load->view('template/template', $data);
    }

    /**
     * constants data
     */
    function _lookUps_data(&$data)
    {
        $this->load->model('settings/constant_details_model');
        $data['help'] = $this->help;
        $data['emps_direct'] = $this->rmodel->get('EMP_TREE_GET', 1506);
        $data['direct_type_cons'] = $this->constant_details_model->get_list(218);
        $data['priority_cons'] = $this->constant_details_model->get_list(219);
        $data['task_direction_type_cons'] = $this->constant_details_model->get_list(220);
        $data['cats_list'] = $this->rmodel->get('TASK_INDEX_CLASS_TB_LIST', 1); ////////////////////////////////
        $this->_loadDatePicker();
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('ckeditor/ckeditor.js');
        add_css('datepicker3.css');
        add_css('animate.css');
        add_js('tooltip.js');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_js('jquery-ui.min.js');
        add_js('main.js');
        $this->_generate_std_urls($data, true);
    }


    /**
     * create new task
     *
     */
    function Create($parent_task = 0)
    {
        //check if http request is post , that mean insert action
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $direct_all = '';
            for ($i = 1; $i <= 10; $i++) {
                $direct_x = 'p_direct_' . $i;
                if (isset($this->{$direct_x})) {
                    $direct_all .= $i . ',' . $this->{$direct_x} . '-';
                }
            }
            $direct_all = trim($direct_all, '-');

            if ($direct_all == '1,-2,-3,') {
                $this->print_error('يجب ادخال مستلم واحد على الاقل');
            }

            $this->task_no = $this->rmodel->insert('TASK_TB_INSERT', $this->_postedData(true, $direct_all));
            if (intval($this->task_no) <= 0) {
                $this->print_error(' فشل في حفظ البيانات ' . $this->task_no);
            } else {
                /* mk2021
                for ($i = 0; $i < count($this->item_desc); $i++) {
                    if ($this->cat_no[$i] != '' and $this->item_desc[$i] != '' and $this->item_emp_no[$i] != '') {
                        $detail_seq = $this->rmodel->insert('TASK_INDEX_TB_INSERT', $this->_postedData_details($this->cat_no[$i], $this->item_desc[$i], $this->item_emp_no[$i]));

                        if (intval($detail_seq) <= 0) {
                            $this->print_error_del($detail_seq);
                        }


                    }
                }
                */
                echo intval($this->task_no);
            }

        } else { // show empty form for insert


            $data['parent_task'] = $parent_task;
            $data['content'] = 'task_popup';
            if ($parent_task > 0) {
                $task_info = $this->rmodel->get('TASK_TB_GET', $parent_task);
                if (count($task_info) < 1) die('Error parent task');
                $data['title'] = ' مهمة فرعية جديدة - ' . $task_info[0]['TASK_TITLE'];
            } else {
                $data['title'] = ' مهمة جديدة ';
            }

            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }

    }

    function _post_validation()
    {
        if ($this->task_title == '' or $this->task_text == '') {
            $this->print_error('يجب ادخال عنوان ونص المهمة');
        }
    }

    function print_error_del($msg = '')
    {
        $ret = $this->rmodel->delete('TASK_TB_DELETE', $this->task_no);
        if (intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: ' . $msg);
        else
            $this->print_error('لم يتم حذف السند: ' . $msg);
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = null;
            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات' . $result);
            }

            echo $result;

        }
    }

    function viewrecep(){
        $arrViewData = array("$this->rmodel");
    }



    function Reply()
    {

        //check if http request is post , that mean insert action
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $replay_to_emps = implode(',', $this->p_replay_to_emps);

            $params = array(
                array('name' => 'TASK_NO', 'value' => $this->p_task_id, 'type' => '', 'length' => -1),
                array('name' => 'REPLY_TEXT', 'value' => $this->p_text, 'type' => '', 'length' => -1),
                array('name' => 'IS_EXTENT', 'value' => $this->p_extent, 'type' => '', 'length' => -1),
                array('name' => 'REPLAY_TO_EMPS', 'value' => $replay_to_emps, 'type' => '', 'length' => -1),
            );

            $result = $this->rmodel->insert('TASK_REPLAY_TB_INSERT', $params);

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات' . $result);
            }

            echo $result;


        }
    }

    function ApplySubTaskAction()
    {

        //check if http request is post , that mean insert action
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            foreach ($this->p_data as $row) {

                $params = array(
                    array('name' => 'ITEM_NO', 'value' => $row['item_no'], 'type' => '', 'length' => -1),
                    array('name' => 'STATUS', 'value' => $row['status'], 'type' => '', 'length' => -1),
                );

                $result = $this->rmodel->insert('TASK_INDEX_TB_UPDATE', $params);
            }

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات' . $result);
            }

            echo $result;
        }
    }

    /**
     * get list of tasks
     * by type of task , income or outcome
     * income => 1
     * outcome => 2
     */
    function public_get_tasks($page= 1)
    {

        //operator to prevent join matching
        $this->load->library('pagination');
        $op = $this->p_type == 2 ? " = " : "<>";
        $where = " AND M.EMP_NO $op T.DIRECT_EMP_NO WHERE 1=1 and m.PARENT_TASK= 0 ";

        //  $where = " AND M.EMP_NO $op T.DIRECT_EMP_NO WHERE TASK_NO = TASK_NO ";

        $sql = $this->p_type == 2 ? " AND M.EMP_NO = {$this->user->emp_no}" : "";
        $sql = $this->p_type == 1 || $this->p_type == 3 ? " AND T.DIRECT_EMP_NO = USER_PKG.GET_USER_EMP_NO " : $sql;
        ////////////////////////////////  USER_PKG.GET_USER_EMP_NO ({$this->user->id})
        $sql .= $this->search_task != '' ? " AND ( M.task_title like '%".$this->search_task."%' OR M.task_text like '%".$this->search_task."%' ) " : "" ;

        $data['tasks'] = $this->rmodel->getList('TASK_TB_LIST', " $where $sql ", 0, 100);

        //income outcoem
    /*  $config["base_url"] = base_url() . "task/public_get_tasks";
       // $config["data"] = $this->rmodel->getList('TASK_TB_LIST');
        $data['tasks'] = $this->rmodel->getList('TASK_TB_LIST', " $where $sql ", 0, 10);
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $data["links"] = $this->pagination->create_links();*/

        $data['p_type']=$this->p_type;


/*
        $config['base_url'] = base_url($this->PAGE_URL);
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = count($this->rmodel->getList('TASK_TB_LIST', " $where $sql ", 0, 1000));
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($this->rmodel->getList('TASK_TB_LIST', " $where $sql ", 0, 1000));
        //$config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
       $config['per_page'] =10;
        $config['num_links'] = 20;
        $config['cur_page']=$page;


        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";



        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );
        $data['page_rows'] = $this->rmodel->getList('TASK_TB_LIST', " $where $sql ", $offset, $row);
        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->pagination->initialize($config);

*/



        $this->load->view('task_t', $data);

    }
    

    function public_get_tasks_statistics()
    {
        $statistics = $this->rmodel->getData('TASK_STATISTICS');
        return $this->return_json($statistics);
    }

    /**
     * get task details by id
     */
    function public_get_task()
    {
        $data['task'] = $this->rmodel->get('TASK_TB_GET', $this->p_id)[0];
        $data['subTasks'] = $this->rmodel->getList('TASK_TB_LIST', " WHERE PARENT_TASK =  {$this->p_id} ", 0, 100);
        $data['RedirectTasks'] = $this->rmodel->getList('TASK_TRANSFER_TB_LIST', " AND M.TASK_NO  = {$this->p_id}", 0, 100);
        $data['ReplyTasks'] = $this->rmodel->getList('TASK_REPLAY_TB_LIST', " AND TASK_NO  = {$this->p_id}", 0, 100);
    
        $data['ItemsTasks'] = $this->rmodel->getList('TASK_INDEX_TB_LIST', " AND TASK_NO  = {$this->p_id}", 0, 100);
        $this->_lookUps_data($data);
        $this->load->view('task_details', $data);
    }
///////////////////////////////////////////////////////

    function public_get_task2()
    {

        $data['task'] = $this->rmodel->get('TASK_TB_GET', $this->p_id)[0];
        $data['RedirectTasks'] = $this->rmodel->getList('TASK_TRANSFER_TB_LIST', " AND M.TASK_NO  = {$this->p_id}", 0, 100);
        $this->load->view('task_test', $data);
    }


/////////////////////////////////////////////////////////////////////
function public_get_task_transaction()
{
    $data['task'] = $this->rmodel->get('TASK_TB_GET', $this->p_id)[0];
    $data['subTasks'] = $this->rmodel->getList('TASK_TB_LIST', " WHERE PARENT_TASK =  {$this->p_id} ", 0, 100);
    $data['RedirectTasks'] = $this->rmodel->getList('TASK_TRANSFER_TB_LIST', " AND M.TASK_NO  = {$this->p_id}", 0, 100);
    $data['ReplyTasks'] = $this->rmodel->getList('TASK_REPLAY_TB_LIST', " AND TASK_NO  = {$this->p_id}", 0, 100);
    $data['ItemsTasks'] = $this->rmodel->getList('TASK_INDEX_TB_LIST', " AND TASK_NO  = {$this->p_id}", 0, 100);
    $this->_lookUps_data($data);
    $this->load->view('task_transaction', $data);
}

/////////////////////////////////////////////////////////////////////
function public_get_each_replay(){
    $sql = " AND TASK_NO  = {$this->task_no} AND EMP_NO = {$this->p_emp_no} ";
    $data['ReplyTasks'] = $this->rmodel->getList('TASK_REPLAY_TB_LIST', $sql, 0, 100);
    $this->load->view('task_each_replay', $data);
}
////////////////////////////////////////////////////////
    /**
     * actions :
     * close ,
     * delay ,
     *  ,
     */
    function actions()
    {

        //check if http request is post , that mean insert action
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = null;
            if($this->p_action == 'extent'){
                $params = array(
                    array('name' => 'TASK_NO', 'value' => $this->p_task_id, 'type' => '', 'length' => -1),
                    array('name' => 'DATE', 'value' => $this->p_date, 'type' => '', 'length' => -1),
                );
            }
          else  if ($this->p_action != 'transfer') {

                $params = array(
                    array('name' => 'TASK_NO', 'value' => $this->p_task_id, 'type' => '', 'length' => -1),
                    array('name' => 'STATUS', 'value' => $this->p_status, 'type' => '', 'length' => -1),
                    
                );
            }

            switch ($this->p_action) {

                case 'close':
                    $result = $this->rmodel->insert('TASK_TB_CANCEL', $params);
                    break;

                    case 'extent' :
                    $result = $this->rmodel->update('TASK_TB_EXTENT', $params);
                    break;

                case 'delay':
                    $result = $this->rmodel->update('TASK_TB_CANCEL', $params);
                    break;

                case 'reopen':
                    $result = $this->rmodel->update('TASK_TB_CANCEL', $params);
                    break;

                case 'transfer' :

                    $this->task_no = $this->p_task_id;

                    foreach ($this->p_data as $row) :

                        $con_id = $row['con_id'];

                        //employees
                        if (isset($row['employees'])) {
                            $employees = $row['employees'];

                            foreach ($employees as $emp) :

                                $params = array(
                                    array('name' => 'TASK_NO', 'value' => $this->p_task_id, 'type' => '', 'length' => -1),
                                    array('name' => 'DIRECT_EMP_NO', 'value' => $emp, 'type' => '', 'length' => -1),
                                    array('name' => 'DIRECT_TYPE', 'value' => $con_id, 'type' => '', 'length' => -1),
                                );

                                $this->rmodel->update('TASK_TRANSFER_TB_INSERT', $params);
                            endforeach;
                        }

                    endforeach;

                    //indicator measurement
                    foreach ($this->p_indicator_measurement as $row) :

                        if ($row['cat_no'] != '' and $row['item_desc'] != '' and $row['item_emp_no'] != '') {

                            $this->rmodel->insert('TASK_INDEX_TB_INSERT', $this->_postedData_details($row['cat_no'], $row['item_desc'], $row['item_emp_no']));
                        }

                    endforeach;

                    //as default
                    $result = 1;

                    break;


            }

            if (intval($result) <= 0) {
                $this->print_error($result);
            }

            echo $result;


        }


    }

    function _postedData($isCreate = true, $direct_all = '')
    {
        $result = array(
            array('name' => 'TASK_NO', 'value' => $this->task_no, 'type' => '', 'length' => -1),
            array('name' => 'PARENT_TASK', 'value' => $this->parent_task, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => 'TASK_TITLE', 'value' => $this->task_title, 'type' => '', 'length' => -1),
            array('name' => 'TASK_TEXT', 'value' => $this->task_text, 'type' => '', 'length' => -1),
            array('name' => 'PRIORITY', 'value' => $this->priority, 'type' => '', 'length' => -1),
            array('name' => 'TASK_DIRECTION_TYPE', 'value' => $this->task_direction_type, 'type' => '', 'length' => -1),
            array('name' => 'DONE_DATE', 'value' => $this->done_date, 'type' => '', 'length' => -1),
            array('name' => 'attachment_ser', 'value' => $this->attachment_ser, 'type' => '', 'length' => -1),
            array('name' => 'DIRECTS', 'value' => $direct_all, 'type' => '', 'length' => -1),
            //array('name'=>'CATS','value'=>$this->p_cats ,'type'=>'','length'=>-1),
        );

        if ($isCreate)
            array_shift($result);
        return $result;
    }

    function _postedData_details($cat_no = null, $item_desc = null, $item_emp_no = null)
    {
        $result = array(
            array('name' => 'TASK_NO', 'value' => $this->task_no, 'type' => '', 'length' => -1),
            array('name' => 'CAT_NO', 'value' => $cat_no, 'type' => '', 'length' => -1),
            array('name' => 'ITEM_DESC', 'value' => $item_desc, 'type' => '', 'length' => -1),
            array('name' => 'ITEM_EMP_NO', 'value' => $item_emp_no, 'type' => '', 'length' => -1),
        );
        return $result;
    }


}