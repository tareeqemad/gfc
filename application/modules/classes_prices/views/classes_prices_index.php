<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 15/02/20
 * Time: 10:00 ص
 */

$MODULE_NAME= 'classes_prices';
$TB_NAME= 'classes_prices';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$select_items_url = base_url("stores/classes/public_index");
$get_class_url = base_url('stores/classes/public_get_id');

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if( HaveAccess($delete_url)):  ?><li><a  onclick="javascript:ddelete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم  مسلسل </label>
                    <div>
                        <input type="text" name="ser"  id="txt_ser" class="form-control" />

                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الصنف </label>
                    <div>  <input type="hidden" id="h_data_search"/>
                        <input type="text"   name="h_class_id"  id="h_class_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">اسم الصنف</label>
                    <div>
                        <input name="class_id" data-val="true"   class="form-control"  id="class_id"    >
                        <input type="hidden" name="type" value="<?=$type?>"  id="txt_type">
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ السعر</label>
                    <div>
                        <input data-val-regex-pattern="<?= date_format_exp() ?>" data-type="date"  data-date-format="DD/MM/YYYY" name="price_date" data-val="true"  class="form-control"  id="txt_price_date"    >
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">حالة الاعتماد</label>
                    <div >
                        <select  name="adopt" id="dp_adopt"  data-curr="false"  class="form-control">
                            <option value="-1">...</option>
                            <?php foreach($adopts as $row) :?>
                                <option   value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <div class="btn-group">
                <button class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                <ul class="dropdown-menu " role="menu">
                    <li><a href="#" onclick="$('#classes_prices_tb').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                    <li><a href="#" onclick="$('#classes_prices_tb').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                </ul>
            </div>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>
        <div id="msg_container"></div>

        <div id="container">

            <?=modules::run($get_page_url,$page, $ser,$type,$price_date,$class_id,$adopt);?>
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
       
 /*$('input[name="h_class_id"]').change(function(e){
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
      






    $('.pagination li').click(function(e){
        e.preventDefault();
    });
 
   function show_row_details(id){

        get_to_link('{$get_url}/'+id);

    }

   function search(){
        get_data('{$get_page_url}',{page:1, ser:$('#txt_ser').val(), type:$('#txt_type').val(),price_date:$('#txt_price_date').val(), class_id:$('#h_class_id').val(), adopt:$('#dp_adopt').val()},function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
       ajax_pager_data('#classes_prices_tb > tbody',{ser:$('#txt_ser').val(), type:$('#txt_type').val(),price_date:$('#txt_price_date').val(), class_id:$('#h_class_id').val(), adopt:$('#dp_adopt').val()});
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
            if(confirm('هل تريد بالتأكيد حذف '+val.length+' سجلات وحذف تفاصيلها ؟!!')){
                ajax_delete(url, val ,function(data){
                    console.log(data);
                   search();
                  // windows.location.reload();
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                    //container.html(data);
                   
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }
</script>
SCRIPT;
sec_scripts($scripts);
?>
