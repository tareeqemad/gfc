<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'gedco_issues';
 $delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_sub_details");
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;
$c_branch=0;
?>

<div class="tb_container">
<table class="table" id="details_tb1" data-container="container"  align="center">
<thead>
<tr>
    <th>اسم المدعي عليه</th>
    <th >عنوانه</th>
    <th >الفرع</th>




</tr>
</thead>
<tbody>
<?php if(count($details) <= 0) {  // ادخال ?>

<tr>


    <td>
        <input type="text" data-val="true" placeholder="اسم المدعي عليه" name="d_name[]"       id="txt_d_name_<?=$count?>" class="form-control" data-val-required="حقل مطلوب">
        <input  type="hidden" name="h_txt_issue_ser[]"   id="h_txt_issue_ser_<?=$count?>" data-val="false" data-val-required="required" >
        <input type="hidden" name="d_ser[]" id="d_ser_id_<?=$count?>" value="0"  />
        <input type="hidden" name="h_defendant_count[]" id="h_defendant_count_<?=$count?>"  />
    </td>
    <td >
        <input type="text" data-val="true" placeholder="عنوانه"     name="d_addres[]"       id="txt_d_addres_<?=$count?>" class="form-control" data-val-required="حقل مطلوب" >
    </td>

    <td>
        <select name="d_branch[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_d_branch_<?=$count?>" class="form-control" data-val-required="حقل مطلوب">
            <?php foreach($branches as $row) :?>
                <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>
            <?php endforeach; ?>
        </select>

    </td>

            </tr>



<?php
    $count++;

}else if(count($details) > 0) { // تعديل
    $count = -1;
    foreach($details as $row1) {
        ++$count+1;



        ?>

        <tr>


            <td>
                <input type="text" data-val="true" placeholder="اسم المدعي عليه" name="d_name[]"  id="txt_d_name_<?=$count?>" class="form-control" value="<?=$row1['D_NAME']?>" data-val-required="حقل مطلوب">
                <input  type="hidden" name="h_txt_issue_ser[]"   id="h_txt_issue_ser_<?=$count?>" data-val="false" data-val-required="required" value="<?=$row1['ISSUES_SER']?>"  >
                <input type="hidden" name="d_ser[]" id="d_ser_id_<?=$count?>" value="<?=$row1['D_SER']?>"  />
                <input type="hidden" name="h_defendant_count[]" id="h_defendant_count_<?=$count?>"  />
            </td>
            <td >
                <input type="text" data-val="true" placeholder="عنوانه"     name="d_addres[]"       id="txt_d_addres_<?=$count?>" class="form-control" value="<?=$row1['D_ADDRES']?>" data-val-required="حقل مطلوب" >
            </td>

            <td>


                <?php
                if($this->user->branch !=1)
                {
                foreach($branches_all as $row) {
                    if($row['NO']==$row1['D_BRANCH'] and $row['NO']==$this->user->branch )
                    {
                       continue;
                    }
                    else
                    {
                        $c_branch++;
                        break;
                    }

                }
                if($c_branch==0)
                {

                    $branches_all=$branches;
                }
                else
                {
                    $branches_all=$branches_all;


                }
                    //
                }
                ?>



                <select name="d_branch[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_d_branch_<?=$count?>" class="form-control" data-val-required="حقل مطلوب">
                    <?php foreach($branches_all as $row) {?>
                        <?php

                            ?>
                            <option value="<?= $row['NO'] ?>"  <?PHP if ($row['NO']==$row1['D_BRANCH']){ echo " selected"; } ?> ><?= $row['NAME'] ?></option>
                        <?php



        } ?>
                </select>

            </td>





        </tr>
    <?php

    }
}

?>
</tbody>

<tfoot>
<tr>
    <th></th>
    <th></th>
    <th></th>


    
</tr>
<tr>
    <th>
        <?php

        if ($c_branch==0)
        {
        ?>
        <a   onclick="javascript:add_row(this,'input:text,input:hidden[name^=d_ser],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
<?php }?>
    </th>
    <th></th>
    <th></th>


    
</tr>

</tfoot>
</table></div>
