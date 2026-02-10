<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 10/02/15
 * Time: 09:57 ص
 *
 */
$MODULE_NAME= 'archive';
$TB_NAME= 'archive_tb';
$get_file_type = $this->uri->segment(4);
$file_no = $this->uri->segment(5);
$back_url=base_url("$MODULE_NAME/archive"); //$action
$post_url= base_url("$MODULE_NAME/".($action == 'index'?'create':$action));
$edit_url= base_url("$MODULE_NAME/edit");
$get_url= base_url("$MODULE_NAME/get_id/");
$attachment_data_url= base_url("$MODULE_NAME/attachment_get");
$delete_details_url= base_url("$MODULE_NAME/delete_details");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
if(!isset($result))
    $result= array();
$HaveRs = count($result) > 0;
//print_r( $result);
$rs =$HaveRs? $result[0] : $result;
$echo_type=$HaveRs? $rs['FILE_TYPE'] : $get_file_type;
//echo $echo_type;
$is_archive=$HaveRs? $rs['IS_ARCHIVE'] : 0;
$file_type=$HaveRs? $rs['FILE_TYPE'] : 0;

$account_type=$HaveRs? $rs['ACCOUNT_TYPE']: 0;
$branch=$HaveRs? $rs['FILE_BRANCH']: 0;

echo AntiForgeryToken();
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

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الملف</label>
                    <div>
                        <input type="text" readonly id="txt_file_id" value="<?= $HaveRs? $rs['FILE_ID'] : "" ?>" class="form-control">
                        <input type="hidden" name="file_id" id="h_file_idd" value="<?= $HaveRs? $rs['FILE_ID'] : "" ?>">

                    </div>
                </div>
                <div class="form-group  col-sm-1">
                    <label class="control-label">المقر</label>
                    <div>
                  <select type="text"   name="branch" id="dp_branch" class="form-control" >
                      <?php if($is_archive==1){?>
                          <?php foreach($branches as $row) :?>
                              <?php if ($branch==$row['NO']) {?>
                                  <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php } ?>
                          <?php endforeach; ?>
                      <?php } else { ?>
                         <?php foreach($branches as $row) :?>
                                <option <?= $HaveRs?($rs['FILE_BRANCH'] ==$row['NO']?'selected':''):'' ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?> <?php } ?>
                        </select>    </div>
                </div>
                <div class="form-group  col-sm-1">
                    <label class="control-label">نوع المعاملة</label>
                    <div>
                      <select type="text"  id="dp_file_type" class="form-control"   name="file_type"   >
                            <?php if($is_archive==1){?>
                                <?php foreach($file_type1 as $row) :?>
                                    <?php if ($file_type==$row['CON_NO']) {?>
                                        <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>

                                    <?php } ?>
                                <?php endforeach; ?>
                            <?php } else { ?>
                                <?php foreach($file_type1 as $row) : ?>
                                    <option <?= $echo_type==$row['CON_NO']?'selected':''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                 <?php endforeach; ?> <?php } ?>
            </select>
                        </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المعاملة</label>
                    <div>
                        <input type="text" <?php if($is_archive==1) echo "readonly"; ?>  id="txt_file_no" name="file_no" value="<?= $HaveRs? $rs['FILE_NO'] : $file_no ?>" class="form-control">
                               </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">نوع المستفيد</label>
                    <div >
                        <select name="account_type"  id="dp_account_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                            <?php if($is_archive==1){?>
                                <?php foreach($customer_type as $row) :?>
                                    <?php if ($account_type==$row['CON_NO']) {?>
                                        <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>

                                    <?php } ?>
                                     <?php endforeach; ?>
                            <?php } else { ?>
                                <?php foreach($customer_type as $row) :?>
                                    <option <?= $HaveRs?($rs['ACCOUNT_TYPE'] ==$row['CON_NO']?'selected':''):'' ?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?> <?php } ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="account_type" data-valmsg-replace="true"></span>

                    </div>

                </div>
                <div class="form-group col-sm-4">
                    <label class="control-label">المورد</label>
                    <div>
                        <input name="customer_name" data-val="true"     class="form-control" readonly id="txt_customer" value="<?= $HaveRs? $rs['CUSTOMER_NAME'] : "" ?>"   >
                        <input type="hidden" name="customer" value="<?= $HaveRs? $rs['CUSTOMER'] : "" ?>" id="h_txt_customer">
                        <span class="field-validation-valid" data-valmsg-for="customer" data-valmsg-replace="true"></span>

                    </div>

                </div>


                <div class="form-group  col-sm-2">
                    <label class="control-label">تاريخ المعاملة</label>
                    </label>
                    <div>
                        <input name="file_date" data-val="true"  <?php if($is_archive==1) echo "readonly"; ?>   value="<?= $HaveRs? $rs['FILE_DATE'] : "" ?>"   data-val-required="حقل مطلوب"   id="txt_file_date" data-type="date"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"  class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="file_date" data-valmsg-replace="true"></span>

                    </div>
                    </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-10">
                    <label class="control-label">الموضوع</label>
                    <div>
                        <textarea rows="6" cols="50" name="subject"  data-val="false"  data-val-required="حقل مطلوب"  id="txt_subject" class="form-control"><?= $HaveRs? $rs['SUBJECT'] : "" ?></textarea>
                        <span class="field-validation-valid" data-valmsg-for="subject" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group  col-sm-7">
                    <label class="control-label">البيان
                    </label>
                    <div>
                        <input name="note" value="<?= $HaveRs? $rs['NOTE'] : "" ?>" id="txt_note" class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>

                    </div>
                </div>
                <hr>
                <div style="clear: both;">

                    <?php
                    if ($action !='index')
                    echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details",$HaveRs?$rs['FILE_ID']:0); ?>


                </div>

                <div class="modal-footer">
                   <?php if (($isCreate || ( isset($can_edit)?$can_edit:false))) : ?>

                       <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php  endif; ?>
                    </div>











            </div>

            </form>
        </div>
    </div>

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
                    <button  onclick="get_to_link(window.location.href);" type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php
$shared_js = <<<SCRIPT
<script>

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(!isNaN(data)){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                //alert('a'+data);
                get_to_link('{$get_url}'+'/'+data);
            }else{
                danger_msg('تحذير..',data);
            }



        },"html");
    });
function attachment_form_1(url){
attachment_form(url);
    $('#uploadModal').modal();
}
  function delete_details(id){
        var url = '{$delete_details_url}';
if(confirm('هل تريد بالتأكيد حذف سجلات وحذف تفاصيلها ؟!!')){
  var values= {id:id};
        get_data('{$delete_details_url}',values ,function(data){
if (data==1){
    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
     get_to_link(window.location.href);
   } else{
         danger_msg('تحذير..',data);
}
        },'html');

        }
    }
    function selectAccount(obj){
         var _type =$('#dp_account_type').val();

            if(_type == 1)
                _showReport('$select_accounts_url/'+$(obj).attr('id') );
            if(_type == 2)
                _showReport('$customer_url/'+$(obj).attr('id')+'/1');

    }
 $('#txt_customer').bind("focus",function(e){

        selectAccount(this);

        });

        $('#txt_customer').click(function(e){

            selectAccount(this);

        });
</script>
SCRIPT;
sec_scripts($shared_js);