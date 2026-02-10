<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 14/12/14
 * Time: 09:40 ص
 */
/*
$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_input';
$TB_NAME2= 'stores_class_input_detail';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$transfer_url =base_url("$MODULE_NAME/$TB_NAME/transferInvoice");

//$adopt_url=base_url("$MODULE_NAME/$TB_NAME/adopt");


$customer_url =base_url('payment/customers/public_index');
$delete_details_url=base_url("$MODULE_NAME/$TB_NAME2/delete");

$select_items_url=base_url("$MODULE_NAME/classes/public_index");
$get_url =base_url("$MODULE_NAME/$TB_NAME/public_get_stores_input");
echo AntiForgeryToken();
*/
?>
<!--
<div class="row">
    <div class="toolbar">

        <div class="caption">   تحويل سندات مخزنية لفاتورة شراء</div>

        <ul><?php
//  if(HaveAccess($transfer_url))
            if(true) echo "<li  ><a onclick='javascript:{$TB_NAME}_transfer();' href='javascript:;'><li class='glyphicon glyphicon-download'>تحويل لفاتورة شراء</li></a> </li>";//glyphicon glyphicon-
           ?>

        </ul>

    </div>


    <div class="form-body">

        <div class="modal-body inline_form">



            <form  id="<?=$TB_NAME?>_form" method="get" action="<?=$get_url?>" role="form" novalidate="novalidate">



                <div class="form-group col-sm-3">
                    <label class="control-label"> من تاريخ</label>
                    <div>
                        <input type="date"  name="from_date"  value="<?=$from_date?>"  id="txt_from_date" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-3">
                    <label class="control-label"> إلى تاريخ </label>
                    <div>
                        <input type="date"  name="to_date"  value="<?=$to_date ?>"  id="txt_to_date" class="form-control">
                    </div>
                </div>

                <input type="hidden"  name="text"  value="<?=$text ?>"   id="txt_text" class="form-control">


                <div class="form-group col-sm-1">
                    <label class="control-label">&nbsp;</label>
                    <div>
                        <button type="button" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success">بحث</button>
                    </div>
                </div>


            </form>
        </div>



    </div>

</div>





    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
        </div>
    </div>

</div>
-->

<?php
/*
$scripts = <<<SCRIPT
<script type="text/javascript">

function {$TB_NAME}_transfer(){
        var url = '{$transfer_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        var x;
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });
x=val[0];
for (v=1;v<val.length;v++){
x=x+','+ val[v];
}

        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد تحويل '+val.length+' سجلات وتحويل تفاصيلها ؟!!')){
alert(x);
            }
        }else
            alert('يجب تحديد السجلات المراد تحويلها');
    }

    function get_val(id){
    alert(id);
//get_to_link('( {$get_url}).'/'.id.'/'.( isset($action)?$action.'/':''));
   //{$TB_NAME}_get(get_id());
    }



</script>

SCRIPT;

sec_scripts($scripts);
*/
?>
