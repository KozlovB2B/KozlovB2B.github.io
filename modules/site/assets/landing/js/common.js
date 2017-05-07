$(document).ready(function() {
    $('a[href^="#"]').on('click',function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': ($target.offset().top - 8)
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });

	//Chrome Smooth Scroll
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch(err) {

	}

	$("img, a").on("dragstart", function(event) { event.preventDefault(); });

    $('.show_menu').click(function() {
        $('.header nav').toggleClass('hide_mobile_menu');
    });

    $('.show_menu_public').click(function() {
        $('.public-nav').toggleClass('hide_mobile_menu');
    });

});