<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 4/10/2019
 * Time: 8:29 AM
 */
?>

//<script>

    function financialAccounts() {

        if (confirm('هل تريد ترحيل الحسابات المالية؟')) {

            get_data('<?= base_url('settings/DataMigration/financialAccounts') ?>', {}, function (data) {

                alert(data);
            });
        }
    }

    function transferOutcomeCheck() {

        if (confirm('هل تريد ترحيل الشيكات الصادرة ؟')) {

            get_data('<?= base_url('settings/DataMigration/transferOutcomeCheck') ?>', {}, function (data) {

                alert(data);
            });
        }
    }

    function transferIncomeCheck() {

        if (confirm('هل تريد ترحيل الشيكات الواردة ؟')) {

            get_data('<?= base_url('settings/DataMigration/transferIncomeCheck') ?>', {}, function (data) {

                alert(data);
            });
        }
    }

    function changeCheckNumber() {

        $('#change_check_number_form').submit();

    }

    function changeOutCheckNumber() {

        $('#change_out_check_number_form').submit();

    }


    $('#change_check_number_form').submit(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){

            ajax_insert_update($(this),function(data){
                if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                    setTimeout(function(){

                        window.location.reload();

                    }, 1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
    });

    $('#change_out_check_number_form').submit(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){

            ajax_insert_update($(this),function(data){
                if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                    setTimeout(function(){

                        window.location.reload();

                    }, 1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
    });

    //</script>
