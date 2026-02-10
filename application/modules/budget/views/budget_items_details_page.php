<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/10/14
 * Time: 10:57 ص
 */

$TB_NAME= 'items_details';
$items_json= json_encode($items);

function select_items($all ,$id=0){
    $select= "<select class='special_items form-control' name='special_items[]' id='txt_special_items' class='form-control'>"
                ."<option value='0' >اختر البند</option>";

    foreach ($all as $row){
        if($row['NO']==$id)
            $selected= 'selected';
        else
            $selected= '';
        $select.= "<option {$selected} value='{$row['NO']}'>{$row['NAME']}</option>";
    }
    $select.= "</select>";
    return $select;
}

$adopt= 0;
$entry_user='';

echo AntiForgeryToken();
?>

<input name='exp_rev_no' type='hidden' value='<?=$exp_rev_no?>' />

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>البند</th>
        <th>الكمية</th>
        <th>السعر</th>
        <th>الاجمالي</th>
        <th>ملاحظات</th>
        <th>حذف</th>
    </tr>
    </thead>
    <tbody>
    <?php
        $count=0;
        foreach ($get_list as $row){
            $count++;
            $adopt= $row['ADOPT'];
            $entry_user= $row['ENTRY_USER'];

            echo "
                    <tr>
                        <td>$count<input name='no[]' type='hidden' value='{$row['NO']}' /></td>
                        <td><div class='col-sm-12'>".select_items($items ,$row['ITEM_NO'])."</div></td>
                        <td><input type='number' class='ccount' name='ccount[]' id='txt_ccount' value='{$row['CCOUNT']}' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>
                        <td><input type='number' class='price' name='price[]' id='txt_price' value='{$row['PRICE']}' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>
                        <td class='total'>".number_format($row['CCOUNT']*$row['PRICE'],2)."</td>
                        <td><input type='text' name='notes[]' id='txt_notes' value='{$row['NOTE']}' maxlength='500' style='width: 150px' ></td>
                        <td><a href='javascript:;' onclick='javascript:{$TB_NAME}_delete({$row['NO']}, {$exp_rev_no} );'> <i class='glyphicon glyphicon-remove'></i></a></td>
                    </tr>
                ";
        }
        if(count($get_list)>0){
            if($adopt==1 and $entry_user==$user_id)
                $can_update=1;
            else
                $can_update=0;
        } // else get can_update from Controller

    ?>
    </tbody>

    <tfoot>
    <tr>
        <?php if($can_update)
            echo "<th><a title='جديد' onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i></a></th>";
        else
            echo "<th></th>";
        ?>
        <th>الاجمالي</th>
        <th class='ccount'></th>
        <th class='price'></th>
        <th class='total'></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>

</table>

<script type='text/javascript'>
    $(document).ready(function() {
        totals('<?=$TB_NAME?>_tb');
        refresh_total('<?=$TB_NAME?>_tb');

        <?php
            if($can_update){
                echo "$('#{$TB_NAME}_save').show();";
                echo "$('.special_items').select2();";
            }else{
                echo "$('#{$TB_NAME}_save').hide();";
                echo "$('#{$TB_NAME}_tb :input').attr('disabled','disabled');";
                echo "$('#{$TB_NAME}_tb .special_items').attr('disabled','disabled');";
                echo "$('#{$TB_NAME}_tb tr td:last-child').hide();";
                echo "$('#{$TB_NAME}_tb tr th:last-child').hide();";
            }
        ?>

    });

    var count= <?=$count?> ;
    var items_json= <?=$items_json?> ;

    function <?=$TB_NAME?>_create() {
        count++ ;
        $("#<?=$TB_NAME?>_tb tbody").append(
            "<tr id='tr-"+count+"'>" +
                "<td>"+count+"<input name='no[]' type='hidden' value='0' /></td>" +
                "<td><div class='col-sm-12'><select class='special_items form-control' name='special_items[]' id='txt_special_items' class='form-control'><option value='0' >اختر البند</option></select></div></td>" +
                "<td><input type='number' class='ccount' name='ccount[]' id='txt_ccount' value='' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>" +
                "<td><input type='number' class='price' name='price[]' id='txt_price' value='' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>" +
                "<td class='total'>0.00</td>" +
                "<td><input type='text' name='notes[]' id='txt_notes' value='' maxlength='500' style='width: 150px' ></td>" +
                "<td></td>" +
            "</tr>"
        );

        refresh_total('<?=$TB_NAME?>_tb');

        $.each(items_json, function(i,item){
            $("#tr-"+count+" .special_items").append("<option value='"+item.NO+"' >"+item.NAME+"</option>");
        });

        $("#tr-"+count+" .special_items").select2();

        $('#<?=$TB_NAME?>_tb #tr-'+count+' .special_items').change(function(){
            if($(this).closest('tr').find(".price").val()=='' || $(this).closest('tr').find(".price").val()==0){
                for(var i=0; i<items_json.length; i++){
                    if(items_json[i].NO== $(this).val()){
                        $(this).closest('tr').find(".price").val( items_json[i].PRICE );
                        return false;
                    }
                }
            }
        });
    }

</script>
