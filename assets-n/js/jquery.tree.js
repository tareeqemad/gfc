$.fn.tree = function(){

    var fn= $.fn.tree;
    var tree;

    fn.selected = function(){

        return $('li > span.selected',tree);
    }

    fn.add = function(text,value,click){

        var html = $('<li/>');

        $(html).attr('title','طي هذا الفرع');
        $(html).append('<span ondblclick="'+click+'"/>');
        $('span',html).append('<i class="fa fa-minus"></i>'+text);
        $('span',html).attr('data-id',value);

        var rootLI = fn.selected().parent();
        var list = $('ul:first',rootLI);

        if(list.length > 0)
            list.append(html);
        else {

            rootLI.append('<ul></ul>');
            $('ul:first',rootLI).append(html);
            rootLI.addClass('parent_li');
        }

        // لا نربط يدوياً هنا؛ الـ delegation في build يغطي العقد الجديدة
    }

    fn.filter = function(text){
        $('li > span.filterd',tree).removeClass('filterd');

        $('li > span:contains("'+text+'")',tree).each(function() {
            $(this).addClass('filterd');
        });

        $('li',tree).hide();
        $('li > span.filterd',tree).parents('li').show();
        $('i.fa-plus',$('li > span.filterd')).addClass('fa-minus').removeClass('fa-plus')
    }

    fn.update = function(text){
        var iClass = fn.selected().find('i').attr('class');
        fn.selected().html('<i class="'+iClass+'"></i>'+text);
        // الـ delegation على الشجرة يغطي الأيقونة الجديدة
    }

    fn.remove =function(){

        fn.selected().parent().remove();
    }

    fn.removeElem =function(elem){

        elem.parent().remove();
    }

    fn.lastElem = function(){

        var Li =  $(' ul:first> li',fn.selected().parent()).last();
        return $('span:first',Li);
    }

    fn.level = function(){

        return $(fn.selected().parent()).parents('ul').length;
    }

    fn.expandAll = function(){
        var children = tree.find('ul > li');
        $('i.fa-plus',tree).addClass('fa-minus').removeClass('fa-plus');
        children.show('fast');
    }

    fn.expandSelected = function(){
        var children = $fn.selected.parents('ul > li');
        $('i.fa-plus',fn.selected).addClass('fa-minus').removeClass('fa-plus');
        children.show('fast');children.find('ul:first>li').show();
    }

    fn.collapseAll= function(){
        var children = tree.find('ul > li');
        $('i.fa-minus',tree).addClass('fa-plus').removeClass('fa-minus');
        children.hide('fast');
    }

    fn.nodeText = function(val){

        return $.fn.tree.selected().parents('li').find('span[data-id="'+val+'"]:first').text();
    }
    spanClick = function (e) {
        $('.selected',tree).removeClass('selected');

        $(this).addClass('selected');

    }

    expande = function(e){

        var children = $(this).parent('span').parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).parent('span').attr('title', 'توسيع هذا الفرع').find(' > i').addClass('fa-plus').removeClass('fa-minus');
        } else {

            children.show('fast');
            $(this).parent('span').attr('title', 'طي هذا الفرع').find(' > i').addClass('fa-minus').removeClass('fa-plus');

        }
        e.stopPropagation();
    }

    build = function(){

        tree = $(this);

        tree.height($(window).height() - 250 );

        $('li:first > span',tree).addClass('selected');

        var root =$('li:has(ul)',tree);

        root.addClass('parent_li').find(' > span').attr('title', 'طي هذا الفرع');

        root.find(' > ul > li').hide();

        $('li.parent_li > span',tree).attr('title', 'توسيع هذا الفرع').find(' > i').addClass('fa-plus').removeClass('fa-minus');

        // تفويض الأحداث مرة واحدة فقط حتى تعمل التوسيع/الطي بعد التنقل بين الفروع أو إضافة عقد
        tree.on('click', 'li > span', spanClick);
        tree.on('click', 'li > span > i', expande);

        $('.tbl',tree).each(function()
        {

            $(this).css({left: ($($(this).parent()).parents('ul').length -1) * -5  });




        });




    }

    return this.each( build );
};
