<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 07/03/2019
 * Time: 11:34 ص
 */
$MODULE_NAME = "hr";
$TB_NAME = "dependent";
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_page_url_r = base_url("$MODULE_NAME/$TB_NAME/get_page_r");
$adopt_all_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$create_url= base_url("$MODULE_NAME/$TB_NAME/index");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$update_dates_url = base_url("$MODULE_NAME/$TB_NAME/update_dates");

$create2_url= base_url("$MODULE_NAME/dependent_students/create");
$get_page2_url= base_url("$MODULE_NAME/dependent_students/get_page");
$get_page3_url= base_url("$MODULE_NAME/dependent_students/public_get");
?>
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">المعالين | الشؤون الادارية</span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">المعالين</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                  استعلام | بيانات المعالين المسجلين على النظام
                </div>
                <div class="flex-shrink-0">

                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>الموظف</label>
                            <select name="emp_no" id="dp_emp_id" class="form-control sel2">
                                <option value=""></option>
                                <?php foreach($employee as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>عرض السجلات</label>
                            <select name="ss" id="dp_ss" class="form-control sel2" >
                                <option value="">الكل</option>
                                <option value="">الفعالة فقط</option>
                            </select>
                        </div>
                    </div>
                    <!--end row -->
                    <hr>
                    <div class="flex-shrink-0">
                        <button type="button" id="btn_search" onclick="javascript:search();" class="btn btn-primary"><i
                                class="fa fa-search"></i> إستعلام
                        </button>
                        <?php  if ( HaveAccess($adopt_all_url.'10') ){ ?>
                            <button type="button" onclick="javascript:adopt_all();" class="btn btn-success"> اعتماد المحدد</button>
                        <?php } ?>

                        <?php  if ( HaveAccess($delete_url) ){ ?>
                            <button type="button" onclick="javascript:person_end();" class="btn btn-danger"> ايقاف المحدد</button>
                        <?php } ?>

                        <button type="button"  id="btn_clear" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                class="fas fa-eraser"></i>تفريغ الحقول
                        </button>
                    </div>
                    <hr>
                    <div id="container_1"></div>

                    <div id="container_2"></div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--dependent_studentsModal Modal -->
<div class="modal fade" id="dependent_studentsModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات الاعتماد</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>


<!--uni_studentsModal Modal -->
<div class="modal fade" id="uni_studentsModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات الاعتماد</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>

<!----------JS------------>
<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2:not("[id^=\'s2\']")').select2();

 
    function search(){
             $('#page_tb ').remove();
             $('#page_tb1 ').remove();
            var values= {emp_no: $('#dp_emp_id').val() };
            get_data('{$get_page_url}',values ,function(data){
                $('#container_1').html(data);
            },'html');
            get_data('{$get_page_url_r}',values ,function(data){
                $('#container_2').html(data);
            },'html');
        }
 
    function clear_form(){
            clearForm($('#{$TB_NAME}_form'));
            $('.sel2').select2('val',0);
      }
 
        
    function update_dates( this_a, ser){
        var from_date= this_a.closest('tr').find('.c_from_date').val() ;
        var to_date=   this_a.closest('tr').find('.c_to_date').val() ;
        
        if(from_date!=''){
            from_date= '01/'+from_date;
        }
        
        if(to_date!=''){
            to_date= '01/'+to_date;
        }
        
        get_data('{$update_dates_url}', {ser:ser, from_date:from_date, to_date:to_date }, function(ret){
            if(ret==1){
                success_msg('رسالة','تمت العملية بنجاح..');
            }else{
                danger_msg('تحذير..',ret);
            }
        }, 'html');
    } // update_dates
      
   
    function adopt_all(){
        var no= 10;
        var ser= 0;
        var cnt= 0;
        cnt= $('#page_tb .checkboxes:checked').length;
        var msg= 'هل تريد اعتماد جميع السجلات المحددة؟؟ #'+cnt;

        if(cnt==0){
            alert('يجب تحديد السجلات المراد اعتمادها اولا..');
            return 0;
        }

        if(confirm(msg)){
            //info_msg('تنويه..','سيتم تحديث الصفحة تلقائيا عند انتهاء العملية');
            $('button').prop('disabled',1);
            $('#page_tb .checkboxes:checked').each(function(i){
                ser= $(this).val();
                get_data('{$adopt_all_url}'+no, {ser:ser} , function(ret){
                    if(ret==1){
                        success_msg('رسالة','تمت العملية بنجاح ');
                    }else{
                        danger_msg('تحذير..',ret);
                    }
                }, 'html');

            }); // each

            setTimeout(function(){
                info_msg('تنويه..','جار تحديث الصفحة..');
                $('button').prop('disabled',0);
                search_();
            },4000);

        } // confirm msg
    }
 
    function person_end(){
        if(confirm('هل تريد ايقاف المحدد ؟!!!')){
            var url = '{$delete_url}';
            var tbl = '#page_tb';
            var container = $('#container');
            var val = [];
            $(tbl + ' .checkboxes:checked').each(function (i) {
                val[i] = $(this).val();
            });
            ajax_delete(url, val ,function(data){
                success_msg('رسالة','تم حذف السجلات بنجاح ..');
                //container.html(data);
                setTimeout(function(){
                    info_msg('تنويه..','جار تحديث الصفحة..');
                    search_();
                },3000);
            });
        }
    }
   

    function add_new_std(ser_m) {
            $('#dependent_studentsModal .modal-body').text('');
            get_data("{$create2_url}/"+ser_m, {}, function(ret){
                $('#dependent_studentsModal .modal-body').html(ret);
                $('#dependent_studentsModal').modal('show');
            }, 'html');
    }

    function add_new_std_2(ser_d) {
            $('#uni_studentsModal').modal('toggle');
            $('#dependent_studentsModal .modal-body').text('');
            get_data("{$get_page3_url}/"+ser_d, {}, function(ret){
                $('#dependent_studentsModal .modal-body').html(ret);
                $('#dependent_studentsModal').modal('show');
            }, 'html');
     }

    function show_univ_std() {
            $('#uni_studentsModal .modal-body').text('');
            var emp_id=$('#dp_emp_id').val();
            if(emp_id==''){
                alert('اختر الموظف');
                return 0;
            }
            get_data("{$get_page2_url}/"+emp_id, {}, function(ret){
                $('#uni_studentsModal .modal-body').html(ret);
                $('#uni_studentsModal').modal();
            }, 'html');
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
