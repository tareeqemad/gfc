<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 20/06/22
 * Time: 09:20 ص
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Constant_sal_details';

$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");

$count=0;
$con_array= array(0);

$tbody='';
foreach ($get_list as $row){
    $count++;
    $con_array[]= $row['CON_NO'];
    $tbody.= "
                <tr data-id='{$row['CON_NO']}'>
                    <td>$count</td>
                    <td>
                        <div class='col-sm-12'><span style='display: none;'>{$row['CON_NO']}</span>
                        <input type='number' disabled data-val='true' data-val-required='حقل مطلوب' name='con_no' id='txt_con_no' value='{$row['CON_NO']}' class='form-control' maxlength='4'>
                        </div>
                    </td>
                    <td>
                        <div class='col-sm-12'><span style='display: none;'>{$row['CON_NAME']}</span>
                        <input type='text' data-val='true' data-val-required='حقل مطلوب' name='con_name' id='txt_con_name' value='{$row['CON_NAME']}' class='form-control' maxlength='100'>
                        </div>
                    </td>

                    ";
    if(HaveAccess($create_url) or HaveAccess($edit_url) || 1)
        $tbody.= "<td class='text-center'><a onclick='javascript:constant_details_save({$row['TB_NO']},{$row['CON_NO']});' href='javascript:;'><i class='fa fa-save' style='color: #226de0'></i> </a> </td>";
    if(HaveAccess($delete_url) || 1)
        $tbody.= "<td class='text-center'><a onclick='javascript:constant_details_delete({$row['TB_NO']},{$row['CON_NO']});' href='javascript:;'><i class='glyphicon glyphicon-trash' style='color: red;'> </i> </a> </td>";

    $tbody.= "</tr>";
}

?>
<div class="table-responsive">
<table class="table table-bordered text-nowrap roundedTable border-bottom" id="Constant_sal_details_tb" data-container="container">
    <thead class="table-light">
    <tr>
        <th>م</th>
        <th>الرقم</th>
        <th>الاسم</th>

        <?php
        if(HaveAccess($create_url) or HaveAccess($edit_url) || 1) echo "<th class='text-center'> حفظ </th>";
        if(HaveAccess($delete_url) || 1) echo"<th class='text-center'> حذف </th>";
        ?>
    </tr>
    </thead>
    <tbody>
    <?=$tbody?>
    </tbody>

    <?php
    if(HaveAccess($create_url) || 1){
        echo "<tfoot>
                <tr>
                <th><a onclick='javascript:constant_details_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد</a></th>
                <th></th>
                <th></th>";
        if(HaveAccess($create_url) or HaveAccess($edit_url) || 1) echo "<th></th>";
        if(HaveAccess($delete_url) || 1) echo"<th></th>";
        echo "</tr>
            </tfoot>";
    }
    ?>

</table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        reBind();

        $('#Constant_sal_details_tb').dataTable({
            "lengthMenu": [ [5,10,20,30,40,50,100, -1], [5,10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    var d_count = <?= $count ?>;
    function constant_details_create() {
        d_count++;
        $('#Constant_sal_details_tb tfoot').hide();
        var count= <?=$count?>+1 ;
        var con_array= <?=json_encode($con_array)?> ;
        var new_con= <?=max($con_array)?>+1;
        $("#Constant_sal_details_tb tbody").append(
            "<tr data-id='0'>" +
            "<td>"+count+"</td>" +
            "<td><div class='col-sm-12'>" +
            "<input type='number' data-val='true' data-val-required='حقل مطلوب' name='con_no' id='txt_con_no' value='"+new_con+"' class='form-control' maxlength='4'>" +
            "</div></td>" +
            "<td><div class='col-sm-12'>" +
            "<input type='text' data-val='true' data-val-required='حقل مطلوب' name='con_name' id='txt_con_name' value='' class='form-control' maxlength='100'>" +
            "</div></td>" +
            "<td><a onclick='javascript:constant_details_save(<?=$tb_no?>,0);' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a> </td>" +
            "<td></td>" +
            "</tr>"
        );
        $('tbody [data-id="0"] #txt_con_name').focus();
        reBind();
    }

    function constant_details_save (tb_no,con_no){
        var tr= $("#Constant_sal_details_tb tbody [data-id="+con_no+"]");
        var con_array= <?=json_encode($con_array)?> ;
        var new_con_no= tr.find("#txt_con_no").val();
        var con_name= tr.find("#txt_con_name").val();

        if(con_name!='' && new_con_no!='' && new_con_no >0 ){
            if($.inArray(new_con_no,con_array)==-1 || new_con_no==con_no){
                if(con_no ==0){
                    values= {tb_no:tb_no,con_no:new_con_no,con_name:con_name};
                    url= '<?=$create_url?>';
                }else {
                    values= {tb_no:tb_no,con_no:con_no,new_con_no:new_con_no,con_name:con_name};
                    url= '<?=$edit_url?>';
                }
                get_data(url, values, function(ret){
                    $("#Constant_sal_details_from .modal-body").html(ret);
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                }, 'html');
            }else{
                alert('رقم الثابت موجود مسبقا');
                tr.find("#txt_con_no").focus();
            }
        } else {
            alert('ادخل رقم واسم الثابت');
            tr.find("#txt_con_name").focus();
        }
    }

    function constant_details_delete (tb_no,con_no){
        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            var url = 'Constant_sal_details/delete';
            var values= {id:con_no,tb_no:tb_no};
            ajax_delete_any(url, values ,function(data){
                success_msg('رسالة','تم حذف السجل بنجاح ..');
                $("#Constant_sal_details_from .modal-body").html(data);
            });
        }
    }

</script>
