<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 10:34 AM
 */
class Accounts extends MY_Controller
{

    var $ACOUNT_PARENT_ID;
    var $ACOUNT_ID;
    var $ACOUNT_NAME;
    var $ACOUNT_TYPE;
    var $CURR_ID;
    var $ACOUNT_FOLLOW;
    var $ACOUNT_CASH_FLOW;

    function  __construct()
    {
        parent::__construct();

        $this->load->model('accounts_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->library('scache');
        $this->load->model('settings/currency_model');
    }

    /**
     *
     * index action perform all functions in view of accounts_show view
     * from this view , can show accounts tree , insert new account , update exists one and delete other ..
     *
     */
    function index()
    {
        $this->load->helper('generate_list');

        $this->load->model('settings/constant_details_model');

        // add_css('jquery-hor-tree.css');
        add_css('combotree.css');
        add_js('jquery.tree.js');


        $data['title'] = ' شجرة الحسابات';
        $data['content'] = 'accounts_index';


        $resource = $this->_get_structure(0);

        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );

        $template = '<li ><span data-id="{ACOUNT_ID}" class="adapt_{ADOPT}" ondblclick="javascript:account_get(\'{ACOUNT_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{ACOUNT_ID} : {ACOUNT_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="accounts">' . generate_list($resource, $options, $template) . '</ul>';
        // $data['tree'] = '<ul class="tree" id="accounts"><li class="parent_li"><span data-id="0" >شركة الكهرباء</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';

        $data['currency'] = $this->currency_model->get_all();
        $data['follow'] = $this->constant_details_model->get_list(1);
        $data['cash_flow'] = $this->constant_details_model->get_list(2);
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['help'] = $this->help;

        $this->load->view('template/template', $data);
    }

    function public_select_accounts($txt, $curr_id = null, $parent = null, $level = 3, $store = null)
    {

        //$this->output->cache(1440); // cache for one day

        $curr_id = $curr_id == -1 ? null : $curr_id;
        $parent = $parent == -1 ? null : $parent;
        $type = $level >= 3 ? 2 : null;

        $user = isset($this->q_user) ? $this->q_user : null;

        $this->load->helper('generate_list');

        add_css('combotree.css');
        add_js('jquery.tree.js');


        $data['title'] = ' شجرة الحسابات';
        $data['content'] = 'account_select_tbl';

        //$resource =  $this->accounts_model->getList_S(urldecode($parent),$user,$level,$curr_id,$type);


        $key = "{$this->FIN_YEAR}_{$parent}_{$user}_{$level}_{$curr_id}_{$type}_{$this->user->id}_$store";


        if (!$cache = $this->scache->read($key)) {

            $cache = json_encode($this->accounts_model->getList_S(urldecode($parent), $user, $level, $curr_id, $type, $store));

            $this->scache->write($key, $cache);
        }

        $data['txt'] = $txt;

        $data['accounts'] = json_decode($cache, true);

        $this->load->view('template/view', $data);
    }

    // mkilani - delete all cache files from cache folder
    function public_delete_cache_files()
    {
        $action = $this->input->post('action');
        if ($action != 'del')
            die();
        $this->load->helper('directory');
        $path = './application/cache/';
        $map = directory_map($path);
        $original_files = array('.htaccess', 'index.html');
        $files_to_del = array_diff($map, $original_files);

        foreach ($files_to_del as $file) {
            @unlink($path . $file);
        }
        echo 1;
    }


    function public_acounts_tb_get_not_cur_master($txt = null, $type)
    {


        $parent = '1|2|3|4|5';


        $user = isset($this->q_user) ? $this->q_user : null;

        $this->load->helper('generate_list');

        add_css('combotree.css');
        add_js('jquery.tree.js');


        $data['title'] = ' شجرة الحسابات';
        $data['content'] = 'account_select_tbl';


        $key = "public_select_parent_{$this->FIN_YEAR}_{$parent}_{$user}_{$this->user->id}";

        if (!$cache = $this->scache->read($key)) {

            $cache = json_encode($this->accounts_model->acounts_tb_get_not_cur_master($type));
            $this->scache->write($key, $cache);
        }

        $data['txt'] = $txt;

        $data['accounts'] = json_decode($cache, true);


        $this->load->view('template/view', $data);
    }

    function public_select_parent($txt = null, $curr_id = null, $parent = null, $level = 9)
    {


        $parent = '1|2|3|4|5';


        $user = isset($this->q_user) ? $this->q_user : null;

        $this->load->helper('generate_list');

        add_css('combotree.css');
        add_js('jquery.tree.js');


        $data['title'] = ' شجرة الحسابات';
        $data['content'] = 'account_select_tbl';

        /*

                $resource =  $this->accounts_model->getList_parent(null,null,0,null,1);

                $data['txt']=$txt;

                $data['accounts']=$resource;*/


        $key = "public_select_parent_{$this->FIN_YEAR}_{$parent}_{$user}_{$level}_{$curr_id}_{$this->user->id}";

        if (!$cache = $this->scache->read($key)) {

            $cache = json_encode($this->accounts_model->getList_parent(null, null, 0, null, null));
            $this->scache->write($key, $cache);
        }

        $data['txt'] = $txt;

        $data['accounts'] = json_decode($cache, true);


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
        $resource = $this->accounts_model->getList_Table(null, null, 1, null);

        $data['accounts'] = $resource;

        $data['txt'] = '';
        /* echo $this->convert(memory_get_usage(true));*/
        $this->load->view('template/template', $data);
    }

    /*  function convert($size)
      {
          $unit=array('b','kb','mb','gb','tb','pb');
          return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
      }*/
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
        $result = $this->accounts_model->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get accounts tree structure ..
     *
     */
    function _get_structure($parent = 0, $user = null, $level = 0)
    {
        $result = $this->accounts_model->getList($parent, $user, $level);
        $i = 0;
        foreach ($result as $key => $item) {
            $result[$i]['subs'] = $this->_get_structure($item['ACOUNT_ID'], $user, $level);
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

        $result = $this->accounts_model->create($this->_postedData());
        $this->Is_success($result);
        $this->return_json($result);

    }

    function get_accounts($click = false)
    {

        $user = ($this->input->post('user')) ? $this->input->post('user') : 0;
        $click = ($this->input->post('click')) ? $this->input->post('click') : false;


        $this->load->helper('generate_list');

        $resource = $this->_get_user_accounts($user, 0, 0, $click);


        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );


        $template = '<li><span data-id="{ACOUNT_ID}"  {ONDBLCLICK}>{IS_CHECKED}{ACOUNT_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="accounts_tree">' . generate_list($resource, $options, $template) . '</ul></li></ul>';


        $data['user'] = $user;
        $this->load->view('accounts_tree', $data);

    }

    function public_get_accounts_all($click = false)
    {

        $branch = ($this->input->post('branch')) ? $this->input->post('branch') : 0;
        $click = ($this->input->post('click')) ? $this->input->post('click') : false;


        $this->load->helper('generate_list');

        $resource = $this->_get_branch_accounts($branch, 0, 0, $click);

        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );


        $template = '<li><span data-id="{ACOUNT_ID}"  {ONDBLCLICK}>{IS_CHECKED}{ACOUNT_ID} : {ACOUNT_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="accounts_tree">' . generate_list($resource, $options, $template) . '</ul></li></ul>';


        $data['branch'] = $branch;
        $this->load->view('accounts_tree', $data);

    }

    /**
     * @return mixed
     * return gcc structure tree json type
     */
    function public_get_accounts_json($add = 0, $start = 0)
    {

        if ($add == 1) {
            $arr = array(array('ACOUNT_PARENT_ID' => 0, 'ACOUNT_ID' => '-1', 'ACOUNT_NAME' => 'لا يوجد حساب', 'subs' => array()));
            $array = $this->_get_structure($start);
            array_splice($array, 0, 0, $arr);
            $result = json_encode($array);
        } else {
            $result = json_encode($this->_get_structure(0));
        }
        $result = str_replace('subs', 'children', $result);
        $result = str_replace('ACOUNT_ID', 'id', $result);
        $result = str_replace('ACOUNT_NAME', 'text', $result);
        echo $result;

    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get user tree accounts ..
     *
     */
    function _get_user_accounts($user, $parent, $level = 0, $click = false)
    {

        $result = $this->accounts_model->getList2($user);
        $i = 0;
        $level++;

        foreach ($result as $key => $item) {

            $result[$i]['IS_CHECKED'] = "<input  type=\"checkbox\" name=\"account_no\" {$item['IS_CHECKED']} value=\"{$item['ACOUNT_ID']}\" />";
            if ($click && $level >= 5 && $item['ACOUNT_TYPE'] == '2')
                $result[$i]['ONDBLCLICK'] = " ondblclick=\"javascript:account_get('{$item['ACOUNT_ID']}',this);\" ";

            //  $result[$i]['subs'] = $this->_get_user_accounts($user,$item['ACOUNT_ID'],$level,$click);
            $i++;
        }


        return $result;
    }

    function _get_branch_accounts($branch, $parent, $level = 0, $click = false)
    {

        $result = $this->accounts_model->getList2B($branch);
        $i = 0;
        $level++;

        foreach ($result as $key => $item) {

            $result[$i]['IS_CHECKED'] = "<input  type=\"checkbox\" name=\"account_no\" {$item['IS_CHECKED']} value=\"{$item['ACOUNT_ID']}\" />";
            if ($click && $level >= 5 && $item['ACOUNT_TYPE'] == '2')
                $result[$i]['ONDBLCLICK'] = " ondblclick=\"javascript:account_get('{$item['ACOUNT_ID']}',this);\" ";

            //$result[$i]['subs'] = $this->_get_branch_accounts($branch,$item['ACOUNT_ID'],$level,$click);
            $i++;
        }


        return $result;
    }

    /**
     * update adapt
     *
     */
    function update_adapt()
    {

        $ADAPT = $this->input->post('adapt');
        $this->ACOUNT_ID = $this->input->post('acount_id');

        if (intval($this->ACOUNT_ID) != -1)
            $ADAPT = intval($ADAPT) == 1 ? 0 : 1;

        $result = array(
            array('name' => 'ACOUNT_ID', 'value' => $this->ACOUNT_ID, 'type' => '', 'length' => -1),
            array('name' => 'ADOPT', 'value' => $ADAPT, 'type' => '', 'length' => -1)
        );

        $result = $this->accounts_model->update_adapt($result);
        $this->Is_success($result);
        $this->return_json($result);

    }


    /**
     * edit action : update exists account data ..
     * receive post data of account
     * depended on account prm key
     */
    function edit()
    {

        echo $this->accounts_model->edit($this->_postedData());

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
                echo $this->accounts_model->delete($val);
            }
        } else {
            echo $this->accounts_model->delete($id);
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


        $this->ACOUNT_ID = $this->input->post('acount_id');
        $this->ACOUNT_NAME = $this->input->post('acount_name');
        $this->ACOUNT_TYPE = $this->input->post('acount_type');
        $this->CURR_ID = $this->input->post('curr_id');
        $this->ACOUNT_FOLLOW = $this->input->post('acount_follow');
        $this->ACOUNT_CASH_FLOW = $this->input->post('acount_cash_flow');
        $BUDGET_ACCOUNT = $this->input->post('budget_account');
        $assel_no = $this->input->post('assel_no');


        $result = array(
            array('name' => 'ACOUNT_PARENT_ID', 'value' => $this->p_acount_parent_id, 'type' => '', 'length' => 15),
            array('name' => 'ACOUNT_ID', 'value' => $this->ACOUNT_ID, 'type' => '', 'length' => 15),
            array('name' => 'ACOUNT_NAME', 'value' => $this->ACOUNT_NAME, 'type' => '', 'length' => 100),
            array('name' => 'ACOUNT_TYPE', 'value' => $this->ACOUNT_TYPE, 'type' => '', 'length' => 1),
            array('name' => 'CURR_ID', 'value' => $this->CURR_ID, 'type' => '', 'length' => 2),
            array('name' => 'ACOUNT_FOLLOW', 'value' => $this->ACOUNT_FOLLOW, 'type' => '', 'length' => 2),
            array('name' => 'ACOUNT_CASH_FLOW', 'value' => $this->ACOUNT_CASH_FLOW, 'type' => '', 'length' => 2),
            array('name' => 'BUDGET_ACCOUNT', 'value' => $BUDGET_ACCOUNT, 'type' => '', 'length' => -1),
            array('name' => 'ASSEL_NO', 'value' => $assel_no, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->p_branch, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_CENTER_ID', 'value' => $this->p_financial_center_id, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $this->p_status, 'type' => '', 'length' => -1),
        );


        return $result;
    }


    /***************************** accounts featured views ***************************/

    function stm_accounts($page = 1, $account)
    {
        $data['title'] = 'حركة الحساب';
        $data['content'] = 'stm_accounts_index';
        $data['page'] = $page;
        $data['help'] = $this->help;
        $data['account'] = $account;

        add_css('datepicker3.css');
        add_css('combotree.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template', $data);
    }

    function get_page_stm_accounts($page = 1, $account = null)
    {

        $account = isset($this->p_account) ? $this->p_account : $account;


        $sql = " AND ( D.ACCOUNT_ID LIKE '{$account}%' OR D.ROOT_ACCOUNT_ID LIKE '{$account}%' )   ";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  FINANCIAL_CHAINS_DATE >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  FINANCIAL_CHAINS_DATE <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_price) && $this->p_price != null ? " AND ((D.DEBIT {$this->p_price_op} {$this->p_price} AND D.CREDIT = 0) OR (D.CREDIT {$this->p_price_op} {$this->p_price} AND D.DEBIT = 0)) " : "";


        $this->load->library('pagination');
        $count_rs = $this->get_table_count(" FINANCIAL_CHAINS_TB  M ,  FINANCIAL_CHAINS_DETAIL_TB  D
                                             WHERE M.FINANCIAL_CHAINS_ID = D.FINANCIAL_CHAINS_ID {$sql} ");

        $data['page_balance'] = isset($this->p_balance) ? $this->p_balance : 0;
        $data['page_c_balance'] = isset($this->p_c_balance) ? $this->p_c_balance : 0;


        $config['base_url'] = base_url("financial/accounts/get_page_stm_accounts/");
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = 1000; //$this->page_size;
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

        $result = $this->accounts_model->stm_accounts_get_list(" {$sql} ", $offset, $row);

        $this->date_format($result, 'FINANCIAL_CHAINS_DATE');

        $data["rows"] = $result;

        $data['offset'] = $offset + 1;

        $this->load->view('stm_accounts_page', $data);

    }

}