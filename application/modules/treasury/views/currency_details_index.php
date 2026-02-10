<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/08/14
 * Time: 11:36 ص
 */

$MODULE_NAME= 'treasury';
$TB_NAME= 'currency_details';
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$select_url= base_url("$MODULE_NAME/$TB_NAME/select2");
$print_url =  base_url('/reports');
$report_url = base_url("JsperReport/showreport?sys=financial/accounts");
$today= date('d/m/Y');

?>

<div class="row">
    <div class="toolbar">

        <div class="caption">أسعار العملات</div>
        <?php
        if (HaveAccess($create_url)){
            echo "
                <ul>
                    <li><a onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد</a></li>
                    <li><a onclick='javascript:{$TB_NAME}_save();' href='javascript:;'><i class='glyphicon glyphicon-saved'></i>حفظ</a></li>
                    <li><a id='show_modal' href='javascript:;'><i class='glyphicon glyphicon-print'></i>طباعة</a></li>
                    <li><a onclick=\"{$help}\" href='javascript:;' class='help'><i class='icon icon-question-circle'></i></a> </li>
                </ul>
            ";
        }
        ?>


    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$create_url?>" role="form" novalidate="novalidate">
                <div class="modal-body">
                    <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
                </div>
                <?=AntiForgeryToken(); ?>

            </form>

        </div>
    </div>

</div>


<div class="modal fade" id="PrintModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">طباعة </h4>
            </div>
            <form class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">من </label>
                        <div class="col-sm-2">
                            <input type="text" id="txt_from" value="<?=$today?>" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">حتى </label>
                        <div class="col-sm-2">
                            <input type="text" id="txt_to" value="<?=$today?>" class="form-control ltr">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="print_rep" class="btn btn-primary">طباعة </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php

$scripts = <<<SCRIPT
<script type="text/javascript">

    var count= {$count} ;
    var today= '{$today}' ;

    function {$TB_NAME}_hide(id){
        $('#{$TB_NAME}_tb tbody [data-id='+id+']').remove();
    }

    function {$TB_NAME}_create() {
        count++ ;
        if(count<=20){
            $("#{$TB_NAME}_tb tbody").append(
                "<tr data-id='"+count+"' id='tr-"+count+"'>" +
                    "<td><div class='col-sm-3'>"+count+"</div></td>" +
                    "<td>{$select_master_curr}</td>" +
                    "<td class='curr_id'>{$select_curr_id}</td>" +
                    "<td><div class='col-sm-9'><input type='number' data-val='true' name='curr_value[]' id='txt_curr_value' value='' class='form-control' maxlength='8' dir='ltr'></div></td> "+
                    "<td><div class='col-sm-9'><input type='text' data-val='true' name='date_by_user[]' id='txt_date_by_user' value='"+today+"' class='form-control' ></div></td> "+
                    "<td></td>"+
                    "<td><div class='col-sm-2'><a href='javascript:;' onclick='javascript:{$TB_NAME}_hide("+count+");'>"+
                    "<i class='glyphicon glyphicon-remove''></i>"+
                    "</a></div></td>"+
                "</tr>"
            );

            $('#tr-'+count+' .master_curr').change(function(){
                var curr_id= $(this).closest('tr').find('td.curr_id');
                get_data('{$select_url}',
                    {id:$(this).val()},
                    function(ret){
                        curr_id.html(ret);
                    }, 'html');
            });

        }else{
            alert('لا يمكن اضافة سجل جديد بسبب تجاوز الحد الاقصى للادخال');
        }
    }


    function {$TB_NAME}_save() {
        if(validation()){
            var form = $('#{$TB_NAME}_form');
            ajax_insert_update(form,function(data){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                $('#{$TB_NAME}_form .modal-body').html(data);
            },"html");
        }
    }


    $('#show_modal').click(function(){
        $("#PrintModal").modal();
    });

    $('#print_rep').click(function(){
        repUrl = '{$report_url}&report_type=pdf&report=curr&p_from_date='+$('#txt_from').val()+'&p_to_date='+$('#txt_to').val()+'';
        _showReport(repUrl);   
    });

    function validation(){
        var ret= true;
        var c=0;
        var i=0;

        $('#{$TB_NAME}_form input[type="number"]').each(function(){
            c++;
            if(this.value=='' || this.value <=0 ){  // || !isNaN(this.value)
                i++;
            }
        });

        if(c==0){
            alert("لا يوجد سجلات للحفظ");
            ret= false;
        }
        else if(i>0){
            alert("يوجد "+i+" قيم غير رقمية");
            ret= false;
        }
        else{
            var master_curr= [];
            var currs= [];
            $('#{$TB_NAME}_form td .master_curr').each(function(){
                master_curr.push(this.value);
            });

            $('#{$TB_NAME}_form td .curr_id').each(function(i){
                currs.push(master_curr[i]+''+this.value);
            });

            var sorted_currs = currs.sort();
            var results = [];
            for (var i= 0; i < sorted_currs.length - 1; i++) {
                if (sorted_currs[i+1] == sorted_currs[i]) {
                    results.push(sorted_currs[i]);
                }
            }
            if(results.length > 0){
                alert('يوجد '+results.length+' سجلات مكررة لنفس العملات');
                ret= false;
            }
        }
        if(ret){
            var i= 0;
            $('#{$TB_NAME}_form td .prev_curr_value').each(function(){
                if($(this).closest('tr').find('input[type="number"]').val() == this.value  )
                    i++;
            });
            if(i>0){
                if(!confirm('لم يتم تعديل '+i+' قيم، هل تريد بالتأكيد حفظ السجلات بنفس القيم السابقة ؟!!')){
                    ret= false;
                }
            }
        }
        return ret;
    }

</script>

SCRIPT;

sec_scripts($scripts);

?>
