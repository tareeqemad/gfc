<?php

$MODULE_NAME = 'supplier_evaluation';
$TB_NAME = 'supplier_evaluation_marks';
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$customer_url = base_url('payment/customers/public_index');
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$report_jasper_url= base_url("JsperReport/showreport?sys=financial/supplier_evaluation");
$report_sn= report_sn();
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($create_url)): ?>
              <!--  <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>-->
            <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>

    </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical" id="<?= $TB_NAME ?>_form">
            <div class="modal-body inline_form">


                <div class="form-group col-sm-4">
                    <label class="control-label">المورد</label>
                    <div>
                        <select name="customer_id" id="dp_customer_id"
                                class="form-control">
                            <option></option>
                            <?php foreach ($customer_ids as $row) : ?>
                                <option value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">التقرير</label>
                    <div>
                        <select name="report_type" id="dp_report_type"
                                class="form-control">
                            <?php foreach ($report_type as $type) : ?>
                                <option value="<?= $type['CON_NO'] ?>"><?= $type['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1 div_for_month">
                    <label class="control-label">شهر</label>
                    <div>
                        <input type="text" name="for_month" id="txt_for_month" class="form-control"
                               value="<?= date('Ym') ?>"/>
                    </div>
                </div>

                <div class="form-group col-sm-1 div_year">
                    <label class="control-label">سنة</label>
                    <div>
                        <input type="text" name="year" id="txt_year" class="form-control" value="<?= date('Y') ?>"/>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="search();" class="btn btn-success"> إستعلام</button>
            <a onclick="javascript:print_extract_report();" class="btn btn-warning" href="javascript:;">طباعة التقييم</a>
            <button type="button" onclick="clear_form();" class="btn btn-default"> تفريغ الحقول</button>
        </div>
        <div id="msg_container"></div>

        <div id="container">

        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $(function(){
        $('#dp_customer_id,#dp_report_type').select2();
            $('#dp_report_type').change(function (e) {
                if($('#dp_report_type').val()=='1')
                    {
                        $('#txt_year').val('');
                         if ($('#txt_for_month').val()=='')
                            {
                                danger_msg('يجب ادخال الشهر!!');
                                $('#container').html('');
                            }
                            
                    }
                else
                    {
                        $('#txt_for_month').val('');
                        if ($('#txt_year').val()=='')
                            {
                            danger_msg('يجب ادخال السنة!!');
                              $('#container').html('');

                            }
                       
                    }
                calcall();                
    });
        search();
        reBind();
    });

    function reBind(){
       ajax_pager({
       customer_id:$('#dp_customer_id').val(),report_type:$('#dp_report_type').val(),for_month:$('#txt_for_month').val(),year:$('#txt_year').val()
        });

    }

    function LoadingData(){

    ajax_pager_data('#page_tb > tbody',{
       customer_id:$('#dp_customer_id').val(),report_type:$('#dp_report_type').val(),for_month:$('#txt_for_month').val(),year:$('#txt_year').val()
        });
    }


   function search(){
                if($('#dp_report_type').val()=='1')
                    {
                        $('#txt_year').val('');
                        if ($('#txt_for_month').val()=='')
                            {
                                danger_msg('يجب ادخال الشهر!!');
                                $('#container').html('');
                            }
                             
                        else
                            {
                get_data('{$get_page_url}',{page: 1,
              customer_id:$('#dp_customer_id').val(),report_type:$('#dp_report_type').val(),for_month:$('#txt_for_month').val(),year:$('#txt_year').val()
        
        },function(data){
            $('#container').html(data);

            reBind();

        },'html');
                            }
                            
                    }
                else
                    {
                        $('#txt_for_month').val('');
                        if ($('#txt_year').val()=='')
                            {
                            danger_msg('يجب ادخال السنة!!');
                              $('#container').html('');

                            }
                          else
                            {
              get_data('{$get_page_url}',{page: 1,
              customer_id:$('#dp_customer_id').val(),report_type:$('#dp_report_type').val(),for_month:$('#txt_for_month').val(),year:$('#txt_year').val()
        
        },function(data){
            $('#container').html(data);

            reBind();

        },'html');
                            }
                       
                    }
 
    }


   function show_row_details(id){

        get_to_link('{$get_url}/'+id);

    }

   

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));

    }

    /////////////////////////////////////////
     function print_extract_report(){
        
           var rep_url = '{$report_jasper_url}&report_type=pdf&report=suppliers_evals&p_customer_id='+$('#dp_customer_id').val()+'&p_for_month='+$('#txt_for_month').val()+'&p_year='+$('#txt_year').val()+'&sn={$report_sn}';
           _showReport(rep_url);
     }

</script>
SCRIPT;
sec_scripts($scripts);
?>



