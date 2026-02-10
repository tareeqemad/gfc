<?php
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'no_signed_reasons';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_id_url = base_url("$MODULE_NAME/$TB_NAME/public_get_id");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page">الغير ملتزمين بالانصراف</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">   اسباب عدم الانصراف بدون بصمة</h3>
                <div class="card-options">
                    <?php if (HaveAccess($create_url)) { ?>
                        <a href="javascript:void(0)" class="btn btn-secondary" id="AddModal">
                            <i class="fa fa-plus"></i>
                            جديد
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>السبب</label>
                            <input type="text" placeholder="السبب" class="form-control" id="txt_reson"
                                   name="txt_reson"/>
                        </div>

                        <div class="form-group col-md-2">
                            <label>حالة الخصم</label>
                            <select class="form-control" name="is_active" id="dp_is_active">
                                <option value="">_________</option>
                                <?php foreach ($is_active_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </form>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                class="fa fa-search"></i> إستعلام
                    </button>

                    <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"
                            class="btn btn-success">
                        <i class="fa fa-file-excel-o"></i>
                        إكسل
                    </button>

                    <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light">
                        <i class="fa fa-eraser"></i>
                        تفريغ الحقول
                    </button>
                </div>
                <hr>
                <div id="container">
                    <?= modules::run($get_page_url, $page); ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Row -->
<!--Start Reason_Modal-->
<div class="modal fade" id="Reason_Modal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="reason_from" method="post" action="<?= $create_url ?>" role="form" novalidate="novalidate">
                    <div class="tr_border">
                        <div class="row">
                            <input type="hidden" name="h_no" id="txt_h_no" value="">
                            <div class="form-group col-md-6">
                                <label>السبب</label>
                                <input type="text" placeholder="السبب" class="form-control" id="txt_reson_m"
                                       name="reson_m"/>
                            </div>

                            <div class="form-group col-md-2">
                                <label>حالة الخصم</label>
                                <select class="form-control" name="is_active_m" id="dp_is_active_m">
                                    <option value="">---------</option>
                                    <option value="0">لا يخصم</option>
                                    <option value="1">يخصم</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ
                            </button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--End Reason_Modal-->

<?php
$scripts = <<<SCRIPT
<script>
 
 function reBind(){
        ajax_pager({
            reson:$('#txt_reson').val(),is_active:$('#dp_is_active').val()
         });
       }

        function LoadingData(){
          ajax_pager_data('#page_tb > tbody',{
            reson:$('#txt_reson').val(),is_active:$('#dp_is_active').val()
          });
       }


      function search(){
             get_data('{$get_page_url}',{page: 1,
                    reson:$('#txt_reson').val(),is_active:$('#dp_is_active').val()
             },function(data){
                $('#container').html(data);
                reBind();
            },'html');
        }

        
       $('#AddModal').click(function (e){
            e.preventDefault();
            $('#Reason_Modal').modal('show');
            $('#txt_reson_m').val();
            $('#dp_is_active_m').val();
            $('#txt_h_no').val();
            resetValidation($('#reason_from'));
       });
  
       function edit(id){
          get_data('{$get_id_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#reason_from #txt_reson_m').val(item.RESON);
                $('#reason_from #dp_is_active_m').val(item.IS_ACTIVE);
                $('#reason_from #txt_h_no').val(item.NO);
                $('#reason_from').attr('action','{$edit_url}');
                resetValidation($('#reason_from'));
                $('#Reason_Modal').modal('show');
            });
         });
      }


       ///// Add In Database
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد الحفظ  ؟!';
        var reson_m = $('#txt_reson_m').val();
        var is_active_m = $('#dp_is_active_m').val();
        if (reson_m == ''){
            warning_msg('يرجى ادخال السبب');
            return -1;
        }
        if (is_active_m == ''){
            warning_msg('يرجى ادخال حالة الخصم');
            return -1;
        }
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                     $('#Reason_Modal').modal('hide');
                     search()
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#Reason_Modal').modal('hide');
                     search();
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }//end if msg
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

 </script>
SCRIPT;
sec_scripts($scripts);
?>
