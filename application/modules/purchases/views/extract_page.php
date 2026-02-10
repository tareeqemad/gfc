<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$get_eval_url = base_url("supplier_evaluation/supplier_evaluation_marks/get");
$prepare_payment_url = base_url("payment/payment_cover/create");
$get_prepare_payment_url= base_url("payment/payment_cover/get");
$count = $offset;
function class_name($adopt)
{

    if ($adopt == '') {
        return 'case_1';
    }
    if ($adopt == 0) {
        return 'case_0';
    }
    if ($adopt == 1) {
        return 'case_2';
    }
    if ($adopt == 10) {
        return 'case_4';
    }
    if ($adopt == 20) {
        return 'case_5';
    }
    if ($adopt == 30) {
        return 'case_6';
    }
}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>م</th>
            <th>مستخلص رقم</th>
            <th>أمر التوريد</th>
            <th>رقم طلب الشراء</th>
            <th>اسم المورد</th>
            <th>حالة الاعتماد</th>
            <?php if (HaveAccess($prepare_payment_url)) { ?>
                <th> تجهيز معاملة صرف</th><?php } ?>
            <th></th>

        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="show_row_details('<?= $row['SER'] ?>');" class="<?= class_name($row['ADOPT']) ?>">
                <td><?= $count ?></td>
                <td><?= $row['EXTRACT_SER_TXT'] ?></td>
                <td><?= $row['ORDER_ID_TEXT'] ?></td>
                <td><?= $row['PURCHASE_TEXT'] ?></td>
                <td><?= $row['CUSTOMER_NAME'] ?></td>
                <td><?php if ($row['ADOPT'] != 0) { ?><?= $row['ADOPT_NAME'] ?><?php } else if ($row['ADOPT'] == 0) echo 'مستخلص ملغي'; ?></td>
                <?php if (HaveAccess($prepare_payment_url)) { ?>
                    <td><?php if ($row['ADOPT'] == 30) { ?>
                       <?php
                        if ((HaveAccess($prepare_payment_url)) and $row['PAYMENT_COVER_SER']=='')
                            {
                        ?>
                        <a class="btn btn-info"
                           href="<?= $prepare_payment_url . '/' . $row['SER'] ?>" target="_blank"> نموذج تجهيز معاملة صرف</a>
                           <?php
                            }

                        else if ((HaveAccess($get_prepare_payment_url)) and $row['PAYMENT_COVER_SER']!='')
                            {
                        ?>

                        <a  class="btn btn-info"
                            href="<?=$get_prepare_payment_url.'/'.$row['PAYMENT_COVER_SER']?>" target="_blank"> عرض نوذج تجهيز معاملة صرف</a>
                                <?php
                            }
                        ?>
                        </td>
                    <?php }
                } ?>
                <td><?php if ($row['EVAL_NO'] != '') { ?><b><a style="color: #0b2e13"
                                                               href="<?= $get_eval_url . '/' . $row['EVAL_NO'] ?>"><?php if ($row['EVAL_ADOPT_ID'] >= 10) { ?><?php $row['EVAL_ADOPT_NAME'] ?><?php } else if ($row['EVAL_ADOPT_ID'] == 1) {
                                echo 'التقييم بحاجة لإعتماد معد التقييم';
                            } else if ($row['EVAL_ADOPT_ID'] == 0) {
                                echo 'التقييم تم إلغاءه';
                            } ?></a></b> <?php } ?>
                </td>

                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }


</script>
