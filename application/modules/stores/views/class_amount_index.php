<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/01/15
 * Time: 07:37 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'class_amount';
$get_page_url =base_url("$MODULE_NAME/$TB_NAME/get_page");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");
$reports_url=base_url("$MODULE_NAME/$TB_NAME/reports_form");
$print_url = gh_itdev_rep_url().'/gfc.aspx?data='.get_report_folder().'&' ;
$report_url = base_url("JsperReport/showreport?sys=financial/stores");
$report_sn= report_sn();

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption">ارصدة الاصناف</div>
    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">المخزن</label>
                    <div>
                        <select name="store_id" id="dp_store_id" class="form-control"  >
                            <option value="0">جميع المخازن</option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الصنف من</label>
                    <div>
                        <input readonly class="form-control"  id="txt_class_id" />
                        <input type="hidden" name="class_id" id="h_txt_class_id" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input readonly class="form-control"  id="txt_class_id2" />
                        <input type="hidden" name="class_id2" id="h_txt_class_id2" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">خصم الحجوزات</label>
                    <div>
                        <select name="reserve" id="dp_reserve" class="form-control"  >
                            <option value="0" selected >نعم</option>
                            <option value="2">نعم عدا الطلبات</option>
                            <option value="1">لا</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">&nbsp;</label>
                    <div>
                        <input id="cb_class_min" type="checkbox"> عرض الاصناف التي وصلت للحد الادنى
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">&nbsp;</label>
                    <div>
                        <input id="cb_class_min_request" type="checkbox"> عرض الاصناف التي وصلت لحد اعادة الطلب
                    </div>
                </div>

                <input type="hidden" id="h_data_search" />

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" id="print_class_amount_rep" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> ارصدة الاصناف </button>
            <button type="button" id="print_class_prcie_rep" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> اسعار الاصناف </button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div class="btn-group">
            <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
            <ul class="dropdown-menu " role="menu">
                <li><a href="#" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                <li><a href="#" onclick="$('#page_tb').tableExport({type:'doc',escape:'false'});">  Word</a></li>
            </ul>
        </div>

        <div id="container">

        </div>

    </div>

</div>


<div class="modal fade" id="repModal">
    <div class="modal-dialog" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>


<?php

$scripts = <<<SCRIPT
<script type="text/javascript">

    $('#dp_store_id,#dp_reserve').select2();

    $('#txt_class_id, #txt_class_id2').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val());
    });

    function do_after_select(){
        var class_id_1 = $('#h_txt_class_id').val();
        var class_id_2 = $('#h_txt_class_id2').val();
        var class_name_1 = $('#txt_class_id').val();

        if( class_id_1 !='' &&  class_id_2=='' ){
            $('#h_txt_class_id2').val(class_id_1);
            $('#txt_class_id2').val(class_name_1);
        }

        // For Report search
        class_id_1 = $('#h_txt_class1').val();
        class_id_2 = $('#h_txt_class2').val();
        class_name_1 = $('#txt_class1').val();

        if( class_id_1 !='' &&  class_id_2=='' ){
            $('#h_txt_class2').val(class_id_1);
            $('#txt_class2').val(class_name_1);
        }
    }

    function search(){
        var class_min=0;
        $('#container').text('');
        if( $('#cb_class_min_request').is(':checked') )   class_min=2;
        if( $('#cb_class_min').is(':checked') )   class_min=1;
        var values= {store_id: $('#dp_store_id').val(), class_id: $('#h_txt_class_id').val(), class_id2: $('#h_txt_class_id2').val(), class_min: class_min , reserve: $('#dp_reserve').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_store_id,#dp_reserve').select2('val',0);
    }

    $('#print_class_amount_rep').click(function(){
        $('#repModal .modal-body').text('');
        get_data("{$reports_url}", {}, function(ret){
            $('#repModal .modal-body').html(ret);
            $('#repModal').modal();
        }, 'html');
    });

    $('#print_class_amount_rep2').click(function(){
        _showReport("$print_url"+"report=CLASS_AMOUNT_BALANCE&params[]= and a.class_id BETWEEN 10101001 and 10405008 &params[]=01/01/2015&params[]=10/03/2015&params[]=2&params[]=$report_sn");
        //_showReport("print_url?report=CLASS_AMOUNT&params[]="+$('#h_txt_class_id').val()+"&params[]="+$('#h_txt_class_id2').val()+"&params[]="+$('#dp_store_id').val());
    });
    $('#print_class_prcie_rep').click(function(){
        _showReport("$report_url"+"&report_type=pdf&report=CLASS_PRCIE_MOVMENT&p_class_id="+$('#h_txt_class_id').val());
    });

</script>

SCRIPT;

sec_scripts($scripts);

?>
