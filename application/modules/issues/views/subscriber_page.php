<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/04/21
 * Time: 06:45 ص
 */
$TB_NAME= 'checks';
?>
<?php echo $this->pagination->create_links();

?>
<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <!--<th  > <input type="checkbox"  class="group-checkable" data-set="#class_tb .checkboxes"/></th> -->

        <th>#</th>
        <th>رقم الاشتراك</th>
        <th>رقم الهوية</th>
        <th>اسم المشترك</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        $count++;
        ?>
        <tr ondblclick="javascript:select_class(<?=$row['NO']?>)">
           <!-- <td> <input type="checkbox" class="checkboxes" data-val=' <?=json_encode($row)?>' value="<?= $row['NO'] ?>"/></td> -->
            <td><?=$count?></td>
            <td><?=$row['NO']?></td>
            <td><?=$row['ID']?></td>
            <td><?=$row['NAME']?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php echo $this->pagination->create_links();

?>

