/**
 * Запись разговора
 *
 * @constructor
 */
var WsConnection = function (config) {
    YiijBaseComponent.apply(this, [config]);
};

/**
 * Extends
 * @type {YiijBaseModel}
 */
WsConnection.prototype = Object.create(YiijBaseComponent.prototype);
WsConnection.prototype.constructor = WsConnection;


/**
 * @type {string}
 */
WsConnection.prototype.user_id = '';

/**
 * @type {string}
 */
WsConnection.prototype.user_name = '';

/**
 * @type {string}
 */
WsConnection.prototype.role = '';

/**
 * @type {string}
 */
WsConnection.prototype.key = '';

/**
 * @type {string}
 */
WsConnection.prototype.host = '';

/**
 * @type {string}
 */
WsConnection.prototype.room = '';

/**
 * @type {ReconnectingWebSocket}
 */
WsConnection.prototype.socket;

/**
 * @type {boolean}
 */
WsConnection.prototype.opened = false;

/**
 * Остановить запись
 */
WsConnection.prototype.init = function () {

    var connection = this;

    this.socket = new ReconnectingWebSocket(this.host, null, {debug: true});

    this.socket.onopen = function () {
        connection.join();
        connection.opened = true;
        Yiij.app.getModule('coordinator').trigger('connected');

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

};

/**
 * Присоединяется к комнате компании
 */
WsConnection.prototype.emit = function (type, data) {
    if(!data){
        data = {}
    }

    data['type'] = type;

    this.socket.send(JSON.stringify(data));
};

/**
 * Обрабатывает сообщение
 */
WsConnection.prototype.dispatch = function (message) {
    if (typeof message != 'string') {
        Yiij.trace('Unknown message: ', message);

        return;
    }

    var data = JSON.parse(message);

    if (typeof data.type !== 'string' || !data.type.length) {
        Yiij.trace('Unknown message: ' + message);

        return;
    }

    Yiij.app.getModule('coordinator').trigger(data.type, new CoordinatorEvent({'data': data}));
};

/**
 * Присоединяется к комнате компании
 */
WsConnection.prototype.join = function () {
    this.emit('join', {
        'room': this.room,
        'role': this.role,
        'key': this.key,
        'user_id': this.user_id,
        'user_name': this.user_name
    });
};