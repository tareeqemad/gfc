<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 17/09/2022
 * Time: 20:32 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'upgrade_bonus';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page">علاوة الترقية 5%</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                   كشف | علاوة الترقية 5%
                </div>
                <div class="flex-shrink-0">

                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?>
                                                value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>

                        <div class="form-group col-md-2">
                            <label>الموظف</label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>من شهر</label>
                            <input type="text" name="from_month" id="txt_from_month" class="form-control"
                                   value="<?= date('Ym', strtotime('last month')) ?>" autocomplete="off">
                        </div>

                        <div class="form-group col-md-2">
                            <label>الى شهر</label>
                            <input type="text" name="to_month" id="txt_to_month" class="form-control"
                                   value="" autocomplete="off">
                        </div>


                    </div>

                </form>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                class="fa fa-search"></i> إستعلام
                    </button>
                    <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">
                        <i class="fa fa-file-excel-o"></i>إكسل
                    </button>

                </div>
                <hr>
                <div id="container">
                    <?/*= modules::run($get_page_url, $page);*/ ?>
                </div>
            </div><!--end card body-->
        </div><!--end card --->
    </div><!--end col lg-12--->
</div><!--end row--->
<?php
$scripts = <<<SCRIPT
<script>

      var table = '{$TB_NAME}';
      
       $('.sel2:not("[id^=\'s2\']")').select2();  

       $('#txt_from_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false
      });
       $('#txt_to_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false
      });
    
      
       function reBind(){
            ajax_pager({
                branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),from_month:$('#txt_from_month').val(),to_month:$('#txt_to_month').val()
             });
        }

       function LoadingData(){
           ajax_pager_data('#page_tb > tbody',{
                    branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),from_month:$('#txt_from_month').val(),to_month:$('#txt_to_month').val()
           });
     }


       function search(){
        get_data('{$get_page_url}',{page: 1,
                branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),from_month:$('#txt_from_month').val(),to_month:$('#txt_to_month').val()
             },function(data){
                $('#container').html(data);
                reBind();
            },'html');
      }
     
      </script>
SCRIPT;
sec_scripts($scripts);
?>
