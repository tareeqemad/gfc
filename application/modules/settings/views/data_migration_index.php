<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 4/10/2019
 * Time: 8:07 AM
 */

?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>


    </div>

    <div class="form-body">


        <fieldset>
            <legend></legend>
            <ul class="report-menu">

                <li><a class="btn blue" href="javascript:;" onclick="javascript:financialAccounts();"> الارصدة
                        الافتتاحية المالية </a></li>

                <li style="margin-right: 20px;"></li>
                <li><a class="btn blue" href="javascript:;" onclick="javascript:transferOutcomeCheck();">ترحيل الشيكات
                        الصادرة</a></li>
                <li><a class="btn blue" href="javascript:;" onclick="javascript:transferIncomeCheck();"> ترحيل الشيكات
                        الوارد </a></li>

            </ul>
        </fieldset>


    </div>

</div>
