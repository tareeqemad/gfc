<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/07/20
 * Time: 11:46 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'employeeTraining';
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width:3%">#</th>
            <th>نوع المدرب</th>
            <th>رقم الترخيص / رقم الهوية / الرقم الوظيفي</th>
            <th>الاسم</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php  if(count($details_trainer) > 0) { // تعديل
            $count = -1;
            foreach($details_trainer as $row1) {
                ++$count+1;

                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser_trainee"
                               class="form-control" value="<?=($count+1)?>">
                    </td>


                    <td>
                        <input type="text" readonly   value="<?=$row1['TRAINEE_TYPE_NAME']?>"
                               name="trainee_type_name[]" id="txt_trainee_type_name_<?=$count?>"
                               class="form-control">
                    </td>
                    <td>
                        <input type="text" readonly data-val="true"
                               name="trainee_ser[]" id="txt_trainee_ser_<?=$count?>"
                               class="form-control" value="<?=$row1['TRAINEE_SER']?>" >
                    </td>

                    <td>
                        <?php if($row1['TRAINEE_TYPE'] == 1){ ?>
                            <input type="text" readonly data-val="true"
                                   name="company_name[]" id="txt_company_name_<?=$count?>"
                                   class="form-control" value="<?=$row1['COMPANY_NAME']?>" >

                        <?php }else if ($row1['TRAINEE_TYPE'] == 2){ ?>
                            <input type="text" readonly data-val="true"
                                   name="name[]" id="txt_name_<?=$count?>"
                                   class="form-control" value="<?=$row1['NAME']?>" >
                        <?php }else if ($row1['TRAINEE_TYPE'] == 4){ ?>
                            <input type="text" readonly data-val="true"
                                   name="name[]" id="txt_name_<?=$count?>"
                                   class="form-control" value="<?=$row1['NAME']?>" >
                        <?php }else{ ?>
                            <input type="text" readonly data-val="true"
                                   name="emp_name[]" id="txt_emp_name_<?=$count?>"
                                   class="form-control" value="<?=$row1['EMP_NAME']?>" >
                         <?php }?>

                    </td>
                    <td>
                        <?php if($row1['TRAINEE_TYPE'] == 1){ ?>
                            <a class="btn btn-info btn-xs" target="_blank" href="<?=base_url("$MODULE_NAME/traineeRequest/get_company/{$row1['TRAINEE_SER']}" )?>">عرض<i class='glyphicon glyphicon-share'></i></a>
                        <?php }else if ($row1['TRAINEE_TYPE'] == 2){ ?>
                            <a  class="btn btn-info btn-xs" target="_blank" href="<?=base_url("$MODULE_NAME/traineeRequest/get_person/{$row1['TRAINEE_SER']}" )?>">عرض<i class='glyphicon glyphicon-share'></i></a>
                        <?php }else if ($row1['TRAINEE_TYPE'] == 4){ ?>
                            <a  class="btn btn-info btn-xs" target="_blank" href="<?=base_url("$MODULE_NAME/traineeRequest/get_trainee/{$row1['TRAINEE_SER']}" )?>">عرض<i class='glyphicon glyphicon-share'></i></a>
                        <?php }else{ ?>
                        <?php }?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>
