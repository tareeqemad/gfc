<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/02/20
 * Time: 09:52 ص
 */


$MODULE_NAME = 'training';
$TB_NAME = 'unPaidTrain';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

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
                <legend>بيانات المتدرب</legend>
                <br>

                <div class="form-group">

                    <label class="col-sm-1 control-label">رقم المتدرب</label>
                    <div class="col-sm-2">
                        <input type="text" readonly name="ser"
                               value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                               id="txt_ser" class="form-control">
                    </div>
                </div>
                <div class="form-group">

                    <label class="col-sm-1 control-label">رقم الهوية</label>
                    <div class="col-sm-2">
                        <input type="text" name="id_check"
                               value="<?= $HaveRs ? $rs['ID'] : "" ?>"
                               id="txt_id_check" class="form-control">
                    </div>

                    <label class="col-sm-1 control-label">اسم المتدرب</label>
                    <div class="col-sm-2">
                        <input type="text" readonly name="name"
                               value="<?= $HaveRs ? $rs['NAME'] : "" ?>"
                               id="txt_name" class="form-control">
                    </div>

                    <label class="col-sm-1 control-label">تاريخ الميلاد</label>
                    <div class="col-sm-2">
                        <input type="text" readonly name="birth_date"
                               value="<?= $HaveRs ? $rs['BIRTH_DATE'] : "" ?>"
                               id="txt_birth_date" class="form-control">
                    </div>
                </div>

                <div class="form-group">

                    <label class="col-sm-1 control-label">التخصص</label>
                    <div class="col-sm-2">
                        <input type="text" name="spec"
                               value="<?= $HaveRs ? $rs['SPEC'] : "" ?>"
                               id="txt_spec" class="form-control">
                    </div>

                    <label class="col-sm-1 control-label">الجامعة</label>
                    <div class="col-sm-2">
                        <input type="text"  name="university"
                               value="<?= $HaveRs ? $rs['UNIVERSITY'] : "" ?>"
                               id="txt_university" class="form-control">
                    </div>

                    <label class="col-sm-1 control-label">تاريخ التخرج</label>
                    <div class="col-sm-2">

                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="DD/MM/YYYY" name="graduate_date"
                               id="txt_graduate_date" class="form-control"
                               value="<?= $HaveRs ? $rs['GRADUATE_DATE'] : "" ?>">
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

                    <label class="col-sm-1 control-label">تاريخ بداية التدريب</label>
                    <div class="col-sm-2">
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="DD/MM/YYYY" name="start_date"
                               id="txt_start_date" class="form-control"
                               value="<?= $HaveRs ? $rs['START_DATE'] : "" ?>">
                    </div>


                    <label class="col-sm-1 control-label">تاريخ نهاية التدريب</label>
                    <div class="col-sm-2">
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="DD/MM/YYYY" name="end_date"
                               id="txt_end_date" class="form-control"
                               value="<?= $HaveRs ? $rs['END_DATE'] : "" ?>">
                    </div>

                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label">رقم الهاتف</label>
                    <div class="col-sm-2">
                        <input type="text" name="mobile"
                               value="<?= $HaveRs ? $rs['MOBILE'] : "" ?>"
                               id="txt_mobile" class="form-control">
                    </div>

                    <label class="col-sm-1 control-label">البريد الالكتروني</label>
                    <div class="col-sm-2">
                        <input type="text" name="email"
                               value="<?= $HaveRs ? $rs['EMAIL'] : "" ?>"
                               id="txt_email" class="form-control">
                    </div>

                </div>



                <div class="form-group">
                    <label class="col-sm-1 control-label">ملاحظات</label>
                    <div class="col-sm-9">
                        <textarea data-val-required="حقل مطلوب"   style="margin: 0px 0px 0px 162.879px; width: 1308px; height: 234px;"
                                  class="form-control" name="notes" rows="3"
                                  id="txt_notes"><?= $HaveRs ? $rs['NOTES'] : "" ?></textarea>
                    </div>

                </div>


                <?php if(!$isCreate) { ?>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">المرفقات</label>
                        <div class="col-sm-2">
                            <?php echo modules::run('attachments/attachment/index',$HaveRs ? $rs['SER'] : 0,'training'); ?>
                        </div>
                    </div>
                <?php  } ?>


                <div class="modal-footer">
                    <?php

                    $exp_date = str_replace('/', '-', @$rs['END_DATE']);
                    $datetime2 = date_create($exp_date);
                    $datetime1 = date_create(date('d-m-Y'));
                    $interval = date_diff($datetime1, $datetime2);
                    $check_date = $interval->format('%R%a');

                    if($check_date < 0  ){
                        $status = 2;

                    }
                    elseif($check_date <= 14 && $check_date >= 0)
                        $status = 1;
                    else
                        $status = 0;


                    if ( HaveAccess($post_url) && $status != 2)  : ?>
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






    $('#txt_id_check').on('change',function () {
        if(checkidno($('#txt_id_check').val()))
        {
            $.ajax({
                url:"https://im-server.gedco.ps:8001/apis/GetData/"+$('#txt_id_check').val(),
                type: "GET",
                data:{},
                dataType:'json',
                success: (function (data) {
                    $('#txt_name').val(data.DATA[0].FNAME_ARB + " "+ data.DATA[0].SNAME_ARB+ " "+ data.DATA[0].TNAME_ARB+ " "+ data.DATA[0].LNAME_ARB);
                    $('#txt_birth_date').val(data.DATA[0].BIRTH_DT);
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
            $('#txt_birth_date').val('');
            $('#txt_id_check').val('');

        }
    });



</script>

SCRIPT;
sec_scripts($scripts);
?>