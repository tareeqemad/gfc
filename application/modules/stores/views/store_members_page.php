<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 04/09/14
 * Time: 10:14 ص
 */
$MODULE_NAME= 'stores';
$TB_NAME= 'store_members';

$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");

$count=0;
//$con_array= array(0);

$tbody="";
foreach ($get_list as $row){

 // $con_array[]= $row['EMP_NO'];
  //  if($row['ID_NO'])
  //      $id_array[]= array('no'=>$row['EMP_NO'],'account'=>$row['ACCOUNT_ID']);
    $count++;
    //$ser=(isset($row['ser'])&&$row['ser']!='')? $row['ser'] :0;
    $tbody.= "
                <tr data-id='{$row['EMP_NO']}'>
                    <td>$count</td>
                    <td>
                        <div class='col-sm-12'><span style='display: none;'>{$row['EMP_NO']}</span>
                        <input type='number'  data-val='true' data-val-required='حقل مطلوب' name='emp_no' id='txt_emp_no' value='{$row['EMP_NO']}' class='form-control' maxlength='4'>
                         <input type='hidden'  data-val='true' data-val-required='حقل مطلوب' name='ser' id='txt_ser' value='{$row['SER']}' class='form-control' maxlength='4'>

                        </div>
                    </td>
                    <td>
                        <div class='col-sm-12'><span style='display: none;'>{$row['NAME']}</span>
                        <input type='text' data-val='true' data-val-required='حقل مطلوب' name='name' id='txt_name' value='{$row['NAME']}' class='form-control' maxlength='100'>
                        </div>
                    </td>
                    <td>
                        <div class='col-sm-12'><span style='display: none;'>{$row['ID_NO']}</span>
                          <input type='text' data-val='true' data-val-required='حقل مطلوب'     name='id_no' id='txt_id_no' value='{$row['ID_NO']}' class='form-control' >
                        </div>
                    </td>
                    <td>
                        <div class='col-sm-12'><span style='display: none;'>{$row['ORDER_NO']}</span>
                          <input type='text' data-val='true' data-val-required='حقل مطلوب'     name='order_no' id='txt_order_no' value='{$row['ORDER_NO']}' class='form-control' >
                        </div>
                    </td>
                    ";
    if(HaveAccess($create_url) or HaveAccess($edit_url))
        $tbody.= "<td><a onclick='javascript:constant_details_save({$row['COMMITTEES_ID']},{$row['EMP_NO']});' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a> </td>";
    if(HaveAccess($delete_url))
        $tbody.= "<td><a onclick='javascript:constant_details_delete({$row['EMP_NO']},{$row['COMMITTEES_ID']});' href='javascript:;'><i class='glyphicon glyphicon-trash'></i> </a> </td>";

    $tbody.= "</tr>";

}

?>

<table class="table" id="constant_details_tb" data-container="container">
    <thead>
    <tr>



        <th style="width: 20px;">م</th>
        <th style="width: 300px;">الرقم</th>
        <th style="width: 400px;">الاسم</th>
        <th style="width: 300px;">رقم الهوية</th>
        <th style="width: 200px;">الترتيب</th>
      <?php  if(HaveAccess($create_url) or HaveAccess($edit_url)) { ?>  <th style="width: 200px;">حفظ</th> <?php } ?>
        <?php  if(HaveAccess($delete_url) ) { ?> <th style="width: 200px;">حذف</th><?php } ?>



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
                <th></th>
                <th></th>
                ";
        if(HaveAccess($create_url) or HaveAccess($edit_url)) echo "<th></th>";
        if(HaveAccess($delete_url)) echo"<th></th>";
        echo "</tr>
            </tfoot>";
    }
    ?>

</table>


<script type="text/javascript">
    /*
    $(document).ready(function() {
       // reBind();

        $('#constant_details_tb').dataTable({
            "lengthMenu": [ [5,10,20,30,40,50,100, -1], [5,10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });
*/
    var d_count = <?= $count ?>;
    function constant_details_create() {
        d_count++;
        $('#constant_details_tb tfoot').hide();
        var count= <?=$count?>+1 ;


        $("#constant_details_tb tbody").append(
            "<tr data-id='0'>" +
                "<td>"+count+"</td>" +
                "<td><div class='col-sm-12'>" +
                "<input type='number' data-val='true' data-val-required='حقل مطلوب' name='emp_no' id='txt_emp_no' value='' class='form-control' maxlength='4'>" +
               " <input type='hidden'  data-val='true' data-val-required='حقل مطلوب' name='ser' id='txt_ser' value='0' class='form-control' maxlength='4'>"+
                 "</div></td>" +
                "<td><div class='col-sm-12'>" +
                "<input type='text' data-val='true' data-val-required='حقل مطلوب' name='name' id='txt_name' value='' class='form-control' maxlength='100'>" +
                "</div></td>" +
                "<td><div class='col-sm-12'>" +
                " <input type='text' data-val='true' data-val-required='حقل مطلوب' name='id_no'  value=''  id='txt_id_no' class='form-control' >" +
                "</div></td>" +
                "<td><div class='col-sm-12'>" +
                " <input type='text' data-val='true' data-val-required='حقل مطلوب' name='order_no'  value=''  id='txt_order_no' class='form-control' >" +
                "</div></td>" +
              "<td><a onclick='javascript:constant_details_save(<?=$committees_id?>,0);' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a> </td>" +
                "<td><div></div></td>" +
                "<td><div></div></td>" +
                "</tr>"
        );
        $('tbody [data-id="0"] #txt_emp_no').focus();


      //  reBind();
    }

    function constant_details_save (committees_id,emp_no){

    var tr= $("#constant_details_tb tbody [data-id="+emp_no+"]");
var ser= tr.find("#txt_ser").val();
      //  alert (ser);
        var new_emp_no= tr.find("#txt_emp_no").val();
        var name= tr.find("#txt_name").val();
        var id_no= tr.find("#txt_id_no").val();
        var order_no= tr.find("#txt_order_no").val();
       if (order_no !=''){
        if (id_no !=''){
       if(name!='' && new_emp_no!='' && new_emp_no >0 )

        {

                if(emp_no ==0){
                    values= {committees_id:committees_id,emp_no:new_emp_no,name:name,id_no:id_no,order_no:order_no};
                    url= '<?=$create_url?>';
                }else {

                   values= {ser:ser,committees_id:committees_id,emp_no:emp_no,new_emp_no:new_emp_no,name:name,id_no:id_no,order_no:order_no};
                   url= '<?=$edit_url?>';
                }
                get_data(url, values, function(ret){
                    $("#store_members_from .modal-body").html(ret);
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                }, 'html');

        } else {
            alert('ادخل رقم واسم الموظف');
            tr.find("#txt_con_name").focus();
        }
}else {
    alert('يجب إدخال رقم هوية الموظف');
    tr.find("#txt_id_no").focus();

}

    }else {
        alert('يجب إدخال ترتيب عرض الاسماء');
        tr.find("#txt_order_no").focus();

    }

    }
    function constant_details_delete (emp_no,committees_id){
        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            var url = 'store_members/delete';
           var values= {emp_no:emp_no,committees_id:committees_id};
           ajax_delete_any(url, values ,function(data){
                success_msg('رسالة','تم حذف السجل بنجاح ..');
                $("#store_members_from .modal-body").html(data);
           });
        }
    }

</script>
