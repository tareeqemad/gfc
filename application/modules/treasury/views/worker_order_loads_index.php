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
<form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form"
      novalidate="novalidate">
<div class="modal-body inline_form">
<div class="tbl_container">
<table class="table" id="donation_tb" data-container="container">
<thead>
<tr>

    <th>رقم المحول</th>
    <th>رقم ترتيب السكينة في المحول</th>
    <th>الاتجاه</th>
    <th>قياس التيار على مل فاز</th>
    <th>S</th>
    <th>T</th>
    <th>N</th>
    <th>RS:الجهد بين الفازات</th>
    <th>RT</th>
    <th>ST</th>
    <th>RN</th>
    <th>SN</th>
    <th>TN</th>
    <?php if (HaveAccess($delete_url_details) && ($isCreate || (isset($can_edit) ? $can_edit : false))) : ?>
        <th>الحذف</th>
    <?php endif; ?>
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

            <input name="adapter_serial[]" data-not-empty="true" type="hidden" value="<?= $workOrderData !=null ? $workOrderData['SOURCE_ID'] :'' ?>" id="h_txt_adapter_serial_<?= $count ?>" class="form-control">
            <input name="adapter_serial_name[]" data-not-empty="true" readonly id="txt_adapter_serial_<?= $count ?>" value="<?= $workOrderData !=null ? $workOrderData['SOURCE_ID_NAME'] :'' ?>" class="form-control">
        </td>
        <td><select name="partition_id[]" id="dp_partition_id_<?= $count ?>" data-curr="false" class="form-control"
                    data-val="true" data-val-required="حقل مطلوب">
                <option value="">-</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>

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

                <input name="adapter_serial[]" data-not-empty="true" type="hidden" value="<?= $row['ADAPTER_SERIAL'] ?>"
                       id="h_txt_adapter_serial_<?= $count ?>" class="form-control">
                <input name="adapter_serial_name[]" data-not-empty="true" readonly value="<?= $row['ADAPTER_SERIAL_NAME'] ?>"
                       id="txt_adapter_serial_<?= $count ?>" class="form-control">
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

            <?php if (HaveAccess($delete_url_details) && ($isCreate || (isset($can_edit) ? $can_edit : false))) : ?>
                <td>
                    <a onclick="javascript:delete_row_del('<?= $row['SER'] ?>','<?= $row['WORK_ORDER_ID'] ?>','<?= $row['ENTRY_USER'] ?>');"
                       href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>
                </td>

            <?php endif; ?>


        </tr>
    <?php
    }
}
?>
</tbody>
<tfoot>
<tr>

    <th>


        <a onclick="javascript:add_row(this,'input',false);" onfocus="javascript:add_row(this,'select,input:not(\'input[data-not-empty]\')',false);"
           href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>


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

</div>

<div style="clear: both;">
    <input type="hidden" id="h_data_search"/>
</div>
<div class="modal-footer">

    <?php
    if (HaveAccess($post_url) && ($isCreate || (isset($can_edit) ? $can_edit : false))) : ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
    <?php endif; ?>


</div>
</form>
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