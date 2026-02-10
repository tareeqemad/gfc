<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/02/20
 * Time: 10:05 ص
 */

$MODULE_NAME= 'training';
$TB_NAME= 'unPaidTrain';
?>

//<script>

    $('.sel2').select2();

    function clear_form(){
        clearForm($('#unPaidTrain_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values=
        {page:1, name:$('#txt_name').val(),branch:$('#dp_branch').val(),
            manage:$('#dp_manage').val(),
            branch:$('#dp_branch').val(),
            id:$('#txt_id').val(),
            start_date:$('#txt_start_date').val(),
            end_date:$('#txt_end_date').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('<?= base_url('training/unPaidTrain/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }



    //</script>
