<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 05/08/19
 * Time: 09:39 ص
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'carRequest';

$post_url= base_url("$MODULE_NAME/$TB_NAME/insert");
$count = $offset;
?>

<div class="tbl_container">

    <br>
    <table class="table" id="carRequest_tb" data-container="container">
        <thead>
        <tr>
            <th>م</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>اليوم</th>
            <th>الوقت المقترح</th>
            <th>من</th>
            <th>الى</th>
            <th>المقر</th>
            <th>اسناد لسائق</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr >
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr id="tr_<?=$row['SER']?>"  data-order-no="<?=$row['SER']?>"  data-emp-name="<?=$row['EMP_NO_NAME']?>" data-emp-email="<?=$row['EMP_EMAIL']?>"  data-time="<?=$row['EXPECTED_LEAVE_TIME']?> " data-date="<?=$row['ASS_START_DATE']?>" data-emp-no="<?=$row['EMP_NO']?>">
                <td><?php echo $count; ?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['ASS_START_DATE']?></td>
                <td><?=$row['EXPECTED_LEAVE_TIME']?></td>
                <td><?=$row['FROM_ADDRESS']?></td>
                <td><?=$row['TO_ADDRESS']?></td>
                <td><?=$row['BRANCH_ID_NAME']?></td>
                <td>
                    <?php if ( HaveAccess($post_url) && $row['STATUS'] < 1) : ?>
                        <button type="button"  class="btn btn-primary btn_reference"> اسناد</button>
                    <?php endif; ?>
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


<script type="text/javascript">

    $(".btn_reference").click(function(){
        var tr= $(this).closest("tr");

        var order_no = tr.attr("data-order-no");
        var emp_name = tr.attr("data-emp-name");
        var time = tr.attr("data-time");
        var date = tr.attr("data-date");
        var emp_no = tr.attr("data-emp-no");
        var emp_email = tr.attr("data-emp-email");

        $('#h_txt_order_no').val(order_no);
        $('#h_txt_emp_name').val(emp_name);
        $('#h_txt_time').val(time);
        $('#h_txt_date').val(date);
        $('#h_emp_no').val(emp_no);
        $('#h_emp_email').val(emp_email);
        $('#myModal').modal();
        $(this).hide();
    });

</script>