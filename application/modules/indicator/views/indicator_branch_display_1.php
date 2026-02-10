<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 17/09/18
 * Time: 12:57 م
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicator';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");
$edit_without_cost_url=base_url("$MODULE_NAME/$TB_NAME/get_without_cost");
$count = 1;
$page=1;
function class_name($mode){

    if(!($mode % 2)){

        return 'case_4';
    }
    else{
        return 'case_3';
    }

}
?>
<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
<thead>
<tr>
    <th class="hidden" >#</th>

    <th>التصنيف</th>

    <th>م</th>
    <th>اسم المؤشر</th>


    <th>
        <div class="row">
            <div class="col-md-12 text-center">مقر الشمال</div>

        </div>

        <hr>
        <br>
        <div class="row">

            <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
            <div class="col-md-3">محقق</div>
            <div class="col-md-3">الفرق</div>
            <div class="col-md-3">%</div>


        </div>
    </th>
    <th>
        <div class="row">
            <div class="col-md-12 text-center">مقر غزة</div>

        </div>

        <hr>
        <br>
        <div class="row">

            <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
            <div class="col-md-3">محقق</div>
            <div class="col-md-3">الفرق</div>
            <div class="col-md-3">%</div>


        </div>
    </th>
    <th>
        <div class="row">
            <div class="col-md-12 text-center">مقر الوسطى</div>

        </div>

        <hr>
        <br>
        <div class="row">

            <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
            <div class="col-md-3">محقق</div>
            <div class="col-md-3">الفرق</div>
            <div class="col-md-3">%</div>


        </div>
    </th>
    <th>
        <div class="row">
            <div class="col-md-12 text-center">مقر خانيونس</div>

        </div>

        <hr>
        <br>
        <div class="row">

            <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
            <div class="col-md-3">محقق</div>
            <div class="col-md-3">الفرق</div>
            <div class="col-md-3">%</div>


        </div>
    </th>
    <th>
        <div class="row">
            <div class="col-md-12 text-center">مقر رفح</div>

        </div>

        <hr>
        <br>
        <div class="row">

            <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
            <div class="col-md-3">محقق</div>
            <div class="col-md-3">الفرق</div>
            <div class="col-md-3">%</div>


        </div>
    </th>

</tr>
</thead>

<tbody>
<?php foreach($page_rows as $row) :

    ?>
    <tr class="<?=class_name($row['CLASS_'])?>">
        <td><?=@$row['CLASS']?></td>
        <td><?=$count?></td>
        <td><?=@$row['INDECATOR_NAME']?><input type="hidden" name="indecator_ser[]" value="<?=@$row['INDECATOR_SER']?>" /></td>

        <td>
            <div class="row">
                <div class="col-md-3">
                    <input class="form-control" name="txt_north[]" value="<?=@$row['NORTH']?>" id="txt_north_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="north_seq[]" id="north_seq_id_<?= $count ?>" value="<?=@$row['NORTH_SEQ']?>" data-check='<?=@$row['NORTH_SEQ']?>' /></div>
                <div class="col-md-3">
                    <input class="form-control" name="prev_north[]" value="<?=@$row['VALUE_NORTH']?>" readonly  />
                </div>
                <div class="col-md-3">
                    <input class="form-control" name="differ_north[]" value="<?=@$row['VALUE_NORTH']-@$row['NORTH'];?>" readonly  />
                </div>

                <div class="col-md-3">
                    <input class="form-control" name="persent_north[]" value="<?php
                    if($row['IS_PERSENT'] == 1)
                    {
                        if (@$row['NORTH']==0)
                            echo 'لايمكن القسمة على صفر';
                        else
                            echo (@$row['VALUE_NORTH']-@$row['NORTH'])/@$row['NORTH'];
                    }
                    else if($row['IS_PERSENT'] == 2)
                        echo round(@$row['NORTH']).'%';
                    ?>" readonly  />
                </div>

            </div>

        </td>
        <td>
            <div class="col-md-3">
                <input class="form-control" name="txt_gaza[]" value="<?=@$row['GAZA']?>" id="txt_gaza_<?=$count?>" data-val="false" data-val-required="required" />
                <input type="hidden" name="gaza_seq[]" id="gaza_seq_id_<?= $count ?>" value="<?=@$row['GAZA_SEQ']?>" /></div>
            <div class="col-md-3">
                <input class="form-control" name="value_gaza[]" value="<?=@$row['VALUE_GAZA']?>" readonly  />
            </div>
            <div class="col-md-3">
                <input class="form-control" name="differ_gaza[]" value="<?=@$row['VALUE_GAZA']-@$row['GAZA']?>" readonly  />
            </div>

            <div class="col-md-3">
                <input class="form-control" name="persent_gaza[]" value="<?php
                if($row['IS_PERSENT'] == 1)
                {
                    if (@$row['GAZA']==0)
                        echo 'لايمكن القسمة على صفر';
                    else
                        echo (@$row['VALUE_GAZA']-@$row['GAZA'])/@$row['GAZA'];
                }
                else if($row['IS_PERSENT'] == 2)
                    echo round(@$row['GAZA']).'%';
                ?>" readonly  />
            </div>

        </td>
        <td>
            <div class="col-md-3">
                <input class="form-control" name="txt_middle[]" value="<?=@$row['MIDDLE']?>" id="txt_middle_<?=$count?>" data-val="false" data-val-required="required" />
                <input type="hidden" name="middle_seq[]" id="middle_seq_id_<?= $count ?>" value="<?=@$row['MIDDLE_SEQ']?>" /></div>
            <div class="col-md-3">
                <input class="form-control" name="value_middle[]" value="<?=@$row['VALUE_MIDDLE']?>" readonly  />
            </div>
            <div class="col-md-3">
                <input class="form-control" name="differ_middle[]" value="<?=@$row['VALUE_MIDDLE']-@$row['MIDDLE']?>" readonly  />
            </div>

            <div class="col-md-3">
                <input class="form-control" name="persent_middle[]" value="<?php
                if($row['IS_PERSENT'] == 1)
                {
                    if (@$row['MIDDLE']==0)
                        echo 'لايمكن القسمة على صفر';
                    else
                        echo (@$row['VALUE_MIDDLE']-@$row['MIDDLE'])/@$row['MIDDLE'];
                }
                else if($row['IS_PERSENT'] == 2)
                    echo round(@$row['MIDDLE']).'%';
                ?>" readonly  />
            </div>

        </td>
        <td>
            <div class="col-md-3">
                <input class="form-control" name="txt_khan[]" value="<?=@$row['KHAN']?>" id="txt_khan_<?=$count?>" data-val="false" data-val-required="required" />
                <input type="hidden" name="khan_seq[]" id="khan_seq_id_<?= $count ?>" value="<?=@$row['KHAN_SEQ']?>" /></div>
            <div class="col-md-3">
                <input class="form-control" name="value_khan[]" value="<?=@$row['VALUE_KHAN']?>" readonly  />
            </div>
            <div class="col-md-3">
                <input class="form-control" name="differ_khan[]" value="<?=@$row['VALUE_KHAN']-@$row['KHAN']?>" readonly  />
            </div>

            <div class="col-md-3">
                <input class="form-control" name="persent_khan[]" value="<?php
                if($row['IS_PERSENT'] == 1)
                {
                    if (@$row['KHAN']==0)
                        echo 'لايمكن القسمة على صفر';
                    else
                        echo (@$row['VALUE_KHAN']-@$row['KHAN'])/@$row['KHAN'];
                }
                else if($row['IS_PERSENT'] == 2)
                    echo round(@$row['KHAN']).'%';
                ?>" readonly  />
            </div>

        </td>
        <td>
            <div class="col-md-3">
                <input class="form-control" name="txt_rafa[]" value="<?=@$row['RAFA']?>" id="txt_rafa_<?=$count?>" data-val="false" data-val-required="required" />
                <input type="hidden" name="rafa_seq[]" id="rafa_seq_id_<?= $count ?>" value="<?=@$row['RAFA_SEQ']?>" /></div>
            <div class="col-md-3">
                <input class="form-control" name="value_rafa[]" value="<?=@$row['VALUE_RAFA']?>" readonly  />
            </div>
            <div class="col-md-3">
                <input class="form-control" name="differ_rafa[]" value="<?=@$row['VALUE_RAFA']-@$row['RAFA']?>" readonly  />
            </div>

            <div class="col-md-3">
                <input class="form-control" name="persent_rafa[]" value="<?php
                if($row['IS_PERSENT'] == 1)
                {
                    if(@$row['RAFA']==0)
                        echo 'لايمكن القسمة على صفر';
                    else
                        echo (@$row['VALUE_RAFA']-@$row['RAFA'])/@$row['RAFA'];
                }
                else if($row['IS_PERSENT'] == 2)
                    echo round(@$row['RAFA']).'%';
                ?>" readonly  />
            </div>

        </td>










        <?php $count++ ?>
    </tr>
<?php endforeach;?>

</tbody>
</table>
</div>
<script type="text/javascript">
    /* $(document).ready(function() {

     reBind();
     $('input[name="txt_t_persant[]"]').change();
     function reBind() {
     $('input[name="txt_t_persant[]"]').change(function () {
     if($(this).val()>0)
     {
     $(this).closest('tr').find('input[name="txt_north[]"]').prop('readonly',true);
     $(this).closest('tr').find('input[name="txt_gaza[]"]').prop('readonly',true);
     $(this).closest('tr').find('input[name="txt_middle[]"]').prop('readonly',true);
     $(this).closest('tr').find('input[name="txt_khan[]"]').prop('readonly',true);
     $(this).closest('tr').find('input[name="txt_rafa[]"]').prop('readonly',true);
     var prev_value_north = $(this).closest('tr').find('input[name="prev_value_north[]"]').val();
     var prev_value_gaza = $(this).closest('tr').find('input[name="prev_value_gaza[]"]').val();
     var prev_value_middle = $(this).closest('tr').find('input[name="prev_value_middle[]"]').val();
     var prev_value_khan = $(this).closest('tr').find('input[name="prev_value_khan[]"]').val();
     var prev_value_rafa = $(this).closest('tr').find('input[name="prev_value_rafa[]"]').val();
     $(this).closest('tr').find('input[name="txt_north[]"]').val((Number(prev_value_north)+(Number(prev_value_north)*(Number($(this).val())/100))).toFixed(2));
     $(this).closest('tr').find('input[name="txt_gaza[]"]').val((Number(prev_value_gaza)+(Number(prev_value_gaza)*(Number($(this).val())/100))).toFixed(2));
     $(this).closest('tr').find('input[name="txt_middle[]"]').val((Number(prev_value_middle)+(Number(prev_value_middle)*(Number($(this).val())/100))).toFixed(2));
     $(this).closest('tr').find('input[name="txt_khan[]"]').val((Number(prev_value_khan)+(Number(prev_value_khan)*(Number($(this).val())/100))).toFixed(2));
     $(this).closest('tr').find('input[name="txt_rafa[]"]').val((Number(prev_value_rafa)+(Number(prev_value_rafa)*(Number($(this).val())/100))).toFixed(2));


     }
     else if($(this).val() ==0 || $(this).val() == '')
     {

     $(this).closest('tr').find('input[name="txt_north[]"]').prop('readonly',false);
     $(this).closest('tr').find('input[name="txt_gaza[]"]').prop('readonly',false);
     $(this).closest('tr').find('input[name="txt_middle[]"]').prop('readonly',false);
     $(this).closest('tr').find('input[name="txt_khan[]"]').prop('readonly',false);
     $(this).closest('tr').find('input[name="txt_rafa[]"]').prop('readonly',false);
     $(this).closest('tr').find('input[name="txt_north[]"]').val(0);
     $(this).closest('tr').find('input[name="txt_gaza[]"]').val(0);
     $(this).closest('tr').find('input[name="txt_middle[]"]').val(0);
     $(this).closest('tr').find('input[name="txt_khan[]"]').val(0);
     $(this).closest('tr').find('input[name="txt_rafa[]"]').val(0);
     }
     else
     {
     danger_msg('ادخال خاطئ لنسبة/او ان النسبة سالبة', '');
     $(this).val(0);
     }




     });

     $('input[name="txt_north[]"]').change(function () {
     var prev_value_north = $(this).closest('tr').find('input[name="prev_value_north[]"]').val();
     $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_north))/Number(prev_value_north))*100).toFixed(2));
     $('input[name="txt_t_persant[]"]').change();
     });

     $('input[name="txt_gaza[]"]').change(function () {
     var prev_value_gaza = $(this).closest('tr').find('input[name="prev_value_gaza[]"]').val();
     $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_gaza))/Number(prev_value_gaza))*100).toFixed(2));
     $('input[name="txt_t_persant[]"]').change();
     });


     $('input[name="txt_middle[]"]').change(function () {
     var prev_value_middle = $(this).closest('tr').find('input[name="prev_value_middle[]"]').val();
     $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_middle))/Number(prev_value_middle))*100).toFixed(2));
     $('input[name="txt_t_persant[]"]').change();
     });


     $('input[name="txt_khan[]"]').change(function () {
     var prev_value_khan = $(this).closest('tr').find('input[name="prev_value_khan[]"]').val();
     $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_khan))/Number(prev_value_khan))*100).toFixed(2));
     $('input[name="txt_t_persant[]"]').change();
     });

     $('input[name="txt_rafa[]"]').change(function () {
     var prev_value_rafa = $(this).closest('tr').find('input[name="prev_value_rafa[]"]').val();
     $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_rafa))/Number(prev_value_rafa))*100).toFixed(2));
     $('input[name="txt_t_persant[]"]').change();
     });
     }



     });
     */

</script>



