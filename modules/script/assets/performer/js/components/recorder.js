/**
 * Запись разговора
 *
 * @constructor
 */
var Recorder = function (config) {
    YiijBaseComponent.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
Recorder.prototype = Object.create(YiijBaseComponent.prototype);
Recorder.prototype.constructor = Recorder;

/**
 * Ключ, используемый для передачи команд
 * @type {number}
 */
Recorder.prototype.key = '';

/**
 * Метка включена запись или нет
 * @type {number}
 */
Recorder.prototype.file = '';

/**
 * @type {ReconnectingWebSocket}
 */
Recorder.prototype.socket;

/**
 * @type {boolean}
 */
Recorder.prototype.connected = false;

/**
 * @type {boolean}
 */
Recorder.prototype.enable = false;

/**
 * Начать запись
 */
Recorder.prototype.init = function () {
    if (!this.enable) {
        return;
    }

    var connection = this;

    this.socket = new ReconnectingWebSocket(this.host, null, {debug: true});

    this.socket.onopen = function () {
        Yiij.trace('Соединение с координатором записей разговоров открыто');
        connection.opened = true;
        connection.emit('ping');
    };

    this.socket.onclose = function (event) {
        if (event.wasClean) {
            Yiij.trace('Соединение закрыто чисто');
        } else {
            Yiij.trace('Обрыв соединения');
        }

        Yiij.trace('Код: ' + event.code + ' причина: ' + event.reason);

        connection.opened = false;
    };

    this.socket.onmessage = function (event) {
        connection.dispatch(event.data);
    };

    this.socket.onerror = function (error) {
        Yiij.trace("Ошибка " + error.message);
        connection.opened = false;
    };

    $('body').on('click', '#performer-recorder-retry-ping', function () {
        if (!$(this).hasClass('disable')) {
            $(this).addClass('disable');
            connection.file = Yiij.app.getModule('performer').account + "/" + UUID.generate();
            connection.emit('ping');
            connection.hideWarning();
        }
    });
};


/**
 * Отправляет команду
 *
 * @param command
 * @param data
 */
Recorder.prototype.emit = function (command, data) {
    if (!this.enable) {
        return;
    }

    if (!this.opened) {
        Yiij.trace('Cannot dispatch - socket is not opened!', command, data);

        return;
    }

    if (!data) {
        data = {}
    }

    data['key'] = this.key;
    data['command'] = command;

    this.socket.send(JSON.stringify(data));
};

/**
 * Обрабатывает сообщение
 */
Recorder.prototype.dispatch = function (message) {

    $('#performer-recorder-retry-ping').removeClass('disable');

    if (typeof message != 'string') {
        Yiij.trace('Unknown message: ', message);

        return;
    }

    var data = JSON.parse(message);

    if (!data.message) {
        return;
    }

    switch (data.message) {
        case 'recorder_available':
            this.hideWarning();
            break;
        case 'recorder_unavailable':
            this.file = '';
            this.showWarning();
            break;
        default:
            break;
    }
};

/**
 * Начать запись
 */
Recorder.prototype.start = function () {
    if (!this.file) {
        return;
    }

    this.emit('start', {'filename': this.file});
};

/**
 * Остановить запись
 */
Recorder.prototype.stop = function () {
    this.emit('stop');
};

/**
 * Показать предупреждение
 */
Recorder.prototype.showWarning = function () {
    $('#performer-recorder-auth-key').text(this.key);
    $('#performer-recorder-warning').show();
};

/**
 * Показать предупреждение
 */
Recorder.prototype.hideWarning = function () {
    $('#performer-recorder-warning').hide();
};