<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 21/02/2019
 * Time: 08:36 ص
 */
$MODULE_NAME = "hr";
$TB_NAME = "dependent";
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/serv_get");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$create_url_get = base_url("$MODULE_NAME/$TB_NAME/index_con");
$attach_url = base_url("attachments/attachment/public_upload");
//OLD - $gov_mtit_rel_ctzn_url = "http://192.168.0.171:801/apis/GetFullData/"; // بيانات العائلة
$gov_mtit_rel_ctzn_url = "https://im-server.gedco.ps:8001/apis/GetFullData/"; // بيانات العائلة
$gov_mtit_rel_ctzn_new_url = base_url("$MODULE_NAME/$TB_NAME/public_get_mtit"); // بيانات العائلة من السجلات المسحوبة من الحكومة
$gov_mtit_rel_mar_url = "https://im-server.gedco.ps:8001/apis/GetRELMAR/"; // تاريخ الزواج

$data_live=0;
if($act=='live'){
    $data_live=1;
    $gov_mtit_rel_ctzn_new_url =$gov_mtit_rel_ctzn_url;
}

?>
<style>
    #employee_tb td.father {
        background-color: #F6D2D2;
    }

    #employee_tb td.live {
        visibility: hidden;
        position: relative;
    }

    #employee_tb td.live:after {
        visibility: visible;
        position: center;
        content: "حي";
        align-content: center;
    }

    #employee_tb td.father:hover {
        background-color: #dbb8b8;
    }

    .father-text {
        text-align: center;
    }
</style>
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">المعالين </span>
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
                    <?php if($data_live){
                        echo 'بيانات العائلة آخر تحديث من الداخلية';
                    }else{
                        echo 'بيانات العائلة المسحوبة من الداخلية';
                    }
                    ?>
                </div>
                <div class="flex-shrink-0">

                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>الموظف</label>
                            <select class="form-control sel2" name="emp_no" id="dp_emp_id" onchange="employeeChange()">
                                <option value="">--اختر-----</option>
                                <?php foreach ($employee as $row) : ?>
                                    <option data-no="<?= $row['NO'] ?>"
                                            value="<?= $row['ID'] ?>"><?= $row['NO'] . " : " . $row['NAME'] ?></option>
                                <?php endforeach; ?>
                                <input type="hidden" id="h_emp_id" value="" class="form-control">
                                <input type="hidden" id="h_emp_no" value="" class="form-control">
                            </select>
                        </div>
                    </div>
                    <!--end row -->
                    <hr>
                    <div class="flex-shrink-0">
                        <button type="button" id="btn_search" onclick="javascript:search();" class="btn btn-primary"><i
                                    class="fa fa-search"></i> إستعلام
                        </button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                    class="fas fa-eraser"></i>تفريغ الحقول
                        </button>
                    </div>
                    <hr>
                    <div id="container">
                        <div>
                            <?php if(!$data_live){ ?>
                            <label>
                                <span class="text-danger">تاريخ تحديث البيانات : </span>
                                <span id="s_entry_date" class="text-danger">-</span>
                            </label>
                            <?php } ?>
                        </div>
                        <div class="table-responsive tableRoundedCorner">
                            <table class="table table-bordered roundedTable" id="employee_tb">
                                <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" class="group-checkable" data-set="#employee_tb .checkboxes"/>
                                    </th>
                                    <th>رقم الهوية</th>
                                    <th>الاسم</th>
                                    <th>اسم الاب</th>
                                    <th>اسم الجد</th>
                                    <th>اسم العائلة</th>
                                    <th>الجنس</th>
                                    <th>صلة القرابة</th>
                                    <th>الحالة الاجتماعية</th>
                                    <th> تاريخ الميلاد</th>
                                    <th> العمر</th>
                                    <th> تاريخ الزواج</th>
                                    <th>حي / ميت</th>
                                    <th>تاريخ الوفاة</th>
                                    <th>تحميل(الاستثناءات)</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="table-responsive tableRoundedCorner" id="family_father_data" style="display: none">
                            <table class="table table-bordered roundedTable" id="family_father_tb" >
                                <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" class="group-checkable"
                                               data-set="#family_father_tb .checkboxes"/></th>
                                    <th>رقم الهوية</th>
                                    <th>الاسم</th>
                                    <th>اسم الاب</th>
                                    <th>اسم الجد</th>
                                    <th>اسم العائلة</th>
                                    <th>الجنس</th>
                                    <th>صلة القرابة</th>
                                    <th>الحالة الاجتماعية</th>
                                    <th> تاريخ الميلاد</th>
                                    <th> العمر</th>
                                    <th>حي / ميت</th>
                                    <th>تاريخ الوفاة</th>
                                <tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="save_place" style="display: none" class="modal-footer">
                            <button type="button" id="save_dependent_btn" onclick='javascript:create_();' class="btn btn-primary" disabled>حفظ البيانات</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!----------JS------------>
<?php
$scripts = <<<SCRIPT
<script>
    var table = '{$TB_NAME}';
    var res_data;
    var res_data_2;
    var res_data_3;    
    $('.sel2:not("[id^=\'s2\']")').select2();
    $('#save_place').hide();
    $('#save_dependent_btn').attr('disabled','disabled');
    $('.father-text').hide();
    $('#family_father_tb').hide();   
     $('#btn_search').click(function() {
            if( !$('#dp_emp_id').val() ) {
                alert('يرجى ادخال اسم الموظف');
                return false;
            }
            else if($('#save_place').is(':hidden') == true){
                if(!$data_live)
                    $('#save_place').show();
            }
     });
    /*x****************** البحث عن بيانات موظف والمعالين ******************x*/
    function search(){
        var h_emp_id= $('#dp_emp_id').val();
        var h_emp_no= $('#dp_emp_id').find('option:selected').data('no');
        $('#h_emp_id').val(h_emp_id);
        $('#h_emp_no').val(h_emp_no);
        // var vals2= {"WB_USER_NAME_IN":"GEDCO4GOVDATA","WB_USER_PASS_IN":"DBDFB888518471E658FED26FED","DATA_IN":{"package":"MOI_GENERAL_PKG","procedure":"REL_CTZN_INFO_DET","user_id":h_emp_id} };
        $('#btn_search').prop('disabled',1);
        setTimeout(function(){
            $('#btn_search').prop('disabled',0);
        },4500);
        if(h_emp_id=='') return 0; 
        // marriage  - بيانات الزواج
        get_data('{$gov_mtit_rel_mar_url}'+h_emp_id, {} ,function(data3){
            res_data_3 =data3.DATA;
        });
          setTimeout(function(){

            // Relations - العلاقات
            get_data('{$gov_mtit_rel_ctzn_new_url}/'+h_emp_id, {} ,function(data){
            res_data = data.DATA;
            if(res_data != ''){
             $('#s_entry_date').text(res_data[0].ENTRY_DATE);
            }else{
                warning_msg('يرجى مراجعة الشؤون الادارية لتحديث البيانات');
                return -1;
            }
           

            var items = [];
            //var row = $(this);
            $.each(res_data,function(key,val) {
                // wife - اذا كانت زوجة عرض تاريخ الزواج
                if(val.RELATIVE_CD ==4 && val.SOCIAL_STATUS_CD == 20 && res_data_3.length > 0){

                    $.each(res_data_3,function(k,v) {
                        if(v.CH_ALTER_TYPE_CD==20){
                            if(v.CH_SPOUSE_ID==val.IDNO_RELATIVE){
                                res_data[key].DATE_STATUS=  reformat_date(v.CH_ALTER_DT); // MARRIED_DATE
                            }
                        }else{
                            res_data[key].DATE_STATUS= '';
                        }
                    });

                }else{
                    res_data[key].DATE_STATUS= "";
                }

                if(val.RELATIVE_CD ==1) // اب
                    items.push("<tr title='انقر مزدوج' data-f_id='"+val.IDNO_RELATIVE+"'>");
                else
                    items.push("<tr>");
                if(val.DETH_DT != null || (val.RELATIVE_CD ==3 && val.SEX_CD == 2 && val.SOCIAL_STATUS_CD == 20) ) // Done
                    items.push("<td></td>");
                else
                    items.push("<td> <input type='checkbox' class='checkboxes' value='"+key+"'/></td>");
                items.push("<td>"+val.IDNO_RELATIVE+"</td>");
                items.push("<td>"+val.FNAME_ARB+"</td>");
                items.push("<td>"+val.SNAME_ARB +"</td>");
                items.push("<td>"+val.TNAME_ARB +"</td>");
                items.push("<td>"+val.LNAME_ARB +"</td>");
                items.push("<td>"+val.SEX+"</td>");
                if(val.RELATIVE_CD ==1) // اب
                    items.push("<td class='father' >"+val.RELATIVE_DESC+"</td>");
                else
                    items.push("<td >"+val.RELATIVE_DESC+"</td>");
                items.push("<td>"+val.SOCIAL_STATUS+"</td>");
                items.push("<td>"+val.BIRTH_DT+"</td>");
                items.push("<td>"+age_calc(val.BIRTH_DT)+"</td>");
                items.push("<td>"+res_data[key].DATE_STATUS+"</td>");
                if(val.DETH_DT == null){
                    items.push("<td>"+'حي'+"</td>");
                    items.push("<td></td>");
                }else{
                    items.push("<td>"+'متوفى'+"</td>");
                    items.push("<td>"+val.DETH_DT+"</td>");
                }
                if( val.RELATIVE_CD == 3 && val.SEX_CD == 2 && val.DETH_DT == null && val.SOCIAL_STATUS_CD != 20 ) // Done
                    items.push( "<td> <a href='javascript:;' onclick=\"javascript:_showReport('{$attach_url}/"+h_emp_id+"/dependent_"+val.IDNO_RELATIVE+"');\" > <i class='fa fa-file'></i> </a> </td>" );
                else if( val.RELATIVE_CD == 3 && val.SEX_CD == 1 && val.DETH_DT == null && age_calc(val.BIRTH_DT) >= 18 && val.SOCIAL_STATUS_CD == 10)  // Done
                    items.push( "<td> <a href='javascript:;' onclick=\"javascript:_showReport('{$attach_url}/"+h_emp_id+"/dependent_"+val.IDNO_RELATIVE+"');\" > <i class='fa fa-file'></i> </a> </td>" );
                else
                    items.push("<td> </td>")
                items.push("</tr>");
            });

            $('#employee_tb tbody').remove();
            $('#family_father_tb tbody').remove();
            $("<tbody/>",{html: items.join("")}).appendTo("table#employee_tb");

            $("#employee_tb tr").dblclick(function () {
                var father_id =$(this).attr('data-f_id');
                if (father_id == null)
                    return false
                else
                    search_parent(father_id);

                if($('#family_father_tb').is(':hidden') == true){
                    $('#family_father_tb').show();
                    $('.father-text').show();
                }
            });

            /********** checkbox th start ***************/
            $('#employee_tb .group-checkable' ).on("click", function() {
                if($('#employee_tb .group-checkable:checked').length >0){
                    $('#save_dependent_btn').prop('disabled',false);
                }else{
                    $('#save_dependent_btn').prop('disabled',true);
                }
            });
            /******************checkbox th end*******************/

            /*****************checkbox td start******************/
            $('#employee_tb .checkboxes' ).on( "click", function(){
                if($('#employee_tb .checkboxes:checked').length >0){
                    $('#save_dependent_btn').prop('disabled',false);
                }else{
                    $('#save_dependent_btn').prop('disabled',true);
                }
            });
            /*****************checkbox td end****************/
            }); //get_data end

            //console.log(res_data);
        },4000);
    } //search function end 
    
    
    /*x******** البحث عن بيانات الاخوة والاخوات **********x*/
    function search_parent(father_id){
        get_data('{$gov_mtit_rel_ctzn_url}'+father_id,{},function(data){
            res_data_2 =data.DATA;
            var items = [];
            $('#family_father_data').show();
            $.each(res_data_2,function(key,val) {
                res_data_2[key].DATE_STATUS= '';
                items.push("<tr>");
                if( val.DETH_DT != null || val.IDNO_RELATIVE== $('#h_emp_id').val() ||  val.RELATIVE_CD == 4 || ( val.RELATIVE_CD == 3 && val.SOCIAL_STATUS_CD == 20 ))
                    items.push("<td></td>");
                else
                    items.push("<td> <input type='checkbox'  class='checkboxes'  value='"+key+"'/></td>");

                items.push("<td>"+val.IDNO_RELATIVE+"</td>");
                items.push("<td>"+val.FNAME_ARB+"</td>");
                items.push("<td>"+val.SNAME_ARB +"</td>");
                items.push("<td>"+val.TNAME_ARB +"</td>");
                items.push("<td>"+val.LNAME_ARB +"</td>");
                items.push("<td>"+val.SEX+"</td>");
                items.push("<td>"+val.RELATIVE_DESC+"</td>");
                items.push("<td>"+val.SOCIAL_STATUS+"</td>");
                items.push("<td>"+val.BIRTH_DT+"</td>");
                items.push("<td>"+age_calc(val.BIRTH_DT)+"</td>");
                if(val.DETH_DT == null){
                    items.push("<td>"+'حي'+"</td>");
                    items.push("<td></td>");
                }else{
                    items.push("<td>"+'متوفى'+"</td>");
                    items.push("<td>"+val.DETH_DT+"</td>");
                }
                items.push("</tr>");
            });

            $('#family_father_tb tbody').remove();
            $("<tbody/>",{html: items.join("")}).appendTo("table#family_father_tb");

            // convert Son to Brother - تعديل نوع العلاقة من ابن الى اخ
            $.each(res_data_2 , function(i, v) {
             if( v.RELATIVE_CD == '3' )
                   v.RELATIVE_CD = '7';
             });

            /*************checkbox th start**************/
            $( '#family_father_tb .group-checkable' ).on( "click", function() {
                if($('#family_father_tb .group-checkable:checked').length >0){
                    $('#save_dependent_btn').prop('disabled',false);
                }
                else{
                    $('#save_dependent_btn').prop('disabled',true);
                }
            });
            /***********checkbox th end********************/
       
            /*****************td*********************/
            $( '#family_father_tb .checkboxes' ).on( "click", function() {
                if($('#family_father_tb .checkboxes:checked').length >0){
                    $('#save_dependent_btn').prop('disabled',false);
                }
                else{
                    $('#save_dependent_btn').prop('disabled',true);
                }
            });
            /****************td*********************/
        });
    }
    
     /************************************************************/
    function create_(){
        var data_in=[];
        $('#employee_tb .checkboxes:checked').each(function(i){
            data_in.push(res_data[$(this).val()]);
        });
        $('#family_father_tb .checkboxes:checked').each(function(i){
            data_in.push(res_data_2[$(this).val()]);
        });
        var param_json= {"emp_id":$('#h_emp_id').val(), "emp_no":$('#h_emp_no').val(), "res_data":data_in};
        get_data("{$create_url}", param_json ,function(res){
            if( parseInt(res) > 0 ){
                success_msg('رسالة','تم  الاضافة بنجاح #'+res);
                $('button').attr('disabled','disabled');
                setTimeout(function(){
                    get_to_link(window.location.href);
                },3000);
            }else{
                danger_msg('تحذير..',res);
            }
        });
    }
    
    function  employeeChange(){
         $('#employee_tb tbody,#family_father_tb tbody').hide();
         $('#family_father_data').hide();
         $('#save_place').hide();
         $('#s_entry_date').text('');
    }
    
     // reformat date  IN= 08-MAY-12   OUT= 08/05/2012
    function reformat_date(date_in){
         return formatDate(new Date(  getDateFromFormat(date_in,'dd-MMM-yy')  ),'dd/MM/yyyy') ;
    }
    
    
     function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val',0);
     }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>
