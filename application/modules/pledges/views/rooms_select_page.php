<?php
/**
 * User: mkilani
 * Date: 16/01/21
 */
// $room_accounts_cons

$accounts_arr = $room_accounts_cons;

function get_account($accounts_arr, $branch=0, $col){
    $key= array_search($branch, array_column($accounts_arr, 'CON_NO'));
    return $accounts_arr[$key][$col];
}

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-4">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>
                <input type="text" id="search-tbl" data-set="rooms_tb" class="form-control" placeholder="بحث">
            </div>
        </div>
        <div id="container">
            <table class="table selected-red" id="rooms_tb" data-container="container">
                <thead>
                <tr>
                    <th>رقم الغرفة</th>
                    <th>اسم الغرفة</th>
                    <th>اسم الطابق</th>
                    <th>اسم المبنى</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($rooms_data as $row) : ?>
                    <tr ondblclick="javascript:select_room( '<?=$row['ROOM_ID']?>', '<?=str_replace('"','',$row['ROOM_NAME'])?>', '<?=get_account($accounts_arr, $row['BRANCH_ID'],'ACCOUNT_ID')?>', '<?=get_account($accounts_arr, $row['BRANCH_ID'],'ACOUNT_NAME')?>' );">

                        <td><?=$row['ROOM_ID']?></td>
                        <td><?=$row['ROOM_NAME']?></td>
                        <td><?=$row['ROOM_PARENT_NAME']?></td>
                        <td><?=$row['BULIDING_NAME']?></td>

                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT

<script>

    function select_room(id, name, account_id, account_name) {
        var text = name;

        //parent.$('#$ txt').val(text); -- removed 202210
        //parent.$('#h_$ txt').val(id); -- removed 202210
        
        parent.$('#$txt').val(account_name);
        parent.$('#h_$txt').val(account_id);
        
        parent.$('#txt_room_id').val(id);
        parent.$('#h_room_id').val(id);
        parent.$('#txt_room_name').val(text);
        
        parent.$('#div_customer_account_type1').show();

        parent.$('#report').modal('hide');
    }

</script>

SCRIPT;

sec_scripts($scripts);

?>
