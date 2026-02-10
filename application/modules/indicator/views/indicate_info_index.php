<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/09/18
 * Time: 12:50 م
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indecator_info';
$TB_NAME2='manage_indecatior_info';//controller
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
//$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$create_url =base_url("$MODULE_NAME/$TB_NAME2");
$get_all_sectors =base_url("indicator/indecator_info/public_get_sectors");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title;?></div>
        <ul>

            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"> <i class=""></i>ادارة نظام المعلومات و التقارير</a> </li><?php endif; ?>
            <?php /*if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"> <i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif*/; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">


                <!--
                    <div class="form-group col-sm-1" >

                        <label class="control-label">المقر</label>
                        <div>
                            <select  name="branch" id="dp_branch"  data-curr="false"  class="form-control"  >
                                <option value="">-</option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>">
                                        <?= $row['NAME'] ?>
                                    </option>


                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
-->
                <div class="form-group col-sm-2">
                    <label class="col-sm-1 control-label">القطاع</label>
                    <div>
                        <select name="sector"  id="dp_sector" class="form-control">
                            <option></option>
                            <?php foreach($all_sectors as $row) :?>
                                <option value="<?= $row['ID'] ?>" >
                                    <?= $row['ID_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        </select>

                        <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>




                    </div>
                </div>

                <div class="form-group col-sm-2">

                    <label class="col-sm-1 control-label">التصنيف الاول</label>
                    <div>
                        <select name="class"   id="dp_class" class="form-control">
                            <option></option>

                            <?php foreach($class_list as $row) :?>
                                <option value="<?= $row['ID'] ?>" ><?= $row['ID_NAME'] ?></option>
                            <?php endforeach; ?>

                        </select>

                        <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>




                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="col-sm-1 control-label">التصنيف الثاني</label>
                    <div>
                        <select name="second_class"   id="dp_second_class" class="form-control">
                            <option></option>

                            <?php foreach($second_class_list as $row) :?>
                                <option value="<?= $row['ID'] ?>" ><?= $row['ID_NAME'] ?></option>
                            <?php endforeach; ?>

                        </select>
                        <span class="field-validation-valid" data-valmsg-for="second_class" data-valmsg-replace="true"></span>




                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="col-sm-1 control-label">الحالة</label>
                    <div>
                        <select name="indecator_flag" data-val="true"  data-val-required="حقل مطلوب"  id="dp_indecator_flag" class="form-control select2">
                            <option></option>
                            <?php foreach($indecator_flag as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>">
                                    <?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="indecator_flag" data-valmsg-replace="true"></span>




                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="col-sm-1 control-label">الاعتماد</label>
                    <div>
                        <select name="adopt" data-val="true"  data-val-required="حقل مطلوب"  id="dp_adopt" class="form-control select2">
                            <option></option>

                            <?php   foreach($adopt_all as $row) :?>

                                <option value="<?= $row['CON_NO']   ?>"><?= $row['CON_NAME'] ?></option>
                            <?php  endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="adopt" data-valmsg-replace="true"></span>




                    </div>
                </div>



                <div class="form-group col-sm-2">
                    <label class="col-sm-12 control-label">طريقة حساب المستهدف</label>
                    <div>
                        <select name="entry_target_way" data-val="true"  data-val-required="حقل مطلوب"  id="dp_entry_target_way" class="form-control select2">
                            <option></option>

                            <?php   foreach($entry_target_way as $row) :?>
                                <?php //if ($row['CON_NO'] != 2 ){?>
                                <option value="<?= $row['CON_NO']   ?>"><?= $row['CON_NAME'] ?></option>
                            <?php  endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="entry_target_way" data-valmsg-replace="true"></span>




                    </div>
                </div>


                    <div class="form-group col-sm-3">
                        <label class="control-label">اسم المؤشر</label>
                        <div>
                            <input type="text" name="indecator_name"  id="txt_indecator_name"  class="form-control"   data-val-required="حقل مطلوب" >
                        </div>
                    </div>




            </div>


        </form>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php echo '';/*modules::run($get_page_url,$page);*/?>
            </div>
        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
function search(){


       var values= {page:1,sector:$('#dp_sector').val(),class:$('#dp_class').val(),second_class:$('#dp_second_class').val(),indecator_flag:$('#dp_indecator_flag').val(),adopt:$('#dp_adopt').val(),entry_target_way:$('#dp_entry_target_way').val(),indecator_name:$('#txt_indecator_name').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
   var values= {page:1,sector:$('#dp_sector').val(),class:$('#dp_class').val(),second_class:$('#dp_second_class').val(),indecator_flag:$('#dp_indecator_flag').val(),adopt:$('#dp_adopt').val(),entry_target_way:$('#dp_entry_target_way').val(),indecator_name:$('#txt_indecator_name').val()};
              ajax_pager_data('#page_tb > tbody',values);
    }

$(document).ready(function() {
$('#dp_second_class').select2();
$('.select2').select2();
 $('#dp_branch').select2().on('change',function(){

        });



$('#dp_sector').select2().on('change',function(){

       get_data('{$get_all_sectors}',{no: $('#dp_sector').val()},function(data){
            $('#dp_class').html('');
              $('#dp_class').append('<option></option>');
              $("#dp_class").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_class').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });
        });

$('#dp_class').select2().on('change',function(){

       get_data('{$get_all_sectors}',{no: $('#dp_class').val()},function(data){
            $('#dp_second_class').html('');
              $('#dp_second_class').append('<option></option>');
              $("#dp_second_class").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_second_class').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });
        });


});



</script>

SCRIPT;

sec_scripts($scripts);

?>