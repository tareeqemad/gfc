<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            direction: rtl;
        }
        .formbold-main-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        .formbold-form-wrapper {
            margin: 0 auto;
            max-width: 1000px;
            width: 100%;
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: -1px 0px 25px -5px rgba(59,59,59,0.72);
            -webkit-box-shadow: -1px 0px 25px -5px rgba(59,59,59,0.72);
            -moz-box-shadow: -1px 0px 25px -5px rgba(59,59,59,0.72);
        }

        .formbold-form-img {
            margin-bottom: 45px;
        }

        .formbold-input-group {
            margin-bottom: 18px;
        }

        .formbold-form-select {
            width: 100%;
            padding: 12px 22px;
            border-radius: 5px;
            border: 1px solid #dde3ec;
            background: #ffffff;
            font-size: 16px;
            color: #536387;
            outline: none;
            resize: none;
        }

        .formbold-input-radio-wrapper {
            margin-bottom: 25px;
        }
        .formbold-radio-flex {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .formbold-radio-label {
            font-size: 14px;
            line-height: 24px;
            color: #07074d;
            position: relative;
            padding-left: 25px;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .formbold-input-radio {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        .formbold-radio-checkmark {
            position: absolute;
            top: -1px;
            left: 0;
            height: 18px;
            width: 18px;
            background-color: #ffffff;
            border: 1px solid #dde3ec;
            border-radius: 50%;
        }
        .formbold-radio-label
        .formbold-input-radio:checked
        ~ .formbold-radio-checkmark {
            background-color: #6a64f1;
        }
        .formbold-radio-checkmark:after {
            content: '';
            position: absolute;
            display: none;
        }

        .formbold-radio-label
        .formbold-input-radio:checked
        ~ .formbold-radio-checkmark:after {
            display: block;
        }

        .formbold-radio-label .formbold-radio-checkmark:after {
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ffffff;
            transform: translate(-50%, -50%);
        }

        .formbold-form-input {
            width: 100%;
            padding: 13px 22px;
            border-radius: 5px;
            border: 1px solid #dde3ec;
            background: #ffffff;
            font-weight: 500;
            font-size: 16px;
            color: #07074d;
            outline: none;
            resize: none;
        }
        .formbold-form-input::placeholder {
            color: #536387;
        }
        .formbold-form-input:focus {
            border-color: #6a64f1;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        }
        .formbold-form-label {
            color: #07074d;
            font-size: 15px;
            font-weight: 550;
            line-height: 24px;
            display: block;
            margin-bottom: 10px;
            text-align: right;
        }

        .formbold-btn {
            text-align: center;
            width: 100%;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            padding: 14px 25px;
            border: none;
            font-weight: 500;
            background-color: rgb(4, 100, 252);
            color: white;
            cursor: pointer;
            margin-top: 25px;
        }
        .formbold-btn:hover {
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        }
        .alert-secondary {
            color: #000000;
            font-weight:700;
            font-size: 17px
        }
        .btn-primary, .btn-secondary{
            font-weight: bold;
        }
        .nav-item a{
            font-weight: bold;
        }
        .tab-pane{
            min-height: 400px;
        }
    </style>

</head>
<body data-new-gr-c-s-check-loaded="14.1114.0" data-gr-ext-installed="">

<div class="formbold-main-wrapper">
    <!-- Author: FormBold Team -->
    <!-- Learn More: https://formbold.com -->
    <div class="formbold-form-wrapper" style="border: 1px solid #bec0c2">
        <div class="row">
            <div class="col-lg-1">
                <img style="width: 70px;height: 75px" src="<?=base_url('assets/images/bi-imgs/logo2.png');?>" alt="">
            </div>
            <div class="col-lg-11" style="text-align: center;color: #050000;font-weight: 600;">
                <h4 style="text-align: center;margin-bottom: 10px;font-weight: 600; padding-top: 10px">نموذج التقرير الشهري لمتابعة الفروع - شركة توزيع كهرباء محافظات غزة </h4>
                <h5 style="font-weight: 600;">تطوير وحدة ذكاء الأعمال ودعم القرار</h5>
            </div>

        </div>


        <form action="<?=base_url('biunit/Report/create') ?>" method="POST" style="margin-top: 20px">
            <div style="background-color: rgba(222,219,219,0.17);width: 100%;height: 60px;padding: 10px 20px;margin-bottom: 20px">
                <div class="form-group row">
                    <label for="age" class="formbold-form-label" style="padding: 5px 10px;" >الفرع: </label>
                    <select class="form-control col-sm-2" disabled>
                        <option>غزة</option>
                        <option selected>الشمال</option>
                        <option>الوسطى</option>
                        <option>خانيونس</option>
                        <option>رفح</option>
                    </select>

                    <label for="age" class="formbold-form-label" style="padding: 5px 10px; margin-right: 30px" >الشهر: </label>
                    <input type="text" class="form-control col-sm-3" id="todayDate" value="" style="height: 38px" disabled>

                    <label for="age" class="formbold-form-label" style="padding: 5px 10px;  margin-right: 30px" >اسم المستخدم: </label>
                    <input type="text" class="form-control col-sm-3" value="مرفت صباح" style="height: 38px" disabled>
                </div>

            </div>


            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php $count=0; $id=0;?>
                <?php foreach ($Main_categories as $key=>$row ){ ?>
                    <?php $count==0?$id=$row['MAIN_CATEGORY']:'';   ?>
                    <li class="nav-item">
                        <?php if($count==0){ ?>
                            <a class="nav-link active" id="<?= 'page'.$row['MAIN_CATEGORY'].'-tab' ?>" data-toggle="tab" href="<?= '#page'.$row['MAIN_CATEGORY'] ?>" role="tab" aria-controls="<?= 'page'.$row['MAIN_CATEGORY'] ?>" aria-selected="<?php $count==0?'true':'false';?>"><?= $row['MAIN_CAT_NAME'] ?></a>
                        <?php   }else{ ?>
                            <a class="nav-link  <?php $count?'':'active'; ?>" id="<?= 'page'.$row['MAIN_CATEGORY'].'-tab' ?>" data-toggle="tab" href="<?= '#page'.$row['MAIN_CATEGORY'] ?>" role="tab" aria-controls="<?= 'page'.$row['MAIN_CATEGORY'] ?>" aria-selected="<?php $count==0?'true':'false';?>"><?= $row['MAIN_CAT_NAME'] ?></a>
                        <?php   } ?>
                    </li>
                    <?php $count= $count +1; ?>
                <?php } ?>

            </ul>
            <div class="tab-content" id="myTabContent">
                <?php $count=0; ?>
                <?php foreach ($Main_categories as $key=>$row ){ ?>

                <?php if($row['MAIN_CATEGORY']==$id){ ?>
                <div class="tab-pane fade row show active" id="<?= 'page'.$row['MAIN_CATEGORY'] ?>" role="tabpanel" aria-labelledby="<?= 'page'.$row['MAIN_CATEGORY'].'-tab' ?>"><br>
                    <?php   }else{ ?>
                    <div class="tab-pane fade row " id="<?= 'page'.$row['MAIN_CATEGORY'] ?>" role="tabpanel" aria-labelledby="<?= 'page'.$row['MAIN_CATEGORY'].'-tab' ?>"><br>
                        <?php   } ?>

                        <?php foreach ($Sub_categories as $key2=>$row2 ){
                            if($row2['MAIN_CATEGORY'] ==$row['MAIN_CATEGORY']){ ?>
                                <div class="form-group col">
                                    <label for="age" class="formbold-form-label"> <?= $row2['SUB_CAT_NAME'] ?><span class="text-danger">*</span> </label>
                                    <input type="text" name="indicator[<?= $row2['SUB_CAT'] ?>]" placeholder="" class="form-control">
                                </div>
                            <?php   } ?>
                        <?php   } ?>

                    </div>
                    <?php $count= $count +1; ?>
                    <?php } ?>
                </div>

                <button class="btn btn-primary col-sm-2" style="float: right;">ارسال</button>
                <a href="<?= base_url('Biunit/index')?>" class="btn btn-secondary col-sm-2" style="float: right; margin-right: 10px">إلغاء</a>







        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $(function () {
        $('#myTab li:first-child a').tab('show')
    })
    $(document).ready(function(){
        var current = new Date();
        current.setMonth(current.getMonth()-1);
        current.setDate(1);
        document.getElementById("todayDate").value = current.toLocaleString('en-CA',{dateStyle:"short"});
        document.getElementById("month").value = current.toLocaleString('en-CA',{dateStyle:"short"});
    });

</script>

</body>
<grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>
</html>