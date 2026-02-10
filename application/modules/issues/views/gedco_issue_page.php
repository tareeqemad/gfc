<?php

$MODULE_NAME= 'issues';
$TB_NAME= 'gedco_issues';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$count = $offset;
$col= ceil(12/count($curr_page_rows));
$cntr_col=1;
function class_name($number)
{
    if($number % 2 == 0)  {
       return 'alert-success';
    } elseif ($number % 3 == 0) {
        return 'alert-info';
    } elseif ($number % 1 == 0) {
        return 'alert-warning';
    }
    else {
        return 'alert-danger';

    }
}
?>



<div class="tbl_container">



            <div class="container">
                <div class="row">
                    <?php foreach($curr_page_rows as $curr_row) :?>
                    <div class="col-md-<?=$col?>">
                        <div class="alert <?=class_name($cntr_col)?> h4" role="alert" >
                            <?='اجمالي قيمة الدعوى بال'.$curr_row['CURRENCY_NAME']?>: <?=$curr_row['ISSUE_VALUE']?>
                        </div>

                    </div>

                    <?php $cntr_col ++; endforeach;?>
                    </div>
                </div>
            </div>







    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>اسم المدعي</th>
            <th>وكيله</th>

            <th>رقم الدعوى</th>
            <th>اسم المحكمة</th>
            <th>نوع الدعوى</th>
            <th>قيمة الدعوة</th>
            <th>حالة القضية</th>
            <th>حالة الاعتماد</th>
            <th>الفرع</th>
            <?php if (HaveAccess(base_url("issues/gedco_issues/get_issue_info/")))  {?> <th>عرض/تحرير</th> <?php } ?>
            <!--<th>حذف</th>-->

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr >
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr id="tr_<?=$row['SER']?>" >
                <td><?=$count++;?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['AGENT']?></td>
                <td><?=$row['ISSUE_NO'].'/'.$row['ISSUE_YEAR']?></td>
                <td><?=$row['N_COURT_NAME']?></td>
                <td><?=$row['ISSUE_TYPE']?></td>
                <td><?=$row['ISSUE_VALUE'].' '.$row['CURRENCY_NAME']?></td>
                <td><?=$row['STATUS_NAME']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['ISSUE_BRANCH_NAME']?></td>
                <?php if((HaveAccess(base_url("issues/gedco_issues/get_issue_info/")))){?>
                    <td>  <a href="<?=base_url("issues/gedco_issues/get_issue_info/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php } ?>


            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>
<script>

</script>
