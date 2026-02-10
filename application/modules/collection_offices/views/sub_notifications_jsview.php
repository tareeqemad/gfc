<?php

$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Sub_notifications';
?>

//<script>


    function clear_form(){
        clearForm($('#sub_notifications_form'));
        $('.sel2').select2('val','');
    }


    function values_search(add_page){
        var values=
        {page:1, branch_no:$('#txt_branch_no').val(),
            disclosure_ser:$('#txt_disclosure_ser').val(),
            sub_no:$('#txt_sub_no').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
        get_data('<?= base_url('collection_offices/Sub_notifications/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }


    function changeStatus_(type,val){

        get_data('<?= base_url('collection_offices/Sub_notifications/changeStatus')?>',{id: val},function(data){

            if(data !='0')
            {
                success_msg('رسالة','تم ارسال الاخطار');
                search();
            }

        });


    }

    //</script>
