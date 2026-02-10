<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 25/02/2020
 * Time: 11:52 ص
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'overtime_calc';
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
echo AntiForgeryToken();
?>
<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>

    <div class="form-body">
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">

                    <label> المقر</label>
                    <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                        <option value="">_______</option>
                        <?php foreach($branches as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>" > <?= $row['CON_NAME'] ?> </option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="form-group col-sm-2">
                    <label>الشهر</label>
                    <div>
                        <input type="text" placeholder="الشهر"
                               name="month"
                               id="txt_month" class="form-control"  >
                    </div>
                </div>


            </div>
        </form>
        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"><i class="fa fa-search"></i> إستعلام</button>
            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  إكسل
                <i class="fa fa-file-excel-o"></i>
            </button>

        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run($get_page,$page); ?>
        </div>
    </div>
</div> <!--end row-->

<?php
$scripts = <<<SCRIPT
<script>

 $(document).ready(function() {
		$('#dp_branch_no').select2();
		//$('#dp_branch').select2();
    });
 
 function values_search(add_page){
        var values=
        {page:1, emp_branch:$('#dp_branch_no').val() , month:$('#txt_month').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }


 function search(){
        var values= values_search(1);
        get_data('{$get_page}',values ,function(data){
            $('#container').html(data);
        },'html');
    }
 
 
 
</script>
SCRIPT;
sec_scripts($scripts);
?>
