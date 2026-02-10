<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 09/02/20
 * Time: 09:45 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_accept/") ;
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
            <th>تاريخ البداية</th>
            <th>تاريخ النهاية</th>
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
            $exp_date3 = str_replace('/', '-', $row['LAST_NEW']);
            $datetime2 = date_create($exp_date);
            $datetime3 = date_create($exp_date3);
            $datetime1 = date_create(date('d-m-Y'));
            $interval = date_diff($datetime1, $datetime2);
            $interval3 = date_diff($datetime1, $datetime3);
            $check_date = $interval->format('%R%a');
            $check_date3 = $interval3->format('%R%a');

            $check_status = 0;
            if($check_date < 0  ){
                $status = 2;

                if($check_date3 != 0  ){
                    if($check_date3 <= 14 && $check_date3 > 0)
                        $status = 9 ;
                    else if($check_date3 < 0){
                        $status = 2 ;
                        $check_status = 1;
                    }

                    else
                        $status = 0;

                }


            }
            elseif($check_date <= 14 && $check_date >= 0)
                $status = 1;
            else
                $status = 0;
            //$status = date('d/m/Y', strtotime('+14 day'))  == $row['END_DATE'] ? 1 : 0 ;?>
            <tr <?php if($status == 1 || $status == 9 ){ ?> style="background-color: #dbdda9;"
            <?php } elseif($status == 2){ ?> style="background-color: #ddb4a4;"
            <?php }  ?>  id="tr_<?=$row['SER']?>"  >
                <td><?=$count?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['MANAGE_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['START_DATE']?></td>
                <td><?=  $row['LAST_NEW'] == null ?$row['END_DATE'] : $row['LAST_NEW']
                    ?></td>

                <td><span class="badge badge-<?php
                    if($status == 2)
                        echo 4;
                    elseif($status == 1)
                        echo 1;
                    elseif($status == 9 )
                        echo 5;
                    else echo 2;
                    ?>"><?php if($status == 2) echo "منتهي"  ;
                            elseif ($status == 9 && $check_status = 1)
                                echo "تجديد";
                        else
                        echo "مستمر"?></span></td>
                <td>
                    <?php if (HaveAccess($get_url)): ?>
                        <a target="_blank"  href="<?= base_url("$MODULE_NAME/$TB_NAME/get_accept/{$row['SER']}") ?>"
                            style="background-color: #8d26dd; color: #FFFFFF"  class="btn btn-xs">عرض  <i class="fa fa-edit"></i>
                        </a>
                    <?php endif; ?>
                </td>

                <?php
                $count++; ?>
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
</script>



