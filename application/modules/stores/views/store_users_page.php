
<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 20/11/14
 * Time: 09:37 ص
 */


$MODULE_NAME= 'stores';
$TB_NAME= 'stores';
$TB_NAME2= 'store_usres';
$edit_url =base_url("$MODULE_NAME/$TB_NAME2/edit");
$create_url =base_url("$MODULE_NAME/$TB_NAME2/create");
$delete_url =base_url("$MODULE_NAME/$TB_NAME2/delete");
/*$get_url =base_url("$MODULE_NAME/$TB_NAME2/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME2/edit");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME/get_page_users");*/
$count=0;
?>

<table class="table" id="store_usres_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>رقم المستخدم</th>
        <th>المستوى</th>
        <th>تلفون</th>
        <th>ايميل</th>
       <?php  if(HaveAccess($create_url) or HaveAccess($edit_url))
           echo "<th>حفظ</th>"; ?>
        <?php if(HaveAccess($delete_url)) echo "<th>حذف</th>"; ?>


    </tr>
    </thead>

    <tbody>
    <?php

    foreach ($get_all as $row1){
        $count++;
        echo "<tr data-id='{$row1['USER_ID']}' ";
       // if(HaveAccess($get_url) and HaveAccess($edit_url))
        //    echo "ondblclick='javascript:stores_get({$row['STORE_ID']});'";
        echo ">

                <td>$count</td>
                   <td>
                 <select  data-val='true' data-val-required='حقل مطلوب' name='user_id[]' style='width: 250px' id='txt_user_id'>
              <option></option>";
                foreach($users as $row) :
                echo "<option value='{$row['ID']}'";
                    if ($row['ID']==$row1['USER_ID']) echo "selected";
                    echo">{$row['USER_NAME']}</option>";
                endforeach;
                 echo "</select>";
                       echo " <input type='hidden'  data-val='true' data-val-required='حقل مطلوب' name='ser' id='txt_ser' value='{$row1['SER']}' class='form-control' maxlength='4'>


                   </td>
                <td>
                  <select  data-val='true' data-val-required='حقل مطلوب' name='store_level'  id='txt_store_level'>
                 <option value='1' ";
        if($row1['STORE_LEVEL']==1) echo "selected";
    echo">أمين المخزن</option>
                  <option value='2' ";
        if($row1['STORE_LEVEL']==2) echo "selected";
        echo">مساعد </option>
     <option value='4' ";
        if($row1['STORE_LEVEL']==4) echo "selected";
        echo">مدير </option>
 <option value='5' ";
        if($row1['STORE_LEVEL']==5) echo "selected";
        echo">نائب مدير </option>
  <option value='3' ";
        if($row1['STORE_LEVEL']==3) echo "selected";
        echo">غير ذلك </option>
                  </select>

                       </td>
                <td><input type='text'  data-val='true' data-val-required='حقل مطلوب' name='tel' id='txt_tel' value='{$row1['TEL']}' class='form-control' maxlength='4'>
                 </td>
        <td><input type='text'  data-val='true' data-val-required='حقل مطلوب' name='email' id='txt_email' value='{$row1['EMAIL']}' class='form-control' maxlength='4'>
       </td>";
        if(HaveAccess($create_url) or HaveAccess($edit_url))
        echo "<td><a onclick='javascript:{$TB_NAME2}_save({$row1['STORE_ID']},{$row1['USER_ID']});' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a> </td>";

       if(HaveAccess($delete_url))
           echo "<td><a onclick='javascript:{$TB_NAME2}_delete({$row1['SER']},{$row1['STORE_ID']});' href='javascript:;'><i class='glyphicon glyphicon-trash'></i> </a> </td>";

        echo "</tr>";
    }
    ?>
    </tbody>
    <?php
    if(HaveAccess($create_url)){
        echo "<tfoot>
                <tr>
                <th></th>
                <th><a onclick='javascript:{$TB_NAME2}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد</a></th>
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

     });
     $('select[name="user_id[]"]').select2();

     var d_count = <?= $count ?>;
    function store_usres_create() {
        d_count++;
        $('#store_usres_tb tfoot').hide();
        var count= <?=$count?>+1 ;


        $("#store_usres_tb tbody").append(
            "<tr data-id='0'>" +
                "<td>"+count+"</td>" +
                "<td><div class='col-sm-12'>" +
                "<select  data-val='true' data-val-required='حقل مطلوب' name='user_id[]' style='width: 250px' id='txt_user_id' > <option></option>"+
        <?php foreach($users as $row) : ?>
         "<option value='<?=$row['ID']?>'><?=$row['USER_NAME']?></option>"+
     <?php    endforeach; ?>
        "</select>"+

                " <input type='hidden'   data-val='true' data-val-required='حقل مطلوب' name='ser' id='txt_ser' value='0' class='form-control' maxlength='4'>"+
                "</div></td>" +
                "<td><div class='col-sm-12'>" +
                "   <select  data-val='true' data-val-required='حقل مطلوب' name='store_level'  id='txt_store_level'><option value='1' >أمين المخزن</option><option value='2'>مساعد الأمين</option><option value='4'> مدير</option><option value='5'> نائب مدير</option>  <option value='3'>غير ذلك </option></select>" +
               "</div></td>" +
                "<td><div class='col-sm-12'>" +
                " <input type='text' data-val='true' data-val-required='حقل مطلوب' name='tel_no'  value=''  id='txt_tel' class='form-control' >" +
                "</div></td>" +
                "<td><div class='col-sm-12'>" +
                " <input type='text' data-val='true' data-val-required='حقل مطلوب' name='email'  value=''  id='txt_email' class='form-control' >" +
                "</div></td>" +
                "<td><a onclick='javascript:store_usres_save(<?=$store_id?>,0);' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a> </td>" +
                "<td><div></div></td>" +
                "<td><div></div></td>" +
                "</tr>"
        );
        $('select[name="user_id[]"]').select2();
      //  $('tbody [data-id="0"] #txt_user_id').focus();


        //  reBind();
    }

    function store_usres_save (store_id,user_id){

        var tr= $("#store_usres_tb tbody [data-id="+user_id+"]");
        var ser= tr.find("#txt_ser").val();
        var store_level= tr.find("#txt_store_level").val();
        var tel= tr.find("#txt_tel").val();
        var email= tr.find("#txt_email").val();
        var user_idx= tr.find("#txt_user_id").val();
        if(user_idx!=''  )

        {
     if (ser>0){

         values= {ser:ser,user_id:user_idx,store_id:store_id,store_level:store_level,tel:tel,email:email};
         url= '<?=$edit_url?>';
     }
        else
     {
         values= {user_id:user_idx,store_id:store_id,store_level:store_level,tel:tel,email:email};
         url= '<?=$create_url?>';

     }
        get_data(url, values, function(ret){
            $("#<?=$TB_NAME2?>_from .modal-body").html(ret);
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        }, 'html');
        }else{

            alert(' يجب اختيار الموظف');

        }
    }

    function store_usres_delete (ser,store_id){

        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            var url = '<?=$delete_url?>';
            var values= {ser:ser,store_id:store_id};
            ajax_delete_any(url, values ,function(data){

                success_msg('رسالة','تم حذف السجل بنجاح ..');
                $("#<?=$TB_NAME2?>_from .modal-body").html(data);
            });
        }
    }

</script>