<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 13/04/15
 * Time: 10:01 ص
 */

?>
<div class="modal fade" id="menuModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">الإجراءات </h4>
            </div>
            <div class="modal-body">

                <ul class="report-menu">

                    <?php if( HaveAccess(base_url('financial/warranty/create_details'))):  ?> <li><a class="btn red" data-report-source="1" data-option="#date1,#reasons" data-type="report" href="javascript:;">تجديد الكفالة</a> </li><?php endif; ?>
                    <?php if( HaveAccess(base_url('financial/warranty/return_bail'))):  ?><li><a class="btn green"  href="javascript:return_bail();"> إرجاع الكفالة  </a> </li><?php endif; ?>
                    <?php if( HaveAccess(base_url('financial/warranty/cash_bail'))):  ?> <li><a class="btn blue" data-report-source="2" data-option="#account1,#account2" data-type="report" href="javascript:;"> تسيل الكفالة  </a> </li><?php endif; ?>


                </ul>

            </div>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="actionsModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> الكفالات</h4>
            </div>
            <div class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group rp_prm" id="date1" >
                        <label class="col-sm-2 control-label"> تجديد لتاريخ </label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date1" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group rp_prm" id="reasons" >
                        <label class="col-sm-2 control-label"> سبب التجديد  </label>
                        <div class="col-sm-9">
                            <input type="text"  id="txt_reasons" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="account1"  style="display: none;">
                        <label class="col-sm-3 control-label"> حساب البنك </label>
                        <div class="col-sm-3">
                            <input type="text"  id="h_txt_account_1" class="form-control"/>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" readonly id="txt_account_1" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="account2"  style="display: none;">
                        <label class="col-sm-3 control-label">حساب الإيراد</label>
                        <div class="col-sm-3">
                            <input type="text"  id="h_txt_account_2" class="form-control"/>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" readonly id="txt_account_2" class="form-control"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-action="report" class="btn btn-primary">حفظ </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->