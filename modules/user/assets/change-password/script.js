/**
 * Форма смены пароля
 *
 * @param id
 * @constructor
 */
var ChangePasswordForm = function () {
    var self = this;
    document.readyState === "complete" ? self.initEvents() : window.addEventListener("load", self.initEvents);
};

ChangePasswordForm.prototype.initEvents = function () {
    on("submit", "#user___user__change_password_form", function () {
        var form = $(this);

        ajaxForm('user___user__change_password_form', 'Смена пароля', function (r) {
            message(r.type, r.message);
            form.trigger('reset');
            $('#user___user__change_password_modal').modal('hide');
        });
        return false;
    });
};