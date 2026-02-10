<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 04/03/21
 * Time: 12:00 ص
 */

/*JASPERREPORT*/
$jasper_url = base_url("JsperReport/showreport?sys=financial");
$report_sn= report_sn();
/* ----- */
$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_room_move';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");


echo AntiForgeryToken();


?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div >
            <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                <div  class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label"> م </label>
                        <div>
                            <input type="text" name="ser" id="txt_ser" class="form-control">
                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label"> حساب المستفيد - الهوية </label>
                        <div>
                            <select name="employee_id" id="txt_employee_id" class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($customer_ids as $row) :?>
                                    <option value="<?=$row['ID']?>"><?=$row['NO'].'-'.$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                            <!--  <input type="text" name="customer_id" id="txt_customer_id" class="form-control">-->
                        </div>
                    </div>


                    <div class="form-group col-sm-2" >
                        <label class="control-label">من غرفة </label>
                        <div>
                            <select name="from_room_id" id="dp_from_room_id" class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($rooms_cons as $row) :?>
                                    <option value="<?=$row['ROOM_ID']?>" ><?=$row['ROOM_ID'].': '.$row['ROOM_PARENT_NAME'].' - '.$row['ROOM_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-2" >
                        <label class="control-label">إلى غرفة </label>
                        <div>
                            <select name="to_room_id" id="dp_to_room_id" class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($rooms_cons as $row) :?>
                                    <option value="<?=$row['ROOM_ID']?>" ><?=$row['ROOM_ID'].': '.$row['ROOM_PARENT_NAME'].' - '.$row['ROOM_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <label class="control-label"> البيان </label>
                        <div>
                            <input type="text" name="notes" id="txt_notes" class="form-control">
                        </div>
                    </div>


                    <div class="form-group col-sm-1" >
                        <label class=" control-label"> من تاريخ  </label>
                        <div >
                            <input type="text" class="form-control" data-type="date" data-date-format="DD/MM/YYYY" id="txt_fdate" name="fdate" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"/>
                        </div>
                    </div>

                    <div class="form-group  col-sm-1" >
                        <label class="control-label"> إلى تاريخ </label>
                        <div >
                            <input type="text" class="form-control" data-type="date" data-date-format="DD/MM/YYYY" id="txt_tdate" name="tdate" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"/>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> المقر </label>
                        <div>
                            <select name="branch" id="dp_branch" class="form-control" >
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">الحالة</label>
                        <div>
                            <select name="adopt" id="dp_adopt" class="form-control" >
                                <option></option>
                                <?php foreach($adopts as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>



                </div>
            </form>

            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>

            </div>
        </div>
        <div id="msg_container"></div>

        <div id="container">
            <? //=modules::run($get_page_url, $page, $emp_pledges, $file_id, $customer_id, $source, $status, $class_id, $class_unit, $exp_account_cust, $class_output_id, $notes , $class_code_ser,$class_type,$manage_st_id,$cycle_st_id,$department_st_id,$serial,$is_adopt,$branch,$tdate,$fdate,$store_id,$adopt,$personal_custody_type,$custody_type,$room_id);?>
        </div>

    </div>

</div>



<?php
$scripts = <<<SCRIPT
<script>
 //  auto_restart_search();
 
    
    
     $('#txt_employee_id').select2();
    $('#dp_from_room_id').select2();
    $('#dp_to_room_id').select2();

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }


    function search(){
        var values= {page:1,ser:$('#txt_ser').val(), employee_id:$('#txt_employee_id').val(), from_room_id:$('#dp_from_room_id').val(), to_room_id:$('#dp_to_room_id').val(), notes:$('#txt_notes').val(), fdate:$('#txt_fdate').val(), tdate:$('#txt_tdate').val(), branch:$('#dp_branch').val(), adopt:$('#dp_adopt').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {ser:$('#txt_ser').val(), employee_id:$('#txt_employee_id').val(), from_room_id:$('#dp_from_room_id').val(), to_room_id:$('#dp_to_room_id').val(), notes:$('#txt_notes').val(), fdate:$('#txt_fdate').val(), tdate:$('#txt_tdate').val(), branch:$('#dp_branch').val(), adopt:$('#dp_adopt').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

   
 $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
           if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    search();
            }else{
                    danger_msg('تحذير..','تأكد من رقم الباركود');
                }
            container.html(data);
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });
</script>
SCRIPT;
sec_scripts($scripts);
?>
