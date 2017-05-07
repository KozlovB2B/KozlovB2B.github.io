/**
 * Обновление данных пользователем
 *
 * @constructor
 */
var UserUpdateProfile = function () {
    var self = this;
    document.readyState === "complete" ? self.initEvents() : window.addEventListener("load", self.initEvents);
};

UserUpdateProfile.prototype.initEvents = function () {
    on("submit", "#user___profile__user_update_form", function () {
        ajaxForm('user___profile__user_update_form', 'Обновление данных', function (r) {
            message(r.type, r.message);
        });
        return false;
    });
};