
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 08/09/15
 * Time: 12:40 م
 */

$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_pledges';
$backs_url=base_url("$MODULE_NAME/$TB_NAME/index/1/2"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$get_code_url  =base_url("$MODULE_NAME/$TB_NAME/public_get_id");
$isCreate =isset($pledge_data) && count($pledge_data)  > 0 ?false:true;
$get_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$get_customer_url= base_url("$MODULE_NAME/$TB_NAME/public_customer_url");
$rs=($isCreate)? array() : $pledge_data;
$fid = (count($rs)>0)?$rs['FILE_ID']:0;
$d_file_id = (count($rs)>0)?$rs['D_FILE_ID']:0;
$customer_id = (count($rs)>0)?$rs['CUSTOMER_ID']:$customer_id;

$public_return_url = $back_url=base_url("$MODULE_NAME/$TB_NAME/cancel"); //$action
$stop_pledge_url = $back_url=base_url("$MODULE_NAME/$TB_NAME/stop"); //$action
$stop_pledge2020_url= $back_url=base_url("$MODULE_NAME/$TB_NAME/stop2020"); //$action
$lost_pledge_url =  $back_url=base_url("$MODULE_NAME/$TB_NAME/lost"); //$action
$cancel_0 =  $back_url=base_url("$MODULE_NAME/$TB_NAME/cancel_0"); //$action
$onemp_pledge_url=  $back_url=base_url("$MODULE_NAME/$TB_NAME/onEmp"); //$action
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$cycle_url =base_url("$MODULE_NAME/$TB_NAME/public_gcc_structure_cycle");
$department_url =base_url("$MODULE_NAME/$TB_NAME/public_gcc_structure_dep");
//echo "<pre>" ; print_r($rs);

$get_customer_class_details=base_url("$MODULE_NAME/$TB_NAME/public_get_customer_class_file_ids");

$class_type=(count($rs)>0)? $rs['CLASS_TYPE']: 0 ;
$report_url=  'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="javascript:;" onclick="javascript:get_link_create();"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
            <hr/>
            <fieldset>
                <legend>  بيانات السند </legend>


                <div class="form-group col-sm-1">
                    <label class="control-label">المسلسل</label>
                    <div>
                        <input type="text" name="file_id" value="<?php if (count($rs)>0)echo $rs['FILE_ID'] ;?>"  readonly  data-type="text"   id="txt_file_id" class="form-control ">
                    </div>
                </div>



                <div class="form-group col-sm-1">
                    <label class="control-label">نوع المستفيد</label>
                    <div>
                        <select name="customer_type" id="dp_customer_type" class="form-control" >
                            <?php foreach($customer_type_cons as $row) :?>
                                <option <?=!$isCreate?($rs['CUSTOMER_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-3" id="customer_div_1">
                    <label class="control-label">حساب المستفيد</label>
                    <div>
                        <select  name="customer_id" id="dp_customer_id"  data-curr="false"  class="form-control" data-val="true" data-val-required="حقل مطلوب" >
                        <option value="">___________________________________</option>
                            <?php foreach($customer_ids as $row) :?>
                                <option value="<?=$row['ID']?>" <?PHP if ($row['ID']==$customer_id){ echo " selected"; } ?> ><?=$row['NO'].'-'.$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="customer_id" data-valmsg-replace="true"></span>
                    </div>
                </div>


              <!--  <div class="form-group col-sm-2" id="customer_div_2">
                    <label class="control-label">الغرف </label>
                    <div>
                        <select name="customer_id2" id="dp_customer_id2" class="form-control" >
                            <option value="">_________</option>
                            <?php //foreach($rooms_cons as $row) :?>
                                <option <? //=!$isCreate?($rs['CUSTOMER_ID']==$row['ROOM_ID']?'selected':''):''?> value="<? //=$row['ROOM_ID']?>" ><? //=$row['ROOM_ID'].': '.$row['ROOM_PARENT_NAME'].' - '.$row['ROOM_NAME']?></option>
                            <?php //endforeach; ?>
                        </select>
                    </div>
                </div>
-->
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الغرفة</label>
                    <div>
                        <input type="text" name="room_id" data-val="true" value="<?php if (count($rs)>0)echo $rs['ROOM_ID'] ;?>"  readonly  data-type="text"   id="txt_room_id" class="form-control "data-val-required="حقل مطلوب"  >
                        <span class="field-validation-valid" data-valmsg-for="room_id" data-valmsg-replace="true"></span>

                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label">اسم الغرفة</label>
                    <div>
                        <input type="text" name="room_name" data-val="true" value="<?php if (count($rs)>0)echo $rs['ROOM_ID_NAME'] ;?>"  readonly  data-type="text"   id="txt_room_name" class="form-control " data-val-required="حقل مطلوب"  >
                        <span class="field-validation-valid" data-valmsg-for="room_name" data-valmsg-replace="true"></span>

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> تاريخ استلام العهده </label>
                    <div>
                        <input type="text" name="recieved_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_recieved_date" class="form-control " value="<?php if (count($rs)>0)echo $rs['RECIEVED_DATE'] ;?>">
                        <span class="field-validation-valid" data-valmsg-for="recieved_date" data-valmsg-replace="true"></span>
                    </div>
                </div>


                <input type=hidden name="source" value="<?php echo (count($rs)>0)? $rs['SOURCE']: 3;?>"    data-type="text"   id="h_source" class="form-control ">
                <!--  <div class="form-group col-sm-2">
                    <label class="col-sm-4 control-label">مصدر العهدة</label>
                    <div>
                        <select  name="source" id="dp_source"  data-curr="false"  class="form-control">
                            <?php //foreach($source_all as $row) :?>
                                <option  value="<?//= $row['CON_NO'] ?>"><?//= $row['CON_NAME'] ?></option>
                            <?php //endforeach; ?>
                        </select>
                    </div>
                </div>-->
                <!--<div class="form-group col-sm-3">
                    <label class="col-sm-5 control-label"> رقم سند الصرف </label>
                    <div >
                        <input type="text" name="class_output_id" value=""  readonly  data-type="text"   id="txt_class_output_id" class="form-control ">

                    </div></div>-->
                <input type=hidden name="status"  value="<?php echo (count($rs)>0)? $rs['STATUS']: 1 ;?>"   data-type="text"   id="h_status" class="form-control ">
            <!--   <div class="form-group col-sm-3">
                    <label class="col-sm-4 control-label">حالة العهدة</label>
                    <div>
                        <select  name="status" id="dp_status"  data-curr="false"  class="form-control">
                            <?php// foreach($status_all as $row) :?>
                                <option  value="<?//= $row['CON_NO'] ?>"><?//= $row['CON_NAME'] ?></option>
                            <?php// endforeach; ?>
                        </select>
                    </div>
                </div> -->

                <div class="form-group col-sm-2">
                    <label class="control-label">الادارة</label>
                    <div>
                        <select  name="manage_st_id" id="dp_manage_st_id"  data-curr="false"  class="form-control" data-val="true" data-val-required="حقل مطلوب" >
                            <option value="">___________________________________</option>
                            <?php foreach($manage_st_ids as $row) :?>
                                <option  value="<?= $row['ST_ID'] ?>" <?PHP  if (count($rs)>0) {if ($row['ST_ID']==$rs['MANAGE_ST_ID']){ echo " selected"; }} ?> ><?php echo $row['ST_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="manage_st_id" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">الدائرة</label>
                    <div>
                        <?php
                        if (count($rs)<0)
                        {
                            ?>
                        <select  name="cycle_st_id" id="dp_cycle_st_id"  data-curr="false"  class="form-control" data-val="true" <!--data-val-required="حقل مطلوب"--> >
                            <option value="">___________________________________</option>

                        </select>
                        <?php
                        }
                        else
                        {
                           ?>
                            <select  name="cycle_st_id" id="dp_cycle_st_id"  data-curr="false"  class="form-control" data-val="true" <!--data-val-required="حقل مطلوب"--> >
                            <option value="">___________________________________</option>

                            <?php foreach($cycle_st_ids as $row) :?>
                            <option  value="<?= $row['ST_ID'] ?>" <?PHP  if (count($rs)>0) {if ($row['ST_ID']==$rs['CYCLE_ST_ID']){ echo " selected"; }} ?> ><?php echo $row['ST_NAME']  ?></option>
                        <?php endforeach; ?>
                         </select>
                        <?php
                        }
                        ?>
                        <span class="field-validation-valid" data-valmsg-for="cycle_st_id" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">القسم</label>
                    <div>
                        <?php
                        if (count($rs)<0)
                        {
                        ?>
                        <select  name="department_st_id" id="dp_department_st_id"  data-curr="false"  class="form-control" data-val="true" <!--data-val-required="حقل مطلوب"--> >
                            <option value="">___________________________________</option>

                        </select>
                         <?php
                        }
                        else
                        {
                        ?>

                            <select  name="department_st_id" id="dp_department_st_id"  data-curr="false"  class="form-control" data-val="true" <!--data-val-required="حقل مطلوب"--> >
                            <option value="">___________________________________</option>
                              <?php foreach($department_st_ids as $row) :?>
                            <option  value="<?= $row['ST_ID'] ?>" <?PHP  if (count($rs)>0) {if ($row['ST_ID']==$rs['DEPARTMENT_ST_ID']){ echo " selected"; }} ?> ><?php echo $row['ST_NAME']  ?></option>
                        <?php endforeach; ?>

</select>
                        <?php
                        }
                        ?>
                        <span class="field-validation-valid" data-valmsg-for="department_st_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label"> البيان </label>
                    <div >
                        <input type="text"   data-val-required="حقل مطلوب" name="notes" id="txt_notes" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['NOTES']: '' ;?>" data-val="true" data-val-required="حقل مطلوب">


                    </div></div>

            </fieldset><hr/>
            <hr/>
            <fieldset>
                <legend>  الباركود </legend>
                <div class="form-group col-sm-3">
                    <label class="control-label">الباركود</label>

                    <div >
                        <input type="text"  name="class_code_ser" id="txt_class_code_ser" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['BARCODE']: '' ;?>">


                    </div>

                   </div>

            </fieldset>
                <fieldset>
                    <legend>  بيانات الصنف</legend>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الصنف</label>
                        <div>
                            <input class="form-control" type="text" readonly name="class_id" id="h_txt_class_id" value="<?php echo (count($rs)>0)? $rs['CLASS_ID']: '' ;?>" data-val="true" data-val-required="حقل مطلوب"  >
                            <span class="field-validation-valid" data-valmsg-for="class_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <label class="control-label">الصنف</label>
                        <div>
                            <input readonly class="form-control"  id="txt_class_id" value="<?php echo (count($rs)>0)? $rs['CLASS_ID_NAME']: '' ;?>" />
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">الوحدة</label>
                        <div>
                            <input readonly class="form-control"  id="unit_name_txt_class_id" value="<?php echo (count($rs)>0)? $rs['CLASS_UNIT_NAME']: '' ;?>"/>
                            <input type="hidden" class="form-control"  name="class_unit" id="unit_txt_class_id"  value="<?php echo (count($rs)>0)? $rs['CLASS_UNIT']: '' ;?>"/>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">السعر</label>
                        <div>
                            <input readonly class="form-control"  name="price" id="price_txt_class_id" value="<?php echo (count($rs)>0)? $rs['PRICE']: '' ;?>" />
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم حساب المصروف للمستفيدين </label>
                        <div>
                            <input readonly name="exp_account_cust" id="exp_account_cust_txt_class_id" class="form-control" value="<?php echo (count($rs)>0)? $rs['EXP_ACCOUNT_CUST']: '' ;?>">
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">حساب المصروف للمستفيدين </label>
                        <div>
                            <input readonly name="exp_account_cust_name" id="exp_account_cust_name_txt_class_id" class="form-control" value="<?php echo (count($rs)>0)? $rs['EXP_ACCOUNT_CUST_NAME']: '' ;?>">
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">حالة الصنف</label>
                        <div>
                            <select name="class_type" id="dp_class_type" class="form-control" >
                            <option></option>
                            <?php foreach($class_type_all as $row) :?>
                                <option value="<?=$row['CON_NO']?>" <?php if($row['CON_NO']==$class_type) echo 'selected'; ?>><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                            </select>
                               </div>
                    </div>
                    <div class="form-group col-sm-9">
                        <label class="control-label">وصف الصنف  </label>
                        <div>
                            <input type="hidden" id="personal_custody_type_txt_class_id" name="personal_custody_type_txt_class_id" value="">
                            <textarea cols="100" rows="5" name="note_class_id" id="note_txt_class_id"><?php echo (count($rs)>0)? $rs['NOTE_CLASS_ID']: '' ;?></textarea>
                           <span class="field-validation-valid" data-valmsg-for="note_class_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <hr/>
                    <fieldset>
                        <legend>  بيانات الاهلاكات</legend>
                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع الاهلاك</label>
                            <div>
                                <input readonly class="form-control"  id="destruction_type_name_txt_class_id" value="<?php echo (count($rs)>0)? $rs['DESTRUCTION_TYPE_NAME']: '' ;?>"/>
                                <input type="hidden" class="form-control"  name="destruction_type" id="destruction_type_txt_class_id" value="<?php echo (count($rs)>0)? $rs['DESTRUCTION_TYPE']: '' ;?>" />
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">نسبة الإهلاك السنوية</label>
                            <div>
                                <input readonly name="destruction_percent" id="destruction_percent_txt_class_id" class="form-control" value="<?php echo (count($rs)>0)? $rs['DESTRUCTION_PERCENT']: '' ;?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم حساب مجمع الإهلاك السنوى </label>
                            <div>
                                <input readonly name="destruction_account_id" id="destruction_account_id_txt_class_id" class="form-control" value="<?php echo (count($rs)>0)? $rs['DESTRUCTION_ACCOUNT_ID']: '' ;?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">اسم حساب مجمع الإهلاك السنوى </label>
                            <div>
                                <input readonly name="destruction_account_name" id="destruction_account_id_name_txt_class_id" class="form-control" value="<?php echo (count($rs)>0)? $rs['DESTRUCTION_ACCOUNT_ID_NAME']: '' ;?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">متوسط العمر الافتراضي</label>
                            <div>
                                <input readonly name="average_life_span" id="average_life_span_txt_class_id" class="form-control" value="<?php echo (count($rs)>0)? $rs['AVERAGE_LIFE_SPAN']: '' ;?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع المتوسط</label>
                            <div>
                                <input readonly class="form-control"  id="average_life_span_type_name_txt_class_id" value="<?php echo (count($rs)>0)? $rs['AVERAGE_LIFE_SPAN_TYPE_NAME']: '' ;?>"/>
                                <input type="hidden" class="form-control"  name="average_life_span_type_txt_class_id" id="h_average_life_span_type" value="<?php echo (count($rs)>0)? $rs['AVERAGE_LIFE_SPAN_TYPE']: '' ;?>"/>
                            </div>
                        </div>
                    </fieldset>

            </fieldset>
            <fieldset>
                <legend> العهدة تابعة لعهدة أخرى؟</legend>
                <div class="form-group col-sm-3">
                    <label class="control-label">رقم العهدة</label>
                    <div>
                        <select  name="d_file_id" id="dp_d_file_id"  data-curr="false"  class="form-control" data-val="true" data-val-required="حقل مطلوب" >
                            <option value="">___________________________________</option>
                            <?php //foreach($customer_ids as $row) :?>
                             <!--   <option  value="<?= $row['CUSTOMER_ID'] ?>" <?PHP  if (count($rs)>0) {if ($row['CUSTOMER_ID']==$rs['CUSTOMER_ID']){ echo " selected"; }} ?> ><?=$row['COMPANY_DELEGATE_ID'].'-'.$row['CUSTOMER_NAME']?></option>
                         -->   <?php //endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="customer_id" data-valmsg-replace="true"></span>
                    </div>
                </div>
                </fieldset>

                <div class="modal-footer">
                    <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
                    if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                    <?php if ( !$isCreate  and $rs['ADOPT']!=0 and $rs['ADOPT']<>1) : ?>
                        <button type="button" id="print_rep" onclick="" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ))) : ?>
                        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد الاستلام  </button>
                    <?php endif; ?>
                    <?php if ( HaveAccess($public_return_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and $rs['STATUS']==1)) : ?>
                        <button type="button" onclick="javascript:return_adopt(0);" class="btn btn-danger">الغاء اعتماد الاستلام</button>
                    <?php endif; ?>
                    <?php if ($isCreate): ?>
                        <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                    <?php   endif; ?>
                    <?php if ( HaveAccess($stop_pledge_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and ( (count($rs)>0)? $rs['STATUS']==1 : '' ))) : ?>
                        <button type="button" onclick="javascript:return_adopt(2);" class="btn btn-danger">تكهين عهدة</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($lost_pledge_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) /*and ( (count($rs)>0)? $rs['SOURCE']<>1 : '' )*/ and ( (count($rs)>0)? $rs['STATUS']==1 : '' ))) : ?>
                        <button type="button" onclick="javascript:return_adopt(3);" class="btn btn-danger">فقدان عهدة</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($stop_pledge2020_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) /*and ( (count($rs)>0)? $rs['SOURCE']<>1 : '' )*/ and ( (count($rs)>0)? $rs['STATUS']==1 : '' ))) : ?>
                        <button type="button" onclick="javascript:return_adopt(6);" class="btn btn-danger"> تكهين لجنة جرد 2020</button>
                    <?php endif; ?>

                    <?php   if ( HaveAccess($onemp_pledge_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) /*and ( (count($rs)>0)? $rs['SOURCE']<>1 : '' )*/ and ( (count($rs)>0)? ($rs['STATUS']==3 || $rs['STATUS']==5) : '' ))) : ?>
                        <button type="button" onclick="javascript:return_adopt(10);" class="btn btn-success"> إعادة العهدة على الموظف</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($cancel_0)  && (!$isCreate and ( (count($rs)>0)? $rs['STATUS']==1 : '' ) and $rs['ADOPT']!=0 )) : ?>
                        <button type="button" onclick="javascript:return_adopt(5);" class="btn btn-danger">إلغاء</button>
                    <?php endif; ?>

                </div>
                <div style="clear: both;">
                    <input type="hidden" id="h_data_search" />
                </div>
                <div style="clear: both;">
                    <?php echo modules::run('settings/notes/public_get_page',(count($rs)>0)?$rs['FILE_ID']:0,'customers_pledges'); ?>
                    <?php echo (count($rs)>0)?  modules::run('attachments/attachment/index',$rs['FILE_ID'],'customers_pledges') : ''; ?>

                    </div>
                <div style="clear: both;">
                    <span id="quote"><?php echo  (count($rs)>0 && $rs['STATUS']==2 && $rs['FOLLOW_FILE_ID']!='')?  modules::run('attachments/attachment/index',$rs['FOLLOW_FILE_ID'],'customers_pledges') : ''; ?>
  </span>
                </div>
        </form>
    </div>
</div>
<?php echo modules::run('settings/notes/index'); ?>

<?php

$notes_url =notes_url();


$scripts = <<<SCRIPT
<script type="text/javascript">
  function do_after_select(){
  val=$('#personal_custody_type_txt_class_id').val();
            if ( (val !=1) && (val !=2) ){
                 $("#h_txt_class_id").val('');
                 $("#txt_class_id").val('');
                $("#unit_name_txt_class_id").val('');
                 $("#price_txt_class_id").val('');
                 $("#unit_txt_class_id").val('');
                 $("#exp_account_cust_txt_class_id").val('');
                 $("#exp_account_cust_name_txt_class_id").val('');
                 $("#destruction_type_name_txt_class_id").val('');
                 $("#destruction_type_txt_class_id").val('');
                 $("#destruction_percent_txt_class_id").val('');
                 $("#destruction_account_id_txt_class_id").val('');
                 $("#destruction_account_id_name_txt_class_id").val('');
                 $("#average_life_span_txt_class_id").val('');
                 $("#average_life_span_type_name_txt_class_id").val('');
                 $("#average_life_span_type_txt_class_id").val('');
                 $("#personal_custody_type_txt_class_id").val('');
                 danger_msg('تحذير','يجب أن يكون قسم العهدة شخصية أو إدارة');
             } 
       
  }
  //////////////////////
$('#quote a div').text('مرفقات العهدة المنقولة ');
 $('#dp_d_file_id').select2();
$('#dp_customer_id2').select2();
$('#dp_customer_id').select2();

// get_d_file_id ('800564973');
 function get_d_file_id (customer_type,customer_id,customer_idx,file_id){
var select_file_ids= "";
$.ajax({
    type: 'POST',
    url: '{$get_customer_class_details}',
    data: {customer_type:customer_type,customer_id:customer_id,customer_idx:customer_idx,file_id:file_id },
    dataType: 'json',
    success: function (data) {
     $('select[name="d_file_id"]').empty();
       select_file_ids = "<option   value='0' >-----------------------</option>";
         $('select[name="d_file_id"]').append(select_file_ids);
         $.each(data, function(index, element) {
         select_file_ids = "<option   value='"+element.FILE_ID+"' >"+'رقم العهدة /'+element.FILE_ID+':'+' رقم الصنف/'+element.CLASS_ID+':'+'اسم الصنف/ '+element.CLASS_NAME+':'+' الباركود/ '+element.BARCODE+':'+' سند صرف / '+element.CLASS_OUTPUT_ID+"</option>";
         $('select[name="d_file_id"]').append(select_file_ids);
        });
    }

});
 }

var action_type;



		var type = $('#dp_manage_st_id').val();
			$.ajax({
				type:'post',
				url:'{$cycle_url}',
				data:{id:type,file_id: $('#txt_file_id').val()},
				cache:false,
				beforeSend:function(){
    // this is where we append a loading image

   $("#dp_cycle_st_id").prop("disabled", true);
   $("#dp_department_st_id").prop("disabled", true);
    },
				success: function(returndata){
				$("#dp_cycle_st_id").prop("disabled", false);
				$('#dp_cycle_st_id').html(returndata);
				}
			});





		var type = $('#dp_cycle_st_id').val();
			$.ajax({
				type:'post',
				url:'{$department_url}',
				data:{id:type,file_id: $('#txt_file_id').val()},
				cache:false,
				beforeSend:function(){
    // this is where we append a loading image
   // $("#dp_department_st_id").val('');
   $("#dp_department_st_id").prop("disabled", true);
    },

				success: function(returndata){
				$("#dp_department_st_id").prop("disabled", false);
				$('#dp_department_st_id').html(returndata);
				}
			});



    // Mkilani
  /*  $('#customer_div_2').hide(0);
    customer_type_change();
    
    $('#dp_customer_type').change(function(){
        customer_type_change();
    });
    
    function customer_type_change(){
        var type_= $('#dp_customer_type').val();
        if(type_==4){
            $('#customer_div_2').show(500);
            $('#customer_div_1').hide(500);
        }else {
            $('#customer_div_1').show(500);
            $('#customer_div_2').hide(500);
        }
    }*/
    // Mkilani


function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

      /*   $('#dp_customer_id2').select2().on('change',function(){
get_d_file_id ($('#dp_customer_type').val(),$('#dp_customer_id').val(),$('#dp_customer_id2').val(),$('#txt_file_id').val());
     });*/ 
          $('#dp_customer_id').select2().on('change',function(){
get_d_file_id ($('#dp_customer_type').val(),$('#dp_customer_id').val(),$('#dp_customer_id2').val(),$('#txt_file_id').val());
  
       get_data('{$get_customer_url}',{id:$('#dp_customer_id').val()},function(data){

       if(Object.keys(data).length>=1)
       {  
       var emp_info=data[0];
       $('#txt_room_id').val(emp_info.ROOM_ID);
        $('#txt_room_name').val(emp_info.ROOM_NAME);
       $('#dp_manage_st_id').select2("val",emp_info.MANAGE); //set the value
var type = $('#dp_manage_st_id').val();
			$.ajax({
				type:'post',
				url:'{$cycle_url}',
				data:{id:type},
				cache:false,
				beforeSend:function(){
    // this is where we append a loading image

   $("#dp_cycle_st_id").prop("disabled", true);
   $("#dp_department_st_id").prop("disabled", true);
    },
				success: function(returndata){
				$("#dp_cycle_st_id").prop("disabled", false);
				$('#dp_cycle_st_id').html(returndata);
				$('#dp_cycle_st_id').select2("val",emp_info.CYCLE); //set the value
				}

			});


			var cycle = emp_info.CYCLE;
			$.ajax({
				type:'post',
				url:'{$department_url}',
				data:{id:cycle},
				cache:false,
				beforeSend:function(){
    // this is where we append a loading image
    $("#dp_department_st_id").val('');
   $("#dp_department_st_id").prop("disabled", true);
    },

				success: function(returndata){
				$("#dp_department_st_id").prop("disabled", false);
				$('#dp_department_st_id').html(returndata);
				$('#dp_department_st_id').select2("val",emp_info.DEPT); //set the value
				}
			});




       }else {
        $('#txt_room_id').val('');
        $('#txt_room_name').val('');
         $('#dp_manage_st_id').select2("val",'');
        $('#dp_cycle_st_id').select2("val",'');
        $('#dp_department_st_id').select2("val",''); 
       }






                });

        });
          $('#dp_manage_st_id').select2().on('change',function(){
          	$('#dp_cycle_st_id').select2("val",''); //set the value
          		$('#dp_department_st_id').select2("val",''); //set the value

        });
         $('#dp_cycle_st_id').select2().on('change',function(){
         	$('#dp_department_st_id').select2("val",''); //set the value

        });
          $('#dp_department_st_id').select2().on('change',function(){

        });

            $('#dp_class_code_ser').select2().on('change',function(){

        });

       function return_adopt(type){

           /* if(type == 0 && ! confirm('هل تريد إرجاع العهدة ؟!')){
                return;
            }

            if(type == 1 && ! confirm('هل تريد إعتماد العهدة ؟!')){
                return;
            }

             if(type == 2 && ! confirm('هل تريد تكهين العهدة ؟!')){
                return;
            }*/

            action_type = type; 
            $('#notesModal').modal();

       }

        function apply_action(){ 
                if(action_type == 1){

                    get_data('{$adopt_url}',{id:{$fid}},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم اعتماد العهدة بنجاح ..');
                            reload_Page();
                    },'html');

                }
                            if(action_type == 2){
                              if($('#txt_g_notes').val() =='' ){
                        alert('تحذير : لم تذكر سبب التكهين؟!!');
                        return;
                    }

                    get_data('{$stop_pledge_url}',{id:{$fid}},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم تكهين العهدة بنجاح ..');
                            reload_Page();
                    },'html');

                }
                if(action_type == 3){
                              if($('#txt_g_notes').val() =='' ){
                            alert('تحذير : لم تذكر سبب الفقدان العهدة؟!!');
                        return;
                    }

                    get_data('{$lost_pledge_url}',{id:{$fid}},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم تسجيل العهدة مفقودة بنجاح ..');
                            reload_Page();
                    },'html');

                }
                
                
                
                if(action_type == 5){
                    if($('#txt_g_notes').val() =='' ){
                        alert('تحذير : لم تذكر سبب الإلغاء؟!!');
                        return;
                    }
                    
                    get_data('{$cancel_0}',{id:{$fid}},function(data){
                  
                        if(data =='1')
                            success_msg('رسالة','تم إالغاء العملية بنجاح ..');
                        reload_Page();
                    },'html');
                
                }
                
                   if(action_type == 6){
                              if($('#txt_g_notes').val() =='' ){
                        alert('تحذير : لم تذكر سبب التكهين؟!!');
                        return;
                    }

                    get_data('{$stop_pledge2020_url}',{id:{$fid}},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم التكهين حسب لجنة جرد 2020 بنجاح ..');
                            reload_Page();
                    },'html');

                }
                
                if(action_type == 10){
                              if($('#txt_g_notes').val() =='' ){
                        alert('تحذير : لم تذكر سبب الإعادة على الموظف؟!!');
                        return;
                    }

                    get_data('{$onemp_pledge_url}',{id:{$fid}},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم إعادة العهدة على الموظف بنجاح ..');
                            reload_Page();
                    },'html');

                }
                
                
if(action_type == 0) {

                    if($('#txt_g_notes').val() =='' ){
                        alert('تحذير : لم تذكر سبب الإرجاع ؟!!');
                        return;
                    }
                    get_data('{$public_return_url}',{id:{$fid}},function(data){
                        if(data =='1')
                            success_msg('رسالة','تم  إرجاع العهدة بنجاح ..');
                            reload_Page();
                    },'html');
                }

                get_data('{$notes_url}',{source_id:{$fid},source:'customers_pledges',notes:$('#txt_g_notes').val()},function(data){
                    $('#txt_g_notes').val('');
                },'html');

                $('#notesModal').modal('hide');


        }

$(document).ready(function() {
$( "#print_rep" ).on( 'click', function (){
 _showReport('$report_url'+'report=store_rep/own_customer_rep&params[]=&params[]='+$('#txt_file_id').val());


});

 $('#txt_class_id').bind("focus",function(e){
    if($('#txt_class_code_ser').val()!='')
    {
    return;
    }
        _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val());
    });
    $('#h_txt_class_id').bind('keyup', '+', function(e) {
    if($('#txt_class_code_ser').val()!='')
    {
    return;
    }
    else
    {
   $(this).val( $(this).val().replace('+', ''));
  _showReport('$select_items_url/'+'txt_class_id'+ $('#h_data_search').val());
  }
           });
      $("#h_txt_class_id").change(function(e){

        var id_v=$(this).val();
                 get_data('{$get_class_url}',{id:id_v, type:1},function(data){
                if (data.length == 1){
                var item= data[0];
$("#txt_class_id").val(item.CLASS_NAME_AR);
$("#unit_name_txt_class_id").val(item.UNIT_NAME);
$("#price_txt_class_id").val(item.CLASS_PURCHASING);
$("#unit_txt_class_id").val(item.CLASS_UNIT_SUB);
$("#exp_account_cust_txt_class_id").val(item.EXP_ACCOUNT_CUST);
$("#exp_account_cust_name_txt_class_id").val(item.EXP_ACCOUNT_CUST_NAME);
$("#destruction_type_name_txt_class_id").val(item.DESTRUCTION_TYPE_NAME);
$("#destruction_type_txt_class_id").val(item.DESTRUCTION_TYPE);
$("#destruction_percent_txt_class_id").val(item.DESTRUCTION_PERCENT);
$("#destruction_account_id_txt_class_id").val(item.DESTRUCTION_ACCOUNT_ID);
$("#destruction_account_id_name_txt_class_id").val(item.DESTRUCTION_ACCOUNT_ID_NAME);
$("#average_life_span_txt_class_id").val(item.AVERAGE_LIFE_SPAN);
$("#average_life_span_type_name_txt_class_id").val(item.AVERAGE_LIFE_SPAN_TYPE_NAME);
$("#average_life_span_type_txt_class_id").val(item.AVERAGE_LIFE_SPAN_TYPE);
$("#personal_custody_type_txt_class_id").val(item.PERSONAL_CUSTODY_TYPE);
do_after_select();
          }else{
                  $("#txt_class_id").val('');
                $("#unit_name_txt_class_id").val('');
                 $("#price_txt_class_id").val('');
                 $("#unit_txt_class_id").val('');
                 $("#exp_account_cust_txt_class_id").val('');
                 $("#exp_account_cust_name_txt_class_id").val('');
                 $("#destruction_type_name_txt_class_id").val('');
                 $("#destruction_type_txt_class_id").val('');
                 $("#destruction_percent_txt_class_id").val('');
                 $("#destruction_account_id_txt_class_id").val('');
                 $("#destruction_account_id_name_txt_class_id").val('');
                 $("#average_life_span_txt_class_id").val('');
                 $("#average_life_span_type_name_txt_class_id").val('');
                 $("#average_life_span_type_txt_class_id").val('');
                  $("#personal_custody_type_txt_class_id").val('');
                }
         });
    });

    $("#txt_class_code_ser").change(function(e){

        var ser_v=$(this).val();
       //  alert(ser_v);
       if (ser_v!='')
       {

         $('#h_txt_class_id').prop('readonly', true);//jQuery <1.9
                $('#h_txt_class_id').attr('readonly', true);//jQuery 1.9+
        get_data('{$get_code_url}',{id:ser_v},function(data){

                if (data.length == 1){
    var item= data[0];
$("#h_txt_class_id").val(item.CLASS_ID);
$("#txt_class_id").val(item.CLASS_NAME_AR);
$("#unit_name_txt_class_id").val(item.UNIT_NAME);
$("#price_txt_class_id").val(item.CLASS_PURCHASING);
$("#unit_txt_class_id").val(item.CLASS_UNIT_SUB);
$("#exp_account_cust_txt_class_id").val(item.EXP_ACCOUNT_CUST);
$("#exp_account_cust_name_txt_class_id").val(item.EXP_ACCOUNT_CUST_NAME);
$("#destruction_type_name_txt_class_id").val(item.DESTRUCTION_TYPE_NAME);
$("#destruction_type_txt_class_id").val(item.DESTRUCTION_TYPE);
$("#destruction_percent_txt_class_id").val(item.DESTRUCTION_PERCENT);
$("#destruction_account_id_txt_class_id").val(item.DESTRUCTION_ACCOUNT_ID);
$("#destruction_account_id_name_txt_class_id").val(item.DESTRUCTION_ACCOUNT_ID_NAME);
$("#average_life_span_txt_class_id").val(item.AVERAGE_LIFE_SPAN);
$("#average_life_span_type_name_txt_class_id").val(item.AVERAGE_LIFE_SPAN_TYPE_NAME);
$("#average_life_span_type_txt_class_id").val(item.AVERAGE_LIFE_SPAN_TYPE);
$("#note_txt_class_id").text(item.CALSS_DESCRIPTION);
$("#personal_custody_type_txt_class_id").val(item.PERSONAL_CUSTODY_TYPE);
do_after_select();
                }else{
                 $("#h_txt_class_id").val('');
                 $("#txt_class_id").val('');
                $("#unit_name_txt_class_id").val('');
                 $("#price_txt_class_id").val('');
                 $("#unit_txt_class_id").val('');
                 $("#exp_account_cust_txt_class_id").val('');
                 $("#exp_account_cust_name_txt_class_id").val('');
                 $("#destruction_type_name_txt_class_id").val('');
                 $("#destruction_type_txt_class_id").val('');
                 $("#destruction_percent_txt_class_id").val('');
                 $("#destruction_account_id_txt_class_id").val('');
                 $("#destruction_account_id_name_txt_class_id").val('');
                 $("#average_life_span_txt_class_id").val('');
                 $("#average_life_span_type_name_txt_class_id").val('');
                 $("#average_life_span_type_txt_class_id").val('');
                 $("#note_txt_class_id").text('');
                   $("#personal_custody_type_txt_class_id").val('');
                }
        });
        }
        else
        {
          $('#h_txt_class_id').prop('readonly', false);//jQuery <1.9
                $('#h_txt_class_id').attr('readonly', false);//jQuery 1.9+
        }
        });

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
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


$('#dp_manage_st_id').change(function(){
		var type = $('#dp_manage_st_id').val();
			$.ajax({
				type:'post',
				url:'{$cycle_url}',
				data:{id:type},
				cache:false,
				beforeSend:function(){
    // this is where we append a loading image

   $("#dp_cycle_st_id").prop("disabled", true);
   $("#dp_department_st_id").prop("disabled", true);
    },
				success: function(returndata){
				$("#dp_cycle_st_id").prop("disabled", false);
				$('#dp_cycle_st_id').html(returndata);
				}
			});


	});

$('#dp_cycle_st_id').change(function(){
		var type = $('#dp_cycle_st_id').val();
			$.ajax({
				type:'post',
				url:'{$department_url}',
				data:{id:type},
				cache:false,
				beforeSend:function(){
    // this is where we append a loading image
    $("#dp_department_st_id").val('');
   $("#dp_department_st_id").prop("disabled", true);
    },

				success: function(returndata){
				$("#dp_department_st_id").prop("disabled", false);
				$('#dp_department_st_id').html(returndata);
				}
			});


	});





});


 if($('#txt_file_id').val() != ''){
   var select_file_ids= "";
$.ajax({
    type: 'POST',
    url: '{$get_customer_class_details}',
    data: {customer_id:$('#dp_customer_id').val(),file_id:$('#txt_file_id').val() },
    dataType: 'json',
    success: function (data) {
     $('select[name="d_file_id"]').empty();
       select_file_ids = "<option   value='0' >-----------------------</option>";
         $('select[name="d_file_id"]').append(select_file_ids);
         $.each(data, function(index, element) {
         if ('{$d_file_id}'==element.FILE_ID)
         select_file_ids = "<option   value='"+element.FILE_ID+"' selected >"+'رقم العهدة /'+element.FILE_ID+':'+' رقم الصنف/'+element.CLASS_ID+':'+'اسم الصنف/ '+element.CLASS_NAME+':'+' الباركود/ '+element.BARCODE+':'+' سند صرف / '+element.CLASS_OUTPUT_ID+"</option>";
         else
         select_file_ids = "<option   value='"+element.FILE_ID+"' >"+'رقم العهدة /'+element.FILE_ID+':'+' رقم الصنف/'+element.CLASS_ID+':'+'اسم الصنف/ '+element.CLASS_NAME+':'+' الباركود/ '+element.BARCODE+':'+' سند صرف / '+element.CLASS_OUTPUT_ID+"</option>";

         $('select[name="d_file_id"]').append(select_file_ids);
        });
          $('select[name="d_file_id"]').change();
    }

});
   }

function get_link_create(){
get_to_link( '{$create_url}/'+ $('#dp_customer_id').val()) ;
}

if (('{$action}'=='index') && ($('#dp_customer_id').val()!='')){
$('#dp_customer_id').change();
}



// get_d_file_id ('800564973');
 function get_d_file_id_edit (d_file_id,customer_type,customer_id,customer_idx,file_id){
var select_file_ids= "";
$.ajax({
    type: 'POST',
    url: '{$get_customer_class_details}',
    data: {customer_type:customer_type,customer_id:customer_id,customer_idx:customer_idx,file_id:file_id },
    dataType: 'json',
    success: function (data) {
     $('select[name="d_file_id"]').empty();
       select_file_ids = "<option   value='0' >-----------------------</option>";
         $('select[name="d_file_id"]').append(select_file_ids);
         $.each(data, function(index, element) {
        if(d_file_id==element.FILE_ID)
        select_file_ids = "<option   value='"+element.FILE_ID+"' selected >"+'رقم العهدة /'+element.FILE_ID+':'+' رقم الصنف/'+element.CLASS_ID+':'+'اسم الصنف/ '+element.CLASS_NAME+':'+' الباركود/ '+element.BARCODE+':'+' سند صرف / '+element.CLASS_OUTPUT_ID+"</option>";
        else
          select_file_ids = "<option   value='"+element.FILE_ID+"' >"+'رقم العهدة /'+element.FILE_ID+':'+' رقم الصنف/'+element.CLASS_ID+':'+'اسم الصنف/ '+element.CLASS_NAME+':'+' الباركود/ '+element.BARCODE+':'+' سند صرف / '+element.CLASS_OUTPUT_ID+"</option>";
         $('select[name="d_file_id"]').append(select_file_ids);
        $('select[name="d_file_id"]').change();
        });
    }

});
 }

if('{$action}' != 'index'){ 
get_d_file_id_edit ('{$d_file_id}',$('#dp_customer_type').val(),$('#dp_customer_id').val(),$('#dp_customer_id2').val(),$('#txt_file_id').val());
}
</script>

SCRIPT;

sec_scripts($scripts);

?>

