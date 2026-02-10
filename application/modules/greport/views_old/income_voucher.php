<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 20/03/18
 * Time: 11:36 ص
 */
$sum = 0;
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title></title>
    <style type="text/css">
        .auto-style1 {
    font-size: 9pt;
            text-align: center;
        }

        .auto-style2 {
    font-size: 9pt;
            text-align: center;
            font-weight: bold;
        }

        .auto-style3 {
    font-size: 9pt;
            text-align: center;
        }

        .fci4clh1wewzuo-2 {
    font-size: 10pt;
            color: #000000;
            font-family: Arial;
            font-weight: bold;
        }

        .auto-style4 {
    font-size: 9pt;
        }

        .auto-style5 {
    font-size: 9pt;
            font-weight: bold;
            text-align: right;
        }

        td {
    padding: 1px 5px;
        }
    </style>
    <script>
        print();
    </script>
</head>
<body>
<div dir="rtl" style="width: 784px">

    <?php for($i= 0;$i<3 ;$i++) :?>
    <?php $sum = 0; ?>
    <div style="height:355px;border-bottom: 1px dashed #000000;margin-bottom: 15px">
        <table style="width: 100%; border-collapse: collapse; border-spacing: 0px;">
            <tr>
                <td style="width: 33%" class="auto-style2">شركة توزيع كهرباء محافظات غزة</td>
                <td rowspan="4" class="auto-style1" style="vertical-align: top">
                    <img src="<?= base_url()?>assets/images/logo.png" border="0" width="72px" height="64px"><br />
                    <br />
                    <span><strong>GEDCo</strong></span></td>
                <td style="width: 33%" class="auto-style2">GAZA ELECTRICITY DISTRIBUTION</td>
            </tr>
            <tr>
                <td class="auto-style2">المساهمة الخصوصية المحدودة</td>
                <td class="auto-style2">CORPORATION</td>
            </tr>
            <tr>
                <td class="auto-style2">الادارة المالية</td>
                <td class="auto-style2">Ltd</td>
            </tr>
            <tr>
                <td class="auto-style3"><strong style="text-align: center">رقم&nbsp;المشتغل 563130061</strong></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="auto-style5">رقم الطلب :&nbsp; <?= $rows[0]['SERVICE_ID'] ?>
                    <br />
                    رقم الاشتراك : <?= $rows[0]['SUPSCRIPER_ID'] ?></td>
                <td class="auto-style5" style="text-align:center;"><?= $rows[0]['BRANCH'] ?>
                    <br>
                <span style="padding-top: 5px;display: block;">
                    <?= $branch !=1 ? ($i == 0?"الاصل":($i==1?"نسخة مقر":"الإدارة المالية")) : ($i == 0?"الاصل":($i==1?" نسخة":"الإدارة المالية")) ?>
                    </span>
                </td>
                <td><span class="auto-style5">رقم&nbsp;الايصال : <?= $rows[0]['VOUCHER_ID'] ?> / <?= $rows[0]['ENTRY_SER'] ?>
                </span><b>
                        <br class="auto-style4" />
                    </b><span class="auto-style5">التاريخ : <?= $rows[0]['VOUCHER_DATE'] ?>
                </span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: 700; font-size: 11pt">الزبون :<?= $rows[0]['SUB_NAME'] ?> </td>
                <td class="auto-style5">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%; border-collapse: collapse; border-spacing: 0px;">
                        <tr>
                            <td style="border: 1px ridge #000000; font-size: 9pt; text-align: center;"><b>رقم&nbsp;الحساب</b></td>
                            <td style="border: 1px ridge #000000; font-size: 9pt; text-align: center" colspan="2"><b>اسم&nbsp;الحساب</b></td>
                            <td style="border: 1px ridge #000000; font-size: 9pt; text-align: center;width:92px;"><b>المبلغ</b></td>
                            <td style="border: 1px ridge #000000; font-size: 9pt; text-align: center;width:69px"><b>العملة</b></td>
                            <td style="border: 1px ridge #000000; font-size: 9pt; text-align: center; width: 56px;"><b>سعر&nbsp;العملة</b></td>
                            <td style="border: 1px ridge #000000; font-size: 9pt; text-align: center"><b>الاجمالي</b></td>
                        </tr>
                        <?php foreach($rows as $row) : ?>
                            <tr>
                                <td style="border: 1px ridge #000000; font-size: 9pt; text-align: center;"><?= $row['CREDIT_ACCOUNT_ID'] ?></td>
                                <td style="border: 1px ridge #000000; text-align: center" colspan="2"><?= $row['NAME_ACCOUNT'] ?></td>
                                <td style="border: 1px ridge #000000; text-align: center"><?= n_format($row['CREDIT']) ?></td>
                                <td style="border: 1px ridge #000000; text-align: center"><?= $row['CURR_NAME'] ?></td>
                                <td style="border: 1px ridge #000000; text-align: center"><?= $row['CURR_VALUE'] ?></td>
                                <td style="border: 1px solid #000000; text-align: center"><?=n_format($row['TOTALLY']) ?> <?php $sum +=$row['TOTALLY']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td style="font-size: 9pt; text-align: right; font-weight: bold; height: 33px;" colspan="4">البيان : <?= $rows[0]['HINTS'] ?></td>
                            <td colspan="2" style="font-size: 9pt; text-align: center; height: 33px;"><b>الاجمالي</b></td>
                            <td style="border: 1px solid #000000; text-align: center" /><?= n_format($sum) ?></td>
                        </tr>
                        <?php if($rows[0]['CHECK_ID'] !=''): ?>
                            <tr>
                                <td style="font-size: 9pt; text-align: right; font-weight: bold;" colspan="7">
                                    <table style="width:100%; border-collapse: collapse; border-spacing: 0px;">
                                        <tr>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center">رقم الشيك</td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center">التاريخ</td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center">صاحب الشيك</td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center">المبلغ</td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center">العملة</td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center">الاجمالي</td>
                                        </tr>
                                        <tr>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center"><?= $rows[0]['CHECK_ID'] ?></td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center"><?= $rows[0]['CHECK_DATE'] ?></td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center"><?= $rows[0]['CHECK_CUSTOMER'] ?></td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center"><?= n_format($sum) ?></td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center"><?= $rows[0]['CURR_NAME'] ?></td>
                                            <td style="border: 1px solid #000000; font-size: 9pt; text-align: center"><?= n_format($sum) ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 9pt; text-align: right; font-weight: bold;" colspan="7"><?= $rows[0]['NOTES'] ?></td>
                            </tr>
                        <?php endif;?>
                        <tr>
                            <td style="font-size: 9pt; text-align: right; font-weight: bold;" colspan="2">أمين&nbsp;الصندوق : <?= $rows[0]['ENTRY_USER'] ?></td>
                            <td style="font-size: 9pt; text-align: right; font-weight: bold;" colspan="5"><b> اجمالي المبلغ  بالحروف:  <?= $rows[0]['SUM_T'] ?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

<?php endfor;?>
</div>
</body>
</html>