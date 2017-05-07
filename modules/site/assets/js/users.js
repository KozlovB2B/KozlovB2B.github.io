/**
 * Operator constructor
 * @constructor
 */
var Users = function () {
    var o = this;
    window.addEventListener("load", function () {
        o.initEvents();

    });
};
/**
 * Operator event handlers
 */
Users.prototype.initEvents = function () {

    /**
     * Модал обновления реквизитов
     */
    setEvent('click', '#billing___account__update_button', function () {
        ajaxModal("billing___account__update_modal", "/billing/account/update?id=" + $(this).data('id'));

        return false;
    });

/**
     * Модал обновления реквизитов
     */
    setEvent('click', '#site___users___change_password', function () {
        ajaxModal("site___users___change_password_modal", "/site/users/change-password?id=" + $(this).data('id'));

        return false;
    });

    /**
     * Форма обновления реквизитов
     */
    setEvent('submit', '#site___users___change_password_form', function () {
        ajaxForm("site___users___change_password_form", "Загрузка", function (r) {
            $('#site___users___change_password_modal').modal('hide');
            showMessage('success', r.message);
        });

        return false;
    });

    /**
     * Форма обновления реквизитов
     */
    setEvent('submit', '#billing___account__update_form', function () {
        ajaxForm("billing___account__update_form", "Загрузка", function (r) {
            $('#billing___account__update_modal').modal('hide');
            showMessage('success', r.message);
        });

        return false;
    });

    /**
     * Модал обновления реквизитов
     */
    setEvent('click', '#billing___account__set_rate_button', function () {
        ajaxModal("billing___account__set_rate_modal", "/billing/account/set-rate?user_id=" + $(this).data('id'));

        return false;
    });

    /**
     * Модал обновления реквизитов
     */
    setEvent('change', '#billing___account__set_rate_form_id', function () {
        $('#billing___account__set_rate_form_fields input[type="text"]').val('').attr('disabled', !!$(this).val());
        $('#billing___account__set_rate_form_fields input[type="checkbox"]').attr('disabled', !!$(this).val());
        return true;
    });

    /**
     * Форма обновления реквизитов
     */
    setEvent('submit', '#billing___account__set_rate_form', function () {
        ajaxForm("billing___account__set_rate_form", "Загрузка", function (r) {
            $('#billing___account__set_rate_modal').modal('hide');
            showMessage('success', r.message);
        });

        return false;
    });
};
