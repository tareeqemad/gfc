<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 12/08/2018
 * Time: 10:10 AM
 */
class BankFinCenter extends MY_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'financial_pkg';
    }

    /**
     *
     * index action perform all functions in view of bank_fin_center_show view
     * from this view , can show bank_fin_center tree , insert new account , update exists one and delete other ..
     *
     */
    function index()
    {
        $this->load->helper('generate_list');
        add_css('combotree.css');
        add_js('jquery.tree.js');


        $resource = $this->_get_structure(0);

        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );

        $template = '<li ><span data-id="{ID}" ondblclick="javascript:account_get(\'{ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{TITLE}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="bank_fin_center"><li><span data-id="0" ><i class="glyphicon glyphicon-minus-sign"></i>الشجرة </span><ul>' . generate_list($resource, $options, $template) . '</ul></li></ul>';

        $data['help'] = $this->help;
        $data['title'] = 'حسابات البنوك';
        $data['content'] = 'bank_fin_center_index';
        $this->_generate_std_urls($data, true);

        $this->load->view('template/template', $data);
    }

    function public_select_bank_fin_center($txt)
    {

        $this->load->helper('generate_list');

        add_css('combotree.css');
        add_js('jquery.tree.js');


        $data['txt'] = $txt;
        $data['title'] = ' شجرة الحسابات';
        $data['content'] = 'account_select_tbl';
        //$data['bank_fin_center'] = $this->rmodel->getList_S();

        $this->load->view('template/view', $data);
    }


    function table()
    {

        add_css('combotree.css');
        add_js('jquery.tree.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['title'] = '  فهرس الحسابات';
        $data['content'] = 'account_select_table';
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['currency'] = $this->currency_model->get_list();
        $resource = $this->rmodel->getList_Table(null, null, 1, null);

        $data['bank_fin_center'] = $resource;

        $data['txt'] = '';
        /* echo $this->convert(memory_get_usage(true));*/
        $this->load->view('template/template', $data);
    }


    /**
     * get account by id ..
     */
    function public_get_id()
    {
        $this->get_id();
    }

    function get_id()
    {

        $id = $this->input->post('id');
        $result = $this->rmodel->get('BANKS_FIN_CENTER_ST_TB_GET',$id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get bank_fin_center tree structure ..
     *
     */
    function _get_structure($parent = 0)
    {
        $result = $this->rmodel->getList('BANKS_FIN_CENTER_ST_TB_LIST', " AND PARENT_ID = {$parent} ", 0, 1000);

        $i = 0;
        foreach ($result as $key => $item) {
            $result[$i]['subs'] = $this->_get_structure($item['ID']);
            $i++;
        }
        return $result;
    }

    /**
     * create action : insert new account data ..
     * receive post data of account
     *
     */
    function create()
    {

        $result = $this->rmodel->insert('BANKS_FIN_CENTER_ST_TB_INSERT', $this->_postedData());
        $this->Is_success($result);
        $this->return_json($result);

    }

    function get_bank_fin_center($click = false)
    {

        $user = ($this->input->post('user')) ? $this->input->post('user') : 0;
        $click = ($this->input->post('click')) ? $this->input->post('click') : false;


        $this->load->helper('generate_list');

        $resource = $this->_get_user_bank_fin_center($user, 0, 0, $click);


        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );


        $template = '<li><span data-id="{ID}"  {ONDBLCLICK}>{IS_CHECKED}{ACCOUNT_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="bank_fin_center_tree">' . generate_list($resource, $options, $template) . '</ul></li></ul>';


        $data['user'] = $user;
        $this->load->view('bank_fin_center_tree', $data);

    }

    function public_get_bank_fin_center_all($click = false)
    {

        $branch = ($this->input->post('branch')) ? $this->input->post('branch') : 0;
        $click = ($this->input->post('click')) ? $this->input->post('click') : false;


        $this->load->helper('generate_list');

        $resource = $this->_get_branch_bank_fin_center($branch, 0, 0, $click);

        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );


        $template = '<li><span data-id="{ID}"  {ONDBLCLICK}>{IS_CHECKED}{ID} : {TITLE}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="bank_fin_center_tree">' . generate_list($resource, $options, $template) . '</ul></li></ul>';


        $data['branch'] = $branch;
        $this->load->view('bank_fin_center_tree', $data);

    }

    /**
     * @return mixed
     * return gcc structure tree json type
     */
    function public_get_bank_fin_center_json($add = 0, $start = 0)
    {

        if ($add == 1) {
            $arr = array(array('PARENT_ID' => 0, 'ID' => '-1', 'TITLE' => 'لا يوجد حساب', 'subs' => array()));
            $array = $this->_get_structure($start);
            array_splice($array, 0, 0, $arr);
            $result = json_encode($array);
        } else {
            $result = json_encode($this->_get_structure(0));
        }
        $result = str_replace('subs', 'children', $result);
        $result = str_replace('ID', 'id', $result);
        $result = str_replace('TITLE', 'text', $result);
        echo $result;

    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get user tree bank_fin_center ..
     *
     */
    function _get_user_bank_fin_center($user, $parent, $level = 0, $click = false)
    {

        $result = $this->rmodel->getList2($user);
        $i = 0;
        $level++;

        foreach ($result as $key => $item) {

            $result[$i]['IS_CHECKED'] = "<input  type=\"checkbox\" name=\"account_no\" {$item['IS_CHECKED']} value=\"{$item['ID']}\" />";
            if ($click && $level >= 5 && $item['ACCOUNT_TYPE'] == '2')
                $result[$i]['ONDBLCLICK'] = " ondblclick=\"javascript:account_get('{$item['ID']}',this);\" ";

            //  $result[$i]['subs'] = $this->_get_user_bank_fin_center($user,$item['ID'],$level,$click);
            $i++;
        }


        return $result;
    }

    function _get_branch_bank_fin_center($branch, $parent, $level = 0, $click = false)
    {

        $result = $this->rmodel->getList2B($branch);
        $i = 0;
        $level++;

        foreach ($result as $key => $item) {

            $result[$i]['IS_CHECKED'] = "<input  type=\"checkbox\" name=\"account_no\" {$item['IS_CHECKED']} value=\"{$item['ID']}\" />";
            if ($click && $level >= 5 && $item['ACCOUNT_TYPE'] == '2')
                $result[$i]['ONDBLCLICK'] = " ondblclick=\"javascript:account_get('{$item['ID']}',this);\" ";

            //$result[$i]['subs'] = $this->_get_branch_bank_fin_center($branch,$item['ID'],$level,$click);
            $i++;
        }


        return $result;
    }


    /**
     * edit action : update exists account data ..
     * receive post data of account
     * depended on account prm key
     */
    function edit()
    {

        echo $this->rmodel->update('BANKS_FIN_CENTER_ST_TB_UPDATE',$this->_postedData());

    }

    /**
     * delete action : delete account data ..
     * receive prm key as request
     *
     */
    function delete()
    {

        $id = $this->input->post('id');

        $this->IsAuthorized();

        if (is_array($id)) {
            foreach ($id as $val) {
                echo $this->rmodel->delete('BANKS_FIN_CENTER_ST_TB_DELETE',$val);
            }
        } else {
            echo $this->rmodel->delete('BANKS_FIN_CENTER_ST_TB_DELETE',$id);
        }

    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData()
    {


        $result = array(
            array('name' => 'ID', 'value' => $this->p_id, 'type' => '', 'length' => -1),
            array('name' => 'PARENT_ID', 'value' => $this->p_parent_id, 'type' => '', 'length' => -1),
            array('name' => 'TITLE', 'value' => $this->p_title, 'type' => '', 'length' => -1),

        );

        return $result;
    }


    /***************************** bank_fin_center featured views ***************************/

    function stm_bank_fin_center($page = 1, $account)
    {
        $data['title'] = 'حركة الحساب';
        $data['content'] = 'stm_bank_fin_center_index';
        $data['page'] = $page;
        $data['help'] = $this->help;
        $data['account'] = $account;

        add_css('datepicker3.css');
        add_css('combotree.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template', $data);
    }

    function get_page_stm_bank_fin_center($page = 1, $account = null)
    {

        $account = isset($this->p_account) ? $this->p_account : $account;


        $sql = " AND (D.ID LIKE '{$account}%' OR D.ROOT_ID LIKE '{$account}%' ) ";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  FINANCIAL_CHAINS_DATE >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  FINANCIAL_CHAINS_DATE <= '{$this->p_to_date}' " : "";

        $this->load->library('pagination');
        $count_rs = $this->get_table_count(" FINANCIAL_CHAINS_TB  M ,  FINANCIAL_CHAINS_DETAIL_TB  D
                                             WHERE M.FINANCIAL_CHAINS_ID = D.FINANCIAL_CHAINS_ID {$sql} ");


        $config['base_url'] = base_url("financial/bank_fin_center/get_page_stm_bank_fin_center/");
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

        $result = $this->rmodel->stm_bank_fin_center_get_list(" {$sql} ", $offset, $row);

        $this->date_format($result, 'FINANCIAL_CHAINS_DATE');

        $data["rows"] = $result;

        $data['offset'] = $offset + 1;

        $this->load->view('stm_bank_fin_center_page', $data);

    }


    function report()
    {

        $data['title'] = 'تقارير الموقف المالي';
        $data['content'] = 'bank_fin_center_report';

        $this->_loadDatePicker();

        $this->load->view('template/template', $data);
    }
}