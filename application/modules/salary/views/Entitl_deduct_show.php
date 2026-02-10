<?php

$MODULE_NAME= 'salary';
$TB_NAME= 'Entitl_deduct';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= '';
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$tax_con = array(0=>'لا',1=>'نعم',2=>'');

if($HaveRs){
    $this_date= $rs['MONTH'];
    $this_month= substr($this_date,-2);
    $prev_date= $this_date-1;
    $next_date= $this_date+1;

    if($this_month=='01'){
        $prev_date= $this_date-89;
    }
    if($this_month=='12'){
        $next_date= $this_date+89;
    }
}

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title.(($HaveRs)?' - '.$rs['EMP_NO_NAME']:'')?></div>
        <ul>
            <?php if( 0 and HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php if($HaveRs): ?>
                <li><a title="السابق"  href="<?=$get_url.'/'.$rs['EMP_NO'].'/'.$prev_date.'/'.$page_act?>"><i class="glyphicon glyphicon-forward"></i> <?=$prev_date?> </a> </li>
                <li><a title="التالي"  href="<?=$get_url.'/'.$rs['EMP_NO'].'/'.$next_date.'/'.$page_act?>"> <?=$next_date?> <i class="glyphicon glyphicon-backward"></i>  </a> </li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="col-md-12 alert alert-info">

                    <input type="hidden" name="page_act" value="<?=$page_act?>" />

                    <div class="form-group col-sm-2">
                        <label class="control-label">الموظف</label>
                        <div>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($emp_no_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['EMP_NO']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">الحالة الاجتماعية</label>
                        <div>
                            <select name="status" id="dp_status" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($status_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['STATUS']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">الشهر</label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['MONTH']:""?>" name="month" id="txt_month" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">صافي الراتب</label>
                        <div>
                            <input style="background-color: #fffbd9" type="text" value="<?=$HaveRs?$rs['NET_SALARY']:""?>" name="net_salary" id="txt_net_salary" class="form-control" />
                        </div>
                    </div>


                    <div style="clear: both"></div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">المقر الرئيسي</label>
                        <div>
                            <select name="bran" id="dp_bran" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($bran_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['BRAN']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المقر الفرعي</label>
                        <div>
                            <select name="branch" id="dp_branch" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($branch_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['BRANCH']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الادارة</label>
                        <div>
                            <select name="department" id="dp_department" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($department_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['DEPARTMENT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div style="clear: both"></div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المسمى المهني</label>
                        <div>
                            <select name="w_no" id="dp_w_no" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($w_no_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['W_NO']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المسمى الوظيفي</label>
                        <div>
                            <select name="w_no_admin" id="dp_w_no_admin" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($w_no_admin_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['W_NO_ADMIN']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المؤهل العلمي</label>
                        <div>
                            <select name="q_no" id="dp_q_no" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($q_no_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['Q_NO']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div style="clear: both"></div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">نوع التعيين</label>
                        <div>
                            <select name="emp_type" id="dp_emp_type" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($emp_type_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['EMP_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">الدرجة</label>
                        <div>
                            <select name="degree" id="dp_degree" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($degree_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['DEGREE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group col-sm-1">
                        <label class="control-label">العلاوة الدورية</label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['PERIODICAL_ALLOWNCES']:""?>" name="periodical_allownces" id="txt_periodical_allownces" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">البنك</label>
                        <div>
                            <select name="bank" id="dp_bank" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($bank_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['BANK']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الحساب</label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['ACCOUNT']:""?>" name="account" id="txt_account" class="form-control" />
                        </div>
                    </div>

                </div>


                <div class="col-md-6 alert alert-success">
                    <strong style="margin-right: 45%"> الاستحقاقات </strong>
                    <table class="table" id="additions_data_tb" data-container="container">
                        <thead>
                            <tr>
                                <th>رقم البند</th>
                                <th>البند</th>
                                <th>القيمة</th>
                                <th>خاضع للضريبة</th>
                                <th>البيان</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $additions_sum= 0;
                            foreach($additions_data as $row){
                                $additions_sum+= $row['VALUE'];
                        ?>
                            <tr>
                                <td><?=$row['CON_NO']?></td>
                                <td><?=$row['CON_NO_NAME']?></td>
                                <td><?=$row['VALUE']?></td>
                                <td><?=$tax_con[ $row['IS_TAXED'] ]?></td>
                                <td><?=$row['NOTES']?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td>المجموع</td>
                                <td style="background-color: #dff0d8"><?=$additions_sum?></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                <div class="col-md-6 alert alert-danger">
                    <strong style="margin-right: 45%"> الاستقطاعات </strong>
                    <table class="table" id="discounts_data_tb" data-container="container">
                        <thead>
                        <tr>
                            <th>رقم البند</th>
                            <th>البند</th>
                            <th>القيمة</th>
                            <th>خاضع للضريبة</th>
                            <th>البيان</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $discounts_sum= 0;
                            foreach($discounts_data as $row){
                                $discounts_sum+= $row['VALUE'];
                        ?>
                            <tr>
                                <td><?=$row['CON_NO']?></td>
                                <td><?=$row['CON_NO_NAME']?></td>
                                <td><?=$row['VALUE']?></td>
                                <td><?=$tax_con[ $row['IS_TAXED'] ]?></td>
                                <td><?=$row['NOTES']?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td>المجموع</td>
                            <td style="background-color: #f2dede"><?=$discounts_sum?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>


            <div class="modal-footer">

            </div>

        </form>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>

reBind();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();
} // reBind


SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }


    </script>
SCRIPT;

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}
    
    $('.inline_form input').prop('readonly',1);
    $('.sel2').select2('readonly',1);

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
