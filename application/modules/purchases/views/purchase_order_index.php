<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 24/02/15
 * Time: 10:47 ص
 */

$MODULE_NAME= 'purchases';
$TB_NAME= 'purchase_order';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create").'?order_purpose='.$order_purpose;
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit").'?order_purpose='.$order_purpose;
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$committee_notify_url = base_url("$MODULE_NAME/suppliers_offers/committee_notify");
$do_order_url = base_url("$MODULE_NAME/$TB_NAME/do_order");
$do_order_items_url = base_url("$MODULE_NAME/$TB_NAME/do_order_items");
$offers_url = base_url("purchases/suppliers_offers");
$merge_url= base_url("$MODULE_NAME/$TB_NAME/merge");
$select_order_url = base_url("$MODULE_NAME/$TB_NAME/public_index");

$print_url=  'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;

/*mtaqia*/
$report_url = base_url("JsperReport/showreport?sys=purchases");
$report_sn= report_sn();
/* ----- */

if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
          <?php  if(HaveAccess($merge_url)) echo "<li><a onclick='javascript:merge();' href='javascript:;'><i class='glyphicon glyphicon-download'></i>دمج الطلبات</a> </li>"; ?>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1" style="display: none">
                    <label class="control-label">الطلبات </label>
                    <div>
                        <input type="text" readonly name="purchase_orders" id="txt_purchase_orders" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المسلسل</label>
                    <div>
                        <input type="text" name="purchase_order_id" id="txt_purchase_order_id" class="form-control">

                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> رقم طلب الشراء </label>
                    <div>
                        <input type="text" name="purchase_order_num" id="txt_purchase_order_num" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع الطلب</label>
                    <div>
                        <select name="purchase_type" id="dp_purchase_type" class="form-control" >
                        <option></option>
                        <?php foreach($purchase_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع الاصناف</label>
                    <div>
                        <select name="purchase_order_class_type" id="dp_purchase_order_class_type" class="form-control" >
                        <option></option>
                        <?php foreach($purchase_order_class_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
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

                <div class="form-group col-sm-2">
                    <label class="control-label">مدخل الطلب</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control" >
                        <option></option>
                        <?php foreach($entry_user_all as $row) :?>
                            <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الطلب </label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control" >
                        <option></option>
                        <?php foreach($adopt_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مصمم عرض السعر</label>
                    <div>
                        <select name="design_quote_user" id="dp_design_quote_user" class="form-control" >
                        <option></option>
                        <?php foreach($design_quote_user_all as $row) :?>
                            <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة عرض السعر </label>
                    <div>
                        <select name="design_quote_case" id="dp_design_quote_case" class="form-control" >
                        <option></option>
                        <?php foreach($design_quote_case_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <input type="hidden" name="committees" value="<?=$committees?>" id="h_committees" class="form-control">

                <?php if(1 or $committees==-1){ ?>
                    <div class="form-group col-sm-2">
                        <label class="control-label">حالة التحويل</label>
                        <div>
                            <select name="committee_case" id="dp_committee_case" class="form-control" >
                            <option></option>
                            <?php foreach($committee_case_all as $row):
                                if(intval($row['CON_NO']) >= intval($committee_case)){
                            ?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php } endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php }else{ ?>
                    <input type="hidden" name="committee_case" value="<?=$committee_case?>" id="dp_committee_case" class="form-control">
                <?php } ?>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة التأجيل</label>
                    <div>
                        <select name="delay_case" id="dp_delay_case" class="form-control" >
                        <option></option>
                        <?php foreach($delay_case_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة التوريد</label>
                    <div>
                        <select name="convert_case" id="dp_convert_case" class="form-control" >
                        <option></option>
                            <option value="1">مورد</option>
                            <option value="2">غير مورد</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">من تاريخ السند  </label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"  id="from_date" class="form-control" />

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">إلى تاريخ السند  </label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_date"  id="to_date" class="form-control" />

                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            <button class="btn btn-warning dropdown-toggle" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
            <button type="button" class="btn blue" onclick="javascript:showReportx('pdf');" class="btn btn-success"> طباعة </button>

        </div>


        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $committees, $committee_case, $purchase_order_id, $purchase_order_num, $purchase_type, $purchase_order_class_type, $notes, $entry_user, $adopt, $design_quote_user, $design_quote_case, $delay_case, $convert_case,$from_date,$to_date );?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>
 function showReportx(type){
var order_purpose='{$order_purpose}';
var quote='{$quote}';
var committees=$('#h_committees').val();
  var committee_case= $('#dp_committee_case').val();
   if(committee_case=='' && parseInt({$committee_case})!= -1)
     committee_case= parseInt({$committee_case}) * -1;
   var purchase_order_id=$('#txt_purchase_order_id').val();
   var purchase_order_num=$('#txt_purchase_order_num').val();
    var purchase_type=$('#dp_purchase_type').val();
    var purchase_order_class_type=$('#dp_purchase_order_class_type').val();
    var notes=$('#txt_notes').val();
    var entry_user=$('#dp_entry_user').val();
    var adopt=$('#dp_adopt').val();
    var design_quote_user=$('#dp_design_quote_user').val();
    var design_quote_case=$('#dp_design_quote_case').val();
    var delay_case=$('#dp_delay_case').val();
    var convert_case=$('#dp_convert_case').val();
    var from_date=$('#from_date').val();
    var to_date=$('#to_date').val() ;
    if (type='pdf')
     var url ='{$report_url}&report_type=pdf&report=Purchase_order_data&p_order_purpose='+order_purpose+'&p_quote='+quote+'&p_committees='+committees+'&p_purchase_order_id='+purchase_order_id+'&p_purchase_order_num='+purchase_order_num+'&p_purchase_type='+purchase_type+'&p_purchase_order_class_type='+purchase_order_class_type+'&p_notes='+notes+'&p_entry_user='+entry_user+'&p_adopt='+adopt+'&p_design_quote_user='+design_quote_user+'&p_design_quote_case='+design_quote_case+'&p_delay_case='+delay_case+'&p_convert_case='+convert_case+'&p_from_date='+from_date+'&p_to_date='+to_date+'&sn={$report_sn}';
    else
    var url ='{$report_url}&report_type=xls&report=Purchase_order_data&p_order_purpose='+order_purpose+'&p_quote='+quote+'&p_committees='+committees+'&p_purchase_order_id='+purchase_order_id+'&p_purchase_order_num='+purchase_order_num+'&p_purchase_type='+purchase_type+'&p_purchase_order_class_type='+purchase_order_class_type+'&p_notes='+notes+'&p_entry_user='+entry_user+'&p_adopt='+adopt+'&p_design_quote_user='+design_quote_user+'&p_design_quote_case='+design_quote_case+'&p_delay_case='+delay_case+'&p_convert_case='+convert_case+'&p_from_date='+from_date+'&p_to_date='+to_date+'&sn={$report_sn}'; 
       _showReport(url);               
   }    
   
    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    $('input[name="purchase_orders"]').click("focus",function(e){
        _showReport('$select_order_url/'+$(this).attr('id')+'?order_purpose=$order_purpose' );
    });

    function show_row_details(id){
        if({$committees}==1 && {$committee_case}==4)
            get_to_link('{$offers_url}/award{$order_purpose}/'+id);
        else if({$committees}==1 && {$committee_case}==2)
            get_to_link('{$offers_url}/envelopment{$order_purpose}/'+id);
        else if('{$quote}'=='1')
            get_to_link('{$get_url}/'+id+'/quote');
        else
            get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    function committee_notify(purchase_order_id,order_purpose,committee_case){
        var values= {purchase_order_id:purchase_order_id,order_purpose:order_purpose,committee_case:committee_case};
        get_data('{$committee_notify_url}',values ,function(data){
            if(data==1) success_msg('رسالة','تم ارسال التنبيه');
            else danger_msg('تحذير..',data);
        },'html');
    }

    function search(){
        var committee_case= $('#dp_committee_case').val();
        if(committee_case=='' && parseInt({$committee_case})!= -1)
            committee_case= parseInt({$committee_case}) * -1;
        var values= {order_purpose:{$order_purpose}, quote:'{$quote}', page:1, committees:$('#h_committees').val(), committee_case:committee_case, purchase_order_id:$('#txt_purchase_order_id').val(), purchase_order_num:$('#txt_purchase_order_num').val(), purchase_type:$('#dp_purchase_type').val(), purchase_order_class_type:$('#dp_purchase_order_class_type').val(), notes:$('#txt_notes').val(), entry_user:$('#dp_entry_user').val(), adopt:$('#dp_adopt').val(), design_quote_user:$('#dp_design_quote_user').val(), design_quote_case:$('#dp_design_quote_case').val(), delay_case:$('#dp_delay_case').val(), convert_case:$('#dp_convert_case').val(),from_date:$('#from_date').val(),to_date:$('#to_date').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var committee_case= $('#dp_committee_case').val();
        if(committee_case=='' && parseInt({$committee_case})!= -1)
            committee_case= parseInt({$committee_case}) * -1;
        var values= {order_purpose:{$order_purpose}, quote:'{$quote}', committees:$('#h_committees').val(), committee_case:committee_case, purchase_order_id:$('#txt_purchase_order_id').val(), purchase_order_num:$('#txt_purchase_order_num').val(), purchase_type:$('#dp_purchase_type').val(), purchase_order_class_type:$('#dp_purchase_order_class_type').val(), notes:$('#txt_notes').val(), entry_user:$('#dp_entry_user').val(), adopt:$('#dp_adopt').val(), design_quote_user:$('#dp_design_quote_user').val(), design_quote_case:$('#dp_design_quote_case').val(), delay_case:$('#dp_delay_case').val(), convert_case:$('#dp_convert_case').val(),from_date:$('#from_date').val(),to_date:$('#to_date').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function do_order(id,o){
    var url='';
    if (o==1) url='{$do_order_url}';
    else  if (o==2) url='{$do_order_items_url}';
        if(confirm('هل تريد تحويل الطلب ؟!')){
            get_data(url, {purchase_order_id: id }, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم تحويل الطلب بنجاح ..');
                    $('a#do_order_'+id).hide();
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }


    function clear_form(){
        var committees= $(':hidden#h_committees').val();
        var committee_case= $(':hidden#dp_committee_case').val();
        clearForm($('#{$TB_NAME}_form'));
        $(':hidden#h_committees').val(committees);
        $(':hidden#dp_committee_case').val(committee_case);
    }
    function print_rep(id){
        _showReport('$print_url'+'report=purchases/PURCHASE_ORDER_REP_INFO1&params[]='+id);
    }

    // mtaqia
    function print_rep_jasper(id){
        _showReport('{$report_url}&type=pdf&report=Purchase_Decide_Committee&p_purchase_order_id='+id+'&sn={$report_sn}',true);
    }

function merge(){
        var url = '{$merge_url}';
        var tbl = '#page_tb';

        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        var x;
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });
x=val[0];
for (v=1;v<val.length;v++){
x=x+','+ val[v];
}
        if(val.length > 1){
            if(confirm('هل تريد بالتأكيد دمج '+val.length+' سجلات ودمج تفاصيلها ؟!!')){
/////////////////
                    get_data('{$merge_url}',{id:x },function(data){
                    if(Number(data)>0 )
                          console.log(data);
                          setTimeout(function(){

                           get_to_link('{$get_url}/'+data+'/edit');

                             }, 1000);

                    },'html');
//////////////////


            }
        }else
            alert('يجب تحديد السجلات المراد تحويلها');
    }
</script>
SCRIPT;
sec_scripts($scripts);
?>
