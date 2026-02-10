
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-delivery-package text-primary"></i></span>
            <h3 class="card-label">تعديل  قرارات وتوجيهات هيئة المديرين </h3>
        </div>

    </div>

    <form id="committe_add_edit" action="<?=base_url('biunit/Document/update') ?>" enctype="multipart/form-data" method="POST" class="fv-plugins-bootstrap fv-plugins-framework" novalidate="novalidate">

        <div class="card-body">

            <div class="form-group row cct-1 fv-plugins-icon-container">
                <label class="col-form-label text-right col-lg-3 col-sm-12">ادخل عنوان الوثيقة
                    <span class="text-danger">*</span>
                </label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <input class="form-control" type="text" placeholder="ادخل عنوان القرار" value="<?= $document[0]['DOC_NAME'] ?>" id="title" name="title">
                    <div class="fv-plugins-message-container"></div></div>
            </div>

            <input type="hidden" value="<?= $document[0]['ID'] ?>" name="id">
            <input type="hidden" value="<?= $document[0]['CATEGORY_ID'] ?>" name="category_id">

            <div class="form-group row">
                <label class="col-form-label text-right col-lg-3 col-sm-12">حالة الوثيقة <span class="text-danger">*</span></label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <select class="form-control selectpicker" id="cat_general" name="is_active"  title="حالة الوثيقة" data-size="7">
                        <option value="1" <?= $document[0]['IS_ACTIVE']?'selected':'' ?> > فعال</option>
                        <option value="0" <?= $document[0]['IS_ACTIVE']?'':'selected' ?> > غير فعال</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label text-right col-lg-3 col-sm-12">صلاحية الوثيقة <span class="text-danger">*</span></label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <select class="form-control selectpicker" id="cat_general" name="is_valid"  title="صلاحية الوثيقة" data-size="7">
                        <option value="1" <?= $document[0]['IS_VALID']?'selected':'' ?> > سارية المفعول</option>
                        <option value="0" <?= $document[0]['IS_VALID']?'':'selected' ?> > منتهية المفعول</option>
                    </select>
                </div>
            </div>


            <div class="form-group row  cct-2">
                <label class="col-form-label text-right col-lg-3 col-sm-12">ارفق ملف
                    <span class="text-danger">*</span>
                </label>
                <!-- <label class="col-form-label col-lg-3 col-sm-12">Live Search</label> -->
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="file" name="file">
                        <label class="custom-file-label" for="file">اختر الملف</label>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <div class="row">
                <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <button type="submit" id="save-btn" class="btn btn-primary mr-2">
                        تعديل                    </button>
                    <a href="<?= base_url('/biunit/info/show?category_id='.$document[0]['CATEGORY_ID'])?>" class="btn btn-secondary">الغاء الامر</a>
                </div>
            </div>
        </div>
        <div></div>
        <input type="hidden">
    </form>
</div>
