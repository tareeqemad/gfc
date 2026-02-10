<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 10/08/20
 * Time: 10:11 ص
 */
$count = $offset;
$print_url =   base_url('greport/reports/public_income_voucher/');
?>



<div class="tbl_container">
    <table class="table" id="installment_page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الطلب</th>
            <th>اسم مقدم الطلب</th>
            <th>اسم المشترك</th>
            <th>رقم الاشتراك</th>
            <th>رقم الهوية</th>
            <th>نوع الخدمة</th>
            <th>طباعة سند الدين المنظم</th>
            <th>ارفاق سند الدين المنظم</th>
            <th>اعتماد</th>

        </tr>
        </thead>
        <tbody>
<?php foreach($page_rows as $row) :

        ?>
        <tr>
            <td><?=$count?></td>
            <td><?=@$row['SUB_NO']?></td>
            <td><?=@$row['SUB_NAME']?></td>
            <td><?=@$row['D_ID']?></td>
            <td><?=@$row['ISSUE_NO']?></td>
            <td><?=@$row['STATUS_NAME']?></td>
            <td><?=@$row['ISSUE_TYPE_NAME']?></td>
			<td><button type="button" id="btn_print_installment_<?= $count ?>" class="btn btn-success" data-ser1='<?=@$row['SER']?>'  name="print_installment[]">طباعة</button></td>
            <td><?php echo modules::run('attachments/attachment/index',@$row['SER'],'installment'); ?></td>
            <td><button type="button" id="btn_adopt_installment_<?= $count; ?>" class="btn btn-warning" data-ser1='<?=@$row['SER']?>'  name="adopt_installment[]">اعتماد</button> </td>

            <?php $count++ ?>
        </tr>
        <?php endforeach;?>



        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>
<script type="text/javascript">
    $(document).ready(function() {

        reBind_pram(0);


        function reBind_pram(cnt){

            $('button[name="print_installment[]').on('click',  function (e) {

                //  alert($(this).attr('data-ser1'));
                //  console.log($('#base_url').val()+'/'+$(this).attr('data-ser1'));
               // _showReport($('#base_url').val()+'/'+$(this).attr('data-ser1'));
	
                alert($(this).attr('data-ser1'));
                var currentRow=$(this).closest("tr");

                var col1=currentRow.find("td:eq(0)").text(); // get current row 1st TD value
                alert(col1);
						   _showReport('$print_url/'+col1);
   $('#report').on('hidden.bs.modal', function () {
                    reload_Page();
                });



            });


        }


    });


</script>