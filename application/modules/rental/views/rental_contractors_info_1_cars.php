<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 22/08/17
 * Time: 10:58 ص
 */

if($cnt_rs!=1){
    die('خطأ: بيانات تفاصيل الخدمة غير متاحة');
}

?>

    <div class="form-group col-sm-2">
        <label class="control-label">رقم السيارة
            <span class="required">*</span>
        </label>
        <div>
            <input type="text" value="<?=$HaveRs?$rs['CAR_NUM']:''?>" name="car_num" id="txt_car_num" class="form-control" />
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">نوع السيارة
            <span class="required">*</span>
        </label>
        <div>
            <select name="car_class" id="dp_car_class" class="form-control sel2" >
                <option value="">_________</option>
                <?php foreach($car_class_cons as $row) :?>
                    <option <?=$HaveRs?($rs['CAR_CLASS']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">الموديل</label>
        <div>
            <input type="text" value="<?=$HaveRs?$rs['CAR_MODEL']:""?>" name="car_model" id="txt_car_model" class="form-control" />
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">نوع الوقود  </label>
        <div>
            <select name="car_fuel_type" id="dp_car_fuel_type" class="form-control sel2" >
                <option value="">_________</option>
                <?php foreach($car_fuel_type_cons as $row) :?>
                    <option <?=$HaveRs?($rs['CAR_FUEL_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">عدد الركاب </label>
        <div>
            <input type="text" value="<?=$HaveRs?$rs['CAR_PASSENGER_COUNT']:""?>" name="car_passenger_count" id="txt_car_passenger_count" class="form-control" />
        </div>
    </div>

    <div style="clear: both"></div>

    <div class="form-group col-sm-2">
        <label class="control-label">رقم مسلسل التأمين</label>
        <div>
            <input type="text" value="<?=$HaveRs?$rs['CAR_INSURANCE_NUM']:""?>" name="car_insurance_num" id="txt_car_insurance_num" class="form-control" />
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">شركة التأمين</label>
        <div>
            <select name="car_insurance_company" id="dp_car_insurance_company" class="form-control sel2" >
                <option value="">_________</option>
                <?php foreach($car_insurance_company_cons as $row) :?>
                    <option <?=$HaveRs?($rs['CAR_INSURANCE_COMPANY']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">نوع التأمين</label>
        <div>
            <select name="car_insurance_type" id="dp_car_insurance_type" class="form-control sel2" >
                <option value="">_________</option>
                <?php foreach($car_insurance_type_cons as $row) :?>
                    <option <?=$HaveRs?($rs['CAR_INSURANCE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">تاريخ بداية التأمين</label>
        <div>
            <input type="text" value="<?=$HaveRs?$rs['CAR_INSURANCE_START_DATE']:""?>" name="car_insurance_start_date" id="txt_car_insurance_start_date" class="form-control" />
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">تاريخ نهاية التأمين</label>
        <div>
            <input type="text" value="<?=$HaveRs?$rs['CAR_INSURANCE_END_DATE']:""?>" name="car_insurance_end_date" id="txt_car_insurance_end_date" class="form-control" />
        </div>
    </div>

    <?php if($HaveRs){
        echo "<div data-txt='مرفقات التأمين' class='form-group col-sm-2 rent_attach'>";
        echo modules::run('attachments/attachment/index',$rs['CONTRACTOR_FILE_ID'],'rental_contractors_insurance');
        echo "</div>";
    } ?>

    <div style="clear: both"></div>

    <div class="form-group col-sm-2">
        <label class="control-label">تاريخ بداية الترخيص</label>
        <div>
            <input type="text" value="<?=$HaveRs?$rs['CAR_LICENSE_START']:""?>" name="car_license_start" id="txt_car_license_start" class="form-control" />
        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="control-label">تاريخ نهاية الترخيص</label>
        <div>
            <input type="text" value="<?=$HaveRs?$rs['CAR_LICENSE_END']:""?>" name="car_license_end" id="txt_car_license_end" class="form-control" />
        </div>
    </div>

    <?php if($HaveRs){
        echo "<div data-txt='مرفقات الترخيص' class='form-group col-sm-2 rent_attach'>";
        echo modules::run('attachments/attachment/index',$rs['CONTRACTOR_FILE_ID'],'rental_contractors_license');
        echo "</div>";
    } ?>

