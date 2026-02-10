<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/01/20
 * Time: 10:13 ص
 */


$MODULE_NAME = 'dmg_documents';
$TB_NAME = 'receipts_disbursements';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create_details");
$creates_url = base_url("$MODULE_NAME/$TB_NAME/create");
$DMG_DOC_TB2='public_get_dmg_res_dis_comm';
$post_url = base_url("$MODULE_NAME/$TB_NAME/edit_comm_role" );
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_commitees_url= base_url("$MODULE_NAME/$TB_NAME/adopt_commitees");


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
                        <legend>اتلاف مستندات</legend>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">رقم النموذج</label>
                            <input type="hidden" readonly name="model_type"  value="<?= $HaveRs ? $rs['MODEL_TYPE'] : "" ?>">
                            <div class="col-sm-2">
                                <input type="text" readonly name="ser" value="<?= $HaveRs ? $rs['SER'] : "" ?>" id="txt_ser" class="form-control">
                            </div>




                            <label class="col-sm-1 control-label">التاريخ</label>
                            <div class="col-sm-2">

                                <input type="text" readonly name="model_date" value="<?= $HaveRs ? $rs['MODEL_DATE'] : "" ?>"
                                       id="txt_model_date" class="form-control">

                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-1 control-label">ملاحظات</label>
                            <div class="col-sm-9">
                                <textarea readonly data-val-required="حقل مطلوب"
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
                            <legend >نموذج اتلاف سندات قبض وصرف</legend>
                            <div class="form-group">
                                <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DMG_DOC_TB2", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>
                            </div>
                        </fieldset>



                        <fieldset class="field_set">
                            <legend>قرار اللجنة</legend>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">قرار الاتلاف</label>
                                <div class="col-sm-2">
                                    <select name="desicion_dmg" id="dp_desicion_dmg" class="form-control sel2">
                                        <option></option>
                                        <?php foreach($desicion_dmg as $row) :?>
                                            <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count(@$rs)>0)?@$rs['DESICION_DMG']:0)){ echo " selected"; } ?> >
                                                <?php echo $row['CON_NAME']  ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">ملاحظات اللجنة</label>
                                <div class="col-sm-9">
                                    <textarea  data-val-required="حقل مطلوب"
                                               class="form-control" name="notes_comm" rows="3"
                                               id="txt_notes_comm"><?php echo @$rs['NOTES_COMM'] ;?></textarea>
                                </div>
                            </div>


                        </fieldset>

                        <fieldset class="field_set">
                            <legend>أعضاء اللجنة</legend>
                            <?php
                            echo modules::run("$MODULE_NAME/$TB_NAME/public_get_group_receipt",$rs['SER'] );
                            ?>
                        </fieldset>



                        <div class="modal-footer">
                            <?php
                            if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==3 ) ) AND  ( $isCreate || @$rs['ENTRY_USER']?$this->user->id : false )  ) : ?>
                                <button type='submit' data-action="submit" id="comm_members"  class='btn btn-primary'>حفظ   </button>
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
                    location.reload(true);
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


   var count1 = 0;
   count1 = $('input[name="h_group_ser[]"]').length;

function addRowGroup(){
//if($('input:text',$('#receipt_class_input_groupTbl')).filter(function() { return this.value == ""; }).length <= 0){
    var html ='<tr><td >'+( count1+1)+' <input type="hidden" value="0" name="h_group_ser[]" id="h_group_ser_'+count1+'>"  class="form-control col-sm-3"> </td>'+
    '<td><input type="text" name="emp_no[]" data-val="true"  id="emp_no_'+count1+'"  class="form-control col-sm-8"> </td>'+
     '<td> <input type="text" name="group_person_id[]" data-val="true"   id="group_person_id_'+count1+'"  class="form-control col-sm-8">  </td>'+
      '<td><input type="text" name="group_person_date[]"  data-val="true"   id="group_person_date_'+count1+'>"   class="form-control">  </td>'+
    '<td><input type="text" name="member_note[]"  data-val="true"   id="member_note_'+count1+'>"   class="form-control">  </td>'+
     '<td><input type="checkbox" name="status['+count1+']" checked      id="status_'+count1+'"   class="form-control"></td></tr>';
    $('#receipt_class_input_groupTbl tbody').append(html);
      count1 = count1+1;
//}
}

</script>

SCRIPT;
sec_scripts($scripts);
?>