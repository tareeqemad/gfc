
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-delivery-package text-primary"></i></span>
            <h3 class="card-label">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background-color:white!important;display: flex;padding-top: 20px!important;">
                      <li class="breadcrumb-item"><a href="<?=base_url('/biunit/info/index')?>"> الرئيسية </a></li>
                      <li class="breadcrumb-item"><a href="<?=base_url('/biunit/info/show?category_id='.$category[0]['ID'])?>"> <?= $category[0]['CATEGORY_NAME'] ?></a></li>
                      <li class="breadcrumb-item active" aria-current="page">إضافة  وثيقة جديدة </li>
                    </ol>
                </nav>
            </h3>
        </div>

    </div>

    <form id="committe_add_edit" action="<?=base_url('biunit/Document/create') ?>" enctype="multipart/form-data" method="POST" class="fv-plugins-bootstrap fv-plugins-framework" novalidate="novalidate">

        <div class="card-body">
            <div class="form-group row cct-1 fv-plugins-icon-container">
                <label class="col-form-label text-right col-lg-3 col-sm-12">ادخل رقم الوثيقة
                    <span class="text-danger">*</span>
                </label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <input class="form-control" type="text" placeholder="ادخل رقم الوثيقة" value="" id="doc_id" name="doc_id">
                    <div class="fv-plugins-message-container"></div></div>
            </div>

            <div class="form-group row cct-1 fv-plugins-icon-container">
                <label class="col-form-label text-right col-lg-3 col-sm-12">ادخل عنوان الوثيقة
                    <span class="text-danger">*</span>
                </label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <input class="form-control" type="text" placeholder="ادخل عنوان الوثيقة" value="" id="title" name="title">
                    <div class="fv-plugins-message-container"></div></div>
            </div>

            <input type="hidden" value="<?= $category[0]['ID'] ?>" name="category_id">

            <div class="form-group row">
                <label class="col-form-label text-right col-lg-3 col-sm-12">نوع الوثيقة <span class="text-danger">*</span></label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <select class="form-control selectpicker" id="cat_general" name="is_active" title="نوع الوثيقة" data-size="7">
                        <option value="0"> عام</option>
                        <option value="1">خاص</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label text-right col-lg-3 col-sm-12">المجال <span class="text-danger">*</span></label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <select class="form-control selectpicker" id="domain" name="domain" title="المجال" data-size="7">
                        <option value="1"> فني</option>
                        <option value="2">إداري</option>
                        <option value="3"> تجاري</option>
                        <option value="4">مالي</option>
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
                        <button type="button" class="btn btn-secondary"><i class="fa fa-upload" style="font-size: 14px"></i>
                        <?php
                          $unique_id = md5(uniqid());
                           echo modules::run('attachments/attachment/indexInline',$unique_id,'controller22',1); ?>
                        </button>
                        <input type="hidden" name="unique_id" value="<?= $unique_id ?>">
                    </div>
                </div>
            </div>



        </div>
        <div class="card-footer">
            <div class="row">
                <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <button type="submit" id="save-btn" class="btn btn-primary mr-2">
                        اضافة                    </button>
                    <a href="<?= base_url('/biunit/info/show?category_id='.$category[0]['ID'])?>" class="btn btn-secondary">الغاء الامر</a>
                </div>
            </div>
        </div>
        <div></div>
        <input type="hidden">
    </form>
</div>

<style>
    .icon-file:after {
        content: 'إضافة مرفق';
        font-style: normal;
        font-weight: 500;
        color: #3f4254;
        background-color: transparent;
        font-size: 1rem;
        line-height: 1.5;

    }
</style>

