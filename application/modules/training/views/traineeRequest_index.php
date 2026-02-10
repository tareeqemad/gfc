<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 23/01/20
 * Time: 11:33 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';

$assign_url= base_url("$MODULE_NAME/$TB_NAME/assign");
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if (HaveAccess($create_url)): ?>
                    <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
                <?php endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>


        <div class="form-body">
            <div class="modal-body inline_form">
            </div>


            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend >الاستعلام عن طلبات المدربين</legend>

                    <div class="modal-body inline_form">



                        <div class="form-group col-sm-2">
                            <label class="control-label"> المعني بالتدريب</label>
                            <div>
                                <select name="trainee_type" id="dp_trainee_type" class="form-control sel2">
                                    <option></option>
                                    <?php foreach($trainee_type as $row) :
									if($row['CON_NO'] != 3){?>
                                        <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME']?></option>
                                    <?php }endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div id="company_div">

                            <div class="form-group col-sm-2">
                                <label class="control-label">رقم الترخيص</label>
                                <div>
                                    <input type="text"  placeholder="رقم الترخيص"
                                           name="license_number"
                                           id="txt_license_number" class="form-control"  >
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label class="control-label">اسم الشركة</label>
                                <div>
                                    <input type="text"  placeholder="اسم الشركة"
                                           name="company_name"
                                           id="txt_company_name" class="form-control"  >
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label class="control-label">نوع الشركة</label>
                                <div>
                                    <input type="text"  placeholder="نوع الشركة"
                                           name="company_type"
                                           id="txt_company_type" class="form-control"  >
                                </div>
                            </div>


                        </div>

                        <br>
                        <div id="person_div">
                            <div class="form-group col-sm-2">
                                <label class="control-label">الاعلان</label>
                                <div>
                                    <select name="advers" id="dp_advers" class="form-control sel2">
                                        <option></option>
                                        <?php foreach($advers as $row) :?>
                                            <option  value="<?= $row['ADVER_ID'] ?>"><?= $row['ADVER_ID'] ." .". $row['ADVER_TITLE']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label class="control-label">رقم الهوية</label>
                                <div>
                                    <input type="text"  placeholder="رقم الهوية"
                                           name="id_number"
                                           id="txt_id_number" class="form-control"  >
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label class="control-label">الاسم</label>
                                <div>
                                    <input type="text"  placeholder="الاسم"
                                           name="name"
                                           id="txt_name" class="form-control"  >
                                </div>
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

 $('#company_div').hide();
 $('#person_div').hide();

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