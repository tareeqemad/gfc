<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 22/01/20
 * Time: 11:48 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'employeeTraining';

$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>


        <div class="form-body">
            <div class="modal-body inline_form">
            </div>


            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend >الاستعلام عن الدورات للموظفين</legend>

                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-2">
                                <label class="control-label"> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($branches as $row) :?>
                                        <option value="<?= $row['NO'] ?>" > <?= $row['NAME'] ?> </option>
                                    <?php endforeach; ?>
                                </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم الدورة</label>
                            <div>
                                <input type="text"  placeholder="رقم الدورة"
                                       name="course_no"
                                       id="txt_course_no" class="form-control"  >
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">اسم الدورة/ عربية</label>
                            <div>
                                <input type="text"  placeholder="اسم الدورة"
                                       name="course_name"
                                       id="txt_course_name" class="form-control"  >
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">اسم الدورة/ انجليزية</label>
                            <div>
                                <input type="text"  placeholder="Course Name"
                                       name="course_name_eng"
                                       id="txt_course_name_eng" class="form-control"  >
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">تاريخ بداية الدورة</label>
                            <div>
                                <input type="text"  placeholder="تاريخ بداية الدورة"
                                       name="course_date"
                                       id="txt_course_date" class="form-control"  >
                            </div>
                        </div>


                        <br>
                    </div>


                </fieldset>


            </form>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php // echo  modules::run($get_page,$page); ?>

            </div>
        </div>

    </div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

 $(document).ready(function() {

		$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
        });




   });

</script>

SCRIPT;
sec_scripts($scripts);
?>