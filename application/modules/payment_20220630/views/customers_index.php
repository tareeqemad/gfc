<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 10:33 ص
 */

$MODULE_NAME = 'payment';
$TB_NAME = 'customers';
$get_url = base_url("$MODULE_NAME/$TB_NAME/public_get_customers");

?>

<div class="form-body">

    <div class="modal-body inline_form">
        <form id="<?= $TB_NAME ?>_form" method="get" action="<?= $get_url ?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-2">
                <label class="control-label">رقم المستفيد</label>

                <div>
                    <input type="text" name="customer_id" id="txt_customer_id" value="<?= $id ?>" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-3">
                <label class="control-label">اسم المستفيد</label>

                <div>
                    <input type="text" name="customer_name" value="<?= $name ?>" id="txt_customer_name"
                           class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-3">
                <label class="control-label">النوع </label>

                <div class="">

                    <select name="customer_type" id="dp_customer_type" class="form-control">
                        <?php foreach ($customer_type as $row) : ?>
                            <option <?= isset($type) && $type == $row['CON_NO'] ? 'selected' : '' ?>
                                value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>

            <input type="hidden" name="text" value="<?= $text ?>" id="txt_text" class="form-control">


            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>

                <div>
                    <button type="button" onclick="javascript:<?= $TB_NAME ?>_search();" class="btn btn-success">بحث
                    </button>
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>

                <div>
                    <button type="button" onclick="javascript:<?= $TB_NAME ?>_clear();" class="btn btn-success">تفريغ
                        الحقول
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="container">
        <?= modules::run("$MODULE_NAME/$TB_NAME/public_get_customers", array('text' => $text, 'id' => $id, 'name' => $name, 'type' => $type, 'account_id'=>'', 'page' => $page)); ?>
    </div>

</div>

</div>

<?php
$scripts = <<<SCRIPT
<script>
    function {$TB_NAME}_search(){
        var id= $('#txt_customer_id').val();
        var name= $('#txt_customer_name').val();
        var type= $('#dp_customer_type').val();
            var text= $('#txt_text').val();
        get_data('{$get_url}',{id:id, name:name, type:type,text:text},function(data){
            $('#container').html(data);
        },'html');
    }

    function {$TB_NAME}_clear(){
        $('#{$TB_NAME}_form :input').val('');
    }

    function select_customer(id,text,types){



            parent.$('#$text').val(text);
            parent.$('#h_$text').val(id);
            parent.$('select[name="customer_account_type[]"]',parent.$('#$text').closest('tr')).val(-1);
            
            parent.$('select[name="customer_account_type[]"] option,#db_customer_account_type option',parent.$('#$text').closest('tr')).each(function() {

                        if(types.indexOf($(this).val()) != -1){
                            $(this).show();
                        }
                        else
                         $(this).hide();
                    });
            parent.$('#db_cat_$text option').each(function() {
                        if(types.indexOf($(this).val()) != -1){
                            $(this).show();
                        }
                        else
                         $(this).hide();
                    });

             parent.$('#db_customer_account_type option,#db_d_customer_account_type option').each(function() {

                      console.log('',$(this));
                        if(types.indexOf($(this).val()) != -1){
                            $(this).show();
                        }
                        else
                         $(this).hide();
                    });


            //MKilani start
            var types_arr= types.split(',');
            parent.$('#dp_customer_account_type option').each(function(){
                if( $.inArray( $(this).val(), types_arr ) != -1 ){
                    $(this).prop('disabled',0);
                }else{
                    $(this).prop('disabled',1);
                }
            });
            //MKilani end

               if( typeof parent.setDefaultCustomerAccount =='function'){
                 parent.setDefaultCustomerAccount();
              }





            //parent.$('select[name="customer_account_type[]"]',parent.$('#$text').closest('tr')).val(-1);

            if(parent.$('#$text').attr('data-balance') != 'false')
              if (typeof  parent.update_balance == 'function') {
              parent.update_balance(parent.$('#h_$text'));
              }

            if( typeof parent.get_customer_invoices =='function')
                 parent.get_customer_invoices();
               
             parent.$('select[name="customer_account_type[]"]',parent.$('#account_2').closest('tr')).val(-1);

            parent.$('#report').modal('hide');

    }

</script>
SCRIPT;
sec_scripts($scripts);

?>
