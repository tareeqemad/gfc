<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/02/15
 * Time: 10:16 ص
 */

$gfc_domain= gh_gfc_domain();
$MODULE_NAME= 'purchases';
$TB_NAME= 'purchase_order';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index?order_purpose=$order_purpose");
if($quote){
    $back_url.= '&quote=1';
}
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action)).'?order_purpose='.$order_purpose;
$create_url =base_url("$MODULE_NAME/$TB_NAME/create").'?order_purpose='.$order_purpose;
$edit_approved_url =base_url("$MODULE_NAME/$TB_NAME/edit_approved").'?order_purpose='.$order_purpose;
$edit_quote_url =base_url("$MODULE_NAME/$TB_NAME/edit_quote").'?order_purpose='.$order_purpose;
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$adopt_reversion_url= base_url("$MODULE_NAME/$TB_NAME/adopt_reversion");
$reversion_case_url= base_url("$MODULE_NAME/$TB_NAME/reversion_case");
$select_branch_url= base_url("$MODULE_NAME/$TB_NAME/select_branch");
$section_data_url= base_url("$MODULE_NAME/$TB_NAME/public_get_section_data");
$quote_case_url= base_url("$MODULE_NAME/$TB_NAME/quote_case_");
$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$get_count_attachment_url =base_url('attachments/attachment/public_count_attachment');
$print_url=  'http://itdev:801/gfc.aspx?data='.get_report_folder().'&' ;
$div_order_url= base_url("$MODULE_NAME/$TB_NAME/divide_purchase_order");
$adv_url= base_url("$MODULE_NAME/$TB_NAME/add_adv");
$civile_data_url= base_url("$MODULE_NAME/$TB_NAME/public_get_det_items");
$class_data_url= base_url("$MODULE_NAME/$TB_NAME/public_get_details");
/*mtaqia*/
$report_url = base_url("JsperReport/showreport?sys=purchases");
$report_sn= report_sn();
/* ----- */

//public_get_merge_purchase_order
if($order_purpose==1)
    $DET_TB_NAME= 'public_get_details';
elseif($order_purpose==2)
    $DET_TB_NAME= 'public_get_det_items';
else
    die('show');

$isCreate =isset($order_data) && count($order_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $order_data[0];
$items_hidden='hidden';
$civil_hidden='hidden';
if (count($rs) != 0)
{
    if ($rs['PURCHASE_ORDER_CLASS_TYPE'] == 11)
    {
        $items_hidden='hidden';
        $civil_hidden='';
    }
    else
    {
        $items_hidden='';
        $civil_hidden='hidden';
    }
}
//die;

$can_adopt_reversion=0;
if( HaveAccess($adopt_reversion_url) and !$isCreate and $rs['ADOPT'] >=10 and $rs['ADOPT'] <=60 ){
    $url_array= array($adopt_url.'20',$adopt_url.'30',$adopt_url.'40',$adopt_url.'45',$adopt_url.'50',$adopt_url.'60',$adopt_url.'70');
    $adopt_array= array(15,20,30,40,45,50,60);
    for($i=0;$i<count($adopt_array);$i++){
        if(HaveAccess($url_array[$i]) and $adopt_array[$i]==$rs['ADOPT']){
            $can_adopt_reversion= 1;
            break;
        }
    }
}

$can_edit_approved=0;
if(!$isCreate and HaveAccess($edit_approved_url) and $rs['ADOPT']==15){
    $post_url= $edit_approved_url;
    $can_edit_approved=1;
}

$can_edit_quote=0;
if($HaveRs and $quote==1 and HaveAccess($edit_quote_url) and $rs['ADOPT']==70 and $rs['DESIGN_QUOTE_CASE']==1){
    $post_url= $edit_quote_url;
    $can_edit_quote=1;
}

$can_select_branch=0;
if(HaveAccess($select_branch_url) and isset($branch) and $branch==1 ){
    $can_select_branch=1;
}
$purchase_type=isset($rs['PURCHASE_TYPE'])? $rs['PURCHASE_TYPE'] :0;
$merge_purchase_order_id=isset($rs['MERGE_PURCHASE_ORDER_ID'])? $rs['MERGE_PURCHASE_ORDER_ID'] :0;
$adoptt=isset($rs['ADOPT'])? $rs['ADOPT'] :0;
$purchase_type=isset($rs['PURCHASE_TYPE'])? $rs['PURCHASE_TYPE'] :-1;
$merge_purchase_order=isset($rs['MERGE_PURCHASE_ORDER'])? $rs['MERGE_PURCHASE_ORDER'] :'';

$purchase_order_id_=($HaveRs)?$rs['PURCHASE_ORDER_ID']:0;
$adopt_=($HaveRs)?$rs['ADOPT']:0;
$quote_=($HaveRs)?$quote:0;
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if( 0 and HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
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
                    <label class="control-label">رقم المسلسل</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['PURCHASE_ORDER_ID']:''?>" id="txt_purchase_order_id" class="form-control" />
                        <input type="hidden" value="<?=$order_purpose?>" name="order_purpose" id="h_order_purpose">
                        <?php if (( isset($can_edit)?$can_edit:false) or $can_edit_quote or $can_edit_approved ) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['PURCHASE_ORDER_ID']:''?>" name="purchase_order_id" id="h_purchase_order_id">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم طلب الشراء</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['PURCHASE_ORDER_NUM']:''?>" readonly id="txt_purchase_order_num" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الطلب </label>
                    <div>
                        <select id="dp_adopt" disabled class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($adopts as $row) :?>
                                <option <?=$HaveRs?($rs['ADOPT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الفرع </label>
                    <div>
                        <select name="branch" id="dp_branch" <?=($can_select_branch)?'':'disabled'?> class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">

                    <label class="control-label">نوع الطلب </label>
                    <div>
                        <?php if ($purchase_type<=3) { ?>
                            <select name="purchase_type" id="dp_purchase_type" disabled class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($purchase_types as $row) :?>
                                    <option <?=$HaveRs?($rs['PURCHASE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php }
                        else  if ($purchase_type==4) {
                            echo  "<a href='$get_url/$merge_purchase_order_id/edit' target='_blank'>تم دمجه </a>";
                        }
                        else  if ($purchase_type==5) { echo "مقسم"; } ?>
                        <input type="hidden" value="<?=$HaveRs?$rs['TOTAL_COST']:''?>" id="h_total_cost" />
                    </div>
                </div>

                <?php if(!$isCreate and $rs['ADOPT']>=60){
                    if($rs['PURCHASE_TYPE']==1)
                        $style='';
                    else
                        $style='display: none';
                    ?>
                    <div class="form-group col-sm-1" id="div_quote_curr_id" style="<?=$style?>">
                        <label class="control-label">العملة</label>
                        <div>
                            <select id="dp_quote_curr_id" class="form-control" >
                                <?php foreach($currencies as $row) :?>
                                    <option <?=$HaveRs?($rs['QUOTE_CURR_ID']==$row['CURR_ID']?'selected':''):''?> value="<?=$row['CURR_ID']?>" ><?=$row['CURR_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الاصناف </label>
                    <div>
                        <select name="purchase_order_class_type" id="dp_purchase_order_class_type" class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($purchase_order_class_types as $row) :?>
                                <option <?=$HaveRs?($rs['PURCHASE_ORDER_CLASS_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2  civil_class hidden">
                    <label class="control-label">أعمال مدنية</label>
                    <div>
                        <select name="civil_type" id="dp_civil_type" class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($civil_type as $row) :?>
                                <option <?=$HaveRs?($rs['CIVIL_TYPE']==$row['CLASS_ID']?'selected':''):''?> value="<?=$row['CLASS_ID']?>" ><?=$row['CLASS_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if($HaveRs and $rs['ADOPT']>=60 ){ ?>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> لجنة فتح المظاريف</label>
                        <div>
                            <select name="committee_envelopes" id="dp_committee_envelopes" class="form-control" >
                                <?=($HaveRs)?'<option value="">_________</option>':''?>
                                <?php foreach($committee_envelopess as $row) :?>
                                    <option <?=$HaveRs?($rs['COMMITTEE_ENVELOPES']==$row['COMMITTEES_ID']?'selected':''):''?> value="<?=$row['COMMITTEES_ID']?>" ><?=$row['COMMITTEES_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> لجنة البت والترسية</label>
                        <div>
                            <select name="committee_award" id="dp_committee_award" class="form-control" >
                                <?=($HaveRs)?'<option value="">_________</option>':''?>
                                <?php foreach($committee_awards as $row) :?>
                                    <option <?=$HaveRs?($rs['COMMITTEE_AWARD']==$row['COMMITTEES_ID']?'selected':''):''?> value="<?=$row['COMMITTEES_ID']?>" ><?=$row['COMMITTEES_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php
                }elseif(0){
                    if($HaveRs){
                        $committee_envelopes_val= $rs['COMMITTEE_ENVELOPES'];
                        $committee_awards_val= $rs['COMMITTEE_AWARD'];
                    }else{
                        foreach($committee_envelopess as $row){
                            $committee_envelopes_val= $row['COMMITTEES_ID'];
                        }
                        foreach($committee_awards as $row){
                            $committee_awards_val= $row['COMMITTEES_ID'];
                        }
                    }
                    echo '<input type="hidden" name="committee_envelopes" value="'.$committee_envelopes_val.'" />';
                    echo '<input type="hidden" name="committee_award" value="'.$committee_awards_val.'" />';
                }
                ?>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة التحويل </label>
                    <div>
                        <select id="dp_committee_case" disabled class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($committee_cases as $row) :?>
                                <option <?=$HaveRs?($rs['COMMITTEE_CASE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTES']:''?>" name="notes" id="txt_notes" class="form-control" />
                    </div>
                </div>

                <?php if ( !$isCreate and HaveAccess($reversion_case_url) ) : ?>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> الجهة المرجع اليها </label>
                        <div>
                            <select id="dp_reversion_case" class="form-control" >
                                <?php foreach($adopts as $row) :
                                    if( $row['CON_NO'] < $rs['ADOPT']){ ?>
                                        <option value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php } endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( !$isCreate ) : ?>
                    <div class="form-group col-sm-9">
                        <label class="control-label">ملاحظة الاعتماد / الارجاع</label>
                        <div>
                            <input type="text" name="adopt_note" id="txt_adopt_note" class="form-control" />
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( !$isCreate and $merge_purchase_order !='' ) : ?>
                    <div class="form-group col-sm-6">
                        <label class="control-label">الطلبات المدموجة</label>
                        <div>
                            <?=$merge_purchase_order?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if($HaveRs and $quote!=1){ ?>
                    <fieldset>
                        <legend>بيانات الموزانة</legend>
                        <!--
                    <div class="form-group col-sm-2">
                        <label class="control-label">الفصل</label>
                        <div>
                            <select id="dp_section_no" name="section_no" class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($sections as $row) :?>
                                    <option <?=$HaveRs?($rs['SECTION_NO']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المقدر</label>
                        <div>
                            <input readonly type="text" id="txt_section_total" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المحقق</label>
                        <div>
                            <input readonly type="text" id="txt_section_balance" class="form-control" />
                        </div>
                    </div>-->

                        <div class="tbl_container">
                            <table class="table"  data-container="container">
                                <thead>

                                <tr>
                                    <th>#</th>
                                    <th>اسم الفصل</th>
                                    <th>المقدر</th>
                                    <th>المحقق</th>
                                    <th>إجمالي مبالغ طلبات الشراء</th>
                                </tr></thead>
                                <?php foreach($balances as $row) :?>
                                    <tr>
                                        <td><?=$row['S_T_SER']?></td>
                                        <td><?=$row['SNAME']?></td>
                                        <td><?=$row['TOTAL']?></td>
                                        <td><?=$row['BALANCE']?></td>
                                        <td><?=$row['SUM_PURCHASE_ORDERS']?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </fieldset>
                <?php } ?>

                <?php if($HaveRs and $quote==1){ ?>
                    <fieldset>
                        <legend>عرض السعر</legend>

                        <div class="form-group col-sm-1">
                            <label class="control-label">العملة</label>
                            <div>
                                <select id="dp_quote_curr_id" name="quote_curr_id" class="form-control" >
                                    <?php foreach($currencies as $row) :?>
                                        <option <?=$HaveRs?($rs['QUOTE_CURR_ID']==$row['CURR_ID']?'selected':''):''?> value="<?=$row['CURR_ID']?>" ><?=$row['CURR_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">تاريخ بداية عرض السعر </label>
                            <div>
                                <input type="text" name="quote_start_date" value="<?=$HaveRs?$rs['QUOTE_START_DATE']:''?>" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_quote_start_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                                <span class="field-validation-valid" data-valmsg-for="quote_start_date" data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">تاريخ نهاية عرض السعر</label>
                            <div>
                                <input type="text" name="quote_end_date" value="<?=$HaveRs?$rs['QUOTE_END_DATE']:''?>" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_quote_end_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                                <span class="field-validation-valid" data-valmsg-for="quote_end_date" data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div style="clear: both"></div>

                        <?php if(0){ ?>
                            <div class="form-group col-sm-2">
                                <label class="control-label">شروط عرض الأسعار</label>
                                <div>
                                    <select multiple id="dp_conditions" class="form-control" >
                                        <?php foreach($conditions as $row) :?>
                                            <option value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="form-group col-sm-1">
                            <a style="width: 120px" href="javascript:;" onclick="javascript:show_conditions();" class="icon-btn">
                                <i class="glyphicon glyphicon-list"></i>
                                <div> شروط عرض الأسعار  </div>
                            </a>
                        </div>

                        <div class="form-group col-sm-9">
                            <div>
                                <textarea rows="3" name="quote_condition" id="txt_quote_condition" class="form-control" ><?=$HaveRs?$rs['QUOTE_CONDITION']:''?></textarea>
                            </div>
                        </div>

                        <div class="modal fade" id="conditions_Modal">
                            <div class="modal-dialog" style="width: 900px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title">شروط عرض السعر </h4>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table" data-container="container">
                                            <thead>
                                            <tr>
                                                <th>م</th>
                                                <th>الشرط</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach($conditions as $row){
                                                echo"
                                                <tr>
                                                    <td>".$row['CON_NO']."</td>
                                                    <td><div style='text-align: right'>
                                                        <label style='font-weight: normal'>
                                                            <input style='margin-left: 5px' class='cb_condition' value='".$row['CON_NAME']."' type='checkbox'>
                                                            ".$row['CON_NAME']."
                                                        </label>
                                                    </div></td>
                                                </tr>";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="javascript:add_conditions();" class="btn btn-primary" data-dismiss="modal">تم</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                        <div class="modal fade" id="adv_Modal">
                            <div class="modal-dialog" style="width: 900px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title">إعلان خارجي</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group col-sm-4">
                                            <label class="control-label">الإعلان</label>
                                            <div>

                                                <select name="adv_no" id="dp_adv_no" class="form-control valid">
                                                    <option value="">_________</option>
                                                    <?php foreach($advs as $row) :?>
                                                        <option <?=$HaveRs?($rs['ADVER']==$row['ADVER_NO']?'selected':''):''?> value="<?=$row['ADVER_NO']?>" ><?=$row['TITLE']?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="control-label">رقم المناقصة</label>
                                            <input name="serial" class="form-control" id="txt_serial" value="<?=$HaveRs?$rs['SERIAL']:''?>">
                                            <div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <?PHP if(HaveAccess($adv_url)){?>
                                            <button type="button" onclick="javascript:add_adv();" class="btn btn-primary" data-dismiss="modal">حفظ</button>
                                        <?php } ?>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->


                    </fieldset>
                <?php } ?>

                <?php if($HaveRs){ ?>
                    <hr>
                    <div style="clear: both;">
                        <a style="width: 100px; margin-left: 10px" href="javascript:;" onclick="javascript:show_adopts();" class="icon-btn">
                            <i class="glyphicon glyphicon-list"></i>
                            <div> بيانات الاعتمادات </div>
                        </a>
                        <?php
                        if($quote!=1 or true){
                            echo modules::run('settings/notes/public_get_page',$HaveRs?$rs['PURCHASE_ORDER_ID']:0,'purchase_order');
                            echo modules::run('attachments/attachment/index',$rs['PURCHASE_ORDER_ID'],'purchase_order');
                            if( !$isCreate and $quote and $rs['PURCHASE_TYPE'] == 3 and ( $rs['DESIGN_QUOTE_CASE']==5 or ( $rs['DESIGN_QUOTE_CASE']==4 and HaveAccess($quote_case_url.'5') )  ) ){
                                echo '<span id="manager_attachment">'. modules::run('attachments/attachment/index',$rs['PURCHASE_ORDER_ID'],'purchase_order_manager') .'</span>';
                            }
                        }
                        ?>
                        <a style="width: 100px; margin-left: 10px" href="javascript:;" onclick="javascript:show_adv();" class="icon-btn">
                            <i class="glyphicon glyphicon-certificate"></i>
                            <div> إعلان خارجي  </div>
                        </a>
                        <span id="quote"><?php echo   modules::run('attachments/attachment/index',$rs['PURCHASE_ORDER_ID'],'quote'); ?></span>
                        <span id="purchase_book"><?php echo modules::run('attachments/attachment/index',$rs['PURCHASE_ORDER_ID'],'purchase_book'); ?></span>

                    </div>
                <?php } ?>

                <div style="clear: both"></div>
                <input type="hidden" id="h_data_search" />

                <div id="content_show" class="<?=$items_hidden?>" >
                    <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", ($HaveRs)?$rs['PURCHASE_ORDER_ID']:0, ($HaveRs)?$rs['ADOPT']:0, ($HaveRs)?$quote:0 ); ?>

                </div>
                <div id="content_items" class="<?=$civil_hidden?>" >
                    <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_det_items", ($HaveRs)?$rs['PURCHASE_ORDER_ID']:0, ($HaveRs)?$rs['ADOPT']:0, ($HaveRs)?$quote:0,($HaveRs)?$rs['PURCHASE_ORDER_CLASS_TYPE']:0 ); ?>

                </div>
            </div>

            <div class="modal-footer">
                <?php
                if (HaveAccess($div_order_url) and ($purchase_type !=0)  and  ($adoptt==70)and ($rs['DESIGN_QUOTE_CASE']==1) and ($rs['DESIGN_QUOTE_USER']=='') and ($rs['COMMITTEE_CASE']==1) ) : //if ( $can_adopt_reversion ) : ?>
                    <button type="button" onclick='javascript:divide_order();' class="btn btn-danger">تقسيم طلب الشراء</button>
                <?php  endif; ?>

                <?php if ( HaveAccess($post_url) && ($isCreate || ( $adoptt==10  and ($purchase_type !=0)  and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( !$isCreate  and $adoptt!=0 ) : ?>
                    <?php if ( $quote==1 ) : ?>
                        <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                    <?php endif; ?>
                    <?php if ( $quote!=1 ) : ?>
                        <button type="button" id="print_rep_portrait" onclick="javascript:print_rep_portrait();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة عمودي </button>
                        <button type="button" id="print_rep_landscape" onclick="javascript:print_rep_landscape();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة افقي </button>
                    <?php endif; ?>
                    <button type="button" onclick="$('#details_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                <?php endif; ?>

                <?php if ( !$isCreate  and $adoptt!=0 and $quote ) : ?>
                    <button type="button" id="print_rep2" onclick="javascript:print_rep2();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة 2</button>
                <?php endif; ?>

                <?php if ( 0 and $can_edit_approved  and ($purchase_type !=0)  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ الكمية المقبولة</button>
                <?php endif; ?>

                <?php if ( $purchase_type<=3 and   $can_edit_quote  and ($purchase_type !=0)  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary"> حفظ عرض السعر </button>
                <?php endif; ?>

                <?php if ($isCreate and 0 ): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php endif; ?>


                <?php if ( HaveAccess($adopt_url.'15') and !$isCreate  and $rs['ADOPT']==10  and $rs['BRANCH']>0 and ($purchase_type !=0) ) :  ?>
                    <button type="button" id="btn_adopt_15" onclick='javascript:adopt(15);' class="btn btn-success">   اعتماد المواصفات و المقاييس  </button>
                <?php endif; ?>
                <?php if ( HaveAccess($adopt_url.'20') and !$isCreate  and $rs['ADOPT']==15  and $rs['BRANCH']>0 and ($purchase_type !=0) ) :   ?>
                    <button type="button" id="btn_adopt_20" onclick='javascript:adopt(20);' class="btn btn-success"> اعتماد الجهة الطالبة/المقر  </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'30') and !$isCreate and $rs['ADOPT']==20 and $rs['BRANCH']>0 and ($purchase_type !=0)) : ?>
                    <button type="button" id="btn_adopt_30" onclick='javascript:adopt(30);' class="btn btn-success">  اعتماد المخازن </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'40') and !$isCreate and $rs['ADOPT']==30 and $rs['BRANCH']>0 and ($purchase_type !=0)) : ?>
                    <button type="button" id="btn_adopt_40" onclick='javascript:adopt(40);' class="btn btn-success">  اعتماد الموازنة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'45') and !$isCreate and $rs['ADOPT']==40 and $rs['BRANCH']>0 and ($purchase_type !=0)) : ?>
                    <button type="button" id="btn_adopt_45" onclick='javascript:adopt(45);' class="btn btn-success">  اعتماد مدير الادارة المالية  </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'50') and !$isCreate and $rs['ADOPT']==45 and $rs['IS_ASSISTENT_MANAGER']==1 and $rs['BRANCH']>0 and ($purchase_type !=0) and $rs['TOTAL_COST']>=3001) : ?>
                    <button type="button" id="btn_adopt_50" onclick='javascript:adopt(50);' class="btn btn-success">  اعتماد مدير عام الشركة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'60') and !$isCreate and ($rs['ADOPT']==50 or ($rs['ADOPT']==45 and $rs['IS_ASSISTENT_MANAGER']==1 and $rs['TOTAL_COST']<3001) )and $rs['BRANCH']>0 and ($purchase_type !=0)) : ?>
                    <button type="button" id="btn_adopt_60" onclick='javascript:adopt(60);' class="btn btn-success">  اعتماد اللوازم والخدمات </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'70') and !$isCreate and $rs['ADOPT']==60 and $rs['BRANCH']>0 and ($purchase_type !=0) ) : ?>
                    <button type="button" id="btn_purchases_adopt" onclick='javascript:adopt(70);' class="btn btn-success">  اعتماد المشتريات </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'0') and !$isCreate and $rs['ADOPT']!=0 and $rs['BRANCH']>0 and ($purchase_type !=0) ) : ?>
                    <button type="button" id="btn_adopt_0" onclick='javascript:adopt(0);' class="btn btn-danger"> إلغاء طلب شراء </button>
                <?php endif; ?>

                <?php if ( $can_adopt_reversion and ($purchase_type !=0) ) : ?>
                    <button type="button" onclick='javascript:adopt_reversion();' class="btn btn-danger">ارجاع</button>
                <?php endif; ?>


                <?php if ( HaveAccess($quote_case_url.'2') and !$isCreate and $quote and $rs['DESIGN_QUOTE_CASE']==1 and $rs['PURCHASE_TYPE'] != 1 and ($purchase_type !=0) ) : ?>
                    <button type="button" onclick='javascript:quote_case(2);' class="btn btn-success"> اعتماد المشتريات لعرض السعر </button>
                <?php endif; ?>

                <?php if ( HaveAccess($quote_case_url.'3') and !$isCreate and $quote and $rs['DESIGN_QUOTE_CASE']==2 and $rs['PURCHASE_TYPE'] == 3 and ($purchase_type !=0) ) : ?>
                    <button type="button" onclick='javascript:quote_case(3);' class="btn btn-success"> اعتماد الجهة الطالبة لعرض السعر </button>
                <?php endif; ?>

                <?php if ( HaveAccess($quote_case_url.'4') and !$isCreate and $quote and $rs['DESIGN_QUOTE_CASE']==3 and $rs['PURCHASE_TYPE'] == 3 and ($purchase_type !=0)) : ?>
                    <button type="button" onclick='javascript:quote_case(4);' class="btn btn-success"> اعتماد الادارة المالية لعرض السعر </button>
                <?php endif; ?>

                <?php if ( HaveAccess($quote_case_url.'5') and !$isCreate and $quote and $rs['DESIGN_QUOTE_CASE']==4 and $rs['IS_ASSISTENT_MANAGER']==1 and $rs['PURCHASE_TYPE'] == 3 and ($purchase_type !=0) ) : ?>
                    <button type="button" onclick='javascript:quote_case(5);' class="btn btn-success"> اعتماد مدير عام الشركة لعرض السعر </button>
                <?php endif; ?>

                <?php if ($purchase_type<=3 and  HaveAccess($adopt_url.'600') and !$isCreate and $rs['ADOPT']==70 and $rs['DESIGN_QUOTE_CASE']==1 and ($purchase_type !=0) ) : ?>
                    <button type="button" onclick='javascript:adopt(600);' class="btn btn-danger"> ارجاع للمشتريات </button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>


<div class="modal fade" id="adopts_Modal">
    <div class="modal-dialog" style="width: 900px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات الاعتمادات</h4>
            </div>
            <div class="modal-body">
                <table class="table" data-container="container">
                    <thead>
                    <tr>
                        <th>م</th>
                        <th>حالة طلب الشراء</th>
                        <th>المستخدم</th>
                        <th>التاريخ</th>
                        <th>الملاحظة</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    echo "
                        <tr>
                            <td>10</td>
                            <td>ادخال الطلب</td>
                            <td>".$rs["ENTRY_USER_NAME"]."</td>
                            <td>".$rs["ENTRY_DATE"]."</td>
                            <td></td>
                        </tr>";
                    for($i=2;$i<=count($adopts);$i++){
                        //   print_r($adopts);
                        $adopt_no=$adopts[$i-1]['CON_NO'];
                        echo"
                        <tr>
                            <td>".$adopt_no."</td>
                            <td>".$adopts[$i-1]['CON_NAME']."</td>
                            <td>".$rs["ADOPT_USER_".$adopt_no."_NAME"]."</td>
                            <td>".$rs["ADOPT_DATE_$adopt_no"]."</td>
                            <td style='max-width: 300px'>".$rs["ADOPT_NOTE_$adopt_no"]."</td>
                        </tr>";
                    }
                    echo "
                        <tr>
                            <td>600</td>
                            <td> إرجاع من عرض سعر</td>
                            <td>".$rs["ADOPT_USER_600_NAME"]."</td>
                            <td>".$rs["ADOPT_DATE_600"]."</td>
                            <td>".$rs["ADOPT_NOTE_600"]."</td>
                        </tr>";
                    ?>
                    </tbody>
                </table>


                <table class="table" data-container="container">
                    <thead>
                    <tr>
                        <th>م</th>
                        <th>حالة عرض السعر</th>
                        <th>المستخدم</th>
                        <th>التاريخ</th>
                        <th>الملاحظة</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    echo "
                        <tr>
                            <td>1</td>
                            <td>تصميم العرض</td>
                            <td>".$rs["DESIGN_QUOTE_USER_NAME"]."</td>
                            <td>".$rs["DESIGN_QUOTE_DATE"]."</td>
                            <td></td>
                        </tr>";
                    for($i=2;$i<=count($design_quote_cases);$i++){
                        echo"
                        <tr>
                            <td>$i</td>
                            <td>".$design_quote_cases[$i-1]['CON_NAME']."</td>
                            <td>".$rs["QUOTE_CASE_USER_".$i."_NAME"]."</td>
                            <td>".$rs["QUOTE_CASE_DATE_$i"]."</td>
                            <td style='max-width: 300px'>".$rs["QUOTE_CASE_NOTE_$i"]."</td>
                        </tr>";
                    }
                    ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
$scripts = <<<SCRIPT
<script>

 $('#quote a div').text('مرفقات عرض السعر');
 $('#purchase_book a div').text('مرفقات كراسة العطاء');
 $('#manager_attachment a div').text('اعتمادات مجلس الادارة');
 
 if ($('#dp_purchase_order_class_type').val()==11)
     {
         $( ".civil_class" ).removeClass( "hidden" );
         var order_purpose=2;
         //$('#content_show').html('');
     }
 else 
     {
         var order_purpose=1;
     }
 
  $('#dp_purchase_order_class_type').select2().on('change',function(){
if ($('#dp_purchase_order_class_type').val()==11)
    {
        order_purpose=2;

       $( ".civil_class" ).removeClass( "hidden" );
         $( "#content_items" ).addClass("hidden" );
         
       $( "#content_show" ).addClass("hidden" );
/*if($( ".civil_class_det tbody" ).length >1)
    {
        $( ".items_class_det tbody" ).empty();
        $( ".civil_class_det tbody" ).empty();
    }*/

      
    }
else 
    {
        order_purpose=1;
       // alert()
         $( ".civil_class" ).addClass( "hidden" ); 
         /* get_data('{$class_data_url}',{purchase_order_id:{$purchase_order_id_},adopt:{$adopt_},quote:{$quote_}} ,function(data){
            $('#content_show').html(data);
        },'html');*/
       $( "#content_items" ).addClass( "hidden" );
       $( "#content_show" ).removeClass("hidden" );
      /* if($( ".items_class_det tbody" ).length >1)
    {
        $( ".items_class_det tbody" ).empty();
        $( ".civil_class_det tbody" ).empty();
    }*/

    }
        });
 
   $('#dp_civil_type').select2().on('change',function(){
        /* get_data('{$civile_data_url}',{purchase_order_id:{$purchase_order_id_},adopt:{$adopt_},quote:{$quote_}} ,function(data){
            $('#content_show').html(data);
        },'html');*/
       /* var civil_type=$( "#dp_civil_type" ).val();
                  get_data('{$civile_data_url}',{purchase_order_id:{$purchase_order_id_},adopt:{$adopt_},quote:{$quote_},civil_type:civil_type} ,function(data){
            $('#content_items').html(data);
        },'html');*/
     /*if($( ".civil_class_det tbody" ).length >1)
    {
        $( ".items_class_det tbody" ).empty();
        $( ".civil_class_det tbody" ).empty();
    }*/
        var civil_type=$( "#dp_civil_type" ).val();
        $.post( '{$civile_data_url}', {purchase_order_id:{$purchase_order_id_},adopt:{$adopt_},quote:{$quote_},civil_type:civil_type}, function( data ) {
$('#content_items').html(data);
}, "html");
       $( "#content_items" ).removeClass( "hidden" );
       $( "#content_show" ).addClass("hidden" );



        });

    var count = 0;
     //var order_purpose=$order_purpose;
     

        

    var class_unit_json= {$class_unit};
    var select_class_unit= '';

    $.each(class_unit_json, function(i,item){
        select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    reBind();

    $('select[name="class_unit[]"]').append(select_class_unit);

    $('select[name="class_unit[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('#dp_section_no, select[name="class_unit[]"],#dp_adv_no,#dp_civil_type').select2();

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        if( $can_edit_quote )
            msg= 'هل تريد عرض السعر ؟!'

        if($can_edit_approved || confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            
            ajax_insert_update(form,function(data){
            
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data)+'/edit');
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                //    get_to_link(window.location.href);
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

        if(order_purpose==1)
             var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="ser[]" value="0" /> <input name="class[]" class="form-control" id="i_txt_class_id'+count+'" /> <input type="hidden" name="class_id[]" id="h_txt_class_id'+count+'" /></td>  <td><input name="class_name[]" readonly class="form-control" id="txt_class_id'+count+'" /></td><td> <input  name="section_no[]" readonly class="form-control" id="txt_section_no'+count+'" /></td> <td><input name="class_unit[]" disabled class="form-control" id="unit_name_txt_class_id'+count+'" /></td> <td><input name="amount[]" class="form-control" id="txt_amount'+count+'" /></td> <td></td> <td><input name="class_price[]" readonly class="form-control"  id="class_price_txt_class_id'+count+'" /></td> <td><input name="buy_price[]" readonly class="form-control" id="price_txt_class_id'+count+'" /></td><td></td> <td></td> <td></td> <td></td> <td><input name="note[]" class="form-control" id="txt_note'+count+'" /></td> <td><input name="order_colum[]" class="form-control" id="txt_order_colum'+count+'" /></td> <td></td></tr>';
        else if(order_purpose==2)
            var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="ser[]" value="0" /> <input name="item[]" class="form-control" id="txt_item'+count+'" /></td> <td><select name="class_unit[]" class="form-control" id="txt_class_unit'+count+'" /></td> <td><input name="amount[]" class="form-control" id="txt_amount'+count+'" /></td> <td></td> <td><input name="buy_price[]" class="form-control" id="price_txt_class_id'+count+'" /></td> <td></td> <td><input name="note[]" class="form-control" id="txt_note'+count+'" /></td> </tr>';
        $('#details_tb tbody').append(html);
        reBind(1);
    }
/*
    function AddRowWithData(id,name_ar,unit,price,unit_name){
        addRow();
        $('#h_txt_class_id'+(count)).val(id);
        $('#i_txt_class_id'+(count)).val(id);
        $('#txt_class_id'+(count)).val(name_ar);
        $('#unit_name_txt_class_id'+(count)).val(unit_name);
        $('#price_txt_class_id'+(count)).val(price);
        $('#class_price_txt_class_id'+(count)).val(price);
        $('#report').modal('hide');
    }
    */
function AddRowWithDataPurchase(id,name_ar,unit,unit_name,price,classes_prices_ser ,buy_price){
        addRow();
        $('#h_txt_class_id'+(count)).val(id);
        $('#i_txt_class_id'+(count)).val(id);
        $('#txt_class_id'+(count)).val(name_ar);
        $('#unit_name_txt_class_id'+(count)).val(unit_name);
        $('#price_txt_class_id'+(count)).val(buy_price);
        // $('#price_txt_class_id'+(count)).readonly();
        $('#class_price_txt_class_id'+(count)).val(price);
        $('#report').modal('hide');
    }

/*
    $('input[type="text"],body').bind('keydown', 'down', function() {
        addRow();
        return false;
    });
*/
    function reBind(s){
        if(s==undefined)
            s=0;
        if(order_purpose==1){
            $('input[name="class_name[]"]').click("focus",function(e){ 
                _showReport('$select_items_url/'+$(this).attr('id')+'/1'+$('#h_data_search').val() );
            });

            $('input[name="class[]"]').bind("focusout",function(e){
                var id= $(this).val();
                var class_id= $(this).closest('tr').find('input[name="class_id[]"]');
                var name= $(this).closest('tr').find('input[name="class_name[]"]');
                var unit= $(this).closest('tr').find('input[name="class_unit[]"]');
                var amount= $(this).closest('tr').find('input[name="amount[]"]');
                var buy_price= $(this).closest('tr').find('input[name="buy_price[]"]');
                var class_price= $(this).closest('tr').find('input[name="class_price[]"]');
              
                if(id==''){
                    class_id.val('');
                    name.val('');
                    unit.val('');
                    buy_price.val('');
                    class_price.val('');
                   
                    
                    return 0;
                }
                get_data('{$get_class_url}',{id:id, type:1},function(data){
                    if (data.length == 1){
                        var item= data[0];
                        class_id.val(item.CLASS_ID);
                        name.val(item.CLASS_NAME_AR);
                        unit.val(item.UNIT_NAME);
                        buy_price.val(item.BUY_PRICE);
                        class_price.val(item.CLASS_PURCHASING);
                        
                        amount.focus();
                    }else{
                        class_id.val('');
                        name.val('');
                        unit.val('');
                        buy_price.val('');
                        class_price.val('');
                     
                    }
                });
            });

            $('input[name="class[]"]').bind('keyup', '+', function(e) {
                $(this).val('');
                var class_name= $(this).closest('tr').find('input[name="class_name[]"]');
                actuateLink(class_name);
            });
        }else if(order_purpose==2){
            if(s){
                $('select#txt_class_unit'+count).append('<option></option>'+select_class_unit).select2();
            }
        }
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

    $('#details_tb #total_cost').text( num_format(parseFloat($('#h_total_cost').val())) );
    $('#details_tb #total_tax').text( num_format(parseFloat( {$rs['TOTAL_TAX']} )) );
    $('#details_tb #total_cost_with_tax').text( num_format(parseFloat( {$rs['TOTAL_COST_WITH_TAX']} )) );

/*
    if('$ _purchase_type'!='' && $ _purchase_type > 0 && ('$ rs['PURCHASE_TYPE']').length !=1 )
        $('#dp_purchase_type').val($ _purchase_type);

    if('{_$ rs['PURCHASE_TYPE']}'!='' && '{_$ rs['PURCHASE_TYPE']}' > 0)
        $('#dp_purchase_type').val({_$ rs['PURCHASE_TYPE']});
*/
    if( $('#btn_purchases_adopt').text() != '' )
        $('#dp_purchase_type').prop('disabled',0);

    $('#dp_purchase_type').change(function(){
        if($(this).val()==1){
            $('#div_quote_curr_id').show();
            $('#dp_committee_envelopes, #dp_committee_award').val('');
        }else{
            $('#div_quote_curr_id').hide();
        }
    });

    if( $can_edit_approved )
        $('input[name="approved[]"]').prop('readonly',0);


    var btn__= '';
    $('#btn_adopt_15,#btn_adopt_20,#btn_adopt_30,#btn_adopt_40,#btn_adopt_45,#btn_adopt_50,#btn_adopt_60,#btn_purchases_adopt,#btn_adopt_0').click( function(){
        btn__ = $(this);
    });
    
    function adopt(no){
        if(no==70 && $('#dp_purchase_type').val()=='' ){
            danger_msg('تحذير..','يجب اختيار نوع الطلب..');
            return false;
        }
        if(no==70 && $('#dp_purchase_type').val()!=1 && ( $('#dp_committee_envelopes').val()=='' || $('#dp_committee_award').val()=='' ) ){
            danger_msg('تحذير..','يجب اختيار اللجان..');
            return false;
        }
        if(no==40 && $('#dp_section_no').val()=='' ){
            danger_msg('تحذير..','يجب اختيار الفصل ..');
            return false;
        }
        var msg= '';
        if(no==600){
            if( $('#txt_adopt_note').val() =='' ){
                danger_msg('تحذير..','ادخل بيان الارجاع');
                return false;
            }
            msg= 'هل تريد ارجاع الطلب للمشتريات ؟!';
        }
        else if (no==0)
           msg= 'هل تريد إلغاء الطلب ؟';
        else
            msg= 'هل تريد اعتماد الطلب ؟!';

        if(confirm(msg)){
            var values= {purchase_order_id: "{$rs['PURCHASE_ORDER_ID']}" , adopt_note: $('#txt_adopt_note').val(), purchase_type: $('#dp_purchase_type').val(), quote_curr_id: $('#dp_quote_curr_id').val(), section_no: $('#dp_section_no').val(), committee_envelopes: $('#dp_committee_envelopes').val(), committee_award: $('#dp_committee_award').val() };
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    if(no==600)
                        success_msg('رسالة','تم ارجاع الطلب بنجاح ..');
                    else
                        success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                    $('button').attr('disabled','disabled');
                    if(no < 70){
                    //alert('{$next_adopt_email}');
                        var sub= 'اعتماد طلب شراء {$rs['PURCHASE_ORDER_ID']}';
                        var text= 'يرجى اعتماد طلب شراء رقم {$rs['PURCHASE_ORDER_ID']}';
                        text+= '<br>{$rs['NOTES']}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= ' <br>{$gfc_domain}{$get_url}/{$rs['PURCHASE_ORDER_ID']} ';
                        _send_mail(btn__,'{$next_adopt_email}',sub,text);
                        btn__ = '';
                    }
                    if(no == 70){
                        var sub= 'اعتماد طلب شراء {$rs['PURCHASE_ORDER_ID']}';
                        var text= 'تم اعتماد طلب شراء رقم {$rs['PURCHASE_ORDER_ID']}';
                        text+= '<br>{$rs['NOTES']}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= ' <br>{$gfc_domain}{$get_url}/{$rs['PURCHASE_ORDER_ID']} ';
                        _send_mail(btn__,'{$purchase_emails}',sub,text);
                        btn__ = '';
                    }
                    setTimeout(function(){
                        if(no==50 || no==600)
                            get_to_link('{$back_url}');
                        else
                            get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }





    function adopt_reversion(){
        if( $('#txt_adopt_note').val() =='' ){
            danger_msg('تحذير..','ادخل بيان الارجاع');
            return false;
        }
        if(confirm('هل تريد ارجاع الطلب ؟!')){
            var adopt_url;
            var values= {purchase_order_id: "{$rs['PURCHASE_ORDER_ID']}" , adopt_note: $('#txt_adopt_note').val(), reversion_case: $('#dp_reversion_case').val() };
            adopt_url= '{$adopt_reversion_url}';
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم ارجاع الطلب بنجاح ..');
                    $('button').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    function quote_case(no){
        var msg= '';
        var manager_attachment= 0;
        var manager_adopt= false;
        var total_cost= $('#h_total_cost').val();
        msg= 'هل تريد اعتماد عرض السعر ؟!';
        if(confirm(msg)){

            get_data('{$get_count_attachment_url}/{$rs['PURCHASE_ORDER_ID']}/purchase_order_manager', {}, function(ret){

                if(parseInt(ret)>=0){
                    manager_attachment= parseInt(ret);

                    if(no==5){
                        // تم اضافة هذا الفحص في 14-12-2015
                        if(total_cost <= 250000){
                            manager_adopt= true;
                        }else if(total_cost > 250000 && total_cost <= 1000000){
                            if(manager_attachment>=1)
                                manager_adopt= true;
                            else
                                danger_msg('تحذير..','يرجى ارفاق اعتماد رئيس مجلس الإدارة بالإضافة إلى عضو من مجلس الإدارة');
                        }else if(total_cost > 1000000){
                            if(manager_attachment>=1)
                                manager_adopt= true;
                            else
                                danger_msg('تحذير..','يرجى ارفاق اعتماد ثلاث أعضاء مجلس إدارة من بينهم الرئيس');
                        }
                    }

                    if(no!=5 || (no==5 && manager_adopt) ){
                        var values= {purchase_order_id: "{$rs['PURCHASE_ORDER_ID']}" , adopt_note: $('#txt_adopt_note').val() };
                        get_data('{$quote_case_url}'+no, values, function(ret){
                            if(ret==1){
                                success_msg('رسالة','تم اعتماد عرض السعر بنجاح ..');
                                $('button').attr('disabled','disabled');
                            }else{
                                danger_msg('تحذير..',ret);
                            }
                        }, 'html');
                    }

                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');

        }
    }

    $('#dp_section_no').change(function(){
        $('#txt_section_total').val('');
        $('#txt_section_balance').val('');

        if($(this).val()!=''){
            get_data('{$section_data_url}', {section_no:$(this).val(), branch:{$rs['BRANCH']}}, function(data){
                $('#txt_section_total').val(data.TOTAL);
                $('#txt_section_balance').val(data.BALANCE);
            }, 'json');
        }
    });

    function show_adopts(){
        $('#adopts_Modal').modal();
    }
 function show_adv(){
        $('#adv_Modal').modal();
    }
    function show_conditions(){
        $('#conditions_Modal').modal();
    }

    function add_conditions(){
        var obj= $('.cb_condition');
        $.each( obj, function(){
            if( $(this).is(':checked') ){
                $('#txt_quote_condition').val( $('#txt_quote_condition').val()+'\\n'+$(this).val() );
            }
        });
    }

     function  add_adv(){

     var purchase_order_id =$('#txt_purchase_order_id').val();
     var adv_no =$('#dp_adv_no').val();
      var serial =$('#txt_serial').val();

     if(confirm('هل تريد إتمام العملية ؟')){

    get_data('{$adv_url}',{id:purchase_order_id,adver_no:adv_no,serial:serial},function(data){
      if(data =='1')

                      success_msg('رسالة','تمت العملية بنجاح');


            //      window.location.reload();


            },'html');


}
    }


/*
    $('#dp_conditions option').dblclick(function(){
        $('#txt_quote_condition').val( $('#txt_quote_condition').val()+'\\n'+$(this).text() );
    });
*/
    $('#print_rep').click(function(){
            _showReport('{$report_url}&type=pdf&report=Offer_Price_Order_Landscape&p_id={$rs['PURCHASE_ORDER_ID']}&sn={$report_sn}',true);
    });
    
    $('#print_rep_portrait').click(function(){
            _showReport('{$report_url}&type=pdf&report=purchase_order_portrait&p_id={$rs['PURCHASE_ORDER_ID']}&sn={$report_sn}',true);
    });
    
    $('#print_rep_landscape').click(function(){
            _showReport('{$report_url}&type=pdf&report=purchase_order_landscape&p_id={$rs['PURCHASE_ORDER_ID']}&sn={$report_sn}',true);
    });

    $('#print_rep2').click(function(){
            _showReport('{$report_url}&type=pdf&report=Offer_Price_Order_Portrait&p_id={$rs['PURCHASE_ORDER_ID']}&sn={$report_sn}',true);
    });

function divide_order(){
        var url = '{$div_order_url}';
        var tbl = '#details_tb';

        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        var x;
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();

        });
x=val[0];
for (v=1;v<val.length;v++){
x=x+','+ val[v];
}
 //alert(x);

        if((val.length > 0 ) && (val.length < $('input[name="class[]"]').length)){
            if(confirm('هل تريد بالتأكيد تقسيم '+val.length+' سجلات   ؟!!')){
/////////////////

                  get_data('{$div_order_url}',{purchase_order_id:{$rs['PURCHASE_ORDER_ID']},id:x },function(data){
                    if(Number(data)>0 )
                          console.log(data);
                          setTimeout(function(){

                           get_to_link('{$get_url}/'+data+'/edit');

                             }, 1000);

                    },'html');
//////////////////


            }
        }else
            alert('يجب تحديد السجلات المراد تحويلها');
    }
    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
