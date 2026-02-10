<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/11/14
 * Time: 02:31 م
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'exp_rev_update';
$TB_NAME2= 'exp_rev_update_details';

$get_page= base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$details_url= base_url("$MODULE_NAME/$TB_NAME/get_details");
$department_url= base_url("$MODULE_NAME/$TB_NAME/select_department");
$chapter_url= base_url("$MODULE_NAME/$TB_NAME/select_chapter");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");

?>

<style type="text/css">
    #data{clear:both; padding-top: 10px;}
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=record_case(encryption_case($adopt))." لموازنة عام ".$year; ?></div>

        <ul>
            <li id="get_data"><a onclick='javascript:<?=$TB_NAME?>_get_data();' href='javascript:;'><i class='glyphicon glyphicon-import'></i>استعلام</a></li>
            <?php if( encryption_case($adopt)==5 ) { ?><li><a onclick='javascript:<?=$TB_NAME?>_adopt_all();' href='javascript:;'><i class='glyphicon glyphicon-saved'></i>اعتماد الكل</a></li> <?php } ?>
        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" role="form" novalidate="novalidate">

                <div id="selects">
                    <div id="type" class="col-sm-2">
                        <select name='type' id='txt_type' class='form-control'>
                            <option value='1' selected='selected'>ايرادات</option>
                            <option value='2' >نفقات</option>
                        </select>
                    </div>
                    <div id="branch" class="col-sm-2"><?=$select_branch?></div>
                    <div id="position" class="col-sm-3""></div>
                    <div id="chapters" class="col-sm-3"></div>
                </div>

                <div id="data"></div>
                <?php
                echo AntiForgeryToken();
                echo "<input name='case' id='txt_case' type='hidden' value='{$adopt}' />";
                ?>
            </form>
        </div>
    </div>

</div>


<div class="modal fade" id="<?=$TB_NAME2?>Modal">
    <div class="modal-dialog">
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
        $('#txt_branch, #txt_type').change(function(){
            $('#container #data, #container #position, #container #chapters').text('');
            if($('#txt_branch').val()==1){
                {$TB_NAME}_get_department();
                $('#position').show();
            }else if($('#txt_branch').val()!=0){
                {$TB_NAME}_get_chapter();
                $('#position').hide();
            }
        });
        $('div#position').change(function(){
            $('#container #data, #container #chapters').text('');
            if($('#txt_department').val()!=0){
                {$TB_NAME}_get_chapter();
            }
        });
        $('div#chapters').change(function(){
            $('#container #data').text('');
        });
    });

    function {$TB_NAME}_get_department(){
        var values= $("#{$TB_NAME}_form").serialize();
        get_data("$department_url", values, function(ret){
            $('#container #position').html(ret);
        }, 'html');
    }

    function {$TB_NAME}_get_chapter(){
        var values= $("#{$TB_NAME}_form").serialize();
        get_data("$chapter_url", values, function(ret){
            $('#container #chapters').html(ret);
        }, 'html');
    }

    function {$TB_NAME}_get_data(){
        $('#container #data').text('');
        if( ($('#txt_type').val() ==1 || $('#txt_type').val() ==2 ) && $('#txt_branch').val() !=0 && $('#txt_branch').val() != null && $('#txt_chapter').val() !=0 && $('#txt_chapter').val() != null){
            if( $('#txt_branch').val() !=1 || ($('#txt_branch').val() ==1 && $('#txt_department').val() !=0  ) ){
                var values= $("#{$TB_NAME}_form").serialize();
                get_data("$get_page", values, function(ret){
                    $('#container #data').html(ret);
                }, 'html');
            }else alert('يجب اختيار الادارة');
        } else alert('يجب اختيار الفرع والباب');
    }

     function {$TB_NAME}_adopt(no, action){
        var action_desc= 'اعتماد';
        if(action==0)
            action_desc= 'الغاء اعتماد';
        var tr = $('#{$TB_NAME}_tb #tr-'+no);
        var values= {type:$('#txt_type').val(), branch: $('#txt_branch').val(), department: $('#txt_department').val(), chapter: $('#txt_chapter').val(), case: $('#txt_case').val(), no:no, action:action, total_update: tr.find('#txt_total_update').val() ,note: tr.find('#txt_note').val() };
        get_data("{$adopt_url}", values, function(ret){
            $('#container #data').html(ret);
            success_msg('رسالة','تم '+action_desc+' البيانات بنجاح ..');
        }, 'html');
     }

     function {$TB_NAME}_adopt_all(){
        var adopt_btns= $('.adopt_sec');
        $.each( adopt_btns, function( key, value ){
            actuateLink( $('.adopt_sec').eq( key ) );
        });
     }

     function {$TB_NAME}_get_details(sec){
        $('#{$TB_NAME2}Modal .modal-body').text('');
        $('#{$TB_NAME2}Modal .modal-title').text('تفاصيل ' + $('#{$TB_NAME}_form #td-'+sec).text() );
        var values= {type:$('#txt_type').val(), branch: $('#txt_branch').val(), department: $('#txt_department').val(), section:sec };
        get_data("{$details_url}", values, function(ret){
            $('#{$TB_NAME2}Modal .modal-body').html(ret);
        }, 'html');
        $('#{$TB_NAME2}Modal').modal();
     }

     function {$TB_NAME}_delete(no){
        var url = '{$delete_url}';
        var branch_desc= 'للمقر';
        if($('#txt_branch').val()==1)
            branch_desc= 'للادارة';
        var values= {no:no,type:$('#txt_type').val(), branch: $('#txt_branch').val(), department: $('#txt_department').val(), chapter: $('#txt_chapter').val(), case: $('#txt_case').val() };
        if(confirm('هل تريد بالتأكيد حذف هذا الفصل من شاشة التنسيب وارجاعه '+branch_desc+'؟!!')){
            ajax_delete_any(url, values ,function(data){
                success_msg('رسالة','تم حذف السجل بنجاح ..');
                $('#container #data').html(data);
            });
        }
     }

</script>

SCRIPT;

sec_scripts($scripts);
?>
