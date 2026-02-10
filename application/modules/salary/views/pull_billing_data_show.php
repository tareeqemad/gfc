<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 03/04/23
 * Time: 10:20 ص
 */

$MODULE_NAME = 'salary';
$TB_NAME = 'Pull_billing_data';

$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$back_url = base_url("$MODULE_NAME/$TB_NAME/index");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$isCreate = isset($master_tb_data) && count($master_tb_data) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $master_tb_data[0];

$date_attr = " data-type='date' data-date-format='YYYYMM' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$count=1;
$d_count=0;

if ($HaveRs) {
    $edit = 1;
} else {
    $edit = 0;
}

?>
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">بدل الاتصال</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex py-3">
                    <div class="mb-0 flex-grow-1 card-title">
                        الطلب
                    </div>
                    <div class="flex-shrink-0">
                        <a class="btn btn-info" href="<?= $back_url ?>"><i class="fa fa-reply"></i> </a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="example">
                        <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" role="form"
                              action="<?= $post_url ?>" novalidate="novalidate">

                            <div class="row">
                                <h4 class="texts-primary">
                                    <u>استيراد اكسل</u>
                                </h4>

                                <div class="form-group col-md-2">
                                    <input type="file" class="form-control" id="file_upload"/>
                                </div>

                                <label>الشهر</label>
                                <div class="form-group col-sm-1">
                                    <div>
                                        <?php if ($HaveRs){ ?>
                                            <input type="text" value="<?=$HaveRs?$rs['THE_MONTH']:''?>" name="the_month" id="txt_the_month" class="form-control" readonly>
                                        <?php } else {  ?>
                                            <input type="text"  value="<?= $current_date ?>" name="the_month" id="txt_the_month" class="form-control" readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered " id="display_excel_data" data-container="container">
                                    <thead class="table-primary">
                                    <tr style="text-align: center">
                                        <th>م</th>
                                        <th>رقم الموظف</th>
                                        <th style="width: 10%;">رقم الهوية</th>
                                        <th style="width: 15%;">اسم الموظف</th>
                                        <th style="width: 10%;">رقم الفاتورة(الجوال)</th>
                                        <th>الفئة</th>
                                        <th>المبلغ المعتمد للفئة</th>
                                        <th>مبلغ الفاتورة</th>
                                        <th style="width: 10%;">اسم البرنامج</th>
                                        <th style="width: 15%;">التكلفة المعتمدة</th>
                                        <th>الحالة</th>
                                        <th>الاجراء</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <?php if (count($rs) <= 0) {  // ادخال ?>
                                        <tr>
                                            <td>
                                                <input name="ser[]" id="ser<?= $count ?>" class="form-control" value="0" style="text-align: center" readonly/>
                                            </td>

                                            <td>
                                                <input type="text"  name="emp_no[]" id="txt_emp_no<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="emp_id[]" id="txt_emp_id<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="emp_name[]" id="txt_emp_name<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="bill_no[]" id="txt_bill_no<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="category[]" id="txt_category<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="category_amount[]" id="txt_category_amount<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>
                                            <td>
                                                <input type="text"  name="bill_amount[]" id="txt_bill_amount<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="program_name[]" id="txt_program_name<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="approved_cost[]" id="txt_approved_cost<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="status[]" id="txt_status<?= $count ?>" class="form-control" style="text-align: center"/>
                                            </td>

                                            <td class="text-center">
                                                <a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a>
                                            </td>

                                        </tr>

                                        <?php
                                    } else if (count($rs) > 0) { // تعديل
                                        $count = -1;

                                        foreach ($master_tb_data as $row) {
                                            $count++;
                                            $d_count++;
                                            ?>

                                            <tr>
                                                <td>
                                                    <input type="hidden" name="ser[]" id="ser<?= $count ?>" class="form-control" value="<?= $row['SER'] ?>" style="text-align: center" readonly>
                                                    <input name="txt_ser[]" id="txt_ser<?= $d_count ?>" class="form-control" value="<?= $d_count ?>" style="text-align: center" readonly>
                                                </td>

                                                <td>
                                                    <input type="text"  name="emp_no[]" id="txt_emp_no<?= $count ?>" value="<?= $row['EMP_NO'] ?>" class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="emp_id[]" id="txt_emp_id<?= $count ?>" value="<?= $row['EMP_ID'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="emp_name[]" id="txt_emp_name<?= $count ?>" value="<?= $row['EMP_NAME'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="bill_no[]" id="txt_bill_no<?= $count ?>" value="<?= $row['BILL_NO'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="category[]" id="txt_category<?= $count ?>" value="<?= $row['CATEGORY'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="category_amount[]" id="txt_category_amount<?= $count ?>" value="<?= $row['CATEGORY_AMOUNT'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="bill_amount[]" id="txt_bill_amount<?= $count ?>" value="<?= $row['BILL_AMOUNT'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="program_name[]" id="txt_program_name<?= $count ?>" value="<?= $row['PROGRAM_NAME'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="approved_cost[]" id="txt_approved_cost<?= $count ?>" value="<?= $row['APPROVED_COST'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="status[]" id="txt_status<?= $count ?>" value="<?= $row['STATUS'] ?>"  class="form-control" style="text-align: center"/>
                                                </td>

                                                <td class="text-center">
                                                    <?php if ($row['ADOPT']  == 1 ) { ?>
                                                        <a onclick="javascript:delete_row(this,<?= $row['SER'] ?>);" href="javascript:;" ><i class="fa fa-trash"></i></a>
                                                    <?php } ?>
                                                </td>

                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <?php if (count($rs) >= 0) { ?>
                                            <th>
                                                <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                                            </th>
                                        <?php } ?>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="modal-footer">

                                <?php if ( HaveAccess($post_url)  && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
                                    <button type="submit" data-action="submit" class="btn btn-primary" id="btn_export_file_to_db">حفظ البيانات</button>
                                <?php endif; ?>

                                <?php if ( $HaveRs and HaveAccess($adopt_url.'10') ) : ?>
                                    <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد</button>
                                <?php endif; ?>

                            </div>
                        </form>

                        <div id="container">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    $('.sel2').select2();
     var count = 0;

    $('#txt_the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false,
    });

    function reBind(s) {
        if (s == undefined) {
            s = 0;
        }
        if (s) {
            $('.sel22:not("[id^=\'s2\']")').select2();
        }
    }
    
    $('#file_upload').on("change", function(){ uploadFile(); });

    function uploadFile() {
          var files = document.getElementById('file_upload').files;
          if(files.length==0){
            danger_msg("الرجاء اختيار أي ملف ...");
            return;
          }
          var filename = files[0].name;
          var extension = filename.substring(filename.lastIndexOf(".")).toUpperCase();
          if (extension == '.XLS' || extension == '.XLSX') {
              excelFileToJSON(files[0]);
              $('#btn_export_file_to_db').show();
          }else{
              danger_msg("الرجاء تحديد ملف اكسل صالح!");
          }
    }
    
    function excelFileToJSON(file){
        try {
            var reader = new FileReader();
            reader.readAsBinaryString(file);
            reader.onload = function(e) {
                var data = e.target.result;
                var workbook = XLSX.read(data, {
                    type : 'binary'
                });
                var result = {};
                var firstSheetName = workbook.SheetNames[0];
                //reading only first sheet data
                var jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheetName]);
                console.log(jsonData);
            
                var SheetList = JSON.parse(JSON.stringify(jsonData));
                var lengthSheet = SheetList.length;
                if (lengthSheet > 2000){
                      danger_msg('الملف المرفق يحتوي اكتر من 2000 سجل لا يمكن ارفاقه')
                      return -1;
                 }
                displayJsonToHtmlTable(jsonData);
                }
        }catch(e){
            console.error(e);
        }
    }

    function displayJsonToHtmlTable(jsonData){
        var table=document.getElementById("display_excel_data");
        if(jsonData.length>0){
            var htmlData='';
            var count = 0;
            for(var i=0;i<jsonData.length;i++){
                var columns = Object.values(jsonData[i]);
                count = count+1;
                htmlData = htmlData+'<tr><td><input type="text" class="form-control ser" id="ser'+count+'" name="ser[]" value="0" style="text-align: center"  readonly/>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_emp_no" id="txt_emp_no'+count+'" name="emp_no[]" value="'+columns[1]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_emp_id"  id="txt_emp_id'+count+'" name="emp_id[]" value="'+columns[2]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_emp_name"  id="txt_emp_name'+count+'" name="emp_name[]" value="'+columns[3]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_bill_no"  id="txt_bill_no'+count+'" name="bill_no[]" value="'+columns[4]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_category"  id="txt_category'+count+'" name="category[]" value="'+columns[5]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_category_amount"  id="txt_category_amount'+count+'" name="category_amount[]" value="'+columns[6]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_bill_amount"  id="txt_bill_amount'+count+'" name="bill_amount[]" value="'+columns[7]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_program_name"  id="txt_program_name'+count+'" name="program_name[]" value="'+columns[8]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_approved_cost"  id="txt_approved_cost'+count+'" name="approved_cost[]" value="'+columns[9]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td><input type="text" class="form-control txt_status"  id="txt_status'+count+'" name="status[]" value="'+columns[10]+'" style="text-align: center"/></td>' ;
                htmlData = htmlData+'<td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td>' ;
            }
            //table.innerHTML=htmlData;
        $('#display_excel_data tbody').append(htmlData);

        }else{
          table.innerHTML='لا يوجد بيانات في ملف الاكسل';
          $('#btn_export_file_to_db').hide();
        }
    }
    
    //اضافة سجل جديد
    function addRow(){
        var rowCount = $('#display_excel_data tbody tr').length;

        if(rowCount == 0){
            count = count+1;
        }else {
            count = rowCount+1
        }
        var html ='<tr><td><input name="ser[]" id="ser'+count+'" class="form-control" value="0" style="text-align: center" readonly> </td>' +
         ' <td class="text-center"><input  name="emp_no[]"  class="form-control" id="txt_emp_no'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="emp_id[]"  class="form-control" id="txt_emp_id'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="emp_name[]" class="form-control" id="txt_emp_name'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="bill_no[]"  class="form-control" id="txt_bill_no'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="category[]"  class="form-control" id="txt_category'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="category_amount[]"  class="form-control" id="txt_category_amount'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="bill_amount[]"  class="form-control" id="txt_bill_amount'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="program_name[]"  class="form-control" id="txt_program_name'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="approved_cost[]"  class="form-control" id="txt_approved_cost'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><input  name="status[]"  class="form-control" id="txt_status'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td>' +
         '</tr>';
        $('#display_excel_data tbody').append(html);
         reBind(count);

    }
    
    function  remove_tr(obj){
        var tr = obj.closest('tr');
        $(tr).closest('tr').css('background','tomato');
        $(tr).closest('tr').fadeOut(800,function(){
            $(this).remove();
        });
    }
    
    //حذف السجل من Database
    function delete_row(a,ser){
        if(confirm('هل تريد حذف السجل ؟')){
            get_data('{$delete_url}',{ser:ser},function(data){
                if(data == '1'){
                    success_msg('رسالة','تم حذف السجل بنجاح ..');
                    $(a).closest('tr').remove();
                }
            },'html');
        }
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+data);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });
    
    </script>

SCRIPT;

if($HaveRs){
    $scripts = <<<SCRIPT
    {$scripts}
    
    <script type="text/javascript">

    function adopt_(no){
        if(no==10 ) var msg= 'هل تريد تأكيد الطلب ؟!';

        if(confirm(msg)){
            var values= {the_month: "{$rs['THE_MONTH']}"};
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }else{

        }
    }

    </script>
SCRIPT;
}
sec_scripts($scripts);
?>