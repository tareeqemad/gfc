<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 18/05/22
 * Time: 10:00 ص
 */

$MODULE_NAME= 'statistics';
$TB_NAME= 'Company_campaigns';

?>

<?= AntiForgeryToken(); ?>

<div class="row">

    <div class="form-body">

        <fieldset>
            <legend>التقارير</legend>
            <div class="modal-body inline_form">
                <div class="form-group rp col-sm-3" id="rep_id">
                    <label class="control-label">التقرير</label>
                    <div>
                        <select name="rep_id" class="form-control" id="dp_rep_id">
                            <option value="0">_______________</option>
                            <option value="1">1- تقرير سداد النقاط</option>
                            <option value="2">2- تقرير إجمالي سداد النقاط </option>
                            <option value="3">3- تقرير تفاصيل إحتساب النقاط </option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-2 op" id="dp_branch_id_div" style="display:none"  >
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id_3" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="txt_sub_no_div" style="display:none;" >
                    <label class="control-label">رقم الاشتراك</label>
                    <div>
                        <input type="text" name="sub_no" id="txt_sub_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_repayment_div" style="display:none">
                    <label class="control-label">آلية سداد بفاتورة 2022/03</label>
                    <div>
                        <select data-val="true" name="sadad_type" id="dp_sadad_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($sadad_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_sadad_type_div" style="display:none">
                    <label class="control-label">آليةالسداد الحالية</label>
                    <div>
                        <select data-val="true" name="sadad_type1" id="dp_sadad_type1" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($sadad_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_has_rem_div" style="display:none">
                    <label class="control-label">له متأخرات على فاتورة 2022/03</label>
                    <div>
                        <select data-val="true" name="has_rem" id="dp_has_rem" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($has_rem as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_type_div" style="display:none">
                    <label class="control-label">آلية الاستفادة من مشتركين بدون آلية سداد</label>
                    <div>
                        <select data-val="true" name="type" id="dp_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_billc_type_div" style="display:none">
                    <label class="control-label">نوع العداد</label>
                    <div>
                        <select data-val="true" name="billc_type" id="dp_billc_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($billc_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_subsciber_status_div" style="display:none">
                    <label class="control-label">حالة الإشتراك</label>
                    <div>
                        <select data-val="true" name="subsciber_status" id="dp_subsciber_status" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($subsciber_status as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_status_div" style="display:none">
                    <label class="control-label">استثناء الإشتراك</label>
                    <div>
                        <select data-val="true" name="sub_exception" id="dp_status" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($status as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group rp col-sm-2 op" id="dp_point_type_div" style="display:none">
                    <label class="control-label">نوع النقاط</label>
                    <div>
                        <select data-val="true" name="point_type" id="dp_point_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($point_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <fieldset id="point_div" style="display:none">
                    <legend>النقاط</legend>

                <div class="form-group rp col-sm-1 op" id="dp_points_sign_div" style="display:none">
                    <label class="control-label">إجمالي عدد النقاط</label>
                    <div>
                        <select data-val="true" name="dp_points_sign" id="dp_points_sign" class="form-control sel2" >
                            <option value="">_________</option>
                            <option value="1">=</option>
                            <option value="2">>=</option>
                            <option value="3"><=</option>
                            <option value="4">></option>
                            <option value="5"><</option>
                            <option value="6">!=</option>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="points_div" style="display:none;" >
                    <label class="control-label">عدد النقاط</label>
                    <div>
                        <input type="text"  name="points"  id="txt_points" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                    </div>
                </div>

                </fieldset>

                <fieldset id="price_div" style="display:none">
                    <legend>المبلغ</legend>

                <div class="form-group rp col-sm-1 op" id="dp_cost_sign_div" style="display:none">
                    <label class="control-label">إجمالي المبلغ(شيكل)</label>
                    <div>
                        <select data-val="true" name="cost_sign" id="dp_cost_sign" class="form-control sel2" >
                            <option value="">_________</option>
                            <option value="1">=</option>
                            <option value="2">>=</option>
                            <option value="3"><=</option>
                            <option value="4">></option>
                            <option value="5"><</option>
                            <option value="6">!=</option>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="txt_cost_div" style="display:none;" >
                    <label class="control-label">المبلغ</label>
                    <div>
                        <input type="text"  name="cost"  id="txt_cost" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                    </div>
                </div>
                </fieldset>

                <br/><br/>

                <div class="form-group rp col-sm-2 op" id="rep_type" style="display:none ;">
                    <label class="control-label">نوع التقرير</label>
                    <div>
                        <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type_id" value="xls">
                        <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                    </div>
                </div>

            </div>

        </fieldset>

        <div class="modal-footer">
            <button type="button" onclick="javascript:print_report_4();" class="btn btn-success">عرض التقرير<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:print_report_5();" class="btn btn-success">الدفعات المسددة<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">إفراغ الحقول</button>
        </div>

        <fieldset>
            <legend>آلية الإحتساب</legend>
            <p style="padding: 15px; font-size: 13px"><br>
                1.	مشتركي الشركة بنظام الفوترة الملتزمين بالسداد النقدي والذين لا تقل دفعاتهم عن 12 دفعة ولا تقل قيمة إجمالي الدفعات عن قيمة الفاتورة الشهرية وذلك خلال العام 2021 بالإضافة إلى الأشهر (1+2+3 / 2022)  , حيث يمنح المشترك 10 نقاط عن كل دفعة نقدية<br><br>
                2.	مشتركي الشركة بنظام الفوترة الملتزمين بالسداد النقدي عبر السداد الآلي حسب نسبة الراتب  (موظفي غزة ) والذين لا تقل دفعاتهم عن 12 دفعة وذلك خلال العام 2021 بالإضافة إلى الأشهر (1+2+3 / 2022)  , حيث يمنح المشترك 5 نقاط عن كل دفعة نقدية<br><br>
                3.	مشتركي الشركة بنظام الدفع المسبق الملتزمين بالشحن والذين لديهم ما لا يقل عن 12 شحنات لا تقل قيمة إجمالي الدفعات عن مبلغ 50 شيكل شهريا خلال العام 2021 بالإضافة إلى الأشهر (1+2+3 / 2022) , حيث يمنح المشترك 10 نقاط عن كل شحنة<br><br>
                4.	الاشتراك المحول للسداد الآلي بناء على طلب المشترك يمنح 100 نقطة بشرط تسديد دفعة على الأقل من خلال البنك خلال عام 2021 بالإضافة إلى الأشهر (1+2+3 / 2022)<br><br>
                5.	الاشتراك المحول للدفع المسبق (هولي) بناء على طلب المشترك يمنح 100 نقطة بشرط الشحن لمرة واحدة على الأقل خلال عام 2021 بالإضافة إلى الأشهر (1+2+3 / 2022)<br><br>
                6.	الاشتراك المحول لنظام العداد الذكي فوترة وله سداد آلي بناء على طلب المشترك يمنح 100 نقطة بشرط تسديد دفعة على الأقل من خلال البنك خلال عام 2021 بالإضافة إلى الأشهر (1+2+3 / 2022)<br><br>
                7.	الاشتراك المحول لنظام العداد الذكي مسبق بناء على طلب المشترك يمنح 100 نقطة بشرط الشحن لمرة واحدة على الأقل خلال عام 2021 بالإضافة إلى الأشهر (1+2+3 / 2022)<br><br>
                8.	المشترك الذي يسدد كافة المتأخرات المترصدة عليه يمنح 100 نقطة بشرط وجود متأخرات بقيمة أكبر من أو يساوي 1000 شيكل على فاتورة 202012 وتم التسديد النقدي لصافي المبلغ المطلوب بعد خصم غرامات التأخير وخصميات الشركة خلال عام 2021 بالإضافة إلى الأشهر (1+2+3 / 2022)<br><br>
                9.	يتم استثناء اشتراكات موظفي شركة توزيع كهرباء غزة والعاملين على بند العقود وكافة الاشتراكات التي تستفيد من منحة 200 KW<br><br>
                10.	تم استثناء الاشتراكات الصناعية التي تم منحها خصم 20%<br><br>
                11.	تم استثناء الاشتراكات الموقوفة نهائيا<br><br>
                12.	تم استثناء الاشتراكات التي استفادت من خصومات حملة رمضان<br><br>
            </p>
        </fieldset>
    </div>

</div>


<script>

    $(function(){

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op,#price_div,#point_div").fadeOut();
        break;

        case "1":
            $(".op").fadeOut();
            $("#dp_branch_id_div,#txt_sub_no_div,#dp_repayment_div,#dp_has_rem_div,#dp_points_sign_div,#points_div,#dp_cost_sign_div,#txt_cost_div,#point_div,#price_div,#dp_type_div,#dp_sadad_type_div,#dp_billc_type_div,#dp_subsciber_status_div,#rep_type").fadeIn();
        break;

        case "2":
            $(".op").fadeOut();
            $("#dp_branch_id_div,#txt_sub_no_div,#dp_repayment_div,#dp_has_rem_div,#dp_points_sign_div,#points_div,#dp_cost_sign_div,#txt_cost_div,#point_div,#price_div,#dp_type_div,#dp_sadad_type_div,#dp_billc_type_div,#dp_subsciber_status_div,#rep_type").fadeIn();
            break;

        case "3":
            $(".op,#price_div,#point_div").fadeOut();
            $("#dp_branch_id_div,#txt_sub_no_div,#dp_point_type_div,#dp_billc_type_div,#dp_subsciber_status_div,#dp_status_div,#dp_points_sign_div,#points_div,#point_div,#rep_type").fadeIn();
        break;
        }
    }
    
});
</script>

