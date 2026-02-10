<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 19/07/16
 * Time: 09:28 ص
 */
$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_employee_order';
$get_emps_url= base_url("$MODULE_NAME/$TB_NAME/public_get_emps");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get_emp_grad");
$count = 0;
$total = 0;
?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>
    <div class="form-body">
        <div id="msg_container"></div>
        <div id="container">
            <!------------------>
            <div class="tbl_container">
                <div class="form-group col-sm-11">
                    <label class="control-label">شروط ومحددات التقييم (سياسة التقييم)</label>
                    <div>
                        <?php echo '<p> ' . nl2br($effective_order) . '</p>'; ?>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value=""> كل المقرات </option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <br />
                <div class="btn-group">
                    <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                    <ul class="dropdown-menu " role="menu">
                        <li><a href="#" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                        <li><a href="#" onclick="$('#page_tb').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                    </ul>
                </div>
                <table class="table" id="page_tb" data-container="container">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th> تقدير الأداء</th>
                        <th>العدد</th>
                        <th>النسبة</th>
                        <th>عرض الموظفين</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($page_rows as $row) : ?>
                        <tr>
                            <td><?= ($count+1); ?></td>
                            <td><?=$row['DEGREE_NAME']?></td>
                            <td><?=$row['CNT']?></td>
                            <td><?=$row['AVG']."%"; ?></td>
                            <td><a onclick="javascript:get_emps('<?=$row['DEGREE']?>','<?=$row['DEGREE_NAME']?>');" href="javascript:;"><i class="glyphicon glyphicon-list" style="font-size: 17px;"></i></td>
                            <?php $count++?>
                        </tr>
                    <?php endforeach;?>
                    <tr style='font-weight: bold'>
                        <td></td>
                        <td>المجموع</td >
                        <td><?=array_sum(array_column($page_rows,'CNT'))?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!------------------>
        </div>
    </div>
</div>

<!-- الموظفين الحاصلين على تقدير محدد -->
<div class="modal fade" id="emps_modal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body inline_form">
                <div class="btn-group">
                    <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                    <ul class="dropdown-menu " role="menu">
                        <li><a href="#" onclick="$('#emps_list_tb').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                        <li><a href="#" onclick="$('#emps_list_tb').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                    </ul>
                </div>
                <table class="table" id="emps_list_tb" data-container="container">
                    <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 15%">الرقم الوظيفي</th>
                        <th style="width: 30%">الإسم</th>
                        <th style="width: 30%">المدير</th>
                        <th style="width: 15%">الدرجة</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- الموظفين الحاصلين على تقدير محدد -->

<?php
$script =<<<SCRIPT

<script type="text/javascript">

    $('#dp_branch_id').val('{$branch}');
    
    $('#dp_branch_id').change(function(){
        info_msg('رسالة','جار تحميل الصفحة، انتظر قليلا..');
        get_to_link('{$get_url}/'+$(this).val() );
    });

    function get_emps(id,degree_name){
        
        var cnt=0;
        get_data('{$get_emps_url}',{id:id, branch:'{$branch}'},function(data){
            $('#emps_list_tb tbody').html('');
            $('.modal-header .modal-title').text('الموظفين الحاصلين على تقدير '+degree_name);
            $.each(data, function(i,item){
                cnt++;
                if(item.EMP_NO== null) item.EMP_NO= '';
                if(item.EMP_NAME== null) item.EMP_NAME= '';
                if(item.MARK== null) item.MARK= '';
                if(item.MANAGER_NAME== null) item.MANAGER_NAME= '';
                var row_html= '<tr><td>'+cnt+'</td><td>'+item.EMP_NO+'</td><td>'+item.EMP_NAME+'</td><td>'+item.MANAGER_NAME+'</td><td>'+item.MARK+'</td><tr>';
				$('#emps_list_tb tbody').append(row_html);
            });
            $('#emps_modal').modal();
        });
    }
    

</script>


SCRIPT;

sec_scripts($script);

?>
 