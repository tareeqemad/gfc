<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 29/01/20
 * Time: 09:50 ص
 */

$MODULE_NAME= 'training';
$TB_NAME= 'advertisement';
?>

//<script>

    $('.sel2').select2();
    function clear_form(){
        clearForm($('#advertisement_form'));
        $('.sel2').select2('val','');
    }


    function values_search(add_page){
        var values=
        {page:1, adver_type:$('#dp_adver_type').val(), adver_id:$('#txt_adver_id').val()
           ,adver_title:$('#txt_adver_title').val() , start_date:$('#txt_start_date').val(),
            end_date:$('#txt_end_date').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
        get_data('<?= base_url('training/advertisement/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }









    //</script>
