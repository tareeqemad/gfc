<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 10/09/14
 * Time: 08:53 ص
 */

//$year= date('Y'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title)?$title:''; ?></title>
    <link href="<?= base_url() ?>assets/css/login.css" rel="stylesheet"/>
	<link href="<?= base_url() ?>assets/css/font-awesome2.min.css" rel="stylesheet">

</head>
<body>

<div id="container">
    <?php
    if(isset($msg)){
        echo "<div class='msg'>$msg</div>";
     }

    if($browser=='true'){
    ?>
	
    <form id="loginForm" class="myForm" method="post" action="<?=base_url("login/check_user")?>">
        <div id="login" class="loginFields">
            <h1>النظام الموحد</h1>
            <p>
                <input class="username" placeholder="اسم المستخدم" type="text" data-val="true"  data-val-required="حقل مطلوب" name="user_id" id="txt_user_id" maxlength="15" >
                <span data-valmsg-for="user_id" data-valmsg-replace="true" style="color: red"></span>
            </p>
            <p>
                <input class="password" placeholder="كلمة المرور" type="password" data-val="true"  data-val-required="حقل مطلوب" name="user_pwd" id="txt_user_pwd" maxlength="32" >
                <span data-valmsg-for="user_pwd" data-valmsg-replace="true" style="color: red"></span>
            </p>

            <p>
                <select name="db_year" id="db_year" onchange="change_year()" >					
					<option value="0">السنة الحالية 2025</option>
                   
                </select>
                <br />
                <label style="display: none">
                    <input type="checkbox" name="keepMeIn" class="floatRight" disabled />
                    تذكر بياناتي
                </label>
            </p>
            <p style="width:233px;">
			
			     <button type="submit" class="btn round3 blueHover floatLeft">
                    <span><i class="fa fa-sign-in" style="font-size:19px;color:#fff"></i> تسجيل دخول</span>
                </button>			
			
			</p>
			<p>
                 <a style="display: none" href="https://gs.gedco.ps/archive" > أرشيف النظام الموحد </a>
            </p>
        </div>


        <div class="loginFields" id="reset" style="display: none">
            <h1>النظام المالي الموحد</h1>
            <p>
                <input class="email" placeholder="البريد الالكتروني: name@gedco.ps" type="text" data-val="true"  data-val-required="حقل مطلوب" name="email" id="txt_email" maxlength="50" >
                <span data-valmsg-for="email" data-valmsg-replace="true" style="color: red"></span>
            </p>
            <p>

            </p>
            <p style="width:233px;"><button type="submit" class="btn round3 blueHover floatLeft">
                    <span>ارسال كلمة المرور</span>
                </button></p>
        </div>

        <?=AntiForgeryToken();?>
        <input type="hidden" name="action" value="do_login" />
    </form>

    <div class="reset">
        <a href="javascript:">استرجاع كلمة المرور</a>
        <label>هل نسيت كلمة المرور؟</label>
    </div>
	
        <div class="tutorial">

            <div class="btn round3 blueHover floatRight show" onclick="javascript:show();">  عـــرض </div>

            <div class="styled-select yellow semi-square floatLeft">
                <select id="vid_name">
                    <option value="0">-----  شروحات توضيحية - فيديو  -----</option>
                    <optgroup label="تسجيل الدخول">
                        <option value="01_login">شرح تسجيل الدخول وتغيير كلمة المرور</option>
                    </optgroup>

                    <optgroup label="برنامج المساهمات">
                        <option value="02_contrib_branches">المساهمات - مقرات</option>
                        <option value="03_contrib_gaza">المساهمات - غزة</option>
                        <option value="04_contrib_tech">المساهمات - الفنية</option>
                    </optgroup>
					
					<optgroup label="القبض المركزي">
                        <option value="05_central_receipts_teller">القبض المركزي - الجزء الخاص بالمحصّل</option>
                    </optgroup>

                </select>
            </div>

        </div>
		
    <div class="reset" id="back" style="display: none">
        <a href="javascript:">تسجيل الدخول</a>
    </div>

    <?php
    } else{
        $brw_msg= 'هذا المتصفح غير مدعوم';
        if($browser=='version'){
            $brw_msg= 'اصدار المتصفح  قديم';
        }
        $brw_msg.='<br/> يرجى استخدام احد المتصفحات التالية';
        $brw_url= base_url('tools');

        echo
        "<form id='loginForm' >
        <div class='loginFields' style='font-size: 18px; font-family: arial-black; direction: rtl; line-height: 22px; text-align: center'>
            <div style='color: #ff0000; text-align: center'>$brw_msg</div>
            <table style='margin: 5px -30px 1px -30px;' cellspacing='20'>
            <tr>
                <td>فايرفوكس</td>
                <td><a target='_blank' href='https://www.mozilla.org/ar/firefox/new'>احدث اصدار</a></td>
                <td><a href='{$brw_url}/firefox_33.exe'>النسخة 33</a></td>
            </tr>
            <tr>
                <td>كروم</td>
                <td><a target='_blank' href='http://www.google.com/chrome'>احدث اصدار</a></td>
                <td><a href='{$brw_url}/chrome_38.exe'>النسخة 38</a></td>
            </tr>
            </table>
        </div>
        </form>";
    }
    ?>

</div>

<script src="<?= base_url()?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?= base_url()?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url()?>assets/js/jqueryval.min.js"></script>

<?= put_headers('js') ?>

</body>
</html>

<script type="text/javascript">
    $(document).ready(function() {

        change_year();

        var reset= <?=$reset?>;
        if(reset==1)
            change_form(1);

        $('.reset a').click(function(){
            change_form(1);
        });

        $('#back a').click(function(){
            change_form(0);
        });
    });

    function change_form(n){
        if(n==1){
            $('#login').hide(); $('#reset').show();
            $('.reset').hide(); $('#back').show();
            $('#loginForm').attr('action','<?=base_url("login/reset")?>');
        }else{
            $('#login').show(); $('#reset').hide();
            $('.reset').show(); $('#back').hide();
            $('#loginForm').attr('action','<?=base_url("login/check_user")?>');
        }
    }

    function change_year(){
        $.post( "<?=base_url("login/index")?>", {db_year:$('#db_year').val()} );
    }
	
	function show() {
        var vid_id = $('#vid_name').val();
        if(vid_id !=0) {
            window.open('<?=base_url()?>tutorial/tutorial_show.php?vm='+vid_id,'_blank');
        }
    }

</script>
