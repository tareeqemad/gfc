<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/06/15
 * Time: 10:13 ص
 */

$details= $amendment_details;
$count = 0;

$sum_amount_add=0;
$sum_amount_remove=0;

function select_branch($branches, $name='branch', $cnt=0, $val=0){
    $select_branch='<select id="dp_'.$name.'_'.$cnt.'" name="'.$name.'[]" class="form-control" >';
    $select_branch.='<option></option>';
    foreach($branches as $row){
        $select_branch.='<option '.($val==$row['NO']?'selected':'').' value="'.$row['NO'].'" >'.$row['NAME'].'</option>';
    }
    $select_branch.='</select>';
    return $select_branch;
}

?>

<?php if($amendment_type==1){ ?>
<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 10%">المقر المضاف اليه</th>
            <th style="width: 20%">الباب المضاف اليه</th>
            <th style="width: 30%">الفصل المضاف اليه</th>
            <th style="width: 15%">المبلغ المضاف</th>
        </tr>
        </thead>

        <tbody>
        <?php if(count($details) <= 0) { ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td><?=select_branch($branches,'to_branch',$count)?></td>
                <td></td>
                <td>
                    <input type="hidden" name="ser[]" value="0" />
                    <select name="section_add[]" class="form-control" id="dp_section_add_<?=$count?>" /></select>
                </td>
                <td>
                    <input name="amount_add[]" class="form-control" id="txt_amount_add_<?=$count?>" />
                </td>
            </tr>

        <?php
        }else
            $count = -1;
        ?>

        <?php
        foreach($details as $row) {
            $count++;
            $sum_amount_add=$sum_amount_add+$row['AMOUNT_ADD'];
            ?>
            <tr>
                <td><?=$count+1?></td>
                <td><?=select_branch($branches,'to_branch',$count,$row['TO_BRANCH'])?></td>
                <td><input class="form-control" readonly value="<?=$row['CHAPTER_ADD_NAME']?>" /></td>
                <td>
                    <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                    <select name="section_add[]" class="form-control" id="dp_section_add_<?=$count?>" data-val="<?=$row['SECTION_ADD']?>" /></select>
                </td>
                <td>
                    <input name="amount_add[]" class="form-control" id="txt_amount_add_<?=$count?>" value="<?=$row['AMOUNT_ADD']?>" />
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and $adopt==1 )) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th><?=$sum_amount_add?></th>
        </tr>
        </tfoot>
    </table>
</div>
<?php } elseif($amendment_type==2){ ?>
<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 10%">المقر المخفض منه</th>
            <th style="width: 20%">الباب المخفض منه</th>
            <th style="width: 30%">الفصل المخفض منه</th>
            <th style="width: 15%">المبلغ المخفض</th>
        </tr>
        </thead>

        <tbody>
        <?php if(count($details) <= 0) { ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td><?=select_branch($branches,'from_branch',$count)?></td>
                <td></td>
                <td>
                    <input type="hidden" name="ser[]" value="0" />
                    <select name="section_remove[]" class="form-control" id="dp_section_remove_<?=$count?>" /></select>
                </td>
                <td>
                    <input name="amount_remove[]" class="form-control" id="txt_amount_remove_<?=$count?>" />
                </td>
            </tr>

        <?php
        }else
            $count = -1;
        ?>

        <?php
        foreach($details as $row) {
            $count++;
            $sum_amount_remove=$sum_amount_remove+$row['AMOUNT_REMOVE'];
            ?>
            <tr>
                <td><?=$count+1?></td>
                <td><?=select_branch($branches,'from_branch',$count,$row['FROM_BRANCH'])?></td>
                <td><input class="form-control" readonly value="<?=$row['CHAPTER_REMOVE_NAME']?>" /></td>
                <td>
                    <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                    <select name="section_remove[]" class="form-control" id="dp_section_remove_<?=$count?>" data-val="<?=$row['SECTION_REMOVE']?>" /></select>
                </td>
                <td>
                    <input name="amount_remove[]" class="form-control" id="txt_amount_remove_<?=$count?>" value="<?=$row['AMOUNT_REMOVE']?>" />
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and $adopt==1 )) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th><?=$sum_amount_remove?></th>
        </tr>
        </tfoot>
    </table>
</div>
<?php } elseif($amendment_type==3){ ?>
<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 7%">المقر المضاف اليه</th>
            <th style="width: 12%">الباب المضاف اليه</th>
            <th style="width: 15%">الفصل المضاف اليه</th>
            <th style="width: 7%">المبلغ المضاف</th>
            <th style="width: 7%">المقر المخفض منه</th>
            <th style="width: 12%">الباب المخفض منه</th>
            <th style="width: 15%">الفصل المخفض منه</th>
            <th style="width: 7%">المبلغ المخفض</th>
        </tr>
        </thead>

        <tbody>
        <?php if(count($details) <= 0) { ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td><?=select_branch($branches,'to_branch',$count)?></td>
                <td></td>
                <td>
                    <input type="hidden" name="ser[]" value="0" />
                    <select name="section_add[]" class="form-control" id="dp_section_add_<?=$count?>" /></select>
                </td>
                <td>
                    <input name="amount_add[]" class="form-control" id="txt_amount_add_<?=$count?>" />
                </td>
                <td><?=select_branch($branches,'from_branch',$count)?></td>
                <td></td>
                <td>
                    <select name="section_remove[]" class="form-control" id="dp_section_remove_<?=$count?>" /></select>
                </td>
                <td>
                    <input name="amount_remove[]" class="form-control" id="txt_amount_remove_<?=$count?>" />
                </td>
            </tr>

        <?php
        }else
            $count = -1;
        ?>

        <?php
        foreach($details as $row) {
            $count++;
            $sum_amount_remove=$sum_amount_remove+$row['AMOUNT_REMOVE'];
            $sum_amount_add=$sum_amount_add+$row['AMOUNT_ADD'];
            ?>
            <tr>
                <td><?=$count+1?></td>
                <td><?=select_branch($branches,'to_branch',$count,$row['TO_BRANCH'])?></td>
                <td><input class="form-control" readonly value="<?=$row['CHAPTER_ADD_NAME']?>" /></td>
                <td>
                    <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                    <select name="section_add[]" class="form-control" id="dp_section_add_<?=$count?>" data-val="<?=$row['SECTION_ADD']?>" /></select>
                </td>
                <td>
                    <input name="amount_add[]" class="form-control" id="txt_amount_add_<?=$count?>" value="<?=$row['AMOUNT_ADD']?>" />
                </td>
                <td><?=select_branch($branches,'from_branch',$count,$row['FROM_BRANCH'])?></td>
                <td><input class="form-control" readonly value="<?=$row['CHAPTER_REMOVE_NAME']?>" /></td>
                <td>
                    <select name="section_remove[]" class="form-control" id="dp_section_remove_<?=$count?>" data-val="<?=$row['SECTION_REMOVE']?>" /></select>
                </td>
                <td>
                    <input name="amount_remove[]" class="form-control" id="txt_amount_remove_<?=$count?>" value="<?=$row['AMOUNT_REMOVE']?>" />
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and $adopt==1 )) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th><?=$sum_amount_add?></th>
            <th></th>
            <th></th>
            <th></th>
            <th><?=$sum_amount_remove?></th>
        </tr>
        </tfoot>
    </table>
</div>
<?php } ?>
