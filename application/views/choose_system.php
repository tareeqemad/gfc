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
    <link rel="icon" href="<?= base_url() ?>assets/img/gedcologo.png" type="image/png">

    <link href="<?= base_url() ?>assets/css/bootstrap5.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/font-awesome2.min.css" rel="stylesheet">

    <style>
        @font-face{
            font-family: MyFont;
            src: url("<?= base_url() ?>assets/fonts/STC-Bold.ttf");
        }

        body{
            background:
                    linear-gradient(rgba(15, 23, 42, 0.68), rgba(15, 23, 42, 0.78)),
                    url("<?= base_url() ?>assets/img/choosesysbg.jpg") no-repeat center center fixed;
            background-size: cover;
            font-family: MyFont, sans-serif;
            min-height: 100vh;
            color: #1e293b;
        }

        .main-shell{
            padding-top: 45px;
            padding-bottom: 30px;
        }

        .dashboard-panel{
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.16);
            border-radius: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,.22);
            overflow: hidden;
        }

        .topbar{
            padding: 24px 28px 14px;
            color: #fff;
        }

        .brand-wrap{
            display:flex;
            align-items:center;
            gap:18px;
            flex-wrap:wrap;
        }

        .logo-box{
            width: 110px;
            height: 110px;
            border-radius: 20px;
            background: rgba(255,255,255,.12);
            display:flex;
            align-items:center;
            justify-content:center;
            border:1px solid rgba(255,255,255,.15);
        }

        .logo-box img{
            max-width: 88px;
            max-height: 88px;
        }

        .brand-name{
            font-size: 17px;
            font-weight: 600;
            color: rgba(255,255,255,.85);
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .welcome-title{
            font-size: 30px;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .welcome-subtitle{
            font-size: 18px;
            color: rgba(255,255,255,.9);
            margin-bottom: 0;
        }

        .content-wrap{
            background: #f8fafc;
            padding: 24px;
            border-radius: 28px 28px 0 0;
        }

        .section-card{
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
            border: 1px solid #e2e8f0;
            padding: 20px;
            margin-bottom: 20px;
            height: 100%;
        }

        .section-title{
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
            display:flex;
            align-items:center;
            gap:10px;
        }

        .section-title i{
            color: #2563eb;
        }

        .att-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            flex-wrap:wrap;
            gap:10px;
            margin-bottom:14px;
        }

        .clock-box{
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            min-width: 130px;
            text-align: center;
        }

        .att-btn{
            width: 100%;
            border: 0;
            border-radius: 16px;
            padding: 14px 16px;
            color: #fff;
            font-size: 19px;
            font-weight: 700;
            transition: .2s ease;
            box-shadow: 0 8px 18px rgba(0,0,0,.08);
            position: relative;
        }

        .att-btn:hover{
            transform: translateY(-1px);
            opacity: .96;
        }

        .att-btn:disabled{
            opacity: .55;
            cursor: not-allowed;
            transform: none;
        }

        .att-btn .spinner{
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            vertical-align: middle;
            margin-inline-start: 8px;
        }

        .att-btn.loading .spinner{
            display: inline-block;
        }

        .att-btn.loading .btn-label{
            opacity: .7;
        }

        @keyframes spin{
            to{ transform: rotate(360deg); }
        }

        #btn_in{
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        #btn_out{
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .last-att-box{
            margin-top: 16px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            padding: 14px 16px;
        }

        .last-att-item{
            font-size: 16px;
            color: #334155;
            margin-bottom: 6px;
        }

        .last-att-item:last-child{
            margin-bottom: 0;
        }

        .sys-card{
            display:flex;
            align-items:center;
            gap:10px;
            text-decoration:none;
            background:#fff;
            border:1px solid #e2e8f0;
            border-radius:12px;
            padding:10px 12px;
            height:100%;
            transition:.2s ease;
            box-shadow: 0 4px 12px rgba(15,23,42,.04);
            color:#0f172a;
        }

        .sys-card:hover{
            transform: translateY(-2px);
            border-color:#93c5fd;
            box-shadow: 0 8px 20px rgba(37,99,235,.10);
            color:#0f172a;
        }

        .sys-icon{
            width:34px;
            height:34px;
            min-width:34px;
            border-radius:9px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color:#1d4ed8;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:15px;
        }

        .sys-title{
            font-size:16px;
            font-weight:700;
            line-height:1.4;
        }

        .footer-bar{
            margin-top: 14px;
            color: #1e293b;
            font-size: 16px;
            font-weight: 600;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
            background: rgba(255,255,255,.75);
            backdrop-filter: blur(6px);
            padding: 10px 20px;
            border-radius: 12px;
        }

        .footer-bar a{
            color:#1e40af;
            text-decoration:none;
            font-weight:700;
        }

        .footer-bar a:hover{
            text-decoration:underline;
        }

        .alert{
            border-radius: 14px;
        }

        @media(max-width: 992px){
            .section-card{
                height: auto;
            }
        }

        @media(max-width: 768px){
            .welcome-title{
                font-size: 22px;
            }

            .content-wrap{
                padding: 16px;
            }

            .topbar{
                padding: 20px 18px 10px;
            }

            .logo-box{
                width: 88px;
                height: 88px;
            }

            .logo-box img{
                max-width: 68px;
                max-height: 68px;
            }
        }
    </style>

    <script>
        if (!<?= $conflict_interest_agree ?>) {
            location.href = '<?= base_url('/welcome/setSystem/3')?>';
        }
    </script>
</head>

<body>

<div class="container main-shell">
    <div class="dashboard-panel">

        <div class="topbar">
            <div class="brand-wrap">
                <div class="logo-box">
                    <img src="<?= base_url() ?>assets/img/gedcologo.png" alt="logo">
                </div>
                <div>
                    <div class="brand-name">شركة توزيع كهرباء غزة</div>
                    <div class="welcome-title">بوابة الأنظمة</div>
                    <p class="welcome-subtitle">الوصول السريع إلى الأنظمة الداخلية والحضور والانصراف</p>
                </div>
            </div>
        </div>

        <div class="content-wrap">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="section-card">
                        <div class="att-header">
                            <div class="section-title mb-0">
                                <i class="fa fa-clock-o"></i>
                                <span>الحضور والانصراف</span>
                            </div>
                            <div id="server_clock" class="clock-box">...</div>
                        </div>

                        <div id="att_msg" class="alert" style="display:none"></div>

                        <div class="row g-3">
                            <div class="col-12">
                                <button id="btn_in" class="att-btn">
                                    <span class="btn-label"><i class="fa fa-check"></i> تسجيل حضور</span>
                                    <span class="spinner"></span>
                                </button>
                            </div>
                            <div class="col-12">
                                <button id="btn_out" class="att-btn">
                                    <span class="btn-label"><i class="fa fa-sign-out"></i> تسجيل انصراف</span>
                                    <span class="spinner"></span>
                                </button>
                            </div>
                        </div>

                        <div class="last-att-box">
                            <div class="last-att-item">
                                <b>آخر حضور (24 ساعة):</b>
                                <span id="last_in_txt">-</span>
                            </div>
                            <div class="last-att-item">
                                <b>آخر انصراف (24 ساعة):</b>
                                <span id="last_out_txt">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="section-card">
                        <div class="section-title">
                            <i class="fa fa-th-large"></i>
                            <span>الأنظمة</span>
                        </div>

                        <div class="row g-3">

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
                                <div class="col-md-6 col-xl-4">
                                    <a class="sys-card" href="<?= base_url('/welcome/setSystem/'.$s[0]) ?>">
                                        <div class="sys-icon">
                                            <i class="fa <?= $s[1] ?>"></i>
                                        </div>
                                        <div class="sys-title"><?= $s[2] ?></div>
                                    </a>
                                </div>
                            <?php endforeach; ?>

                            <div class="col-md-6 col-xl-4">
                                <a class="sys-card" href="../../Trading/Main">
                                    <div class="sys-icon">
                                        <i class="fa fa-clipboard"></i>
                                    </div>
                                    <div class="sys-title">النظام التجاري</div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <a class="sys-card" href="../../Commercial/cpanel">
                                    <div class="sys-icon">
                                        <i class="fa fa-shopping-bag"></i>
                                    </div>
                                    <div class="sys-title">النظام التجاري الجديد</div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <a class="sys-card" href="../../RecordAssets/Cpanel">
                                    <div class="sys-icon">
                                        <i class="fa fa-file-text"></i>
                                    </div>
                                    <div class="sys-title">نظام سجل الأصول</div>
                                </a>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <a class="sys-card" href="<?= base_url('/Biunit/Biunit/index') ?>" target="_blank">
                                    <div class="sys-icon">
                                        <i class="fa fa-desktop"></i>
                                    </div>
                                    <div class="sys-title">ذكاء الأعمال ودعم القرار</div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bar">
                <div>
                    <i class="fa fa-user-circle"></i>
                    <?= get_curr_user()->fullname ?>
                </div>
                <div style="font-size:15px;color:#475569;">شركة توزيع كهرباء غزة &copy; <?= date('Y') ?></div>
                <div>
                    <a href="<?= base_url('/login/logout') ?>"><i class="fa fa-sign-out"></i> تسجيل الخروج</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){

        loadLastInOut();

        function showMsg(type, text){
            var el = $('#att_msg');
            el.removeClass('alert-success alert-danger alert-warning');
            el.addClass('alert ' + (type === 'ok' ? 'alert-success' : 'alert-danger'));
            el.html(text).show();
        }

        function setButtonsByStatus(lastStatus){
            lastStatus = parseInt(lastStatus, 10) || 0;

            if(lastStatus === 1){
                $('#btn_in').prop('disabled', true).removeClass('loading');
                $('#btn_out').prop('disabled', false).removeClass('loading');
            }else{
                $('#btn_in').prop('disabled', false).removeClass('loading');
                $('#btn_out').prop('disabled', true).removeClass('loading');
            }
        }

        function lockButtons(){
            $('#btn_in').prop('disabled', true);
            $('#btn_out').prop('disabled', true);
        }

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
                error: function(){
                    setButtonsByStatus(0);
                }
            });
        }

        function callAttendance(status){
            lockButtons();
            $('#att_msg').hide();

            var btnId = status == 1 ? '#btn_in' : '#btn_out';
            $(btnId).addClass('loading');

            $.ajax({
                url: "<?= base_url('hr_attendance/Finger_attendance/autoFinger') ?>",
                type: "POST",
                data: { status: status },
                success: function(res){
                    res = $.trim(res);

                    if(res === '1'){
                        showMsg('ok', status == 1 ? 'تم تسجيل الحضور بنجاح' : 'تم تسجيل الانصراف بنجاح');
                        setButtonsByStatus(status);
                        loadLastInOut();
                        loadLastStatus();
                    }else{
                        showMsg('err', res);
                        loadLastStatus();
                    }
                },
                error: function(xhr){
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

        var _serverTime = null;
        var _clockInterval = null;

        function loadServerTime(){
            $.ajax({
                url: "<?= base_url('hr_attendance/Finger_attendance/serverTime') ?>",
                type: "GET",
                dataType: "json",
                success: function(resp){
                    if(resp && resp.ok){
                        var match = resp.time.match(/(\d{4}-\d{2}-\d{2})\s+(\d{1,2}):(\d{2})/);
                        if(match){
                            var h = match[2].length < 2 ? '0' + match[2] : match[2];
                            _serverTime = new Date(match[1] + 'T' + h + ':' + match[3] + ':00');
                        } else {
                            _serverTime = new Date();
                        }
                        if(!_clockInterval){
                            _clockInterval = setInterval(tickClock, 1000);
                        }
                        tickClock();
                    }
                }
            });
        }

        function tickClock(){
            if(!_serverTime) return;
            _serverTime.setSeconds(_serverTime.getSeconds() + 1);
            var h = _serverTime.getHours();
            var m = _serverTime.getMinutes();
            var s = _serverTime.getSeconds();
            var ampm = h >= 12 ? 'م' : 'ص';
            var h12 = h % 12 || 12;
            var display = pad(h12) + ':' + pad(m) + ':' + pad(s) + ' ' + ampm;
            $('#server_clock').text(display);
        }

        function pad(n){ return n < 10 ? '0' + n : n; }

        $('#btn_in').on('click', function(){ callAttendance(1); });
        $('#btn_out').on('click', function(){ callAttendance(4); });

        loadLastStatus();
        loadServerTime();
        setInterval(loadServerTime, 300000);
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