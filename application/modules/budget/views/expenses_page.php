<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/09/14
 * Time: 11:24 ص
 */

$TB_NAME= 'expenses';

$details_total= 1;

if($item_data[0]['HAS_DETAILS'] == 1) // has_details
    $has_details=1;
else
    $has_details=0;

?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>الشهر</th>
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
    $order= true;
    if(count($get_list)<=12 ){
        for ($i=0; $i< count($get_list)-1;$i++) {
            if ($get_list[$i]['MMONTH'] > $get_list[$i+1]['MMONTH']) {
                $order= false;
            }
        }

        if($order){
            $m= 12;
            for ($i=0; $i< $m;$i++){
                if(@$get_list[$i]['MMONTH']== ++$count){

                    if(@$get_list[$i]['ADOPT']== 1 and @$get_list[$i]['ENTRY_USER']==$user_id and HaveAccess(base_url("budget/{$TB_NAME}/delete")))
                        $del= "<a href='javascript:;' onclick='javascript:{$TB_NAME}_delete({$get_list[$i]['NO']});'> <i class='glyphicon glyphicon-remove'></i></a>";
                    else
                        $del='';

                    if(@$get_list[$i]['ADOPT']== 1 and @$get_list[$i]['ENTRY_USER']==$user_id and HaveAccess(base_url("budget/{$TB_NAME}/receive_data"))){
                         // اذا كان السجل مدخل وغير معتمد والمستخدم الحالي نفس المستخدم المدخل وله صلاحيات تعديل

                        if( $has_details and $get_list[$i]['CCOUNT']*$get_list[$i]['PRICE'] != $get_list[$i]['DETAILS_TOTAL'])
                            $details_total=0;

                        echo "
                                <tr>
                                    <td>
                                        <input name='no[]' type='hidden' value='{$get_list[$i]['NO']}' />
                                        <input name='entry_user[]' type='hidden' value='{$get_list[$i]['ENTRY_USER']}' />
                                        $count
                                    </td>
                                    <td><input name='mmonth[]' type='hidden' value='{$get_list[$i]['MMONTH']}' />".months($get_list[$i]['MMONTH'])."</td>
                                    <td><input type='number' class='ccount' name='ccount[]' id='txt_ccount' value='{$get_list[$i]['CCOUNT']}' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>
                                    <td><input type='number' class='price' name='price[]' id='txt_price' value='{$get_list[$i]['PRICE']}' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>
                                    <td class='total'>".number_format($get_list[$i]['CCOUNT']*$get_list[$i]['PRICE'],2)."</td>
                                    <td><input type='text' name='notes[]' id='txt_notes' value='{$get_list[$i]['NOTES']}' maxlength='1000' ></td>";
                                    if($has_details)
                                        echo "<td><a onclick='javascript:{$TB_NAME}_details_get({$get_list[$i]['NO']}, 1);' href='javascript:;'><i class='glyphicon glyphicon-list'></i>".number_format($get_list[$i]['DETAILS_TOTAL'],2)."</a></td>";
                                    echo
                                    "<td><input name='adopt[]' type='hidden' value='{$get_list[$i]['ADOPT']}' />".record_case($get_list[$i]['ADOPT'])."</td>
                                    <td>$del</td>
                                </tr>
                        ";

                    }else{
                        // عرض البيانات فقط بدون امكانية التعديل
                        echo "
                                <tr>
                                    <td>
                                        <input name='no[]' type='hidden' value='{$get_list[$i]['NO']}' />
                                        <input name='entry_user[]' type='hidden' value='{$get_list[$i]['ENTRY_USER']}' />
                                        $count
                                    </td>
                                    <td><input name='mmonth[]' type='hidden' value='{$get_list[$i]['MMONTH']}' />".months($get_list[$i]['MMONTH'])."</td>
                                    <td><input type='hidden' class='ccount' name='ccount[]' id='txt_ccount' value='{$get_list[$i]['CCOUNT']}' />{$get_list[$i]['CCOUNT']}</td>
                                    <td><input type='hidden' class='price' name='price[]' id='txt_price' value='{$get_list[$i]['PRICE']}' />{$get_list[$i]['PRICE']}</td>
                                    <td class='total'>".number_format($get_list[$i]['CCOUNT']*$get_list[$i]['PRICE'],2)."</td>
                                    <td><input type='hidden' name='notes[]' id='txt_notes' value=''  />{$get_list[$i]['NOTES']}</td>";
                                    if($has_details)
                                        echo "<td><a onclick='javascript:{$TB_NAME}_details_get({$get_list[$i]['NO']}, 0);' href='javascript:;'><i class='glyphicon glyphicon-list'></i>".number_format($get_list[$i]['DETAILS_TOTAL'],2)."</a></td>";
                                    echo
                                    "<td><input name='adopt[]' type='hidden' value='{$get_list[$i]['ADOPT']}' />".record_case($get_list[$i]['ADOPT'])."</td>
                                    <td>$del</td>
                                </tr>
                        ";
                    }
                } else { // سجل جديد فارغ
                    $i--;
                    $m--;
                    echo "
                            <tr>
                                <td>
                                    <input name='no[]' type='hidden' value='0' />
                                    <input name='entry_user[]' type='hidden' value='0' />
                                    $count
                                </td>
                                <td><input name='mmonth[]' type='hidden' value='$count' />".months($count)."</td>
                                <td><input type='number' class='ccount' name='ccount[]' id='txt_ccount' value='' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>
                                <td><input type='number' class='price' name='price[]' id='txt_price' value='' maxlength='15' min='0' max='999999999999999' style='width:80px;' ></td>
                                <td class='total'>0.00</td>
                                <td><input type='text' name='notes[]' id='txt_notes' value='' maxlength='1000' ></td>";
                                if($has_details) echo "<td></td>";
                                echo
                                "<td><input name='adopt[]' type='hidden' value='0' /></td>
                                <td></td>
                            </tr>
                        ";
                }
            }
        }else echo "خطأ في ترتيب النتائج";
    }else echo "خطأ في الاستعلام";

    ?>
    </tbody>

    <tfoot>
    <tr>
        <th><input type="hidden" name="h_total" id="h_total" value="0"></th>
        <th>الاجمالي</th>
        <th></th>
        <th></th>
        <th class='total'></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>

</table>

<script type="text/javascript" >
    $(document).ready(function() {
        <?php
            if(!$details_total)
                echo "warning_msg('تنبيه','الاجمالي لا يساوي تفاصيل البند..');";
        ?>

        totals('<?=$TB_NAME?>_tb');

        $('#<?=$TB_NAME?>_tb .ccount').change(function(){
            if($(this).closest('tr').find(".price").val()==''){
                $(this).closest('tr').find(".price").val( $('#item_price').val() );
            }
        });

        refresh_total('<?=$TB_NAME?>_tb');

    });

</script>