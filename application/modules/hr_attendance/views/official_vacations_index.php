<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 03/11/18
 * Time: 12:29 م
 */

$MODULE_NAME='hr_attendance';
$TB_NAME="official_vacations";
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$status_url=base_url("$MODULE_NAME/$TB_NAME/status");
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
?>
<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <?php if( HaveAccess($create_url)):  ?>
                <li><a  href="<?= $create_url ?>"><i class=" glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" name="ser" id="txt_ser" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الاجازة</label>
                    <div>
                        <input type="text"  <?=$date_attr?>   name="v_date" id="txt_v_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">بيان الاجازة</label>
                    <div>
                        <input type="text"  name="v_note" id="txt_v_note" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">مدخل الاجازة</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($entry_user_all as $row) :?>
                                <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
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
    <?=modules::run($get_page_url, $page,$ser, $v_date, $v_note,$entry_user ); ?>
</div>
<?php
$scripts = <<<SCRIPT
<script>

$('.sel2').select2();

 function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }


function values_search(add_page){
        var values=
        {page:1, ser:$('#txt_ser').val(), v_date:$('#txt_v_date').val(), v_note:$('#txt_v_note').val(), entry_user:$('#dp_entry_user').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }


 function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }



 function status_(val){
        var url = '{$status_url}';
        if(confirm( 'هل انت متأكد؟')){
        get_data(url,{ser:val},function(data){
             if(data==1 ){
                 success_msg('تم الحذف بنجاح..!');
                 $('#tr_'+val).hide(500);
                }else{
                 danger_msg('لم يتم الحذف..!!');
                }
            });
        }
    }


</script>
SCRIPT;
sec_scripts($scripts);
?>
