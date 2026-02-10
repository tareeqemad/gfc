<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 08/04/18
 * Time: 08:54 ص
 */
class Categories extends MY_Controller
{

    function  __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');

        $this->rmodel->package = 'TASK_PKG';
    }

    function index()
    {
        $this->load->helper('generate_list');

        $data['content'] = 'categories_show';
        $data['title'] = 'تصنيفات المهام';


        //categories tree
        $resource = $this->_get_structure(0);
        $options = array('template_head' => '<ul>', 'template_foot' => '</ul>', 'use_top_wrapper' => false);
        $template = '<li ><span data-id="{CAT_NO}"   ondblclick="javascript:category_get(\'{CAT_NO}\');"><i class="glyphicon glyphicon-minus-sign"></i>  {CAT_NAME}</span>{SUBS}</li>';
        $data['tree'] = '<ul class="tree" id="categories"><li ><span data-id="0"><i class="glyphicon glyphicon-minus-sign"></i> التصنيفات</span><ul>' . generate_list($resource, $options, $template) . '</ul></li></ul>';
        //******* end categories tree


        $this->_lookUps_data($data);

        $this->load->view('template/template', $data);
    }

    /**
     * constants data
     */
    function _lookUps_data(&$data)
    {
        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);

        add_js('jquery.tree.js');
    }

    /**
     * actions :
     * create ,
     * update ,
     * delete ,
     */
    function actions()
    {

        //check if http request is post , that mean insert action
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = null;

            switch ($this->p_action) {

                case 'create':
                    $result = $this->rmodel->insert('TASK_INDEX_CLASS_TB_INSERT', $this->_postedData());
                    break;

                case 'update':
                    $result = $this->rmodel->update('TASK_INDEX_CLASS_TB_UPDATE', $this->_postedData(false));
                    break;

                case 'delete':
                    $result = $this->rmodel->delete('TASK_INDEX_CLASS_TB_DELETE', $this->p_id);
                    break;


            }

            if (intval($result) <= 0) {
                $this->print_error($result);
            }

            echo $result;


        }


    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'CAT_NO', 'value' => $this->p_cat_no, 'type' => '', 'length' => -1),
            array('name' => 'PARENT_CAT', 'value' => $this->p_parent_cat, 'type' => '', 'length' => -1),
            array('name' => 'CAT_NAME', 'value' => $this->p_cat_name, 'type' => '', 'length' => -1),
            array('name' => 'CAT_POINTS', 'value' => $this->p_cat_points, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $this->p_status, 'type' => '', 'length' => -1),
        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }

    /**
     * get category by id
     * return data as json string
     */
    function public_get()
    {

        $result = $this->rmodel->get('TASK_INDEX_CLASS_TB_GET', $this->p_id);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * get list of categories by parent id
     * @param int $parent
     * @return mixed
     */
    function _get_structure($parent = 0)
    {
        $result = $this->rmodel->get('TASK_INDEX_CLASS_TB_LIST', $parent);
        $i = 0;
        foreach ($result as $key => $item) {
            $result[$i]['subs'] = $this->_get_structure($item['CAT_NO']);
            $i++;
        }
        return $result;
    }
}