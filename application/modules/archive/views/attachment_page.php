<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/10/14
 * Time: 10:33 ص
 */

$TB_NAME= 'ARCHIVE_TB';
$attachment_form= base_url("archive/show_form?FILE_ID=".$ID);
$attachment_upload= base_url("archive/upload_file");
$attachment_delete= base_url("archive/delete_details");
$download_file= base_url("archive/download/");
echo AntiForgeryToken();
?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>اسم الملف</th>
        <th>اسم المستخدم</th>
        <th>تاريخ الرفع</th>
        <th>حذف</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        $count++;
      //  ".base_url($row['FILE_PATH'])."
        echo "
                    <tr id='tr-{$row['SER']}'>
                        <td>$count</td>
                        <td><a href='".$download_file.'/'.$row['FILE_PATH']."/".$row['FOLLOW_NAME']."'>{$row['FOLLOW_NAME']}</a></td>
                        <td>".get_user_name($row['ENTRY_USER'])."</td>
                        <td>{$row['ENTRY_DATE']}</td>";
        if(HaveAccess($attachment_delete))
                    echo "<td><a href='javascript:;' onclick='javascript:delete_details({$row['SER']} );'> <i class='glyphicon glyphicon-remove'></i></a></td>";
       else        echo "<td></td>";
                    echo "</tr>
                ";
    }
    echo "</tbody>";


    echo "
        <tfoot>
        <tr>
            <th></th>
            <th><a onclick='javascript:attachment_form_1(\"$attachment_form\");' href='javascript:;'><i class='glyphicon glyphicon-upload'></i>رفع ملف جديد</a></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>";

    ?>
</table>
