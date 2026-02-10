<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 18/01/16
 * Time: 11:57 ص
 */

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
            font-size: 10pt;
            text-align: right;
        }

        td {
            padding: 5px 5px;
            text-align: left;
        }
        .auto-style6 {
            text-align: left;
        }



        @media print {
            .hh000 {
                position: fixed;
                top: 0;
            }

            @page {
                size: auto;
                margin-top: 30px;
            }

             /*landscape
            @page{
                size: auto A4 landscape;
                margin: 3mm;
            }
            */

        }


    </style>

    <script>
        setTimeout(function(){
            print();
        }, 300);
    </script>
</head>
<body>

<?php
    $sum_act_1 = 0;
    $sum_act_2 = 0;
?>
<div dir="rtl" style="width: 784px; height: 750px;">

    <table style="width: 100%; border-collapse: collapse; border-spacing: 0px;">
        <thead>
            <tr>
                <td colspan="6"><img src="<?= base_url()?>assets/images/header_rep.png" border="0" width="780px"></td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="display: block;width: 260px; float: right; text-align: right" > <span class="auto-style6" style="font-size: 10pt">التاريخ : <?=date('H:i d/m/Y') ?> </span> </div>
                    <div style="display: block;width: 260px; float: right; text-align: center" > <span style="font-weight: 700; font-size: 13pt">حركات الاصناف</span> </div>
                    <div style="display: block;width: 260px; float: right; text-align: left" > <span class="auto-style6" style="font-size: 10pt"><?=$sn ?></span> </div>
                </td>
            </tr>
        <tr>
            <td style="border: 1px ridge #000000; font-size: 11pt; text-align: center;width:50px;"><b>رقم الصنف</b></td>
            <td style="border: 1px ridge #000000; font-size: 11pt; text-align: center;width:200px;"><b>الصنف</b></td>
            <td style="border: 1px ridge #000000; font-size: 11pt; text-align: center;width:50px;"><b>الوحدة</b></td>
            <td style="border: 1px ridge #000000; font-size: 11pt; text-align: center;width:60px;"><b>الكمية الواردة</b></td>
            <td style="border: 1px ridge #000000; font-size: 11pt; text-align: center;width:60px;"><b>الكمية الصادرة</b></td>
            <td style="border: 1px ridge #000000; font-size: 11pt; text-align: center;width:40px;"><b>السعر</b></td>
            <td style="border: 1px ridge #000000; font-size: 11pt; text-align: center;width:40px;"><b>التاريخ</b></td>
        </tr>
        </thead>

        <tbody>
        <?php foreach($rows as $row) :
            if($row['ACTION']==1){
                $sum_act_1+= $row['AMOUNT'];
            }elseif($row['ACTION']==2){
                $sum_act_2+= $row['AMOUNT'];
            }
            ?>
            <tr>
                <td style="border: 1px ridge #000000; text-align: center"><?= $row['CLASS_ID'] ?></td>
                <td style="border: 1px ridge #000000; text-align: right; font-size: 10pt"><?= $row['CLASS_ID_NAME'] ?></td>
                <td style="border: 1px ridge #000000; text-align: center;"><?= $row['CLASS_ID_UNIT'] ?></td>
                <td style="border: 1px ridge #000000; text-align: center;"><?= $row['ACTION']==1? number_format($row['AMOUNT'],2):'' ?></td>
                <td style="border: 1px ridge #000000; text-align: center;"><?= $row['ACTION']==2? number_format($row['AMOUNT'],2):'' ?></td>
                <td style="border: 1px ridge #000000; text-align: center;"><?= number_format($row['PRICE'],2) ?></td>
                <td style="border: 1px ridge #000000; text-align: center;"><?= substr($row['ADOPT_DATE'],6) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="border: 1px ridge #000000; text-align: center"><?=number_format($sum_act_1,2)?></td>
            <td style="border: 1px ridge #000000; text-align: center"><?=number_format($sum_act_2,2)?></td>
            <td></td>
            <td></td>
        </tr>
    </table>

</div>

</body>
</html>
