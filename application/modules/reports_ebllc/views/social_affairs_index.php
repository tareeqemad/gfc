<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 13/09/23
 * Time: 13:30 م
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Social_affairs';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_detail");
$add_complaint_url = base_url("$MODULE_NAME/$TB_NAME/add_complaint");
?>

<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1"><?=$title?></span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">احصائيات</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">
                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                    <div class="row">

                        <div class="form-group col-md-2">
                            <label> المقر</label>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['VID'] ?>"><?= $row['VNAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>رقم الملف</label>
                            <div>
                                <input type="text"  name="file_ser_no" id="txt_file_ser_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>رقم الهوية</label>
                            <div>
                                <input type="text"  name="id" id="txt_id" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>رقم الإشتراك</label>
                            <div>
                                <input type="text"  name="subscriber_no" id="txt_subscriber_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>اسم المشترك</label>
                            <div>
                                <input type="text"  name="subscriber_name" id="txt_subscriber_name" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>نوع الاشتراك</label>
                            <select type="text" name="subscriber_type" id="dp_subscriber_type" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($subscriber_type as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>التصنيف الرئيسي</label>
                            <select type="text" name="use_type" id="dp_use_type" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($use_type as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>حالة الاشتراك</label>
                            <select type="text" name="subscriber_status" id="dp_subscriber_status" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($subscriber_status as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>له شكوى</label>
                            <select type="text" name="complaint_status" id="dp_complaint_status" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($complaint_status as $row) : ?>
                                    <option value="<?=$row['VID']?>"><?=$row['VNAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#social_affairs_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>
                </div>
                <hr>
                <div id="container">

                </div>
            </div><!--end card body-->
        </div><!--end card --->
    </div><!--end col lg-12--->
</div><!--end row--->
<!--Start  EditModal تعديل موظف للكشف-->
<div class="modal fade" id="EditModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اضافة شكوى</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="createBookForm">
                    <div class="tr_border">
                        <div class="row">

                            <div class="form-group  col-md-2">
                                <label> رقم الاشتراك </label>
                                <input type="text" readonly name="subscriber_no_m" id="txt_subscriber_no_m" class="form-control">
                            </div>

                            <div class="form-group  col-md-8">
                                <label> الشكوى </label>
                                <input type="text" name="complaint_m" id="txt_complaint_m" class="form-control">
                            </div>

                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <?php if (1 || HaveAccess($update_url)): ?>
                    <button class="btn ripple btn-primary" type="button" onclick="add_data()">حفظ البيانات</button>
                 <?php endif; ?>

                </button>
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End EditModal تعديل موظف للكشف-->
<?php
$scripts = <<<SCRIPT

<script type="text/javascript">
    
    $('.sel2').select2();

    function values_search(add_page){
        var values= {page:1,file_ser_no:$('#txt_file_ser_no').val(),id:$('#txt_id').val(),subscriber_no:$('#txt_subscriber_no').val(),subscriber_name:$('#txt_subscriber_name').val(),subscriber_type:$('#dp_subscriber_type').val(),use_type:$('#dp_use_type').val(),subscriber_status:$('#dp_subscriber_status').val(),branch_id:$('#dp_branch_id').val(),complaint_status:$('#dp_complaint_status').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }
    
    function search(){
        var values= values_search(1);
        
        if ($('#dp_branch_id').val() == '' ) {
            danger_msg('رسالة','يجب اختيار المقر ..');
            return;
        }
        
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }
    
    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#social_affairs_tb > tbody',values);
    }
    
    function clear_form(){
        $('#txt_file_ser_no,#txt_id,#txt_subscriber_no,#txt_subscriber_name').val('');
        $('#dp_subscriber_type,#dp_use_type,#dp_subscriber_status,#dp_complaint_status').select2('val',0);
    }

   function add_data(){
       var serial = $('#txt_subscriber_no_m').val();
       var complaint = $('#txt_complaint_m').val();
       
       if (complaint == '') {
            danger_msg('يرجى ادخال الشكوى');
            return -1;
       }else {
           
       if(confirm('هل تريد بالتأكيد إضافة الشكوى ')){

            get_data('{$add_complaint_url}',{subscriber_no_m:serial,complaint_m:complaint }, function (data) {
            var len = data.length;
            if (len > 0) {
                success_msg('رسالة','تم الاضافة للكشف بنجاح ..');
                $('#EditModal').modal('hide');
                search();
            } else {
                danger_msg('تحذير..', data);
            }
          }, 'html');   
                  
       }
      }
   }

   function show_detail_row(obj) {

        $('#txt_subscriber_no_m').val('');
        $('#txt_complaint_m').val('');
        
        $('#EditModal').modal('show');
        $("#EditModal").appendTo("body");
        
        var tr = obj.closest('tr');
        var subscriber_no  = $('input[name="subscriber_no"]',tr).val();
        var complaint  = $('input[name="complaint"]',tr).val();
        
        $('input[name="subscriber_no_m"]').val(subscriber_no);
        $('input[name="complaint_m"]').val(complaint);

   }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
