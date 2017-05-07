/**
 *
 * @constructor
 */
var VariantsReport = function () {
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
VariantsReport.prototype.init = function () {
    var c = this;
    c.initEvents();
};

/**
 * Init working events, dialogs e.t.c.
 */
VariantsReport.prototype.initEvents = function () {
    var c = this;
    /**
     * Форма создания причины
     */
    setEvent('change', '#script___variants_report__script_id', function () {
        var nodes_list_elem = $('#script___variants_report__node_id');

        if (!$(this).val()) {
            nodes_list_elem.html('');
        } else {
            $.ajax({
                type: "GET",
                url: '/script/report/load-nodes-list?script_id=' + $(this).val(),
                success: function (r) {
                    nodes_list_elem.html(r);
                }
            });
        }

        return true;
    });
};