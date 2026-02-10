<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 29/12/22
 * Time: 12:00 ص
 */

class Payment_status extends MY_Controller{

    var $MODEL_NAME= 'Payment_status_model';
    var $PAGE_URL= 'reports_ebllc/Payment_status/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Payment_status_model');
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'REPORT_PKG';

        $this->branch_id = $this->input->post('branch_id');
        $this->payment_type = $this->input->post('payment_type');
        $this->the_month = $this->input->post('the_month');
        $this->from_date = $this->input->post('from_date');
        $this->to_date = $this->input->post('to_date');

    }

    function index()
    {
        $data['content']='payment_status_index';
        $data['title']='حالات التسديد';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('BANK_ORG_PAR T ');
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($this->branch_id,$this->payment_type, $this->the_month, $this->from_date, $this->to_date, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('payment_status_page',$data);
    }

    public function public_get_detail(){
        $bank_type = $this->input->post('bank_type');
        $branch_id  = $this->input->post('branch_id');
        $the_month = $this->input->post('the_month');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $payment_type = $this->input->post('payment_type');
        $count = 1;
        if (intval($bank_type) > 0) {

            $result= $this->{$this->MODEL_NAME}->get($bank_type ,$branch_id ,$the_month ,$from_date ,$to_date ,$payment_type);
            $html = '<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="banks_tb">
                                <thead class="table-active">
                                <tr>
                                      <th>#</th>
                                      <th>رقم البنك</th>
                                      <th>اسم البنك</th>
                                      <th>عدد الاشتراكات</th>
                                      <th>التحصيل النقدي</th>
                                </tr>
                                </thead>
                                <tbody>';
            foreach ($result as $rows) {
                $html .= '<tr>
                                <td>' . $count++ . '</td>
                                <td>' . $rows['BANK_NO'] . '</td>
                                <td>' . $rows['BANK_NAME'] . '</td>
                                <td>' . $rows['SUBSCRIBERS'] . '</td>
                                <td>' . $rows['VALUE'] . '</td>
                            </tr>';
            }
            $html .= '</tbody></table></div></div></div>';
            echo $html;
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);
        $data['payment_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',498,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
