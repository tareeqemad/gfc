<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/02/15
 * Time: 01:55 م
 */

/*JASPERREPORT*/
$jasper_url = base_url("JsperReport/showreport?sys=financial");
$report_sn= report_sn();
/* ----- */
$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_pledges';
$report_url = 'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$select_items_url=base_url("stores/classes/public_index");
$edit_barcode_url=base_url("$MODULE_NAME/$TB_NAME/edit_barcode");
$get_bar_url =base_url("$MODULE_NAME/$TB_NAME/public_get_info_id");

echo AntiForgeryToken();

?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة عهدة</a> </li><?php endif; ?>
            <li><a onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" href="javascript:;"><i class="glyphicon glyphicon-download-alt"></i>اكسل </a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div <?php if($emp_pledges==1) echo "hidden"; ?> class="form-group col-sm-12">
            <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                <div  class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label"> م </label>
                        <div>
                            <input type="text" name="file_id" id="txt_file_id" class="form-control">
                            <input type="hidden" value="<?=encryption_case($emp_pledges,1)?>" name="emp_pledges" id="h_emp_pledges" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">نوع المستفيد</label>
                        <div>
                            <select name="customer_type" id="dp_customer_type" class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($customer_type_cons as $row) :?>
                                    <option value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2" id="customer_div_1">
                        <label class="control-label"> حساب المستفيد - الهوية </label>
                        <div>
                            <select name="customer_id" id="txt_customer_id" class="form-control" >
                                <option value="">_________</option>
                            <?php foreach($customer_ids as $row) :?>
                                <option value="<?=$row['ID']?>"><?=$row['NO'].'-'.$row['NAME']?></option>
                            <?php endforeach; ?>
                            </select>
                            <!--  <input type="text" name="customer_id" id="txt_customer_id" class="form-control">-->
                        </div>
                    </div>


                    <div class="form-group col-sm-2" id="customer_div_2">
                        <label class="control-label">الغرف </label>
                        <div>
                            <select name="room_id" id="dp_room_id" class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($rooms_cons as $row) :?>
                                    <option value="<?=$row['ROOM_ID']?>" ><?=$row['ROOM_ID'].': '.$row['ROOM_PARENT_NAME'].' - '.$row['ROOM_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الادارة</label>
                        <div>
                            <select  name="manage_st_id" id="dp_manage_st_id"  data-curr="false"  class="form-control" data-val="true" <!--data-val-required="حقل مطلوب"--> >
                            <option value="">___________________________________</option>
                            <?php foreach($manage_st_ids as $row) :?>
                                <option  value="<?= $row['ST_ID'] ?>" ><?php echo $row['ST_NAME']  ?></option>
                            <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="manage_st_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">الدائرة</label>
                        <div>
                            <select  name="cycle_st_id" id="dp_cycle_st_id"  data-curr="false"  class="form-control" data-val="true" <!--data-val-required="حقل مطلوب"--> >
                            <option value="">___________________________________</option>
                            <?php foreach($cycle_st_ids as $row) :?>
                                <option  value="<?= $row['ST_ID'] ?>" ><?php echo $row['ST_NAME']  ?></option>
                            <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="cycle_st_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">القسم</label>
                        <div>
                            <select  name="department_st_id" id="dp_department_st_id"  data-curr="false"  class="form-control" data-val="true" <!--data-val-required="حقل مطلوب"--> >
                            <option value="">___________________________________</option>
                            <?php foreach($department_st_ids as $row) :?>
                                <option  value="<?= $row['ST_ID'] ?>"  ><?php echo $row['ST_NAME']  ?></option>
                            <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="department_st_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> مصدر العهدة </label>
                        <div>
                            <select name="source" id="dp_source" class="form-control" >
                                <option></option>
                                <?php foreach($source_all as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> حالة العهدة </label>
                        <div>
                            <select name="status" id="dp_status" class="form-control" >
                                <option></option>
                                <?php foreach($status_all as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> يحتاج تنفيذ؟  </label>
                        <div>
                            <select name="is_adopt" id="dp_is_adopt" class="form-control" >
                                <option></option>
                                <option value="1">نعم</option>
                                <option value="2">لا</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> رقم الصنف </label>
                        <div>
                            <input type="text" name="class_id" id="txt_class_id" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2" style="display: none">
                        <label class="control-label"> وحدة الصنف </label>
                        <div>
                            <input type="text" name="class_unit" id="txt_class_unit" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> حساب المصروف للمستفيدين </label>
                        <div>
                            <input type="text"  readonly  class="form-control" id="txt_exp_account_cust" />
                            <input type="hidden" name="exp_account_cust" id="h_txt_exp_account_cust" />
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> حالة الصنف </label>
                        <div>
                            <select name="class_type" id="dp_class_type" class="form-control" >
                                <option></option>
                                <?php foreach($class_type_all as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> رقم سند الصرف </label>
                        <div>
                            <input type="text" name="class_output_id" id="txt_class_output_id" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> المخزن</label>
                        <div>

                            <select name="store_id" id="dp_store_id" class="form-control" >
                                <option value="">_________</option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?= $row['STORE_NO'].":".$row['STORE_NAME'] ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label"> البيان </label>
                        <div>
                            <input type="text" name="notes" id="txt_notes" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> الباركود </label>
                        <div>
                            <input type="text" name="class_code_ser" id="txt_class_code_ser" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> رقم الشركة المصنعة </label>
                        <div>
                            <input type="text" name="serial" id="txt_serial" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-1" >
                        <label class=" control-label"> من تاريخ استلام </label>
                        <div >
                            <input type="text" class="form-control" data-type="date" data-date-format="DD/MM/YYYY" id="txt_fdate" name="fdate" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"/>
                        </div>
                    </div>

                    <div class="form-group  col-sm-1" >
                        <label class="control-label"> إلى تاريخ استلام</label>
                        <div >
                            <input type="text" class="form-control" data-type="date" data-date-format="DD/MM/YYYY" id="txt_tdate" name="tdate" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"/>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> المقر </label>
                        <div>
                            <select name="branch" id="dp_branch" class="form-control" >
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> حالة الاستلام </label>
                        <div>
                            <select name="adopt" id="dp_adopt" class="form-control" >
                                <option></option>
                                <?php foreach($adopts as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> نوع العهدة </label>
                        <div>
                            <select name="custody_type" id="dp_custody_type" class="form-control" >
                                <option></option>
                                <?php foreach($custody_types as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">أقسام العهد </label>
                        <div>
                            <select name="personal_custody_type" id="dp_personal_custody_type" class="form-control" >
                                <option></option>
                                <?php foreach($personal_custody_types as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">منتهي العمر الزمني؟ </label>
                        <div>
                            <select name="is_end_age" id="dp_is_end_age" class="form-control" >
                                <option></option>
                                <option value="1">نعم</option>
                                <option value="2">لا</option>
                            </select>
                        </div>
                    </div>

                </div>
            </form>

            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <button type="button" class="btn blue" onclick="javascript:showReportx('pdf');" class="btn btn-success"> عهد الموظفين</button>
                <button type="button" class="btn yellow" onclick="javascript:showReportx('xls');" class="glyphicon glyphicon-print"> عهد الموظفينXLS <i class="glyphicon glyphicon-print"></i>  </button>
                <button type="button" class="btn blue" onclick="javascript:showReportC('pdf');" class="btn btn-success">  لجان الجرد </button>
                <button type="button" class="btn yellow" onclick="javascript:showReportC('xls');" class="glyphicon glyphicon-print">  لجان الجرد XLS  <i class="glyphicon glyphicon-print"></i> </button>


                <!--   <a class="btn blue" data-report-source="2" data-option="" data-type="report" href="javascript:;">تقرير عهد الموظفين</a>
                   <a class="btn blue" data-report-source="3" data-option="" data-type="report" href="javascript:;">تقرير لجان الجرد</a>
                   <a class="btn blue" data-report-source="4" data-option="" data-type="report" href="javascript:;">تقرير حركة العهد- الاصناف</a>

                <a class="btn blue" onclick="showReportx();">تقرير عهد الموظفين</a>
                <a class="btn blue"  onclick="showReportC();" >تقرير لجان الجرد</a>-->
            </div>
        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?php
            if($emp_pledges==1)
            echo modules::run($get_page_url, $page, $emp_pledges, $file_id, $customer_id, $source, $status, $class_id, $class_unit, $exp_account_cust, $class_output_id, $notes , $class_code_ser,$class_type,$manage_st_id,$cycle_st_id,$department_st_id,$serial,$is_adopt,$branch,$tdate,$fdate,$store_id,$adopt,$personal_custody_type,$custody_type,$room_id,$is_end_age,$customer_type);?>

        </div>

    </div>

</div>


<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات الصنف</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/edit")?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <div class="form-group">
                        <label class="col-sm-1 control-label"> اسم الصنف  </label>
                        <div class="col-sm-9">
                            <input type="text"  readonly data-val="true"   data-val-required="حقل مطلوب" name="class_id_name" id="txt_class_id_name" class="form-control">
                            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="file_idd" id="txt_file_idd" class="form-control">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">اسم الموظف</label>
                        <div class="col-sm-9">
                            <input type="text" readonly  data-val="true"   data-val-required="حقل مطلوب" name="emp_name" id="txt_emp_name" class="form-control">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label"> رقم الباركود  </label>
                        <div class="col-sm-2">
                            <input type="text"  data-val="true"   data-val-required="حقل مطلوب" name="class_code_serr" id="txt_class_code_serr" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="class_code_serr" data-valmsg-replace="true"></span>

                        </div>
                    </div>



                    <div class="modal-footer">
                        <?php if( HaveAccess($edit_barcode_url)){?>
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
$scripts = <<<SCRIPT
<script>
   auto_restart_search();
    //tasneem
   function showReportx(type){
 if ($('#txt_customer_id').val()!=''  ||   $('#dp_branch').val()!=''  ||   $('#txt_class_id').val()!='' ||   $('#txt_class_code_ser').val()!='' ) {
    var f_date =$('#txt_fdate').val();
    var t_date =$('#txt_tdate').val();
    var class_id =$('#txt_class_id').val();
    var source =$('#dp_source').val();
    var status =$('#dp_status').val();
    var adopt =$('#dp_is_adopt').val();
      var is_adopt_1='';
    var is_adopt_2='';
    var is_end_age_1='';
    var is_end_age_2='';
    if (adopt==1) is_adopt_1='1' ;
    else  if (adopt==2) is_adopt_2='1' ;
    var class_type =$('#dp_class_type').val();
    var barcode =$('#txt_class_code_ser').val();
    var customer_id1 =$('#txt_customer_id').val();
     var room_id =$('#dp_room_id').val();
      var custody_type =$('#dp_custody_type').val();
     var personal_custody_type =$('#dp_personal_custody_type').val();
    var customer_type =$('#dp_customer_type').val(); //s_customer_id
     var s_customer_id= (customer_type==4)? room_id:customer_id1;
    var class_output_id =$('#txt_class_output_id').val();
    var notes =$('#txt_notes').val();
    var serial =$('#txt_serial').val(); 
    var s_branch =$('#dp_branch').val();
    var file_id=$('#txt_file_id').val(); 
       var adopts =$('#dp_adopt').val();
var store_id =$('#dp_store_id').val();
var is_end_age=$('#dp_is_end_age').val();
 if (is_end_age==1) is_end_age_1='1' ;
    else  if (is_end_age==2) is_end_age_2='1' ;
    if (type=='pdf')
     var url ='{$jasper_url}&report_type=pdf&report=Fin_Employees_Custody&p_from_date='+f_date+'&p_to_date='+t_date+'&p_class_id='+class_id+'&p_source='+source+'&p_status='+status+'&p_is_adopt_1='+is_adopt_1+'&p_is_adopt_2='+is_adopt_2+'&p_class_type='+class_type+'&p_barcode='+barcode+'&p_customer_id='+s_customer_id+'&p_branch='+s_branch+'&p_serial='+serial+'&p_file_id='+file_id+'&p_notes='+notes+'&p_customer_type='+customer_type+'&p_class_output_id='+class_output_id+'&p_store_id='+store_id+'&p_adopt='+adopts+'&p_room_id='+room_id+'&p_custody_type='+custody_type+'&p_personal_custody_type='+personal_custody_type+'&p_is_end_age_1='+is_end_age_1+'&p_is_end_age_2='+is_end_age_2+'&sn={$report_sn}';
    else
    var url ='{$jasper_url}&report_type=xls&report=Fin_Employees_Custody_xls&p_from_date='+f_date+'&p_to_date='+t_date+'&p_class_id='+class_id+'&p_source='+source+'&p_status='+status+'&p_is_adopt_1='+is_adopt_1+'&p_is_adopt_2='+is_adopt_2+'&p_class_type='+class_type+'&p_barcode='+barcode+'&p_customer_id='+s_customer_id+'&p_branch='+s_branch+'&p_serial='+serial+'&p_file_id='+file_id+'&p_notes='+notes+'&p_customer_type='+customer_type+'&p_class_output_id='+class_output_id+'&p_store_id='+store_id+'&p_adopt='+adopts+'&p_room_id='+room_id+'&p_custody_type='+custody_type+'&p_personal_custody_type='+personal_custody_type+'&p_is_end_age_1='+is_end_age_1+'&p_is_end_age_2='+is_end_age_2+'&sn={$report_sn}';
   
       _showReport(url);               
   } else {danger_msg('تحذير','يجب اختيار الموظف أو المقر أو إدخال رقم الصنف أو رقم الباركود');}

  }    
    function showReportC(type){
		 if ($('#txt_customer_id').val()!=''  ||   $('#dp_branch').val()!=''  ||   $('#txt_class_id').val()!='' ||   $('#txt_class_code_ser').val()!='' ) {
   var is_end_age_1='';
    var is_end_age_2='';
    var f_date =$('#txt_fdate').val();
    var t_date =$('#txt_tdate').val();
    var class_id =$('#txt_class_id').val();
    var source =$('#dp_source').val();
    var status =$('#dp_status').val();
    var adopt =$('#dp_is_adopt').val();
    var is_adopt_1='';
    var is_adopt_2='';
    if (adopt==1) is_adopt_1='1' ;
    else  if (adopt==2) is_adopt_2='1' ;
    
    var class_type =$('#dp_class_type').val();
    var barcode =$('#txt_class_code_ser').val();
    var customer_id1 =$('#txt_customer_id').val();
     var room_id =$('#dp_room_id').val();
      var custody_type =$('#dp_custody_type').val();
     var personal_custody_type =$('#dp_personal_custody_type').val();
    var customer_type =$('#dp_customer_type').val(); //s_customer_id
    var s_customer_id= (customer_type==4)? room_id:customer_id1;
    var class_output_id =$('#txt_class_output_id').val();
    var notes =$('#txt_notes').val();
    var serial =$('#txt_serial').val(); 
    var s_branch =$('#dp_branch').val();
     var file_id=$('#txt_file_id').val(); 
     var store_id =$('#dp_store_id').val();
        var adopts =$('#dp_adopt').val();
        var is_end_age=$('#dp_is_end_age').val();
         if (is_end_age==1) is_end_age_1='1' ;
    else  if (is_end_age==2) is_end_age_2='1' ;
          if (type=='pdf')
    var url ='{$jasper_url}&report_type=pdf&report=Custody_Inventory_Committees&p_from_date='+f_date+'&p_to_date='+t_date+'&p_class_id='+class_id+'&p_source='+source+'&p_status='+status+'&p_is_adopt_1='+is_adopt_1+'&p_is_adopt_2='+is_adopt_2+'&p_class_type='+class_type+'&p_barcode='+barcode+'&p_customer_id='+s_customer_id+'&p_branch='+s_branch+'&p_serial='+serial+'&p_file_id='+file_id+'&p_notes='+notes+'&p_customer_type='+customer_type+'&p_class_output_id='+class_output_id+'&p_store_id='+store_id+'&p_adopt='+adopts+'&p_room_id='+room_id+'&p_custody_type='+custody_type+'&p_personal_custody_type='+personal_custody_type+'&p_is_end_age_1='+is_end_age_1+'&p_is_end_age_2='+is_end_age_2+'&sn={$report_sn}';
    else 
         var url ='{$jasper_url}&report_type=xls&report=Custody_Inventory_Committees_xls&p_from_date='+f_date+'&p_to_date='+t_date+'&p_class_id='+class_id+'&p_source='+source+'&p_status='+status+'&p_is_adopt_1='+is_adopt_1+'&p_is_adopt_2='+is_adopt_2+'&p_class_type='+class_type+'&p_barcode='+barcode+'&p_customer_id='+s_customer_id+'&p_branch='+s_branch+'&p_serial='+serial+'&p_file_id='+file_id+'&p_notes='+notes+'&p_customer_type='+customer_type+'&p_class_output_id='+class_output_id+'&p_store_id='+store_id+'&p_adopt='+adopts+'&p_room_id='+room_id+'&p_custody_type='+custody_type+'&p_personal_custody_type='+personal_custody_type+'&p_is_end_age_1='+is_end_age_1+'&p_is_end_age_2='+is_end_age_2+'&sn={$report_sn}';
   
       _showReport(url);       
 } else {danger_msg('تحذير','يجب اختيار الموظف أو المقر أو إدخال رقم الصنف أو رقم الباركود');}	   
   }    
    
    
     $('#txt_customer_id').select2();
    $('#dp_room_id').select2();
  $('#dp_manage_st_id').select2();
        $('#dp_cycle_st_id').select2();
        $('#dp_department_st_id').select2();
      $('#dp_store_id').select2();
        
    // Mkilani
    $('#customer_div_2').hide(0);
    
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
    }
    // Mkilani
        
        
        

$('#txt_class_id2').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));
    });

    $('#h_txt_class_id2').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
    var id_v=$(this).closest('div').find('input[id^="txt_class_id"]').attr('id');
  _showReport('$select_items_url/'+id_v);
           });


    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$emp_pledges}');
    }

    $('#txt_exp_account_cust').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id') );
    });

    function search(){
			var customer_id_ ;
      var customer_type=$('#dp_customer_type').val();	
      if (customer_type==1) customer_id_=$('#txt_customer_id').val();
      else 	  customer_id_=$('#dp_room_id').val();
	   var values= {page:1,emp_pledges:$('#h_emp_pledges').val(), file_id:$('#txt_file_id').val(), customer_id:customer_id_, customer_type:$('#dp_customer_type').val(), source:$('#dp_source').val(), status:$('#dp_status').val(), class_id:$('#txt_class_id').val(), class_unit:$('#txt_class_unit').val(), exp_account_cust:$('#h_txt_exp_account_cust').val(), class_output_id:$('#txt_class_output_id').val(), notes:$('#txt_notes').val(), class_code_ser:$('#txt_class_code_ser').val(), class_type:$('#dp_class_type').val(), manage_st_id:$('#dp_manage_st_id').val(), cycle_st_id:$('#dp_cycle_st_id').val(), department_st_id:$('#dp_department_st_id').val(), serial:$('#txt_serial').val() , is_adopt:$('#dp_is_adopt').val(),branch:$('#dp_branch').val(),fdate:$('#txt_fdate').val(),tdate:$('#txt_tdate').val(),store_id:$('#dp_store_id').val(),adopt:$('#dp_adopt').val(),personal_custody_type:$('#dp_personal_custody_type').val(),custody_type:$('#dp_custody_type').val(),room_id:$('#dp_room_id').val(),is_end_age:$('#dp_is_end_age').val(),customer_type:$('#dp_customer_type').val()};
      	if (customer_type !='' || customer_id_!=''  ||   $('#dp_branch').val()!=''  ||   $('#txt_class_id').val()!='' ||   $('#txt_class_code_ser').val()!='' ) {
	   get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
		
	   } else { danger_msg('تحذير','يجب اختيار نوع المستفيد و(اختيار الموظف أو الغرفة) أو المقر أو إدخال رقم الصنف أو رقم الباركود');}
    }

    function LoadingData(){
    	var customer_id_ ;
      var customer_type=$('#dp_customer_type').val();	
      if (customer_type==1) customer_id_=$('#txt_customer_id').val();
      else 	  customer_id_=$('#dp_room_id').val();
        var values= {emp_pledges:$('#h_emp_pledges').val(),file_id:$('#txt_file_id').val(), customer_id:customer_id_, source:$('#dp_source').val(), status:$('#dp_status').val(), class_id:$('#txt_class_id').val(), class_unit:$('#txt_class_unit').val(), exp_account_cust:$('#h_txt_exp_account_cust').val(), class_output_id:$('#txt_class_output_id').val(), notes:$('#txt_notes').val(), class_code_ser:$('#txt_class_code_ser').val(), class_case:$('#dp_class_case').val(), class_type:$('#dp_class_type').val(), manage_st_id:$('#dp_manage_st_id').val(), cycle_st_id:$('#dp_cycle_st_id').val(), department_st_id:$('#dp_department_st_id').val(), serial:$('#txt_serial').val() , is_adopt:$('#dp_is_adopt').val(),branch:$('#dp_branch').val(),fdate:$('#txt_fdate').val(),tdate:$('#txt_tdate').val(),store_id:$('#dp_store_id').val(),adopt:$('#dp_adopt').val(),personal_custody_type:$('#dp_personal_custody_type').val(),custody_type:$('#dp_custody_type').val(),room_id:$('#dp_room_id').val(),is_end_age:$('#dp_is_end_age').val(),customer_type:$('#dp_customer_type').val()  };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

   
      function customers_pledges_get(id){
        get_data('{$get_bar_url}',{id:id},function(data){
            $.each(data, function(i,item){

               $('#txt_class_code_serr').val('');
                $('#txt_class_id_name').val( '');
                $('#txt_emp_name').val( '');
                $('#txt_file_idd').val( '');

                $('#txt_class_code_serr').val(item.BARCODE);
                $('#txt_class_id_name').val( item.CLASS_ID+':'+item.CLASS_ID_NAME);
                $('#txt_emp_name').val( item.CUSTOMER_ID_NAME);
                $('#txt_file_idd').val( item.FILE_ID);


                $('#{$TB_NAME}_from').attr('action','{$edit_barcode_url}');

                resetValidation($('#{$TB_NAME}_from'));
                $('#{$TB_NAME}Modal').modal();
            });
        });
    }
 $('button[data-action="submit"]').click(function(e){
	    $(this).attr('disabled','disabled');
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
           if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    search();
            }else{
                    danger_msg('تحذير..','تأكد من رقم الباركود');
                }
            container.html(data);
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });
</script>
SCRIPT;
sec_scripts($scripts);
?>
