/**
 * Контроллер рабочей области
 *
 * @param config
 * @constructor
 */
var CallController = function (config) {
    YiijBaseController.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
CallController.prototype = Object.create(YiijBaseController.prototype);
CallController.prototype.constructor = CallController;

/**
 * @type {Call}
 */
CallController.prototype.model;

/**
 * @type {{}}
 */
CallController.prototype.releases = {};


/**
 * Инициализация контроллера групп
 */
CallController.prototype.init = function () {
    Yiij.trace('Инициализация контроллера звонка');
    this.setEvents();
    this.runHash();
};

/**
 * Загрузка данных публикации

 * @param script_id
 * @param mode
 * @param start_node_uuid
 * @param dataset
 */
CallController.prototype.loadRelease = function (script_id, mode, start_node_uuid, dataset) {
    Yiij.trace('Загружаю данные скрипта: ' + script_id + ' режим: ' + mode);

    var cc = this;

    $.ajax({
        'type': 'get',
        'async': true,
        'dataType': 'JSON',
        'url': '/script/script/data?id=' + script_id + '&mode=' + mode,
        'success': function (data) {
            cc.releases[script_id] = data;

            cc.model = new Call({
                'mode': mode,
                'script_id': script_id,
                'release_id': cc.releases[script_id]['release_id'],
                'start_node_uuid': start_node_uuid,
                'dataset': dataset,
                'data': cc.releases[script_id]['build']
            });

            cc.model.ready();
        },
        error: function (jqXHR, textStatus, errorThrown) {

            if (jqXHR.responseJSON) {
                Yiij.app.getModule('performer').workspaceController.showError(jqXHR.responseJSON.message);
            } else {
                window.location.href = '/';
            }
        }
    });
};


/**
 * Подготовка звонка
 *
 * @param script_id
 * @param mode
 * @param start_node_uuid
 * @param dataset
 */
CallController.prototype.prepare = function (script_id, mode, start_node_uuid, dataset) {
    Yiij.app.getModule('performer').workspaceController.workspace.message = "Загрузка...";
    Yiij.app.getModule('performer').workspaceController.stateTo(Workspace.STATE_MESSAGE);

    if (mode == "test" || typeof this.releases[script_id] == 'undefined') {
        this.loadRelease(script_id, mode, start_node_uuid, dataset);
    } else {
        this.model = new Call({
            'mode': mode,
            'script_id': script_id,
            'release_id': this.releases[script_id]['release_id'],
            'start_node_uuid': start_node_uuid,
            'dataset': dataset,
            'data': this.releases[script_id]['build']
        });

        this.model.ready();
    }
};

/**
 *
 */
CallController.prototype.start = function () {

    if (!this.model) {
        throw new Error('Нет звонка для старта!');
    }

    if (this.model.id) {
        return;
    }

    this.model.id = ' ... ';

    Yiij.trace('Запрашиваю ID звонка');

    var cc = this;

    $.ajax({
        'type': 'POST',
        'async': true,
        'dataType': "JSON",
        'data': {
            'data': cc.model.dataset
        },
        'url': '/script/call/start?script_id=' + cc.model.script_id + '&release_id=' + cc.model.release_id,
        'success': function (data) {
            cc.model.id = data;
            Yiij.app.getModule('performer').workspaceController.workspace.call_id = data;
            Yiij.app.getModule('performer').workspaceController.updateView();
        },
        error: function (jqXHR, textStatus) {
            cc.model.destroy();
            cc.model.id = null;
            Yiij.app.getModule('performer').workspaceController.updateView();

            if (jqXHR.responseJSON) {
                Yiij.app.getModule('performer').workspaceController.showError(jqXHR.responseJSON.message);
            } else {
                window.location.href = '/';
            }
        }
    });

    Yiij.app.getModule('performer').workspaceController.stateTo(Workspace.STATE_CALL);

    this.model.setUnloadTrigger();

    if (cc.model.mode != 'test') {
        Yiij.app.getModule('performer').recorder.start();
    }
};

/**
 * Обрабатывает текущий хеш и выполняет действия со звонком
 */
CallController.prototype.runHash = function () {
    var wc = this;

    var data = window.location.hash.split('/');

    var script_id = null;
    var start_node_id = null;
    var dataset = null;
    var mode = 'call';

    for (var i = 1; i < data.length; i += 2) {
        var key = data[i];
        var value = data[i + 1];
        switch (key) {
            case 'call':
                script_id = value;
                break;
            case 'data':
                dataset = value;
                break;
            case 'start':
                start_node_id = value;
                break;
            case 'mode':
                mode = value;
                break;
                break;
            case 'fields':
                // Ожидается, что контекст будет передан как base64 кодированная строка
                Yiij.app.getModule("context").setData(JSON.parse(Base64.decode(value)));
                break;
        }
    }

    if (script_id) {
        wc.prepare(script_id, mode, start_node_id, dataset);
    } else {
        if (wc.model) {
            wc.model.destroy();
        }

        Yiij.app.getModule('performer').workspaceController.stateTo(Workspace.STATE_HIDDEN);
    }

    parent.postMessage(JSON.stringify({location: window.location.href}), "*");
};

/**
 * Отображение группы
 *
 * @param {Call} n
 */
CallController.prototype.setEvents = function (n) {
    var wc = this;

    $(window).on('hashchange', function () {
        wc.runHash();
    });

    $("body").on('click', '.variant-button', function () {

        if (!wc.model.id) {
            wc.start();
        }

        wc.model.to($(this).attr('data-node'), $(this).attr('data-variant'));

        Yiij.app.getModule('performer').workspaceController.updateView();

        return false;
    });

    $("body").on('click', '#performer___functions_back', function () {
        wc.model.back();
        Yiij.app.getModule('performer').workspaceController.updateView();
        return false;
    });


    $("body").on('click', '.guard-terminate-sessions', function () {
        $.ajax({
            'type': 'get',
            'async': true,
            'url': $(this).attr('href'),
            'success': function (data) {
                wc.prepare(wc.model.script_id, wc.model.release_id, wc.model.start_node_uuid, wc.model.dataset);
            },
            error: function (jqXHR, textStatus) {
                Yiij.app.getModule('performer').workspaceController.showError('При сбросе сессий произошла ошибка!');
            }
        });

        return false;
    });

    $("body").on('click', '#performer___functions_end_call', function () {
        wc.model.end();
        return false;
    });

    $("body").on('click', '#performer___close a', function () {
        if (wc.model && wc.model.id && wc.model.id != 'test') {
            if (confirm('Вы уверены, что хотите закрыть прогонщик во время звонка?')) {
                wc.model.sendEnd();

                return true;
            }
        }

        return true;
    });

    $("body").on('submit', '#performer___form', function () {

        if ($(this).hasClass("disabled")) {
            return false;
        }

        wc.model.sendReport();

        return false;
    });
};