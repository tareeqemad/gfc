<?php
$MODULE_NAME = 'hr';
$TB_NAME = 'Archive_scan';
?>
<div class="card-border">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3 col-xl-3" id="div_grand">
                <div class="card-border">
                    <div class="card-header">
                        <h3 class="card-title">
                            التصنيفات الجد
                        </h3>
                        <div class="card-options">

                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>الرمز</th>
                                <th>التصنيف</th>
                                <th>عدد الورق</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($result_grand as $row) : ?>
                                <tr onclick="javascript:showParent(<?= $row['TYPE_NO'] ?>,<?= $row['CNT_M'] ?>);">
                                    <td><?= $row['TYPE_CODE'] ?></td>
                                    <td><?= $row['TYPE_NAME'] ?>-(<?= $row['CNT_M'] ?>)</td>
                                    <td><?= $row['CNT_EMP'] ?></td>

                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input type="hidden" value="<?=$emp_no?>" id="txt_h_emp_no" name="h_emp_no">
            <div class="col-lg-3 col-xl-3" id="div_parent"></div>
            <div class="col-lg-3 col-xl-3" id="div_son"></div>
            <div class="col-lg-3 col-xl-3" id="div_generate">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label class="text-primary fs-15 fw-bold">العدد</label>
                        <input type="number" name="ccount" id="txt_ccount" class="form-control" autocomplete="off" value="1">
                        <input type="hidden" name="type_no" id="txt_type_no" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="text-primary fs-15 fw-bold">الباركود</label>
                        <input type="text" name="barcode" id="txt_barcode" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-secondary me-2" id="btn_generate_barcode" onclick="generate_barcode(<?=$emp_no?>)">
                            <i class="fa fa-barcode"></i>
                            توليد باركود
                        </button>
                        <button type="button" class="btn btn-primary" onclick="search_lsit(<?=$emp_no?>)">
                            <i class="fa fa-search"></i>
                            استعلام
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div id="container-barcode">
                <?php /*echo modules::run($get_page, $page);*/ ?>
            </div>
        </div>
    </div>
</div>
<script>
    initFunctions();
</script>