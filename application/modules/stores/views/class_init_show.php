<?php

$MODULE_NAME= 'stores';
$TB_NAME= 'class_init';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$rev_adopt_url= base_url("$MODULE_NAME/$TB_NAME/rev_adopt");

$get_class_child_url= base_url("$MODULE_NAME/$TB_NAME/public_get_class_child");

$select_accounts_url = base_url('financial/accounts/public_select_accounts');

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$LEVEL= ($HaveRs)?$rs['ADOPT']:10;

if($isCreate){
    $post_url= base_url("$MODULE_NAME/$TB_NAME/create");
}elseif($HaveRs){
    $post_url= base_url("$MODULE_NAME/$TB_NAME/edit_".$LEVEL);
}else{
    $post_url= base_url("$MODULE_NAME/$TB_NAME/Error__");
}

function bg_color($case,$level){
    if($case==$level){
        return 'style="background-color: #d5efff"';
    }else{
        return '';
    }
}

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title.(($HaveRs)?' - '.$rs['CLASS_NAME_AR']:'')?></div>
        <ul>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">


                <ul id="myTab" class="nav nav-tabs">
                    <li class="active"><a href="#T1" data-toggle="tab">البيانات العامة</a></li>
                    <li><a href="#T2" data-toggle="tab">المخازن</a></li>
                    <li><a href="#T3" data-toggle="tab">بيانات اللوازم</a></li>
                    <li><a href="#T4" data-toggle="tab">التسعير</a></li>
                    <li><a href="#T5" data-toggle="tab">البيانات المالية</a></li>
                    <li><a href="#T10" data-toggle="tab">حالة الطلب </a></li>
                </ul>


                <div id="myTabContent" class="tab-content">

                    <div class="tab-pane fade in active" id="T1">

                        <input type="hidden" value="<?=$HaveRs?$rs['SER']:""?>" name="ser" id="h_ser" class="form-control" />
                        <input type="hidden" value="<?=$LEVEL?>" id="h_level" class="form-control" />

                        <div class="form-group col-sm-2">
                            <label class="control-label">الصنف الأب *</label>
                            <div>
                                <select name="class_parent_id" id="dp_class_parent_id" class="form-control sel2 level_10" >
                                    <option value="">_________</option>
                                    <?php foreach($class_parent_id_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['CLASS_PARENT_ID']==$row['CLASS_ID']?'selected':''):''?> value="<?=$row['CLASS_ID']?>" ><?=$row['CLASS_ID'].': '.$row['CLASS_NAME_AR']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">الاصناف المدخلة (للعرض فقط)</label>
                            <div>
                                <select id="dp_class_child" class="form-control sel2 level_10" >
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم الصنف من الشجرة</label>
                            <div>
                                <input type="text" readonly value="<?=$HaveRs?$rs['NEW_CLASS_ID']:""?>" id="txt_new_class_id" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم الصنف بالعربية *</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CLASS_NAME_AR']:""?>" name="class_name_ar" id="txt_class_name_ar" class="form-control level_10" />
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم الصنف بالانجليزية</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CLASS_NAME_EN']:""?>" name="class_name_en" id="txt_class_name_en" class="form-control level_10" />
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label"> وصف الصنف </label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CLASS_DESCRIPTION']:""?>" name="class_description" id="txt_class_description" class="form-control level_10" />
                            </div>
                        </div>

                        <div style="clear: both" ></div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> نوع الصنف * </label>
                            <div>
                                <select name="class_type" id="dp_class_type" class="form-control sel2 level_10" >
                                    <option value="">_________</option>
                                    <?php foreach($class_type_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['CLASS_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> وحدة الصنف * </label>
                            <div>
                                <select name="class_unit" id="dp_class_unit" class="form-control sel2 level_10" >
                                    <option value="">_________</option>
                                    <?php foreach($class_unit_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['CLASS_UNIT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> الوحدة الفرعية * </label>
                            <div>
                                <select name="class_unit_sub" id="dp_class_unit_sub" class="form-control sel2 level_10" >
                                    <option value="">_________</option>
                                    <?php foreach($class_unit_sub_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['CLASS_UNIT_SUB']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">الكمية بالنسبة للوحدة الفرعية *</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CLASS_UNIT_COUNT']:""?>" name="class_unit_count" id="txt_class_unit_count" class="form-control level_10" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> الحد الأدنى * </label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CLASS_MIN']:""?>" name="class_min" id="txt_class_min" class="form-control level_10" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> الحد الأقصى * </label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CLASS_MAX']:""?>" name="class_max" id="txt_class_max" class="form-control level_10" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> حد إعادة الطلب *</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CLASS_MIN_REQUEST']:""?>" name="class_min_request" id="txt_class_min_request" class="form-control level_10" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> مسؤولية صرف الصنف * </label>
                            <div>
                                <select name="responsible_in_out" id="dp_responsible_in_out" class="form-control sel2 level_10" >
                                    <option value="">_________</option>
                                    <?php foreach($responsible_in_out_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['RESPONSIBLE_IN_OUT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="tab-pane fade" id="T2">

                        <div class="form-group col-sm-2">
                            <label class="control-label">فعالية الصنف *</label>
                            <div>
                                <select name="class_status" id="dp_class_status" class="form-control sel2 level_20" >
                                    <option value="">_________</option>
                                    <?php foreach($class_status_cons as $row) :?>
                                        <option <?=($row['CON_NO']!=1)?'disabled':''?> <?=$HaveRs?($rs['CLASS_STATUS']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="tab-pane fade" id="T3">

                        <div class="form-group col-sm-2">
                            <label class="control-label"> نوع العهدة * </label>
                            <div>
                                <select name="custody_type" id="dp_custody_type" class="form-control sel2 level_30" >
                                    <option value="">_________</option>
                                    <?php foreach($custody_type_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['CUSTODY_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> اقسام العهد </label>
                            <div>
                                <select name="personal_custody_type" id="dp_personal_custody_type" class="form-control sel2 level_30" >
                                    <option value="">_________</option>
                                    <?php foreach($personal_custody_type_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['PERSONAL_CUSTODY_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="tab-pane fade" id="T4">

                        <div class="form-group col-sm-2">
                            <label class="control-label">سعر الشراء / السوق *</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['BUY_PRICE']:""?>" name="buy_price" id="txt_buy_price" class="form-control level_40" />
                            </div>
                        </div>

                        <div style="display: none" class="form-group col-sm-2">
                            <label class="control-label"> سعر افتتاحي السنة الحالية</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['OPEN_PRICE']:""?>" name="open_price" id="txt_open_price" class="form-control level_40" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">نسبة تسعير المستعمل *</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['USED_PERCENT']:"75"?>" name="used_percent" id="txt_used_percent" class="form-control level_40" />
                            </div>
                        </div>

                    </div>



                    <div class="tab-pane fade" id="T5">

                        <div class="form-group col-sm-2">
                            <label class="control-label"> نوع حساب تداول الصنف *</label>
                            <div>
                                <select name="class_acount_type" id="dp_class_acount_type" class="form-control sel2 level_50" >
                                    <option value="">_________</option>
                                    <?php foreach($class_acount_type_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['CLASS_ACOUNT_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">العملة *</label>
                            <div>
                                <select name="curr_id" id="dp_curr_id" class="form-control sel2 level_50" >
                                    <option value="">_________</option>
                                    <?php foreach($curr_id_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['CURR_ID']==$row['CURR_ID']?'selected':''):''?> value="<?=$row['CURR_ID']?>" ><?=$row['CURR_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع الحساب *</label>
                            <div>
                                <select name="account_type" id="dp_account_type" class="form-control sel2 level_50" >
                                    <option value="">_________</option>
                                    <?php foreach($account_type_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['ACCOUNT_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> رقم الحساب *</label>
                            <div>
                                <input type="text" readonly value="<?=$HaveRs?$rs['ACCOUNT_ID']:""?>" name="account_id" id="h_txt_account_id" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> اسم الحساب * </label>
                            <div>
                                <input type="text" readonly value="<?=$HaveRs?$rs['ACCOUNT_ID_NAME']:""?>" id="txt_account_id" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم حساب المصروف </label>
                            <div>
                                <input type="text" readonly value="<?=$HaveRs?$rs['EXP_ACCOUNT_CUST']:""?>" name="exp_account_cust" id="h_txt_exp_account_cust" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">اسم حساب المصروف </label>
                            <div>
                                <input type="text" readonly value="<?=$HaveRs?$rs['EXP_ACCOUNT_CUST_NAME']:""?>" id="txt_exp_account_cust" class="form-control" />
                            </div>
                        </div>

                        <div style="clear: both"></div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> يخضع لإعداد الموازنة </label>
                            <div>
                                <select name="is_budget" id="dp_is_budget" class="form-control sel2 level_50" >
                                    <option value="">_________</option>
                                    <?php foreach($is_budget_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['IS_BUDGET']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">الفصل</label>
                            <div>
                                <select name="section_no" id="dp_section_no" class="form-control sel2 level_50" >
                                    <option value="">_________</option>
                                    <?php foreach($section_no_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['SECTION_NO']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div style="clear: both"></div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> نوع الإهلاك</label>
                            <div>
                                <select name="destruction_type" id="dp_destruction_type" class="form-control sel2 level_50" >
                                    <option value="">_________</option>
                                    <?php foreach($destruction_type_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['DESTRUCTION_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">نسبة الإهلاك</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['DESTRUCTION_PERCENT']:""?>" name="destruction_percent" id="txt_destruction_percent" class="form-control level_50" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم حساب الإهلاك</label>
                            <div>
                                <input type="text" readonly value="<?=$HaveRs?$rs['DESTRUCTION_ACCOUNT_ID']:""?>" name="destruction_account_id" id="h_txt_destruction_account_id" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">اسم حساب الإهلاك </label>
                            <div>
                                <input type="text" readonly value="<?=$HaveRs?$rs['DESTRUCTION_ACCOUNT_ID_NAME']:""?>" id="txt_destruction_account_id" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> العمر الإنتاجي</label>
                            <div>
                                <select name="average_life_span" id="dp_average_life_span" class="form-control sel2 level_50" >
                                    <option value="">_________</option>
                                    <?php foreach($average_life_span_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['AVERAGE_LIFE_SPAN']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>


                    <div class="tab-pane fade" id="T10">

                        <?php
                        $adopt_case= array(10=>'الجهة الطالبة', 20=>'المخازن', 30=>'اللوازم', 40=>'التسعير', 50=>'المالية');
                        for($i=10;$i<=50;$i+=10){
                        ?>

                        <div <?=($HaveRs)?bg_color($rs['ADOPT'],$i+10):''?> class="form-group col-sm-2">
                            <label class="control-label"> <?=$adopt_case[$i]?> </label>
                            <div>
                                <input type="text" readonly value='<?=$HaveRs?$rs["ENTRY_USER_{$i}_NAME"]:""?>' class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> التاريخ </label>
                            <div>
                                <input type="text" readonly value='<?=$HaveRs?$rs["ENTRY_DATE_{$i}"]:""?>' class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="control-label"> بيان الارجاع </label>
                            <div>
                                <input type="text" readonly value='<?=($HaveRs && $i!=10)?$rs["REV_NOTE_{$i}"]:""?>' class="form-control" />
                            </div>
                        </div>

                        <div style="clear: both"></div>

                        <?php } ?>

                    </div>


                </div>


            </div>

            <div class="modal-footer">

                <div class="form-group col-sm-8" id="div_rev_adopt" style="display:none;text-align: right">

                    <div class="form-group col-sm-3">
                        <label class="control-label">ارجاع الى</label>
                        <div>
                            <select name="rev_adopt" id="dp_rev_adopt" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($adopt_cons as $row) {
                                    if($HaveRs and $row['CON_NO']> 10 and $rs['ADOPT'] >= $row['CON_NO'] ){ ?>
                                        <option value="<?=$row['CON_NO']?>" ><?=str_replace("اعتماد","",$row['CON_NAME'])?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="control-label"> بيان الارجاع </label>
                        <div>
                            <input type="text" name="rev_note" id="txt_rev_note" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">_</label>
                        <div>
                            <button type="button" onclick='javascript:rev_adopt_2();' class="btn btn-warning">  ارجاع </button>
                        </div>
                    </div>

                </div>

                <?php if ( HaveAccess($post_url) ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'20') and !$isCreate and $rs['ADOPT']==10 ) : ?>
                    <button type="button" onclick='javascript:adopt_(20);' class="btn btn-success">اعتماد الجهة الطالبة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'30') and !$isCreate and $rs['ADOPT']==20 ) : ?>
                    <button type="button" onclick='javascript:adopt_(30);' class="btn btn-success"> اعتماد المخازن </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'40') and !$isCreate and $rs['ADOPT']==30 ) : ?>
                    <button type="button" onclick='javascript:adopt_(40);' class="btn btn-success">اعتماد اللوازم</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'50') and !$isCreate and $rs['ADOPT']==40 ) : ?>
                    <button type="button" onclick='javascript:adopt_(50);' class="btn btn-success"> اعتماد لجنة التسعير</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'60') and !$isCreate and $rs['ADOPT']==50 ) : ?>
                    <button type="button" onclick='javascript:adopt_(60);' class="btn btn-success"> اعتماد المالية </button>
                <?php endif; ?>


                <?php if ( HaveAccess($rev_adopt_url) and !$isCreate and $rs['ADOPT']>=20 and $rs['ADOPT']<=50 ) : ?>
                    <button type="button" id="btn_rev_adopt" onclick='javascript:rev_adopt_();' class="btn btn-warning">  ارجاع </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'0') and !$isCreate and $rs['ADOPT']==10 ) : ?>
                    <button type="button" onclick='javascript:adopt_(0);' class="btn btn-danger">  الغاء </button>
                <?php endif; ?>

                <?php if ( !$isCreate ) : ?>
                    <span><?php echo modules::run('attachments/attachment/index',$rs['SER'],'class_init'); ?></span>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>

    reBind();
    
    function reBind(){
        $('.sel2:not("[id^=\'s2\']")').select2();
        $('.level_10, .level_20, .level_30, .level_40, .level_50').prop('readonly',1);
        $('.level_10, .level_20, .level_30, .level_40, .level_50').select2('readonly',1);
        
        if({$LEVEL}==10){
            $('.level_10').prop('readonly',0);
            $('.level_10').select2('readonly',0);
        }
        
        if({$LEVEL}==20){
            $('.level_20').prop('readonly',0);
            $('.level_20').select2('readonly',0);
        }
        
        if({$LEVEL}==30){
            $('.level_30').prop('readonly',0);
            $('.level_30').select2('readonly',0);
        }
        
        if({$LEVEL}==40){
            $('.level_40').prop('readonly',0);
            $('.level_40').select2('readonly',0);
        }
        
        if({$LEVEL}==50){
            $('.level_50').prop('readonly',0);
            $('.level_50').select2('readonly',0);
        }
        
    } // reBind
    
    
    $('#dp_class_parent_id').change(function(){
        var class_parent; 
        var class_parent_option= '<option value="">_________</option>';
        
        class_parent = $('#dp_class_parent_id').select2('val');
        $('#dp_class_child').html('');
        
        if(class_parent!=''){
            get_data('{$get_class_child_url}', {class_parent_id:class_parent}, function(data){
                $.each(data, function(i,item){
                    class_parent_option += "<option value='"+item.CLASS_ID+"' >"+item.CLASS_ID+': '+item.CLASS_NAME_AR+"</option>";
                });
                $('#dp_class_child').html(class_parent_option);
            }, 'json');
        }
        
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
    
    $('#txt_account_id').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/1|2|3|4|5/0' );
    });
    
    $('#txt_exp_account_cust').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/5/0' );
    });
    
    $('#txt_destruction_account_id').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/5/0' );
    });

    
SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }
    
    $('#dp_class_type').select2('val',1);

    </script>
SCRIPT;

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}
        
    var other_data= $('#class_init_form').serializeArray();
        
    function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(no==0) msg= 'هل تريد الغاء الطلب بشكل نهائي ؟!';
        if(confirm(msg)){
            //var values= {ser: "{$rs['SER']}" };
            var values= other_data;
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
        }
    }
    
    function rev_adopt_(){
        $('#div_rev_adopt').show(1000);
        $('#btn_rev_adopt').hide(1000)  
    }

    function rev_adopt_2(){ 
        var msg= 'هل تريد الارجاع ؟!';
        var v_rev_adopt=  $('#dp_rev_adopt').val();
        var v_rev_note= $('#txt_rev_note').val();
        var v_class_parent= $('#dp_class_parent_id').val();
        v_class_parent= v_class_parent.charAt(0); // المواد الكهربائية تبدا ب 1

        if(v_rev_adopt==''){
            alert('اختر الجهة المرجع اليها');
            return 0;
        }
        if(v_rev_note.length < 5){
            alert('ادخل سبب الارجاع');
            return 0;
        }
        if(v_rev_adopt== 40 && v_class_parent==1 ){
            alert(' لا يمكن ارجاع المواد الكهربائية للوازم');
            return 0;
        }
        v_rev_adopt = parseInt(v_rev_adopt)-10;
        
        
        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}" , adopt: {$rs['ADOPT']}, rev_adopt: v_rev_adopt, rev_note: v_rev_note }; 
            get_data('{$rev_adopt_url}', values, function(ret){
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
        }
    
    }


    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
