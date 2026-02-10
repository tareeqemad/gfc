<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 04/09/2022
 * Time: 09:03 AM
 */
$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';

?>

<div>
    <div class="padding-20">
        <form onsubmit="javascript:updateUserData(event);" >

            <div class="form-group row mt-20">
                <label class="control-label col-md-4">الرقم الوظيفي</label>
                <div class="col-md-3">
                    <input type="hidden" id="ser" name="ser" value="<?= $user['NO'] ?>"/>
                    <input type="text"
                           name="user_no"
                           id="user_no"
                           readonly
                           value="<?= $user['NO'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-4">اسم المحصل</label>
                <div class="col-md-5">
                    <input type="text"
                           name="user_name"
                           id="user_name"
                           readonly
                           value="<?= $user['NAME'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-4">المقر</label>
                <div class="col-md-5">
                    <input type="text"
                           name="user_branch"
                           id="user_branch"
                           readonly
                           value="<?= $user['BRANCH_NAME'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-4">رقم الموبايل</label>
                <div class="col-md-5">
                    <input type="text"
                           name="user_mobile"
                           id="user_mobile"
                           value="0<?= $user['MOBILE'] ?>"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control"/>
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

