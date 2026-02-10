

<h3 class="mt-4">المؤشرات الرئيسية</h3><br>
<a href="" data-bs-toggle="modal" data-bs-target="#indicatorInsertModal" class="btn btn-outline-primary" style="float: left; margin-bottom: 5px;font-size: 16px; font-weight: bold">جديد</a>


<!-- ‘Update Indicator model -->
<div class="modal fade" id="indicatorInsertModal" tabindex="-1" aria-labelledby="indicatorInsertModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="font-family: Tajawal, Sans-Serif;">
            <div class="modal-header">
                <h5 class="modal-title" id="indicatorInsertModal" style="color: #05235b">إضافة مؤشر جديد</h5>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="post" action="<?= base_url('biunit/Indicator/create')?>" novalidate>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="form-label">اسم المؤشر</label>
                        <input type="input" class="form-control" name="title" required>
                        <div class="invalid-feedback">
                            الحقل مطلوب
                        </div>
                    </div><br>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="form-label">القيمة</label>
                        <input type="input" class="form-control" name="val" required>
                        <div class="invalid-feedback">
                            الحقل مطلوب
                        </div>
                    </div><br>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1" class="form-label">الحالة</label>
                        <select class="form-control" name="act" id="exampleFormControlSelect1">
                            <option value="1"> فعال</option>
                            <option value="0">غير فعال</option>
                        </select>
                    </div>
<br>
                    <div class="form-group">
                        <label for="formFile" class="form-label">الأيقونة:</label><br>
                        <div class="btn-group" id="btnradio_icon" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio1" value="<?= 'icon-database'?>" autocomplete="off" checked>
                            <label class="btn btn-outline-light" for="btnradio1" style="border-top-left-radius: 0;border-bottom-left-radius: 0;"><i class="icon icon-database" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio2" value="<?= 'icon-pie-chart'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio2"><i class="icon icon-pie-chart" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio3" value="<?= 'icon-line-chart'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio3"><i class="icon icon-line-chart" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio4" value="<?= 'icon-list'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio4"><i class="icon icon-list" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio5" value="<?= 'icon-files-o'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio5"><i class="icon icon-files-o" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio6" value="<?= 'icon-stack-exchange'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio6"><i class="icon icon-stack-exchange" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio7" value="<?= 'icon-file-text-o'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio7"><i class="icon icon-file-text-o" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio8" value="<?= 'icon-hand-o-left'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio8"><i class="icon icon-hand-o-left" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio9" value="<?= 'icon-comments-o'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio9"><i class="icon icon-comments-o" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio10" value="<?= 'icon-list-ul'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio10"><i class="icon icon-list-ul" style="color: #0c0c0c;font-size: 17px;"></i></label>

                            <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio11" value="<?= 'icon-area-chart'?>" autocomplete="off">
                            <label class="btn btn-outline-light" for="btnradio11" style="border-top-right-radius: 0;border-bottom-right-radius: 0;"><i class="icon icon-area-chart" style="color: #0c0c0c;font-size: 17px;"></i></label>
                        </div>
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
    <td>المؤشر</td>
    <td>القيمة</td>
    <td>الحالة</td>
    <td>الاجراءات</td>
    </thead>
    <tbody>
    <?php foreach ($indicators as $key=>$row ){ ?>
        <tr>
            <td><?= $row['INDICATOR_TITLE'] ?></td>
            <td><?= $row['VALU'] ?></td>
            <td><?php if($row['ACTIVE']==1){ ?>
                <a  class="btn btn-success" style="font-size: 12px;">فعال</a>
                <?php } else{?>
                <a  class="btn btn-danger" style="font-size: 12px;">غير فعال</a>
                <?php } ?>

            </td>
            <td>
                <!-- Update Category Option -->
                <a style="margin-right: 5px;"  href="" data-bs-toggle="modal" data-bs-target="<?= '#indicatorUpdateModal'.$row['ID'] ?>" ><i style="font-size: 18px; color: rgba(12,12,12,0.37);margin: 1px 0px 0px 7px;" class="icon icon-edit btn-outline-light ic-edit-bg"></i></a>
                <!-- Delete Category Option -->
                <a style="margin-left: 5px"  href="" data-bs-toggle="modal" data-bs-target="<?= '#indicatorDeleteModal'.$row['ID'] ?>"><i style="font-size: 18px; color: rgba(12,12,12,0.37);" class="icon icon-trash-o btn-outline-light ic-delete-bg"></i></a>
            </td>

            <!-- ‘Update Indicator model -->
            <div class="modal fade" id="<?= 'indicatorUpdateModal'.$row['ID'] ?>" tabindex="-1" aria-labelledby="indicatorUpdateModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="font-family: Tajawal, Sans-Serif;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="indicatorUpdateModal" style="color: #05235b">تحديث بيانات المؤشر</h5>
                        </div>
                        <div class="modal-body">
                            <form class="needs-validation" method="post" action="<?= base_url('biunit/Indicator/update')?>" novalidate>
                                <div class="form-group">
                                    <label for="exampleInputEmail1" class="form-label">اسم المؤشر</label>
                                    <input type="input" class="form-control" name="title" value="<?= $row['INDICATOR_TITLE']?>" aria-describedby="emailHelp" required>
                                    <div class="invalid-feedback">
                                        الحقل مطلوب
                                    </div>
                                </div><br>
                                <div class="form-group">
                                    <label for="exampleInputPassword1" class="form-label">القيمة</label>
                                    <input type="input" class="form-control" name="val" value="<?= $row['VALU'] ?>" required>
                                    <div class="invalid-feedback">
                                        الحقل مطلوب
                                    </div>
                                </div><br>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="form-label">الحالة</label>
                                    <select class="form-control" name="act" id="exampleFormControlSelect1">
                                        <option value="1" <?= ($row['ACTIVE']==1)?'selected':'' ?> > فعال</option>
                                        <option value="0" <?= ($row['ACTIVE']==0)?'selected':'' ?> >غير فعال</option>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="formFil" class="form-label">الأيقونة:</label><br>
                                    <div class="btn-group" id="btn_icon" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON1'.$row['ID'] ?>" value="<?= 'icon-database'?>" autocomplete="off" <?= ($row['ICON']=='icon-database')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON1'.$row['ID'] ?>" style="border-top-left-radius: 0;border-bottom-left-radius: 0;"><i class="icon icon-database" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON2'.$row['ID'] ?>" value="<?= 'icon-pie-chart'?>" autocomplete="off" <?= ($row['ICON']=='icon-pie-chart')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON2'.$row['ID']?>"><i class="icon icon-pie-chart" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON3'.$row['ID'] ?>" value="<?= 'icon-line-chart'?>" autocomplete="off" <?= ($row['ICON']=='icon-line-chart')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON3'.$row['ID'] ?>"><i class="icon icon-line-chart" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON4'.$row['ID'] ?>" value="<?= 'icon-list'?>" autocomplete="off" <?= ($row['ICON']=='icon-list')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON4'.$row['ID'] ?>"><i class="icon icon-list" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON5'.$row['ID'] ?>" value="<?= 'icon-files-o'?>" autocomplete="off" <?= ($row['ICON']=='icon-files-o')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON5'.$row['ID'] ?>"><i class="icon icon-files-o" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON6'.$row['ID'] ?>" value="<?= 'icon-stack-exchange'?>" autocomplete="off" <?= ($row['ICON']=='icon-stack-exchange')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON6'.$row['ID'] ?>"><i class="icon icon-stack-exchange" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON7'.$row['ID'] ?>" value="<?= 'icon-file-text-o'?>" autocomplete="off" <?= ($row['ICON']=='icon-file-text-o')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON7'.$row['ID'] ?>"><i class="icon icon-file-text-o" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON8'.$row['ID'] ?>" value="<?= 'icon-hand-o-left'?>" autocomplete="off" <?= ($row['ICON']=='icon-hand-o-left')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON8'.$row['ID'] ?>"><i class="icon icon-hand-o-left" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON9'.$row['ID'] ?>" value="<?= 'icon-comments-o'?>" autocomplete="off" <?= ($row['ICON']=='icon-comments-o')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON9'.$row['ID'] ?>"><i class="icon icon-comments-o" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON10'.$row['ID'] ?>" value="<?= 'icon-list-ul'?>" autocomplete="off" <?= ($row['ICON']=='icon-list-ul')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON10'.$row['ID'] ?>"><i class="icon icon-list-ul" style="color: #0c0c0c;font-size: 17px;"></i></label>

                                        <input type="radio" class="btn-check" name="btn_icon" id="<?= 'ICON11'.$row['ID'] ?>" value="<?= 'icon-area-chart'?>" autocomplete="off" <?= ($row['ICON']=='icon-area-chart')?'checked':'' ?> >
                                        <label class="btn btn-outline-light" for="<?= 'ICON11'.$row['ID'] ?>" style="border-top-right-radius: 0;border-bottom-right-radius: 0;"><i class="icon icon-area-chart" style="color: #0c0c0c;font-size: 17px;"></i></label>
                                    </div>

                                </div>
                                <input type="hidden" name="id" value="<?= $row['ID']?>">

                                <br><br>
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
            <div class="modal fade" id="<?= 'indicatorDeleteModal'.$row['ID'] ?>" tabindex="-1" aria-labelledby="indicatorDeleteModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="indicatorDeleteModal">تنبيه</h5>
                        </div>
                        <div class="modal-body">
                            <?= 'هل تريد حذف المؤشر  "'.$row['INDICATOR_TITLE'].'"' ?>
                        </div>
                        <div class="modal-footer">
                            <a href="<?= base_url('biunit/Indicator/delete?id='.$row['ID'])?>" class="btn btn-primary">تأكيد</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>

        </tr>
    <?php }?>

    </tbody>
</table>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>