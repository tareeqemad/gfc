<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/14/2021
 * Time: 12:26 PM
 */
    $MODULE_NAME= 'treasury';
    $TB_NAME= 'workfield';
    $get_sub_info_url =base_url("$MODULE_NAME/$TB_NAME/public_sub_info");

?>

<div>
    <div class="padding-20">
        <form onsubmit="javascript:updateSaveBill(event);" >
            <div class="form-group row mt-20">
                <label class="control-label col-md-2"> رقم الاشتراك </label>
                <div class="col-md-5">
                    <input type="hidden" id="ser" name="ser" value="<?= $bill['SER'] ?>"/>
                    <input type="number"
                           name="no"
                           id="no"
                           maxlength="8"
                           value="<?= $bill['SUBSCRIBE'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-2"> الاسم </label>
                <div class="col-md-5">
                    <input type="text"
                           name="name"
                           id="name"
                           value="<?= $bill['NAME'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>

                <div class="col-md-5">
                    <input type="text"
                           name="txt_name"
                           id="txt_name"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control hidden"/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-2"> رقم الهوية </label>
                <div class="col-md-5">
                    <input type="number"
                           name="identity"
                           id="identity"
                           maxlength="9"
                           value="<?= $bill['IDENTITY'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>

                <div class="col-md-5">
                    <input type="number"
                           name="txt_identity"
                           id="txt_identity"
                           maxlength="9"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control hidden "/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-2"> الجوال</label>
                <div class="col-md-5">
                    <input type="tel"
                           name="mobile"
                           id="mobile"
                           maxlength="10"
                           value="<?= $bill['MOBILE'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-2"> العنوان</label>
                <div class="col-md-5">
                    <input type="text"
                           name="address"
                           id="address"
                           value="<?= $bill['ADDRESS'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div>
                <button type="submit"  class="btn btn-success">
                    حفظ و اعتماد
                </button>

                <button type="button" id="btn_edit" class="btn btn-warning hidden">
                    تصحيح البيانات
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
		$('#no').change(function(){
			get_data('{$get_sub_info_url}',{id:$(this).val()},function(data){
				var item = data[0];
				if (data.length == 1){
					$("#txt_name").val(item.NAME);
					$("#txt_identity").val(item.ID);
					$("#btn_edit").removeClass("hidden");
					$("#txt_name").removeClass("hidden");
					$("#txt_identity").removeClass("hidden");
				} else {
					$("#txt_name").val('');
					$("#txt_identity").val('');
					$("#btn_edit").addClass("hidden");
					$("#txt_name").addClass("hidden");
					$("#txt_identity").addClass("hidden");
				}
            });
        });
		$('#btn_edit').click(function(){ 
			$("#name").val($("#txt_name").val());
			$("#identity").val($("#txt_identity").val());
		})
</script>
SCRIPT;
sec_scripts($scripts);
?>

