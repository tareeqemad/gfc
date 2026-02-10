<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 02/11/15
 * Time: 11:31 ص
 */
$count = 0;
//$work_order_id = $this->uri->segment(3);
//echo $work_order_id;
$MODULE_NAME = 'technical';
$TB_NAME = 'Worker_Order_Loads';
$adapters_url = base_url('projects/adapter/public_index');
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'index' : 'index'));
$delete_url_details = base_url("$MODULE_NAME/$TB_NAME/delete_details");
$rs = $isCreate ? array() : $details[0];

echo AntiForgeryToken();


?>

    <script> var show_page = true; </script>

<div class="row">
<div class="toolbar">

    <div class="caption"><?= $title ?></div>

    <ul>
        <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
        </li>
    </ul>

</div>

<div class="form-body">

<div id="msg_container"></div>
<div id="container">

<div class="modal-body inline_form">
<div class="tbl_container">
<div class="form-group col-sm-1">
    <label class="control-label"> الرقم  </label>
    <div>
        <input type="text"  readonly  value="<?=$details != null ? $details[0]['ADAPTER_SERIAL'] : '' ?>"  name="adapter_serial" id="txt_adapter_serial" class="form-control">
    </div>
</div>
<div class="form-group col-sm-2">

    <label class="control-label"> اسم المحول</label>
    <div>
        <input type="text" name="adapter_name"  value="<?=  $details != null ? $details[0]['ADAPTER_SERIAL_NAME'] : '' ?>" readonly  data-val="true"  data-val-required="حقل مطلوب"  id="txt_adapter_name" class="form-control">
        <span class="field-validation-valid" data-valmsg-for="adapter_name" data-valmsg-replace="true"></span>

    </div>
</div>


<div></div>

<div style="clear: both;">
    <div class="btn-group">
        <button class="btn btn-warning dropdown-toggle" onclick="$('#donation_tb').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
    </div>

</div>
<table class="table" id="donation_tb" data-container="container">
<thead>
<tr>
    <th>التاريخ</th>
    <th>رقم ترتيب السكينة في المحول</th>
    <th>الاتجاه</th>
    <th  colspan="4">قياس التيار على مل فاز</th>

    <th  colspan="6">RS:الجهد بين الفازات</th>
    <th></th>

</tr>
<tr>

    <th style="width: 120px;" ></th>
    <th style="width: 120px;" ></th>
    <th style="width: 120px;" ></th>
    <th style="width: 70px;" >R</th>
    <th style="width: 70px;" >S</th>
    <th style="width: 70px;" >T</th>
    <th style="width: 70px;" >N</th>
    <th style="width: 70px;" >RS</th>
    <th style="width: 70px;" >RT</th>
    <th style="width: 70px;" >ST</th>
    <th style="width: 70px;" >RN</th>
    <th style="width: 70px;" >SN</th>
    <th style="width: 70px;" >TN</th>
    <th></th>

</tr>
</thead>

</tbody>
<input type="hidden" name="work_order_id" id="h_txt_work_order_id"
       value="<?php if (count($rs) > 0) echo $rs['WORK_ORDER_ID']; else echo $work_order_id; ?>" data-val="true"
       data-val-required="required">

<?php if (count($details) <= 0) { // ادخال ?>

    <tr>

        <td>
            <input type="hidden" name="ser[]" value="0"/>

            <input name="entry_date[]" type="text"  class="form-control"
                   value="">
        </td>
        <td><select name="partition_id[]" id="dp_partition_id_<?= $count ?>" data-curr="false" class="form-control"
                    data-val="true" data-val-required="حقل مطلوب">
                <option value="">-</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
            <span class="field-validation-valid" data-valmsg-for="partition_id[]" data-valmsg-replace="true"></td>
        <td>

            <select class="form-control" name="power_adapter_direction[]" id="dp_power_adapter_direction_<?= $count ?>"
                    data-val="true" data-val-required="حقل مطلوب">
                <option value="">-</option>
                <?php foreach ($power_adapter_direction as $row) : ?>
                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>

            <span class="field-validation-valid" data-valmsg-for="power_adapter_direction[]" data-valmsg-replace="true">
        </td>

        <td>
            <input name="measure_r[]" class="form-control" id="txt_measure_r_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="measure_s[]" class="form-control" id="txt_measure_s_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="measure_t[]" class="form-control" id="txt_measure_t_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="measure_n[]" class="form-control" id="txt_measure_n_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="voltage_rs[]" class="form-control" id="txt_voltage_rs_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="voltage_rt[]" class="form-control" id="txt_voltage_rt_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="voltage_st[]" class="form-control" id="txt_voltage_st_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="voltage_rn[]" class="form-control" id="txt_voltage_rn_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="voltage_sn[]" class="form-control" id="txt_voltage_sn_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>

        <td>
            <input name="voltage_tn[]" class="form-control" id="txt_voltage_tn_<?= $count ?>" data-val="true"
                   data-val-required="required"/>
        </td>
<td></td>

    </tr>
<?php
} else if (count($details) > 0) { // تعديل
    $count = -1;
    foreach ($details as $row) {
        ++$count + 1;
        ?>
        <tr>

            <td>
                <input type="hidden" name="ser[]" value="<?= $row['SER'] ?>"/>

                <input name="entry_date[]" type="text" readonly class="form-control"
                       value="<?= $row['ENTRY_DATE'] ?>">
            </td>
            <td><select name="partition_id[]" id="dp_partition_id_<?= $count ?>" data-curr="false" class="form-control"
                        data-val="true" data-val-required="حقل مطلوب">
                    <option value="">-</option>
                    <option value="1" <?PHP if ($row['PARTITION_ID'] == 1) {
                        echo " selected";
                    } ?>>1
                    </option>
                    <option value="2" <?PHP if ($row['PARTITION_ID'] == 2) {
                        echo " selected";
                    } ?>>2
                    </option>
                    <option value="3" <?PHP if ($row['PARTITION_ID'] == 3) {
                        echo " selected";
                    } ?>>3
                    </option>
                    <option value="4" <?PHP if ($row['PARTITION_ID'] == 4) {
                        echo " selected";
                    } ?>>4
                    </option>



                    <option <?= $row['PARTITION_ID'] == 5?'selected':'' ?> value="5">5</option>
                    <option <?= $row['PARTITION_ID'] == 6?'selected':'' ?>  value="6">6</option>
                    <option <?= $row['PARTITION_ID'] == 7?'selected':'' ?>  value="7">7</option>
                    <option <?= $row['PARTITION_ID'] == 8?'selected':'' ?>  value="8">8</option>
                    <option <?= $row['PARTITION_ID'] == 9?'selected':'' ?>  value="9">9</option>
                    <option <?= $row['PARTITION_ID'] == 10?'selected':'' ?>  value="10">10</option>
                    <option <?= $row['PARTITION_ID'] == 11?'selected':'' ?>  value="11">11</option>
                    <option <?= $row['PARTITION_ID'] == 12?'selected':'' ?>  value="12">12</option>


                </select>
                <span class="field-validation-valid" data-valmsg-for="partition_id[]" data-valmsg-replace="true"></td>
            <td>

                <select class="form-control" name="power_adapter_direction[]"
                        id="dp_power_adapter_direction_<?= $count ?>">
                    <option value="">-</option>
                    <?php foreach ($power_adapter_direction as $r) : ?>
                        <option value="<?= $r['CON_NO']; ?>" <?PHP if ($r['CON_NO'] == $row['PARTITION_DIRECTION']) {
                            echo " selected";
                        } ?>><?= $r['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>

            </td>

            <td>
                <input name="measure_r[]" value="<?= $row['MEASURE_R'] ?>" class="form-control"
                       id="txt_measure_r_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="measure_s[]" value="<?= $row['MEASURE_S'] ?>" class="form-control"
                       id="txt_measure_s_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="measure_t[]" value="<?= $row['MEASURE_T'] ?>" class="form-control"
                       id="txt_measure_t_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="measure_n[]" value="<?= $row['MEASURE_N'] ?>" class="form-control"
                       id="txt_measure_n_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="voltage_rs[]" value="<?= $row['VOLTAGE_RS'] ?>" class="form-control"
                       id="txt_voltage_rs_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="voltage_rt[]" value="<?= $row['VOLTAGE_RT'] ?>" class="form-control"
                       id="txt_voltage_rt_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="voltage_st[]" value="<?= $row['VOLTAGE_ST'] ?>" class="form-control"
                       id="txt_voltage_st_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="voltage_rn[]" value="<?= $row['VOLTAGE_RN'] ?>" class="form-control"
                       id="txt_voltage_rn_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="voltage_sn[]" value="<?= $row['VOLTAGE_SN'] ?>" class="form-control"
                       id="txt_voltage_sn_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="voltage_tn[]" value="<?= $row['VOLTAGE_TN'] ?>" class="form-control"
                       id="txt_voltage_tn_<?= $count ?>" data-val="true" data-val-required="required"/>
            </td>
<td></td>

        </tr>
    <?php
    }
}
?>
</tbody>
</table>
</div>

</div>

<div style="clear: both;">
    <input type="hidden" id="h_data_search"/>
</div>


</div>
</div>

<?php


$notes_url = notes_url();

$scripts = <<<SCRIPT
<script type="text/javascript">
var count = 0;

  reBind();
function reBind(){


        $('input[name="adapter_serial_name[]"]').click("focus",function(e){
            _showReport('$adapters_url/'+$(this).attr('id')+ $('#h_data_search').val() );
        });





    }

 function delete_row_del(id,work_id,user_entry)
 {
  if(confirm('هل تريد بالتأكيد اجراء عملية الحذف ؟!!')){
  var values= {id:id,work_id:work_id,user_entry:user_entry};
                     get_data('{$delete_url_details}',values ,function(data){
                    //alert(data);
                        if (data=='1'){

                          success_msg('تمت عملية الحذف بنجاح');
                          get_to_link(window.location.href);
                        }
                        else if (data=='-1'){

danger_msg('لم يتم الحذف لابد من وجود محول واحد على الاقل');
                        }
                        else{
                    danger_msg('لم يتم الحذف',data);
                }

                    },'html');




         }
     }




function AfterCreateRow(tr){

    $('input[name="ser[]"]',tr).val('0');
     $('input[name="partition_id[]"]',tr).val('');
     $('input[name="power_adapter_direction[]"]',tr).val('');

}

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ ؟!')){

            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');

            ajax_insert_update(form,function(data){
               if(data>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });


</script>

SCRIPT;

sec_scripts($scripts);

?>