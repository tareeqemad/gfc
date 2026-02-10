<?php
/**
 * User: mkilani
 * Date: 16/01/21
 */

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
                </tr>
                </thead>
                <tbody>

                <?php foreach ($rooms_data as $row) : ?>
                    <tr ondblclick="javascript:select_room( '<?=$row['ROOM_ID']?>', '<?=$row['ROOM_NAME']?>' );">

                        <td><?=$row['ROOM_ID']?></td>
                        <td><?=$row['ROOM_NAME']?></td>
                        <td><?=$row['ROOM_PARENT_NAME']?></td>

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

    function select_room(id, name) {
        var text = name;

        parent.$('#$txt').val(text);
        parent.$('#h_$txt').val(id);
        parent.$('#report').modal('hide');
    }

</script>

SCRIPT;

sec_scripts($scripts);

?>
