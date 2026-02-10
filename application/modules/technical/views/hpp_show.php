<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url=base_url('technical/HighPowerPartition');


if(!isset($result))
    $result= array();
$HaveRs = count($result) > 0;

$rs =$HaveRs? $result[0] : $result;

?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>
            <ul>
                <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>


        </div>

        <div class="form-body">

            <div id="msg_container"></div>
            <div id="container">
                <form class="form-form-vertical" id="hpp_form" method="post" action="<?=base_url('technical/HighPowerPartition/'.($HaveRs?'edit':'create'))?>" role="form" novalidate="novalidate">
                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-1">
                            <label class="control-label"> الرقم  </label>
                            <div>
                                <input type="text"  readonly  value="<?= $HaveRs? $rs['HIGH_POWER_PARTITION_SERIAL'] : "" ?>"  name="high_power_partition_serial" id="txt_high_power_partition_serial" class="form-control">
                            </div>
                        </div>

                        <div class="form-group  col-sm-1">
                            <label class="control-label">الفرع </label>
                            <div>

                                <select type="text"   name="branch" id="dp_branch" class="form-control" >

                                    <?php foreach($branches as $row) :?>
                                        <option <?= $HaveRs?($rs['BRANCH'] ==$row['NO']?'selected':''):'' ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>    </div>
                        </div>


                        <div class="form-group col-sm-2">
                            <label class="control-label">الاتجاه </label>
                            <div>

                                <select   name="hpartition_direction" id="txt_hpartition_direction" class="form-control">
                                    <option <?= $HaveRs?($rs['HPARTITION_DIRECTION'] ==1?'selected':''):'' ?> value="1" >رأسي</option>
                                    <option <?= $HaveRs?($rs['HPARTITION_DIRECTION'] ==2?'selected':''):'' ?> value="2" >جانبي</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label"> البيان   </label>
                            <div>
                                <input type="text" data-val="true" data-val-required="يجب إدخال البيان "    value="<?= $HaveRs? $rs['NOTES'] : "" ?>"  name="notes" id="txt_notes" class="form-control">
                            </div>
                        </div>


                        <div class="form-group col-sm-2">
                            <label class="control-label"> الشركة المصنعة  </label>
                            <div>
                                <input type="text"   value="<?= $HaveRs? $rs['HPARTITION_ID'] : "" ?>"  name="hpartition_id" id="txt_hpartition_id" class="form-control">
                            </div>
                        </div>

                        <hr>
                        <div class="form-group col-sm-1">
                            <label class="control-label">حالة السكينة </label>
                            <div>
                                <select   name="hpartition_case" id="dp_hpartition_case" class="form-control">
                                    <?php foreach($HPARTITION_CASE as $row) :?>
                                        <option <?= $HaveRs?($rs['HPARTITION_CASE'] ==$row['CON_NO']?'selected':''):'' ?> value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">الاستخدام </label>
                            <div>

                                <select   name="hpartition_activiation" id="txt_hpartition_activiation" class="form-control">
                                    <option <?= $HaveRs?($rs['HPARTITION_ACTIVIATION'] ==1?'selected':''):'' ?> value="1" >مستخدمة</option>
                                    <option <?= $HaveRs?($rs['HPARTITION_ACTIVIATION'] ==2?'selected':''):'' ?> value="2" >غير مستخدمة</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">عمل السكينة </label>
                            <div>

                                <select   name="hpartition_cut" id="txt_hpartition_cut" class="form-control">
                                    <option <?= $HaveRs?($rs['HPARTITION_CUT'] ==1?'selected':''):'' ?> value="1" >فصل المحول</option>
                                    <option <?= $HaveRs?($rs['HPARTITION_CUT'] ==2?'selected':''):'' ?> value="2" > فصل الشبكة </option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع العازل  </label>
                            <div>

                                <select   name="insulator_type" id="txt_insulator_type" class="form-control">
                                    <option <?= $HaveRs?($rs['INSULATOR_TYPE'] ==1?'selected':''):'' ?> value="1" >بولمير </option>
                                    <option <?= $HaveRs?($rs['INSULATOR_TYPE'] ==2?'selected':''):'' ?> value="2" > بورسلان  </option>
                                </select>
                            </div>
                        </div>





                        <div class="form-group col-sm-1">
                            <label class="control-label">التأريض   </label>
                            <div>

                                <select   name="ground" id="txt_ground" class="form-control">
                                    <option <?= $HaveRs?($rs['GROUND'] ==1?'selected':''):'' ?> value="1" > مؤرضة</option>
                                    <option <?= $HaveRs?($rs['GROUND'] ==2?'selected':''):'' ?> value="2" > غير مؤرضة  </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> الخط المغذي </label>
                            <div>

                                <select   name="feeder_line" id="txt_feeder_line" class="form-control">
                                    <?php foreach($FEEDER_LINE as $row) :?>
                                        <option <?= $HaveRs?($rs['FEEDER_LINE'] ==$row['CON_NO']?'selected':''):'' ?> value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group  col-sm-2">
                            <label class="control-label">المحول
                            </label>
                            <div>
                                <input name="adapter_serial" type="hidden"  value="<?= $HaveRs? $rs['ADAPTER_SERIAL'] : "" ?>"  id="h_txt_adapter_serial" class="form-control">
                                <input name="adapter_serial_name" value="<?= $HaveRs? $rs['ADAPTER_SERIAL_NAME'] : "" ?>" readonly     id="txt_adapter_serial" class="form-control">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-12 control-label">الموقع (خريطة)</label>
                            <div class="col-sm-6">
                                <input type="text"  data-val="true" data-val-required="يجب إدخال الاحداثيات "    value="<?= $HaveRs? $rs['X'] : "" ?>"   name="x" id="txt_x" class="form-control">

                            </div>
                            <div class="col-sm-6">
                                <input type="text"   data-val="true" data-val-required="يجب إدخال الاحداثيات "   value="<?= $HaveRs? $rs['Y'] : "" ?>"   name="y" id="txt_y" class="form-control">

                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="col-sm-12 control-label" style="height: 25px;">  </label>
                            <button  type="button" class="btn green" onclick="javascript:_showReport('<?=base_url("maps/public_map/txt_x/txt_y") ?>');">
                                <i class="icon icon-map-marker"></i>
                            </button>

                        </div>
                        <hr>



                        <div class="form-group  col-sm-7">
                            <label class="control-label">الملاحظات</label>
                            <div >
                                <input type="text"   value="<?= $HaveRs? $rs['HINT'] : "" ?>"   name="hint" id="txt_hint" class="form-control">
                            </div>
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </form>

            </div>

        </div>
    </div>

<?php
$delete_url = base_url('technical/HighPowerPartition/delete_partition');
$adapters_url =base_url('projects/adapter/public_index');
$shared_script=<<<SCRIPT
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


$create_script=<<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;



$edit_script=<<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;

if($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>