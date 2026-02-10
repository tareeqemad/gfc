<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 15/07/19
 * Time: 01:06 م
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'carrequest';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_list");
$post_url= base_url("$MODULE_NAME/$TB_NAME/insert");
$page=1;
$select_url= base_url('payment/cars/public_select_car');
$driver_company_url = base_url("$MODULE_NAME/$TB_NAME/public_select_driver_company");
$driver_url = base_url("$MODULE_NAME/$TB_NAME/public_select_driver");

$manager_url= base_url("$MODULE_NAME/$TB_NAME/index/1/manager");
$gfc_domain= gh_gfc_domain();

$get_index_url = base_url("$MODULE_NAME/$TB_NAME/index");
$car ="";

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];


echo AntiForgeryToken();
?>

<div class="row" xmlns="http://www.w3.org/1999/html">

    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" name="ser" id="txt_ser" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الطلبات من</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=date('d/m/Y',strtotime('-0 day'))?>" name="ass_start_date" id="txt_ass_start_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text" <?=$date_attr?> name="ass_end_date" id="txt_ass_end_date" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="$('#carRequest_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>

            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>
        <div id="container">

        </div>

    </div>
    <!------------------طلب سيارة----------------->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="title" class="modal-title">اسناد لسائق</h3>
                </div>
                <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form" >

                    <div class="modal-body">

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">نوع السيارة</label>
                            <div class="col-sm-9">

                                <select data-val="true" name="car_type" id="dp_car_type" class="form-control" required>
                                    <option></option>
                                    <?php foreach ($car_type as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>


                        <div class="row owner">
                            <input type="hidden" name="order_no" id="h_txt_order_no">
                            <input type="hidden" name="emp_name" id="h_txt_emp_name">
                            <input type="hidden" name="time" id="h_txt_time">
                            <input type="hidden" name="date_move" id="h_txt_date">
                            <input type="hidden" name="h_emp_no" id="h_emp_no">
                            <input type="hidden" name="emp_email" id="h_emp_email">
                            <br>
                            <label class="col-sm-1 control-label"> صاحب العهدة</label>
                            <div class="col-sm-9">

                                <select data-val="true" name="car_id" id="dp_car_id_name" class="form-control sel2" required>
                                    <option></option>
                                    <?php foreach ($car_owner as $rows) : ?>
                                        <option value="<?= $rows['CAR_FILE_ID'] ?>" ><?= $rows['CAR_OWNER'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>

                        </div>

                        <div class="row driver_company">
                            <br>
                            <label class="col-sm-1 control-label"> السائق</label>
                            <div class="col-sm-9">

                                <select data-val="true" name="driver_id" id="dp_driver_name" class="form-control sel2" required></select>

                            </div>

                        </div>

                        <div class="row contractor">
                            <br>
                            <label class="col-sm-1 control-label">اسم المكتب </label>
                            <div class="col-sm-9">

                                <select data-val="true" name="office_id" id="dp_office_id" class="form-control" required>
                                    <option></option>
                                    <?php foreach ($office_name as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="row" style="display: none;">
                            <br>
                            <label class="col-sm-1 control-label ">تكلفة المهمة</label>
                            <div class="col-sm-9">
                                <input type="text" data-val-required="حقل مطلوب" class="form-control" name="task_cost" rows="1" id="txt_task_cost">
                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">نوع الحركة</label>
                            <div class="col-sm-9">

                                <select data-val="true" name="movment_type" id="dp_movement_type" class="form-control">
                                    <option></option>
                                    <?php foreach ($movement_type as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">ملاحظات</label>
                            <div class="col-sm-9">
                                <textarea data-val-required="حقل مطلوب" class="form-control" name="notes" rows="7" id="txt_notes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <?php if ( HaveAccess($post_url)) :  ?>
                                <button type="submit" data-action="submit" id="btn_save" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>

                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-------------------------------------------->

</div>

</div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2').select2();

        
    if ('{$emp_branch_selected}' != 1){
       $('#dp_branch_id').select2('val','{$emp_branch_selected}');
       $('#dp_branch_id').select2('readonly','{$emp_branch_selected}');
    }else { 
        $('#dp_branch_id').select2('val','{$emp_branch_selected}');
    }
    
    
    $('.contractor,.owner,.driver_company,.driver_rented').hide();

    $('#select_car_id').click(function (e) { 
          _showReport('{$select_url}/'+$("#txt_car_id_name").attr('id')+'/');
    });
       
    $('#dp_office_id').change(function () {
        office_name = $("#dp_office_id option:selected").text();
    });
    
    $('#dp_driver_name').change(function () {
        driver_name = $("#dp_driver_name option:selected").text();
    });

    
    var car_type_no ,car_type_name,office_name, driver_name;
    $('#dp_car_type').change(function(){
        
        car_type_no = $(this).val();       
        car_type_name = $("#dp_car_type option:selected").text();
             
        if( car_type_no == 1 ){

            $('.contractor,.driver_rented').hide(); 
            $('.owner,.driver_company').show(); 
            $('#txt_driver_name,#h_txt_driver_name,#txt_car_id_name,#h_txt_car_id_name,#dp_office_id').val(''); 
            $('#dp_car_id_name,#dp_driver_name,select[name="driver_id').select2('val','');
           
            var drive_name_company_json= {$car_drive_name_company};
            var drive_name_company= '<option></option>';
            
            $.each(drive_name_company_json, function(i,item){
                drive_name_company += "<option value='"+item.NO+"' data-mobile-company='"+item.TEL+"' >"+item.NAME+"</option>";
            });
            
            $('select[name="driver_id"]').html(drive_name_company);       
           
        }
        else if(car_type_no == 2){

            $('.contractor,.owner').hide();   
            $('.driver_rented,.driver_company').show(); 
            $('#txt_car_id_name,#h_txt_car_id_name,#txt_driver_name,#h_txt_driver_name,#dp_office_id,input[name="class_name[]"]').val(''); 
            $('#dp_car_id_name,#dp_driver_name').select2('val','');
            
            var drive_name_rented_json= {$car_drive_name_rented};
            var drive_name_rented= '<option></option>';
            
            $.each(drive_name_rented_json, function(i,item){
                drive_name_rented += "<option value='"+item.CONTRACTOR_FILE_ID+"' data-mobile-rented='"+item.MOBILE_NO+"' >"+item.DRIVER_NAME+"</option>";
            });
            
            $('select[name="driver_id"]').html(drive_name_rented);
                        
        } 
        else if(car_type_no == 3){
            $('.owner,.driver,.driver_company,.driver_rented').hide();
            $('.contractor').show(); 
            $('#txt_car_id_name,#h_txt_car_id_name,#txt_driver_name,#h_txt_driver_name').val(''); 
            $('#dp_car_id_name,#dp_driver_name').select2('val','');
        }
        else {
            $('.contractor,.owner,.driver').hide();   
        }        
    });
    
    $(".btn_reference").click(function(){
    
        var tr= $(this).closest("tr");
        
        var order_no = tr.attr("data-order-no");
        var emp_name = tr.attr("data-emp-name");
        var time = tr.attr("data-time");
        var date = tr.attr("data-date");
        var emp_no = tr.attr("data-emp-no");
        var emp_email = tr.attr("data-emp-email");
       
        $('#h_txt_order_no').val(order_no);
        $('#h_txt_emp_name').val(emp_name);  
        $('#h_txt_time').val(time);  
        $('#h_txt_date').val(date); 
        $('#h_emp_no').val(emp_no);
        $('#h_emp_email').val(emp_email);
        $('#myModal').modal();
        $(this).hide();
    });
       
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values= {page:1,  ser:$('#txt_ser').val(), emp_no:$('#dp_emp_no').val(),ass_start_date:$('#txt_ass_start_date').val(), ass_end_date:$('#txt_ass_end_date').val(), branch_id:$('#dp_branch_id').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');

    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#carRequest_tb > tbody',values);
    }
    
    function clear() {
        $('#dp_car_type,#h_txt_order_no,#h_txt_emp_name,#h_txt_car_id_name,#txt_car_id_name,#h_txt_driver_name,#txt_driver_name,#dp_office_id,#txt_task_cost,#dp_movement_type,#txt_notes').val(''); 
    }
    
    
    var btn__= '';
    $('#btn_save').click( function(){
      btn__ = $(this);    
    });
    
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    $('#myModal').modal('hide');

                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    //var driver_name = $('#txt_driver_name').val();
                    var time = $('#h_txt_time').val();
                    var date = $('#h_txt_date').val();
                    var emp_email = $('#h_emp_email').val();
                    
                    var mobile_no_company = '0'+$('#dp_driver_name').find(':selected').attr("data-mobile-company");
                    var mobile_no_rented = $('#dp_driver_name').find(':selected').attr("data-mobile-rented");
                    
                    var sub= 'حسب تكليف العمل ';
                    var text=  'تم حجز '+ car_type_name+'<br>' ;
                        
                    if (car_type_no == 1){
                        text+= ' اسم السائق ' + driver_name+'<br>';                      
                        text+= ' رقم الجوال ' + mobile_no_company+'<br>';                      
                    }else if (car_type_no == 2){
                        text+= ' اسم السائق ' + driver_name+'<br>';
                        text+= ' رقم الجوال ' + mobile_no_rented+'<br>'; 
                         
                    }else {
                        text+= office_name +'<br>';
                    }
                    
                    text+= 'بتاريخ' +date +'<br>';
                    text+= 'الساعة' +time +'<br>';
        
                     _send_mail(btn__, emp_email ,sub,text);
                      clear();
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
sec_scripts($scripts);

?>
