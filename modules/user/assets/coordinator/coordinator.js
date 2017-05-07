/**
 * Модуль координатор работы команды
 *
 * @param id
 * @param parent
 * @param config
 * @constructor
 */
var Coordinator = function (id, parent, config) {
    YiijBaseModule.apply(this, [id, parent, config]);
};
/**
 * Extends
 * @type {YiijBaseObject}
 */
Coordinator.prototype = Object.create(YiijBaseModule.prototype);
Coordinator.prototype.constructor = Coordinator;

/**
 * @type {WsConnection}
 */
Coordinator.prototype.connection;

/**
 *
 * @returns {boolean}
 */
Coordinator.prototype.isConnected = function () {
    return !!this.connection.opened;
};

/**
 * Инициализация координатора
 */
Coordinator.prototype.start = function () {
    this.connection = new WsConnection(this.connection);

    if(Yiij.app.modules['employee-tracker']){
        Yiij.app.getModule('employee-tracker').start();
    }
};

/**
 * Команда на запись звонка
 */
Coordinator.prototype.requestUsersList = function () {
    this.connection.emit('get_users_list');
};

/**
 * Устанавливает текущий статус пользователя
 */
Coordinator.prototype.setStatus = function (status) {
    this.connection.emit('set_status', {'status' : status});
};

/**
 * Команда на запись звонка
 */
Coordinator.prototype.startRecording = function (filename) {
    this.connection.emit('command', {
        'command': 'start',
        'filename': filename
    });
};

/**
 * Команда на прекращение записи звонка
 */
Coordinator.prototype.stopRecording = function () {
    this.connection.emit('command', {
        'command': 'stop_recording'
    });
};