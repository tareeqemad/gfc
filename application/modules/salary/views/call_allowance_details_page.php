<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/02/23
 * Time: 12:20 ص
 */
$MODULE_NAME= 'salary';
$TB_NAME= 'Call_allowance_details';

$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");

$count=0;
$con_array= array(0);

$option_val='<option value="0">------اختر--</option>';
foreach ($w_no_admin_cons as $row2) :
    $option_val =$option_val.'<option  value="'.$row2['CON_NO'].'">'.$row2['CON_NAME'].'</option>';
endforeach;

$json_option_val = json_encode($option_val);
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
                        <select name='con_name' id= 'txt_con_name' class='form-control sel2' > ";
                            foreach ($w_no_admin_cons as $row3) {
                                if($row['CON_NAME']==$row3['CON_NO']){
                                    $tbody.= "<option selected value='{$row3['CON_NO']}'>{$row3['CON_NAME']}</option>";
                                }else{
                                    $tbody.= "<option value='{$row3['CON_NO']}'>{$row3['CON_NAME']}</option>";
                                }

                            }
    $tbody.= "        </select>

                     </td>";

    if(HaveAccess($create_url) or HaveAccess($edit_url) )
        $tbody.= "<td class='text-center'><a onclick='javascript:call_allowance_details_save({$row['TB_NO']},{$row['CON_NO']});' href='javascript:;'><i class='fa fa-save' style='color: #226de0'></i> </a> </td>";
    if(HaveAccess($delete_url))
        $tbody.= "<td class='text-center'><a onclick='javascript:call_allowance_details_delete({$row['TB_NO']},{$row['CON_NO']});' href='javascript:;'><i class='glyphicon glyphicon-trash' style='color: red;'> </i> </a> </td>";

    $tbody.= "</tr>";
}

?>
<div class="table-responsive">
    <table class="table table-bordered text-nowrap roundedTable border-bottom" id="Call_allowance_details_tb" data-container="container">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>الرقم</th>
            <th>المنتفع</th>
            <?php
            if(HaveAccess($create_url) or HaveAccess($edit_url) ) echo "<th class='text-center'> حفظ </th>";
            if(HaveAccess($delete_url) ) echo"<th class='text-center'> حذف </th>"; ?>
        </tr>
        </thead>
        <tbody>
        <?=$tbody?>
        </tbody>

        <?php
        if(HaveAccess($create_url) ){
            echo "<tfoot>
                <tr>
                <th><a onclick='javascript:call_allowance_details_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد</a></th>
                <th></th>
                <th></th>";
            if(HaveAccess($create_url) or HaveAccess($edit_url)) echo "<th></th>";
            if(HaveAccess($delete_url)) echo"<th></th>";
            echo "</tr>
            </tfoot>";
        }
        ?>

    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        reBind();

        $('#Call_allowance_details').dataTable({
            "lengthMenu": [ [5,10,20,30,40,50,100, -1], [5,10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    var d_count = <?= $count ?>;
    var json_option = <?=$json_option_val?>;
    console.log(json_option);
    function call_allowance_details_create() {
        d_count++;
        $('#Call_allowance_details_tb tfoot').hide();
        var count= <?=$count?>+1 ;
        var con_array= <?=json_encode($con_array)?> ;
        var new_con= <?=max($con_array)?>+1;
        $("#Call_allowance_details_tb tbody").append(
            "<tr data-id='0'>" +
            "<td>"+count+"</td>" +
            "<td><div class='col-sm-12'>" +
            "<input type='number' data-val='true' data-val-required='حقل مطلوب' name='con_no' id='txt_con_no' value='"+new_con+"' class='form-control' maxlength='4'>" +
            "</div></td>" +
            "<td><div class='col-sm-12'>" +
            "<select name='con_name' id='txt_con_name' class='form-control sel2' >"+json_option+"</select>" +
            "</div></td>" +
            "<td><a onclick='javascript:call_allowance_details_save(<?=$tb_no?>,0);' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a> </td>" +
            "<td></td>" +
            "</tr>"
        );
        $('tbody [data-id="0"] #txt_con_name').focus();
        reBind(d_count);
    }

    function reBind(s){
        if(s==undefined){s=0;}
        if(s){
        }

        $('select[name="con_name"]').select2({
            dropdownParent: $('#Call_allowance_details_tb')
        });
    }

    function call_allowance_details_save (tb_no,con_no){
        var tr= $("#Call_allowance_details_tb tbody [data-id="+con_no+"]");
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
                    $("#Call_allowance_details_from .modal-body").html(ret);
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                }, 'html');
            }else{
                alert('رقم الفئة موجود مسبقا');
                tr.find("#txt_con_no").focus();
            }
        } else {
            alert('ادخل رقم واسم الفئة');
            tr.find("#txt_con_name").focus();
        }
    }

    function call_allowance_details_delete (tb_no,con_no){
        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            var url = '<?=$delete_url?>';
            var values= {id:con_no,tb_no:tb_no};
            ajax_delete_any(url, values ,function(data){
                success_msg('رسالة','تم حذف السجل بنجاح ..');
                $("#Call_allowance_details_from .modal-body").html(data);
            });
        }
    }

</script>
