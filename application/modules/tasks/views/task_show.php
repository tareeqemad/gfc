<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/04/18
 * Time: 12:54 م
 */

$MODULE_NAME='tasks';
$TB_NAME='task';
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$isCreate= 1;
$back_url='';

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>

    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">

        <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$create_url?>" role="form" novalidate="novalidate">
            <div class="modal-body">

                <?php foreach($direct_type_cons as $type) :?>

                <div class="form-group">
                    <label class="col-sm-2 control-label"> <?=$type['CON_NAME']?> </label>
                    <div class="col-sm-8">
                        <select multiple id="dp_direct_<?=$type['CON_NO']?>" class="form-control sel2"  >
                            <?php foreach($emps_direct as $row) :?>
                                <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" class="task_directs" data-con="<?=$type['CON_NO']?>" id="h_direct_<?=$type['CON_NO']?>" name="direct_<?=$type['CON_NO']?>" value="" />
                        <input type="hidden" name="parent_task" value="<?=$parent_task?>" />
                    </div>
                </div>

                <?php endforeach; ?>


                <div class="form-group">
                    <label class="col-sm-2 control-label"> نوع المهمة </label>
                    <div class="col-sm-8">
                        <select name="task_direction_type" id="dp_task_direction_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($task_direction_type_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-sm-2 control-label"> عنوان المهمة </label>
                    <div class="col-sm-8">
                        <input type="text" name="task_title" id="txt_task_title" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"> درجة الاولوية  </label>
                    <div class="col-sm-8">
                        <select name="priority" id="dp_priority" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($priority_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"> تاريخ الانجاز المطلوب  </label>
                    <div class="col-sm-8">
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" name="done_date" id="txt_done_date" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"> نص المهمة </label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="task_text" id="txt_task_text" rows="7"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"> مؤشرات القياس </label>
                    <div class="col-sm-8">

                        <label style="display: none">
                        <input type='checkbox' class='checkboxes' value='2' />
                        محمد
                        </label>
                        <input disabled type="hidden" id="h_cats" value="" />


                        <!-- مؤشرات القياس -->
                        <div class="tb_container">
                            <table class="table" id="details_tb" data-container="container">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">الموظف</th>
                                        <th style="width: 25%">التصنيف</th>
                                        <th style="width: 40%">البند</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="item_emp_no[]" id="dp_item_emp_no_0" class="form-control item_emps" />
                                            <option value="">_________</option>
                                                <?php foreach($emps_direct as $row) :?>
                                                    <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="cat_no[]" class="form-control" id="dp_cat_no_0" />
                                            <option value="">_________</option>
                                            <?php foreach($cats_list as $row) :?>
                                                <option value="<?=$row['CAT_NO']?>" ><?=$row['CAT_NAME']?></option>
                                            <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input name="item_desc[]" class="form-control" id="txt_item_desc_0" />
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th> <a onclick="javascript:add_row(this,'input',false);item_emps_select();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a> </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- مؤشرات القياس -->


                    </div>
                </div>


            </div>

            <div class="modal-footer">

                <?php if ( HaveAccess($create_url) && $isCreate ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">ارسال</button>
                <?php endif; ?>

            </div>


        </form>

    </div>
</div>
