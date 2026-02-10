<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 24/07/18
 * Time: 09:43 ص
 */
$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_target");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_sector= base_url("$MODULE_NAME/$TB_NAME/public_get_sector");
$adopt= base_url("$MODULE_NAME/$TB_NAME/adopt");
$unadopt= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$get_is_adopt_url= base_url("$MODULE_NAME/$TB_NAME/public_get_is_adopt");

?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

<ul>

    <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
    <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

</ul>

</div>



<div class="form-body">
    <div class="modal-body inline_form">
    </div>

    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
        <div class="modal-body inline_form">

            <div class="form-group col-sm-1">
                <label class="control-label">الشهر</label>
                <div>
                    <input type="number" name="for_month"  id="txt_for_month" value="<?php echo date("Ym");?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                </div>
            </div>

            <div class="form-group col-sm-2">
                <label class="col-sm-1 control-label">طريقة الاحتساب</label>
                <div>
                    <select name="entry_way" data-val="true"  data-val-required="حقل مطلوب"  id="dp_entry_way" class="form-control">
                        <option></option>
                        <?php foreach($enter_way as $row) :?>

                            <?php if ($row['CON_NO'] == 3 ){?>
                        <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                       <?php }?>

                        <?php endforeach; ?>

                    </select>

                    <span class="field-validation-valid" data-valmsg-for="entry_way" data-valmsg-replace="true"></span>




                </div>
            </div>

            <div class="form-group col-sm-2">
                <label class="col-sm-1 control-label">القطاع</label>
                <div>
                   <select name="sector" data-val="true"  data-val-required="حقل مطلوب"  id="dp_sector" class="form-control">
                                <option></option>
								<?php foreach($sector as $row) :?>
                                     <option value="<?= $row['ID'] ?>" >
                                    <?= $row['ID_NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                    <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>




                </div>
            </div>


        </div>

        <div class="modal-footer">

           <!-- <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>-->
            <?php  if( HaveAccess($save_all_url)):  ?>
                <button type="submit" id="save_btn_show" data-action="submit" class="btn btn-primary hidden">حفظ البيانات</button>
            <?php  endif; ?>

<?php  if( HaveAccess($adopt)):  ?>
                <button type="button"  id="adopt_btn" onclick="javascript:return_adopt(1);" class="btn btn-danger hidden">اعتماد</button>
<?php  endif; ?>

    <?php  if( HaveAccess($unadopt)):  ?>
                <button type="button" id="unadopt_btn" onclick="javascript:return_adopt(2);" class="btn btn-warning hidden">ارجاع</button>

    <?php  endif; ?>

           <!-- <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
-->

        </div>
        <div id="msg_container"></div>

        <div id="container">

        </div>
    </form>



</div>

</div>


<?php
$month=date("Ym");
$scripts = <<<SCRIPT

<script type="text/javascript">



    function return_adopt(type) {
var val = [];
var val_gaza = [];
var val_middle = [];
var val_khan = [];
var val_rafah = [];
if (type == 1) {
    $('input[name="north_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });

    $('input[name="gaza_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val_gaza[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });


    $('input[name="middle_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val_middle[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });

    $('input[name="khan_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val_khan[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });

    $('input[name="rafa_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val_rafah[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });
 if(val.length > 0 && val_rafah.length && val_khan.length && val_middle.length && val_gaza.length){
     get_data('{$adopt}',{sector:$('#dp_sector').val(),txt_for_month:$('#txt_for_month').val(),entry_way:$('#dp_entry_way').val(),adopt:2},function(data){


       if (data == 1)
            {
                success_msg('رسالة', 'تم العملية بنجاح ..');
                $('#save_btn_show').addClass("hidden");
                    $('#adopt_btn').addClass("hidden");
                   $('#unadopt_btn').removeClass("hidden");


            }



            else
            {
               danger_msg('لم يتم الاعتماد');
            }


        }, 'html');

   }

}
 else if(val.length == 0){

 //danger_msg('يتوجب عليك حفظ المؤشرات اولا');
}
if (type == 2) {
    $('input[name="north_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });

    $('input[name="gaza_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val_gaza[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });


    $('input[name="middle_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val_middle[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });

    $('input[name="khan_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val_khan[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });

    $('input[name="rafa_seq[]"]').each(function (i) {
        if($(this).attr('data-check')!='0')
        {

           val_rafah[i]=$(this).attr('data-check');

        }
        else
        {
danger_msg('يتوجب عليك حفظ الكشف اولا');
        }


    });
 if(val.length > 0 && val_rafah.length && val_khan.length && val_middle.length && val_gaza.length){
     get_data('{$unadopt}',{sector:$('#dp_sector').val(),txt_for_month:$('#txt_for_month').val(),entry_way:$('#dp_entry_way').val(),adopt:1},function(data){


       if (data == 1)
            {

                success_msg('رسالة', 'تم العملية بنجاح ..');
                 $('#save_btn_show').removeClass("hidden");
                    $('#adopt_btn').removeClass("hidden");
                   $('#unadopt_btn').addClass("hidden");

            }



            else
            {
               danger_msg('لم يتم الاعتماد');
            }


        }, 'html');

   }

}
 else if(val.length == 0){


 //danger_msg('يتوجب عليك حفظ المؤشرات اولا');
}
       }
 function search(){
if($('#txt_for_month').val()>'{$month}')
{
 danger_msg('ادخال خاطئ لشهر');
 $('#txt_for_month').val('');
}
else
{

       var values= {page:1,sector:$('#dp_sector').val(),txt_for_month:$('#txt_for_month').val(),entry_way:$('#dp_entry_way').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }
}
    function LoadingData(){
    var values= {page:1,sector:$('#dp_sector').val(),txt_for_month:$('#txt_for_month').val(),entry_way:$('#dp_entry_way').val()};
          ajax_pager_data('#page_tb > tbody',values);
    }
 $(document).ready(function() {


 $('#txt_for_month').on('change',function(){
 if($('#txt_for_month').val().substring(4, 6)=='13')
 {

 $('#txt_for_month').val((Number($('#txt_for_month').val().substring(0, 4))+1).toString()+'01')
 }
  if($('#txt_for_month').val().substring(4, 6)=='00')
 {

 $('#txt_for_month').val((Number($('#txt_for_month').val().substring(0, 4))-1).toString()+'12')
 }
search();

  get_data('{$get_is_adopt_url}',{sector:$('#dp_sector').val(),txt_for_month:$('#txt_for_month').val(),entry_way:$('#dp_entry_way').val()} ,function(data){
  if(data=='')
  {

                    $('#save_btn_show').removeClass("hidden");

                    $('#adopt_btn').addClass("hidden");
                   $('#unadopt_btn').addClass("hidden");



  }
        $.each(data, function(i,item){
        if(item.T_ADOPT==1)
       {



                    $('#save_btn_show').removeClass("hidden");
                    $('#adopt_btn').removeClass("hidden");
                   $('#unadopt_btn').addClass("hidden");

       }
        else if(item.T_ADOPT==2)
        {
                $('#save_btn_show').addClass("hidden");
                    $('#adopt_btn').addClass("hidden");
                   $('#unadopt_btn').removeClass("hidden");
                   }

        });


        });
  });
  $('#dp_entry_way').select2().on('change',function(){
  search();

  get_data('{$get_is_adopt_url}',{sector:$('#dp_sector').val(),txt_for_month:$('#txt_for_month').val(),entry_way:$('#dp_entry_way').val()} ,function(data){
  if(data=='')
  {

                    $('#save_btn_show').removeClass("hidden");

                    $('#adopt_btn').addClass("hidden");
                   $('#unadopt_btn').addClass("hidden");



  }
        $.each(data, function(i,item){
        if(item.T_ADOPT==1)
       {



                    $('#save_btn_show').removeClass("hidden");
                    $('#adopt_btn').removeClass("hidden");
                   $('#unadopt_btn').addClass("hidden");

       }
        else if(item.T_ADOPT==2)
        {
                $('#save_btn_show').addClass("hidden");
                    $('#adopt_btn').addClass("hidden");
                   $('#unadopt_btn').removeClass("hidden");
                   }

        });


        });
  });
 $('#dp_sector').select2().on('change',function(){
search();

  get_data('{$get_is_adopt_url}',{sector:$('#dp_sector').val(),txt_for_month:$('#txt_for_month').val(),entry_way:$('#dp_entry_way').val()} ,function(data){
  if(data=='')
  {

                    $('#save_btn_show').removeClass("hidden");

                    $('#adopt_btn').addClass("hidden");
                   $('#unadopt_btn').addClass("hidden");



  }
        $.each(data, function(i,item){
        if(item.T_ADOPT==1)
       {



                    $('#save_btn_show').removeClass("hidden");
                    $('#adopt_btn').removeClass("hidden");
                   $('#unadopt_btn').addClass("hidden");

       }
        else if(item.T_ADOPT==2)
        {
                $('#save_btn_show').addClass("hidden");
                    $('#adopt_btn').addClass("hidden");
                   $('#unadopt_btn').removeClass("hidden");
                   }

        });

        });
        });


        });


 $(document).ready(function() {



       $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>=1){


                    var values= {page:1,};


      get_data('{$get_sector}',{sector:$('#dp_sector').val(),txt_for_month:$('#txt_for_month').val(),entry_way:$('#dp_entry_way').val()},function(data){


  $('#container').html(data);
        },'html');

success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                   $('#save_btn_show').removeClass("hidden");
                    $('#adopt_btn').removeClass("hidden");
                   $('#unadopt_btn').addClass("hidden");

                 //     get_to_link(window.location.href);
                }else{
                     danger_msg('لم يتم الحفظ');
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);

});


});






</script>

SCRIPT;

sec_scripts($scripts);

?>