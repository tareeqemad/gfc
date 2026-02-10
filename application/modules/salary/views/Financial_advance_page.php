<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 27/12/21
 * Time: 07:21 ص
 */
$MODULE_NAME= 'salary';
$TB_NAME= 'Financial_advance';
$count = $offset;

//salary/Financial_advance/get/

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>مقر الموظف</th>
            <th>نوع التعيين</th>
            <th>عدد الأقساط</th>
            <th>قيمة السلفة</th>
            <th>طبيعة السلفة</th>
            <th>سبب السلفة</th>
            <th>الراتب الأساسي</th>
            <th>حالة الاعتماد</th>
            <?php
            if (HaveAccess(base_url("$MODULE_NAME/$TB_NAME/get/"))) {
                ?> <th>عرض/تحرير</th> <?php
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($page > 1):
            ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php
        endif;
        ?>
        <?php
        foreach ($page_rows as $row):
            ?>

            <tr ondblclick="javascript:show_row_details('<?= $row['SER'] ?>');" >
                <td><?= $count ?></td>
                <td><?= $row['SER'] ?></td>
                <td><?= $row['EMP_NO'] ?></td>
                <td><?= $row['EMP_NO_NAME'] ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_TYPE_NAME'] ?></td>
                <td><?= $row['INSTALLMENTS_NO'] ?></td>
                <td><?= $row['ADVANCE_VALUE'] ?></td>
                <td><?= $row['ADVANCE_TYPE_NAME'] ?></td>
                <td><?= $row['REASON_ID_NAME'] ?></td>
                <td><?= $row['BASIC_SALARY'] ?></td>
                <td><?php 
                     if($row['ADOPT'] == '0')
                     {
                     echo 'ملغي';
                     }
                    else 
                    {
                    ?>
                <?php
                    echo $row['ADOPT_NAME'];                     }

                   ?>
                </td>
                <?php
                if ((HaveAccess(base_url("$MODULE_NAME/$TB_NAME/get/")))) {
                    ?>
                    <td>  <a href="<?= base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}") ?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php
                }
                ?>

                <?php
                $count++;
                ?>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
</div>
<?php
echo $this->pagination->create_links();
?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>