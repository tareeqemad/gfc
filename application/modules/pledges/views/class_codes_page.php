<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/05/15
 * Time: 11:02 ص
 */
$MODULE_NAME= 'pledges';
$TB_NAME= 'class_codes';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$count =0;
$can_print =0;
?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>الكود القديم</th>
            <th>الكود الجديد</th>
            <th>رقم الصنف</th>
            <th>اسم الصنف</th>
            <th>الوحدة</th>
            <th>السعر</th>
            <th>الحالة</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($get_list as $row) :
        if($row['CODE_CASE']==1){
            $can_print= 1;
        }
        ?>
        <tr  data-id="<?=$row['SER']?>"
                                    <?php if( HaveAccess($edit_url)){?>
                                    ondblclick="javascript:class_codes_get('<?=$row['CLASS_CODE_SER']?>')"
                                    <?php  }?>
                                       >
            <td><?=++$count?></td>
            <td><?=$row['CLASS_CODE_SER']?></td>
            <td><?=$row['BARCODE']?></td>
            <td><?=$row['CLASS_ID']?></td>
            <td><?=$row['CLASS_ID_NAME']?></td>
            <td><?=$row['CLASS_UNIT_NAME']?></td>
            <td><?=$row['PRICE']?></td>
            <td><?=$row['CODE_CASE_NAME']?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script type="text/javascript" >
    $(document).ready(function() {
        if(<?=$can_print?>)
            $('#btn_print').prop('disabled',0);
    });
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
