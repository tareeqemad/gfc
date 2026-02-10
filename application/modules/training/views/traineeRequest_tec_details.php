<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 10/03/20
 * Time: 11:21 ص
 */

$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;
?>
<div class="tb_container">
    <h4>مواضيع التدريب</h4>
    <br>
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th>رقم المسلسل</th>
            <th>المحتوى</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach($details as $row1) {
                if($row1['DETAILS'] == 0){
                ++$count+1;

                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser4"
                               class="form-control" value="<?=$count?>">
                    </td>


                    <td>
                        <input type="text" readonly   value="<?=$row1['DETAILS']?>"
                               name="cost_no[]" id="txt_cost_no_<?=$count?>"
                               class="form-control">
                    </td>
                </tr>


            <?php
                }
        }

        ?>
        </tbody>

        <tfoot>

        </tfoot>
    </table>

    <br>
    <h4>أساليب التدريب</h4>
    <br>
    <table class="table" id="details_tb2" data-container="container"  align="center">
        <thead>
        <tr>
            <th>رقم المسلسل</th>
            <th>المحتوى</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($details as $row1) {
            ++$count+1;
            if($row1['DETAILS'] == 1){

            ?>
            <tr>
                <td>
                    <input type="text" readonly name="" id="txt_ser4"
                           class="form-control" value="<?=$count?>">
                </td>


                <td>
                    <input type="text" readonly   value="<?=$row1['DETAILS']?>"
                           name="cost_no[]" id="txt_cost_no_<?=$count?>"
                           class="form-control">
                </td>
            </tr>


        <?php
            }
        }

        ?>
        </tbody>

        <tfoot>

        </tfoot>
    </table>
</div>
