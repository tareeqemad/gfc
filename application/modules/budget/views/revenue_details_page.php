<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/10/14
 * Time: 01:09 م
 */

$TB_NAME= 'revenue_details';
$total_price= $total[0]['TOTAL'];

$collection_type_json= json_encode($collection_type);

function select_collection_type($all ,$id=0){
    $select= "<select class='collection_type form-control' name='collection_type[]' id='txt_collection_type' class='form-control'>"
        ."<option value='0' >اختر نوع التحصيل</option>";

    foreach ($all as $row){
        if($row['CON_NO']==$id)
            $selected= 'selected';
        else
            $selected= '';
        $select.= "<option {$selected} value='{$row['CON_NO']}'>{$row['CON_NAME']}</option>";
    }
    $select.= "</select>";
    return $select;
}

function adopt_desc($n){
    if($n==1)
        return "غير معتمد";
    else
        return "معتمد";

}

?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م<input id='total_price' type='hidden' value='<?=$total_price?>' /></th>
        <th>نوع التحصيل</th>
        <th>النسبة</th>
        <th>المبلغ</th>
        <th>ملاحظات</th>
        <th>المدخل</th>
        <th>حالة السجل</th>
    </tr>
    </thead>


    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        $count++;
        $entry_user= $row['ENTRY_USER'];
        $adopt= $row['ADOPT'];

        echo "
                <tr>
                    <td>$count<input name='no[]' type='hidden' value='{$row['NO']}' /></td>
                    <td><div class='col-sm-12'>".select_collection_type($collection_type ,$row['COLLECTION_TYPE'])."</div></td>
                    <td><input type='number' class='rate' name='rate[]' id='txt_rate' value='{$row['RATE']}' min='0' max='100' step='0.001' style='width:100px;' ></td>
                    <td class='total'>".number_format($row['RATE']*$total_price/100 ,2)."</td>
                    <td><input type='text' name='note[]' id='txt_note' value='{$row['NOTE']}' maxlength='500' ></td>
                    <td>".get_user_name($row['ENTRY_USER'])."</td>
                    <td>".adopt_desc($row['ADOPT'])."</td>
                </tr>
            ";
    }

    $can_update=1;
    if(count($get_list)>0 and ($entry_user!=$user_id or $adopt!=1) )
        $can_update=0;

    ?>
    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>الاجمالي</th>
        <th class='rate'></th>
        <th class='total'></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>

</table>

<script type='text/javascript'>
    $(document).ready(function() {
        if(<?=$can_update?>)
            $('.toolbar ul').show();
        else
            $('.toolbar ul').hide();

        $('#container #total').text('المبلغ الاجمالي: '+<?=$total_price?>);
        $('.collection_type').select2();
        totals('<?=$TB_NAME?>_tb');
        refresh_total('<?=$TB_NAME?>_tb');

        $('tbody .rate').change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(3));
        });
    });

    var count= <?=$count?> ;
    var collection_json= <?=$collection_type_json?> ;

    function <?=$TB_NAME?>_create() {
        count++ ;
        $("#<?=$TB_NAME?>_tb tbody").append(
            "<tr id='tr-"+count+"'>" +
                "<td>"+count+"<input name='no[]' type='hidden' value='0' /></td>" +
                "<td><div class='col-sm-12'><select class='collection_type form-control' name='collection_type[]' id='txt_collection_type' class='form-control'><option value='0' >اختر نوع التحصيل</option></select></div></td>" +
                "<td><input type='number' class='rate' name='rate[]' id='txt_rate' value='' min='0' max='100' step='0.001' style='width:100px;' ></td>" +
                "<td class='total'>0.00</td>" +
                "<td><input type='text' name='note[]' id='txt_note' value='' maxlength='500' ></td>" +
                "<td></td>" +
                "<td></td>" +
            "</tr>"
        );

        refresh_total('<?=$TB_NAME?>_tb');

        $.each(collection_json, function(i,item){
            $("#tr-"+count+" .collection_type").append("<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>");
        });
        $("#tr-"+count+" .collection_type").select2();

        $("#tr-"+count+" .rate").change(function(){
            $(this).val(parseFloat($(this).val()).toFixed(3));
        });
    }

</script>