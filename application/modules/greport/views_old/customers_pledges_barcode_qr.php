<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/06/16
 * Time: 01:40 م
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
<?php
//print_r($row);
$row=$rows[0];
?>

<div dir="rtl" style="width: 100%;height: 100%">


    <?php if($row['CLASS_CODE_SER']!=''){?>
        <div style=" display: block;border: 1px solid #000;text-align: center;margin: 2px; padding-bottom: 0px; padding-top: 2px;page-break-after: always;">
            <table style="width: 100%; border-collapse: collapse; border-spacing: 0px;font-weight: bolder;  text-align: center">
                <tr><td style="text-align: center;font-size: 10pt; padding-bottom: 0px; padding-top: 0px"><?=' CI:'.$row['CLASS_ID']?></td></tr>
                <tr><td style=" text-align: center;font-size: 10pt; padding-bottom: 0px; padding-top: 0px"><?=$row['CLASS_ID_NAME2']?></td></tr>

                <tr><td style="text-align: center; padding-bottom: 0px; padding-top: 0px"><img height="90pt"   src="http://gs/gfc/barcode/QR.php?text=<?=$row['CLASS_CODE_SER'].' CI:'.$row['CLASS_ID'].' CN:'.$row['CLASS_ID_NAME']?>" alt="<?=$row['CLASS_CODE_SER']?>" </td></tr>
            </table>
        </div>
    <?php }
    ?>


</div>
</body>
</html>
