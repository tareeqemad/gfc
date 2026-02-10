<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 21/05/17
 * Time: 08:51 ص
 */
class ItemCollections extends MY_Controller
{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('itemCollections_model');
    }

    function index()
    {
        $data['title'] = 'تجميع الاصناف لاغراض التقرير';
        $data['content'] = 'ItemCollections_index';

        $data['rows'] = $this->itemCollections_model->get_all();

        $this->load->view('template/template', $data);
    }

    function get($id = 0)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->itemCollections_model->edit($this->_postedData());
        }

        $data['title'] = 'تجميع الاصناف لاغراض التقرير';
        $data['content'] = 'ItemCollections_list';

        $data['rows'] = $this->itemCollections_model->get($id);

        $this->load->view('template/template', $data);

    }

    function _postedData()
    {

        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => implode(",", (array) $this->p_class_id), 'type' => '', 'length' => -1)


        );
        return $result;
    }


}