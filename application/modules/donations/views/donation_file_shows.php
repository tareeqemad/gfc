<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 22/10/15
 * Time: 12:46 م
 */


$MODULE_NAME= 'donations';
$TB_NAME= 'donation';
$DET_TB_NAME='public_get_details_case';

$rs=$donation_data[0];
$post_url=base_url("$MODULE_NAME/$TB_NAME/change_cases");
$back_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$get_class_url =base_url('stores/classes/public_get_id');
$view_url=base_url("$MODULE_NAME/$TB_NAME/view");
$select_items_url=base_url("stores/classes/public_index");
//$change_url=base_url("stores/classes/public_index");
echo AntiForgeryToken();
?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>
    </div>
    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم المسلسل</label>
                        <div  class="form-control">
                            <?=$rs['DONATION_FILE_ID'] ;?>    </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم المنحة - من الجهة المانحة</label>
                        <div class="form-control"><?=$rs['DONATION_ID'] ;?>
                              </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الجهة المانحة</label>
                        <div class="form-control"><?=$rs['DONATION_NAME'] ;?>
                              </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم المنحة</label>
                        <div class="form-control"><?=$rs['DONATION_LABEL'] ;?>
                              </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label">حساب المنحة</label>
                        <div class="form-control">
                            <?=$rs['DONATION_ACCOUNT'].":".$rs['DONATION_ACCOUNT_NAME'] ;?>
                             </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الجهة الممولة</label>
                        <div class="form-control">
                            <?=$rs['DONOR_NAME'] ;?> </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">نوع الجهة الممولة </label>
                        <div class="form-control"><?=$rs['DONATION_TYPE_NAME']?>
                       </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">طبيعة المنحة</label>
                        <div class="form-control"><?=$rs['DONATION_KIND_NAME']?>
                                           </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">  عملة المنحة  </label>
                        <div class="form-control"><?= $rs['CURR_ID_NAME'] ?>

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> سعر العملة</label>
                        <div class="form-control"><?=$rs['CURR_VALUE']; ?>
                            </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">قيمة المنحة</label>
                        <div class="form-control"><?=$rs['DONATION_VALUE'] ;?>
                             </div>
                    </div>

                    <div class="form-group col-sm-1">

                        <label class="control-label">شروط المنحة</label>
                        <div class="form-control"><?=$rs['CONDITIONS_NAME']?>
                            </div>
                    </div>

                    <div class="form-group col-sm-2">

                        <label class="control-label">مخزن المنحة</label>
                        <div class="form-control">
                          <?=$rs['STORE_ID_NAME']?>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">تاريخ اعتماد المنحة</label>
                        <div class="form-control">
                            <?=$rs['DONATION_APPROVED_DATE'] ;?>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">تاريخ نهاية المنحة</label>
                        <div class="form-control">
                            <?=$rs['DONATION_END_DATE'] ;?>
                        </div>
                    </div>
                    <div class="form-group col-sm-10">
                        <label class="control-label"> البيان </label>
                        <div class="form-control">
                            <?=$rs['NOTES']?>

                        </div></div>
                    <div style="clear: both"></div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">قيمة مصاريف اخرى</label>
                        <div class="form-control"><?=$rs['OTHER_EXPENSES'] ;?>
                                </div>
                    </div>
                    <div class="form-group col-sm-8">
                        <label class="control-label">  بيان المصاريف الاخرى</label>
                        <div class="form-control">
                           <?=$rs['OTHER_EXPENSES_NOTE']?>
                        </div></div>


                    <div style="clear: both"></div>
                    <input type="hidden" id="h_data_search" />
                    <div   class="details">
                        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME",  $rs['DONATION_FILE_ID']); ?>
                    </div>
                </div>

                <div class="modal-footer">
                    <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
                    if (  HaveAccess($post_url) && ($rs['DONATION_FILE_CASE']==2  )) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ التغييرات</button>
                    <?php endif; ?>



                </div>

            </form>
        </div>
    </div>
<?php




$scripts = <<<SCRIPT
<script type="text/javascript">



 $('select[name="class_case[]"]').change(function(e){
    var trr=$(this).closest('tr');
 var class_casee=trr.find('input[name="class_case[]"]').val();
 var old_class_casee=trr.find('input[name="old_class_case[]"]').val();
 var unit=trr.find('input[name="replace_class_unit[]"]').val();
  var replace_class_idd=trr.find('input[name="replace_class_id[]"]').val();
   var rem_amountt=trr.find('input[name="rem_amount[]"]').val();
    var orderderdd=trr.find('input[name="orderderd[]"]').val();
    if( ($(this).val()==2) && (old_class_casee==1)){
    if(rem_amountt>0)
       $(this).val(2);
    else{
     danger_msg('تحذير','الكمية المتبقية صفر فلا يمكن الاستبدال');
      $(this).val(1);
     }
 }else   if( ($(this).val()==4) && (old_class_casee==1)){
  if(orderderdd==0)
    $(this).val(4);
    else{
     danger_msg('تحذير','الكمية الموردة أكبر من صفر فلا يمكن الإلغاء');
      $(this).val(1); }
 } else  if( ($(this).val()==3) && (old_class_casee==1)){
    if ((rem_amountt>0)&& (orderderdd>0))
    $(this).val(3);
    else{
     danger_msg('تحذير',' لا يمكن إتمام عملية الإيقاف ');
     $(this).val(1);}

 }


 });

    $('input[name="replace_class_id[]"]').bind('keyup', '+', function(e) {
   var trr=$(this).closest('tr');
    var old_class_casee=trr.find('input[name="old_class_case[]"]').val();
    var class_casee=trr.find('select[name="class_case[]"]').val();
    if(old_class_casee==1 && class_casee==2){
   $(this).val( $(this).val().replace('+', ''));
    var id_v=$(this).closest('tr').find('input[name="replace_class_id[]"]').attr('id');
  _showReport('$select_items_url/'+id_v+ $('#h_data_search').val());}
           });

    $('input[name="replace_class_id_name[]"]').bind("focus",function(e){
     var trr=$(this).closest('tr');
    var old_class_casee=trr.find('input[name="old_class_case[]"]').val();
     var class_casee=trr.find('select[name="class_case[]"]').val();
    if(old_class_casee==1 && class_casee==2){
        _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val());
        }
    });


                $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$view_url}/'+parseInt(data));
                }else if(data==1){
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
</script>

SCRIPT;

sec_scripts($scripts);

?>