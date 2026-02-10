<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 08/04/18
 * Time: 09:15 ص
 */
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <ul>

            <li><a onclick="javascript:category_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد
                </a></li>

            <li><a onclick="javascript:category_get($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i
                        class="glyphicon glyphicon-edit"></i>تحرير</a></li>

            </li>
            <li><a onclick="javascript:category_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a>
            </li>
            <li><a onclick="$.fn.tree.expandAll()" href="javascript:;"><i
                        class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a></li>
            <li><a onclick="$.fn.tree.collapseAll()" href="javascript:;"><i
                        class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a></li>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-2">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>
        <?= $tree ?>

    </div>

</div>


<div class="modal fade" id="categoriesModal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات التصنيف</h4>
            </div>
            <form class="form-horizontal" id="category_from" method="post"
                  action="<?= base_url('tasks/categories/actions') ?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <input name="action" id="action" value="create" type="hidden">
                        <label class="col-sm-2 control-label"> التصنيف الرئيسي</label>

                        <div class="col-sm-2">
                            <input type="text" name="parent_cat" readonly id="parent_cat"
                                   class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="parent_cat_name" readonly id="parent_cat_name"
                                   class="form-control" "="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">التصنيف</label>

                        <div class="col-sm-2">
                            <input type="text" name="cat_no" readonly id="cat_no"
                                   class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="cat_name"   id="cat_name"
                                   class="form-control" "="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">النقاط</label>

                        <div class="col-sm-2">
                            <input type="text" name="cat_points"   id="cat_points"
                                   class="form-control ltr" "="">
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">فعال</label>

                        <div class="col-sm-2" >

                            <select id="status" name="status" class="form-control">
                                <option value="1">نعم</option>
                                <option value="0">لا</option>
                            </select>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->