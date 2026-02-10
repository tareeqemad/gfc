/**
 * Created by Ahmed Barakat on 8/19/14.
 */

String.prototype.padRight = function (l, c) {
    return this + Array(l - this.length + 1).join(c || " ")
}
var screenWidth = window.screen.width;
if (screenWidth < 1366)
    $('html').css({zoom: (screenWidth * 100 / 1366) + '%'});

//mkilani- disable the browser zooming for chrome
//document.firstElementChild.style.zoom = "reset";

function LeftPad(str, max) {
    str = str.toString();
    return str.length < max ? LeftPad("0" + str, max) : str;
}

jQuery.fn.center = function () {

    var top = (($(window).height() - $(this).outerHeight()) / 2) +
        $(window).scrollTop();
    var left = (($(window).width() - $(this).outerWidth()) / 2) +
        $(window).scrollLeft();


    this.css("position", "absolute");
    this.css("top", Math.max(0, top) + "px");
    this.css("left", Math.max(0, left) + "px");
    return this;
}

function account_id(level, maxId, parentId) {
    level = level + 1;
    parentId = parentId.trim();
    if (maxId == '' || maxId == undefined) {

        return parentId + '01';
    }

    switch (level) {
        case 1:
            return maxId + 1;
            break;
        case 2:
            var id = parseInt(maxId.substr(1)) + 1;
            return parentId + LeftPad(id, 2);
            break;
        case 3:
            var id = parseInt(maxId.substr(3)) + 1;
            return parentId + LeftPad(id, 2);
            break;
        case 4:
            var id = parseInt(maxId.substr(5)) + 1;
            return parentId + LeftPad(id, 2);
            break;
        case 5:
            var id = parseInt(maxId.substr(7)) + 1;
            return parentId + LeftPad(id, 2);
            break;
        case 6:
            var id = parseInt(maxId.substr(9)) + 1;
            return parentId + LeftPad(id, 2);
            break;
    }
}

function account_withRoot_id(level, maxId, parentId) {
    level = level;
    parentId = parentId.trim();


    if (maxId == '' || maxId == undefined) {
        if (parentId == '0')
            return 1;//parentId+'01';
        else return parentId + '01';
    }

    switch (level) {
        case 1:
            return parseInt(maxId) + 1;
            break;
        case 2:
            var id = parseInt(maxId.substr(1)) + 1;
            return parentId + LeftPad(id, 2);
            break;
        case 3:
            var id = parseInt(maxId.substr(3)) + 1;
            return parentId + LeftPad(id, 2);
            break;
        case 4:
            var id = parseInt(maxId.substr(5)) + 1;
            return parentId + LeftPad(id, 2);
            break;
        case 5:
            var id = parseInt(maxId.substr(7)) + 1;
            return parentId + LeftPad(id, 2);
            break;
        case 6:
            var id = parseInt(maxId.substr(9)) + 1;
            return parentId + LeftPad(id, 2);
            break;
    }
}

//---------------------
function class_id(level, maxId, parentId) {
    level = level + 1;
    parentId = parentId.trim();

    if (maxId == '' || maxId == undefined) {
        if (level == 5)
            return parentId + '001';
        else
            return parentId + '01';
    }
    maxId = '1' + maxId;

    return (parseInt(maxId) + 1).toString().substr(1);


}

//---------------------
function structure_id(maxId, parentId) {

    parentId = parentId.trim();
    if (maxId == '' || maxId == undefined) {

        return parentId + '01';
    }

    maxId = '1' + maxId;

    return (parseInt(maxId) + 1).toString().substr(1);
}

tab_click();

function tab_click() {
    $('input,select').on("keypress", function (e) {

        /* ENTER PRESSED*/
        if (e.keyCode == 13) {
            var inps = $("input, select"); //add select too
            for (var x = 0; x < inps.length; x++) {
                if (inps[x] == this) {
                    while ((inps[x + 1]) != undefined && (inps[x]).name == (inps[x + 1]).name) {
                        x++;
                    }
                    if ((x + 1) < inps.length) $(inps[x + 1]).focus();
                }
            }
            e.preventDefault();
        }
    });

}

/*function Up_Down(){
 $('input').bind('keyup', 'up', function(e) {
 var inputName = $(this).attr('name');
 console.log('',e);
 var inps = $('input[name="'+inputName+'"]'); //add select too
 for (var x = inps.length; x > 0; x--) {
 if (inps[x] == this) {

 $(inps[x - 1]).focus();
 }
 }   e.preventDefault();
 });

 $('input').bind('keyup', 'down', function(e) {
 var inputName = $(this).attr('name');
 console.log('',e);
 var inps = $('input[name="'+inputName+'"]'); //add select too
 for (var x = 0; x <inps.length; x++) {
 if (inps[x] == this) {

 $(inps[x + 1]).focus();
 }
 }   e.preventDefault();
 });
 }*/

function showAlert(title, message, style) {

    /*  $('#msg_container,#msg_container_alt').html('<div class="alert alert-'+style+' fade in" role="alert" style="display:none;"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button><strong>'+title+'</strong> '+message+' </div>');
     $('#msg_container .alert,#msg_container_alt .alert').slideDown();
     setInterval(function(){ $('#msg_container .alert,#msg_container_alt .alert').slideUp();},3000);*/

    showNotifications(message, title, style);
}

function showNotifications(n, t, i) {
    toastr.options = {
        closeButton: !0,
        debug: !1,
        positionClass: "toast-top-right",
        onclick: null,
        showDuration: "600",
        hideDuration: "1000",
        timeOut: "6000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };
    toastr[i](n, t);
}


function warning_msg(title, message) {
    showAlert(title, message, 'warning');
}


function success_msg(title, message) {
    showAlert(title, message, 'success');
}


function info_msg(title, message) {
    showAlert(title, message, 'info');
}


function danger_msg(title, message) {
    showAlert(title, message, 'error');
}

//mkilani- get format numbers like 999,999.99
function num_format(n) {
    return n.toFixed(2).replace(/./g, function (c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
    });
}

//mkilani- get the total and rate of table column
function totals(tb) {
    var total = 0;
    $('#' + tb + ' tbody .total').each(function () {
        total += +parseFloat($(this).text().replace(/,/g, ''));
    });
    $('#' + tb + ' tfoot .total').text(num_format(total));
    $('#' + tb + ' tfoot #h_total').val(total);

    var total2 = 0;
    $('#' + tb + ' tbody .total2').each(function () {
        total2 += +parseFloat($(this).text().replace(/,/g, ''));
    });
    $('#' + tb + ' tfoot .total2').text(num_format(total2));

    var rate = 0;
    $('#' + tb + ' tbody .rate').each(function () {
        if ($(this).val() != '')
            rate += +parseFloat($(this).val());
    });
    $('#' + tb + ' tfoot .rate').text(num_format(rate) + ' %');
}

//mkilani- get (count * price)  and  rate % and refresh the total
function refresh_total(tb) {

    $('#' + tb + ' .ccount').change(function () {

        $(this).closest('tr').children(".total").text(($(this).val() * $(this).closest('tr').find(".price").val()));
        totals(tb);
    });

    $('#' + tb + ' .price').change(function () {
        $(this).closest('tr').children(".total").text(($(this).val() * $(this).closest('tr').find(".ccount").val()));
        totals(tb);
    });

    $('#' + tb + ' .rate').change(function () {
        $(this).closest('tr').children(".total").text(($(this).val() * $('#' + tb + ' #total_price').val() / 100));
        totals(tb);
    });
}

// mkilani- convert Num with decimal To Time HH:MM - ex. 2.45 => 2:27
function hhmm_decimal_to_time(hhmm){
    var hhmm_arr= hhmm.split('.');
    var hh= hhmm_arr[0];
    hh= (hh.length==0)? 0: parseInt(hh);
    var decimal= hhmm_arr[1];
    var mm;
    var error=0;

    if(decimal > 0){
        if(decimal.length==1){ // if 0.2 then its 0.20
            decimal = decimal * 10;
        }else if(decimal.length > 2){ // if 0.233 Error
            decimal = 0;
            error=1;
        }

        mm= decimal*60/100;
    }else{
        mm= null;
    }
    mm= Math.floor(mm);
    if(mm<10){
        mm= '0'+mm;
    }
    var hhmm_time= hh+':'+mm;

    if(error)
        return "error";
    else
        return hhmm_time;
}

// mkilani- to click on something
function actuateLink(link) {
    var allowDefaultAction = true;

    if (link.click) {
        link.click();
        return;
    }
    else if (document.createEvent) {
        var e = document.createEvent('MouseEvents');
        e.initEvent(
            'click'     // event type
            , true      // can bubble?
            , true      // cancelable?
        );
        allowDefaultAction = link.dispatchEvent(e);
    }

    if (allowDefaultAction) {
        var f = document.createElement('form');
        f.action = link.href;
        document.body.appendChild(f);
        f.submit();
    }
}

// mkilani- to make change on something by element id
function actuate_change(element_id) {
    var evObj = document.createEvent("HTMLEvents");
    evObj.initEvent("change", true, true);
    var elem = document.getElementById(element_id);
    elem.dispatchEvent(evObj);
}

// mkilani- send e-mail
function _send_mail(btn_obj, to, subject, send_text, html, cnt) {

    if (jQuery.type(btn_obj) == 'object'){
        if(btn_obj.attr("id").substring(0,4) == 'btn_'){
            //send
        }else{
            return false;
        }
    }else{
        return false;
    }

    if (to == undefined) {
        warning_msg('تنبيه..', 'يجب تحديد البريد الالكتروني المرسل اليه');
        return false;
    }
    if (subject == undefined)
        subject = '';
    if (send_text == undefined)
        send_text = '';
    if (html == undefined)
        html = 0;
    if (cnt == undefined)
        cnt = 1;

    var this_moment = moment(); //Get the current date
    this_moment = parseInt(this_moment.format("YYYYMMDDmm")) ;
    var chk_hash = this_moment*3;

    var mail_url = _base_url + 'settings/mail/public_send';
    var values = {to: to, subject: subject, send_text: send_text, html: html, cnt: cnt, chk_hash:chk_hash};

    get_data(mail_url, values, function (ret) {
        if (ret == 1) {
            return 1;
        }
        else {
            warning_msg('تنبيه..', 'لم يتم ارسال البريد الالكتروني');
        }
    }, 'html');
}

//mkilani- restart search when back from browser
function auto_restart_search() {
    $('form input:text, form select').each(function () {
        if ($(this).val() != '') {
            search();
            info_msg('يرجى الانتظار', 'جار اعادة عرض السجلات السابقة');
            return false;
        }
    });
}

// mkilani - convert date-string to date-date
function string_to_date(dateString) {
    var dateSplite = dateString.split("/");
    var year = dateSplite[2];
    var month = dateSplite[1] - 1;
    var day = dateSplite[0];
    var newDate = new Date(year, month, day);
    return newDate;
}

// mkilani - convert date dd/mm/yyyy to yyyymmdd
function date_yyyymmdd(dateString) {
	if(dateString=='') return '';
	
    var dateSplite = dateString.split("/");
    var year = dateSplite[2];
    var month = dateSplite[1] - 1;
    var day = dateSplite[0];
    var newDate = new Date(year, month, day);

    let year_ = newDate.getFullYear();
    let month_ = newDate.getMonth() + 1;
    let day_ = newDate.getDate();

    if (month_ < 10) {
        month_ = "0" + month_;
    }
    if (day_ < 10) {
        day_ = "0" + day_;
    }

    let yyyymmdd = year_.toString() + month_.toString() + day_.toString();
    return yyyymmdd;
}

// mkilani - calc age by birthdate - ret as 21.56
function age_calc(b_dt) {
    return ((new Date() - string_to_date(b_dt)) / (365 * 60 * 60 * 24 * 1000)).toFixed(2);
}


function delete_action() {
    $('a[data-action="delete"]').click(function () {
        $(this).closest('tr').remove();
        if (typeof update_after_delete == 'function') {
            update_after_delete();
        }
    });

}

//Function For Check All checkboxes
function initFunctions() {

    $('.table tbody tr').click(function () {

        $(this).parent().find('tr').removeClass('selected');
        $(this).addClass('selected');

    });

    $('.tb-row').click(function () {

        $(this).removeClass('selected');
        $(this).addClass('selected');

    });

    Apply_Sort();
    delete_action();

    $('input:checkbox').on('change', function () {


        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        jQuery(set).each(function () {
            if (checked) {

                $(this).prop("checked", true);
                $(this).attr("checked", true);
            } else {

                $(this).prop("checked", false);
                $(this).attr("checked", false);
            }
            $(this).parents('tr').toggleClass("active");
        });
    });
}

initFunctions();

function _showReport(url, top) {

    var mModal = top ? '#top_modal' : '#report';
    console.log('', mModal);
    if (url.indexOf('reports?report=') > -1 || url.indexOf('reports?type=') > -1) {

        $(mModal + ' .modal-dialog').width(window.screen.width - 60);
        $(mModal + ' .modal-dialog').height('90%');

    }


    $(mModal + ' .modal-body').html('<iframe width="100%" height="100%" frameborder="0"   allowtransparency="true" src="' + url + '"></iframe>');
    $(mModal).modal();
}

function _showHtmlRep(url) {
    $.get(url, function(data){
        $('.htmlrep').html(data);
        $('.htmlrep').show();
    });
}

function GetData(url, val, successCallback, type) {
    if (type == undefined)
        type = "json";

    $.ajax({
        url: url,
        type: "GET",
        data: val,
        dataType: type,
        success: successCallback, error: function (xhr, errorType, exception) {
            showNotifications('تحدذير', 'فشل باستعراض البيانات', 'error');
        }

    });
}


function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function _showNewModal(url, title = '', cls = 'modal-lg') {

    var id = $('.urlModel').length;

    var modal =
        ' <div class="urlModel modal fade bd-example-' + cls + '"   id= "urlModel-' + id + '"  role= "dialog" style="display:none;" >   ' +
        '     <div class="modal-dialog modal-dialog-centered ' + cls + '" role="document">                                                                               ' +
        '         <div class="modal-content"  style="border: 3px solid ' + getRandomColor() + ';">                                                                                                                ' +
        '             <div class="modal-header">                                                                                                             ' +
        '                 <h5 class="modal-title" id="exampleModalLongTitle">' + title + '</h5>                                                                ' +
        '                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">                                                       ' +
        '                     <span aria-hidden="true">&times;</span>                                                                                        ' +
        '                 </button>                                                                                                                          ' +
        '             </div>                                                                                                                                 ' +
        '             <div class="modal-body">                                                                                                               ' +
        '                 Loading... ' +
        '             </div>                                                                                                                                 ' +
        '         </div>                                                                                                                                     ' +
        '     </div>                                                                                                                                         ' +
        '  </div >';


    $('body').append(modal);
    $('#urlModel-' + id + '').modal({backdrop: 'static', keyboard: false});

    $('#urlModel-' + id + '').on('hidden', function () {
        console.log("hidden");
        $('#urlModel-' + id + '').remove();
    });

    $('#urlModel-' + id + '').on('hidden.bs.modal', function () {
        console.log("hidden");
        $('#urlModel-' + id + '').remove();
    })

    GetData(url, '', function (data) {
        $('#urlModel-' + id + ' .modal-body').html(data);

        initBindings();

        jQuery.validator.unobtrusive.parse('#urlModel-' + id + ' form');

        if (typeof AfterShowModal == 'function') {
            AfterShowModal();
        }


    }, 'html');

}

function get_to_link(url) {
    window.location.href = url;
}


$('i[class="icon icon-reply"]').closest('a').attr('onclick', "return confirm('هل تريد الرجوع للخلف ؟!');");

$('body').on("keyup", function (e) {
    /* ENTER PRESSED*/
    /* if (e.keyCode == 27) {

     if(!$('#report').hasClass('in'))
     window.history.back();
     else $('#report').modal('hide');

     }*/

});
$('#search-tbl').focus();
$('#search-tbl').keypress(function (e) {

    var DataSet = $('#' + $(this).attr('data-set'));

    var text = $(this).val();
    if (e.keyCode == 13 || text == '') {
        if ($(DataSet).prop('tagName') != 'UL') {
            $('tr > td.filterd', DataSet).removeClass('filterd');

            $('tr > td:contains("' + text + '")', DataSet).each(function () {
                $(this).addClass('filterd');
            });

            $('tr  input', DataSet).each(function () {

                if ($(this).val().indexOf(text) > -1) {
                    $(this).closest('td').addClass('filterd');
                }
            });

            $('tr', DataSet).hide();
            $('thead >tr:first-child', DataSet).show();
            $('tr > td.filterd', DataSet).parents('tr').show();

        } else {

            $('.tb-row > li.filterd', DataSet).removeClass('filterd');

            $('.tb-row > li:contains("' + text + '")', DataSet).each(function () {
                $(this).addClass('filterd');
            });

            $('.tb-row', DataSet).hide();

            $('.tb-row > li.filterd', DataSet).parents('.tb-row').show();
        }
    }


});


apply_scroll();

function apply_scroll() {
    $(window).scroll(function () {

        var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
        var scrolltrigger = 0.95;
        var winToS = (wintop / (docheight - winheight));

        if (screenWidth < 1366) {
            scrolltrigger = ((screenWidth * 100 / 1366) * scrolltrigger) / 100;
            winToS = ((wintop / (docheight - winheight)) * 100) / (screenWidth * 100 / 1366);
        }


        if (winToS > scrolltrigger) {

            if (typeof LoadingData == 'function') {
                LoadingData();
            }
        }
    });

}

function replaceAll(find, replace, str) {
    str = str == undefined ? '' : str;
    return str.replace(new RegExp(find, 'g'), replace);
}

function isNaNVal(val, d) {
    if (d == undefined)
        d = 5;

    if (val == '' || isNaN(parseFloat(val)))
        return 0;
    else return parseFloat(parseFloat(val).toFixed(d));

}

function isNaNValMath(val, d) {
    if (d == undefined)
        d = 5;

    if (val == '' || isNaN(parseFloat(val)))
        return 0;
    //else return parseFloat(parseFloat(val).toFixed(d));
    else return Math.round((parseFloat(val).toFixed(d) * 1000) / 10) / 100;

}

function reload_Page() {
    setTimeout(function () {

        window.location.reload();

    }, 1000);
}


function reload_PageUrl(url) {
    setTimeout(function () {

        window.location = url;

    }, 1000);
}

$('legend').click(function () {

    $(this).closest('fieldset').find('div.modal-body').toggle('slide');
    $(this).closest('fieldset').find('div.modal-footer').toggle('slide');
});

function VAL_TX_VALID(VAL, TX, VAT_TYPE, HAVE_TX) {

    var RES;
    if (HAVE_TX == 1 && VAT_TYPE == 1)

        RES = VAL - GET_TAX_VAL(VAL, TX, 1);
    else
        RES = VAL;

    return isNaNVal(RES, 2);
}

function GET_TAX_VAL(val, tx, vat_type) {
    var res;
    if (vat_type == 1)

        res = val - (val / (1 + (tx / 100)));

    else if (vat_type == 2)

        res = val * (tx / 100);

    return res;
}


function GET_DISCOUNT(val, disc_val, disc_type) {

    var res = 0;

    if (disc_type == 1)

        res = disc_val;

    else if (disc_type == 2 && disc_val > 0)

        res = val * (disc_val / 100);

    else if (disc_val = 0)
        res = 0;

    return res;
}

function GET_TAX_VAL_WT_DESC(val, val2, desc_num, tx) {
    var res;
    var total_tx;
    var desc_tx;
    var waste;
    var done_n;
    var not_done;
    var new_val;

    waste = val + val2;
    done_n = val / waste;
    not_done = val2 / waste;
    new_val = done_n * desc_num;
    total_tx = GET_TAX_VAL(val, tx, 2) - GET_TAX_VAL(new_val, tx, 2);

    res = total_tx;

    return res;

}

(function () {

    var beforePrint = function () {
        console.log('Functionality to run before printing.');
    };

    var afterPrint = function () {
        console.log('Functionality to run after printing');
    };

    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function (mql) {
            if (mql.matches) {
                beforePrint();
            } else {
                afterPrint();
            }
        });
    }

    window.onbeforeprint = beforePrint;
    window.onafterprint = afterPrint;

}());


$(".navbar-nav li").hover(function () {
        $(this).children("ul").stop().fadeIn("fast");
    },
    function () {
        $(this).children("ul").stop().fadeOut("fast");
    });

//Up_Down();
//Apply_Sort();

function Apply_Sort() {

    $('fieldset[data-allow-sort]').append('<input type="hidden" name="sort_data" />');

    $('th[data-sort]').each(function (i) {
        var dir = $(this).attr('data-sort-dir');
        if ($('i', $(this)).length <= 0)
            $(this).append('<i class=""></i>');
        if (dir == 'desc')
            $('i', $(this)).attr('class', 'icon icon-angle-up');
        else if (dir == 'asc')
            $('i', $(this)).attr('class', 'icon icon-angle-down');


        $(this).click(function () {

            $('i', $('th[data-sort]')).not($(this)).attr('class', '');

            var dir = $(this).attr('data-sort-dir');
            var sort_c = $(this).attr('data-sort');

            if (dir == 'desc') {
                $('i', $(this)).attr('class', 'icon icon-angle-down');
                $(this).attr('data-sort-dir', 'asc');
                sort_c = ' ' + sort_c + ' asc ';

            } else {
                $('i', $(this)).attr('class', 'icon icon-angle-up');
                $(this).attr('data-sort-dir', 'desc');
                sort_c = ' ' + sort_c + ' desc ';
            }

            $('input[name="sort_data"]').val(sort_c);


            search_data();

        });
    });
}

function add_row(a, empty, c, countInput) {

    var table = $(a).closest('table');
    var tr = $('tbody > tr:first', table).clone();
    var count = $('tbody tr:not(tbody tr table tr)', table).length + 1;

    var data_allow_count = $(table).attr('data-allow-count');

    if (data_allow_count != undefined && count > parseInt(data_allow_count)) {
        return;
    }


    $(empty, $(tr)).val('');
    $(empty, $(tr)).not('select').text('');
    $(empty, $(tr)).attr('value', '');
    $(empty, $(tr)).children().removeAttr('selected');

    $('input[type="checkbox"]', $(tr)).prop('checked', false);
    $('input[type="checkbox"]', $(tr)).attr('checked', false);


    $('tbody:first', table).append(tr);

    if (c)
        $('td:first', $(tr)).text(count);
    if (countInput != undefined) {
        $(countInput, $(tr)).val(count);
        $(countInput, $(tr)).attr('value', count);
    }

    tr.html(replaceAll('_0', '_' + (count - 1), tr.html()));

    $('input:regex(name,[0]),select:regex(name,[0].),textarea:regex(name,[0])', tr).each(function (i) {

        var name = $(this).attr('name');
        $(this).attr('name', name.replace('0', count - 1));

    });

    $('select  option', tr).show();

    if (typeof reBind == 'function') {
        reBind();
    }

    if (typeof reBind_pram == 'function') {
        reBind_pram(count - 1);
        return false;
    }

    if (typeof reBindAfterInsert == 'function') {
        reBindAfterInsert(tr);
    }

    $('td[data-action="delete"]', tr).html('<a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a> ');
    delete_action();
    reBindDateTime();
}


jQuery.expr[':'].regex = function (elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ?
                matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels, '')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^s+|s+$/g, ''), regexFlags);
    return regex.test(jQuery(elem)[attr.method](attr.property));
}

function reBindDateTime() {
    if ($.fn.datetimepicker) {
        $('input[data-type="date"]').datetimepicker({
            pickTime: false

        });
    }
}


function reloadIframeOfReport() {
    $('#report iframe').each(function () {
        this.contentWindow.location.reload(true);
    });
}


function Up_Down() {
   /* $("input").bind("keyup", "up", function (n) {

        for (var r = $(this).attr("name").replace(/\w+\[[\d]]+/g, ""), i = $('input[name="' + r + '"]'), t = i.length; t > 0; t--) i[t] == this && $(i[t - 1]).focus();
        n.preventDefault()
    }), $("input").bind("keyup", "down", function (n) {
        for (var r = $(this).attr("name").replace(/\w+\[[\d]]+/g, ""), i = $('input[name="' + r + '"]'), t = 0; t < i.length; t++) i[t] == this && $(i[t + 1]).focus();
        n.preventDefault()
    })*/
}

function Up_downTable() {

    $(document).bind("keyup", "up", function (n) {

        var prevTr = $('.table.selected-red tbody tr.selected').prev();

        if (prevTr != undefined) {
            $('.table.selected-red  tbody tr.selected').removeClass('selected');
            prevTr.addClass('selected');
        }
        n.stopPropagation()
        n.preventDefault()
    }), $(document).bind("keyup", "down", function (n) {

        var nextTr = $('.table.selected-red tbody tr.selected').next();
        if (nextTr != undefined) {
            $('.table.selected-red  tbody tr.selected').removeClass('selected');
            nextTr.addClass('selected');
        }
        n.stopPropagation()
        n.preventDefault()
    })
}


$('button[data-hints]').click(function () {

    $('#hints-content').text($(this).attr('data-hints'));
    $('#hints-model').modal();
});

function restInputs(tr) {

    var tabel = $(tr).closest('table');

    console.log('', $('tbody tr', tabel).length);

    if ($('tbody tr', tabel).length <= 1) {

        $('input,select,textarea', $(tr)).val('');
        $('input,select,textarea', $(tr)).attr('value', '');
        $('input,textarea', $(tr)).text('');

        $('select option', $(tr)).prop('selected', false);
    } else {

        $(tr).remove();

    }

}

function AddDays(dateString, days) {

    var dateSplite = dateString.split("/");
    var year = dateSplite[2];
    var month = dateSplite[1] - 1;
    var day = dateSplite[0];

    var newDate = new Date(year, month, day);

    newDate.setDate(newDate.getDate() + days);

    return newDate.toInputFormat();
}

Date.prototype.toInputFormat = function () {
    var YYYY = this.getFullYear().toString();
    var MM = (this.getMonth() + 1).toString(); // getMonth() is zero-based
    var DD = this.getDate().toString();
    return (DD[1] ? DD : "0" + DD[0]) + '/' + MM + '/' + YYYY; // padding
};

function reloadParentPage() {

    setTimeout(function () {
        parent.location.reload();
    }, 1000);
}

//Submit form using insert-fetech-form and return partial view
var insertFetechForm = function (event) {
    event.preventDefault();

    var form = $(this);
    var url = form.attr("action");

    var IsUpdate = form.attr('data-update');
    var reload_parent = form.attr('reload-parent');
    var callbackFunction = form.attr('callback');
    var hide_modal = form.attr('hide-modal');
    var container = form.attr('data-container');

    var formData = new FormData();

    var file_data = $('input[type="file"]', form); // for multiple files

    //file_data = file_data.length > 0 ? file_data[0].files : file_data;

    for (var i = 0; i < file_data.length; i++) {
        var name = $(file_data[i]).attr('name');
        var files = file_data[i].files;


        for (var ix = 0; ix < files.length; ix++) {
            formData.append(name + "[" + ix + "]", files[ix]);
            console.log('-', name + "[" + ix + "]");
            console.log('-', files[ix]);
        }
    }

    var other_data = form.serializeArray();

    $.each(other_data, function (key, input) {
        formData.append(input.name, input.value);
    });


    if (!$(this).valid())
        return;
    if (typeof CheckValid == 'function') {
        if (!CheckValid())
            return;
    }
    showLoading();

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string
        dataType: "html",
        success: function (result) {

            showNotifications('تم حفظ البيانات بنجاح', 'رسالة', 'success');


            if (reload_parent)
                reloadParentPage();

            if (hide_modal != undefined && hide_modal != '') {
                $(hide_modal).modal('hide');
            }

            if (!IsUpdate || IsUpdate == 'False')
                clearForm(form);

            $('#' + container).fadeOut("slow", function () {
                $('#' + container).html(result);
                $('#' + container).slideDown("slow");
            });


            if (typeof AfterSaveData == 'function') {
                AfterSaveData(result);
            }


            if (typeof AfterAddingSaveData == 'function') {
                AfterAddingSaveData(result);
            }

            if (callbackFunction != undefined) {

                var fn = window[callbackFunction];
                // is object a function?
                if (typeof fn === "function") fn(result);
            }

        }, error: function (xhr, errorType, exception) {


            if (typeof ErrorSaveData == 'function') {
                ErrorSaveData();
            }

            Unauthorized(xhr.status);


            if (xhr.statusText == 'error')
                showNotifications('فشل بحفظ البيانات' + '<br>' + xhr.responseText, 'رسالة', 'error');
            else if (xhr.statusText == 'valid')
                showNotifications(_messageWrongData + '<br>' + xhr.responseText, 'رسالة', 'warning');
            else
                showNotifications(xhr.responseText, 'رسالة', 'error');


        }, complete: function () {
            $('[data-alert]').slideDown();
            HideLoading();
        }

    });
}

function initBindings() {

    $('input[type="text"],input[type="password"],input[type="datetime"],input[type="datetime-local"],input[type="date"],input[type="month"],input[type="time"],input[type="week"],input[type="number"],input[type="email"],input[type="url"],input[type="search"],input[type="tel"],input[type="color"],select').addClass("form-control");

    $('form[insert-fetech-form]').on("submit", insertFetechForm);

    if (jQuery().datepicker) {
        $('input.date-picker').datepicker({
            //rtl: true,
            format: 'dd/mm/yyyy',
            autoclose: true
        });
    }

    if (jQuery().select2) {

        $('select[data-allow-select2="true"]').select2();
    }
}

function isDoubleClicked(element) {
    //if already clicked return TRUE to indicate this click is not allowed
    if (element.data("isclicked")) return true;

    //mark as clicked for 1 second
    element.data("isclicked", true);
    setTimeout(function () {
        element.removeData("isclicked");
    }, 20000);

    //return FALSE to indicate this click was allowed
    return false;
}
