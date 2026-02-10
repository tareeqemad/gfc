<?php
$MODULE_NAME = 'contracts_services';
$TB_NAME = 'Gcc_tree_structure';

$create_gcc_url = base_url("$MODULE_NAME/$TB_NAME/create");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create_contract");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <?php if (HaveAccess($create_url)): ?>
                <li><a onclick="javascript:contract_create();" href="javascript:;"><i
                                class="glyphicon glyphicon-plus"></i>جديد
                    </a>
                </li>
            <?php endif; ?>
            <?php if (HaveAccess($get_url, $edit_url)): ?>
                <li>
                    <a onclick="javascript:contract_get($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i
                                class="glyphicon glyphicon-edit"></i>تحرير</a>
                </li>
            <?php endif; ?>
            <?php if (HaveAccess($delete_url)): ?>
                <li>
                    <a onclick="javascript:contract_delete();" href="javascript:;"><i
                                class="glyphicon glyphicon-remove"></i>حذف</a>
                </li>
            <?php endif; ?>

            <?php if (HaveAccess($create_gcc_url)): ?>
                <li>
                    <a onclick="javascript:gcc_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>اضافة
                        إدارة</a>
                </li>
            <?php endif; ?>
            <li>
                <a onclick="$.fn.tree.expandAll()" href="javascript:;"><i
                            class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a>
            </li>
            <li>
                <a onclick="$.fn.tree.collapseAll()" href="javascript:;"><i
                            class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a>
            </li>
            <li>
                <a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i>
                </a>
            </li>

        </ul>
    </div>
    <div class="form-body">
        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-3">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="البحث عن التعاقدات">
            </div>
        </div>
        <?= $tree ?>
    </div>
</div>
<?= $this->load->view('contract_model') ?>

<?php
$scripts = <<<SCRIPT
<script>
  $(function () {
        $('#gcc_tree_structure').tree();
        $('#dp_gcc_no').select2();
    });
  
  
  
      function validate(){
    if ($('#txt_contract_name').val()==''){
    danger_msg('يجب ادخال اسم التعاقد ') ();
    return true;}
    else {
    return false ; }   
    }
  
    

        //اضافة ادارة //
      function gcc_create(){
       $('#dp_gcc_no').select2('val','');
        clearForm( $('#gcc_form')  );
        if($(".tree span.selected").length <= 0){ // $.fn.tree.level() != 2
            warning_msg('تحذير ! ','يجب إختيار الأب ..');
            return;
        }

        var parentId =$.fn.tree.selected().attr('data-id');
        var parentName = $('.tree li > span.selected').text();

          $('#txt_gcc_parent_id').val(parentId);
          $('#txt_gcc_name').val(parentName);

        $('#gcc_form').attr('action','{$create_gcc_url}');
        $('#gcc_add_modal').modal();
    }
    
      $('#gcc_form button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if( $('#dp_gcc_no').select2('val') == '' )
            return 0;

        var form = $(this).closest('form');
        var isCreate = form.attr('action').indexOf('create') >= 0;
        var gcc_text = $('#dp_gcc_no').select2('data').text;

        ajax_insert_update(form,function(data){
                console.log(data);
                 if(isCreate){
                var obj = jQuery.parseJSON(data);
                $.fn.tree.add( ( gcc_text ),obj.id,"javascript:contract_get('"+obj.id+"');");
                $('span[data-id="'+obj.id+'"]').attr('data-id',obj.id);
            }else{
                if(data == '1'){
                    $.fn.tree.update(form.find('select[name="gcc_st_no"]').val());
                }
            }
            $('#gcc_add_modal').modal('hide');
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");
    });
  
      
         function contract_create(){

            clearForm($('#contract_form'));
    
            if($(".tree span.selected").length <= 0 || $.fn.tree.level() >= 5 ){
    
                if(($.fn.tree.level() >= 8) ) {
                     warning_msg('تحذير','غير مسموح بإدراج تعاقد جديد');
    
                }else{
    
                    warning_msg('تحذير','غير مسموح بإدراج تعاقد جديد');
                }
    
                return;
            }


        var parentId =$.fn.tree.selected().attr('data-id');
        var productionId= $.fn.tree.lastElem().attr('data-id');
        $('#txt_gcc_id').val(structure_id(productionId,parentId));

      
        var gcc_no =$.fn.tree.selected().attr('data-gcc-no');
       
        var parentName = $('.tree li > span.selected').text();

        $('#txt_gcc_con_parent_id').val(parentId);
         $('#txt_st_id').val(structure_id(productionId,parentId)+1);

        $('#txt_contract_parent_name').val(parentName);


        $('#contract_form').attr('action','{$create_url}');
        $('#contract_add_modal').modal();

    }
    
      $('#contract_form button[data-action="submit"]').click(function(e){

        e.preventDefault();

        if(validate())
        return;

        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){

            if(isCreate){

                var obj = data;

                $.fn.tree.add(data+' : '+form.find('input[name="contract_name"]').val(),data,"javascript:contract_get('"+data+"');");

            }else{
                
             if(data == 1){
                $.fn.tree.update($.fn.tree.selected().attr('data-id')+' : '+form.find('input[name="contract_name"]').val());
             }}

            $('#contract_add_modal').modal('hide');

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");

    });

    
        
     function contract_delete(){

        if(confirm('هل تريد حذف التعاقد؟')){
            var elem =$.fn.tree.selected();
            var id = elem.attr('data-id');
            var url = '{$delete_url}';

            ajax_delete(url,id,function(data){
                if(data == '1'){

                    $.fn.tree.removeElem(elem);
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                }else {
                  danger_msg('تحذير..',' رقم التعاقد المراد حذفه مستخدم  ... لم يتم الحذف ');
                }
            });
        }
    }
    
     function contract_get(id){

        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
               $('#txt_gcc_con_parent_id').val(item.GCC_PARENT_ID);
               $('#txt_gcc_id').val( item.GCC_ID);
               $('#txt_contract_name').val(item.CONTRACT_NAME);
               $('#txt_contract_parent_name').val($.fn.tree.nodeText(item.GCC_PARENT_ID));
               $('#dp_contract_type').val(item.CONTRACT_TYPE);
               $('#txt_contract_detail').val(item.CONTRACT_DETAIL);
                $('#contract_form').attr('action','{$edit_url}');
            $('#contract_add_modal').modal();

            });
        });
    }
</script>
SCRIPT;
sec_scripts($scripts);
?>






















