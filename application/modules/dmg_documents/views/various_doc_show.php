<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/07/19
 * Time: 10:23 ص
 */


$MODULE_NAME = 'dmg_documents';
$TB_NAME = 'various_doc';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create_details");
$creates_url = base_url("$MODULE_NAME/$TB_NAME/create");
$DMG_DOC_TB='public_get_dmg_var_doc';
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_commitees_url= base_url("$MODULE_NAME/$TB_NAME/adopt_commitees");
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME/unadopt");

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;


?>


    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if (HaveAccess($creates_url)): ?>
                <li><a href="<?= $creates_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
                <?php endif; ?>
            </ul>

        </div>

    </div>


    <div class="form-body">

        <div id="container">
            <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?php echo $post_url ?>" role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">

                    <fieldset class="field_set">
                        <legend>اتلاف مستندات متنوعة</legend>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">رقم النموذج</label>
                            <input type="hidden" name="model_type"  value="<?= $HaveRs ? $rs['MODEL_TYPE'] : "" ?>">
                            <div class="col-sm-2">
                                <input type="text" readonly name="ser" value="<?= $HaveRs ? $rs['SER'] : "" ?>" id="txt_ser" class="form-control">
                            </div>




                            <label class="col-sm-1 control-label">التاريخ</label>
                            <div class="col-sm-2">
                                <input type="text" data-val-required="حقل مطلوب" data-type="date"
                                       data-date-format="DD/MM/YYYY" name="model_date"
                                       id="txt_model_date" class="form-control"
                                       value="<?= @$rs['MODEL_DATE'] ?>">
                            </div>
                        </div>




                        <div class="form-group">

                            <label  class="col-sm-1 control-label">نوع اللجنة</label>
                            <div class="col-sm-2">

                                <select name="class_input_class_type" id="dp_class_input_class_type" class="form-control sel2">
                                    <?php foreach($class_input_class_type as $row) :?>
                                        <option  value="<?= $row['COMMITTEES_ID'] ?>" <?PHP if ($row['COMMITTEES_ID']==((count(@$rs)>0)?@$rs['COMMITTEES_NO']:0)){ echo " selected"; } ?> ><?php echo $row['COMMITTEES_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">ملاحظات</label>
                            <div class="col-sm-9">
                                <textarea data-val-required="حقل مطلوب"
                                          class="form-control" name="notes" rows="3"
                                          id="txt_notes"><?php echo @$rs['NOTES'] ;?></textarea>
                            </div>
                        </div>

                        <?php if(!$isCreate){ ?>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">المرفقات</label>
                                <div class="col-sm-2">
                                    <?php echo modules::run('attachments/attachment/index',$rs['SER'],'dmg_documents'); ?>
                                </div>

                            </div>
                        <?php  } ?>



                        <fieldset class="field_set"  >
                            <legend >نموذج اتلاف مستندات متنوعة</legend>
                            <div class="form-group">
                                <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DMG_DOC_TB", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>
                            </div>
                        </fieldset>



                        <div class="modal-footer">
                            <?php
                            if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) ) AND  ( $isCreate || @$rs['ENTRY_USER']?$this->user->id : false )  ) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>

                            <?php  if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  $this->user->branch == $rs['BRANCH_NO']/*$rs['ISSUE_BRANCH']*/ )) : ?>
                                <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
                            <?php  endif; ?>

                            <?php  if ( HaveAccess($un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  $this->user->branch == $rs['BRANCH_NO'] )) : ?>
                                <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(3);" class="btn btn-danger">الغاء الاعتماد</button>
                            <?php  endif; ?>

                            <?php  if ( HaveAccess($adopt_commitees_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  $this->user->branch == $rs['BRANCH_NO'] )) : ?>
                                <button type="button"  id="btn_comadopt" onclick="javascript:return_adopt(2);" class="btn btn-warning">تحويل الى اللجنة</button>
                            <?php  endif; ?>
                        </div>

                    </fieldset>

                </div>

            </form>

        </div>
    </div>






<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

$('.sel2').select2();

 function return_adopt (type){
                if(type == 1){
					   get_data('{$adopt_url}',{id: $('#txt_ser').val()},function(data){
                                if(data =='1')
                                {
                                success_msg('رسالة','تم اعتماد بنجاح ..');
                                reload_Page();
                                }
                                },'html');
                 }

                 if(type == 2){
					   get_data('{$adopt_commitees_url}',{id: $('#txt_ser').val()},function(data){
                                if(data =='1')
                                {
                                success_msg('تم التحويل للجنة بنجاح');
                                reload_Page();
                                }
                                },'html');
                 }

                 if(type == 3){
					   get_data('{$un_adopt_url }',{id:$('#txt_ser').val()},function(data){
                        if(data =='1')
                        {
                            success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
                             reload_Page();
                        }
                        },'html');
                 }
             }

 $(document).ready(function() {




		$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
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




   });

</script>

SCRIPT;
sec_scripts($scripts);
?>