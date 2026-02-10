<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/09/14
 * Time: 01:40 م
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'expenses_adopt';
$TB_NAME2= 'expenses_details';
$section_url= base_url("$MODULE_NAME/$TB_NAME/select_section");
$get_data= base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$details_url= base_url("$MODULE_NAME/$TB_NAME/get_details");
$attachment_data_url= base_url("$MODULE_NAME/$TB_NAME/attachment_get");

?>
<style type="text/css">
    #data{clear:both; padding-top: 10px;}
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=record_case($adopt)." لنفقات عام ".$year; ?></div>

        <ul>
        <li id="get_data"><a onclick='javascript:<?=$TB_NAME?>_get_data();' href='javascript:;'><i class='glyphicon glyphicon-import'></i>استعلام</a> </li>
        <?php
        if (HaveAccess($adopt_url))
            echo "<li><a onclick='javascript:{$TB_NAME}(1);' href='javascript:;'><i class='glyphicon glyphicon-saved'></i>اعتماد</a> </li>";
        if (HaveAccess($adopt_url) and $adopt!=3)
            echo "<li><a onclick='javascript:{$TB_NAME}(0);' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>الغاء الاعتماد</a> </li>";
        ?>
        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$adopt_url?>" role="form" novalidate="novalidate">

                <div id="selects">
                    <div id="position" class="col-sm-3"><?=$select_position?></div>
                    <div id="section" class="col-sm-3"></div>
                </div>

                <?php
                echo AntiForgeryToken();
                echo "<input name='adopt' type='hidden' value='{$adopt}' />";
                echo "<input name='action' id='action' type='hidden' value='' />";
                ?>

                <div id="data"></div>
            </form>
        </div>
    </div>

</div>


    <div class="modal fade" id="<?=$TB_NAME2?>Modal">
        <div class="modal-dialog" style="width: 700px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <form class="form-horizontal" id="<?=$TB_NAME2?>_from" method="post"  role="form" novalidate="novalidate">
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $(document).ready(function() {
        $('#txt_user_position').select2();

        $('#section, #txt_user_position').change(function(){
            disable_save();
        });

        $('#txt_user_position').change(function(){
            $('#section').text('');
            if($('#txt_user_position').val() !=0 ){
                get_data('$section_url', {user_position:$('#txt_user_position').val()}, function(ret){ $('#section').html(ret); }, 'html');
            }
        });

    });


    function {$TB_NAME}_get_data(){
        if($('#txt_section').val() !=0 && $('#txt_section').val() != null && $('#txt_user_position').val() !=0 && $('#txt_user_position').val() != null ){
            var values= $("#{$TB_NAME}_form").serialize();
            get_data("$get_data", values, function(ret){
                $('#container #data').html(ret);
                $('li#get_data').hide();
            }, 'html');
        } else alert('يجب اختيار الفصل والقسم');
    }

    function disable_save(){
        $('li#get_data').show();
        $('#container #data').text('');
    }

    function {$TB_NAME}(action){
        var action_desc= 'اعتماد';
        $('#{$TB_NAME}_form #action').val(action);
        if(action==0){
            action_desc= 'الغاء اعتماد';
        }
        var val = [];
        $('#{$TB_NAME}_form .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });
        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
                var form = $('#{$TB_NAME}_form');
                ajax_insert_update(form,function(data){
                    success_msg('رسالة','تم '+action_desc+' البيانات بنجاح ..');
                    $('#container #data').html(data);
                },"html");
            }
        }
    }

    function {$TB_NAME2}_get(no, name, adopt, adopt_emp_no){
        clearForm($('#{$TB_NAME2}_from'));
        $("#{$TB_NAME2}_from .modal-body").text('');
        $('#{$TB_NAME2}Modal .modal-title').text('تفاصيل بند '+name);
        $('#{$TB_NAME2}Modal').modal();

        get_data("$details_url",
        {items:no, user_position: $('#txt_user_position').val(), adopt:adopt, adopt_emp_no:adopt_emp_no },
        function(ret){
            $("#{$TB_NAME2}_from .modal-body").html(ret);
        },
        'html');
    }


    function attachment_get(item){
        $('#{$TB_NAME2}Modal .modal-body').text('');
        $('#{$TB_NAME2}Modal .modal-title').text('المرفقات');
        if(item!=0 && item!= null && $('#txt_user_position').val() !=0 && $('#txt_user_position').val() != null  ){
            var values= {user_position:$('#txt_user_position').val(),item:item};
            get_data("{$attachment_data_url}", values, function(ret){
                    $('#{$TB_NAME2}Modal .modal-body').html(ret);
                    $('#{$TB_NAME2}Modal').modal();
            }, 'html');
        }
    }


</script>

SCRIPT;

sec_scripts($scripts);
?>