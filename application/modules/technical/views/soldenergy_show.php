<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/12/17
 * Time: 09:30 ص
 */

$back_url = base_url('technical/SoldEnergy');

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;


?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>


    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-form-vertical" id="SoldEnergy_form" method="post"
                  action="<?= base_url('technical/SoldEnergy/' . ($HaveRs ? ($action == 'index' ? 'edit' : $action) : 'create')) ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> الشهر </label>

                        <div>
                            <input type="hidden" value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                                   name="ser"
                                   id="ser" class="form-control">

                            <input type="text" value="<?= $HaveRs ? $rs['MONTH'] : "" ?>"
                                   name="month"
                                   data-date-format="YYYYMM"
                                   data-date-viewmode="years"
                                   data-date-minviewmode="months"
                                   data-val-required="required"
                                   data-val="true"
                                   data-type="date"
                                   id="month" class="form-control">
                        </div>
                    </div>


                  <!--  <div class="form-group  col-sm-2">
                        <label class="control-label"> الخط الرئيسي   </label>


                        <select name="main_feeder" id="main_feeder" class="form-control">

                            <?php /*foreach ($feeder_name as $row) : */?>
                                <option   <?/*= $HaveRs ? ($row['CON_NO'] == $rs['MAIN_FEEDER'] ? 'selected' : '') : '' */?>
                                    value="<?/*= $row['CON_NO'] */?>"><?/*= $row['CON_NAME'] */?></option>
                            <?php /*endforeach; */?>
                        </select>


                    </div>-->


                    <div class="form-group  col-sm-2">
                        <label class="control-label">فاتورة العدادات المكيكانيكية ك.و.س</label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PAID'] : "" ?>"
                                   name="paid"
                                   data-val-required="required"
                                   data-val="true"
                                   id="paid" class="form-control">
                        </div>
                    </div>


                    <div class="form-group  col-sm-2">
                        <label class="control-label"> فاتورة العدادات مسبقة الدفع ك.و.س </label>

                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PREPAID'] : "" ?>"
                                   name="prepaid"
                                   data-val-required="required"
                                   data-val="true"
                                   id="prepaid" class="form-control">
                        </div>
                    </div>



                    <hr/>
                    <div class="modal-footer">

                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                    </div>
                </div>
            </form>

        </div>
    </div>
</div>


<?php

$projectTecUrl = base_url('projects/projects/publicGetProjectByTec');
$projectItemTecUrl = base_url('technical/SoldEnergy/publicGetProjectItem');

$script = <<<SCRIPT
<script>
$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                reload_Page();

            },'html');
        }
    });

</script>

SCRIPT;

sec_scripts($script);

?>

