<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 29/01/20
 * Time: 09:41 ص
 */




class Advertisement extends MY_Controller
{

    var $PKG_NAME = "TRAIN_PKG";

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRAIN_PKG';
        //this for constant using
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
    }

    /************************************index*********************************************/

    function index($page = 1)
    {

        $data['title'] = 'ادارة الاعلانات';
        $data['content'] = 'advertisement_index';

        $data['offset']=1;
        $data['page']=$page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

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
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['adver_type'] = $this->constant_details_model->get_list(171);
        $data['qif'] = $this->constant_details_model->get_list(161);
        $data['spes'] = $this->constant_details_model->get_list(162);

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Advertisement/get_page/");

        $sql = 'WHERE 1 = 1';
        $sql .= isset($this->p_adver_type) && $this->p_adver_type ? " AND ADVER_TYPE = {$this->p_adver_type} " : '';
        $sql .= isset($this->p_adver_id) && $this->p_adver_id ? " AND ADVER_ID like '".add_percent_sign($this->p_adver_id)."'" : '';
        $sql .= isset($this->p_adver_title) && $this->p_adver_title ? " AND ADVER_TITLE like '".add_percent_sign($this->p_adver_title)."'" : '';
        $sql .= isset($this->p_end_date) && $this->p_end_date ? " AND END_DATE= '{$this->p_end_date}' " : '';
        $sql .= isset($this->p_start_date) && $this->p_start_date ? " AND START_DATE= '{$this->p_start_date}' " : '';


        $count_rs = 4000;

        $config['use_page_numbers'] = TRUE;
        // $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $data["rows"] = $this->rmodel->getList('ADVER_TRAINING_LIST', " $sql ", $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;


        $this->load->view('advertisement_page', $data);

    }


    function get($id)
    {
        $result = $this->rmodel->get('ADVER_TRAINING_TB_GET', $id);
        $data['title'] = 'تعديل بيانات الاعلان';
        $data['content'] = 'advertisement_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }


    function public_get_adv_condition($id = 0,$adopt=1)
    {
        $data['details'] = $this->rmodel->get('ADVER_TRAINING_COND_TB_GET', $id);
        $data['adopt']=$adopt;
        $this->_lookup($data);
        $this->load->view('advertisement_details', $data);
    }


    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $this->ser = $this->rmodel->insert('ADVER_TRAINING_TB_INSERT', $this->_postedData());
            $this->ser2 = $this->rmodel->insert('ADVER_TRAINING_COND_GET_SEQ', array());

            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                    if ($this->p_qif[$i] != 0  &&  $this->p_spes[$i] != 0   ) {
                        $serDet=$this->rmodel->insert('ADVER_TRAINING_COND_TB_INSERT',$this->_posteddata_details
                            (null, $this->ser , $this->p_qif[$i], $this->p_spes[$i],'create') );

                    }
                }
            }



            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser2);
            }

        } else {

            $data['title'] = 'اعلان جديد';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['content'] = 'advertisement_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser1, 'type' => '', 'length' => -1),
            array('name' => 'ADVER_TYPE', 'value' => $this->p_adver_type , 'type' => '', 'length' => -1),
            array('name' => 'ADVER_TITLE', 'value' => $this->p_adver_title, 'type' => '', 'length' => -1),
            array('name' => 'TO_AGE_YEAR', 'value' => $this->p_to_age_year , 'type' => '', 'length' => -1),
            array('name' => 'START_DATE', 'value' => $this->p_start_date , 'type' => '', 'length' => -1),
            array('name' => 'END_DATE', 'value' => $this->p_end_date, 'type' => '', 'length' => -1),
            array('name' => 'EXP_NUM', 'value' => $this->p_exp_num, 'type' => '', 'length' => -1),
            array('name' => 'FROM_AGE_YEAR', 'value' => $this->p_from_age_year, 'type' => '', 'length' => -1),
        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }


    function _posteddata_details($ser = null, $adver_id = null, $qif = null,
                                 $spes = null,$type)
    {

        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ADVER_ID', 'value' => $adver_id, 'type' => '', 'length' => -1),
            array('name' => 'QIF', 'value' => $qif, 'type' => '', 'length' => -1),
            array('name' => 'SPES', 'value' => $spes, 'type' => '', 'length' => -1),


        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }




    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {




            $this->ser= $this->rmodel->update('ADVER_TRAINING_TB_UPDATE', $this->_postedData(false));
            $result = array(
                array('name' => 'SER', 'value' =>  $this->ser, 'type' => '', 'length' => -1),
            );
            $this->ser2 = $this->rmodel->insert('ADVER_TRAINING_COND_GET_SEQ2', $result);



            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }

            for ($i = 0; $i < count($this->p_seq1); $i++)
            {

                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {

                    if ($this->p_qif[$i] != 0  &&  $this->p_spes[$i] != 0   ) {
                        $serDet=$this->rmodel->insert('ADVER_TRAINING_COND_TB_INSERT',$this->_posteddata_details
                            (null, $this->ser2 , $this->p_qif[$i], $this->p_spes[$i],'create') );
                    }
                    if (intval($serDet) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                    }
                } else {
                    $x=$this->rmodel->insert('ADVER_TRAINING_COND_UPDATE',$this->_posteddata_details
                        ($this->p_seq1[$i],$this->ser2 , $this->p_qif[$i], $this->p_spes[$i],'edit') );


                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                }
            }

            echo intval($this->ser);
        }
    }






}
