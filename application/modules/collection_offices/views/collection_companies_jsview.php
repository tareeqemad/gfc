<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/12/19
 * Time: 05:01 م
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Collection_companies';
?>

//<script>
    $('.sel2').select2();
    function clear_form(){
        clearForm($('#collection_companies_form'));
        $('.sel2').select2('val','');
    }


    function values_search(add_page){
        var values=
        {page:1, license_no:$('#txt_license_no').val(), company_name:$('#txt_company_name').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function showEmployee(ser){
        $('#myModal').modal();
        get_data('<?= base_url('collection_offices/Collection_companies/public_get_employee') ?>', {id: ser}, function (data) {
            $('#div_employees').html(data);
        }, 'html');
    }

    function search(){
        var values= values_search(1);
        get_data('<?= base_url('collection_offices/Collection_companies/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }


    function trans_values_search(add_page){
        var values=
        {page:1, branch_no:$('#txt_branch_no').val(),
            disclosure_ser:$('#txt_disclosure_ser').val(),
            sub_no:$('#txt_sub_no').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function trans_search(){
        var values= trans_values_search(1);
        get_data('<?= base_url('collection_offices/Collection_companies/trans_get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function transOffice_(sub_ser){
        $('#myModal').modal();
        $('#txt_sub_ser').val(sub_ser);
        /*get_data('<?= base_url('collection_offices/collection_companies/get_office') ?>', {sub_ser: sub_ser}, function (data) {
            if (data != null || data != '') {
                var json = jQuery.parseJSON(data);

                    $('#txt_sub_ser').val(json.SER_OFFICE);



            }

        }, 'html');*/

    }

    function save_(company_ser){

        var values=
        {page:1, company_ser:company_ser,
            sub_ser:$('#txt_sub_ser').val()
        };

        get_data('<?= base_url('collection_offices/Collection_companies/update_ser_office') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم اسناد الاشتراك للشركة بنجاح');
                $('#myModal').modal('hide');
                trans_search();
            }

        }, 'html');

    }

    function deleteSection(ser){
        var values=
        {page:1, section_ser:ser};

        get_data('<?= base_url('collection_offices/Collection_companies/deleteSection') ?>',values, function (data) {
            if (parseInt(data) >= 1) {
                success_msg('رسالة', 'تم حذف قطاع العمل');
                reload_Page();

            }

        }, 'html');
    }



    //</script>
