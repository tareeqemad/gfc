<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 08/04/18
 * Time: 09:43 ص
 */
?>

//<script>

    $(function () {

        $('#categoriesModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        })

        $('#categories').tree();
    });


    function category_create() {


        clearForm($('#category_from'));
        if ($(".tree span.selected").length <= 0 || $.fn.tree.level() >= 3) {

            if ($.fn.tree.level() >= 3) {

                warning_msg('تحذير ! ', 'غير مسموح بإدراج حساب جديد ..');
            } else {
                warning_msg('تحذير ! ', 'يجب إختيار الحساب الأب ..');
            }


            // return;
        }

        var parentId = $.fn.tree.selected().attr('data-id');
        var productionId = $.fn.tree.lastElem().attr('data-id');
        var parentName = $('.tree li > span.selected').text();

        $('#parent_cat').val(parentId);
        $('#cat_no').val(0);
        $('#parent_cat_name').val(parentName);


        $('#action').val('create');
        $('#categoriesModal').modal();

    }

    function category_delete() {

        if (confirm('هل تريد حذف الحساب ؟!!!')) {
            var elem = $.fn.tree.selected();
            var id = elem.attr('data-id');
            var url = '<?= base_url('tasks/categories/actions') ?>';

            ajax_delete_any(url, {id:id,action:'delete'}, function (data) {
                if (data == '1') {

                    $.fn.tree.removeElem(elem);
                    success_msg('رسالة', 'تم حذف السجلات بنجاح ..');
                } else {
                    danger_msg('تحذير', 'فشل في حفظ الحساب , قد يكون عليه حركات ..');
                }
            });
        }

    }

    function category_get(id) {

        get_data('<?= base_url('tasks/categories/public_get') ?>', {id: id}, function (data) {

            $.each(data, function (i, item) {
                $('#parent_cat').val(item.PARENT_CAT);
                $('#cat_no').val(item.CAT_NO);
                $('#parent_cat_name').val(item.PARENT_CAT_NAME);
                $('#cat_name').val(item.CAT_NAME);
                $('#cat_points').val(item.CAT_POINTS);
                $('#status').val(item.STATUS);

                $('#action').val('update')

                $('#categoriesModal').modal();

            });
        });
    }


    $('button[data-action="submit"]').click(function (e) {

        e.preventDefault();

        var form = $(this).closest('form');

        var isCreate =  $('#action').val().indexOf('create') >= 0;

        ajax_insert_update(form, function (data) {

            if (isCreate) {

                $.fn.tree.add( (form.find('input[name="cat_name"]').val()),data, "javascript:category_get('" + data + "');");

            } else {
                if (data == '1') {
                    $.fn.tree.update(form.find('input[name="cat_name"]').val());
                }
            }

            $('#categoriesModal').modal('hide');

            success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');
        }, "html");

    });


    //</script>