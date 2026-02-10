<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 05/01/23
 * Time: 09:20 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Subscribers_types';

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$back_url= base_url("$MODULE_NAME/$TB_NAME/index");
$public_get_max = base_url("$MODULE_NAME/$TB_NAME/public_get_max");
$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>

    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">احصائيات</a></li>
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

                            <div class="form-group col-sm-2">
                                <label>نوع الاشتراك</label>
                                <select type="text" name="bill_type" id="dp_bill_type" class="form-control sel2"  onchange="get_bill_type();">
                                    <option value="0">__________</option>
                                    <?php foreach ($bill_type as $row) : ?>
                                        <option  <?=$HaveRs?($rs['BILL_TYPE']==$row['VID']?'selected':''):''?>  value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-sm-1">
                                <label>رقم الثابت </label>
                                <input type="text" value="<?=$HaveRs?$rs['NO']:''?>" name="ser" id="txt_ser" class="form-control"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>اسم الثابت</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['SUBSCRIBER_TYPE']:''?>" name="subscriber_type" id="txt_subscriber_type" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group col-sm-1">
                                <label>قيمة الرسم</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['SUBSCRIBE_FEES']:''?>" name="subscribe_fees" id="txt_subscribe_fees" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <?php if (  HaveAccess($post_url) && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
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

    function get_bill_type(){
        var no =  $('#dp_bill_type').val();
        if (no == 0) {
            $('#txt_ser').val('');   
            return -1; 
        } else {
            get_data('{$public_get_max}', {id: no}, function (data) {
                $.each(data, function (i, value) {
                    $('#txt_ser').val(value.NO);                 
                });
            });
        }           
    }

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
sec_scripts($scripts);
?>