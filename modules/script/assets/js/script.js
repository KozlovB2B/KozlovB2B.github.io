/**
 * Designer of scripts
 *
 * @constructor
 */
var Script = function (config) {
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
Script.prototype.init = function () {
    var c = this;
    c.initEvents();
};

/**
 * Init working events, dialogs e.t.c.
 */
Script.prototype.initEvents = function () {
    var c = this;

    /**
     * Script export restricted modal
     */
    setEvent('click', '.script___script__export_restricted_modal_button', function () {
        ajaxModal('script___script__export_restricted_modal', $(this).attr("href"));
        return false;
    });
};