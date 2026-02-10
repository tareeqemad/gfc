<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 06/03/23
 * Time: 11:00 ص
 */

$MODULE_NAME= 'ratio_emp_lost';
$TB_NAME= 'Works_teams_data';
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$back_url= base_url("$MODULE_NAME/$TB_NAME/index");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

?>

    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">مشروع الفاقد</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex py-3">
                    <div class="mb-0 flex-grow-1 card-title">
                        <?= $title ?>
                    </div>
                    <div class="flex-shrink-0">
                        <a class="btn btn-info" href="<?= $back_url ?>"><i class="fa fa-reply"></i> </a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" role="form" action="<?=$post_url?>" novalidate="novalidate">

                        <div class="row">

                            <div class="form-group col-sm-1">
                                <label>الرقم التسلسلي</label>
                                <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                                <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                    <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                                <?php endif; ?>
                            </div>

                            <div class="form-group col-md-2">
                                <label>المقر </label>
                                <select name="branch_no" id="dp_branch_no" class="form-control " >
                                    <option value="">_________</option>
                                    <?php foreach($branches as $row) :?>
                                        <?php if ($HaveRs){ ?>
                                            <option <?=$HaveRs?($rs['BRANCH_NO']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                        <?php } else {  ?>
                                            <option <?= $this->user->branch == $row['NO'] ? 'selected' : '' ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                        <?php } ?>
                                    <?php endforeach;?>
                                </select>
                            </div>

                            <div class="form-group col-sm-1">
                                <label class="control-label">الشهر</label>
                                <div>
                                    <?php if ($HaveRs){ ?>
                                        <input type="text" value="<?=$HaveRs?$rs['THE_MONTH']:''?>" name="the_month" id="txt_the_month" class="form-control">
                                    <?php } else {  ?>
                                        <input type="text"  value="<?= $current_date ?>" name="the_month" id="txt_the_month" class="form-control">
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">

                            <?php if (HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>

                            <?php if ((HaveAccess($adopt_url.'2') and $HaveRs and $rs['ADOPT']==1)  ) : ?>
                                <button type="button" id="btn_adopt_2" onclick='javascript:adopt_(2);' class="btn btn-warning">سحب</button>
                            <?php endif; ?>

                            <?php if ((HaveAccess($adopt_url.'3') and $HaveRs and $rs['ADOPT']==2 ) ) : ?>
                                <button type="button" id="btn_adopt_3" onclick='javascript:adopt_(3);' class="btn btn-success">اعتماد</button>
                            <?php endif; ?>

                            <?php if ((HaveAccess($adopt_url.'1') and $HaveRs and $rs['ADOPT']==2)  ) : ?>
                                <button type="button" id="btn_adopt_1" onclick='javascript:adopt_(1);' class="btn btn-danger">الغاء السحب</button>
                            <?php endif; ?>

                        </div>
                    </form>
                    <div id="container">
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2').select2();
    
    $('#dp_branch_no').prop('readonly', true);
    $('#dp_branch_no').attr('readonly', true);
    
    $('#txt_the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        if(confirm(msg)){

            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data));
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
</script>
SCRIPT;

if($HaveRs){
    $scripts = <<<SCRIPT
    {$scripts}
<script type="text/javascript">

    function adopt_(no){
        if(no==2 ) var msg= 'هل تريد سحب البيانات ؟!';
        if(no==3 ) var msg= 'هل تريد تأكيد الطلب ؟!';
        if(no==1 ) var msg= 'هل تريد الغاء السحب ؟!';

        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}"};
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }else{

        }
    }

</script>
SCRIPT;
}
sec_scripts($scripts);
?>
