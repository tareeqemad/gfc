<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 08/01/2023
 * Time: 07:25 م
 */
$MODULE_NAME = 'interviews';
$TB_NAME = 'Interviews_result';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

?>

    <div class="card-body">
        <form id="<?= $TB_NAME ?>_form">
            <div class="row">

                <div class="form-group col-md-3">
                    <label>الموظف</label>
                    <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                        <option value="">_________</option>
                        <?php foreach ($emp_no_cons as $row) : ?>
                            <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label> الوظيفة</label>
                    <select name="ads_ser" id="dp_ads_ser" class="form-control sel2">
                        <option value="">_______</option>
                        <?php foreach ($ads_arr as $row) : ?>
                            <option value="<?= $row['SER'] ?>"><?= $row['ADS_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>


            </div>
            <div class="row">
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
            <hr>
            <div id="container">
                <?php //echo modules::run($get_page_url, $page, $action); ?>
            </div>
        </form>
    </div>



<?php
$scripts = <<<SCRIPT
<script type="text/javascript">



</script>
SCRIPT;
sec_scripts($scripts);
?>