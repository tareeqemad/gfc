<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/06/22
 * Time: 09:00 ص
 */
class Add_and_ded_items extends MY_Controller {

    var $MODEL_NAME= 'Add_and_ded_items_model';
    var $PAGE_URL= 'payroll_data/Add_and_ded_items/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

        $this->ser = $this->input->post('ser');
        $this->item_name = $this->input->post('item_name');
        $this->item_type = $this->input->post('item_type');
        $this->special = $this->input->post('special');
        $this->item_type = $this->input->post('item_type');
        $this->constant = $this->input->post('constant');
        $this->con_group = $this->input->post('con_group');
        $this->item_val = $this->input->post('item_val');
        $this->vacancy_ded = $this->input->post('vacancy_ded');
        $this->absence_ded = $this->input->post('absence_ded');
        $this->case_item = $this->input->post('case_item');
    }

    function index()
    {
        $data['content']='add_and_ded_items_index';
        $data['title']='بنود الإستحقاق والإستقطاع';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->ser!= null)? " and M.NO= '{$this->ser}' " : '';
        $where_sql .= isset($this->item_name) && $this->item_name !=null ? " AND  M.NAME like '%{$this->item_name}%' " :"" ;
        $where_sql.= ($this->special!= null)? " and M.IS_SPECIAL= '{$this->special}' " : '';
        $where_sql.= ($this->item_type!= null)? " and M.IS_ADD= '{$this->item_type}' " : '';
        $where_sql.= ($this->constant!= null)? " and M.IS_CONSTANT= '{$this->constant}' " : '';
        $where_sql.= ($this->con_group!= null)? " and M.CON_G= '{$this->con_group}' " : '';
        $where_sql.= ($this->vacancy_ded!= null)? " and M.VACANCY_DED= '{$this->vacancy_ded}' " : '';
        $where_sql.= ($this->absence_ded!= null)? " and M.IS_ABS= '{$this->absence_ded}' " : '';
        $where_sql.= ($this->case_item!= null)? " and IS_UPDATE= '{$this->case_item}' " : '';
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('DATA.CONSTANT  M'.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('add_and_ded_items_page',$data);

    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if(intval($this->ser) <= 0){
                $this->print_error($this->ser);
            }else{
                echo intval($this->ser);
            }
        }
        $data['content']='add_and_ded_items_show';
        $data['title']='طلب بند إستحقاق أو إستقطاع';
        $data['action'] = 'index';
        $data['isCreate']= true;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['content']='add_and_ded_items_show';
        $data['title']='بيانات بنود الإستحقاق والإستقطاع ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }

    function adopt_1(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(1);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('salary/constants_sal_model');
        $this->load->model('root/Rmodel');

        $data['con_group'] = $this->Rmodel->getAll('TRANSACTION_PKG', 'CON_GROUP_LIST');
        $data['special'] = $this->constants_sal_model->get_list(31);
        $data['item_type'] = $this->constants_sal_model->get_list(32);
        $data['constant'] = $this->constants_sal_model->get_list(33);
        $data['vacancy_ded'] = $this->constants_sal_model->get_list(34);
        $data['absence_ded'] = $this->constants_sal_model->get_list(35);
        $data['case_item'] = $this->constants_sal_model->get_list(36);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'NO','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'NAME','value'=> $this->item_name,'type'=>'','length'=>-1),
            array('name'=>'IS_SPECIAL','value'=>$this->special,'type'=>'','length'=>-1),
            array('name'=>'IS_ADD','value'=> $this->item_type ,'type'=>'','length'=>-1),
            array('name'=>'IS_CONSTANT','value'=>$this->constant ,'type'=>'','length'=>-1),
            array('name'=>'VAL','value'=>$this->item_val ,'type'=>'','length'=>-1),
            array('name'=>'CON_G','value'=>$this->con_group ,'type'=>'','length'=>-1),
            array('name'=>'VACANCY_DED','value'=>$this->vacancy_ded ,'type'=>'','length'=>-1),
            array('name'=>'IS_ABS','value'=>$this->absence_ded ,'type'=>'','length'=>-1),
        );

        return $result;
    }

}