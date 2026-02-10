

<h3 class="mt-4">صلاحيات الآدمن</h3><br>
<!--
<a href="" data-bs-toggle="modal" data-bs-target="#adminInsertModal" class="btn btn-outline-primary" style="float: left; margin-bottom: 5px;font-size: 16px; font-weight: bold">جديد</a>

-->
<!-- ADD NEW admin model -->
<div class="modal fade" id="adminInsertModal" tabindex="-1" aria-labelledby="adminInsertModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="font-family: Tajawal, Sans-Serif;">
            <div class="modal-header">
                <h5 class="modal-title" id="indicatorInsertModal" style="color: #05235b">إضافة آدمن جديد</h5>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url('biunit/Admin/create')?>">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="form-label">اسم المستخدم</label>
                        <select name="users[]" class="form-control js-select2" multiple required>
                            <?php foreach($users as $row){ ?>
                                <option value="<?=$row['USER_ID'] ?>" data-badge=""><?=$row['USER_NAME'] ?></option>
                            <?php } ?>
                        </select>
                    </div><br>

                    <div class="form-group">
                        <label for="exampleFormControlSelect1" class="form-label">الحالة</label>
                        <select class="form-control" name="act" id="exampleFormControlSelect1">
                            <option value="1"> فعال</option>
                            <option value="0">غير فعال</option>
                        </select>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">إضافة</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
            </form>
        </div>
    </div>
</div>


<table class="table  table-striped" id="myTable" style="padding: 0px 10px; font-family: Tajawal, Sans-Serif;">
    <thead style="background-color: rgba(189,194,213,0.93);">
    <td>المستخدم</td>
    <td>الحالة</td>
    <td>الاجراءات</td>
    </thead>
    <tbody>
    <?php foreach ($admins as $key=>$row ){ ?>
        <tr>
            <td> <?= $row['FULLNAME'] ?> </td>
            <td> <?php if($row['STATUS']==1){ ?>
                    <a  class="btn btn-success" style="font-size: 12px;">فعال</a>
                <?php } else{?>
                    <a  class="btn btn-danger" style="font-size: 12px;">غير فعال</a>
                <?php } ?> </td>
            <td>
                <!-- Update Category Option -->
                <a style="margin-right: 5px;"  href="" data-bs-toggle="modal" data-bs-target="<?= '#adminUpdateModal'.$row['USERNAME'] ?>" ><i style="font-size: 18px; color: rgba(12,12,12,0.37);margin: 1px 0px 0px 7px;" class="icon icon-edit btn-outline-light ic-edit-bg"></i></a>
                <!-- Delete Category Option -->
                <a style="margin-left: 5px"  href="" data-bs-toggle="modal" data-bs-target="<?= '#adminDeleteModal'.$row['USERNAME'] ?>"><i style="font-size: 18px; color: rgba(12,12,12,0.37);" class="icon icon-trash-o btn-outline-light ic-delete-bg"></i></a>
            </td>

            <!--  Update Indicator model -->
            <div class="modal fade" id="<?= 'adminUpdateModal'.$row['USERNAME']  ?>" tabindex="-1" aria-labelledby="adminUpdateModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="font-family: Tajawal, Sans-Serif;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adminUpdateModal" style="color: #05235b">تحديث بيانات الآدمن</h5>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="<?= base_url('biunit/Admin/update')?>">
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">اسم الآدمن</label>
                                    <input type="input" class="form-control" name="" value="<?= $row['FULLNAME'] ?>" aria-describedby="emailHelp" placeholder="Enter title" disabled>
                                </div><br>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="form-label">الحالة</label>
                                    <select class="form-control" name="status" id="exampleFormControlSelect1">
                                        <option value="1" <?= ($row['STATUS']==1)? 'SELECTED':'' ?> > فعال</option>
                                        <option value="0" <?= ($row['STATUS']==0)? 'SELECTED':'' ?> >غير فعال</option>
                                    </select>
                                </div>
                                <input type="hidden" name="uname" value="<?= $row['USERNAME'] ?>">


                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">تحديث</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Delete Indicator model -->
            <div class="modal fade" id="<?= 'adminDeleteModal'.$row['USERNAME'] ?>" tabindex="-1" aria-labelledby="adminDeleteModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adminDeleteModal">تنبيه</h5>
                        </div>
                        <div class="modal-body">
                            <?= 'هل تريد حذف الآدمن  "'.$row['FULLNAME'].'"' ?>
                        </div>
                        <div class="modal-footer">
                            <a href="<?= base_url('biunit/Admin/delete?username='.$row['USERNAME'])?>" class="btn btn-primary">تأكيد</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>

        </tr>
    <?php } ?>
    </tbody>
</table>

