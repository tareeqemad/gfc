<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/08/15
 * Time: 12:45 م
 */


$MODULE_NAME= 'technical';
$TB_NAME= 'technical_work_tree';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");

$get_details_url =base_url("$MODULE_NAME/$TB_NAME/get_details");
$save_details_url =base_url("$MODULE_NAME/$TB_NAME/save_details");

$select_tec_job_url =base_url("$MODULE_NAME/technical_jobs/public_index");

echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">

        <div class="caption">هيكلية الأعمال الفنية</div>

        <ul>
            <?php if( HaveAccess($create_url)): ?> <li><a onclick="javascript:create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_url,$edit_url)): ?><li><a  onclick="javascript:get($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li> <?php endif;?>
            <?php if( HaveAccess($delete_url)): ?><li><a  onclick="javascript:delete_work();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li> <?php endif;?>
            <?php if( HaveAccess($get_details_url)): ?><li><a  onclick="javascript:det_list($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-list"></i>المهام</a> </li> <?php endif;?>
            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-2">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>
        <?= $tree ?>

    </div>

</div>


<div class="modal fade" id="tec_work_modal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات نوع العمل</h4>
            </div>
            <form class="form-horizontal" id="tec_work_form" method="post" action="<?=$create_url?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> الأب</label>
                        <div class="col-sm-3">
                            <input type="text"  name="parent_id" readonly id="txt_parent_id" class="form-control ltr" />
                        </div>
                        <div class="col-sm-6">
                            <input type="text"  name="parent_id_name" readonly id="txt_parent_id_name" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> العمل</label>
                        <div class="col-sm-3">
                            <input type="text" name="technical_work_id" readonly id="txt_technical_work_id" class="form-control ltr">
                        </div>

                        <div class="col-sm-6">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="technical_work_name" id="txt_technical_work_name" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="technical_work_name" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">ملاحظات </label>
                        <div class="col-sm-9">
                            <input type="text" name="notes" id="txt_notes" class="form-control">
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


<div class="modal fade" id="tec_work_jobs_modal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> المهام التابعة للعمل </h4>
            </div>
            <form class="form-horizontal" id="tec_work_jobs_form" method="post" action="<?=$save_details_url?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <input type="hidden" id="h_technical_work_id" name="technical_work_id" value="">

                    <table class="table" id="details_tb" data-container="container">
                        <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">رقم المهمة</th>
                            <th style="width: 30%">اسم المهمة</th>
                            <th style="width: 50%">ملاحظات</th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>
                                <?php if ( HaveAccess($save_details_url) ) { ?>
                                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                                <?php } ?>
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>

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
        $('#tec_works').tree();
    });

    var count = 0;

    function create(){
        clearForm($('#tec_work_form'));
        if($(".tree span.selected").length <= 0 || $.fn.tree.level() >= 3){

            if($.fn.tree.level() >= 3){
                warning_msg('تحذير ! ','غير مسموح بإدراج حساب جديد ..');
            }else{
                warning_msg('تحذير ! ','يجب إختيار الحساب الأب ..');
            }

            return;
        }

        var parentId =$.fn.tree.selected().attr('data-id');
        var productionId= $.fn.tree.lastElem().attr('data-id');
        var parentName = $('.tree li > span.selected').text();

        $('#txt_parent_id').val(parentId);
        $('#txt_technical_work_id').val(account_id($.fn.tree.level(),productionId,parentId));
        $('#txt_parent_id_name').val(parentName);

        $('#tec_work_form').attr('action','{$create_url}');
        $('#tec_work_modal').modal();
    }

    function get(id){
        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_parent_id').val(item.PARENT_ID);
                $('#txt_technical_work_id').val( item.TECHNICAL_WORK_ID);
               //  $('#txt_parent_id_name').val($.fn.tree.nodeText(item.PARENT_ID));
                $('#txt_parent_id_name').val(item.PARENT_ID_NAME);
                $('#txt_technical_work_name').val(item.TECHNICAL_WORK_NAME);
                $('#txt_notes').val(item.NOTES);

                $('#tec_work_form').attr('action','{$edit_url}');
                $('#tec_work_modal').modal();
            });
        });
    }

    function delete_work(){
        if(confirm('هل تريد الحذف بالتأكيد ؟!!!')){
            var elem =$.fn.tree.selected();
            var id = elem.attr('data-id');
            var url = '{$delete_url}';
            ajax_delete(url,id,function(data){
                if(data == '1'){
                    $.fn.tree.removeElem(elem);
                    success_msg('رسالة','تم الحذف بنجاح ..');
                }else {
                    danger_msg('تحذير','لم يتم الحذف !!<br>'+data);
                }
            });
        }
    }

    $('#tec_work_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){

            if(isCreate){
                var obj = jQuery.parseJSON(data);
                $.fn.tree.add(obj.id+':'+(form.find('input[name="technical_work_name"]').val()),obj.id,"javascript:get('"+obj.id+"');");
            }else{
                if(data == '1'){
                    $.fn.tree.update(form.find('input[name="technical_work_name"]').val());
                }
            }

            $('#tec_work_modal').modal('hide');
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");
    });


    function det_list(id){
        if($.fn.tree.level() < 3){
            warning_msg('تحذير ! ','المهام للابناء فقط ..');
            return 0;
        }
        var cnt=0;
        $('#h_technical_work_id').val(id);
        get_data('{$get_details_url}',{id:id},function(data){
            $('#details_tb tbody').html('');
            $.each(data, function(i,item){
                cnt++;
                if(item.JOB_ID_NAME== null) item.JOB_ID_NAME= '';
                if(item.NOTE== null) item.NOTE= '';
                var row_html= '<tr> <td>'+cnt+'</td> <td><input type="hidden" name="ser[]" value="'+item.SER+'" /><input name="job_id[]" readonly class="form-control" id="h_txt_job_id_name'+cnt+'" value="'+item.JOB_ID+'" /></td> <td><input name="job_id_name[]" readonly class="form-control" id="txt_job_id_name'+cnt+'" value="'+item.JOB_ID_NAME+'" /></td> <td><input name="note[]" class="form-control" id="txt_note'+cnt+'" value="'+item.NOTE+'" /></td>';
                $('#details_tb tbody').append(row_html);
            });
            count= cnt;
            $('#tec_work_jobs_modal').modal();
            reBind();
        });
    }

    function addRow(){
        count = count+1;
        var html= '<tr> <td>'+count+'</td> <td><input type="hidden" name="ser[]" value="0" /><input name="job_id[]" readonly class="form-control" id="h_txt_job_id_name'+count+'" /></td> <td><input name="job_id_name[]" readonly class="form-control" id="txt_job_id_name'+count+'" /></td> <td><input name="note[]" class="form-control" id="txt_note'+count+'" /></td>';
        $('#details_tb tbody').append(html);
        reBind();
    }

    function reBind(){
        $('input[name="job_id_name[]"]').click("focus",function(e){
            _showReport('$select_tec_job_url/'+$(this).attr('id') );
        });
    }

    $('#tec_work_jobs_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                det_list( $('#h_technical_work_id').val() );
            }else{
                danger_msg('تحذير..',data);
            }
        },"html");
    });

</script>
SCRIPT;

sec_scripts($scripts);

?>
