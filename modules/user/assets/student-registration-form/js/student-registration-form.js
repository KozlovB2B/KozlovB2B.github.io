/**
 * Форма регистрации оператора
 *
 * @param id
 * @constructor
 */
var OperatorRegistrationForm = function (id) {
    var self = this;

    self.id = id;

    document.readyState === "complete" ? self.initEvents() : window.addEventListener("load", self.initEvents);
};

/**
 * События
 */
OperatorRegistrationForm.prototype.initEvents = function () {
    var o = this;

    on("click", "#user___operator__register_form_send_phone_code", function () {
        var phone = $("#user___operator__register_form_phone");

        if (!phone.val()) {
            return false;
        }

        var msg_block = phone.parent().find('.help-block');
        phone.parent()
            .removeClass('has-error')
            .removeClass('has-success');

        ajax('/user/confirm-phone-code/request?phone=' + phone.val(), null, function (r) {
            var msg = stripTags(r.message);
            if (r.type == 'error') {
                phone.parent().addClass('has-error');
                msg_block.html(msg);
            } else {
                phone.parent().addClass('has-success');
                msg_block.html(msg);
            }
        });
        return false;
    });
};