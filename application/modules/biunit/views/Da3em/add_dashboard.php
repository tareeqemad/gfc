<?php if (!$is_admin){
    redirect('Biunit/index');
}?>
<br><br>

    <h5 style="color: rgba(7,83,210,0.95); line-height: 30px;">إضافة لوحة جديدة</h5>
    <form class=" g-3 needs-validation" action="<?=base_url('biunit/Dashboard/create')?>" enctype="multipart/form-data" method="post" style="text-align: right; border: solid 1px rgba(204,207,211,0.54); padding: 20px;" novalidate>
        <div class="col-md-6">
            <label for="validationCustom01" class="form-label">العنوان:</label>
            <input type="text" name="title" class="form-control" id="validationCustom01" value="" required>
            <div class="invalid-feedback">
                الحقل مطلوب
            </div>
        </div>
        <br>
        <input name="category_id" type="hidden" value="<?= $_GET['id'] ?>" >

        <div class="col-md-6">
            <label for="validationCustomUsername" class="form-label">الرابط URL:</label>
            <div class="input-group has-validation">
                <span class="input-group-text have" id="inputGroupPrepend" style="border-top-left-radius: 0;border-bottom-left-radius: 0;border-top-right-radius: 4px;border-bottom-right-radius: 4px;" ><i class="icon icon-link"></i></span>
                <input type="text" name="url" class="form-control" style="border-top-right-radius: 0;border-bottom-right-radius: 0;border-top-left-radius: 4px;border-bottom-left-radius: 4px;" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                <div class="invalid-feedback">
                    الحقل مطلوب
                </div>
            </div>
        </div>
        <br>
        <div class="col-md-6">
            <label for="validationCustom03" class="form-label">   المستخدمين:</label><br>
            <select name="users[]" class="form-control js-select2" multiple >
                <?php foreach($users as $row){ ?>
                <option value="<?=$row['USER_ID'] ?>" data-badge=""><?=$row['USER_NAME'] ?></option>
                <?php } ?>
            </select>


        </div>
        <br>
        <div class="col-md-6">
            <label for="formFile" class="form-label">الأيقونة:</label><br>
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio1" value="<?= 'icon1.png'?>" autocomplete="off" checked>
                <label class="btn btn-outline-light" for="btnradio1"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon1.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio2" value="<?= 'icon2.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio2"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon2.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio3" value="<?= 'icon3.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio3"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon3.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio4" value="<?= 'icon4.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio4"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon4.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio5" value="<?= 'icon5.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio5"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon5.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio6" value="<?= 'icon6.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio6"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon6.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio7" value="<?= 'icon7.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio7"><img width="55" height="50" src="<?=base_url('assets/da3em/img/icons/icon7.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio8" value="<?= 'icon8.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio8"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon8.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio9" value="<?= 'icon9.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio9"><img width="55" height="50" src="<?=base_url('assets/da3em/img/icons/icon9.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio10" value="<?= 'icon10.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio10"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon10.png') ?>"></label>

                <input type="radio" class="btn-check" name="btnradio_icon" id="btnradio11" value="<?= 'icon11.png'?>" autocomplete="off">
                <label class="btn btn-outline-light" for="btnradio11"><img width="50" height="50" src="<?=base_url('assets/da3em/img/icons/icon11.png') ?>"></label>
            </div>
            <br> <br>
            <input type="file" name="icon-file" class="form-control" aria-label="file example">
        </div>

        <br>
            <button type="submit" class="btn btn-primary">إضافة</button>
            <a href="<?=base_url('Biunit/da3em_setting')?>" class="btn btn-secondary">إلغاء</a>

    </form>

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