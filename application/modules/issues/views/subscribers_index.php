<?php

$MODULE_NAME= 'issues';
$TB_NAME= 'subscribers';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_sub");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title; ?></div>
        <ul>

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
                            <input type="text" name="sub_no" id="txt_sub_no" class="form-control" dir="rtl" placeholder="رقم الإشتراك">

                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم المشترك</label>
                        <div>
                            <input type="text" name="sub_name"  id="txt_sub_name" class="form-control " placeholder="اسم المشترك">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الهوية</label>
                        <div>
                            <input type="text" name="id"  id="txt_id" class="form-control " placeholder="رقم الهوية">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم المدعي</label>
                        <div>
                            <input type="text" name="d_name"  id="txt_d_name" class="form-control " placeholder="اسم المدعي">
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
                <?php echo ''; //modules::run($get_page_url,$page,$sub_no,$sub_name,$id,$for_month);?>
            </div>
        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

        function values_search(add_page){
 if($('#txt_sub_no').val() == '' && $('#txt_sub_name').val() == '' && $('#txt_id').val() == ''  && $('#txt_d_name').val() == '' )
       {
 danger_msg('يتوجب عليك اختيار محدد بحث واحد على الأقل','');

       }
        else
       {
                var values=

                {page:1, sub_no:$('#txt_sub_no').val(), sub_name:$('#txt_sub_name').val(), id:$('#txt_id').val(),d_name:$('#txt_d_name').val()
            };
                if(add_page==0)
                delete values.page;
                return values;
                }
}
 function search(){
  if($('#txt_sub_no').val() == '' && $('#txt_sub_name').val() == '' && $('#txt_id').val() == ''  && $('#txt_d_name').val() == '' )
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