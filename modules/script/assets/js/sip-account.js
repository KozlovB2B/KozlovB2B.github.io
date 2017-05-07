/**
 * Designer of scripts
 *
 * @constructor
 */
var SipAccount = function (config) {
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
SipAccount.prototype.init = function () {
    var c = this;
    c.initEvents();
};

/**
 * Init working events, dialogs e.t.c.
 */
SipAccount.prototype.initEvents = function () {
    var c = this;

    /**
     * Форма создания причины
     */
    setEvent('submit', '#script___sip_account__update_form', function () {
        ajaxForm("script___sip_account__update_form", "Сохранение", function (r) {
            $('#script___sip_account__update_modal').modal('hide');
            showMessage("success", r.message);

            $.pjax.reload({
                container: '#script___sip_account__index_grid',
                url: "/sip-accounts",
                timeout: 50000
            });
        });

        return false;
    });

    /**
     * Модальное окно со списком причин
     */
    setEvent('click', '.script___sip_account__update', function () {
        ajaxModal('script___sip_account__update_modal', $(this).attr("href"));
        return false;
    });
};