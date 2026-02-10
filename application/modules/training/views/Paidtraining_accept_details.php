<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 10/02/20
 * Time: 12:39 م
 */


$MODULE_NAME = 'training';
$TB_NAME = 'Paidtraining';

$isCreate =isset($details) && count($details)  > 0 ? false:true;
$count=0;



?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 8%">رقم المسلسل</th>
            <th>قيمة الحافز</th>
            <th>تاريخ بداية التدريب</th>
            <th>مدة العقد</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) { ?>

            <tr>
                <td>
                    <input type="text" readonly name="" id="txt_ser"
                           class="form-control" >
                    <input  type="hidden" name="h_txt_trainee_ser[]"
                            id="h_txt_trainee_ser_<?=$count?>"
                            data-val="false" data-val-required="required" >

                    <input type="hidden" name="seq1[]"
                           id="seq1_id[]"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <input type="text"  name="incentive_val[]" id="txt_incentive_val"
                           class="form-control" >
                </td>

                <td>
                    <input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="start_dat[]"
                           id="txt_start_dat_<?=$count?>" class="form-control">

                </td>
                <td>

                    <input type="text"  name="training_per[]" id="txt_training_per"
                           class="form-control" >
                </td>
            </tr>

            <?php
            $count++;
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row1) {
                ++$count+1;
                ?>

                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser"
                               class="form-control" value="<?=$row1['SER']?>">
                        <input  type="hidden" name="h_txt_trainee_ser[]" value="<?=$row1['TRAINEE_SER']?>"
                                id="h_txt_trainee_ser_<?=$count?>"
                                data-val="false" data-val-required="required" >

                        <input type="hidden" name="seq1[]" value="<?=$row1['SER']?>"
                               id="seq1_id[]"  />
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <input type="text"  name="incentive_val[]" id="txt_incentive_val"
                               class="form-control" value="<?=$row1['INCENTIVE_VAL']?>">
                    </td>

                    <td>
                        <input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                               name="start_dat[]" value="<?=$row1['START_DAT']?>"
                               id="txt_start_dat_<?=$count?>" class="form-control">

                    </td>
                    <td>

                        <input type="text"  name="training_per[]" id="txt_training_per"
                               class="form-control" value="<?=$row1['TRAINING_PER']?>">
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
            <th>
                <?php
                $all =0 ;
                foreach($allPeriod as $row2) {
                    $all +=$row2['PER_ALL'];
                }
                if($all < 11){ ?>
                    <a   onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],select,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php  } ?>
            </th>
            <th></th>
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table>
</div>
