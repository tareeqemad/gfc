<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 17/09/19
 * Time: 09:43 ص
 */

$MODULE_NAME = 'Destruction';
$TB_NAME = 'INPUT_DOCUMENTS';
$get_url =base_url('Destruction/INPUT_DOCUMENTS/get_id');

$creates_url=base_url('Destruction/INPUT_DOCUMENTS/create');
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . (@$action == 'index' ? 'create' : @$action));
$adopt_url=base_url('Destruction/INPUT_DOCUMENTS/adopt');
$unadopt_url=base_url('Destruction/INPUT_DOCUMENTS/unadopt');


if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;


?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <?php //if (HaveAccess($creates_url)): ?>
            <li><a href="<?= $creates_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php //endif; ?>
        </ul>



    </div>

    <div class="form-body">

        <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form"
              novalidate="novalidate">
            <fieldset>
                <legend>اتلاف مستندات الادخال </legend>
                <div class="modal-body inline_form">


                    <!------------------------رقم النموذج-------------------->

                    <div class="form-group col-sm-3">
                        <label class="control-label"> رقم النموذج </label>
                        <div>
                            <input type="text"  name="ser"  value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                                   readonly  id="txt_MODEL_NO" class="form-control ltr" >
                        </div>
                    </div>
                    <!-------------------تاريخ القيد--------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">التاريخ</label>
                        <div>
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="model_date"
                                   value="<?= $HaveRs ? $rs['MODEL_DATE'] : "" ?>"
                                   id="txt_MODEL_DATE"  class="form-control ltr">
                        </div>
                    </div>
                    <!----------------------نوع اللجنة------------------->

                    <div class="form-group col-sm-3">
                        <label class="control-label">نوع اللجنة</label>

                        <select name="class_input_class_type" id="dp_class_input_class_type" class="form-control">
                            <option></option>
                            <?php foreach($class_input_class_type as $row) :?>
                                <option  value="<?= $row['COMMITTEES_ID'] ?>" <?PHP if ($row['COMMITTEES_ID']==((count(@$rs)>0)?@$rs['COMMITTEES_NO']:0)){ echo " selected"; } ?> ><?php echo $row['COMMITTEES_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>


                <!------------------- الملاحظات------------------->

                <div class="form-group col-sm-5">
                    <label class="control-label">الملاحظات </label>
                    <div>

                        <textarea data-val-required="حقل مطلوب"
                                  class="form-control" name="notes" rows="4"
                                  value="<?= $HaveRs ? $rs['MODEL_DATE'] : "" ?>"
                                  id="txt_notes"><?= $HaveRs ? $rs['NOTES'] : "" ?></textarea>
                    </div>
                </div>
                <br>
                <!------------------------------------------------------->
                <div>
                    <?php echo $HaveRs ? modules::run('attachments/attachment/index', $rs['SER'], 'DMG_MODELS_TB') : ''; ?>
                </div>

                <!----------------------------------------------------->
    </div>


    </fieldset>


    <div id="msg_container"></div>
    <fieldset>
        <legend>نموذج اتلاف مستندات الادخال</legend>
        <div id="container">

            <?php echo modules::run('Destruction/INPUT_DOCUMENTS/public_list_input_documents', $HaveRs ? $rs['SER'] : 0   ); ?>


        </div>
    </fieldset>
    </form>

</div>

<div class="modal-footer">
    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
    <?php  if ( HaveAccess(@$adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  (count($rs)>0)? $rs['HAVE_TARGET']==0 : '' )) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(2);' class="btn btn-success">اعتماد  </button>
    <?php endif; ?>

    <?php  if ( HaveAccess(@$adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  (count($rs)>0)? $rs['HAVE_TARGET']==0 : '' )) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(3);' class="btn btn-">تحويل اللجنة  </button>
    <?php endif; ?>
    <?php  if ( HaveAccess(@$un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  (count($rs)>0)? $rs['HAVE_TARGET']==0 : '' )) : ?>
        <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(1);" class="btn btn-danger">الغاء الاعتماد</button>
    <?php endif; ?>
</div>





<?php


$scripts = <<<SCRIPT

<script>

     $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $('#{$TB_NAME}_form');
            ajax_insert_update(form,function(data){
            console.log(data);
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







function return_adopt (type){

                if(type == 1){
					   get_data('{$adopt_url}',{id: $('#txt_indecator_ser').val()},function(data){
           if(data =='1')
                            success_msg('رسالة','تم اعتماد بنجاح ..');
                           reload_Page();
                    },'html');
				                }
                            if(type == 2){
                    get_data('{$unadopt_url }',{id:$('#txt_indecator_ser').val()},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
                            reload_Page();
                    },'html');
                    }
                }

</script>
SCRIPT;
sec_scripts($scripts);
?>



