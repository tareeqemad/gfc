<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 06/05/2022
 * Time: 02:03 PM
 */
$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';

?>

<div>
    <div class="padding-20">
        <form onsubmit="javascript:updateUserPassword(event);" >
            <div class="form-group row mt-20">
                <label class="control-label col-md-4"> كلمة المرور الحالية  </label>
                <div class="col-md-5">
                    <input type="hidden" id="ser" name="ser" value="<?= $user['NO'] ?>"/>
                    <input type="text"
                           name="old_password"
                           id="old_password"
                           readonly
                           value="<?= $user['PASSWORD'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-4"> كلمة المرور الجديدة </label>
                <div class="col-md-5">
                    <input type="text"
                           name="new_password"
                           id="new_password"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-4"> تأكيد كلمة المرور  </label>
                <div class="col-md-5">
                    <input type="text"
                           name="confirm_new_password"
                           id="confirm_new_password"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit"  class="btn btn-primary">
                    حفظ
                </button>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


		
</script>
SCRIPT;
sec_scripts($scripts);
?>

