<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 24/12/20
 * Time: 7:11 م
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


    <?php if($row['BARCODE']!=''){?>
        <div style="page-break-after: always;display: block;border: 0px solid #000;text-align: center;margin: 0px; padding-bottom: 0px; padding-top: 0px;">
            <table style="height: 100%;width: 100%; border-collapse: collapse; border-spacing: 0px;font-weight: bolder;  text-align: center">
                <tr><td style="text-align: center;font-size: 6pt; padding-bottom: 0px; padding-top: 0px;height: 40px;"><? //=' CI:'.$row['CLASS_ID'].'/'.$row['BARCODE']?></td></tr>
                <!-- <tr><td style=" text-align: center;font-size: 8pt; padding-bottom: 0px; padding-top: 0px"><?=$row['CLASS_ID_NAME2']?></td></tr>
                           <tr><td><img width="140px" src="http://gs.gedco.com/gfc/barcode/html/image.php?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=Arial.ttf&font_size=16&text=<?=$row['BARCODE']?>&thickness=30&checksum=&code=BCGcode39" alt="<?=$row['BARCODE']?>" </td></tr>
                     https://gsx.gedco.ps/gfc/barcode.php?text=<? //=$row['BARCODE']?>&print=true"
                      -->
                <tr><td style="text-align: center; padding-bottom: 0px; padding-top: 0px;vertical-align: top;"><img height="50pt" width="170pt"  src="<?php  echo 'https://'.$_SERVER['SERVER_NAME'].base_url().'barcode.php?text='.$row['BARCODE'].'&print=true'; ?>"  alt="<?=$row['BARCODE']?>" </td></tr>

            </table>
        </div>
    <?php }
    ?>


</div>
</body>
</html>
