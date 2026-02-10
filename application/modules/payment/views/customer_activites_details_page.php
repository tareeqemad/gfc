<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$count = 0;
$MODULE_NAME = 'purchases';
$TB_NAME = 'customers_activity';
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
?>

<div class="tbl_container">
    <table class="table" id="customers_activites_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th>النشاط</th>
            <?php if (HaveAccess($delete_url)) { ?>
            <th style="width: 70px">حذف</th>
            <?php } ?>
        </tr>

        </thead>

        <tbody>

        <?php if(count($activities_details) <= 0) : ?>
            <tr>
                <td>
                    <input type="hidden" name="act_ser[]" value="0" />
                    <select name="activity_no[]" id="dp_activity_no_<?=$count?>" data-curr="false" class="form-control select2" >
                        <option  value="">-</option>
                        <?php foreach ($activities_index as $activity)  {?>
                            <option value="<?= $activity['SER'] ?>"><?=$activity['ACTIVITY_NAME']?></option>
                        <?php } ?>

                    </select>

                </td>
                <?php if (HaveAccess($delete_url)) { ?>
                <td></td>
                <?php } ?>


            </tr>

        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($activities_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td>
                    <input type="hidden" name="act_ser[]" value="<?=$row['SER_SEQ']?>" />
                    <select name="activity_no[]" id="dp_activity_no_<?=$count?>" data-curr="true" class="form-control select2" >
                        <option  value="">-</option>
                        <?php foreach ($activities_index as $activity)  {?>
                            <option value="<?= $activity['SER'] ?>" <?PHP if ($activity['SER']==$row['ACTIVITY_NO']){ echo " selected"; } ?>><?= $activity['ACTIVITY_NAME']?></option>
                        <?php } ?>

                    </select>
                </td>

                <?php if (HaveAccess($delete_url)) { ?>
                    <td>
                    <a onclick="deleteactivity('<?= $row['SER_SEQ'] ?>');" href="javascript:"><i
                                class="glyphicon glyphicon-trash"></i> </a>


                    </td><?php } ?>

            </tr>

        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th>
                <?php if (count($activities_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="javascript:add_row(this,'input:hidden[name^=act_ser],select',false);" onfocus="javascript:add_row(this,'input:hidden[name^=act_ser],select',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>
            <?php if (HaveAccess($delete_url)) { ?>
            <th></th>
            <?php }?>


        </tr></tfoot>
    </table>
</div>



    <script type="text/javascript">
        reBind_pram(0);
        function reBind_pram(cnt){
        $('table tr td .select2-container').remove();
        $('select[name="activity_no[]"]').select2().on('change',function(){

        //  checkBoxChanged();

    });
    }
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
