<?php

$MODULE_NAME= 'issues';
$TB_NAME= 'bonds';
$back_url=base_url("$MODULE_NAME/$TB_NAME");
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$get_branch_issues=base_url("$MODULE_NAME/$TB_NAME/public_get_court");
$subscribers_tb_get=base_url("$MODULE_NAME/$TB_NAME/public_subscribers_tb_get");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_bond_info");
$get_sub_info_url =base_url("$MODULE_NAME/$TB_NAME/public_sub_info");
$get_charge_report=base_url("issues/bonds/charge_index");
$DET_TB_NAME2='public_get_payments_bond_details';

$rs=($isCreate)? array():( count($bond_data) > 0 ? $bond_data[0] : array()) ;

$gedco_branch_issuess= modules::run("issues/bonds/public_get_court_b", (count($rs)>0)?$rs['BRANCH']:$this->user->branch);
$gedco_subscribers_tb_get= modules::run("issues/bonds/public_subscribers_tb_get_b", (count($rs)>0)?$rs['ID']:-100);


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
<form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
<div class="modal-body inline_form">

<!-------------------------------------------------- بيانات المشترك  ------------------------------------------->
<fieldset  class="field_set">
    <legend >بيانات المشترك</legend>

    <div class="form-group">
        <label class="col-sm-1 control-label">رقم المسلسل</label>
        <div class="col-sm-2">
            <input type="text" readonly value="<?php echo @$rs['SER'] ;?>"  name="ser" id="txt_ser" class="form-control" >
        </div>

        <?php
        if($this->user->branch==1)
        {
        ?>
        <label class="col-sm-1 control-label">الفرع</label>
        <div class="col-sm-2">

            <select name="branch" id="dp_branch" class="form-control">
                <option>-</option>
                <?php foreach($branches as $row) :?>
                    <?php
                    if($row['NO']<>1)
                    {
                        ?>

                        <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==((count($rs)>0)?$rs['BRANCH']:0)){ echo " selected"; } ?> > <?= $row['NAME'] ?> </option>
                    <?php
                    }
                    ?>

                <?php endforeach; ?>
            </select>
            <?php
            }
            else {
            ?>
            <div class="col-sm-2">
                <input type="hidden" value="<?php echo $this->user->branch;?>" name="branch" id="txt_branch">

            </div>


            <?php

                 }
            ?>


        </div>


    </div>



		<div class="form-group">
        <label class="col-sm-1 control-label">الية ادخال بيانات المشترك </label>

        <div class="col-sm-2">
            <select name="sub_info_data" id="dp_sub_info_data" class="form-control">

                <?php foreach($way_sub_info_data as $yrow) :?>
                    <option  value="<?= $yrow['CON_NO'] ?>" <?php if ($yrow['CON_NO']==((count($rs)>0)?$rs['WAY_SUB_DATA']:0)){ echo " selected"; } ?> ><?php echo $yrow['CON_NAME']  ?></option>
				<?php endforeach; ?>
            </select>
        </div>
		
		<label class="col-sm-1 control-label ">الحالة</label>
        <div class="col-sm-2">
            <input type="text" data-val="true" value="<?php echo @$rs['SUB_STATUS'] ;?>" placeholder="الحالة"   data-val-required="حقل مطلوب" name="sub_type" id="txt_sub_type" class="form-control" readonly>
            <span class="field-validation-valid" data-valmsg-for="sub_type" data-valmsg-replace="true"></span>
        </div>
		
		</div>
	
    <div class="form-group sub_info_class">
        <label class="col-sm-1 control-label ">رقم الاشتراك</label>
        <div class="col-sm-2">
            <input type="text" data-val="true" value="<?php echo @$rs['SUB_NO'] ;?>" placeholder="رقم الاشتراك"   data-val-required="حقل مطلوب" name="sub_no" id="txt_sub_no" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="sub_no" data-valmsg-replace="true"></span>
        </div>

        <label class="col-sm-1 control-label">اسم المشترك</label>
        <div class="col-sm-5">
            <input type="text" data-val="true" value="<?php echo @$rs['SUB_NAME'] ;?>" readonly placeholder="اسم المشترك"  data-val-required="حقل مطلوب" name="sub_name" id="txt_sub_name" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="sub_name" data-valmsg-replace="true"></span>
        </div>
        </div>
    <div class="form-group">
        <label class="col-sm-1 control-label">رقم الهوية</label>
        <div class="col-sm-2">
            <input type="text" data-val="true" readonly value="<?php echo @$rs['ID'] ;?>"  placeholder="رقم الهوية"  data-val-required="حقل مطلوب" name="id" id="txt_id" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="id" data-valmsg-replace="true"></span>
        </div>
    </div>
	
	    <div class="form-group sub_info_id_class hidden">
      <label class="col-sm-1 control-label">رقم الاشتراك</label>

        <div class="col-sm-2">
            <select name="sub_info_id" id="dp_sub_info_id" class="form-control">

                <?php foreach($gedco_subscribers_tb_get as $grow) :?>
                    <option  value="<?= $grow['NO'] ?>" <?php if ($grow['NO']==((count($rs)>0)?$rs['SUB_NO']:0)){ echo " selected"; } ?> ><?php echo $grow['NO']  ?></option>                <?php endforeach; ?>
            </select>
        </div>

        <label class="col-sm-1 control-label">اسم المشترك</label>
        <div class="col-sm-5">
            <input type="text" data-val="true" value="<?php echo @$rs['SUB_NAME'] ;?>" readonly placeholder="اسم المشترك"  data-val-required="حقل مطلوب" name="sub_name_id" id="txt_sub_name_id" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="sub_name_id" data-valmsg-replace="true"></span>
        </div>
        </div>




</fieldset>

<hr/>

<!-------------------------------------------------بيانات السند------------------------------------------->
<fieldset  class="field_set">
    <legend >بيانات السند</legend>

    <div class="form-group">
        <label class="col-sm-1 control-label">رقم السند</label>
        <div class="col-sm-2">
            <input type="text" data-val="true" value="<?php echo @$rs['BOND_NO'] ;?>"  placeholder="رقم السند" data-val-required="حقل مطلوب" name="bond_no" id="txt_bond_no" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="bond_no" data-valmsg-replace="true"></span>
        </div>
        <label class="col-sm-1 control-label"> سنة السند </label>

        <div class="col-sm-2">
            <input type="text" data-val="true" value="<?php echo @$rs['BOND_YEAR'] ;?>"  placeholder="سنة السند"   data-val-required="حقل مطلوب" name="bond_year" id="txt_bond_year" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="bond_year" data-valmsg-replace="true"></span>
        </div>
    </div>




    <div class="form-group">
        <label class="col-sm-1 control-label">مبلغ السند</label>
        <div class="col-sm-2">
            <input type="text" data-val="true" value="<?php echo @$rs['BOND_VALUE'] ;?>"  placeholder="مبلغ السند" data-val-required="حقل مطلوب" name="bond_value" id="txt_bond_value" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="bond_value" data-valmsg-replace="true"></span>
        </div>

        <label class="col-sm-1 control-label">تاريخ تحرير السند</label>
        <div class="col-sm-2">
            <input type="text" value="<?php echo @$rs['BOND_DATE'] ;?>"  placeholder="تاريخ تحرير السند" data-val="true" data-type="date"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"   data-val-required="حقل مطلوب" name="bond_date" id="txt_bond_date" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="bond_date" data-valmsg-replace="true"></span>
        </div>

        <label class="col-sm-1 control-label">كاتب العدل</label>

        <div class="col-sm-2">
            <select name="court_name" id="dp_court_name" class="form-control">

                <?php foreach($gedco_branch_issuess as $row) :?>
                    <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['COURT_NAME']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>                <?php endforeach; ?>
            </select>
        </div>


    </div>

    <?php if(!$isCreate){ ?>
    <div class="form-group">
        <label class="col-sm-1 control-label">ارفاق صورة السند</label>
        <div class="col-sm-2">
           <?php
              if (  HaveAccess($post_url)  && ($isCreate || ( @$rs['ADOPT']==1 ) )/* AND  ( $isCreate || (@$rs['INSERT_USER']==$this->user->id)?1 : 0 ) ||  ( $isCreate || (@$rs['UPDATE_USER']==$this->user->id)?1 : 0 )*/ ) { ?>
            <?php echo modules::run('attachments/attachment/index',$rs['SER'],'issues'); ?>
            <?php  }else{  ?>
            <?php echo modules::run('attachments/attachment/indexInline',$rs['SER'],'issues',0); ?>
            <?php  } ?>
        
           
        </div>

    </div>
<?php  } ?>
</fieldset>

<hr/>
<!-------------------------------------------------بيانات القسط------------------------------------------->
<fieldset  class="field_set">
    <legend >بيانات القسط</legend>

    <div class="form-group">
        <label class="col-sm-1 control-label">قيمة القسط</label>
        <div class="col-sm-2">
            <input type="text" data-val="true" value="<?php echo @$rs['INSTALLMENT_VALUE'] ;?>"  placeholder="قيمة قسط "  data-val-required="حقل مطلوب"  name="installment_value" id="txt_installment_value" class="form-control">
            <span class="field-validation-valid" data-valmsg-for="installment_value" data-valmsg-replace="true"></span>
        </div>
        <label class="col-sm-1 control-label"> عدد الاقساط </label>

        <div class="col-sm-2">
            <input type="text" data-val="true" value="<?php echo @$rs['INSTALLMENT_CNTR'] ;?>"  placeholder="عدد الاقساط"   data-val-required="حقل مطلوب" name="installment_cntr" id="txt_installment_cntr" class="form-control" readonly>
            <span class="field-validation-valid" data-valmsg-for="installment_cntr" data-valmsg-replace="true"></span>
        </div>
    </div>





    <div class="form-group">
        <label class="col-sm-1 control-label">تاريخ أول قسط</label>
        <div class="col-sm-2">
            <input type="number" name="from_installment_date"  id="txt_from_installment_date" value="<?php echo @$rs['FROM_INSTALLMENT_DATE'] ;?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  placeholder="تاريخ أول قسط" >
            <span class="field-validation-valid" data-valmsg-for="from_installment_date" data-valmsg-replace="true"></span>
        </div>

        <label class="col-sm-1 control-label">تاريخ اخر قسط</label>
        <div class="col-sm-2">
            <input type="number" name="to_installment_date"  id="txt_to_installment_date" value="<?php echo @$rs['TO_INSTALLMENT_DATE'] ;?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  placeholder="تاريخ اخر قسط" >
            <span class="field-validation-valid" data-valmsg-for="to_installment_date" data-valmsg-replace="true"></span>
  </div>




    </div>


</fieldset>
<hr/>
    <!-------------------------------------------الأقساط-------------------------------------------------->
<!--
    <div >
        <fieldset  class="field_set">
            <legend>الأقساط</legend>

            <div class="details" >
                <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME2", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>

            </div>

        </fieldset>

        <hr/>
    </div>


<hr/>

-->
<!--------------------------------------------------------------------------------------------------->

    <fieldset  class="field_set">
        <legend >ملاحظات على سند الدين</legend>
        <div class="form-group">
            <label class="col-sm-1 control-label">ملاحظات</label>


            <div class="col-sm-8">
                <textarea class="form-control" name="notes"   id="txt_notes"style="margin: 0px 0px 0px -413.896px; width: 1555px; height: 148px;"><?php echo @$rs['NOTES'] ;?></textarea>
            </div>



        </div>
    </fieldset>


</div>
<div class="modal-footer">
        <?php
        if (  HaveAccess($post_url)  && ($isCreate || ( @$rs['ADOPT']==1 ) ) /*AND  ( $isCreate || (@$rs['INSERT_USER']==$this->user->id)?1 : 0 ) || */ /*( $isCreate || (@$rs['UPDATE_USER']==$this->user->id)?1 : 0 )*/ ) : ?>
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>
<!--

        <?php  if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  ($this->user->branch == $rs['BRANCH'] and  ( (@$rs['UN_ADOPT_USER']!='')?(@$rs['UN_ADOPT_USER']!=$this->user->id?1:0) : 1 )))) : ?>
            <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
        <?php  endif; ?>

        <?php  if ( HaveAccess($un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and   (($this->user->branch == $rs['BRANCH']) ) and   (($this->user->id != $rs['ADOPT_USER'])) )) : ?>
            <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء الاعتماد</button>
        <?php  endif; ?>
-->

             <?php  if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' )  and  ((($this->user->branch == $rs['BRANCH']) or ($this->user->branch==1))and  ( (@$rs['UN_ADOPT_USER']!='')?(@$rs['UN_ADOPT_USER']!=$this->user->id?1:0) : 1 )))) : ?>
                 <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
             <?php  endif; ?>
             <?php  if ( HaveAccess($un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and   (($this->user->branch == $rs['BRANCH']) or ($this->user->branch==1)) and   (($this->user->id != $rs['ADOPT_USER']))/*$rs['ISSUE_BRANCH']*/ )) : ?>
                 <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء الاعتماد</button>
             <?php  endif; ?>
         <?php if ( (count($rs) == 0) )
            $hide='hidden';
            else if($rs['IS_PREPAID']==1)
{
$hide='';
}
else
$hide='hidden';
         ?>
         <button type="button" id="button_report"  onclick="javascript:;" class="btn btn-warning <?=$hide?>">تقرير الشحنات</button>

</div>
</form>

</div>

</div>




<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
var count=[];

 $('#txt_from_installment_date').on('change',function(){
if(isNaN($('#txt_from_installment_date').val()) )
 {
 $('#txt_from_installment_date').val('');
 danger_msg('ادخال خاطئ لتاريخ اول القسط','');

 }
 else if($('#txt_from_installment_date').val().length !=6)
 {
  $('#txt_from_installment_date').val('');
  danger_msg('ادخال خاطئ لتاريخ اول القسط','');
 }
 else
 {
  if($('#txt_from_installment_date').val().substring(4, 6)>='13')
 {

 $('#txt_from_installment_date').val((Number($('#txt_from_installment_date').val().substring(0, 4))+1).toString()+'01')
 }
  if($('#txt_from_installment_date').val().substring(4, 6)=='00')
 {

 $('#txt_from_installment_date').val((Number($('#txt_from_installment_date').val().substring(0, 4))-1).toString()+'12')
 }
 }
 });

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
                    if(data =='1')
                    {

                           success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
                           reload_Page();
                    }
                    },'html');

                    }


                }
 $(document).ready(function() {
 					 

if($('#dp_sub_info_data').val() == '2')
		{
		 $(".sub_info_class").addClass("hidden");
		 $(".sub_info_id_class").removeClass("hidden");		 
		
		 $("#txt_id").attr("readonly", false); 
		}
		else if($('#dp_sub_info_data').val() == '1')
		{
		
		
		$(".sub_info_class").removeClass("hidden");
		$(".sub_info_id_class").addClass("hidden");		 
		$("#txt_id").attr("readonly", true); 
		}

		$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');

            var form = $("#bonds_form")
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

$('#txt_bond_value,#txt_installment_value,#txt_from_installment_date').on('change',function(){

$('#txt_installment_cntr').val(Math.ceil($('#txt_bond_value').val()/$('#txt_installment_value').val()));
if($('#txt_from_installment_date').val() != '')
{

//$('#txt_to_installment_date').val(Number($('#txt_installment_cntr').val())+Number($('#txt_from_installment_date').val()));
var dt = new Date($('#txt_from_installment_date').val().substring(0, 4),Number($('#txt_from_installment_date').val().substring(4, 6))-1,1);
$('#txt_to_installment_date').val(add_months(dt, $('#txt_installment_cntr').val(),$('#txt_from_installment_date').val()).toString());



}
});
       
       $('#dp_court_name').select2().on('change',function(){
        });
		
		$('#dp_sub_info_id').select2().on('change',function(){
        });
		
		
		$('#dp_sub_info_data').select2().on('change',function(){
		
		if($('#dp_sub_info_data').val() == '2')
		{
		 $(".sub_info_class").addClass("hidden");
		 $(".sub_info_id_class").removeClass("hidden");		 
		 $("#txt_id").val('');
		 $("#txt_sub_type").val('');		 
		 $("#txt_sub_name_id").val("");
		 $("#txt_id").attr("readonly", false); 
		}
		else if($('#dp_sub_info_data').val() == '1')
		{
		$("#txt_id").val("").trigger("change");
		$("#txt_sub_name_id").val("");
		$(".sub_info_class").removeClass("hidden");
		$(".sub_info_id_class").addClass("hidden");		 
		$("#txt_id").val('');
		$("#txt_sub_type").val('');
		$("#txt_sub_no").val('');
		$("#txt_sub_name").val('');		
		$("#txt_id").attr("readonly", true); 
		}
		
        });
		$('#dp_branch').select2().on('change',function(){
		get_data('{$get_branch_issues}',{no: $('#dp_branch').val()},function(data){
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
		//////////////////////////////

		$('#txt_id').on('change',function(){
		
		get_data('https://gs.gedco.ps/gfc/issues/Bonds/public_subscribers_tb_get/',{id: $('#txt_id').val()},function(data){
		;

            $('#dp_sub_info_id').html('');
             $('#dp_sub_info_id').append('<option></option>');
             $("#dp_sub_info_id").select2('val','');
            $.each(data,function(index, item)
            {

            $('#dp_sub_info_id').append('<option value=' + item.NO + '>' + item.NO + '</option>');
            });
            });
		//	$('#dp_sub_info_id option:eq(1)').prop('selected',true);
        });
		///////////////////////////////////////////////
		
				 $('#dp_sub_info_id').select2().on('change',function(){
				 		
		get_data('https://gs.gedco.ps/gfc/issues/Bonds/public_subscribers_tb_sub_get/',{no: $('#dp_sub_info_id').val()},function(data){
		
	var item = data[0];
	console.log(data.length);
	console.log(item.NAME);
	if (data.length == 1){
	$("#txt_sub_name_id").val(item.NAME);
	$("#txt_sub_type").val(item.SUB_STATUS);
	}
	else
	{
	$("#txt_sub_name_id").val("");
	$("#txt_sub_type").val("");
	}

            });
        });


});



       $('#txt_sub_no').change(function(){
			get_data('{$get_sub_info_url}',{id:$(this).val()},function(data){
				var item = data[0];
				if (data.length == 1){
					$("#txt_sub_name").val(item.NAME);
					$("#txt_id").val(item.ID);
					$("#txt_sub_type").val(item.SUB_STATUS);			

					   if(item.IS_PREPAID == 1){
					   $('#button_report').removeClass( "hidden" );
					   }else{
					   $('#button_report').addClass( "hidden" );
					   }

					}
					else
					{
						danger_msg('رقم الاشتراك غير صحيح');
						$("#txt_sub_no").val('');
						$("#txt_sub_name").val('');
						$("#txt_id").val('');
						$("#txt_sub_type").val("");
					}

            });


        });


$('#button_report').on('click',  function (e) {

             _showReport('{$get_charge_report}/'+$("#txt_sub_no").val());





            });

calcall();
reBind_pram(0);
function add_months(dt, n,dtformate)
 {
 if(n==1)
 {
 return dtformate;
 }
 else
dt.setMonth(dt.getMonth()+Number(n));
if(dt.getMonth()<10)
{
if(dt.getMonth()==0)
{
return dt.getFullYear()+'01';
}
else
return dt.getFullYear()+'0'+dt.getMonth();
}
else
return dt.getFullYear()+''+dt.getMonth();


 }

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
</script>

SCRIPT;
sec_scripts($scripts);
?>

