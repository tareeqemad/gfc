<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 29/12/14
 * Time: 09:37 ص
 */

$count = 1;
$create_url =$type == 1? base_url('treasury/checks_processing/create') :  base_url('payment/out_checks_processing/create') ;
$cancel_income = base_url('treasury/checks_processing/cancel');
$cancel_outcome =base_url('payment/out_checks_processing/cancel');

function cancel_url($thisYear,$checkId,$Id,$type,$case,$cancel_income,$cancel_outcome){

    $url_in ="{$cancel_income}/{$checkId}/{$Id}";
    $url_out ="{$cancel_outcome}/{$checkId}/{$Id}";

    $output = (($case > 1 && $type == 1 && HaveAccess($cancel_income)) || ($thisYear !=2 && $case ==1 && HaveAccess($cancel_income) && $type == 1))?$url_in :
        ((($case >= 1 && $type == 2 && HaveAccess($cancel_outcome)) || ($thisYear !=2 && $case ==1 && HaveAccess($cancel_outcome) && $type == 2))?$url_out:"");

    if($output != '')
        return "<a href='{$output}' class='btn-xs btn-danger'  >إلغاء اخر حركة</a>";

}

?>
<?php if(count($rows) > 0) : ?>
    <div class="tbl_container">
        <table class="table" id="cashTbl" data-container="container">
            <thead>
            <tr>

                <th  >#</th>
                <th >رقم الشيك</th>
                <th>اسم صاحب الشيك</th>
                <th  >البنك</th>
                <th class="price">المبلغ</th>
                <th>العملة</th>
                <th  > تاريخ الاستحقاق </th>
				<th>السنة</th>
                <th>حالة السند  </th>

                <th style="width: 183px;"> </th>

            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr <?= $row['CHECKS_CASE'] == 0 || $row['CHECKS_CASE'] == 6?'class="case_0"':"" ?> >
                    <td><?= $count ?></td>
                    <td><?= $row['CHECK_ID'] ?></td>
                    <td><?= $row['CHECK_CUSTOMER'] ?></td>
                    <td><?= $row['CHECK_BANK_ID_NAME'] ?></td>
                    <td><?= $row['CRIDET'] ?></td>
                    <td><?= $row['CURR_ID_NAME'] ?></td>
                    <td><?= $row['CHECK_DATE'] ?></td>
				    <td><?=  $row['THISYEAR'] == 2 ? 'مرحل' : ''?></td>
                    <td><?= $row['CHECKS_CASE_NAME'] ?></td>

                    <td class="align-right">
                        <?php if($row['CHECKS_CASE'] != 0 && $row['CHECKS_CASE'] != 6) : ?>
                            <a href="<?= "{$create_url}/{$row['SEQ']}" ?>" class="btn-xs btn-success" >معالجة</a>
                            <?= cancel_url($row['THISYEAR'],$row['CHECK_ID'],$row['SEQ'],$row['CHECK_TYPE'],$row['CHECKS_CASE'],$cancel_income,$cancel_outcome) ?>


                        <a href="javascript:;" onclick="javascript:withdraw(<?= $row['SEQ'] ?>);" class="btn-xs btn-warning" type="button">سحب</a>
                        <?php endif; ?>

                        <?php if( HaveAccess('payment/checks_portfolio/delete')): ?>
                            <a href="javascript:;" onclick="javascript:delete_check(this,<?= $row['SEQ'] ?>);" class="btn-xs btn-danger" type="button">حذف</a>
                        <?php endif; ?>

                    </td>
                </tr>
                <?php $count++; ?>

            <?php endforeach;?>
            </tbody>
        </table>
    </div>


<?php endif; ?>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>