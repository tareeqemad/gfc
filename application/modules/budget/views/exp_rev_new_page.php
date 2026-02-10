<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 25/02/15
 * Time: 12:18 م
 */

$count =0;

if(!isset($total['TOTAL_UPDATE'])){
    die();
}elseif($total['TOTAL_UPDATE']==null or $total['TOTAL_UPDATE']=='' or $total['TOTAL_UPDATE']<=0){
    $total['TOTAL_UPDATE']=0;
}

$attachment_data_url= base_url("budget/exp_rev_new/attachment_get");

$show_attachment=0;
if (HaveAccess($attachment_data_url))
    $show_attachment=1;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>القسم</th>
            <th>البند</th>
            <th>الشهر</th>
            <th>الكمية</th>
            <th>السعر</th>
            <th>الكمية الجديدة</th>
            <th>السعر الجديد</th>
            <th>الاجمالي</th>
            <th>المرفقات</th>
            <th>الحالة</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($get_list as $row) :
            if($row['NEW_ADOPT']==1){
                $readonly= '';
            }else{
                $readonly= 'readonly';
            }
        ?>
        <tr>
            <td><?=++$count?>
                <input name='no[]' type='hidden' value='<?=$row['NO'].','.$row['ITEM_NO']?>' />
            </td>
            <td><?=$row['DEPARTMENT_NAME']?></td>
            <td><?=$row['ITEM_NAME']?></td>
            <td><?=$row['MMONTH']?></td>
            <td><?=$row['CCOUNT']?></td>
            <td><?=$row['PRICE']?></td>
            <td><input class='form-control ccount' <?=$readonly?> name='new_count[]' value='<?=$row['NEW_COUNT']?>' style='width:120px;' ></td>
            <td><input class='form-control price' <?=$readonly?> name='new_price[]' value='<?=$row['NEW_PRICE']?>' style='width:120px;'  ></td>
            <td class='total'><?=number_format($row['NEW_COUNT']*$row['NEW_PRICE'],2)?></td>
            <td> <?php if($show_attachment){ ?> <a class="a_attachment" href="javascript:;" onclick="javascript:attachment_get('<?=$row['ITEM_NO']?>','<?=$row['DEPARTMENT_NO']?>');">  <i class="glyphicon glyphicon-file"></i>  </a> <?php } ?> </td>
            <td><?=$row['NEW_ADOPT_NAME']?></td>
        </tr>
        <?php endforeach;?>
        </tbody>

        <tfoot>
        <tr>
            <th></th>
            <th>الاجمالي</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><input type="hidden" id="h_total" value="0" /></th>
            <th class='total'></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript" >
    $(document).ready(function() {
        $('#txt_total_update').val(num_format(<?=$total['TOTAL_UPDATE']?>) );
        $('#h_total_update').val(<?=$total['TOTAL_UPDATE']?>);
        $('#txt_total').val(num_format(<?=$total['TOTAL']?>) );

        totals('page_tb');
        refresh_total('page_tb');

        $('.ccount, .price').change(function(){
            if( parseFloat($('#h_total_update').val()) < ( parseFloat($('tfoot #h_total').val()) ).toFixed(4)  )
                alert('لقد تجاوزت الحد الاعلى المسموح به');
        });

    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>

