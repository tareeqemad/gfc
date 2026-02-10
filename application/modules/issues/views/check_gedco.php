<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'checks';
$DET_TB_NAME2='public_get_payments_check_details';
$get_sub_info_url =base_url("$MODULE_NAME/$TB_NAME/public_sub_info");
$back_url=base_url("$MODULE_NAME/$TB_NAME");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_check_info");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));


$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$rs=($isCreate)? array():( count($check_data) > 0 ? $check_data[0] : array()) ;
$rs2=($isCreate2)? array():( count($details_check) > 0 ? $details_check[0] : array() );


$get_branch_issues=base_url("$MODULE_NAME/$TB_NAME/public_get_court");
$gedco_branch_issuess= modules::run("issues/checks/public_get_court_b", (count($rs)>0)?$rs['BRANCH']:$this->user->branch);
$select_subscriber_no_url=base_url("issues/checks/public_index");


?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>
</div>

<div class="form-body">
<div id="container">
<div class="modal-body inline_form">


    <!-------------------------------------------------- بيانات الشيك------------------------------------------->
    <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
    <?php if(count($rs) > 0){
        $check_no=$rs['CHECK_NO'];
        $check_date=$rs['CHECK_DATE'];
        $check_name=$rs['CHECK_NAME'];
        $check_value=$rs['CHECK_VALUE'];
        $bank_name=$rs['BANK_NAME'];
        $branch=$rs['BRANCH'];
        $branch_no=$rs2['BRANCH_NAME'];
        $bank_no=$rs2['BANK_NO'];
    }
    else{

        $check_no=$rs2['CHECK_ID'];
        $check_date=$rs2['CHECK_DATE'];
        $check_name=$rs2['CHECK_CUSTOMER'];
        $check_value=$rs2['CRIDET'];
        $bank_name=$rs2['CHECK_BANK_ID_NAME2'];
        $branch=$rs2['BRANCH'];
        $branch_no=$rs2['BRANCH_NAME'];
        $bank_no=$rs2['CHECK_BANK_ID'];
    }

    ?>
    <fieldset  class="field_set">
    <legend >بيانات الشيك</legend>
        <div class="form-group">
        <label class="col-sm-1 control-label">رقم المسلسل</label>
        <div class="col-sm-2">
            <input type="hidden"    name="ser_check" id="txt_ser_check" class="form-control" value="<?php echo @$rs2['SEQ'] ;?>">

            <input type="text" readonly   name="ser" id="txt_ser" class="form-control" value="<?php echo @$rs['SER'] ;?>">
        </div>




            <label class="col-sm-1 control-label">الفرع</label>
            <div class="col-sm-2">
                <input type="text" readonly data-val="true" placeholder="رقم الشيك" data-val-required="حقل مطلوب" name="branch_no" id="txt_branch_no" value=" <?php echo $branch_no;   ?>" class="form-control">
                <input type="hidden"   data-val="true"  data-val-required="حقل مطلوب" name="branch" id="txt_branch" value=" <?php echo $branch;   ?>" class="form-control">

            </div>



        </div>


        <div class="form-group">
            <label class="col-sm-1 control-label">رقم الشيك</label>
            <div class="col-sm-2">
                <input type="text" readonly data-val="true" placeholder="رقم الشيك" data-val-required="حقل مطلوب" name="check_no" id="txt_check_no" value=" <?php echo $check_no;   ?>" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="check_no" data-valmsg-replace="true"></span>
            </div>


            <label class="col-sm-1 control-label">البنك</label>
            <div class="col-sm-2">
                <input type="text" readonly data-val="true" placeholder="اسم البنك" data-val-required="حقل مطلوب" name="bank_name" id="txt_bank_name" value="<?php echo $bank_name;   ?>" class="form-control">
                <input type="hidden" readonly data-val="true" placeholder="رقم البنك" data-val-required="حقل مطلوب" name="bank_no" id="txt_bank_no" value="<?php echo $bank_no;   ?>" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="bank_name" data-valmsg-replace="true"></span>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-1 control-label">تاريخ استحقاق الشيك</label>
            <div class="col-sm-2">
                <input type="text" readonly data-val="true" placeholder="تاريخ استحقاق الشيك" data-val-required="حقل مطلوب" name="check_date" id="txt_check_date" value="<?php echo $check_date;   ?>" class="form-control">

                <span class="field-validation-valid" data-valmsg-for="check_date" data-valmsg-replace="true"></span>
            </div>

            <label class="col-sm-1 control-label">اسم صاحب الشيك</label>
            <div class="col-sm-2">
                <input type="text" readonly data-val="true" placeholder="اسم صاحب الشيك" data-val-required="حقل مطلوب" name="check_name" value="<?php echo $check_name;   ?>" id="txt_check_name" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="check_name" data-valmsg-replace="true"></span>
            </div>

            <label class="col-sm-1 control-label">قيمة الشيك</label>
            <div class="col-sm-2">
                <input type="text" readonly data-val="true" placeholder="قيمة الشيك" data-val-required="حقل مطلوب" name="check_value" value="<?php echo $check_value;   ?>" id="txt_check_value" class="form-control">
                <span class="field-validation-valid" data-valmsg-for="check_value" data-valmsg-replace="true"></span>
            </div>


        </div>







</fieldset>

<hr/>


    <!-------------------------------------------------- بيانات المشترك  ------------------------------------------->
    <fieldset  class="field_set">
        <legend >بيانات المشترك</legend>


            <div class="form-group">

                <label class="col-sm-1 control-label">رقم الاشتراك</label>
                <div class="col-sm-2">
                    <input readonly type="text"   value="<?php echo @$rs['SUB_NO'] ;?>"  placeholder="رقم الاشتراك"  name="sub_no" id="txt_sub_no" class="form-control" data-val="true" data-val-required="حقل مطلوب"  >
					<span class="field-validation-valid" data-valmsg-for="sub_no" data-valmsg-replace="true"></span>
					
                   
                </div>

                <label class="col-sm-1 control-label">اسم المشترك</label>
                <div class="col-sm-2">
                    <input type="text"  value="<?php echo @$rs['SUB_NAME'] ;?>" placeholder="اسم المشترك"  name="sub_name" id="txt_sub_name" class="form-control" data-val="true" data-val-required="حقل مطلوب" readonly>
					<span class="field-validation-valid" data-valmsg-for="sub_name" data-valmsg-replace="true"></span>
          
                </div>

                <label class="col-sm-1 control-label">رقم الهوية</label>
                <div class="col-sm-2">
                    <input type="text"  value="<?php echo @$rs['ID'] ;?>"  placeholder="رقم الهوية"   name="id" id="txt_id" class="form-control" data-val="true" data-val-required="حقل مطلوب" readonly >
					<span class="field-validation-valid" data-valmsg-for="id" data-valmsg-replace="true"></span>
         
                </div>

            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label">شهرالفاتورة</label>
                <div class="col-sm-2">
                    <input type="text" value="<?php echo @$rs['FOR_MONTH'] ;?>" placeholder="شهر الفاتورة"  name="for_month" id="txt_for_month" class="form-control" data-val="true" data-val-required="حقل مطلوب" readonly >
					<span class="field-validation-valid" data-valmsg-for="for_month" data-valmsg-replace="true"></span>
                
                </div>

                <label class="col-sm-1 control-label">قيمة الفاتورة</label>
                <div class="col-sm-2">
                    <input type="text"  value="<?php echo @$rs['NET_PAY'] ;?>" placeholder="قيمة الفاتورة"  name="net_pay" id="txt_net_pay" class="form-control" data-val="true" data-val-required="حقل مطلوب" readonly >
					<span class="field-validation-valid" data-valmsg-for="net_pay" data-valmsg-replace="true"></span>
                    
                </div>

                <label class="col-sm-1 control-label">نوع الاشتراك</label>
                <div class="col-sm-2">
                    <input type="text"  value="<?php echo @$rs['TYPE_NAME_SHOW'] ;?>"   placeholder="نوع الاشتراك"   name="type_name" id="txt_type_name" class="form-control" data-val="true" data-val-required="حقل مطلوب" readonly>
                    <input type="hidden"  value="<?php echo @$rs['TYPE_NAME'] ;?>"  placeholder="نوع الاشتراك"   name="type_pa" id="txt_type_pa" data-val="true" data-val-required="حقل مطلوب" class="form-control" >
					<span class="field-validation-valid" data-valmsg-for="type_name" data-valmsg-replace="true"></span>

           
                </div>

            </div>

            <div class="form-group">
                <label class="col-sm-1 control-label">العنوان</label>
                <div class="col-sm-8">
                    <input type="text" value="<?php echo @$rs['ADDRESS'] ;?>" data-val="true" placeholder="العنوان"    name="address" id="txt_address" class="form-control" data-val="true" data-val-required="حقل مطلوب" readonly >
                    <span class="field-validation-valid" data-valmsg-for="address" data-valmsg-replace="true"></span>
                </div>
            </div>

    </fieldset>

    <hr/>
    <!-------------------------------------------------بيانات الشكوى------------------------------------------->
<fieldset  class="field_set">
    <legend >بيانات الشكوى</legend>
    <div class="form-group">
        <label class="col-sm-1 control-label">رقم الشكوى</label>
        <div class="col-sm-2">
            <input type="text"  placeholder="رقم الشكوى"  name="complaint_no" value="<?php echo @$rs['COMPLAINT_NO'] ;?>" id="txt_complaint_no" class="form-control" data-val="true" data-val-required="حقل مطلوب">
            <span class="field-validation-valid" data-valmsg-for="complaint_no" data-valmsg-replace="true"></span>
        </div>
        <label class="col-sm-1 control-label"> / </label>

        <div class="col-sm-2">
            <input type="text" placeholder="سنة الشكوى"  name="complaint_year" value="<?php echo @$rs['COMPLAINT_YEAR'] ;?>" id="txt_complaint_year" class="form-control" data-val="true" data-val-required="حقل مطلوب">
            <span class="field-validation-valid" data-valmsg-for="complaint_year" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-1 control-label">تاريخ الشكوى</label>
        <div class="col-sm-2">
            <input type="text" placeholder="تاريخ الشكوى" data-type="date"  data-date-format="DD/MM/YYYY" value="<?php echo @$rs['COMPLAINT_DATE'] ;?>"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"    name="complaint_date" id="txt_complaint_date" class="form-control" data-val="true" data-val-required="حقل مطلوب">
            <span class="field-validation-valid" data-valmsg-for="complaint_date" data-valmsg-replace="true"></span>
        </div>

            <label class="col-sm-1 control-label">رقم هوية المشتكي</label>
            <div class="col-sm-2">
                <input type="text"  value="<?php echo @$rs['COMPLAINANT_ID'] ;?>"  placeholder="رقم هوية المدعي"  name="complainant_id" id="txt_complainant_id" class="form-control" data-val="true" data-val-required="حقل مطلوب">
                <span class="field-validation-valid" data-valmsg-for="complainant_id" data-valmsg-replace="true"></span>
            </div>


        <label class="col-sm-1 control-label">اسم المشتكي</label>
            <div class="col-sm-2">
                <input type="text"  value="<?php echo @$rs['COMPLAINANT_NAME'] ;?>" placeholder="اسم المشتكي"   name="complainant_name" id="txt_complainant_name" class="form-control" data-val="true" data-val-required="حقل مطلوب">
                <span class="field-validation-valid" data-valmsg-for="complainant_name" data-valmsg-replace="true"></span>
            </div>




    </div>
</fieldset>
<hr/>
 <!---------------------------------------------------- الاجراءات ------------------------------------------------->
    <fieldset  class="field_set">
        <legend >الاجراء</legend>
        <div class="form-group">
            <label class="col-sm-1 control-label">الاجراء</label>
            <div class="col-sm-2">
                <select name="check_action" id="dp_check_action" class="form-control">
                    <option>-</option>
                        <?php foreach($type_check as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"  <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['CHECK_ACTION']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                </select>
 </div>

            <?php
            if(@$rs['CHECK_ACTION'] == 3){
                $hide='';
            } elseif(@$rs['CHECK_ACTION'] == 1){
                $hide_div_paid='';
                $hide_div_action_date_paid='';

            }
            elseif(@$rs['CHECK_ACTION'] == 4){
                $hide_div_action='';
            }elseif(@$rs['CHECK_ACTION'] == 5){
                $hide_div_action_del='';
            }

            ?>

            <div <?= $hide_div_action ?> id="div_action">
                <label class="col-sm-1 control-label">تاريخ الحبس</label>
                <div class="col-sm-2">
                    <input type="text" placeholder="تاريخ الحبس" data-val="true" data-type="date"  data-date-format="DD/MM/YYYY" value="<?php echo @$rs['ACTION_DATE'] ;?>"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" name="action_date" id="txt_action_date" class="form-control">

                </div>
            </div>

            <div <?= $hide_div_action_date_paid ?> id="div_action_date_paid">
                <label class="col-sm-1 control-label">تاريخ الدفع</label>
                <div class="col-sm-2">
                    <input type="text" placeholder="تاريخ الدفع" data-val="true" data-type="date"  data-date-format="DD/MM/YYYY" value="<?php echo @$rs['ACTION_DATE_PAID'] ;?>"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" name="action_date_paid" id="txt_action_date_paid" class="form-control">

                </div>
            </div>

            <div <?= $hide_div_paid ?> id="div_paid">
                <label class="col-sm-1 control-label">القيمة المدفوعة</label>
                <div class="col-sm-2">
                    <input type="text" data-val="true" placeholder="القيمة المدفوعة"  name="paid_val" value="<?php echo @$rs['PAID_VAL'] ;?>" id="txt_paid_val" class="form-control">
                </div>
            </div>
			
			
			<div <?= $hide_div_action_del ?> id="div_action_del">
                <label class="col-sm-1 control-label">تاريخ الاستلام</label>
                <div class="col-sm-2">
                    <input type="text" placeholder="تاريخ الاستلام" data-val="true" data-type="date"  data-date-format="DD/MM/YYYY" value="<?php echo @$rs['DELIV_DATE'] ;?>"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" name="deliv_date" id="txt_deliv_date" class="form-control">

                </div>
            </div>

         </div>
    </fieldset>
    <!-------------------------------------------------الأقساط الشهرية------------------------------------------->

    <?php
    if(@$rs['CHECK_ACTION'] == 3)
        $hide='';
    ?>

    <fieldset <?= $hide ?>  id="ins_div"  class="field_set">

        <legend>الأقساط</legend>

        <div class="details" >
            <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME2", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>

        </div>





</fieldset>

<hr/>


<!--------------------------------------------------------------------------------------------------->

    <fieldset  class="field_set">
        <legend >ملاحظات على الشيك</legend>
        <div class="form-group">
            <label class="col-sm-1 control-label">ملاحظات</label>


            <div class="col-sm-8">
                <textarea class="form-control" name="notes"   id="txt_notes"style="margin: 0px 0px 0px -413.896px; width: 1555px; height: 148px;"><?php  echo @$rs['NOTES'] ;?></textarea>
            </div>



        </div>
    </fieldset>



    <div class="modal-footer">
        <?php
        if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) ) AND  ( $isCreate || @$rs['INSERT_USER']?$this->user->id : false ) ||  ( $isCreate || @$rs['UPDATE_USER']?$this->user->id : false ) ) : ?>
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>


        <?php if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  $this->user->branch == $rs['BRANCH'] )) : ?>
            <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
        <?php  endif; ?>
        <?php if ( HaveAccess($un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  $this->user->branch ==$rs['BRANCH'] )) : ?>
            <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء الاعتماد</button>
        <?php  endif; ?>
    </div>

</form>



</div>
</div>

</div>




<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

 function return_adopt (type){
                if(type == 1){
					   get_data('{$adopt_url}',{id: $('#txt_ser').val()},function(data){
           if(data =='1')
           {
                success_msg('رسالة','تم اعتماد بنجاح ..');
                reload_Page();
           }
             },'html');

		   }
		   if(type == 2){
		        get_data('{$un_adopt_url }',{id:$('#txt_ser').val()},function(data){
		            if(data =='1'){
		             success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
		             reload_Page();
                    }
                },'html');

           }
  }

 $(document).ready(function() {




		$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');

            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>=1){

                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    console.log(data);
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
    $('#check_details').hide();

          $('#dp_check_action').on('change',function(){
        if($(this).val()=='3'){
            $('#ins_div').show();
            $('#div_paid').hide();
            $('#div_action_del').hide();
            $('#div_action').hide();
            $('#div_action_date_paid').hide();
            $('#txt_action_date').val('');
            $('#txt_paid_val').val('');
            $('#txt_action_date_paid').val('');

            }else{
            $('#ins_div').hide();
                if($(this).val()=='1'){
                    $('#div_paid').show();
                     $('#div_action').hide();
                     $('#div_action_del').hide();
                     $('#div_action_date_paid').show();
                     $('#txt_action_date').val('');
                 }else{

                 if($(this).val()=='4'){
                    $('#div_paid').hide();
                    $('#div_action_del').hide();
                    $('#div_action_date_paid').hide();
                     $('#div_action').show();
                     $('#txt_paid_val').val('');
                     $('#txt_action_date_paid').val('');
                 }else{
					 
					 if($(this).val()=='5'){
                    $('#div_paid').hide();
                    $('#div_action_del').show();
                    $('#div_action_date_paid').hide();
                     $('#div_action').show();
                     $('#txt_paid_val').val('');
                     $('#txt_action_date_paid').val('');
                 }else{
                 $('#txt_action_date').val('');
                   $('#div_paid').hide();
                   $('#div_action_del').hide();
                   $('#div_action_date_paid').hide();
                   $('#div_action').hide();
                   $('#txt_paid_val').val('');
                   $('#txt_action_date_paid').val('');
                 }}

                 }
            }
        });


         $('#dp_check_action').select2().on('change',function(){
          var details_pay=0;
        var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();


            $('input[name="seq1[]"]').each(function (i) {
            if($(this).val()!='0')
            {
                 details_pay++;
            }
            });

            if(details_pay >= 1 )
			{
			danger_msg('لا يمكن التعديل غليه اقساط مدرجة')
			$('#dp_check_action').select2('val','3');
			 $('#ins_div').show();
            $('#div_paid').hide();
            $('#div_action_del').hide();
			
            $('#div_action_date_paid').hide();
            $('#div_action').hide();
            $('#txt_action_date').val('');
            $('#txt_paid_val').val('');;
            $('#txt_action_date_paid').val('');

			}else{
			 if($(this).val()=='1'){
                    $('#div_paid').show();
                    $('#div_action_date_paid').show();
                     $('#div_action').hide();
                     $('#txt_action_date').val('');
                 }else{

                 if($(this).val()=='4'){
                    $('#div_paid').hide();
                    $('#div_action_date_paid').hide();
                     $('#div_action').show();
                     $('#txt_paid_val').val('');
                     $('#txt_action_date_paid').val('');
                 }else{
                 $('#txt_action_date').val('');
                   $('#div_paid').hide();
                   $('#div_action_date_paid').hide();
                   $('#div_action').hide();
                   $('#txt_paid_val').val('');
                   $('#txt_action_date_paid').val('');
                 }

                 }
			}


        });


         $('#txt_complainant_id').change(function(){

           $.ajax({
                url: 'https://im-server.gedco.ps:8001/apis/GetData/'+$(this).val(),
				dataType: 'JSON',
                success: function( data ) {
				  if(data.DATA[0].length ==0){
                    $('#txt_complainant_id').val('');
                    $('#txt_complainant_name').val('');
                    danger_msg('رقم الهوية غير صحيح');

                     }
                    else{
                    var obj = (data.DATA[0]);

                    var FULL_NAME = obj.FNAME_ARB+' '+obj.SNAME_ARB+' '+obj.TNAME_ARB+' '+obj.LNAME_ARB;

                    $('#txt_complainant_name').val(FULL_NAME);
 }
                }
            });
        });





          $('#dp_branch').select2().on('change',function(){
        });

            /*   $('#txt_sub_no').change(function(){
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
  $("#txt_sub_name").val('');
    $("#txt_for_month").val('');
    $("#txt_net_pay").val('');
   $("#txt_type_name").val('');
   $("#txt_id").val('');
   $("#txt_address").val('');
   $("#txt_type_pa").val('');
 }

            });


        });*/
 $('#txt_sub_no').bind("focus",function(e){
    _showReport('$select_subscriber_no_url');
    });


calcall();
reBind_pram(0);

function calcall() {

    var total_ins = 0;

        $('input[name="pay_value[]"]').each(function () {

        var ins = $(this).closest('tr').find('input[name="pay_value[]"]').val();
        total_ins += Number(ins);

        $('#total_pays').text(total_ins);

    });

}


function reBind_pram(cnt){
$('table tr td .select2-container').remove();
    $('input[name="pay_value[]"]').keyup(function () {
        calcall();
    });

    $('input[name="pay_date[]"]').each(function (i) {
           count[i]=$(this).closest('tr').find('input[name="h_count[]"]').val(i);
    });

    $('input[name="pay_date[]"]').on('focus',function(){
        var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
        $('#txt_pay_date_'+cnt_tr).datetimepicker({
         });
    });





    }
});
</script>

SCRIPT;
sec_scripts($scripts);
?>

