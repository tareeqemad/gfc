<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/12/15
 * Time: 12:19 م
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'electricity_layers';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$case_url=base_url("$MODULE_NAME/$TB_NAME/edit_case");

$group_select_url=base_url("$MODULE_NAME/feeder_groups/public_index");
$group_create_url=base_url("$MODULE_NAME/$TB_NAME/group_create");
$group_delete_url=base_url("$MODULE_NAME/$TB_NAME/group_delete");

$post_url= $group_create_url;
$HaveRs = true;
$rs= $layer_data[0];

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الشريحة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['LAYER_ID']:''?>" name="layer_id" id="txt_layer_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نظام توزيع الأحمال</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['ELECTRICITY_LOAD_SYSTEM_NAME']:''?>" name="electricity_load_system" id="txt_electricity_load_system" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم الشريحة </label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['LAYER_NAME']:''?>" name="layer_name" id="txt_layer_name" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">حالة الشريحة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['LAYER_CASE_NAME']:''?>" name="layer_case" id="txt_layer_case" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-5">
                    <label class="control-label">وصف الشريحة </label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['NOTES']:''?>" name="notes" id="txt_notes" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ التعديل </label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['UPDATE_DATE']:''?>" name="update_date" id="txt_update_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المستخدم</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['UPDATE_USER_NAME']:''?>" name="update_user" id="txt_update_user" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>
                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", ($HaveRs)?$rs['LAYER_ID']:0  ); ?>
                <div style="clear: both"></div>
                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_det_groups", ($HaveRs)?$rs['LAYER_ID']:0  ); ?>
            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ المجموعات</button>
                <?php endif; ?>
                <?php if ( HaveAccess($case_url) and $HaveRs and $rs['LAYER_CASE']==0 ) : ?>
                    <button type="button" onclick="javascript:edit_case();" class="btn btn-success"> <i class="glyphicon glyphicon-transfer"></i> تفعيل نظام الشريحة </button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    var count = 0;
    if({$rs['COUNT_DET']}==0)
        count = 0;
    else
        count = {$rs['COUNT_DET']} -1;

    reBind();

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ المجموعات ؟!';
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

    function group_addRow(){
        count = count+1;
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="g_ser[]" value="0" /> <input name="group_id_name[]" readonly class="form-control" id="txt_group_id'+count+'" /> <input type="hidden" name="group_id[]" id="h_txt_group_id'+count+'" /></td> <td><i class="glyphicon glyphicon-remove remove_group"></i></td> <td></td> </tr>';
        $('#group_details_tb tbody').append(html);
        reBind(1);
    }

    function reBind(s){
        if(s==undefined)
            s=0;

        $('input[name="group_id_name[]"]').click("focus",function(e){
            var tr= $(this).closest('tr');
            var g_ser= tr.find('input[name="g_ser[]"]').val();
            if(g_ser==0)
                _showReport('$group_select_url/'+$(this).attr('id'));
        });

        $('.remove_group').click(function(){
            var tr = $(this).closest('tr');
            tr.find('input[name="group_id_name[]"]').val('');
            tr.find('input[name="group_id[]"]').val('');
        });
    }

    $('.delete_group').click(function(){
        if(confirm('هل تريد بالتأكيد حذف هذه المجموعة؟!!')){
            var tr= $(this).closest('tr');
            var g_ser= tr.find('input[name="g_ser[]"]').val();
            get_data('{$group_delete_url}', {g_ser:g_ser}, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم الحذف بنجاح..');
                    tr.remove();
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    });

    function edit_case(){
        if(confirm( 'سيتم تفعيل نظام {$rs['ELECTRICITY_LOAD_SYSTEM_NAME']}، والغاء تفعيل الانظمة الاخرى!!' )){
            var values= {electricity_load_system: "{$rs['ELECTRICITY_LOAD_SYSTEM']}" };
            get_data('{$case_url}', values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم التفعيل بنجاح..');
                    $('button').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
