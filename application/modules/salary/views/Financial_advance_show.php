<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 12/16/21
 * Time: 6:06 AM
  */
  
$MODULE_NAME= 'salary';
$TB_NAME= 'Financial_advance';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); ////
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$gfc_domain= gh_gfc_domain();
$report_url = base_url("JsperReport/showreport?sys=hr/myfacilities");
$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];
$get_emp_url=base_url("$MODULE_NAME/$TB_NAME/public_get_id");
$msg4="يرجى ارفاق المستندات المعززة لطلب السلف ان وجدت!!";
$msg3="يجب ارفاق تعهد قانوني للكفالة!!";
$msg2="سوف يتم تقسيط السلفة على مدار 18 شهر!!";
$msg5='طلب السلفة استثنائي واعلى من المسموح به!!';

$adopt_20_chk_js=0; // new adopt_50_chk_js
$msg_note='';
if(!$isCreate and $rs['ADOPT'] == 40 and ($rs['ADVANCE_BALANCE_ALLOW'] =='' or $rs['BANK_COMMITMENT']=='') )
{
    $adopt_20_chk_js=1;
}
if(!$isCreate)
{
switch ($rs['ADOPT']) {
    /*case 1:
        $msg_note =$rs['NOTE'];
        break;*/
    case 10:
        $msg_note =$rs['ADOPT_NOTE_20'];
        break;
    case 20:
        $msg_note =$rs['ADOPT_NOTE_30'];
        break;
    case 30:
        $msg_note =$rs['ADOPT_NOTE_40'];
        break;
    case 40:
        $msg_note =$rs['ADOPT_NOTE_50'];
        break;
    case 50:
        $msg_note =$rs['ADOPT_NOTE_60'];
        break;
    case 60:
        $msg_note =$rs['ADOPT_NOTE_70'];
        break;
    case 70:
        $msg_note =$rs['ADOPT_NOTE_70'];
        break;
    default:
        $msg_note ='';
}


}

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
<ul>
    <?php if( 0 and HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
</ul>
</div>
</div>

<div class="form-body">

    <div id="msg_container" class="alert alert-danger hidden" role="alert"></div>
    <div id="msg5_container" class="alert alert-danger <?=$HaveRs?($rs['ADVANCE_TYPE']!=1?'  ':' hidden '):' hidden '?>" role="alert"><?=$msg5; ?></div>
    <div id="msg3_container" class="alert alert-danger hidden" role="alert"></div>


    <div id="msg2_container" class="alert alert-danger <?=$HaveRs?($rs['INSTALLMENTS_NO']==18?'  ':' hidden '):' hidden '?>" role="alert"><?=$msg2; ?></div>


    <div id="msg4_container" class="alert alert-danger <?=$HaveRs? ' ':' hidden '?>" role="alert"><?=$HaveRs?($rs['EMP_TYPE']==1?$msg4:$msg3):' '?></div>


    <div id="container">
    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
    <div class="modal-body inline_form">
                <fieldset>
                    <!----------------------------------  بيانات ادارية ---------------------------------------------->
                    <legend> بيانات ادارية </legend>
                <div class="col-sm-10">

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم السند </label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" name="ser" class="form-control" />
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                            <?php endif; ?>
                        </div>
                    </div>


                    <div class="form-group col-sm-3">
                        <label class="control-label">الموظف</label>
                        <div>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2" data-val="true" data-val-required="حقل مطلوب" >
                                <option value="">_________</option>
                                <?php $emp_exist=0; foreach($emp_no_cons as $row) :?>
                                <?php if($emp_exist==0 && $rs['EMP_NO']==$row['EMP_NO']) { $emp_exist=1; } ?>
                                    <option <?=$HaveRs?($rs['EMP_NO']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                                <?=(!$emp_exist && $HaveRs)?"<option selected value='{$rs['EMP_NO']}' > {$rs['EMP_NO']}_ {$rs['EMP_NAME']} </option>":""  ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="emp_no" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">مقر الموظف</label>
                        <div>
                            <select name="branch" id="dp_branch" class="form-control" disabled data-val="true" data-val-required="حقل مطلوب">
                                <option value="">_________</option>
                                <?php foreach($bran_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['BRANCH']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="branch" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">نوع التعيين</label>
                        <div>
                            <select name="emp_type" id="dp_emp_type" class="form-control" disabled data-val="true" data-val-required="حقل مطلوب">
                                <option value="">_________</option>
                                <?php foreach($emp_type_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['EMP_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="branch" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">حالة الاعتماد</label>
                        <div>
                            <select name="adopt" id="dp_adopt" class="form-control" disabled>
                                <option value="">_________</option>
                                <?php foreach($adopt_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['ADOPT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="branch" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                </fieldset>
                <hr/>

                <fieldset>
                    <!----------------------------------   بيانات السلفة المالية ---------------------------------------------->
                    <legend> بيانات السلفة المالية </legend>
                    <div class="col-sm-10">

                        <div class="form-group col-sm-1">
                            <label class="control-label">قيمة السلفة</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['ADVANCE_VALUE']:""?>" name="advance_value" id="txt_advance_value" class="form-control" data-val="true" data-val-required="حقل مطلوب" />
                                <input type="hidden" value="<?=$HaveRs?$rs['ADVANCE_VALUE']:""?>" id="h_advance_value" name="h_advance_value" class="form-control" data-val="true" data-val-required="حقل مطلوب"/>
                                <span class="field-validation-valid" data-valmsg-for="advance_value" data-valmsg-replace="true"></span>
                                <span class="field-validation-valid" data-valmsg-for="h_advance_value" data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">عدد الأقساط</label>
                            <div>
                                <select name="installments_no" id="dp_installments_no" class="form-control sel2" data-val="true" data-val-required="حقل مطلوب"  >
                                    <option <?=$HaveRs?($rs['INSTALLMENTS_NO']==12?'selected':''):''?> value="12">12 شهر</option>
                                    <option <?=$HaveRs?($rs['INSTALLMENTS_NO']==18?'selected':''):''?> value="18">18 شهر</option>

                                </select>
                                <span class="field-validation-valid" data-valmsg-for="installments_no" data-valmsg-replace="true"></span>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">قيمة قسط السلفة</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['INS_ADVANCE_VALUE']:""?>" name="ins_advance_value" id="txt_ins_advance_value" class="form-control" readonly data-val="true" data-val-required="حقل مطلوب" />
                                <span class="field-validation-valid" data-valmsg-for="ins_advance_value" data-valmsg-replace="true"></span>
                             </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">طبيعة السلفة</label>
                            <div>
                                <select name="advance_type" id="dp_advance_type" class="form-control" disabled data-val="true" data-val-required="حقل مطلوب"  >
                                   <?php foreach($advance_type_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['ADVANCE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="advance_type" data-valmsg-replace="true"></span>
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">تاريخ آخر قسط سلفة</label>
                            <div>
                                <input type="text"  value="<?=$HaveRs?$rs['LAST_ADVANCE_DATE']:''?>" name="last_advance_date" id="txt_last_advance_date" class="form-control" readonly />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">سبب السلفة</label>
                            <div>
                                <select name="reason_id" id="dp_reason_id" class="form-control sel2" data-val="true" data-val-required="حقل مطلوب"    >
                                    <option value="">_________</option>
                                    <?php foreach($reason_id_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['REASON_ID']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="reason_id" data-valmsg-replace="true"></span>

                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">رصيد السلفة</label>
                            <div>
                                <select name="advance_balance_allow" id="dp_advance_balance_allow" class="form-control<?=$HaveRs?($rs['ADOPT']!=40?'':' sel2 '):''?>"  <?=$HaveRs?($rs['ADOPT']!=2?' readonly ':''):'readonly'?>>
                                    <option value="">_________</option>
                                    <?php foreach($advance_balance_allow_cons as $row) :?>
                                       <?php if($HaveRs && $rs['ADVANCE_BALANCE_ALLOW']=='') { $rs['ADVANCE_BALANCE_ALLOW']=1; } ?>
                                        <option <?=$HaveRs?($rs['ADVANCE_BALANCE_ALLOW']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                        <br/>
                        <div id="div_emp_sponsor_1" class="form-group col-sm-3 <?=$HaveRs?($rs['EMP_TYPE']==1?'hidden':''):''?>">
                            <label class="control-label">الكفيل الاول</label>
                            <div>
                                <select name="emp_sponsor_1" id="dp_emp_sponsor_1" class="form-control sel2" data-val="true" data-val-required="حقل مطلوب"    >
                                    <option value="">_________</option>
                                    <?php foreach($sponsor_no_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['EMP_SPONSOR_1']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="emp_sponsor_1" data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div id="div_emp_sponsor_2" class="form-group col-sm-3 <?=$HaveRs?($rs['EMP_TYPE']==1?'hidden':''):''?>">
                            <label class="control-label">الكفيل الثاني</label>
                            <div>
                                <select name="emp_sponsor_2" id="dp_emp_sponsor_2" class="form-control sel2"  >
                                    <option value="">_________</option>
                                    <?php foreach($sponsor_no_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['EMP_SPONSOR_2']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

<br>
                        <div class="form-group col-sm-11">
                            <label class="control-label">ملاحظات</label>
                            <div>
                                <input type="text"  name="notes" value="<?=$HaveRs?$rs['NOTE']:""?>" id="txt_notes" class="form-control" <?=$HaveRs?($rs['ADOPT']!=1?'readonly':''):''?>>
                            </div>
                        </div>
                        <br>
                        <?php if(!$isCreate){ ?>
                            <div class="form-group hidden">
                                <label class="col-sm-1 control-label">ارفاق صورة السند</label>
                                <div class="col-sm-2">
                                    <?php
                                    if (  HaveAccess($post_url)  && ($isCreate || ( ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false)  ) ) AND  ( $isCreate || ($rs['ENTRY_USER']==$this->user->id)?1 : 0 )  ) { ?>
                                        <?php echo modules::run('attachments/attachment/index',$rs['SER'],'Financial_advance'); ?>
                                    <?php  }else{  ?>
                                        <?php echo modules::run('attachments/attachment/index',$rs['SER'],'Financial_advance',0); ?>
                                    <?php  } ?>


                                </div>

                            </div>
                        <?php  } ?>

                </fieldset>
                <hr/>
                <fieldset>

                    <!----------------------------------  بيانات مالية ---------------------------------------------->
                    <legend> بيانات مالية </legend>
                    <div class="col-sm-10">

                        <div class="form-group col-sm-2">
                            <label class="control-label">الراتب الأساسي</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['BASIC_SALARY']:""?>" name="basic_salary" id="txt_basic_salary" class="form-control" readonly  data-val="true" data-val-required="حقل مطلوب"    />
                                <span class="field-validation-valid" data-valmsg-for="basic_salary" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">الصافي للبنك</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['NET_BANK']:""?>" name="net_bank" id="txt_net_bank" class="form-control" readonly  data-val="true" data-val-required="حقل مطلوب"  />
                                <span class="field-validation-valid" data-valmsg-for="net_bank" data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">الراتب بدون إضافات</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['NET_SALARY']:""?>" name="net_salary" id="txt_net_salary" class="form-control" readonly  data-val="true" data-val-required="حقل مطلوب"  />
                                <span class="field-validation-valid" data-valmsg-for="net_salary" data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">قيمة الالتزامات البنكية</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['BANK_COMMITMENT']:""?>" name="bank_commitment" id="txt_bank_commitment" class="form-control" <?=$HaveRs?($rs['ADOPT']!=40?'readonly':''):'readonly'?>/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">قيمة صافي الراتب لأغراض الإئتمان التجاري</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['NET_SALARY_TRAD']:""?>" name="net_salary_trad" id="txt_net_salary_trad" class="form-control" readonly/>
                            </div>
                        </div>

                </fieldset>
              
                <hr/>
                <fieldset>

                    <!----------------------------------  ملاحظات المعتمد ---------------------------------------------->
                    <legend> ملاحظات المعتمد </legend>
                    <div class="form-group col-sm-11">
                            <label class="control-label">ملاحظات</label>
                            <div>
                                <input type="text" name="adopt_notes" value="<?=$HaveRs?$msg_note:""?>" id="txt_adopt_notes" class="form-control valid" <?=$HaveRs?($rs['ADOPT']==1?'readonly':''):'readonly'?>>
                            </div>
                        </div>

                </fieldset>
            

                </div>

    <?php if($HaveRs){ ?>
        <hr>
        <div style="clear: both;">
            <a style="width: 100px; margin-left: 10px" href="javascript:;" onclick="javascript:show_adopts();" class="icon-btn">
                <i class="glyphicon glyphicon-list"></i>
                <div> بيانات الاعتمادات </div>
            </a>
       <span id="quote"><?=modules::run('attachments/attachment/index',$rs['SER'],'Financial_advance');?></span>
       </div>
    <?php } ?>
            <div class="modal-footer">

                <?php if ( HaveAccess($post_url)&& ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ) &&  ( $isCreate || ($rs['ENTRY_USER']==$this->user->id)?1 : 0 ) ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'10') and !$isCreate and $rs['ADOPT']==1 && $rs['ENTRY_USER']==$this->user->id ) : ?>
                    <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد المدخل</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'0') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt_(0);' class="btn btn-danger">إلغاء طلب سلفة</button>
                <?php endif; ?>

                <?php if (HaveAccess($adopt_url.'50') &&  HaveAccess($post_url)&& (!$isCreate && ($rs['ADOPT']==40 and isset($can_edit)?$can_edit:false)  )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'20')&& (!$isCreate && ($rs['ADOPT']==10 and ($rs['IS_CURR_USER_MANAGER']==1 or $rs['BRANCH']>1) and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt_20" onclick='javascript:adopt_(20);' class="btn btn-success">اعتماد الدائرة المختصة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_10')&& (!$isCreate && ($rs['ADOPT']==10 and ($rs['IS_CURR_USER_MANAGER']==1 or $rs['BRANCH']>1) and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt__10" onclick='javascript:adopt_("_10");' class="btn btn-danger unadopt">إرجاع السلفة من الدائرة المختصة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'30')&& (!$isCreate && ($rs['ADOPT']==20 and ($rs['IS_CURR_USER_MANAGER']==1 or $rs['BRANCH']>1) and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt_30" onclick='javascript:adopt_(30);' class="btn btn-success">اعتماد مدير المقر / الإدارة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_20')&& (!$isCreate && ($rs['ADOPT']==20 and ($rs['IS_CURR_USER_MANAGER']==1 or $rs['BRANCH']>1) and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt__20" onclick='javascript:adopt_("_20");' class="btn btn-danger unadopt">إرجاع السلفة من مدير المقر / الإدارة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'40')&& (!$isCreate && ($rs['ADOPT']==30 and isset($can_edit)?$can_edit:false) )   ) : ?>
                    <button type="button" id="btn_adopt_40" onclick='javascript:adopt_(40);' class="btn btn-success">اعتماد الرقابة الداخلية</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_30')&& (!$isCreate && ($rs['ADOPT']==30 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt__30" onclick='javascript:adopt_("_30");' class="btn btn-danger unadopt">إرجاع السلفة من الرقابة الداخلية</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'50')&& (!$isCreate && ($rs['ADOPT']==40 and isset($can_edit)?$can_edit:false) ) ) : ?>
                    <button type="button" id="btn_adopt_50" onclick='javascript:adopt_(50);' class="btn btn-success">اعتماد دائرة الحسابات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_40')&& (!$isCreate && ($rs['ADOPT']==40 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt__40" onclick='javascript:adopt_("_40");' class="btn btn-danger unadopt">إرجاع السلفة من دائرة الحسابات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'60')&& (!$isCreate && ($rs['ADOPT']==50 and isset($can_edit)?$can_edit:false) )  ) : ?>
                   <button type="button" id="btn_adopt_60" onclick='javascript:adopt_(60);' class="btn btn-success">اعتماد مساعد المدير عام الشركة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_50')&& (!$isCreate && ($rs['ADOPT']==50 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt__50" onclick='javascript:adopt_("_50");' class="btn btn-danger unadopt">ارجاع السلفة من مساعد المدير العام</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'70')&& (!$isCreate && ($rs['ADOPT']==60 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt_70" onclick='javascript:adopt_(70);' class="btn btn-success">اعتماد المدير عام الشركة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_60')&& (!$isCreate && ($rs['ADOPT']==60 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="button" id="btn_adopt__60" onclick='javascript:adopt_("_60");' class="btn btn-danger unadopt">ارجاع السلفة من  المدير العام</button>
                <?php endif; ?>
				<?php if (!$isCreate && $rs['ADOPT']==70) : ?>
				<button type="button" onclick="javascript:print_report();" class="btn btn-info">طباعة<span class="glyphicon glyphicon-print"></span></button>
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
                            <th>حالة السلفة</th>
                            <th>المستخدم</th>
                            <th>التاريخ</th>
                            <th>الملاحظة</th>
                        </tr>
                        </thead>
                        <tbody>
                         <tr>
				 <td>0</td>   
				 <td>إلغاء سلفة</td> 
				 <td><?=$rs["ADOPT_USER_0_NAME"];?></td>
                                <td><?=$rs["ADOPT_DATE_0"];?></td>
                                <td style='max-width: 300px'><?=$rs["ADOPT_NOTE_0"];?></td> 
                        </tr>
<?php 
                         


foreach ($adopt_cons as $value) {

    if($value['CON_NO']==50){
?>
    <tr>
        <td><?=$value['CON_NO'];?></td>
        <td>ادخال بيانات الرواتب</td>
        <td><?=$rs["ENTRY_USER_50_NAME"]?></td>
        <td></td>
        <td></td>
    </tr>
<?php
    } // end if
?>        
    <tr>
        <td><?=$value['CON_NO'];?></td>
        <td><?=$value['CON_NAME'];?></td>
        <td><?=$rs["ADOPT_USER_".$value['CON_NO']."_NAME"];?></td>
        <td><?=$rs["ADOPT_DATE_".$value['CON_NO']];?></td>
        <td style='max-width: 300px'><?=$rs["ADOPT_NOTE_".$value['CON_NO']];?></td>
    </tr>
<?php
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
$('#quote a div').text('المرفقات ');
reBind();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();
} // reBind


SCRIPT;


    $scripts = <<<SCRIPT
    {$scripts}

function clear_form() {
    clearForm($('#{$TB_NAME}_form'));
}
//$('.sel2').select2();

function show_adopts(){
    $('#adopts_Modal').modal();
}

$('#dp_emp_no').select2().on('change', function() {
    var emp_no = $(this).val();
    if (emp_no != '') {

        get_data('{$get_emp_url}', {
            id: emp_no
        }, function(data) {

            if (data.length == 1) {

                var item = data[0];
                if (item.MONTHS_FROM_LAST_ADVANCE <= 12) {
                    $("#msg_container").removeClass("hidden").html('لا يمكن الاستفادة من سلفة لا بد من مرور 12 شهر على الاقل من تاريخ سداد آخر قسط للسلفة السابقة !!');

                } else {
                    if (item.EMP_TYPE != '1') {
                        $("#div_emp_sponsor_1").removeClass("hidden");
                        $("#div_emp_sponsor_2").removeClass("hidden");
                       // $("#msg3_container").removeClass("hidden").html('يجب ارفاق تعهد قانوني للكفالة!!');
                    } else {
                        $("#div_emp_sponsor_1").addClass("hidden");
                        $("#div_emp_sponsor_2").addClass("hidden");
                        $("#msg3_container").addClass("hidden").html('');
                    }
                    $("#dp_branch").val(item.BRAN);
                    $("#dp_emp_type").val(item.EMP_TYPE);
                    $("#txt_basic_salary").val(item.BASIC_SALARY);
                    $("#txt_net_bank").val(item.NET_SALARY);
                    $("#txt_last_advance_date").val(item.LAST_ADVANCE_DATE);
                    $("#txt_net_salary").val(item.NO_ADD_SALARY);
                    $("#txt_advance_value").val(item.ADVANCE_VALUE);
                    $("#txt_ins_advance_value").val(item.INS_ADVANCE_VALUE);
                    $("#h_advance_value").val(item.ADVANCE_VALUE);
                    $("#msg_container").addClass("hidden").html('');


                }
            }



        });
    } else {

        $("#txt_basic_salary").val('');
        $("#txt_net_bank").val('');
        $("#txt_last_advance_date").val('');
        $("#txt_net_salary").val('');
        $("#txt_advance_value").val('');
        $("#txt_ins_advance_value").val('');
        $("#dp_installments_no").select2('val', 12);
        $("#dp_advance_type").val(1);
        $("#dp_branch").val('');
        $("#dp_emp_type").val('');
        $("#h_advance_value").val('');
        $("#msg_container").addClass("hidden").html('');

    }
});
//////////////////////////////////////////////////////////
$('#txt_advance_value,#dp_installments_no').on('change', function() {
    if (parseInt($("#h_advance_value").val()) < parseInt($("#txt_advance_value").val())) {
        $("#msg5_container").removeClass("hidden").html('طلب السلفة استثنائي واعلى من المسموح به!!');
        $("#dp_advance_type").val(2);


    } else {
        $("#msg5_container").addClass("hidden").html('');
        $("#dp_advance_type").val(1);
    }
    if ($("#dp_installments_no").val() == 18) {
        $("#msg2_container").removeClass("hidden").html('سوف يتم تقسيط السلفة على مدار 18 شهر!!');
    } else {
        $("#msg2_container").addClass("hidden").html('');
    }
    $("#txt_ins_advance_value").val(Math.trunc((parseInt($("#txt_advance_value").val()) / parseInt($("#dp_installments_no").val())).toFixed(2)));

});
//////////////////////////////////////////////////////////
$('#dp_reason_id').select2().on('change', function() {
    /*if ($("#dp_reason_id").val() == 20) {
        $("#msg4_container").removeClass("hidden").html('يرجى ارفاق المستندات المعززة لطلب السلف ان وجدت!!');

    } else {
        $("#msg4_container").addClass("hidden").html('');
    }*/

});
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
$('#txt_bank_commitment').on('change', function() {
    var salary_trad_calc= parseFloat( $("#txt_net_salary").val() ) - parseFloat( $("#txt_bank_commitment").val() ) ;
   $('#txt_net_salary_trad').val( num_format( salary_trad_calc ).replace(/,/g, '') );
});
///////////////////////////////////////////////////////////////////////
$('button[data-action="submit"]').click(function(e){
    if ($("#dp_reason_id").val() == 20 && $("#txt_notes").val()=='' ) {
        e.preventDefault();
        danger_msg( 'تحذير','يجب ادخال سبب السلفة في خانة الملاحظات!!');
    }
    else
    {
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
    }
});
///////////////////////////////////////

    var btn__= '';
    $('#btn_adopt_20,#btn_adopt_30,#btn_adopt_40,#btn_adopt_50,#btn_adopt_60').click( function(){
        btn__ = $(this);
    });

function adopt_(no){
	if(no[0] == '_')
	{
		if($('#txt_adopt_notes').val() == '')
		{
			danger_msg('يجب ادخال ملاحظات المعتمد و توضيح سبب الإرجاع !!');
			return 0;
		}
	}
   if ({$adopt_20_chk_js} && no!='_40' ) {
        danger_msg('تنبيه','يتوجب عليك ادخال جميع الحقول التالية <br> رصيد السلفة <br> قيمة الالتزامات البنكية ');
        return 0;
    }

    else
    {
    get_data('{$adopt_url}'+no,{id: $('#txt_ser').val(),emp_no: $('#dp_emp_no').val(),emp_sponsor_1: $('#dp_emp_sponsor_1').val(),emp_sponsor_2: $('#dp_emp_sponsor_2').val(),advance_balance_allow:$('#dp_advance_balance_allow').val(),bank_commitment:$('#txt_bank_commitment').val(),net_salary_trad:$('#txt_net_salary_trad').val(),notes:$('#txt_adopt_notes').val()},function(data){
        if(data =='1')
           success_msg('رسالة','تمت العملية بنجاح ..');
           
           if(no < 70){
                var sub= 'اعتماد طلب سلفة {$rs['SER']}';
                var text= 'يرجى اعتماد طلب سلفة رقم {$rs['SER']}';
                text+= '<br>للاطلاع افتح الرابط';
                text+= ' <br>{$gfc_domain}{$get_url}/{$rs['SER']} ';
                _send_mail(btn__,'{$next_adopt_email}',sub,text);
                btn__ = '';
            }
           
        reload_Page();
    },'html');
    }
}

///////////////////////////////////////

   function print_report(){
		var rep_url = '{$report_url}&report_type=pdf&report=financial_advance&p_ser='+$('#txt_ser').val();
		_showReport(rep_url);
    }
	
 </script>
SCRIPT;


sec_scripts($scripts);

?>