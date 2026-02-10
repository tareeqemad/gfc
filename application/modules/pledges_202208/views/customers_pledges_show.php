<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 10/02/15
 * Time: 08:22 ص
 */

$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_pledges';
$TB_NAME2= 'class_codes';
$rs= $pledge_data;
$code_url= base_url("$MODULE_NAME/$TB_NAME/set_code");
$print_code_url= base_url("$MODULE_NAME/class_codes/print_case_code");
$post_url='';
$rep_url = base_url('greport/reports/public_customers_pledges_barcode');
$back_url=base_url("$MODULE_NAME/$TB_NAME/index/1/".$emp_pledges); //$action
$class_output_url=base_url("stores/stores_class_output/get");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$serial_url =base_url("pledges/class_codes/edit");
$get_code_url =base_url("$MODULE_NAME/class_codes/public_get_id");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/public_adopt");
echo AntiForgeryToken();
$count=0;
$class_code_ser=(isset($rs['BARCODE']) )? $rs['BARCODE'] :'0';

$fid=(isset($rs['FILE_ID']) )? $rs['FILE_ID'] :'0';

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">م </label>
                    <div><?=$rs['FILE_ID']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حساب المستفيد</label>
                    <div><?=$rs['CUSTOMER_ID']?></div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label"> المستفيد</label>
                    <div><?=$rs['CUSTOMER_ID_NAME']?></div>
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label"> الغرفة</label>
                    <div>
                       <?=$rs['ROOM_ID'].":".$rs['ROOM_ID_NAME']?>
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> الإدارة</label>
                    <div><?=$rs['MANAGE_ST_ID_NAME']?></div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> الدائرة</label>
                    <div><?=$rs['CYCLE_ST_ID_NAME']?></div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> القسم</label>
                    <div><?=$rs['DEPARTMENT_ST_ID_NAME']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مصدر العهدة</label>
                    <div><?=$rs['SOURCE_NAME']?></div>
                </div>
<?php if ($rs['SOURCE']==1) {
?>
                <div class="form-group col-sm-2">
                    <label class="control-label">رقم سند الصرف</label>
                    <div>

                        <a target="_blank" href="<?php  echo $class_output_url."/".$rs['CLASS_OUTPUT_ID']; ?>"><?=$rs['CLASS_OUTPUT_ID']?></a>
                    </div>
                </div>
<?php
}
?>
                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة العهدة</label>
                    <div><?php if($rs['STATUS']==2) echo $rs['STATUS_NAME'].' - '.$rs['CUSTOMER_MOVMENT_ID_NAME']; else if($rs['STATUS']==4) echo $rs['STATUS_NAME'].' - '.$rs['STORE_ID_NAME'].' - '.$rs['STORES_CLASS_RETURN_ADOPT_NAME']; else
                            echo $rs['STATUS_NAME'];?> </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الباركود</label>
                    <div><?=$rs['BARCODE']?></div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الصنف</label>
                    <div><?=$rs['CLASS_ID']?></div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">اسم الصنف</label>
                    <div><?=$rs['CLASS_ID_NAME']?></div>
                </div>




                <div class="form-group col-sm-2">
                    <label class="control-label">الوحدة </label>
                    <div><?=$rs['CLASS_UNIT_NAME']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الكمية </label>
                    <div><?=$rs['AMOUNT']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">السعر </label>
                    <div><?=$rs['PRICE']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم حساب المصروف للمستفيدين </label>
                    <div><?=$rs['EXP_ACCOUNT_CUST']?></div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">حساب المصروف للمستفيدين </label>
                    <div><?=$rs['EXP_ACCOUNT_CUST_NAME']?></div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">حالة الصنف</label>
                    <div><?=$rs['CLASS_TYPE_NAME']?></div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ استلام العهدة</label>
                    <div><?=$rs['RECIEVED_DATE']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة السجل</label>
                    <div><?=$rs['ADOPT_NAME']?></div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-9">
                    <label class="control-label">البيان</label>
                    <div><?=$rs['NOTES']?></div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المدخل</label>
                    <div><?=$rs['ENTRY_USER_NAME']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الإدخال</label>
                    <div><?=$rs['ENTRY_DATE']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المعتمد</label>
                    <div><?=$rs['ADOPT_USER_NAME']?></div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الاعتماد</label>
                    <div><?=$rs['ADOPT_DATE']?></div>
                </div>
                <?php if($rs['D_FILE_ID'] != '') { ?>
                <div class="form-group col-sm-2">
                    <label class="control-label">تابع لعهدة </label>
                    <div><a target="_blank" href="<?=$get_url.'/'.$rs['D_FILE_ID'].'/'.$emp_pledges?>"><?=$rs['D_FILE_ID']?></a> </div>
                </div>
    <?php } ?>
                <div class="form-group col-sm-2">
                <?php

                if(HaveAccess($print_code_url) and  $rs['BARCODE']!='' and $rs['CLASS_ACOUNT_TYPE']==1 ){  ?>
                    <button id="btn_print"  onclick="javascript:print();" type="button" class="btn btn-success">  طباعة </button>
                <?php } ?>
                 <?php   if (HaveAccess($code_url) and $rs['BARCODE']=='' and $rs['CLASS_ACOUNT_TYPE']==1 and $rs['STATUS']==1){
                    ?>
               <button id="btn_code"  onclick="javascript:setcode();" type="button" class="btn btn-success"> إنشاء كود للعهدة </button>
                  <?php } ?>
                    <?php   if (HaveAccess($serial_url) and $rs['BARCODE']!='' and $rs['CLASS_ACOUNT_TYPE']==1 and $rs['STATUS']==1){
                        ?>
                        <button id="btn_serial"  onclick="javascript:class_codes_get('<?=$rs['BARCODE']?>');" type="button" class="btn btn-success"> رقم الشركة المصنعة</button>
                    <?php } ?>
                    <?php if (  ($rs['ADOPT']==1) && ($rs['IS_EMP_PLEDGES']==1)  ) : ?>
                        <button type="button" id="btn_adopt" onclick='javascript:adopt();' class="btn btn-success">اعتماد الاستلام  </button>
                    <?php endif; ?>
                    <?php //if ( HaveAccess($public_return_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and $rs['STATUS']==1)) : ?>
                      <!--  <button type="button" onclick="javascript:return_adopt(0);" class="btn btn-danger">الغاء اعتماد الاستلام</button>
                   -->
                    <?php //endif; ?>

    </div>

            </div>
<?php if(count($result_d)>0){ ?>
            <div class="modal-body inline_form">
                <table class="table" id="page_tb" data-container="container">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم العهدة</th>
                        <th>اسم الصنف</th>
                        <th>حالة الصنف</th>
                    </tr>
                    </thead>
                    <tbody>
    <?php
       foreach($result_d as $row) : $count++;?>
                    <tr>
                        <td><?=$count?></td>
                        <td><a target="_blank" href="<?=$get_url.'/'.$row['FILE_ID'].'/'.$emp_pledges?>"><?=$row['FILE_ID']?></a>
                       </td>
                        <td><?=$row['CLASS_ID'].':'.$row['CLASS_ID_NAME']?></td>
                        <td><?=$row['CLASS_TYPE_NAME']?></td>
                    </tr>
    <?php endforeach;?>
                    </tbody>
                </table>
               </div>
    <?php } ?>
            <div style="clear: both;">
                <input type="hidden" id="h_data_search" />
            </div>
            <div style="clear: both;">
                <?php echo modules::run('settings/notes/public_get_page',(count($rs)>0)?$rs['FILE_ID']:0,'customers_pledges'); ?>
                <?php echo (count($rs)>0)?  modules::run('attachments/attachment/index',$rs['FILE_ID'],'customers_pledges') : ''; ?>
            </div>
        </form>
    </div>
</div>


    <div class="modal fade" id="<?=$TB_NAME2?>Modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"> بيانات الصنف</h4>
                </div>
                <form class="form-horizontal" id="<?=$TB_NAME2?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME2/edit")?>" role="form" novalidate="novalidate">
                    <div class="modal-body inline_form">
                        <div class="form-group">
                            <label class="col-sm-1 control-label"> رقم الباركود  </label>
                            <div class="col-sm-2">
                                <input type="text" readonly data-val="true"   data-val-required="حقل مطلوب" name="class_code_serr" id="txt_class_code_serr" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label"> اسم الصنف  </label>
                            <div class="col-sm-9">
                                <input type="text"  readonly data-val="true"   data-val-required="حقل مطلوب" name="class_id_name" id="txt_class_id_name" class="form-control">
                                <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="ser" id="txt_ser" class="form-control">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">رقم الشركة المصنعة</label>
                            <div class="col-sm-3">
                                <input type="text"   data-val="true"   data-val-required="حقل مطلوب" name="serial" id="txt_serial" class="form-control">
                                <span class="field-validation-valid" data-valmsg-for="serial" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <?php if( HaveAccess($serial_url)){?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php } ?>
                            <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                        </div>
                    </div>
                </form>
            </div> <!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



<?php
$shared_js = <<<SCRIPT
 function print(){
           _showReport('$rep_url/'+{$rs['FILE_ID']});


if ('{$class_code_ser}' != '0'){
                       get_data('{$print_code_url}',{class_code_ser:{$class_code_ser}},function(data){
                    if(data =='1')
                      success_msg('رسالة','تم طباعة كود العهدة بنجاح ..');
                        },'html');
                               }
    }

    function setcode(){
    get_data('{$code_url}',{id:{$rs['FILE_ID']}},function(data){
                    if(data =='1')
                     success_msg('رسالة','تم تكويد العهدة بنجاح ..');
                       get_to_link(window.location.href);
                    },'html');
    }

     function class_codes_get(id){
        get_data('{$get_code_url}',{id:id},function(data){
            $.each(data, function(i,item){

                $('#txt_class_code_serr').val('');
                $('#txt_class_id_name').val( '');
                $('#txt_ser').val( '');
                $('#txt_serial').val( '');

                $('#txt_class_code_serr').val(item.BARCODE);
                $('#txt_class_id_name').val( item.CLASS_ID+':'+item.CLASS_ID_NAME);
                $('#txt_ser').val( item.SER);
                $('#txt_serial').val( item.SERIAL);


                $('#{$TB_NAME2}_from').attr('action','{$serial_url}');

                resetValidation($('#{$TB_NAME2}_from'));
                $('#{$TB_NAME2}Modal').modal();
            });
        });
    }

     $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME2}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            container.html(data);
            $('#{$TB_NAME2}Modal').modal('hide');
        },"html");
    });
    
    function adopt(){
       get_data('{$adopt_url}',{id:{$fid}},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم اعتماد العهدة بنجاح ..');
                            reload_Page();
                    },'html');
                    }
SCRIPT;

$scripts = <<<SCRIPT
<script>
  {$shared_js}
  </script>

SCRIPT;
sec_scripts($scripts);



