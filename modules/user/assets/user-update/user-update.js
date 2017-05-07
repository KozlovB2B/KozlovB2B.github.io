/**
 * Форма регистрации оператора
 *
 * @param id
 * @constructor
 */
var UserUpdate = function (id) {
    var self = this;

    self.id = id;

    document.readyState === "complete" ? self.initEvents() : window.addEventListener("load", self.initEvents);
};

/**
 * События
 */
UserUpdate.prototype.initEvents = function () {

    /**
     * Удаление профиля
     */
    on("click", ".user___profile__delete_button", function () {

        if (confirm('Вы действительно хотите удалить этот профиль?')) {
            ajax($(this).attr('href'), null, function () {
                window.location.reload();
            });
        }

        return false;
    });
    /**
     * Добавление профиля пользователю
     */
    on("click", "#user___profile___create_button", function () {
        ajaxModal('user___profile__create_modal', $(this).attr('href'));
        return false;
    });

    /**
     * Добавление профиля пользователю
     */
    on("change", "#user___profile__update_form-profile", function () {
        loadElement('user___profile__update_form-profile_fields', '/user/profile/update-form?id=' + $(this).data('id') + '&profile=' + $(this).val())
        return false;
    });

    /**
     * Добавление профиля пользователю
     */
    on("change", "#user___profile__create_form-profile_class", function () {
        loadElement('user___profile__create_form-profile_fields', '/user/profile/create-form?profile=' + $(this).val())
        return false;
    });

    /**
     * Добавление профиля пользователю
     */
    on("submit", "#user___profile__create_form", function () {
        ajaxForm('user___profile__create_form', 'Добавление профиля', function (r) {
            message(r.type, r.message);
            $('#user___profile__create_modal').modal('hide');
        });
        return false;
    });
    /**
     * Добавление профиля пользователю
     */
    on("submit", "#user___profile__update_form", function () {
        ajaxForm('user___profile__update_form', 'Обновление данных профиля', function (r) {
            message(r.type, r.message);
        });
        return false;
    });
};