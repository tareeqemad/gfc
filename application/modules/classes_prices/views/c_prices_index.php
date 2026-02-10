<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 15/02/20
 * Time: 10:00 ص
 */

$MODULE_NAME= 'classes_prices';
$TB_NAME= 'c_prices';
$get_url =base_url("$MODULE_NAME/classes_prices/get");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$select_items_url = base_url("stores/classes/public_index");
$get_class_url = base_url('stores/classes/public_get_id');
$select_orders_url = base_url("purchases/orders/public_index");
$orders_details_url = base_url('purchases/orders/public_get_order_receipt_details');
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الصنف </label>
                    <div>  <input type="hidden" id="h_data_search"/>
                        <input type="text"   name="h_class_id"  id="h_class_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">اسم الصنف</label>
                    <div>
                        <input name="class_id" readonly data-val="true"   class="form-control"  id="class_id"    >
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">من تاريخ حركة</label>
                    <div>
                        <input  data-val-regex-pattern="<?= date_format_exp() ?>" data-type="date"  data-date-format="DD/MM/YYYY" name="from_price_date" data-val="true"  class="form-control"  id="txt_from_price_date"    >
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">إلى تاريخ حركة</label>
                    <div>
                        <input  data-val-regex-pattern="<?= date_format_exp() ?>" data-type="date"  data-date-format="DD/MM/YYYY" name="to_price_date" data-val="true"  class="form-control"  id="txt_to_price_date"    >
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> نوع التسعير</label>
                    <div >
                        <select  name="type" id="dp_type"  data-curr="false"  class="form-control">
                            <option value="-1">...</option>
                            <?php foreach($types as $row) :?>
                                <option   value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> أمر التوريد </label>
                    <div>
                        <input data-type="text" name="order_id" data-val="true"  class="form-control"  id="order_id"    >
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> مقارنة سعر الشراء بالسعر المتوسط  </label>
                    <div>
                        <input type="text" value="سعر الشراء"  disabled class="form-control col-sm-1">
                    </div>

                    <div>
                        <select id="buy_price_op" name="buy_price_op" class="form-control col-sm-1">
                            <option value="-1">...</option>
                            <?php foreach($compares as $row) :?>
                                <option   value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <div>
                        <input type="text" value=" السعر المتوسط"  disabled class="form-control col-sm-1">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> مقارنة سعر البيع بالسعر المتوسط  </label>
                    <div>
                        <input type="text" value="سعر البيع"  disabled class="form-control col-sm-1">
                    </div>

                    <div>
                        <select id="sell_price_op" name="sell_price_op" class="form-control col-sm-1" >
                            <option value="-1">...</option>
                            <?php foreach($compares as $row) :?>
                                <option   value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <div>
                        <input type="text" value=" السعر المتوسط"  disabled class="form-control col-sm-1">
                    </div>
                </div>
            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <div class="btn-group">
                <button class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                <ul class="dropdown-menu " role="menu">
                    <li><a href="#" onclick="$('#c_prices_tb').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                    <li><a href="#" onclick="$('#c_prices_tb').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                </ul>
            </div>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>
        <div id="msg_container"></div>

        <div id="container">

            <?=modules::run($get_page_url,$page,$class_id ,$from_price_date,$to_price_date,$type,$order_id,$buy_price_op,$sell_price_op);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>
  $('input[name="class_id"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_items_url/'+$(this).attr('id')+'/1'+ $('#h_data_search').val() );
    });
$('input[name="h_class_id"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_items_url/'+$('#class_id').attr('id')+'/1'+ $('#h_data_search').val() );
    });
       
/* $('input[name="h_class_id"]').change(function(e){
       var id_v=$(this).val();
 
         get_data('{$get_class_url}',{id:id_v, type:1},function(data){
              /////
                if (data.length == 1){

                    var item= data[0];
                     if(item.CLASS_STATUS==1){
                     $('#class_id').val(item.CLASS_NAME_AR);

                 }else{
                    $('#h_class_id').val('');
                    $('#class_id').val('');
                 }
              } else{ $('#h_class_id').val('');
                    $('#class_id').val('');}
         });
  });

     */ 

$('input[name="order_id"]').bind('keyup', '+', function(e) {
        $(this).val( $(this).val().replace('+', ''));
          _showReport('$select_orders_url' );
        });



    $('.pagination li').click(function(e){
        e.preventDefault();
    });
 
    
   function show_row_details(id){

        get_to_link('{$get_url}/'+id);

    }

   function search(){ 
        get_data('{$get_page_url}',{page:1,class_id:$('#h_class_id').val(), from_price_date:$('#txt_from_price_date').val(), to_price_date:$('#txt_to_price_date').val(),type:$('#dp_type').val(), order_id:$('#order_id').val(), buy_price_op:$('#buy_price_op').val(), sell_price_op:$('#sell_price_op').val()},function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
       ajax_pager_data('#c_prices_tb > tbody',{class_id:$('#h_class_id').val(), from_price_date:$('#txt_from_price_date').val(), to_price_date:$('#txt_to_price_date').val(),type:$('#dp_type').val(), order_id:$('#order_id').val(), buy_price_op:$('#buy_price_op').val(), sell_price_op:$('#sell_price_op').val()});
    }



    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));

    }
    
    function after_selected_order(order_id){ 
              
               $('#order_id').val(order_id);
                $('#report').modal('hide');
}

</script>
SCRIPT;
sec_scripts($scripts);
?>
