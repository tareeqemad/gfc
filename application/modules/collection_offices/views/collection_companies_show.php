<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/12/19
 * Time: 10:12 ص
 */

$MODULE_NAME = 'collection_offices';
$TB_NAME = 'Collection_companies';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$send_url= base_url("$MODULE_NAME/$TB_NAME/send_email");
$unadopt = base_url("$MODULE_NAME/$TB_NAME/unadopt");
$get_sub =base_url("$MODULE_NAME/$TB_NAME/public_get_sub");
$get_employee =base_url("$MODULE_NAME/$TB_NAME/public_get_employee");
$get_gov_url =base_url("$MODULE_NAME/$TB_NAME/public_get_detaild_name");
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
                        <legend>بيانات الشركة</legend>
                        <div class="row">

                            <br>
                            <label class="col-sm-1 control-label">رقم المسلسل</label>
                            <div class="col-sm-2">
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
                            <label class="col-sm-1 control-label">رقم الترخيص</label>
                            <div class="col-sm-3">
                                <input type="text"
                                       data-val="true"
                                       name="license_no"
                                       value="<?= $HaveRs ? $rs['LICENSE_NO'] : "" ?>"
                                       id="txt_license_no"
                                       class="form-control">
                            </div>
                            <label class="col-sm-1 control-label">اسم الشركة</label>
                            <div class="col-sm-5">

                                <input type="text"
                                       name="company_name"
                                       data-val="true"
                                       value="<?= $HaveRs ? $rs['COMPANY_NAME'] : "" ?>"
                                       id="txt_company_name"
                                       class="form-control">
                            </div>

                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">رقم هوية المفوض</label>
                            <div class="col-sm-3">
                                <input type="text"
                                       data-val="true"
                                       name="id_number"
                                       value="<?= $HaveRs ? $rs['ID_NUMBER'] : "" ?>"
                                       id="txt_id_number"
                                       class="form-control">
                            </div>

                            <label class="col-sm-1 control-label">اسم المفوض</label>
                            <div class="col-sm-5">

                                <input type="text"
                                       name="user_name"
                                       data-val="true" readonly
                                       value="<?= $HaveRs ? $rs['USER_NAME'] : "" ?>"
                                       id="txt_user_name"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">العنوان</label>
                            <div class="col-sm-9">

                                <input type="text"
                                       data-val="true"
                                       name="address"
                                       value="<?= $HaveRs ? $rs['ADDRESS'] : "" ?>"
                                       id="txt_address"
                                       class="form-control">
                            </div>



                            <br>
                        </div>
                        <br>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">وسيلة الاتصال</label>
                            <div class="col-sm-3">

                                <input type="text"
                                       data-val="true"
                                       placeholder="رقم الهاتف"
                                       name="tel_no"
                                       value="<?= $HaveRs ? $rs['TEL_NO'] : "" ?>"
                                       id="txt_tel_no"
                                       class="form-control">
                            </div>

                            <div class="col-sm-3">

                                <input type="text"
                                       data-val="true"
                                       placeholder="رقم المحمول"
                                       name="mobile"
                                       value="<?= $HaveRs ? $rs['MOBILE'] : "" ?>"
                                       id="txt_mobile"
                                       class="form-control">
                            </div>

                            <div class="col-sm-3">

                                <input type="text"
                                       data-val="true"
                                       placeholder="البريد الالكتروني"
                                       name="email"
                                       value="<?= $HaveRs ? $rs['EMAIL'] : "" ?>"
                                       id="txt_email"
                                       class="form-control">
                            </div>




                            <br>
                        </div>
                        <br>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">ملاحظات</label>
                            <div class="col-sm-9">
                                <textarea data-val-required="حقل مطلوب"
                                          class="form-control" name="notes" rows="3"
                                          id="txt_notes"><?php echo @$rs['NOTES'] ;?></textarea>
                            </div>
                        </div>

                    </fieldset>


                    

                    <div class="modal-footer">

                        <?php
                        if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) ) AND  ( $isCreate || @$rs['ENTRY_USER']?$this->user->id : false )  ) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>
                        <?php  if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  $this->user->branch == $rs['BRANCH_ID']  )) : ?>
                            <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
                        <?php  endif; ?>
                        <?php  if ( HaveAccess($unadopt) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  $this->user->branch == $rs['BRANCH_ID'] )) : ?>
                            <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء اعتماد</button>
                           <!-- <a class="btn default blue" target="_blank" href="<?= base_url("$MODULE_NAME/$TB_NAME/add/".@$rs['SER']) ?>">
                                <i class="glyphicon glyphicon-plus"></i> اضافة عامل
                            </a> -->
                        <?php  endif; ?>
                    </div>



                </div>

            </form>

            <?php if ($HaveRs) { ?>
                <fieldset class="field_set" >
                    <legend >العاملين في المكتب</legend>
                    <div class="form-group">

                        <?php echo modules::run($get_employee, (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>
                    </div>
                </fieldset>


            <!--<fieldset class="field_set" >
                <legend >الاشتراكات</legend>
                <div class="form-group">

                    <?php echo modules::run($get_sub, (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>
                </div>
            </fieldset>-->

            <?php  } ?>

        </div>
    </div>





<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
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
		get_data('{$unadopt}',{id: $('#txt_ser').val()},function(data){
			if(data =='1')
			{
				success_msg('تم الغاء اعتماد بنجاح');
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
            //console.log(data);
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
                    $('#txt_user_name').val(data.DATA[0].FNAME_ARB + " "+ data.DATA[0].SNAME_ARB+ " "+ data.DATA[0].TNAME_ARB+ " "+ data.DATA[0].LNAME_ARB);
                    toastr.info('رقم الهوية صحيح');
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
            $('#txt_user_name').val('');
        }
    });


   });

</script>

SCRIPT;
sec_scripts($scripts);
?>