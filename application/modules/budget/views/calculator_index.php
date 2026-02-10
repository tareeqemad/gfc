<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/10/14
 * Time: 07:28 ص
 */

$salary_calc_url=base_url('budget/calculator/salary_calc');
$salary_count_url=base_url('budget/calculator/salary_count');
$overtime_calc_url=base_url('budget/calculator/overtime_calc');
$budget_calc_url=base_url('budget/calculator/budget_calc');
$overtime_count_url=base_url('budget/calculator/overtime_count');
$budget_history_items_count_url=base_url('budget/calculator/budget_history_items_count');
$budget_update_prices_url=base_url('budget/calculator/budget_update_prices');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <ul class="calc-menu">
            <li class="col-sm-4">
                <div class="circle" id="calc_salary">
                    <strong></strong>

                </div>

                <button class="btn green"  onclick="javascript:salary_calc();">إحتساب الراتب</button>

            </li>

            <li class="col-sm-4">
                <div class="circle" id="calc_overtime">
                    <strong></strong>

                </div>

                <button class="btn blue" id="calc_overtime_btn" disabled onclick="javascript:overtime_calc();" > إحتساب الوقت الإضافي</button>

            </li>

            <li class="col-sm-4">
                <div class="circle" id="calc_budget">
                    <strong></strong>

                </div>

                <button class="btn red" id="calc_budget_btn" disabled  onclick="javascript:budget_calc();">إحتساب الموازنة </button>

            </li>

            <li class="col-sm-4">
                <div class="circle" id="calc_prices">
                    <strong></strong>

                </div>

                <button class="btn red" id="calc_prices_btn"   onclick="javascript:budget_up_prices();">تحديث أسعار بنود  من سعر شراء الأصناف لموازنة سنة  <?=($year+1)?></button>

            </li>

        </ul>


    </div>

</div>
<?php


$scripts = <<<SCRIPT
<script>

    SalProgress($sal_per);
    OvertimeProgress($overtime_per);
    BudgetProgress($budget_per);
      BudgetPrices(0);
    var salCountInt;
    var salCStrVal = 0.0;

    function salCountTimer() {

        $.ajax({
            url: '$salary_count_url',
            type: "POST",
            data:{},

            dataType: 'html',
            success: function(data){
                var val = parseFloat(data);
                SalProgress(val);
                salCStrVal =val;
            }
        });
    }

 function OvertimeCountTimer() {

        $.ajax({
            url: '$overtime_count_url',
            type: "POST",
            data:{},

            dataType: 'html',
            success: function(data){
                var val = parseFloat(data);
                console.log('',val);
                OvertimeProgress(val);
                salCStrVal =val;
            }
        });
    }


 function BudgetCountTimer() {

        $.ajax({
            url: '$budget_history_items_count_url',
            type: "POST",
            data:{},

            dataType: 'html',
            success: function(data){
                var val = parseFloat(data);
                console.log('',val);
                BudgetProgress(val);
                salCStrVal =val;
            }
        });
    }


    function salCountIntStopFunction() {
        clearInterval(salCountInt);
    }


    function salary_calc(){
        salCountInt= setInterval(function(){salCountTimer()}, 2000);
        get_data('$salary_calc_url',{},function(data){
            salCountIntStopFunction();
            salCStrVal = 1;
            SalProgress(1);

        },'html');
    }

    function overtime_calc(){
         salCStrVal = 0.0;
        salCountInt= setInterval(function(){OvertimeCountTimer()}, 2000);
        get_data('$overtime_calc_url',{},function(data){
            salCountIntStopFunction();
            salCStrVal = 1;
            OvertimeProgress(1);
        },'html');
    }


   function budget_calc(){
         salCStrVal = 0.0;
        salCountInt= setInterval(function(){BudgetCountTimer()}, 2000);
        get_data('$budget_calc_url',{},function(data){
            salCountIntStopFunction();
            salCStrVal = 1;
            BudgetProgress(1);
        },'html');
    }
  function budget_up_prices(){
           get_data('$budget_update_prices_url',{},function(data){
           if(data==1) {  
               success_msg('رسالة','تمت العملية بنجاح');
           BudgetPrices(1);
           }
           else { danger_msg('تحذير',data);}
           
        },'html');
    }
    
    function SalProgress(val){
        if(val >= 1)
         $('#calc_overtime_btn').prop('disabled',false);

        $('#calc_salary').circleProgress({
            value: val,animationStartValue:salCStrVal
        }).on('circle-animation-progress', function(event, progress, stepValue) {
            $(this).find('strong').html(parseInt(100 * stepValue) + '<i>%</i>');
        });
    }

 function OvertimeProgress(val){
        if(val >= 1)
         $('#calc_budget_btn').prop('disabled',false);

        $('#calc_overtime').circleProgress({
            value: val,animationStartValue:salCStrVal
        }).on('circle-animation-progress', function(event, progress, stepValue) {
            $(this).find('strong').html(parseInt(100 * stepValue) + '<i>%</i>');
        });
    }


   function BudgetProgress(val){

           $('#calc_budget').circleProgress({
            value: (val%100),animationStartValue:salCStrVal
        }).on('circle-animation-progress', function(event, progress, stepValue) {
            $(this).find('strong').html(parseInt(100 * stepValue) + '<i>%</i>');
        });
    }
    
    function BudgetPrices(val){

           $('#calc_prices').circleProgress({
            value: (val%100),animationStartValue:salCStrVal
        }).on('circle-animation-progress', function(event, progress, stepValue) {
            $(this).find('strong').html(parseInt(100 * stepValue) + '<i>%</i>');
        });
    }

</script>
SCRIPT;

sec_scripts($scripts);



?>



