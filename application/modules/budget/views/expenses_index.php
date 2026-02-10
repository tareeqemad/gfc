<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/09/14
 * Time: 10:40 ص
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'expenses';
$TB_NAME2= 'items_details';

$items_url= base_url("$MODULE_NAME/$TB_NAME/select_items");
$get_data= base_url("$MODULE_NAME/$TB_NAME/get_page");
$post_data= base_url("$MODULE_NAME/$TB_NAME/receive_data");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
$details_url= base_url("$MODULE_NAME/budget_items_details/get_page");
$delete_details_url= base_url("$MODULE_NAME/budget_items_details/delete");
$post_details= base_url("$MODULE_NAME/budget_items_details/receive_data");
$attachment_data_url= base_url("$MODULE_NAME/$TB_NAME/attachment_get");

?>
<style type="text/css">
    #data{clear:both; padding-top: 10px;}
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption">النفقات لعام <?=$year?></div>

        <ul>
            <li id="get_data"><a onclick='javascript:<?=$TB_NAME?>_get_data();' href='javascript:;'><i class='glyphicon glyphicon-import'></i>استعلام</a></li>
            <?php
            if (HaveAccess($post_data))
                echo "<li id='save' style='display: none;'><a onclick='javascript:{$TB_NAME}_save();' href='javascript:;'><i class='glyphicon glyphicon-saved'></i>حفظ</a></li>";
            if (HaveAccess($attachment_data_url))
                echo "<li id='attachment' style='display: none;'><a onclick='javascript:attachment_get();' href='javascript:;'><i class='glyphicon glyphicon-file'></i>المرفقات</a></li>";
            ?>
        </ul>


    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_data?>" role="form" novalidate="novalidate">

                <div id="selects">
                    <div id="position" class="col-sm-3"><?=$select_position?></div>
                    <div id="section" class="col-sm-3"><?=$select_section?></div>
                    <div id="section_cval"  class="col-sm-1"></div>
                    <input type="hidden" name="s_cval" id="s_cval" value="0">
                    <div id="items" class="col-sm-3"></div>
                    <div id="item_data" class="col-sm-2"></div>
                </div>

                <div id="data"></div>
                <?=AntiForgeryToken(); ?>
            </form>
        </div>
    </div>

</div>



    <div class="modal fade" id="<?=$TB_NAME2?>Modal">
        <div class="modal-dialog" style="width: 850px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <form class="form-horizontal" id="<?=$TB_NAME2?>_form" method="post" action="<?=$post_details?>" role="form" novalidate="novalidate">
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button id="<?=$TB_NAME2?>_save" type="button" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade" id="uploadModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">المرفقات</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $(document).ready(function() {

        $('#txt_section').select2();
        $('#txt_user_position').select2();

        $('#txt_section').change(function(){
         $('#section_cval').text(" السقف المالي : "+$('#txt_section').find(':selected').attr('data-cval')+" شيكل "+" المتبقي : "+$('#txt_section').find(':selected').attr('data-aval')+" شيكل ");
            $('#s_cval').val($('#txt_section').find(':selected').attr('data-cval'));
            $('#items').text('');
            $('#item_data').text('');
            if($('#txt_section').val() !=0 ){
                get_data('$items_url', {section:$('#txt_section').val()}, function(ret){ $('#items').html(ret); }, 'html');
            }
        });

        $('#txt_section, #items, #txt_user_position').change(function(){
            disable_save();
        });

    });


    function {$TB_NAME}_get_data(){
        if($('#txt_items').val() !=0 && $('#txt_items').val() != null && $('#txt_user_position').val() !=0 && $('#txt_user_position').val() != null  ){
            var values= $("#{$TB_NAME}_form").serialize();
            get_data("$get_data", values, function(ret){
                $('#container #data').html(ret);
                $('li#get_data').hide();
                $('li#save').show();
                $('li#attachment').show();
            }, 'html');
        } else alert('يجب اختيار البند والقسم');
    }

    function {$TB_NAME}_save(){
        if(validation('{$TB_NAME}_tb')){
            if(confirm('هل تريد بالتأكيد حفظ جميع السجلات')){
                $('li#save').hide();
                var form = $("#{$TB_NAME}_form");
                //console.log('',form); // for test
                ajax_insert_update(form,function(data){
                    $('#container #data').html(data);
                    $('li#save').show();
                },"html");
            }
        }
    }

    function validation(tb){
        var ret= true;

        $('#'+tb+' tbody tr').each(function() {
            var ccount= $(this).find('.ccount').val();
            var price= $(this).find('.price').val();
            if( (price== 0 && price!='' ) || ( (ccount!='' && ccount > 0 ) && (price='' || price <= 0 || isNaN(price))) ){
                alert('يجب ان يكون السعر اكبر من صفر');
                $(this).find('.price').focus();
                ret= false;
                return false;
            }else if((price!='' && price > 0 ) && (ccount='' || ccount <= 0 || isNaN(ccount))){
                alert('يجب ان تكون الكمية اكبر من صفر');
                $(this).find('.ccount').focus();
                ret= false;
                return false;
            }
        });

        if(tb=='{$TB_NAME2}_tb' && ret){
            var items= [];
            var ccount= null;
            var price= null;
            $('#'+tb+' tbody .special_items').each(function(){
                items.push(this.value);
                ccount= $(this).closest('tr').find('.ccount').val();
                price= $(this).closest('tr').find('.price').val();
                if((this.value!= null && this.value!=0) && (ccount=='' || price =='')){
                    alert('ادخل الكمية والسعر');
                    $(this).closest('tr').find('.ccount').focus();
                    ret= false;
                    return false;
                }
            });

            var items = items.sort();
            var results = [];
            for (var i= 0; i < items.length - 1; i++) {
                if (items[i+1] == items[i] && items[i]!= null && items[i]!= 0) {
                    results.push(items[i]);
                }
            }
            if(results.length > 0 && ret){
                if(!confirm('يوجد '+results.length+' سجلات مكررة لنفس البند، هل تريد المتابعة؟!'))
                    ret= false;
            }
        }
        return ret;
    }

    function disable_save(){
        $('li#get_data').show();
        $('li#save').hide();
        $('li#attachment').hide();
        $('#container #data').text('');
    }

    function {$TB_NAME}_delete(id){
        var items= $('#txt_items').val();
        var user_position= $('#txt_user_position').val();
        var url = '{$delete_url}';
        var values= {id:id,items:items,user_position:user_position};
        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            ajax_delete_any(url, values ,function(data){
                success_msg('رسالة','تم حذف السجل بنجاح ..');
                $('#container #data').html(data);
            });
        }
    }



    function {$TB_NAME}_details_get(id, n){
        $('#{$TB_NAME2}_form .modal-body').text();
        clearForm($('#{$TB_NAME2}_form'));
        $('#{$TB_NAME2}Modal .modal-title').text('تفاصيل ' + $('#txt_items option:selected').text() );
        get_data('$details_url', {exp_rev_no:id, n:n}, function(ret){ $('#{$TB_NAME2}_form .modal-body').html(ret); }, 'html');
        $('#{$TB_NAME2}Modal').modal();
    }

    $('#{$TB_NAME2}_save').click(function(e){
        e.preventDefault();
        if(validation('{$TB_NAME2}_tb')){
            if(confirm('هل تريد بالتأكيد حفظ جميع السجلات')){
                var form = $("#{$TB_NAME2}_form");
                ajax_insert_update(form,function(data){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#{$TB_NAME2}_form .modal-body').html(data);
                    {$TB_NAME}_get_data();
                },"html");
            }
        }
    });

    function {$TB_NAME2}_delete(id,no){
        var url = '{$delete_details_url}';
        var values= {id:id,exp_rev_no:no};
        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            ajax_delete_any(url, values ,function(data){
                success_msg('رسالة','تم حذف السجل بنجاح ..');
                $('#{$TB_NAME2}_form .modal-body').html(data);
                {$TB_NAME}_get_data();
            });
        }
    }


    function attachment_get(){
        if($('#txt_items').val() !=0 && $('#txt_items').val() != null && $('#txt_user_position').val() !=0 && $('#txt_user_position').val() != null  ){
            var values= {user_position:$('#txt_user_position').val(),item:$('#txt_items').val()};
            get_data("{$attachment_data_url}", values, function(ret){
                    $('#uploadModal .modal-body').html(ret);
                    $('#uploadModal').modal();
            }, 'html');
        }
   }


</script>

SCRIPT;

sec_scripts($scripts);
?>