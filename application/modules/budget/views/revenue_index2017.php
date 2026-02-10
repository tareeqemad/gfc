<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 24/09/16
 * Time: 01:19 م
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'revenue';
$TB_NAME2= 'items_details';
$TB_NAME4= 'exp_rev_history';

$section_url= base_url("$MODULE_NAME/$TB_NAME/public_select_section");
$items_url= base_url("$MODULE_NAME/$TB_NAME/select_items");
$get_data= base_url("$MODULE_NAME/$TB_NAME/get_page");
$post_data= base_url("$MODULE_NAME/$TB_NAME/receive_data");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
$details_url= base_url("$MODULE_NAME/budget_items_details/get_page");
$delete_details_url= base_url("$MODULE_NAME/budget_items_details/delete");
$post_details= base_url("$MODULE_NAME/budget_items_details/receive_data");
$attachment_data_url= base_url("$MODULE_NAME/$TB_NAME/attachment_get");
$get_history= base_url("budget/exp_rev_total/get_history");
$get_active_val_url= base_url("$MODULE_NAME/$TB_NAME/public_get_active_val");
?>

<style type="text/css">
    #data{clear:both; padding-top: 10px;}
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption">الايرادات لعام <?=$year?></div>

        <ul>
            <li id="get_data"><a onclick='javascript:<?=$TB_NAME?>_get_data();' href='javascript:;'><i class='glyphicon glyphicon-import'></i>استعلام</a></li>
            <?php
            if (HaveAccess($post_data))
                echo "<li id='save' style='display: block;'><a onclick='javascript:{$TB_NAME}_save();' href='javascript:;'><i class='glyphicon glyphicon-saved'></i>حفظ</a></li>";
            if (HaveAccess($attachment_data_url))
                echo "<li id='attachment' style='display: block;'><a onclick='javascript:attachment_get();' href='javascript:;'><i class='glyphicon glyphicon-file'></i>المرفقات</a></li>";
            ?>
            <li class="btn btn-warning dropdown-toggle"><a onclick="$('#revenue_tb').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown" href='javascript:;'><i ></i>تصدير</a></li>

        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_data?>" role="form" novalidate="novalidate">



                <?php if(HaveAccess('show_branches')){ ?>
                <div class="form-group col-md-3">
                    <div>
                        <select name="branch" id="id_branch" class="form-control">
                            <?php foreach($select_branch as $row) :?>
                                <option  value="<?= $row['NO'] ?>"><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php  } else { ?>
                    <div style="display: none">
                        <select name="branch" id="id_branch" class="form-control">
                                <option  value="<?=$this->user->branch ?>"></option>
                        </select>
                    </div>
                <?php } ?>


                <div id="selects">
                    <div id="position" class="col-sm-2"><?=$select_position?></div>
                    <div id="type" class="col-sm-1">
                        <select name='type' id='txt_type' class='form-control'>
                            <?php foreach($consts as $row) :?>
                                <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="section" class="col-sm-2"><?=$select_section?></div>
                   <div id="section_cval"  class="col-sm-2" style="color: #009900;"></div>
                    <input type="hidden" name="s_cval" id="s_cval" value="0">
                    <div id="history"  class="col-sm-1" style="font: bold" ></div>
                    <div id="items" class="col-sm-2"></div>
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

<div class="modal fade" id="<?=$TB_NAME4?>Modal">
    <div class="modal-dialog" style="width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
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
        $('#id_branch').select2();
        $('#txt_section').select2();
        $('#txt_user_position').select2();

        $('#txt_type').change(function(){
            $('#section').text('');
            get_data('$section_url', {type:$('#txt_type').val()}, function(ret){ $('#section').html(ret);
                   $('#txt_section').select2();
                 }, 'html');

                 $('#section_cval').text('');
                 $('#s_cval').val('0');
                  $('#items').text('');
                  $('#item_data').text('');
                   $('#history').html("");
                    $('#data').text('');


    });

        $('#txt_section').change(function(){

 get_data('$get_active_val_url', {sec_no:$(this).val(),type:$('#txt_type').val(),branch:$('#id_branch').val()}, function(ret){
       $('#section_cval').text(" السقف المالي : "+$('#txt_section').find(':selected').attr('data-cval')+" شيكل "+" المتبقي : "+ret+" شيكل ");

         });
   $('#s_cval').val($('#txt_section').find(':selected').attr('data-cval'));
            if($('#txt_section').val() !=0 ){
                get_data('$items_url', {section:$('#txt_section').val()}, function(ret){ $('#items').html(ret); }, 'html');
       $('#history').html("<a href='javascript:;' onclick='javascript: exp_rev_total_get_history("+$('#txt_section').val()+"); '> <i  class='glyphicon glyphicon-list'>التاريخية</i></a>");

            }
             $('#items').text('');
            $('#item_data').text('');
              $('#data').text('');
        });

        $('#txt_section, #items, #txt_user_position').change(function(){
            disable_save();
        });

    });

function change_section(){

 get_data('$get_active_val_url', {sec_no:$('#txt_section').find(':selected').val(),type:$('#txt_type').val(),branch:$('#id_branch').val()}, function(ret){
       $('#section_cval').text(" السقف المالي : "+$('#txt_section').find(':selected').attr('data-cval')+" شيكل "+" المتبقي : "+ret+" شيكل ");

         });
             $('#s_cval').val($('#txt_section').find(':selected').attr('data-cval'));

            if($('#txt_section').val() !=0 ){
                get_data('$items_url', {section:$('#txt_section').val()}, function(ret){ $('#items').html(ret); }, 'html');

       $('#history').html("<a href='javascript:;' onclick='javascript: exp_rev_total_get_history("+$('#txt_section').val()+"); '> <i  class='glyphicon glyphicon-list'>التاريخية</i></a>");

            }


             $('#items').text('');
            $('#item_data').text('');
              $('#data').text('');

}
    function {$TB_NAME}_get_data(){
      if($('#txt_section').val() !=0 && $('#txt_section').val() != null && $('#txt_user_position').val() !=0 && $('#txt_user_position').val() != null  ){
            var values= $("#{$TB_NAME}_form").serialize();
            get_data("$get_data", values, function(ret){
                $('#container #data').html(ret);
              //  $('li#get_data').hide();
             //   $('li#save').show();
              //  $('li#attachment').show();
            }, 'html');
        } else alert('يجب اختيار  القسم و الفصل');


    }

    function {$TB_NAME}_save(){
    var sec;
        if(validation('{$TB_NAME}_tb')){
            if(confirm('هل تريد بالتأكيد حفظ جميع السجلات')){
              //  $('li#save').hide();
                var form = $("#{$TB_NAME}_form");
                //console.log('',form); // for test
                ajax_insert_update(form,function(data){
                 //   $('#container #data').html(data);
              //      $('li#save').show();
             //  sec=$('#txt_section').val();

          get_data('$get_active_val_url', {sec_no:$('#txt_section').val(),type:$('#txt_type').val(),branch:$('#id_branch').val()}, function(ret){
           $('#section_cval').text(" السقف المالي : "+$('#txt_section').find(':selected').attr('data-cval')+" شيكل "+" المتبقي : "+ret+" شيكل ");
            });

             {$TB_NAME}_get_data();

                },"html");

            }
        }
    }

    function validation(tb){
        var ret= true;


        return ret;
    }

    function disable_save(){
       // $('li#get_data').show();
      //  $('li#save').hide();
      //  $('li#attachment').hide();
        $('#container #data').text('');
    }

    function {$TB_NAME}_delete(id){
        var items= $('#txt_items').val();
        var user_position= $('#txt_user_position').val();
        var url = '{$delete_url}';
        var values= {id:id,items:items,user_position:user_position};
        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            setTimeout(function(){
            ajax_delete_any(url, values ,function(data){

                  success_msg('رسالة','تم حذف السجل بنجاح ..');
                   get_data('$get_active_val_url', {sec_no:$('#txt_section').val(),type:$('#txt_type').val(),branch:$('#id_branch').val()}, function(ret){
           $('#section_cval').text(" السقف المالي : "+$('#txt_section').find(':selected').attr('data-cval')+" شيكل "+" المتبقي : "+ret+" شيكل ");
            });
               {$TB_NAME}_get_data();
            //    $('#container #data').html(data);
                 }, 1000);

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



    function attachment_get(){
        if($('#txt_items').val() !=0 && $('#txt_items').val() != null && $('#txt_user_position').val() !=0 && $('#txt_user_position').val() != null  ){
            var values= {user_position:$('#txt_user_position').val(),item:$('#txt_items').val()};
            get_data("{$attachment_data_url}", values, function(ret){
                    $('#uploadModal .modal-body').html(ret);
                    $('#uploadModal').modal();
            }, 'html');
        }
   }
    function exp_rev_total_get_history(sec){

            $('#{$TB_NAME4}Modal .modal-body').text('');
            $('#{$TB_NAME4}Modal .modal-title').text('البيانات التاريخية -  ' + $('#{$TB_NAME2}Modal #td-'+sec).text() );
            get_data('$get_history', {branch:$('#txt_branch').val(), section:sec}, function(ret){ $('#{$TB_NAME4}Modal .modal-body').html(ret); }, 'html');
            $('#{$TB_NAME4}Modal').modal();

    }


</script>

SCRIPT;

sec_scripts($scripts);
?>
