<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 29/09/15
 * Time: 12:33 م
 */
//echo"<pre>"; print_r($rows);
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

    <table style="width: 100%; border-collapse: collapse; border-spacing: 0px;">
        <?php
            $cnt_col= 4;
            $cnt_rows= ceil( count($rows)/$cnt_col );
            for($i=0;$i<$cnt_rows;$i++){
        ?>
            <tr>
                <?php
                    for($j=1;$j<=$cnt_col;$j++){
                        $x= $i*$cnt_col+$j-1;
                        if($x< count($rows)){
                            $row=$rows[$x];
                ?>
                    <td style="border: 1px ridge #000000; text-align: center; padding-bottom: 3px; padding-top: 3px">
                        <table style="width: 100%; border-collapse: collapse; border-spacing: 0px; font-size: 11pt; text-align: center">
                            <tr><td><?='IN:'.$row['RECEIPT_CLASS_INPUT_ID'].' CI:'.$row['CLASS_ID']?></td></tr>
                            <tr><td><?=$row['CLASS_ID_NAME']?></td></tr>
                         <!--   <tr><td><img width="140px" src="http://gs.gedco.com/gfc/barcode/html/image.php?filetype=PNG&dpi=72&scale=2&rotation=0&font_family=Arial.ttf&font_size=16&text=<?=$row['CLASS_CODE_SER']?>&thickness=30&checksum=&code=BCGcode39" alt="<?=$row['CLASS_CODE_SER']?>" </td></tr>
                      -->
                            <tr><td><img  src="http://gs/gfc/barcode/QR.php?text=<?=$row['CLASS_CODE_SER'].' CI:'.$row['CLASS_ID'].' CN:'.$row['CLASS_ID_NAME']?>" alt="<?=$row['CLASS_CODE_SER']?>" </td></tr>

                        </table>
                    </td>
                <?php
                        } else echo "<td></td>";
                    }
                ?>
            </tr>
        <?php } ?>
    </table>

</div>
</body>
</html>
