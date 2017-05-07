/**
 * A gift for our new users
 * @constructor
 */
var Gift = function () {
    this.init();
};

/**
 *
 */
Gift.prototype.init = function () {
    this.setEvents();
};

/**
 *
 */
Gift.prototype.setEvents = function () {
    $('body').off('click', '.script___gift__button').on('click', '.script___gift__button', function () {
        get($(this).attr('href'), function (res) {
            $('#script___gift__banner').find('.script___gift__message').html(res.message);
            showMessage('success', res.message);
            app.reloadPjax('script___script__main_page_list_grid');
        });

        $('.script___gift__button').remove();

        return false;
    });
};