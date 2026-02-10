<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/03/18
 * Time: 12:12 م
 */

$MODULE_NAME= 'issues';
$TB_NAME= 'bonds';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_details");
$count_distrbute=0;
$save_part_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_bonds");
$rs=( count($details) > 0 ? $details[0] : array()) ;

?>


<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th >استلام سند</th>

        </tr>
        </thead>

        <tbody>
<tr><td><form class="form-horizontal" id="<?=$TB_NAME?>_modal_form" method="post" action="<?=$save_part_url?>" role="form" novalidate="novalidate">
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">استلام السند</label>
                    <div class="col-sm-8">
                        <select name="delivers_status" id="dp_delivers_status" class="form-control">
                            <?php foreach($delivers_status as $row) {
                                if($row['CON_NO']!=1)
                                {
                                    ?>
                                    <option  value="<?= $row['CON_NO'] ?>" <?php if ($row['CON_NO']==$rs['DELIVERS_STATUS']){ echo " selected"; } ?>><?php echo $row['CON_NAME']  ?></option>

                                <?php }  }?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">التاريخ</label>
                    <div class="col-sm-8">
                        <input type="text" value="<?php echo $rs['DELIVERS_DATE'] ;?>" placeholder="تاريخ استلام/رفض استلام السند" data-val="true" data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"   data-val-required="حقل مطلوب" name="delivers_date" id="txt_delivers_date" class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="delivers_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">ملاحظات</label>
                    <div class="col-sm-8">
                        <input type="hidden" name="ser" id="txt_ser" value="<?php echo $rs['SER'] ;?>" />
                        <input type="text" name="delivers_notes" value="<?php echo $rs['DELIVERS_NOTES'] ;?>" id="txt_delivers_notes" value="" class="form-control" data-val="true" data-val-required="حقل مطلوب">
                        <span class="field-validation-valid" data-valmsg-for="delivers_notes" data-valmsg-replace="true"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php
                if (  HaveAccess($save_part_url)  && ($rs['DELIVERS_DATE']==''  && $rs['DELIVERS_STATUS']==1 ) ) : ?>
                    <button type="submit" data-action="submit" id="modal_submit" class="btn btn-primary">حفظ</button>
                <?php endif; ?>

            </div>
        </form></td></tr>
        </tbody>


    </table></div>




<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
  $('#dp_delivers_status').select2();

		$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');

            var form = $("#bonds_modal_form")
            ajax_insert_update(form,function(data){

                if(parseInt(data)>=1){

                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');


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


