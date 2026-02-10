<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/03/23
 * Time: 11:00 ص
 */

$MODULE_NAME= 'salary';
$TB_NAME= 'Exceptional_credit';
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$back_url= base_url("$MODULE_NAME/$TB_NAME/index");
$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$date_attr = " data-type='date' data-date-format='YYYYMM' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
?>

    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">بدل الاتصال</a></li>
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
                                        <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>

                            <div class="form-group col-sm-1">
                                <label class="control-label">الشهر</label>
                                <div>
                                    <input type="text"  value="<?= $current_date ?>" name="the_month" id="txt_the_month" readonly class="form-control" >
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>الرصيد</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['BALANCE']:''?>" name="balance" id="txt_balance" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>


                            <div class="form-group col-sm-2">
                                <label>المتبقي</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['RESIDUAL']:''?>" name="residual" id="txt_residual" class="form-control" readonly onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label>فئة الاتصال</label>
                                <select name="category" id="dp_category" class="form-control">
                                    <option value="">_______</option>
                                    <?php foreach ($category as $row) : ?>
                                        <option <?= $HaveRs ? ($rs['CATEGORY'] == $row['CON_NO'] ? 'selected' : '') : '' ?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <div class="modal-footer">

                            <?php if (HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>

                            <?php if ( $HaveRs and HaveAccess($adopt_url.'10') and $rs['ADOPT']==1 ) : ?>
                                <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد</button>
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

    $('.sel2:not("[id^=\'s2\']")').select2();
    
    $('#dp_category').prop('readonly', true);
    $('#dp_category').attr('readonly', true);
    
    $("#dp_branch_no").change(function(){
        var branch = $(this).val();
        
        $("#dp_category").val(1);
        
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
    
        if(no==10 ) var msg= 'هل تريد تأكيد الطلب ؟!';

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