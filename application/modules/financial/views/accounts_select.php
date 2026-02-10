<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 12:22 PM
 */



?>

<div class="row">


    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-3">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>
        <?= $tree ?>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>
    $(function () {

        $('#accountsModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        });

        $('#accounts').tree();
    });

    function select_account(id){
        var level = $('.tree li > span.selected').attr('data-id').length;

        if(level >=5){
            var text = $('.tree li > span.selected').text();
            var cr = parent.$('#dp_curr_id');

        console.log('',cr.length);

         if( parent.$('#dp_curr_id').attr('data-curr') !='false')
            if(cr != undefined && cr.length > 0)
                if($(cr).val() != $('.tree li > span.selected').attr('data-curr'))
                {
                    alert('تحذير عملة الحساب تختلف عن عملة القيد ؟!');
                    return;
                }

            parent.$('#$txt').val(text);
            parent.$('#h_$txt').val(id);
            if( parent.$('#$txt').attr('data-balance') !='false')

             if (typeof  parent.update_balance == 'function') {
         parent.update_balance(parent.$('#h_$txt'));
    }



            parent.$('#report').modal('hide');

        }
    }

</script>

SCRIPT;

sec_scripts($scripts);



?>

