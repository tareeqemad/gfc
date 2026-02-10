<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url = base_url('technical/Adapter_address');


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
                <form class="form-form-vertical" id="hpp_form" method="post"
                      action="<?= base_url('technical/Adapter_address/' . ($HaveRs ? 'edit' : 'create')) ?>" role="form"
                      novalidate="novalidate">
                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-1">
                            <label class="control-label"> الرقم </label>

                            <div>
                                <input type="text" readonly value="<?= $HaveRs ? $rs['ID'] : "" ?>" name="id"
                                       id="txt_id"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group  col-sm-1">
                            <label class="control-label">الفرع </label>

                            <div>

                                <select type="text" name="branch" id="dp_branch" class="form-control">

                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= $HaveRs ? ($rs['BRANCH'] == $row['NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                        </div>


                        <div class="form-group col-sm-3">
                            <label class="control-label">المكان</label>

                            <div>
                                <input type="text" data-val="true" data-val-required="يجب إدخال البيان "
                                       value="<?= $HaveRs ? $rs['ADDRESS'] : "" ?>" name="address" id="txt_address"
                                       class="form-control">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-12 control-label">الموقع (خريطة)</label>

                            <div class="col-sm-6">
                                <input type="text" data-val="true" data-val-required="يجب إدخال الاحداثيات "
                                       value="<?= $HaveRs ? $rs['X_GIS'] : "" ?>" name="x" id="txt_x"
                                       class="form-control">

                            </div>
                            <div class="col-sm-6">
                                <input type="text" data-val="true" data-val-required="يجب إدخال الاحداثيات "
                                       value="<?= $HaveRs ? $rs['Y_GIX'] : "" ?>" name="y" id="txt_y"
                                       class="form-control">

                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="col-sm-12 control-label" style="height: 25px;"> </label>
                            <button type="button" class="btn green"
                                    onclick="javascript:_showReport('<?= base_url("maps/public_map/txt_x/txt_y") ?>');">
                                <i class="icon icon-map-marker"></i>
                            </button>

                        </div>

                    </div>


                    <div class="modal-footer">
                        <?php if ((isset($can_edit) && $can_edit) || !$HaveRs) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>
                        <button type = "button" class="btn btn-default" data - dismiss = "modal" > إغلاق</button >

                    </div>
                </form>

            </div>

        </div>
    </div>

<?php
$delete_url = base_url('technical/Adapter_address/delete_partition');
$adapters_url = base_url('projects/adapter/public_index');
$shared_script = <<<SCRIPT
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

        $('#txt_adapter_serial').click(function(){
            _showReport('$adapters_url/'+$(this).attr('id')+'/');
        });


SCRIPT;


$create_script = <<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;


$edit_script = <<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;

if ($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>