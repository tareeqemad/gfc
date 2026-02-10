<?php
$MODULE_NAME = 'hr';
$TABLE_NAME = 'Archive_scan';
$get_url = base_url("$MODULE_NAME/$TABLE_NAME/public_get_scan_type");
$post_url = base_url("$MODULE_NAME/$TABLE_NAME/create_scan_type");
$edit_url = base_url("$MODULE_NAME/$TABLE_NAME/update_scan_type");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('cpanel'); ?>">النظام الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- ROW OPEN -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $title ?>
                </h3>
                <div class="card-options">
                    <?php if (HaveAccess($post_url)) { ?>
                        <button type="button" onclick="javascript:create_type()" class="btn btn-primary me-2">
                            <i class="fa fa-plus"></i>
                            اضافة
                        </button>
                    <?php } ?>

                    <?php if (HaveAccess($edit_url)) { ?>
                        <button type="button" onclick="javascript:get_type()" class="btn btn-warning me-2">
                            <i class="fa fa-edit" style="color: #fff;"></i>
                            تحرير
                        </button>
                    <?php } ?>

                    <button type="button" class="btn btn-success me-2" onclick="$.fn.tree.expandAll()"
                            href="javascript:;">
                        <i class="mdi mdi-arrow-expand"></i>توسيع
                    </button>
                    <button type="button" class="btn btn-success me-2" onclick="$.fn.tree.collapseAll()"
                            href="javascript:;"><i class="fa fa-arrows-alt"></i>
                        طي
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="input-group col-sm-2">
                        <input type="text" id="search-tree" class="form-control" placeholder="بحث">
                    </div>
                </div>
                <?= $tree ?>
            </div>
        </div>
    </div>
</div>

<!--Start Create Modal -->
<div class="modal fade create_modal" id="create_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> اضافة تصنيف جديد</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="example">
                    <!--begin::Form-->
                    <form class="form-horizontal" id="create_tree_form" method="post" action="<?= $post_url ?>"
                          role="form"
                          novalidate="novalidate">
                        <div class="row">
                            <input class="form-control" type="hidden" name="parent_no" id="h_parent_no" readonly/>
                            <input class="form-control" type="hidden" name="level_no" id="h_level_no" readonly/>
                            <div class="form-group col-md-2">
                                <label>رمز الاب</label>
                                <input type="text" class="form-control" id="txt_type_code_parent" readonly/>
                            </div>
                            <div class="form-group col-md-2">
                                <label>التصنيف الاب</label>
                                <input type="text" class="form-control" id="txt_parent_title" readonly/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>التصنيف</label>
                                <input type="text" class="form-control" name="type_name" id="txt_type_name"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" data-action="submit" class="btn btn-primary me-2">حفظ البيانات
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Create Modal -->

<!--Start Edit Modal -->
<div class="modal fade edit_modal" id="edit_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل بيانات</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="example">
                    <!--begin::Form-->
                    <form class="form-horizontal" id="edit_tree_form" method="post" action="<?= $edit_url ?>"
                          role="form"
                          novalidate="novalidate">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>رقم الاب</label>
                                <input type="text" class="form-control" id="txt_type_no_up" name="type_no_up" readonly/>
                            </div>
                            <div class="form-group col-md-2">
                                <label>رمز التصنيف</label>
                                <input type="text" class="form-control" id="txt_type_code_up" name="type_code_up"
                                       readonly/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>التصنيف</label>
                                <input type="text" class="form-control" id="txt_type_name_up" name="type_name_up"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Edit Modal -->


<?php
$scripts = <<<SCRIPT
<script>

  $(function () {
     $('#Tree_Type').tree();
         $('.checkboxes:first').hide();
    });
  
  
  function create_type(){
        if($(".tree span.selected").length <= 0){ // $.fn.tree.level() != 2
            warning_msg('تحذير ! ','يجب إختيار الأب ..');
            return;
        }
        var form =  $('#create_tree_form'); 
        clearForm(form);
        var parentId = $.fn.tree.selected().attr('data-id');
        var task_title = $.fn.tree.selected().attr('data-title');
        var code_parent = $.fn.tree.selected().attr('data-codetype');
        form.attr('action','{$post_url}');
        $('#create_tree_form #h_parent_no').val(parentId);
        $('#create_tree_form #txt_parent_title').val(task_title);
        $('#create_tree_form #txt_type_code_parent').val(code_parent);
        $('#create_tree_form #h_level_no').val($.fn.tree.level());
        $('.create_modal').modal('show');
  }
  
    function get_type(){
        
        var form =  $('#edit_tree_form'); 
        clearForm(form);
        var elem =$.fn.tree.selected();
        var type_no = elem.attr('data-id'); //ser master to update
        $('.edit_modal').modal('show');
        get_data('{$get_url}',{type_no:type_no},function(item){
                $('#edit_tree_form').attr('action','{$edit_url}');
                $('#edit_tree_form #txt_type_no_up').val(item.TYPE_NO);
                $('#edit_tree_form #txt_type_code_up').val(item.TYPE_CODE);
                $('#edit_tree_form #txt_type_name_up').val(item.TYPE_NAME);
        });
    }
   
  $('#create_tree_form  button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        var isCreate = form.attr('action').indexOf('create') >= 0;
        ajax_insert_update(form,function(data){
            var obj = jQuery.parseJSON(data);
            console.log(obj);
            if(parseInt(obj.msg) >= 1){
                $.fn.tree.add(obj.type_code+' : '+form.find('input[name="type_name"]').val(),data,"javascript:get_type('"+obj.id+"');");
                $('span[data-id="'+obj.id+'"]').attr('data-no',obj.id);
                $('#create_modal').modal('hide');
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            }else{
                danger_msg(data);
                return -1;
            } 
        },"json");
    });
  
  
    $('#edit_tree_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form =  $('#edit_tree_form'); 
           ajax_insert_update(form,function(data){
               if(parseInt(data) >= 1){
                       var type_name = form.find('input[name="type_name_up"]').val();
                       $.fn.tree.update(type_name);
                       $('.edit_modal').modal('hide');
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                }
            },"json");
    });
  
   
  
</script>
SCRIPT;
sec_scripts($scripts);
?>
