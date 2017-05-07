/**
 * Отображатель сообщений
 *
 * @param config
 * @constructor
 */
var Messenger = function (config) {
    YiijBaseComponent.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseComponent}
 */
Messenger.prototype = Object.create(YiijBaseComponent.prototype);
Messenger.prototype.constructor = Messenger;

/**
 * @type {{}}
 */
Messenger.prototype.overlay = function (message) {

    var overlay = $('#messenger-overlay');

    if (!overlay.length) {
        overlay = $('<div id="messenger-overlay" class="overlay-message"></div>');
        $('body').append(overlay);
    }

    var error = $('<div id="messenger-overlay" class="overlay-message"></div>');
    overlay.html(message).show();
};

/**
 * @type {{}}
 */
Messenger.prototype.hideOverlay = function () {
    $('#messenger-overlay').hide()
};