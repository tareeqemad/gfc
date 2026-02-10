<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 31/08/15
 * Time: 12:08 م
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'branches_teams';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");

$customer_url =base_url('payment/customers/public_index');

$print_url= 'http://itdev:801/gfc.aspx';

$isCreate =isset($team_data) && count($team_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $team_data[0];

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">م الفريق</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['TEAM_SER']:''?>" id="txt_team_ser" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false) ) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['TEAM_SER']:''?>" name="team_ser" id="h_team_ser">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">  الفرع </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" <?=$HaveRs||1?'disabled':''?> class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الفريق</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['TEAM_ID']:''?>" name="team_id" id="txt_team_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">اسم الفريق</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['TEAM_NAME']:''?>" name="team_name" id="txt_team_name" class="form-control" />
                    </div>
                </div>


                <div style="clear: both"></div>
                <input type="hidden" id="h_data_search" />

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", ($HaveRs)?$rs['TEAM_SER']:0, ($HaveRs)?1:0 ); ?>
            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( !$isCreate  and 0 ) : ?>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>

                <?php if ($isCreate and 0 ): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    var count = 0;

    var worker_jobs_json= {$worker_jobs};
    var select_worker_jobs= '';

    $.each(worker_jobs_json, function(i,item){
        select_worker_jobs += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    reBind();

    $('select[name="worker_job[]"]').append(select_worker_jobs);

    $('select[name="worker_job[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="worker_job[]"]').select2();

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الفريق ؟!';
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data)+'/edit');
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

    function addRow(){
        count = count+1;
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="ser[]" value="0" /><input class="form-control" readonly name="customer_id[]" id="h_txt_customer_id'+count+'" /></td> <td><input name="customer_name[]" readonly class="form-control"  id="txt_customer_id'+count+'" /></td> <td><select name="worker_job[]" class="form-control" id="txt_worker_job'+count+'" /></select></td> <td><i class="glyphicon glyphicon-remove delete_account"></i></td> </tr>';
        $('#details_tb tbody').append(html);
        reBind(1);
    }

    $('.delete_account').click(function(){
        var tr = $(this).closest('tr');
        tr.find('input[name="customer_id[]"]').val('');
        tr.find('input[name="customer_name[]"]').val('');
    });

    function reBind(s){
        if(s==undefined)
            s=0;

        $('input[name="customer_name[]"]').click("focus",function(e){
            _showReport('$customer_url/'+$(this).attr('id')+'/3' );
        });

        if(s==1){
            $('select#txt_worker_job'+count).append('<option></option>'+select_worker_jobs).select2();
        }

        $('.delete_account').click(function(){
            var tr = $(this).closest('tr');
            tr.find('input[name="customer_id[]"]').val('');
            tr.find('input[name="customer_name[]"]').val('');
        });

    }

SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    $(function() {
        $( "#details_tb tbody" ).sortable();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}else{
    $scripts = <<<SCRIPT
    {$scripts}

    count = {$rs['COUNT_DET']} -1;

    </script>
SCRIPT;
}
sec_scripts($scripts);
?>
