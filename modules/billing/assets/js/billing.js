/**
 *
 * @param config
 * @constructor
 */
var Payment = function (config) {
    var c = this;
    c.params = config;


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
Payment.prototype.init = function () {
    var c = this;
    c.initEvents();
};

/**
 * Init working events, dialogs e.t.c.
 */
Payment.prototype.initEvents = function () {
    var c = this;

    /**
     * Форма создания причины
     */
    setEvent('submit', '#billing___payment__pay_form', function () {
        ajaxForm("billing___payment__pay_form", window['app']['loading_message'], function (r) {
            $("#billing___payment__pay_form").trigger("reset");
            $('#billing___payment__pay_modal').modal('hide');
            showMessage('success', c.params.redirect_message);

            if (r.message.match(/<form.*/i)) {
                $('body').append('<div id="billing___payment__redirect_form" class="hide">' + r.message + '</div>');
                $('#billing___payment__redirect_form').find('form').trigger('submit');
            } else {
                window.location.href = r.message;
            }
        });

        return false;
    });
};

/**
 *
 * @param config
 * @constructor
 */
var BillingAccount = function (config) {
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
BillingAccount.prototype.init = function () {
    var c = this;
    c.initEvents();
};

/**
 * Init working events, dialogs e.t.c.
 */
BillingAccount.prototype.initEvents = function () {
    var c = this;


    /**
     * Форма создания причины
     */
    setEvent('submit', '#billing___account__change_rate_form', function () {
        ajaxForm("billing___account__change_rate_form", window['app']['loading_message'], function (r) {
            $('#billing___account__change_rate_modal').modal('hide');
            showMessage('success', r.message);
            window.location.href = '/billing';
        });

        return false;
    });

    /**
     * Форма обновления реквизитов
     */
    setEvent('submit', '#billing___bank_props__edit_from', function () {
        ajaxForm("billing___bank_props__edit_from", window['app']['loading_message'], function (r) {
            $('#billing___bank_props__edit_modal').modal('hide');
            showMessage('success', r.message);
            window.location.href = '/billing';
        });

        return false;
    });


    /**
     * Модал обновления реквизитов
     */
    setEvent('click', '#billing___bank_props__edit_button', function () {
        ajaxModal("billing___bank_props__edit_modal", "/billing/bank-props/edit");

        return false;
    });

    /**
     * Форма создания причины
     */
    setEvent('change', '#billing___account__rate_id', function () {
        $.pjax.reload({
            container: '#billing___rate__change_restrictions',
            url: "/billing/rate/change-restrictions?id=" + $(this).val(),
            timeout: 50000
        });

        return false;
    });
};
