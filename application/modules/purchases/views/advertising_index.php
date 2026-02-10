<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 26/09/18
 * Time: 08:24 ص
 */

$MODULE_NAME= 'purchases';
$TB_NAME= 'advertising';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption">إدارة الإعلانات </div>

        <ul><?php
            if(HaveAccess($create_url)) echo "<li><a onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";
            if(HaveAccess($get_url) and HaveAccess($edit_url)) echo "<li><a onclick='javascript:{$TB_NAME}_get(get_id());' href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>";
            if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>";
            ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
        </div>
    </div>

</div>


<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات الإعلان</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">


                    <div class="form-group">
                        <label class="col-sm-4 control-label">عنوان الإعلان</label>
                        <div class="col-sm-7">
                            <input type="text" data-val="true"   data-val-required="حقل مطلوب" name="title" id="txt_title" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="title" data-valmsg-replace="true"></span>
                            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="adver_no" id="txt_adver_no" class="form-control">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"> نص الإعلان </label>
                        <div class="col-sm-7">
                            <textarea data-val="true" class="form-control" dir="rtl"  data-val-required="حقل مطلوب"  name="body" id="txt_body"></textarea>
                            <span class="field-validation-valid" data-valmsg-for="body" data-valmsg-replace="true"></span>


                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">نوع الإعلان</label>
                        <div class="col-sm-7">
                            <select name="adver_type" id="dp_adver_type" class="form-control">
                                <?php foreach($adver_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php
$scripts = <<<SCRIPT
<script type="text/javascript">




        $('#{$TB_NAME}_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });



    $(function () {
        $('#{$TB_NAME}Modal').on('shown.bs.modal', function () {
            $('#txt_title').focus();
        });
    });

    function get_id(){

        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_from'));

        $('#{$TB_NAME}_from').attr('action','{$create_url}');

        $('#{$TB_NAME}Modal').modal();
    }

    function {$TB_NAME}_get(id){


        get_data('{$get_url}',{id:id},function(data){
        console.log(data);

            $.each(data, function(i,item){
                $('#txt_adver_no').val('');
                $('#txt_title').val( '');
                $('#txt_body').val( '');
                $('#dp_adver_type').val( '');

               $('#txt_adver_no').val(item.ADVER_NO);
                $('#txt_title').val( item.TITLE);
                $('#txt_body').val( item.BODY);
                $('#dp_adver_type').val(item.ADVER_TYPE);
                $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));

                $('#{$TB_NAME}Modal').modal();

            });
        });
    }

    function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد حذف '+val.length+' سجلات وحذف تفاصيلها ؟!!')){
                ajax_delete(url, val ,function(data){
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                   // container.html(data);
                    window.location.reload();
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
          //  container.html(data);
           window.location.reload();
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
        window.location.reload();
    });


</script>

SCRIPT;

sec_scripts($scripts);

?>
