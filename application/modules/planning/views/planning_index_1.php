<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/10/17
 * Time: 01:29 م
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption">تخطيط الأنشطة</div>

        <ul>
            <?php
            if(HaveAccess($create_url)) echo "<li><a onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";
        //    if(HaveAccess($get_url) and HaveAccess($edit_url)) echo "<li><a onclick='javascript:{$TB_NAME}_get(get_id());' href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>";
            if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>الغاء</a> </li>";
            ?>

            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>


    <div class="form-body">
        <div id="msg_container"></div>
      <!--  <div class="form-group row">
            <label class="mr-sm-2" for="inlineFormCustomSelect">العام</label>
            <select class="custom-select mb-2 mr-sm-2 mb-sm-0 form-control" style="width: 250px" id="all_year" name="all_year">

                <?php foreach($all_year as $row) :?>
                    <option value="<?= $row['ALL_YEAR'] ?>"><?= $row['ALL_YEAR'] ?></option>
                <?php endforeach; ?>

            </select>
        </div>-->
        <div id="container">

            <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
        </div>
    </div>

</div>

<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات النشاط</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">تعريف النشاط لعام</label>
                   <div class="col-sm-1">
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="<?php echo date('Y')+1;?>" readonly>
                        <span class="field-validation-valid" data-valmsg-for="year" data-valmsg-replace="true"></span>
                       <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="seq" id="txt_seq" class="form-control">


                    </div>

                </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">نوع النشاط</label>
                        <div class="col-sm-2">
                            <select name="type" data-val="true"  data-val-required="حقل مطلوب" style="width: 250px" id="dp_type" class="form-control">
                                <option></option>
                                <?php foreach($activity_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="type" data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <div class="form-group hidden" id="proj">
                        <label class="col-sm-2 control-label">اسم المشروع</label>
                        <div class="col-sm-2">
                            <select name="project_id" data-val="true"  data-val-required="حقل مطلوب" style="width: 250px" id="dp_project_id" class="form-control">
                                <option></option>
                                <?php foreach($all_project as $row) :?>
                                    <option value="<?= $row['SER'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="project_id" data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"> الغاية  </label>
                        <div class="col-sm-6">
                            <select name="objective" data-val="true"  data-val-required="حقل مطلوب"style="width: 250px" id="dp_objective" class="form-control">
                                <option></option>
                                <option value="1">A</option>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="objective" data-valmsg-replace="true"></span>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"> الهدف</label>
                        <div class="col-sm-6">
                            <select name="goal" data-val="true"  data-val-required="حقل مطلوب" style="width: 250px" id="dp_goal" class="form-control">
                                <option></option>
                                <option value="1">B</option>
                                <span class="field-validation-valid" data-valmsg-for="goal" data-valmsg-replace="true"></span>
                            </select>
                        </div>
                    </div>



                    <div class="form-group" style="display: none">
                        <label class="col-sm-2 control-label">رقم النشاط</label>
                        <div class="col-sm-3">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="activity_no" id="txt_activity_no" class="form-control" dir="rtl" readonly>
                            <span class="field-validation-valid" data-valmsg-for="activity_no" data-valmsg-replace="true"></span>


                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">اسم النشاط</label>
                        <div class="col-sm-6">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="activity_name" id="txt_activity_name" class="form-control" dir="rtl" >
                            <span class="field-validation-valid" data-valmsg-for="activity_name" data-valmsg-replace="true"></span>


                        </div>

                    </div>



                    <div class="form-group">
                        <label class="col-sm-2 control-label">التفاصيل</label>
                            <div class="col-sm-6">
                                <textarea  data-val="false"  data-val-required="حقل مطلوب" name="detailes" id="txt_detailes" class="form-control" rows="5"></textarea>
                                <span class="field-validation-valid" data-valmsg-for="detailes" data-valmsg-replace="true"></span>


                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                    </div>
              </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

 $('#dp_type').select2().on('change',function(){

        if($("#dp_type").val()==2)
                {

                    $("#proj").removeClass("hidden");
                }
                else
                {

                $('#proj').addClass("hidden");

                }

        });

         $('#dp_project_id').select2().on('change',function(){

       //  checkBoxChanged();

        });

         $('#dp_objective').select2().on('change',function(){

       //  checkBoxChanged();

        });

           $('#dp_goal').select2().on('change',function(){

       //  checkBoxChanged();

        });

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_from'));

         $("#txt_year").val((new Date).getFullYear()+1);
          $('#dp_type').select2('val', '');
                $('#dp_goal').select2('val', '');
                $('#dp_objective').select2('val', '');
 $('#dp_project_id').select2('val', '');
        $('#{$TB_NAME}_from').attr('action','{$create_url}');

        $('#{$TB_NAME}Modal').modal();




    }

$('#planning_tb tbody').on('click', '.btn-details', function () {

id= ( $("tbody>[class~='selected']").attr('data-id') );

        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
       $('#txt_year').val('');
                $('#txt_seq').val( '');
                $('#dp_type').select2('val', '');
                $('#dp_goal').select2('val', '');
                $('#dp_objective').select2('val', '');
                $('#txt_activity_no').val( '');
                $('#txt_activity_name').val( '');
                $('#txt_detailes').val( '');
                $('#dp_project_id').select2('val', '');


                $('#txt_year').val(item.YEAR);
                $('#txt_seq').val( item.SEQ);
                $('#dp_type').select2('val',  item.TYPE);
                $('#dp_goal').select2('val',  item.GOAL);
                $('#dp_objective').select2('val',  item.OBJECTIVE);
                $('#txt_activity_no').val( item.ACTIVITY_NO);
                $('#txt_activity_name').val( item.ACTIVITY_NAME);
                $('#txt_detailes').val( item.DETAILES);

if($("#dp_type").val()==2)
                {


                    $("#proj").removeClass("hidden");
                   $('#dp_project_id').select2('val',item.PROJECT_ID);
                }
                else
                {

                $('#proj').addClass("hidden");
 $('#dp_project_id').select2('val', '');
                }

              $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));
               $('#{$TB_NAME}Modal').modal();
            });
        });
    } );

/*function {$TB_NAME}_get(id){
 get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_year').val('');
                $('#txt_seq').val( '');
                $('#dp_type').select2('val', '');
                $('#dp_goal').select2('val', '');
                $('#dp_objective').select2('val', '');
                $('#txt_activity_no').val( '');
                $('#txt_activity_name').val( '');
                $('#txt_detailes').val( '');
                $('#dp_project_id').select2('val', '');


                $('#txt_year').val(item.YEAR);
                $('#txt_seq').val( item.SEQ);
                $('#dp_type').select2('val',  item.TYPE);
                $('#dp_goal').select2('val',  item.GOAL);
                $('#dp_objective').select2('val',  item.OBJECTIVE);
                $('#txt_activity_no').val( item.ACTIVITY_NO);
                $('#txt_activity_name').val( item.ACTIVITY_NAME);
                $('#txt_detailes').val( item.DETAILES);

if($("#dp_type").val()==2)
                {


                    $("#proj").removeClass("hidden");
                   $('#dp_project_id').select2('val',item.PROJECT_ID);
                }
                else
                {

                $('#proj').addClass("hidden");
 $('#dp_project_id').select2('val', '');
                }

              $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));
               $('#{$TB_NAME}Modal').modal();
            });
        });

    }*/
 var oTable = $('#planning_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }

    function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد الغاء '+val.length+' سجلات والغاء تفاصيلها ؟!!')){
                ajax_delete(url, val ,function(data){
                    success_msg('رسالة','تم الغاء السجلات بنجاح ..');
                    container.html(data);
                     // get_to_link(window.location.href);
                });
            }
        }else
            alert('يجب تحديد السجلات المراد الغائها');
    }

     function get_id(){
        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
           container.html(data);
            $('#{$TB_NAME}Modal').modal('hide');
          // oTable.ajax.reload();
          //   get_to_link(window.location.href);
        },"html");
    });



</script>

SCRIPT;

sec_scripts($scripts);

?>

