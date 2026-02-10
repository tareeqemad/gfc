<?php
/**
 * User: Mkilani
 * Date: 20/01/2022
 */

$gfc_domain= gh_gfc_domain();
?>
<html>
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
            font-size: 8pt;
            font-weight: bold;
            text-align: right;
        }
        td {
            padding: 1px 2px;
        }
    </style>
    <script>
        print();
    </script>
</head>
<body>

<div style="page-break-after: always;display: block;border: 0px solid #000;text-align: center;margin: 0px; padding-bottom: 0px; padding-top: 0px;">
    <table style="height: 100%;width: 100%; border-collapse: collapse; border-spacing: 0px;font-weight: bolder;  text-align: center">
        <tr><td style="text-align: center;font-size: 6pt; padding-bottom: 0px; padding-top: 0px;height: 40px;"> </td></tr>

        <tr><td style="text-align: center; padding-bottom: 0px; padding-top: 0px;vertical-align: top;"><img height="50pt" style="visibility: hidden;"   src="" /> </td></tr>

    </table>
</div>

<?php
    foreach ($rows as $row ){
?>

    <div style="page-break-after: always;display: block;border: 0px solid #000;text-align: center;margin: 0px; padding-bottom: 0px; padding-top: 0px;">
        <table style="height: 100%;width: 100%; border-collapse: collapse; border-spacing: 0px;font-weight: bolder;  text-align: center">
            <tr>
                <td style="text-align: center;font-size: 6pt; padding-bottom: 0px; padding-top: 0px;height: 40px;"></td>
            </tr>

            <tr>
                <td style="text-align: center; padding-bottom: 0px; padding-top: 0px;vertical-align: top;">
                    <img height="50pt" src="<?=$gfc_domain.base_url().'barcode.php?text='.$row.'&print=true'; ?>" alt="<?=$row?>" >
                </td>
            </tr>

        </table>
    </div>
<?php } ?>

<script>
    print();
    $('body').html($('#print-div').html());
</script>
</body>
</html>
