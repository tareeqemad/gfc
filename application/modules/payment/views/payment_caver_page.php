<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 06/11/14
 * Time: 09:08 ص
 */
$count = $offset;

$create_url=base_url('payment/financial_payment/');

$report_url = base_url("JsperReport/showreport?sys=financial/archives");

?>
<div class="tbl_container">
    <table class="table" id="paymentTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th >رقم النموذج</th>
            <th >التاريخ</th>
            <th > حساب المستفيد </th>
            <th > اسم المستفيد </th>
            <th > العملة</th>
            <th  class="price" >القيمة </th>
            <th >المدخل</th>
            <th >سند الصرف</th>
            <th style="width: 100px;"></th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $row) :?>
            <tr ondblclick="javascript:get_to_link('<?= base_url('payment/payment_cover/get/').'/'.$row['COVER_SEQ'] ?>');" >

                <td><?= $count ?></td>
                <td><?= $row['SER'] ?></td>
                <td><?= $row['ENTERY_DATE'] ?></td>
                <td><?= $row['CUSTOMER_ID_NAME'] ?></td>
                <td><?= $row['CUSTOMER_NAME'] ?></td>
                <td><?= $row['CURR_ID_NAME'] ?></td>
                <td class="price"><?= $row['INVOICE_VALUE'] ?></td>
                <?php
                if(($row['EXTRACT_SER']!=0 or $row['EXTRACT_SER']!='') and $row['EXTRACT_SER']!=0)
                {
                    $sourse='  - مصدرها المستخلص ر.م '.$row['EXTRACT_SER'] ;
                }
                else
                {
                    $sourse='';
                }
                ?>
                <td><?= $row['ENTER_USER_NAME'].$sourse ?></td>

                <td><?= $row['ENTRY_SER'] >0?$row['ENTRY_SER']:''  ?></td>
                <td>
                    <?php if( HaveAccess($create_url.(isset($row['FINANCIAL_PAYMENT_ID'])? '/get':'/create'))):  ?>
                        <a  href="<?= $create_url.(( $row['FINANCIAL_PAYMENT_ID'] >= 1? '/get/'.$row['FINANCIAL_PAYMENT_ID'].'/edit/1' : '/create/'.$row['COVER_SEQ'])) ?>" class="<?= $row['FINANCIAL_PAYMENT_ID'] >= 1?'btn btn-xs green':'btn  btn-xs red' ?>">
                            سند صرف </a>
                    <?php endif; ?>
                    <a onclick="javascript:_showReport('<?=$report_url ?>&report_type=pdf&report=payment_cover&p_cover_seq=<?=$row['COVER_SEQ'] ?>');" href="javascript:;"><i class="icon icon-print print-action"></i> </a>
                </td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>