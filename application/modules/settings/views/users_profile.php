<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 29/10/14
 * Time: 10:16 ص
 */

$year_url= base_url('settings/users/change_year');
echo AntiForgeryToken();

$today_time= date_default_timezone_get(). ' | ' .date("Y/m/d H:i");
$public_emp_data_url =  base_url('settings/users/public_emp_data');
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a onclick="javascript:change_year();" href="javascript:;"><i class="glyphicon glyphicon-refresh"></i> <?=$year?> </a> </li>
            <?php if($can_change_pass){?>
            <li><a onclick="javascript:show_form();" href="javascript:;"><i class="glyphicon glyphicon-lock"></i>تغيير كلمة المرور</a> </li>
            <?php } ?>
            <li><a href="<?=$public_emp_data_url?>"><i class="glyphicon glyphicon-edit"></i>تحديث بياناتي</a> </li>
        </ul>

</div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <table class="table">
                <?php
                echo "
                <tbody>
                <tr>
                    <td>اسم المستخدم</td>
                    <td>{$data[0]['USER_ID']}</td>
                </tr>
                <tr>
                    <td>اسم الموظف</td>
                    <td>{$data[0]['USER_NAME']}</td>
                </tr>
                <tr>
                    <td>الرقم الوظيفي</td>
                    <td>{$data[0]['EMP_NO']}</td>
                </tr>
                <tr>
                    <td>الفرع</td>
                    <td>{$data[0]['BRANCH_NAME']}</td>
                </tr>
                 <tr>
                    <td>القسم</td>
                    <td>{$data[0]['POSITION_NAME']}</td>
                </tr>
                <tr>
                    <td>البريد الالكتروني</td>
                    <td>{$data[0]['EMAIL']}</td>
                </tr>
				<tr>
                    <td>سيرفر</td>
                    <td>{$_SERVER['SERVER_ADDR']}</td>
                </tr>
				<tr>
                    <td>التوقيت</td>
                    <td>{$today_time}</td>
                </tr>

                </tbody>
                ";
                ?>
            </table>

            <form class="form-horizontal" id="change_pass" style="display: none" method="post" action="<?=base_url('settings/users/change_pass')?>" role="form" novalidate="novalidate">
                <div class="form-group col-sm-12">
                    <label class="col-sm-4 control-label">كلمة المرور الحالية</label>
                    <div class="col-sm-4">
                        <input type="password" data-val="true"  data-val-required="حقل مطلوب" name="curr_pwd" id="txt_curr_pwd" class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="curr_pwd" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-12">
                    <label class="col-sm-4 control-label"> كلمة المرور الجديدة</label>
                    <div class="col-sm-4">
                        <input type="password" data-val="true" data-val-regex-pattern="^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^_+-]).*$" data-val-regex=" كلمة المرور يجب أن تكون 8 خانات على الاقل ويجب ان تحتوي على حروف كبيرة وحروف صغيرة ورموز وارقام (خانة واحدة من كل نوع على الاقل) مثال: Aa#12345 "  data-val-required="حقل مطلوب" name="user_pwd" id="txt_user_pwd" class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="user_pwd" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-12">
                    <label class="col-sm-4 control-label"> تأكيد كلمة المرور</label>
                    <div class="col-sm-4">
                        <input type="password" data-val="true" data-val-equalto-other="user_pwd" data-val-equalto="كلمة المرور غير متطابقة"  data-val-required="حقل مطلوب" name="user_cpwd" id="txt_user_cpwd" class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="user_cpwd" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-12">
                    <label class="col-sm-4 control-label"></label>
                    <div class="col-sm-4">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ كلمة المرور</button>
                    </div>
                </div>
            </form>

            <form class="form-horizontal" id="change_year" style="display: none" method="post" action="<?=base_url('settings/users/change_year')?>" role="form" novalidate="novalidate">

                <div class="form-group col-sm-12" title="تستخدم هذه الخاصية للشاشات الخاصة بالموازنة">
                    <label class="col-sm-4 control-label">اختر السنة الموازنة</label>
                    <div class="col-sm-2">
                        <select id="dp_years" class="form-control" >
                            <?php for($i=2014;$i<=$year+1;$i++){ ?>
                                <option <?=($i==$year?'selected':'')?> value="<?=$i?>" ><?=$i?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    function show_form(){
         $("#change_pass").slideToggle('1500');
    }

    function change_year(){
         $("#change_year").slideToggle('1500');
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(data==1){
                success_msg('رسالة','تم تغيير كلمة المرور بنجاح ..');
                $("#change_pass").hide();
                clearForm($('#change_pass'));
            }else
                danger_msg('خطأ',data);
        },"html");
    });

    $('#dp_years').change(function(){
        get_data('{$year_url}', {year:$(this).val()}, function(ret){
            if(ret==1){
                success_msg('رسالة','تم التعديل بنجاح ..');
                get_to_link(window.location.href);
            }else{
                danger_msg('تحذير..',ret);
            }
        }, 'html');
    });

</script>

SCRIPT;

sec_scripts($scripts);

?>
