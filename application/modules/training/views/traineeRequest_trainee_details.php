<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 05/02/20
 * Time: 01:29 م
 */

$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th>رقم المسلسل</th>
            <th>رقم الهوية</th>
            <th>الاسم</th>
            <th>السيرة الذاتية</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row1) {
                ++$count+1;

                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser_trainee"
                               class="form-control" value="<?=$row1['SER']?>">

                        <input type="hidden" readonly name="seq1_trainee[]" value="<?=$row1['SER']?>"
                               id="seq12_id[]"  />
                        <input type="hidden" readonly name="h_count_trainee[]" id="h_count_trainee_<?=$count?>"  />
                    </td>


                    <td>
                        <input type="text" readonly   value="<?=$row1['ID_NUMBER']?>"
                               name="id_number[]" id="txt_id_number_<?=$count?>"
                               class="form-control">
                    </td>
                    <td>
                        <input type="text" readonly data-val="true"
                               name="full_name[]" id="txt_full_name_<?=$count?>"
                               class="form-control" value="<?=$row1['FULL_NAME']?>" >
                    </td>

                    <td>
                        <a href="<?=base_url("$MODULE_NAME/$TB_NAME/getData/{$row1['SER']}" )?>" target="_blank"><i class='glyphicon glyphicon-share'></i></a>
                    </td>

                </tr>


            <?php

            }
        }

        ?>
        </tbody>

        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>

        </tfoot>
    </table></div>
