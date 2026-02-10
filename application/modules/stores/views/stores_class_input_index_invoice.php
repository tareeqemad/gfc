<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 25/02/15
 * Time: 09:14 ص
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_input';


$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_invoice");


echo AntiForgeryToken();
?>

<div class="row">


    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
         <input type="hidden" name="invoice_seq"  value="<?=$invoice_seq?>">
            </form>
            <div id="container">
            <?=modules::run($get_page_url,$page,$invoice_seq);?>
        </div>

    </div>

</div>

<?php
/*$scripts = <<<SCRIPT
<script>
 $('.pagination li').click(function(e){
        e.preventDefault();
    });




    function search(){
        get_data('{$get_page_url}',{ page:1,invoice_seq:$('#invoice_seq').val()},function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        ajax_pager_data('#stores_class_input_tb > tbody',{ page:1,invoice_seq:$('#invoice_seq').val()});
    }

</script>
SCRIPT;
sec_scripts($scripts);*/
?>
