<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 28/12/19
 * Time: 11:26 ص
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Pay_order';
?>

//<script>

    $('.sel2').select2();
    function clear_form(){
        clearForm($('#pay_order_form'));
        $('.sel2').select2('val','');
    }


    function values_search(add_page){
        var values=
        {page:1, branch_no:$('#txt_branch_no').val(),branch_no_dp:$('#dp_branch_no').val(),
            company_no:$('#dp_company_no').val(),
            sub_no:$('#txt_sub_no').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
        get_data('<?= base_url('collection_offices/Pay_order/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function pay_(ser,month,net_to_pay){

            $('#Details').modal();
            $('#h_txt_subs_no').val(ser);
            $('#h_txt_for_month').val(month);
            $('#h_txt_net_to_pay').val(net_to_pay);

    }









    //</script>
