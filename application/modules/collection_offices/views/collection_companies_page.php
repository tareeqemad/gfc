<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/12/19
 * Time: 09:15 ص
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Collection_companies';
$add_employee_url =  base_url("$MODULE_NAME/$TB_NAME/add");

$count = 1;
?>


<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الترخيص</th>
            <th>اسم الشركة</th>
            <th>العنوان</th>
            <th>رقم الهاتف</th>
            <th>عدد الاشتراكات</th>
            <th>الحالة</th>
            <th>مدخل البيانات</th>
            <th></th>
            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr >
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr id="tr_<?=$row['SER']?>" >
                <td><?=$count?></td>
                <td><?=$row['LICENSE_NO']?></td>
                <td><?=$row['COMPANY_NAME']?></td>
                <td><?=$row['ADDRESS']?></td>
                <td><?=$row['TEL_NO']?></td>
                <td><?=$row['SUB_COUNT']?></td>
                <?php if($row['ADOPT'] == 1){ ?>
                    <td>
                        <span class="badge badge-4"><?= $row['ADOPT_NAME'] ?></span>
                    </td>

                <?php }else{ ?>
                    <td>
                        <span class="badge badge-2"><?= $row['ADOPT_NAME'] ?></span>
                    </td>
                <?php } ?>

                <td><?=$row['ENTRY_USER_NAME']?></td>
                <td>
                   <!-- <a  class="btn default btn-xs purple">
                        <i class="fa fa-edit" onclick="javascript:showEmployee(<?=$row['SER'] ?>)" data-pk="<?=$row['SER']?>">  عرض العاملين  </i>
                    </a>-->
                    <?php if($row['ADOPT'] == 2 ):  ?>
                      <!--  <a class="btn default btn-xs blue" target="_blank" href="<?= base_url("$MODULE_NAME/$TB_NAME/add/".$row['SER']) ?>">
                                <i class="glyphicon glyphicon-plus"></i> اضافة عامل
                        </a> -->
                    <?php endif; ?>

                    <a class="btn default btn-xs purple" href="<?=base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}" )?>">
                        <i class='glyphicon glyphicon-share'></i>عرض</a>
                </td>

                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>
<script>

</script>
