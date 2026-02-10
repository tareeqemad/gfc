<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 12/02/19
 * Time: 09:18 ص
 */

$MODULE_NAME='gis';
$TB_NAME="User_Controller";
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>USER_ID</th>
            <th>UserName</th>
            <th>PassWord</th>
            <th>STATUS</th>


            <?php (base_url("gis/User_Controller/get_user_info/"))  ?> <th>chang password</th> <?php  ?>
            <th>Effectiveness</th>
            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach($page_rows as $row) :
            ?>

            <tr>


                <td><?=$count?></td>
                <td><?=$row['USER_ID']?></td>
                <td><?=$row['USER_NAME']?></td>
                <td><?=$row['PASS_WORD']?></td>
                <td><?=$row['STATUS']?></td>
                <?php (base_url("gis/User_Controller/get_user_info/"))?>
                <td> <a href="<?=base_url("gis/User_Controller/get_user_info/{$row['ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>

                <?php if( $row['STATUS']==10 ) : ?>
                    <td><button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(<?=$row['ID']?>,20);" class="btn btn-danger">الغاء الفعالية</button>
                    </td>
                <?php endif?>
                <?php if( $row['STATUS']==20 ) : ?>
                    <td><button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(<?=$row['ID']?>,10);" class="btn btn-success">تفعيل المستخدم</button>
                    </td>
                <?php endif?>


















                <?php $count++ ?>
            </tr>
        <?php endforeach;?>

        </tbody>
    </table>
</div>
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
