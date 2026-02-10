<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/05/20
 * Time: 10:03 ص
 */



$MODULE_NAME = 'collection_offices';
$TB_NAME = 'Collection_companies';

$isCreate =isset($details) && count($details)  > 0 ? false:true;
$count=0;


?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <?php if(count($details) > 0) {   ?>
        <tr>
            <th >رقم المسلسل</th>
            <th>رقم الهوية</th>
            <th >اسم الموظف</th>
            <th >طبيعة العمل</th>
            <th>مكان العمل</th>
            <th>الحالة</th>
            <th></th>
        </tr>
        <?php } ?>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) {

        echo "لا يوجد موظفين ";
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row1) {
                ++$count+1;

                ?>
                <tr>
                    <td>
                        <input type="text" readonly
                               value="<?=$row1['SER']?>" name="ser[]" id="txt_ser_<?=$count?>"
                               class="form-control">

                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <input type="text"  readonly value="<?=$row1['ID_NUMBER']?>"
                               name="id_number[]" id="txt_id_number_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text" readonly  value="<?=$row1['EMP_NAME']?>"
                               name="emp_name[]" id="txt_emp_name_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text" readonly value="<?=$row1['WORK_NATURE_NAME']?>"
                               name="work_nature[]" id="txt_work_nature_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text" readonly  name="work_place[]"
                               value="<?=$row1['WORK_PLACE_NAME']?>"
                               id="txt_work_place_<?=$count?>"
                               class="form-control" >
                    </td>
					
					<td>
						<?php if( $row1['STATUS'] == 2){ ?>
							<span class="badge badge-danger"><?=$row1['STATUS_NAME']?></span>
						<?php } else { ?>
							<span class="badge badge-success"><?=$row1['STATUS_NAME']?></span>
						<?php } ?>
						
                    </td>
					
                    <td>
                        <a class="btn default btn-xs green" target="_blank" href="<?= base_url("$MODULE_NAME/$TB_NAME/get_employee/".$row1['SER']) ?>">
                            <i class="glyphicon glyphicon-edit"></i> عرض
                        </a>
                    </td>
                </tr>

            <?php

            }
        }

        ?>
        </tbody>
    </table>
</div>
