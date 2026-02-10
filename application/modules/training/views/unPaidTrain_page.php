<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/02/20
 * Time: 09:52 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'unPaidTrain';
$count = 1;


?>

    <div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th>#</th>
                <th>رقم الهوية</th>
                <th>الاسم</th>
                <th>الادارة</th>
                <th>المقر</th>
                <th>الحالة</th>
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
            <?php foreach ($rows as $row) :
                $exp_date = str_replace('/', '-', $row['END_DATE']);
                $datetime2 = date_create($exp_date);
                $datetime1 = date_create(date('d-m-Y'));
                $interval = date_diff($datetime1, $datetime2);
                $check_date = $interval->format('%R%a');

                if($check_date < 0  ){
                    $status = 2;

                }
                elseif($check_date <= 14 && $check_date >= 0)
                    $status = 1;
                else
                    $status = 0;
                ?>
                <tr <?php if($status == 1  ){ ?> style="background-color: #dbdda9;"
                <?php } elseif($status == 2){ ?> style="background-color: #ddb4a4;"
                <?php }  ?>  id="tr_<?=$row['SER']?>" >
                    <td><?=$count?></td>
                    <td><?=$row['ID']?></td>
                    <td><?=$row['NAME']?></td>
                    <td><?=$row['MANAGE_NAME']?></td>
                    <td><?=$row['BRANCH_NAME']?></td>


                    <td><span class="badge badge-<?php
                        if($status == 2)
                            echo 4;
                        elseif($status == 1)
                            echo 1;
                        else echo 2;
                        ?>"><?php if($status == 2)
                                echo "منتهي"  ;
                            else
                                echo "مستمر"?></span></td>

                    <td>
                        <a href="<?=base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
                    </td>
                    <?php
                    $count++; ?>

                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>






<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>



