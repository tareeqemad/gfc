<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 27/02/19
 * Time: 11:16 ص
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';
$TB_NAME2= 'indicatior';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_gedco");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_sector= base_url("$MODULE_NAME/$TB_NAME/public_get_sector");
$adopt= base_url("$MODULE_NAME/$TB_NAME/adopt");
$unadopt= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$get_is_adopt_url= base_url("$MODULE_NAME/$TB_NAME/public_get_is_adopt");
$indicator=base_url("$MODULE_NAME/$TB_NAME2/public_get_sector");
$page=1;
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

                    <div class="form-group col-sm-1 hidden" >

                        <label class="control-label">المقر</label>
                        <div>
                            <select  name="branch" id="dp_br"  data-curr="false"  class="form-control"  >
                                <option></option>
                                <?php
                                if($this->user->branch == 1){
                                    ?>
                                    <option value="0"> عرض جميع المقرات</option>
                                <?php
                                }?>

                                <?php foreach($branches as $row) :?>

                                    <?php if(($row['NO']!='1')) {?>
                                        <?php if(($row['NO']!='8')) {?>
                                            <option value="<?= $row['NO'] ?>">
                                                <?= $row['NAME'] ?>
                                            </option>
                                        <?php }?>
                                    <?php }?>


                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="col-sm-1 control-label">القطاع</label>
                        <div>
                            <select name="sector" data-val="true"  data-val-required="حقل مطلوب"  id="dp_sector" class="form-control">
                                <option></option>
                                <option value="0">عرض جميع القطاعات</option>
                                <?php foreach($sector as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>




                        </div>
                    </div>
					<div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">التصنيف</label>
        <div>
            <select name="class"   id="dp_class" class="form-control">
                <option></option>
                <?php if (count($rs)>0){?>
                    <?php foreach($class as $row) :?>
                        <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                <?php } ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>
                    <div class="form-group col-sm-2 hidden" id="one_branch" style="float: left">



                        <table class="table" align="center" >
                            <thead>
                            <tr>

                                <th>الاجمالي الكلي</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr align="center">
                                <td id="total_branch_print" align="center" >0</td>

                            </tr>
                            </tbody>
                        </table>


                    </div>


                    <div class="form-group col-sm-4 hidden" id="all_branch" style="float: left">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>مقر الشمال</th>

                                <th>مقر غزة</th>
                                <th>مقر الوسطى</th>
                                <th>مقر حانيونس</th>
                                <th>مقر رفح </th>
                                <th>االتقييم الكلي للمقرات</th>


                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td id="total_n" >0</td>
                                <td id="total_g" >0</td>
                                <td id="total_m" >0</td>
                                <td id="total_kh" >0</td>
                                <td id="total_r" >0</td>
                                <td id="total_a" >0</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>



                    <div class="form-group col-sm-2 hidden">
                        <label class="col-sm-1 control-label">الاعتماد</label>
                        <div>
                            <select name="adopt" data-val="true"  data-val-required="حقل مطلوب"  id="dp_adopt" class="form-control">
                                <option></option>
                                <?php foreach($adopt_const as $row1) :?>
                                    <option value="<?= $row1['CON_NO'] ?>" ><?= $row1['CON_NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="adopt" data-valmsg-replace="true"></span>




                        </div>
                    </div>


                </div>

                <div class="modal-footer">


                </div>
                <div id="msg_container"></div>

                <div id="container">
                     
                </div>
            </form>



        </div>

    </div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">






 function search(){


       var values= {page:1,sector:$('#dp_sector').val(),class:$('#dp_class').val(),txt_for_month:$('#txt_for_month').val(),branch:$('#dp_br').val(),adopts:$('#dp_adopt').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
     var values= {page:1,sector:$('#dp_sector').val(),class:$('#dp_class').val(),txt_for_month:$('#txt_for_month').val(),adopts:$('#dp_adopt').val()};
          ajax_pager_data('#page_tb > tbody',values);
    }
 $(document).ready(function() {


 $('#dp_sector').select2().on('change',function(){
get_data('{$indicator}',{no: $('#dp_sector').val()},function(data){
            $('#dp_class').html('');
              $('#dp_class').append('<option></option>');
              $("#dp_class").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_class').append('<option value=' + item.CON_NO + '>' + item.CON_NAME + '</option>');
            });
            });
search();
if($('#dp_br').val()>=1)
{

//$( "#all_branch" ).addClass("hidden");
//$( "#one_branch" ).removeClass("hidden");


  var result_amount_total = $('#result_amount_total').clone();
 $("#total_branch_print").css("text-align", "center");
$( "#total_branch_print" ).html(result_amount_total);



}
else
{

//$( "#all_branch" ).removeClass("hidden");
//$( "#one_branch" ).addClass("hidden");


  var gaza_res_amount_total = $('#gaza_res_amount_total').clone();
 $("#total_g").css("text-align", "center ");
$( "#total_g" ).html(gaza_res_amount_total);


  var north_res_amount_total = $('#north_res_amount_total').clone();
 $("#total_n").css("text-align", "center ");
$( "#total_n" ).html(north_res_amount_total);

  var middle_res_amount_total = $('#middle_res_amount_total').clone();
 $("#total_m").css("text-align", "center ");
$( "#total_m" ).html(middle_res_amount_total);

  var khan_res_amount_total = $('#khan_res_amount_total').clone();
 $("#total_kh").css("text-align", "center ");
$( "#total_kh" ).html(khan_res_amount_total);

  var rafah_res_amount_total = $('#rafah_res_amount_total').clone();
 $("#total_r").css("text-align", "center ");
$( "#total_r" ).html(rafah_res_amount_total);


}



        });
		 $('#dp_class').select2().on('change',function(){
search();



        });
 $('#dp_adopt').select2().on('change',function(){
search();

if($('#dp_br').val()>=1)
{

//$( "#all_branch" ).addClass("hidden");
//$( "#one_branch" ).removeClass("hidden");


  var result_amount_total = $('#result_amount_total').clone();
 $("#total_branch_print").css("text-align", "center" );
$( "#total_branch_print" ).html(result_amount_total);



}
else
{
//$( "#all_branch" ).removeClass("hidden");
//$( "#one_branch" ).addClass("hidden");
 var gaza_res_amount_total = $('#gaza_res_amount_total').clone();
 $("#total_g").css("text-align", "center");
$( "#total_g" ).html(gaza_res_amount_total);


  var north_res_amount_total = $('#north_res_amount_total').clone();
 $("#total_n").css("text-align", "center ");
$( "#total_n" ).html(north_res_amount_total);

  var middle_res_amount_total = $('#middle_res_amount_total').clone();
 $("#total_m").css("text-align", "center");
$( "#total_m" ).html(middle_res_amount_total);

  var khan_res_amount_total = $('#khan_res_amount_total').clone();
 $("#total_kh").css("text-align", "center");
$( "#total_kh" ).html(khan_res_amount_total);

  var rafah_res_amount_total = $('#rafah_res_amount_total').clone();
 $("#total_r").css("text-align", "center ");
$( "#total_r" ).html(rafah_res_amount_total);
}

        });

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

if($('#dp_br').val()>=1)
{

//$( "#all_branch" ).addClass("hidden");
//$( "#one_branch" ).removeClass("hidden");


  var result_amount_total = $('#result_amount_total').clone();
 $("#total_branch_print").css("text-align", "center");
$( "#total_branch_print" ).html(result_amount_total);



}
else if($('#dp_br').val()=='')
{

//$( "#all_branch" ).removeClass("hidden");
//$( "#one_branch" ).addClass("hidden");
 var gaza_res_amount_total = $('#gaza_res_amount_total').clone();
 $("#total_g").css("text-align", "center");
$( "#total_g" ).html(gaza_res_amount_total);


  var north_res_amount_total = $('#north_res_amount_total').clone();
 $("#total_n").css("text-align", "center");
$( "#total_n" ).html(north_res_amount_total);

  var middle_res_amount_total = $('#middle_res_amount_total').clone();
 $("#total_m").css("text-align", "center");
$( "#total_m" ).html(middle_res_amount_total);

  var khan_res_amount_total = $('#khan_res_amount_total').clone();
 $("#total_kh").css("text-align", "center");
$( "#total_kh" ).html(khan_res_amount_total);

  var rafah_res_amount_total = $('#rafah_res_amount_total').clone();
 $("#total_r").css("text-align", "center");
$( "#total_r" ).html(rafah_res_amount_total);
}

        });

        });


 $(document).ready(function() {

 $('#dp_br').select2().on('change',function(){
search();
if($(this).val()>=1)
{


//$( "#all_branch" ).addClass("hidden");
//$( "#one_branch" ).removeClass("hidden");
  var result_amount_total = $('#span_result_amount_total').clone();
 //$("#total_branch_print").css("text-align", "center");
$( "#total_branch_print" ).html(result_amount_total);



}
else
{

//$( "#all_branch" ).removeClass("hidden");
//$( "#one_branch" ).addClass("hidden");

 var gaza_res_amount_total = $('#gaza_res_amount_total').clone();
 $("#total_g").css("text-align", "center");
$( "#total_g" ).html(gaza_res_amount_total);

  var north_res_amount_total = $('#north_res_amount_total').clone();
 $("#total_n").css("text-align", "center");
$( "#total_n" ).html(north_res_amount_total);

  var middle_res_amount_total = $('#middle_res_amount_total').clone();
 $("#total_m").css("text-align", "center");
$( "#total_m" ).html(middle_res_amount_total);

  var khan_res_amount_total = $('#khan_res_amount_total').clone();
 $("#total_kh").css("text-align", "center");
$( "#total_kh" ).html(khan_res_amount_total);

  var rafah_res_amount_total = $('#rafah_res_amount_total').clone();
 $("#total_r").css("text-align", "center");
$( "#total_r" ).html(rafah_res_amount_total);
}

        });




});






</script>

SCRIPT;

sec_scripts($scripts);

?>