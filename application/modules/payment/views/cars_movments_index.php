<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 24/04/19
 * Time: 10:53 ص
 */


$get_url =base_url('payment/Carmovements/get_id');
$edit_url =base_url('payment/Carmovements/edit');
$create_url =base_url('payment/Carmovements/create');
$get_page_url = base_url('payment/cars/get_page');
$MODULE_NAME= 'payment';
$TB_NAME= 'Carmovements';
$back_url=base_url("$MODULE_NAME/$TB_NAME");
$status_url=base_url("$MODULE_NAME/$TB_NAME/status");

$report_url =base_url('reports?type=31');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>

    </div>

</div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">

                <!-------------------------رقم الطلب----------------------------->
                <div class="form-group col-sm-1">
                    <label class="control-label"> رقم الطلب </label>
                    <div>
                        <input type="text"  name="order_no"   id="txt_order_no" class="form-control " "="">
                    </div>
                </div>
                <!-------------------------اسم الموظف----------------------------->
<!--                <div class="form-group col-sm-3">-->
<!--                    <label class="control-label"> اسم الموظف </label>-->
<!--                    <div>-->
<!--                        <input type="text"  name="emp_name"   id="txt_emp_name" class="form-control " "="">-->
<!--                    </div>-->
<!--                </div>-->


                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!--------------------------رقم السيارة----------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم السيارة  </label>
                    <div>
                        <input type="text"  name="car_id" id="txt_car_id" class="form-control">
                    </div>
                </div>

                <!--------------------------نوع السيارة----------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع السيارة  </label>
                    <div>
                        <select data-val="true" name="car_type" id="txt_car_type" class="form-control">
                            <option></option>
                            <?php foreach ($car_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-------------------------رقم السائق----------------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم السائق </label>
                    <div>
                        <input type="text"  name="driver_id"   id="txt_driver_id" class="form-control " "="">
                    </div>
                </div>
                <div style="clear: both"></div>
                <!---------------------------تاريخ الحركة من ------------------------------>
                <div class="form-group col-sm-2">
                    <label class="control-label">  تاريخ الحركة من </label>
                    <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="the_date" value="<?=date('d/m/Y',strtotime('-0 day'))?>" id="txt_the_date" class="form-control">
                </div>
                <!---------------------------تاريخ الحركة الى------------------------------>
                <div class="form-group col-sm-2">
                    <label class="control-label"> الى </label>
                    <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_the_date"  id="to_txt_the_date" class="form-control">
                </div>
                <!------------------------------------نوع الحركة-------------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع الحركة  </label>
                    <div>
                        <select data-val="true" name="movment_type" id="txt_movment_type" class="form-control">
                            <option></option>
                            <?php foreach ($movement_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!------------------------------------المقر-------------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label"> المقر  </label>
                    <div>
                        <select data-val="true" name="branches" id="dp_branches" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!------------------------------------الحالة-------------------------->

                <!--
                <div class="form-group col-sm-2">
                    <label class="control-label"> حالة الحركة  </label>
                    <div>
                        <select data-val="true" name="movment_status" id="txt_movment_status" class="form-control">
                            <option></option>
                            <?php /*foreach ($movement_status as $row) : */?>
                                <option value="<?/*= $row['CON_NO'] */?>"><?/*= $row['CON_NAME'] */?></option>
                            <?php /*endforeach; */?>
                        </select>
                    </div>
                </div>-->

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="$('#chainsTbl').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">

        </div>

    </div>

</div>

<?php


$scripts = <<<SCRIPT

<script>

    $('.sel2').select2();
        
    if ('{$emp_branch_selected}' != 1){
        $('#dp_branches').select2('val','{$emp_branch_selected}');
        $('#dp_branches').select2('readonly','{$emp_branch_selected}');
    }else { 
        $('#dp_branches').select2('val','{$emp_branch_selected}');
    }

</script>

SCRIPT;

sec_scripts($scripts);
?>





