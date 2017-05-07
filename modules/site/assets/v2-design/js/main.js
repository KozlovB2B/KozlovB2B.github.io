var V2Design = function () {
    var v2 = this;

    v2.init();
};

V2Design.prototype.init = function () {
    var v2 = this;

    v2.setEvents();
};


V2Design.prototype.setEvents = function () {
    var v2 = this;

    //$('body').on("mouseover", '[title]', function () {
    //    if (!$(this).attr('data-hasqtip')) {
    //        var show_my = 'center left';
    //        var show_at = 'bottom center';
    //
    //        if ($(this).attr('data-my')) {
    //            show_my = $(this).attr('data-my');
    //        }
    //        if ($(this).attr('data-at')) {
    //            show_at = $(this).attr('data-at');
    //        }
    //
    //        $(this).qtip({
    //            position: {
    //                my: show_my,
    //                at: show_at
    //            }
    //        }).qtip('toggle', true);
    //
    //        //setTimeout(function(){
    //        //    elem.qtip('toggle', true);
    //        //}, 500);
    //    }
    //});


    $('body').on('click', '#func-panel-toggle-menu', function (e) {
        e.stopImmediatePropagation();
        v2.toggleMenu();
    });

    $('#main-navigation-top').on('shown.bs.dropdown hidden.bs.dropdown', function () {
        var main_navigation_top = $('#main-navigation-top');
        var body = $('body');
        var left_col = $('#menu-left-col');
        var brand = $('#brand');
        var content_wrap = $('#content-wrap');
        var func_panel = $('#func-panel');
        var offset_left = main_navigation_top.offset().left;
        if (!offset_left) {
            offset_left = left_col.outerWidth();
        }
        var offset_top = main_navigation_top.height();
        if (!offset_top) {
            offset_top = brand.outerHeight();
        }

        var css = {"transform": "translate(" + offset_left + "px, " + (offset_top) + "px)"};
        content_wrap.css(css);
        func_panel.css(css);
    });
};
//$('body').on('show.bs.modal', '.modal', function (e) {
//    $('#blur-wrap').addClass('blur');
//});
//$('body').on('hide.bs.modal', '.modal', function (e) {
//    $('#blur-wrap').removeClass('blur');
//});

V2Design.prototype.toggleMenu = function () {
    var body = $('body');
    var left_col = $('#menu-left-col');
    var brand = $('#brand');
    var main_navigation_top = $('#main-navigation-top');
    var content_wrap = $('#content-wrap');
    var func_panel = $('#func-panel');

    var offset_left = main_navigation_top.offset().left;
    if (!offset_left) {
        offset_left = left_col.outerWidth();
    }
    var offset_top = main_navigation_top.height();
    if (!offset_top) {
        offset_top = brand.outerHeight();
    }

    var css = {"transform": "translate(" + offset_left + "px, " +offset_top + "px)"};

    if (body.hasClass('show-menu')) {
        css = {"transform": "none"};
        body.removeClass('show-menu');
    }
    else {
        body.addClass('show-menu');
    }

    content_wrap.css(css);
    func_panel.css(css);
};