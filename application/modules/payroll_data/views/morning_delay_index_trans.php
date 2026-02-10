<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 13/04/18
 * Time: 10:01 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'morning_delay';
$prepare_morning_delay = base_url("$MODULE_NAME/$TB_NAME/index");
$trans_delay_emp_url = base_url("$MODULE_NAME/$TB_NAME/trans_delay_emp");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page">ترحيل الموظفين المتأخرين عن الدوام الصباحي</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> ترحيل المتأخرين عن الدوام</h3>
                <div class="card-options">
                    <a href="<?=$prepare_morning_delay?>" class="btn btn-secondary"><i
                                class="fa fa-search"></i>
                        عرض كشف التاخير الصباحي
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>


                        <div class="form-group col-md-2">
                            <label>من تاريخ </label>
                            <input type="text" <?= $date_attr ?> name="from_the_date" id="txt_from_the_date"
                                   value="<?= date("d/m/Y", mktime(0, 0, 0, date("m")-1, 1)); ?>"   class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>الى </label>
                            <input type="text" <?= $date_attr ?> name="to_the_date" id="txt_to_the_date"
                                   value="<?=date('d/m/Y',strtotime('-1 second',strtotime(date('m').'/01/'.date('Y'))));?>"    class="form-control">
                        </div>


                    </div>

                </form>
                <div class="flex-shrink-0">
                    <?php if (HaveAccess($trans_delay_emp_url)) {?>
                        <button type="button" onclick="javascript:trans_delay_emp();" class="btn btn-primary"><i
                                    class="fe fe-corner-down-left"></i>
                            ترحيل المتأخرين عن الدوام
                        </button>
                    <?php } ?>

                    <a href="<?=$prepare_morning_delay?>" class="btn btn-secondary"><i
                                class="fa fa-search"></i>
                        عرض كشف التاخير الصباحي
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row -->

<?php
$scripts = <<<SCRIPT
<script>

 
   
      function trans_delay_emp(){
          var branch_no = $('#dp_branch_no').val();
          var from_the_date = $('#txt_from_the_date').val();
          var to_the_date = $('#txt_to_the_date').val();
          if(process(from_the_date) > process(to_the_date)){
             warning_msg('يجب ان تكون قيمة الى تاريخ اكبر من تاريخ البداية');
             return -1;
         }else {
               if(confirm('هل تريد ترحيل كشف التأخير الصباحي  ؟!')){
                  get_data('{$trans_delay_emp_url}', {branch_no:branch_no,from_the_date:from_the_date,to_the_date:to_the_date} , function(ret){
                     if(ret>= 1){
                        success_msg('رسالة','تم ترحيل البيانات بنجاح ..');
                    }else{
                        warning_msg('تحذير..',ret);
                        return -1;
                    }
                  }, 'html');
               }
         }
   
      }
        
      function process(date){
           var parts = date.split("/");
           var date = new Date(parts[1] + "/" + parts[0] + "/" + parts[2]);
           return date.getTime();
      }
      
   
</script>
SCRIPT;
sec_scripts($scripts);
?>


