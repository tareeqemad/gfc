<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 05/07/20
 * Time: 01:50 م
 */



$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Procedures';
?>

//<script>

    $('.sel2').select2();
    function clear_form(){
        clearForm($('#Procedures_form'));
        $('.sel2').select2('val','');
        //$('#dp_proc_type').select2('val',1);
    }

    function values_search(add_page){
        var values=
        {page:1, company_no:$('#dp_company_no').val(),
            sub_no:$('#txt_sub_no').val(),
            for_month:$('#txt_for_month').val(),
            proc_type:$('#dp_proc_type').val()
        };

        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('<?= base_url('collection_offices/Procedures/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function show_details(id,type){
     $('#myModal').modal();
     get_data('<?= base_url('collection_offices/Procedures/public_get_details') ?>', {id: id, type: type}, function (data) {
     $('#div_details').html(data);
     }, 'html');
     }



    //</script>
