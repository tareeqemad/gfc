<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * history: Ahmed Barakat
 * Date: 9/22/14
 * Time: 11:34 AM
 */

$history_page_url =base_url('budget/history/get_history');
$delete_url =base_url('budget/history/delete');
$get_url =base_url('budget/history/get_id');
$edit_url =base_url('budget/history/edit');
$create_url =base_url('budget/history/create');


?>
<?= AntiForgeryToken();?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)): ?><li><a onclick="javascript:history_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>
            <?php if( HaveAccess($delete_url)): ?><li><a  onclick="javascript:history_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li><?php endif;?>

            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="col-sm-4">

            <div class="form-group">
                <div class="col-sm-8">
                    <div class="input-group">

                        <span class="input-group-addon">  <i class="icon icon-search"></i></span>
                        <input type="text" id="search-tree" class="form-control" placeholder="بحث">
                    </div>
                </div>
                <?php if($showBranch): ?>
                    <div class="input-group col-sm-4">
                        <select type="text"   name="branch" id="dp_branch" class="form-control" >
                            <option value="-1">إختر الفرع</option>
                            <?php foreach($branches as $row) :?>
                                <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <?php echo modules::run('budget/budget/public_get_bedget_history'); ?>

        </div>


        <div class="col-sm-8"  id="container">

            <?php echo modules::run('budget/history/get_history'); ?>

        </div>

    </div>

</div>


<div class="modal fade" id="historyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات </h4>
            </div>
            <form class="form-horizontal" id="history_from" method="post" action="<?=base_url('budget/history/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">


                    <div class="form-group">
                        <label class="col-sm-4 control-label">السنة </label>
                        <div class="col-sm-4">
                            <input type="hidden" name="branch" id="txt_branch">
                            <input type="hidden" name="item_no" id="txt_item_no">
                            <input type="text" name="yyear" data-val="true"  data-val-required="حقل مطلوب"   id="txt_yyear"   data-val-regex="يجب إدخال السنة صحيح" data-val-regex-pattern="^(19|20)\d{2}$" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="yyear" data-valmsg-replace="true"></span>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">المقدر </label>
                        <div class="col-sm-4">
                            <input type="text" name="estimated_value" data-val="true"  data-val-required="حقل مطلوب"   id="txt_estimated_value" data-val-regex="المدخل غير صحيح" data-val-regex-pattern="^\d*\.?\d*$" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="estimated_value" data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">إعادة تقدير فعلي النصف الأول </label>
                        <div class="col-sm-4">
                            <input type="text" name="re_estimate_f" data-val="true"  data-val-required="حقل مطلوب"   id="txt_re_estimate_f" data-val-regex="المدخل غير صحيح" data-val-regex-pattern="^\d*\.?\d*$" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="re_estimate_f" data-valmsg-replace="true"></span>

                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">إعادة تقدير النصف الثاني </label>
                        <div class="col-sm-4">
                            <input type="text" name="re_estimate_l" data-val="true"  data-val-required="حقل مطلوب"   id="txt_re_estimate_l" data-val-regex="المدخل غير صحيح" data-val-regex-pattern="^\d*\.?\d*$" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="re_estimate_l" data-valmsg-replace="true"></span>

                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">  المجموع </label>
                        <div class="col-sm-4">
                            <input type="text" name="total"    id="txt_total"  readonly class="form-control">

                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">الفعلي </label>
                        <div class="col-sm-4">
                            <input type="text" name="actual_value" data-val="true"  data-val-required="حقل مطلوب"   id="txt_actual_value" data-val-regex="المدخل غير صحيح" data-val-regex-pattern="^\d*\.?\d*$" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="actual_value" data-valmsg-replace="true"></span>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php




$scripts = <<<SCRIPT
<script>

    $(function () {

        $('#historyModal').on('shown.bs.modal', function () {
            $('#txt_item_no').focus();
        })

        $('#budget').tree();

        $('#txt_re_estimate_f,#txt_re_estimate_l').keyup(function(){
            $('#txt_total').val(parseFloat( $('#txt_re_estimate_f').val())+parseFloat($('#txt_re_estimate_l').val()));
        });

        $('#dp_branch').change(function(){
            $('#txt_branch').val($(this).val());
        });

    });

    function budget_get(id){
   if($.fn.tree.level() > 1){
        get_data('$history_page_url',{item_no:id,branch:$('#dp_branch').val()} ,function(data){

            $('#container').html(data);

        },"html");
        }
    }


    function history_create(){

if($.fn.tree.level() > 1){

        clearForm($('#history_from'));

        $('#txt_item_no').val($.fn.tree.selected().attr('data-id'));
        $('#txt_branch').val($('#dp_branch').val());
        $('#history_from').attr('action','{$create_url}');
        $('#historyModal').modal();
        }

    }

    function history_delete(){

        if(confirm('هل تريد حذف السجلات المختارة ؟!!!')){


            var url = '{$delete_url}';


            var tbl = '#historyTbl';

            var container = $('#' + $(tbl).attr('data-container'));

            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                val[i] = $(this).val();

            });


            ajax_delete_any(url,{'id[]':val,item_no:$.fn.tree.selected().attr('data-id'),__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val()},function(data){
                success_msg('رسالة','تم حذف السجلات بنجاح ..');
                container.html(data);
            });


        }

    }

    function history_get(year,item){

        get_data('{$get_url}',{year:year,item_no:item,branch : $('#dp_branch').val()},function(data){

            $.each(data, function(i,item){
                console.log('',item);
                $('#txt_item_no').val(item.ITEM_NO);
                $('#txt_yyear').val( item.YYEAR);
                $('#txt_estimated_value').val( item.ESTIMATED_VALUE);
                $('#txt_re_estimate_f').val( item.RE_ESTIMATE_F);
                $('#txt_re_estimate_l').val( item.RE_ESTIMATE_L);
                $('#txt_actual_value').val( item.ACTUAL_VALUE);
                    $('#txt_total').val( parseFloat(item.RE_ESTIMATE_F)+parseFloat(item.RE_ESTIMATE_L));




                $('#history_from').attr('action','{$edit_url}');

                resetValidation($('#history_from'));
                $('#historyModal').modal();

            });
        });
    }

    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            $('#container').html(data);
            $('#historyModal').modal('hide');

        },"html");

    });


</script>
SCRIPT;

sec_scripts($scripts);



?>


