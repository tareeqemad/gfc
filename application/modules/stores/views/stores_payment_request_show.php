<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/12/14
 * Time: 10:16 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_payment_request';

if($action=='archive')
    $archive=1;
else
    $archive=0;

$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); //$action

$isCreate =isset($request_data) && count($request_data)  > 0 ?false:true;
$rs=$isCreate ?array() : $request_data[0];

$donation_file_id=(isset($rs['DONATION_FILE_ID']) )? $rs['DONATION_FILE_ID'] :0;

$select_items_url=base_url("$MODULE_NAME/classes/public_index");
$select_donation_items_url=base_url("donations/donation/public_index");

$request_input= (!isset($request_input) and count($rs)>0 ) ? $rs['REQUEST_INPUT'] :$request_input;
$project_data= isset($project_data)?$project_data: array();

if($request_input==3){
    $stores= $customer_stores;
}

if(isset($project_data['HINTS']))
    $project_data['HINTS']= trim(preg_replace('/\s\s+/', ' ', $project_data['HINTS']));

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$back_later_url= '';
if($archive){
    $post_url= base_url("$MODULE_NAME/$TB_NAME/error_404");
    $back_later_url= '/1/1';
}

$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$request_url= base_url("$MODULE_NAME/$TB_NAME/get");

$adopt_0_url= base_url("$MODULE_NAME/$TB_NAME/cancel_request");
$adopt_t_url= base_url("$MODULE_NAME/$TB_NAME/technical_adopt");
$adopt_s_url= base_url("$MODULE_NAME/$TB_NAME/store_adopt");
$adopt_a_url= base_url("$MODULE_NAME/$TB_NAME/account_adopt");
$adopt_f_url= base_url("$MODULE_NAME/$TB_NAME/financial_adopt");
$adopt_m_url= base_url("$MODULE_NAME/$TB_NAME/manger_adopt");

$adopt_bs_url= base_url("$MODULE_NAME/$TB_NAME/bs_adopt");
$adopt_bf_url= base_url("$MODULE_NAME/$TB_NAME/bf_adopt");
$adopt_bm_url= base_url("$MODULE_NAME/$TB_NAME/bm_adopt");

$adopt_cancel_url= base_url("$MODULE_NAME/$TB_NAME/cancel_adopt");
$reserve_url= base_url("$MODULE_NAME/$TB_NAME/reserve");
$btn_reserve='';

$transport_url= base_url("$MODULE_NAME/stores_class_transport/create");
$output_url= base_url("$MODULE_NAME/stores_class_output/create");
$project_url= base_url("projects/projects/get");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$project_accounts_url = str_replace("/gfc","/Technical",base_url('projects/projects/public_select_project_accounts'));
/*$project_accounts_url =base_url('projects/projects/public_select_project_accounts');*/
$get_class_url =base_url('stores/classes/public_get_id');
$select_rooms_url =base_url('pledges/rooms_structure_tree/public_select_rooms');
$room_cus_url= base_url('pledges/rooms_structure_tree/public_get_room_by_id');

$store_serv_req_url =base_url("$MODULE_NAME/$TB_NAME/serv_get");

$get_details_url =base_url("$MODULE_NAME/$TB_NAME/public_get_details");

//$donation_url =base_url('donations/donation/public_get_donation_by_store_input');
$donation_url =base_url('donations/donation/public_get_donation_by_store_id');
$donation_view_url=base_url("donations/donation/get");

// الانواع التي يتم عليها اعتماد الادارة الفنية - الجهة الطالبة
// تم اضافة 9,10,12 بناء على طلب هشام حراراة بتاريخ 12-2-2015
// تم اضافة 19 بناء على طلب هشام حراراة بتاريخ 29-4-2015
// تم اضافة 17 بناء على طلب هشام حراراة بتاريخ 16-06-2015
// تم اضافة 13 بناء على طلب هشام حراراة بتاريخ 05-08-2015
// تم اضافة 20 بناء على طلب سليمان ابو مدين بتاريخ 08-08-2015
// تم اضافة 23 بناء على طلب هشام حراراة بتاريخ 09-12-2015
// تم اضافة 7 بناء على طلب هشام حراراة بتاريخ 16-12-2015
// تم تفعيل اعتماد الجهة الطالبة لجميع الطلبات بناء على طلب هشام حرارة في تاريخ 20/1/2016
$request_type_technical= array(1,2,4,5,6,  9,10,12,  19,  17, 13, 20, 23, 7);

$print_url = gh_itdev_rep_url().'/gfc.aspx?data='.get_report_folder().'&' ;
$print_project_url =  base_url('/reports');

$gfc_domain= gh_gfc_domain();

$can_cancel_adopt= 0;
if( HaveAccess($adopt_cancel_url) and !$isCreate and $rs['ADOPT'] >=1 and $rs['ADOPT'] <=4 ){
    $url_array= array($post_url,$adopt_s_url,$adopt_a_url,$adopt_f_url, $adopt_bf_url,$adopt_bm_url);
    $adopt_array= array(1,2,3,4, 2,3);
    for($i=0;$i<count($adopt_array);$i++){
        if(HaveAccess($url_array[$i]) and $adopt_array[$i]==$rs['ADOPT']){
            $can_cancel_adopt= 1;
            break;
        }
    }
}

$can_cancel_req= 0;
if ( HaveAccess($adopt_0_url) and !$isCreate and $rs['ADOPT']!=0 ){
    $can_cancel_req= 1;
}
$report_url = base_url("JsperReport/showreport?sys=technical");
$report_fin_url = base_url("JsperReport/showreport?sys=financial/archives");
echo AntiForgeryToken();

?>

<style>
    .select2-results .select2-disabled,  .select2-results__option[aria-disabled=true] {
        display: none;
    }
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if(!$isCreate and $rs['ADOPT']==6): ?><li><a target="_blank" href="<?=base_url("stores/stores_class_transport/index/1/-1/-1/{$rs['REQUEST_NO']}")?>"><i class="glyphicon glyphicon-new-window"></i>سندات المناقلة </a> </li><?php endif; ?>
            <?php if(!$isCreate and $rs['ADOPT']==6): ?><li><a target="_blank" href="<?=base_url("stores/stores_class_output/index/1/-1/1/{$rs['REQUEST_NO']}")?>"><i class="glyphicon glyphicon-new-window"></i>سندات الصرف </a> </li><?php endif; ?>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url.$back_later_url?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
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
                    <label class="control-label">رقم الطلب</label>
                    <div>
                        <input type="text" readonly id="txt_request_no" class="form-control ">
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" name="request_no" id="h_request_no">
                        <?php endif; ?>
                        <input type="hidden" name="request_input" id="h_request_input" value="<?=encryption_case($request_input,1)?>" />
                        <input type="hidden" name="project_serial" id="h_project_serial" />
                    </div>
                </div>

                <div class="form-group col-sm-1"  id="donation" style="display: none">
                    <label class="control-label"> رقم المنحة <a target="_blank" style="cursor: pointer" href="javascript:;" ><i class="glyphicon glyphicon-new-window"></i></a> </label>
                    <div>
                        <input type="text" readonly name="donation_file_id" id="txt_donation_file_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الجهة الطالبة</label>
                    <div>
                        <select name="request_side" id="dp_request_side" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($request_side as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="request_side" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">حساب الجهة الطالبة</label>
                    <div>
                        <input type="text" data-val="false" readonly data-val-required="required" class="form-control" id="txt_request_side_account" />
                        <input type="hidden" name="request_side_account" id="h_txt_request_side_account" />
                        <span class="field-validation-valid" data-valmsg-for="request_side_account" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2" id="div_customer_account_type" style="display: none">
                    <label class="control-label">نوع حساب المستفيد</label>
                    <div>
                        <select name="customer_account_type" id="dp_customer_account_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($customer_account_type as $row) :?>
                                <option disabled value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="customer_account_type" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2" style="display: none" id="div_output_update_account_cnt">
                    <label class="control-label">تنويه</label>
                    <div>
                        <input type="text" readonly value="تم تعديل الحساب في سندات الصرف" class="form-control ">
                    </div>
                </div>

                <div id="div_customer_account_type1" style="display: none">
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الغرفة</label>
                        <div>
                            <input type="text" readonly id="txt_room_id" class="form-control" >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم الغرفة</label>
                        <div>
                            <input type="text" readonly id="txt_room_name" class="form-control" >
                        </div>
                    </div>
                </div>

                <div style="clear: both"></div>

                <input type="hidden" name="room_id" id="h_room_id" class="form-control" >

                <div class="form-group col-sm-2">
                    <label class="control-label"> المخزن المطلوب منه </label>
                    <div>
                        <select name="request_store_from" id="dp_request_store_from" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($stores as $row) :?>
                                <option data-account_id="<?=$row['ACCOUNT_ID']?>" data-account_name="<?=$row['ACCOUNT_ID_NAME']?>" data-isdonation="<?=$row['ISDONATION']?>"  value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="request_store_from" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الطلب</label>
                    <div>
                        <select name="request_type" id="dp_request_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($request_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="request_type" data-valmsg-replace="true"></span>
                    </div>
                </div>


                <div class="form-group col-sm-2" id="project" style="display: none">
                    <label class="control-label"> رقم المشروع <a target="_blank" style="cursor: pointer" href="javascript:;" ><i class="glyphicon glyphicon-new-window"></i></a> </label>
                    <div>
                        <input type="text" name="project_id" data-val="false"  data-val-required="حقل مطلوب"  id="txt_project_id" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="project_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2" id="project_old" style="display: none">
                    <label class="control-label">رقم المشروع القديم</label>
                    <div>
                        <input type="text" name="project_id_old" readonly data-val="false"  data-val-required="حقل مطلوب"  id="txt_project_id_old" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم الطلبية من الخدمات <a style="cursor: pointer" id="a_store_serv_req" ><i class="glyphicon glyphicon-new-window"></i></a> </label>
                    <div>
                        <input type="text" name="store_serv_req" data-val="false" id="txt_store_serv_req" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label">بيان</label>
                    <div>
                        <input type="text" name="notes" data-val="false"  data-val-required="حقل مطلوب"  id="txt_notes" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="notes" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <input type="text" name="notes2" data-val="false"  data-val-required="حقل مطلوب"  id="txt_notes2" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="notes2" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-9" id="cancel_note" style="display: none">
                    <label class="control-label">بيان الارجاع / الالغاء</label>
                    <div>
                        <input type="text" name="cancel_note" id="txt_cancel_note" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-9" id="cancel_request_note" style="display: none">
                    <label class="control-label">بيان الالغاء</label>
                    <div>
                    </div>
                </div>

                <div class="form-group col-sm-9" id="entry_note" style="display: none">
                    <label class="control-label">بيان ارجاع المدخل </label>
                    <div>
                    </div>
                </div>

                <div class="form-group col-sm-9" id="technical_note" style="display: none">
                    <label class="control-label">بيان ارجاع الجهة الطالبة </label>
                    <div>
                    </div>
                </div>

                <div class="form-group col-sm-9" id="store_note" style="display: none">
                    <label class="control-label">ملاحظة رقابة المخازن / المدير المالي</label>
                    <div>
                    </div>
                </div>

                <div class="form-group col-sm-9" id="account_note" style="display: none">
                    <label class="control-label">بيان ارجاع دائرة الحسابات </label>
                    <div>
                    </div>
                </div>

                <div class="form-group col-sm-9" id="stores_dept_note" style="display: none">
                    <label class="control-label">بيان ارجاع الادارة المالية / مدير المقر  </label>
                    <div>
                    </div>
                </div>


                <div style="clear: both"></div>

                <input type="hidden" id="h_data_search" />

                <div id="details_div">
                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", $request_input, (count($rs))?$rs['REQUEST_NO']:0, (count($project_data))?$project_data['PROJECT_SERIAL']:0, (count($rs))?$rs['ADOPT']:0 ); ?>
                </div>

                <div style="clear: both"></div>

                <div id="adopt_data" style="display: none" >
                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم المدخل</label>
                        <div><?=$rs['ENTRY_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ الادخال</label>
                        <div><?=$rs['ENTRY_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم معتمد الجهة الطالبة</label>
                        <div><?=$rs['TECHNICAL_DEPT_ADOPT_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ اعتماد الجهة الطالبة</label>
                        <div><?=$rs['TECHNICAL_DEPT_ADOPT_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم معتمد رقابة المخازن/المدير المالي</label>
                        <div><?=$rs['STORE_ADOPT_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ اعتماد رقابة المخازن/المدير المالي</label>
                        <div><?=$rs['STORE_ADOPT_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم معتمد دائرة الحسابات </label>
                        <div><?=$rs['ACCOUNT_ADOPT_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ اعتماد دائرة الحسابات </label>
                        <div><?=$rs['ACCOUNT_ADOPT_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم معتمد الادارة المالية/مدير المقر </label>
                        <div><?=$rs['STORES_DEPT_ADOPT_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ اعتماد الادارة المالية/مدير المقر </label>
                        <div><?=$rs['STORES_DEPT_ADOPT_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم معتمد مدير عام الشركة</label>
                        <div><?=$rs['MANGER_ADOPT_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ اعتماد مدير عام الشركة </label>
                        <div><?=$rs['MANGER_ADOPT_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم ملغي الطلب </label>
                        <div><?=$rs['CANCEL_REQUEST_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ الغاء الطلب </label>
                        <div><?=$rs['CANCEL_REQUEST_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">حالة الطلب</label>
                        <div><?=$rs['ADOPT_NAME']?></div>
                    </div>
                </div>

            </div>


            <div class="modal-footer">


                <?php if ( !$isCreate  and $rs['ADOPT']!=0 ) : ?>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                    <button type="button" onclick="$('#details_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                <?php endif; ?>

                <?php if ( !$isCreate and $rs['ADOPT']!=0 and ($rs['REQUEST_INPUT']==2 or $rs['REQUEST_INPUT']==3) ) : ?>
                   <!-- <button type="button" onclick="javascript:_showReport('<?=$print_project_url?>?report=project_files_rep&params[]=<?=$rs['PROJECT_SERIAL']?>&params[]=1');" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> جدول الكميات </button>-->
                    <button type="button"
                            onclick="javascript:print_project_ar(<?= $rs['PROJECT_SERIAL'] ?>)"
                            class="btn btn-success"> جدول الكميات
                    </button>
                    <button type="button"
                            onclick="javascript:_showReport('<?= $report_url ?>&report=project_return_items_pdf&p_project_ser=<?= $rs['PROJECT_SERIAL'] ?>');"
                            class="btn btn-danger"> المواد المرجعة
                    </button>

                <?php endif; ?>

                <?php if ($archive!=1){ ?>

                <?php if ( !$isCreate and $rs['ADOPT']!=0 and ($rs['REQUEST_INPUT']==2 or $rs['REQUEST_INPUT']==3) ) : ?>
                    <span id="project_attachment"><?php echo modules::run('attachments/attachment/index',$rs['PROJECT_SERIAL'],'projects'); ?></span>
                <?php endif; ?>

                <?php if ( !$isCreate and $rs['ADOPT']!=0 and ($rs['REQUEST_INPUT']==1 or $rs['REQUEST_INPUT']==4) ) : ?>
                    <span><?php echo modules::run('attachments/attachment/index',$rs['REQUEST_NO'],'stores_payment_request'); ?></span>
                <?php endif; ?>

                <?php if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( $can_cancel_req ) : ?>
                    <button type="button" id="btn_0_adopt" onclick='javascript:adopt(0);' class="btn btn-danger">الغاء الطلب</button>
                <?php endif; ?>

                <?php if ( HaveAccess($reserve_url) and !$isCreate and $rs['ADOPT']== 6 ) :
                    if($rs['RESERVE']==2)
                        $btn_reserve='الغاء الحجز';
                    else
                        $btn_reserve='اعادة الحجز';
                ?>
                    <button type="button" id="btn_reserve" onclick='javascript:reserve();' class="btn btn-info"><?=$btn_reserve?></button>
                <?php endif; ?>

                <?php if ( $can_cancel_adopt ) : ?>
                    <button type="button" id="btn_cancel_adopt" onclick='javascript:cancel_adopt();' class="btn btn-danger">ارجاع</button>
                <?php endif; ?>


                <?php if ( HaveAccess($adopt_t_url) and !$isCreate and $rs['ADOPT']==1 and $rs['BRANCH']==1 ) : // and in_array($rs['REQUEST_TYPE'],$request_type_technical) - تم تفعيل اعتماد الجهة الطالبة لجميع الطلبات بناء على طلب هشام حرارة في تاريخ 20/1/2016  ?>
                    <button type="button" id="btn_t_adopt" onclick='javascript:adopt(1);' class="btn btn-success">اعتماد الجهة الطالبة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_s_url) and !$isCreate and ($rs['ADOPT']==2 or (0 and $rs['ADOPT']==1 and !in_array($rs['REQUEST_TYPE'],$request_type_technical))) and $rs['BRANCH']==1 ) : ?>
                    <button type="button" id="btn_s_adopt" onclick='javascript:adopt(2);' class="btn btn-success">اعتماد رقابة المخازن</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_a_url) and !$isCreate and $rs['ADOPT']==3 and $rs['BRANCH']==1 ) : ?>
                    <button type="button" id="btn_a_adopt" onclick='javascript:adopt(3);' class="btn btn-success">اعتماد دائرة الحسابات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_f_url) and !$isCreate and $rs['ADOPT']==4 and $rs['BRANCH']==1 ) : ?>
                    <button type="button" id="btn_f_adopt" onclick='javascript:adopt(4);' class="btn btn-success">اعتماد الادارة المالية</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_m_url) and !$isCreate and $rs['ADOPT']==5 and $rs['GENERAL_MANAGER']==1 and $rs['BRANCH']==1 ) : ?>
                    <button type="button" id="btn_m_adopt" onclick='javascript:adopt(5);' class="btn btn-success">اعتماد مدير عام الشركة</button>
                <?php endif; ?>


                <?php if ( HaveAccess($adopt_bs_url) and !$isCreate and $rs['ADOPT']==1 and $rs['BRANCH']!=1 ) : ?>
                    <button type="button" id="btn_bs_adopt" onclick='javascript:adopt(10);' class="btn btn-success"> اعتماد الجهة الطالبة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_bf_url) and !$isCreate and $rs['ADOPT']==2 and $rs['BRANCH']!=1 ) : ?>
                    <button type="button" id="btn_bf_adopt" onclick='javascript:adopt(11);' class="btn btn-success">اعتماد المدير المالي</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_bm_url) and !$isCreate and $rs['ADOPT']==3 and $rs['BRANCH']!=1 ) : ?>
                    <button type="button" id="btn_bm_adopt" onclick='javascript:adopt(12);' class="btn btn-success">اعتماد مدير المقر</button>
                <?php endif; ?>


                <?php if ( HaveAccess($transport_url) and !$isCreate and $rs['ADOPT']==6) : ?>
                    <button type="button" onclick='javascript:request_transport();' class="btn btn-success">مناقلة الطلب</button>
                <?php endif; ?>

                <?php if ( HaveAccess($output_url) and !$isCreate and $rs['ADOPT']==6) : ?>
                    <button type="button" onclick='javascript:request_output();' class="btn btn-success">صرف الطلب</button>
                <?php endif; ?>

                <?php if ($isCreate and 0 ): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php   endif; ?>
                <?php } ?>
            </div>
        </form>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>

    if(balance_req_msg!='')
        warning_msg('لقد وصل رصيد الاصناف التالية لحد اعادة الطلب:',balance_req_msg);

    if(balance_msg!=''){
        danger_msg('لقد وصل رصيد الاصناف التالية للحد الادنى:',balance_msg);
/*
        var sub= 'الحد الادنى للاصناف طلب صرف $ rs['BOOK_NO']';
        var text= 'لقد وصل رصيد الاصناف التالية للحد الادنى:';
        text+= '<br>'+ balance_msg;
        text+= '<br>للاطلاع افتح الرابط';
        text+= '<br>https://gs.gedco.ps$ request_url/$ rs['REQUEST_NO']';
        _send_mail('$ class_min_email',sub,text);

*/
    }

    var count = 0;

    var class_unit_json= {$class_unit};
    var select_class_unit= '';

    var class_type_json= {$class_type};
    var select_class_type= '';

    function do_first(Bind){
        if(Bind==undefined)
            Bind=0;

        $.each(class_unit_json, function(i,item){
            select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
        });

        $.each(class_type_json, function(i,item){
            select_class_type += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
        });

        if(Bind==1)
            reBind();

        $('select[name="class_unit[]"]').append(select_class_unit);
        $('select[name="class_type[]"]').append(select_class_type);

        $('select[name="class_unit[]"]').each(function(){
            $(this).val($(this).attr('data-val'));
        });

        $('select[name="class_type[]"]').each(function(){
            $(this).val($(this).attr('data-val'));
        });
        $('select[name="class_unit[]"] ,select[name="class_type[]"] , #dp_request_side , #dp_request_store_from , #dp_request_type, #dp_customer_account_type').select2();
        $('select[name="class_unit[]"]').select2("readonly",true);
    }

    do_first(1);

    $('#txt_request_side_account').click(function(e){
        var isdonation= $('#dp_request_store_from').find(':selected').attr('data-isdonation');
        var _type = $('#dp_request_side').select2('val');
        if(_type == 1){
             if(isdonation==1 && '{$request_input}'==1){
                if('{$isCreate}'){
                //    _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/-1/3/'+$('#dp_request_store_from').val()  );
                    _showReport('$select_accounts_url/'+$(this).attr('id') );
                }
             }else
                _showReport('$select_accounts_url/'+$(this).attr('id') );  // +'/-1/{stores_prefix}'
        }else if(_type == 2 ) // && isdonation!=1 <== تم السماح اختيار مستفيد في حالة المنحة بناء على كتاب من المالية وبموافقة مدير الحاسوب
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
        else if(_type == 3 && {$branch} != 1 && isdonation!=1 )
            _showReport('$project_accounts_url/'+$(this).attr('id')+'/1' );
        else if(_type == 4 )
            _showReport('$select_rooms_url/'+$(this).attr('id'));
    });

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ الطلب ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$request_url}/'+parseInt(data)+'/edit');
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
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="ser[]" value="0" /> <input name="class[]" class="form-control" id="i_txt_class_id'+count+'" /> <input type="hidden" name="class_id[]" id="h_txt_class_id'+count+'" /></td>  <td><input name="class_name[]" readonly class="form-control" id="txt_class_id'+count+'" /></td>  <td><input name="request_amount[]" class="form-control" id="txt_request_amount'+count+'" /></td> <td><select name="class_unit[]" class="form-control" id="unit_txt_class_id'+count+'" /></select></td> <td><select name="class_type[]" class="form-control" id="txt_class_type'+count+'" /></select></td> </tr>';
        $('#details_tb tbody').append(html);
        reBind(1);
        var donation_id= parseInt('{$donation_file_id}');
        if(donation_id > 0){
            $('input[name="class[]"]').prop("readonly",true);
            $('select[name="class_type[]"]').select2("readonly",true);
            $('#txt_class_type'+count).select2("val",'');
        }
    }

    function AddRowWithData(id,name_ar,unit,price,unit_name){
        addRow();
        $('#h_txt_class_id'+(count)).val(id);
        $('#i_txt_class_id'+(count)).val(id);
        $('#txt_class_id'+(count)).val(name_ar);
        $('#unit_txt_class_id'+(count)).select2("val", unit);
        $('#report').modal('hide');
    }

    $('#dp_request_side').change(function(){
        $('#txt_request_side_account').val('');
        $('#h_txt_request_side_account').val('');
        chk_customer_account_type();
        {
            $('#div_customer_account_type1').hide();
            $('#txt_room_id').val('');
            $('#h_room_id').val('');
            $('#txt_room_name').val('');
        }
    });

    function chk_customer_account_type(){
        if( $('#dp_request_side').val()==2 ){
            $('#div_customer_account_type').show();
        }else{
            $('#div_customer_account_type').hide();
        }
        
        if( $('#dp_request_side').val()==4 ){
            $('#div_customer_account_type1').show();
        }
    }
    
    function setDefaultCustomerAccount(){
        // الغاء نوع مستفيد موظف حسب طلب هشام حرارة
        $("#dp_customer_account_type option[value='3']").prop('disabled',1);
    }

    $('#dp_request_type').change(function(){
        chk_request_type();
    });

    function chk_request_type(){
        var typ= $('#dp_request_type').val();
        if(typ ==1)
            $('div#project,div#project_old').fadeIn(300);
        else
            $('div#project,div#project_old').fadeOut(300);
    }

    $('#a_store_serv_req').click(function(){
        _showReport('$store_serv_req_url/'+ $('#txt_store_serv_req').val() );
    });

/*
    $('input[type="text"],body').bind('keydown', 'down', function() {
        addRow();
        return false;
    });
*/

    function reBind(s){
        if(s==undefined)
            s=0;
        $('input[name="class_name[]"]').click("focus",function(e){
            if ('{$donation_file_id}'=='0'){
                _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val() );
            }else{
                _showReport('$select_donation_items_url/$donation_file_id/'+$(this).attr('id')+ $('#h_data_search').val() );
            }
        });

        $('input[name="class[]"]').bind("focusout",function(e){
            var id= $(this).val();
            var class_id= $(this).closest('tr').find('input[name="class_id[]"]');
            var name= $(this).closest('tr').find('input[name="class_name[]"]');
            var unit= $(this).closest('tr').find('select[name="class_unit[]"]');
            var amount= $(this).closest('tr').find('input[name="request_amount[]"]');
            if(id==''){
                class_id.val('');
                name.val('');
                unit.select2("val", '');
                return 0;
            }else if( id.search("-")!= -1 ){
                return 0;
            }
            get_data('{$get_class_url}',{id:id, type:1},function(data){
                if (data.length == 1){
                    var item= data[0];
                    class_id.val(item.CLASS_ID);
                    name.val(item.CLASS_NAME_AR);
                    unit.select2("val", item.CLASS_UNIT_SUB);
                    amount.focus();
                }else{
                    class_id.val('');
                    name.val('');
                    unit.select2("val", '');
                }
            });
        });

        $('input[name="class[]"]').bind('keyup', '+', function(e) {
            $(this).val('');
            var class_name= $(this).closest('tr').find('input[name="class_name[]"]');
            actuateLink(class_name);
        });

        if(s){
            $('select#unit_txt_class_id'+count).append('<option></option>'+select_class_unit).select2().select2("readonly",true);
            $('select#txt_class_type'+count).append('<option></option>'+select_class_type).select2().select2('val','1');
        }
    }

    $('input[name="class[]"]').bind('keyup', '-', function(e){
        var transport_id= $(this).val().replace('-','');
        get_data('{$get_details_url}/-1/'+transport_id,{},function(data){
            $('#details_div').html(data);
            count = $('#details_tb tbody tr').length -1;
            reBind();

            $('select[name="class_unit[]"]').append(select_class_unit);
            $('select[name="class_type[]"]').append(select_class_type);

            $('select[name="class_unit[]"]').each(function(){
                $(this).val($(this).attr('data-val'));
            });

            $('select[name="class_type[]"]').each(function(){
                $(this).val($(this).attr('data-val'));
            });

            $('select[name="class_unit[]"] ,select[name="class_type[]"]').select2();
            $('select[name="class_unit[]"]').select2("readonly",true);

            $(function() {
                $( "#details_tb tbody" ).sortable();
            });

        },'html');
    });


    $('#dp_request_store_from').select2().on('change',function(){
        var isdonation= $('#dp_request_store_from').find(':selected').attr('data-isdonation');
        if (isdonation==1 && '{$request_input}'==1){
            $('#dp_request_side').select2('val',1);
            $('#txt_request_side_account').val(  $('#dp_request_store_from').find(':selected').attr('data-account_name')  ) ;
            $('#h_txt_request_side_account').val(  $('#dp_request_store_from').find(':selected').attr('data-account_id')  ) ;
            $('#details_div').text('');
            after_selected_2();
        }
    });


    function after_selected_2(){
        var id_v= $('#dp_request_store_from').select2('val');
        get_data('{$donation_url}',{id:id_v},function(data){

            $.each(data, function(i,item){
                $('div#donation').show();
                $('#txt_donation_file_id').val(item.DONATION_FILE_ID);
                $('#dp_request_store_from').select2("val",item.STORE_ID).select2("readonly",true);
            });

            var donate_id= $('#txt_donation_file_id').val();
            var cnt=0;
            if( donate_id > 0 ){
                get_data('{$get_details_url}/-2/'+donate_id,{},function(data){
                    $('#details_div').html(data);
                    do_first(0);
                    $('select[name="class_type[]"]').select2("readonly",true);
                },'html');
            }
        });
    }

SCRIPT;

if($isCreate and ($request_input==1 or $request_input==4) ){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#h_project_serial').val('{$maintenance_ser}');

    $(function() {
        $( "#details_tb tbody" ).sortable();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        chk_request_type();
    }
    
    

    </script>
SCRIPT;

}elseif($isCreate and ($request_input==2 or $request_input==3) ){
    $scripts = <<<SCRIPT
    {$scripts}

    if('{$project_data['PROJECT_ID']}'.length < 5 || '{$project_data['PROJECT_ACCOUNT_NAME']}'.length < 5 || '{$project_data['PROJECT_ACCOUNT_ID']}'.length < 5 || {$project_data['PROJECT_CASE']} == 15 )
        $('.container').text('');

    $('#h_project_serial').val('{$project_data['PROJECT_SERIAL']}');
    $('#dp_request_side').select2('val', 3);
    $('#txt_request_side_account').val('{$project_data['PROJECT_ACCOUNT_NAME']}');
    $('#h_txt_request_side_account').val('{$project_data['PROJECT_ID']}');
    $('#dp_request_type').select2('val', 1);
    $('#txt_project_id').val('{$project_data['PROJECT_TEC_CODE']}');
    $('#txt_project_id_old').val('{$project_data['OLD_PROJECT_TEC_CODE']}');
    $('#txt_notes').val('{$project_data['PROJECT_NAME']}');
    $('#txt_notes2').val('{$project_data['HINTS']}');

    $('#dp_request_side, #dp_request_type').select2('readonly', true);
    $('#txt_project_id, #txt_store_serv_req').prop('readonly', true);

    chk_request_type();

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}elseif($request_input==1 or $request_input==4){
    $scripts = <<<SCRIPT
    {$scripts}
    $('#txt_request_no').val('{$rs['BOOK_NO']}'); // REQUEST_NO
    $('#h_request_no').val('{$rs['REQUEST_NO']}');
    $('#dp_request_side').select2('val', '{$rs['REQUEST_SIDE']}');
    $('#dp_customer_account_type option[value="{$rs['CUSTOMER_ACCOUNT_TYPE']}"]').prop('disabled',0);
    $('#dp_customer_account_type').select2('val', '{$rs['CUSTOMER_ACCOUNT_TYPE']}');
    $('#txt_room_id').val('{$rs['ROOM_ID']}');
    $('#h_room_id').val('{$rs['ROOM_ID']}');
    $('#txt_room_name').val('{$rs['ROOM_ID_NAME']}');
    chk_customer_account_type();
    $('#dp_request_store_from').select2('val', '{$rs['REQUEST_STORE_FROM']}');
    $('#txt_request_side_account').val('{$rs['REQUEST_SIDE_ACCOUNT_NAME']}');
    $('#h_txt_request_side_account').val('{$rs['REQUEST_SIDE_ACCOUNT']}');
    $('#dp_request_type').select2('val', '{$rs['REQUEST_TYPE']}');
    $('#txt_project_id').val('{$rs['PROJECT_ID']}');

    $('#h_project_serial').val('{$rs['PROJECT_SERIAL']}');

    var donation_id= parseInt('{$donation_file_id}');
    if(donation_id > 0){
        $('div#donation').show();
        $('#dp_request_store_from,#dp_request_side').select2("readonly",true);
        $('input[name="class[]"]').prop("readonly",true);
        $('select[name="class_type[]"]').select2("readonly",true);
        $('#txt_donation_file_id').val('{$rs['DONATION_FILE_ID']}');
        $('div#donation label a').attr('href','{$donation_view_url}/'+donation_id+'/1');
    }

    count = {$rs['COUNT_DET']} -1;

SCRIPT;

}elseif($request_input==2 or $request_input==3){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#project_attachment a div').text('مرفقات المشروع');

    $('#txt_request_no').val('{$rs['BOOK_NO']}'); // REQUEST_NO
    $('#h_request_no').val('{$rs['REQUEST_NO']}');
    $('#h_project_serial').val('{$rs['PROJECT_SERIAL']}');
    $('#dp_request_side').select2('val', '{$rs['REQUEST_SIDE']}');
    $('#dp_customer_account_type').select2('val', '{$rs['CUSTOMER_ACCOUNT_TYPE']}');
    chk_customer_account_type();
    $('#dp_request_store_from').select2('val', '{$rs['REQUEST_STORE_FROM']}');
    $('#txt_request_side_account').val('{$rs['REQUEST_SIDE_ACCOUNT_NAME']}');
    $('#h_txt_request_side_account').val('{$rs['REQUEST_SIDE_ACCOUNT']}');
    $('#dp_request_type').select2('val', '{$rs['REQUEST_TYPE']}');
    $('#txt_project_id').val('{$rs['PROJECT_ID']}');

    $('div#project label a').attr('href','{$project_url}/{$rs['PROJECT_SERIAL']}');

    $('#dp_request_side, #dp_request_type').select2('readonly', true);
    $('#txt_project_id, #txt_store_serv_req').prop('readonly', true);

SCRIPT;
}

if(!$isCreate and ($request_input==1 or $request_input==2 or $request_input==3 or $request_input==4)){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#txt_project_id_old').val('{$rs['PROJECT_ID_OLD']}');
    $('#txt_notes').val('{$rs['NOTES']}');
    $('#txt_notes2').val('{$rs['NOTES2']}');
    $('#txt_store_serv_req').val('{$rs['STORE_SERV_REQ']}');
    $('#adopt_data').show();
    
    if('{$rs['OUTPUT_UPDATE_ACCOUNT_CNT']}' > 0){
        $('#div_output_update_account_cnt').show(700);
    }

    if( $('#btn_s_adopt').text() != '' )
        $('div#cancel_note label').text('ملاحظة الاعتماد / الارجاع / الالغاء');

    if({$can_cancel_adopt} + {$can_cancel_req} > 0)
        $('div#cancel_note').show();

    if(('{$rs['CANCEL_REQUEST_NOTE']}').length > 3){
        $('div#cancel_request_note div').text('{$rs['CANCEL_REQUEST_NOTE']}');
        $('div#cancel_request_note').show();
    }

    if(('{$rs['ENTRY_NOTE']}').length > 3){
        $('div#entry_note div').text('{$rs['ENTRY_NOTE']}');
        $('div#entry_note').show();
    }
    if(('{$rs['TECHNICAL_NOTE']}').length > 3){
        $('div#technical_note div').text('{$rs['TECHNICAL_NOTE']}');
        $('div#technical_note').show();
    }
    if(('{$rs['STORE_NOTE']}').length > 3){
        $('div#store_note div').text('{$rs['STORE_NOTE']}');
        $('div#store_note').show();
    }
    if(('{$rs['ACCOUNT_NOTE']}').length > 3){
        $('div#account_note div').text('{$rs['ACCOUNT_NOTE']}');
        $('div#account_note').show();
    }
    if(('{$rs['STORES_DEPT_NOTE']}').length > 3){
        $('div#stores_dept_note div').text('{$rs['STORES_DEPT_NOTE']}');
        $('div#stores_dept_note').show();
    }

    chk_request_type();

    var general_manager= '{$rs['GENERAL_MANAGER']}';
    general_manager= parseInt(general_manager);
    var final_adopt=0;
    
    var btn__= '';
    $('#btn_t_adopt,#btn_s_adopt,#btn_a_adopt,#btn_f_adopt,#btn_m_adopt,#btn_bs_adopt,#btn_bf_adopt,#btn_bm_adopt').click( function(){
        btn__ = $(this);
    });

    function adopt(no){
        if(dont_adopt == 1 && no!=0 && ( no==2 || no==4 || no==12 || 1 )){
            danger_msg('تحذير..','لم يتم الاعتماد لان الكمية المطلوبة اكبر من المتاحة');
            return false;
        }
        if( no==0 && $('#txt_cancel_note').val() =='' ){
            danger_msg('تحذير..','ادخل بيان الالغاء');
            return false;
        }
        if( (no==3 && general_manager==0) || (no==5 && general_manager==1) || no==12 ){
            final_adopt=1;
        }
        var msg= '';
        if(no==0)
             msg= 'هل تريد الغاء الطلب ؟!';
        else
             msg= 'هل تريد اعتماد الطلب ؟!';
        if(confirm(msg)){
            var adopt_url, adopt_btn;
            if(no==1){
                adopt_url= '{$adopt_t_url}';
                adopt_btn= $('#btn_t_adopt');
            }else if(no==2){
                adopt_url= '{$adopt_s_url}';
                adopt_btn= $('#btn_s_adopt');
            }else if(no==3){
                adopt_url= '{$adopt_a_url}';
                adopt_btn= $('#btn_a_adopt');
            }else if(no==4){
                adopt_url= '{$adopt_f_url}';
                adopt_btn= $('#btn_f_adopt');
            }else if(no==5){
                adopt_url= '{$adopt_m_url}';
                adopt_btn= $('#btn_m_adopt');

            }else if(no==10){
                adopt_url= '{$adopt_bs_url}';
                adopt_btn= $('#btn_bs_adopt');
            }else if(no==11){
                adopt_url= '{$adopt_bf_url}';
                adopt_btn= $('#btn_bf_adopt');
            }else if(no==12){
                adopt_url= '{$adopt_bm_url}';
                adopt_btn= $('#btn_bm_adopt');
            }
            else if(no==0){
                adopt_url= '{$adopt_0_url}';
                adopt_btn= $('#btn_0_adopt');
            }
            var values= {request_no: "{$rs['REQUEST_NO']}" , cancel_note: $('#txt_cancel_note').val() };
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    if(no==0){
                        success_msg('رسالة','تم الغاء الطلب بنجاح ..');
                        $('button').attr('disabled','disabled');
                    }else{
                        success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                        adopt_btn.attr('disabled','disabled');
                        $('button').attr('disabled','disabled');
                        if(final_adopt && $.inArray(parseInt('{$rs['REQUEST_TYPE']}'), [1,2,10] )!= -1  ){
                            var sub= 'تم اعتماد طلب صرف {$rs['BOOK_NO']}';
                            var text= 'لقد تم اعتماد طلب الصرف رقم {$rs['BOOK_NO']}';
                            text+= '<br>{$rs['NOTES']}';
                            text+= '<br>للاطلاع افتح الرابط';
                            text+= ' <br> {$gfc_domain}{$request_url}/{$rs['REQUEST_NO']}/archive ';
                            _send_mail(btn__,'{$eng_emails}',sub,text);
                            btn__ = '';
                        }
                        if(no==3 && general_manager==1){
                            var sub= 'اعتماد طلب صرف {$rs['BOOK_NO']}';
                            var text= 'يرجى اعتماد طلب صرف رقم {$rs['BOOK_NO']}';
                            text+= '<br>{$rs['NOTES']}';
                            text+= '<br>للاطلاع افتح الرابط';
                            text+= ' <br> {$gfc_domain}{$request_url}/{$rs['REQUEST_NO']} ';
                            _send_mail(btn__,'{$g_manager_email}',sub,text);
                            btn__ = '';
                        }
                        if(no==5){
                            get_to_link('{$back_url}');
                        }else{
                            setTimeout(function(){
                                if(confirm('هل تريد الرجوع للصفحة السابقة؟')){
                                    get_to_link('{$back_url}');
                                }
                            },700);
                        }
                    }
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    function cancel_adopt(){
        if( $('#txt_cancel_note').val() =='' ){
            danger_msg('تحذير..','ادخل بيان الارجاع');
            return false;
        }
        if(confirm('هل تريد ارجاع الطلب ؟!')){
            var adopt_url, adopt_btn;
            var values= {request_no: "{$rs['REQUEST_NO']}" , cancel_note: $('#txt_cancel_note').val() };
            adopt_url= '{$adopt_cancel_url}';
            adopt_btn= $('#btn_cancel_adopt');
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم ارجاع الطلب بنجاح ..');
                    adopt_btn.attr('disabled','disabled');
                    $('button').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    function reserve(){
        var msg= '{$btn_reserve}';
        if(confirm('هل تريد '+msg+' ؟!')){
            var adopt_url, adopt_btn;
            var values= {request_no: "{$rs['REQUEST_NO']}" };
            adopt_url= '{$reserve_url}';
            adopt_btn= $('#btn_reserve');
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح ..');
                    adopt_btn.attr('disabled','disabled');
                    $('button').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    function request_transport(){
        get_to_link('{$transport_url}/{$rs['REQUEST_NO']}');
    }

    function request_output(){
        get_to_link('{$output_url}/1/{$rs['REQUEST_NO']}');
    }

    $('#print_rep').click(function(){
        _showReport('{$report_fin_url}&report_type=pdf&report=STORES_PAYMENT_REQUEST_TB&p_request_no={$rs['REQUEST_NO']}');
    });
    
    function print_project_ar(ser){
           _showReport('{$report_url}&report_type=pdf&report=projects_file_ar&p_project_serial='+ser);
        }
    </script>
SCRIPT;
}
sec_scripts($scripts);

?>
