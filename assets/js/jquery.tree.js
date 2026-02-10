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
        $('span',html).append('<i class="glyphicon glyphicon-minus-sign"></i>'+text);
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

        $('span',html).on('click', spanClick);
        $('li > span > i',tree).on('click', expande);

    }

    fn.filter = function(text){
        $('li > span.filterd',tree).removeClass('filterd');

        $('li > span:contains("'+text+'")',tree).each(function() {
            $(this).addClass('filterd');
        });

        $('li',tree).hide();
        $('li > span.filterd',tree).parents('li').show();
        $('i.glyphicon-plus-sign',$('li > span.filterd')).addClass('glyphicon-minus-sign').removeClass('glyphicon-plus-sign')
    }

    fn.update = function(text){
        var iClass = fn.selected().find('i').attr('class');
        fn.selected().html('<i class="'+iClass+'"></i>'+text);

        //$('span',html).on('click', spanClick);
        $('i',fn.selected()).on('click', expande);
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
        $('i.glyphicon-plus-sign',tree).addClass('glyphicon-minus-sign').removeClass('glyphicon-plus-sign');
        children.show('fast');
    }

    fn.expandSelected = function(){
        var children = $fn.selected.parents('ul > li');
        $('i.glyphicon-plus-sign',fn.selected).addClass('glyphicon-minus-sign').removeClass('glyphicon-plus-sign');
        children.show('fast');children.find('ul:first>li').show();
    }

    fn.collapseAll= function(){
        var children = tree.find('ul > li');
        $('i.glyphicon-minus-sign',tree).addClass('glyphicon-plus-sign').removeClass('glyphicon-minus-sign');
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
            $(this).parent('span').attr('title', 'توسيع هذا الفرع').find(' > i').addClass('glyphicon-plus-sign').removeClass('glyphicon-minus-sign');
        } else {

            children.show('fast');
            $(this).parent('span').attr('title', 'طي هذا الفرع').find(' > i').addClass('glyphicon-minus-sign').removeClass('glyphicon-plus-sign');

        }
        e.stopPropagation();
    }

    build = function(){

        tree = $(this);

        tree.height($(window).height() - 300 );

        $('li:first > span',tree).addClass('selected');

        var root =$('li:has(ul)',tree);

        root.addClass('parent_li').find(' > span').attr('title', 'طي هذا الفرع');

        root.find(' > ul > li').hide();

        $('li.parent_li > span',tree).attr('title', 'توسيع هذا الفرع').find(' > i').addClass('glyphicon-plus-sign').removeClass('glyphicon-minus-sign');

        $('li > span',tree).on('click', spanClick);
        $('li > span > i',tree).on('click', expande);

        $('.tbl',tree).each(function()
        {

            $(this).css({left: ($($(this).parent()).parents('ul').length -1) * -5  });




        });




    }

    return this.each( build );
};
