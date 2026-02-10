<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 8/3/2019
 * Time: 11:40 AM
 */
?>

//<script>

    finBankHideShow();

    function finBankHideShow() {

        var financial_payment_account_id_cashFlow = $('#txt_financial_payment_account_id').attr('data-cash-flow');
        var customer_type = $('#txt_customer_type').val();

        var customer_id_cashFlow = $('#h_txt_customer_name').attr('data-cash-flow');

        console.log('',customer_type,customer_id_cashFlow);

        if (financial_payment_account_id_cashFlow == 5 || (customer_id_cashFlow == 5 && customer_type == 1)) {

            $('#dp_bk_fin_id').closest('div.form-group').show();

        } else {

            $('#dp_bk_fin_id').closest('div.form-group').hide();
            $('#dp_bk_fin_id').val(-1);

        }
    }

	
	    $('select[name="deduction_account_type[]"]').change(function () {

        var type = $(this).val();
        var customerType = $('select[name="d_customer_account_type[]"]',$(this).closest('tr'));

        if (type == 2) {

            customerType.show();
        } else
        {
            customerType.hide();

        }

        $('option:not(:selected)',customerType).hide();

    });

    $('select[name="deduction_account_type[]"]').change();

    //</script>