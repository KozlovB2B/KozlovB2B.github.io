/**
 * Контроллер узла
 *
 * @param config
 * @constructor
 */
var ScriptController = function (config) {

    /**
     * Объект, отвечающий за представление узлов
     *
     * @type {ScriptView}
     */
    this.view;

    /**
     * @type {Script}
     */
    this.script;

    /**
     * @type {ScriptForm}
     */
    this.script_form;

    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
ScriptController.prototype = Object.create(YiijBaseController.prototype);
ScriptController.prototype.constructor = ScriptController;

/**
 * @inheritdoc
 */
ScriptController.prototype.requiredConfig = function () {
    return ['view', 'script', 'script_form'];
};


/**
 * Инициализация контроллера узлов
 */
ScriptController.prototype.init = function () {
    Yiij.trace('Инициализация контроллера скрипта.');

    this.setEvents();
};

/**
 * Найти или создать узел
 *
 * @param id
 * @returns {Node}
 */
ScriptController.prototype.getOrCreate = function (id) {
    if (this.script.id != id) {
        throw new Error('Неверный скрипт - ' + id);
    }

    return this.script;
};

/**
 * Получение данных по скрипту
 *
 * @returns {Script}
 */
ScriptController.prototype.get = function (id) {
    Yiij.trace('Получаю данные по скрипту ' + this.script.id + '.');
    return this.script;
};


/**
 * Отображение узла
 */
ScriptController.prototype.renderScript = function () {
    Yiij.trace('Рисую представления скрипта.');

    if (!this.view.get()) {
        this.view.create(this.script);
    }

    this.view.applyChanges(this.script);
};


/**
 * Отображение узла
 *
 * @param {Script} n
 */
ScriptController.prototype.setEvents = function (n) {
    var sc = this;

    $("body").on('click', '#script-edit-name-button', function (e) {
        $('#editor___script__name').focus();
    });

    $("body").on('click', '#editor___function__draft_new_release', function (e) {
        app.openPjaxModal("#script___release__create_modal_pjax", "/script/release/create?id=" + Yiij.app.getModule('editor').scriptController.script.id);

        return false;
    });


    $("body").on('click', '#editor___function__performer_options', function (e) {
        app.openPjaxModal("#editor___function__performer_options_pjax", "/script/script/performer-options?id=" + Yiij.app.getModule('editor').scriptController.script.id);

        return false;
    });

    $("body").on('click', '#editor___function__editor_options', function (e) {
        app.openPjaxModal("#editor___function__editor_options_pjax", "/script/script/editor-options?id=" + Yiij.app.getModule('editor').scriptController.script.id);

        return false;
    });


    $("body").on('click', '#editor___function__image_export', function (e) {
        app.openPjaxModal("#editor___function__image_export_pjax", "/script/script-image/list-modal?script_id=" + Yiij.app.getModule('editor').scriptController.script.id);

        return false;
    });

    $("body").on('click', '#script___script_image__create', function (e) {
        $(this).remove();

        $('#script___script_image__pending').show();

        var data = Yiij.app.getModule('editor').imageExporter.export();

        $.ajax({
            async: true,
            method: "POST",
            url: '/script/script-image/create?script_id=' + Yiij.app.getModule('editor').scriptController.script.id,
            dataType: 'json',
            data: {'svg': data},
            success: function () {
                reloadPjax('script___script_image__list_form_container');
            },
            error: function (jqXHR) {
                $('#script___script_image__pending').hide();
                alert(jqXHR.responseJSON.message);
            }
        });

        return false;
    });


    $("body").on('change', '#editor___script__name', function (e) {
        if ($(this).val() != sc.script.name) {
            Yiij.app.getModule('editor').create({
                'model_class': 'Script',
                'model_id': sc.script.id,
                'p': {
                    'name': $(this).val()
                },
                'r': {
                    'name': sc.script.name
                }
            });
        }

        return true;
    });

    $("body").on('change', '#editor___script__start_node_uuid', function (e) {
        if ($(this).val() != sc.script.start_node_uuid) {

            var uuid = $(this).val();

            Yiij.app.getModule('editor').create({
                'model_class': 'Script',
                'model_id': sc.script.id,
                'p': {
                    'start_node_uuid': uuid
                },
                'r': {
                    'start_node_uuid': sc.script.start_node_uuid
                }
            });

            // Подсвечиваем старт
            $('.node-head').removeClass('start');

            if (uuid) {
                $('#' + uuid).find('.node-head').addClass('start');
            }
        }
    });
};

