<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 05/10/15
 * Time: 09:00 ص
 */
$MODULE_NAME= 'purchases';
$TB_NAME= 'purchase_class';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));

$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/p_adopt");
$rs=($isCreate)? array() : $purchase_class_data;
$fid = (count($rs)>0)?$rs['SER']:0;
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
                            <input type="text" name="ser" value="<?php echo $rs['SER'] ;?>"  readonly  data-type="text"   id="txt_ser" class="form-control " >
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label">اسم الصنف</label>
                        <div>
                            <input type="text" name="class_name_ar" value="<?php echo $rs['CLASS_NAME_AR'] ;?>"   readonly  data-type="text"   id="txt_class_name_ar" class="form-control " data-val="true" data-val-required="حقل مطلوب" >
                        </div>
                    </div>
                    <div style="clear: both;">
                        <div class="form-group col-sm-4">
                            <label class="control-label"> وصف الصنف </label>
                            <div>
                                <textarea class="form-control" name="calss_description" rows="5" id="txt_calss_description" data-type="text" readonly data-val="true" data-val-required="حقل مطلوب"><?php echo $rs['CALSS_DESCRIPTION'] ;?></textarea>

                            </div>
                        </div>


                        <div style="clear: both;">

                            <div class="form-group col-sm-4">
                                <label class="control-label"> الشروط و الملاحظات </label>
                                <div>
                                    <textarea class="form-control" name="notes" rows="5" id="txt_notes" data-type="text" readonly data-val="true" data-val-required="حقل مطلوب"><?php echo $rs['NOTES'] ;?></textarea>
                                </div>
                            </div>

                </fieldset>
                <hr/>
                <fieldset>
                    <legend>  بيانات المشتريات </legend>
                    <div class="form-group col-sm-3">
                        <label class="control-label">اسم الصنف في المشتريات</label>
                        <div>
                            <input type="text" name="purchase_class_name" value="<?php if (count($rs)>0)echo $rs['PURCHASE_CLASS_NAME'] ;?>"     data-type="text"   id="txt_purchase_class_name" class="form-control " data-val="true" data-val-required="حقل مطلوب" >
                        </div>
                    </div>
                    <div style="clear: both;">
                        <div class="form-group col-sm-4">
                            <label class="control-label"> توصف المشتريات </label>
                            <div>
                                <textarea class="form-control" name="purchase_notes" rows="5" id="txt_purchase_notes" data-type="text"  data-val="true" data-val-required="حقل مطلوب"><?php if (count($rs)>0)echo $rs['PURCHASE_NOTES'] ;?></textarea>

                            </div>
                        </div>
<hr/>
                        <fieldset>
                        <legend>  بيانات التسعير </legend>
                            <div class="form-group col-sm-1">
                                <label class="control-label">سعر1</label>
                                <div>
                                    <input type="text" name="purchase_price1" value="<?php if (count($rs)>0)echo $rs['PURCHASE_PRICE1'];  ?>"    data-type="text"   id="txt_purchase_price1" class="form-control " data-val="true" data-val-required="حقل مطلوب"  >
                                </div>
                            </div>
                            <div class="form-group col-sm-1">
                                <label class="control-label">سعر2</label>
                                <div>
                                    <input type="text" name="purchase_price2" value="<?php if (count($rs)>0)echo $rs['PURCHASE_PRICE2'];  ?>"    data-type="text"   id="txt_purchase_price2" class="form-control " data-val="true" data-val-required="حقل مطلوب"  >
                                </div>
                            </div>
                            <div class="form-group col-sm-1">
                                <label class="control-label">سعر3</label>
                                <div>
                                    <input type="text" name="purchase_price3" value="<?php if (count($rs)>0)echo $rs['PURCHASE_PRICE3']; ?>"    data-type="text"   id="txt_purchase_price3" class="form-control " data-val="true" data-val-required="حقل مطلوب"  >
                                </div>
                            </div>

                            <div class="form-group col-sm-1">
                                <label class="control-label">المتوسط</label>
                                <div>
                                    <input type="text" name="avarage_prices" value="<?php if (count($rs)>0)echo $rs['AVG_PRICE']; ?>"  readonly   data-type="text"   id="txt_avarage_prices" class="form-control " >
                                </div>
                            </div>

                        </fieldset>
                </fieldset>
                <hr/>
                <div class="modal-footer">
                    <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
                    if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==2 ) )  ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <?php if ($isCreate): ?>
                        <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                    <?php   endif; ?>


                    <?php if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and ( (count($rs)>0)? $rs['PURCHASE_CLASS_NAME'] : '' ))) : ?>
                        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(2);' class="btn btn-success">تحويل الى الحسابات</button>
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
var calc=0;
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }


 function return_adopt(type){

            action_type = type;
            $('#notesModal').modal();

       }

        function apply_action(){

                if(action_type == 2){

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
/*if($( "#txt_purchase_price1" ).val()=='')
$( "#txt_purchase_price1" ).val('0');
if($( "#txt_purchase_price2" ).val()=='')
$( "#txt_purchase_price2" ).val('0');
if($( "#txt_purchase_price3" ).val()=='')
$( "#txt_purchase_price3" ).val('0');
$( "#txt_avarage_prices" ).val((parseInt($( "#txt_purchase_price1" ).val())+parseInt($( "#txt_purchase_price2" ).val())+parseInt($( "#txt_purchase_price3" ).val()))/3);*/
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

$( "input[name*='purchase_price']" ).change(function() {
//alert($.isNumeric( $(this).val() );
if((isNaN($(this).val())))
{
$(this).val('');
$(this).focus();
alert('ادخال خاطئ يتوجب عليك ادخال رقم');
return;
}
else
{
//calc+=parseInt($(this).val());
if($( "#txt_purchase_price1" ).val()=='')
$( "#txt_purchase_price1" ).val('0');
if($( "#txt_purchase_price2" ).val()=='')
$( "#txt_purchase_price2" ).val('0');
if($( "#txt_purchase_price3" ).val()=='')
$( "#txt_purchase_price3" ).val('0');
$( "#txt_avarage_prices" ).val((parseInt($( "#txt_purchase_price1" ).val())+parseInt($( "#txt_purchase_price2" ).val())+parseInt($( "#txt_purchase_price3" ).val()))/3);
}
});

});

</script>

SCRIPT;

sec_scripts($scripts);

?>

