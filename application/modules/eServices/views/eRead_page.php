<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/08/18
 * Time: 11:44 ص
 */

$MODULE_NAME= 'eServices';
$TB_NAME='EReads';
$count = 1;


?>
<table class="table" id="c_eReads_tb" data-container="container">
    <thead>
    <tr>
        <th class="hidden" >#</th>

        <th>م</th>
        <th>المقر</th>
        <th>شهر</th>
        <th>رقم الهوية</th>

        <th>رقم الإشتراك</th>
        <th>اسم المشترك</th>
        <th>اسم المنتفع</th>
<th>القراءة المدخلة</th>
        <th>تاريخ ادخال القراءة</th>
        <th>العداد</th>

    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>

       <tr>
            <td><?=$count?></td>
            <td><?=@$row['BRANCH_NAME']?></td>
            <td><?=@$row['FOR_MONTH']?></td>
            <td><?=@$row['ID']?></td>
            <td>
                <?=@$row['SUBSCRIBER'];?>
            </td>
           <td>
               <?=@$row['NAME'];?>
           </td>
            <td>
                <?=@$row['BENIFICARY_NAME'];?>
            </td>
  <td><?=@$row['KW_READ'];?></td>
            <td><?=@$row['P_ENTRY_DATE'];?></td>
            <td>     <?php
                if ($row['IMAGES']!='')
                {
                    ?>
                    <a class="group1" target="_blank" href="<?php echo @$row['IMAGES']; ?>" title="صورة العداد">العداد</a>
                <?php
                }
                ?></td>
             <?php $count++ ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script>

   /* if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }*/
</script>
