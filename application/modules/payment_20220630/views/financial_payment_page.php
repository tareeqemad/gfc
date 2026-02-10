<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 06/11/14
 * Time: 09:08 ص
 */
$count = $offset;
$print_url = base_url('payment/financial_payment/print_per');
$report_url = base_url("JsperReport/showreport?sys=financial/treasury");

?>
<div class="tbl_container">
    <table class="table" id="chainsTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th > رقم السند </th>
            <th > رقم النموذج </th>
            <th >التاريخ</th>
            <th>حساب المستفيد</th>
            <th>المستفيد</th>
            <th > نوع الصرف</th>
            <th > رقم الشيك/الحوالة </th>
            <th > العملة</th>
            <th style="width: 60px">سعر العملة</th>
            <th  class="price" >المبلغ </th>

            <th >المستلم</th>
            <th>تاريخ التسليم</th>
            <th >المدخل</th>
            <th>رقم القيد</th>


            <th>البيان</th>
            <th>الحالة</th>
            <th style="width: 160px"><i class="icon icon-print"></i> </th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($payments as $row) :?>
            <tr ondblclick="javascript:get_to_link('<?= base_url('payment/financial_payment/get/').'/'.$row['FINANCIAL_PAYMENT_ID'].'/'.( isset($action)?$action.'/':'').(isset($case)?$case:'') ?>');"  class="case_<?= $row['FINANCIAL_PAYMENT_CASE'] ?>">

                <td><?= $count ?></td>
                <td><?= $row['ENTRY_SER'] ?></td>
                <td><?= $row['SER'] ?></td>
                <td><?= $row['FINANCIAL_PAYMENT_DATE'] ?></td>
                <td><?= $row['CUSTOMER_ID_NAME'] ?></td>
                <td><?= $row['CHECK_CUSTOMER'] ?></td>
                <td><?= $row['PAYMENT_TYPE_NAME'] ?></td>
                <td><?=  $row['PAYMENT_TYPE'] == 2 ?$row['CHECK_ID'] :$row['TRANSER_ID'] ?></td>

                <td><?= $row['CURR_ID_NAME'] ?></td>
                <td><?= $row['CURR_VALUE'] ?></td>
                <td class="price"><?= n_format($row['TOTAL']) ?> </td>
                <td><?= $row['RECEIPT_CUSTOMER_NAME'] ?></td>
                <td><?= $row['RECEIPT_DATE'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
                <td>
                    <?php if($row['QEED_NO'] > 0) : ?>
                        <a id="source_url" href="<?= base_url("financial/financial_chains/get/{$row['QEED_NO']}/index?type=4") ?>"  target="_blank"><?= $row['QEED_NO'] ?></a>
                    <?php endif; ?>
                </td>

                <td><?= $row['HINTS'] ?></td>
                <td><?= f_payment_case($row['FINANCIAL_PAYMENT_CASE']) ?>
                     <a href="javascript:;"
                               onclick="javascript:_showReport('<?= base_url("attachments/attachment/public_upload/{$row['FINANCIAL_PAYMENT_ID']}/financial_payment") ?>');">
                                <i class="<?= ($row['ATTACHMENT_COUNT'] > 0?"icon icon-archive":"icon icon-upload delete-action") ?> "></i>
                            </a>

                </td>
                <td>   <?php if ( $row['FINANCIAL_PAYMENT_CASE'] >= 4): ?>
                        <!--<a onclick="<?= HaveAccess($print_url)? "javascript:_showReport('".base_url('/reports')."?report=financial_payment_rep&params[]={$row['FINANCIAL_PAYMENT_ID']}');" :"" ?>" class="btn-xs btn-default" href="javascript:;"> طباعة السند</a>-->

                        <?php if (HaveAccess($print_url)) { ?>
                            <a onclick="javascript:print_financial_payment_report(<?=$row['FINANCIAL_PAYMENT_ID']?>);" class="btn-xs btn-default" href="javascript:;"> طباعة السند</a>
                        <?php } ?>
                    <?php   endif; ?>

                    <?php if ( $row['FINANCIAL_PAYMENT_CASE'] >= 4 && $row['PAYMENT_TYPE'] == 2 && $row['CHECK_ID'] != '' && ($row['CHECK_BANK_ID'] == 3 )) : ?>
                        <a onclick="<?= HaveAccess($print_url)? "javascript:_showReport('".base_url('/reports')."?report=". check_reports($row['CHECK_ID'],$row['CURR_ID'],$row['CHECK_BANK_ID'])."&params[]={$row['FINANCIAL_PAYMENT_ID']}');":"" ?>" class="btn-xs btn-success" href="javascript:;"> طباعة الشيك</a>
                    <?php   endif; ?>

                    <?php if ( $row['FINANCIAL_PAYMENT_CASE'] >= 4 && $row['PAYMENT_TYPE'] == 2 && $row['CHECK_ID'] != '' && ( $row['CHECK_BANK_ID'] == 1  || $row['CHECK_BANK_ID'] == 4 || $row['CHECK_BANK_ID'] == 5 || $row['CHECK_BANK_ID'] == 8 || $row['CHECK_BANK_ID'] == 19 )): ?>
                        <a onclick="<?= HaveAccess($print_url)? "javascript:_showReport('".$report_url."&report=". check_reports($row['CHECK_ID'],$row['CURR_ID'],$row['CHECK_BANK_ID'])."&p_financial_payment_id={$row['FINANCIAL_PAYMENT_ID']}');":"" ?>" class="btn-xs btn-success" href="javascript:;"> طباعة الشيك</a>
                    <?php   endif; ?>
					
                    <?php if ( $row['FINANCIAL_PAYMENT_CASE'] >= 4 && ($row['PAYMENT_TYPE'] == 3)): ?>
                        <!--<a onclick="<?= HaveAccess($print_url)? "javascript:_showReport('".base_url('/reports')."?report=HEWALAH&params[]={$row['FINANCIAL_PAYMENT_ID']}');":"" ?>" class="btn-xs btn-success" href="javascript:;"> طباعة حوالة</a>-->
                        <?php if (HaveAccess($print_url)) { ?>
                            <a onclick="javascript:print_hewalah_report(<?=$row['FINANCIAL_PAYMENT_ID']?>);" class="btn-xs btn-success" href="javascript:;"> طباعة حوالة</a>
                        <?php } ?>

                    <?php   endif; ?>

                    <?php if ( $row['FINANCIAL_PAYMENT_CASE'] >= 4 && ($row['PAYMENT_TYPE'] == 1)): ?>
                        <?php if (HaveAccess($print_url)) { ?>
                            <a onclick="javascript:print_cash_report(<?=$row['FINANCIAL_PAYMENT_ID']?>);" class="btn-xs btn-success" href="javascript:;"> طباعة نقدا</a>
                        <?php } ?>

                    <?php   endif; ?>

                </td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo  $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>