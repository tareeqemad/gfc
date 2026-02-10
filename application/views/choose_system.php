<?php
if (isset($this->user->conflict_interest) && $this->user->conflict_interest == 1) {
    $conflict_interest_agree = 1;
} else {
    $conflict_interest_agree = 0;
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : ''; ?></title>

    <link href="<?= base_url() ?>assets/css/bootstrap5.min.css" rel="stylesheet">
    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery-1.11.1.min.js"></script>
    <link href="<?= base_url() ?>assets/css/font-awesome2.min.css" rel="stylesheet">

    <style>
        body{
            background:url("<?= base_url() ?>assets/img/choosesysbg.jpg") no-repeat center center fixed;
            background-size:cover;
            font-family: MyFont;
        }

        @font-face{
            font-family: MyFont;
            src:url("<?= base_url() ?>assets/fonts/STC-Bold.ttf");
        }

        .container-main{
            background:rgba(241,241,241,.45);
            border-radius:18px;
            padding:25px;
            margin-top:160px;
        }

        .logoimg{margin-top:40px}

        .att-bar,
        .systems-box{
            background:rgba(255,255,255,.7);
            border:1px solid rgba(255,255,255,.8);
            border-radius:14px;
            padding:14px;
            margin-bottom:16px;
        }

        .att-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:10px;
            font-size:18px;
            color:#2d4b57;
        }

        #server_clock{
            font-size:16px;
            color:#2d4b57;
        }

        .btn{
            width:100%;
            margin-bottom:10px;
            padding:12px;
            font-size:18px;
            border-radius:12px;
            background-color:#62889c !important;
            color:#fff;
            border-color:#fff;
        }

        #btn_in{background:#2f7d32 !important}
        #btn_out{background:#b23b3b !important}

        .systems-title{
            font-size:18px;
            color:#2d4b57;
            margin-bottom:10px;
        }
    </style>

    <script>
        if (!<?= $conflict_interest_agree ?>) {
            location.href = '<?= base_url('/welcome/setSystem/3')?>';
        }
    </script>
</head>

<body>

<div class="container container-main">

    <div class="row mb-3">
        <div class="col-md-3 text-center">
            <img src="<?= base_url() ?>assets/img/gedcologo.png" width="220" class="logoimg">
        </div>
        <div class="col-md-9">
            <!-- الحضور والانصراف -->
            <div class="att-bar">
                <div class="att-header">
                    <div><i class="fa fa-clock-o"></i> الحضور والانصراف</div>
                    <div id="server_clock">...</div>
                </div>

                <div id="att_msg" class="alert" style="display:none"></div>

                <div class="row">
                    <div class="col-md-3">
                        <button id="btn_in" class="btn">
                            <i class="fa fa-check"></i> تسجيل حضور
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button id="btn_out" class="btn">
                            <i class="fa fa-sign-out"></i> تسجيل انصراف
                        </button>
                    </div>
                </div>

                <div id="last_att_box" style="margin-top:10px;font-size:17px;color:#2d4b57;">
                    <div><b>آخر حضور (24 ساعة):</b> <span id="last_in_txt">-</span></div>
                    <div><b>آخر انصراف (24 ساعة):</b> <span id="last_out_txt">-</span></div>
                </div>
            </div>
            <!-- الأنظمة -->
            <div class="systems-box">
                <div class="systems-title">
                    <i class="fa fa-th-large"></i> الأنظمة
                </div>

                <div class="row">

                    <?php
                    $systems = [
                            [1,'fa-money','النظام المالي'],
                            [2,'fa-cogs','النظام الفني'],
                            [3,'fa-users','النظام الإداري'],
                            [7,'fa-university','النظام القانوني'],
                            [8,'fa-bar-chart','نظام التخطيط'],
                            [10,'fa-list','نظام التدريب'],
                            [15,'fa-tasks','نظام المهام والمراسلات'],
                            [9,'fa-cogs','النظام الفني الجديد'],
                            [11,'fa-desktop','الخدمات الإلكترونية'],
                            [16,'fa-file-text','نظام المتابعة والمعلومات'],
                    ];

                    foreach($systems as $s): ?>
                        <div class="col-md-3 col-sm-6">
                            <button class="btn" onclick="location.href='<?= base_url('/welcome/setSystem/'.$s[0]) ?>'">
                                <i class="fa <?= $s[1] ?>"></i> <?= $s[2] ?>
                            </button>
                        </div>
                    <?php endforeach; ?>

                    <div class="col-md-3 col-sm-6">
                        <button class="btn" onclick="location.href='../../Trading/Main'">
                            <i class="fa fa-clipboard"></i> النظام التجاري
                        </button>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <button class="btn" onclick="location.href='../../RecordAssets/Cpanel'">
                            <i class="fa fa-file-text"></i> نظام سجل الأصول
                        </button>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <button class="btn" onclick="window.open('<?= base_url('/Biunit/Biunit/index')?>','_blank')">
                            <i class="fa fa-desktop"></i> ذكاء الأعمال ودعم القرار
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div style="color:#bbb">
        <?= get_curr_user()->fullname ?> |
        <a style="color:#bbb" href="<?= base_url('/login/logout') ?>">تسجيل الخروج</a>
    </div>

</div>

<script>
    $(document).ready(function(){

        loadLastInOut();

        function showMsg(type, text){
            var el = $('#att_msg');
            el.removeClass('alert-success alert-danger');
            el.addClass('alert ' + (type === 'ok' ? 'alert-success' : 'alert-danger'));
            el.html(text).show();
        }

        function setButtonsByStatus(lastStatus){
            lastStatus = parseInt(lastStatus, 10) || 0;

            if(lastStatus === 1){
                $('#btn_in').prop('disabled', true);
                $('#btn_out').prop('disabled', false);
            }else{
                $('#btn_in').prop('disabled', false);
                $('#btn_out').prop('disabled', true);
            }
        }

        function lockButtons(){
            $('#btn_in').prop('disabled', true);
            $('#btn_out').prop('disabled', true);
        }

        // ✅ جلب آخر حالة (POST + JSON)
        function loadLastStatus(){
            $.ajax({
                url: "<?= base_url('hr_attendance/Finger_attendance/getLastStatus') ?>",
                type: "POST",
                dataType: "json",
                success: function(resp){
                    if(resp && resp.ok){
                        setButtonsByStatus(resp.status);
                    }else{
                        setButtonsByStatus(0);
                    }
                },
                error: function(xhr){
                    // إذا فشل الجلب، خلي الافتراضي حضور مفتوح
                    setButtonsByStatus(0);
                }
            });
        }

        // ✅ تنفيذ حضور/انصراف (POST + نص)
        function callAttendance(status){
            lockButtons();
            $('#att_msg').hide();

            $.ajax({
                url: "<?= base_url('hr_attendance/Finger_attendance/autoFinger') ?>",
                type: "POST",
                data: { status: status },

                success: function(res){
                    res = $.trim(res);

                    if(res === '1'){
                        showMsg('ok', status == 1 ? 'تم تسجيل الحضور بنجاح' : 'تم تسجيل الانصراف بنجاح');
                        setButtonsByStatus(status); // ثبّت الحالة فورًا
                    }else{
                        showMsg('err', res);
                        loadLastStatus();
                    }
                },

                error: function(xhr){
                    // ✅ اعرض رسالة السيرفر حتى لو 500
                    var msg = 'حدث خطأ في الاتصال بالخادم';
                    if(xhr && xhr.responseText){
                        msg = $.trim(xhr.responseText);
                        if(msg === '') msg = 'حدث خطأ غير معروف';
                    }
                    showMsg('err', msg);
                    loadLastStatus();
                }
            });
        }

        // ✅ ساعة السيرفر (بدون getJSON) - الأفضل تخليها GET أو POST حسب كنترولرك
        function loadServerTime(){
            $.ajax({
                url: "<?= base_url('hr_attendance/Finger_attendance/serverTime') ?>",
                type: "GET", // إذا endpoint عندك POST غيّرها لـ POST
                dataType: "json",
                success: function(resp){
                    if(resp && resp.ok){
                        $('#server_clock').text(resp.time);
                    }
                }
            });
        }

        // تشغيل أولي
        loadLastStatus();
        loadServerTime();
        setInterval(loadServerTime, 60000);

        // أحداث الأزرار
        $('#btn_in').on('click', function(){ callAttendance(1); });
        $('#btn_out').on('click', function(){ callAttendance(4); });

    });

    function loadLastInOut(){
        $.ajax({
            url: "<?= base_url('hr_attendance/Finger_attendance/getLastInOut') ?>",
            type: "POST",
            dataType: "json",
            success: function(resp){
                if(resp && resp.ok){
                    $('#last_in_txt').text(resp.last_in ? resp.last_in : '-');
                    $('#last_out_txt').text(resp.last_out ? resp.last_out : '-');
                }
            }
        });
    }
</script>


</body>
</html>
