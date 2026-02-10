<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 04/09/14
 * Time: 10:14 ص
 */
$MODULE_NAME= 'settings';
$TB_NAME= 'constant_details';

$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
$account_url= base_url("financial/accounts/public_get_accounts_json/1");

    $count=0;
    $con_array= array(0);
    $account_array= array();
    $tbody='';
    foreach ($get_list as $row){
        $count++;
        $con_array[]= $row['CON_NO'];
        if($row['ACCOUNT_ID'])
            $account_array[]= array('no'=>$row['CON_NO'],'account'=>$row['ACCOUNT_ID']);
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
                    <td>
                        <div class='col-sm-12'>
                          <input type='hidden' value='{$row['ACCOUNT_ID']}' data-val='true'  name='account_id' id='h_txt_account_id{$count}' class='form-control' >
                        <input type='text' data-val='true' value='{$row['ACOUNT_NAME']}'  id='txt_account_id{$count}' class='accounts form-control' >
                        </div>
                    </td>
                    ";
        if(HaveAccess($create_url) or HaveAccess($edit_url))
            $tbody.= "<td><a onclick='javascript:constant_details_save({$row['TB_NO']},{$row['CON_NO']});' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a> </td>";
        if(HaveAccess($delete_url))
            $tbody.= "<td><a onclick='javascript:constant_details_delete({$row['TB_NO']},{$row['CON_NO']});' href='javascript:;'><i class='glyphicon glyphicon-trash'></i> </a> </td>";

        $tbody.= "</tr>";
    }

?>

<table class="table" id="constant_details_tb" data-container="container">
    <thead>
    <tr>
        <th style="width: 10px;">م</th>
        <th style="width: 100px;">الرقم</th>
        <th style="width: 300px;">الاسم</th>
        <th style="width: 270px;">رقم الحساب</th>
        <?php
        if(HaveAccess($create_url) or HaveAccess($edit_url)) echo "<th> حفظ </th>";
        if(HaveAccess($delete_url)) echo"<th> حذف </th>";
        ?>
    </tr>
    </thead>
    <tbody>
        <?=$tbody?>
    </tbody>

    <?php
    if(HaveAccess($create_url)){
        echo "<tfoot>
                <tr>
                <th></th>
                <th><a onclick='javascript:constant_details_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد</a></th>
                <th></th>
                <th></th>";
        if(HaveAccess($create_url) or HaveAccess($edit_url)) echo "<th></th>";
        if(HaveAccess($delete_url)) echo"<th></th>";
        echo "</tr>
            </tfoot>";
    }
    ?>

</table>


<script type="text/javascript">
    $(document).ready(function() {
        reBind();

        $('#constant_details_tb').dataTable({
            "lengthMenu": [ [5,10,20,30,40,50,100, -1], [5,10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    var d_count = <?= $count ?>;
    function constant_details_create() {
        d_count++;
        $('#constant_details_tb tfoot').hide();
        var count= <?=$count?>+1 ;
        var con_array= <?=json_encode($con_array)?> ;
        var new_con= <?=max($con_array)?>+1;
        $("#constant_details_tb tbody").append(
            "<tr data-id='0'>" +
                "<td>"+count+"</td>" +
                "<td><div class='col-sm-12'>" +
                "<input type='number' data-val='true' data-val-required='حقل مطلوب' name='con_no' id='txt_con_no' value='"+new_con+"' class='form-control' maxlength='4'>" +
                "</div></td>" +
                "<td><div class='col-sm-12'>" +
                "<input type='text' data-val='true' data-val-required='حقل مطلوب' name='con_name' id='txt_con_name' value='' class='form-control' maxlength='100'>" +
                "</div></td>" +
                "<td><div class='col-sm-12'>" +
                " <input type='hidden' data-val='true'  name='account_id' id='h_txt_account_id"+d_count+">' class='form-control' ><input type='text' data-val='true'  name='account_id' id='txt_account_id"+d_count+"' class='accounts form-control' >" +
                "</div></td>" +
                "<td><a onclick='javascript:constant_details_save(<?=$tb_no?>,0);' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a> </td>" +
                "<td></td>" +
            "</tr>"
        );
        $('tbody [data-id="0"] #txt_con_name').focus();


        reBind();
    }

    function constant_details_save (tb_no,con_no){
		warning_msg('تنويه', 'الخاصية معطلة على الرسمي');
		return false;
	
        var tr= $("#constant_details_tb tbody [data-id="+con_no+"]");
        var con_array= <?=json_encode($con_array)?> ;
        var new_con_no= tr.find("#txt_con_no").val();
        var con_name= tr.find("#txt_con_name").val();
        var account_id= tr.find("[name='account_id']").val();
        if(account_id==-1)
            account_id='';

        if(con_name!='' && new_con_no!='' && new_con_no >0 ){
            if($.inArray(new_con_no,con_array)==-1 || new_con_no==con_no){
                if(con_no ==0){
                    values= {tb_no:tb_no,con_no:new_con_no,con_name:con_name,account_id:account_id};
                    url= '<?=$create_url?>';
                }else {
                    values= {tb_no:tb_no,con_no:con_no,new_con_no:new_con_no,con_name:con_name,account_id:account_id};
                    url= '<?=$edit_url?>';
                }
                get_data(url, values, function(ret){
                    $("#constant_details_from .modal-body").html(ret);
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
		warning_msg('تنويه', 'الخاصية معطلة على الرسمي');
		return false;
	
        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            var url = 'constant_details/delete';
            var values= {id:con_no,tb_no:tb_no};
            ajax_delete_any(url, values ,function(data){
                success_msg('رسالة','تم حذف السجل بنجاح ..');
                $("#constant_details_from .modal-body").html(data);
            });
        }
    }

</script>
