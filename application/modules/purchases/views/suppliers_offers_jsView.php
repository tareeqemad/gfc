<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 2/25/2019
 * Time: 9:16 AM
 */
$MODULE_NAME = 'purchases';
$TB_NAME = 'suppliers_offers';
$get_id_url = base_url("$MODULE_NAME/$TB_NAME/public_get_award_detailsxx/");
?>


//<script>

    QtyPriceTotal(); // for calulate total price of qty
    // for calculate Total Approce price
    $('.table').click(function () {

        //GEt the current element
        var current = $(this);
        var name = current.attr("href");

        //Remove the class from the previous element
        $('.selected').removeClass("selected");

        //Add the class to the current element
        current.addClass("selected");

        //Let's get back to work!
        return false;

    });

    function calcPrices() {
        $('input[data-type="approved-amount"]').keyup(function () {
            var tr = $(this).closest('tr');
            var dataVal = $(this).attr('data-val');
            calcDiscountClass(tr, dataVal);
            calcDiscountVal(tr, dataVal);
            calcApprovedPrice(tr, dataVal);
            calcTotalPrice(tr, dataVal);
            totalAppPrice();
        });

        $('input[data-type="discount-percent"]').keyup(function () {

            var tr = $(this).closest('tr');
            var dataVal = $(this).attr('data-val');
            calcDiscountClass(tr, dataVal);
            calcDiscountVal(tr, dataVal);
            calcApprovedPrice(tr, dataVal);
            calcTotalPrice(tr, dataVal);
            totalAppPrice();

        });


        //for change

        $('input[data-type="discount-value"]').keyup(function () {

            var tr = $(this).closest('tr');
            var dataVal = $(this).attr('data-val');
            calcDiscountClass(tr, dataVal);
            calcDiscountVal(tr, dataVal);
            calcApprovedPrice(tr, dataVal);
            calcTotalPrice(tr, dataVal);
            totalAppPrice();

        });

        $('input[data-type="discountclass"]').keyup(function () {
            var val = $(this).val();
            var tr = $(this).closest('tr');
            var dataVal = $(this).attr('data-val');
            var discount = $('input[data-type="discount-percent"][data-val="' + dataVal + '"]', tr).val();
            var price = parseFloat($('td[data-type="price"]', tr).text());
            var totalVal = (val / price  * 100);
            $('input[data-type="discount-percent"]', tr).val(totalVal.toFixed(2));
            calcDiscountVal(tr, dataVal);
            calcApprovedPrice(tr, dataVal);
            calcTotalPrice(tr, dataVal);
            totalAppPrice();



        });





        $('input[data-type="approved-total"]').keyup(function () {

            var tr = $(this).closest('tr');
            var val = $(this).val();
            var dataVal = $(this).attr('data-val');

            var amount = $('input[data-type="approved-amount"][data-val="' + dataVal + '"]', tr).val();
            var price = $('input[data-type="approved-price"][data-val="' + dataVal + '"]', tr).val();


            var discountPercent = val * 100 / (amount * price);


            $('input[data-type="discount-percent"]', tr).val(isNaNVal(discountPercent, 2));

            calcTotalPrice(tr, dataVal);
        });

        $('input[name*="suppliers_discount"]').keyup(function () {

            var val = $(this).val();
            var dataVal = $(this).attr('data-val');

            $('input[data-type="discount-percent"][data-val="' + dataVal + '"]').each(function (i) {
                $(this).val(val);
                $('input[data-type="discount-percent"]').keyup();
            });

        });

        $('input[name*="c_discount_value"]').not('input[data-type="discount-value"]').keyup(function () {

            var val = $(this).val();
            var dataVal = $(this).attr('data-val');

            $('input[data-type="discount-value"][data-val="' + dataVal + '"]').each(function (i) {
                $(this).val(val);
                $(this).keyup()
            });

            //$('input[data-type="discount-value"]').keyup();
        });
    }

    function calcDiscountVal(tr, dataVal) {
        var obj = $('input[data-type="discountclass"][data-val="' + dataVal + '"]', tr);
        var val = $(obj).val();
        var amount = $('input[data-type="approved-amount"][data-val="' + dataVal + '"]', tr).val();
        var discount = 0;
        if (amount > 0)
            discount = ( amount * val).toFixed(2);
        $('input[data-type="discount-value"][data-val="' + dataVal + '"]', tr).val(discount);
        console.log('', amount, val);
    }

    //  //this new
    function calcTotalPrice(tr, dataVal) {

        var obj = $('input[data-type="approved-amount"][data-val="' + dataVal + '"]', tr);
        var val = $(obj).val();
        var price = $('input[data-type="approved-price"][data-val="' + dataVal + '"]', tr).val();
        var total = $('td[data-type="approved-total"][data-val="' + dataVal + '"]', tr);
        if (val == '') return;
        if (val <= 0) {
            total.text(0);
            return;
        }
        var totalVal = (price * val);
        total.text(totalVal.toFixed(2));
        //   var approved_price=totalVal/amount;
        //    price.val(approved_price.toFixed(2));
        colorsRows();
        calcTotalsAndCounts();
    }


    function calcApprovedPrice(tr, dataVal) {

        var obj = $('input[data-type="approved-amount"][data-val="' + dataVal + '"]', tr);
        var val = $(obj).val();
        var price = parseFloat($('td[data-type="price"]', tr).text());
        console.log(price);
        var discount = $('input[data-type="discount-value"][data-val="' + dataVal + '"]', tr).val();
        // console.log('discount='+discount);
        var totalApprpvePrice = $('input[data-type="approved-price"][data-val="' + dataVal + '"]', tr);
        var total = $('td[data-type="approved-total"][data-val="' + dataVal + '"]', tr);
        // if (val == '') return;
        if (val <= 0) {
            total.val(0);
            return;
        }
        //   console.log('price='+price);
        //   console.log('val='+val);
        //   console.log('discount='+discount);
        var totalApproveVal = (price * val) - discount;
        var totalApproveClass = totalApproveVal/val;
        //  console.log('totalApproveClass='+totalApproveClass);
        // console.log('totalApproveVal='+totalApproveVal);
        totalApprpvePrice.val(totalApproveClass.toFixed(2));
        total.text(totalApproveVal);
    }


    //this new
    function calcDiscountClass(tr,dataVal) {
        var obj = $('input[data-type="discount-percent"][data-val="' + dataVal + '"]', tr);
        var val = $(obj).val();
        var price = parseFloat($('td[data-type="price"]', tr).text());
        var  discountClass = (price * val / 100).toFixed(2);
        $('input[data-type="discountclass"][data-val="' + dataVal + '"]', tr).val(discountClass);
        console.log('', discountClass, price, val);
    }

    function colorsRows() {
        $('select[name*="award_delay_decision"]').each(function () {
            var val = $(this).val();
            var tr = $(this).closest('tr');
            var totalAmount = parseInt($('td[data-type="amount"]', tr).text());
            console.log('', val);
            if (val == 1 || val == 3) {

                var amounts = $('input[data-type="approved-amount"]', tr).filter(function (index) {
                    return $(this).val() > 0;
                });

                if (amounts.length > 1) {
                    $(tr).css({background: 'rgba(255, 166, 0, 0.53)'});
                }
                var sumOfAmounts = 0;

                $(amounts).each(function () {

                    sumOfAmounts += parseInt($(this).val());
                });

                if (sumOfAmounts < totalAmount) {
                    $(tr).css({background: 'rgba(104, 181, 2, 0.39)'});
                }

                console.log('totals -> ', totalAmount, sumOfAmounts);

            } else if (val == 0) {

                $(tr).css({background: 'rgba(184, 50, 60, 0.27)'});
            }

        });
    }

    function calcTotalsAndCounts() {
        $('span[data-type="suppliers-totals"]').each(function () {
            var dataVal = $(this).attr('data-val');
            var sumOfApproved = 0;
            var approvedTotal = $('td[data-type="approved-total"][data-val="' + dataVal + '"]').filter(function () {
                return parseInt($(this).text()) > 0;
            });
            approvedTotal.each(function () {
                sumOfApproved += parseInt($(this).text());
            });
            $(this).text(' عدد البنود : ' + approvedTotal.length + '  , اجمالي الملبغ :  ' + sumOfApproved.toFixed(2));
        });
    }

    calcPrices();
    colorsRows();


    function apply_action() {
        get_data('<?= base_url('settings/notes/public_create')?>', {
            source_id: $('#txt_purchase_order_id').val(),
            source: 'purchase_order',
            notes: $('#txt_g_notes').val()
        }, function (data) {
            $('#notesModal').modal('hide');
            success_msg('رسالة', 'تم حفظ بيانات الملاحظة بنجاح ..');
        }, 'html');
    }

    $('input[data-type="approved-amount"]').keyup();

    //New Code
    function addRow(ser, class_id) {
        var count1 = $('#table_' + ser + ' tbody tr').length;
        //var count1 = parseInt($('#count_' + ser).val());
        var html = $.parseHTML('<tr  data-class-ids=' + class_id + '><td>' + count1  + ' <input type="hidden"  name="h_class_id[]" id="h_class_id_' + count1 + '>"  class="form-control col-sm-2"><input type="hidden" readonly name="ser[]" data-val="true" data-type="ser" id="ser' + count1 + '">' +
            '<td><select  name="CustId[]"  data-val="true"   id="CustId_' + count1 + '"  class="form-control sel2 " >' + suppliers_offers_data_options + '</select></td>' +
            '<td data-type="amount" data-val="true" id="amount' + count1 + '" class="amountxx"></td>' +
            '<td  data-type="price"  data-val="true" id="price' + count1 + '" class="price"></td>' +
            '<td data-type="totalPrice" data-val="true" class="totalP" id="APPROVED_PRICE' + count1 +'"> </td>' +
            '<td><input type="text" name="approved_amount[]" onkeyup="Amount(this)" data-val="true"  data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!" data-type="approved-amount" id="approved_amount' + count1 + '"  class="form-control balance "> </td>' +
            '<td><input type="text" name="class_discount[]"  min="0" data-val="true"  data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!" data-type="discount-percent" id="class_discount' + count1 + '"  class="form-control percent-input  "> </td>' +
            '<td><input type="text" name="discount_class[]"  data-val="true" data-type="discountclass"  id="discountclass' + count1 + '"   class="form-control " > </td>' +
            '<td><input type="text" name="c_discount_value[]" readonly min="0" data-val="true" data-type="discount-value"  data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!"  id="c_discount_value' + count1 + '"   class="form-control " > </td>' +
            '<td><input type="text" name="approved_price[]" readonly data-val="true" data-type="approved-price"  id="approved_price' + count1 + '"   class="form-control  "> </td>' +
            '<td data-val="true"  id="approved_total' + count1 + '" onchange="totalAppPrice()" data-type="approved-total"  class="total_" > 0 </td>' +
            '<td><textarea  rows="2" name="award_hints[]"  data-val="true"   id="award_hints_' + count1 + '"  class="form-control "></textarea></td>' +
            '<td><a href="javascript:;"   onclick="addDetail1(this)"><span class="glyphicon glyphicon-plus"></span></a><a ="javaschrefript:;" class="remove_tr" id="remove_tr" onclick="remove_tr(this)"><i class="glyphicon glyphicon-trash"></i></a></td>' +
            ' </tr>');
        $('#table_' + ser + ' tbody ').append(html);
        $('#count_' + ser).val(count1);
        $('select[name="CustId[]"]').change(function () {
            var selectElement = $(this);
            var selectedValue = $(this).val();
            if (selectedValue == 0){
                warning_msg('تنبيه يرجى اختيار الشركة');
                // Remove row from HTML Table
                $(this).closest('tr').css('background','tomato');
                $(this).closest('tr').fadeOut(800,function(){
                    $(this).closest('tr').remove();
                });
                return  -1;
            }
            //alert(selectedValue);
            var tr = $(this).closest('tr');
            var classId = $(tr).attr('data-class-ids');
            var classTr = $(this).closest('tr[data-class-id]');
            var arr = [];
            $.each($('input[name="c_array[]"]', classTr), function (i, val) {
                var customerarr = $(this).val();
                arr += customerarr + '';
            });

            var selectCount = $('select[name="CustId[]"]', classTr).not($(selectElement)).filter(function (index) {
                return $(this).val() === selectedValue;
            }).length;

            selectCount += $('input[name="c_array[]"]', classTr).filter(function (index) {
                return $(this).val() === selectedValue;
            }).length;

            if (selectCount <= 0) {
                get_data('<?= base_url('purchases/suppliers_offers/public_get_award_detailsxx') ?>', {
                    suplier_id: selectedValue,
                    class_id: classId
                }, function (data) {
                    console.log(data);
                    var len = data.length;
                    if (len > 0) {
                        // Read values
                        var supplier_offerid = jQuery.parseJSON(data)[0].SUPPLIERS_OFFERS_ID;
                        var ser_val = jQuery.parseJSON(data)[0].SER;
                        var amount_val = jQuery.parseJSON(data)[0].AMOUNT;
                        var price_val = jQuery.parseJSON(data)[0].PRICE;
                        var approved_amount_val = jQuery.parseJSON(data)[0].APPROVED_AMOUNT;
                        var discount_val = jQuery.parseJSON(data)[0].CLASS_DISCOUNT;
                        var class_discount_value = jQuery.parseJSON(data)[0].CLASS_DISCOUNT_VALUE;
                        var discount_value_class = jQuery.parseJSON(data)[0].DISCOUNT_VALUE_CLASS;
                        var approved_price = jQuery.parseJSON(data)[0].APPROVED_PRICE;
                        $('input[name="ser[]"]', tr).val(ser_val);
                        $('td[data-type="amount"]',tr).attr('data-val',supplier_offerid).text(amount_val);
                        $('td[data-type="price"]',tr).attr('data-val',supplier_offerid).text(price_val);
                        $('td[data-type="totalPrice"]', tr).attr('data-val',supplier_offerid).text(parseFloat(amount_val * price_val).toFixed(2));
                        $('input[name="approved_amount[]"]', tr).val(approved_amount_val);
                        $('input[name="class_discount[]"]', tr).val(discount_val);
                        $('input[name="c_discount_value[]"]', tr).val(class_discount_value);
                        $('input[name="discount_class[]"]', tr).val(discount_value_class);
                        $('input[name="approved_price[]"]', tr).val(approved_price);
                        $('td[data-type="approved-total"]', tr).attr('data-val',supplier_offerid).text(parseFloat(approved_price * approved_amount_val));
                        $('input[data-val]').attr('data-val',supplier_offerid);
                        $('.total_').attr('data-val',supplier_offerid);
                        $('.total_').val(supplier_offerid);
                        calcPrices();
                        calcDiscountVal();
                        calcDiscountClass();
                        calcApprovedPrice();
                        calcTotalPrice();
                        calcTotalsAndCounts();
                        QtyPriceTotal();
                        totalAppPrice();

                    } else {
                        $('input[name="ser[]"]', tr).text('');
                        $('td[data-type="amount"]',tr).text('');
                        $('td[data-type="price"]',tr).text('');
                        $('td[data-type="totalPrice"]', tr).text('');
                        $('input[name="class_discount[]"]', tr).val('');
                        $('input[name="discount_class[]"]', tr).val('');
                        $('input[name="c_discount_value[]"]', tr).val('');
                        $('input[name="approved_price[]"]', tr).text('0.0');
                    }
                }, 'html');
            } else {
                $(this).val(-1);
                danger_msg('الشركة مدخلة يرجى ادخال شركة اخرى');
                return -1;
            }

            if ($('select[name="CustId[]"]', classTr).not($(this)).filter(function (index) {
                return $(this).val() === selectedValue;

            }).length > 0) {
                $(this).val(-1);
                danger_msg('الشركة مدخلة يرجى ادخال شركة اخرى');
                return;
            }
            //in the regain code of selected customer
            var discountValue = $('input[name*="suppliers_discount"]', $('tr[data-sub-id="' + selectedValue + '"]')).val();
            $('input[name="class_discount[]"]', tr).val(discountValue);
            var discount = $('input[name*="discount_value"]', $('tr[data-sub-id="' + selectedValue + '"]')).val();
            $('input[name="c_discount_value[]"]', tr).val(discount);

        });

        $('input[data-type="approved-amount"],tr').keyup();
    }

    //حذف  من tr حذف شكلي
    function remove_tr (obj)
    {
        // Remove row from HTML Table
        $(obj).closest('tr').css('background','tomato');
        $(obj).closest('tr').fadeOut(800,function(){
            $(obj).closest('tr').remove();
        });
    } //end remove_tr
    //
    $('input[name*="suppliers_discount"]').keyup(function () {
        var val = $(this).val();
        var tr = $(this).closest('tr');
        var customerId = $(tr).attr('data-sub-id');
        var selectedCustomer = $('select[name="CustId[]"]').filter(function (index) {
            return $(this).val() == customerId
        });
        var selectedCustomerTr = $(selectedCustomer).closest('tr');
        $('input[name="class_discount[]"]', selectedCustomerTr).val(val);
        console.log(val, selectedCustomer, selectedCustomerTr);
    });

    $('input[name*="discount_value"]').keyup(function () {
        var val = $(this).val();
        var tr = $(this).closest('tr');
        var customerId = $(tr).attr('data-sub-id');
        var selectedCustomer = $('select[name="CustId[]"]').filter(function (index) {
            return $(this).val() == customerId
        });
        var selectedCustomerTr = $(selectedCustomer).closest('tr');
        $('input[name="c_discount_value[]"]', selectedCustomerTr).val(val);
        console.log(val, selectedCustomer, selectedCustomerTr);
    });
    function compute(tr) {
        /*  console.log(tr);*/
        $('input[name="amount[]"]', tr).keyup(function () {
            var value1 = $(this).val();
            // alert(value1);
            $('input[name="CUSTOMER_PRICE[]"]', tr).keyup(function () {
                var value2 = $(this).val();
                //alert(value2);
                var total = value1 * value2;
                $('input[name="APPROVED_PRICE[]"]').val(total.toFixed(2));
            });

        });
    }
    //for insert item detail table
    function addDetail(obj){
        var tr = obj.closest('tr');
        var dataVal =$(obj).attr('data-val');
        var ser1 = $('input[data-type="ser"][data-val="' + dataVal + '"]', tr).val();
        var class_id1 = $('input[data-type="classid"][data-val="' + dataVal + '"]', tr).val();
        var amount1 = $('input[data-type="approved-amount"][data-val="' + dataVal + '"]', tr).val();
        var discount1 = $('input[data-type="discount-percent"][data-val="' + dataVal + '"]', tr).val();
        var discount_val1 = $('input[data-type="discount-value"][data-val="' + dataVal + '"]', tr).val();
        var discount_value_class1 = $('input[data-type="discountclass"][data-val="' + dataVal + '"]', tr).val();
        var approve_price = $('input[data-type="approved-price"][data-val="' + dataVal + '"]', tr).val();
        var approve_total = $('input[data-type="approved-total"][data-val="' + dataVal + '"]', tr).val();
        var award_hints1 =  $('textarea[data-type="award-hints"][data-val="' + dataVal + '"]', tr).val();
        var AmounntApp = $('.amountapproved_'+class_id1+'').text();
        var purchase_amout = $('input[name="purchase_amount[]"]',tr).val();
        if (parseFloat(AmounntApp) > parseFloat(purchase_amout)) {
            danger_msg('يجب ان يكون مجموع كمية الترسية اقل من او يساوي طلب الشراء');
            return false ;
        }else {
            get_data('<?= base_url('purchases/suppliers_offers/public_item_detail') ?>', {
                ser: ser1,
                class_id: class_id1,
                approved_amount: amount1,
                award_hints: award_hints1,
                class_discount: discount1,
                approved_price: approve_price,
                class_discount_value: discount_val1,
                discount_value_class: discount_value_class1,
                order_purpose: +order_purpose
            }, function (data) {
                //console.log(data);
                var len = data.length;

                if (len > 0) {
                    success_msg('تم حفظ البيانات بنجاح');
                } else {
                    danger_msg('تحذير..', data);
                }
            }, 'html');
        }
    }

    //For addRow item detail table
    function addDetail1(obj){
        var tr = $(obj).closest('tr');
        var dataVal = $('select[name="CustId[]"]',tr).val();
        var ser1 = $('input[name="ser[]"]', tr).val();
        var class_id1 = $(tr).attr('data-class-ids');
        var purchase_amount = $('td[data-type="amount"]', tr).text();
        var amount1 = $('input[name="approved_amount[]"]', tr).val();
        var discount1 = $('input[name="class_discount[]"]', tr).val();
        var discount_val1 = $('input[name="c_discount_value[]"]', tr).val();
        var discount_value_class1 = $('input[name="discount_class[]"]', tr).val();
        var approve_price = $('input[name="approved_price[]"]', tr).val();
        var approve_total = $('.total_', tr).text();
        var award_hints1 =  $('textarea[name="award_hints[]"]', tr).val();
        if (parseFloat(amount1) > parseFloat(purchase_amount)) {
            danger_msg('يجب ان يكون مجموع كمية الترسية اقل من او يساوي طلب الشراء');
            return false ;
        }else {
            get_data('<?= base_url('purchases/suppliers_offers/public_item_detail') ?>', {
                ser: ser1,
                class_id: class_id1,
                approved_amount: amount1,
                award_hints: award_hints1,
                class_discount: discount1,
                approved_price: approve_price,
                class_discount_value: discount_val1,
                discount_value_class: discount_value_class1,
                order_purpose: +order_purpose
            }, function (data) {
                //console.log(data);
                var len = data.length;
                if (len > 0) {
                    success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');
                    //get_to_link(window.location.href);
                } else {
                    danger_msg('تحذير..', data);
                }
            }, 'html');
        }
    }

    function approved_total(obj){

        var tr = obj.closest('tr');
        var h_ser = $('input[name="h_ser[]"]',tr).val();
        //  alert(h_ser);
        var class_id = $('input[name="h_class_id[]"]',tr).val();
        var class_name = $('input[name="class_id_name[]"]',tr).val();
        var class_unit = $('input[name="class_unit[]"]',tr).val();
        var select_award = $('select[name*="award_delay_decision['+class_id+']"]',tr).val();
        var hint_award = $('textarea[name*="award_delay_decision_hint['+class_id+']"]',tr).val();
        var purchase_amout = $('input[name="purchase_amount[]"]',tr).val();
        get_data('<?= base_url('purchases/suppliers_offers/public_edit_award_item_tb1') ?>',{class_id:class_id,class_unit:class_unit,p_award_delay_decision:select_award,p_award_delay_decision_hint:hint_award} ,function(data){
            //console.log(data);
            var len = data.length;
            if(len > 0){
                if(select_award == 1){
                    $(tr).css({background: 'rgba(104, 181, 2, 0.39)'});
                    $('#xhide_table_'+h_ser+'').hide();
                    $('#td_tb_'+h_ser+'').show();
                    $('#table_'+h_ser+'').show();
                }else if(select_award == 0) {
                    $('#table_'+h_ser+'').hide();
                    $('#xhide_table_'+h_ser+'').show();
                    $(tr).css({background: 'rgba(184, 50, 60, 0.27)'});
                }
                else if(select_award == 2) {
                    //  $('#xhide_table_'+h_ser+'').hide();
                    //   $('#table_'+h_ser+'').show();
                    $('#table_'+h_ser+'').hide();
                    $('#xhide_table_'+h_ser+'').show();

                    $(tr).css({background: 'rgba(245, 225, 66,11)'}); }
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');
    }

    function Amount(i) {
        var tr = $(i).closest('tr[data-class-id]');
        var dataclass = tr.attr('data-class-id');
        //alert(dataclass)
        var sum = 0;
        $('input.balance',tr).each(function () {

            //find the combat elements in the current row and sum it
            var combat1 = $(this).val();

            if (!isNaN(combat1) && combat1.length !== 0) {
                sum += parseFloat(combat1);
                //alert(sum);
            }

        });
        $('.amountapproved_'+dataclass+'').html(sum);
        //  $('td[class*="amountapproved_['+dataclass+']"]',tr).text(sum);
    }

    function QtyPriceTotal() {
        $('td').each(function () {
            var sum = 0;
            //find the combat elements in the current row and sum it
            $(this).find('.totalP').each(function () {
                var combat2 = $(this).text();
                if (!isNaN(combat2) && combat2.length !== 0) {
                    sum += parseFloat(combat2);
                }
            });
            //set the value of currents rows sum to the total-combat element in the current row
            $('.colsum', this).html(sum);
        });
    }
    //For calculate sum of total price
    function totalAppPrice(){
        $('td').each(function () {
            var sum = 0;
            //find the combat elements in the current row and sum it
            $(this).find('.total_').each(function () {
                var combat3 = $(this).text();
                if (!isNaN(combat3) && combat3.length !== 0) {
                    sum += parseFloat(combat3);
                }
                //set the value of currents rows sum to the total-combat element in the current row

            });

            $('.totalApprove_',this).html(sum.toFixed(4));
        });

    }



    //</script>
