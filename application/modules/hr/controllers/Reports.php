<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 17/09/16
 * Time: 08:55 ص
 */

class reports extends MY_Controller{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('hr/evaluation_order_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/gcc_structure_model');// get departments in main branch
        $this->load->model('hr/evaluation_jobs_model');
        $this->load->model('employees/employees_model');
        $this->load->model('hr/evaluation_form_model');
        $this->load->model('hr/reports_model');

        $this->parent= $this->input->post('parent');
    }

    function index(){
        $data['title']='تقارير تقييم الأداء';
        $data['content']='reports_index';

        $data['order_id'] = $this->evaluation_order_model->get_all();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data["departments"]= $this->gcc_structure_model->getStructure(1);
        $data['jobs'] = $this->evaluation_jobs_model->get_all();
        $data['all_employees'] = $this->employees_model->get_all();
        $data['all_forms'] = $this->evaluation_form_model->get_child(0,1);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->view('template/template',$data);
    }

    function public_get_questions() {
        $res = $this->evaluation_form_model->get_child($this->parent,2);
        $op="<option value=''></option>";
        foreach ($res as $row){
            $op.="<optgroup label='".$row['EV_PK']." : ".$row['EV_NAME']."'>";
            $res2 = $this->evaluation_form_model->get_child($row['EV_PK'],3);
                foreach ($res2 as $row2){
                    $op.="<option data-dept='".$row2['EV_PK']."'";
                    $op.="value='".$row2['EV_PK']."'>".$row2['EV_PK']." : ".$row2['EV_NAME']."</option>";
                }
            $op.="</optgroup>";
        }

        echo $op;
    }

    function public_fill() {
        $order_id= $this->input->post('order_id');
        $res = $this->reports_model->fill($order_id);
        echo $res;
    }


}