/**
 * Created by Ahmed Barakat on 8/18/14.
 */



$('#search-tree').keyup(function(){

    $.fn.tree.filter($(this).val());

});
function clearForm(n) {
    $(":input", n).each(function() {
        var n = this.type,
            t = this.tagName.toLowerCase();
        n == "text" || n == "password" || n == "hidden"|| n == "date" || n == "number" || n == "datetime" || t == "textarea" || n == "file" ? this.value = "" : n == "checkbox" || n == "radio" ? this.checked = !1 : t == "select" && (this.selectedIndex = 0)
    }), resetValidation(n)
}

function clearForm_any(n) {
    $(":input", n).each(function() {
        var n = this.type,
            t = this.tagName.toLowerCase();
        n == "text" || n == "password" || n == "hidden"|| n == "date" || n == "number" || n == "datetime" || t == "textarea" || n == "file" ? this.value = "" : n == "checkbox" || n == "radio" ? this.checked = !1 : t == "select" && (this.selectedIndex = 0)
    })
}

function clearFormExpected(n,ex) {
    $(":input", n).not(ex).each(function() {
        var n = this.type,
            t = this.tagName.toLowerCase();
        n == "text" || n == "password" || n == "hidden"|| n == "date" || n == "number" || n == "datetime" || t == "textarea" || n == "file" ? this.value = "" : n == "checkbox" || n == "radio" ? this.checked = !1 : t == "select" && (this.selectedIndex = 0)
    }), resetValidation(n)
}

function resetValidation(n) {
    var t = n;
    t.data("validator").resetForm(), t.find(".validation-summary-errors").addClass("validation-summary-valid").removeClass("validation-summary-errors"), t.find(".input-validation-error").removeClass("input-validation-error"), t.find(".field-validation-error").addClass("field-validation-valid").removeClass("field-validation-error").removeData("unobtrusiveContainer").find(">*").removeData("unobtrusiveContainer"), t.find(".field-validation-valid span,.validation-summary-valid span").text(""), jQuery().select2 && $("select[data-select2me]").select2("val", "")
}
function ajax_delete(n, t, i) {
    if (t != "") {
        showLoading();
        var r = $('input[name="__AntiForgeryToken"]').val();
        $.ajax({
            url: n,
            traditional: !0,
            type: "POST",
            data: {
                'id[]': t,
                __AntiForgeryToken: r
            },
            dataType: "html",
            success: i,
            error: function(n) {
                Unauthorized(n.status)
                if (n.status == 500)
                    danger_msg( 'تحذير','فشل في العملية');
            },
            complete: function() {
                HideLoading();
            }
        })
    }
}
function ajax_delete_any(n, t, i) {
    if (t != "") {
        showLoading();
        var r = $('input[name="__AntiForgeryToken"]').val();
        t['__AntiForgeryToken']= r;

        $.ajax({
            url: n,
            traditional: !0,
            type: "POST",
            data:t,
            dataType: "html",
            success: i,
            error: function(n) {
                Unauthorized(n.status)
                if (n.status == 500)
                    danger_msg( 'تحذير','فشل في العملية');
            },
            complete: function() {
                HideLoading();
            }
        })
    }
}

function get_data(n, t, i,type) {
    if(type == undefined || type =='')
        type ='json';
    showLoading();
    $.ajax({
        url: n,
        type: "POST",
        data: t,

        dataType: type,
        success: i,
        error: function(n) {
            Unauthorized(n.status)
            if (n.status == 500)
                danger_msg( 'تحذير','فشل في العملية<br> '+n.responseText);
        },
        complete: function() {
            HideLoading();
        }
    })

}

function get_dataWithOutLoading(n, t, i,type) {
    if(type == undefined || type =='')
        type ='json';

    $.ajax({
        url: n,
        type: "POST",
        data: t,

        dataType: type,
        success: i,
        error: function(n) {
            Unauthorized(n.status)
            if (n.status == 500)
                danger_msg( 'تحذير','فشل في العملية');
        },
        complete: function() {

        }
    })

}
function Unauthorized(n) {
    if( parseInt(n) == 401)
        danger_msg( 'تحذير','لا تملك صلاحيات كافية , لإتمام العملية');
}
function showLoading(){

     $('body').append('<div class="modal-backdrop fade in" id="gs_bk_loading"></div>');
     $('.gs_loading').center();
     $('.gs_loading').show();

    $('.loader').show();

}
function HideLoading(){


     $('.gs_loading').hide();

     $('#gs_bk_loading').remove();
    $('.loader').hide();
}
function get_help(url,id){

    get_data(url, {id:id}, function(data){

        try{
            console.log('',data);

            var html = '<div class="modal fade" id="helpModel"><div class="modal-dialog"><div class="modal-content"><div class="modal-body">'+data[0].HELP_TEXT+'</div></div><!-- /.modal-content --></div><!-- /.modal-dialog --></div><!-- /.modal -->';

            $('#helpModel').remove();

            $('body').append(html);



            $('#helpModel').modal();
        }catch (e){}

    },'json');

}
var ajax_insert_update = function (form,success_callback,type,complate) {



    if(type == undefined || type ==''){
        type = "json";
    }


    var url = form.attr("action");


    var formData = new FormData();

    var file_data = $('input[type="file"]'); // for multiple files

    file_data = file_data.length > 0 ? file_data[0].files : file_data;

    for (var i = 0; i < file_data.length; i++) {
        formData.append("file_" + i, file_data[i]);
    }

    var other_data = form.serializeArray();

    $.each(other_data, function (key, input) {
        formData.append(input.name, input.value);
    });


    if (!$(form).valid())
        return;

    showLoading();


    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        dataType: type,
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string
        success: success_callback, error: function (xhr, errorType, exception) {

            Unauthorized(xhr.status);

            if ( parseInt(xhr.status) == 500){

                try {
                    var obj  = $.parseJSON(xhr.responseText);
                    danger_msg( 'تحذير',obj.message);
                    $('#txt_'+obj.col).attr('class','form-control input-validation-error');
                    $('#txt_'+obj.col).focus();
                } catch (e) {
                    // not json
                    danger_msg( 'تحذير',xhr.responseText);
                }



            }
            //   danger_msg( 'تحذير','فشل في حفظ البيانات');



        }, complete: function () {

          //  complate();

            HideLoading();
        }

    });
}

/*
 function resetValidation(form){
 var validator = form.validate();
 validator.resetForm();

 form.find('.field-validation-error')
 .removeClass('field-validation-error')
 .addClass('field-validation-valid').html('');

 form.find('.input-validation-error')
 .removeClass('input-validation-error')
 .addClass('valid');
 }
 */

function ajax_pager(Pdata){
                        $('.pagination-container a').click(function(e){
                            e.preventDefault();

                            var page  =parseInt( $(this).text());
                            if($(this).text() =='«'){
                                var li = $('ul.pagination > li.active').prev();
                                page=  $(li).find('a').text();
                            }else  if($(this).text() =='»'){
                                var li = $('ul.pagination > li.active').next();
                                page=  $(li).find('a').text();
                            }

                            if(page == 1)
                                scrollToElem($('table.table > thead'));
                            else {
                                if($('td#page-'+page+'[data-page]').length >= 1){

                                    scrollToElem($('td#page-'+page+'[data-page]'));

                                }
                                else{
                                    get_data($(this).attr('href'),Pdata,function(data){
                                        $('#container').html(data);
                                        ajax_pager(Pdata);
                },'html');
            }
        }
    });

}

function scrollToElem(elem){
    $('html, body').animate({
        scrollTop: elem.offset().top
    }, 2000);
}

var currantPage = 1;

function ajax_pager_data(container,Pdata){

    var li = $('ul.pagination > li.active').next();
    var url = $(li).find('a').attr('href');
    var page=  $(li).find('a').text();


    if(currantPage == page)
        return;

    currantPage=page;


    if($('td#page-'+page+'[data-page]').length <=0 && page != '' ){

        if(url != undefined)
            get_data(url,Pdata,function(data){


                var html =   $('table > tbody',data).html();
                var pager =  $('ul.pagination',data).html();

                $('ul.pagination').html(pager);
                $(container).append(html);

                initFunctions();

                ajax_pager(Pdata);

                if (typeof ApplyOtherAction == 'function') {
                    ApplyOtherAction(data);
                }

            },'html');

    }

}

function ajax_fun(url, formData, callback, type, Loading) {

    if (type == undefined || type == '')
        type = 'json';
    if (Loading)
        showLoading();

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        dataType: type,
        traditional: true,
        success: callback, error: function (xhr, errorType, exception) {

            Unauthorized(xhr.status);
            showNotifications('تحذير', 'فشل بتنفيذ الاجراء', 'error');


        }, complete: function () {
            HideLoading();
        }

    });

}

function getCustomerData(obj , val){
    if(val.length > 6)
        get_data(_base_url+'payment/customers/public_get_customers_json',{id:val},function(data){

            if(data.length > 0){
                $(obj).val(data[0].CUSTOMER_NAME);
            }
        });
}