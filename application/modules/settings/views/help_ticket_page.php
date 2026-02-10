<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 09/09/14
 * Time: 10:09 ص
 */
?>

<table class="table" id="help_ticket" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>العنوان</th>
        <th>الرابط الارشادي</th>
       <!-- <th>النص الارشادي</th>-->

    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_all as $row){
        $count++;
        echo "
                           <tr data-id='{$row['ID']}' ondblclick='javascript:help_ticket_get({$row['ID']});'>
                                <td><input type='checkbox' class='checkboxes' value='{$row['ID']}' /></td>
                                <td>$count</td>
                                <td>{$row['TITLE']}</td>
                                <td>{$row['FORM_ID']}</td>
                                <!--<td>{$row['HELP_TEXT']}</td>-->

                            </tr>
                        ";
    }
    ?>
    </tbody>

</table>

<script type="text/javascript">
  /*  $(document).ready(function() {
        $('#help_ticket').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });*/

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
