<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 02/12/14
 * Time: 08:49 ص
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'customers';
$report_url =base_url('reports?type=31');
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url =base_url('payment/customers/get_page');
$jasper_report_url = base_url("JsperReport/showreport?sys=financial/customers");

echo AntiForgeryToken();
?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=($c_type==7)?$create_url.'/sun':$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>

        <div class="form-body">
            <fieldset>
                <legend>بحـث</legend>
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-1">
                        <label class="control-label">الرقم</label>
                        <div class="">
                            <input type="text" id="txt_customer_id"  class="form-control "/>


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> الاسم</label>
                        <div class="">
                            <input type="text"   id="txt_customer_name" class="form-control "/>


                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">النوع </label>
                        <div class="">
                            <select id="dp_customer_type" class="form-control" >
                                <option value="">____________</option>
                                <?php foreach($customer_type as $row) :?>
                                    <option <?=($c_type==$row['CON_NO'])?'selected':''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الحساب </label>
                        <div >
                            <select id="dp_account_id" class="form-control" >
                                <option value="">____________</option>
                                <?php foreach($account_cons as $row) :?>
                                    <option <?=($c_type==$row['ACOUNT_ID'])?'selected':''?> value="<?= $row['ACOUNT_ID'] ?>"><?= $row['ACOUNT_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>


                </div>
                <div class="modal-footer">

                    <button type="button" onclick="javascript:search_data();" class="btn btn-success"> إستعلام</button>

                    <button type="button" onclick="javascript:print_report();" class="btn btn-warning">طباعة <i class="fa fa-file-excel-o" style="font-size:17px;color:#fff"></i></button>

                    <button type="button" onclick="$('#customers_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>

                    <button type="button" onclick="javascript:clearForm_any($('fieldset'));search_data();" class="btn btn-default"> تفريغ الحقول</button>
                </div>
            </fieldset>

            <div id="msg_container"></div>

            <div id="container">
                <?=modules::run("$MODULE_NAME/$TB_NAME/get_page",$page, ($c_type==7)? 7:1 );?>
            </div>

        </div>

    </div>

<?php
$edit='';
if(HaveAccess($edit_url))
    $edit= isset($action)?$action:'';

$scripts = <<<SCRIPT
<script>

    if({$c_type}==7){
        $('#dp_customer_type').select2().select2('readonly',1);
    }

    function select_customer(id, name){
        get_to_link('{$get_url}'+'/'+id+'/'+'{$edit}');
    }

      $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({customer_id : $('#txt_customer_id').val(),customer_name:$('#txt_customer_name').val(),customer_type:$('#dp_customer_type').val(), account_id:$('#dp_account_id').val() });

    }

    function LoadingData(){

    ajax_pager_data('#customers_tb > tbody',{id : $('#txt_customer_id').val(),name:$('#txt_customer_name').val(),type:$('#dp_customer_type').val(), account_id:$('#dp_account_id').val() });

    }


   function search_data(){

        if({$c_type}==7){
            var customer_type= {$c_type};
        }else{
            var customer_type= $('#dp_customer_type').val();
        }

        get_data('{$get_page_url}',{page:1,id : $('#txt_customer_id').val(),name:$('#txt_customer_name').val(),type:customer_type, account_id:$('#dp_account_id').val() },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
 var selected = 0;
    var data_report_source ='';

    $(function () {



        $('#accountsModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        });

        $('a[data-type="report"]').click(function(){

            $('.rp_prm').hide();
            clearForm_any('#reportFilterModal');

            data_report_source = $(this).attr('data-report-source');

            $($(this).attr('data-option')).show();
            $('#reportFilterModal').modal();
        });


        $('button[data-action="report"]').click(function(){

            var url='';
            var f_date =$('#txt_date1').val();
            var t_date =$('#txt_date2').val();
            var branch =$('#dp_branch').val();


            switch(data_report_source){

                 case '1':
                   var report_id = $('#dp_report').val();

                   switch(report_id){
                         case '1':

                          url ='$report_url&report=FINANCIAL_BOOK_CHAINS_CUSTOMER&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+selected+'&params[]=&params[]='+$('#dp_currency').val()+'&params[]=1&params[]={$this->user->id}';
                         break;

                         case '2':

                          url ='$report_url&report=FINANCIAL_BOOK_CHAINS_ALL-CUSTOMER&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+selected+'&params[]=&params[]='+$('#dp_currency').val()+'&params[]=1&params[]={$this->user->id}';
                         break;
                   }


                 break;


                 case '2':
                        url ='$report_url&report=FINANCIAL_CHAINS_TB_acount_REP&params[]='+selected+'&params[]='+f_date+'&params[]='+t_date+'&params[]=2';

                 break;

            }

            _showReport(url);

        });
    });

    function select_account(id,name,curr){
           selected = id;


           $('#menuModal').modal();
    }


    function print_report(){
        var customer_name = $('#txt_customer_name').val();
        var customer_id = $('#txt_customer_id').val();
        var customer_type = $('#dp_customer_type').val();
        //var account_id= $('#dp_account_id').val();

        _showReport('{$jasper_report_url}&report_type=xls&report=customers&p_customer_id='+customer_id+'&p_customer_name='+customer_name+'&p_customer_type='+customer_type+'');
    }



</script>
SCRIPT;

sec_scripts($scripts);

?>



<div class="modal fade" id="menuModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">التقارير و الشاشات </h4>
            </div>
            <div class="modal-body">

                <ul class="report-menu">
                     <li><a class="btn green" data-report-source="2" data-option="" data-type="report" href="javascript:;">حركة الحساب  </a> </li>
                    <li><a class="btn blue" data-report-source="1" data-option="#t_report,#branch,#currency" data-type="report" href="javascript:;">حساب الاستاذ </a> </li>

                    <li></li>
                    <li></li>
                    <li></li>

                </ul>

            </div>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="reportFilterModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">معير البحث</h4>
            </div>
            <div class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group rp_prm" id="t_report" style="display: none;" >
                        <label class="col-sm-3 control-label"> نوع التقرير</label>
                        <div class="col-sm-7">
                            <select   class="form-control"   id="dp_report">
                                <option value="1">حساب استاذ بسيط حسب المجموعة</option>
                                <option value="2">حساب استاذ مفصل</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group rp_prm" id="m_report" style="display: none;" >
                        <label class="col-sm-3 control-label"> نوع التقرير</label>
                        <div class="col-sm-7">
                            <select   class="form-control"   id="dp_m_report">
                                <option value="1">ميزان المراجعة بالمجاميع  </option>
                                <option value="2">ميزان المراجعة بالأرصدة </option>
                                <option value="3">ميزان المراجعة بالأرصدة و المجاميع</option>
                                <option value="4">ميزان مراجعه الحسابات وتوابعها بالمجاميع </option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="date1" >
                        <label class="col-sm-3 control-label"> التاريخ </label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date1" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group" id="date2" >
                        <label class="col-sm-3 control-label"> حتى</label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date2" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="currency" style="display: none;" >
                        <label class="col-sm-3 control-label"> العملة</label>
                        <div class="col-sm-4">
                            <select name="report_curr" class="form-control"   id="dp_currency">
                                <?php foreach($currency as $row) :?>
                                    <option data-dept="<?= $row['CURR_ID'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group rp_prm" id="branch" style="display: none;" >
                        <label class="col-sm-3 control-label"> الفرع</label>
                        <div class="col-sm-4">
                            <select name="report" class="form-control"   id="dp_branch">
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-action="report" class="btn btn-primary">عرض التقرير </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

