<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'issues';
$get_sub_info_url =base_url("$MODULE_NAME/$TB_NAME/public_sub_info");
$back_url=base_url("$MODULE_NAME/$TB_NAME");
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$get_branch_issues=base_url("$MODULE_NAME/$TB_NAME/public_get_court");

$DET_TB_NAME1='public_get_action_details';
$DET_TB_NAME2='public_get_payments_details';

$rs=($isCreate)? $details /*array()*/: (count($issues_data) > 0 ? $issues_data[0] : array()) ;
$gedco_branch_issuess= modules::run("issues/issues/public_get_court_b", (count($rs)>0)?$rs['ISSUE_BRANCH']:$this->user->branch);
//$isCreate =isset($details) && count($details)  > 0 ?false:true;
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_issue_info");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$count_array=count($details);
$isCreate=($isCreate)?1:0;

if(count($details) > 0) {
    foreach($details as $row3) {
        if($row3['TYPE'] == 3){
            $hide_ins='';
            $hide_add='hidden';
        }elseif(($row3['TYPE'] == 2) || ($row3['TYPE'] == 4)){
            $hide_ins='hidden';
            $hide_add='hidden';
        } else{
            $hide_ins='hidden';
            $hide_add='';
        }

    }
}

?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
                <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>

    </div>

    <div class="form-body">

    <div id="container">
    <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
    <div class="modal-body inline_form">
    <input type="hidden" value="<?php echo @$rs['SER_ISSUE_TYPE'];?>" name="ser_issue_type" id="txt_ser_issue_type">
    <input type="hidden" value="<?php if ($isCreate && count($details) )echo $this->uri->segment(4) ; else echo @$rs['BONDS_SER'] ; ?>" name="bonds_ser" id="txt_bonds_ser">
    <input type="hidden" value="<?php if ($isCreate && count($details) )echo $this->uri->segment(4) ; else echo @$rs['INSPECTION_SER'] ; ?>" name="inspection_ser" id="txt_inspection_ser">
    <input type="hidden" value="<?php if ($isCreate && count($details) )echo $this->uri->segment(5) ; else '' ; ?>" name="from_source" id="txt_from_source">

    <!-------------------------------------------------- بيانات المشترك  ------------------------------------------->
    <fieldset  class="field_set">
        <legend >بيانات المشترك</legend>
        <div class="form-group">

            <label class="col-sm-1 control-label">رقم المسلسل</label>
            <div class="col-sm-2">
                <input type="text" readonly   name="ser" id="txt_SER" class="form-control" value="<?php echo @$rs['SER'] ;?>">
            </div>
            <?php
            if($this->user->branch==1)
            {
            ?>
            <label class="col-sm-1 control-label">الفرع</label>
            <div class="col-sm-2">

                <select name="issue_branch" id="dp_issue_branch" class="form-control">
                    <option>-</option>
                    <?php foreach($branches as $row) :?>
                        <?php
                        if($row['NO']<>1)
                        {
                            ?>

                            <option value="<?= $row['NO'] ?>" <?php if ($isCreate && count($details) ){if ($row['NO']==((count($rs)>0)?$rs[0]['BRANCH']:0)){ echo " selected"; }} else {if ($row['NO']==((count($rs)>0)?$rs['ISSUE_BRANCH']:0)){ echo " selected"; }} ;  ?> >
                                <?= $row['NAME'] ?>
                            </option>
                        <?php
                        }
                        ?>

                    <?php endforeach; ?>
                </select>
                <?php
                }
                else
                {
                ?>
                <div class="col-sm-2">
                    <input type="hidden" value="<?php echo $this->user->branch; ?>" name="issue_branch" id="issue_branch">

                    <?php

                    }
                    ?>
                </div>


            </div>

            <div class="form-group">

                <label class="col-sm-1 control-label">رقم الاشتراك</label>
                <div class="col-sm-2">
                    <input type="text" data-val="true"  value="<?php if ($isCreate && count($details) )echo $rs[0]['SUBSCRIBER'] ; else echo $rs['SUB_NO'] ; ?>"  placeholder="رقم الاشتراك"   data-val-required="حقل مطلوب" name="sub_no" id="txt_sub_no" class="form-control"  >
                    <span class="field-validation-valid" data-valmsg-for="sub_no" data-valmsg-replace="true"></span>
                </div>

                <label class="col-sm-1 control-label">اسم المشترك</label>
                <div class="col-sm-2">
                    <input type="text" data-val="true" value="<?php if ($isCreate && count($details))echo $rs[0]['NAME'] ; else echo $rs['SUB_NAME'] ;?>" placeholder="اسم المشترك"  data-val-required="حقل مطلوب" name="sub_name" id="txt_sub_name" class="form-control" readonly>
                    <span class="field-validation-valid" data-valmsg-for="sub_name" data-valmsg-replace="true"></span>
                </div>

                <label class="col-sm-1 control-label">رقم الهوية</label>
                <div class="col-sm-2">
                    <input type="text" data-val="true" value="<?php if ($isCreate && count($details))echo $rs[0]['ID'] ; else echo $rs['ID'] ;?>"  placeholder="رقم الهوية"  data-val-required="حقل مطلوب" name="id" id="txt_id" class="form-control" readonly >
                    <span class="field-validation-valid" data-valmsg-for="id" data-valmsg-replace="true"></span>
                </div>

            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label">شهرالفاتورة</label>
                <div class="col-sm-2">
                    <input type="text" data-val="true" value="<?php if ($isCreate && count($details))echo $rs[0]['FOR_MONTH'] ; else echo $rs['FOR_MONTH'] ;?>" placeholder="شهر الفاتورة" data-val-required="حقل مطلوب" name="for_month" id="txt_for_month" class="form-control" readonly >
                    <span class="field-validation-valid" data-valmsg-for="for_month" data-valmsg-replace="true"></span>
                </div>

                <label class="col-sm-1 control-label">قيمة الفاتورة</label>
                <div class="col-sm-2">
                    <input type="text" data-val="true" value="<?php if ($isCreate && count($details))echo $rs[0]['NET_TO_PAY'] ; else echo $rs['NET_PAY'] ;?>" placeholder="قيمة الفاتورة" data-val-required="حقل مطلوب" name="net_pay" id="txt_net_pay" class="form-control" readonly >
                    <span class="field-validation-valid" data-valmsg-for="net_pay" data-valmsg-replace="true"></span>
                </div>

                <label class="col-sm-1 control-label">نوع الاشتراك</label>
                <div class="col-sm-2">
                    <input type="text" data-val="true" value="<?php if ($isCreate && count($details))echo $rs[0]['TYPE_NAME'] ; else echo $rs['TYPE_NAME_SHOW'] ;?>"   placeholder="نوع الاشتراك"  data-val-required="حقل مطلوب" name="type_name" id="txt_type_name" class="form-control" readonly>
                    <input type="hidden" data-val="true" value="<?php if ($isCreate && count($details))echo $rs[0]['TYPE'] ; else echo $rs['TYPE_NAME'] ;?>"  placeholder="نوع الاشتراك"  data-val-required="حقل مطلوب" name="type_pa" id="txt_type_pa" class="form-control" >

                    <span class="field-validation-valid" data-valmsg-for="type" data-valmsg-replace="true"></span>
                </div>

            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label">العنوان</label>
                <div class="col-sm-8">
                    <input type="text" value="<?php if ($isCreate && count($details))echo $rs[0]['ADDRESS'] ; else echo $rs['ADDRESS'] ;?>" data-val="true" placeholder="العنوان"   data-val-required="حقل مطلوب" name="address" id="txt_address" class="form-control" readonly >
                    <span class="field-validation-valid" data-valmsg-for="address" data-valmsg-replace="true"></span>
                </div>
            </div>

    </fieldset>

    <hr/>


    <!-------------------------------------------------بيانات المدعى عليه------------------------------------------->
    <fieldset  class="field_set">
        <legend >بيانات المُدعى عليه</legend>
        <div class="form-group">
            <label class="col-sm-1 control-label">رقم الهوية</label>
            <div class="col-sm-2">
                <input type="text" data-val="true" value="<?php if ($isCreate && count($details))echo $rs[0]['ID'] ; else echo $rs['D_ID'] ;?>"   placeholder="رقم قوية المدعى عليه"  data-val-required="حقل مطلوب" name="d_id" id="txt_d_id" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="d_id" data-valmsg-replace="true"></span>
            </div>


            <label class="col-sm-1 control-label">اسم المُدّعى عليه</label>
            <div class="col-sm-5">
                <input  type="text" value="<?php echo @$rs['D_NAME'] ;?>"  readonly data-val="true"  placeholder="اسم المدعى عليه" data-val-required="حقل مطلوب" name="d_name" id="txt_d_name" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="d_name" data-valmsg-replace="true"></span>
            </div>


        </div>


        <div class="form-group">

            <label class="col-sm-1 control-label">رقم الهاتف</label>
            <div class="col-sm-2">
                <input  type="text" data-val="true" value="<?php echo @$rs['D_TEL'] ;?>"   placeholder="رقم هاتف المدعى عليه"   name="d_tel" id="txt_d_tel" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="d_tel" data-valmsg-replace="true"></span>
            </div>

            <label class="col-sm-1 control-label">رقم الجوال</label>
            <div class="col-sm-2">
                <input  type="text" data-val="true" value="<?php echo @$rs['D_MOBILE'] ;?>"   placeholder="رقم جوال المدعى عليه"   name="d_mobile" id="txt_d_mobile" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="d_mobile" data-valmsg-replace="true"></span>
            </div>

        </div>


        <div class="form-group">
            <label class="col-sm-1 control-label">العنوان</label>
            <div class="col-sm-8">
                <input  type="text" data-val="true" value="<?php echo @$rs['D_ADDRESS'] ;?>"   placeholder="عنوان المدعى عليه" data-val-required="حقل مطلوب" name="d_address" id="txt_d_address" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="d_address" data-valmsg-replace="true"></span>
            </div>
        </div>

    </fieldset>

    <hr/>
    <!-------------------------------------------------بيانات القضية------------------------------------------->
    <fieldset  class="field_set">
        <legend >بيانات القضية</legend>
        <div class="form-group">
            <label class="col-sm-1 control-label">رقم القضية</label>
            <div class="col-sm-2">
                <input type="text" data-val="true" value="<?php echo @$rs['ISSUE_NO'] ;?>"  placeholder="رقم القضية"  data-val-required="حقل مطلوب" name="issue_no" id="txt_issue_no" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="issue_no" data-valmsg-replace="true"></span>
            </div>
            <label class="col-sm-1 control-label"> / </label>

            <div class="col-sm-2">
                <input type="text" data-val="true" value="<?php echo @$rs['ISSUE_YEAR'] ;?>"   placeholder="سنة القضية"  data-val-required="حقل مطلوب" name="issue_year" id="txt_issue_year" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="issue_year" data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">رقم هوية المدعي</label>
            <div class="col-sm-2">
                <input type="text" data-val="true" value="<?php echo @$rs['PE_ID'] ;?>"  placeholder="رقم هوية المدعي" data-val-required="حقل مطلوب" name="pe_id" id="txt_pe_id" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="pe_id" data-valmsg-replace="true"></span>
            </div>


            <label class="col-sm-1 control-label">اسم المدعي</label>
            <div class="col-sm-5">
                <input type="text" data-val="true" value="<?php echo @$rs['PE_NAME'] ;?>" placeholder="اسم المدعي"  data-val-required="حقل مطلوب" name="pe_name" id="txt_pe_name" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="pe_name" data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label"> رسوم ومصاريف  </label>
            <div class="col-sm-2">
                <input type="text" data-val="true" value="<?php echo @$rs['FEES'] ;?>"  placeholder="رسوم ومصاريف القضية"   data-val-required="حقل مطلوب" name="fees" id="txt_fees" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="fees" data-valmsg-replace="true"></span>
            </div>

            <label class="col-sm-1 control-label">ملاحظات على الرسوم</label>
            <div class="col-sm-2">
                <input type="text" data-val="true" value="<?php echo @$rs['F_NOTES'] ;?>" placeholder="ملاحظات على الرسوم"    name="f_notes" id="txt_f_notes" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="f_notes" data-valmsg-replace="true"></span>
            </div>

            <label class="col-sm-1 control-label">تاريخ ايداع القضية</label>
            <div class="col-sm-2">
                <input type="text" data-val="true" value="<?php echo @$rs['ISSUE_DATE'] ;?>" placeholder="تاريخ ايداع القضية" data-type="date"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"   data-val-required="حقل مطلوب" name="issue_date" id="txt_issue_date" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="issue_date" data-valmsg-replace="true"></span>
            </div>




        </div>

        <div class="form-group">

            <label class="col-sm-1 control-label">اسم المحكمة</label>
            <div class="col-sm-2">
                <select name="court_name" id="dp_court_name" class="form-control">

                    <?php foreach($gedco_branch_issuess as $row) :?>
                        <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['COURT_NAME']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <label class="col-sm-1 control-label">نوع القضية</label>
            <div class="col-sm-2">
                <select name="issue_type" id="dp_issue_type" class="form-control">
                    <?php foreach($issue_type as $row) :?>
                        <?php if($row['CON_NO'] != 4){ ?>
                            <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['ISSUE_TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                        <?php }endforeach; ?>
                </select>
            </div>
        </div>

    </fieldset>
    <hr/>
    <?PHP

    if(count($issues_data) > 0){
        if ($rs['ISSUE_TYPE']==2){
            $hide='';
        }

    }



    ?>
    <!------------------------------------------------- بيانات القضية التنفيذية ------------------------------------------->
    <fieldset class="field_set" <?= $hide ?>   id="field_set_exe_issue">
        <legend >بيانات القضية التنفيذية</legend>
        <div class="form-group">
            <label class="col-sm-1 control-label">رقم القضية</label>
            <div class="col-sm-2">
                <input   type="text" data-val="true" value="<?php echo @$rs['EXE_ISSUE_NO'] ;?>"  placeholder="رقم القضية"   name="exe_issue_no" id="txt_exe_issue_no" class="form-control" data-val-required="حقل مطلوب">
            </div>
            <label class="col-sm-1 control-label"> / </label>

            <div class="col-sm-2">
                <input   type="text" data-val="true" value="<?php echo @$rs['EXE_ISSUE_YEAR'] ;?>"   placeholder="سنة القضية"   name="exe_issue_year" id="txt_exe_issue_year" class="form-control" data-val-required="حقل مطلوب">
            </div>
            <div class="col-sm-2">

                <?php  if ((  @$rs['ADOPT']== 2 ) and ( @$rs['ISSUE_TYPE'] == 2 )) {
                    if( $rs['SER_ISSUE_TYPE'] == null){?>
                        <a  href="<?=base_url("issues/issues/create/".@$rs['SER'] )?>" target="_blank"><button type="button" class="btn btn-primary">القضة التنفيذية</button></a>
                    <?php } else { ?>
                        <a target="_blank" href="<?=base_url("issues/issues/get_exec_issue_info/".@$rs['SER_ISSUE_TYPE'] )?>"><button type="button" class="btn btn-primary">القضة التنفيذية</button></a>
                    <?php } }//else { ?>

            </div>

        </div>




    </fieldset>


    <!-------------------------------------------------اجراءات المحكمة------------------------------------------->
    <fieldset  class="field_set">
        <legend >اجراءات المحكمة</legend>

        <div   class="details" >
            <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME1", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>

        </div>

    </fieldset>

    <hr/>
    <!-------------------------------------------الأقساط-------------------------------------------------->
    <?php
    if(count(@$details) > 0) { // تعديل

        foreach($details as $row1) {
            if($row1['TYPE'] == 3){
                $hide_ins = '';
                break;

            }

        }}

    ?>
    <div <?= $hide_ins ?>  id="ins_div">
        <fieldset  class="field_set">
            <legend>الأقساط</legend>

            <div class="details" >
                <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME2", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>

            </div>

        </fieldset>

        <hr/>
    </div>

    <?PHP //}

    ?>

    <!-------------------------------------------------حالة القضية------------------------------------------->
    <fieldset  class="field_set">
        <legend >حالة القضية</legend>
        <div class="form-group">
            <label class="col-sm-1 control-label">مبلغ الدفع</label>
            <div class="col-sm-2">
                <input type="text" value="<?php echo @$rs['PAID_VALUE'] ;?>" data-val="true" placeholder="مبلغ الدفع"   name="paid_value" id="txt_paid_value" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="paid_value" data-valmsg-replace="true"></span>
            </div>

            <label class="col-sm-1 control-label">حالة القضية</label>
            <div class="col-sm-2">
                <select name="status" id="dp_status" class="form-control">

                    <?php foreach($status as $row) :?>
                        <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count(@$rs)>0)?@$rs['STATUS']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>
    </fieldset>


    <!-------------------------------------------------ملاحظات على القضية------------------------------------------->
    <fieldset  class="field_set">
        <legend >ملاحظات على القضية</legend>
        <div class="form-group">
            <label class="col-sm-1 control-label">ملاحظات</label>


            <div class="col-sm-8">
                <textarea class="form-control" name="issues_notes"   id="txt_issues_notes"style="margin: 0px 0px 0px -413.896px; width: 1555px; height: 148px;"><?php echo @$rs['ISSUES_NOTES'] ;?></textarea>
            </div>



        </div>
    </fieldset>

    <!--------------------------------------------------------------------------------------------------->



    </div>
    <div class="modal-footer">
        <?php
        if (  HaveAccess($post_url)  && ($isCreate || ( @$rs['ADOPT']==1 ) ) AND  ( $isCreate || (@$rs['INSERT_USER']==$this->user->id)?1 : 0 ) ||  ( $isCreate || (@$rs['UPDATE_USER']==$this->user->id)?1 : 0 ) ) : ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>

      <?php  if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  ($this->user->branch == $rs['ISSUE_BRANCH'] and  ( (@$rs['UN_ADOPT_USER']!='')?(@$rs['UN_ADOPT_USER']!=$this->user->id?1:0) : 1 )))) : ?>
            <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
        <?php  endif; ?>

        <?php  if ( HaveAccess($un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and   (($this->user->branch == $rs['ISSUE_BRANCH']) ) and   (($this->user->id != $rs['ADOPT_USER'])) )) : ?>
            <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء الاعتماد</button>
        <?php  endif; ?>
    </div>
    </form>

    </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
var count=[];
var count2=[];
var type_taqseet=[];
//$("#txt_d_id").trigger("change");
if ({$isCreate} && {$count_array})
{
return_id_info();
return_no_info();
}
function return_id_info()
{

 $.ajax({
    url: 'https://im-server.gedco.ps:8001/apis/GetData/'+$("#txt_d_id").val(),
				dataType: 'JSON',
                success: function( data ) {
				 //  if(data.DATA[0].length ==0){
				if(data.DATA.length==0){
                    $('#txt_d_id').val('');
                    $('#txt_d_name').val('');
                    danger_msg('رقم الهوية غير صحيح');

                     }
                    else{
                    var obj = (data.DATA[0]);

                    var FULL_NAME = obj.FNAME_ARB+' '+obj.SNAME_ARB+' '+obj.TNAME_ARB+' '+obj.LNAME_ARB;

                    $('#txt_d_name').val(FULL_NAME);
					$('#txt_d_address').val(obj.CI_CITY+'-'+obj.STREET_ARB);
}
                }
            });
}

function return_no_info()
{

get_data('{$get_sub_info_url}',{id:$("#txt_sub_no").val()},function(data){
 //console.log(data[0]);
 var item = data[0];
 if (data.length == 1){
 $("#txt_sub_name").val(item.NAME);
  $("#txt_for_month").val(item.FOR_MONTH);
   $("#txt_net_pay").val(item.NET_TO_PAY);
   $("#txt_type_name").val(item.TYPE_NAME);
   $("#txt_id").val(item.ID);
   $("#txt_address").val(item.ADDRESS);
   $("#txt_type_pa").val(item.TYPE);
 }
 else
 {
 danger_msg('رقم الاشتراك غير صحيح');
 $("#txt_sub_no").val('');
  $("#txt_sub_name").val('');
    $("#txt_for_month").val('');
    $("#txt_net_pay").val('');
   $("#txt_type_name").val('');
   $("#txt_id").val('');
   $("#txt_address").val('');
   $("#txt_type_pa").val('');
 }

            });

}

 function return_adopt (type){

                if(type == 1){

					   get_data('{$adopt_url}',{id: $('#txt_SER').val()},function(data){


           if(data =='1')
           {

                            success_msg('رسالة','تم اعتماد بنجاح ..');
                          reload_Page();



                          }
                    },'html');

				                }
                            if(type == 2){
                    get_data('{$un_adopt_url }',{id:$('#txt_SER').val()},function(data){
                    if(data =='1')
                    {

                           success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
                           reload_Page();
                    }
                    },'html');

                    }


                }
 $(document).ready(function() {


         $('#dp_status').select2().on('change',function(){
        });
       $('#dp_court_name').select2().on('change',function(){
        });
		$('#dp_issue_branch').select2().on('change',function(){
		get_data('{$get_branch_issues}',{no: $('#dp_issue_branch').val()},function(data){
		;

            $('#dp_court_name').html('');
              $('#dp_court_name').append('<option></option>');
              $("#dp_court_name").select2('val','');
            $.each(data,function(index, item)
            {

            $('#dp_court_name').append('<option value=' + item.CON_NO + '>' + item.CON_NAME + '</option>');
            });
            });
        });
		$('#dp_exe_court_name').select2().on('change',function(){
        });
         $('#dp_issue_type').select2().on('change',function(){
			 if($(this).val()=='2' || $(this).val()=='4'){
				 $('#field_set_exe_issue').show();
			 }
			 else{

			 if( ($('#txt_exe_issue_no').val() != '') || ($('#txt_exe_issue_year').val() != '') ){
			  danger_msg('يجب عليك اولاً تغريغ الحقول الخاصة بالقضية التنفيذية')
			   $("#dp_issue_type").select2('val','2');;

			 }
			 else {
			 $('#field_set_exe_issue').hide();}


			 }
        });






		$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){

                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                  //  console.log(data);
                    //get_to_link(window.location.href);
                     get_to_link('{$get_url}/'+parseInt(data));
                }else{
                 //console.log(data);
                      danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });
});

 $('#txt_d_id').change(function(){
 $.ajax({
    url: 'https://im-server.gedco.ps:8001/apis/GetData/'+$(this).val(),
				dataType: 'JSON',
                success: function( data ) {
				 //  if(data.DATA[0].length ==0){
				if(data.DATA.length==0){
                    $('#txt_d_id').val('');
                    $('#txt_d_name').val('');
                    danger_msg('رقم الهوية غير صحيح');

                     }
                    else{
                    var obj = (data.DATA[0]);

                    var FULL_NAME = obj.FNAME_ARB+' '+obj.SNAME_ARB+' '+obj.TNAME_ARB+' '+obj.LNAME_ARB;

                    $('#txt_d_name').val(FULL_NAME);
					$('#txt_d_address').val(obj.CI_CITY+'-'+obj.STREET_ARB);
 }
                }
            });
          /* $.ajax({
               url: 'https://im-server.gedco.ps:8001/apis/GetData/'+$(this).val(),
				dataType: 'JSON',
                success: function( data ) {
                   //  if(data.DATA[0].length ==0){
				if(data.DATA.length==0){
                    $('#txt_d_name').val('');
                    $('#txt_d_id').val('');
                    danger_msg('رقم الهوية غير صحيح');

                     }
                    else{
                    var obj = $.parseJSON(data.DATA[0]);
                    var FULL_NAME = obj.CI_FIRST_ARB+' '+obj.CI_FATHER_ARB+' '+obj.CI_GRAND_FATHER_ARB+' '+obj.CI_FAMILY_ARB;
                    var ADDRESS_D = obj.CI_STREET_ARB;
                        $('#txt_d_name').val(FULL_NAME);
                        $('#txt_d_address').val(ADDRESS_D);

                    }
                }
            });*/
        });


       $('#txt_sub_no').change(function(){
get_data('{$get_sub_info_url}',{id:$(this).val()},function(data){
 //console.log(data[0]);
 var item = data[0];
 if (data.length == 1){
 $("#txt_sub_name").val(item.NAME);
  $("#txt_for_month").val(item.FOR_MONTH);
   $("#txt_net_pay").val(item.NET_TO_PAY);
   $("#txt_type_name").val(item.TYPE_NAME);
   $("#txt_id").val(item.ID);
   $("#txt_address").val(item.ADDRESS);
   $("#txt_type_pa").val(item.TYPE);
 }
 else
 {
 danger_msg('رقم الاشتراك غير صحيح');
 $("#txt_sub_no").val('');
  $("#txt_sub_name").val('');
    $("#txt_for_month").val('');
    $("#txt_net_pay").val('');
   $("#txt_type_name").val('');
   $("#txt_id").val('');
   $("#txt_address").val('');
   $("#txt_type_pa").val('');
 }

            });


        });

 $('#txt_pe_id').change(function(){

           $.ajax({
                url: 'https://im-server.gedco.ps:8001/apis/GetData/'+$(this).val(),
				dataType: 'JSON',
                success: function( data ) {
				//alert(data.DATA.length);
				//  if(data.DATA[0].length ==0){
				if(data.DATA.length  ==0){
                    $('#txt_pe_id').val('');
                    $('#txt_pe_name').val('');
                    danger_msg('رقم الهوية غير صحيح');

                     }
                    else{
                    var obj = (data.DATA[0]);

                    var FULL_NAME = obj.FNAME_ARB+' '+obj.SNAME_ARB+' '+obj.TNAME_ARB+' '+obj.LNAME_ARB;

                    $('#txt_pe_name').val('شركة توزيع كهرباء محافظات غزة / '+FULL_NAME);
 }
                }
            });
        });


 $('#txt_exe_pe_id').change(function(){
           $.ajax({
                url: 'https://im-server.gedco.ps:8001/apis/GetData/'+$(this).val(),
                success: function( data ) {
                   //  if(data.DATA[0].length ==0){
				if(data.DATA.length==0){
                    $('#txt_exe_pe_id').val('');
                    $('#txt_exe_pe_name').val('');
                    danger_msg('رقم الهوية غير صحيح');

                     }
                    else{
                    var obj = (data.DATA[0]);

                    var FULL_NAME = obj.FNAME_ARB+' '+obj.SNAME_ARB+' '+obj.TNAME_ARB+' '+obj.LNAME_ARB;
                    var ADDRESS_D = obj.CI_STREET_ARB;
                        $('#txt_exe_pe_name').val(FULL_NAME);
                    }
                }
            });
        });


calcall();

reBind_pram(0);
function calcall() {

    var total_weight = 0;
    var total_ins = 0;

    $('input[name="value_pay[]"]').each(function () {

        var weight = $(this).closest('tr').find('input[name="value_pay[]"]').val();
        total_weight += Number(weight);

        $('#sum_paid').text(total_weight);
       // $('#txt_paid_value').val(total_weight);
    });



        $('input[name="pay_value[]"]').each(function () {

        var ins = $(this).closest('tr').find('input[name="pay_value[]"]').val();
        total_ins += Number(ins);

        $('#sum_ins').text(total_ins);

    });



}



function reBind_pram(cnt){
$('table tr td .select2-container').remove();

$('select[name="type[]"]').each(function (i) {
           count[i]=$(this).closest('tr').find('input[name="h_count[]"]').val(i);
    });
    $('input[name="pay_date[]"]').each(function (i) {
           count2[i]=$(this).closest('tr').find('input[name="h_count2[]"]').val(i);
    });


$('input[name="value_pay[]"]').keyup(function () {
        calcall();
    });

    $('input[name="pay_value[]"]').keyup(function () {
        calcall();
    });

   $('select[name="type_pay[]"]').each(function (i) {
          // count2[i]=$(this).closest('tr').find('input[name="h_count2[]"]').val(i);
           if($(this).val()==1){

           $('#txt_value_pay_'+i).val(0);
           }



    });


   $('select[name="type_pay[]"]').select2().on('change',function(){
    var cnt_tr1=$(this).closest('tr').find('input[name="h_count2[]"]').val();

    var total_pa =$('#txt_value_pay_'+cnt_tr1);

    if($(this).val()==1){
        $('#txt_value_pay_'+cnt_tr1).val(0);
        $('#txt_value_pay_'+cnt_tr1).attr('readonly', true);
    }
    else{
    $('#txt_value_pay_'+cnt_tr1).val('');
    $('#txt_value_pay_'+cnt_tr1).attr('readonly', false);

}






    });
      $('input[name="issue_date_action[]"]').on('focus',function(){

var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
 $('#txt_issue_date_action_'+cnt_tr).datetimepicker({

                });
        });


$('input[name="j_date[]"]').on('focus',function(){

var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
 $('#txt_j_date_'+cnt_tr).datetimepicker({

                });
        });



$('input[name="pay_date[]"]').on('focus',function(){

var cnt_tr=$(this).closest('tr').find('input[name="h_count2[]"]').val();
 $('#txt_pay_date_'+cnt_tr).datetimepicker({

                });
        });

      $('select[name="type[]"]').select2().on('change',function(){

        var c=0;
        var details_pay=0;
        var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
        $('select[name="type[]"]').each(function (i) {
           if($(this).val()=='3')
            {
              c++;
            }

            });


        $('input[name="seq12[]"]').each(function (i) {
         if($(this).val()!='0')
         {
          details_pay++;
         }
         });



		if(c >1 )
		{

			if(details_pay >=1 )
		{

			danger_msg('لا يمكن التعديل غليه اقساط مدرجة');
			$('#dp_type_'+cnt_tr).select2('val','');
			$('#ins_div').show();
		}

			danger_msg('لقد تم اختيار الاقساط مسبقا');
			$('#dp_type_'+cnt_tr).select2('val','');
			$('#ins_div').show();
			// alert(cnt_tr);
		}
		else{
			if($(this).val()==2 || $(this).val()==1){
				if(c == 1 )
				{
					$('#ins_div').show();
				}
				else
				{
					$('#ins_div').hide();
				}
        }else{
         $('#txt_j_date_'+cnt_tr).val('');
         $('#txt_j_notes_'+cnt_tr).val('');
            if(($(this).val()==3) ||($(this).val()==4) ){


                    if($(this).val()==3){

				  $('#ins_div').show();

                    }



                    if($(this).val()==4){

                    if(c == 1 )
   {

            $('#ins_div').show();

            }
            else
            {

            $('#ins_div').hide();
            }
                   // $('#ins_div').hide();
                    }

                }
                else {

               $('#ins_div').hide();
                }
        }


}









        });


        }
</script>

SCRIPT;
sec_scripts($scripts);
?>