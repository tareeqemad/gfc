<?php
$MODULE_NAME = "empvacancey";
$TB_NAME = 'vacancey';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$index_request_url = base_url("$MODULE_NAME/$TB_NAME/index_page");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرئيسية</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-options">
                    <a class="btn btn-secondary" href="<?= $index_request_url ?>">
                        <i class="fa fa-inbox"></i>
                        متابعة طلبات خلو الطرف
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>الموظف</label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary">
                            <i class="fa fa-search"></i> إستعلام
                        </button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                    class="fa fa-eraser"></i>تفريغ الحقول
                        </button>
                    </div>
                </form>
                <hr>
                <div id="container">
                    <?php /*echo modules::run($get_page, $page);*/ ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------JS------------>
<?php
$scripts = <<<SCRIPT
<script>

 $('.sel2:not("[id^=\'s2\']")').select2();  
 
   function search(){
      get_data('{$get_page_url}',{
             emp_no:$('#dp_emp_no').val() 
          },function(data){
             $('#container').html(data);
      },'html');
   } 

</script>
SCRIPT;
sec_scripts($scripts);
?>
