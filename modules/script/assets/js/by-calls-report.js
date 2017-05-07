/**
 *
 * @constructor
 */
var ByCallsReport = function () {
    var c = this;


    if (document.readyState === "complete") {
        c.init();
    }
    else {
        window.addEventListener("load", function () {
            c.init();
        });
    }
};
/**
 * Designer init
 */
ByCallsReport.prototype.init = function () {
    var c = this;
    c.initEvents();
};

/**
 * Init working events, dialogs e.t.c.
 */
ByCallsReport.prototype.initEvents = function () {
    var c = this;

    /**
     * Форма создания причины
     */
    setEvent('click', '.script___report___play', function () {
        ajaxModal('script___report___listen_play_modal', $(this).attr('href'));
        return false;
    });
};