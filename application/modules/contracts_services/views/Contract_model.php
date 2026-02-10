<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Tareq Elbawab
 * Date: 24/03/2020
 * Time: 8:07 AM
 */
$MODULE_NAME = 'contracts_services';
$TB_NAME = 'Gcc_tree_structure';

$create_gcc_url = base_url("$MODULE_NAME/$TB_NAME/create");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create_contract");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
?>
<!--اضافة ادارة--->
<!-- Add Edit gcc_modal  -->
<div class="modal fade" id="gcc_add_modal">
    <div class="modal-dialog" style="width: 900px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات الادارة</h4>
            </div>
            <form class="form-horizontal" id="gcc_form" method="post" action="<?= $create_gcc_url ?>" role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> الإدارة الأب </label>
                        <div class="col-sm-3">
                            <input type="number" name="gcc_parent_id" readonly id="txt_gcc_parent_id"
                                   class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" readonly id="txt_gcc_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">الإدارة </label>
                        <select name="gcc_st_no" id="dp_gcc_no" class="form-control col-sm-4">
                            <option value="">__________</option>
                            <?php foreach ($structure_all as $row) : ?>
                                <option value="<?= $row['ST_ID'] ?>"><?= $row['ST_ID'] . ': ' . $row['ST_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <hr>
                </div>
                <div class="modal-footer">
                    <?php //if ( HaveAccess($create_url) or HaveAccess($edit_emp_url) ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php //endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal   EXP_ACCOUNT_CUST-->
<!-- End Add Edit Emps Modal -->


<!-- Add Edit contract_add_modal  -->
<div class="modal fade" id="contract_add_modal">
    <div class="modal-dialog" style="width: 900px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات التعاقد</h4>
            </div>
            <form class="form-horizontal" id="contract_form" method="post" action="<?= $create_url ?>" role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> الصنف الأب </label>
                        <div class="col-sm-3">
                            <input type="number" name="gcc_con_parent_id" readonly id="txt_gcc_con_parent_id"
                                   class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly id="txt_contract_parent_name"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> رقم المركز </label>
                        <div class="col-sm-3">
                            <input type="number" name="gcc_id" readonly id="txt_gcc_id"
                                   class="form-control">
                        </div>


                        <label class="col-sm-2 control-label"> اسم التعاقد </label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="contract_name"
                                   id="txt_contract_name" class="form-control" dir="rtl">
                            <span class="field-validation-valid" data-valmsg-for="contract_name"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> نوع التعاقد </label>
                        <div class="col-sm-3">
                            <select name="contract_type" id="dp_contract_type" class="form-control">
                                <?php foreach ($contracts_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <label class="col-sm-2 control-label"> تفاصيل التعاقد </label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="contract_detail"
                                   id="txt_contract_detail" class="form-control" dir="rtl">
                            <span class="field-validation-valid" data-valmsg-for="contract_detail"
                                  data-valmsg-replace="true"></span>
                        </div>

                    </div>

                    <hr>
                </div>
                <div class="modal-footer">
                    <?php if (HaveAccess($create_url) or HaveAccess($edit_url)) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal   EXP_ACCOUNT_CUST-->
<!-- End Add Edit Emps Modal -->

