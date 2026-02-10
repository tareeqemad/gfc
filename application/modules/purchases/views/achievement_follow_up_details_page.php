<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'purchases';
$TB_NAME = 'achievement_follow_up_details';

$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$save_part_url='';
//if (count($get_list) <= 0)
    $save_part_url = $create_url;
/*else if (count($get_list) > 0)
    $save_part_url = $edit_url;*/
$count = 0;

?>

<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered roundedTable" id="details_tb1">
            <thead class="table-info">
            <tr>
                <th class="hidden" style="width: 8%">م.</th>
                <th style="width: 8%">المدة المعتمدة</th>
                <th style="width: 15%">الفاعلية</th>
            </tr>
            </thead>
            <tbody>

            <?php if (count($get_list) <= 0) {  // ادخال
                $count++;
                ?>
                <tr>
                    <td class="hidden">
                        <?= $count; ?>
                        <input type="hidden" name="achive_ser[]" id="achive_ser_<?= $count ?>" data-val="true"
                               data-val-required="required" value="<?=$master_ser;?>">
                        <input type="hidden" name="ser_dets[]" id="ser_dets[]" value="0"/>
                        <input type="hidden" name="h_count[]" id="h_count_<?= $count ?>"/>


                    </td>
                    <td>
                        <input class="form-control" name="achive_num[]" id="achive_num_<?= $count ?>" data-val="true"
                               data-val-required="حقل مطلوب"/>
                        <span class="field-validation-valid" data-valmsg-for="achive_num[]"
                              data-valmsg-replace="true"></span>


                    </td>
                    <td>
                        <select name="status_dets[]" data-val="true" data-val-required="حقل مطلوب"
                                id="status_<?= $count ?>" class="form-control">
                            <?php foreach ($status as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>


                            <?php endforeach; ?>


                        </select>
                        <span class="field-validation-valid" data-valmsg-for="status_dets[]"
                              data-valmsg-replace="true"> </span>
                    </td>


                </tr>
                <?php
            } else if (count($get_list) > 0) { // تعديل
                $count = -1;
                foreach ($get_list as $row) {
                    ++$count + 1;
                    ?>

                    <tr>
                        <td class="hidden">
                            <?= $count; ?>
                            <input type="hidden" name="achive_ser[]" id="achive_ser_<?= $count ?>" data-val="true"
                                   data-val-required="required" value="<?= $row['ACHIVE_SER'] ?>">
                            <input type="hidden" name="ser_dets[]" id="ser_dets[]" value="<?= $row['SER'] ?>"/>
                            <input type="hidden" name="h_count[]" id="h_count_<?= $count ?>"/>

                        </td>

                        <td>

                            <input class="form-control" name="achive_num[]" id="achive_num_<?= $count ?>"
                                   data-val="false" value="<?= $row['ACHIVE_NUM'] ?>"
                                   data-val-required="حقل مطلوب"/>
                            <span class="field-validation-valid" data-valmsg-for="achive_num[]"
                                  data-valmsg-replace="false"></span>


                        </td>

                        <td>
                            <select name="status_dets[]" data-val="true" data-val-required="حقل مطلوب"
                                    id="status_<?= $count ?>" class="form-control">
                                <?php foreach ($status as $row2) : ?>
                                    <option value="<?= $row2['CON_NO'] ?>" <?php if ($row['STATUS'] == $row2['CON_NO']) {
                                        echo " selected";
                                    } ?>><?= $row2['CON_NAME'] ?></option>


                                <?php endforeach; ?>


                            </select>
                            <span class="field-validation-valid" data-valmsg-for="status_dets[]"
                                  data-valmsg-replace="true"> </span>
                        </td>


                    </tr>
                    <?php

                }
            }

            ?>
            </tbody>

            <tfoot>
            <tr>
                <th class="hidden"></th>
                <th></th>
                <th></th>


            </tr>
            <tr>
                <th class="hidden"></th>
                <th>
                    <a onclick="add_row(this,'input:text,input:hidden[name^=ser_dets],textarea,select',false);"
                       href="javascript:"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                </th>

                <th></th>


            </tr>

            </tfoot>
        </table>
    </div>
</div>
<div class="modal-footer">
    <?php if (HaveAccess($save_part_url)) : ?>
        <button type="button" data-action="submit" class="btn btn-primary save_part">حفظ البيانات</button>
        <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>

    <?php endif; ?>
</div>
<script type="text/javascript">
    reBind_pram(0);
    $('.save_part').on('click',  function (e) {

        var url = "<?php echo $save_part_url?>";
        var tbl = '#details_tb1';
        var container = $('#' + $(tbl).attr('data-container'));
        var achive_ser= [];
        var ser_dets = [];
        var achive_num = [];
        var status_dets = [];
        $('input[name="achive_num[]"]').each(function (i) {

            if($(this).closest('tr').find('input[name="achive_num[]"]').val() !='' || achive_num[i]==0)
            {
                ser_dets[i]=$(this).closest('tr').find('input[name="ser_dets[]"]').val();
                achive_ser[i]=$(this).closest('tr').find('input[name="achive_ser[]"]').val();
                achive_num[i]=$(this).closest('tr').find('input[name="achive_num[]"]').val();
                status_dets[i]=$(this).closest('tr').find('select[name="status_dets[]"]').val();



            }
            else
            {

                    danger_msg('يجب تحديد المدة المعتمدة!!');


            }


        });
        if(achive_num.length > 0){
            $( ".save_part" ).remove();
            get_data(url,{ser_dets:ser_dets,achive_ser:achive_ser,achive_num:achive_num,status_dets:status_dets},function(data){
                if(data>=1)
                {

                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#Achievement_follow_up_detailsModal').modal('hide');

                }

                else
                    danger_msg('لم يتم الحفظ', data);


            });


        }
    });
</script>
