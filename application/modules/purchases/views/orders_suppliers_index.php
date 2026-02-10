<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 26/06/16
 * Time: 01:04 م
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$MODULE_NAME= 'purchases';
$TB_NAME= 'orders';


$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_orders");


echo AntiForgeryToken();
?>

<div class="row">


    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <input type="hidden" name="purchase_order_id"  value="<?=$purchase_order_id?>">
            <input type="hidden" name="customer_id"  value="<?=$customer_id?>">
            <input type="hidden" name="customer_curr_id"  value="<?=$customer_curr_id?>">
        </form>
        <div id="container">
            <?= modules::run($get_page_url,$page,$purchase_order_id,$customer_id,$customer_curr_id);?>
        </div>

    </div>

</div>

