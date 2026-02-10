<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 29/01/20
 * Time: 09:51 ص
 */


$MODULE_NAME = 'training';
$TB_NAME = 'advertisement';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$CONDITION = 'public_get_adv_condition';
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

?>


    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if (HaveAccess($create_url)): ?>
                    <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
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
                        <legend>اعلان وظيفة مدرب</legend>
                        <br>
                        <input type="hidden"  name="ser1" value="<?= $HaveRs ? $rs['SER'] : "" ?>" id="txt_ser1">

                        <div class="form-group">
                            <label class="col-sm-1 control-label">رقم الاعلان</label>
                            <div class="col-sm-2">
                                <input type="text" readonly name="adver_id"
                                       value="<?= $HaveRs ? $rs['ADVER_ID'] : "" ?>"
                                       id="txt_adver_id" class="form-control">
                            </div>

                            <label class="col-sm-1 control-label">نوع الاعلان</label>
                            <div class="col-sm-2">

                                <select name="adver_type" id="dp_adver_type" class="form-control sel2">
                                    <option></option>
                                    <?php foreach($adver_type as $row) :?>
                                        <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count(@$rs)>0)?@$rs['ADVER_TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <label class="col-sm-1 control-label">المسمى الوظيفي</label>
                            <div class="col-sm-2">
                                <input type="text"
                                       name="adver_title"
                                       id="txt_adver_title"
                                       data-val="true" value="<?= $HaveRs ? $rs['ADVER_TITLE'] : "" ?>"
                                       class="form-control ">
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="col-sm-1 control-label">تاريخ بداية الاعلان</label>
                            <div class="col-sm-2">
                                <input type="text" data-val-required="حقل مطلوب" data-type="date"
                                       data-date-format="DD/MM/YYYY" name="start_date"
                                       id="txt_start_date" class="form-control"
                                       value="<?= $HaveRs ? $rs['START_DATE'] : "" ?>">
                            </div>


                            <label class="col-sm-1 control-label">تاريخ نهاية الاعلان</label>
                            <div class="col-sm-2">
                                <input type="text" data-val-required="حقل مطلوب" data-type="date"
                                       data-date-format="DD/MM/YYYY" name="end_date"
                                       id="txt_end_date" class="form-control"
                                       value="<?= $HaveRs ? $rs['END_DATE'] : "" ?>">
                            </div>

                            <label class="col-sm-1 control-label">عدد سنوات الخبرة</label>
                            <div class="col-sm-2">
                                <input type="text"
                                       name="exp_num"
                                       id="txt_exp_num"
                                       data-val="true" value="<?= $HaveRs ? $rs['EXP_NUM'] : "" ?>"
                                       class="form-control ">
                            </div>

                        </div>

                        <div class="form-group">

                            <label class="col-sm-1 control-label">من مواليد سنة</label>
                            <div class="col-sm-2">
                                <input type="text"
                                       name="from_age_year" placeholder="<?=date('Y')?>"
                                       id="txt_from_age_year"
                                       data-val="true" value="<?= $HaveRs ? $rs['FROM_AGE_YEAR'] : "" ?>"
                                       class="form-control ">
                            </div>


                            <label class="col-sm-1 control-label">الى مواليد سنة</label>
                            <div class="col-sm-2">
                                <input type="text"
                                       name="to_age_year" placeholder="<?=date('Y')?>"
                                       id="txt_to_age_year"
                                       data-val="true" value="<?= $HaveRs ? $rs['TO_AGE_YEAR'] : "" ?>"
                                       class="form-control ">
                            </div>

                        </div>

                        <br>



                        <fieldset class="field_set"    >
                            <legend >شروط الاعلان</legend>
                            <div class="form-group">
                                <?php echo modules::run("$MODULE_NAME/$TB_NAME/$CONDITION", $HaveRs ? $rs['ADVER_ID'] : 0  ); ?>
                            </div>
                        </fieldset>



                        <div class="modal-footer">
                            <?php
                            if (HaveAccess($post_url)) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>
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


 $(document).ready(function() {

    $('#dp_model_type').on('change',function(){
			 if($(this).val()=='1'){
				 $('#document_type_div').show();
				 $('#field_set_dmg_document').show();
			 }
			 else{
                 $('#document_type_div').hide();
                 $('#field_set_dmg_document').hide();
			 }
        });


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