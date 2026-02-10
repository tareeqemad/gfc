<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 11/12/19
 * Time: 08:15 ص
 */



class Subscriber_info extends MY_Controller
{

    var $PKG_NAME = "";

    function __construct()
    {
        parent::__construct();


        $this->load->model('root/rmodel');
        $this->rmodel->package = 'OUT_COLLECT_PKG';
        //this for constant using
        $this->load->model('stores/store_committees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model("stores/receipt_class_input_group_model");
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
        //$this->load->library('excel');
    }

    /************************************index*********************************************/

    function index($page = 1)
    {

        $data['title'] = 'بيانات فواتير وسداد المشتركين';
        $data['content'] = 'subscriber_info_index';

        $data['offset']=1;
        $data['page']=$page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['hide'] ='hidden';

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    /*************************************_lookup****************************************/

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        $data['phase_type'] = $this->constant_details_model->get_list(140);
        $data['org_name'] = $this->constant_details_model->get_list(141);
        $data['payment_type'] = $this->constant_details_model->get_list(306);
        $data['sub_use'] = $this->constant_details_model->get_list(307);
        $data['branches'] = $this->gcc_branches_model->get_all();
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    function import($page = 1)
    {

        $data['title'] = 'استيراد الاشتراكات من ملف اكسل';
        $data['content'] = 'subscriber_info_excel';

        $data['offset']=1;
        $data['page']=$page;

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("collection_offices/subscriber_info/get_page/");
        $da = date('Ym', strtotime('-1 month'));
        //$payment_use = $this->p_payment_type  == 2 ? 0 : $this->p_payment_type ;

        $sql = " AND FOR_MONTH >= $da AND OUT_COLLECT_PKG.PAYMENTS_LAST_PAID(M.SUBSCRIBER) >= 200812
        and rownum < 20" ;

        $sql .= isset($this->p_branch_no) && $this->p_branch_no ? " AND BRANCH= {$this->p_branch_no} " : '';
        $sql .= isset($this->p_sub_no) && $this->p_sub_no ? " AND SUBSCRIBER= {$this->p_sub_no} " : '';
        $sql .= isset($this->p_sub_name) && $this->p_sub_name ? " and NAME like '".add_percent_sign($this->p_sub_name)."' " : "";
        //$sql .= isset($this->p_for_month) && $this->p_for_month ? " AND FOR_MONTH= {$this->p_for_month} " : '';
        $sql .= isset($this->p_payment_status) && $this->p_payment_status ? " AND OUT_COLLECT_PKG.PAYMENTS_LAST_PAID(M.SUBSCRIBER)= {$this->p_payment_status} " : '';
       // $sql .= isset($this->p_phase_type) && $this->p_phase_type ? " AND PHASE_TYPE= {$this->p_phase_type} " : '';
       // $sql .= isset($this->p_org) && $this->p_org ? " AND ORG= {$this->p_org} " : '';
       // $sql .= isset($this->p_payment_type) && $this->p_payment_type ? " AND OUT_COLLECT_PKG.BANK_SUB_IS_ALY(M.SUBSCRIBER) = $payment_use" : '';
       // $sql .= isset($this->p_sub_use) && $this->p_sub_use ? " AND OUT_COLLECT_PKG.BANK_SUB_IS_ALY(M.SUBSCRIBER)= $payment_use " : '';
        $sql .= isset($this->p_net_to_pay) && $this->p_net_to_pay ? " AND NET_TO_PAY <= {$this->p_net_to_pay} " : '';

        //var_dump($sql); die;

        $count_rs = $this->get_table_count(" BILLS@EBLLC.PS M WHERE 1=1  {$sql}");


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
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data["rows"] = $this->rmodel->getList('BILLS_LINK_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('subscriber_info_page', $data);
    }


    function adopt()
    {

        $id = $this->input->post('subs_no');
        $count = $this->input->post('count_sub');

        $params = array(
            array('name' => 'SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'COUNT_IN', 'value' => $count, 'type' => '', 'length' => -1),
            array('name' => 'DISCLOSURE_OPEN_DATE', 'value' => $this->p_disclosure_open_date, 'type' => '', 'length' => -1),
            array('name' => 'DISCLOSURE_CLOSE_DATE', 'value' => $this->p_disclosure_close_date, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
        );

        $msg= $this->rmodel->update('OUT_DISCLOSURE_TB_INSERT',$params);

        if ($msg < 1) {
            $this->print_error('لم يتم الاعتماد' . '<br>');
        } else {
            echo intval($msg);
        }

    }


    function showImport()
    {
        /*if(isset($_FILES["file"]["name"]))
        {
            $output = '
            <table class="table" id="page_tb" data-container="container">
                <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>رقم الاشتراك</th>
                    <th>رقم الموقع</th>
                    <th>اسم المشترك</th>
                    <th>التصنيف</th>
                    <th>الفاتورة الشهرية</th>
                    <th>المتأخرات</th>
                    <th>القيمة المطلوبة</th>
                    <th>القسط</th>
                    <th>اجمالي الدفعات</th>
                    <th>شهر اخر دفعة نقدية</th>
                    <th>تاريخ اخر دفعة نقدية</th>
                </tr>
                </thead>
                <tbody>
                    ';

            $path = $_FILES["file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for($row=2; $row<=$highestRow; $row++)
                {
                    $a = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $b = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $c = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $d = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $e = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $f = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $g = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $h = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $i = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $j = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $k = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $l = $worksheet->getCellByColumnAndRow(11, $row)->getValue();


                    $output .= '<tr>
                    <td><input type="checkbox" class="checkboxes" value="'.$b.'/'.$k.'"></td>
                    <td>'.$a.'</td>
                    <td>'.$b.'</td>
                    <td>'.$c.'</td>
                    <td>'.$d.'</td>
                    <td>'.$e.'</td>
                    <td>'.$f.'</td>
                    <td>'.$g.'</td>
                    <td>'.$h.'</td>
                    <td>'.$i.'</td>
                    <td>'.$j.'</td>
                    <td>'.$k.'</td>
                    <td>'.$l.'</td>
                    </tr>
                    ';
                }
            }

            $output .= '</tbody></table>';
            echo $output;


        }*/
    }










}
