/**
 * Куратор WS соединения
 *
 * @param data
 * @constructor
 */
var CentrifugoSocket = function (data) {

    var ws = this;

    /**
     * @type {string}
     */
    ws.user;

    /**
     * @type {string}
     */
    ws.url;

    /**
     * @type {string}
     */
    ws.timestamp;

    /**
     * @type {string}
     */
    ws.token;

    /**
     * @type {string}
     */
    ws.channel;

    /**
     * @type {Centrifuge}
     */
    ws.centrifuge;

    $.extend(ws, data);

    ws.init();
};

/**
 * Инициализация объекта куратора WS соединения
 */
CentrifugoSocket.prototype.init = function () {
    var ws = this;

    Yiij.trace('Инициализация объекта куратора WS соединения');

    checkRequired(ws, ['user', 'url', 'timestamp', 'token', 'channel']);

    ws.establishConnection()
};

/**
 * Инициализирует соединение
 */
CentrifugoSocket.prototype.establishConnection = function () {

    var ws = this;

    ws.centrifuge = new Centrifuge({
        'user': ws.user,
        'url': ws.url,
        'timestamp': ws.timestamp,
        'token': ws.token
    });

    Yiij.trace('Подписываюсь на канал: ' + ws.channel);

    ws.centrifuge.subscribe(ws.channel, function (message) {
        if (message.data['CommandInvoker']) {

            // Обарабываем только операция по другим сессиям
            if (message.data['CommandInvoker']['session']['id'] == Yiij.app.getModule('editor').session.id) {
                return;
            }

            Yiij.trace(message.data['CommandInvoker']);

            var session = new EditorSession(message.data['CommandInvoker']['session']);
            var command = CommandFactory.getInstance(message.data['CommandInvoker']['data']);

            switch (message.data['CommandInvoker']['action']) {
                case 'create':

                    CommandInvoker.create(session, command, false);

                    break;
                case 'redo':

                    if (!CommandInvoker.canRedo(session)) {
                        Yiij.trace('Стек отмены в локаьной сессии ' + session.id + ' пуст. Добавляем команду ' + command.id + ' в локальную копию этой сессии.');
                        session.redo_stack.push(command)
                    }

                    CommandInvoker.redo(session, command.id, false);

                    break;
                case 'undo':

                    if (!CommandInvoker.canUndo(session)) {
                        Yiij.trace('Стек повторного выполнения в локаьной сессии ' + session.id + ' пуст. Добавляем команду ' + command.id + ' в локальную копию этой сессии.');
                        session.undo_stack.push(command)
                    }

                    CommandInvoker.undo(session, command.id, false);

                    break;
                default:
                    throw new Error('Неизвестный параметр action: ' + message.data['CommandInvoker']['action']);
                    break;
            }
        }
    });

    ws.centrifuge.connect();
};