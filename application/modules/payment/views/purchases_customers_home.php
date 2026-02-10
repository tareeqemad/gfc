<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 02/12/14
 * Time: 08:49 ص
 */

$MODULE_NAME = 'payment';
$TB_NAME = 'customers';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_purchases");
$create_url = base_url().'CreateCustomer';
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url('payment/customers/get_page_purchases');


echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">الرقم</label>
                    <div class="">
                        <input type="text" id="txt_customer_id" class="form-control "/>


                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الاسم</label>
                    <div class="">
                        <input type="text" id="txt_customer_name" class="form-control "/>


                    </div>
                </div>


            </div>
            <div class="modal-footer">

                <button type="button" onclick="search_data();" class="btn btn-success"> إستعلام</button>

                <button type="button" onclick="clearForm_any($('fieldset'));search_data();"
                        class="btn btn-default"> تفريغ الحقول
                </button>
            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?= modules::run($get_page_url, $page); ?>
        </div>

    </div>

</div>

<?php
$edit = '';
if (HaveAccess($edit_url))
    $edit = 'edit';

$scripts = <<<SCRIPT
<script>
    function select_customer(ser){
    get_to_link('{$get_url}'+'/'+ser+'/'+'{$edit}');
    }

      $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({customer_id : $('#txt_customer_id').val(),customer_name:$('#txt_customer_name').val() });

    }

    function LoadingData(){

    ajax_pager_data('#customers_tb > tbody',{customer_id : $('#txt_customer_id').val(),customer_name:$('#txt_customer_name').val() });

    }


   function search_data(){

        get_data('{$get_page_url}',{page:1,customer_id : $('#txt_customer_id').val(),customer_name:$('#txt_customer_name').val() },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }



</script>
SCRIPT;

sec_scripts($scripts);

?>


