<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 29/09/16
 * Time: 11:42 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_order_archives';

if($admin_manager)
    $get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_admin_manager");
else
    $get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page");

$get_emp_order_url = base_url("$MODULE_NAME/evaluation_emp_order/get");

$show_before_end_url= base_url("hr/evaluation_order_archives/show_before_end");
if(HaveAccess($show_before_end_url)){
    $act='show_before_end';
}else{
    $act='archive';
}

/*mtaqia*/
$report_url = base_url("JsperReport/showreport?sys=hr/Employees_Evaluation");
$report_sn= report_sn();
/* ----- */

echo AntiForgeryToken();

?>
<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><i class="glyphicon glyphicon-check"></i><?= $title ?></div>
        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>

    <div class="form-body">
        <?php if(!$admin_manager){ ?>
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">أمر التقييم</label>
                    <div>
                        <select name="evaluation_order_id" id="dp_evaluation_order_id" class="form-control" >
                            <option value=""></option>
                            <?php foreach($order_id as $row) :?>
                                <option value="<?= $row['EVALUATION_ORDER_ID'] ?>"><?= $row['EVALUATION_ORDER_ID'].' - '.substr($row['ORDER_START'],-4).' - '?><?=($row['ORDER_TYPE']==1) ? 'سنوي' : 'نصفي' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control" >
                            <option value=""></option>
                            <?php foreach($employee as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المدير المباشر</label>
                    <div>
                        <select name="emp_manager_id" id="dp_emp_manager_id" class="form-control" >
                            <option value=""></option>
                            <?php foreach($employee as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مدير الادارة </label>
                    <div>
                        <select name="management_manager_no" id="dp_management_manager_no" class="form-control" >
                            <option value=""></option>
                            <option value="0">بدون مدير ادارة</option>
                            <?php foreach($employee as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">التقدير</label>
                    <div>
                        <select name="degree_no" id="dp_degree_no" class="form-control" >
                            <option value=""></option>
                            <?php foreach($degree as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NO']." : ".$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div> <!-- /.modal-body inline_form -->
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success">استعلام</button>
            <button type="button" onclick="javascript:print_all_report();" class="btn btn-primary">طباعة</button>
            <button type="button" onclick="javascript:$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-warning">اكسل</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>
        <?php } ?>

        <div id="msg_container"></div>

        <div id="container">
                <?//=modules::run($get_page_url, $page, $evaluation_order_id, $emp_no, $emp_manager_id, $management_manager_no, $degree_no);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $(document).ready(function() {
		$('#dp_evaluation_order_id, #dp_emp_no, #dp_emp_manager_id, #dp_management_manager_no, #dp_degree_no').select2();

		if({$admin_manager}){
            $('#page_tb').dataTable({
                "lengthMenu": [ [50,100,200,300, -1], [50,100,200,300, "الكل"] ],
                "sPaginationType": "full_numbers"
            });
        }
    });

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        if({$admin_manager})
            get_to_link('{$get_emp_order_url}/'+id+'/admin_manager');
        else
            get_to_link('{$get_emp_order_url}/'+id+'/{$act}');
    }

    function clear_form(){
        $('#dp_evaluation_order_id, #dp_emp_no, #dp_emp_manager_id, #dp_management_manager_no, #dp_degree_no').select2('val','');
        clearForm($('#{$TB_NAME}_form'));
    }

    function search(){
        var values= {page:1, evaluation_order_id:$('#dp_evaluation_order_id').val(), emp_no:$('#dp_emp_no').val(), emp_manager_id:$('#dp_emp_manager_id').val(), management_manager_no:$('#dp_management_manager_no').val(), degree_no:$('#dp_degree_no').val()};
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {evaluation_order_id:$('#dp_evaluation_order_id').val(), emp_no:$('#dp_emp_no').val(), emp_manager_id:$('#dp_emp_manager_id').val(), management_manager_no:$('#dp_management_manager_no').val(), degree_no:$('#dp_degree_no').val()};
        ajax_pager_data('#page_tb > tbody',values);
    }

    /*mtaqia*/
    function print_report(type,evaluation_order_serial,grandson_order_serial,evaluation_order_id,emp_no){
        if(type == 'obj')
            _showReport('{$report_url}&type=pdf&report=Hr_Emp_Objection&p_serial='+evaluation_order_serial+'&p_serial_2='+grandson_order_serial+'&p_order_id='+evaluation_order_id+'&p_emp_no='+emp_no+'&sn={$report_sn}',true);
        else if (type == 'res')
            _showReport('{$report_url}&type=pdf&report=Hr_Emp_Result&p_serial='+evaluation_order_serial+'&p_serial_2='+grandson_order_serial+'&p_order_id='+evaluation_order_id+'&p_emp_no='+emp_no+'&sn={$report_sn}',true);

    }
    /*----*/
    
    function print_all_report() {
        if($('#dp_evaluation_order_id').val() == '' || $('#dp_evaluation_order_id').val() == null) {
            danger_msg('تحذير..','يجب اختيار أمر التقييم');
        }else {
        _showReport('{$report_url}&type=pdf&report=Hr_Emp_Result_All&p_order_id='+$('#dp_evaluation_order_id').val()+'&p_emp_no='+$('#dp_emp_no').val()+'&p_manager_id='+$('#dp_emp_manager_id').val()+'&p_degree='+$('#dp_degree_no').val()+'&p_management_manager='+$('#dp_management_manager_no').val()+'&sn={$report_sn}',true);
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
