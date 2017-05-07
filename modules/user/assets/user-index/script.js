/**
 * Форма регистрации оператора
 *
 * @param id
 * @constructor
 */
var UserIndex = function (id) {
    var self = this;

    self.id = id;

    document.readyState === "complete" ? self.initEvents() : window.addEventListener("load", self.initEvents);
};

/**
 * События
 */
UserIndex.prototype.initEvents = function () {

    /**
     * Блокировка пользователя
     */
    on("click", ".user___user___block", function () {
        var button = $(this),
            is_blocking = button.attr('data-blocked') == 0;

        if (!confirm('Вы действительно хотите ' + (is_blocking ? "заблокировать" : "разблокировать") + ' этого пользователя?')) {
            return false;
        }

        ajax(button.attr('href'), null, function (r) {
            message(r.type, r.message);

            if (is_blocking) {
                button.removeClass('btn-danger');
                button.addClass('btn-success');
                button.html('Разблокировать');
                button.attr('data-blocked', 1);
            } else {
                button.removeClass('btn-success');
                button.addClass('btn-danger');
                button.html('Блокировать');
                button.attr('data-blocked', 0);
            }
        });

        return false;
    });

    /**
     * Создание пользователя
     */
    on("click", "#user___user___create_button", function () {
        ajaxModal('user___user___create_modal', $(this).attr('href'));
        return false;
    });

    /**
     * Создание пользователя
     */
    on("change", "#user___user___create_form-profile", function () {
        loadElement('user___user___create_form_profile_fields', '/user/profile/create-form?profile=' + $(this).val())
        return false;
    });
    /**
     * Создание пользователя
     */
    on("change", "#user___user___create_form-scenario", function () {
        $('.user___user___create_form-scenario-fields').hide();
        $('#user___user___create_form-scenario-' + $(this).val() + '-fields').show();
        return false;
    });

    /**
     * Создание пользователя
     */
    on("submit", "#user___user___create_form", function () {
        ajaxForm('user___user___create_form', 'Создание пользователя', function (r) {
            message(r.type, r.message);
            $('#user___user___create_modal').modal('hide');
            //$.pjax.reload({container: '#user___user__index_grid'});
        });
        return false;
    });

    on("nodeSelected", "#user___user___create_form", function () {
        ajaxForm('user___user___create_form', 'Создание пользователя', function (r) {
            message(r.type, r.message);
            $('#user___user___create_modal').modal('hide');
            //$.pjax.reload({container: '#user___user__index_grid'});
        });
        return false;
    });


    $('#aff___category__tree').on('nodeSelected', function(event, data) {
    });
};