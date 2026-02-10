<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 09/02/20
 * Time: 11:57 ص
 */

$MODULE_NAME = 'training';
$TB_NAME = 'Paidtraining';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create_accept");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create_accept' : $action));
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_accept");
$RENEW = 'public_get_trainee_renew';
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;

$exp_date = str_replace('/', '-', $HaveRs ?$rs['END_DATE']:"");
$checkReadOnly = "";
if ( $HaveRs && (strtotime($exp_date) < strtotime(date('d-m-Y')) )  ) {
    $checkReadOnly = "readonly";
}
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
                    <legend>بيانات المتدرب</legend>
                    <br>
                    <div class="form-group">
                        <input type="hidden" name="ser" id="txt_ser" value="<?= $HaveRs ? $rs['SER'] : "" ?>">
                        <label class="col-sm-1 control-label">رقم المتدرب</label>
                        <div class="col-sm-2">
                            <input type="text" readonly name="ser_train"
                                   value="<?= $HaveRs ? $rs['SER_TRAIN'] : "" ?>"
                                   id="txt_ser_train" class="form-control">
                        </div>
                        <label class="col-sm-1 control-label">رقم الهوية</label>
                        <div class="col-sm-2">
                            <input type="text" name="id_check" <?php if($HaveRs){echo "readonly";} ?>
                                   value="<?= $HaveRs ? $rs['ID'] : "" ?>"
                                   id="txt_id_check" class="form-control">
                        </div>

                        <label class="col-sm-1 control-label">اسم المتدرب</label>
                        <div class="col-sm-2">
                            <input type="text" readonly name="name"
                                   value="<?= $HaveRs ? $rs['NAME'] : "" ?>"
                                   id="txt_name" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">المقر</label>
                        <div class="col-sm-2">
                            <select name="branch" id="dp_branch" class="form-control sel2">
                                <option value="0">_______</option>
                                <?php foreach($branches as $row) :?>
                                    <?php
                                    if($row['NO']<>1)
                                    { ?> <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==((count(@$rs)>0)?@$rs['BRANCH']:0)){ echo " selected"; } ?> > <?= $row['NAME'] ?> </option>
                                    <?php
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <label class="col-sm-1 control-label">الادارة</label>
                        <div class="col-sm-2">
                            <select name="manage" id="dp_manage" class="form-control sel2">
                                <option value="0">_______</option>
                                <?php foreach($manage as $row) :?>
                                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==((count(@$rs)>0)?@$rs['MANAGE']:0)){ echo " selected"; } ?> > <?= $row['ST_NAME'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <label class="col-sm-1 control-label">مشرف التدريب</label>
                        <div class="col-sm-2">
                            <select name="responsible_emp" id="dp_responsible_emp" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($emp_no_cons as $row) :?>
                                    <option value="<?=$row['EMP_NO']?>" <?PHP if ($row['EMP_NO']==((count(@$rs)>0)?@$rs['RESPONSIBLE_EMP']:0)){ echo " selected"; } ?>  >
                                        <?= $row['EMP_NO'].": ".$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">ملاحظات</label>
                        <div class="col-sm-9">
                            <textarea data-val-required="حقل مطلوب"   style="margin: 0px 0px 0px 162.879px; width: 1308px; height: 234px;"
                                      class="form-control" name="notes" rows="3"
                                      id="txt_notes"><?= $HaveRs ? $rs['NOTES'] : "" ?></textarea>
                        </div>
                        <input type="hidden" name="end_date" value="<?= $HaveRs ?$rs['END_DATE']:"" ?>">
                    </div>

                    <?php if(!$isCreate) { ?>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">المرفقات</label>
                            <div class="col-sm-2">
                                <?php echo modules::run('attachments/attachment/index',$HaveRs ? $rs['SER'] : 0,'training'); ?>
                            </div>
                        </div>
                    <?php  } ?>

                    <fieldset class="field_set">
                        <legend>بيانات العقد الأول</legend>

                        <div class="form-group">

                            <label class="col-sm-1 control-label">تاريخ بداية التعاقد </label>
                            <div class="col-sm-2">
                                <input <?=$checkReadOnly ?> type="text" data-val-required="حقل مطلوب" data-type="date"
                                                            data-date-format="DD/MM/YYYY" name="start_date"
                                                            id="txt_start_date" class="form-control"
                                                            value="<?= $HaveRs ? $rs['START_DATE'] : "" ?>">
                            </div>
                            <label class="col-sm-1 control-label">مدة التدريب - شهر</label>
                            <div class="col-sm-2">
                                <input <?=$checkReadOnly ?> type="text"  name="training_period"
                                                            value="<?= $HaveRs ? $rs['TRAINING_PERIOD'] : "" ?>"
                                                            id="txt_training_period" class="form-control">
                            </div>
                            <label class="col-sm-1 control-label">قيمة الحافز - شيكل</label>
                            <div class="col-sm-2">
                                <input  <?=$checkReadOnly ?> type="text"  name="incentive_value"
                                                             value="<?= $HaveRs ? $rs['INCENTIVE_VALUE'] : "" ?>"
                                                             id="txt_incentive_value" class="form-control">
                            </div>
                        </div>

                    </fieldset>

                    <?php
                    $check =  @$rs['ALL_PERIOD'] > 0 ?  "" : "hidden"  ;
                        if( $HaveRs){
                        $exp_date = str_replace('/', '-', $HaveRs ?$rs['END_DATE']:"");
                        if (  (strtotime($exp_date) < strtotime(date('d-m-Y')) ) ) : ?>
                            <fieldset <?= $check?>  id="renew_div" class="field_set"    >
                                <legend >تجديد العقد</legend>
                                <div class="form-group">
                                    <?php echo modules::run("$MODULE_NAME/$TB_NAME/$RENEW", $HaveRs ? $rs['SER'] : 0  ); ?>
                                </div>
                            </fieldset>
                        <?php endif; } ?>

                    <div class="modal-footer">
                        <?php
                        $exp_date = str_replace('/', '-', $HaveRs ?$rs['END_DATE'] : "");
                        if ( @$rs['ALL_PERIOD'] > 0 or  HaveAccess($post_url) && (strtotime($exp_date) > strtotime(date('d-m-Y')) ) or $isCreate ) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>

                        <?php
                        if( $HaveRs){

                        $exp_date = str_replace('/', '-', $HaveRs ?$rs['END_DATE']:"");
                        if (  (strtotime($exp_date) < strtotime(date('d-m-Y')) ) && $rs['ALL_PERIOD'] == 0 ) : ?>
                            <button id="renew_btn" type="button" onclick="javascript:renew_trainee(<?= $HaveRs ? $rs['SER'] : 0 ?>);"
                                    data-toggle="modal" data-target="#showmsgrec"  class="btn btn-danger">تجديد للمتدرب</button>
                        <?php endif;} ?>
                        <?php if(@$rs['ALL_PERIOD'] > 0) ?>
                        <button id="renew_save_btn" type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                    </div>
                </fieldset>
            </div>
        </form>
    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
        $('.sel2').select2();

        $('#renew_save_btn').hide();
        $('#renew_btn').click(function(e){
            $('#renew_save_btn').show();
            $('#renew_btn').hide();


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


        function checkidno(idno){
            MultID  = [1,2,1,2,1,2,1,2];
            SumID=0;i=0;X=0;
            r = ""+idno;//.replace(/\s/g,'');
            intRegex = /^\d+$/;
            if (r.length==9 && (intRegex.test(r)) )
            {
                for (var i=0;i<MultID.length;i++)
                {
                    x=MultID[i] * r[i];
                    if (x>9)
                        x=parseInt(x.toString()[0]) + parseInt(x.toString()[1]);
                    SumID=SumID+x;
                }
                if (SumID % 10 != 0)
                    SumID = ( 10 * (Math.floor(SumID / 10) + 1 )) - SumID
                else
                    SumID = 0;
                if (SumID == r[8])
                    return true;
                else
                    return false;
            }
            else
                return false;
        }

        $('#txt_id_check').on('change',function () {
            if(checkidno($('#txt_id_check').val()))
            {
                $.ajax({
                    url:"http://192.168.0.171:801/apis/GetData/"+$('#txt_id_check').val(),
                    type: "GET",
                    data:{},
                    dataType:'json',
                    success: (function (data) {
                        $('#txt_name').val(data.DATA[0].FNAME_ARB + " "+ data.DATA[0].SNAME_ARB+ " "+ data.DATA[0].TNAME_ARB+ " "+ data.DATA[0].LNAME_ARB);
                    }),
                    error: (function (e) {
                        alert('ER');

                    })
                });
            }
            else
            {
                toastr.error('ادخال خاطئ لرقم الهوية');
                $('#txt_name').val('');
                $('#txt_id_check').val('');

            }
        });

</script>

SCRIPT;
sec_scripts($scripts);
?>