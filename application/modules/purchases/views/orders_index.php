<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 04/03/15
 * Time: 01:27 م
 */

$MODULE_NAME= 'purchases';
$TB_NAME= 'Orders';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create?order_purpose=".$order_purpose);
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$customer_url =base_url('payment/customers/public_index');
$select_order_url = base_url("$MODULE_NAME/purchase_order/public_index");
$adopt5_url=base_url("$MODULE_NAME/$TB_NAME/adopt5_$order_purpose");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if( HaveAccess($delete_url)):  ?><li><a  onclick="javascript:ddelete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>إلغاء </a> </li><?php endif; ?>
            <?php if   (HaveAccess($adopt5_url))  : ?>
                <li><a  onclick="javascript:adopt5();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>اعتماد المدير العام </a> </li>
                    <?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم أمر التوريد</label>
                    <div>
                        <input type="text" name="order_id"  id="txt_order_id" class="form-control" />

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> مسلسل التوريد </label>
                    <div>
                        <input type="text"  name="order_text_t"  id="txt_order_text_t" class="form-control" />

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم طلب الشراء</label>
                    <div>
                        <input type="text"   name="purchase_order_id"  id="txt_purchase_order_id" class="form-control" />
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> عملة عرض السعر  </label>
                    <div >
                        <select  name="curr_id" id="dp_curr_id"  data-curr="false"  class="form-control">
                            <option value="-1">...</option>
                            <?php foreach($currency as $row) :?>
                                <option  data-val="<?= $row['VAL'] ?>"  value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label class="control-label">المورد</label>
                    <div>
                        <input name="customer_name" data-val="true" readonly  class="form-control"  id="txt_customer_name"    >
                        <input type="hidden" name="customer_id"  id="h_txt_customer_name">
                        <input type="hidden" name="order_purpose" value="<?=$order_purpose?>"  id="dp_order_purpose">
                    </div></div>


                <div class="form-group col-sm-1">
                    <label class="control-label">  عملة المورد  </label>
                    <div >
                        <select  name="customer_curr_id" id="dp_customer_curr_id"  data-curr="false"  class="form-control">
                            <option value="-1">...</option>
                            <?php foreach($currency as $row) :?>
                                <option  data-val="<?= $row['VAL'] ?>"  value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> حالة الاعتماد  </label>
                    <div >
                        <select  name="adopt" id="txt_adopt"  data-curr="false"  class="form-control">
                            <option value="-1">...</option>
                            <?php foreach($adopts as $row) :?>
                                <option   value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
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
            <button class="btn btn-warning dropdown-toggle" onclick="$('#Orders_tb').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
        </div>
        <div id="msg_container"></div>

        <div id="container">

            <?=modules::run($get_page_url,$page, $order_id ,$purchase_order_id,$customer_id,$order_purpose,$curr_id,$customer_curr_id,$order_text_t,$adopt,$from_date,$to_date);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script> 
    $('#select-all').click(function(event) { 
	      var chk= false;
        if(this.checked) {
            chk= true;
        }
        $('.checkboxes:checkbox').each(function() {
            this.checked = chk;
        });
    });

  $('input[name="purchase_order_id"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_order_url/'+$(this).attr('id')+'?order_purpose=$order_purpose' );
    });

  $('#txt_customer_name').bind("focus",function(e){

          selectAccount(this);

        });

        $('#txt_customer_name').click(function(e){

             selectAccount(this);

        });

    $('.pagination li').click(function(e){
        e.preventDefault();
    });
    function selectAccount(obj){

                _showReport('$customer_url/'+$(obj).attr('id')+'/1');

    }

   function show_row_details(id){

        get_to_link('{$get_url}/'+id+'/'+{$order_purpose});

    }

   function search(){
        get_data('{$get_page_url}',{page:1, order_id:$('#txt_order_id').val(), purchase_order_id:$('#txt_purchase_order_id').val(),customer_id:$('#h_txt_customer_name').val(), order_purpose:$('#dp_order_purpose').val(), curr_id:$('#dp_curr_id').val(), customer_curr_id:$('#dp_customer_curr_id').val(),order_text_t:$('#txt_order_text_t').val(),adopt:$('#txt_adopt').val(),from_date:$('#from_date').val(),to_date:$('#to_date').val()},function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
       ajax_pager_data('#orders_tb > tbody',{order_id:$('#txt_order_id').val(), purchase_order_id:$('#txt_purchase_order_id').val(),customer_id:$('#h_txt_customer_name').val(), order_purpose:$('#dp_order_purpose').val(), curr_id:$('#dp_curr_id').val(), customer_curr_id:$('#dp_customer_curr_id').val(),order_text_t:$('#txt_order_text_t').val(),adopt:$('#txt_adopt').val(),from_date:$('#from_date').val(),to_date:$('#to_date').val()});
    }



    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));

    }
function ddelete(){
        var url = '{$delete_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد إلغاء '+val.length+' سجلات وإلغاء تفاصيلها ؟!!')){
                ajax_delete(url, val ,function(data){
                    success_msg('رسالة','تم إلغاء السجلات بنجاح ..');
                    search();
                });
            }
        }else
            alert('يجب تحديد السجلات المراد إلغاؤها');
    }
    
    function adopt5(){
        var url = '{$adopt5_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
          if(confirm('هل تريد بالتأكيد اعتماد أوامر التوريد  ؟!!')){
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
            get_data(url,{id:val[i]},function(data){
               
            },'html');
        });
        
         setTimeout(function(){
                   success_msg('رسالة','تمت العملية بنجاح');
                search();
                }, 1000);
        
          }else
            alert('يجب تحديد السجلات المراد إلغاؤها');
        
    }
</script>
SCRIPT;
sec_scripts($scripts);
?>
