<?php if (!$is_admin){
    redirect('biunit/Da3em/index');
}?>



<h3 class="mt-4">إعدادات منصة داعم لذكاء الأعمال</h3>

<a href="" data-bs-toggle="modal" data-bs-target="#catInsertModal" class="btn btn-outline-primary" style="float: left; margin-bottom: 5px; font-size: 16px; font-weight: bold">مجلد جديد</a>
<div class="modal fade" id="catInsertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">إضافة مجلد جديد</h5>
            </div>
            <div class="modal-body">
                <form name="myForm" method="post" action="<?=base_url('biunit/Category/create') ?>">
                    <div class="mb-3" style="text-align: right">
                        <label for="recipient-name" class="col-form-label">    العنوان:</label>
                        <input type="text" id="name" class="form-control" name="cat_title" value="" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">إضافة</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
            </form>
        </div>
    </div>
</div>
<br>


    <!-- Show All Categories -->
<?php foreach ($categories as $key=>$row ){ ?>

    <table class="table  table-striped" id="myTable" style="padding: 0px 10px; font-family: Tajawal, Sans-Serif;">
        <thead style="background-color: rgba(189,194,213,0.93);">
        <td><?= $row['TITLE'] ?></td>
        <td>
            <!-- Add Dashboard Option -->
            <a style="margin-right: 5px;"  href="<?= base_url('Biunit/add_dashboard?id='.$row['ID'])?>"  ><i style="font-size: 18px; color: rgba(12,12,12,0.37);margin: 1px 0px 0px 7px;" class="icon icon-plus btn-outline-light ic-add-bg"></i></a>
            <!-- Update Category Option -->
            <a style="margin-right: 5px;"  href="" data-bs-toggle="modal" data-bs-target="<?= '#updateModal'.$row['ID'] ?>" data-bs-id="<?= '#updateModal'.$row['ID'] ?>" ><i style="font-size: 18px; color: rgba(12,12,12,0.37);margin: 1px 0px 0px 7px;" class="icon icon-edit btn-outline-light ic-edit-bg"></i></a>
            <!-- Delete Category Option -->
            <a style="margin-left: 5px"  href="" data-bs-toggle="modal" data-bs-target="<?= '#catDeleteModal'.$row['ID'] ?>"><i style="font-size: 18px; color: rgba(12,12,12,0.37);" class="icon icon-trash-o btn-outline-light ic-delete-bg"></i></a>
            <div class="modal fade" id="<?= 'catDeleteModal'.$row['ID'] ?>" tabindex="-1" aria-labelledby="catDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="catDeleteModalLabel">تنبيه</h5>
                        </div>
                        <div class="modal-body">
                            <?= 'هل تريد حذف المجلد "'.$row['TITLE'].'"' ?>
                        </div>
                        <div class="modal-footer">
                            <a href="<?= base_url('biunit/Category/delete?id='.$row['ID'])?>" class="btn btn-primary">تأكيد</a>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="<?= 'updateModal'.$row['ID'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">تعديل المجلد</h5>
                        </div>
                        <div class="modal-body">
                            <form name="myForm" method="post" action="<?=base_url('biunit/Category/update') ?>">
                                <div class="mb-3" style="text-align: right">
                                    <label for="recipient-name" class="col-form-label">    العنوان:</label>
                                    <input type="text" id="name" class="form-control" name="cat_title" value="<?= $row['TITLE']?>" required>
                                    <input type="hidden" id="id" name="id" value="<?= $row['ID']?>">
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">تعديل</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>


        </td>
        </thead>
        <tbody>
        <?php foreach ($dashboards as $key2=>$row2 ){
            if($row2['CATEGORY_ID'] ==$row['ID']){ ?>
                <tr>
                    <td style="width: 92%"><?= $row2['TITLE'] ?></td>
                    <td>
                        <!-- Update Category Option -->
                        <a style="margin-right: 5px;"  href="<?=base_url('Biunit/edit_dashboard?id='.$row2['ID'] ) ?>" " ><i style="font-size: 18px; color: rgba(12,12,12,0.37);margin: 1px 0px 0px 7px;" class="icon icon-edit btn-outline-light ic-edit-bg"></i></a>
                        <!-- Delete Category Option -->
                        <a style="margin-left: 5px"  href="" data-bs-toggle="modal" data-bs-target="<?= '#dashboardDeleteModal'.$row2['ID'] ?>"><i style="font-size: 18px; color: rgba(12,12,12,0.37);" class="icon icon-trash-o btn-outline-light ic-delete-bg"></i></a>
                    </td>
                </tr>
                <div class="modal fade" id="<?= 'dashboardDeleteModal'.$row2['ID'] ?>" tabindex="-1" aria-labelledby="dashboardDeleteModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dashboardDeleteModal">تنبيه</h5>
                            </div>
                            <div class="modal-body">
                                <?= 'هل تريد حذف اللوحة  "'.$row2['TITLE'].'"' ?>
                            </div>
                            <div class="modal-footer">
                                <a href="<?= base_url('biunit/Dashboard/delete?id='.$row2['ID'])?>" class="btn btn-primary">تأكيد</a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php    }
        }?>

        </tbody>
    </table>
    <br>
<?php }?>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>