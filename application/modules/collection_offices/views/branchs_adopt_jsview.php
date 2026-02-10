<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/12/19
 * Time: 01:05 م
 */

$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Branchs_adopt';
?>

//<script>


    function clear_form(){
        clearForm($('#branchs_adopt_form'));
        $('.sel2').select2('val','');
    }
    $('.sel2').select2();

    function values_search(add_page){
        var values=
        {page:1, branch_no:$('#txt_branch_no').val(),branch_no_dp:$('#dp_branch_no').val(),
            disclosure_ser:$('#txt_disclosure_ser').val()
        };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
        get_data('<?= base_url('collection_offices/Branchs_adopt/get_page')?>',values ,function(data){
            $('#container').html(data);
        },'html');
    }


    function changeStatus_(type,val){

        get_data('<?= base_url('collection_offices/Branchs_adopt/adopt')?>',{id: val},function(data){

            if(data !='0')
            {
                success_msg('رسالة','تم الاعتماد بنجاح');
                search();
            }

        });


    }

    function details_(ser){
        get_data('<?= base_url('collection_offices/Branchs_adopt/get_sub')?>',{sub_ser: ser} ,function(data){
            $('#container_sub').html(data);

            $('#modalSub').modal();
            $('#txt_dis_ser').val(ser);
        },'html');

    }

    function changeSubStatus_(val){


        get_data('<?= base_url('collection_offices/Branchs_adopt/adopt_sub')?>',{id_ser: val},function(data){

            if(data !='0')
            {
                success_msg('رسالة','تم الاعتماد بنجاح');

                details_($('#txt_dis_ser').val());
                search();
            }

        });

    }



    //</script>
