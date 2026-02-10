<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 26/09/18
 * Time: 09:21 ص
 */

$MODULE_NAME= 'purchases';
$TB_NAME= 'advertising';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
?>

<table class="table" id="advertising_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>رقم الاعلان</th>
        <th>عنوان الاعلان</th>
        <th>نوع الاعلان</th>
        <th>مصدر الإعلان</th>
        <th>مسلسل المصدر</th>


    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_all as $row){
        $count++;
        echo "<tr data-id='{$row['ADVER_NO']}' ";
        if(HaveAccess($get_url) and HaveAccess($edit_url))
            echo "ondblclick='javascript:advertising_get({$row['ADVER_NO']});'";
        echo ">
                <td><input type='checkbox' class='checkboxes' value='{$row['ADVER_NO']}' /></td>
                <td>$count</td>
                   <td>{$row['ADVER_NO']}</td>
                <td>{$row['TITLE']}</td>
                <td>{$row['ADVER_TYPE_NAME']}</td>
                  <td>{$row['SOURCE_NAME']}</td>
                    <td>{$row['FILE_NO']}</td>
                     ";

        echo "</tr>";
    }
    ?>
    </tbody>

</table>

<script type="text/javascript">
  /*  $(document).ready(function() {
        $('#advertising_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }*/
</script>
