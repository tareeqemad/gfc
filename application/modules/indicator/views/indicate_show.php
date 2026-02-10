<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 17/09/18
 * Time: 09:27 ص
 */
$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';
$TB_NAME2= 'indicatior';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_display_target");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_sector= base_url("$MODULE_NAME/$TB_NAME/public_get_sector");
$adopt= base_url("$MODULE_NAME/$TB_NAME/adopt");
$unadopt= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$get_is_adopt_url= base_url("$MODULE_NAME/$TB_NAME/public_get_is_adopt");
$indicator=base_url("indicator/indecator_info/public_get_sectors");
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
                                if($this->user->branch == -1){
                                ?>
                                <option value="-2"> عرض جميع المقرات</option>
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
								 <option value="-2">عرض جميع القطاعات</option>
                              <?php foreach($sector as $row) :?>
                                     <option value="<?= $row['ID'] ?>" >
                                    <?= $row['ID_NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>




                        </div>
                    </div>
			<div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">التصنيف الاول</label>
        <div>
            <select name="class"   id="dp_class" class="form-control">
                <option value=""></option>
                <?php if (count($rs)>0){?>
                    <?php foreach($class as $row) :?>
                        <option value="<?= $row['ID'] ?>" ><?= $row['ID_NAME'] ?></option>
                    <?php endforeach; ?>
                <?php } ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>
	 <div class="form-group col-sm-2">
                    <label class="col-sm-1 control-label">التصنيف الثاني</label>
                    <div>
                        <select name="second_class"   id="dp_second_class" class="form-control select2">
                            <option value=""></option>

                            <?php foreach($second_class_list as $row) :?>
                                <option value="<?= $row['ID'] ?>" ><?= $row['ID_NAME'] ?></option>
                            <?php endforeach; ?>

                        </select>
                        <span class="field-validation-valid" data-valmsg-for="second_class" data-valmsg-replace="true"></span>




                    </div>
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


       var values= {page:1,sector:$('#dp_sector').val(),class:$('#dp_class').val(),txt_for_month:$('#txt_for_month').val(),branch:$('#dp_br').val(),adopts:$('#dp_adopt').val(),second_class:$('#dp_second_class').val()};
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
     var values= {page:1,sector:$('#dp_sector').val(),class:$('#dp_class').val(),txt_for_month:$('#txt_for_month').val(),adopts:$('#dp_adopt').val(),second_class:$('#dp_second_class').val()};
          ajax_pager_data('#page_tb > tbody',values);
    }
 $(document).ready(function() {


 $('#dp_sector').select2().on('change',function(){
 

 get_data('{$indicator}',{no: $('#dp_sector').val()},function(data){
            $('#dp_class').html('');
			
              $('#dp_class').append('<option></option>');
              $("#dp_class").val('').trigger("change");
			  
			  $('#dp_second_class').html('');
			
              $('#dp_second_class').append('<option></option>');
              $("#dp_second_class").val('').trigger("change");
            $.each(data,function(index, item)
            {
            $('#dp_class').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });
search();



        });
		 $('#dp_class').select2().on('change',function(){
				  if($("#dp_sector").val() == '' )
 danger_msg('عليك اختيار التصنيف الاول','');
 else
 {
				   get_data('{$indicator}',{no: $('#dp_class').val()},function(data){
            $('#dp_second_class').html('');
              $('#dp_second_class').append('<option></option>');
              $("#dp_second_class").val('').trigger("change");
            $.each(data,function(index, item)
            {
            $('#dp_second_class').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });
search();

}

        });
		 $('#dp_second_class').select2().on('change',function(){
search();



        });
 $('#dp_adopt').select2().on('change',function(){
 
search();



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



        });

        });


 $(document).ready(function() {

 $('#dp_br').select2().on('change',function(){

 
search();


        });




});






</script>

SCRIPT;

sec_scripts($scripts);

?>