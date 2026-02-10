<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 29/07/20
 * Time: 08:29 ص
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
            <th style="width: 8%">#</th>
            <th>النوع</th>
            <th>نوع الخصم / الاضافة</th>
            <th>المبلغ</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) {  ?>

            <tr>
                <td>
                    <input type="text" readonly name="ser[]" id="txt_ser_<?=$count?>"
                           class="form-control">
                    <input  type="hidden" name="h_txt_trainee_ser[]"   id="h_txt_trainee_ser_<?=$count?>"
                            data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]"  value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <select name="type[]" id="dp_type" class="form-control">
                        <option value="0">_________</option>
                        <?php foreach($type_salary as $row) : ?>
                            <option value="<?= $row['CON_NO'] ?>" > <?= $row['CON_NAME']?> </option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <select name="add_minus[]" id="dp_add_minus" class="form-control">
                        <option value="0">_________</option>
                    </select>
                </td>

                <td>
                    <input type="text"  name="value[]" id="txt_value_<?=$count?>" placeholder="المبلغ"
                           class="form-control" >
                </td>
                <td></td>
            </tr>

            <?php
            $count++;
        }else if(count($details) > 0) {
            $count = -1;
            foreach($details as $row1) {
                ++$count+1;
                ?>
                <tr>
                    <td>
                        <input type="text" readonly value="<?=$count+1?>" name="ser[]" id="txt_ser_<?=$count?>"
                               class="form-control">
                        <input  type="hidden" name="h_txt_trainee_ser[]"   id="h_txt_trainee_ser_<?=$count?>"
                                data-val="false" data-val-required="required" >
                        <input type="hidden" name="seq1[]"  value="<?=$row1['SER']?>"  />
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <select name="type[]" id="dp_type_<?=$count?>" class="form-control">
                            <option value="0">_________</option>
                            <?php foreach($type_salary as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"
                                    <?PHP if ($row['CON_NO'] == ($row1['SOURCE_TYPE'])){
                                            echo " selected";
                                          }
                                    ?> >
                                    <?= $row['CON_NAME']?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td>
                        <?php if($row1['SOURCE_ID'] != null && $row1['SOURCE_TYPE'] == 1 ){ ?>
                            <select name="add_minus[]" id="dp_add_minus_<?=$count?>" class="form-control">
                                <option value="0">_________</option>
                                <?php foreach($source_type_one as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"
                                        <?PHP if ($row['CON_NO'] == ($row1['SOURCE_ID'])){
                                            echo " selected";
                                        }
                                        ?> >
                                        <?= $row['CON_NAME']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php } else if($row1['SOURCE_ID'] != null && $row1['SOURCE_TYPE'] == 2 ){ ?>
                            <select name="add_minus[]" id="dp_add_minus_<?=$count?>" class="form-control">
                                <option value="0">_________</option>
                                <?php foreach($source_type_two as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"
                                        <?php if ($row['CON_NO'] == ($row1['SOURCE_ID'])){
                                            echo " selected";
                                        }
                                        ?> >
                                        <?= $row['CON_NAME']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php }else{ ?>
                            <select name="add_minus[]" id="dp_add_minus" class="form-control">
                            </select>
                        <?php } ?>
                    </td>

                    <td>
                        <input type="text" name="value[]" value="<?=$row1['VALUE']?>"
                               id="txt_value_<?=$count?>" placeholder="المبلغ"
                               class="form-control" >
                    </td>

                    <td>
                        <?php if($row1['ADOPT'] == 1) {?>
                        <a onclick="adoptAdded(<?= $row1['SER'] ?>);" class="btn btn-success btn-xs">اعتماد</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th>
                <a  onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>