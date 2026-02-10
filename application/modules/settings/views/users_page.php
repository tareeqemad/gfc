<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;
?>
<div class="tbl_container">
<table class="table" id="userTbl" data-container="container">
            <thead>
            <tr>
                <th  ><input type="checkbox"  class="group-checkable" data-set="#userTbl .checkboxes"/></th>
                <th  >#</th>
                <th >الرقم الوظيفي</th>
                <th  >اسم الدخول</th>
                <th >الاسم كامل</th>
                <th style="width: 150px"> الفرع</th>
                <th style="width: 250px">القسم</th>
                <?php if(HaveAccess(base_url("settings/users/login_by_user"))) echo "<th></th>"; ?>
            </tr>
            </thead>
            <tbody>
            <?php if($page > 1): ?>
                <tr>
                    <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

                </tr>
            <?php endif; ?>
            <?php foreach($users as $user) :?>
    <tr ondblclick="javascript:user_get('<?= $user['USER_ID'] ?>');">
        <td><input type="checkbox" class="checkboxes" value="<?= $user['USER_ID'] ?>"/></td>
        <td><?= $count ?></td>
        <td><?= $user['EMP_NO'] ?></td>
        <td><?= $user['USER_ID'] ?></td>
        <td><?= $user['USER_NAME'] ?></td>
        <td><?= $user['NAME'] ?></td>
        <td><?= $user['ST_NAME'] ?></td>
        <?php if(HaveAccess(base_url("settings/users/login_by_user")))
            echo "<td><li><a href='".base_url("settings/users/login_by_user/{$user['ID']}")."'><i class='glyphicon glyphicon-user'></i></a></li></td>";
        ?>
        <?php $count++ ?>
    </tr>
<?php endforeach;?>
     </tbody>
        </table>
</div>
        <?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }



</script>