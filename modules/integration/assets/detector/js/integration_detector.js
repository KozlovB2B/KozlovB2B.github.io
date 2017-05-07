/**
 * Js worker for integration possibility detection
 *
 * @constructor
 */
var IntegrationDetector = function () {

    var d = this;

    if (document.readyState === "complete") {
        d.init();
    }
    else {
        window.addEventListener("load", function () {
            d.init();
        });
    }
};

/**
 * Init
 */
IntegrationDetector.prototype.init = function () {
    var d = this;
    d.setEvents();
};

/**
 * Events
 */
IntegrationDetector.prototype.setEvents = function () {

    /**
     * Listen window message and fill up call performing page field
     *
     * @param event
     */
    function messageListener(event) {
        var data = JSON.parse(event.data);
        if (typeof data.page !== 'undefined') {
            loadElement('integration___detector_content', '/integration/detector/detect', {url: data.page});
        }
    }

    window.addEventListener ? window.addEventListener("message", messageListener) : window.attachEvent("onmessage", messageListener); // IE8;


    setInterval(function () {
        var detector_message_wrapper = $('#integration___detector_content');
        if (!detector_message_wrapper.html()) {
            detector_message_wrapper.hide();
        } else {
            detector_message_wrapper.show();
        }
    }, 500);
};