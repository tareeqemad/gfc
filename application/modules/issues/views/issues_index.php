<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/02/19
 * Time: 11:43 ص
 */
$MODULE_NAME= 'issues';
$TB_NAME= 'issues';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title; ?></div>
        <ul>

            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الاشتراك</label>
                    <div>
                        <input type="text" name="sub_no" id="txt_sub_no" class="form-control" dir="rtl">

                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المشترك</label>
                    <div>
                        <input type="text" name="sub_name"  id="txt_sub_name" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم هوية المدعى عليه</label>
                    <div>
                        <input type="text" name="id"  id="txt_id" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم القضية</label>
                    <div>
                        <input type="text" name="issue_no"  id="txt_issue_no" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">سنة القضية</label>
                    <div>
                        <input type="text" name="issue_year"  id="txt_issue_year" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع القضية</label>
                    <div>
                        <select name="issue_type" id="dp_issue_type" class="form-control">
                            <option value="">-</option>
                            <?php foreach($issue_type as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div id="hide_exe_issue">
                <div  class="form-group col-sm-2">
                    <label class="control-label">رقم القضية</label>
                    <div>
                        <input type="text"  name="exe_issue_no"  id="txt_exe_issue_no" class="form-control ">
                    </div>
                </div>

                <div  class="form-group col-sm-2">
                    <label class="control-label">سنة القضية</label>
                    <div>
                        <input type="text" name="exe_issue_year"  id="txt_exe_issue_year" class="form-control ">
                    </div>
                </div>

                </div>
                <br>
                <div class="form-group col-sm-2">
                    <label class="control-label">حالة القضية</label>
                    <div>
                        <select name="status" id="dp_status" class="form-control">
                            <option value="">-</option>
                            <?php foreach($status as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الاعتماد</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control">
                            <option value="">-</option>
                            <?php foreach($adopt as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">

                    <?php
                    if($this->user->branch==1)
                    {
                    ?>
                    <label class="control-label">الفرع</label>

                    <div>

                        <select name="branches" id="dp_issue_branch" class="form-control">

                            <option value="">-</option>
                            <?php foreach($branches as $row) :?>
                                <?php
                                if($row['NO']<>1)
                                {
                                    ?>

                                    <option value="<?= $row['NO'] ?>"  >
                                        <?= $row['NAME'] ?>
                                    </option>
                                <?php
                                }
                                ?>

                            <?php endforeach; ?>
                        </select>
                        <?php
                        }
                        else
                        {
                        ?>
                        <div>
                            <input type="hidden" value="<?php echo $this->user->branch;?>" name="branches" id="dp_issue_branch">

                            <?php

                            }
                            ?>
                        </div>


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
           <?php echo ''; /*modules::run($get_page_url,$page,$sub_no,$sub_name,$id,$issue_no,$issue_year,$issue_type,$exe_issue_no,$exe_issue_no,$status,$adopt,$branches); */ ?>
        </div>
    </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


         $('#dp_status').select2().on('change',function(){
        });

         $('#dp_adopt').select2().on('change',function(){
        });
if ('{$this->user->branch}' ==1)
{
         $('#dp_issue_branch').select2().on('change',function(){
        });
        }
$('#hide_exe_issue').hide();
         $('#dp_issue_type').select2().on('change',function(){
			 if($(this).val()=='2' || $(this).val()=='4' ){
				 $('#hide_exe_issue').show();
			 }
			 else{
				 $('#hide_exe_issue').hide();
			 }
        });


function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

        function values_search(add_page){

                var values=

                {page:1, sub_no:$('#txt_sub_no').val(), sub_name:$('#txt_sub_name').val(), id:$('#txt_id').val(), issue_no:$('#txt_issue_no').val(), issue_year:$('#txt_issue_year').val(),issue_type:$('#dp_issue_type').val(),status:$('#dp_status').val(),
                 adopt:$('#dp_adopt').val(),branches:$('#dp_issue_branch').val(),exe_issue_no:$('#txt_exe_issue_no').val(),exe_issue_year:$('#txt_exe_issue_year').val()};
                if(add_page==0)
                delete values.page;
                return values;
                }

 function search(){
   if($('#txt_sub_no').val() == '' && $('#txt_sub_name').val() == '' && $('#txt_id').val() == ''  && $('#txt_issue_no').val() == '' && $('#txt_issue_year').val() == '' && $('#dp_issue_type').val() == '' && $('#dp_status').val() == '' && $('#dp_adopt').val() == '' )
       {
 danger_msg('يتوجب عليك اختيار محدد بحث واحد على الأقل','');

       }
       else
       {
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }
    }
</script>

SCRIPT;

sec_scripts($scripts);

?>