/**
 * Created by tekrayem on 29/06/15.
 */
$(function(){

    $('#tabs #nav li a').click(function(){

        var currentNum = $(this).attr('id').slice(-1);
        $('#tabs #nav li a').removeClass('current');
        $(this).addClass('current');

        $('#tabs #content .tab-slide').hide();
        $('#tabs #content #slide-'+currentNum+'.tab-slide').show();
    });



    $('#tabsSlide #nav li a').click(function(){

        var currentNum = $(this).attr('id').slice(-1);
        $('#tabsSlide #nav li a').removeClass('current');
        $(this).addClass('current');

        $('#tabsSlide #content .tab-slide').slideUp(300);
        $('#tabsSlide #content #slide-'+currentNum+'.tab-slide').slideDown(300);
    });

});

$(".nexttab").click(function() {
    $("#tabs").tabs("select", this.hash);
});
