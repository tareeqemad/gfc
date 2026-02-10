<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/03/22
 * Time: 08:10 ص
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'Car_maintenance_workshop';
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$count=1;
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 5%">م</th>
            <th style="width: 15%">اسم الصنف</th>
            <th style="width: 7%">العدد</th>
            <th style="width: 7%">الوحدة</th>
            <th style="width: 7%">حالة الصنف</th>
            <th style="width: 7%">المبلغ</th>
            <th style="width: 7%">الكمية المنجزة</th>
            <th style="width: 7%">الكمية المرجعة</th>
            <th style="width: 7%">المورد</th>
            <th style="width: 7%">رقم الفاتورة</th>
            <th style="width: 7%">تاريخ الفاتورة</th>
            <th style="width: 10%">رقم الارسالية</th>
            <th style="width: 10%">الاجراء</th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" ></i></td>
                <td>
                    <input name="txt_ser_det[]" id="txt_ser_det<?=$count?>" class="form-control" value="0"  style="text-align: center" readonly>
                </td>

                <td>
                    <select name="class_name[]" id="dp_class_id<?=$count?>" class="form-control sel2" required>
                        <option value="">_________</option>
                        <?php foreach($spare_parts as $row) :?>
                            <option  value="<?= $row['CLASS_NO'] ?>"><?= $row['CLASS_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <input  name="quantity[]" data-val="true" data-val-required="required" class="form-control" id="txt_quantity<?=$count?>" style="text-align: center" required />
                </td>

                <td>
                    <select name="class_unit[]" id="dp_class_unit<?=$count?>" class="form-control sel2" >
                        <option value="">_________</option>
                        <?php foreach($class_unit as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <select name="class_status[]" id="dp_class_status<?=$count?>" class="form-control sel2" >
                        <option value="">_________</option>
                        <?php foreach($class_status as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <input  name="price[]"  class="form-control" id="txt_price<?=$count?>" style="text-align: center" required />
                </td>

                <td>
                    <input  name="complementing_amount[]"  class="form-control" id="txt_complementing_amount<?=$count?>" style="text-align: center" />
                </td>

                <td>
                    <input  name="review_amount[]"  class="form-control" id="txt_review_amount<?=$count?>" style="text-align: center" />
                </td>
                <td>
                    <select name="supplier_no[]" id="dp_supplier_no<?=$count?>" class="form-control sel2" >
                        <option value="">_________</option>
                        <?php foreach($suppliers as $row) :?>
                            <option  value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input  name="bill_no[]"  class="form-control" id="txt_bill_no<?=$count?>" style="text-align: center" />
                </td>
                <td>
                    <input  name="bill_date[]"  class="form-control the_date" id="txt_bill_date<?=$count?>"  <?= $date_attr ?> style="text-align: center" />
                </td>
                <td>
                    <input  name="consignment_no[]"  class="form-control" id="txt_consignment_no<?=$count?>"  style="text-align: center" />
                </td>
                <td class="text-center">
                    <a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a>
                </td>
            </tr>

            <?php
        }

        else if(count($details) > 0) { // تعديل
            $count = -1;

            foreach($details as $row) {

                ?>

                <tr>

                    <td><i class="glyphicon glyphicon-sort" ></i></td>

                    <td>
                        <input name="txt_ser_det[]" id="txt_ser_det<?=$count?>" class="form-control" value="<?=$row['SER']?>"  style="text-align: center" readonly>
                    </td>

                    <td>
                        <input type="hidden" name="class_name[]" value="<?=$row['CLASS_ID']?>" />
                        <input  class="form-control" id="dp_class_id<?=$count?>"  value="<?=$row['CLASS_ID_NAME']?>" style="text-align: center" disabled  />
                    </td>

                    <td>
                        <input  name="quantity[]"  class="form-control" id="txt_quantity<?=$count?>"  value="<?=$row['QUANTITY']?>" style="text-align: center" />
                    </td>

                    <td>
                        <input type="hidden" name="class_unit[]" value="<?=$row['CLASS_UNIT']?>" />
                        <input    class="form-control" id="dp_class_unit<?=$count?>"  value="<?=$row['CLASS_ID_UNIT']?>" style="text-align: center"  disabled/>
                    </td>

                    <td>
                        <input type="hidden" name="class_status[]" value="<?=$row['CLASS_STATUS']?>" />
                        <input class="form-control" id="dp_class_status<?=$count?>"  value="<?=$row['CLASS_ID_STATUS']?>" style="text-align: center"  disabled/>
                    </td>

                    <td>
                        <input  name="price[]"  class="form-control" id="txt_price<?=$count?>" value="<?=$row['PRICE']?>" style="text-align: center" />
                    </td>

                    <td>
                        <input  name="complementing_amount[]"  class="form-control" id="txt_complementing_amount<?=$count?>" value="<?=$row['COMPLEMENTING_AMOUNT']?>" style="text-align: center" />
                    </td>

                    <td>
                        <input  name="review_amount[]"  class="form-control" id="txt_review_amount<?=$count?>" value="<?=$row['REVIEW_AMOUNT']?>" style="text-align: center" />
                    </td>
                    <td>
                        <select name="supplier_no[]" id="dp_supplier_no<?=$count?>" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($suppliers as $rows) : ?>
                                <option <?=$row['SUPPLIER_NO']==$rows['CUSTOMER_ID']?'selected':''?> value="<?= $rows['CUSTOMER_ID'] ?>"><?= $rows['CUSTOMER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input  name="bill_no[]"  class="form-control" id="txt_bill_no<?=$count?>" value="<?=$row['BILL_NO']?>" style="text-align: center" />
                    </td>
                    <td>
                        <input  name="bill_date[]"  class="form-control the_date" id="txt_bill_date<?=$count?>" value="<?=$row['BILL_DATE']?>" style="text-align: center" />
                    </td>
                    <td>
                        <input  name="consignment_no[]"  class="form-control" id="txt_consignment_no<?=$count?>" value="<?=$row['CONSIGNMENT_NO']?>" style="text-align: center" />
                    </td>
                    <td></td>

                </tr>
                <?php
            }
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) >= 0 and  $adopt_det == 20  ) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">


</script>