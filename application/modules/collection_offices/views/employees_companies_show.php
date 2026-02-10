<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/05/20
 * Time: 10:25 ص
 */

$MODULE_NAME = 'collection_offices';
$TB_NAME = 'Collection_companies';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/add");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_employee");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'add/'.$id : $action));
$get_gov_url =base_url("$MODULE_NAME/$TB_NAME/public_get_detaild_name");
$GET_SECTIONS = 'public_get_sections';
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;


?>


    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>

            </ul>

        </div>

    </div>


    <div class="form-body">

        <div id="container">
            <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post"
                  action="<?php echo $post_url ?>" role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">

                    <fieldset class="field_set">
                        <legend>البيانات الشخصية</legend>
                        <div class="row">

                            <br>
                            <label class="col-sm-1 control-label">رقم المسلسل</label>
                            <div class="col-sm-2">
                                <input type="hidden" name="id" value="<?= @$id ?>">
                                <input type="text"
                                       data-val="true"
                                       readonly
                                       value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                                       name="ser"
                                       id="txt_ser"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">رقم هوية الموظف</label>
                            <div class="col-sm-2">
                                <input type="text"
                                       data-val="true"
                                       value="<?= $HaveRs ? $rs['ID_NUMBER'] : "" ?>"
                                       name="id_number"
                                       id="txt_id_number"
                                       class="form-control">
                            </div>
                            <label class="col-sm-1 control-label">اسم الموظف</label>
                            <div class="col-sm-3">

                                <input type="text" readonly
                                       name="emp_name"
                                       data-val="true"
                                       value="<?= $HaveRs ? $rs['EMP_NAME'] : "" ?>"
                                       id="txt_emp_name"
                                       class="form-control">
                            </div>

                            <label class="col-sm-1 control-label">رقم المحمول </label>
                            <div class="col-sm-2">
                                <input type="text"
                                       data-val="true"
                                       value="<?= $HaveRs ? $rs['MOBILE'] : "" ?>"
                                       name="mobile"
                                       id="txt_mobile"
                                       class="form-control">
                            </div>

                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">تاريخ الميلاد</label>
                            <div class="col-sm-2">
                                <input type="text" value="<?= $HaveRs ? $rs['BIRTH_DATE'] : "" ?>"
                                       data-val-required="حقل مطلوب" data-type="date"
                                       data-date-format="DD/MM/YYYY" name="birth_date"
                                       id="txt_birth_date" class="form-control" >
                            </div>

                            <label class="col-sm-1 control-label">عنوان السكن</label>
                            <div class="col-sm-1">
                                <select name="district" id="dp_district" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($district as $row) :?>
                                        <option  value="<?= $row['CON_NO'] ?>"
                                            <?PHP if ($row['CON_NO']==($HaveRs ? $rs['DISTRICT'] : 0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-sm-2">

                                <input type="text"
                                       data-val="true"
                                       placeholder="المنطقة"
                                       name="region"
                                       value="<?= $HaveRs ? $rs['REGION'] : "" ?>"
                                       id="txt_region"
                                       class="form-control">
                            </div>

                            <label class="col-sm-1 control-label">الحالة الاجتماعية</label>
                            <div class="col-sm-2">
                                <select name="social_status" id="dp_social_status" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($social_status as $row) :?>
                                        <option  value="<?= $row['CON_NO'] ?>"
                                            <?PHP if ($row['CON_NO']==($HaveRs ? $rs['SOCIAL_STATUS'] : 0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">الجنس</label>
                            <div class="col-sm-2">
                                <select name="gender" id="dp_gender" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($gender as $row) :?>
                                        <option  value="<?= $row['CON_NO'] ?>"
                                            <?PHP if ($row['CON_NO']==($HaveRs ? $rs['GENDER'] : 0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <br>
                        </div>
                        <br>

                    </fieldset>

                    <fieldset class="field_set">
                        <legend>البيانات الادارية</legend>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">تاريخ التعيين</label>
                            <div class="col-sm-2">
                                <input type="text" value="<?= $HaveRs ? $rs['HIRE_DATE'] : "" ?>"
                                       data-val-required="حقل مطلوب" data-type="date"
                                       data-date-format="DD/MM/YYYY" name="hire_date"
                                       id="txt_hire_date" class="form-control" >
                            </div>

                            <label class="col-sm-1 control-label">المؤهل العلمي</label>
                            <div class="col-sm-2">
                                <select name="qualification" id="dp_qualification" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($qualification as $row) :?>
                                        <option  value="<?= $row['CON_NO'] ?>"
                                            <?PHP if ($row['CON_NO']==($HaveRs ? $rs['QUALIFICATION'] : 0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>

                            <label class="col-sm-1 control-label">سنة التخرج</label>
                            <div class="col-sm-2">
                                <input type="text"
                                       data-val="true" value="<?= $HaveRs ? $rs['GRADUATION_YEAR'] : "" ?>"
                                       placeholder="<?php echo date('Y'); ?>"
                                       name="graduation_year"
                                       id="txt_graduation_year"
                                       class="form-control">
                            </div>

                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">طبيعة العمل</label>
                            <div class="col-sm-2">
                                <select name="work_nature" id="dp_work_nature" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($work_nature as $row) :?>
                                        <option  value="<?= $row['CON_NO'] ?>"
                                            <?PHP if ($row['CON_NO']==($HaveRs ? $rs['WORK_NATURE'] : 0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <label class="col-sm-1 control-label">مكان العمل</label>
                            <div class="col-sm-2">
                                <select name="work_place" id="dp_work_place" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($district as $row) :?>
                                        <option  value="<?= $row['CON_NO'] ?>"
                                            <?PHP if ($row['CON_NO']==($HaveRs ? $rs['WORK_PLACE'] : 0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <fieldset class="field_set">
                            <legend> قطاع العمل في المحافظة</legend>
                            <div class="form-group">
                                <?php echo modules::run("$MODULE_NAME/$TB_NAME/$GET_SECTIONS", $HaveRs ? $rs['SER'] : 0); ?>
                            </div>
                        </fieldset>

                    </fieldset>





                    <div class="modal-footer">
                        <?php
                        //if (HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) ) AND  ( $isCreate || @$rs['ENTRY_USER']?$this->user->id : false )  ) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php //endif; ?>
                        <!--<?php  //if (HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  $this->user->branch == $rs['BRANCH_ID']  )) : ?>
                            <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
                        <?php  //endif; ?>
                        <?php  //if (HaveAccess($unadopt) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  $this->user->branch == $rs['BRANCH_ID'] )) : ?>
                            <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-warning">الغاء اعتماد</button>
                        <?php // endif; ?> -->

                    </div>



                </div>

            </form>

        </div>
    </div>





<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


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




     $('#txt_id_number').on('change',function () {
        if(checkidno($('#txt_id_number').val()))
        {
            $.ajax({
                url:'{$get_gov_url}',
				type: "POST",
				data:{id:$('#txt_id_number').val()},
				dataType: 'JSON',
                success: (function (data) {
                    $('#txt_emp_name').val(data.DATA[0].FNAME_ARB + " "+ data.DATA[0].SNAME_ARB+ " "+ data.DATA[0].TNAME_ARB+ " "+ data.DATA[0].LNAME_ARB);
                    toastr.warning('رقم الهوية صحيح');
                }),
                error: (function (e) {
                    alert('حدث خطأ');
                })
            });
        }
        else
        {
            toastr.error('ادخال خاطئ لرقم الهوية');
            $('#txt_id_number').val('');
            $('#txt_emp_name').val('');
        }
    });


   });

</script>

SCRIPT;
sec_scripts($scripts);
?>