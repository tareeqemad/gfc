<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'gedco_issues';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$isCreate2=isset($details_ins) && count($details_ins)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_sub_details");
$count_pay=0;

?>
<div class="tb_container">
<table class="table" id="details_ins_tb3" data-container="container"  align="center">
<thead>
<tr>
    <th  style="width: 16%">رقم الطلب</th>
    <th style="width: 16%" >سنة الطلب</th>
    <th style="width: 16%">نوع الطلب</th>





</tr>
</thead>
<tbody>
<?php if(count($details_ins) <= 0) {  // ادخال ?>

    <tr>
        <td>
            <input type="text" data-val="true" placeholder="رقم الطلب"  name="req_no[]" id="txt_req_no_<?=$count_pay?>" class="form-control">
            <input type="hidden" name="seq12[]" id="seq1_id12[]" value="0"  />
            <input  type="hidden" name="h_txt_issue_req_ser[]"   id="h_txt_issue_req_ser_<?=$count_pay?>" data-val="false" data-val-required="required" >
            <input type="hidden" name="h_request_count[]" id="h_request_count_<?=$count_pay?>"  />
        </td>

        <td>
            <input type="text" data-val="true" placeholder="سنة الطلب"  name="req_year[]" id="txt_req_year_<?=$count_pay?>" class="form-control"">
        </td>
        <td>
            <input type="text" data-val="true" placeholder="نوع الطلب"  name="req_type[]" id="txt_req_type_<?=$count_pay?>" class="form-control">
        </td>


    </tr>



<?php
    $count_pay++;
}else if(count($details_ins) > 0) { // تعديل
    $count_pay = -1;
    foreach($details_ins as $row2) {
        ++$count_pay+1


        ?>

        <tr>
            <td>
                <input type="text" data-val="true" placeholder="رقم الطلب"  name="req_no[]" id="txt_req_no_<?=$count_pay?>" class="form-control" value="<?=$row2['REQ_NO']?>">
                <input type="hidden" name="seq12[]" id="seq1_id12[]" value="<?=$row2['SER']?>"  />
                <input  type="hidden" name="h_txt_issue_req_ser[]"   id="h_txt_issue_req_ser_<?=$count_pay?>" data-val="false" data-val-required="required" value="<?=$row2['ISSUES_SER']?>" >
                <input type="hidden" name="h_request_count[]" id="h_request_count_<?=$count_pay?>"  />
            </td>

            <td>
                <input type="text" data-val="true" placeholder="سنة الطلب"  name="req_year[]" id="txt_req_year_<?=$count_pay?>" class="form-control" value="<?=$row2['REQ_YEAR']?>">
            </td>
            <td>
                <input type="text" data-val="true" placeholder="نوع الطلب"  name="req_type[]" id="txt_req_type_<?=$count_pay?>" class="form-control" value="<?=$row2['REQ_TYPE']?>">
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
     <a id="add_new_row" onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq12],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
    </th>
    <th></th>
    <th></th>



    
</tr>

</tfoot>
</table></div>
