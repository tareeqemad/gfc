

<h3 class="mt-4">آخر الأعمال</h3><br>
<a href="" data-bs-toggle="modal" data-bs-target="#newsInsertModal" class="btn btn-outline-primary" style="float: left; margin-bottom: 5px;font-size: 16px; font-weight: bold">جديد</a>


<!-- ADD NEWS model -->
<div class="modal fade" id="newsInsertModal" tabindex="-1" aria-labelledby="newsInsertModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="font-family: Tajawal, Sans-Serif;">
            <div class="modal-header">
                <h5 class="modal-title" id="indicatorInsertModal" style="color: #05235b">إضافة عمل جديد</h5>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="post" action="<?= base_url('biunit/News/create')?>" novalidate>
                    <div class="form-group">
                        <label for="" class="form-label">العنوان</label>
                        <input type="input" class="form-control" name="title" required>
                        <div class="invalid-feedback">
                            الحقل مطلوب
                        </div>
                    </div><br>
                    <div class="form-group">
                        <label for="" class="form-label">تاريخ النشر</label>
                        <input type="date" class="form-control" name="date" required>
                    </div><br>
                    <div class="form-group">
                        <label for="" class="form-label">الحالة</label>
                        <select class="form-control" name="status">
                            <option value="1"> فعال</option>
                            <option value="0">غير فعال</option>
                        </select>
                    </div><br>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="link" id="link" onclick="myFunction()" value="" style="float: right;margin-left: 15px">
                        <label class="form-check-label" for="defaultCheck1">
                            إظهار رابط نموذج التقرير الشهري لمتابعة الفروع
                        </label>
                    </div><br>
                    <div class="form-group" id="month" style="display: none">
                        <label for="" class="form-label">تاريخ الدورة</label>
                        <input type="text" class="form-control" value="<?=date('Ym')?>"  pattern="\d{4}(?:0[1-9]|1[0-2])" name="month" required>
                    </div><br>



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
    <td>العنوان</td>
    <td>تاريخ النشر</td>
    <td>الحالة</td>
    <td>الاجراءات</td>
    </thead>
    <tbody>
    <?php foreach ($news as $key=>$row ){ ?>
        <tr>
            <td><?= $row['TITLE'] ?></td>
            <td><?= $row['T_DATE'] ?></td>
            <td><?php if($row['ACTIVE']==1){ ?>
                    <a  class="btn btn-success" style="font-size: 12px;">فعال</a>
                <?php } else{?>
                    <a  class="btn btn-danger" style="font-size: 12px;">غير فعال</a>
                <?php } ?>

            </td>
            <td>
                <!-- Update NEWS Option -->
                <a style="margin-right: 5px;"  href="" data-bs-toggle="modal" data-bs-target="<?= '#newsUpdateModal'.$row['ID'] ?>" ><i style="font-size: 18px; color: rgba(12,12,12,0.37);margin: 1px 0px 0px 7px;" class="icon icon-edit btn-outline-light ic-edit-bg"></i></a>
                <!-- Delete NEWS Option -->
                <a style="margin-left: 5px"  href="" data-bs-toggle="modal" data-bs-target="<?= '#newsDeleteModal'.$row['ID'] ?>"><i style="font-size: 18px; color: rgba(12,12,12,0.37);" class="icon icon-trash-o btn-outline-light ic-delete-bg"></i></a>
            </td>

            <!--  Update NEWS model -->
            <div class="modal fade" id="<?= 'newsUpdateModal'.$row['ID'] ?>" tabindex="-1" aria-labelledby="newsUpdateModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="font-family: Tajawal, Sans-Serif;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newsUpdateModal" style="color: #05235b">تحديث بيانات الخبر</h5>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="<?= base_url('biunit/News/update')?>">
                                <div class="form-group">
                                    <label  class="form-label">العنوان</label>
                                    <input type="input" class="form-control" name="title2" value="<?= $row['TITLE'] ?>" >
                                </div><br>
                                <div class="form-group">
                                    <label class="form-label"> تاريخ النشر </label>
                                    <input type="date" class="form-control" name="date2" value="<?= $row['T_DATE']?>" >
                                </div><br>
                                <div class="form-group">
                                    <label class="form-label">الحالة</label>
                                    <select class="form-control" name="status2" id="">
                                        <option value="1" <?= ($row['ACTIVE']==1)?'selected':'' ?> > فعال</option>
                                        <option value="0" <?= ($row['ACTIVE']==0)?'selected':'' ?> >غير فعال</option>
                                    </select>
                                </div><br>
                                <div style="<?= (isset($row['REPORT_LINK'])==1)?'':'display:none' ?>">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="link2" id="link2" onclick="myFunction2()"  value="" <?= (isset($row['REPORT_LINK'])==1)?'checked':'' ?> style="float: right;margin-left: 15px">
                                    <label class="form-check-label" for="defaultCheck1">
                                        إظهار رابط نموذج التقرير الشهري لمتابعة الفروع
                                    </label>
                                </div><br>
                                <div class="form-group" id="month2">
                                    <label for="" class="form-label">تاريخ الدورة</label>
                                    <input type="text" class="form-control" value="<?= (isset($row['REPORT_LINK'])==1)?$row['REPORT_LINK']:'' ?>"  pattern="\d{4}(?:0[1-9]|1[0-2])" name="month2" required>
                                </div><br>
                                </div>
                                <input type="hidden" name="id" value="<?= $row['ID']?>">


                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">تحديث</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Delete NEWS model -->
            <div class="modal fade" id="<?= 'newsDeleteModal'.$row['ID'] ?>" tabindex="-1" aria-labelledby="newsDeleteModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newsDeleteModal">تنبيه</h5>
                        </div>
                        <div class="modal-body">
                            <?= 'هل تريد حذف الخبر  "'.$row['TITLE'].'"' ?>
                        </div>
                        <div class="modal-footer">
                            <a href="<?= base_url('biunit/News/delete?id='.$row['ID'])?>" class="btn btn-primary">تأكيد</a>
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
    function myFunction() {
        if ( document.getElementById("link").checked ==true) {
            document.getElementById("month").style.display = "block";
        }
        else {
            document.getElementById("month").style.display = "none";
        }
    }
    function myFunction2() {
        if ( document.getElementById("link2").checked ==true) {
            document.getElementById("month2").style.display = "block";
        }
        else {
            document.getElementById("month2").style.display = "none";
        }
    }


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