<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/03/15
 * Time: 05:30 م
 */

$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$print_url= base_url('/reports');
$report_sn= report_sn();
$report_url = base_url("JsperReport/showreport?sys=financial/stores");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$project_accounts_url =base_url('projects/projects/public_select_project_accounts');

$domain= strtolower($_SERVER['HTTP_HOST']);
$base_folder= trim( base_url(),'/');

?>

<style>
     #repModal .form-horizontal, #repModal .btn-move{display: none}
</style>

<div class="modal-body">
    <form class="form-horizontal">

        <div class="form-group">
            <div class="group">
                <label class="col-sm-1 control-label">الصنف من</label>
                <div class="col-sm-1">
                    <input type="text" id="h_txt_class1" class="form-control class_id">
                </div>
                <div class="col-sm-3">
                    <input type="text" readonly id="txt_class1" class="form-control class_name">
                </div>
            </div>
            <div class="group">
                <label class="col-sm-1 control-label"> الى</label>
                <div class="col-sm-1">
                    <input type="text" id="h_txt_class2" class="form-control class_id">
                </div>
                <div class="col-sm-3">
                    <input type="text" readonly id="txt_class2" class="form-control class_name">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">نوع الصنف من</label>
            <div class="col-sm-4">
                <select name="class_acount_type" id="dp_class_acount_type1" class="form-control" >
                <option></option>
                <?php foreach($class_acount_type_all as $row) :?>
                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <label class="col-sm-1 control-label"> الى</label>
            <div class="col-sm-4">
                <select name="class_acount_type" id="dp_class_acount_type2" class="form-control" >
                <option></option>
                <?php foreach($class_acount_type_all as $row) :?>
                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">حالة الصنف من</label>
            <div class="col-sm-4">
                <select name="class_type" id="dp_class_type1" class="form-control" >
                <option></option>
                <?php foreach($class_type_all as $row) :?>
                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <label class="col-sm-1 control-label"> الى</label>
            <div class="col-sm-4">
                <select name="class_type" id="dp_class_type2" class="form-control" >
                <option></option>
                <?php foreach($class_type_all as $row) :?>
                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">المخزن  من</label>
            <div class="col-sm-4">
                <select name="store_id" id="dp_store_id1" class="form-control"  >
                    <option value="0">جميع المخازن</option>
                    <?php foreach($stores_all as $row) :?>
                        <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <label class="col-sm-1 control-label"> الى</label>
            <div class="col-sm-4">
                <select name="store_id" id="dp_store_id2" class="form-control"  >
                    <option value="0">جميع المخازن</option>
                    <?php foreach($stores_all as $row) :?>
                        <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">التاريخ  من</label>
            <div class="col-sm-4">
                <input type="text" name="date1" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_date1" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                <span class="field-validation-valid" data-valmsg-for="date1" data-valmsg-replace="true"></span>
            </div>
            <label class="col-sm-1 control-label"> الى</label>
            <div class="col-sm-4">
                <input type="text" name="date2" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_date2" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                <span class="field-validation-valid" data-valmsg-for="date2" data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">الحساب</label>
            <div class="col-sm-2">
                <select name="account_type" id="dp_account_type" class="form-control" >
                <option value="0"> </option>
                <?php foreach($account_type_all as $row) :?>
                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-4">
                <input type="text" readonly class="form-control" id="txt_account_id" />
                <input type="hidden" name="account_id" id="h_txt_account_id" />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">الحركة</label>
            <div class="col-sm-2">
                <select name="action" id="dp_action" class="form-control"  >
                    <option value="0">جميع الحركات</option>
                    <option value="1">وارد</option>
                    <option value="2">صادر</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-3">
                <div class="checkbox">
                    <label>
                        <input id="cb_reserve_all" type="checkbox"> بدون حجز كميات
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-3">
                <div class="checkbox">
                    <label>
                        <input id="cb_reserve_req" type="checkbox"> بدون حجز كميات طلب الصرف
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group btn-move">
            <div class="col-sm-offset-2 col-sm-4">
                <button type="button" class="btn btn-info rep_balance_new">
                    <i class="glyphicon glyphicon-print"></i>
                    أرصدة الأصناف
                </button>

                </br></br>
                <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio"  name="rep_type_id" value="xls">
                <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>




            </div>
            <div class="col-sm-offset-2 col-sm-2">
                <button type="button" class="btn btn-info">
                    <i class="glyphicon glyphicon-print"></i>
حركات الأصناف
                </button>
            </div>
        </div>

    </form>
</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript" >
    $(document).ready(function() {

        $('#repModal .form-horizontal').slideDown(500);
        $('#repModal .btn-move').delay(450).fadeIn(700);

        $('.class_name').click("focus",function(e){
            _showReport('$select_items_url/'+$(this).attr('id') );
        });

        $('#dp_class_acount_type1, #dp_class_type1, #dp_store_id1, #txt_date1').change(function(){
            set_def_val($(this).attr('id'));
        });

        $('#dp_action, #dp_account_type').change(function(){
            if( $(this).val() ==0 && $('#dp_action').val() ==0 && $('#dp_account_type').val()==0 )
                $('#rep_balance').prop('disabled', false);
            else
                $('#rep_balance').prop('disabled', true);
        });

        $('.class_id').bind("focusout",function(e){
            var id= $(this).val();
            var class_id= $(this).closest('.group').find('.class_id');
            var name= $(this).closest('.group').find('.class_name');
            if(id=='' || isNaN(id)){
                class_id.val('');
                name.val('');
                return 0;
            }
            get_data('{$get_class_url}',{id:id, type:1},function(data){
                if (data.length == 1){
                    var item= data[0];
                    class_id.val(item.CLASS_ID);
                    name.val(item.CLASS_NAME_AR);
                }else{
                    class_id.val('');
                    name.val('');
                }
            });
        });

        $('#dp_account_type').change(function(){
            $('#txt_account_id').val('');
            $('#h_txt_account_id').val('');
        });

        $('#txt_account_id').click(function(e){
            var _type = $('#dp_account_type').val();
            if(_type == 1)
                _showReport('$select_accounts_url/'+$(this).attr('id') );
            else if(_type == 2)
                _showReport('$customer_url/'+$(this).attr('id')+'/1');
            else if(_type == 3)
                _showReport('$project_accounts_url/'+$(this).attr('id')+'/1');
        });

        $('#rep_balance').click("focus",function(e){
            var where= ' ';
            var reserve= 1;
            if( $('#h_txt_class1').val() != '' )
                where+= ' and a.class_id >= '+ $('#h_txt_class1').val();
            if( $('#h_txt_class2').val() != '' )
                where+= ' and a.class_id <= '+ $('#h_txt_class2').val();

            if( $('#dp_class_acount_type1').val() != '' )
                where+= ' and c.class_acount_type >= '+ $('#dp_class_acount_type1').val();
            if( $('#dp_class_acount_type2').val() != '' )
                where+= ' and c.class_acount_type <= '+ $('#dp_class_acount_type2').val();

            if( $('#dp_class_type1').val() != '' )
                where+= ' and a.class_type >= '+ $('#dp_class_type1').val();
            if( $('#dp_class_type2').val() != '' )
                where+= ' and a.class_type <= '+ $('#dp_class_type2').val();

            if( $('#dp_store_id1').val() != 0 )
                where+= ' and a.store_id >= '+ $('#dp_store_id1').val();
            if( $('#dp_store_id2').val() != 0 )
                where+= ' and a.store_id <= '+ $('#dp_store_id2').val();

            if( $('#txt_date1').val() != '' )
                where+= " and TRUNC(a.adopt_date,'dd') >= to_date('"+$('#txt_date1').val()+"','dd/mm/yyyy') ";
            if( $('#txt_date2').val() != '' )
                where+= " and TRUNC(a.adopt_date,'dd') <= to_date('"+$('#txt_date2').val()+"','dd/mm/yyyy') ";
/*
            if( $('#dp_account_type').val() != 0 && $('#h_txt_account_id').val() != '' ){
                where+= ' and qf_pkg.get_qeed( DECODE(a.source,5,17, 6,18, 3,19, 2,21, 0) , a.PK) in ';
                where+= ' ( select d.financial_chains_id ';
                where+= ' from gfc.financial_chains_detail_tb d ';
                where+= ' where d.account_type= '+ $('#dp_account_type').val() + " and d.account_id= '"+ $('#h_txt_account_id').val() + "') " ;
            }
*/
            if( $('#cb_reserve_all').is(':checked') ){
                where+= ' and a.source !=7 and a.source !=8 and a.source !=9 '; // OLD-202103 and a.source not in (7,8,9)
                reserve= 2;
            }else if( $('#cb_reserve_req').is(':checked') ){
                where+= ' and a.source != 7 ';
                reserve= 3;
            }
            _showReport("$print_url?report=CLASS_AMOUNT_BALANCE&params[]= "+where+" &params[]="+$('#txt_date1').val()+"&params[]="+$('#txt_date2').val()+"&params[]="+reserve+"&params[]=$report_sn");
        });
        
        
        $('.rep_balance_new').click("focus",function(e){
            var rep_type = $('input[name=rep_type_id]:checked').val();
            
            var repUrl = '{$report_url}&report_type='+rep_type+'&report=class_amount_balance_'+rep_type+''
            +'&p_domain='+'$domain'
            +'&p_year='+'$base_folder'
            +'&p_class_id_1='+$('#h_txt_class1').val()
            +'&p_class_id_2='+$('#h_txt_class2').val()
            +'&p_class_acount_type1='+$('#dp_class_acount_type1').val()
            +'&p_class_acount_type2='+$('#dp_class_acount_type2').val()
            +'&p_class_type1='+$('#dp_class_type1').val()
            +'&p_class_type2='+$('#dp_class_type2').val()
            +'&p_store_id1='+$('#dp_store_id1').val()
            +'&p_store_id2='+$('#dp_store_id2').val()
            +'&p_adopt_date1='+$('#txt_date1').val()
            +'&p_adopt_date2='+$('#txt_date2').val()
            ;
            if( $('#cb_reserve_all').is(':checked') ){
                repUrl+= '&p_cb_reserve_all='+1+'&p_cb_reserve_req='+'';
            }else if( $('#cb_reserve_req').is(':checked') ){
                repUrl+= '&p_cb_reserve_all='+''+'&p_cb_reserve_req='+1;
            }else{
                repUrl+= '&p_cb_reserve_all='+''+'&p_cb_reserve_req='+'';
            }
            
            _showReport(repUrl);
            warning_msg('تنويه','لفتح رابط حركات الاصناف من التقرير يرجى الضغط على Ctrl والنقر على رقم الصنف');
        });
        
    });

    function set_def_val(id){
        var id2 = id.replace("1", "2");
        if( $('#'+id2).val()== 0 || $('#'+id2).val()== undefined ){
            $('#'+id2).val( $('#'+id).val() );
        }
    }


</script>
SCRIPT;
sec_scripts($scripts);
?>
