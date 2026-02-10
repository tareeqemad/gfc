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

$wol = base_url('technical/Worker_Order_Loads/public_index/' . ($HaveRs ? $rs['ADAPTER_SERIAL'] : -1));

?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a href="<?= $wol ?>">قياس الأحمال </a></li>
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
          action="<?= base_url('projects/adapter/' . ($HaveRs ? 'edit' : 'create')) ?>" role="form"
          novalidate="novalidate">
    <div class="modal-body inline_form">

    <div class="form-group col-sm-1">
        <label class="control-label"> الرقم </label>

        <div>
            <input type="text" readonly value="<?= $HaveRs ? $rs['ADAPTER_SERIAL'] : "" ?>"
                   name="adapter_serial" id="txt_adapter_serial" class="form-control">
        </div>
    </div>


    <div class="form-group  col-sm-2">
        <label class="control-label">المكان
        </label>

        <div>
            <input name="address_id" type="hidden"
                   value="<?= $HaveRs ? $rs['ADDRESS_ID'] : "" ?>" id="h_txt_address_id"
                   class="form-control">
            <input name="address_id_name" value="<?= $HaveRs ? $rs['ADDRESS_ID_NAME'] : "" ?>"
                   readonly id="txt_address_id" class="form-control">
        </div>
    </div>


    <div class="form-group col-sm-2">

        <label class="control-label"> اسم المحول</label>

        <div>
            <input type="text" name="adapter_name" value="<?= $HaveRs ? $rs['ADAPTER_NAME'] : "" ?>"
                   data-val="true" data-val-required="حقل مطلوب" id="txt_adapter_name"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="adapter_name"
                                  data-valmsg-replace="true"></span>

        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="control-label">نوع المحول</label>

        <div>

            <select name="power_adapter_sc" id="txt_power_adapter_sc" class="form-control valid">
                <option <?= $HaveRs && $rs['POWER_ADAPTER_SC'] == 'هوائي' ? "selected" : "" ?> value="هوائي">هوائي
                </option>
                <option <?= $HaveRs && $rs['POWER_ADAPTER_SC'] == 'ارضي' ? "selected" : "" ?> value="ارضي">ارضي</option>
            </select>

        </div>

    </div>
    <div class="form-group col-sm-1">
        <label class="control-label"> قدرة المحول KVA </label>

        <div>


            <select name="power_adapter" id="txt_power_adapter" class="form-control valid">

                <?php foreach ($POWER_ADAPTER as $row) : ?>
                    <option  <?= $HaveRs && $rs['POWER_ADAPTER'] == $row['CON_NAME'] ? "selected" : "" ?>
                        value="<?= $row['CON_NAME'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>

            </select>


        </div>

    </div>

    <hr>
    <div class="form-group col-sm-2">
        <label class="control-label">رمز المحول GIS</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['GIS_ID'] : "" ?>" name="gis_id" id="txt_GIS_ID"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="GIS_ID"
                                  data-valmsg-replace="true"></span>
        </div>
    </div>
    <div class="form-group col-sm-4">
        <label class="col-sm-12 control-label">الموقع (خريطة)</label>

        <div class="col-sm-6">
            <input type="text" value="<?= $HaveRs ? $rs['GIS_X'] : "" ?>" name="gis_x" id="txt_gis_x"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="gis_x"
                                  data-valmsg-replace="true"></span>
        </div>
        <div class="col-sm-6">
            <input type="text" value="<?= $HaveRs ? $rs['GIS_Y'] : "" ?>" name="gis_y" id="txt_gis_y"
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
        <label class="control-label">رمز مصنع المحول</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['FACTORY_SERIAL'] : "" ?>" name="factory_serial"
                   id="txt_factory_serial" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="factory_serial"
                                  data-valmsg-replace="true"></span>
        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="control-label">مكان التركيب</label>

        <div>

            <select name="position_type" id="txt_position_type" class="form-control">
                <option <?= $HaveRs ? ($rs['POSITION_TYPE'] == 1 ? 'selected' : '') : '' ?> value="1">
                    هوائي
                </option>
                <option <?= $HaveRs ? ($rs['POSITION_TYPE'] == 2 ? 'selected' : '') : '' ?> value="2">
                    ارضي
                </option>
            </select>
        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="control-label">الشركة المصنعة</label>

        <div>

 
            <select name="company_name" id="txt_company_name" class="form-control">
                <?php foreach ($COMPANY_NAME as $row) : ?>
                    <option <?= $HaveRs ? ($rs['COMPANY_NAME'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>

        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="control-label">تاريخ التصنيع</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['MANUFACTORING_DATE'] : "" ?>"  name="manufactoring_date" id="txt_manufactoring_date"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="manufactoring_date"
                                  data-valmsg-replace="true"></span>
        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="control-label">نوع الزيت</label>

        <div>
            <select name="oil_id" id="txt_oil_id" class="form-control">
                <?php foreach ($OIL_ID_TYPE as $row) : ?>
                    <option <?= $HaveRs ? ($rs['OIL_ID'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"
                        data-tec="<?= $row['ACCOUNT_ID'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <hr>
    <div class="form-group col-sm-1">
        <label class="control-label">طقة المحول</label>

        <div>

            <input type="text" value="<?= $HaveRs ? $rs['TAP_CHANGER_POSITION'] : "" ?>" name="tap_changer_position"
                   id="txt_tap_changer_position"
                   class="form-control">


        </div>
    </div>
    <div class="form-group col-sm-1">
        <label class="control-label">عدد الطقات </label>

        <div>

            <input type="text" value="<?= $HaveRs ? $rs['TAP_CHANGER_COUNT'] : "" ?>" name="tap_changer_count"
                   id="txt_tap_changer_count"
                   class="form-control">


        </div>
    </div>
    <div class="form-group col-sm-2">
        <label class="control-label">الفولتية</label>

        <div>
            <select name="adapter_voltage" id="txt_adapter_voltage" class="form-control">
                <?php foreach ($ADAPTER_VOLTAGE as $row) : ?>
                    <option <?= $HaveRs ? ($rs['ADAPTER_VOLTAGE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <hr>
    <div class="form-group col-sm-2">
        <label class="control-label"> تاريخ التركيب علي الشبكة</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['INSTALLATION_DATE'] : "" ?>" data-type="date"
                   data-date-format="DD/MM/YYYY" name="installation_date" id="txt_installation_date"
                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="installation_date"
                                  data-valmsg-replace="true"></span>
        </div>
    </div>
    <div class="form-group  col-sm-3">
        <label class="control-label">الخط المغذي </label>

        <div>


            <select name="line_feeder" id="txt_line_feeder" class="form-control">
                <?php foreach ($LINE_FEEDER as $row) : ?>
                    <option <?= $HaveRs ? ($rs['LINE_FEEDER'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group  col-sm-5">
        <label class="control-label">الملاحظات</label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['HINTS'] : "" ?>" name="hints" id="txt_hints"
                   class="form-control">
        </div>
    </div>
    </div>

    <div style="clear: both;">

        <?php echo $HaveRs ? modules::run('attachments/attachment/index', $HaveRs ? $rs['ADAPTER_SERIAL'] : 0, 'adapter_tb') : ''; ?>
    </div>

    <div style="clear: both;">
        <?php echo modules::run('projects/adapter/public_get_partitions', $HaveRs ? $rs['ADAPTER_SERIAL'] : 0); ?>
    </div>

    <hr>

    <div>
        <p>
            أماكن المحول السابقة
        </p>
        <table class="table" id="projects_detailsTbl" data-container="container">
            <thead>
            <tr>
                <th style="width: 20px"> #</th>
                <th> المكان</th>
                <th style="width: 100px">التاريخ</th>
            </tr>
            </thead>

            <tbody>
            <?php $count = 1;
            if (isset($locations)): foreach ($locations as $row): ?>
                <tr>
                    <td><?= $count; ?></td>
                    <td><?= $row['ADDRESS_ID_NAME'] ?></td>
                    <td><?= $row['ENTRY_DATE'] ?></td>
                </tr>
                <?php $count++; endforeach; endif; ?>
            </tbody>

        </table>
    </div>

    <div class="modal-footer">
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>


    </div>
    </form>

    </div>

    </div>
    </div>

<?php
$delete_url = base_url('projects/adapter/delete_partition');
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