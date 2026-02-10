<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/10/15
 * Time: 09:03 ص
 */
$MODULE_NAME= 'purchases';
$TB_NAME= 'purchase_class';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$rs=($isCreate)? array() : $purchase_class_data;
$fid = (count($rs)>0)?$rs['SER']:0;
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>

    <div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
    <div class="modal-body inline_form">
    <hr/>
    <fieldset>
        <legend>  بيانات الصنف </legend>


        <div class="form-group col-sm-1">
            <label class="control-label">المسلسل</label>
            <div>
                <input type="text" name="ser" value="<?php if (count($rs)>0)echo $rs['SER'] ;?>"  readonly  data-type="text"   id="txt_ser" class="form-control ">
            </div>
        </div>

        <div class="form-group col-sm-3">
            <label class="control-label">اسم الصنف</label>
            <div>
                <input type="text" name="class_name_ar" value="<?php if (count($rs)>0)echo $rs['CLASS_NAME_AR'] ;?>"    data-type="text"   id="txt_class_name_ar" class="form-control " data-val="true" data-val-required="حقل مطلوب">
            </div>
        </div>
        <div style="clear: both;">
        <div class="form-group col-sm-4">
            <label class="control-label"> وصف الصنف </label>
            <div>
                <textarea class="form-control" name="calss_description" rows="5" id="txt_calss_description" data-type="text" data-val="true" data-val-required="حقل مطلوب"><?php if (count($rs)>0)echo $rs['CALSS_DESCRIPTION'] ;?></textarea>

            </div>
        </div>


        <div style="clear: both;">

        <div class="form-group col-sm-4">
            <label class="control-label"> الشروط و الملاحظات </label>
            <div>
                <textarea class="form-control" name="notes" rows="5" id="txt_notes" data-type="text" data-val="true" data-val-required="حقل مطلوب"><?php if (count($rs)>0)echo $rs['NOTES'] ;?></textarea>
               </div>
        </div>

    </fieldset>

    <div class="modal-footer">
        <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
        if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>
        <?php if ($isCreate): ?>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        <?php   endif; ?>


        <?php if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ))) : ?>
            <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">تحويل الى المشتريات</button>
        <?php endif; ?>


    </div>
    <div style="clear: both;">
        <input type="hidden" id="h_data_search" />
    </div>
    <div style="clear: both;">
        <?php echo modules::run('settings/notes/public_get_page',(count($rs)>0)?$rs['SER']:0,'purchase_class'); ?>
        <?php echo (count($rs)>0)?  modules::run('attachments/attachment/index',$rs['SER'],'purchase_class') : ''; ?>
    </div>
    </form>
    </div>
    </div>
<?php echo modules::run('settings/notes/index'); ?>

<?php

$notes_url =notes_url();


$scripts = <<<SCRIPT
<script type="text/javascript">
var action_type;
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }


 function return_adopt(type){

            action_type = type;
            $('#notesModal').modal();

       }

        function apply_action(){

                if(action_type == 1){

                    get_data('{$adopt_url}',{id:{$fid}},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم العملية بنجاح ..');
                            reload_Page();
                    },'html');


                }

                get_data('{$notes_url}',{source_id:{$fid},source:'purchase_class',notes:$('#txt_g_notes').val()},function(data){

                    $('#txt_g_notes').val('');
                },'html');
                $('#notesModal').modal('hide');


        }


$(document).ready(function() {

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ البيانات ؟'))
        {
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                     get_to_link('{$get_url}/'+parseInt(data));
                   }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });


});

</script>

SCRIPT;

sec_scripts($scripts);

?>

