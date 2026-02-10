<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/02/19
 * Time: 11:43 ص
 */
$MODULE_NAME= 'issues';
$TB_NAME= 'gedco_issues';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$gedco_branch_issuess= modules::run("issues/issues/public_get_court_b",$this->user->branch);
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


                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الدعوى</label>
                    <div>
                        <input type="text" name="issue_no"  id="txt_issue_no" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">سنة الدعوى</label>
                    <div>
                        <input type="text" name="issue_year"  id="txt_issue_year" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المحكمة</label>
                    <div>
                        <select name="court_name" id="dp_court_name" class="form-control">
                            <option value="">-</option>
                            <?php foreach($gedco_branch_issuess as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المدعي</label>
                    <div>
                        <input type="text" name="name"  id="txt_name" class="form-control ">
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
                    <label class="control-label">حالة القضية</label>
                    <div>
                        <select name="endedstatus" id="dp_endedstatus" class="form-control">
                            <option value="">-</option>
                            <?php foreach($endedstatus as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
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



    $('#dp_adopt,#dp_court_name,#dp_endedstatus').select2().on('change',function(){
        });


function clear_form(){
        clearForm($('#{$TB_NAME}_form'));

         $('#dp_adopt').select2('val','');
          $('#dp_court_name').select2('val','');

    }

     function search(){
 /*if($('#txt_issue_no').val() == '' && $('#txt_issue_year').val() == '' && $('#dp_court_name').val() == ''  && $('#txt_name').val() == '' && $('#dp_adopt').val() =='' )
       {
 danger_msg('يتوجب عليك اختيار محدد بحث واحد على الأقل','');

       }
        else
       {*/
       var values=  {page:1, issue_no:$('#txt_issue_no').val(), issue_year:$('#txt_issue_year').val(), court_name:$('#dp_court_name').val(), name:$('#txt_name').val(), adopt:$('#dp_adopt').val(),endedstatus:$('#dp_endedstatus').val()};

        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
   /* }*/
    }

    function LoadingData(){
     var values=  {page:1, issue_no:$('#txt_issue_no').val(), issue_year:$('#txt_issue_year').val(), court_name:$('#dp_court_name').val(), name:$('#txt_name').val(), adopt:$('#dp_adopt').val(),endedstatus:$('#dp_endedstatus').val()};

          ajax_pager_data('#page_tb > tbody',values);
    }




</script>

SCRIPT;

sec_scripts($scripts);

?>