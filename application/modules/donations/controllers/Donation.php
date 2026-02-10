<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 11/10/15
 * Time: 02:12 م
 */
class Donation extends MY_Controller
{

    var $MODEL_NAME = 'donation_file_model';
    var $DETAILS_MODEL_NAME = 'donation_detail_model';
    var $PAGE_URL = 'donations/donation/get_page';

    function  __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('stores/class_model');

        $this->donation_file_id = $this->input->post('donation_file_id');
        $this->donation_id = $this->input->post('donation_id');
        $this->donation_name = $this->input->post('donation_name');
        $this->donation_approved_date = $this->input->post('donation_approved_date');
        $this->donation_end_date = $this->input->post('donation_end_date');
        $this->donation_label = $this->input->post('donation_label');
        $this->donation_account = $this->input->post('donation_account');
        $this->donor_name = $this->input->post('donor_name');
        $this->donation_type = $this->input->post('donation_type');
        $this->donation_kind = $this->input->post('donation_kind');
        $this->curr_id = $this->input->post('curr_id');
        $this->curr_value = $this->input->post('curr_value');
        $this->donation_value = $this->input->post('donation_value');
        $this->store_id = $this->input->post('store_id');
        $this->notes = $this->input->post('notes');
        $this->other_expenses = $this->input->post('other_expenses');
        $this->other_expenses_note = $this->input->post('other_expenses_note');
        $this->donation_file_case = $this->input->post('donation_file_case');
        $this->conditions = $this->input->post('conditions');
//---------------------
        $this->class_id = $this->input->post('class_id');
        $this->replace_class_id = $this->input->post('replace_class_id');
        $this->class_case = $this->input->post('class_case');
        $this->old_class_case = $this->input->post('old_class_case');
        $this->rem_amount = $this->input->post('rem_amount');
        $this->orderderd = $this->input->post('orderderd');
        $this->replace_class_unit = $this->input->post('replace_class_unit');
        $this->replace_class_type = $this->input->post('replace_class_type');
        $this->class_type = $this->input->post('class_type');
        $this->ser = $this->input->post('ser');
        $this->class_case_hints = $this->input->post('class_case_hints');
        $this->replace_class_case_hints = $this->input->post('replace_class_case_hints');
        $this->donation_dept_value = $this->input->post('donation_dept_value');
    }

    function index($page = 1, $donation_file_id = -1, $donation_id = -1, $donation_name = -1, $donation_approved_date = -1, $donation_end_date = -1, $donation_label = -1, $donation_account = -1, $donor_name = -1, $donation_type = -1, $donation_kind = -1, $curr_id = -1, $store_id = -1, $donation_file_case = -1)
    {


        $data['page'] = $page;
        $data['donation_file_id'] = $donation_file_id;
        $data['donation_id'] = $donation_id;
        $data['donation_name'] = $donation_name;
        $data['donation_approved_date'] = $donation_approved_date;
        $data['donation_end_date'] = $donation_end_date;
        $data['donation_label'] = $donation_label;
        $data['donation_account'] = $donation_account;
        $data['donor_name'] = $donor_name;
        $data['donation_type'] = $donation_type;
        $data['donation_kind'] = $donation_kind;
        $data['curr_id'] = $curr_id;
        $data['store_id'] = $store_id;
        $data['donation_file_case'] = $donation_file_case;

        $data['title'] = 'منح و هبات';
        $data['content'] = 'donation_file_index';
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function get_page($page = 1)
    {

        $this->load->library('pagination');

        $where_sql = "";

        $where_sql .= isset($this->p_donation_file_id) && $this->p_donation_file_id != null ? " AND  DONATION_FILE_ID ={$this->p_donation_file_id}  " : "";
        $where_sql .= isset($this->p_donation_id) && $this->p_donation_id != null ? " AND  DONATION_ID LIKE '%{$this->p_donation_id}%' " : "";
        $where_sql .= isset($this->p_donation_name) && $this->p_donation_name != null ? " AND  DONATION_NAME LIKE '%{$this->p_donation_name}%' " : "";
        $where_sql .= isset($this->p_donation_approved_date) && $this->p_donation_approved_date != null ? " AND  DONATION_APPROVED_DATE >= '{$this->p_donation_approved_date}' " : "";
        $where_sql .= isset($this->p_donation_end_date) && $this->p_donation_end_date != null ? " AND  DONATION_END_DATE <= '{$this->p_donation_end_date}' " : "";
        $where_sql .= isset($this->p_donation_label) && $this->p_donation_label != null ? " AND  DONATION_LABEL LIKE '%{$this->p_donation_label}%' " : "";
        $where_sql .= isset($this->p_donation_account) && $this->p_donation_account != null ? " AND  DONATION_ACCOUNT LIKE '%{$this->p_donation_account}%' " : "";
        $where_sql .= isset($this->p_donor_name) && $this->p_donor_name != null ? " AND  DONOR_NAME LIKE '%{$this->p_donor_name}%' " : "";
        $where_sql .= isset($this->p_donation_type) && $this->p_donation_type != null ? " AND  DONATION_TYPE ={$this->p_donation_type}  " : "";
        $where_sql .= isset($this->p_curr_id) && $this->p_curr_id != null ? " AND  CURR_ID ={$this->p_curr_id}  " : "";
        $where_sql .= isset($this->p_store_id) && $this->p_store_id != null ? " AND  STORE_ID ={$this->p_store_id}  " : "";
        $where_sql .= isset($this->p_donation_file_case) && $this->p_donation_file_case != null ? " AND  DONATION_FILE_CASE ={$this->p_donation_file_case}  " : "";
        $where_sql .= isset($this->p_conditions) && $this->p_conditions != null ? " AND  CONDITIONS ={$this->p_conditions}  " : "";


        //echo $where_sql;die;

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('donation_file_tb', $where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('donation_file_page', $data);
    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = ($this->{$c_var}) ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            /*if (date('Y-m-d', strtotime($this->donation_approved_date)) >= date('Y-m-d', strtotime($this->donation_end_date))) {
                $this->print_error('يجب ان يكون تاريخ الاعتماد اصغر من تاريخ نهاية المنحة');
            }*/
            $this->donation_file_id = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if (intval($this->donation_file_id) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->donation_file_id);
            }


            if ($this->p_donation_type == 1) {


                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_donation_dept_value[$i] != '') {
                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        } else if ($this->p_expenses[$i] == '') {
                            $this->print_error('يجب ان ادخال المصاريف ');

                        } else if ($this->p_vat_percent[$i] == '') {
                            $this->print_error('يجب  ادخال نسبة الضريبة ');

                        } else if ($this->p_vat_value[$i] == '') {
                            $this->print_error('يجب ادخال قيمة الضريبة ');

                        } else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        } else if ($this->p_expenses_trans_percentage[$i] == '') {
                            $this->print_error('يجب ادخال نسبة المصاريف ');

                        } else if ($this->p_expenses_transport[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف النقل ');

                        } else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }


                        $this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            $this->p_expenses[$i],
                            $this->p_vat_percent[$i],
                            $this->p_vat_value[$i],
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            $this->p_expenses_trans_percentage[$i],
                            $this->p_expenses_transport[$i],
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            1,
                            'create'
                        ));
                    }


                }
                echo intval($this->donation_file_id);

              //  ---insert


               // ---


            }
            else   if ($this->p_donation_type == 3) {


                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_donation_dept_value[$i] != '') {
                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        }  else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        }  else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }


                        $this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            null,
                            null,
                            null,
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            null,
                            null,
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            1,
                            'create'
                        ));
                    }


                }
                echo intval($this->donation_file_id);

                //  ---insert


                // ---


            }



            else
                echo intval($this->donation_file_id);


        } else {

            $result = array();
            $data['donation_data'] = $result;
            $data['donation_dept'] = $result;
            $data['content'] = 'donation_file_show';
            $data['title'] = 'اضافة منحة/هبة';
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $data['order_purpose']=1;
            $data['install']=1;
            $data['help'] = $this->help;
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }
function x ($id=0,$type=0){
    if ($type==3) echo "1";
    else
        echo "1";


}
    function save_dept()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();

            if (($this->donation_type == 2 and $this->store_id != '')) {
                $this->print_error('لا يتوجب وجود مخزن في حالة المنحة النقدية');
            }
            /*else if (date('Y-m-d', strtotime($this->donation_approved_date)) >= date('Y-m-d', strtotime($this->donation_end_date))) {
                $this->print_error('يجب ان يكون تاريخ الاعتماد اصغر من تاريخ نهاية المنحة');
            }*/
            $this->donation_file_id = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if (intval($this->donation_file_id) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->donation_file_id);
            }






            if ($this->p_donation_type == 1) {

                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_ser_dept[$i] <= 0) {

                        if ($this->p_donation_dept_value[$i] != '') {
                            if ($this->p_donation_dept_name[$i] == '') {
                                $this->print_error('يجب ان ادخال اسم ووصف القسم');
                            } else if ($this->p_donation_dept_code[$i] == '') {
                                $this->print_error('يجب ان ادخال الرمز العام للقسم');
                            } else if ($this->p_donation_dept_value[$i] == '') {
                                $this->print_error('يجب ان ادخال قيمة المنحة');
                            } else if ($this->p_expenses[$i] == '') {
                                $this->print_error('يجب ان ادخال المصاريف ');

                            } else if ($this->p_vat_percent[$i] == '') {
                                $this->print_error('يجب  ادخال نسبة الضريبة ');

                            } else if ($this->p_vat_value[$i] == '') {
                                $this->print_error('يجب ادخال قيمة الضريبة ');

                            } else if ($this->p_discount_percentage[$i] == '') {
                                $this->print_error('يجب ا ادخال نسبة الخصم ');

                            } else if ($this->p_discount_value[$i] == '') {
                                $this->print_error('يجب  ادخال قيمة الخصم ');

                            } else if ($this->p_expenses_trans_percentage[$i] == '') {
                                $this->print_error('يجب ادخال نسبة المصاريف ');

                            } else if ($this->p_expenses_transport[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف النقل ');

                            } else if ($this->p_expenses_adjustments[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف و تسويات ');

                            }


                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                                $this->p_donation_dept_name[$i],
                                $this->p_donation_dept_value[$i],
                                $this->p_expenses[$i],
                                $this->p_vat_percent[$i],
                                $this->p_vat_value[$i],
                                $this->p_discount_percentage[$i],
                                $this->p_discount_value[$i],
                                $this->p_expenses_trans_percentage[$i],
                                $this->p_expenses_transport[$i],
                                $this->p_expenses_adjustments[$i],
                                $this->p_department_note[$i],
                                1,
                                'create'
                            ));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }


                    }
                    else
                    {
                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        } else if ($this->p_expenses[$i] == '') {
                            $this->print_error('يجب ان ادخال المصاريف ');

                        } else if ($this->p_vat_percent[$i] == '') {
                            $this->print_error('يجب  ادخال نسبة الضريبة ');

                        } else if ($this->p_vat_value[$i] == '') {
                            $this->print_error('يجب ادخال قيمة الضريبة ');

                        } else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        } else if ($this->p_expenses_trans_percentage[$i] == '') {
                            $this->print_error('يجب ادخال نسبة المصاريف ');

                        } else if ($this->p_expenses_transport[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف النقل ');

                        } else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }



                        $y= $this->{$this->DETAILS_MODEL_NAME}->edit_dept($this->_postData_details_dept($this->p_ser_dept[$i], $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            $this->p_expenses[$i],
                            $this->p_vat_percent[$i],
                            $this->p_vat_value[$i],
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            $this->p_expenses_trans_percentage[$i],
                            $this->p_expenses_transport[$i],
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            'edit'
                        ));
                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }
                    }
                }
                for ($i = 0; $i < count($this->p_class_id); $i++)

                    if ($this->p_ser[$i] <= 0) {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        /*
                         *
                         * ($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null,
                                                            $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                                                       ,$price_with_trans_expenses= null,$last_price= null,$last_total= null, $typ = null)


                                                      */
                        // $this->print_error($this->p_ser[$i]);
                        if ($this->p_class_id[$i] != '') {
                            $x1= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postData_details_det(null,
                                $this->p_donation_file_id,
                                $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                $this->p_price1[$i],
                                $this->p_class_type[$i],
                                $this->p_class_case[$i],
                                $this->p_class_case_hints[$i],
                                $this->p_donation_dept_ser[$i],
                                $this->p_vat_type[$i],
                                $this->p_d_other_expenses[$i],
                                $this->p_hints[$i],
                                $this->p_d_expenses[$i],
                                $this->p_total_with_expenses[$i],
                                $this->p_price_without_vat[$i],
                                $this->p_price_without_discount[$i],
                                $this->p_price_with_trans_expenses[$i],
                                $this->p_last_price[$i],
                                $this->p_last_total[$i],
                                $this->p_class_price_no_vat[$i],
                                $this->p_tax_persent[$i],
                                $this->p_tax_value[$i],

                                'create'
                            ));

                            if (intval($x1) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x1);
                            }
                            //print_r($x);
                        }

                    }

                    else {

                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        }else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        } else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        //    $this->print_error('لم يتم الحفظ' . '<br>' .  $this->p_donation_file_id);

                        $y1=$this->{$this->DETAILS_MODEL_NAME}->edit($this->_postData_details_det( $this->p_ser[$i],
                            $this->p_donation_file_id,
                            $this->p_class_id[$i],
                            $this->p_class_unit[$i],
                            $this->p_donation_class_id[$i],
                            $this->p_amount[$i],
                            $this->p_price1[$i],
                            $this->p_class_type[$i],
                            $this->p_class_case[$i],
                            $this->p_class_case_hints[$i],
                            $this->p_donation_dept_ser[$i],
                            $this->p_vat_type[$i],
                            $this->p_d_other_expenses[$i],
                            $this->p_hints[$i],
                            $this->p_d_expenses[$i],
                            $this->p_total_with_expenses[$i],
                            $this->p_price_without_vat[$i],
                            $this->p_price_without_discount[$i],
                            $this->p_price_with_trans_expenses[$i],
                            $this->p_last_price[$i],
                            $this->p_last_total[$i],
                            $this->p_class_price_no_vat[$i],
                            $this->p_tax_persent[$i],
                            $this->p_tax_value[$i],
                            'edit'
                        ));


                        if (intval($y1) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y1);
                        }
                    }

                echo intval($this->donation_file_id);
            }

            if ($this->p_donation_type == 3) {

                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_ser_dept[$i] <= 0) {

                        if ($this->p_donation_dept_value[$i] != '') {
                            if ($this->p_donation_dept_name[$i] == '') {
                                $this->print_error('يجب ان ادخال اسم ووصف القسم');
                            } else if ($this->p_donation_dept_code[$i] == '') {
                                $this->print_error('يجب ان ادخال الرمز العام للقسم');
                            } else if ($this->p_donation_dept_value[$i] == '') {
                                $this->print_error('يجب ان ادخال قيمة المنحة');
                            }  else if ($this->p_discount_percentage[$i] == '') {
                                $this->print_error('يجب ا ادخال نسبة الخصم ');

                            } else if ($this->p_discount_value[$i] == '') {
                                $this->print_error('يجب  ادخال قيمة الخصم ');

                            }  else if ($this->p_expenses_adjustments[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف و تسويات ');

                            }




                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                                $this->p_donation_dept_name[$i],
                                $this->p_donation_dept_value[$i],
                                null,
                                null,
                                null,
                                $this->p_discount_percentage[$i],
                                $this->p_discount_value[$i],
                                null,
                                null,
                                $this->p_expenses_adjustments[$i],
                                $this->p_department_note[$i],
                                1,
                                'create'
                            ));

                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }


                    }
                    else
                    {

                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        }  else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        }  else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }



                        $y= $this->{$this->DETAILS_MODEL_NAME}->edit_dept($this->_postData_details_dept($this->p_ser_dept[$i],$this->p_donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            null,
                            null,
                            null,
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            null,
                            null,
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            'edit'
                        ));
                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }
                    }
                }
                for ($i = 0; $i < count($this->p_class_id); $i++)

                    if ($this->p_ser[$i] <= 0) {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_class_unit[$i] == '') {
                            $this->print_error('يجب اختيار الوحدة');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        /*
                         *
                         * ($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null,
                                                            $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                                                       ,$price_with_trans_expenses= null,$last_price= null,$last_total= null, $typ = null)


                                                      */
                        // $this->print_error($this->p_ser[$i]);
                        if ($this->p_class_id[$i] != '') {
                            $x1= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postData_details_det(null,
                                $this->p_donation_file_id,
                                $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                $this->p_price1[$i],
                                null,
                                null,
                                $this->p_class_case_hints[$i],
                                $this->p_donation_dept_ser[$i],
                                null,
                                $this->p_d_other_expenses[$i],
                                $this->p_hints[$i],
                                null,
                                $this->p_total_with_expenses[$i],
                                null,
                                $this->p_price_without_discount[$i],
                                null,
                                null,
                                $this->p_last_total[$i],
                                null,
                                null,
                                null,

                                'create'
                            ));
                            if (intval($x1) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x1);
                            }
                            //print_r($x);
                        }

                    }

                    else {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_class_unit[$i] == '') {
                            $this->print_error('يجب اختيار الوحدة');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        //    $this->print_error('لم يتم الحفظ' . '<br>' .  $this->p_donation_file_id);

                        $y1=$this->{$this->DETAILS_MODEL_NAME}->edit($this->_postData_details_det( $this->p_ser[$i],
                            $this->p_donation_file_id,
                            $this->p_class_id[$i],
                            $this->p_class_unit[$i],
                            $this->p_donation_class_id[$i],
                            $this->p_amount[$i],
                            $this->p_price1[$i],
                            null,
                            null,
                            $this->p_class_case_hints[$i],
                            $this->p_donation_dept_ser[$i],
                            null,
                            $this->p_d_other_expenses[$i],
                            $this->p_hints[$i],
                            null,
                            $this->p_total_with_expenses[$i],
                            null,
                            $this->p_price_without_discount[$i],
                            null,
                            null,
                            $this->p_last_total[$i],
                            null,
                            null,
                            null,
                            'edit'
                        ));
                        if (intval($y1) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y1);
                        }
                    }

                echo intval($this->donation_file_id);
            }

            } else
                echo intval($this->donation_file_id);


        }

    function save_dept_addtinal()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();

            if (($this->donation_type == 2 and $this->store_id != '')) {
                $this->print_error('لا يتوجب وجود مخزن في حالة المنحة النقدية');
            }
            /*else if (date('Y-m-d', strtotime($this->donation_approved_date)) >= date('Y-m-d', strtotime($this->donation_end_date))) {
                $this->print_error('يجب ان يكون تاريخ الاعتماد اصغر من تاريخ نهاية المنحة');
            }*/
            $this->donation_file_id = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if (intval($this->donation_file_id) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->donation_file_id);
            }






            if ($this->p_donation_type == 1) {

                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_ser_dept[$i] <= 0) {

                        if ($this->p_donation_dept_value[$i] != '') {
                            if ($this->p_donation_dept_name[$i] == '') {
                                $this->print_error('يجب ان ادخال اسم ووصف القسم');
                            } else if ($this->p_donation_dept_code[$i] == '') {
                                $this->print_error('يجب ان ادخال الرمز العام للقسم');
                            } else if ($this->p_donation_dept_value[$i] == '') {
                                $this->print_error('يجب ان ادخال قيمة المنحة');
                            } else if ($this->p_expenses[$i] == '') {
                                $this->print_error('يجب ان ادخال المصاريف ');

                            } else if ($this->p_vat_percent[$i] == '') {
                                $this->print_error('يجب  ادخال نسبة الضريبة ');

                            } else if ($this->p_vat_value[$i] == '') {
                                $this->print_error('يجب ادخال قيمة الضريبة ');

                            } else if ($this->p_discount_percentage[$i] == '') {
                                $this->print_error('يجب ا ادخال نسبة الخصم ');

                            } else if ($this->p_discount_value[$i] == '') {
                                $this->print_error('يجب  ادخال قيمة الخصم ');

                            } else if ($this->p_expenses_trans_percentage[$i] == '') {
                                $this->print_error('يجب ادخال نسبة المصاريف ');

                            } else if ($this->p_expenses_transport[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف النقل ');

                            } else if ($this->p_expenses_adjustments[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف و تسويات ');

                            }


                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                                $this->p_donation_dept_name[$i],
                                $this->p_donation_dept_value[$i],
                                $this->p_expenses[$i],
                                $this->p_vat_percent[$i],
                                $this->p_vat_value[$i],
                                $this->p_discount_percentage[$i],
                                $this->p_discount_value[$i],
                                $this->p_expenses_trans_percentage[$i],
                                $this->p_expenses_transport[$i],
                                $this->p_expenses_adjustments[$i],
                                $this->p_department_note[$i],
                                2,
                                'create'
                            ));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }


                    }
                    else
                    {
                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        } else if ($this->p_expenses[$i] == '') {
                            $this->print_error('يجب ان ادخال المصاريف ');

                        } else if ($this->p_vat_percent[$i] == '') {
                            $this->print_error('يجب  ادخال نسبة الضريبة ');

                        } else if ($this->p_vat_value[$i] == '') {
                            $this->print_error('يجب ادخال قيمة الضريبة ');

                        } else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        } else if ($this->p_expenses_trans_percentage[$i] == '') {
                            $this->print_error('يجب ادخال نسبة المصاريف ');

                        } else if ($this->p_expenses_transport[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف النقل ');

                        } else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }



                        $y= $this->{$this->DETAILS_MODEL_NAME}->edit_dept($this->_postData_details_dept($this->p_ser_dept[$i], $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            $this->p_expenses[$i],
                            $this->p_vat_percent[$i],
                            $this->p_vat_value[$i],
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            $this->p_expenses_trans_percentage[$i],
                            $this->p_expenses_transport[$i],
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            'edit'
                        ));
                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }
                    }
                }
                for ($i = 0; $i < count($this->p_class_id); $i++)

                    if ($this->p_ser[$i] <= 0) {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        /*
                         *
                         * ($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null,
                                                            $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                                                       ,$price_with_trans_expenses= null,$last_price= null,$last_total= null, $typ = null)


                                                      */
                        // $this->print_error($this->p_ser[$i]);
                        if ($this->p_class_id[$i] != '') {
                            $x1= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postData_details_det(null,
                                $this->p_donation_file_id,
                                $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                $this->p_price1[$i],
                                $this->p_class_type[$i],
                                $this->p_class_case[$i],
                                $this->p_class_case_hints[$i],
                                $this->p_donation_dept_ser[$i],
                                $this->p_vat_type[$i],
                                $this->p_d_other_expenses[$i],
                                $this->p_hints[$i],
                                $this->p_d_expenses[$i],
                                $this->p_total_with_expenses[$i],
                                $this->p_price_without_vat[$i],
                                $this->p_price_without_discount[$i],
                                $this->p_price_with_trans_expenses[$i],
                                $this->p_last_price[$i],
                                $this->p_last_total[$i],
                                $this->p_class_price_no_vat[$i],
                                $this->p_tax_persent[$i],
                                $this->p_tax_value[$i],

                                'create'
                            ));

                            if (intval($x1) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x1);
                            }
                            //print_r($x);
                        }

                    }

                    else {

                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        }else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        } else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        //    $this->print_error('لم يتم الحفظ' . '<br>' .  $this->p_donation_file_id);

                        $y1=$this->{$this->DETAILS_MODEL_NAME}->edit($this->_postData_details_det( $this->p_ser[$i],
                            $this->p_donation_file_id,
                            $this->p_class_id[$i],
                            $this->p_class_unit[$i],
                            $this->p_donation_class_id[$i],
                            $this->p_amount[$i],
                            $this->p_price1[$i],
                            $this->p_class_type[$i],
                            $this->p_class_case[$i],
                            $this->p_class_case_hints[$i],
                            $this->p_donation_dept_ser[$i],
                            $this->p_vat_type[$i],
                            $this->p_d_other_expenses[$i],
                            $this->p_hints[$i],
                            $this->p_d_expenses[$i],
                            $this->p_total_with_expenses[$i],
                            $this->p_price_without_vat[$i],
                            $this->p_price_without_discount[$i],
                            $this->p_price_with_trans_expenses[$i],
                            $this->p_last_price[$i],
                            $this->p_last_total[$i],
                            $this->p_class_price_no_vat[$i],
                            $this->p_tax_persent[$i],
                            $this->p_tax_value[$i],
                            'edit'
                        ));


                        if (intval($y1) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y1);
                        }
                    }

                echo intval($this->donation_file_id);
            }

            if ($this->p_donation_type == 3) {

                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_ser_dept[$i] <= 0) {

                        if ($this->p_donation_dept_value[$i] != '') {
                            if ($this->p_donation_dept_name[$i] == '') {
                                $this->print_error('يجب ان ادخال اسم ووصف القسم');
                            } else if ($this->p_donation_dept_code[$i] == '') {
                                $this->print_error('يجب ان ادخال الرمز العام للقسم');
                            } else if ($this->p_donation_dept_value[$i] == '') {
                                $this->print_error('يجب ان ادخال قيمة المنحة');
                            }  else if ($this->p_discount_percentage[$i] == '') {
                                $this->print_error('يجب ا ادخال نسبة الخصم ');

                            } else if ($this->p_discount_value[$i] == '') {
                                $this->print_error('يجب  ادخال قيمة الخصم ');

                            }  else if ($this->p_expenses_adjustments[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف و تسويات ');

                            }




                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                                $this->p_donation_dept_name[$i],
                                $this->p_donation_dept_value[$i],
                                null,
                                null,
                                null,
                                $this->p_discount_percentage[$i],
                                $this->p_discount_value[$i],
                                null,
                                null,
                                $this->p_expenses_adjustments[$i],
                                $this->p_department_note[$i],
                                2,
                                'create'
                            ));

                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }


                    }
                    else
                    {

                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        }  else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        }  else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }



                        $y= $this->{$this->DETAILS_MODEL_NAME}->edit_dept($this->_postData_details_dept($this->p_ser_dept[$i],$this->p_donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            null,
                            null,
                            null,
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            null,
                            null,
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            'edit'
                        ));
                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }
                    }
                }
                for ($i = 0; $i < count($this->p_class_id); $i++)

                    if ($this->p_ser[$i] <= 0) {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_class_unit[$i] == '') {
                            $this->print_error('يجب اختيار الوحدة');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        /*
                         *
                         * ($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null,
                                                            $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                                                       ,$price_with_trans_expenses= null,$last_price= null,$last_total= null, $typ = null)


                                                      */
                        // $this->print_error($this->p_ser[$i]);
                        if ($this->p_class_id[$i] != '') {
                            $x1= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postData_details_det(null,
                                $this->p_donation_file_id,
                                $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                $this->p_price1[$i],
                                null,
                                null,
                                $this->p_class_case_hints[$i],
                                $this->p_donation_dept_ser[$i],
                                null,
                                $this->p_d_other_expenses[$i],
                                $this->p_hints[$i],
                                null,
                                $this->p_total_with_expenses[$i],
                                null,
                                $this->p_price_without_discount[$i],
                                null,
                                null,
                                $this->p_last_total[$i],
                                null,
                                null,
                                null,

                                'create'
                            ));
                            if (intval($x1) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x1);
                            }
                            //print_r($x);
                        }

                    }

                    else {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_class_unit[$i] == '') {
                            $this->print_error('يجب اختيار الوحدة');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        //    $this->print_error('لم يتم الحفظ' . '<br>' .  $this->p_donation_file_id);

                        $y1=$this->{$this->DETAILS_MODEL_NAME}->edit($this->_postData_details_det( $this->p_ser[$i],
                            $this->p_donation_file_id,
                            $this->p_class_id[$i],
                            $this->p_class_unit[$i],
                            $this->p_donation_class_id[$i],
                            $this->p_amount[$i],
                            $this->p_price1[$i],
                            null,
                            null,
                            $this->p_class_case_hints[$i],
                            $this->p_donation_dept_ser[$i],
                            null,
                            $this->p_d_other_expenses[$i],
                            $this->p_hints[$i],
                            null,
                            $this->p_total_with_expenses[$i],
                            null,
                            $this->p_price_without_discount[$i],
                            null,
                            null,
                            $this->p_last_total[$i],
                            null,
                            null,
                            null,
                            'edit'
                        ));
                        if (intval($y1) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y1);
                        }
                    }

                echo intval($this->donation_file_id);
            }

        } else
            echo intval($this->donation_file_id);


    }

    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();

            if (($this->donation_type == 2 and $this->store_id != '')) {
                $this->print_error('لا يتوجب وجود مخزن في حالة المنحة النقدية');
            }
            if (($this->donation_type == 3 and $this->store_id != '')) {
                $this->print_error('لا يتوجب وجود مخزن في حالة منحة التركيب');
            }
            /*else if (date('Y-m-d', strtotime($this->donation_approved_date)) >= date('Y-m-d', strtotime($this->donation_end_date))) {
                $this->print_error('يجب ان يكون تاريخ الاعتماد اصغر من تاريخ نهاية المنحة');
            }*/
            $this->donation_file_id = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if (intval($this->donation_file_id) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->donation_file_id);
            }

            if ($this->p_donation_type == 1) {

                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_ser_dept[$i] <= 0) {

                    if ($this->p_donation_dept_value[$i] != '') {
                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        } else if ($this->p_expenses[$i] == '') {
                            $this->print_error('يجب ان ادخال المصاريف ');

                        } else if ($this->p_vat_percent[$i] == '') {
                            $this->print_error('يجب  ادخال نسبة الضريبة ');

                        } else if ($this->p_vat_value[$i] == '') {
                            $this->print_error('يجب ادخال قيمة الضريبة ');

                        } else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        } else if ($this->p_expenses_trans_percentage[$i] == '') {
                            $this->print_error('يجب ادخال نسبة المصاريف ');

                        } else if ($this->p_expenses_transport[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف النقل ');

                        } else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }


                        $x=$this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            $this->p_expenses[$i],
                            $this->p_vat_percent[$i],
                            $this->p_vat_value[$i],
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            $this->p_expenses_trans_percentage[$i],
                            $this->p_expenses_transport[$i],
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            1,
                            'create'
                        ));
                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }
                    }


                }
                    else
                    {
                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        } else if ($this->p_expenses[$i] == '') {
                            $this->print_error('يجب ان ادخال المصاريف ');

                        } else if ($this->p_vat_percent[$i] == '') {
                            $this->print_error('يجب  ادخال نسبة الضريبة ');

                        } else if ($this->p_vat_value[$i] == '') {
                            $this->print_error('يجب ادخال قيمة الضريبة ');

                        } else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        } else if ($this->p_expenses_trans_percentage[$i] == '') {
                            $this->print_error('يجب ادخال نسبة المصاريف ');

                        } else if ($this->p_expenses_transport[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف النقل ');

                        } else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }



                       $y= $this->{$this->DETAILS_MODEL_NAME}->edit_dept($this->_postData_details_dept($this->p_ser_dept[$i], $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            $this->p_expenses[$i],
                            $this->p_vat_percent[$i],
                            $this->p_vat_value[$i],
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            $this->p_expenses_trans_percentage[$i],
                            $this->p_expenses_transport[$i],
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            'edit'
                        ));
                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }
                    }
                }
                for ($i = 0; $i < count($this->p_class_id); $i++)

                    if ($this->p_ser[$i] <= 0) {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
/*
 *
 * ($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null,
                                    $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                               ,$price_with_trans_expenses= null,$last_price= null,$last_total= null, $typ = null)


                              */
                       // $this->print_error($this->p_ser[$i]);
                        if ($this->p_class_id[$i] != '') {
                            $x1= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postData_details_det(null,
                                $this->p_donation_file_id,
                                $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                $this->p_price1[$i],
                                $this->p_class_type[$i],
                                $this->p_class_case[$i],
                                $this->p_class_case_hints[$i],
                                $this->p_donation_dept_ser[$i],
                                $this->p_vat_type[$i],
                                $this->p_d_other_expenses[$i],
                                $this->p_hints[$i],
                                $this->p_d_expenses[$i],
                                $this->p_total_with_expenses[$i],
                                $this->p_price_without_vat[$i],
                                $this->p_price_without_discount[$i],
                                $this->p_price_with_trans_expenses[$i],
                                $this->p_last_price[$i],
                                $this->p_last_total[$i],
                                $this->p_class_price_no_vat[$i],
                                $this->p_tax_persent[$i],
                                $this->p_tax_value[$i],

                                'create'
                            ));

                            if (intval($x1) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x1);
                            }
                            //print_r($x);
                        }

                    }

                    else {

                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        }else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        } else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                    //    $this->print_error('لم يتم الحفظ' . '<br>' .  $this->p_donation_file_id);

                       $y1=$this->{$this->DETAILS_MODEL_NAME}->edit($this->_postData_details_det( $this->p_ser[$i],
                               $this->p_donation_file_id,
                               $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                 $this->p_price1[$i],
                           $this->p_class_type[$i],
                                $this->p_class_case[$i],
                                $this->p_class_case_hints[$i],
                               $this->p_donation_dept_ser[$i],
                               $this->p_vat_type[$i],
                               $this->p_d_other_expenses[$i],
                               $this->p_hints[$i],
                               $this->p_d_expenses[$i],
                               $this->p_total_with_expenses[$i],
                               $this->p_price_without_vat[$i],
                                   $this->p_price_without_discount[$i],
                               $this->p_price_with_trans_expenses[$i],
                               $this->p_last_price[$i],
                               $this->p_last_total[$i],
                           $this->p_class_price_no_vat[$i],
                           $this->p_tax_persent[$i],
                           $this->p_tax_value[$i],
                                'edit'
                            ));


                        if (intval($y1) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y1);
                        }
                    }

                //echo intval($this->donation_file_id);
            }

            if ($this->p_donation_type == 3) {

                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_ser_dept[$i] <= 0) {

                        if ($this->p_donation_dept_value[$i] != '') {
                            if ($this->p_donation_dept_name[$i] == '') {
                                $this->print_error('يجب ان ادخال اسم ووصف القسم');
                            } else if ($this->p_donation_dept_code[$i] == '') {
                                $this->print_error('يجب ان ادخال الرمز العام للقسم');
                            } else if ($this->p_donation_dept_value[$i] == '') {
                                $this->print_error('يجب ان ادخال قيمة المنحة');
                            }  else if ($this->p_discount_percentage[$i] == '') {
                                $this->print_error('يجب ا ادخال نسبة الخصم ');

                            } else if ($this->p_discount_value[$i] == '') {
                                $this->print_error('يجب  ادخال قيمة الخصم ');

                            }  else if ($this->p_expenses_adjustments[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف و تسويات ');

                            }

                            $this->print_error($this->p_donation_file_id);


                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                                $this->p_donation_dept_name[$i],
                                $this->p_donation_dept_value[$i],
                                null,
                                null,
                                null,
                                $this->p_discount_percentage[$i],
                                $this->p_discount_value[$i],
                                null,
                                null,
                                $this->p_expenses_adjustments[$i],
                                $this->p_department_note[$i],
                                1,
                                'create'
                            ));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }


                    }
                    else
                    {

                            if ($this->p_donation_dept_name[$i] == '') {
                                $this->print_error('يجب ان ادخال اسم ووصف القسم');
                            } else if ($this->p_donation_dept_code[$i] == '') {
                                $this->print_error('يجب ان ادخال الرمز العام للقسم');
                            } else if ($this->p_donation_dept_value[$i] == '') {
                                $this->print_error('يجب ان ادخال قيمة المنحة');
                            }  else if ($this->p_discount_percentage[$i] == '') {
                                $this->print_error('يجب ا ادخال نسبة الخصم ');

                            } else if ($this->p_discount_value[$i] == '') {
                                $this->print_error('يجب  ادخال قيمة الخصم ');

                            }  else if ($this->p_expenses_adjustments[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف و تسويات ');

                            }




                        $y= $this->{$this->DETAILS_MODEL_NAME}->edit_dept($this->_postData_details_dept($this->p_ser_dept[$i], $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            null,
                            null,
                            null,
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            null,
                            null,
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            'edit'
                        ));
                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }
                    }
                }
                for ($i = 0; $i < count($this->p_class_id); $i++)

                    if ($this->p_ser[$i] <= 0) {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_class_unit[$i] == '') {
                            $this->print_error('يجب اختيار الوحدة');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        /*
                         *
                         * ($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null,
                                                            $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                                                       ,$price_with_trans_expenses= null,$last_price= null,$last_total= null, $typ = null)


                                                      */
                        // $this->print_error($this->p_ser[$i]);
                        if ($this->p_class_id[$i] != '') {
                            $x1= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postData_details_det(null,
                                $this->p_donation_file_id,
                                $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                $this->p_price1[$i],
                                null,
                                null,
                                $this->p_class_case_hints[$i],
                                $this->p_donation_dept_ser[$i],
                                null,
                                $this->p_d_other_expenses[$i],
                                $this->p_hints[$i],
                               null,
                                $this->p_total_with_expenses[$i],
                                null,
                                $this->p_price_without_discount[$i],
                               null,
                                null,
                                $this->p_last_total[$i],
                                null,
                                null,
                                null,

                                'create'
                            ));
                            if (intval($x1) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x1);
                            }
                            //print_r($x);
                        }

                    }

                    else {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_class_unit[$i] == '') {
                            $this->print_error('يجب اختيار الوحدة');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        //    $this->print_error('لم يتم الحفظ' . '<br>' .  $this->p_donation_file_id);

                        $y1=$this->{$this->DETAILS_MODEL_NAME}->edit($this->_postData_details_det( $this->p_ser[$i],
                            $this->p_donation_file_id,
                            $this->p_class_id[$i],
                            $this->p_class_unit[$i],
                            $this->p_donation_class_id[$i],
                            $this->p_amount[$i],
                            $this->p_price1[$i],
                            null,
                            null,
                            $this->p_class_case_hints[$i],
                            $this->p_donation_dept_ser[$i],
                            null,
                            $this->p_d_other_expenses[$i],
                            $this->p_hints[$i],
                            null,
                            $this->p_total_with_expenses[$i],
                            null,
                            $this->p_price_without_discount[$i],
                            null,
                            null,
                            $this->p_last_total[$i],
                            null,
                            null,
                            null,
                            'edit'
                        ));
                        if (intval($y1) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y1);
                        }
                    }

                echo intval($this->donation_file_id);
            }
            else
                echo intval($this->donation_file_id);


        }
    }



    function change_items()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();

            if (($this->donation_type == 2 and $this->store_id != '')) {
                $this->print_error('لا يتوجب وجود مخزن في حالة المنحة النقدية');
            }
            /*else if (date('Y-m-d', strtotime($this->donation_approved_date)) >= date('Y-m-d', strtotime($this->donation_end_date))) {
                $this->print_error('يجب ان يكون تاريخ الاعتماد اصغر من تاريخ نهاية المنحة');
            }*/
           $this->donation_file_id = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if (intval($this->donation_file_id) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->donation_file_id);
            }

            if ($this->p_donation_type == 1) {


                for ($i = 0; $i < count($this->p_class_id); $i++)

                    if ($this->p_ser[$i] <= 0) {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        /*
                         *
                         * ($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null,
                                                            $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                                                       ,$price_with_trans_expenses= null,$last_price= null,$last_total= null, $typ = null)


                                                      */
                        // $this->print_error($this->p_ser[$i]);
                        if ($this->p_class_id[$i] != '') {
                            $x1= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postData_details_det(null,
                                $this->p_donation_file_id,
                                $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                $this->p_price1[$i],
                                $this->p_class_type[$i],
                                $this->p_class_case[$i],
                                $this->p_class_case_hints[$i],
                                $this->p_donation_dept_ser[$i],
                                $this->p_vat_type[$i],
                                $this->p_d_other_expenses[$i],
                                $this->p_hints[$i],
                                $this->p_d_expenses[$i],
                                $this->p_total_with_expenses[$i],
                                $this->p_price_without_vat[$i],
                                $this->p_price_without_discount[$i],
                                $this->p_price_with_trans_expenses[$i],
                                $this->p_last_price[$i],
                                $this->p_last_total[$i],
                                $this->p_class_price_no_vat[$i],
                                $this->p_tax_persent[$i],
                                $this->p_tax_value[$i],

                                'create'
                            ));
                            if (intval($x1) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x1);
                            }
                            //print_r($x);
                        }

                    }

                    else {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        }else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        } else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        //    $this->print_error('لم يتم الحفظ' . '<br>' .  $this->p_donation_file_id);

                        $y1=$this->{$this->DETAILS_MODEL_NAME}->edit($this->_postData_details_det( $this->p_ser[$i],
                            $this->p_donation_file_id,
                            $this->p_class_id[$i],
                            $this->p_class_unit[$i],
                            $this->p_donation_class_id[$i],
                            $this->p_amount[$i],
                            $this->p_price1[$i],
                            $this->p_class_type[$i],
                            $this->p_class_case[$i],
                            $this->p_class_case_hints[$i],
                            $this->p_donation_dept_ser[$i],
                            $this->p_vat_type[$i],
                            $this->p_d_other_expenses[$i],
                            $this->p_hints[$i],
                            $this->p_d_expenses[$i],
                            $this->p_total_with_expenses[$i],
                            $this->p_price_without_vat[$i],
                            $this->p_price_without_discount[$i],
                            $this->p_price_with_trans_expenses[$i],
                            $this->p_last_price[$i],
                            $this->p_last_total[$i],
                            $this->p_class_price_no_vat[$i],
                            $this->p_tax_persent[$i],
                            $this->p_tax_value[$i],
                            'edit'
                        ));
                        if (intval($y1) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y1);
                        }
                    }

                echo intval($this->donation_file_id);
            }

            if ($this->p_donation_type == 3) {

                for ($i = 0; $i < count($this->p_donation_dept_value); $i++) {

                    if ($this->p_ser_dept[$i] <= 0) {

                        if ($this->p_donation_dept_value[$i] != '') {
                            if ($this->p_donation_dept_name[$i] == '') {
                                $this->print_error('يجب ان ادخال اسم ووصف القسم');
                            } else if ($this->p_donation_dept_code[$i] == '') {
                                $this->print_error('يجب ان ادخال الرمز العام للقسم');
                            } else if ($this->p_donation_dept_value[$i] == '') {
                                $this->print_error('يجب ان ادخال قيمة المنحة');
                            }  else if ($this->p_discount_percentage[$i] == '') {
                                $this->print_error('يجب ا ادخال نسبة الخصم ');

                            } else if ($this->p_discount_value[$i] == '') {
                                $this->print_error('يجب  ادخال قيمة الخصم ');

                            }  else if ($this->p_expenses_adjustments[$i] == '') {
                                $this->print_error('يجب  ادخال مصاريف و تسويات ');

                            }




                            $x=$this->{$this->DETAILS_MODEL_NAME}->create_dept($this->_postData_details_dept(null, $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                                $this->p_donation_dept_name[$i],
                                $this->p_donation_dept_value[$i],
                                null,
                                null,
                                null,
                                $this->p_discount_percentage[$i],
                                $this->p_discount_value[$i],
                                null,
                                null,
                                $this->p_expenses_adjustments[$i],
                                $this->p_department_note[$i],
                                'create'
                            ));
                            if (intval($x) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                            }
                        }


                    }
                    else
                    {

                        if ($this->p_donation_dept_name[$i] == '') {
                            $this->print_error('يجب ان ادخال اسم ووصف القسم');
                        } else if ($this->p_donation_dept_code[$i] == '') {
                            $this->print_error('يجب ان ادخال الرمز العام للقسم');
                        } else if ($this->p_donation_dept_value[$i] == '') {
                            $this->print_error('يجب ان ادخال قيمة المنحة');
                        }  else if ($this->p_discount_percentage[$i] == '') {
                            $this->print_error('يجب ا ادخال نسبة الخصم ');

                        } else if ($this->p_discount_value[$i] == '') {
                            $this->print_error('يجب  ادخال قيمة الخصم ');

                        }  else if ($this->p_expenses_adjustments[$i] == '') {
                            $this->print_error('يجب  ادخال مصاريف و تسويات ');

                        }



                        $y= $this->{$this->DETAILS_MODEL_NAME}->edit_dept($this->_postData_details_dept($this->p_ser_dept[$i], $this->p_donation_file_id, $this->p_donation_dept_code[$i],
                            $this->p_donation_dept_name[$i],
                            $this->p_donation_dept_value[$i],
                            null,
                            null,
                            null,
                            $this->p_discount_percentage[$i],
                            $this->p_discount_value[$i],
                            null,
                            null,
                            $this->p_expenses_adjustments[$i],
                            $this->p_department_note[$i],
                            'edit'
                        ));
                        if (intval($y) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                        }
                    }
                }
                for ($i = 0; $i < count($this->p_class_id); $i++)

                    if ($this->p_ser[$i] <= 0) {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_class_unit[$i] == '') {
                            $this->print_error('يجب اختيار الوحدة');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        /*
                         *
                         * ($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null,
                                                            $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                                                       ,$price_with_trans_expenses= null,$last_price= null,$last_total= null, $typ = null)


                                                      */
                        // $this->print_error($this->p_ser[$i]);
                        if ($this->p_class_id[$i] != '') {
                            $x1= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postData_details_det(null,
                                $this->p_donation_file_id,
                                $this->p_class_id[$i],
                                $this->p_class_unit[$i],
                                $this->p_donation_class_id[$i],
                                $this->p_amount[$i],
                                $this->p_price1[$i],
                                null,
                                null,
                                $this->p_class_case_hints[$i],
                                $this->p_donation_dept_ser[$i],
                                null,
                                $this->p_d_other_expenses[$i],
                                $this->p_hints[$i],
                                null,
                                $this->p_total_with_expenses[$i],
                                null,
                                $this->p_price_without_discount[$i],
                                null,
                                null,
                                $this->p_last_total[$i],
                                null,
                                null,
                                null,

                                'create'
                            ));
                            if (intval($x1) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $x1);
                            }
                            //print_r($x);
                        }

                    }

                    else {
                        if ($this->p_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف');
                        } else if ($this->p_donation_dept_ser[$i] == '') {
                            $this->print_error('يجب اختيار الرمز العام للقسم');
                        }
                        else if ($this->p_class_unit[$i] == '') {
                            $this->print_error('يجب اختيار الوحدة');
                        }
                        else if ($this->p_donation_class_id[$i] == '') {
                            $this->print_error('يجب ان ادخال رقم الصنف في المنحة/ المناقصة');
                        } else if ($this->p_amount[$i] == '') {
                            $this->print_error('يجب ان ادخال الكمية');
                        } else if ($this->p_price1[$i] == '') {
                            $this->print_error('يجب ان ادخال السعر ');

                        }
                        //    $this->print_error('لم يتم الحفظ' . '<br>' .  $this->p_donation_file_id);

                        $y1=$this->{$this->DETAILS_MODEL_NAME}->edit($this->_postData_details_det( $this->p_ser[$i],
                            $this->p_donation_file_id,
                            $this->p_class_id[$i],
                            $this->p_class_unit[$i],
                            $this->p_donation_class_id[$i],
                            $this->p_amount[$i],
                            $this->p_price1[$i],
                            null,
                            null,
                            $this->p_class_case_hints[$i],
                            $this->p_donation_dept_ser[$i],
                            null,
                            $this->p_d_other_expenses[$i],
                            $this->p_hints[$i],
                            null,
                            $this->p_total_with_expenses[$i],
                            null,
                            $this->p_price_without_discount[$i],
                            null,
                            null,
                            $this->p_last_total[$i],
                            null,
                            null,
                            null,
                            'edit'
                        ));
                        if (intval($y1) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $y1);
                        }
                    }

                echo intval($this->donation_file_id);
            }



        }
    }
    function _post_validation($isEdit = false)
    {
        if (($this->donation_type == '' and $isEdit) /*or ($this->donation_type == 1 and $this->store_id == '') or ($this->donation_account == '')*/) {
            $this->print_error('يجب ادخال جميع البيانات');
        } /*  else if (date('Y-m-d', strtotime($this->donation_approved_date)) >= date('Y-m-d', strtotime($this->donation_end_date))) {
            $this->print_error('يجب ان يكون تاريخ الاعتماد اصغر من تاريخ نهاية المنحة');
        }*/

        else if (($this->donation_type == 2 and $this->store_id != '')) {
            $this->print_error('لا يتوجب وجود مخزن في حالة المنحة النقدية');
        }
    }


    function adopt()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,'','',$this->p_adopts);

            if (intval($res) <= 0) {
                echo intval($res);
            }
else
            echo intval($res);

        } else
            echo "لم يتم ارسال رقم العهدة";
    }

    function un_adopt()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,'','',$this->p_adopt);

            if (intval($res) <= 0) {
                echo intval($res);
               // $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
            }

            echo intval($res);

        } else
            echo "لم يتم ارسال رقم العهدة";
    }
    function get_store_donation()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->MODEL_NAME}->adopt($this->p_id,$this->p_acount_don,$this->p_store_don,$this->p_adopt);

            if (intval($res) <= 0) {
                $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
            }

            echo intval($res);

        } else
            echo "لم يتم ارسال رقم العهدة";
    }

    function adopt_close()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->MODEL_NAME}->adopt_close($this->p_id,$this->p_adopt);

            if (intval($res) <= 0) {
                $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
            }

            echo 1;

        } else
            echo "لم يتم ارسال رقم العهدة";
    }

    function donation_file_det_tb_check($ser)
    {

            $res = $this->{$this->DETAILS_MODEL_NAME}->donation_file_det_tb_check($ser);

        echo intval($res);
    }

    function delete_details()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->DETAILS_MODEL_NAME}->delete($this->p_id, $this->p_donation_id);

            /* if (intval($res) < 0) {
                 //$this->print_error('لم يتم الحذف'.'<br>'.$res);
                 //echo $res;
                 echo $res;
             } else if (intval($res) == 0) {
                 echo $res;
             }else echo 1;*/
            echo intval($res);
        } else
            echo "لم يتم ارسال رقم المنحة";
    }

    function delete_details_dept()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->DETAILS_MODEL_NAME}->delete_dept($this->p_id, $this->p_donation_id);

            /* if (intval($res) < 0) {
                 //$this->print_error('لم يتم الحذف'.'<br>'.$res);
                 //echo $res;
                 echo $res;
             } else if (intval($res) == 0) {
                 echo $res;
             }else echo 1;*/
            echo intval($res);
        } else
            echo "لم يتم ارسال رقم المنحة";
    }

    function delete()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete($val);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete($id);
        }

        if ($msg == 1) {
            echo modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }

    function get($id,$order_purpose)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        $result2 = $this->{$this->DETAILS_MODEL_NAME}->get_dept($id);
        //$data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        // $this->print_error('لم يتم الاعتماد'.'<br>'.$id);
        if (!(count($result) == 1))
            die();
        $data['donation_data'] = $result;
        $data['donation_dept'] = $result2;
        $data['content'] = 'donation_file_show';
        $data['action'] = 'edit';
        $data['order_purpose']=$order_purpose;
        $data['title'] = 'عرض بيانات المنح';

        $data['isCreate'] = false;

        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['INPUT_USER'] && $data['action'] == 'edit') ? true : false : false;
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);

    }

    function _look_ups(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');


        $this->load->model('payment/customers_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('stores/stores_model');

        $data['donation_types'] = $this->constant_details_model->get_list(100);
        $data['donation_kinds'] = $this->constant_details_model->get_list(101);
        $data['conditions'] = $this->constant_details_model->get_list(102);
        $data['class_case'] = $this->constant_details_model->get_list(103);
        $data['class_install'] = $this->constant_details_model->get_list(103);
        $data['class_type_show'] = $this->constant_details_model->get_list(29);
        $data['donation_adopts'] = $this->constant_details_model->get_list(104);
        $data['items_type'] = $this->constant_details_model->get_list(41);
        $data['account_type_all'] = $this->constant_details_model->get_list(15);
        $data['currency'] = $this->currency_model->get_all();
        $data['stores_all'] = $this->{$this->MODEL_NAME}->get_store_donation();
        /*$data['customer_ids']=$this->customers_model->get_all_by_type(3);
        $data['class_code_ser']='';*/


    }

    function public_get_details($id = 0, $adopt=0,$type_donation_det=0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        if($id!=0){


        $adopt = $this->input->post('adopt') ? $this->input->post('adopt') :$adopt;
        $type_donation_det = $this->input->post('type_donation_det') ? $this->input->post('type_donation_det') : $type_donation_det;
        //$this->print_error($id);
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        $data['adopt'] = $adopt;
        $data['departments_all'] = $this->{$this->DETAILS_MODEL_NAME}->get_dept($id);
        $data['donation_type_install']=$type_donation_det;
        $data['action'] = 'edit';
       // echo $type_donation_det;

        if($type_donation_det!=2)
        { $this->_look_ups($data);
            echo ($this->load->view('donation_file_details', $data));
         }
        }

    }

    function public_get_details_dept($id = 0, $adopt=0,$is_install=0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') :$id;
        $adopt = $this->input->post('adopt') ? $this->input->post('adopt') :$adopt;
        $is_install = $this->input->post('is_install') ? $this->input->post('is_install') :$is_install;

//echo $this->input->post('is_install');
        if ($id>0) {

        $data['details_dept'] = $this->{$this->DETAILS_MODEL_NAME}->get_dept($id);
        $data['adopt'] = $adopt;}
        else{

            $data['details_dept'] = array();
        }
if($is_install==1)
{$data['install'] = 0;

    echo ($this->load->view('donation_file_details_dept', $data));}
        if($is_install==3)
        {
            $data['install'] = 1;

            echo ($this->load->view('donation_file_details_dept', $data));}
        else
            echo '';
}

    function public_get_details_dept_install($id = 0, $adopt=0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') :$id;
        $adopt = $this->input->post('adopt') ? $this->input->post('adopt') :$adopt;

        echo $this->donation_type;
        if ($id>0) {

            $data['details_dept'] = $this->{$this->DETAILS_MODEL_NAME}->get_dept($id);
            $data['adopt'] = $adopt;}
        else{

            $data['details_dept'] = array();
        }


        $data['install'] = 1;

        echo ($this->load->view('donation_file_details_dept', $data));
    }




    function view($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        if (!(count($result) == 1))
            die();
        $data['donation_data'] = $result;
        $data['content'] = 'donation_file_shows';
        $data['action'] = 'edit';
        $data['title'] = 'عرض بيانات المنحة';
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function public_get_details_case($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        $this->_look_ups($data);
        $this->load->view('donation_file_details_case', $data);
    }

    function change_cases()
    {
        /*   $ser = $this->input->post('ser') ? $this->input->post('ser') : $ser;
         $class_case = $this->input->post('class_case') ? $this->input->post('class_case') : $class_case;
         $replace_class_id = $this->input->post('replace_class_id') ? $this->input->post('replace_class_id') : $replace_class_id;
         $replace_class_unit = $this->input->post('replace_class_unit') ? $this->input->post('replace_class_unit') : $replace_class_unit;
         $rem_amount = $this->input->post('rem_amount') ? $this->input->post('rem_amount') : $rem_amount;

           $res = $this->{$this->DETAILS_MODEL_NAME}->update_class_case($ser,$class_case,$replace_class_id,$replace_class_unit,$rem_amount);

             if(intval($res) <= 0){
                 $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
             }
             echo 1;
     */
        //  print_r($this->ser);
        for ($i = 0; $i < count($this->ser); $i++) {

            if ($this->old_class_case[$i] != $this->class_case[$i]) {
              //  $this->print_error(  $this->replace_class_id[$i]."dd".$i);
               // exit ;
                if ($this->class_case[$i] == 2 and ($this->replace_class_id[$i] == '' or $this->replace_class_type[$i] == '' or (($this->replace_class_id[$i] ==$this->class_id[$i]) and ($this->replace_class_type[$i] ==$this->class_type[$i])) ) )

                    $this->print_error('اختر الصنف المستبدل');
                else

                    $res = $this->{$this->DETAILS_MODEL_NAME}->update_class_case(
                        $this->ser[$i]
                        , $this->class_case[$i]
                        , $this->replace_class_id[$i]
                        , $this->replace_class_unit[$i]
                        , $this->replace_class_type[$i]
                        , $this->rem_amount[$i]
                        , $this->class_case_hints[$i]
                        , $this->replace_class_case_hints[$i]);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم تغيير حالة الأصناف' . '<br>' . $res);
                }
            }

        }
        echo 1;
    }

    function _postedData($typ = null)
    {
//print_r($this->p_donation_account);die;
  // $this->print_error('x'.$this->other_expenses);
        $result = array(
            array('name' => 'DONATION_FILE_ID', 'value' => $this->donation_file_id, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_ID', 'value' => $this->donation_id, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_NAME', 'value' => $this->donation_name, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_APPROVED_DATE', 'value' => $this->donation_approved_date, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_END_DATE', 'value' => $this->donation_end_date, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_LABEL', 'value' => $this->donation_label, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_ACCOUNT', 'value' => $this->p_donation_account, 'type' => '', 'length' => -1),
            array('name' => 'DONOR_NAME', 'value' => $this->donor_name, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_TYPE', 'value' => $this->donation_type, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_KIND', 'value' => $this->donation_kind, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->curr_id, 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $this->curr_value, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_VALUE', 'value' => $this->donation_value, 'type' => '', 'length' => -1),
            array('name' => 'CONDITIONS', 'value' => $this->conditions, 'type' => '', 'length' => -1),
            array('name' => 'STORE_ID', 'value' => $this->store_id, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->notes, 'type' => '', 'length' => -1),
            array('name' => 'OTHER_EXPENSES', 'value' => $this->other_expenses, 'type' => '', 'length' => -1),
            array('name' => 'OTHER_EXPENSES_NOTE', 'value' => $this->other_expenses_note, 'type' => '', 'length' => -1),
            array('name' => 'CURR_DATE', 'value' => $this->p_curr_date, 'type' => '', 'length' => -1)
        );
        //  print_r($result);
        if ($typ == 'create')
            unset($result[0]);

        //print_r($result);
        return $result;
    }


  /*  function _postData_details($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null, $class_case = null, $class_case_hints = null, $typ = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_FILE_ID', 'value' => $donation_file_id, 'type' => '', 'length' => -1),
            array('name' => 'CALSS_ID', 'value' => $calss_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_CLASS_ID', 'value' => $donation_class_id, 'type' => '', 'length' => -1),
            array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_CASE', 'value' => $class_case, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_CASE_HINTS', 'value' => $class_case_hints, 'type' => '', 'length' => -1)
        );
        if ($typ == 'create')
            unset($result[0]);
        else    unset($result[1]);

        return $result;

    }*/


    function _postData_details_dept($ser = null, $donation_file_id, $donation_dept_code = null, $donation_dept_name = null, $donation_dept_value = null, $expenses = null, $vat_percent = null, $vat_value = null, $discount_percentage = null,
                                    $discount_value = null, $expenses_trans_percentage = null, $expenses_transport = null, $expenses_adjustments = null, $department_note = null,$is_additional_lot = null, $typ = null)
    {


        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_FILE_ID', 'value' => $donation_file_id, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_DEPT_CODE', 'value' => $donation_dept_code, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_DEPT_NAME', 'value' => $donation_dept_name, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_DEPT_VALUE', 'value' => $donation_dept_value, 'type' => '', 'length' => -1),
            array('name' => 'EXPENSES', 'value' => $expenses, 'type' => '', 'length' => -1),
            array('name' => 'VAT_PERCENT', 'value' => $vat_percent, 'type' => '', 'length' => -1),
            array('name' => 'VAT_VALUE', 'value' => $vat_value, 'type' => '', 'length' => -1),
            array('name' => 'DISCOUNT_PERCENTAGE', 'value' => $discount_percentage, 'type' => '', 'length' => -1),
            array('name' => 'DISCOUNT_VALUE', 'value' => $discount_value, 'type' => '', 'length' => -1),
            array('name' => 'EXPENSES_TRANS_PERCENTAGE', 'value' => $expenses_trans_percentage, 'type' => '', 'length' => -1),
            array('name' => 'EXPENSES_TRANSPORT', 'value' => $expenses_transport, 'type' => '', 'length' => -1),
            array('name' => 'EXPENSES_ADJUSTMENTS', 'value' => $expenses_adjustments, 'type' => '', 'length' => -1),
            array('name' => 'DEPARTMENT_NOTE', 'value' => $department_note, 'type' => '', 'length' => -1),
            array('name' => 'IS_ADDITIONAL_LOT', 'value' => $is_additional_lot, 'type' => '', 'length' => -1)

        );
        //print_r($result);
        if ($typ == 'create')
            unset($result[0]);
        else
            unset($result[14]);


        return $result;

    }

    function _postData_details_det($ser = null, $donation_file_id, $calss_id = null, $class_unit = null, $donation_class_id = null, $amount = null, $price = null,$class_type=null, $class_case = null, $class_case_hints = null,
                                    $donation_dept_ser = null, $vat_type = null, $other_expenses = null, $hints = null, $expenses = null,$total_with_expenses= null,$price_without_vat= null,$price_without_discount= null
                               ,$price_with_trans_expenses= null,$last_price= null,$last_total= null,$class_price_no_vat=null,$tax_persent=null,$tax_value=null, $typ = null)
    {


        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_FILE_ID', 'value' => $donation_file_id, 'type' => '', 'length' => -1),
            array('name' => 'CALSS_ID', 'value' => $calss_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_CLASS_ID', 'value' => $donation_class_id, 'type' => '', 'length' => -1),
            array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_TYPE', 'value' => $class_type, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_CASE', 'value' => $class_case, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_CASE_HINTS', 'value' => $class_case_hints, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_DEPT_SER', 'value' => $donation_dept_ser, 'type' => '', 'length' => -1),
            array('name' => 'VAT_TYPE', 'value' => $vat_type, 'type' => '', 'length' => -1),
            array('name' => 'OTHER_EXPENSES', 'value' => $other_expenses, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'EXPENSES', 'value' => $expenses, 'type' => '', 'length' => -1),
            array('name' => 'TOTAL_WITH_EXPENSES', 'value' => $total_with_expenses, 'type' => '', 'length' => -1),
            array('name' => 'PRICE_WITHOUT_VAT', 'value' => $price_without_vat, 'type' => '', 'length' => -1),
            array('name' => 'PRICE_WITHOUT_DISCOUNT', 'value' => $price_without_discount, 'type' => '', 'length' => -1),
            array('name' => 'PRICE_WITH_TRANS_EXPENSES', 'value' => $price_with_trans_expenses, 'type' => '', 'length' => -1),
            array('name' => 'LAST_PRICE', 'value' => $last_price, 'type' => '', 'length' => -1),
            array('name' => 'LAST_TOTAL', 'value' => $last_total, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_PRICE_NO_VAT', 'value' => $class_price_no_vat, 'type' => '', 'length' => -1),
            array('name' => 'TAX_PERSENT', 'value' => $tax_persent, 'type' => '', 'length' => -1),
            array('name' => 'TAX_VALUE', 'value' => $tax_value, 'type' => '', 'length' => -1),





        );
        //print_r($result);
        if ($typ == 'create')
            unset($result[0]);
else      unset($result[1]);

        return $result;

    }
    function public_get_donation_by_store_input($id = null)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $result = $this->{$this->MODEL_NAME}->donation_file_get_id($id);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
        //  return json_encode($result);
    }

    function public_get_donation_by_store_id($id = null)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $result = $this->{$this->MODEL_NAME}->donation_file_get_id_by_store($id);
        if( count($result)!= 1 )
            $this->print_error('خطا في عدد المنح');
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function public_get_donation_details($id = 0)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->model('settings/constant_details_model');

        $data['class_unit'] = $this->constant_details_model->get_list(29);
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        if ($id != 0) {
            $data['action'] = 'edit';
            $data['rec_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_for_stores($id);
            echo($this->load->view('stores_class_input_detail_page', $data));

        }
    }

    function public_get_donation_receipt_details($id = 0)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->model('settings/constant_details_model');

        $data['class_unit'] = $this->constant_details_model->get_list(29);
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        if ($id != 0) {
            $data['action'] = 'edit';
            $data['rec_details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_for_stores($id);
        //    print_r($data['rec_details']);
            echo($this->load->view('receipt_class_input_detail_page', $data));

        }
    }
    function public_index($did='',$text= null, $type =null,  $id='', $name ='',$parent_id='',$grand_id='', $page= 1){
        $type= $type==-1 ? null: $type;
        $id= $id==-1 ? null: $id;
        $name= $name==-1 ? null: urldecode($name);
        $data['text']= $this->input->get_post('text') ? $this->input->get_post('text') : $text;
        $data['did']=   $this->input->get_post('did')   ? $this->input->get_post('did')   : $did;
        $data['id']=   $this->input->get_post('id')   ? $this->input->get_post('id')   : $id;
        $data['name_ar']= $this->input->get_post('name_ar') ? $this->input->get_post('name_ar') : $name;
        $data['name_en']= $this->input->get_post('name_en') ? $this->input->get_post('name_en') : $type;
        $data['parent_id']= $this->input->get_post('parent_id') ? $this->input->get_post('parent_id') : $parent_id;
        $data['grand_id']= $this->input->get_post('grand_id') ? $this->input->get_post('grand_id') : $grand_id;
        $data['page']= $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        $data['class_parent_id'] = $this->class_model->getAllParents();



        $data['grands'] = $this->class_model->getAllGrandsClasses();
        //   $x= (isset( $grand_id) && $grand_id !='')?  $grand_id:$grand_id ;
        $data['class_parent_id'] = $this->class_model->getAllParentsClasses(null);




        $data['content']='classes_index';
        add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['txt']=$text;
        $this->load->view('template/view',$data);
    }
    function public_get_classes($prm= array()){
        add_js('jquery.hotkeys.js');
        if(!$prm) //add_percent_sign

            $prm= array('text'=>$this->input->get_post('text'),
                'did'=>$this->input->get_post('did'),
                'id'=>$this->input->get_post('id'),
                'name_ar'=>$this->input->get_post('name_ar'),
                'name_en'=>$this->input->get_post('name_en'),
                'parent_id'=>$this->input->get_post('parent_id'),
                'grand_id'=>$this->input->get_post('grand_id'),
                'page'=>$this->input->get_post('page')
            );

        $this->load->library('pagination');

        $page= $prm['page'] ? $prm['page']: 1;

        $config['base_url'] = base_url("donations/donation/public_index/?text={$prm['text']}&did={$prm['did']}&id={$prm['id']}&name_ar={$prm['name_ar']}&name_en={$prm['name_en']}&parent_id={$prm['parent_id']}&grand_id={$prm['grand_id']}");

        $prm['did']= $prm['did'] != -1 ? $prm['did']: null;
        $prm['id']= $prm['id'] != -1 ? $prm['id']: null;
        $prm['name_ar']= $prm['name_ar'] !=-1 ? add_percent_sign($prm['name_ar']): null;
        $prm['name_en']= $prm['name_en'] !=-1 ? $prm['name_en']: null;
        $prm['parent_id']= $prm['parent_id'] !=-1 ? $prm['parent_id']: null;
        $prm['grand_id']= $prm['grand_id'] !=-1 ? $prm['grand_id']: null;
//echo "$prm[did],$prm[id], $prm[name_ar], $prm[name_en], $prm[parent_id], $prm[grand_id],1";
     //   exit;
        $count_rs = $this->{$this->MODEL_NAME}->get_count($prm['did'],$prm['id'], $prm['name_ar'], $prm['name_en'], $prm['parent_id'], $prm['grand_id'],1);

     //  echo ($count_rs);

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;

        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;
        $config['page_query_string']=true;
        $config['query_string_segment']='page';

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );
//echo " $offset, $row";
        $data['get_list'] =$this->{$this->MODEL_NAME}->get_lists($prm['did'],$prm['id'], $prm['name_ar'] , $prm['name_en'], $prm['parent_id'], $prm['grand_id'], $offset, $row,1 );

//print_r( $data['get_list']);
        $this->load->view('class_page',$data);
    }
}
