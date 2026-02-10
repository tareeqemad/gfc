<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/10/14
 * Time: 10:33 ص
 */

$TB_NAME= 'attachments';
$attachment_form= base_url("$TB_NAME/attachment/show_form");
$attachment_upload= base_url("$TB_NAME/attachment/upload_file");
$attachment_delete= base_url("$TB_NAME/attachment/delete");

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

        echo "
                    <tr id='tr-{$row['ID']}'>
                        <td>$count</td>
                        <td><a href='".base_url(preg_replace('/\s+/', '_', $row['FILE_PATH']))."'>{$row['FILE_NAME']}</a></td>
                        <td>".get_user_name($row['ENTRY_USER'])."</td>
                        <td>{$row['ENTRY_DATE']}</td>";
        if(HaveAccess($attachment_delete) and $row['ENTRY_USER']==$user_id)
                    echo "<td><a href='javascript:;' onclick='javascript:attachment_delete(\"{$attachment_delete}\",{$row['ID']} );'> <i class='glyphicon glyphicon-remove'></i></a></td>";
        else        echo "<td></td>";
                    echo "</tr>
                ";
    }
    echo "</tbody>";

    if(HaveAccess($attachment_upload) and HaveAccess($attachment_form) and $adopt==0){
    echo "
        <tfoot>
        <tr>
            <th></th>
            <th><a onclick='javascript:attachment_form(\"$attachment_form\");' href='javascript:;'><i class='glyphicon glyphicon-upload'></i>رفع ملف جديد</a></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>";
    }
    ?>
</table>
