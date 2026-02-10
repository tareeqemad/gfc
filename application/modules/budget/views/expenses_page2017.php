<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 29/09/16
 * Time: 01:54 م
 */


$TB_NAME= 'expenses';

$details_total= 1;
/*
if($item_data[0]['HAS_DETAILS'] == 1) // has_details
    $has_details=1;
else*/
$has_details=0;

?>


<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>اسم البند</th>
        <th>سعر البند</th>
        <th>الوحدة</th>
        <th>الكمية</th>
        <th>السعر</th>
        <th>الاجمالي</th>
        <th>ملاحظات</th>
        <?php
        if($has_details) echo "<th>التفاصيل</th>";
        ?>
        <th>حالة السجل</th>
        <th>حذف</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        if(@$row['ADOPT']== 1 and @$row['ENTRY_USER']==$user_id and HaveAccess(base_url("budget/{$TB_NAME}/delete")))
            $del= "<a href='javascript:;' onclick='javascript:{$TB_NAME}_delete({$row['EXP_REV_NO']});'> <i class='glyphicon glyphicon-remove'></i></a>";
        else
            $del='';

        $count++;
        ?>
        <tr>
            <td><?=$count?>
                <input name='no[]' type='hidden' value='<?=$row['EXP_REV_NO']?>' />
                <input name='entry_user[]' type='hidden' value='<?=$row['ENTRY_USER']?>' />
            </td>
            <td><?=$row['NAME']?> <input type="hidden" class="txt_item_no" value="<?=$row['NO']?>" name="item_no[]" id="txt_item_no"></td>
            <td><?=$row['ITEM_PRICE']?><input type="hidden" class="i_price" value="<?=$row['ITEM_PRICE']?>" name="i_price[]" id="txt_i_price"></td>
            <td><?=$row['UNIT']?></td>
            <td><input type='number'  <?PHP if ($row['UNIT_NO']==8) echo "readonly";?> class='ccount' name='ccount[]' id='txt_ccount' value='<?PHP if ($row['UNIT_NO']==8) echo "1"; else echo $row['CCOUNT']?>' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>
            <td><input type='number' class='price' name='price[]' id='txt_price' value='<?=$row['RPRICE']?>' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>

            <td class='total'><?=number_format($row['CCOUNT']*$row['RPRICE'],2)?></td>
            <td><input type='text' name='notes[]' id='txt_notes' value='<?=$row['NOTES']?>' maxlength='1000' ></td>


            <?php
            if($has_details)
                echo "<td><a onclick='javascript:{$TB_NAME}_details_get({$row['NO']}, 1);' href='javascript:;'><i class='glyphicon glyphicon-list'></i>".number_format($row['DETAILS_TOTAL'],2)."</a></td>";

            ?>
            <td><input name='adopt[]' type='hidden' value='<?=$row['ADOPT']?>' /><?=record_case($row['ADOPT'])?></td>
            <td><?=$del?></td>
        </tr>
        ";
        <!--  <td></td>
          <td></td>-->
        </tr>

    <?PHP } ?>

    </tbody>

    <tfoot>
    <tr>
        <th><input type="hidden" name="h_total" id="h_total" value="0"> </th>
        <th></th>
        <th></th>
        <th>الاجمالي</th>
        <th></th>
        <th></th>
        <th class='total'></th>
        <th></th>
        <th></th>  <th></th>
    </tr>
    </tfoot>

</table>

<script type="text/javascript" >
    $(document).ready(function() {


        totals('<?=$TB_NAME?>_tb');

        $('#<?=$TB_NAME?>_tb .ccount').change(function(){
            // alert($('#item_price').val());
            if($(this).closest('tr').find(".price").val()==''){
                $(this).closest('tr').find(".price").val(  $(this).closest('tr').find(".i_price").val() );
                //  $(this).closest('tr').find(".price").val( $('#item_price').val() );
            }
        });

        refresh_total('<?=$TB_NAME?>_tb');

    });

</script>

