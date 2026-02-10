<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 27/07/19
 * Time: 09:20 ص
 */

$MODULE_NAME = 'Destruction';
$TB_NAME = 'Bonds';
$count = 0;

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;



?>
<div class="tbl_container">


    <div id="container">
        <?php echo modules::run('Destruction/Bonds/get_id', $HaveRs ? $rs['SER'] : 0   ); ?>


    </div>

    <fieldset>
        <legend> قرار اللجنة</legend>
        <div class="modal-body inline_form">


            <!-------------------قرار الاتلاف---------------->

            <div class="form-group col-sm-3">
                <label class="control-label">قرار الاتلاف </label>

                <select name="desicion_dmg" id="dp_DESICION_DMG" class="form-control">
                    <?php foreach($desicion_dmg as $row) :?>
                        <option  <?PHP if ($row['CON_NO']==((count(@$rs)>0)?@$rs['CON_NAME']:0)) ?> ><?php echo $row['CON_NAME']  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>
            <!------------------- الملاحظات------------------->

            <div class="form-group col-sm-5">
                <label class="control-label">الملاحظات </label>
                <div>

                    <textarea data-val-required="حقل مطلوب"
                              class="form-control" name="notes" rows="4"
                              id="txt_notes"></textarea>
                </div>
            </div>
        </div>
        </fieldset>
    <fieldset>

        <fieldset class="field_set">
            <legend>أعضاء اللجنة</legend>
            <?php
            echo modules::run("Destruction/Bonds/public_get_group_receipt",@$rs['SER'] );
            ?>
        </fieldset>

    </fieldset>






</div>
<div class="modal-footer">
    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

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
                //    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
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



