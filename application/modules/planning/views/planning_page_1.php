<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/10/17
 * Time: 01:29 م
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");


?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>العام</th>
        <th>رقم النشاط</th>
        <th>اسم النشاط</th>
        <th>تفاصيل</th>
        <th>نوع النشاط</th>
        <th>اسم المشروع</th>
        <th>حالة النشاط</th>
        <?php if(HaveAccess($edit_url)) echo "<th>تحرير</th>"; ?>

        <th>عرض</th>

    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_all as $row){
        $count++;
        echo "<tr data-id='{$row['ACTIVITY_NO']}' ";

            echo "'>";

        echo "
                <td><input type='checkbox' class='checkboxes' value='{$row['ACTIVITY_NO']}' /></td>
                <td>$count</td>
                   <td>{$row['YEAR']}</td>
                   <td>{$row['ACTIVITY_NO']}</td>
                <td>{$row['ACTIVITY_NAME']}</td>
                <td>{$row['DETAILES']}</td>
                  <td>{$row['TYPE_NAME']}</td>
                  <td>{$row['PROJECT_NAME']}</td>
                   <td>{$row['STATUS_NAME']}</td>";
                    if(HaveAccess($edit_url))
                    {
                        if ($row['STATUS']==1)
                        echo "<td><button type='button' class='btn btn-primary btn-details'>تحرير</button><input type='hidden' class='h_active[]' value='{$row['ACTIVITY_NO']}' /></td>";
                        else
                            echo"<td></td>";


                    }


        if($row['TYPE']==1)
        echo "<td><i class='glyphicon glyphicon-th-list'></i>فصول النشاط</td>";
        else if($row['TYPE']==2)
            echo "<td><i class='glyphicon glyphicon-th-list'></i>فصول المشروع</td>";
        echo "</tr>";
    }
    ?>
    </tbody>

</table>


<script type="text/javascript">
    $(document).ready(function() {
        $('#planning_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });


        $('#planning_tb tbody').on('click', '.btn-details', function () {

             // id= $(this).find('input#h_active').val();
            var tr=$(this).closest('tr');
            var id =tr.find('input[class="h_active[]"]').val();
var base_url =window.location.origin + '/test/' + 'planning/planning/get_id';
            var edit_url=window.location.origin + '/test/' + 'planning/planning/edit';
          // console.log(tr);
            get_data(base_url,{id:id},function(data){

                $.each(data, function(i,item){
                    $('#txt_year').val('');
                    $('#txt_seq').val( '');
                    $('#dp_type').select2('val', '');
                    $('#dp_goal').select2('val', '');
                    $('#dp_objective').select2('val', '');
                    $('#txt_activity_no').val( '');
                    $('#txt_activity_name').val( '');
                    $('#txt_detailes').val( '');
                    $('#dp_project_id').select2('val', '');


                    $('#txt_year').val(item.YEAR);
                    $('#txt_seq').val( item.SEQ);
                    $('#dp_type').select2('val',  item.TYPE);
                    $('#dp_goal').select2('val',  item.GOAL);
                    $('#dp_objective').select2('val',  item.OBJECTIVE);
                    $('#txt_activity_no').val( item.ACTIVITY_NO);
                    $('#txt_activity_name').val( item.ACTIVITY_NAME);
                    $('#txt_detailes').val( item.DETAILES);

                    if($("#dp_type").val()==2)
                    {


                        $("#proj").removeClass("hidden");
                        $('#dp_project_id').select2('val',item.PROJECT_ID);
                    }
                    else
                    {

                        $('#proj').addClass("hidden");
                        $('#dp_project_id').select2('val', '');
                    }

                    $('#planning_from').attr('action',edit_url);
                    resetValidation($('#planning_from'));
                    $('#planningModal').modal();
                });
            });
        } );

        /*$('#planning_tb tbody').on('dblclick', 'tr', function () {
            id= $(this).find('input').val();

    alert();
        } );*/

    });
    ajax_delete
    if (typeof initFunctions == 'function') {
        initFunctions();
    }

</script>
