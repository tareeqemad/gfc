
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon"><i class="flaticon2-delivery-package text-primary"></i></span>
            <h3 class="card-label">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background-color:white!important;display: flex;padding-top: 20px!important;">
                        <li class="breadcrumb-item"><a href="<?=base_url('/biunit/info/index')?>"> الرئيسية </a></li>
                        <li class="breadcrumb-item"><a href="<?=base_url('/biunit/info/permission')?>"> صلاحيات المستخدمين</a></li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة  صلاحية جديدة </li>
                    </ol>
                </nav>
            </h3>
        </div>

    </div>

    <form id="committe_add_edit" action="<?=base_url('biunit/Permission/create') ?>" enctype="multipart/form-data" method="POST" class="fv-plugins-bootstrap fv-plugins-framework" novalidate="novalidate">

        <div class="card-body">
            <?php if(isset($_GET['msg']) ){ ?>
            <div class="alert alert-danger" role="alert">
               <?= $_GET['msg']?>
            </div>
            <?php } ?>

            <div class="form-group row">
                <label class="col-form-label text-right col-lg-3 col-sm-12">اسم المستخدم
                    <span class="text-danger">*</span>
                </label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <select id="user" name="user" class="form-control js-select2" data-placeholder="بحث عن اسم المستخدم">
                        <option value="" data-badge=""> </option>
                        <?php foreach($users as $row){ ?>
                            <option value="<?=$row['USER_ID'] ?>" data-badge=""><?=$row['USER_NAME'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <input type="hidden" name="created_user" value="<?= get_curr_user()->username ?>">

            <div class="form-group row">
                <label class="col-form-label text-right col-lg-3 col-sm-12">اسم الصلاحية
                    <span class="text-danger">*</span>
                </label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <select id="role" name="role" class="form-control selectpicker" onchange="styleselect()" title="اسم الصلاحية">
                        <?php foreach($roles as $row){ ?>
                            <option value="<?=$row['ID'] ?>" data-badge=""><?=$row['NAME'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row" id="extend">
                <label class="col-form-label text-right col-lg-3 col-sm-12"> </label>
                <div class="col-lg-6 col-md-9 col-sm-12 row">
                    <div class="form-check" style="margin-left:25px;margin-right: 15px ">
                        <input class="form-check-input" type="checkbox" name="add" value="" id="flexCheckDefault" disabled>
                        <label class="form-check-label" for="flexCheckDefault">
                            إضافة
                        </label>
                    </div>
                    <div class="form-check" style="margin-left:25px ">
                        <input class="form-check-input" type="checkbox" name="edit" value="" id="flexCheckDefault" disabled>
                        <label class="form-check-label" for="flexCheckDefault">
                            تعديل
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="delete" value="" id="flexCheckDefault" disabled>
                        <label class="form-check-label " for="flexCheckDefault">
                            حذف
                        </label>
                    </div>

                </div>
            </div>
            <p></p>



        </div>
        <div class="card-footer">
            <div class="row">
                <label class="col-form-label text-right col-lg-3 col-sm-12"></label>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <button type="submit" id="save-btn" class="btn btn-primary mr-2">
                        اضافة                    </button>
                    <a href="<?= base_url('/biunit/info/permission')?>" class="btn btn-secondary">الغاء الامر</a>
                </div>
            </div>
        </div>
        <div></div>
        <input type="hidden">
    </form>
</div>

<script>
    $(window).load(function(){
        styleselect();
    })
    function styleselect() {
        if (document.getElementById('role').value == "2") {
            $('input[type="checkbox"]').removeAttr("checked");
            $('input[type="checkbox"]').attr('disabled',false);
        }
        if (document.getElementById('role').value == "3") {
            $('input[type="checkbox"]').removeAttr("checked");
            $('input[type="checkbox"]').attr('disabled',false);
        }
        if (document.getElementById('role').value == "4") {
            $('input[type="checkbox"]').removeAttr("checked");
            $('input[type="checkbox"]').attr('disabled',false);
        }
        if (document.getElementById('role').value == "1") {
            $('input[type="checkbox"]').attr('checked','checked');
            $('input[type="checkbox"]').attr('disabled',true);
        }
        if (document.getElementById('role').value == "5") {
            $('input[type="checkbox"]').removeAttr("checked");
            $('input[type="checkbox"]').attr('disabled',true);
        }

    }


</script>