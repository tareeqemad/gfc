<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 04/09/2022
 * Time: 09:03 AM
 */
$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$get_url =base_url('treasury/workfield/public_get_user_data');
$post_url = base_url("$MODULE_NAME/$TB_NAME/insertUser");
$back_url = base_url("$MODULE_NAME/$TB_NAME/users");

?>

<div>
    <div class="padding-20">
        <form id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form" novalidate="novalidate">
            <div class="form-group row mt-20">
                <?php if($this->user->branch == 1 ){ ?>
                    <label class="control-label col-md-4">رقم الشركة</label>
                <?php } else { ?>
                    <label class="control-label col-md-4">رقم المحصل</label>
                <?php } ?>
                <div class="col-md-5">
                    <input type="text"
                           name="user_no"
                           id="user_no"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>
                </div>
            </div>

            <div class="form-group row">
                <?php if($this->user->branch == 1 ){ ?>
                    <label class="control-label col-md-4">اسم الشركة</label>
                <?php } else { ?>
                    <label class="control-label col-md-4">اسم المحصل</label>
                <?php } ?>

                <div class="col-md-5">
                    <input type="text"
                           name="user_name"
                           id="user_name"
                           <?=$this->user->branch == 1 ? "" : "readonly"?>
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control "/>

                    <input type="hidden"
                           name="user_type"
                           id="user_type"
                           value="<?=$this->user->branch ?>"
                           class="form-control "/>

                </div>
            </div>

            <?php if($this->user->branch == 1 ){ ?>
                <input type="hidden" id="user_branch_no" value="1" name="user_branch_no" />
            <?php } else { ?>
                <div class="form-group row">
                    <label class="control-label col-md-4">المقر</label>
                    <div class="col-md-5">
                        <input type="hidden" id="user_branch_no" name="user_branch_no" />
                        <input type="text"
                               name="user_branch"
                               id="user_branch"
                               readonly
                               data-val="true"
                               data-val-required="حقل مطلوب"
                               class="form-control "/>
                    </div>
                </div>
            <?php } ?>



            <div class="form-group row">
                <label class="control-label col-md-4">رقم الموبايل</label>
                <div class="col-md-5">
                    <input type="text"
                           name="user_mobile"
                           id="user_mobile"
                           data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control"/>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="btn_save"  class="btn btn-primary">
                    حفظ
                </button>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $('#btn_save').hide();
    
    
    
    $('#user_no').on('change',function () {
        if($('#user_branch_no').val() == 1 ){
            $('#btn_save').show();
        } else {
            ajax_fun('{$get_url}', {'user_no': $('#user_no').val() }, function (data) {
                if(data.length > 0 ){
                    $('#user_name').val(data[0]['NAME']);
                    $('#user_branch_no').val(data[0]['BRAN']);
                    $('#user_branch').val(data[0]['BRANCH_NAME']);
                    $('#btn_save').show();
                    
                } else {
                    $('#user_name').val("");
                    $('#user_branch_no').val("");
                    $('#user_branch').val("");
                    $('#btn_save').hide();
                    danger_msg('تحذير..','يرجى التاكد من رقم الموظف');
                }
                
            });
        }
    });
    
    $('#btn_save').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ المستخدم ؟!';
        if(confirm(msg)){
            let mob = $('#user_mobile').val();
            str = mob.substring(0, 3);
            var check_num_mob = isNaN($('#user_mobile').val());
            if($('#user_mobile').val().length == 10 && (str == '059' || str =='056') && check_num_mob == false ){
                $(this).attr('disabled','disabled');
                var form = $(this).closest('form');
                ajax_insert_update(form,function(data){
                    if(parseInt(data)>=1){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        get_to_link('{$back_url}');
                    }else{
                        danger_msg('تحذير..',data);
                    }
                },'html');
            } else {
                danger_msg('تحذير..','يرجى التاكد من رقم المحمول');
            }
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

		
</script>
SCRIPT;
sec_scripts($scripts);
?>

