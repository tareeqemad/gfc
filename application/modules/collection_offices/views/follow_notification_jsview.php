<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/12/19
 * Time: 05:01 م
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Follow_notification';
?>

//<script>

    $('.sel2').select2();
    function clear_form(){
        clearForm($('#follow_notification_form'));
        $('.sel2').select2('val','');
    }


    function values_search(add_page){
        var values=
        {page:1, branch_no:$('#txt_branch_no').val(),
            disclosure_ser:$('#txt_disclosure_ser').val(),
            noti_status:$('#dp_status').val(),
            sub_no:$('#txt_sub_no').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
        get_data('<?= base_url('collection_offices/Follow_notification/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function values_search_trading(add_page){
        var values=
        {page:1, branch_no:$('#txt_branch_no').val(),
            disclosure_ser:$('#txt_disclosure_ser').val(),
            noti_status:$('#dp_status').val(),
            sub_no:$('#txt_sub_no').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search_trading(){
        var values= values_search_trading(1);
        get_data('<?= base_url('collection_offices/Follow_notification/get_page_trading')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }


    function changeStatus_(status,val){

        get_data('<?= base_url('collection_offices/Follow_notification/changeStatus')?>',{id: val, status: status},function(data){

            if(data !='0')
            {
                search();
            }

        });


    }

    //</script>
