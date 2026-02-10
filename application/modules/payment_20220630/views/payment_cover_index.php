<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 09:08 ص
 */
$create_url=base_url('payment/payment_cover/create');
$get_page_url = base_url('payment/payment_cover/get_page');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند </label>
                    <div>
                        <input type="text"  name="entry_ser" id="txt_entry_ser"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> المستفيد</label>
                    <div>
                        <input type="text"  name="customer_name"    id="txt_customer_name" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ من</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"    id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ الي</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_date"    id="txt_to_date" class="form-control">
                    </div>
                </div>



                <div class="form-group col-sm-2">
                    <label class="control-label"> المدخل</label>
                    <div>
                        <input type="text"  name="entry_usr"    id="txt_entry_user" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> البيان</label>
                    <div>
                        <input type="text"  name="hint"    id="txt_hint" class="form-control">
                    </div>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#paymentTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>


            </div>
        </fieldset>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run('payment/payment_cover/get_page',$page);?>
        </div>

    </div>

</div>


<?php


$scripts = <<<SCRIPT

<script>

    $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),entry_user : $('#txt_entry_user').val(),hint:$('#txt_hint').val()});

    }

    function LoadingData(){

    ajax_pager_data('#paymentTbl > tbody',{id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),entry_user : $('#txt_entry_user').val(),hint:$('#txt_hint').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),entry_user : $('#txt_entry_user').val(),hint:$('#txt_hint').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>

