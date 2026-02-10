<?php
/**
 * Created by PhpStorm.
 * User: Mkilani
 * Date: 15/02/22
 */

// http://192.168.200.6/Scada_Portal/Default.aspx
// https://scadaweb.gedco.ps/Scada_Portal/Default.aspx

?>
<div class="alert alert-info text-center h4">
    <?= $desc ?>
</div>
<div class="row">
    <a style="display: none" href="javascript:;" class="prints btn-lg" h_title="طباعة" onclick="javascript:print_report(7,1,1);">
        <span class="glyphicon glyphicon-print" style="color: #0a8800"></span>
    </a>
    <div class="alert alert-danger text-center" style="margin-right: 80px; width:750px;">

        التقرير يظهر الاحمال لآخر 24 ساعة فقط
    </div>

</div>
<div class="tbl_container">
    <iframe style="border:none; overflow:hidden; width:750px; height:1050px; margin-right: 80px" scrolling="no" src="https://scadaweb.gedco.ps/Scada_Portal/Default.aspx"></iframe>
</div>