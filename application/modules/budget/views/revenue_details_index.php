<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/10/14
 * Time: 12:37 م
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'revenue_details';

$get_data= base_url("$MODULE_NAME/$TB_NAME/get_page");
$post_data= base_url("$MODULE_NAME/$TB_NAME/receive_data");
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");

?>

<style type="text/css">
    #data{clear:both; padding-top: 10px;}
</style>

<div class="row">
    <div class="toolbar">

        <?php
        if(encryption_case($type)==2)
            $subj=' (بعد التنسيب) ';
        else
            $subj='';
        ?>

        <div class="caption">تفاصيل ايرادات تحصيلات فاتورة كهرباء لعام <?=$year.$subj?></div>

        <ul style="display: none">
            <?php
            if(HaveAccess($create_url)) echo "<li><a onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد</a></li>";
            if(HaveAccess($post_data)) echo "<li><a onclick='javascript:{$TB_NAME}_save();' href='javascript:;'><i class='glyphicon glyphicon-saved'></i>حفظ</a></li>";
            if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a></li>";

            $get_data.="/$type";
            $post_data.="/$type";
            $create_url.="/$type";
            $delete_url.="/$type";
            ?>
        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_data?>" role="form" novalidate="novalidate">

                <div style="margin-bottom: 10px">
                <span style="color: #ff0000">
                    ملاحظة: يجب ان يكون مجموع نسب بنود التحصيل النقدي <?=$cash_percent?> % على الأقل، وهي :
                </span>
                    <br/>
                <span style="color: blue">
تحصيل المقرات ، تحصيل الجباية الميدانية ، تحصيل الخزينة العامة ( الادارة المالية ) ، شركة ابناء زياد مرتجى ، شركة ابناء احمد القدوة ، تسديد بال بي ، تسديد استقطاعات موظفين ومركبات مستأجرة ، تسديد مقاصة موردين ،
تسديد فواتير الاذاعات ، السداد الالي ، مبيعات مسبق الدفع .
                </span>

                </div>
                <div id="selects">
                    <div id="months" class="col-sm-3"><?=$months?></div>
                    <div id="total" class="col-sm-2"></div>
                </div>

                <div id="data"></div>
                <?=AntiForgeryToken(); ?>
            </form>
        </div>
    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $(document).ready(function() {

        $('#txt_month').select2();

        $('#txt_month').change(function(){
            $('#container #data').text('');
            $('#container #total').text('');
            $('.toolbar ul').hide();

            if($('#txt_month').val() !=0 ){
                get_data('$get_data', {month:$('#txt_month').val()}, function(ret){ $('#container #data').html(ret); }, 'html');
            }
        });

    });

    function {$TB_NAME}_save(){
        if(validation('{$TB_NAME}_tb')){
            if(confirm('هل تريد بالتأكيد حفظ جميع السجلات')){
                $('.toolbar ul').hide();
                var form = $("#{$TB_NAME}_form");
                ajax_insert_update(form,function(data){
                    $('#container #data').html(data);
                    success_msg('رسالة','تم حفظ السجلات بنجاح ..');
                    $('.toolbar ul').show();
                },"html");
            }
        }
    }

    function validation(tb){
        var ret= true;
        var sum_ret= 0;
        var sum_cash= 0;
        var cash_percent= $cash_percent;
        var arr_cash= [1,4,5,6,8,9,10,13,14,15,16]; // البنود النقدية

        $('#'+tb+' tbody tr').each(function() {
            var collection_type= 0;
            var rate= $(this).find('.rate').val();
            $(this).find('.collection_type').each(function(){
                if(this.value!= undefined)
                    collection_type= this.value;
            });

            if( (collection_type== 0 && rate!='' ) || (collection_type > 0 && rate=='' ) || (rate<=0 && rate!='') || isNaN(rate)   ){
                alert('ادخل نوع التحصيل والنسبة');
                $(this).find('.rate').focus();
                ret= false;
                return false;
            }else if( rate!=''){
                sum_ret+= parseFloat(rate)*1000;
                if($.inArray(parseInt(collection_type),arr_cash)!= -1){
                    sum_cash+= parseFloat(rate)*1000;
                }
            }
        });

        if(ret){
            sum_ret= sum_ret/1000;
            sum_cash= sum_cash/1000;
            if(sum_ret!=100){
                alert('يجب ان يكون مجموع النسب 100% المجموع الحالي هو '+sum_ret);
                ret= false;
            }

            if(sum_cash < cash_percent ){
                alert('يجب ان يكون مجموع نسب البنود النقدية '+cash_percent+'% على الأقل، المجموع الحالي هو '+sum_cash);
                ret= false;
            }

            var items= [];
            $('#'+tb+' tbody .collection_type').each(function(){
                items.push(this.value);
            });
            var items = items.sort();
            var results = [];
            for (var i= 0; i < items.length - 1; i++) {
                if (items[i+1] == items[i] && items[i]!= null && items[i]!= 0) {
                    results.push(items[i]);
                }
            }
            if(results.length > 0 && ret){
                alert('يوجد '+results.length+' سجلات مكررة لنفس نوع التحصيل');
                ret= false;
            }
        }
        return ret;
    }

    function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        if(confirm('هل تريد بالتأكيد حذف جميع السجلات ؟!!')){
            ajax_delete_any(url, {month:$('#txt_month').val()} ,function(data){
                success_msg('رسالة','تم حذف السجلات بنجاح ..');
                $('#container #data').html(data);
            });
        }
    }

</script>

SCRIPT;

sec_scripts($scripts);
?>
