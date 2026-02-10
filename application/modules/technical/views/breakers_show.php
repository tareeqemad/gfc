<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url = base_url('projects/adapter');


if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

$wol = base_url('technical/Worker_Order_Loads/public_index/' . ($HaveRs ? $rs['BREAKER_ID'] : -1));

?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>

            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>


    </div>

    <div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
    <form class="form-form-vertical" id="adapter_from" method="post"
          action="<?= base_url('technical/Breakers/' . ($HaveRs ? 'edit' : 'create')) ?>" role="form"
          novalidate="novalidate">
    <div class="modal-body inline_form">

    <div class="form-group col-sm-1">
        <label class="control-label"> الرقم </label>

        <div>
            <input type="text" readonly value="<?= $HaveRs ? $rs['BREAKER_ID'] : "" ?>"
                   name="breaker_id" id="txt_breaker_id" class="form-control">
        </div>
    </div>


    <div class="form-group  col-sm-1">
        <label class="control-label">الفرع </label>
        <div>

            <select type="text"   name="branch_id" id="dp_branch_id" class="form-control" >

                <?php foreach($branches as $row) :?>
                    <option <?= $HaveRs?($rs['BRANCH_ID'] ==$row['NO']?'selected':''):'' ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                <?php endforeach; ?>
            </select>    </div>
    </div>


    <div class="form-group  col-sm-1">
        <label class="control-label">كود القاطع
        </label>

        <div>

            <input name="breaker_code" value="<?= $HaveRs ? $rs['BREAKER_CODE'] : "" ?>"
                   id="txt_breaker_code" class="form-control">
        </div>
    </div>


    <div class="form-group col-sm-2">

        <label class="control-label"> اسم القاطع</label>

        <div>
            <input type="text" name="breaker_name" value="<?= $HaveRs ? $rs['BREAKER_NAME'] : "" ?>"
                   data-val="true" data-val-required="حقل مطلوب" id="txt_breaker_name"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="BREAKER_NAME"
                                  data-valmsg-replace="true"></span>

        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="control-label">نوع القاطع</label>

        <div>

            <select name="breaker_type" id="txt_breaker_type" class="form-control valid">

                <?php foreach ($BREAKER_TYPE as $row) : ?>
                    <option  <?= $HaveRs && $rs['BREAKER_TYPE'] == $row['CON_NAME'] ? "selected" : "" ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>


            </select>

        </div>

    </div>


    <hr>
    <div class="form-group col-sm-2">
        <label class="control-label">الاحداثيات</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['COORDINATION'] : "" ?>" name="coordination"
                   id="txt_coordination"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="GIS_ID"
                                  data-valmsg-replace="true"></span>
        </div>
    </div>
    <div class="form-group col-sm-4">
        <label class="col-sm-12 control-label">الموقع (خريطة)</label>

        <div class="col-sm-6">
            <input type="text" value="<?= $HaveRs ? $rs['GPSX'] : "" ?>" name="gpsx" id="txt_gpsx"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="gis_x"
                                  data-valmsg-replace="true"></span>
        </div>
        <div class="col-sm-6">
            <input type="text" value="<?= $HaveRs ? $rs['GPSY'] : "" ?>" name="gpsy" id="txt_gpsy"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="gis_y"
                                  data-valmsg-replace="true"></span>
        </div>
    </div>
    <div class="form-group col-sm-1">
        <label class="col-sm-12 control-label" style="height: 25px;"> </label>
        <button type="button" class="btn green"
                onclick="javascript:_showReport('<?= base_url("maps/public_map/txt_gis_x/txt_gis_y") ?>');">
            <i class="icon icon-map-marker"></i>
        </button>

    </div>
    <hr>

    <div class="form-group col-sm-2">
        <label class="control-label">رقم المصنع</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['FACTOR_CODE'] : "" ?>" name="factor_code"
                   id="txt_factor_code" class="form-control">

        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="control-label">الشركة المصنعة</label>

        <div>


            <select name="company_produced" id="txt_company_produced" class="form-control">
                <?php foreach ($COMPANY_PRODUCED as $row) : ?>
                    <option <?= $HaveRs ? ($rs['COMPANY_PRODUCED'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>

        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="control-label">تاريخ التصنيع</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['PRODUCT_YEAR'] : "" ?>" name="product_year"
                   id="txt_product_year"
                   class="form-control">

        </div>
    </div>


    <div class="form-group col-sm-2">
        <label class="control-label"> تاريخ الإدخال للخدمة</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['WORK_INDATE'] : "" ?>" name="work_indate"
                   id="txt_work_indate"
                   data-type="date"
                   data-date-format="DD/MM/YYYY"
                   class="form-control">

        </div>
    </div>

    <hr>

    <div class="form-group col-sm-2">
        <label class="control-label">استخدام القاطع</label>

        <div>
            <select name="breaker_used" id="dp_breaker_used" class="form-control">
                <?php foreach ($BREAKER_USED as $row) : ?>
                    <option <?= $HaveRs ? ($rs['BREAKER_USED'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="control-label"> ق.ف خانة المحول</label>

        <div>
            <select name="adapter_digit_fues" id="dp_adapter_digit_fues" class="form-control">
                <?php foreach ($ADAPTER_DIGIT_FUES as $row) : ?>
                    <option <?= $HaveRs ? ($rs['ADAPTER_DIGIT_FUES'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>


    <hr>


    <div class="form-group col-sm-1">
        <label class="control-label">عدد الخانات المستخدمة</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['DIGITS_USED'] : "" ?>" name="digits_used" id="txt_digits_used"
                   class="form-control">
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label"> عدد الخانات الغير مستخدمة</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['DIGITS_UNUSED'] : "" ?>" name="digits_unused"
                   id="txt_digits_unused"
                   class="form-control">
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label"> نوع الخانة المستخدمة</label>

        <div>
            <select name="digits_used_type" id="dp_digits_used_type" class="form-control">
                <?php foreach ($DIGITS_USED_TYPE as $row) : ?>
                    <option <?= $HaveRs ? ($rs['DIGITS_USED_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>


    <div class="form-group col-sm-1">
        <label class="control-label"> نوع الخانة الغير مستخدمة</label>

        <div>
            <select name="digits_unused_type" id="dp_digits_unused_type" class="form-control">
                <?php foreach ($DIGITS_UNUSED_TYPE as $row) : ?>
                    <option <?= $HaveRs ? ($rs['DIGITS_UNUSED_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>


    <div class="form-group col-sm-1">
        <label class="control-label"> نوع العزل المستخدم</label>

        <div>
            <select name="breaker_isolate" id="dp_breaker_isolate" class="form-control">
                <?php foreach ($BREAKER_ISOLATE as $row) : ?>
                    <option <?= $HaveRs ? ($rs['BREAKER_ISOLATE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <hr>
    <div class="form-group col-sm-5">
        <label class="control-label"> الملاحظات</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['HINTS'] : "" ?>" name="hints"
                   id="txt_hints"
                   class="form-control">
        </div>
    </div>

    </div>

    <div style="clear: both;">

        <?php echo $HaveRs ? modules::run('attachments/attachment/index', $HaveRs ? $rs['BREAKER_ID'] : 0, 'adapter_tb') : ''; ?>
    </div>


    <hr>


    <div class="modal-footer">
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>


    </div>
    </form>

    </div>

    </div>
    </div>

<?php
$delete_url = base_url('technical/Breakers/delete_partition');
$adapter_address_url = base_url('technical/Adapter_address/public_index');
$shared_script = <<<SCRIPT




        $('#txt_address_id').click(function(){
            _showReport('$adapter_address_url/'+$(this).attr('id')+'/');
        });


      $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                reload_Page();

            },'html');
        }
    });

    function delete_details(a,id){
             if(confirm('هل تريد حذف البند ؟!')){

                  get_data('{$delete_url}',{id:id},function(data){
                             if(data == '1'){
                                $(a).closest('tr').remove();

                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }
         }

SCRIPT;


$create_script = <<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;


$edit_script = <<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;

if ($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>