<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/10/14
 * Time: 07:28 ص
 */


$close_year_url=base_url('pledges/inventory_pledges/close_year');
$overtime_count_url=base_url('budget/calculator/overtime_count');

$open_new_year_url=base_url('pledges/inventory_pledges/open_new_year');
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
                <div class="circle" id="calc_budget">
                    <strong></strong>

                </div>

                <button class="btn red" id="calc_budget_btn"   onclick="javascript:close_year(this);">إغلاق الجرد </button>

            </li>

            <li class="col-sm-4">
                <div class="circle" id="calc_prices">
                    <strong></strong>

                </div>

                <button class="btn red" id="calc_prices_btn"   onclick="javascript:open_new_year(this);">فتح جرد جديد  <?=($LAST_YEAR+1)?></button>

            </li>

        </ul>


    </div>

</div>
<?php


$scripts = <<<SCRIPT
<script>

  

    BudgetProgress(0);
      BudgetPrices(0);
    var salCountInt;
    var salCStrVal = 0.0;





    function salCountIntStopFunction() {
        clearInterval(salCountInt);
    }


   function close_year(obj){
	     if (isDoubleClicked($(obj))) return;
         salCStrVal = 0.0; 
          get_data('$close_year_url',{},function(data){
            salCountIntStopFunction();
            salCStrVal = 1;
            BudgetProgress(1);
			alert(data);
        },'html');
    }
  function open_new_year(obj){
	    if (isDoubleClicked($(obj))) return;
           get_data('$open_new_year_url',{},function(data){
			salCountIntStopFunction();
             BudgetPrices(1);
			 alert(data);
			   /*
           if(data==1) {  
               success_msg('رسالة','تمت العملية بنجاح');
           BudgetPrices(1);
           }
           else { danger_msg('تحذير',data);} */
           
        },'html');
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



