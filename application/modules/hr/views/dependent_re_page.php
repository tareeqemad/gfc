<?php
$get_page_url = base_url('hr/dependent/get_page');
$get_page_url_r = base_url('hr/dependent/get_page_r');
?>

<h4 style="text-align: center">البيانات القديمة</h4>

<table class="table" id="page_tb" data-container="container">
    <thead>
    <tr>
        <th>#</th>
        <th>الاسم</th>
        <th>صلة القرابة</th>
        <th>تاريخ الميلاد</th>
        <th>من تاريخ</th>
        <th>حتى</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($page_relative as $rows) :?>
        <?php // echo '<pre>'; print_r($page_relative); echo '</pre>'; ?>
        <tr>
            <td><?=$rows['SERIAL']?></td>
            <td><?= $rows['NAME'] ?></td>
            <td><?= $rows['RELATION_NAME'] ?></td>
            <td><?= $rows['BIRTH_DATE'] ?></td>
            <?//= $rows['WORTH_ALLOWNCE']?>
            <td><?= $rows['FROM_MONTH']?></td>
            <td><?= $rows['UP_TO_MONTH']?></td>


        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<!---------------------------------------------------------------------------->
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

