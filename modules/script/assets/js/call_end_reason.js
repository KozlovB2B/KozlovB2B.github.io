/**
 * Designer of scripts
 *
 * @constructor
 */
var CallEndReason = function (config) {
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
CallEndReason.prototype.init = function () {
    var c = this;
    c.initEvents();
};

/**
 * Init working events, dialogs e.t.c.
 */
CallEndReason.prototype.initEvents = function () {
    var c = this;

    /**
     * Форма создания причины
     */
    setEvent('submit', '#script___call_end_reason__create_form', function () {
        ajaxForm("script___call_end_reason__create_form", "Сохранение", function (r) {
            $("#script___call_end_reason__create_form").trigger("reset");
            showMessage("success", r.message);
            $.pjax.reload({
                container: '#script___call_end_reason__list_grid',
                url: "/script/call-end-reason/list-grid",
                timeout: 50000
            });

        });

        return false;
    });

    /**
     * Модальное окно со списком причин
     */
    setEvent('click', '#script___call_end_reason__list_modal_button', function () {
        ajaxModal('script___call_end_reason__list_modal', $(this).attr("href"));
        return false;
    });




    /**
     * Обязателен или не обязателен комментарий
     */
    setEvent('change', '.script___call_end_reason__list_toggle_comment_required', function () {

        var checked = 0;
        if ($(this).prop("checked")) {
            checked = 1;
        }

        ajax("/script/call-end-reason/toggle-comment-required?id=" + $(this).val() + "&value=" + checked, null, function () {
        });
        return true;
    });
    /**
     * Обязателен или не обязателен комментарий
     */
    setEvent('click', '.script___call_end_reason__list_delete_button', function () {
        //alert($(this).attr("href"));
        //return false;
        if (confirm("Вы уверены, что хотите удалить причину завершения?")) {
            var el = $(this).closest("tr");

            ajax($(this).attr("href"), null, function () {
                el.remove();
            });
        }

        return false;
    });

    /**
     * Обязателен или не обязателен комментарий
     */
    setEvent('click', '#script___call_end_reason__import_default_list_button', function () {
        ajax("/script/call-end-reason/import-default-list", null, function (r) {
            showMessage("success", r.message);
            $("#script___call_end_reason__list_modal").modal("hide");
        });
        return true;
    });
};